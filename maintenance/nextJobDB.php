<?php
/**
 * Pick a database that has pending jobs
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
 * @todo Make this work on PostgreSQL and maybe other database servers
 * @ingroup Maintenance
 */

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Maintenance script that picks a database that has pending jobs.
 *
 * @ingroup Maintenance
 */
class nextJobDB extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Pick a database that has pending jobs";
		$this->addOption( 'type', "The type of job to search for", false, true );
	}

	public function execute() {
		global $wgMemc;

		$type = $this->getOption( 'type', false );

		$memcKey = 'jobqueue:dbs:v3';
		$pendingDbInfo = $wgMemc->get( $memcKey );

		// If the cache entry wasn't present, or in 1% of cases otherwise,
		// regenerate the cache. Use any available stale cache if another
		// process is currently regenerating the pending DB information.
		if ( !$pendingDbInfo || mt_rand( 0, 100 ) == 0 ) {
			$lock = $wgMemc->add( 'jobqueue:dbs:v3:lock', 1 ); // lock
			if ( $lock ) {
				$pendingDbInfo = array(
					'pendingDBs' => $this->getPendingDbs(),
					'timestamp'  => time()
				);
				$wgMemc->set( $memcKey, $pendingDbInfo );
				$wgMemc->delete( 'jobqueue:dbs:v3:lock' ); // unlock
			}
		}

		if ( !$pendingDbInfo || !$pendingDbInfo['pendingDBs'] ) {
			return; // no DBs with jobs or cache is both empty and locked
		}
		$pendingDBs = $pendingDbInfo['pendingDBs'];

		do {
			$again = false;

			if ( $type === false ) {
				$candidates = call_user_func_array( 'array_merge', $pendingDBs );
			} elseif ( isset( $pendingDBs[$type] ) ) {
				$candidates = $pendingDBs[$type];
			} else {
				$candidates = array();
			}
			if ( !$candidates ) {
				return;
			}

			$candidates = array_values( $candidates );
			$db = $candidates[ mt_rand( 0, count( $candidates ) - 1 ) ];
			if ( !$this->checkJob( $type, $db ) ) {
				// This job is not available in the current database. Remove it from
				// the cache.
				if ( $type === false ) {
					foreach ( $pendingDBs as $type2 => $dbs ) {
						$pendingDBs[$type2] = array_diff( $pendingDBs[$type2], array( $db ) );
					}
				} else {
					$pendingDBs[$type] = array_diff( $pendingDBs[$type], array( $db ) );
				}

				$wgMemc->set( $memcKey, $pendingDBs, 300 );
				$again = true;
			}
		} while ( $again );

		$this->output( $db . "\n" );
	}

	/**
	 * Check if the specified database has a job of the specified type in it.
	 * The type may be false to indicate "all".
	 * @param $type string
	 * @param $dbName string
	 * @return bool
	 */
	function checkJob( $type, $dbName ) {
		$group = JobQueueGroup::singleton( $dbName );
		if ( $type === false ) {
			foreach ( $group->getDefaultQueueTypes() as $type ) {
				if ( !$group->get( $type )->isEmpty() ) {
					return true;
				}
			}
			return false;
		} else {
			return !$group->get( $type )->isEmpty();
		}
	}

	/**
	 * Get all databases that have a pending job
	 * @return array
	 */
	private function getPendingDbs() {
		global $wgLocalDatabases;

		$pendingDBs = array(); // (job type => (db list))
		foreach ( $wgLocalDatabases as $db ) {
			$types = JobQueueGroup::singleton( $db )->getQueuesWithJobs();
			foreach ( $types as $type ) {
				$pendingDBs[$type][] = $db;
			}
		}

		return $pendingDBs;
	}
}

$maintClass = "nextJobDb";
require_once( RUN_MAINTENANCE_IF_MAIN );
