<?php
/**
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
 * HTML file cache invalidation all the pages linking to a given title
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
	 * @param Title $titleTo
	 * @param string $table
	 * @param string $causeAction Triggering action
	 * @param string $causeAgent Triggering user
	 */
	public function __construct(
		Title $titleTo, $table, $causeAction = 'unknown', $causeAgent = 'unknown'
	) {
		$this->title = $titleTo;
		$this->table = $table;
		$this->causeAction = $causeAction;
		$this->causeAgent = $causeAgent;
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
