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
 * In CLI mode, updates run immediately if no DB writes are pending. Otherwise, they run when:
 *   - a) Any waitForReplication() call if no writes are pending on any DB
 *   - b) A commit happens on Maintenance::getDB( DB_MASTER ) if no writes are pending on any DB
 *   - c) EnqueueableDataUpdate tasks may enqueue on commit of Maintenance::getDB( DB_MASTER )
 *   - d) At the completion of Maintenance::execute()
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
	 * @param integer $stage DeferredUpdates constant (PRESEND or POSTSEND) (since 1.27)
	 */
	public static function addUpdate( DeferrableUpdate $update, $stage = self::POSTSEND ) {
		global $wgCommandLineMode;

		if ( self::$executeContext && self::$executeContext['stage'] >= $stage ) {
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
	 * @param integer $stage DeferredUpdates constant (PRESEND or POSTSEND) (since 1.27)
	 * @param IDatabase|null $dbw Abort if this DB is rolled back [optional] (since 1.28)
	 */
	public static function addCallableUpdate(
		$callable, $stage = self::POSTSEND, IDatabase $dbw = null
	) {
		self::addUpdate( new MWCallableUpdate( $callable, wfGetCaller(), $dbw ), $stage );
	}

	/**
	 * Do any deferred updates and clear the list
	 *
	 * @param string $mode Use "enqueue" to use the job queue when possible [Default: "run"]
	 * @param integer $stage DeferredUpdates constant (PRESEND, POSTSEND, or ALL) (since 1.27)
	 */
	public static function doUpdates( $mode = 'run', $stage = self::ALL ) {
		$stageEffective = ( $stage === self::ALL ) ? self::POSTSEND : $stage;

		if ( $stage === self::ALL || $stage === self::PRESEND ) {
			self::execute( self::$preSendUpdates, $mode, $stageEffective );
		}

		if ( $stage === self::ALL || $stage == self::POSTSEND ) {
			self::execute( self::$postSendUpdates, $mode, $stageEffective );
		}
	}

	/**
	 * @param bool $value Whether to just immediately run updates in addUpdate()
	 * @since 1.28
	 * @deprecated 1.29 Causes issues in Web-executed jobs - see T165714 and T100085.
	 */
	public static function setImmediateMode( $value ) {
		wfDeprecated( __METHOD__, '1.29' );
	}

	/**
	 * @param DeferrableUpdate[] $queue
	 * @param DeferrableUpdate $update
	 */
	private static function push( array &$queue, DeferrableUpdate $update ) {
		if ( $update instanceof MergeableUpdate ) {
			$class = get_class( $update ); // fully-qualified class
			if ( isset( $queue[$class] ) ) {
				/** @var $existingUpdate MergeableUpdate */
				$existingUpdate = $queue[$class];
				$existingUpdate->merge( $update );
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
	 * @param integer $stage Class constant (PRESEND, POSTSEND) (since 1.28)
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
					self::$executeContext = null;
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
	 * @param integer $stage
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
				JobQueueGroup::singleton( $spec['wiki'] )->push( $spec['job'] );
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
		}

		return $guiError;
	}

	/**
	 * Run all deferred updates immediately if there are no DB writes active
	 *
	 * If $mode is 'run' but there are busy databates, EnqueueableDataUpdate
	 * tasks will be enqueued anyway for the sake of progress.
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
				JobQueueGroup::singleton( $spec['wiki'] )->push( $spec['job'] );
			} else {
				$remaining[] = $update;
			}
		}

		return $remaining;
	}

	/**
	 * @return integer Number of enqueued updates
	 * @since 1.28
	 */
	public static function pendingUpdatesCount() {
		return count( self::$preSendUpdates ) + count( self::$postSendUpdates );
	}

	/**
	 * @param integer $stage DeferredUpdates constant (PRESEND, POSTSEND, or ALL)
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
		if ( $lbFactory->hasTransactionRound() ) {
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
