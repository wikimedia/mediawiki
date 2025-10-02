<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\RecentChanges\RecentChangesUpdateJob;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Purge rows from the recentchanges table older than wgRCMaxAge.
 *
 * Useful to run on wikis which are infrequently edited and therefore RecentChangesUpdateJob
 * may not be run frequently enough.
 *
 * @ingroup RecentChanges
 * @ingroup Maintenance
 * @since 1.43
 */
class PurgeRecentChanges extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Purge rows from the recentchanges table which are older than wgRCMaxAge.' );
	}

	public function execute() {
		// Run directly instead of pushing to the job queue, so that the script will exit only once the
		// purge is complete. If a job is already running to do the purge, then the script will exit early.
		// However, this is fine because a purge is already in progress.
		$recentChangesPruneJob = RecentChangesUpdateJob::newPurgeJob();
		$recentChangesPruneJob->run();
		$this->output( "Finished purging data from recentchanges.\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = PurgeRecentChanges::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
