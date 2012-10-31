<?php
/**
 * Degenerate job that just replaces itself in the queue.
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
 * Degenerate job that just replace itself in the queue.
 * Useful for lock contention and performance testing.
 *
 * @ingroup JobQueue
 */
class NullJob extends Job {
	/**
	 * @param $title Title: the title linked to
	 * @param $params Array: job parameters (table, start and end page_ids)
	 * @param $id Integer: job id
	 */
	function __construct( $title, $params, $id = 0 ) {
		parent::__construct( 'null', $title, $params, $id );
		if ( !isset( $this->params['lives'] ) ) {
			$this->params['lives'] = 1;
		}
	}

	public function run() {
		if ( $this->params['lives'] > 1 ) {
			$params = $this->params;
			$params['lives']--;
			$job = new self( $this->title, $params );
			$job->insert();
		}
		return true;
	}
}
