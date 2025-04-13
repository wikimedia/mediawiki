<?php
/**
 * Clean up broken page links when somebody turns on $wgCapitalLinks.
 *
 * Usage: php cleanupCaps.php [--dry-run]
 * Options:
 *   --dry-run  don't actually try moving them
 *
 * Copyright © 2005 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
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
 * @author Brooke Vibber <bvibber@wikimedia.org>
 * @ingroup Maintenance
 */

use MediaWiki\Title\Title;
use MediaWiki\User\User;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/TableCleanup.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to clean up broken page links when somebody turns
 * on or off $wgCapitalLinks.
 *
 * @ingroup Maintenance
 */
class CleanupCaps extends TableCleanup {

	/** @var User */
	private $user;
	/** @var int */
	private $namespace;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Script to cleanup capitalization' );
		$this->addOption( 'namespace', 'Namespace number to run caps cleanup on', false, true );
	}

	public function execute() {
		$this->user = User::newSystemUser( 'Conversion script', [ 'steal' => true ] );

		$this->namespace = intval( $this->getOption( 'namespace', 0 ) );

		if (
			$this->getServiceContainer()->getNamespaceInfo()->
				isCapitalized( $this->namespace )
		) {
			$this->output( "Will be moving pages to first letter capitalized titles\n" );
			$callback = 'processRowToUppercase';
		} else {
			$this->output( "Will be moving pages to first letter lowercase titles\n" );
			$callback = 'processRowToLowercase';
		}

		$this->dryrun = $this->hasOption( 'dry-run' );

		$this->runTable( [
			'table' => 'page',
			'conds' => [ 'page_namespace' => $this->namespace ],
			'index' => 'page_id',
			'callback' => $callback ] );
	}

	protected function processRowToUppercase( \stdClass $row ) {
		$current = Title::makeTitle( $row->page_namespace, $row->page_title );
		// Set the ID of the page because Title::exists will return false
		// unless the Article ID is already known, because Title::canExist will be false
		// when $wgCapitalLinks is true and the old title is in lower case.
		$current->mArticleID = $row->page_id;
		$display = $current->getPrefixedText();
		$lower = $row->page_title;
		$upper = $this->getServiceContainer()->getContentLanguage()->ucfirst( $row->page_title );
		if ( $upper == $lower ) {
			$this->output( "\"$display\" already uppercase.\n" );

			$this->progress( 0 );
			return;
		}

		$target = Title::makeTitle( $row->page_namespace, $upper );
		if ( $target->exists() ) {
			// Prefix "CapsCleanup" to bypass the conflict
			$target = Title::newFromText( 'CapsCleanup/' . $display );
		}
		$ok = $this->movePage(
			$current,
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable target is always valid
			$target,
			'Converting page title to first-letter uppercase',
			false
		);
		if ( !$ok ) {
			$this->progress( 0 );
			return;
		}

		$this->progress( 1 );
		if ( $row->page_namespace == $this->namespace ) {
			// We need to fetch the existence of the talk page from the DB directly because
			// Title::exists will return false if Title::canExist returns false. Title::canExist will
			// return false if $wgCapitalLinks is true and the old title is in lowercase form.
			$namespaceInfo = $this->getServiceContainer()->getNamespaceInfo();
			if ( $namespaceInfo->canHaveTalkPage( $current ) ) {
				$talkNamespace = $namespaceInfo->getTalk( $row->page_namespace );
				$talkPageExists = $this->getReplicaDB()->newSelectQueryBuilder()
					->select( '1' )
					->from( 'page' )
					->where( [ 'page_title' => $row->page_title, 'page_namespace' => $talkNamespace ] )
					->caller( __METHOD__ )
					->fetchField();
				if ( $talkPageExists ) {
					$row->page_namespace = $talkNamespace;
					$this->processRowToUppercase( $row );
				}
			}
		}
	}

	protected function processRowToLowercase( \stdClass $row ) {
		$current = Title::makeTitle( $row->page_namespace, $row->page_title );
		$display = $current->getPrefixedText();
		$upper = $row->page_title;
		$lower = $this->getServiceContainer()->getContentLanguage()->lcfirst( $row->page_title );
		if ( $upper == $lower ) {
			$this->output( "\"$display\" already lowercase.\n" );

			$this->progress( 0 );
			return;
		}

		$target = Title::makeTitle( $row->page_namespace, $lower );
		if ( $target->exists() ) {
			$targetDisplay = $target->getPrefixedText();
			$this->output( "\"$display\" skipped; \"$targetDisplay\" already exists\n" );

			$this->progress( 0 );
			return;
		}

		$ok = $this->movePage( $current, $target, 'Converting page titles to lowercase', true );
		if ( $ok !== true ) {
			$this->progress( 0 );
		}

		$this->progress( 1 );
		if ( $row->page_namespace == $this->namespace ) {
			$talk = $current->getTalkPage();
			if ( $talk->exists() ) {
				$row->page_namespace = $talk->getNamespace();
				$this->processRowToLowercase( $row );
			}
		}
	}

	/**
	 * @param Title $current
	 * @param Title $target
	 * @param string $reason
	 * @param bool $createRedirect
	 * @return bool Success
	 */
	private function movePage( Title $current, Title $target, $reason, $createRedirect ) {
		$display = $current->getPrefixedText();
		$targetDisplay = $target->getPrefixedText();

		if ( $this->dryrun ) {
			$this->output( "\"$display\" -> \"$targetDisplay\": DRY RUN, NOT MOVED\n" );
			$ok = 'OK';
		} else {
			$mp = $this->getServiceContainer()->getMovePageFactory()
				->newMovePage( $current, $target );
			$status = $mp->move( $this->user, $reason, $createRedirect );
			$ok = $status->isOK() ? 'OK' : 'FAILED';
			$this->output( "\"$display\" -> \"$targetDisplay\": $ok\n" );
			if ( !$status->isOK() ) {
				$this->error( $status );
			}
		}

		return $ok === 'OK';
	}
}

// @codeCoverageIgnoreStart
$maintClass = CleanupCaps::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
