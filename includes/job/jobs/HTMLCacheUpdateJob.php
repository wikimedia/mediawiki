<?php
/**
 * HTML cache invalidation of all pages linking to a given title.
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
 * Job wrapper for HTMLCacheUpdate. Gets run whenever a related
 * job gets called from the queue.
 *
 * @ingroup JobQueue
 */
class HTMLCacheUpdateJob extends Job {
	var $table, $start, $end;

	/**
	 * Construct a job
	 * @param $title Title: the title linked to
	 * @param $params Array: job parameters (table, start and end page_ids)
	 * @param $id Integer: job id
	 */
	function __construct( $title, $params, $id = 0 ) {
		parent::__construct( 'htmlCacheUpdate', $title, $params, $id );
		$this->table = $params['table'];
		$this->start = $params['start'];
		$this->end = $params['end'];
	}

	public function run() {
		$update = new HTMLCacheUpdate( $this->title, $this->table, $this->start, $this->end );
		$update->doUpdate();
		return true;
	}
}
