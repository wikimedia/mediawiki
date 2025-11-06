<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\JobQueue;

use LogicException;
use MediaWiki\Cache\LinkCache;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Exception\MWExceptionHandler;
use MediaWiki\Http\Telemetry;
use MediaWiki\JobQueue\Exceptions\JobQueueError;
use MediaWiki\JobQueue\Jobs\DuplicateJob;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use Psr\Log\LoggerInterface;
use Throwable;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Rdbms\DBReadOnlyError;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\ReadOnlyMode;
use Wikimedia\ScopedCallback;
use Wikimedia\Stats\StatsFactory;

/**
 * Job queue runner utility methods.
 *
 * @since 1.24
 * @ingroup JobQueue
 */
class JobRunner {

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::JobBackoffThrottling,
		MainConfigNames::JobClasses,
		MainConfigNames::MaxJobDBWriteDuration,
		MainConfigNames::TrxProfilerLimits,
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

	/** @var StatsFactory */
	private $statsFactory;

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
	 * @internal For use by ServiceWiring
	 * @param ServiceOptions $serviceOptions
	 * @param ILBFactory $lbFactory
	 * @param JobQueueGroup $jobQueueGroup The JobQueueGroup for this wiki
	 * @param ReadOnlyMode $readOnlyMode
	 * @param LinkCache $linkCache
	 * @param StatsFactory $statsFactory
	 * @param LoggerInterface $logger
	 */
	public function __construct(
		ServiceOptions $serviceOptions,
		ILBFactory $lbFactory,
		JobQueueGroup $jobQueueGroup,
		ReadOnlyMode $readOnlyMode,
		LinkCache $linkCache,
		StatsFactory $statsFactory,
		LoggerInterface $logger
	) {
		$serviceOptions->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $serviceOptions;
		$this->lbFactory = $lbFactory;
		$this->jobQueueGroup = $jobQueueGroup;
		$this->readOnlyMode = $readOnlyMode;
		$this->linkCache = $linkCache;
		$this->statsFactory = $statsFactory;
		$this->logger = $logger;
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
	 *  memory-limit, exception)
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

		$jobClasses = $this->options->get( MainConfigNames::JobClasses );
		$profilerLimits = $this->options->get( MainConfigNames::TrxProfilerLimits );

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

		[ , $maxLag ] = $this->lbFactory->getMainLB()->getMaxLag();
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
			$backoffKeys = $throttle ? array_keys( $backoffs ) : [];
			$wait = 'nowait'; // less important now

			if ( $type === false ) {
				// Treat the default job type queues as a single queue and pop off a job
				$job = $this->jobQueueGroup
					->pop( JobQueueGroup::TYPE_DEFAULT, JobQueueGroup::USE_CACHE, $backoffKeys );
			} else {
				// Pop off a job from the specified job type queue unless the execution of
				// that type of job is currently rate-limited by the back-off list
				$job = in_array( $type, $backoffKeys ) ? false : $this->jobQueueGroup->pop( $type );
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
					$backoffDeltas[$jType] = ( $backoffDeltas[$jType] ?? 0 ) + $ttw;
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

				// Stop if we caught a DBConnectionError. In theory it would be
				// possible to explicitly reconnect, but the present behaviour
				// is to just throw more exceptions every time something database-
				// related is attempted.
				if ( in_array( DBConnectionError::class, $info['caught'], true ) ) {
					$response['reached'] = 'exception';
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
	 * $wgMaxJobDBWriteDuration.
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
		$telemetry = Telemetry::getInstance();
		$oldRequestId = $telemetry->getRequestId();

		if ( $job->getRequestId() !== null ) {
			// Temporarily inherit the original ID of the web request that spawned this job
			$telemetry->overrideRequestId( $job->getRequestId() );
		} else {
			// TODO: do we need to regenerate if job doesn't have the request id?
			// If JobRunner was called with X-Request-ID header, regeneration will generate the
			// same value
			$telemetry->regenerateRequestId();
		}
		// Use an appropriate timeout to balance lag avoidance and job progress
		$oldTimeout = $this->lbFactory->setDefaultReplicationWaitTimeout( self::SYNC_TIMEOUT );
		try {
			return $this->doExecuteJob( $job );
		} finally {
			$this->lbFactory->setDefaultReplicationWaitTimeout( $oldTimeout );
			$telemetry->overrideRequestId( $oldRequestId );
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
				$this->lbFactory->commitPrimaryChanges( $fnameTrxOwner ); // new implicit round
			} else {
				$this->lbFactory->beginPrimaryChanges( $fnameTrxOwner ); // new explicit round
			}
			// Clear any stale REPEATABLE-READ snapshots from replica DB connections

			$scope = LoggerFactory::getContext()->addScoped( [
				'context.job_type' => $jType,
			] );
			$status = $job->run();
			$error = $job->getLastError();
			ScopedCallback::consume( $scope );

			// Commit all pending changes from this job
			$this->lbFactory->commitPrimaryChanges(
				$fnameTrxOwner,
				// Abort if any transaction was too big
				$this->options->get( MainConfigNames::MaxJobDBWriteDuration )
			);
			// Run any deferred update tasks; doUpdates() manages transactions itself
			DeferredUpdates::doUpdates();
		} catch ( Throwable $e ) {
			MWExceptionHandler::rollbackPrimaryChangesAndLog( $e );
			$status = false;
			$error = get_class( $e ) . ': ' . $e->getMessage() . ' in '
				. $e->getFile() . ' on line ' . $e->getLine();
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
			$this->statsFactory->getTiming( 'jobqueue_pickup_delay_seconds' )
				->setLabel( 'jobtype', $jType )
				->copyToStatsdAt( [
					"jobqueue_pickup_delay_all_mean", "jobqueue.pickup_delay.$jType"
				] )
				->observe( 1000 * $pickupDelay );
		}
		// Record root job age for jobs being run
		$rootTimestamp = $job->getRootJobParams()['rootJobTimestamp'];
		if ( $rootTimestamp ) {
			$age = max( 0, $jobStartTime - (int)wfTimestamp( TS_UNIX, $rootTimestamp ) );

			$this->statsFactory->getTiming( "jobqueue_pickup_root_age_seconds" )
				->setLabel( 'jobtype', $jType )
				->copyToStatsdAt( "jobqueue.pickup_root_age.$jType" )
				->observe( 1000 * $age );
		}
		// Track the execution time for jobs
		$this->statsFactory->getTiming( 'jobqueue_runtime_seconds' )
			->setLabel( 'jobtype', $jType )
			->copyToStatsdAt( "jobqueue.run.$jType" )
			->observe( $timeMs );
		// Track RSS increases for jobs (in case of memory leaks)
		if ( $rssStart && $rssEnd ) {
			$this->statsFactory->getCounter( 'jobqueue_rss_delta_total' )
				->setLabel( 'rss_delta', $jType )
				->copyToStatsdAt( "jobqueue.rss_delta.$jType" )
				->incrementBy( $rssEnd - $rssStart );
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
		$throttling = $this->options->get( MainConfigNames::JobBackoffThrottling );

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
				[ , $num, $unit ] = $m;
				$conv = [ 'g' => 1073741824, 'm' => 1048576, 'k' => 1024, '' => 1 ];
				$maxBytes = (int)$num * $conv[strtolower( $unit )];
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
			( $this->debug )( wfTimestamp( TS_DB ) . " $msg\n" );
		}
	}
}

/** @deprecated class alias since 1.44 */
class_alias( JobRunner::class, 'JobRunner' );
