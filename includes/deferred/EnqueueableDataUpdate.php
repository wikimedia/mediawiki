<?php

namespace MediaWiki\Deferred;

/**
 * Interface that marks a DataUpdate as enqueuable via the JobQueue
 *
 * Such updates must be representable using IJobSpecification, so that
 * they can be serialized into jobs and enqueued for later execution
 *
 * @stable to implement
 *
 * @since 1.27
 */
interface EnqueueableDataUpdate {
	/**
	 * @return array (domain => DB domain ID, job => IJobSpecification)
	 * @phan-return array{domain: string, job: \IJobSpecification}
	 */
	public function getAsJobSpecification();
}

/** @deprecated class alias since 1.42 */
class_alias( EnqueueableDataUpdate::class, 'EnqueueableDataUpdate' );
