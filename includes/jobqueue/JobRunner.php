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

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DBReadOnlyError;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\ScopedCallback;

/**
 * Job queue runner utility methods
 *
 * @ingroup JobQueue
 * @since 1.24
 */
class JobRunner implements LoggerAwareInterface {

	public const CONSTRUCTOR_OPTIONS = [
		'JobBackoffThrottling',
		'JobClasses',
		'JobSerialCommitThreshold',
		'MaxJobDBWriteDuration',
		'TrxProfilerLimits'
	];

	/** @var ServiceOptions */
	private $options;

	/** @var ILBFactory */
	private $lbFactory;

	/** @var JobQueueGroup */
	private $jobQueueGroup;

	/** @var ReadOnlyMode */
	private $readOnlyMode;

	/** @var LinkCache */
	private $linkCache;

	/** @var StatsdDataFactoryInterface */
	private $stats;

	/** @var callable|null Debug output handler */
	private $debug;

	/** @var LoggerInterface */
	private $logger;

	/** @var int Abort if more than this much DB lag is present */
	private const MAX_ALLOWED_LAG = 3;
	/** @var int An appropriate timeout to balance lag avoidance and job progress */
	private const SYNC_TIMEOUT = self::MAX_ALLOWED_LAG;
	/** @var float Check replica DB lag this many seconds */
	private const LAG_CHECK_PERIOD = 1.0;
	/** @var int Seconds to back off a queue due to errors */
	private const ERROR_BACKOFF_TTL = 1;
	/** @var int Seconds to back off a queue due to read-only errors */
	private const READONLY_BACKOFF_TTL = 30;

	/**
	 * @param callable $debug Optional debug output handler
	 */
	public function setDebugHandler( $debug ) {
		$this->debug = $debug;
	}

	/**
	 * @param LoggerInterface $logger
	 * @return void
	 * @deprecated since 1.35. Rely on the logger passed in the constructor.
	 */
	public function setLogger( LoggerInterface $logger ) {
		wfDeprecated( __METHOD__, '1.35' );
		$this->logger = $logger;
	}

	/**
	 * Calling this directly is deprecated.
	 * Obtain an instance via MediaWikiServices instead.
	 * @param ServiceOptions|LoggerInterface|null $serviceOptions
	 * @param ILBFactory|null $lbFactory
	 * @param JobQueueGroup|null $jobQueueGroup The JobQueueGroup for this wiki
	 * @param ReadOnlyMode|null $readOnlyMode
	 * @param LinkCache|null $linkCache
	 * @param StatsdDataFactoryInterface|null $statsdDataFactory
	 * @param LoggerInterface|null $logger
	 */
	public function __construct(
		$serviceOptions = null,
		ILBFactory $lbFactory = null,
		JobQueueGroup $jobQueueGroup = null,
		ReadOnlyMode $readOnlyMode = null,
		LinkCache $linkCache = null,
		StatsdDataFactoryInterface $statsdDataFactory = null,
		LoggerInterface $logger = null
	) {
		if ( !$serviceOptions || $serviceOptions instanceof LoggerInterface ) {
			// TODO: wfDeprecated( __METHOD__ . 'called directly. Use MediaWikiServices instead', '1.35' );
			$logger = $serviceOptions;
			$serviceOptions = new ServiceOptions(
				static::CONSTRUCTOR_OPTIONS,
				MediaWikiServices::getInstance()->getMainConfig()
			);
		}

		$this->options = $serviceOptions;
		$this->lbFactory = $lbFactory ?? MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$this->jobQueueGroup = $jobQueueGroup ?? JobQueueGroup::singleton();
		$this->readOnlyMode = $readOnlyMode ?: MediaWikiServices::getInstance()->getReadOnlyMode();
		$this->linkCache = $linkCache ?? MediaWikiServices::getInstance()->getLinkCache();
		$this->stats = $statsdDataFactory ?? MediaWikiServices::getInstance()->getStatsdDataFactory();
		$this->logger = $logger ?? LoggerFactory::getInstance( 'runJobs' );
	}

