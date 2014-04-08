<?php
/**
 * Run pending jobs.
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
		$this->addOption( 'nothrottle', 'Ignore job throttling configuration', false, false );
	}

	public function memoryLimit() {
		if ( $this->hasOption( 'memory-limit' ) ) {
			return parent::memoryLimit();
		}
		// Don't eat all memory on the machine if we get a bad job.
		return "150M";
	}

	public function execute() {
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

		$type = $this->getOption( 'type', false );
		$maxJobs = $this->getOption( 'maxjobs', false );
		$maxTime = $this->getOption( 'maxtime', false );
		$noThrottle = $this->hasOption( 'nothrottle' );
		$startTime = time();

		$group = JobQueueGroup::singleton();
		// Handle any required periodic queue maintenance
		$count = $group->executeReadyPeriodicTasks();
		if ( $count > 0 ) {
			$this->runJobsLog( "Executed $count periodic queue task(s)." );
		}

		$backoffs = $this->loadBackoffs(); // map of (type => UNIX expiry)
		$startingBackoffs = $backoffs; // avoid unnecessary writes
		$backoffExpireFunc = function( $t ) { return $t > time(); };

		$jobsRun = 0; // counter
		$flags = JobQueueGroup::USE_CACHE;
		$lastTime = time(); // time since last slave check
		do {
			$backoffs = array_filter( $backoffs, $backoffExpireFunc );
			$blacklist = $noThrottle ? array() : array_keys( $backoffs );
			if ( $type === false ) {
				$job = $group->pop( JobQueueGroup::TYPE_DEFAULT, $flags, $blacklist );
			} elseif ( in_array( $type, $blacklist ) ) {
				$job = false; // requested queue in backoff state
			} else {
				$job = $group->pop( $type ); // job from a single queue
			}
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
					MWExceptionHandler::rollbackMasterChangesAndLog( $e );
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

				// Back off of certain jobs for a while
				$ttw = $this->getBackoffTimeToWait( $job );
				if ( $ttw > 0 ) {
					$jType = $job->getType();
					$backoffs[$jType] = isset( $backoffs[$jType] ) ? $backoffs[$jType] : 0;
					$backoffs[$jType] = max( $backoffs[$jType], time() + $ttw );
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
		// Sync the persistent backoffs for the next runJobs.php pass
		$backoffs = array_filter( $backoffs, $backoffExpireFunc );
		if ( $backoffs !== $startingBackoffs ) {
			$this->syncBackoffs( $backoffs );
		}
	}

	/**
	 * @param Job $job
	 * @return integer Seconds for this runner to avoid doing more jobs of this type
	 * @see $wgJobBackoffThrottling
	 */
	private function getBackoffTimeToWait( Job $job ) {
		global $wgJobBackoffThrottling;

		if ( !isset( $wgJobBackoffThrottling[$job->getType()] ) ) {
			return 0; // not throttled
		}
		$itemsPerSecond = $wgJobBackoffThrottling[$job->getType()];
		if ( $itemsPerSecond <= 0 ) {
			return 0; // not throttled
		}

		$seconds = 0;
		if ( $job->workItemCount() > 0 ) {
			$seconds = floor( $job->workItemCount() / $itemsPerSecond );
			$remainder = $job->workItemCount() % $itemsPerSecond;
			$seconds += ( mt_rand( 1, $itemsPerSecond ) <= $remainder ) ? 1 : 0;
		}

		return (int)$seconds;
	}

	/**
	 * Get the previous backoff expiries from persistent storage
	 *
	 * @return array Map of (job type => backoff expiry timestamp)
	 */
	private function loadBackoffs() {
		$section = new ProfileSection( __METHOD__ );

		$backoffs = array();
		$file = wfTempDir() . '/mw-runJobs-backoffs.json';
		if ( is_file( $file ) ) {
			$handle = fopen( $file, 'rb' );
			flock( $handle, LOCK_SH );
			$content = stream_get_contents( $handle );
			flock( $handle, LOCK_UN );
			fclose( $handle );
			$backoffs = json_decode( $content, true ) ?: array();
		}

		return $backoffs;
	}

	/**
	 * Merge the current backoff expiries from persistent storage
	 *
	 * @param array $backoffs Map of (job type => backoff expiry timestamp)
	 */
	private function syncBackoffs( array $backoffs ) {
		$section = new ProfileSection( __METHOD__ );

		$file = wfTempDir() . '/mw-runJobs-backoffs.json';
		$handle = fopen( $file, 'wb+' );
		flock( $handle, LOCK_EX );
		$content = stream_get_contents( $handle );
		$cBackoffs = json_decode( $content, true ) ?: array();
		foreach ( $backoffs as $type => $timestamp ) {
			$cBackoffs[$type] = isset( $cBackoffs[$type] ) ? $cBackoffs[$type] : 0;
			$cBackoffs[$type] = max( $cBackoffs[$type], $backoffs[$type] );
		}
		ftruncate( $handle, 0 );
		fwrite( $handle, json_encode( $backoffs ) );
		flock( $handle, LOCK_UN );
		fclose( $handle );
	}

	/**
	 * Make sure that this script is not too close to the memory usage limit.
	 * It is better to die in between jobs than OOM right in the middle of one.
	 * @throws MWException
	 */
	private function assertMemoryOK() {
		static $maxBytes = null;
		if ( $maxBytes === null ) {
			$m = array();
			if ( preg_match( '!^(\d+)(k|m|g|)$!i', ini_get( 'memory_limit' ), $m ) ) {
				list( , $num, $unit ) = $m;
				$conv = array( 'g' => 1073741824, 'm' => 1048576, 'k' => 1024, '' => 1 );
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
