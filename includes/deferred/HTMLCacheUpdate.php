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
 * Class to invalidate the HTML cache of all the pages linking to a given title.
 *
 * @ingroup Cache
 */
class HTMLCacheUpdate implements DeferrableUpdate {
	/** @var Title */
	public $mTitle;

	/** @var string */
	public $mTable;

	/**
	 * @param Title $titleTo
	 * @param string $table
	 */
	function __construct( Title $titleTo, $table ) {
		$this->mTitle = $titleTo;
		$this->mTable = $table;
	}

	public function doUpdate() {
		$job = HTMLCacheUpdateJob::newForBacklinks( $this->mTitle, $this->mTable );

		JobQueueGroup::singleton()->lazyPush( $job );
	}
}