	/**
	 * Run jobs of the specified number/type for the specified time
	 *
	 * The response map has a 'job' field that lists status of each job, including:
	 *   - type   : the job/queue type
	 *   - status : ok/failed
	 *   - error  : any error message string
	 *   - time   : the job run time in ms
	 * The response map also has:
	 *   - backoffs : the (job/queue type => seconds) map of backoff times
	 *   - elapsed  : the total time spent running tasks in ms
	 *   - reached  : the reason the script finished, one of (none-ready, job-limit, time-limit,
	 *  memory-limit)
	 *
	 * This method outputs status information only if a debug handler was set.
	 * Any exceptions are caught and logged, but are not reported as output.
	 *
	 * @param array $options Map of parameters:
	 *    - type     : specified job/queue type (or false for the default types)
	 *    - maxJobs  : maximum number of jobs to run
	 *    - maxTime  : maximum time in seconds before stopping
	 *    - throttle : whether to respect job backoff configuration
	 * @return array Summary response that can easily be JSON serialized
	 * @throws JobQueueError
	 */
	public function run( array $options ) {
		$type = $options['type'] ?? false;
		$maxJobs = $options['maxJobs'] ?? false;
		$maxTime = $options['maxTime'] ?? false;
		$throttle = $options['throttle'] ?? true;

		$jobClasses = $this->options->get( 'JobClasses' );
		$profilerLimits = $this->options->get( 'TrxProfilerLimits' );

		$response = [ 'jobs' => [], 'reached' => 'none-ready' ];

		if ( $type !== false && !isset( $jobClasses[$type] ) ) {
			// Invalid job type specified
			$response['reached'] = 'none-possible';
			return $response;
		}

		if ( $this->readOnlyMode->isReadOnly() ) {
			// Any jobs popped off the queue might fail to run and thus might end up lost
			$response['reached'] = 'read-only';
			return $response;
		}

		list( , $maxLag ) = $this->lbFactory->getMainLB()->getMaxLag();
		if ( $maxLag >= self::MAX_ALLOWED_LAG ) {
			// DB lag is already too high; caller can immediately try other wikis if applicable
			$response['reached'] = 'replica-lag-limit';
			return $response;
		}

		// Narrow DB query expectations for this HTTP request
		$this->lbFactory->getTransactionProfiler()
			->setExpectations( $profilerLimits['JobRunner'], __METHOD__ );

		// Error out if an explicit DB transaction round is somehow active
		if ( $this->lbFactory->hasTransactionRound() ) {
			throw new LogicException( __METHOD__ . ' called with an active transaction round.' );
		}

		// Some jobs types should not run until a certain timestamp
		$backoffs = []; // map of (type => UNIX expiry)
		$backoffDeltas = []; // map of (type => seconds)
		$wait = 'wait'; // block to read backoffs the first time

		$loopStartTime = microtime( true );
		$jobsPopped = 0;
		$timeMsTotal = 0;
		$lastSyncTime = 1; // initialize "last sync check timestamp" to "ages ago"
		// Keep popping and running jobs until there are no more...
		do {
			// Sync the persistent backoffs with concurrent runners
			$backoffs = $this->syncBackoffDeltas( $backoffs, $backoffDeltas, $wait );
			$blacklist = $throttle ? array_keys( $backoffs ) : [];
			$wait = 'nowait'; // less important now

			if ( $type === false ) {
				// Treat the default job type queues as a single queue and pop off a job
				$job = $this->jobQueueGroup
					->pop( JobQueueGroup::TYPE_DEFAULT, JobQueueGroup::USE_CACHE, $blacklist );
			} else {
				// Pop off a job from the specified job type queue unless the execution of
				// that type of job is currently rate-limited by the back-off blacklist
				$job = in_array( $type, $blacklist ) ? false : $this->jobQueueGroup->pop( $type );
			}

			if ( $job ) {
				++$jobsPopped;
				$jType = $job->getType();

				// Back off of certain jobs for a while (for throttling and for errors)
				$ttw = $this->getBackoffTimeToWait( $job );
				if ( $ttw > 0 ) {
					// Always add the delta for other runners in case the time running the
					// job negated the backoff for each individually but not collectively.
					$backoffDeltas[$jType] = ( $backoffDeltas[$jType] ?? 0 ) + $ttw;
					$backoffs = $this->syncBackoffDeltas( $backoffs, $backoffDeltas, $wait );
				}

				$info = $this->executeJob( $job );

				// Mark completed or "one shot only" jobs as resolved
				if ( $info['status'] !== false || !$job->allowRetries() ) {
					$this->jobQueueGroup->ack( $job );
				}

				// Back off of certain jobs for a while (for throttling and for errors)
				if ( $info['status'] === false && mt_rand( 0, 49 ) == 0 ) {
					$ttw = max( $ttw, $this->getErrorBackoffTTL( $info['caught'] ) );
					$backoffDeltas[$jType] = isset( $backoffDeltas[$jType] )
						? $backoffDeltas[$jType] + $ttw
						: $ttw;
				}

				$response['jobs'][] = [
					'type'   => $jType,
					'status' => ( $info['status'] === false ) ? 'failed' : 'ok',
					'error'  => $info['error'],
					'time'   => $info['timeMs']
				];
				$timeMsTotal += $info['timeMs'];

				// Break out if we hit the job count or wall time limits
				if ( $maxJobs && $jobsPopped >= $maxJobs ) {
					$response['reached'] = 'job-limit';
					break;
				} elseif ( $maxTime && ( microtime( true ) - $loopStartTime ) > $maxTime ) {
					$response['reached'] = 'time-limit';
					break;
				}

				// Don't let any of the main DB replica DBs get backed up.
				// This only waits for so long before exiting and letting
				// other wikis in the farm (on different masters) get a chance.
				$timePassed = microtime( true ) - $lastSyncTime;
				if ( $timePassed >= self::LAG_CHECK_PERIOD || $timePassed < 0 ) {
					$opts = [ 'ifWritesSince' => $lastSyncTime, 'timeout' => self::SYNC_TIMEOUT ];
					if ( !$this->lbFactory->waitForReplication( $opts ) ) {
						$response['reached'] = 'replica-lag-limit';
						break;
					}
					$lastSyncTime = microtime( true );
				}

				// Abort if nearing OOM to avoid erroring out in the middle of a job
				if ( !$this->checkMemoryOK() ) {
					$response['reached'] = 'memory-limit';
					break;
				}
			}
		} while ( $job );

		// Sync the persistent backoffs for the next runJobs.php pass
		if ( $backoffDeltas ) {
			$this->syncBackoffDeltas( $backoffs, $backoffDeltas, 'wait' );
		}

		$response['backoffs'] = $backoffs;
		$response['elapsed'] = $timeMsTotal;

		return $response;
	}

