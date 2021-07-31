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

namespace MediaWiki\Deferred;

use DataUpdate;
use DeferrableCallback;
use DeferrableUpdate;
use DeferredUpdatesScopeStack;
use EnqueueableDataUpdate;
use ErrorPageError;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use LogicException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\JobQueue\JobQueueGroupFactory;
use MWCallableUpdate;
use MWExceptionHandler;
use Psr\Log\LoggerInterface;
use RequestContext;
use Throwable;
use TransactionRoundAwareUpdate;
use Wikimedia\Rdbms\DBTransactionError;
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
 * default for DeferredUpdatesManager::addUpdate() and DeferredUpdatesManager::addCallableUpdate().
 * An update that might need to alter the HTTP response output must use PRESEND. The control flow
 * with regard to deferred updates during a typical state changing web request is as follows:
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
 *   - a) During DeferredUpdatesManager::addUpdate() if no LBFactory DB handles have writes pending
 *   - b) On commit of an LBFactory DB handle if no other such handles have writes pending
 *   - c) During an LBFactory::waitForReplication call if no LBFactory DBs have writes pending
 *   - d) When the queue is large and an LBFactory DB handle commits (EnqueueableDataUpdate only)
 *   - e) Upon the completion of Maintenance::execute() via Maintenance::shutdown()
 *
 * @see MWLBFactory::applyGlobalState()
 *
 * If DeferredUpdatesManager::doUpdates() is currently running a deferred update, then the public
 * DeferredUpdatesManager interface operates on the PRESEND/POSTSEND "sub"-queues that correspond to
 * the innermost in-progress deferred update. Otherwise, the public interface operates on the
 * PRESEND/POSTSEND "top"-queues. Affected methods include:
 *   - DeferredUpdatesManager::addUpdate()
 *   - DeferredUpdatesManager::addCallableUpdate()
 *   - DeferredUpdatesManager::doUpdates()
 *   - DeferredUpdatesManager::tryOpportunisticExecute()
 *   - DeferredUpdatesManager::pendingUpdatesCount()
 *   - DeferredUpdatesManager::getPendingUpdates()
 *   - DeferredUpdatesManager::clearPendingUpdates()
 *
 * Updates that work through this system will be more likely to complete by the time the
 * client makes their next request after this request than with the JobQueue system.
 *
 * @since 1.41 as a replacement for \DeferredUpdates
 */
class DeferredUpdatesManager {
	/** @var DeferredUpdatesScopeStack|null Queue states based on recursion level */
	private static $scopeStack;
	/**
	 * @var int Nesting level for preventOpportunisticUpdates()
	 */
	private $preventOpportunisticUpdates = 0;

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

	/** @internal For use by ServiceWiring */
	public const CONSTRUCTOR_OPTIONS = [
		'CommandLineMode',
	];

	/** @var ServiceOptions */
	private $options;

	/** @var LoggerInterface */
	private $logger;

	/** @var ILBFactory */
	private $lbFactory;

	/** @var StatsdDataFactoryInterface */
	private $stats;

	/** @var JobQueueGroupFactory */
	private $jobQueueGroupFactory;

