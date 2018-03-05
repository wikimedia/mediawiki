<?php

/**
 * A wrapper for the JobQueue that delegates all the method calls to a single,
 * main queue, and also pushes all the jobs to a second job queue that's being
 * debugged.
 *
 * This class was temporary added to test transitioning to the JobQueueEventBus
 * and will removed after the transition is completed. This code is only needed
 * while we are testing the new infrastructure to be able to compare the results
 * between the queue implementations and make sure that they process the same jobs,
 * deduplicate correctly, compare the delays, backlogs and make sure no jobs are lost.
 * When the new infrastructure is well tested this will not be needed any more.
 *
 * @deprecated since 1.30
 * @since 1.30
 */
class JobQueueSecondTestQueue extends JobQueue {

	/**
	 * @var JobQueue
	 */
	private $mainQueue;

	/**
	 * @var JobQueue
	 */
	private $debugQueue;

	/**
	 * @var bool
	 */
	private $onlyWriteToDebugQueue;

	protected function __construct( array $params ) {
		if ( !isset( $params['mainqueue'] ) ) {
			throw new MWException( "mainqueue parameter must be provided to the debug queue" );
		}

		if ( !isset( $params['debugqueue'] ) ) {
			throw new MWException( "debugqueue parameter must be provided to the debug queue" );
		}

		$conf = [ 'wiki' => $params['wiki'], 'type' => $params['type'] ];
		$this->mainQueue = JobQueue::factory( $params['mainqueue'] + $conf );
		$this->debugQueue = JobQueue::factory( $params['debugqueue'] + $conf );
		$this->onlyWriteToDebugQueue = isset( $params['readonly'] ) ? $params['readonly'] : false;

		// We need to construct parent after creating the main and debug queue
		// because super constructor calls some methods we delegate to the main queue.
		parent::__construct( $params );
	}

	/**
	 * Get the allowed queue orders for configuration validation
	 *
	 * @return array Subset of (random, timestamp, fifo, undefined)
	 */
	protected function supportedOrders() {
		return $this->mainQueue->supportedOrders();
	}

	/**
	 * Get the default queue order to use if configuration does not specify one
	 *
	 * @return string One of (random, timestamp, fifo, undefined)
	 */
	protected function optimalOrder() {
		return $this->mainQueue->optimalOrder();
	}

	/**
	 * Find out if delayed jobs are supported for configuration validation
	 *
	 * @return bool Whether delayed jobs are supported
	 */
	protected function supportsDelayedJobs() {
		return $this->mainQueue->supportsDelayedJobs();
	}

	/**
	 * @see JobQueue::isEmpty()
	 * @return bool
	 */
	protected function doIsEmpty() {
		return $this->mainQueue->doIsEmpty();
	}

	/**
	 * @see JobQueue::getSize()
	 * @return int
	 */
	protected function doGetSize() {
		return $this->mainQueue->doGetSize();
	}

	/**
	 * @see JobQueue::getAcquiredCount()
	 * @return int
	 */
	protected function doGetAcquiredCount() {
		return $this->mainQueue->doGetAcquiredCount();
	}

	/**
	 * @see JobQueue::getDelayedCount()
	 * @return int
	 */
	protected function doGetDelayedCount() {
		return $this->mainQueue->doGetDelayedCount();
	}

	/**
	 * @see JobQueue::getAbandonedCount()
	 * @return int
	 */
	protected function doGetAbandonedCount() {
		return $this->mainQueue->doGetAbandonedCount();
	}

	/**
	 * @see JobQueue::batchPush()
	 * @param IJobSpecification[] $jobs
	 * @param int $flags
	 */
	protected function doBatchPush( array $jobs, $flags ) {
		if ( !$this->onlyWriteToDebugQueue ) {
			$this->mainQueue->doBatchPush( $jobs, $flags );
		}

		try {
			$this->debugQueue->doBatchPush( $jobs, $flags );
		} catch ( Exception $exception ) {
			MWExceptionHandler::logException( $exception );
		}
	}

	/**
	 * @see JobQueue::pop()
	 * @return Job|bool
	 */
	protected function doPop() {
		return $this->mainQueue->doPop();
	}

