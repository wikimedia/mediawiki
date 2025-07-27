<?php

// @phan-file-suppress PhanPluginNeverReturnMethod

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

namespace MediaWiki\Watchlist;

use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\DBReadOnlyError;

/**
 * @internal
 * @since 1.31
 */
class NoWriteWatchedItemStore implements WatchedItemStoreInterface {

	private WatchedItemStoreInterface $actualStore;

	private const DB_READONLY_ERROR = 'The watchlist is currently readonly.';

	/**
	 * Initially set WatchedItemStore that will be used in cases where writing is not needed.
	 */
	public function __construct( WatchedItemStoreInterface $actualStore ) {
		$this->actualStore = $actualStore;
	}

	/** @inheritDoc */
	public function countWatchedItems( UserIdentity $user ) {
		return $this->actualStore->countWatchedItems( $user );
	}

	/** @inheritDoc */
	public function countWatchers( $target ) {
		return $this->actualStore->countWatchers( $target );
	}

	/** @inheritDoc */
	public function countVisitingWatchers( $target, $threshold ) {
		return $this->actualStore->countVisitingWatchers( $target, $threshold );
	}

	/** @inheritDoc */
	public function countWatchersMultiple( array $targets, array $options = [] ) {
		return $this->actualStore->countWatchersMultiple(
			$targets,
			$options
		);
	}

	/** @inheritDoc */
	public function countVisitingWatchersMultiple(
		array $targetsWithVisitThresholds,
		$minimumWatchers = null
	) {
		return $this->actualStore->countVisitingWatchersMultiple(
			$targetsWithVisitThresholds,
			$minimumWatchers
		);
	}

	/** @inheritDoc */
	public function getWatchedItem( UserIdentity $user, $target ) {
		return $this->actualStore->getWatchedItem( $user, $target );
	}

	/** @inheritDoc */
	public function loadWatchedItem( UserIdentity $user, $target ) {
		return $this->actualStore->loadWatchedItem( $user, $target );
	}

	/** @inheritDoc */
	public function loadWatchedItemsBatch( UserIdentity $user, array $targets ) {
		return $this->actualStore->loadWatchedItemsBatch( $user, $targets );
	}

	/** @inheritDoc */
	public function getWatchedItemsForUser( UserIdentity $user, array $options = [] ) {
		return $this->actualStore->getWatchedItemsForUser( $user, $options );
	}

	/** @inheritDoc */
	public function isWatched( UserIdentity $user, $target ) {
		return $this->actualStore->isWatched( $user, $target );
	}

	/** @inheritDoc */
	public function isTempWatched( UserIdentity $user, $target ): bool {
		return $this->actualStore->isTempWatched( $user, $target );
	}

	/** @inheritDoc */
	public function getNotificationTimestampsBatch( UserIdentity $user, array $targets ) {
		return $this->actualStore->getNotificationTimestampsBatch( $user, $targets );
	}

	/** @inheritDoc */
	public function countUnreadNotifications( UserIdentity $user, $unreadLimit = null ) {
		return $this->actualStore->countUnreadNotifications( $user, $unreadLimit );
	}

	/** @inheritDoc */
	public function duplicateAllAssociatedEntries( $oldTarget, $newTarget ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/** @inheritDoc */
	public function duplicateEntry( $oldTarget, $newTarget ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/** @inheritDoc */
	public function addWatch( UserIdentity $user, $target, ?string $expiry = null ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/** @inheritDoc */
	public function addWatchBatchForUser(
		UserIdentity $user,
		array $targets,
		?string $expiry = null
	) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/** @inheritDoc */
	public function removeWatch( UserIdentity $user, $target ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/** @inheritDoc */
	public function setNotificationTimestampsForUser(
		UserIdentity $user,
		$timestamp,
		array $targets = []
	) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/** @inheritDoc */
	public function updateNotificationTimestamp(
		UserIdentity $editor, $target, $timestamp
	) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/** @inheritDoc */
	public function resetAllNotificationTimestampsForUser( UserIdentity $user, $timestamp = null ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/** @inheritDoc */
	public function resetNotificationTimestamp(
		UserIdentity $user,
		$title,
		$force = '',
		$oldid = 0
	) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/** @inheritDoc */
	public function clearUserWatchedItems( UserIdentity $user ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/** @inheritDoc */
	public function mustClearWatchedItemsUsingJobQueue( UserIdentity $user ): bool {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/** @inheritDoc */
	public function clearUserWatchedItemsUsingJobQueue( UserIdentity $user ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/** @inheritDoc */
	public function maybeEnqueueWatchlistExpiryJob(): void {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/** @inheritDoc */
	public function removeWatchBatchForUser( UserIdentity $user, array $targets ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/** @inheritDoc */
	public function getLatestNotificationTimestamp(
		$timestamp, UserIdentity $user, $target
	) {
		return wfTimestampOrNull( TS_MW, $timestamp );
	}

	/** @inheritDoc */
	public function countExpired(): int {
		return $this->actualStore->countExpired();
	}

	/** @inheritDoc */
	public function removeExpired( int $limit, bool $deleteOrphans = false ): void {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}
}
/** @deprecated class alias since 1.43 */
class_alias( NoWriteWatchedItemStore::class, 'NoWriteWatchedItemStore' );
