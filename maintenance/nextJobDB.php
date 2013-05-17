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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that picks a database that has pending jobs.
 *
 * @ingroup Maintenance
 */
class nextJobDB extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Pick a database that has pending jobs";
		$this->addOption( 'type', "Search by job type", false, true );
		$this->addOption( 'types', "Space separated list of job types to search for", false, true );
	}

	public function execute() {
		global $wgJobTypesExcludedFromDefaultQueue;

		// job type required/picked
		if ( $this->hasOption( 'types' ) ) {
			$types = explode( ' ', $this->getOption( 'types' ) );
		} elseif ( $this->hasOption( 'type' ) ) {
			$types = array( $this->getOption( 'type' ) );
		} else {
			$types = false;
		}

		// Handle any required periodic queue maintenance
		$this->executeReadyPeriodicTasks();

		// Get all the queues with jobs in them
		$pendingDBs = JobQueueAggregator::singleton()->getAllReadyWikiQueues();
		if ( !count( $pendingDBs ) ) {
			return; // no DBs with jobs or cache is both empty and locked
		}

		do {
			$again = false;

			$candidates = array(); // list of (type, db)
			// Flatten the tree of candidates into a flat list so that a random
			// item can be selected, weighing each queue (type/db tuple) equally.
			foreach ( $pendingDBs as $type => $dbs ) {
				if (
					( is_array( $types ) && in_array( $type, $types ) ) ||
					( $types === false && !in_array( $type, $wgJobTypesExcludedFromDefaultQueue ) )
				) {
					foreach ( $dbs as $db ) {
						$candidates[] = array( $type, $db );
					}
				}
			}
			if ( !count( $candidates ) ) {
				return; // no jobs for this type
			}

			list( $type, $db ) = $candidates[mt_rand( 0, count( $candidates ) - 1 )];
			if ( JobQueueGroup::singleton( $db )->isQueueDeprioritized( $type ) ) {
				$pendingDBs[$type] = array_diff( $pendingDBs[$type], array( $db ) );
				$again = true;
			}
		} while ( $again );

		if ( $this->hasOption( 'types' ) ) {
			$this->output( $db . " " . $type . "\n" );
		} else {
			$this->output( $db . "\n" );
		}
	}

	/**
	 * Do all ready periodic jobs for all databases every 5 minutes (and .1% of the time)
	 * @return integer
	 */
	private function executeReadyPeriodicTasks() {
		global $wgLocalDatabases, $wgMemc;

		$count = 0;
		$memcKey = 'jobqueue:periodic:lasttime';
		$timestamp = (int)$wgMemc->get( $memcKey ); // UNIX timestamp or 0
		if ( ( time() - $timestamp ) > 300 || mt_rand( 0, 999 ) == 0 ) { // 5 minutes
			if ( $wgMemc->add( "$memcKey:rebuild", 1, 1800 ) ) { // lock
				foreach ( $wgLocalDatabases as $db ) {
					$count += JobQueueGroup::singleton( $db )->executeReadyPeriodicTasks();
				}
				$wgMemc->set( $memcKey, time() );
				$wgMemc->delete( "$memcKey:rebuild" ); // unlock
			}
		}

		return $count;
	}
}

$maintClass = "nextJobDb";
require_once RUN_MAINTENANCE_IF_MAIN;
