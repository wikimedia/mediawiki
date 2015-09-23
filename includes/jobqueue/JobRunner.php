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

use MediaWiki\Logger\LoggerFactory;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Job queue runner utility methods
 *
 * @ingroup JobQueue
 * @since 1.24
 */
class JobRunner implements LoggerAwareInterface {
	/** @var callable|null Debug output handler */
	protected $debug;

	/**
	 * @var LoggerInterface $logger
	 */
	protected $logger;

	/**
	 * @param callable $debug Optional debug output handler
	 */
	public function setDebugHandler( $debug ) {
		$this->debug = $debug;
	}

	/**
	 * @param LoggerInterface $logger
	 * @return void
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @param LoggerInterface $logger
	 */
	public function __construct( LoggerInterface $logger = null ) {
		if ( $logger === null ) {
			$logger = LoggerFactory::getInstance( 'runJobs' );
		}
		$this->setLogger( $logger );
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
		global $wgJobClasses, $wgTrxProfilerLimits;

		$response = array( 'jobs' => array(), 'reached' => 'none-ready' );

		$type = isset( $options['type'] ) ? $options['type'] : false;
		$maxJobs = isset( $options['maxJobs'] ) ? $options['maxJobs'] : false;
		$maxTime = isset( $options['maxTime'] ) ? $options['maxTime'] : false;
		$noThrottle = isset( $options['throttle'] ) && !$options['throttle'];

		if ( $type !== false && !isset( $wgJobClasses[$type] ) ) {
			$response['reached'] = 'none-possible';
			return $response;
		}

		// Bail out if in read-only mode
		if ( wfReadOnly() ) {
			$response['reached'] = 'read-only';
			return $response;
		}

		// Catch huge single updates that lead to slave lag
		$trxProfiler = Profiler::instance()->getTransactionProfiler();
		$trxProfiler->setLogger( LoggerFactory::getInstance( 'DBPerformance' ) );
		$trxProfiler->setExpectations( $wgTrxProfilerLimits['JobRunner'], __METHOD__ );

		// Bail out if there is too much DB lag.
		// This check should not block as we want to try other wiki queues.
		$maxAllowedLag = 3;
		list( , $maxLag ) = wfGetLB( wfWikiID() )->getMaxLag();
		if ( $maxLag >= $maxAllowedLag ) {
			$response['reached'] = 'slave-lag-limit';
			return $response;
		}

		$group = JobQueueGroup::singleton();

		// Flush any pending DB writes for sanity
		wfGetLBFactory()->commitAll();

		// Some jobs types should not run until a certain timestamp
		$backoffs = array(); // map of (type => UNIX expiry)
		$backoffDeltas = array(); // map of (type => seconds)
		$wait = 'wait'; // block to read backoffs the first time

		$stats = RequestContext::getMain()->getStats();
		$jobsPopped = 0;
		$timeMsTotal = 0;
		$flags = JobQueueGroup::USE_CACHE;
		$startTime = microtime( true ); // time since jobs started running
		$checkLagPeriod = 1.0; // check slave lag this many seconds
		$lastCheckTime = 1; // timestamp of last slave check
		do {
			// Sync the persistent backoffs with concurrent runners
			$backoffs = $this->syncBackoffDeltas( $backoffs, $backoffDeltas, $wait );
			$blacklist = $noThrottle ? array() : array_keys( $backoffs );
			$wait = 'nowait'; // less important now

			if ( $type === false ) {
				$job = $group->pop( JobQueueGroup::TYPE_DEFAULT, $flags, $blacklist );
			} elseif ( in_array( $type, $blacklist ) ) {
				$job = false; // requested queue in backoff state
			} else {
				$job = $group->pop( $type ); // job from a single queue
			}

			if ( $job ) { // found a job
				$popTime = time();
				$jType = $job->getType();

				// Back off of certain jobs for a while (for throttling and for errors)
				$ttw = $this->getBackoffTimeToWait( $job );
				if ( $ttw > 0 ) {
					// Always add the delta for other runners in case the time running the
					// job negated the backoff for each individually but not collectively.
					$backoffDeltas[$jType] = isset( $backoffDeltas[$jType] )
						? $backoffDeltas[$jType] + $ttw
						: $ttw;
					$backoffs = $this->syncBackoffDeltas( $backoffs, $backoffDeltas, $wait );
				}

				$msg = $job->toString() . " STARTING";
				$this->logger->debug( $msg );
				$this->debugCallback( $msg );

				// Run the job...
				$jobStartTime = microtime( true );
				try {
					++$jobsPopped;
					$status = $job->run();
					$error = $job->getLastError();
					$this->commitMasterChanges( $job );

					DeferredUpdates::doUpdates();
					$this->commitMasterChanges( $job );
				} catch ( Exception $e ) {
					MWExceptionHandler::rollbackMasterChangesAndLog( $e );
					$status = false;
					$error = get_class( $e ) . ': ' . $e->getMessage();
					MWExceptionHandler::logException( $e );
				}
				// Commit all outstanding connections that are in a transaction
				// to get a fresh repeatable read snapshot on every connection.
				wfGetLBFactory()->commitAll();
				$timeMs = intval( ( microtime( true ) - $jobStartTime ) * 1000 );
				$timeMsTotal += $timeMs;

				// Record how long jobs wait before getting popped
				$readyTs = $job->getReadyTimestamp();
				if ( $readyTs ) {
					$pickupDelay = $popTime - $readyTs;
					$stats->timing( 'jobqueue.pickup_delay.all', 1000 * $pickupDelay );
					$stats->timing( "jobqueue.pickup_delay.$jType", 1000 * $pickupDelay );
				}
				// Record root job age for jobs being run
				$root = $job->getRootJobParams();
				if ( $root['rootJobTimestamp'] ) {
					$age = $popTime - wfTimestamp( TS_UNIX, $root['rootJobTimestamp'] );
					$stats->timing( "jobqueue.pickup_root_age.$jType", 1000 * $age );
				}
				// Track the execution time for jobs
				$stats->timing( "jobqueue.run.$jType", $timeMs );

				// Mark the job as done on success or when the job cannot be retried
				if ( $status !== false || !$job->allowRetries() ) {
					$group->ack( $job ); // done
				}

				// Back off of certain jobs for a while (for throttling and for errors)
				if ( $status === false && mt_rand( 0, 49 ) == 0 ) {
					$ttw = max( $ttw, 30 ); // too many errors
					$backoffDeltas[$jType] = isset( $backoffDeltas[$jType] )
						? $backoffDeltas[$jType] + $ttw
						: $ttw;
				}

				if ( $status === false ) {
					$msg = $job->toString() . " t=$timeMs error={$error}";
					$this->logger->error( $msg );
					$this->debugCallback( $msg );
				} else {
					$msg = $job->toString() . " t=$timeMs good";
					$this->logger->info( $msg );
					$this->debugCallback( $msg );
				}

				$response['jobs'][] = array(
					'type'   => $jType,
					'status' => ( $status === false ) ? 'failed' : 'ok',
					'error'  => $error,
					'time'   => $timeMs
				);

				// Break out if we hit the job count or wall time limits...
				if ( $maxJobs && $jobsPopped >= $maxJobs ) {
					$response['reached'] = 'job-limit';
					break;
				} elseif ( $maxTime && ( microtime( true ) - $startTime ) > $maxTime ) {
					$response['reached'] = 'time-limit';
					break;
				}

				// Don't let any of the main DB slaves get backed up.
				// This only waits for so long before exiting and letting
				// other wikis in the farm (on different masters) get a chance.
				$timePassed = microtime( true ) - $lastCheckTime;
				if ( $timePassed >= $checkLagPeriod || $timePassed < 0 ) {
					if ( !wfWaitForSlaves( $lastCheckTime, false, '*', $maxAllowedLag ) ) {
						$response['reached'] = 'slave-lag-limit';
						break;
					}
					$lastCheckTime = microtime( true );
				}
				// Don't let any queue slaves/backups fall behind
				if ( $jobsPopped > 0 && ( $jobsPopped % 100 ) == 0 ) {
					$group->waitForBackups();
				}

				// Bail if near-OOM instead of in a job
				if ( !$this->checkMemoryOK() ) {
					$response['reached'] = 'memory-limit';
					break;
				}
			}
		} while ( $job ); // stop when there are no jobs

		// Sync the persistent backoffs for the next runJobs.php pass
		if ( $backoffDeltas ) {
			$this->syncBackoffDeltas( $backoffs, $backoffDeltas, 'wait' );
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
	 * On I/O or lock acquisition failure this returns the original $backoffs.
	 *
	 * @param array $backoffs Map of (job type => UNIX timestamp)
	 * @param string $mode Lock wait mode - "wait" or "nowait"
	 * @return array Map of (job type => backoff expiry timestamp)
	 */
	private function loadBackoffs( array $backoffs, $mode = 'wait' ) {
		$file = wfTempDir() . '/mw-runJobs-backoffs.json';
		if ( is_file( $file ) ) {
			$noblock = ( $mode === 'nowait' ) ? LOCK_NB : 0;
			$handle = fopen( $file, 'rb' );
			if ( !flock( $handle, LOCK_SH | $noblock ) ) {
				fclose( $handle );
				return $backoffs; // don't wait on lock
			}
			$content = stream_get_contents( $handle );
			flock( $handle, LOCK_UN );
			fclose( $handle );
			$ctime = microtime( true );
			$cBackoffs = json_decode( $content, true ) ?: array();
			foreach ( $cBackoffs as $type => $timestamp ) {
				if ( $timestamp < $ctime ) {
					unset( $cBackoffs[$type] );
				}
			}
		} else {
			$cBackoffs = array();
		}

		return $cBackoffs;
	}

	/**
	 * Merge the current backoff expiries from persistent storage
	 *
	 * The $deltas map is set to an empty array on success.
	 * On I/O or lock acquisition failure this returns the original $backoffs.
	 *
	 * @param array $backoffs Map of (job type => UNIX timestamp)
	 * @param array $deltas Map of (job type => seconds)
	 * @param string $mode Lock wait mode - "wait" or "nowait"
	 * @return array The new backoffs account for $backoffs and the latest file data
	 */
	private function syncBackoffDeltas( array $backoffs, array &$deltas, $mode = 'wait' ) {
		if ( !$deltas ) {
			return $this->loadBackoffs( $backoffs, $mode );
		}

		$noblock = ( $mode === 'nowait' ) ? LOCK_NB : 0;
		$file = wfTempDir() . '/mw-runJobs-backoffs.json';
		$handle = fopen( $file, 'wb+' );
		if ( !flock( $handle, LOCK_EX | $noblock ) ) {
			fclose( $handle );
			return $backoffs; // don't wait on lock
		}
		$ctime = microtime( true );
		$content = stream_get_contents( $handle );
		$cBackoffs = json_decode( $content, true ) ?: array();
		foreach ( $deltas as $type => $seconds ) {
			$cBackoffs[$type] = isset( $cBackoffs[$type] ) && $cBackoffs[$type] >= $ctime
				? $cBackoffs[$type] + $seconds
				: $ctime + $seconds;
		}
		foreach ( $cBackoffs as $type => $timestamp ) {
			if ( $timestamp < $ctime ) {
				unset( $cBackoffs[$type] );
			}
		}
		ftruncate( $handle, 0 );
		fwrite( $handle, json_encode( $cBackoffs ) );
		flock( $handle, LOCK_UN );
		fclose( $handle );

		$deltas = array();

		return $cBackoffs;
	}

	/**
	 * Make sure that this script is not too close to the memory usage limit.
	 * It is better to die in between jobs than OOM right in the middle of one.
	 * @return bool
	 */
	private function checkMemoryOK() {
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
			$msg = "Detected excessive memory usage ($usedBytes/$maxBytes).";
			$this->debugCallback( $msg );
			$this->logger->error( $msg );

			return false;
		}

		return true;
	}

	/**
	 * Log the job message
	 * @param string $msg The message to log
	 */
	private function debugCallback( $msg ) {
		if ( $this->debug ) {
			call_user_func_array( $this->debug, array( wfTimestamp( TS_DB ) . " $msg\n" ) );
		}
	}

	/**
	 * Issue a commit on all masters who are currently in a transaction and have
	 * made changes to the database. It also supports sometimes waiting for the
	 * local wiki's slaves to catch up. See the documentation for
	 * $wgJobSerialCommitThreshold for more.
	 *
	 * @param Job $job
	 * @throws DBError
	 */
	private function commitMasterChanges( Job $job ) {
		global $wgJobSerialCommitThreshold;

		$lb = wfGetLB( wfWikiID() );
		if ( $wgJobSerialCommitThreshold !== false ) {
			// Generally, there is one master connection to the local DB
			$dbwSerial = $lb->getAnyOpenConnection( $lb->getWriterIndex() );
		} else {
			$dbwSerial = false;
		}

		if ( !$dbwSerial
			|| !$dbwSerial->namedLocksEnqueue()
			|| $dbwSerial->pendingWriteQueryDuration() < $wgJobSerialCommitThreshold
		) {
			// Writes are all to foreign DBs, named locks don't form queues,
			// or $wgJobSerialCommitThreshold is not reached; commit changes now
			wfGetLBFactory()->commitMasterChanges();
			return;
		}

		$ms = intval( 1000 * $dbwSerial->pendingWriteQueryDuration() );
		$msg = $job->toString() . " COMMIT ENQUEUED [{$ms}ms of writes]";
		$this->logger->info( $msg );
		$this->debugCallback( $msg );

		// Wait for an exclusive lock to commit
		if ( !$dbwSerial->lock( 'jobrunner-serial-commit', __METHOD__, 30 ) ) {
			// This will trigger a rollback in the main loop
			throw new DBError( $dbwSerial, "Timed out waiting on commit queue." );
		}
		// Wait for the generic slave to catch up
		$pos = $lb->getMasterPos();
		if ( $pos ) {
			$lb->waitForOne( $pos );
		}

		$fname = __METHOD__;
		// Re-ping all masters with transactions. This throws DBError if some
		// connection died while waiting on locks/slaves, triggering a rollback.
		wfGetLBFactory()->forEachLB( function( LoadBalancer $lb ) use ( $fname ) {
			$lb->forEachOpenConnection( function( DatabaseBase $conn ) use ( $fname ) {
				if ( $conn->writesOrCallbacksPending() ) {
					$conn->query( "SELECT 1", $fname );
				}
			} );
		} );

		// Actually commit the DB master changes
		wfGetLBFactory()->commitMasterChanges();

		// Release the lock
		$dbwSerial->unlock( 'jobrunner-serial-commit', __METHOD__ );
	}
}
