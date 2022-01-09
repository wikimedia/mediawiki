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
use MediaWiki\Page\PageIdentity;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\DBReadOnlyError;

/**
 * @internal
 * @since 1.31
 * @phan-file-suppress PhanPluginNeverReturnMethod
 */
class NoWriteWatchedItemStore implements WatchedItemStoreInterface {

	/**
	 * @var WatchedItemStoreInterface
	 */
	private $actualStore;

	private const DB_READONLY_ERROR = 'The watchlist is currently readonly.';

	/**
	 * Initially set WatchedItemStore that will be used in cases where writing is not needed.
	 * @param WatchedItemStoreInterface $actualStore
	 */
	public function __construct( WatchedItemStoreInterface $actualStore ) {
		$this->actualStore = $actualStore;
	}

	public function countWatchedItems( UserIdentity $user ) {
		return $this->actualStore->countWatchedItems( $user );
	}

	/**
	 * @param LinkTarget|PageIdentity $target deprecated passing LinkTarget since 1.36
	 * @return int
	 */
	public function countWatchers( $target ) {
		return $this->actualStore->countWatchers( $target );
	}

	/**
	 * @param LinkTarget|PageIdentity $target deprecated passing LinkTarget since 1.36
	 * @param mixed $threshold
	 * @return int
	 * @throws MWException
	 */
	public function countVisitingWatchers( $target, $threshold ) {
		return $this->actualStore->countVisitingWatchers( $target, $threshold );
	}

	/**
	 * @param LinkTarget[]|PageIdentity[] $targets deprecated passing LinkTarget[] since 1.36
	 * @param array $options Allowed keys:
	 *        'minimumWatchers' => int
	 * @return array multi dimensional like $return[$namespaceId][$titleString] = int $watchers
	 *         All targets will be present in the result. 0 either means no watchers or the number
	 *         of watchers was below the minimumWatchers option if passed.
	 */
	public function countWatchersMultiple( array $targets, array $options = [] ) {
		return $this->actualStore->countWatchersMultiple(
			$targets,
			$options
		);
	}

	/**
	 * @param array $targetsWithVisitThresholds array of pairs (LinkTarget|PageIdentity $target,
	 *     mixed $threshold),
	 *        $threshold is:
	 *        - a timestamp of the recent edit if $target exists (format accepted by wfTimestamp)
	 *        - null if $target doesn't exist
	 *      deprecated passing LinkTarget since 1.36
	 * @param int|null $minimumWatchers
	 * @return array multi-dimensional like $return[$namespaceId][$titleString] = $watchers,
	 *         where $watchers is an int:
	 *         - if the page exists, number of users watching who have visited the page recently
	 *         - if the page doesn't exist, number of users that have the page on their watchlist
	 *         - 0 means there are no visiting watchers or their number is below the
	 *     minimumWatchers
	 *         option (if passed).
	 */
	public function countVisitingWatchersMultiple(
		array $targetsWithVisitThresholds,
		$minimumWatchers = null
	) {
		return $this->actualStore->countVisitingWatchersMultiple(
			$targetsWithVisitThresholds,
			$minimumWatchers
		);
	}

	/**
	 * @param UserIdentity $user
	 * @param LinkTarget|PageIdentity $target deprecated passing LinkTarget since 1.36
	 * @return false|WatchedItem
	 */
	public function getWatchedItem( UserIdentity $user, $target ) {
		return $this->actualStore->getWatchedItem( $user, $target );
	}

	/**
	 * @param UserIdentity $user
	 * @param LinkTarget|PageIdentity $target deprecated passing LinkTarget since 1.36
	 * @return false|WatchedItem
	 */
	public function loadWatchedItem( UserIdentity $user, $target ) {
		return $this->actualStore->loadWatchedItem( $user, $target );
	}

	/**
	 * @param UserIdentity $user
	 * @param LinkTarget[]|PageIdentity[] $targets deprecated passing LinkTarget[] since 1.36
	 * @return WatchedItem[]|false
	 */
	public function loadWatchedItemsBatch( UserIdentity $user, array $targets ) {
		return $this->actualStore->loadWatchedItemsBatch( $user, $targets );
	}

	public function getWatchedItemsForUser( UserIdentity $user, array $options = [] ) {
		return $this->actualStore->getWatchedItemsForUser( $user, $options );
	}

	/**
	 * @param UserIdentity $user
	 * @param LinkTarget|PageIdentity $target deprecated passing LinkTarget since 1.36
	 * @return bool
	 */
	public function isWatched( UserIdentity $user, $target ) {
		return $this->actualStore->isWatched( $user, $target );
	}

	/**
	 * @param UserIdentity $user
	 * @param LinkTarget|PageIdentity $target deprecated passing LinkTarget since 1.36
	 * @return bool
	 */
	public function isTempWatched( UserIdentity $user, $target ): bool {
		return $this->actualStore->isTempWatched( $user, $target );
	}

