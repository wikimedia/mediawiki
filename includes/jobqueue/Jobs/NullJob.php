<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\JobQueue\Jobs;

use MediaWiki\JobQueue\GenericParameterJob;
use MediaWiki\JobQueue\Job;
use MediaWiki\MediaWikiServices;

/**
 * No-op job that does nothing.
 *
 * This is used for testing purposes, e.g. to measure overall system
 * performance of the JobQueue system, lock contention, etc.
 *
 * This job can optionally recursively re-queue itself a number of times
 * or spend a fixed amount of time idling in execution time.
 *
 * @par Example:
 * Inserting a null job in the configured job queue:
 * @code
 * $ php maintenance/eval.php
 * > $queue = MediaWikiServices::getInstance()->getJobQueueGroup();
 * > $job = new NullJob( [ 'lives' => 10 ] );
 * > $queue->push( $job );
 * @endcode
 *
 * You can confirm the job has been enqueued via maintenance/showJobs.php:
 *
 * @code
 * $ php maintenance/showJobs.php --group
 * null: 1 queue; 0 claimed (0 active, 0 abandoned)
 * @endcode
 *
 * @ingroup JobQueue
 */
class NullJob extends Job implements GenericParameterJob {
	/**
	 * @param array $params Job parameters (lives, usleep)
	 */
	public function __construct( array $params ) {
		parent::__construct( 'null', $params );
		if ( !isset( $this->params['lives'] ) ) {
			$this->params['lives'] = 1;
		}
		if ( !isset( $this->params['usleep'] ) ) {
			$this->params['usleep'] = 0;
		}
		$this->removeDuplicates = !empty( $this->params['removeDuplicates'] );
	}

	/** @inheritDoc */
	public function run() {
		if ( $this->params['usleep'] > 0 ) {
			usleep( $this->params['usleep'] );
		}
		if ( $this->params['lives'] > 1 ) {
			$params = $this->params;
			$params['lives']--;
			$job = new self( $params );
			MediaWikiServices::getInstance()->getJobQueueGroup()->push( $job );
		}

		return true;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( NullJob::class, 'NullJob' );
