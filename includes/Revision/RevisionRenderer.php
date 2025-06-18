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

use InvalidArgumentException;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Html\Html;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Permissions\Authority;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * The RevisionRenderer service provides access to rendered output for revisions.
 * It does so by acting as a factory for RenderedRevision instances, which in turn
 * provide lazy access to ParserOutput objects.
 *
 * One key responsibility of RevisionRenderer is implementing the layout used to combine
 * the output of multiple slots.
 *
 * @since 1.32
 */
class RevisionRenderer implements LoggerAwareInterface {

	/** @var LoggerInterface */
	private $saveParseLogger;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var SlotRoleRegistry */
	private $roleRegistry;

	/** @var ContentRenderer */
	private $contentRenderer;

	/** @var string|false */
	private $dbDomain;

	/**
	 * @param ILoadBalancer $loadBalancer
	 * @param SlotRoleRegistry $roleRegistry
	 * @param ContentRenderer $contentRenderer
	 * @param string|false $dbDomain DB domain of the relevant wiki or false for the current one
	 */
	public function __construct(
		ILoadBalancer $loadBalancer,
		SlotRoleRegistry $roleRegistry,
		ContentRenderer $contentRenderer,
		$dbDomain = false
	) {
		$this->loadBalancer = $loadBalancer;
		$this->roleRegistry = $roleRegistry;
		$this->contentRenderer = $contentRenderer;
		$this->dbDomain = $dbDomain;
		$this->saveParseLogger = new NullLogger();
	}

	/** @inheritDoc */
	public function setLogger( LoggerInterface $saveParseLogger ): void {
		$this->saveParseLogger = $saveParseLogger;
	}

	// phpcs:disable Generic.Files.LineLength.TooLong
	/**
	 * @param RevisionRecord $rev
	 * @param ParserOptions|null $options
	 * @param Authority|null $forPerformer User for privileged access. Default is unprivileged
	 *        (public) access, unless the 'audience' hint is set to something else RevisionRecord::RAW.
	 * @param array{use-master?:bool,audience?:int,known-revision-output?:ParserOutput,causeAction?:?string,previous-output?:?ParserOutput} $hints
	 *   Hints given as an associative array. Known keys:
	 *      - 'use-master' Use primary DB when rendering for the parser cache during save.
	 *        Default is to use a replica.
	 *      - 'audience' the audience to use for content access. Default is
	 *        RevisionRecord::FOR_PUBLIC if $forUser is not set, RevisionRecord::FOR_THIS_USER
	 *        if $forUser is set. Can be set to RevisionRecord::RAW to disable audience checks.
	 *      - 'known-revision-output' a combined ParserOutput for the revision, perhaps from
	 *        some cache. the caller is responsible for ensuring that the ParserOutput indeed
	 *        matched the $rev and $options. This mechanism is intended as a temporary stop-gap,
	 *        for the time until caches have been changed to store RenderedRevision states instead
	 *        of ParserOutput objects.
	 *      - 'previous-output' A previously-rendered ParserOutput for this page. This
	 *        can be used by Parsoid for selective updates.
	 *      - 'causeAction' the reason for rendering. This should be informative, for used for
	 *        logging and debugging.
	 *
	 * @return RenderedRevision|null The rendered revision, or null if the audience checks fails.
	 * @throws BadRevisionException
	 * @throws RevisionAccessException
	 */
	// phpcs:enable Generic.Files.LineLength.TooLong
	public function getRenderedRevision(
		RevisionRecord $rev,
		?ParserOptions $options = null,
		?Authority $forPerformer = null,
		array $hints = []
	) {
		if ( $rev->getWikiId() !== $this->dbDomain ) {
			throw new InvalidArgumentException( 'Mismatching wiki ID ' . $rev->getWikiId() );
		}

		$audience = $hints['audience']
			?? ( $forPerformer ? RevisionRecord::FOR_THIS_USER : RevisionRecord::FOR_PUBLIC );

		if ( !$rev->audienceCan( RevisionRecord::DELETED_TEXT, $audience, $forPerformer ) ) {
			// Returning null here is awkward, but consistent with the signature of
			// RevisionRecord::getContent().
			return null;
		}

		if ( !$options ) {
			$options = $forPerformer ?
				ParserOptions::newFromUser( $forPerformer->getUser() ) :
				ParserOptions::newFromAnon();
		}

		if ( isset( $hints['causeAction'] ) ) {
			$options->setRenderReason( $hints['causeAction'] );
		}

		$usePrimary = $hints['use-master'] ?? false;

		$dbIndex = $usePrimary
			? DB_PRIMARY // use latest values
			: DB_REPLICA; // T154554

		$options->setSpeculativeRevIdCallback( function () use ( $dbIndex ) {
			return $this->getSpeculativeRevId( $dbIndex );
		} );
		$options->setSpeculativePageIdCallback( function () use ( $dbIndex ) {
			return $this->getSpeculativePageId( $dbIndex );
		} );

		if ( !$rev->getId() && $rev->getTimestamp() ) {
			// This is an unsaved revision with an already determined timestamp.
			// Make the "current" time used during parsing match that of the revision.
			// Any REVISION* parser variables will match up if the revision is saved.
			$options->setTimestamp( $rev->getTimestamp() );
		}

		$previousOutput = $hints['previous-output'] ?? null;
		$renderedRevision = new RenderedRevision(
			$rev,
			$options,
			$this->contentRenderer,
			function ( RenderedRevision $rrev, array $hints ) use ( $options, $previousOutput ) {
				$h = [ 'previous-output' => $previousOutput ] + $hints;
				return $this->combineSlotOutput( $rrev, $options, $h );
			},
			$audience,
			$forPerformer
		);

		$renderedRevision->setSaveParseLogger( $this->saveParseLogger );

		if ( isset( $hints['known-revision-output'] ) ) {
			$renderedRevision->setRevisionParserOutput( $hints['known-revision-output'] );
		}

		return $renderedRevision;
	}

