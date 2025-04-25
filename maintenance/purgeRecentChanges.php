<?php
/**
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
