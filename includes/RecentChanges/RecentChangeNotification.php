<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RecentChanges;

use MediaWiki\Notification\Types\WikiNotification;
use MediaWiki\Page\PageIdentity;
use MediaWiki\User\UserIdentity;

/**
 * Notification representing a Recent change. Used as base to transport Watchlist, UserTalk and
 * Administrator recent changes notifications
 *
 * @newable
 * @since 1.45
 */
class RecentChangeNotification extends WikiNotification {

	public const TYPE = 'mediawiki.recent_change';

	public const TALK_NOTIFICATION = 'talk';
	public const ADMIN_NOTIFICATION = 'admin';
	public const WATCHLIST_NOTIFICATION = 'watchlist';

	private RecentChange $recentChange;
	private string $pageStatus;
	private string $source;

	/**
	 * @param UserIdentity $editor
	 * @param PageIdentity $title
	 * @param RecentChange $recentChange
	 * @param string $pageStatus the Page status from RecentChange, this change is not persisted
	 * @param string $source one of types talk, admin or watchlist
	 */
	public function __construct(
		UserIdentity $editor,
		PageIdentity $title,
		RecentChange $recentChange,
		string $pageStatus,
		string $source
	) {
		parent::__construct( self::TYPE, $title, $editor );
		$this->source = $source;
		$this->pageStatus  = $pageStatus;
		$this->recentChange = $recentChange;
	}

	public function getRecentChange(): RecentChange {
		return $this->recentChange;
	}

	/**
	 * Retrieve page state, list provided by MediaWiki is
	 * [ 'deleted', 'created', 'moved', 'restored', 'changed' ]
	 * but additionally extensions can add their own states.
	 */
	public function getPageStatus(): string {
		return $this->pageStatus;
	}

	/**
	 * What is the notification source, is it a User Talk change, Watchlist notification or
	 * an admin notification ( Users loaded from UsersNotifiedOnAllChanges )
	 * @return string
	 */
	public function getSource(): string {
		return $this->source;
	}

	public function isUserTalkNotification(): bool {
		return $this->source === self::TALK_NOTIFICATION;
	}

	public function isWatchlistNotification(): bool {
		return $this->source === self::WATCHLIST_NOTIFICATION;
	}

}

/** @deprecated class alias since 1.45 */
class_alias( RecentChangeNotification::class, 'MediaWiki\\Watchlist\\RecentChangeNotification' );
