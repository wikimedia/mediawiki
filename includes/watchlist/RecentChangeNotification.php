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
use MediaWiki\RecentChanges\RecentChange;
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

	public const TALK_NOTIFICATION = 'talk';
	public const ADMIN_NOTIFICATION = 'admin';
	public const WATCHLIST_NOTIFICATION = 'watchlist';

	private RecentChange $recentChange;
	private string $pageStatus;
	private string $source;

	/**
	 * @param UserIdentity $editor
	 * @param PageIdentity $title
	 * @param RecentChange $recentChange
	 * @param string $pageStatus the Page status from RecentChange, this change is not persisted
	 * @param string $source one of types talk, admin or watchlist
	 */
	public function __construct(
		UserIdentity $editor,
		PageIdentity $title,
		RecentChange $recentChange,
		string $pageStatus,
		string $source
	) {
		parent::__construct( self::TYPE, $title, $editor );
		$this->source = $source;
		$this->pageStatus  = $pageStatus;
		$this->recentChange = $recentChange;
	}

	public function getRecentChange(): RecentChange {
		return $this->recentChange;
	}

	/**
	 * Retrieve page state, list provided by MediaWiki is
	 * [ 'deleted', 'created', 'moved', 'restored', 'changed' ]
	 * but additionally extensions can add their own states.
	 */
	public function getPageStatus(): string {
		return $this->pageStatus;
	}

	/**
	 * What is the notification source, is it a User Talk change, Watchlist notification or
	 * an admin notification ( Users loaded from UsersNotifiedOnAllChanges )
	 * @return string
	 */
	public function getSource(): string {
		return $this->source;
	}

}
