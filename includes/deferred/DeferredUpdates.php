<?php
/**
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

use MediaWiki\Logger\LoggerFactory;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\ScopedCallback;

/**
 * Defer callable updates to run later in the PHP process
 *
 * This is a performance feature that enables MediaWiki to produce faster web responses.
 * It allows you to postpone non-blocking work (e.g. work that does not change the web
 * response) to after the HTTP response has been sent to the client (i.e. web browser).
 *
 * Once the response is finalized and sent to the browser, the webserver process stays
 * for a little while longer (detached from the web request) to run your POSTSEND tasks.
 *
 * There is also a PRESEND option, which runs your task right before the finalized response
 * is sent to the browser. This is for critical tasks that does need to block the response,
 * but where you'd like to benefit from other DeferredUpdates features. Such as:
 *
 * - MergeableUpdate: batch updates from different components without coupling
 *   or awareness of each other.
 * - Automatic cancellation: pass a IDatabase object (for any wiki or database) to
 *   DeferredUpdates::addCallableUpdate or AtomicSectionUpdate.
 * - Reducing lock contention: if the response is likely to take several seconds
 *   (e.g. uploading a large file to FileBackend, or saving an edit to a large article)
 *   much of that work may overlap with a database transaction that is staying open for
 *   the entire duration. By moving contentious writes out to a PRESEND update, these
 *   get their own transaction (after the main one is committed), which give up some
 *   atomicity for improved throughput.
 *
 * ## Expectation and comparison to job queue
 *
 * When scheduling a POSTSEND via the DeferredUpdates system you can generally expect
 * it to complete well before the client makes their next request. Updates runs directly after
 * the web response is sent, from the same process on the same server. This unlike the JobQueue,
 * where jobs may need to wait in line for some minutes or hours.
 *
 * If your update fails, this failure is not known to the client and gets no retry. For updates
 * that need re-tries for system consistency or data integrity, it is recommended to implement
 * it as a job instead and use JobQueueGroup::lazyPush. This has the caveat of being delayed
 * by default, the same as any other job.
 *
 * A hybrid solution is available via the EnqueueableDataUpdate interface. By implementing
 * this interface, you can queue your update via the DeferredUpdates first, and if it fails,
 * the system will automatically catch this and queue it as a job instead.
 *
 * ## How it works during web requests
 *
 * 1. Your request route is executed (e.g. Action or SpecialPage class, or API).
 * 2. Output is finalized and main database transaction is committed.
 * 3. PRESEND updates run via DeferredUpdates::doUpdates.
 * 5. The web response is sent to the browser.
 * 6. POSTSEND updates run via DeferredUpdates::doUpdates.
 *
 * @see MediaWiki::preOutputCommit
 * @see MediaWiki::restInPeace
 *
 * ## How it works for Maintenance scripts
 *
 * In CLI mode, no distinction is made between PRESEND and POSTSEND deferred updates,
 * and the queue is periodically executed throughout the process.
 *
 * @see DeferredUpdates::tryOpportunisticExecute
 *
 * ## How it works internally
 *
 * Each update is added via DeferredUpdates::addUpdate and stored in either the PRESEND or
 * POSTSEND queue. If an update gets queued while another update is already running, then
 * we store in a "sub"-queue associated with the current update. This allows nested updates
 * to be completed before other updates, which improves ordering for process caching.
 *
 * @since 1.19
 */
class DeferredUpdates {
	/** @var int Process all updates; in web requests, use only after flushing output buffer */
	public const ALL = 0;
	/** @var int Specify/process updates that should run before flushing output buffer */
	public const PRESEND = 1;
	/** @var int Specify/process updates that should run after flushing output buffer */
	public const POSTSEND = 2;

	/** @var int[] List of "defer until" queue stages that can be reached */
	public const STAGES = [ self::PRESEND, self::POSTSEND ];

	/** @var int Queue size threshold for converting updates into jobs */
	private const BIG_QUEUE_SIZE = 100;

	/** @var DeferredUpdatesScopeStack|null Queue states based on recursion level */
	private static $scopeStack;

	/**
	 * @var int Nesting level for preventOpportunisticUpdates()
	 */
	private static $preventOpportunisticUpdates = 0;

	/**
	 * @return DeferredUpdatesScopeStack
	 */
	private static function getScopeStack(): DeferredUpdatesScopeStack {
		self::$scopeStack ??= new DeferredUpdatesScopeMediaWikiStack();
		return self::$scopeStack;
	}