	/**
	 * Run a specific job in a manner appropriate for mass use by job dispatchers
	 *
	 * Wraps the job's run() and tearDown() methods into appropriate transaction rounds.
	 * During execution, SPI-based logging will use the ID of the HTTP request that spawned
	 * the job (instead of the current one). Large DB write transactions will be subject to
	 * $wgJobSerialCommitThreshold and $wgMaxJobDBWriteDuration.
	 *
	 * This should never be called if there are explicit transaction rounds or pending DB writes
	 *
	 * @param RunnableJob $job
	 * @return array Map of:
	 *   - status: boolean; whether the job succeed
	 *   - error: error string; empty if there was no error specified
	 *   - caught: list of FQCNs corresponding to any exceptions caught
	 *   - timeMs: float; job execution time in milliseconds
	 * @since 1.35
	 */
	public function executeJob( RunnableJob $job ) {
		$oldRequestId = WebRequest::getRequestId();
		// Temporarily inherit the original ID of the web request that spawned this job
		WebRequest::overrideRequestId( $job->getRequestId() );
		// Use an appropriate timeout to balance lag avoidance and job progress
		$oldTimeout = $this->lbFactory->setDefaultReplicationWaitTimeout( self::SYNC_TIMEOUT );
		try {
			return $this->doExecuteJob( $job );
		} finally {
			$this->lbFactory->setDefaultReplicationWaitTimeout( $oldTimeout );
			WebRequest::overrideRequestId( $oldRequestId );
		}
	}

