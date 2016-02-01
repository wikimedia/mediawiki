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
 * @ingroup Watchlist
 */

/**
 * Representation of a pair of user and title for watchlist entries.
 *
 * @ingroup Watchlist
 */
class WatchedItem {

	/**
	 * @var Title
	 */
	private $title;

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var null|string the value of the wl_notificationtimestamp field otherwise
	 */
	private $notificationTimestamp;

	/**
	 * @param User $user
	 * @param Title $title
	 * @param null|string $notificationTimestamp the value of the wl_notificationtimestamp field otherwise
	 */
	public function __construct(
		User $user,
		Title $title,
		$notificationTimestamp
	) {
		$this->user = $user;
		$this->title = $title;
		$this->notificationTimestamp = $notificationTimestamp;
	}

	/**
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @return Title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Get the notification timestamp of this entry.
	 *
	 * @return null|string
	 */
	public function getNotificationTimestamp() {
		return $this->notificationTimestamp;
	}

	/**
	 * @deprecated since 1.27. Use WatchedItemStore::duplicateEntries
	 *
	 * @param Title $oldTitle
	 * @param Title $newTitle
	 */
	public static function duplicateEntries( $oldTitle, $newTitle ) {
		WatchedItemStore::getDefaultInstance()->duplicateEntries( $oldTitle, $newTitle );
	}

}