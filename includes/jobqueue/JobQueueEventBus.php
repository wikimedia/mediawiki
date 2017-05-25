<?php

class JobQueueMemory extends JobQueue {

	/**
	 * Get the allowed queue orders for configuration validation
	 *
	 * @return array Subset of (random, timestamp, fifo, undefined)
	 */
	protected function supportedOrders() {
		return [ 'fifo' ];
	}

	/**
	 * Get the default queue order to use if configuration does not specify one
	 *
	 * @return string One of (random, timestamp, fifo, undefined)
	 */
	protected function optimalOrder() {
		return [ 'fifo' ];
	}

	/**
	 * @see JobQueue::isEmpty()
	 * @return bool
	 */
	protected function doIsEmpty() {
		z
	}

	/**
	 * @see JobQueue::getSize()
	 * @return int
	 */
	protected function doGetSize() {
		// TODO: Implement doGetSize() method.
	}

	/**
	 * @see JobQueue::getAcquiredCount()
	 * @return int
	 */
	protected function doGetAcquiredCount() {
		// TODO: Implement doGetAcquiredCount() method.
	}

	/**
	 * @see JobQueue::batchPush()
	 * @param IJobSpecification[] $jobs
	 * @param int $flags
	 */
	protected function doBatchPush( array $jobs, $flags ) {
		wfDebug('JOOOOOOOOOOOOOOB' . json_encode( $jobs ) );
	}

	/**
	 * @see JobQueue::pop()
	 * @return Job|bool
	 */
	protected function doPop() {
		// TODO: Implement doPop() method.
	}

	/**
	 * @see JobQueue::ack()
	 * @param Job $job
	 */
	protected function doAck( Job $job ) {
		// TODO: Implement doAck() method.
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
		// TODO: Implement getAllQueuedJobs() method.
	}
}