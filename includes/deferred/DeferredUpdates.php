<?php
/**
 * Interface and manager for deferred updates.
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
 */

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\DBTransactionError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * Class for managing the deferred updates
 *
 * In web request mode, deferred updates can be run at the end of the request, either before or
 * after the HTTP response has been sent. In either case, they run after the DB commit step. If
 * an update runs after the response is sent, it will not block clients. If sent before, it will
 * run synchronously. These two modes are defined via PRESEND and POSTSEND constants, the latter
 * being the default for addUpdate() and addCallableUpdate().
 *
 * Updates that work through this system will be more likely to complete by the time the client
 * makes their next request after this one than with the JobQueue system.
 *
 * In CLI mode, deferred updates will run:
 *   - a) During DeferredUpdates::addUpdate if no LBFactory DB handles have writes pending
 *   - b) On commit of an LBFactory DB handle if no other such handles have writes pending
 *   - c) During an LBFactory::waitForReplication call if no LBFactory DBs have writes pending
 *   - d) When the queue is large and an LBFactory DB handle commits (EnqueueableDataUpdate only)
 *   - e) At the completion of Maintenance::execute()
 *
 * @see Maintenance::setLBFactoryTriggers
 *
 * When updates are deferred, they go into one two FIFO "top-queues" (one for pre-send and one
 * for post-send). Updates enqueued *during* doUpdate() of a "top" update go into the "sub-queue"
 * for that update. After that method finishes, the sub-queue is run until drained. This continues
 * for each top-queue job until the entire top queue is drained. This happens for the pre-send
 * top-queue, and later on, the post-send top-queue, in doUpdates().
 *
 * @since 1.19
 */
class DeferredUpdates {
	/**
	 * @var DeferrableUpdate[] Updates to be deferred until just before HTTP response emission.
	 *  Integer-keyed entries form a list of FIFO updates and a string-keyed entries form a map
	 *  of (class => MergeableUpdate) for updates that absorb the work of any already pending
	 *  updates of the same class.
	 */
	private static $preSendUpdates = [];
	/**
	 * @var DeferrableUpdate[] Updates to be deferred until just after HTTP response emission.
	 *  Integer-keyed entries form a list of FIFO updates and a string-keyed entries form a map
	 *  of (class => MergeableUpdate) for updates that absorb the work of any already pending
	 *  updates of the same class.
	 */
	private static $postSendUpdates = [];
	/**
	 * @var array[] Execution stack of currently running updates
	 * @phan-var array<array{stage:int,update:DeferrableUpdate,subqueue:DeferrableUpdate[]}>
	 */
	private static $executionStack = [];

	public const ALL = 0; // all updates; in web requests, use only after flushing the output buffer
	public const PRESEND = 1; // for updates that should run before flushing output buffer
	public const POSTSEND = 2; // for updates that should run after flushing output buffer

	private const BIG_QUEUE_SIZE = 100;

	/**
	 * Add an update to the deferred update queue for execution at the appropriate time
	 *
	 * In CLI mode, callback magic will also be used to run updates when safe
	 *
	 * If an update is already in progress, then what happens to this update is as follows:
	 *  - MergeableUpdate instances always go on the top-queue for the specified stage, with
	 *    existing updates melding into the newly added instance at the end of the queue.
	 *  - Non-MergeableUpdate instances with a "defer until" stage at/before the actual run
	 *    stage of the innermost in-progress update go into the sub-queue of that in-progress
	 *    update. They are executed right after the update finishes to maximize isolation.
	 *  - Non-MergeableUpdate instances with a "defer until" stage after the actual run stage
	 *    of the innermost in-progress update go into the normal top-queue for that stage.
	 *
	 * @param DeferrableUpdate $update Some object that implements doUpdate()
	 * @param int $stage DeferredUpdates constant (PRESEND or POSTSEND) (since 1.27)
	 */
	public static function addUpdate( DeferrableUpdate $update, $stage = self::POSTSEND ) {
		global $wgCommandLineMode;

		// Special handling for updates pushed while another update is in progress
		if ( self::$executionStack && !( $update instanceof MergeableUpdate ) ) {
			// Get the innermost in-progress update
			end( self::$executionStack );
			$topStackPos = key( self::$executionStack );
			if ( self::$executionStack[$topStackPos]['stage'] >= $stage ) {
				// Put this update into the sub-queue of that in-progress update
				self::push( self::$executionStack[$topStackPos]['subqueue'], $update );

				return;
			}
		}

		if ( $stage === self::PRESEND ) {
			self::push( self::$preSendUpdates, $update );
		} else {
			self::push( self::$postSendUpdates, $update );
		}

		// Try to run the updates now if in CLI mode and no transaction is active.
		// This covers scripts that don't/barely use the DB but make updates to other stores.
		if ( $wgCommandLineMode ) {
			self::tryOpportunisticExecute( 'run' );
		}
	}

