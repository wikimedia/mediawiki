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

use MediaWiki\Linker\LinkTarget;
use MediaWiki\User\UserIdentity;

/**
 * Representation of a pair of user and title for watchlist entries.
 *
 * @author Tim Starling
 * @author Addshore
 *
 * @ingroup Watchlist
 */
class WatchedItem {
	/**
	 * @var LinkTarget
	 */
	private $linkTarget;

	/**
	 * @var UserIdentity
	 */
	private $user;

	/**
	 * @var null|string the value of the wl_notificationtimestamp field
	 */
	private $notificationTimestamp;

	/**
	 * @var string|null When to automatically unwatch the page
	 */
	private $expiry;

	/**
	 * @param UserIdentity $user
	 * @param LinkTarget $linkTarget
	 * @param null|string $notificationTimestamp the value of the wl_notificationtimestamp field
	 * @param null|string $expiry Optional expiry timestamp in any format acceptable to wfTimestamp()
	 */
	public function __construct(
		UserIdentity $user,
		LinkTarget $linkTarget,
		$notificationTimestamp,
		?string $expiry = null
	) {
		$this->user = $user;
		$this->linkTarget = $linkTarget;
		$this->notificationTimestamp = $notificationTimestamp;
		$this->expiry = $expiry;
	}

	/**
	 * @deprecated since 1.34, use getUserIdentity()
	 * @return User
	 */
	public function getUser() {
		return User::newFromIdentity( $this->user );
	}

	/**
	 * @return UserIdentity
	 */
	public function getUserIdentity() {
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
	 * @return bool|null|string
	 */
	public function getNotificationTimestamp() {
		return $this->notificationTimestamp;
	}

	/**
	 * When the watched item will expire.
	 *
	 * @since 1.35
	 *
	 * @return string|null null or in a format acceptable to wfTimestamp().
	 */
	public function getExpiry(): ?string {
		return $this->expiry;
	}

	/**
	 * Has the watched item expired?
	 *
	 * @since 1.35
	 *
	 * @return bool
	 */
	public function isExpired(): bool {
		if ( null === $this->getExpiry() ) {
			return false;
		}

		$unix = MWTimestamp::convert( TS_UNIX, $this->getExpiry() );
		return $unix < wfTimestamp();
	}
}
