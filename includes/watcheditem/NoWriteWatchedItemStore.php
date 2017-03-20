<?php

use MediaWiki\Linker\LinkTarget;

/**
 * @internal
 */
class NoWriteWatchedItemStore implements WatchedItemStoreInterface {

	/**
	 * @var WatchedItemStoreInterface
	 */
	private $actualStore;

	public function __construct( WatchedItemStoreInterface $actualStore ) {
		$this->actualStore = $actualStore;
	}

	public function countWatchedItems( User $user ) {
		return $this->actualStore->countWatchedItems( $user );
	}

	public function countWatchers( LinkTarget $target ) {
		return $this->actualStore->countWatchers( $target );
	}

	public function countVisitingWatchers( LinkTarget $target, $threshold ) {
		return $this->actualStore->countVisitingWatchers( $target, $threshold );
	}

	public function countWatchersMultiple( array $targets, array $options = [] ) {
		return $this->actualStore->countVisitingWatchersMultiple( $targets, $options );
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

	public function getWatchedItem( User $user, LinkTarget $target ) {
		return $this->actualStore->getWatchedItem( $user, $target );
	}

	public function loadWatchedItem( User $user, LinkTarget $target ) {
		return $this->actualStore->loadWatchedItem( $user, $target );
	}

	public function getWatchedItemsForUser( User $user, array $options = [] ) {
		return $this->actualStore->getWatchedItemsForUser( $user, $options );
	}

	public function isWatched( User $user, LinkTarget $target ) {
		return $this->actualStore->isWatched( $user, $target );
	}

	public function getNotificationTimestampsBatch( User $user, array $targets ) {
		return $this->actualStore->getNotificationTimestampsBatch( $user, $targets );
	}

	public function countUnreadNotifications( User $user, $unreadLimit = null ) {
		$this->actualStore->countUnreadNotifications( $user, $unreadLimit );
	}

	public function duplicateAllAssociatedEntries( LinkTarget $oldTarget, LinkTarget $newTarget ) {
		$this->actualStore->duplicateAllAssociatedEntries( $oldTarget, $newTarget );
	}

	public function duplicateEntry( LinkTarget $oldTarget, LinkTarget $newTarget ) {
		$this->actualStore->duplicateEntry( $oldTarget, $newTarget );
	}

	public function addWatch( User $user, LinkTarget $target ) {
		throw new DBReadOnlyError( null, 'The watchlist is currently readonly.' );
	}

	public function addWatchBatchForUser( User $user, array $targets ) {
		throw new DBReadOnlyError( null, 'The watchlist is currently readonly.' );
	}

	public function removeWatch( User $user, LinkTarget $target ) {
		throw new DBReadOnlyError( null, 'The watchlist is currently readonly.' );
	}

	public function setNotificationTimestampsForUser(
		User $user,
		$timestamp,
		array $targets = []
	) {
		throw new DBReadOnlyError( null, 'The watchlist is currently readonly.' );
	}

	public function updateNotificationTimestamp( User $editor, LinkTarget $target, $timestamp ) {
		throw new DBReadOnlyError( null, 'The watchlist is currently readonly.' );
	}

	public function resetNotificationTimestamp(
		User $user,
		Title $title,
		$force = '',
		$oldid = 0
	) {
		throw new DBReadOnlyError( null, 'The watchlist is currently readonly.' );
	}

}