	/**
	 * Add a callable update. In a lot of cases, we just need a callback/closure,
	 * defining a new DeferrableUpdate object is not necessary
	 *
	 * @see MWCallableUpdate::__construct()
	 *
	 * @param callable $callable
	 * @param int $stage DeferredUpdates constant (PRESEND or POSTSEND) (since 1.27)
	 * @param IDatabase|IDatabase[]|null $dbw Abort if this DB is rolled back [optional] (since 1.28)
	 */
	public static function addCallableUpdate(
		$callable, $stage = self::POSTSEND, $dbw = null
	) {
		self::addUpdate( new MWCallableUpdate( $callable, wfGetCaller(), $dbw ), $stage );
	}

	/**
	 * Consume the list of deferred updates and execute them
	 *
	 * Note that it is rarely the case that this method should be called outside of a few
	 * select entry points. For simplicity, that kind of recursion is discouraged. Recursion
	 * cannot happen if an explicit transaction round is active, which limits usage to updates
	 * with TRX_ROUND_ABSENT that do not leave open an transactions round of their own during
	 * the call to this method.
	 *
	 * In the less-common case of this being called within an in-progress DeferrableUpdate,
	 * this will not see any top-queue updates (since they were consumed and are being run
	 * inside an outer execution loop). In that case, it will instead operate on the sub-queue
	 * of the innermost in-progress update on the stack.
	 *
	 * If $stage is self::ALL then the queue of PRESEND updates will be resolved, followed
	 * by the queue of POSTSEND updates.
	 *
	 * @param string $mode Use "enqueue" to use the job queue when possible [Default: "run"]
	 * @param int $stage DeferredUpdates constant (PRESEND, POSTSEND, or ALL) (since 1.27)
	 */
	public static function doUpdates( $mode = 'run', $stage = self::ALL ) {
		$stageEffective = ( $stage === self::ALL ) ? self::POSTSEND : $stage;
		// Special handling for when an in-progress update triggers this method
		if ( self::$executionStack ) {
			// Run the sub-queue updates for the innermost in-progress update
			end( self::$executionStack );
			$topStackPos = key( self::$executionStack );
			self::handleUpdateQueue(
				self::$executionStack[$topStackPos]['subqueue'],
				$mode,
				$stageEffective
			);

			return;
		}
		// For ALL mode, make sure that any PRESEND updates added along the way get run.
		// Normally, these use the subqueue, but that isn't true for MergeableUpdate items.
		do {
			if ( $stage === self::ALL || $stage === self::PRESEND ) {
				self::handleUpdateQueue( self::$preSendUpdates, $mode, $stageEffective );
			}

			if ( $stage === self::ALL || $stage == self::POSTSEND ) {
				self::handleUpdateQueue( self::$postSendUpdates, $mode, $stageEffective );
			}
		} while ( $stage === self::ALL && self::$preSendUpdates );
	}

	/**
	 * @param DeferrableUpdate[] &$queue Combined FIFO update list and MergeableUpdate map
	 * @param DeferrableUpdate $update
	 */
	private static function push( array &$queue, DeferrableUpdate $update ) {
		if ( $update instanceof MergeableUpdate ) {
			$class = get_class( $update ); // fully-qualified class
			if ( isset( $queue[$class] ) ) {
				/** @var MergeableUpdate $existingUpdate */
				$existingUpdate = $queue[$class];
				'@phan-var MergeableUpdate $existingUpdate';
				$existingUpdate->merge( $update );
				// Move the update to the end to handle things like mergeable purge
				// updates that might depend on the prior updates in the queue running
				unset( $queue[$class] );
				$queue[$class] = $existingUpdate;
			} else {
				$queue[$class] = $update;
			}
		} else {
			$queue[] = $update;
		}
	}