	private function getSpeculativeRevId( int $dbIndex ): int {
		// Use a separate primary DB connection in order to see the latest data, by avoiding
		// stale data from REPEATABLE-READ snapshots.
		$flags = ILoadBalancer::CONN_TRX_AUTOCOMMIT;

		$db = $this->loadBalancer->getConnection( $dbIndex, [], $this->dbDomain, $flags );

		return 1 + (int)$db->newSelectQueryBuilder()
			->select( 'MAX(rev_id)' )
			->from( 'revision' )
			->caller( __METHOD__ )->fetchField();
	}

	private function getSpeculativePageId( int $dbIndex ): int {
		// Use a separate primary DB connection in order to see the latest data, by avoiding
		// stale data from REPEATABLE-READ snapshots.
		$flags = ILoadBalancer::CONN_TRX_AUTOCOMMIT;

		$db = $this->loadBalancer->getConnection( $dbIndex, [], $this->dbDomain, $flags );

		return 1 + (int)$db->newSelectQueryBuilder()
			->select( 'MAX(page_id)' )
			->from( 'page' )
			->caller( __METHOD__ )->fetchField();
	}

	/**
	 * This implements the layout for combining the output of multiple slots.
	 *
	 * @todo Use placement hints from SlotRoleHandlers instead of hard-coding the layout.
	 *
	 * @param RenderedRevision $rrev
	 * @param ParserOptions $options
	 * @param array $hints see RenderedRevision::getRevisionParserOutput()
	 *
	 * @return ParserOutput
	 * @throws SuppressedDataException
	 * @throws BadRevisionException
	 * @throws RevisionAccessException
	 */
	private function combineSlotOutput( RenderedRevision $rrev, ParserOptions $options, array $hints = [] ) {
		$revision = $rrev->getRevision();
		$slots = $revision->getSlots()->getSlots();

		$withHtml = $hints['generate-html'] ?? true;
		$previousOutputs = $this->splitSlotOutput( $rrev, $options, $hints['previous-output'] ?? null );

		// short circuit if there is only the main slot
		// T351026 hack: if use-parsoid is set, only return main slot output for now
		// T351113 will remove this hack.
		if ( array_keys( $slots ) === [ SlotRecord::MAIN ] || $options->getUseParsoid() ) {
			$h = [ 'previous-output' => $previousOutputs[SlotRecord::MAIN] ] + $hints;
			return $rrev->getSlotParserOutput( SlotRecord::MAIN, $h );
		}

		// move main slot to front
		if ( isset( $slots[SlotRecord::MAIN] ) ) {
			$slots = [ SlotRecord::MAIN => $slots[SlotRecord::MAIN] ] + $slots;
		}

		$combinedOutput = new ParserOutput( null );
		$slotOutput = [];

		$options = $rrev->getOptions();
		$options->registerWatcher( [ $combinedOutput, 'recordOption' ] );

		foreach ( $slots as $role => $slot ) {
			$h = [ 'previous-output' => $previousOutputs[$role] ] + $hints;
			$out = $rrev->getSlotParserOutput( $role, $h );
			$slotOutput[$role] = $out;

			// XXX: should the SlotRoleHandler be able to intervene here?
			$combinedOutput->mergeInternalMetaDataFrom( $out );
			$combinedOutput->mergeTrackingMetaDataFrom( $out );
		}

		if ( $withHtml ) {
			$html = '';
			$first = true;
			/** @var ParserOutput $out */
			foreach ( $slotOutput as $role => $out ) {
				$roleHandler = $this->roleRegistry->getRoleHandler( $role );

				// TODO: put more fancy layout logic here, see T200915.
				$layout = $roleHandler->getOutputLayoutHints();
				$display = $layout['display'] ?? 'section';

				if ( $display === 'none' ) {
					continue;
				}

				if ( $first ) {
					// skip header for the first slot
					$first = false;
				} else {
					// NOTE: this placeholder is hydrated by ParserOutput::getText().
					$headText = Html::element( 'mw:slotheader', [], $role );
					$html .= Html::rawElement( 'h1', [ 'class' => 'mw-slot-header' ], $headText );
				}

				// XXX: do we want to put a wrapper div around the output?
				// Do we want to let $roleHandler do that?
				$html .= $out->getRawText();
				$combinedOutput->mergeHtmlMetaDataFrom( $out );
			}

			$combinedOutput->setRawText( $html );
		}

		$options->registerWatcher( null );
		return $combinedOutput;
	}

