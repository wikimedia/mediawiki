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

namespace MediaWiki\Watchlist;

use MediaWiki\Notification\Types\WikiNotification;
use MediaWiki\Page\PageIdentity;
use MediaWiki\User\UserIdentity;

/**
 * Notification representing a Recent change. Used as base to transport Watchlist, UserTalk and
 * Administrator recent changes notifications
 *
 * @since 1.44
 * @unstable
 */
class RecentChangeNotification extends WikiNotification {

	public const TYPE = 'mediawiki.recent_change';

	/**
	 * @todo Pass the RecentChange object
	 *
	 * @param UserIdentity $editor
	 * @param PageIdentity $title
	 * @param string $summary
	 * @param bool $minorEdit
	 * @param int|null $oldid
	 * @param string $timestamp
	 * @param string $pageStatus
	 */
	public function __construct(
		UserIdentity $editor,
		PageIdentity $title,
		string $summary,
		bool $minorEdit,
		$oldid,
		$timestamp,
		string $pageStatus
	) {
		parent::__construct(
			self::TYPE, $title, $editor, [
				'summary' => $summary,
				'minorEdit' => $minorEdit,
				'oldid' => $oldid,
				'timestamp' => $timestamp,
				'pageStatus' => $pageStatus
			]
		);
	}

}