	/**
	 * Immediately run or enqueue a list of updates
	 *
	 * Updates that implement EnqueueableDataUpdate and fail to run will be enqueued
	 *
	 * @param DeferrableUpdate[] &$queue List of DeferrableUpdate objects
	 * @param string $mode Either "run" or "enqueue" (to use the job queue when possible)
	 * @param int $stage Class constant (PRESEND, POSTSEND) (since 1.28)
	 * @throws ErrorPageError Happens on top-level calls
	 * @throws Exception Happens on second-level calls
	 */
	protected static function handleUpdateQueue( array &$queue, $mode, $stage ) {
		$services = MediaWikiServices::getInstance();
		$stats = $services->getStatsdDataFactory();
		$lbf = $services->getDBLoadBalancerFactory();
		$logger = LoggerFactory::getInstance( 'DeferredUpdates' );
		$httpMethod = $services->getMainConfig()->get( 'CommandLineMode' )
			? 'cli'
			: strtolower( RequestContext::getMain()->getRequest()->getMethod() );

		/** @var ErrorPageError $guiEx */
		$guiEx = null;
		/** @var Throwable $exception */
		$exception = null;

		/** @var DeferrableUpdate[] $updates Snapshot of queue */
		$updates = $queue;

		// Keep doing rounds of updates until none get enqueued...
		while ( $updates ) {
			$queue = []; // clear the queue

			// Segregate the queue into one for DataUpdate and one for everything else
			$dataUpdateQueue = [];
			$genericUpdateQueue = [];
			foreach ( $updates as $update ) {
				if ( $update instanceof DataUpdate ) {
					$dataUpdateQueue[] = $update;
				} else {
					$genericUpdateQueue[] = $update;
				}
			}
			// Execute all DataUpdate queue followed by the DeferrableUpdate queue...
			foreach ( [ $dataUpdateQueue, $genericUpdateQueue ] as $updateQueue ) {
				foreach ( $updateQueue as $curUpdate ) {
					// Enqueue the update into the job queue system instead if applicable
					if ( $mode === 'enqueue' && $curUpdate instanceof EnqueueableDataUpdate ) {
						self::jobify( $curUpdate, $lbf, $logger, $stats, $httpMethod );
						continue;
					}
					// Otherwise, execute the update, followed by any sub-updates that it spawns
					$stackEntry = [ 'stage' => $stage, 'update' => $curUpdate, 'subqueue' => [] ];
					$stackKey = count( self::$executionStack );
					self::$executionStack[$stackKey] =& $stackEntry;
					try {
						$e = self::run( $curUpdate, $lbf, $logger, $stats, $httpMethod );
						$guiEx = $guiEx ?: ( $e instanceof ErrorPageError ? $e : null );
						$exception = $exception ?: $e;
						// Do the subqueue updates for $update until there are none
						// @phan-suppress-next-line PhanImpossibleConditionInLoop
						while ( $stackEntry['subqueue'] ) {
							$duChild = reset( $stackEntry['subqueue'] );
							$duChildKey = key( $stackEntry['subqueue'] );
							unset( $stackEntry['subqueue'][$duChildKey] );

							$e = self::run( $duChild, $lbf, $logger, $stats, $httpMethod );
							$guiEx = $guiEx ?: ( $e instanceof ErrorPageError ? $e : null );
							$exception = $exception ?: $e;
						}
					} finally {
						// Make sure we always clean up the context.
						// Losing updates while rewinding the stack is acceptable,
						// losing updates that are added later is not.
						unset( self::$executionStack[$stackKey] );
					}
				}
			}

			$updates = $queue; // new snapshot of queue (check for new entries)
		}

		// VW-style hack to work around T190178, so we can make sure
		// PageMetaDataUpdater doesn't throw exceptions.
		if ( $exception && defined( 'MW_PHPUNIT_TEST' ) ) {
			throw $exception;
		}

		// Throw the first of any GUI errors as long as the context is HTTP pre-send. However,
		// callers should check permissions *before* enqueueing updates. If the main transaction
		// round actions succeed but some deferred updates fail due to permissions errors then
		// there is a risk that some secondary data was not properly updated.
		if ( $guiEx && $stage === self::PRESEND && !headers_sent() ) {
			throw $guiEx;
		}
	}

