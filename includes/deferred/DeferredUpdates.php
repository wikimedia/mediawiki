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
 * In CLI mode, updates are only deferred until the current wiki has no DB write transaction
 * active within this request.
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

	const ALL = 0; // all updates
	const PRESEND = 1; // for updates that should run before flushing output buffer
	const POSTSEND = 2; // for updates that should run after flushing output buffer

	/**
	 * Add an update to the deferred list
	 *
	 * @param DeferrableUpdate $update Some object that implements doUpdate()
	 * @param integer $type DeferredUpdates constant (PRESEND or POSTSEND) (since 1.27)
	 */
	public static function addUpdate( DeferrableUpdate $update, $type = self::POSTSEND ) {
		if ( $type === self::PRESEND ) {
			self::push( self::$preSendUpdates, $update );
		} else {
			self::push( self::$postSendUpdates, $update );
		}
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
			self::execute( self::$preSendUpdates, $mode );
		}

		if ( $type === self::ALL || $type == self::POSTSEND ) {
			self::execute( self::$postSendUpdates, $mode );
		}
	}

	private static function push( array &$queue, DeferrableUpdate $update ) {
		global $wgCommandLineMode;

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

		// CLI scripts may forget to periodically flush these updates,
		// so try to handle that rather than OOMing and losing them entirely.
		// Try to run the updates as soon as there is no current wiki transaction.
		static $waitingOnTrx = false; // de-duplicate callback
		if ( $wgCommandLineMode && !$waitingOnTrx ) {
			$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
			$dbw = $lb->getAnyOpenConnection( $lb->getWriterIndex() );
			// Do the update as soon as there is no transaction
			if ( $dbw && $dbw->trxLevel() ) {
				$waitingOnTrx = true;
				$dbw->onTransactionIdle( function() use ( &$waitingOnTrx ) {
					DeferredUpdates::doUpdates();
					$waitingOnTrx = false;
				} );
			} else {
				self::doUpdates();
			}
		}
	}

	/**
	 * @param DeferrableUpdate[] &$queue List of DeferrableUpdate objects
	 * @param string $mode Use "enqueue" to use the job queue when possible [Default: run]
	 * @throws ErrorPageError
	 */
	public static function execute( array &$queue, $mode ) {
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
				// When possible, push updates as jobs instead of calling doUpdate()
				try {
					// Push enqueuable updates to the job queue and get the rest
					$updates = self::enqueueUpdates( $updates );
					$lbFactory->commitMasterChanges( __METHOD__ ); // in case of DB queue
				} catch ( Exception $e ) {
					// Let other updates have a chance to run if this failed
					MWExceptionHandler::rollbackMasterChangesAndLog( $e );
				}
			}

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
					foreach ( $trxDataUpdates as $trxUpdate ) {
						/** @var $trxUpdate DataUpdate */
						$trxUpdate->doUpdate();
					}
					$lbFactory->commitMasterChanges( __METHOD__ );
				} catch ( Exception $e ) {
					// Let other updates have a chance to run if this failed
					MWExceptionHandler::rollbackMasterChangesAndLog( $e );
				}
			}

			// Execute the non-transactional DataUpdate and non-DataUpdate tasks...
			foreach ( $otherUpdates as $update ) {
				/** @var $update DeferrableUpdate */
				try {
					$update->doUpdate();
					$lbFactory->commitMasterChanges( __METHOD__ );
				} catch ( ErrorPageError $e ) {
					// Let other updates have a chance to run if this failed
					if ( $mode === self::PRESEND ) {
						$reportError = $reportError ?: $e;
					} else {
						// Reporting GUI exceptions does not work post-send
						MWExceptionHandler::logException( $e );
					}
					$lbFactory->commitMasterChanges( __METHOD__ );
				} catch ( Exception $e ) {
					// Let other updates have a chance to run if this failed
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
	 * Clear all pending updates without performing them. Generally, you don't
	 * want or need to call this. Unit tests need it though.
	 */
	public static function clearPendingUpdates() {
		self::$preSendUpdates = [];
		self::$postSendUpdates = [];
	}
}
