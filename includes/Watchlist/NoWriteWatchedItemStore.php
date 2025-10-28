<?php

// @phan-file-suppress PhanPluginNeverReturnMethod

/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Watchlist
 */

namespace MediaWiki\Watchlist;

use MediaWiki\Page\PageReference;
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
	public function countWatchers( PageReference $target ) {
		return $this->actualStore->countWatchers( $target );
	}

	/** @inheritDoc */
	public function countVisitingWatchers( PageReference $target, $threshold ) {
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
	public function getWatchedItem( UserIdentity $user, PageReference $target ) {
		return $this->actualStore->getWatchedItem( $user, $target );
	}

	/** @inheritDoc */
	public function loadWatchedItem( UserIdentity $user, PageReference $target ) {
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
	public function isWatched( UserIdentity $user, PageReference $target ) {
		return $this->actualStore->isWatched( $user, $target );
	}

	/** @inheritDoc */
	public function isTempWatched( UserIdentity $user, PageReference $target ): bool {
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
	public function duplicateAllAssociatedEntries( PageReference $oldTarget, PageReference $newTarget ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/** @inheritDoc */
	public function duplicateEntry( PageReference $oldTarget, PageReference $newTarget ) {
		throw new DBReadOnlyError( null, self::DB_READONLY_ERROR );
	}

	/** @inheritDoc */
	public function addWatch( UserIdentity $user, PageReference $target, ?string $expiry = null ) {
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
	public function removeWatch( UserIdentity $user, PageReference $target ) {
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
		PageReference $title,
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
		$timestamp, UserIdentity $user, PageReference $target
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
