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
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\IDatabase;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\DBTransactionError;

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
 * top-queue, and later on, the post-send top-queue, in execute().
 *
 * @since 1.19
 */
class DeferredUpdates {
	/** @var DeferrableUpdate[] Updates to be deferred until before request end */
	private static $preSendUpdates = [];
	/** @var DeferrableUpdate[] Updates to be deferred until after request end */
	private static $postSendUpdates = [];

	const ALL = 0; // all updates; in web requests, use only after flushing the output buffer
	const PRESEND = 1; // for updates that should run before flushing output buffer
	const POSTSEND = 2; // for updates that should run after flushing output buffer

	const BIG_QUEUE_SIZE = 100;

	/** @var array|null Information about the current execute() call or null if not running */
	private static $executeContext;

	/**
	 * Add an update to the deferred list to be run later by execute()
	 *
	 * In CLI mode, callback magic will also be used to run updates when safe
	 *
	 * @param DeferrableUpdate $update Some object that implements doUpdate()
	 * @param int $stage DeferredUpdates constant (PRESEND or POSTSEND) (since 1.27)
	 */
	public static function addUpdate( DeferrableUpdate $update, $stage = self::POSTSEND ) {
		global $wgCommandLineMode;

		if (
			self::$executeContext &&
			self::$executeContext['stage'] >= $stage &&
			!( $update instanceof MergeableUpdate )
		) {
			// This is a sub-DeferredUpdate; run it right after its parent update.
			// Also, while post-send updates are running, push any "pre-send" jobs to the
			// active post-send queue to make sure they get run this round (or at all).
			self::$executeContext['subqueue'][] = $update;

			return;
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
	 * Do any deferred updates and clear the list
	 *
	 * If $stage is self::ALL then the queue of PRESEND updates will be resolved,
	 * followed by the queue of POSTSEND updates
	 *
	 * @param string $mode Use "enqueue" to use the job queue when possible [Default: "run"]
	 * @param int $stage DeferredUpdates constant (PRESEND, POSTSEND, or ALL) (since 1.27)
	 */
	public static function doUpdates( $mode = 'run', $stage = self::ALL ) {
		$stageEffective = ( $stage === self::ALL ) ? self::POSTSEND : $stage;
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
	 * @param DeferrableUpdate[] $queue
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
				foreach ( $updateQueue as $du ) {
					// Enqueue the task into the job queue system instead if applicable
					if ( $mode === 'enqueue' && $du instanceof EnqueueableDataUpdate ) {
						self::jobify( $du, $lbf, $logger, $stats, $httpMethod );
						continue;
					}
					// Otherwise, execute the task and any subtasks that it spawns
					self::$executeContext = [ 'stage' => $stage, 'subqueue' => [] ];
					try {
						$e = self::run( $du, $lbf, $logger, $stats, $httpMethod );
						$guiEx = $guiEx ?: ( $e instanceof ErrorPageError ? $e : null );
						// Do the subqueue updates for $update until there are none
						while ( self::$executeContext['subqueue'] ) {
							$duChild = reset( self::$executeContext['subqueue'] );
							$firstKey = key( self::$executeContext['subqueue'] );
							unset( self::$executeContext['subqueue'][$firstKey] );

							$e = self::run( $duChild, $lbf, $logger, $stats, $httpMethod );
							$guiEx = $guiEx ?: ( $e instanceof ErrorPageError ? $e : null );
						}
					} finally {
						// Make sure we always clean up the context.
						// Losing updates while rewinding the stack is acceptable,
						// losing updates that are added later is not.
						self::$executeContext = null;
					}
				}
			}

			$updates = $queue; // new snapshot of queue (check for new entries)
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
	 * Run a task and catch/log any exceptions
	 *
	 * @param DeferrableUpdate $update
	 * @param LBFactory $lbFactory
	 * @param LoggerInterface $logger
	 * @param StatsdDataFactoryInterface $stats
	 * @param string $httpMethod
	 * @return Exception|Throwable|null
	 */
	private static function run(
		DeferrableUpdate $update,
		LBFactory $lbFactory,
		LoggerInterface $logger,
		StatsdDataFactoryInterface $stats,
		$httpMethod
	) {
		$name = get_class( $update );
		$suffix = ( $update instanceof DeferrableCallback ) ? "_{$update->getOrigin()}" : '';
		$stats->increment( "deferred_updates.$httpMethod.{$name}{$suffix}" );

		$e = null;
		try {
			self::attemptUpdate( $update, $lbFactory );
		} catch ( Exception $e ) {
		} catch ( Throwable $e ) {
		}

		if ( $e ) {
			$logger->error(
				"Deferred update {type} failed: {message}",
				[
					'type' => $name . $suffix,
					'message' => $e->getMessage(),
					'trace' => $e->getTraceAsString()
				]
			);
			$lbFactory->rollbackMasterChanges( __METHOD__ );
			// VW-style hack to work around T190178, so we can make sure
			// PageMetaDataUpdater doesn't throw exceptions.
			if ( defined( 'MW_PHPUNIT_TEST' ) ) {
				throw $e;
			}
		}

		return $e;
	}

	/**
	 * Push a task into the job queue system and catch/log any exceptions
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
		$stats->increment( "deferred_updates.$httpMethod." . get_class( $update ) );

		$e = null;
		try {
			$spec = $update->getAsJobSpecification();
			JobQueueGroup::singleton( $spec['domain'] ?? $spec['wiki'] )->push( $spec['job'] );
		} catch ( Exception $e ) {
		} catch ( Throwable $e ) {
		}

		if ( $e ) {
			$logger->error(
				"Job insertion of deferred update {type} failed: {message}",
				[
					'type' => get_class( $update ),
					'message' => $e->getMessage(),
					'trace' => $e->getTraceAsString()
				]
			);
			$lbFactory->rollbackMasterChanges( __METHOD__ );
		}
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
	 * tasks might be enqueued as jobs to be executed later.
	 *
	 * @param string $mode Use "enqueue" to use the job queue when possible
	 * @return bool Whether updates were allowed to run
	 * @since 1.28
	 */
	public static function tryOpportunisticExecute( $mode = 'run' ) {
		// execute() loop is already running
		if ( self::$executeContext ) {
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
	 * @return int Number of enqueued updates
	 * @since 1.28
	 */
	public static function pendingUpdatesCount() {
		return count( self::$preSendUpdates ) + count( self::$postSendUpdates );
	}

	/**
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
	 * Clear all pending updates without performing them. Generally, you don't
	 * want or need to call this. Unit tests need it though.
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