	/**
	 * @see JobQueue::ack()
	 * @param Job $job
	 * @return Job|bool
	 */
	protected function doAck( Job $job ) {
		return $this->mainQueue->doAck( $job );
	}

	/**
	 * @see JobQueue::deduplicateRootJob()
	 * @param IJobSpecification $job
	 * @throws MWException
	 * @return bool
	 */
	protected function doDeduplicateRootJob( IJobSpecification $job ) {
		return $this->mainQueue->doDeduplicateRootJob( $job );
	}

	/**
	 * @see JobQueue::isRootJobOldDuplicate()
	 * @param Job $job
	 * @return bool
	 */
	protected function doIsRootJobOldDuplicate( Job $job ) {
		return $this->mainQueue->doIsRootJobOldDuplicate( $job );
	}

	/**
	 * @param string $signature Hash identifier of the root job
	 * @return string
	 */
	protected function getRootJobCacheKey( $signature ) {
		return $this->mainQueue->getRootJobCacheKey( $signature );
	}

	/**
	 * @see JobQueue::delete()
	 * @return bool
	 * @throws MWException
	 */
	protected function doDelete() {
		return $this->mainQueue->doDelete();
	}

	/**
	 * @see JobQueue::waitForBackups()
	 * @return void
	 */
	protected function doWaitForBackups() {
		$this->mainQueue->doWaitForBackups();
	}

	/**
	 * @see JobQueue::flushCaches()
	 * @return void
	 */
	protected function doFlushCaches() {
		$this->mainQueue->doFlushCaches();
	}

	/**
	 * Get an iterator to traverse over all available jobs in this queue.
	 * This does not include jobs that are currently acquired or delayed.
	 * Note: results may be stale if the queue is concurrently modified.
	 *
	 * @return Iterator
	 * @throws JobQueueError
	 */
	public function getAllQueuedJobs() {
		return $this->mainQueue->getAllQueuedJobs();
	}

	/**
	 * Get an iterator to traverse over all delayed jobs in this queue.
	 * Note: results may be stale if the queue is concurrently modified.
	 *
	 * @return Iterator
	 * @throws JobQueueError
	 * @since 1.22
	 */
	public function getAllDelayedJobs() {
		return $this->mainQueue->getAllDelayedJobs();
	}

	/**
	 * Get an iterator to traverse over all claimed jobs in this queue
	 *
	 * Callers should be quick to iterator over it or few results
	 * will be returned due to jobs being acknowledged and deleted
	 *
	 * @return Iterator
	 * @throws JobQueueError
	 * @since 1.26
	 */
	public function getAllAcquiredJobs() {
		return $this->mainQueue->getAllAcquiredJobs();
	}

	/**
	 * Get an iterator to traverse over all abandoned jobs in this queue
	 *
	 * @return Iterator
	 * @throws JobQueueError
	 * @since 1.25
	 */
	public function getAllAbandonedJobs() {
		return $this->mainQueue->getAllAbandonedJobs();
	}

	/**
	 * Do not use this function outside of JobQueue/JobQueueGroup
	 *
	 * @return string
	 * @since 1.22
	 */
	public function getCoalesceLocationInternal() {
		return $this->mainQueue->getCoalesceLocationInternal();
	}

	/**
	 * @see JobQueue::getSiblingQueuesWithJobs()
	 * @param array $types List of queues types
	 * @return array|null (list of queue types) or null if unsupported
	 */
	protected function doGetSiblingQueuesWithJobs( array $types ) {
		return $this->mainQueue->doGetSiblingQueuesWithJobs( $types );
	}

	/**
	 * @see JobQueue::getSiblingQueuesSize()
	 * @param array $types List of queues types
	 * @return array|null (list of queue types) or null if unsupported
	 */
	protected function doGetSiblingQueueSizes( array $types ) {
		return $this->mainQueue->doGetSiblingQueueSizes( $types );
	}

	/**
	 * @throws JobQueueReadOnlyError
	 */
	protected function assertNotReadOnly() {
		$this->mainQueue->assertNotReadOnly();
	}
}