	/**
	 * Run an update, and, if an error was thrown, catch/log it and fallback to the job queue
	 *
	 * @param DeferrableUpdate $update
	 * @param LBFactory $lbFactory
	 * @param LoggerInterface $logger
	 * @param StatsdDataFactoryInterface $stats
	 * @param string $httpMethod
	 * @return Throwable|null
	 */
	private static function run(
		DeferrableUpdate $update,
		LBFactory $lbFactory,
		LoggerInterface $logger,
		StatsdDataFactoryInterface $stats,
		$httpMethod
	) : ?Throwable {
		$suffix = ( $update instanceof DeferrableCallback ) ? "_{$update->getOrigin()}" : '';
		$type = get_class( $update ) . $suffix;
		$stats->increment( "deferred_updates.$httpMethod.$type" );

		$updateId = spl_object_id( $update );
		$logger->debug( __METHOD__ . ": started $type #$updateId" );
		$e = null;
		try {
			self::attemptUpdate( $update, $lbFactory );

			return null;
		} catch ( Throwable $e ) {
		} finally {
			$logger->debug( __METHOD__ . ": ended $type #$updateId" );
		}

		MWExceptionHandler::logException( $e );
		$logger->error(
			"Deferred update '{deferred_type}' failed to run.",
			[
				'deferred_type' => $type,
				'exception' => $e,
			]
		);

		$lbFactory->rollbackMasterChanges( __METHOD__ );

		// Try to push the update as a job so it can run later if possible
		if ( $update instanceof EnqueueableDataUpdate ) {
			$jobEx = null;
			try {
				$spec = $update->getAsJobSpecification();
				JobQueueGroup::singleton( $spec['domain'] )->push( $spec['job'] );

				return $e;
			} catch ( Throwable $jobEx ) {
			}

			MWExceptionHandler::logException( $jobEx );
			$logger->error(
				"Deferred update '{deferred_type}' failed to enqueue as a job.",
				[
					'deferred_type' => $type,
					'exception' => $jobEx,
				]
			);

			$lbFactory->rollbackMasterChanges( __METHOD__ );
		}

		return $e;
	}

	/**
	 * Push a update into the job queue system and catch/log any exceptions
	 *
	 * @param EnqueueableDataUpdate $update
	 * @param LBFactory $lbFactory
	 * @param LoggerInterface $logger
	 * @param StatsdDataFactoryInterface $stats
	 * @param string $httpMethod
	 */
	private static function jobify(
		EnqueueableDataUpdate $update,
		LBFactory $lbFactory,
		LoggerInterface $logger,
		StatsdDataFactoryInterface $stats,
		$httpMethod
	) {
		$type = get_class( $update );
		$stats->increment( "deferred_updates.$httpMethod.$type" );

		$jobEx = null;
		try {
			$spec = $update->getAsJobSpecification();
			JobQueueGroup::singleton( $spec['domain'] )->push( $spec['job'] );

			return;
		} catch ( Throwable $jobEx ) {
		}

		MWExceptionHandler::logException( $jobEx );
		$logger->error(
			"Deferred update '$type' failed to enqueue as a job.",
			[
				'deferred_type' => $type,
				'exception' => $jobEx,
			]
		);

		$lbFactory->rollbackMasterChanges( __METHOD__ );
	}

	/**
	 * Attempt to run an update with the appropriate transaction round state it expects
	 *
	 * DeferredUpdate classes that wrap the execution of bundles of other DeferredUpdate
	 * instances can use this method to run the updates. Any such wrapper class should
	 * always use TRX_ROUND_ABSENT itself.
	 *
	 * @param DeferrableUpdate $update
	 * @param ILBFactory $lbFactory
	 * @since 1.34
	 */
	public static function attemptUpdate( DeferrableUpdate $update, ILBFactory $lbFactory ) {
		$ticket = $lbFactory->getEmptyTransactionTicket( __METHOD__ );
		if ( !$ticket || $lbFactory->hasTransactionRound() ) {
			throw new DBTransactionError( null, "A database transaction round is pending." );
		}

		if ( $update instanceof DataUpdate ) {
			$update->setTransactionTicket( $ticket );
		}

		// Designate $update::doUpdate() as the write round owner
		$fnameTrxOwner = ( $update instanceof DeferrableCallback )
			? $update->getOrigin()
			: get_class( $update ) . '::doUpdate';
		// Determine whether the write round will be explicit or implicit
		$useExplicitTrxRound = !(
			$update instanceof TransactionRoundAwareUpdate &&
			$update->getTransactionRoundRequirement() == $update::TRX_ROUND_ABSENT
		);

		// Flush any pending changes left over from an implicit transaction round
		if ( $useExplicitTrxRound ) {
			$lbFactory->beginMasterChanges( $fnameTrxOwner ); // new explicit round
		} else {
			$lbFactory->commitMasterChanges( $fnameTrxOwner ); // new implicit round
		}
		// Run the update after any stale master view snapshots have been flushed
		$update->doUpdate();
		// Commit any pending changes from the explicit or implicit transaction round
		$lbFactory->commitMasterChanges( $fnameTrxOwner );
	}

