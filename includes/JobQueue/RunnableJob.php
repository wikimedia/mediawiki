<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\JobQueue;

/**
 * Job that has a run() method and metadata accessors for JobQueue::pop() and JobQueue::ack().
 *
 * Instances are not only enqueueable via JobQueue::push(), but they can also be executed by
 * calling their run() method. When constructing a job to be enqueued via JobQueue::push(), it
 * will not be possible to construct a RunnableJob instance if the class for that job is not
 * loaded by the application for the local DB domain. In that case, the general-purpose
 * JobSpecification class can be used instead.
 *
 * See [the architecture doc](@ref jobqueuearch) for more information.
 *
 * @stable to implement
 * @since 1.33
 * @ingroup JobQueue
 */
interface RunnableJob extends IJobSpecification {
	/** @var int Job must not be wrapped in the usual explicit LBFactory transaction round */
	public const JOB_NO_EXPLICIT_TRX_ROUND = 1;

	/**
	 * Run the job.
	 *
	 * If this method returns `false` or completes exceptionally, the job runner will retry executing this
	 * job unless the number of retries has exceeded its configured retry limit.
	 * Retries are allowed by default, unless allowRetries() is overridden to disable retries.
	 *
	 * See [the architecture doc](@ref jobqueuearch) for more information.
	 *
	 * @return bool Return `false` to instruct the job runner to retry a failed job.
	 * Otherwise return `true` to indicate that a job completed
	 * (i.e. succeeded, or failed in a way that's deterministic or redundant).
	 */
	public function run();

	/**
	 * @param string|null $field Metadata field or null to get all the metadata
	 * @return mixed|null Value; null if missing
	 */
	public function getMetadata( $field = null );

	/**
	 * @param string $field Key name to set the value for
	 * @param mixed $value The value to set the field for
	 * @return mixed|null The prior field value; null if missing
	 */
	public function setMetadata( $field, $value );

	/**
	 * @param int $flag JOB_* class constant
	 * @return bool
	 * @since 1.31
	 */
	public function hasExecutionFlag( $flag );

	/**
	 * @return string|null Id of the request that created this job. Follows
	 *  jobs recursively, allowing to track the id of the request that started a
	 *  job when jobs insert jobs which insert other jobs.
	 * @since 1.27
	 */
	public function getRequestId();

	/**
	 * Whether to retry execution of this job if run() returned `false` or threw an exception.
	 *
	 * @warning In some setups (i.e. when using change-propagation) jobs may
	 *  still be retried even when this is false if the job fails due to a
	 *  timeout unless it is also configured in change-prop config (T358939).
	 * @return bool Whether this job can be retried on failure by job runners
	 * @since 1.21
	 */
	public function allowRetries();

	/**
	 * @return int Number of actually "work items" handled in this job
	 * @see $wgJobBackoffThrottling
	 * @since 1.23
	 */
	public function workItemCount();

	/**
	 * @return int|null UNIX timestamp of when the job was runnable, or null
	 * @since 1.26
	 */
	public function getReadyTimestamp();

	/**
	 * Do any final cleanup after run(), deferred updates, and all DB commits happen
	 * @param bool $status Whether the job, its deferred updates, and DB commit all succeeded
	 * @since 1.27
	 */
	public function tearDown( $status );

	/**
	 * @return string
	 */
	public function getLastError();

	/**
	 * @return string Debugging string describing the job
	 */
	public function toString();
}

/** @deprecated class alias since 1.44 */
class_alias( RunnableJob::class, 'RunnableJob' );
