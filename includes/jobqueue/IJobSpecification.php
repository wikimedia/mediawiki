<?php
/**
 * Job queue task description base code.
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
 * Job queue task description interface
 *
 * @ingroup JobQueue
 * @since 1.23
 */
interface IJobSpecification {
	/**
	 * @return string Job type
	 */
	public function getType();

	/**
	 * @return array
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

	/**
	 * @return Title Descriptive title (this can simply be informative)
	 */
	public function getTitle();
}
