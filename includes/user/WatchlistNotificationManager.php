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
 * @author DannyS712
 */

namespace MediaWiki\User;

use DeferredUpdates;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Revision\RevisionLookup;
use ReadOnlyMode;
use WatchedItemStoreInterface;

/**
 * WatchlistNotificationManager service
 *
 * @since 1.35
 */
class WatchlistNotificationManager {

	public const CONSTRUCTOR_OPTIONS = [
		'UseEnotif',
		'ShowUpdatedMarker',
	];

	/** @var ServiceOptions */
	private $options;

	/** @var HookRunner */
	private $hookRunner;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var ReadOnlyMode */
	private $readOnlyMode;

	/** @var RevisionLookup */
	private $revisionLookup;

	/** @var TalkPageNotificationManager */
	private $talkPageNotificationManager;

	/** @var WatchedItemStoreInterface */
	private $watchedItemStore;

	/**
	 * @var array
	 *
	 * Cache for getTitleNotificationTimestamp
	 *
	 * Keys need to reflect both the specific user and the title:
	 *
	 * Since only users have watchlists, the user is represented with `u⧼user id⧽`
	 *
	 * Since the method accepts LinkTarget objects, cannot rely on the object's toString,
	 *     since it is different for TitleValue and Title. Implement a simplified string
	 *     representation of the string that TitleValue uses: `⧼namespace number⧽:⧼db key⧽`
	 *
	 * Entries are in the form of
	 *     u⧼user id⧽-⧼namespace number⧽:⧼db key⧽ => ⧼timestamp or false⧽
	 */
	private $notificationTimestampCache = [];

	/**
	 * @param ServiceOptions $options
	 * @param HookContainer $hookContainer
	 * @param PermissionManager $permissionManager
	 * @param ReadOnlyMode $readOnlyMode
	 * @param RevisionLookup $revisionLookup
	 * @param TalkPageNotificationManager $talkPageNotificationManager
	 * @param WatchedItemStoreInterface $watchedItemStore
	 */
	public function __construct(
		ServiceOptions $options,
		HookContainer $hookContainer,
		PermissionManager $permissionManager,
		ReadOnlyMode $readOnlyMode,
		RevisionLookup $revisionLookup,
		TalkPageNotificationManager $talkPageNotificationManager,
		WatchedItemStoreInterface $watchedItemStore
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->permissionManager = $permissionManager;
		$this->readOnlyMode = $readOnlyMode;
		$this->revisionLookup = $revisionLookup;
		$this->talkPageNotificationManager = $talkPageNotificationManager;
		$this->watchedItemStore = $watchedItemStore;
	}

	/**
	 * Resets all of the given user's page-change notification timestamps.
	 * If e-notif e-mails are on, they will receive notification mails on
	 * the next change of any watched page.
	 *
	 * @note If the user doesn't have 'editmywatchlist', this will do nothing.
	 *
	 * @param UserIdentity $user
	 */
	public function clearAllUserNotifications( UserIdentity $user ) {
		if ( $this->readOnlyMode->isReadOnly() ) {
			// Cannot change anything in read only
			return;
		}

		if ( !$this->permissionManager->userHasRight( $user, 'editmywatchlist' ) ) {
			// User isn't allowed to edit the watchlist
			return;
		}

		if ( !$this->options->get( 'UseEnotif' ) &&
			!$this->options->get( 'ShowUpdatedMarker' )
		) {
			$this->talkPageNotificationManager->removeUserHasNewMessages( $user );
			return;
		}

		$userId = $user->getId();
		if ( !$userId ) {
			return;
		}

		$this->watchedItemStore->resetAllNotificationTimestampsForUser( $user );

		// We also need to clear here the "you have new message" notification for the own
		// user_talk page; it's cleared one page view later in WikiPage::doViewUpdates().
	}