	/**
	 * @param ServiceOptions $options
	 * @param LoggerInterface $logger
	 * @param ILBFactory $lbFactory
	 * @param StatsdDataFactoryInterface $stats
	 * @param JobQueueGroupFactory $jobQueueGroupFactory
	 */
	public function __construct(
		ServiceOptions $options,
		LoggerInterface $logger,
		ILBFactory $lbFactory,
		StatsdDataFactoryInterface $stats,
		JobQueueGroupFactory $jobQueueGroupFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->logger = $logger;
		$this->lbFactory = $lbFactory;
		$this->stats = $stats;
		$this->jobQueueGroupFactory = $jobQueueGroupFactory;
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
	 * @param int $stage One of (DeferredUpdatesManager::PRESEND, ::POSTSEND)
	 */
	public function addUpdate( DeferrableUpdate $update, $stage = self::POSTSEND ) {
		$this->getScopeStack()->current()->addUpdate( $update, $stage );
		// If CLI mode is active and no RDBMs transaction round is in the way, then run all
		// the pending updates now. This is needed for scripts that never, or rarely, use the
		// RDBMs layer, but that do modify systems via deferred updates. This logic avoids
		// excessive pending update queue sizes when long-running scripts never trigger the
		// basic RDBMs hooks for running pending updates.
		if ( $this->options->get( 'CommandLineMode' ) ) {
			$this->tryOpportunisticExecute();
		}
	}

	/**
	 * Add an update to the pending update queue that invokes the specified callback when run
	 *
	 * @see DeferredUpdatesManager::addUpdate()
	 * @see MWCallableUpdate::__construct()
	 *
	 * @param callable $callable
	 * @param int $stage One of (DeferredUpdatesManager::PRESEND, ::POSTSEND)
	 * @param IDatabase|IDatabase[]|null $dbw Abort if this DB is rolled back [optional]
	 */
	public function addCallableUpdate( $callable, $stage = self::POSTSEND, $dbw = null ) {
		$this->addUpdate( new MWCallableUpdate( $callable, wfGetCaller(), $dbw ), $stage );
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
	 *  (DeferredUpdatesManager::PRESEND, ::POSTSEND, ::ALL)
	 */
	public function doUpdates( $stage = self::ALL ) {
		$httpMethod = $this->options->get( 'CommandLineMode' )
			? 'cli'
			: strtolower( RequestContext::getMain()->getRequest()->getMethod() );

		/** @var ErrorPageError $guiError First presentable client-level error thrown */
		$guiError = null;
		/** @var Throwable $exception First of any error thrown */
		$exception = null;

		$scope = $this->getScopeStack()->current();

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
			function ( DeferrableUpdate $update, $activeStage )
				use ( $httpMethod, &$guiError, &$exception )
			{
				$scopeStack = $this->getScopeStack();
				$childScope = $scopeStack->descend( $activeStage, $update );
				try {
					$e = $this->run( $update, $httpMethod );
					$guiError = $guiError ?: ( $e instanceof ErrorPageError ? $e : null );
					$exception = $exception ?: $e;
					// Any addUpdate() calls between descend() and ascend() used the sub-queue.
					// In rare cases, DeferrableUpdate::doUpdates() will process them by calling
					// doUpdates() itself. In any case, process remaining updates in the subqueue.
					// them, enqueueing them, or transferring them to the parent scope
					// queues as appropriate...
					$childScope->processUpdates(
						$activeStage,
						function ( DeferrableUpdate $subUpdate )
							use ( $httpMethod, &$guiError, &$exception )
						{
							$e = $this->run( $subUpdate, $httpMethod );
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
	 * @return bool Whether updates were allowed to run
	 */
	public function tryOpportunisticExecute(): bool {
		// Leave execution up to the current loop if an update is already in progress
		// or if updates are explicitly disabled
		if ( self::getRecursiveExecutionStackDepth()
			|| $this->preventOpportunisticUpdates
		) {
			return false;
		}

		// Run the updates for this context if they will have outer transaction scope
		if ( !$this->areDatabaseTransactionsActive() ) {
			$this->doUpdates( self::ALL );

			return true;
		}

		if ( $this->pendingUpdatesCount() >= self::BIG_QUEUE_SIZE ) {
			// There are a large number of pending updates and none of them can run yet.
			// The odds of losing updates due to an error increase when executing long queues
			// and when large amounts of time pass while tasks are queued. Mitigate this by
			// trying to migrate updates to the job queue system (where applicable).
			$this->getScopeStack()->current()->consumeMatchingUpdates(
				self::ALL,
				EnqueueableDataUpdate::class,
				function ( EnqueueableDataUpdate $update ) {
					$spec = $update->getAsJobSpecification();
					$this->jobQueueGroupFactory
						->makeJobQueueGroup( $spec['domain'] )->push( $spec['job'] );
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
	public function preventOpportunisticUpdates() {
		$this->preventOpportunisticUpdates++;
		return new ScopedCallback( function () {
			$this->preventOpportunisticUpdates--;
		} );
	}

	/**
	 * Get the number of pending updates for the current execution context
	 *
	 * If an update is in progress, then this operates on the sub-queues of the
	 * innermost in-progress update. Otherwise, it acts on the top-queues.
	 *
	 * @return int
	 */
	public function pendingUpdatesCount() {
		return $this->getScopeStack()->current()->pendingUpdatesCount();
	}

	/**
	 * Get a list of the pending updates for the current execution context
	 *
	 * If an update is in progress, then this operates on the sub-queues of the
	 * innermost in-progress update. Otherwise, it acts on the top-queues.
	 *
	 * @param int $stage Look for updates with this "defer until" stage. One of
	 *  (DeferredUpdatesManager::PRESEND, ::POSTSEND, ::ALL)
	 * @return DeferrableUpdate[]
	 * @internal This method should only be used for unit tests
	 */
	public function getPendingUpdates( $stage = self::ALL ) {
		return $this->getScopeStack()->current()->getPendingUpdates( $stage );
	}

	/**
	 * Cancel all pending updates for the current execution context
	 *
	 * If an update is in progress, then this operates on the sub-queues of the
	 * innermost in-progress update. Otherwise, it acts on the top-queues.
	 *
	 * @internal This method should only be used for unit tests
	 */
	public function clearPendingUpdates() {
		$this->getScopeStack()->current()->clearPendingUpdates();
	}

	/**
	 * Get the number of in-progress calls to DeferredUpdatesManager::doUpdates()
	 *
	 * @return int
	 * @internal This method should only be used for unit tests
	 */
	public function getRecursiveExecutionStackDepth() {
		return $this->getScopeStack()->getRecursiveDepth();
	}

	/**
	 * Run an update, and, if an error was thrown, catch/log it and enqueue the update as
	 * a job in the job queue system if possible (e.g. implements EnqueueableDataUpdate)
	 *
	 * @param DeferrableUpdate $update
	 * @param string $httpMethod
	 * @return Throwable|null
	 */
	private function run(
		DeferrableUpdate $update,
		$httpMethod
	): ?Throwable {
		$suffix = $update instanceof DeferrableCallback ? '_' . $update->getOrigin() : '';
		$type = get_class( $update ) . $suffix;
		$this->stats->increment( "deferred_updates.$httpMethod.$type" );

		$updateId = spl_object_id( $update );
		$this->logger->debug( __METHOD__ . ": started $type #$updateId" );

		$updateException = null;

		$startTime = microtime( true );
		try {
			$this->attemptUpdate( $update, $this->lbFactory );
		} catch ( Throwable $updateException ) {
			MWExceptionHandler::logException( $updateException );
			$this->logger->error(
				"Deferred update '{deferred_type}' failed to run.",
				[
					'deferred_type' => $type,
					'exception' => $updateException,
				]
			);
			$this->lbFactory->rollbackPrimaryChanges( __METHOD__ );
		} finally {
			$walltime = microtime( true ) - $startTime;
			$this->logger->debug( __METHOD__ . ": ended $type #$updateId, processing time: $walltime" );
		}

		// Try to push the update as a job so it can run later if possible
		if ( $updateException && $update instanceof EnqueueableDataUpdate ) {
			try {
				$spec = $update->getAsJobSpecification();
				$this->jobQueueGroupFactory->makeJobQueueGroup( $spec['domain'] )->push( $spec['job'] );
			} catch ( Throwable $jobException ) {
				MWExceptionHandler::logException( $jobException );
				$this->logger->error(
					"Deferred update '{deferred_type}' failed to enqueue as a job.",
					[
						'deferred_type' => $type,
						'exception' => $jobException,
					]
				);
				$this->lbFactory->rollbackPrimaryChanges( __METHOD__ );
			}
		}

		return $updateException;
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
	 */
	public function attemptUpdate( DeferrableUpdate $update, ILBFactory $lbFactory ) {
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
			$lbFactory->beginPrimaryChanges( $fnameTrxOwner ); // new explicit round
		} else {
			$lbFactory->commitPrimaryChanges( $fnameTrxOwner ); // new implicit round
		}
		// Run the update after any stale primary DB view snapshots have been flushed
		$update->doUpdate();
		// Commit any pending changes from the explicit or implicit transaction round
		$lbFactory->commitPrimaryChanges( $fnameTrxOwner );
	}

	/**
	 * @return bool If a transaction round is active or connection is not ready for commit()
	 */
	private function areDatabaseTransactionsActive() {
		if ( $this->lbFactory->hasTransactionRound()
			|| !$this->lbFactory->isReadyForRoundOperations()
		) {
			return true;
		}

		foreach ( $this->lbFactory->getAllLBs() as $lb ) {
			if ( $lb->hasPrimaryChanges() || $lb->explicitTrxActive() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return DeferredUpdatesScopeStack
	 */
	private function getScopeStack() {
		// We keep the scope stack static just in case there are multiple instances
		// of DeferredUpdatesManager created, to make sure no updates get missed.
		if ( self::$scopeStack === null ) {
			self::$scopeStack = new DeferredUpdatesScopeStack();
		}

		return self::$scopeStack;
	}
}