	/**
	 * @param DeferredUpdatesScopeStack $scopeStack
	 * @internal Only for use in tests.
	 */
	public static function setScopeStack( DeferredUpdatesScopeStack $scopeStack ): void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new LogicException( 'Cannot reconfigure DeferredUpdates outside tests' );
		}
		self::$scopeStack = $scopeStack;
	}

	/**
	 * Add an update to the pending update queue for execution at the appropriate time
	 *
	 * In CLI mode, callback magic will also be used to run updates when safe
	 *
	 * If an update is already in progress, then what happens to this update is as follows:
	 *  - If it has a "defer until" stage at/before the actual run stage of the innermost
	 *    in-progress update, then it will go into the sub-queue of that in-progress update.
	 *    As soon as that update completes, MergeableUpdate instances in its sub-queue will be
	 *    merged into the top-queues and the non-MergeableUpdate instances will be executed.
	 *    This is done to better isolate updates from the failures of other updates and reduce
	 *    the chance of race conditions caused by updates not fully seeing the intended changes
	 *    of previously enqueued and executed updates.
	 *  - If it has a "defer until" stage later than the actual run stage of the innermost
	 *    in-progress update, then it will go into the normal top-queue for that stage.
	 *
	 * @param DeferrableUpdate $update Some object that implements doUpdate()
	 * @param int $stage One of (DeferredUpdates::PRESEND, DeferredUpdates::POSTSEND)
	 * @since 1.28 Added the $stage parameter
	 */
	public static function addUpdate( DeferrableUpdate $update, $stage = self::POSTSEND ) {
		self::getScopeStack()->current()->addUpdate( $update, $stage );
		self::tryOpportunisticExecute();
	}

	/**
	 * Add an update to the pending update queue that invokes the specified callback when run
	 *
	 * @param callable $callable
	 * @param int $stage One of (DeferredUpdates::PRESEND, DeferredUpdates::POSTSEND)
	 * @param IDatabase|IDatabase[]|null $dbw Cancel the update if a DB transaction
	 *  is rolled back [optional]
	 * @since 1.27 Added $stage parameter
	 * @since 1.28 Added the $dbw parameter
	 */
	public static function addCallableUpdate( $callable, $stage = self::POSTSEND, $dbw = null ) {
		self::addUpdate( new MWCallableUpdate( $callable, wfGetCaller(), $dbw ), $stage );
	}

	/**
	 * Run an update, and, if an error was thrown, catch/log it and enqueue the update as
	 * a job in the job queue system if possible (e.g. implements EnqueueableDataUpdate)
	 *
	 * @param DeferrableUpdate $update
	 * @return Throwable|null
	 */
	private static function run( DeferrableUpdate $update ): ?Throwable {
		$logger = LoggerFactory::getInstance( 'DeferredUpdates' );

		$type = get_class( $update )
			. ( $update instanceof DeferrableCallback ? '_' . $update->getOrigin() : '' );
		$updateId = spl_object_id( $update );
		$logger->debug( __METHOD__ . ": started $type #$updateId" );

		$updateException = null;

		$startTime = microtime( true );
		try {
			self::attemptUpdate( $update );
		} catch ( Throwable $updateException ) {
			MWExceptionHandler::logException( $updateException );
			$logger->error(
				"Deferred update '{deferred_type}' failed to run.",
				[
					'deferred_type' => $type,
					'exception' => $updateException,
				]
			);
			self::getScopeStack()->onRunUpdateFailed( $update );
		} finally {
			$walltime = microtime( true ) - $startTime;
			$logger->debug( __METHOD__ . ": ended $type #$updateId, processing time: $walltime" );
		}

		// Try to push the update as a job so it can run later if possible
		if ( $updateException && $update instanceof EnqueueableDataUpdate ) {
			try {
				self::getScopeStack()->queueDataUpdate( $update );
			} catch ( Throwable $jobException ) {
				MWExceptionHandler::logException( $jobException );
				$logger->error(
					"Deferred update '{deferred_type}' failed to enqueue as a job.",
					[
						'deferred_type' => $type,
						'exception' => $jobException,
					]
				);
				self::getScopeStack()->onRunUpdateFailed( $update );
			}
		}

		return $updateException;
	}

	/**
	 * Consume and execute all pending updates
	 *
	 * Note that it is rarely the case that this method should be called outside of a few
	 * select entry points. For simplicity, that kind of recursion is discouraged. Recursion
	 * cannot happen if an explicit transaction round is active, which limits usage to updates
	 * with TRX_ROUND_ABSENT that do not leave open any transactions round of their own during
	 * the call to this method.
	 *
	 * In the less-common case of this being called within an in-progress DeferrableUpdate,
	 * this will not see any top-queue updates (since they were consumed and are being run
	 * inside an outer execution loop). In that case, it will instead operate on the sub-queue
	 * of the innermost in-progress update on the stack.
	 *
	 * @internal For use by MediaWiki, Maintenance, JobRunner, JobExecutor
	 * @param int $stage Which updates to process. One of
	 *  (DeferredUpdates::PRESEND, DeferredUpdates::POSTSEND, DeferredUpdates::ALL)
	 */
	public static function doUpdates( $stage = self::ALL ) {
		/** @var ErrorPageError $guiError First presentable client-level error thrown */
		$guiError = null;
		/** @var Throwable $exception First of any error thrown */
		$exception = null;

		$scope = self::getScopeStack()->current();

		// T249069: recursion is not possible once explicit transaction rounds are involved
		$activeUpdate = $scope->getActiveUpdate();
		if ( $activeUpdate ) {
			$class = get_class( $activeUpdate );
			if ( !( $activeUpdate instanceof TransactionRoundAwareUpdate ) ) {
				throw new LogicException(
					__METHOD__ . ": reached from $class, which is not TransactionRoundAwareUpdate"
				);
			}
			if ( $activeUpdate->getTransactionRoundRequirement() !== $activeUpdate::TRX_ROUND_ABSENT ) {
				throw new LogicException(
					__METHOD__ . ": reached from $class, which does not specify TRX_ROUND_ABSENT"
				);
			}
		}

		$scope->processUpdates(
			$stage,
			static function ( DeferrableUpdate $update, $activeStage ) use ( &$guiError, &$exception ) {
				$scopeStack = self::getScopeStack();
				$childScope = $scopeStack->descend( $activeStage, $update );
				try {
					$e = self::run( $update );
					$guiError = $guiError ?: ( $e instanceof ErrorPageError ? $e : null );
					$exception = $exception ?: $e;
					// Any addUpdate() calls between descend() and ascend() used the sub-queue.
					// In rare cases, DeferrableUpdate::doUpdates() will process them by calling
					// doUpdates() itself. In any case, process remaining updates in the subqueue.
					// them, enqueueing them, or transferring them to the parent scope
					// queues as appropriate...
					$childScope->processUpdates(
						$activeStage,
						static function ( DeferrableUpdate $sub ) use ( &$guiError, &$exception ) {
							$e = self::run( $sub );
							$guiError = $guiError ?: ( $e instanceof ErrorPageError ? $e : null );
							$exception = $exception ?: $e;
						}
					);
				} finally {
					$scopeStack->ascend();
				}
			}
		);

		// VW-style hack to work around T190178, so we can make sure
		// PageMetaDataUpdater doesn't throw exceptions.
		if ( $exception && defined( 'MW_PHPUNIT_TEST' ) ) {
			throw $exception;
		}

		// Throw the first of any GUI errors as long as the context is HTTP pre-send. However,
		// callers should check permissions *before* enqueueing updates. If the main transaction
		// round actions succeed but some deferred updates fail due to permissions errors then
		// there is a risk that some secondary data was not properly updated.
		if ( $guiError && $stage === self::PRESEND && !headers_sent() ) {
			throw $guiError;
		}
	}

	/**
	 * Consume and execute pending updates now if possible, instead of waiting.
	 *
	 * In web requests, updates are always deferred until the end of the request.
	 *
	 * In CLI mode, updates run earlier and more often. This is important for long-running
	 * Maintenance scripts that would otherwise grow an excessively large queue, which increases
	 * memory use, and risks losing all updates if the script ends early or crashes.
	 *
	 * The folllowing conditions are required for updates to run early in CLI mode:
	 *
	 * - No update is already in progress (ensure linear flow, recursion guard).
	 * - LBFactory indicates that we don't have any "busy" database connections, i.e.
	 *   there are no pending writes or otherwise active and uncommitted transactions,
	 *   except if the transaction is empty and merely used for primary DB read queries,
	 *   in which case the transaction (and its repeatable-read snapshot) can be safely flushed.
	 *
	 * How this works:
	 *
	 * - When a maintenance script commits a change or waits for replication, such as
	 *   via. IConnectionProvider::commitAndWaitForReplication, then ILBFactory calls
	 *   tryOpportunisticExecute(). This is injected via MWLBFactory::applyGlobalState.
	 *
	 * - For maintenance scripts that don't do much with the database, we also call
	 *   tryOpportunisticExecute() after every addUpdate() call.
	 *
	 * - Upon the completion of Maintenance::execute() via Maintenance::shutdown(),
	 *   any remaining updates are run.
	 *
	 * Note that this method runs both PRESEND and POSTSEND updates and thus should not be called
	 * during web requests. It is only intended for long-running Maintenance scripts.
	 *
	 * @internal For use by Maintenance
	 * @since 1.28
	 * @return bool Whether updates were allowed to run
	 */
	public static function tryOpportunisticExecute(): bool {
		// Leave execution up to the current loop if an update is already in progress
		// or if updates are explicitly disabled
		if ( self::getRecursiveExecutionStackDepth()
			|| self::$preventOpportunisticUpdates
		) {
			return false;
		}

		if ( self::getScopeStack()->allowOpportunisticUpdates() ) {
			self::doUpdates( self::ALL );
			return true;
		}

		if ( self::pendingUpdatesCount() >= self::BIG_QUEUE_SIZE ) {
			// There are a large number of pending updates and none of them can run yet.
			// The odds of losing updates due to an error increases when executing long queues
			// and when large amounts of time pass while tasks are queued. Mitigate this by
			// trying to eagerly move updates to the JobQueue when possible.
			//
			// TODO: Do we still need this now maintenance scripts automatically call
			// tryOpportunisticExecute from addUpdate, from every commit, and every
			// waitForReplication call?
			self::getScopeStack()->current()->consumeMatchingUpdates(
				self::ALL,
				EnqueueableDataUpdate::class,
				static function ( EnqueueableDataUpdate $update ) {
					self::getScopeStack()->queueDataUpdate( $update );
				}
			);
		}

		return false;
	}

	/**
	 * Prevent opportunistic updates until the returned ScopedCallback is
	 * consumed.
	 *
	 * @return ScopedCallback
	 */
	public static function preventOpportunisticUpdates() {
		self::$preventOpportunisticUpdates++;
		return new ScopedCallback( static function () {
			self::$preventOpportunisticUpdates--;
		} );
	}

	/**
	 * Get the number of pending updates for the current execution context
	 *
	 * If an update is in progress, then this operates on the sub-queues of the
	 * innermost in-progress update. Otherwise, it acts on the top-queues.
	 *
	 * @return int
	 * @since 1.28
	 */
	public static function pendingUpdatesCount() {
		return self::getScopeStack()->current()->pendingUpdatesCount();
	}

	/**
	 * Get a list of the pending updates for the current execution context
	 *
	 * If an update is in progress, then this operates on the sub-queues of the
	 * innermost in-progress update. Otherwise, it acts on the top-queues.
	 *
	 * @param int $stage Look for updates with this "defer until" stage. One of
	 *  (DeferredUpdates::PRESEND, DeferredUpdates::POSTSEND, DeferredUpdates::ALL)
	 * @return DeferrableUpdate[]
	 * @internal This method should only be used for unit tests
	 * @since 1.29
	 */
	public static function getPendingUpdates( $stage = self::ALL ) {
		return self::getScopeStack()->current()->getPendingUpdates( $stage );
	}

	/**
	 * Cancel all pending updates for the current execution context
	 *
	 * If an update is in progress, then this operates on the sub-queues of the
	 * innermost in-progress update. Otherwise, it acts on the top-queues.
	 *
	 * @internal This method should only be used for unit tests
	 */
	public static function clearPendingUpdates() {
		self::getScopeStack()->current()->clearPendingUpdates();
	}

	/**
	 * Get the number of in-progress calls to DeferredUpdates::doUpdates()
	 *
	 * @return int
	 * @internal This method should only be used for unit tests
	 */
	public static function getRecursiveExecutionStackDepth() {
		return self::getScopeStack()->getRecursiveDepth();
	}

	/**
	 * Attempt to run an update with the appropriate transaction round state if needed
	 *
	 * It is allowed for a DeferredUpdate to directly execute one or more other DeferredUpdate
	 * instances without queueing them by calling this method. In that case, the outer update
	 * must use TransactionRoundAwareUpdate::TRX_ROUND_ABSENT, e.g. by extending
	 * TransactionRoundDefiningUpdate, so that this method can give each update its own
	 * transaction round.
	 *
	 * @param DeferrableUpdate $update
	 * @since 1.34
	 */
	public static function attemptUpdate( DeferrableUpdate $update ) {
		self::getScopeStack()->onRunUpdateStart( $update );

		$update->doUpdate();

		self::getScopeStack()->onRunUpdateEnd( $update );
	}
}