	/**
	 * Clear the user's notification timestamp for the given title.
	 * If e-notif e-mails are on, they will receive notification mails on
	 * the next change of the page if it's watched etc.
	 *
	 * @note If the user doesn't have 'editmywatchlist', this will do nothing.
	 *
	 * @param UserIdentity $user
	 * @param LinkTarget $title
	 * @param int $oldid The revision id being viewed. If not given or 0, latest revision is assumed.
	 */
	public function clearTitleUserNotifications(
		UserIdentity $user,
		LinkTarget $title,
		int $oldid = 0
	) {
		if ( $this->readOnlyMode->isReadOnly() ) {
			// Cannot change anything in read only
			return;
		}

		if ( !$this->permissionManager->userHasRight( $user, 'editmywatchlist' ) ) {
			// User isn't allowed to edit the watchlist
			return;
		}

		$userTalkPage = (
			$title->getNamespace() === NS_USER_TALK &&
			$title->getText() === $user->getName()
		);

		if ( $userTalkPage ) {
			// If we're working on user's talk page, we should update the talk page message indicator
			if ( !$this->hookRunner->onUserClearNewTalkNotification( $user, $oldid ) ) {
				return;
			}

			// Try to update the DB post-send and only if needed...
			$talkPageNotificationManager = $this->talkPageNotificationManager;
			$revisionLookup = $this->revisionLookup;
			DeferredUpdates::addCallableUpdate( function () use (
				$user,
				$oldid,
				$talkPageNotificationManager,
				$revisionLookup
			) {
				if ( !$talkPageNotificationManager->userHasNewMessages( $user ) ) {
					// no notifications to clear
					return;
				}
				// Delete the last notifications (they stack up)
				$talkPageNotificationManager->removeUserHasNewMessages( $user );

				// If there is a new, unseen, revision, use its timestamp
				if ( !$oldid ) {
					return;
				}

				$oldRev = $revisionLookup->getRevisionById(
					$oldid,
					RevisionLookup::READ_LATEST
				);
				if ( !$oldRev ) {
					return;
				}

				$newRev = $revisionLookup->getNextRevision( $oldRev );
				if ( $newRev ) {
					$talkPageNotificationManager->setUserHasNewMessages(
						$user,
						$newRev
					);
				}
			} );
		}

		if ( !$this->options->get( 'UseEnotif' ) &&
			!$this->options->get( 'ShowUpdatedMarker' )
		) {
			return;
		}

		if ( !$user->isRegistered() ) {
			// Nothing else to do
			return;
		}

		// Only update the timestamp if the page is being watched.
		// The query to find out if it is watched is cached both in memcached and per-invocation,
		// and when it does have to be executed, it can be on a replica DB
		// If this is the user's newtalk page, we always update the timestamp
		$force = $userTalkPage ? 'force' : '';
		$this->watchedItemStore->resetNotificationTimestamp( $user, $title, $force, $oldid );
	}

	/**
	 * Get the timestamp when this page was updated since the user last saw it.
	 *
	 * @param UserIdentity $user
	 * @param LinkTarget $title
	 * @return string|bool|null String timestamp, false if not watched, null if nothing is unseen
	 */
	public function getTitleNotificationTimestamp( UserIdentity $user, LinkTarget $title ) {
		$userId = $user->getId();

		if ( !$userId ) {
			return false;
		}

		$cacheKey = 'u' . (string)$userId . '-' .
			(string)$title->getNamespace() . ':' . $title->getDBkey();

		// avoid isset here, as it'll return false for null entries
		if ( array_key_exists( $cacheKey, $this->notificationTimestampCache ) ) {
			return $this->notificationTimestampCache[ $cacheKey ];
		}

		$watchedItem = $this->watchedItemStore->getWatchedItem( $user, $title );
		if ( $watchedItem ) {
			$timestamp = $watchedItem->getNotificationTimestamp();
		} else {
			$timestamp = false;
		}

		$this->notificationTimestampCache[ $cacheKey ] = $timestamp;
		return $timestamp;
	}

}
