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

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\ScopedCallback;

/**
 * Class for managing the deferral of updates within the scope of a PHP script invocation
 *
 * In web request mode, deferred updates run at the end of request execution, after the main
 * database transaction round ends, and either before (PRESEND) or after (POSTSEND) the HTTP
 * response has been sent. If an update runs after the HTTP response is sent, it will not block
 * clients. Otherwise, the client will not see the response until the update finishes. Use the
 * PRESEND and POSTSEND class constants to specify when an update should run. POSTSEND is the
 * default for DeferredUpdates::addUpdate() and DeferredUpdates::addCallableUpdate(). An update
 * that might need to alter the HTTP response output must use PRESEND. The control flow with
 * regard to deferred updates during a typical state changing web request is as follows:
 *   - 1) Main transaction round starts
 *   - 2) Various writes to RBMS/file/blob stores and deferred updates enqueued
 *   - 3) Main transaction round ends
 *   - 4) PRESEND pending update queue is B1...BN
 *   - 5) B1 runs, resulting PRESEND updates iteratively run in FIFO order; likewise for B2..BN
 *   - 6) The web response is sent out to the client
 *   - 7) POSTSEND pending update queue is A1...AM
 *   - 8) A1 runs, resulting updates iteratively run in FIFO order; likewise for A2..AM
 *
 * @see MediaWiki::restInPeace()
 *
 * In CLI mode, no distinction is made between PRESEND and POSTSEND deferred updates and all of
 * them will run during the following occasions:
 *   - a) During DeferredUpdates::addUpdate() if no LBFactory DB handles have writes pending
 *   - b) On commit of an LBFactory DB handle if no other such handles have writes pending
 *   - c) During an LBFactory::waitForReplication call if no LBFactory DBs have writes pending
 *   - d) When the queue is large and an LBFactory DB handle commits (EnqueueableDataUpdate only)
 *   - e) Upon the completion of Maintenance::execute() via Maintenance::shutdown()
 *
 * @see MWLBFactory::applyGlobalState()
 *
 * If DeferredUpdates::doUpdates() is currently running a deferred update, then the public
 * DeferredUpdates interface operates on the PRESEND/POSTSEND "sub"-queues that correspond to
 * the innermost in-progress deferred update. Otherwise, the public interface operates on the
 * PRESEND/POSTSEND "top"-queues. Affected methods include:
 *   - DeferredUpdates::addUpdate()
 *   - DeferredUpdates::addCallableUpdate()
 *   - DeferredUpdates::doUpdates()
 *   - DeferredUpdates::tryOpportunisticExecute()
 *   - DeferredUpdates::pendingUpdatesCount()
 *   - DeferredUpdates::getPendingUpdates()
 *   - DeferredUpdates::clearPendingUpdates()
 *
 * Updates that work through this system will be more likely to complete by the time the
 * client makes their next request after this request than with the JobQueue system.
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
		// Migrating to DeferredUpdatesManager service
		MediaWikiServices::getInstance()
			->getDeferredUpdatesManager()
			->addUpdate( $update, $stage );
	}

	/**
	 * Add an update to the pending update queue that invokes the specified callback when run
	 *
	 * @see DeferredUpdates::addUpdate()
	 * @see MWCallableUpdate::__construct()
	 *
	 * @param callable $callable
	 * @param int $stage One of (DeferredUpdates::PRESEND, DeferredUpdates::POSTSEND)
	 * @param IDatabase|IDatabase[]|null $dbw Abort if this DB is rolled back [optional]
	 * @since 1.27 Added $stage parameter
	 * @since 1.28 Added the $dbw parameter
	 */
	public static function addCallableUpdate( $callable, $stage = self::POSTSEND, $dbw = null ) {
		// Migrating to DeferredUpdatesManager service. Cannot just call the corresponding
		// DeferredUpdatesManager::addCallableUpdate() because that would have a different
		// result for wfGetCaller(), so we duplicate the logic for creating the MWCallableUpdate
		MediaWikiServices::getInstance()
			->getDeferredUpdatesManager()
			->addUpdate( new MWCallableUpdate( $callable, wfGetCaller(), $dbw ), $stage );
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
		// Migrating to DeferredUpdatesManager service
		MediaWikiServices::getInstance()
			->getDeferredUpdatesManager()
			->doUpdates( $stage );
	}

	/**
	 * Consume and execute all pending updates unless an update is already
	 * in progress or the ILBFactory service instance has "busy" DB handles
	 *
	 * A DB handle is considered "busy" if it has an unfinished transaction that cannot safely
	 * be flushed or the parent ILBFactory instance has an unfinished transaction round that
	 * cannot safely be flushed. If the number of pending updates reaches BIG_QUEUE_SIZE and
	 * there are still busy DB handles, then EnqueueableDataUpdate updates might be enqueued
	 * as jobs. This avoids excessive memory use and risk of losing updates due to failures.
	 *
	 * Note that this method operates on updates from all stages and thus should not be called
	 * during web requests. It is only intended for long-running Maintenance scripts.
	 *
	 * @internal For use by Maintenance
	 * @since 1.28
	 * @return bool Whether updates were allowed to run
	 */
	public static function tryOpportunisticExecute(): bool {
		// Migrating to DeferredUpdatesManager service
		return MediaWikiServices::getInstance()
			->getDeferredUpdatesManager()
			->tryOpportunisticExecute();
	}

	/**
	 * Prevent opportunistic updates until the returned ScopedCallback is
	 * consumed.
	 *
	 * @return ScopedCallback
	 */
	public static function preventOpportunisticUpdates() {
		// Migrating to DeferredUpdatesManager service
		return MediaWikiServices::getInstance()
			->getDeferredUpdatesManager()
			->preventOpportunisticUpdates();
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
		// Migrating to DeferredUpdatesManager service
		return MediaWikiServices::getInstance()
			->getDeferredUpdatesManager()
			->pendingUpdatesCount();
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
		// Migrating to DeferredUpdatesManager service
		return MediaWikiServices::getInstance()
			->getDeferredUpdatesManager()
			->getPendingUpdates( $stage );
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
		// Migrating to DeferredUpdatesManager service
		MediaWikiServices::getInstance()
			->getDeferredUpdatesManager()
			->clearPendingUpdates();
	}

	/**
	 * Get the number of in-progress calls to DeferredUpdates::doUpdates()
	 *
	 * @return int
	 * @internal This method should only be used for unit tests
	 */
	public static function getRecursiveExecutionStackDepth() {
		// Migrating to DeferredUpdatesManager service
		return MediaWikiServices::getInstance()
			->getDeferredUpdatesManager()
			->getRecursiveExecutionStackDepth();
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
		// Migrating to DeferredUpdatesManager service
		MediaWikiServices::getInstance()
			->getDeferredUpdatesManager()
			->attemptUpdate( $update, $lbFactory );
	}

}