	/**
	 * @param UserIdentity $user
	 * @param LinkTarget[]|PageIdentity[] $targets deprecated passing LinkTarget[] since 1.36
	 * @return array multi-dimensional like $return[$namespaceId][$titleString] = $timestamp,
	 *         where $timestamp is:
	 *         - string|null value of wl_notificationtimestamp,
	 *         - false if $target is not watched by $user.
	 */
	public function getNotificationTimestampsBatch( UserIdentity $user, array $targets ) {
		return $this->actualStore->getNotificationTimestampsBatch( $user, $targets );
	}

	public function countUnreadNotifications( UserIdentity $user, $unreadLimit = null ) {
		return $this->actualStore->countUnreadNotifications( $user, $unreadLimit );
	}

	/**
	 * @param LinkTarget|PageIdentity $oldTarget deprecated passing LinkTarget since 1.36
	 * @param LinkTarget|PageIdentity $newTarget deprecated passing LinkTarget since 1.36
	 */
	public function duplicateAllAssociatedEntries( $oldTarget, $newTarget ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/**
	 * @param LinkTarget|PageIdentity $oldTarget deprecated passing LinkTarget since 1.36
	 * @param LinkTarget|PageIdentity $newTarget deprecated passing LinkTarget since 1.36
	 */
	public function duplicateEntry( $oldTarget, $newTarget ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/**
	 * @param UserIdentity $user
	 * @param LinkTarget|PageIdentity $target deprecated passing LinkTarget since 1.36
	 * @param string|null $expiry
	 */
	public function addWatch( UserIdentity $user, $target, ?string $expiry = null ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/**
	 *
	 * @param UserIdentity $user
	 * @param LinkTarget[]|PageIdentity[] $targets deprecated passing LinkTarget[] since 1.36
	 * @param string|null $expiry Optional expiry timestamp in any format acceptable to wfTimestamp(),
	 *   null will not create expiries, or leave them unchanged should they already exist.
	 * @return bool success
	 */
	public function addWatchBatchForUser(
		UserIdentity $user,
		array $targets,
		?string $expiry = null
	) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/**
	 * @param UserIdentity $user
	 * @param LinkTarget|PageIdentity $target deprecated passing LinkTarget since 1.36
	 * @return bool|void
	 */
	public function removeWatch( UserIdentity $user, $target ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/**
	 * @param UserIdentity $user The user to set the timestamps for
	 * @param string|null $timestamp Set the update timestamp to this value
	 * @param LinkTarget[]|PageIdentity[] $targets List of targets to update. Default to all targets.
	 *         deprecated passing LinkTarget[] since 1.36
	 * @return bool success
	 */
	public function setNotificationTimestampsForUser(
		UserIdentity $user,
		$timestamp,
		array $targets = []
	) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/**
	 * @param UserIdentity $editor
	 * @param LinkTarget|PageIdentity $target deprecated passing LinkTarget since 1.36
	 * @param string $timestamp
	 * @return int[]|void
	 */
	public function updateNotificationTimestamp(
		UserIdentity $editor, $target, $timestamp
	) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	public function resetAllNotificationTimestampsForUser( UserIdentity $user, $timestamp = null ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/**
	 * @param UserIdentity $user
	 * @param LinkTarget|PageIdentity $title deprecated passing LinkTarget since 1.36
	 * @param string $force
	 * @param int $oldid
	 * @return bool|void
	 */
	public function resetNotificationTimestamp(
		UserIdentity $user,
		$title,
		$force = '',
		$oldid = 0
	) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	public function clearUserWatchedItems( UserIdentity $user ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	public function mustClearWatchedItemsUsingJobQueue( UserIdentity $user ): bool {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	public function clearUserWatchedItemsUsingJobQueue( UserIdentity $user ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	public function maybeEnqueueWatchlistExpiryJob(): void {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/**
	 * @param UserIdentity $user
	 * @param LinkTarget[]|PageIdentity[] $targets deprecated passing LinkTarget[] since 1.36
	 * @return bool success
	 */
	public function removeWatchBatchForUser( UserIdentity $user, array $targets ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/**
	 * @param string|null $timestamp
	 * @param UserIdentity $user
	 * @param LinkTarget|PageIdentity $target deprecated passing LinkTarget since 1.36
	 * @return bool|string|null
	 */
	public function getLatestNotificationTimestamp(
		$timestamp, UserIdentity $user, $target
	) {
		return wfTimestampOrNull( TS_MW, $timestamp );
	}

	public function countExpired(): int {
		return $this->actualStore->countExpired();
	}

	public function removeExpired( int $limit, bool $deleteOrphans = false ): void {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}
}
