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
	 * Used to calculate how many days are remaining until a watched item will expire.
	 * Uses a different algorithm from Language::getDurationIntervals for calculating
	 * days remaining in an interval of time
	 *
	 * @since 1.35
	 */
	private const SECONDS_IN_A_DAY = 86400;

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
	 * @since 1.35
	 * @param RecentChange $recentChange
	 * @param UserIdentity $user
	 * @return WatchedItem
	 */
	public static function newFromRecentChange( RecentChange $recentChange, UserIdentity $user ) {
		return new self(
			$user,
			$recentChange->getTitle(),
			$recentChange->notificationtimestamp,
			$recentChange->watchlistExpiry
		);
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
		if ( $this->getExpiry() === null ) {
			return false;
		}

		$unix = MWTimestamp::convert( TS_UNIX, $this->getExpiry() );
		return $unix < wfTimestamp();
	}

	/**
	 * Get days remaining until a watched item expires.
	 *
	 * @since 1.35
	 *
	 * @return int|null days remaining or null if no expiration is present
	 */
	public function getExpiryInDays(): ?int {
		return self::calculateExpiryInDays( $this->getExpiry() );
	}

	/**
	 * Get the number of days remaining until the given expiry time.
	 *
	 * @since 1.35
	 *
	 * @param string|null $expiry The expiry to calculate from, in any format
	 * supported by MWTimestamp::convert().
	 *
	 * @return int|null The remaining number of days or null if $expiry is null.
	 */
	public static function calculateExpiryInDays( ?string $expiry ): ?int {
		if ( $expiry === null ) {
			return null;
		}

		$unixTimeExpiry = MWTimestamp::convert( TS_UNIX, $expiry );
		$diffInSeconds = $unixTimeExpiry - wfTimestamp();
		$diffInDays = $diffInSeconds / self::SECONDS_IN_A_DAY;

		if ( $diffInDays < 1 ) {
			return 0;
		}

		return (int)ceil( $diffInDays );
	}

	/**
	 * Get days remaining until a watched item expires as a text.
	 *
	 * @since 1.35
	 * @param MessageLocalizer $msgLocalizer
	 * @param bool $isDropdownOption Whether the text is being displayed as a dropdown option.
	 *             The text is different as a dropdown option from when it is used in other
	 *             places as a watchlist indicator.
	 * @return string days remaining text and '' if no expiration is present
	 */
	public function getExpiryInDaysText( MessageLocalizer $msgLocalizer, $isDropdownOption = false ): string {
		$expiryInDays = $this->getExpiryInDays();
		if ( $expiryInDays === null ) {
			return '';
		}

		if ( $expiryInDays < 1 ) {
			if ( $isDropdownOption ) {
				return $msgLocalizer->msg( 'watchlist-expiry-hours-left' )->text();
			}
			return $msgLocalizer->msg( 'watchlist-expiring-hours-full-text' )->text();
		}

		if ( $isDropdownOption ) {
			return $msgLocalizer->msg( 'watchlist-expiry-days-left', [ $expiryInDays ] )->text();
		}

		return $msgLocalizer->msg( 'watchlist-expiring-days-full-text', [ $expiryInDays ] )->text();
	}
}
