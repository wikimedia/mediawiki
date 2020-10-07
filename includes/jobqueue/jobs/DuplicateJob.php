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
 * @ingroup JobQueue
 */

/**
 * No-op job that does nothing. Used to represent duplicates.
 *
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

	public function run() {
		return true;
	}
}
