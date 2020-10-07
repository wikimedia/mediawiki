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
use Wikimedia\Rdbms\DBReadOnlyError;

/**
 * @internal
 * @since 1.31
 */
class NoWriteWatchedItemStore implements WatchedItemStoreInterface {

	/**
	 * @var WatchedItemStoreInterface
	 */
	private $actualStore;

	private const DB_READONLY_ERROR = 'The watchlist is currently readonly.';

	/**
	 * Initialy set WatchedItemStore that will be used in cases where writing is not needed.
	 * @param WatchedItemStoreInterface $actualStore
	 */
	public function __construct( WatchedItemStoreInterface $actualStore ) {
		$this->actualStore = $actualStore;
	}

	public function countWatchedItems( UserIdentity $user ) {
		return $this->actualStore->countWatchedItems( $user );
	}

	public function countWatchers( LinkTarget $target ) {
		return $this->actualStore->countWatchers( $target );
	}

	public function countVisitingWatchers( LinkTarget $target, $threshold ) {
		return $this->actualStore->countVisitingWatchers( $target, $threshold );
	}

	public function countWatchersMultiple( array $targets, array $options = [] ) {
		return $this->actualStore->countVisitingWatchersMultiple(
			$targets,
			$options['minimumWatchers'] ?? null
		);
	}

	public function countVisitingWatchersMultiple(
		array $targetsWithVisitThresholds,
		$minimumWatchers = null
	) {
		return $this->actualStore->countVisitingWatchersMultiple(
			$targetsWithVisitThresholds,
			$minimumWatchers
		);
	}

	public function getWatchedItem( UserIdentity $user, LinkTarget $target ) {
		return $this->actualStore->getWatchedItem( $user, $target );
	}

	public function loadWatchedItem( UserIdentity $user, LinkTarget $target ) {
		return $this->actualStore->loadWatchedItem( $user, $target );
	}

	public function getWatchedItemsForUser( UserIdentity $user, array $options = [] ) {
		return $this->actualStore->getWatchedItemsForUser( $user, $options );
	}

	public function isWatched( UserIdentity $user, LinkTarget $target ) {
		return $this->actualStore->isWatched( $user, $target );
	}

	public function isTempWatched( UserIdentity $user, LinkTarget $target ): bool {
		return $this->actualStore->isTempWatched( $user, $target );
	}

	public function getNotificationTimestampsBatch( UserIdentity $user, array $targets ) {
		return $this->actualStore->getNotificationTimestampsBatch( $user, $targets );
	}

	public function countUnreadNotifications( UserIdentity $user, $unreadLimit = null ) {
		return $this->actualStore->countUnreadNotifications( $user, $unreadLimit );
	}

	public function duplicateAllAssociatedEntries( LinkTarget $oldTarget, LinkTarget $newTarget ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	public function duplicateEntry( LinkTarget $oldTarget, LinkTarget $newTarget ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	public function addWatch( UserIdentity $user, LinkTarget $target, ?string $expiry = null ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	public function addWatchBatchForUser(
		UserIdentity $user,
		array $targets,
		?string $expiry = null
	) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	public function removeWatch( UserIdentity $user, LinkTarget $target ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	public function setNotificationTimestampsForUser(
		UserIdentity $user,
		$timestamp,
		array $targets = []
	) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	public function updateNotificationTimestamp(
		UserIdentity $editor, LinkTarget $target, $timestamp
	) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	public function resetAllNotificationTimestampsForUser( UserIdentity $user, $timestamp = null ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	public function resetNotificationTimestamp(
		UserIdentity $user,
		LinkTarget $title,
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

	public function enqueueWatchlistExpiryJob( float $watchlistPurgeRate ): void {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	public function removeWatchBatchForUser( UserIdentity $user, array $titles ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	public function getLatestNotificationTimestamp(
		$timestamp, UserIdentity $user, LinkTarget $target
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
