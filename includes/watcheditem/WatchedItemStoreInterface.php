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

/**
 * @author Addshore
 * @since 1.31 interface created. WatchedItemStore implementation available since 1.27
 */
interface WatchedItemStoreInterface {

	/**
	 * @since 1.31
	 */
	const SORT_ASC = 'ASC';

	/**
	 * @since 1.31
	 */
	const SORT_DESC = 'DESC';

	/**
	 * Count the number of individual items that are watched by the user.
	 * If a subject and corresponding talk page are watched this will return 2.
	 *
	 * @since 1.31
	 *
	 * @param User $user
	 *
	 * @return int
	 */
	public function countWatchedItems( User $user );

	/**
	 * @since 1.31
	 *
	 * @param LinkTarget $target
	 *
	 * @return int
	 */
	public function countWatchers( LinkTarget $target );

	/**
	 * Number of page watchers who also visited a "recent" edit
	 *
	 * @since 1.31
	 *
	 * @param LinkTarget $target
	 * @param mixed $threshold timestamp accepted by wfTimestamp
	 *
	 * @return int
	 * @throws DBUnexpectedError
	 * @throws MWException
	 */
	public function countVisitingWatchers( LinkTarget $target, $threshold );

	/**
	 * @since 1.31
	 *
	 * @param LinkTarget[] $targets
	 * @param array $options Allowed keys:
	 *        'minimumWatchers' => int
	 *
	 * @return array multi dimensional like $return[$namespaceId][$titleString] = int $watchers
	 *         All targets will be present in the result. 0 either means no watchers or the number
	 *         of watchers was below the minimumWatchers option if passed.
	 */
	public function countWatchersMultiple( array $targets, array $options = [] );

	/**
	 * Number of watchers of each page who have visited recent edits to that page
	 *
	 * @since 1.31
	 *
	 * @param array $targetsWithVisitThresholds array of pairs (LinkTarget $target, mixed
	 *     $threshold),
	 *        $threshold is:
	 *        - a timestamp of the recent edit if $target exists (format accepted by wfTimestamp)
	 *        - null if $target doesn't exist
	 * @param int|null $minimumWatchers
	 *
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
	);

	/**
	 * Get an item (may be cached)
	 *
	 * @since 1.31
	 *
	 * @param User $user
	 * @param LinkTarget $target
	 *
	 * @return WatchedItem|false
	 */
	public function getWatchedItem( User $user, LinkTarget $target );

	/**
	 * Loads an item from the db
	 *
	 * @since 1.31
	 *
	 * @param User $user
	 * @param LinkTarget $target
	 *
	 * @return WatchedItem|false
	 */
	public function loadWatchedItem( User $user, LinkTarget $target );

	/**
	 * @since 1.31
	 *
	 * @param User $user
	 * @param array $options Allowed keys:
	 *        'forWrite' => bool defaults to false
	 *        'sort' => string optional sorting by namespace ID and title
	 *                     one of the self::SORT_* constants
	 *
	 * @return WatchedItem[]
	 */
	public function getWatchedItemsForUser( User $user, array $options = [] );

	/**
	 * Must be called separately for Subject & Talk namespaces
	 *
	 * @since 1.31
	 *
	 * @param User $user
	 * @param LinkTarget $target
	 *
	 * @return bool
	 */
	public function isWatched( User $user, LinkTarget $target );

	/**
	 * @since 1.31
	 *
	 * @param User $user
	 * @param LinkTarget[] $targets
	 *
	 * @return array multi-dimensional like $return[$namespaceId][$titleString] = $timestamp,
	 *         where $timestamp is:
	 *         - string|null value of wl_notificationtimestamp,
	 *         - false if $target is not watched by $user.
	 */
	public function getNotificationTimestampsBatch( User $user, array $targets );

	/**
	 * Must be called separately for Subject & Talk namespaces
	 *
	 * @since 1.31
	 *
	 * @param User $user
	 * @param LinkTarget $target
	 */
	public function addWatch( User $user, LinkTarget $target );

