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
use Wikimedia\Rdbms\IDatabase;
use MediaWiki\MediaWikiServices;
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
				self::execute( self::$preSendUpdates, $mode, $stageEffective );
			}

			if ( $stage === self::ALL || $stage == self::POSTSEND ) {
				self::execute( self::$postSendUpdates, $mode, $stageEffective );
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
	 * Immediately run/queue a list of updates
	 *
	 * @param DeferrableUpdate[] &$queue List of DeferrableUpdate objects
	 * @param string $mode Use "enqueue" to use the job queue when possible
	 * @param int $stage Class constant (PRESEND, POSTSEND) (since 1.28)
	 * @throws ErrorPageError Happens on top-level calls
	 * @throws Exception Happens on second-level calls
	 */
	protected static function execute( array &$queue, $mode, $stage ) {
		$services = MediaWikiServices::getInstance();
		$stats = $services->getStatsdDataFactory();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$method = RequestContext::getMain()->getRequest()->getMethod();

		$ticket = $lbFactory->getEmptyTransactionTicket( __METHOD__ );

		/** @var ErrorPageError $reportableError */
		$reportableError = null;
		/** @var DeferrableUpdate[] $updates Snapshot of queue */
		$updates = $queue;

		// Keep doing rounds of updates until none get enqueued...
		while ( $updates ) {
			$queue = []; // clear the queue

			// Order will be DataUpdate followed by generic DeferrableUpdate tasks
			$updatesByType = [ 'data' => [], 'generic' => [] ];
			foreach ( $updates as $du ) {
				if ( $du instanceof DataUpdate ) {
					$du->setTransactionTicket( $ticket );
					$updatesByType['data'][] = $du;
				} else {
					$updatesByType['generic'][] = $du;
				}

				$name = ( $du instanceof DeferrableCallback )
					? get_class( $du ) . '-' . $du->getOrigin()
					: get_class( $du );
				$stats->increment( 'deferred_updates.' . $method . '.' . $name );
			}

			// Execute all remaining tasks...
			foreach ( $updatesByType as $updatesForType ) {
				foreach ( $updatesForType as $update ) {
					self::$executeContext = [ 'stage' => $stage, 'subqueue' => [] ];
					try {
						/** @var DeferrableUpdate $update */
						$guiError = self::runUpdate( $update, $lbFactory, $mode, $stage );
						$reportableError = $reportableError ?: $guiError;
						// Do the subqueue updates for $update until there are none
						while ( self::$executeContext['subqueue'] ) {
							$subUpdate = reset( self::$executeContext['subqueue'] );
							$firstKey = key( self::$executeContext['subqueue'] );
							unset( self::$executeContext['subqueue'][$firstKey] );

							if ( $subUpdate instanceof DataUpdate ) {
								$subUpdate->setTransactionTicket( $ticket );
							}

							$guiError = self::runUpdate( $subUpdate, $lbFactory, $mode, $stage );
							$reportableError = $reportableError ?: $guiError;
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

		if ( $reportableError ) {
			throw $reportableError; // throw the first of any GUI errors
		}
	}

	/**
	 * @param DeferrableUpdate $update
	 * @param LBFactory $lbFactory
	 * @param string $mode
	 * @param int $stage
	 * @return ErrorPageError|null
	 */
	private static function runUpdate(
		DeferrableUpdate $update, LBFactory $lbFactory, $mode, $stage
	) {
		$guiError = null;
		try {
			if ( $mode === 'enqueue' && $update instanceof EnqueueableDataUpdate ) {
				// Run only the job enqueue logic to complete the update later
				$spec = $update->getAsJobSpecification();
				$domain = $spec['domain'] ?? $spec['wiki'];
				JobQueueGroup::singleton( $domain )->push( $spec['job'] );
			} elseif ( $update instanceof TransactionRoundDefiningUpdate ) {
				$update->doUpdate();
			} else {
				// Run the bulk of the update now
				$fnameTrxOwner = get_class( $update ) . '::doUpdate';
				$lbFactory->beginMasterChanges( $fnameTrxOwner );
				$update->doUpdate();
				$lbFactory->commitMasterChanges( $fnameTrxOwner );
			}
		} catch ( Exception $e ) {
			// Reporting GUI exceptions does not work post-send
			if ( $e instanceof ErrorPageError && $stage === self::PRESEND ) {
				$guiError = $e;
			}
			MWExceptionHandler::rollbackMasterChangesAndLog( $e );

			// VW-style hack to work around T190178, so we can make sure
			// PageMetaDataUpdater doesn't throw exceptions.
			if ( defined( 'MW_PHPUNIT_TEST' ) ) {
				throw $e;
			}
		}

		return $guiError;
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
