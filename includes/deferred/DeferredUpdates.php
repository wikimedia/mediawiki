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

/**
 * Class for managing the deferred updates
 *
 * In web request mode, deferred updates can be run at the end of the request, either before or
 * after the HTTP response has been sent. In either case, they run after the DB commit step. If
 * an update runs after the response is sent, it will not block clients. If sent before, it will
 * run synchronously. If such an update works via queueing, it will be more likely to complete by
 * the time the client makes their next request after this one.
 *
 * In CLI mode, updates run immediately if no DB writes are pending. Otherwise, they run when:
 *   - a) Any waitForReplication() call if no writes are pending on any DB
 *   - b) A commit happens on Maintenance::getDB( DB_MASTER ) if no writes are pending on any DB
 *   - c) EnqueueableDataUpdate tasks enqueue on any commit of Maintenance::getDB( DB_MASTER )
 *   - d) At the completion of Maintenance::execute()
 *
 * When updates are deferred, they use a FIFO queue (one for pre-send and one for post-send).
 *
 * @since 1.19
 */
use \MediaWiki\MediaWikiServices;

class DeferredUpdates {
	/** @var DeferrableUpdate[] Updates to be deferred until before request end */
	private static $preSendUpdates = [];
	/** @var DeferrableUpdate[] Updates to be deferred until after request end */
	private static $postSendUpdates = [];

	const ALL = 0; // all updates; in web requests, use only after flushing the output buffer
	const PRESEND = 1; // for updates that should run before flushing output buffer
	const POSTSEND = 2; // for updates that should run after flushing output buffer

	const BIG_QUEUE_SIZE = 100;

	/**
	 * Add an update to the deferred list to be run later by execute()
	 *
	 * In CLI mode, callback magic will also be used to run updates when safe
	 *
	 * @param DeferrableUpdate $update Some object that implements doUpdate()
	 * @param integer $type DeferredUpdates constant (PRESEND or POSTSEND) (since 1.27)
	 */
	public static function addUpdate( DeferrableUpdate $update, $type = self::POSTSEND ) {
		global $wgCommandLineMode;

		if ( $type === self::PRESEND ) {
			self::push( self::$preSendUpdates, $update );
		} else {
			self::push( self::$postSendUpdates, $update );
		}

		if ( $wgCommandLineMode ) {
			// If no writes are pending, then callbacks can run immediately
			self::tryOpportunisticExecute( 'run' );
		}
	}

	/**
	 * @return IDatabase[] Connection where commit() cannot be called yet
	 */
	private static function getBusyDbConnections() {
		$connsBusy = [];

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lbFactory->forEachLB( function ( LoadBalancer $lb ) use ( &$connsBusy ) {
			$lb->forEachOpenMasterConnection( function ( IDatabase $conn ) use ( &$connsBusy ) {
				if ( $conn->writesOrCallbacksPending() || $conn->explicitTrxActive() ) {
					$connsBusy[] = $conn;
				}
			} );
		} );

		return $connsBusy;
	}

	/**
	 * Add a callable update. In a lot of cases, we just need a callback/closure,
	 * defining a new DeferrableUpdate object is not necessary
	 *
	 * @see MWCallableUpdate::__construct()
	 *
	 * @param callable $callable
	 * @param integer $type DeferredUpdates constant (PRESEND or POSTSEND) (since 1.27)
	 * @param IDatabase|null $dbw Abort if this DB is rolled back [optional] (since 1.28)
	 */
	public static function addCallableUpdate(
		$callable, $type = self::POSTSEND, IDatabase $dbw = null
	) {
		self::addUpdate( new MWCallableUpdate( $callable, wfGetCaller(), $dbw ), $type );
	}

	/**
	 * Do any deferred updates and clear the list
	 *
	 * @param string $mode Use "enqueue" to use the job queue when possible [Default: "run"]
	 * @param integer $type DeferredUpdates constant (PRESEND, POSTSEND, or ALL) (since 1.27)
	 */
	public static function doUpdates( $mode = 'run', $type = self::ALL ) {
		if ( $type === self::ALL || $type == self::PRESEND ) {
			self::execute( self::$preSendUpdates, $mode, $type );
		}

		if ( $type === self::ALL || $type == self::POSTSEND ) {
			self::execute( self::$postSendUpdates, $mode, $type );
		}
	}

