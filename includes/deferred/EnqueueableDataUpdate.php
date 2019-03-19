<?php
/**
 * Interface that marks a DataUpdate as enqueuable via the JobQueue
 *
 * Such updates must be representable using IJobSpecification, so that
 * they can be serialized into jobs and enqueued for later execution
 *
 * @since 1.27
 */
interface EnqueueableDataUpdate {
	/**
	 * @return array (domain => DB domain ID, job => IJobSpecification)
	 */
	public function getAsJobSpecification();
}
