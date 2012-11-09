<?php
/**
 * Degenerate job that does nothing.
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
 * Degenerate job that does nothing, but can optionally replace itself
 * in the queue and/or sleep for a brief time period. These can be used
 * to represent "no-op" jobs or test lock contention and performance.
 *
 * @ingroup JobQueue
 */
class NullJob extends Job {
	/**
	 * @param $title Title (can be anything)
	 * @param $params Array: job parameters (lives, usleep)
	 * @param $id Integer: job id
	 */
	function __construct( $title, $params, $id = 0 ) {
		parent::__construct( 'null', $title, $params, $id );
		if ( !isset( $this->params['lives'] ) ) {
			$this->params['lives'] = 1;
		}
		if ( !isset( $this->params['usleep'] ) ) {
			$this->params['usleep'] = 0;
		}
	}

	public function run() {
		if ( $this->params['usleep'] > 0 ) {
			usleep( $this->params['usleep'] );
		}
		if ( $this->params['lives'] > 1 ) {
			$params = $this->params;
			$params['lives']--;
			$job = new self( $this->title, $params );
			$job->insert();
		}
		return true;
	}
}
