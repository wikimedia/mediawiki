<?php
/**
 * Job queue base code.
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
 * @defgroup JobQueue JobQueue
 */

/**
 * Class to handle enqueueing and running of background jobs.
 * This is for simple job types that don't need to overload JobQueue;
 * they can simple use this class by providing a job type parameter.
 *
 * @ingroup JobQueue
 * @since 1.20
 */
class JobQueueSimple extends JobQueue {
	protected $type; // string; job type

	/**
	 * @see JobQueue::__construct()
	 * @param $params array
	 */
	protected function __construct( array $params ) {
		parent::__construct( $params );

		$this->type = $params['type'];
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}
}
