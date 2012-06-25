<?php
/**
 * Run pending jobs.
 *
 * Options:
 *  --maxjobs <num> (default 10000)
 *  --type <job_cmd>
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

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

/**
 * Maintenance script that runs pending jobs.
 *
 * @ingroup Maintenance
 */
class RunJobs extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Run pending jobs";
		$this->addOption( 'maxjobs', 'Maximum number of jobs to run', false, true );
		$this->addOption( 'maxtime', 'Maximum amount of wall-clock time', false, true );
		$this->addOption( 'type', 'Type of job to run', false, true );
		$this->addOption( 'procs', 'Number of processes to use', false, true );
	}

	public function memoryLimit() {
		if ( $this->hasOption( 'memory-limit' ) ) {
			return parent::memoryLimit();
		}
		// Don't eat all memory on the machine if we get a bad job.
		return "150M";
	}

	public function execute() {
		global $wgTitle;

		if ( $this->hasOption( 'procs' ) ) {
			$procs = intval( $this->getOption( 'procs' ) );
			if ( $procs < 1 || $procs > 1000 ) {
				$this->error( "Invalid argument to --procs", true );
			}
			$fc = new ForkController( $procs );
			if ( $fc->start() != 'child' ) {
				exit( 0 );
			}
		}
		$maxJobs = $this->getOption( 'maxjobs', false );
		$maxTime = $this->getOption( 'maxtime', false );
		$startTime = time();
		$type = $this->getOption( 'type', false );
		$wgTitle = Title::newFromText( 'RunJobs.php' );
		$dbw = wfGetDB( DB_MASTER );
		$n = 0;

		$jqGroup = JobQueueGroup::singleton();
		if ( $type === false ) {
			$types = $jqGroup->getDefaultQueueTypes();
			shuffle( $types ); // avoid starvation
		} else {
			$types = array( $type );
		}

		do {
			$jobFound = false; // found a job in any queue?
			foreach ( $types as $type ) {
				// Pop a job off of the queue...
				$queue = $jqGroup->get( $type );
				$job = $queue->pop();
				if ( !$job ) {
					continue;
				}
				$jobFound = true;
				// Perform the job (logging success/failure and runtime)...
				$t = microtime( true );
				$this->runJobsLog( $job->toString() . " STARTING" );
				$status = $job->run();
				$queue->ack( $job ); // done
				$t = microtime( true ) - $t;
				$timeMs = intval( $t * 1000 );
				if ( !$status ) {
					$this->runJobsLog( $job->toString() . " t=$timeMs error={$job->error}" );
				} else {
					$this->runJobsLog( $job->toString() . " t=$timeMs good" );
				}
				// Break out if we hit the job count or wall time limits...
				if ( $maxJobs && ++$n > $maxJobs ) {
					break 2;
				}
				if ( $maxTime && time() - $startTime > $maxTime ) {
					break 2;
				}
				// Don't let any disk backup fall behind...
				$queue->waitForBackup();
			}
		} while ( $jobFound );
	}

	/**
	 * Log the job message
	 * @param $msg String The message to log
	 */
	private function runJobsLog( $msg ) {
		$this->output( wfTimestamp( TS_DB ) . " $msg\n" );
		wfDebugLog( 'runJobs', $msg );
	}
}

$maintClass = "RunJobs";
require_once( RUN_MAINTENANCE_IF_MAIN );
