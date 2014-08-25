<?php
/**
 * Job queue runner utility methods
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
 * @ingroup JobQueue
 */

/**
 * Job queue runner utility methods
 *
 * @ingroup JobQueue
 * @since 1.24
 */
class JobRunner {
	/** @var callable|null Debug output handler */
	protected $debug;

	/**
	 * @param callable $debug Optional debug output handler
	 */
	public function setDebugHandler( $debug ) {
		$this->debug = $debug;
	}

	/**
	 * Run jobs of the specified number/type for the specified time
	 *
	 * The response map has a 'job' field that lists status of each job, including:
	 *   - type   : the job type
	 *   - status : ok/failed
	 *   - error  : any error message string
	 *   - time   : the job run time in ms
	 * The response map also has:
	 *   - backoffs : the (job type => seconds) map of backoff times
	 *   - elapsed  : the total time spent running tasks in ms
	 *   - reached  : the reason the script finished, one of (none-ready, job-limit, time-limit)
	 *
	 * This method outputs status information only if a debug handler was set.
	 * Any exceptions are caught and logged, but are not reported as output.
	 *
	 * @param array $options Map of parameters:
	 *    - type     : the job type (or false for the default types)
	 *    - maxJobs  : maximum number of jobs to run
	 *    - maxTime  : maximum time in seconds before stopping
	 *    - throttle : whether to respect job backoff configuration
	 * @return array Summary response that can easily be JSON serialized
	 */
	public function run( array $options ) {
		$response = array( 'jobs' => array(), 'reached' => 'none-ready' );

		$type = isset( $options['type'] ) ? $options['type'] : false;
		$maxJobs = isset( $options['maxJobs'] ) ? $options['maxJobs'] : false;
		$maxTime = isset( $options['maxTime'] ) ? $options['maxTime'] : false;
		$noThrottle = isset( $options['throttle'] ) && !$options['throttle'];

		$group = JobQueueGroup::singleton();
		// Handle any required periodic queue maintenance
		$count = $group->executeReadyPeriodicTasks();
		if ( $count > 0 ) {
			$this->runJobsLog( "Executed $count periodic queue task(s)." );
		}

		// Flush any pending DB writes for sanity
		wfGetLBFactory()->commitMasterChanges();

		$backoffs = $this->loadBackoffs(); // map of (type => UNIX expiry)
		$startingBackoffs = $backoffs; // avoid unnecessary writes
		$backoffExpireFunc = function ( $t ) {
			return $t > time();
		};

		$jobsRun = 0; // counter
		$timeMsTotal = 0;
		$flags = JobQueueGroup::USE_CACHE;
		$startTime = microtime( true ); // time since jobs started running
		$lastTime = microtime( true ); // time since last slave check
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
				$jType = $job->getType();

				$this->runJobsLog( $job->toString() . " STARTING" );

				// Run the job...
				wfProfileIn( __METHOD__ . '-' . get_class( $job ) );
				$t = microtime( true );
				try {
					++$jobsRun;
					$status = $job->run();
					$error = $job->getLastError();
					wfGetLBFactory()->commitMasterChanges();
				} catch ( MWException $e ) {
					MWExceptionHandler::rollbackMasterChangesAndLog( $e );
					$status = false;
					$error = get_class( $e ) . ': ' . $e->getMessage();
					MWExceptionHandler::logException( $e );
				}
				$timeMs = intval( ( microtime( true ) - $t ) * 1000 );
				wfProfileOut( __METHOD__ . '-' . get_class( $job ) );
				$timeMsTotal += $timeMs;

				// Mark the job as done on success or when the job cannot be retried
				if ( $status !== false || !$job->allowRetries() ) {
					$group->ack( $job ); // done
				}

				if ( $status === false ) {
					$this->runJobsLog( $job->toString() . " t=$timeMs error={$error}" );
				} else {
					$this->runJobsLog( $job->toString() . " t=$timeMs good" );
				}

				$response['jobs'][] = array(
					'type'   => $jType,
					'status' => ( $status === false ) ? 'failed' : 'ok',
					'error'  => $error,
					'time'   => $timeMs
				);

				// Back off of certain jobs for a while (for throttling and for errors)
				$ttw = $this->getBackoffTimeToWait( $job );
				if ( $status === false && mt_rand( 0, 49 ) == 0 ) {
					$ttw = max( $ttw, 30 );
				}
				if ( $ttw > 0 ) {
					$backoffs[$jType] = isset( $backoffs[$jType] ) ? $backoffs[$jType] : 0;
					$backoffs[$jType] = max( $backoffs[$jType], time() + $ttw );
				}

				// Break out if we hit the job count or wall time limits...
				if ( $maxJobs && $jobsRun >= $maxJobs ) {
					$response['reached'] = 'job-limit';
					break;
				} elseif ( $maxTime && ( microtime( true ) - $startTime ) > $maxTime ) {
					$response['reached'] = 'time-limit';
					break;
				}

				// Don't let any of the main DB slaves get backed up
				$timePassed = microtime( true ) - $lastTime;
				if ( $timePassed >= 5 || $timePassed < 0 ) {
					wfWaitForSlaves( $lastTime );
					$lastTime = microtime( true );
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

		$response['backoffs'] = $backoffs;
		$response['elapsed'] = $timeMsTotal;

		return $response;
	}

	/**
	 * @param Job $job
	 * @return int Seconds for this runner to avoid doing more jobs of this type
	 * @see $wgJobBackoffThrottling
	 */
	private function getBackoffTimeToWait( Job $job ) {
		global $wgJobBackoffThrottling;

		if ( !isset( $wgJobBackoffThrottling[$job->getType()] ) ||
			$job instanceof DuplicateJob // no work was done
		) {
			return 0; // not throttled
		}

		$itemsPerSecond = $wgJobBackoffThrottling[$job->getType()];
		if ( $itemsPerSecond <= 0 ) {
			return 0; // not throttled
		}

		$seconds = 0;
		if ( $job->workItemCount() > 0 ) {
			$exactSeconds = $job->workItemCount() / $itemsPerSecond;
			// use randomized rounding
			$seconds = floor( $exactSeconds );
			$remainder = $exactSeconds - $seconds;
			$seconds += ( mt_rand() / mt_getrandmax() < $remainder ) ? 1 : 0;
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
	 * @param string $msg The message to log
	 */
	private function runJobsLog( $msg ) {
		if ( $this->debug ) {
			call_user_func_array( $this->debug, array( wfTimestamp( TS_DB ) . " $msg\n" ) );
		}
		wfDebugLog( 'runJobs', $msg );
	}
}