	/**
	 * This reverses ::combineSlotOutput() in order to enable selective
	 * update of individual slots.
	 *
	 * @todo Currently this doesn't do much other than disable selective
	 * update if there is more than one slot.  But in the case where
	 * slot combination is reversible, this should reverse it and attempt
	 * to reconstruct the original split ParserOutputs from the merged
	 * ParserOutput.
	 *
	 * @param RenderedRevision $rrev
	 * @param ParserOptions $options
	 * @param ?ParserOutput $previousOutput A combined ParserOutput for a
	 *   previous parse, or null if none available.
	 * @return array<string,?ParserOutput> A mapping from role name to a
	 *   previous ParserOutput for that slot in the previous parse
	 */
	private function splitSlotOutput( RenderedRevision $rrev, ParserOptions $options, ?ParserOutput $previousOutput ) {
		// If there is no previous parse, then there is nothing to split.
		$revision = $rrev->getRevision();
		$revslots = $revision->getSlots();
		if ( $previousOutput === null ) {
			return array_fill_keys( $revslots->getSlotRoles(), null );
		}

		// short circuit if there is only the main slot
		// T351026 hack: if use-parsoid is set, only return main slot output for now
		// T351113 will remove this hack.
		if ( $revslots->getSlotRoles() === [ SlotRecord::MAIN ] || $options->getUseParsoid() ) {
			return [ SlotRecord::MAIN => $previousOutput ];
		}

		// @todo Currently slot combination is not reversible
		return array_fill_keys( $revslots->getSlotRoles(), null );
	}
}
