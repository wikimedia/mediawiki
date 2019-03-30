<?php
/**
 * Job queue task description interface
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
 * Interface for serializable objects that describe a job queue task
 *
 * A job specification can be inserted into a queue via JobQueue::push().
 * The specification parameters should be JSON serializable (e.g. no PHP classes).
 * Whatever queue the job specification is pushed into is assumed to have job runners
 * that will eventually pop the job specification from the queue, construct a RunnableJob
 * instance from the specification, and then execute that instance via RunnableJob::run().
 *
 * @ingroup JobQueue
 * @since 1.23
 */
interface IJobSpecification {
	/**
	 * @return string Job type that defines what sort of changes this job makes
	 */
	public function getType();

	/**
	 * @return array Parameters that specify sources, targets, and options for execution
	 */
	public function getParams();

	/**
	 * @return int|null UNIX timestamp to delay running this job until, otherwise null
	 */
	public function getReleaseTimestamp();

	/**
	 * @return bool Whether only one of each identical set of jobs should be run
	 */
	public function ignoreDuplicates();

	/**
	 * Subclasses may need to override this to make duplication detection work.
	 * The resulting map conveys everything that makes the job unique. This is
	 * only checked if ignoreDuplicates() returns true, meaning that duplicate
	 * jobs are supposed to be ignored.
	 *
	 * @return array Map of key/values
	 */
	public function getDeduplicationInfo();

	/**
	 * @see JobQueue::deduplicateRootJob()
	 * @return array
	 * @since 1.26
	 */
	public function getRootJobParams();

	/**
	 * @see JobQueue::deduplicateRootJob()
	 * @return bool
	 * @since 1.22
	 */
	public function hasRootJobParams();

	/**
	 * @see JobQueue::deduplicateRootJob()
	 * @return bool Whether this is job is a root job
	 */
	public function isRootJob();
}