	/**
	 * @param DeferredUpdates[] $queue
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
	 * @param DeferrableUpdate[] &$queue List of DeferrableUpdate objects
	 * @param string $mode Use "enqueue" to use the job queue when possible
	 * @param integer $type Class constant (PRESEND, POSTSEND, or ALL) (since 1.28)
	 * @throws ErrorPageError
	 */
	public static function execute( array &$queue, $mode, $type ) {
		$services = MediaWikiServices::getInstance();
		$stats = $services->getStatsdDataFactory();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$method = RequestContext::getMain()->getRequest()->getMethod();

		/** @var ErrorPageError $reportError */
		$reportError = null;
		/** @var DeferrableUpdate[] $updates Snapshot of queue */
		$updates = $queue;

		// Keep doing rounds of updates until none get enqueued...
		while ( $updates ) {
			$queue = []; // clear the queue

			if ( $mode === 'enqueue' ) {
				try {
					// Push enqueuable updates to the job queue and get the rest
					$updates = self::enqueueUpdates( $updates );
				} catch ( Exception $e ) {
					// Let other updates have a chance to run if this failed
					MWExceptionHandler::rollbackMasterChangesAndLog( $e );
				}
			}

			// Segregate updates into those that want their own transaction rounds
			// and those that all want to join each other in a single transaction round
			$trxDataUpdates = [];
			$otherUpdates = [];
			foreach ( $updates as $update ) {
				if ( $update instanceof DataUpdate && $update->useTransaction() ) {
					$trxDataUpdates[] = $update;
				} else {
					$otherUpdates[] = $update;
				}

				$name = ( $update instanceof DeferrableCallback )
					? get_class( $update ) . '-' . $update->getOrigin()
					: get_class( $update );
				$stats->increment( 'deferred_updates.' . $method . '.' . $name );
			}

			// Run a transactional DataUpdate task round...
			if ( $trxDataUpdates ) {
				try {
					$lbFactory->beginMasterChanges( __METHOD__ );
					foreach ( $trxDataUpdates as $trxDataUpdate ) {
						/** @var $trxDataUpdate DataUpdate */
						$trxDataUpdate->doUpdate();
					}
					$lbFactory->commitMasterChanges( __METHOD__ );
				} catch ( Exception $e ) {
					// Reporting GUI exceptions does not work post-send
					if ( $e instanceof ErrorPageError && $type === self::PRESEND ) {
						$reportError = $reportError ?: $e;
					}
					MWExceptionHandler::rollbackMasterChangesAndLog( $e );
				}
			}

			// Execute the non-transactional DataUpdate and non-DataUpdate tasks...
			foreach ( $otherUpdates as $update ) {
				/** @var $update DeferrableUpdate */
				try {
					$lbFactory->beginMasterChanges( __METHOD__ );
					$update->doUpdate();
					$lbFactory->commitMasterChanges( __METHOD__ );
				} catch ( Exception $e ) {
					// Reporting GUI exceptions does not work post-send
					if ( $e instanceof ErrorPageError && $type === self::PRESEND ) {
						$reportError = $reportError ?: $e;
					}
					MWExceptionHandler::rollbackMasterChangesAndLog( $e );
				}
			}

			$updates = $queue; // new snapshot of queue (check for new entries)
		}

		if ( $reportError ) {
			throw $reportError; // throw the first of any GUI errors
		}
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
		static $recursionGuard = false;
		if ( $recursionGuard ) {
			return false; // COMMITs trigger inside update loop and inside some updates
		}

		try {
			$recursionGuard = true;
			if ( !self::getBusyDbConnections() ) {
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
		} finally {
			$recursionGuard = false;
		}
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
	 * Clear all pending updates without performing them. Generally, you don't
	 * want or need to call this. Unit tests need it though.
	 */
	public static function clearPendingUpdates() {
		self::$preSendUpdates = [];
		self::$postSendUpdates = [];
	}

	/**
	 * Set the rollback/commit watcher on a DB to trigger update runs when safe
	 *
	 * @param IDatabase $dbw
	 * @since 1.28
	 */
	public static function installDBListener( IDatabase $dbw ) {
		static $triggers = [ IDatabase::TRIGGER_COMMIT, IDatabase::TRIGGER_ROLLBACK ];
		// Hook into active master connections to find a moment where no writes are pending
		$dbw->setTransactionListener(
			__METHOD__,
			function ( $trigger, IDatabase $conn ) use ( $triggers ) {
				global $wgCommandLineMode;

				if ( $wgCommandLineMode && in_array( $trigger, $triggers ) ) {
					DeferredUpdates::tryOpportunisticExecute();
				}
			}
		);
	}
}
