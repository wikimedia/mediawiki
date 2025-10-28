<?php

/**
 * @license GPL-2.0-or-later
 * @file
 * @author DannyS712
 */

namespace MediaWiki\Watchlist;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Status\Status;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\User\TalkPageNotificationManager;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use StatusValue;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * WatchlistManager service
 *
 * @since 1.35
 */
class WatchlistManager {

	/**
	 * @internal For use by ServiceWiring
	 */
	public const OPTION_ENOTIF = 'isEnotifEnabled';

	/** @var bool */
	private $isEnotifEnabled;

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
	 * @param array{isEnotifEnabled:bool} $options
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
		array $options,
		HookContainer $hookContainer,
		ReadOnlyMode $readOnlyMode,
		RevisionLookup $revisionLookup,
		TalkPageNotificationManager $talkPageNotificationManager,
		WatchedItemStoreInterface $watchedItemStore,
		UserFactory $userFactory,
		NamespaceInfo $nsInfo,
		WikiPageFactory $wikiPageFactory
	) {
		$this->isEnotifEnabled = $options[ self::OPTION_ENOTIF ];
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
	 * @param Authority $performer
	 */
	public function clearAllUserNotifications( Authority $performer ) {
		if ( $this->readOnlyMode->isReadOnly() ) {
			// Cannot change anything in read only
			return;
		}

		$user = $performer->getUser();

		// NOTE: Has to be before `editmywatchlist` user right check, to ensure
		// anonymous / temporary users have their talk page notifications cleared (T345031).
		if ( !$this->isEnotifEnabled ) {
			$this->talkPageNotificationManager->removeUserHasNewMessages( $user );
			return;
		}

		if ( !$performer->isAllowed( 'editmywatchlist' ) ) {
			// User isn't allowed to edit the watchlist
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
	 * @param Authority $performer
	 * @param PageReference $title
	 * @param int $oldid The revision id being viewed. If not given or 0, latest revision is assumed.
	 * @param RevisionRecord|null $oldRev The revision record associated with $oldid, or null if
	 *   the latest revision is used
	 */
	public function clearTitleUserNotifications(
		Authority $performer,
		PageReference $title,
		int $oldid = 0,
		?RevisionRecord $oldRev = null
	) {
		if ( $this->readOnlyMode->isReadOnly() ) {
			// Cannot change anything in read only
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
			// NOTE: Has to be called before isAllowed() check, to ensure users with no watchlist
			// access (by default, temporary and anonymous users) can clear their talk page
			// notification (T345031).
			$this->talkPageNotificationManager->clearForPageView( $userIdentity, $oldRev );
		}

		if ( !$this->isEnotifEnabled ) {
			return;
		}

		if ( !$userIdentity->isRegistered() ) {
			// Nothing else to do
			return;
		}

		// NOTE: Has to be checked after the TalkPageNotificationManager::clearForPageView call, to
		// ensure users with no watchlist access (by default, temporary and anonymous users) can
		// clear their talk page notification (T345031).
		if ( !$performer->isAllowed( 'editmywatchlist' ) ) {
			// User isn't allowed to edit the watchlist
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
	 * @param PageReference $title
	 * @return string|bool|null String timestamp, false if not watched, null if nothing is unseen
	 */
	public function getTitleNotificationTimestamp( UserIdentity $user, PageReference $title ) {
		if ( !$user->isRegistered() ) {
			return false;
		}

		$cacheKey = 'u' . $user->getId() . '-' .
			$title->getNamespace() . ':' . $title->getDBkey();

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
	 * @param PageReference $target
	 * @return bool
	 */
	public function isWatchedIgnoringRights( UserIdentity $userIdentity, PageReference $target ): bool {
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
	 * @param PageReference $target
	 * @return bool
	 */
	public function isWatched( Authority $performer, PageReference $target ): bool {
		if ( $performer->isAllowed( 'viewmywatchlist' ) ) {
			return $this->isWatchedIgnoringRights( $performer->getUser(), $target );
		}
		return false;
	}

	/**
	 * Check if the article is temporarily watched by the user.
	 * @since 1.37
	 * @param UserIdentity $userIdentity
	 * @param PageReference $target
	 * @return bool
	 */
	public function isTempWatchedIgnoringRights( UserIdentity $userIdentity, PageReference $target ): bool {
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
	 * @param PageReference $target
	 * @return bool
	 */
	public function isTempWatched( Authority $performer, PageReference $target ): bool {
		if ( $performer->isAllowed( 'viewmywatchlist' ) ) {
			return $this->isTempWatchedIgnoringRights( $performer->getUser(), $target );
		}
		return false;
	}

	/**
	 * Watch a page. Calls the WatchArticle and WatchArticleComplete hooks.
	 * @since 1.37
	 * @param UserIdentity $userIdentity
	 * @param PageReference $target
	 * @param string|null $expiry Optional expiry timestamp in any format acceptable to wfTimestamp(),
	 *   null will not create expiries, or leave them unchanged should they already exist.
	 * @return StatusValue
	 */
	public function addWatchIgnoringRights(
		UserIdentity $userIdentity,
		PageReference $target,
		?string $expiry = null
	): StatusValue {
		if ( !$this->isWatchable( $target ) ) {
			return StatusValue::newFatal( 'watchlistnotwatchable' );
		}

		$wikiPage = $this->wikiPageFactory->newFromTitle( $target );
		$title = $wikiPage->getTitle();

		$status = Status::newFatal( 'hookaborted' );
		// TODO: broaden the interface on these hooks to accept PageReference
		if ( $this->hookRunner->onWatchArticle( $userIdentity, $wikiPage, $status, $expiry ) ) {
			$status = StatusValue::newGood();
			$this->watchedItemStore->addWatch( $userIdentity, $this->getSubjectPage( $title ), $expiry );
			if ( $this->nsInfo->canHaveTalkPage( $title ) ) {
				$this->watchedItemStore->addWatch( $userIdentity, $this->getTalkPage( $title ), $expiry );
			}
			$this->hookRunner->onWatchArticleComplete( $userIdentity, $wikiPage );
		}

		// eventually user_touched should be factored out of User and this should be replaced
		$user = $this->userFactory->newFromUserIdentity( $userIdentity );
		$user->invalidateCache();

		return $status;
	}

	/**
	 * Watch a page if the user has permission to edit their watchlist.
	 * Calls the WatchArticle and WatchArticleComplete hooks.
	 * @since 1.37
	 * @param Authority $performer
	 * @param PageReference $target
	 * @param string|null $expiry Optional expiry timestamp in any format acceptable to wfTimestamp(),
	 *   null will not create expiries, or leave them unchanged should they already exist.
	 * @return StatusValue
	 */
	public function addWatch(
		Authority $performer,
		PageReference $target,
		?string $expiry = null
	): StatusValue {
		$status = PermissionStatus::newEmpty();
		if ( !$performer->isAllowed( 'editmywatchlist', $status ) ) {
			return $status;
		}

		return $this->addWatchIgnoringRights( $performer->getUser(), $target, $expiry );
	}

	/**
	 * Stop watching a page. Calls the UnwatchArticle and UnwatchArticleComplete hooks.
	 * @since 1.37
	 * @param UserIdentity $userIdentity
	 * @param PageReference $target
	 * @return StatusValue
	 */
	public function removeWatchIgnoringRights(
		UserIdentity $userIdentity,
		PageReference $target
	): StatusValue {
		if ( !$this->isWatchable( $target ) ) {
			return StatusValue::newFatal( 'watchlistnotwatchable' );
		}

		$wikiPage = $this->wikiPageFactory->newFromTitle( $target );
		$title = $wikiPage->getTitle();

		$status = Status::newFatal( 'hookaborted' );
		// TODO broaden the interface on these hooks from WikiPage to PageReference
		if ( $this->hookRunner->onUnwatchArticle( $userIdentity, $wikiPage, $status ) ) {
			$status = StatusValue::newGood();
			$this->watchedItemStore->removeWatch( $userIdentity, $this->getSubjectPage( $title ) );
			if ( $this->nsInfo->canHaveTalkPage( $title ) ) {
				$this->watchedItemStore->removeWatch( $userIdentity, $this->getTalkPage( $title ) );
			}
			$this->hookRunner->onUnwatchArticleComplete( $userIdentity, $wikiPage );
		}

		// eventually user_touched should be factored out of User and this should be replaced
		$user = $this->userFactory->newFromUserIdentity( $userIdentity );
		$user->invalidateCache();

		return $status;
	}

	/**
	 * Like NamespaceInfo::getSubjectPage() but acting on a PageReference
	 *
	 * @param PageReference $title
	 * @return PageReference
	 */
	private function getSubjectPage( PageReference $title ): PageReference {
		if ( $this->nsInfo->isSubject( $title->getNamespace() ) ) {
			return $title;
		}
		return PageReferenceValue::localReference(
			$this->nsInfo->getSubject( $title->getNamespace() ),
			$title->getDBkey()
		);
	}

	/**
	 * Like NamespaceInfo::getTalkPage() but acting on a PageReference
	 *
	 * @param PageReference $title
	 * @return PageReference
	 */
	private function getTalkPage( PageReference $title ): PageReference {
		if ( $this->nsInfo->isTalk( $title->getNamespace() ) ) {
			return $title;
		}
		return PageReferenceValue::localReference(
			$this->nsInfo->getTalk( $title->getNamespace() ),
			$title->getDBkey()
		);
	}

	/**
	 * Stop watching a page if the user has permission to edit their watchlist.
	 * Calls the UnwatchArticle and UnwatchArticleComplete hooks.
	 * @since 1.37
	 * @param Authority $performer
	 * @param PageReference $target
	 * @return StatusValue
	 */
	public function removeWatch(
		Authority $performer,
		PageReference $target
	): StatusValue {
		$status = PermissionStatus::newEmpty();
		if ( !$performer->isAllowed( 'editmywatchlist', $status ) ) {
			return $status;
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
	 * @param PageReference $target Page to watch/unwatch
	 * @param string|null $expiry Optional expiry timestamp in any format acceptable to wfTimestamp(),
	 *   null will not create expiries, or leave them unchanged should they already exist.
	 * @return StatusValue
	 * @since 1.37
	 */
	public function setWatch(
		bool $watch,
		Authority $performer,
		PageReference $target,
		?string $expiry = null
	): StatusValue {
		// User must be registered, and (T371091) not a temp user
		if ( !$performer->getUser()->isRegistered() || $performer->isTemp() ) {
			return StatusValue::newGood();
		}

		// User must be either changing the watch state or at least the expiry.

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
