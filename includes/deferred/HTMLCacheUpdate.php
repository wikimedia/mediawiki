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
 * Class to invalidate the HTML/file cache of all the pages linking to a given title.
 *
 * @ingroup Cache
 * @deprecated Since 1.34; Enqueue jobs from HTMLCacheUpdateJob::newForBacklinks instead
 */
class HTMLCacheUpdate extends DataUpdate {
	/** @var Title */
	private $title;
	/** @var string */
	private $table;

	/**
	 * @param Title $title
	 * @param string $table
	 */
	public function __construct( Title $title, $table ) {
		$this->title = $title;
		$this->table = $table;
	}

	public function doUpdate() {
		$job = HTMLCacheUpdateJob::newForBacklinks(
			$this->title,
			$this->table,
			[ 'causeAction' => $this->getCauseAction(), 'causeAgent' => $this->getCauseAgent() ]
		);
		JobQueueGroup::singleton()->lazyPush( $job );
	}
}
