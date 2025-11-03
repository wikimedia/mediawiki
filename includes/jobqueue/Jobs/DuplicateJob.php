<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\JobQueue\Jobs;

use MediaWiki\JobQueue\GenericParameterJob;
use MediaWiki\JobQueue\Job;
use MediaWiki\JobQueue\RunnableJob;

/**
 * No-op job that does nothing.
 *
 * This is used by JobQueue::pop to temporarily represent duplicates.
 *
 * @internal
 * @ingroup JobQueue
 */
final class DuplicateJob extends Job implements GenericParameterJob {
	/**
	 * Callers should use DuplicateJob::newFromJob() instead
	 *
	 * @param array $params Job parameters
	 */
	public function __construct( array $params ) {
		parent::__construct( 'duplicate', $params );
	}

	/**
	 * Get a duplicate no-op version of a job
	 *
	 * @param RunnableJob $job
	 * @return Job
	 */
	public static function newFromJob( RunnableJob $job ) {
		$djob = new self( $job->getParams() );
		$djob->command = $job->getType();
		$djob->params = is_array( $djob->params ) ? $djob->params : [];
		$djob->params = [ 'isDuplicate' => true ] + $djob->params;
		$djob->metadata = $job->getMetadata();

		return $djob;
	}

	/** @inheritDoc */
	public function run() {
		return true;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( DuplicateJob::class, 'DuplicateJob' );