	/**
	 * @param RunnableJob $job
	 * @return array Map of:
	 *   - status: boolean; whether the job succeed
	 *   - error: error string; empty if there was no error specified
	 *   - caught: list of FQCNs corresponding to any exceptions caught
	 *   - timeMs: float; job execution time in milliseconds
	 */
	private function doExecuteJob( RunnableJob $job ) {
		$jType = $job->getType();
		$msg = $job->toString() . " STARTING";
		$this->logger->debug( $msg, [ 'job_type' => $job->getType() ] );
		$this->debugCallback( $msg );

		// Clear out title cache data from prior snapshots
		// (e.g. from before JobRunner was invoked in this process)
		$this->linkCache->clear();

		// Run the job...
		$caught = [];
		$rssStart = $this->getMaxRssKb();
		$jobStartTime = microtime( true );
		try {
			$fnameTrxOwner = get_class( $job ) . '::run'; // give run() outer scope
			// Flush any pending changes left over from an implicit transaction round
			if ( $job->hasExecutionFlag( $job::JOB_NO_EXPLICIT_TRX_ROUND ) ) {
				$this->lbFactory->commitMasterChanges( $fnameTrxOwner ); // new implicit round
			} else {
				$this->lbFactory->beginMasterChanges( $fnameTrxOwner ); // new explicit round
			}
			// Clear any stale REPEATABLE-READ snapshots from replica DB connections
			$this->lbFactory->flushReplicaSnapshots( $fnameTrxOwner );
			$status = $job->run();
			$error = $job->getLastError();
			// Commit all pending changes from this job
			$this->commitMasterChanges( $job, $fnameTrxOwner );
			// Run any deferred update tasks; doUpdates() manages transactions itself
			DeferredUpdates::doUpdates();
		} catch ( Throwable $e ) {
			MWExceptionHandler::rollbackMasterChangesAndLog( $e );
			$status = false;
			$error = get_class( $e ) . ': ' . $e->getMessage();
			$caught[] = get_class( $e );
		}
		// Always attempt to call teardown(), even if Job throws exception
		try {
			$job->tearDown( $status );
		} catch ( Throwable $e ) {
			MWExceptionHandler::logException( $e );
		}

		$timeMs = intval( ( microtime( true ) - $jobStartTime ) * 1000 );
		$rssEnd = $this->getMaxRssKb();

		// Record how long jobs wait before getting popped
		$readyTs = $job->getReadyTimestamp();
		if ( $readyTs ) {
			$pickupDelay = max( 0, $jobStartTime - $readyTs );
			$this->stats->timing( 'jobqueue.pickup_delay.all', 1000 * $pickupDelay );
			$this->stats->timing( "jobqueue.pickup_delay.$jType", 1000 * $pickupDelay );
		}
		// Record root job age for jobs being run
		$rootTimestamp = $job->getRootJobParams()['rootJobTimestamp'];
		if ( $rootTimestamp ) {
			$age = max( 0, $jobStartTime - wfTimestamp( TS_UNIX, $rootTimestamp ) );
			$this->stats->timing( "jobqueue.pickup_root_age.$jType", 1000 * $age );
		}
		// Track the execution time for jobs
		$this->stats->timing( "jobqueue.run.$jType", $timeMs );
		// Track RSS increases for jobs (in case of memory leaks)
		if ( $rssStart && $rssEnd ) {
			$this->stats->updateCount( "jobqueue.rss_delta.$jType", $rssEnd - $rssStart );
		}

		if ( $status === false ) {
			$msg = $job->toString() . " t={job_duration} error={job_error}";
			$this->logger->error( $msg, [
				'job_type' => $job->getType(),
				'job_duration' => $timeMs,
				'job_error' => $error,
			] );

			$msg = $job->toString() . " t=$timeMs error={$error}";
			$this->debugCallback( $msg );
		} else {
			$msg = $job->toString() . " t={job_duration} good";
			$this->logger->info( $msg, [
				'job_type' => $job->getType(),
				'job_duration' => $timeMs,
			] );

			$msg = $job->toString() . " t=$timeMs good";
			$this->debugCallback( $msg );
		}

		return [
			'status' => $status,
			'error' => $error,
			'caught' => $caught,
			'timeMs' => $timeMs
		];
	}

	/**
	 * @param string[] $caught List of FQCNs corresponding to any exceptions caught
	 * @return int TTL in seconds
	 */
	private function getErrorBackoffTTL( array $caught ) {
		return in_array( DBReadOnlyError::class, $caught )
			? self::READONLY_BACKOFF_TTL
			: self::ERROR_BACKOFF_TTL;
	}

	/**
	 * @return int|null Max memory RSS in kilobytes
	 */
	private function getMaxRssKb() {
		$info = getrusage( 0 /* RUSAGE_SELF */ );
		// see https://linux.die.net/man/2/getrusage
		return isset( $info['ru_maxrss'] ) ? (int)$info['ru_maxrss'] : null;
	}

