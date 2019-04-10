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
 * @ingroup JobQueue
 * @since 1.33
 */
interface RunnableJob extends IJobSpecification {
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
}
