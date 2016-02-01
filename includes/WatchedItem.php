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
	 * @var LinkTarget
	 */
	private $linkTarget;

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var null|string the value of the wl_notificationtimestamp field
	 */
	private $notificationTimestamp;

	/**
	 * @param User $user
	 * @param LinkTarget $linkTarget
	 * @param null|string $notificationTimestamp the value of the wl_notificationtimestamp field
	 * @param bool|null $checkRights DO NOT USE - used internally for backward compatibility
	 */
	public function __construct(
		User $user,
		LinkTarget $linkTarget,
		$notificationTimestamp,
		$checkRights = null
	) {
		$this->user = $user;
		$this->linkTarget = $linkTarget;
		$this->notificationTimestamp = $notificationTimestamp;
		if ( $checkRights === false || $checkRights === 0 ) {
			$this->checkRights = $checkRights;
		}
	}

	/**
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @return LinkTarget
	 */
	public function getLinkTarget() {
		return $this->linkTarget;
	}

	/**
	 * Get the notification timestamp of this entry.
	 *
	 * @return null|string
	 */
	public function getNotificationTimestamp() {
		// Back compat for objects constructed using self::fromUserTitle
		if ( $this->notificationTimestamp === self::DEPRECATED_USAGE_TIMESTAMP ) {
			wfDeprecated( __METHOD__, '1.27' );
			$item = WatchedItemStore::getDefaultInstance()->getWatchedItem( $this->user, $this->linkTarget );
			$this->notificationTimestamp = $item->getNotificationTimestamp();
		}
		return $this->notificationTimestamp;
	}

	/**
	 * Back compat pre 1.27 with the WatchedItemStore introduction
	 * TODO remove in 1.28/9
	 * -------------------------------------------------
	 */

	/**
	 * @deprecated since 1.27
	 */
	const IGNORE_USER_RIGHTS = false;
	/**
	 * @deprecated since 1.27
	 */
	const CHECK_USER_RIGHTS = true;
	/**
	 * @deprecated since 1.27
	 */
	const DEPRECATED_USAGE_TIMESTAMP = -100;

	/**
	 * @var bool
	 * @deprecated Internal use only
	 */
	public $checkRights = User::CHECK_USER_RIGHTS;

	/**
	 * @return Title
	 */
	private function getTitle() {
		static $title;
		if ( !$title ) {
			if ( $this->linkTarget instanceof Title ) {
				$title = $this->linkTarget;
			} else {
				$title = Title::newFromLinkTarget( $this->linkTarget );
			}
		}
		return $title;
	}

	/**
	 * @deprecated since 1.27
	 */
	public static function fromUserTitle( $user, $title, $checkRights = User::CHECK_USER_RIGHTS ) {
		wfDeprecated( __METHOD__, '1.27' );
		return new self( $user, $title, self::DEPRECATED_USAGE_TIMESTAMP, $checkRights );
	}

	/**
	 * @deprecated since 1.27
	 */
	public function resetNotificationTimestamp( $force = '', $oldid = 0 ) {
		wfDeprecated( __METHOD__, '1.27' );
		if ( $this->checkRights && !$this->user->isAllowed( 'editmywatchlist' ) ) {
			return;
		}
		WatchedItemStore::getDefaultInstance()->resetNotificationTimestamp(
			$this->user,
			$this->getTitle(),
			$force,
			$oldid
		);
	}

	/**
	 * @deprecated since 1.27
	 */
	public static function batchAddWatch( array $items ) {
		wfDeprecated( __METHOD__, '1.27' );
		$userTargetCombinations = array();
		/** @var WatchedItem $watchedItem */
		foreach ( $items as $watchedItem ) {
			if ( $watchedItem->checkRights && !$watchedItem->getUser()->isAllowed( 'editmywatchlist' ) ) {
				continue;
			}
			$userTargetCombinations[] = array( $watchedItem->getUser(), $watchedItem->getLinkTarget() );
		}
		$store = WatchedItemStore::getDefaultInstance();
		$store->addWatchBatch( $userTargetCombinations );
	}

	/**
	 * @deprecated since 1.27
	 */
	public function addWatch() {
		wfDeprecated( __METHOD__, '1.27' );
		$this->user->addWatch( $this->getTitle(), $this->checkRights );
	}

	/**
	 * @deprecated since 1.27
	 */
	public function removeWatch() {
		wfDeprecated( __METHOD__, '1.27' );
		$this->user->removeWatch( $this->getTitle(), $this->checkRights );
	}

	/**
	 * @deprecated since 1.27
	 */
	public static function duplicateEntries( Title $oldTitle, Title $newTitle ) {
		wfDeprecated( __METHOD__, '1.27' );
		$store = WatchedItemStore::getDefaultInstance();
		$store->duplicateEntry( $oldTitle->getSubjectPage(), $newTitle->getSubjectPage() );
		$store->duplicateEntry( $oldTitle->getTalkPage(), $newTitle->getTalkPage() );
	}

}