	/**
	 * @param RunnableJob $job
	 * @return int Seconds for this runner to avoid doing more jobs of this type
	 * @see $wgJobBackoffThrottling
	 */
	private function getBackoffTimeToWait( RunnableJob $job ) {
		$throttling = $this->options->get( 'JobBackoffThrottling' );

		if ( !isset( $throttling[$job->getType()] ) || $job instanceof DuplicateJob ) {
			return 0; // not throttled
		}

		$itemsPerSecond = $throttling[$job->getType()];
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
			$cBackoffs = json_decode( $content, true ) ?: [];
			foreach ( $cBackoffs as $type => $timestamp ) {
				if ( $timestamp < $ctime ) {
					unset( $cBackoffs[$type] );
				}
			}
		} else {
			$cBackoffs = [];
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
	 * @param array &$deltas Map of (job type => seconds)
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
		$cBackoffs = json_decode( $content, true ) ?: [];
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

		$deltas = [];

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
			$m = [];
			if ( preg_match( '!^(\d+)(k|m|g|)$!i', ini_get( 'memory_limit' ), $m ) ) {
				list( , $num, $unit ) = $m;
				$conv = [ 'g' => 1073741824, 'm' => 1048576, 'k' => 1024, '' => 1 ];
				$maxBytes = $num * $conv[strtolower( $unit )];
			} else {
				$maxBytes = 0;
			}
		}
		$usedBytes = memory_get_usage();
		if ( $maxBytes && $usedBytes >= 0.95 * $maxBytes ) {
			$msg = "Detected excessive memory usage ({used_bytes}/{max_bytes}).";
			$this->logger->error( $msg, [
				'used_bytes' => $usedBytes,
				'max_bytes' => $maxBytes,
			] );

			$msg = "Detected excessive memory usage ($usedBytes/$maxBytes).";
			$this->debugCallback( $msg );

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
			call_user_func_array( $this->debug, [ wfTimestamp( TS_DB ) . " $msg\n" ] );
		}
	}

	/**
	 * Issue a commit on all masters who are currently in a transaction and have
	 * made changes to the database. It also supports sometimes waiting for the
	 * local wiki's replica DBs to catch up. See the documentation for
	 * $wgJobSerialCommitThreshold for more.
	 *
	 * @param RunnableJob $job
	 * @param string $fnameTrxOwner
	 * @throws DBError
	 */
	private function commitMasterChanges( RunnableJob $job, $fnameTrxOwner ) {
		$syncThreshold = $this->options->get( 'JobSerialCommitThreshold' );

		$time = false;
		$lb = $this->lbFactory->getMainLB();
		if ( $syncThreshold !== false && $lb->hasStreamingReplicaServers() ) {
			// Generally, there is one master connection to the local DB
			$dbwSerial = $lb->getAnyOpenConnection( $lb->getWriterIndex() );
			// We need natively blocking fast locks
			if ( $dbwSerial && $dbwSerial->namedLocksEnqueue() ) {
				$time = $dbwSerial->pendingWriteQueryDuration( $dbwSerial::ESTIMATE_DB_APPLY );
				if ( $time < $syncThreshold ) {
					$dbwSerial = false;
				}
			} else {
				$dbwSerial = false;
			}
		} else {
			// There are no replica DBs or writes are all to foreign DB (we don't handle that)
			$dbwSerial = false;
		}

		if ( !$dbwSerial ) {
			$this->lbFactory->commitMasterChanges(
				$fnameTrxOwner,
				// Abort if any transaction was too big
				[ 'maxWriteDuration' => $this->options->get( 'MaxJobDBWriteDuration' ) ]
			);

			return;
		}

		$ms = intval( 1000 * $time );

		$msg = $job->toString() . " COMMIT ENQUEUED [{job_commit_write_ms}ms of writes]";
		$this->logger->info( $msg, [
			'job_type' => $job->getType(),
			'job_commit_write_ms' => $ms,
		] );

		$msg = $job->toString() . " COMMIT ENQUEUED [{$ms}ms of writes]";
		$this->debugCallback( $msg );

		// Wait for an exclusive lock to commit
		if ( !$dbwSerial->lock( 'jobrunner-serial-commit', $fnameTrxOwner, 30 ) ) {
			// This will trigger a rollback in the main loop
			throw new DBError( $dbwSerial, "Timed out waiting on commit queue." );
		}
		$unlocker = new ScopedCallback( function () use ( $dbwSerial, $fnameTrxOwner ) {
			$dbwSerial->unlock( 'jobrunner-serial-commit', $fnameTrxOwner );
		} );

		// Wait for the replica DBs to catch up
		$pos = $lb->getMasterPos();
		if ( $pos ) {
			$lb->waitForAll( $pos );
		}

		// Actually commit the DB master changes
		$this->lbFactory->commitMasterChanges(
			$fnameTrxOwner,
			// Abort if any transaction was too big
			[ 'maxWriteDuration' => $this->options->get( 'MaxJobDBWriteDuration' ) ]
		);
		ScopedCallback::consume( $unlocker );
	}
}