	/**
	 * @since 1.31
	 *
	 * @param User $user
	 * @param LinkTarget[] $targets
	 *
	 * @return bool success
	 */
	public function addWatchBatchForUser( User $user, array $targets );

	/**
	 * Removes the an entry for the User watching the LinkTarget
	 * Must be called separately for Subject & Talk namespaces
	 *
	 * @since 1.31
	 *
	 * @param User $user
	 * @param LinkTarget $target
	 *
	 * @return bool success
	 * @throws DBUnexpectedError
	 * @throws MWException
	 */
	public function removeWatch( User $user, LinkTarget $target );

	/**
	 * @since 1.31
	 *
	 * @param User $user The user to set the timestamps for
	 * @param string|null $timestamp Set the update timestamp to this value
	 * @param LinkTarget[] $targets List of targets to update. Default to all targets
	 *
	 * @return bool success
	 */
	public function setNotificationTimestampsForUser(
		User $user,
		$timestamp,
		array $targets = []
	);

	/**
	 * Reset all watchlist notificaton timestamps for a user using the job queue
	 *
	 * @since 1.31
	 *
	 * @param User $user The user to reset the timestamps for
	 */
	public function resetAllNotificationTimestampsForUser( User $user );

	/**
	 * @since 1.31
	 *
	 * @param User $editor The editor that triggered the update. Their notification
	 *  timestamp will not be updated(they have already seen it)
	 * @param LinkTarget $target The target to update timestamps for
	 * @param string $timestamp Set the update timestamp to this value
	 *
	 * @return int[] Array of user IDs the timestamp has been updated for
	 */
	public function updateNotificationTimestamp( User $editor, LinkTarget $target, $timestamp );

	/**
	 * Reset the notification timestamp of this entry
	 *
	 * @since 1.31
	 *
	 * @param User $user
	 * @param Title $title
	 * @param string $force Whether to force the write query to be executed even if the
	 *    page is not watched or the notification timestamp is already NULL.
	 *    'force' in order to force
	 * @param int $oldid The revision id being viewed. If not given or 0, latest revision is
	 *     assumed.
	 *
	 * @return bool success Whether a job was enqueued
	 */
	public function resetNotificationTimestamp( User $user, Title $title, $force = '', $oldid = 0 );

	/**
	 * @since 1.31
	 *
	 * @param User $user
	 * @param int $unreadLimit
	 *
	 * @return int|bool The number of unread notifications
	 *                  true if greater than or equal to $unreadLimit
	 */
	public function countUnreadNotifications( User $user, $unreadLimit = null );

	/**
	 * Check if the given title already is watched by the user, and if so
	 * add a watch for the new title.
	 *
	 * To be used for page renames and such.
	 *
	 * @since 1.31
	 *
	 * @param LinkTarget $oldTarget
	 * @param LinkTarget $newTarget
	 */
	public function duplicateAllAssociatedEntries( LinkTarget $oldTarget, LinkTarget $newTarget );

	/**
	 * Check if the given title already is watched by the user, and if so
	 * add a watch for the new title.
	 *
	 * To be used for page renames and such.
	 * This must be called separately for Subject and Talk pages
	 *
	 * @since 1.31
	 *
	 * @param LinkTarget $oldTarget
	 * @param LinkTarget $newTarget
	 */
	public function duplicateEntry( LinkTarget $oldTarget, LinkTarget $newTarget );

	/**
	 * Queues a job that will clear the users watchlist using the Job Queue.
	 *
	 * @since 1.31
	 *
	 * @param User $user
	 */
	public function clearUserWatchedItems( User $user );

	/**
	 * Queues a job that will clear the users watchlist using the Job Queue.
	 *
	 * @since 1.31
	 *
	 * @param User $user
	 */
	public function clearUserWatchedItemsUsingJobQueue( User $user );

}
