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

namespace MediaWiki\Watchlist;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\TalkPageNotificationManager;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use NamespaceInfo;
use ReadOnlyMode;
use Status;
use StatusValue;
use User;
use WatchedItemStoreInterface;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;

/**
 * WatchlistManager service
 *
 * @since 1.35
 */
class WatchlistManager {

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::EnotifUserTalk,
		MainConfigNames::EnotifWatchlist,
		MainConfigNames::ShowUpdatedMarker,
	];

	/** @var ServiceOptions */
	private $options;

	/** @var HookRunner */
	private $hookRunner;

	/** @var ReadOnlyMode */
	private $readOnlyMode;

	/** @var RevisionLookup */
	private $revisionLookup;

	/** @var TalkPageNotificationManager */
	private $talkPageNotificationManager;

	/** @var WatchedItemStoreInterface */
	private $watchedItemStore;

	/** @var UserFactory */
	private $userFactory;

	/** @var NamespaceInfo */
	private $nsInfo;

	/** @var WikiPageFactory */
	private $wikiPageFactory;

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
	 * @param ReadOnlyMode $readOnlyMode
	 * @param RevisionLookup $revisionLookup
	 * @param TalkPageNotificationManager $talkPageNotificationManager
	 * @param WatchedItemStoreInterface $watchedItemStore
	 * @param UserFactory $userFactory
	 * @param NamespaceInfo $nsInfo
	 * @param WikiPageFactory $wikiPageFactory
	 */
	public function __construct(
		ServiceOptions $options,
		HookContainer $hookContainer,
		ReadOnlyMode $readOnlyMode,
		RevisionLookup $revisionLookup,
		TalkPageNotificationManager $talkPageNotificationManager,
		WatchedItemStoreInterface $watchedItemStore,
		UserFactory $userFactory,
		NamespaceInfo $nsInfo,
		WikiPageFactory $wikiPageFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->readOnlyMode = $readOnlyMode;
		$this->revisionLookup = $revisionLookup;
		$this->talkPageNotificationManager = $talkPageNotificationManager;
		$this->watchedItemStore = $watchedItemStore;
		$this->userFactory = $userFactory;
		$this->nsInfo = $nsInfo;
		$this->wikiPageFactory = $wikiPageFactory;
	}

	/**
	 * Resets all of the given user's page-change notification timestamps.
	 * If e-notif e-mails are on, they will receive notification mails on
	 * the next change of any watched page.
	 *
	 * @note If the user doesn't have 'editmywatchlist', this will do nothing.
	 *
	 * @param Authority|UserIdentity $performer deprecated passing UserIdentity since 1.37
	 */
	public function clearAllUserNotifications( $performer ) {
		if ( $this->readOnlyMode->isReadOnly() ) {
			// Cannot change anything in read only
			return;
		}

		if ( !$performer instanceof Authority ) {
			$performer = $this->userFactory->newFromUserIdentity( $performer );
		}

		if ( !$performer->isAllowed( 'editmywatchlist' ) ) {
			// User isn't allowed to edit the watchlist
			return;
		}

		$user = $performer->getUser();

		if ( !$this->options->get( MainConfigNames::EnotifUserTalk ) &&
			!$this->options->get( MainConfigNames::EnotifWatchlist ) &&
			!$this->options->get( MainConfigNames::ShowUpdatedMarker )
		) {
			$this->talkPageNotificationManager->removeUserHasNewMessages( $user );
			return;
		}

		if ( !$user->isRegistered() ) {
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
	 * @param Authority|UserIdentity $performer deprecated passing UserIdentity since 1.37
	 * @param LinkTarget|PageIdentity $title deprecated passing LinkTarget since 1.37
	 * @param int $oldid The revision id being viewed. If not given or 0, latest revision is assumed.
	 * @param RevisionRecord|null $oldRev The revision record associated with $oldid, or null if
	 *   the latest revision is used
	 */
	public function clearTitleUserNotifications(
		$performer,
		$title,
		int $oldid = 0,
		RevisionRecord $oldRev = null
	) {
		if ( $this->readOnlyMode->isReadOnly() ) {
			// Cannot change anything in read only
			return;
		}

		if ( !$performer instanceof Authority ) {
			$performer = $this->userFactory->newFromUserIdentity( $performer );
		}

		if ( !$performer->isAllowed( 'editmywatchlist' ) ) {
			// User isn't allowed to edit the watchlist
			return;
		}

		$userIdentity = $performer->getUser();
		$userTalkPage = (
			$title->getNamespace() === NS_USER_TALK &&
			$title->getDBkey() === strtr( $userIdentity->getName(), ' ', '_' )
		);

		if ( $userTalkPage ) {
			if ( !$oldid ) {
				$oldRev = null;
			} elseif ( !$oldRev ) {
				$oldRev = $this->revisionLookup->getRevisionById( $oldid );
			}
			$this->talkPageNotificationManager->clearForPageView( $userIdentity, $oldRev );
		}

		if ( !$this->options->get( MainConfigNames::EnotifUserTalk ) &&
			!$this->options->get( MainConfigNames::EnotifWatchlist ) &&
			!$this->options->get( MainConfigNames::ShowUpdatedMarker )
		) {
			return;
		}

		if ( !$userIdentity->isRegistered() ) {
			// Nothing else to do
			return;
		}

		// Only update the timestamp if the page is being watched.
		// The query to find out if it is watched is cached both in memcached and per-invocation,
		// and when it does have to be executed, it can be on a replica DB
		// If this is the user's newtalk page, we always update the timestamp
		$force = $userTalkPage ? 'force' : '';
		$this->watchedItemStore->resetNotificationTimestamp( $userIdentity, $title, $force, $oldid );
	}

	/**
	 * Get the timestamp when this page was updated since the user last saw it.
	 *
	 * @param UserIdentity $user
	 * @param LinkTarget|PageIdentity $title deprecated passing LinkTarget since 1.37
	 * @return string|bool|null String timestamp, false if not watched, null if nothing is unseen
	 */
	public function getTitleNotificationTimestamp( UserIdentity $user, $title ) {
		if ( !$user->isRegistered() ) {
			return false;
		}

		$cacheKey = 'u' . (string)$user->getId() . '-' .
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

	/**
	 * @since 1.37
	 * @param PageReference $target
	 * @return bool
	 */
	public function isWatchable( PageReference $target ): bool {
		if ( !$this->nsInfo->isWatchable( $target->getNamespace() ) ) {
			return false;
		}

		if ( $target instanceof PageIdentity && !$target->canExist() ) {
			// Catch "improper" Title instances
			return false;
		}

		return true;
	}

	/**
	 * Check if the page is watched by the user.
	 * @since 1.37
	 * @param UserIdentity $userIdentity
	 * @param PageIdentity $target
	 * @return bool
	 */
	public function isWatchedIgnoringRights( UserIdentity $userIdentity, PageIdentity $target ): bool {
		if ( $this->isWatchable( $target ) ) {
			return $this->watchedItemStore->isWatched( $userIdentity, $target );
		}
		return false;
	}

	/**
	 * Check if the page is watched by the user and the user has permission to view their
	 * watchlist.
	 * @since 1.37
	 * @param Authority $performer
	 * @param PageIdentity $target
	 * @return bool
	 */
	public function isWatched( Authority $performer, PageIdentity $target ): bool {
		if ( $performer->isAllowed( 'viewmywatchlist' ) ) {
			return $this->isWatchedIgnoringRights( $performer->getUser(), $target );
		}
		return false;
	}

	/**
	 * Check if the article is temporarily watched by the user.
	 * @since 1.37
	 * @param UserIdentity $userIdentity
	 * @param PageIdentity $target
	 * @return bool
	 */
	public function isTempWatchedIgnoringRights( UserIdentity $userIdentity, PageIdentity $target ): bool {
		if ( $this->isWatchable( $target ) ) {
			return $this->watchedItemStore->isTempWatched( $userIdentity, $target );
		}
		return false;
	}

	/**
	 * Check if the page is temporarily watched by the user and the user has permission to view
	 * their watchlist.
	 * @since 1.37
	 * @param Authority $performer
	 * @param PageIdentity $target
	 * @return bool
	 */
	public function isTempWatched( Authority $performer, PageIdentity $target ): bool {
		if ( $performer->isAllowed( 'viewmywatchlist' ) ) {
			return $this->isTempWatchedIgnoringRights( $performer->getUser(), $target );
		}
		return false;
	}

	/**
	 * Watch a page. Calls the WatchArticle and WatchArticleComplete hooks.
	 * @since 1.37
	 * @param UserIdentity $userIdentity
	 * @param PageIdentity $target
	 * @param string|null $expiry Optional expiry timestamp in any format acceptable to wfTimestamp(),
	 *   null will not create expiries, or leave them unchanged should they already exist.
	 * @return StatusValue
	 */
	public function addWatchIgnoringRights(
		UserIdentity $userIdentity,
		PageIdentity $target,
		?string $expiry = null
	): StatusValue {
		if ( !$this->isWatchable( $target ) ) {
			return StatusValue::newFatal( 'watchlistnotwatchable' );
		}

		$wikiPage = $this->wikiPageFactory->newFromTitle( $target );
		$title = $wikiPage->getTitle();

		// TODO: update hooks to take Authority
		$status = Status::newFatal( 'hookaborted' );
		$user = $this->userFactory->newFromUserIdentity( $userIdentity );
		if ( $this->hookRunner->onWatchArticle( $user, $wikiPage, $status, $expiry ) ) {
			$status = StatusValue::newGood();
			$this->watchedItemStore->addWatch( $userIdentity, $this->nsInfo->getSubjectPage( $title ), $expiry );
			if ( $this->nsInfo->canHaveTalkPage( $title ) ) {
				$this->watchedItemStore->addWatch( $userIdentity, $this->nsInfo->getTalkPage( $title ), $expiry );
			}
			$this->hookRunner->onWatchArticleComplete( $user, $wikiPage );
		}

		// eventually user_touched should be factored out of User and this should be replaced
		$user->invalidateCache();

		return $status;
	}

	/**
	 * Watch a page if the user has permission to edit their watchlist.
	 * Calls the WatchArticle and WatchArticleComplete hooks.
	 * @since 1.37
	 * @param Authority $performer
	 * @param PageIdentity $target
	 * @param string|null $expiry Optional expiry timestamp in any format acceptable to wfTimestamp(),
	 *   null will not create expiries, or leave them unchanged should they already exist.
	 * @return StatusValue
	 */
	public function addWatch(
		Authority $performer,
		PageIdentity $target,
		?string $expiry = null
	): StatusValue {
		if ( !$performer->isAllowed( 'editmywatchlist' ) ) {
			// TODO: this function should be moved out of User
			return User::newFatalPermissionDeniedStatus( 'editmywatchlist' );
		}

		return $this->addWatchIgnoringRights( $performer->getUser(), $target, $expiry );
	}

	/**
	 * Stop watching a page. Calls the UnwatchArticle and UnwatchArticleComplete hooks.
	 * @since 1.37
	 * @param UserIdentity $userIdentity
	 * @param PageIdentity $target
	 * @return StatusValue
	 */
	public function removeWatchIgnoringRights(
		UserIdentity $userIdentity,
		PageIdentity $target
	): StatusValue {
		if ( !$this->isWatchable( $target ) ) {
			return StatusValue::newFatal( 'watchlistnotwatchable' );
		}

		$wikiPage = $this->wikiPageFactory->newFromTitle( $target );
		$title = $wikiPage->getTitle();

		// TODO: update hooks to take Authority
		$status = Status::newFatal( 'hookaborted' );
		$user = $this->userFactory->newFromUserIdentity( $userIdentity );
		if ( $this->hookRunner->onUnwatchArticle( $user, $wikiPage, $status ) ) {
			$status = StatusValue::newGood();
			$this->watchedItemStore->removeWatch( $userIdentity, $this->nsInfo->getSubjectPage( $title ) );
			if ( $this->nsInfo->canHaveTalkPage( $title ) ) {
				$this->watchedItemStore->removeWatch( $userIdentity, $this->nsInfo->getTalkPage( $title ) );
			}
			$this->hookRunner->onUnwatchArticleComplete( $user, $wikiPage );
		}

		// eventually user_touched should be factored out of User and this should be replaced
		$user->invalidateCache();

		return $status;
	}

	/**
	 * Stop watching a page if the user has permission to edit their watchlist.
	 * Calls the UnwatchArticle and UnwatchArticleComplete hooks.
	 * @since 1.37
	 * @param Authority $performer
	 * @param PageIdentity $target
	 * @return StatusValue
	 */
	public function removeWatch(
		Authority $performer,
		PageIdentity $target
	): StatusValue {
		if ( !$performer->isAllowed( 'editmywatchlist' ) ) {
			// TODO: this function should be moved out of User
			return User::newFatalPermissionDeniedStatus( 'editmywatchlist' );
		}

		return $this->removeWatchIgnoringRights( $performer->getUser(), $target );
	}

	/**
	 * Watch or unwatch a page, calling watch/unwatch hooks as appropriate.
	 * Checks before watching or unwatching to see if the page is already in the requested watch
	 * state and if the expiry is the same so it does not act unnecessarily.
	 *
	 * @param bool $watch Whether to watch or unwatch the page
	 * @param Authority $performer who is watching/unwatching
	 * @param PageIdentity $target Page to watch/unwatch
	 * @param string|null $expiry Optional expiry timestamp in any format acceptable to wfTimestamp(),
	 *   null will not create expiries, or leave them unchanged should they already exist.
	 * @return StatusValue
	 * @since 1.37
	 */
	public function setWatch(
		bool $watch,
		Authority $performer,
		PageIdentity $target,
		string $expiry = null
	): StatusValue {
		// User must be registered, and either changing the watch state or at least the expiry.
		if ( !$performer->getUser()->isRegistered() ) {
			return StatusValue::newGood();
		}

		// Only call addWatchIgnoringRights() or removeWatch() if there's been a change in the watched status.
		$oldWatchedItem = $this->watchedItemStore->getWatchedItem( $performer->getUser(), $target );
		$changingWatchStatus = (bool)$oldWatchedItem !== $watch;
		if ( $oldWatchedItem && $expiry !== null ) {
			// If there's an old watched item, a non-null change to the expiry requires an UPDATE.
			$oldWatchPeriod = $oldWatchedItem->getExpiry() ?? 'infinity';
			$changingWatchStatus = $changingWatchStatus ||
				$oldWatchPeriod !== ExpiryDef::normalizeExpiry( $expiry, TS_MW );
		}

		if ( $changingWatchStatus ) {
			// If the user doesn't have 'editmywatchlist', we still want to
			// allow them to add but not remove items via edits and such.
			if ( $watch ) {
				return $this->addWatchIgnoringRights( $performer->getUser(), $target, $expiry );
			} else {
				return $this->removeWatch( $performer, $target );
			}
		}

		return StatusValue::newGood();
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.36
 */
class_alias( WatchlistManager::class, 'MediaWiki\User\WatchlistNotificationManager' );
