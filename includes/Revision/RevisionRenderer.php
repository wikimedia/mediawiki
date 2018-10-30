<?php
/**
 * This file is part of MediaWiki.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Revision;

use Html;
use InvalidArgumentException;
use ParserOptions;
use ParserOutput;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Title;
use User;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * The RevisionRenderer service provides access to rendered output for revisions.
 * It does so be acting as a factory for RenderedRevision instances, which in turn
 * provide lazy access to ParserOutput objects.
 *
 * One key responsibility of RevisionRenderer is implementing the layout used to combine
 * the output of multiple slots.
 *
 * @since 1.32
 */
class RevisionRenderer {

	/** @var LoggerInterface */
	private $saveParseLogger;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var string|bool */
	private $wikiId;

	/**
	 * @param ILoadBalancer $loadBalancer
	 * @param bool|string $wikiId
	 */
	public function __construct( ILoadBalancer $loadBalancer, $wikiId = false ) {
		$this->loadBalancer = $loadBalancer;
		$this->wikiId = $wikiId;

		$this->saveParseLogger = new NullLogger();
	}

	/**
	 * @param LoggerInterface $saveParseLogger
	 */
	public function setLogger( LoggerInterface $saveParseLogger ) {
		$this->saveParseLogger = $saveParseLogger;
	}

	/**
	 * @param RevisionRecord $rev
	 * @param ParserOptions|null $options
	 * @param User|null $forUser User for privileged access. Default is unprivileged (public)
	 *        access, unless the 'audience' hint is set to something else RevisionRecord::RAW.
	 * @param array $hints Hints given as an associative array. Known keys:
	 *      - 'use-master' Use master when rendering for the parser cache during save.
	 *        Default is to use a replica.
	 *      - 'audience' the audience to use for content access. Default is
	 *        RevisionRecord::FOR_PUBLIC if $forUser is not set, RevisionRecord::FOR_THIS_USER
	 *        if $forUser is set. Can be set to RevisionRecord::RAW to disable audience checks.
	 *
	 * @return RenderedRevision|null The rendered revision, or null if the audience checks fails.
	 */
	public function getRenderedRevision(
		RevisionRecord $rev,
		ParserOptions $options = null,
		User $forUser = null,
		array $hints = []
	) {
		if ( $rev->getWikiId() !== $this->wikiId ) {
			throw new InvalidArgumentException( 'Mismatching wiki ID ' . $rev->getWikiId() );
		}

		$audience = $hints['audience']
			?? ( $forUser ? RevisionRecord::FOR_THIS_USER : RevisionRecord::FOR_PUBLIC );

		if ( !$rev->audienceCan( RevisionRecord::DELETED_TEXT, $audience, $forUser ) ) {
			// Returning null here is awkward, but consist with the signature of
			// Revision::getContent() and RevisionRecord::getContent().
			return null;
		}

		if ( !$options ) {
			$options = ParserOptions::newCanonical( $forUser ?: 'canonical' );
		}

		$useMaster = $hints['use-master'] ?? false;

		$dbIndex = $useMaster
			? DB_MASTER // use latest values
			: DB_REPLICA; // T154554

		$options->setSpeculativeRevIdCallback( function () use ( $dbIndex ) {
			return $this->getSpeculativeRevId( $dbIndex );
		} );

		$title = Title::newFromLinkTarget( $rev->getPageAsLinkTarget() );

		$renderedRevision = new RenderedRevision(
			$title,
			$rev,
			$options,
			function ( RenderedRevision $rrev, array $hints ) {
				return $this->combineSlotOutput( $rrev, $hints );
			},
			$audience,
			$forUser
		);

		$renderedRevision->setSaveParseLogger( $this->saveParseLogger );

		return $renderedRevision;
	}

	private function getSpeculativeRevId( $dbIndex ) {
		// Use a fresh master connection in order to see the latest data, by avoiding
		// stale data from REPEATABLE-READ snapshots.
		// HACK: But don't use a fresh connection in unit tests, since it would not have
		// the fake tables. This should be handled by the LoadBalancer!
		$flags = defined( 'MW_PHPUNIT_TEST' ) || $dbIndex === DB_REPLICA
			? 0 : ILoadBalancer::CONN_TRX_AUTOCOMMIT;

		$db = $this->loadBalancer->getConnectionRef( $dbIndex, [], $this->wikiId, $flags );

		return 1 + (int)$db->selectField(
			'revision',
			'MAX(rev_id)',
			[],
			__METHOD__
		);
	}

	/**
	 * This implements the layout for combining the output of multiple slots.
	 *
	 * @todo Use placement hints from SlotRoleHandlers instead of hard-coding the layout.
	 *
	 * @param RenderedRevision $rrev
	 * @param array $hints see RenderedRevision::getRevisionParserOutput()
	 *
	 * @return ParserOutput
	 */
	private function combineSlotOutput( RenderedRevision $rrev, array $hints = [] ) {
		$revision = $rrev->getRevision();
		$slots = $revision->getSlots()->getSlots();

		$withHtml = $hints['generate-html'] ?? true;

		// short circuit if there is only the main slot
		if ( array_keys( $slots ) === [ SlotRecord::MAIN ] ) {
			return $rrev->getSlotParserOutput( SlotRecord::MAIN );
		}

		// TODO: put fancy layout logic here, see T200915.

		// move main slot to front
		if ( isset( $slots[SlotRecord::MAIN] ) ) {
			$slots = [ SlotRecord::MAIN => $slots[SlotRecord::MAIN] ] + $slots;
		}

		$combinedOutput = new ParserOutput( null );
		$slotOutput = [];

		$options = $rrev->getOptions();
		$options->registerWatcher( [ $combinedOutput, 'recordOption' ] );

		foreach ( $slots as $role => $slot ) {
			$out = $rrev->getSlotParserOutput( $role, $hints );
			$slotOutput[$role] = $out;

			$combinedOutput->mergeInternalMetaDataFrom( $out, $role );
			$combinedOutput->mergeTrackingMetaDataFrom( $out );
		}

		if ( $withHtml ) {
			$html = '';
			$first = true;
			/** @var ParserOutput $out */
			foreach ( $slotOutput as $role => $out ) {
				if ( $first ) {
					// skip header for the first slot
					$first = false;
				} else {
					// NOTE: this placeholder is hydrated by ParserOutput::getText().
					$headText = Html::element( 'mw:slotheader', [], $role );
					$html .= Html::rawElement( 'h1', [ 'class' => 'mw-slot-header' ], $headText );
				}

				$html .= $out->getRawText();
				$combinedOutput->mergeHtmlMetaDataFrom( $out );
			}

			$combinedOutput->setText( $html );
		}

		$options->registerWatcher( null );
		return $combinedOutput;
	}

}
