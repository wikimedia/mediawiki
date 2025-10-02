<?php
/**
 * Remove broken, unparseable titles in the watchlist table.
 *
 * Usage: php cleanupWatchlist.php [--fix]
 * Options:
 *   --fix  Actually remove entries; without will only report.
 *
 * Copyright © 2005,2006 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 * @author Brooke Vibber <bvibber@wikimedia.org>
 * @ingroup Maintenance
 */

use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/TableCleanup.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to remove broken, unparseable titles in the watchlist table.
 *
 * @ingroup Maintenance
 */
class CleanupWatchlist extends TableCleanup {
	/** @inheritDoc */
	protected $defaultParams = [
		'table' => 'watchlist',
		'index' => [ 'wl_id' ],
		'conds' => [],
		'callback' => 'processRow'
	];

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Script to remove broken, unparseable titles in the Watchlist' );
		$this->addOption( 'fix', 'Actually remove entries; without will only report.' );
	}

	public function execute() {
		if ( !$this->hasOption( 'fix' ) ) {
			$this->output( "Dry run only: use --fix to enable updates\n" );
		}
		parent::execute();
	}

	protected function processRow( \stdClass $row ) {
		$current = Title::makeTitle( $row->wl_namespace, $row->wl_title );
		$display = $current->getPrefixedText();
		$verified = $this->getServiceContainer()->getContentLanguage()->normalize( $display );
		$title = Title::newFromText( $verified );

		if ( $row->wl_user == 0 || $title === null || !$title->equals( $current ) ) {
			$this->output( "invalid watch by {$row->wl_user} for "
				. "({$row->wl_namespace}, \"{$row->wl_title}\")\n" );
			$updated = $this->removeWatch( $row );
			$this->progress( $updated );

			return;
		}
		$this->progress( 0 );
	}

	private function removeWatch( \stdClass $row ): int {
		if ( !$this->dryrun && $this->hasOption( 'fix' ) ) {
			$dbw = $this->getPrimaryDB();
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'watchlist' )
				->where( [ 'wl_id' => $row->wl_id ] )
				->caller( __METHOD__ )
				->execute();
			if ( $this->getConfig()->get( MainConfigNames::WatchlistExpiry ) ) {
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'watchlist_expiry' )
					->where( [ 'we_item' => $row->wl_id ] )
					->caller( __METHOD__ )
					->execute();
			}

			$this->output( "- removed\n" );

			return 1;
		} else {
			return 0;
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = CleanupWatchlist::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
