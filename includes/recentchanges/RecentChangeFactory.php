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
 */

namespace MediaWiki\RecentChanges;

use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Storage\EditResult;
use MediaWiki\User\UserIdentity;
use stdClass;

/**
 * @since 1.45
 */
interface RecentChangeFactory {

	/**
	 * @var bool For insertRecentChange() - save to the database only, without any events.
	 */
	public const SEND_NONE = true;

	/**
	 * @var bool For insertRecentChange() - do emit the change to RCFeeds (usually public).
	 */
	public const SEND_FEED = false;

	/**
	 * Create a new RecentChange object from a database row
	 *
	 * @param stdClass $row Database row from recentchanges table
	 * @return RecentChange
	 */
	public function newRecentChangeFromRow( $row );

	/**
	 * Create a RecentChange for an edit
	 *
	 * @param string $timestamp Timestamp of the edit to occur
	 * @param PageIdentity $page Page of the edit to occur
	 * @param bool $minor Whether the edit is minor
	 * @param UserIdentity $user User who made the edit
	 * @param string $comment Summary of the edit
	 * @param int $oldId ID of the previous revision
	 * @param string $lastTimestamp Timestamp of the previous revision
	 * @param bool $bot Whether the edit was made by a bot
	 * @param string $ip IP address of the user, if the edit was made anonymously
	 * @param int $oldSize Size of the previous revision
	 * @param int $newSize Size of the new revision
	 * @param int $newId ID of the new revision
	 * @param int $patrol Whether the edit was patrolled (PRC_UNPATROLLED, PRC_PATROLLED, PRC_AUTOPATROLLED)
	 * @param string[] $tags
	 * @param EditResult|null $editResult EditResult associated with this edit. Can be safely
	 *  skipped if the edit is not a revert. Used only for marking revert tags.
	 *
	 * @return RecentChange
	 */
	public function createEditRecentChange(
		$timestamp,
		$page,
		$minor,
		$user,
		$comment,
		$oldId,
		$lastTimestamp,
		$bot,
		$ip = '',
		$oldSize = 0,
		$newSize = 0,
		$newId = 0,
		$patrol = 0,
		$tags = [],
		?EditResult $editResult = null
	): RecentChange;

	/**
	 * Create a RecentChange for a new page
	 *
	 * @param string $timestamp Timestamp of the page creation to occur
	 * @param PageIdentity $page created page
	 * @param bool $minor Whether the page creation is minor
	 * @param UserIdentity $user User who made the page creation
	 * @param string $comment Summary of the page creation
	 * @param bool $bot Whether the page creation was made by a bot
	 * @param string $ip IP address of the user, if the page creation was made anonymously
	 * @param int $size Size of the new revision
	 * @param int $newId ID of the new revision
	 * @param int $patrol Whether the edit was patrolled (PRC_UNPATROLLED, PRC_PATROLLED, PRC_AUTOPATROLLED)
	 * @param string[] $tags
	 *
	 * @return RecentChange
	 */
	public function createNewPageRecentChange(
		$timestamp,
		$page,
		$minor,
		$user,
		$comment,
		$bot,
		$ip = '',
		$size = 0,
		$newId = 0,
		$patrol = 0,
		$tags = []
	): RecentChange;

	/**
	 * Create a RecentChange for a log entry
	 *
	 * @param string $timestamp Timestamp of the log entry to occur
	 * @param PageReference $logPage
	 * @param UserIdentity $user User who performed the log action
	 * @param string $actionComment Summary of the log action
	 * @param string $ip IP address of the user, if the log action was made anonymously
	 * @param string $type Log type
	 * @param string $action Log action
	 * @param PageReference $target Target of the log action
	 * @param string $logComment
	 * @param string $params
	 * @param int $newId
	 * @param string $actionCommentIRC IRC comment of the log action
	 * @param int $revId Id of associated revision, if any
	 * @param bool $isPatrollable Whether this log entry is patrollable
	 * @param bool|null $forceBotFlag Override the default behavior and set bot flag to
	 * 	the value of the argument. When omitted or null, it falls back to the global state.
	 *
	 * @return RecentChange
	 */
	public function createLogRecentChange(
		$timestamp,
		$logPage,
		$user,
		$actionComment,
		$ip,
		$type,
		$action,
		$target,
		$logComment,
		$params,
		$newId = 0,
		$actionCommentIRC = '',
		$revId = 0,
		$isPatrollable = false,
		$forceBotFlag = null
	): RecentChange;

	/**
	 * Create a RecentChange for a category membership change
	 *
	 * @param string $timestamp Timestamp of the recent change to occur
	 * @param PageIdentity $categoryTitle the category a page is being added to or removed from
	 * @param UserIdentity|null $user User object of the user that made the change
	 * @param string $comment Change summary
	 * @param PageIdentity $pageTitle the page that is being added or removed
	 * @param int $oldRevId Parent revision ID of this change
	 * @param int $newRevId Revision ID of this change
	 * @param string $lastTimestamp Parent revision timestamp of this change
	 * @param bool $bot true, if the change was made by a bot
	 * @param string $ip IP address of the user, if the change was made anonymously
	 * @param int $deleted Indicates whether the change has been deleted
	 * @param bool|null $added true, if the category was added, false for removed
	 * @param bool $forImport Whether the associated revision was imported
	 *
	 * @return RecentChange
	 */
	public function createCategorizationRecentChange(
		$timestamp,
		PageIdentity $categoryTitle,
		?UserIdentity $user,
		$comment,
		PageIdentity $pageTitle,
		$oldRevId,
		$newRevId,
		$lastTimestamp,
		$bot,
		$ip = '',
		$deleted = 0,
		$added = null,
		$forImport = false
	): RecentChange;

	/**
	 * Insert a recent change into the database.
	 *
	 * For compatibility reasons, the SEND_ constants internally reference a value
	 * that may seem negated from their purpose (none=true, feed=false). This is
	 * because the parameter used to be called "$noudp", defaulting to false.
	 *
	 * @param RecentChange $recentChange
	 * @param bool $send self::SEND_FEED or self::SEND_NONE
	 */
	public function insertRecentChange( RecentChange $recentChange, bool $send = self::SEND_FEED );
}
