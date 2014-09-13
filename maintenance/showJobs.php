<?php
/**
 * Report number of jobs currently waiting in master database.
 *
 * Based on runJobs.php
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
 * @author Tim Starling
 * @author Antoine Musso
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that reports the number of jobs currently waiting
 * in master database.
 *
 * @ingroup Maintenance
 */
class ShowJobs extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Show number of jobs waiting in master database";
		$this->addOption( 'group', 'Show number of jobs per job type' );
		$this->addOption( 'list', 'Show a complete list of all jobs in a machine-readable format, instead of statistics' );
	}

	public function execute() {
		$group = JobQueueGroup::singleton();
		if ( $this->hasOption( 'list' ) ) {
			foreach ( $group->getQueueTypes() as $type ) {
				$queue = $group->get( $type );
				foreach ( $queue->getAllQueuedJobs() as $job ) {
					$this->output( $job->toString() . " status=unclaimed\n" );
				}
				foreach ( $queue->getAllDelayedJobs() as $job ) {
					$this->output( $job->toString() . " status=delayed\n" );
				}
			}
		} elseif ( $this->hasOption( 'group' ) ) {
			foreach ( $group->getQueueTypes() as $type ) {
				$queue = $group->get( $type );
				$delayed = $queue->getDelayedCount();
				$pending = $queue->getSize();
				$claimed = $queue->getAcquiredCount();
				$abandoned = $queue->getAbandonedCount();
				$active = max( 0, $claimed - $abandoned );
				if ( ( $pending + $claimed + $delayed ) > 0 ) {
					$this->output(
						"{$type}: $pending queued; " .
						"$claimed claimed ($active active, $abandoned abandoned); " .
						"$delayed delayed\n"
					);
				}
			}
		} else {
			$count = 0;
			foreach ( $group->getQueueTypes() as $type ) {
				$count += $group->get( $type )->getSize();
			}
			$this->output( "$count\n" );
		}
	}
}

$maintClass = "ShowJobs";
require_once RUN_MAINTENANCE_IF_MAIN;
