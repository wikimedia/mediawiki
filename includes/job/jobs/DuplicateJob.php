<?php
/**
 * No-op job that does nothing.
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
 * @ingroup Cache
 */

/**
 * No-op job that does nothing. Used to represent duplicates.
 *
 * @ingroup JobQueue
 */
final class DuplicateJob extends Job {
	/**
	 * Callers should use DuplicateJob::newFromJob() instead
	 *
	 * @param $title Title
	 * @param $params Array: job parameters
	 * @param $id Integer: job id
	 */
	function __construct( $title, $params, $id = 0 ) {
		parent::__construct( 'duplicate', $title, $params, $id );
	}

	/**
	 * Get a duplicate no-op version of a job
	 *
	 * @param Job $job
	 * @return Job
	 */
	public static function newFromJob( Job $job ) {
		$job = new self( $job->getTitle(), $job->getParams(), $job->getId() );
		$job->command = $job->getType();
		$job->params  = is_array( $job->params ) ? $job->params : array();
		$job->params  = array( 'isDuplicate' => true ) + $job->params;
		return $job;
	}

	public function run() {
		return true;
	}
}
