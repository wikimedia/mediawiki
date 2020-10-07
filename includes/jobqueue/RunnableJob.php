<?php
/**
 * Job queue task instance that can be executed via a run() method
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

/**
 * Job that has a run() method and metadata accessors for JobQueue::pop() and JobQueue::ack()
 *
 * Instances are not only enqueueable via JobQueue::push(), but they can also be executed by
 * by calling their run() method. When constructing a job to be enqueued via JobQueue::push(),
 * it will not be possible to construct a RunnableJob instance if the class for that job is not
 * loaded by the application for the local DB domain. In that case, the general-purpose
 * JobSpecification class can be used instead.
 *
 * @stable to implement
 *
 * @ingroup JobQueue
 * @since 1.33
 */
interface RunnableJob extends IJobSpecification {
	/** @var int Job must not be wrapped in the usual explicit LBFactory transaction round */
	public const JOB_NO_EXPLICIT_TRX_ROUND = 1;

	/**
	 * Run the job
	 * @return bool Success
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
