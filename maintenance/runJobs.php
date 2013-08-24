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

require_once __DIR__ . '/Maintenance.php';

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

		if ( wfReadOnly() ) {
			$this->error( "Unable to run jobs; the wiki is in read-only mode.", 1 ); // die
		}

		if ( $this->hasOption( 'procs' ) ) {
			$procs = intval( $this->getOption( 'procs' ) );
			if ( $procs < 1 || $procs > 1000 ) {
				$this->error( "Invalid argument to --procs", true );
			} elseif ( $procs != 1 ) {
				$fc = new ForkController( $procs );
				if ( $fc->start() != 'child' ) {
					exit( 0 );
				}
			}
		}
		$maxJobs = $this->getOption( 'maxjobs', false );
		$maxTime = $this->getOption( 'maxtime', false );
		$startTime = time();
		$type = $this->getOption( 'type', false );
		$wgTitle = Title::newFromText( 'RunJobs.php' );
		$jobsRun = 0; // counter

		$group = JobQueueGroup::singleton();
		// Handle any required periodic queue maintenance
		$count = $group->executeReadyPeriodicTasks();
		if ( $count > 0 ) {
			$this->runJobsLog( "Executed $count periodic queue task(s)." );
		}

		$flags = JobQueueGroup::USE_CACHE | JobQueueGroup::USE_PRIORITY;
		$lastTime = time(); // time since last slave check
		do {
			$job = ( $type === false )
				? $group->pop( JobQueueGroup::TYPE_DEFAULT, $flags )
				: $group->pop( $type ); // job from a single queue
			if ( $job ) { // found a job
				++$jobsRun;
				$this->runJobsLog( $job->toString() . " STARTING" );

				// Set timer to stop the job if too much CPU time is used
				set_time_limit( $maxTime ?: 0 );
				// Run the job...
				wfProfileIn( __METHOD__ . '-' . get_class( $job ) );
				$t = microtime( true );
				try {
					$status = $job->run();
					$error = $job->getLastError();
				} catch ( MWException $e ) {
					$status = false;
					$error = get_class( $e ) . ': ' . $e->getMessage();
					$e->report(); // write error to STDERR and the log
				}
				$timeMs = intval( ( microtime( true ) - $t ) * 1000 );
				wfProfileOut( __METHOD__ . '-' . get_class( $job ) );
				// Disable the timer
				set_time_limit( 0 );

				// Mark the job as done on success or when the job cannot be retried
				if ( $status !== false || !$job->allowRetries() ) {
					$group->ack( $job ); // done
				}

				if ( $status === false ) {
					$this->runJobsLog( $job->toString() . " t=$timeMs error={$error}" );
				} else {
					$this->runJobsLog( $job->toString() . " t=$timeMs good" );
				}

				// Break out if we hit the job count or wall time limits...
				if ( $maxJobs && $jobsRun >= $maxJobs ) {
					break;
				} elseif ( $maxTime && ( time() - $startTime ) > $maxTime ) {
					break;
				}

				// Don't let any of the main DB slaves get backed up
				$timePassed = time() - $lastTime;
				if ( $timePassed >= 5 || $timePassed < 0 ) {
					wfWaitForSlaves();
					$lastTime = time();
				}
				// Don't let any queue slaves/backups fall behind
				if ( $jobsRun > 0 && ( $jobsRun % 100 ) == 0 ) {
					$group->waitForBackups();
				}

				// Bail if near-OOM instead of in a job
				$this->assertMemoryOK();
			}
		} while ( $job ); // stop when there are no jobs
	}

	/**
	 * Make sure that this script is not too close to the memory usage limit
	 * @throws MWException
	 */
	private function assertMemoryOK() {
		static $maxBytes = null;
		if ( $maxBytes === null ) {
			$m = array();
			if ( preg_match( '!^(\d+)(k|m|g|)$!i', ini_get( 'memory_limit' ), $m ) ) {
				list( , $num, $unit ) = $m;
				$conv = array( 'g' => 1024 * 1024 * 1024, 'm' => 1024 * 1024, 'k' => 1024, '' => 1 );
				$maxBytes = $num * $conv[strtolower( $unit )];
			} else {
				$maxBytes = 0;
			}
		}
		$usedBytes = memory_get_usage();
		if ( $maxBytes && $usedBytes >= 0.95 * $maxBytes ) {
			throw new MWException( "Detected excessive memory usage ($usedBytes/$maxBytes)." );
		}
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
require_once RUN_MAINTENANCE_IF_MAIN;