	/**
	 * Run all deferred updates immediately if there are no DB writes active
	 *
	 * If there are many deferred updates pending, $mode is 'run', and there
	 * are still busy LBFactory database handles, then any EnqueueableDataUpdate
	 * updates might be enqueued as jobs to be executed later.
	 *
	 * @param string $mode Use "enqueue" to use the job queue when possible
	 * @return bool Whether updates were allowed to run
	 * @since 1.28
	 */
	public static function tryOpportunisticExecute( $mode = 'run' ) {
		// An update is already in progress
		if ( self::$executionStack ) {
			return false;
		}

		// Avoiding running updates without them having outer scope
		if ( !self::areDatabaseTransactionsActive() ) {
			self::doUpdates( $mode );
			return true;
		}

		if ( self::pendingUpdatesCount() >= self::BIG_QUEUE_SIZE ) {
			// If we cannot run the updates with outer transaction context, try to
			// at least enqueue all the updates that support queueing to job queue
			self::$preSendUpdates = self::enqueueUpdates( self::$preSendUpdates );
			self::$postSendUpdates = self::enqueueUpdates( self::$postSendUpdates );
		}

		return !self::pendingUpdatesCount();
	}

	/**
	 * Enqueue a job for each EnqueueableDataUpdate item and return the other items
	 *
	 * @param DeferrableUpdate[] $updates A list of deferred update instances
	 * @return DeferrableUpdate[] Remaining updates that do not support being queued
	 */
	private static function enqueueUpdates( array $updates ) {
		$remaining = [];

		foreach ( $updates as $update ) {
			if ( $update instanceof EnqueueableDataUpdate ) {
				$spec = $update->getAsJobSpecification();
				$domain = $spec['domain'] ?? $spec['wiki'];
				JobQueueGroup::singleton( $domain )->push( $spec['job'] );
			} else {
				$remaining[] = $update;
			}
		}

		return $remaining;
	}

	/**
	 * Get the number of currently enqueued updates in the top-queues
	 *
	 * Calling this while an update is in-progress produces undefined results
	 *
	 * @return int
	 * @since 1.28
	 */
	public static function pendingUpdatesCount() {
		if ( self::$executionStack ) {
			throw new LogicException( "Called during execution of a DeferrableUpdate" );
		}

		return count( self::$preSendUpdates ) + count( self::$postSendUpdates );
	}

	/**
	 * Get the list of pending updates in the top-queues
	 *
	 * Calling this while an update is in-progress produces undefined results
	 *
	 * This method should only be used for unit tests
	 *
	 * @param int $stage DeferredUpdates constant (PRESEND, POSTSEND, or ALL)
	 * @return DeferrableUpdate[]
	 * @since 1.29
	 */
	public static function getPendingUpdates( $stage = self::ALL ) {
		$updates = [];
		if ( $stage === self::ALL || $stage === self::PRESEND ) {
			$updates = array_merge( $updates, self::$preSendUpdates );
		}
		if ( $stage === self::ALL || $stage === self::POSTSEND ) {
			$updates = array_merge( $updates, self::$postSendUpdates );
		}

		return $updates;
	}

	/**
	 * Clear all pending updates without performing them
	 *
	 * Calling this while an update is in-progress produces undefined results
	 *
	 * This method should only be used for unit tests
	 */
	public static function clearPendingUpdates() {
		self::$preSendUpdates = [];
		self::$postSendUpdates = [];
	}

	/**
	 * @return bool If a transaction round is active or connection is not ready for commit()
	 */
	private static function areDatabaseTransactionsActive() {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		if ( $lbFactory->hasTransactionRound() || !$lbFactory->isReadyForRoundOperations() ) {
			return true;
		}

		$connsBusy = false;
		$lbFactory->forEachLB( function ( LoadBalancer $lb ) use ( &$connsBusy ) {
			$lb->forEachOpenMasterConnection( function ( IDatabase $conn ) use ( &$connsBusy ) {
				if ( $conn->writesOrCallbacksPending() || $conn->explicitTrxActive() ) {
					$connsBusy = true;
				}
			} );
		} );

		return $connsBusy;
	}
}
