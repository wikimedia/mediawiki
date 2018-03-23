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

	/**
	 * Initialy set WatchedItemStore that will be used in cases where writing is not needed.
	 * @param WatchedItemStoreInterface $actualStore
	 */
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
		return $this->actualStore->countUnreadNotifications( $user, $unreadLimit );
	}

	public function duplicateAllAssociatedEntries( LinkTarget $oldTarget, LinkTarget $newTarget ) {
		throw new DBReadOnlyError( null, 'The watchlist is currently readonly.' );
	}

	public function duplicateEntry( LinkTarget $oldTarget, LinkTarget $newTarget ) {
		throw new DBReadOnlyError( null, 'The watchlist is currently readonly.' );
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

	public function resetAllNotificationTimestampsForUser( User $user ) {
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

	public function clearUserWatchedItems( User $user ) {
		throw new DBReadOnlyError( null, 'The watchlist is currently readonly.' );
	}

	public function clearUserWatchedItemsUsingJobQueue( User $user ) {
		throw new DBReadOnlyError( null, 'The watchlist is currently readonly.' );
	}
}
