<?php
/**
 * Performs the watch actions on a page
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 * @ingroup Actions
 */

use MediaWiki\MediaWikiServices;

/**
 * Page addition to a user's watchlist
 *
 * @ingroup Actions
 */
class WatchAction extends FormAction {

	/** @var bool The value of the $wgWatchlistExpiry configuration variable. */
	protected $watchlistExpiry;

	/** @var string */
	protected $expiryFormFieldName = 'expiry';

	/** @var false|WatchedItem */
	protected $watchedItem = false;

	/**
	 * Only public since 1.21
	 *
	 * @param Page $page
	 * @param IContextSource|null $context
	 */
	public function __construct( Page $page, IContextSource $context = null ) {
		parent::__construct( $page, $context );
		$this->watchlistExpiry = $this->getContext()->getConfig()->get( 'WatchlistExpiry' );
		if ( $this->watchlistExpiry ) {
			// The watchedItem is only used in this action's form if $wgWatchlistExpiry is enabled.
			$this->watchedItem = MediaWikiServices::getInstance()
				->getWatchedItemStore()
				->getWatchedItem( $this->getUser(), $this->getTitle() );
		}
	}

	public function getName() {
		return 'watch';
	}

	public function requiresUnblock() {
		return false;
	}

	protected function getDescription() {
		return '';
	}

	public function onSubmit( $data ) {
		$expiry = $this->getRequest()->getText( 'wp' . $this->expiryFormFieldName );
		return self::doWatch( $this->getTitle(), $this->getUser(), User::CHECK_USER_RIGHTS, $expiry );
	}

	protected function checkCanExecute( User $user ) {
		// Must be logged in
		if ( $user->isAnon() ) {
			throw new UserNotLoggedIn( 'watchlistanontext', 'watchnologin' );
		}

		parent::checkCanExecute( $user );
	}

	protected function usesOOUI() {
		return true;
	}

	protected function getFormFields() {
		// If watchlist expiry is not enabled, return a simple confirmation message.
		if ( !$this->watchlistExpiry ) {
			return [
				'intro' => [
					'type' => 'info',
					'vertical-label' => true,
					'raw' => true,
					'default' => $this->msg( 'confirm-watch-top' )->parse(),
				],
			];
		}

		// Otherwise, use a select-list of expiries.
		$expiryOptions = static::getExpiryOptions( $this->getContext(), $this->watchedItem );
		return [
			$this->expiryFormFieldName => [
				'type' => 'select',
				'label-message' => 'confirm-watch-label',
				'options' => $expiryOptions['options'],
				'default' => $expiryOptions['default'],
			]
		];
	}

	/**
	 * Get options and default for a watchlist expiry select list. If an expiry time is provided, it
	 * will be added to the top of the list as 'x days left'.
	 *
	 * @since 1.35
	 * @todo Move this somewhere better when it's being used in more than just this action.
	 *
	 * @param MessageLocalizer $msgLocalizer
	 * @param WatchedItem|bool $watchedItem
	 *
	 * @return mixed[] With keys `options` (string[]) and `default` (string).
	 */
	public static function getExpiryOptions( MessageLocalizer $msgLocalizer, $watchedItem ) {
		$expiryOptionsMsg = $msgLocalizer->msg( 'watchlist-expiry-options' );
		$expiryOptionsMsgText = $expiryOptionsMsg->text();
		$expiryOptions = XmlSelect::parseOptionsMessage( $expiryOptionsMsgText );
		$default = 'infinite';
		if ( $watchedItem instanceof WatchedItem && $watchedItem->getExpiry() ) {
			// If it's already being temporarily watched,
			// add the existing expiry as the default option in the dropdown.
			$expiry = MWTimestamp::getInstance( $watchedItem->getExpiry() );
			$diffInDays = $watchedItem->getExpiryInDays();
			$daysLeft = $msgLocalizer->msg( 'watchlist-expiry-days-left', [ $diffInDays ] )->text();
			$expiryOptions = array_merge(
				[ $daysLeft => $expiry->getTimestamp( TS_MW ) ],
				$expiryOptions
			);
			$default = $expiry->getTimestamp( TS_MW );
		}
		return [
			'options' => $expiryOptions,
			'default' => $default,
		];
	}

	protected function alterForm( HTMLForm $form ) {
		$msg = $this->watchlistExpiry && $this->watchedItem ? 'updatewatchlist' : 'addwatch';
		$form->setWrapperLegendMsg( $msg );
		$submitMsg = $this->watchlistExpiry ? 'confirm-watch-button-expiry' : 'confirm-watch-button';
		$form->setSubmitTextMsg( $submitMsg );
		$form->setTokenSalt( 'watch' );
	}

	public function onSuccess() {
		$msgKey = $this->getTitle()->isTalkPage() ? 'addedwatchtext-talk' : 'addedwatchtext';
		$this->getOutput()->addWikiMsg( $msgKey, $this->getTitle()->getPrefixedText() );
	}

	/**
	 * Watch or unwatch a page
	 * @since 1.22
	 * @since 1.35 New $expiry parameter.
	 * @param bool $watch Whether to watch or unwatch the page
	 * @param Title $title Page to watch/unwatch
	 * @param User $user User who is watching/unwatching
	 * @param string|null $expiry Optional expiry timestamp in any format acceptable to wfTimestamp(),
	 *   null will not create expiries, or leave them unchanged should they already exist.
	 * @return Status
	 */
	public static function doWatchOrUnwatch(
		$watch,
		Title $title,
		User $user,
		string $expiry = null
	) {
		// User must be logged in, and either changing the watch state or at least the expiry.
		if ( !$user->isLoggedIn() ) {
			return Status::newGood();
		}
		$changingWatchStatus = $user->isWatched( $title, User::IGNORE_USER_RIGHTS ) !== $watch;
		if ( $expiry !== null || $changingWatchStatus ) {
			// If the user doesn't have 'editmywatchlist', we still want to
			// allow them to add but not remove items via edits and such.
			if ( $watch ) {
				return self::doWatch( $title, $user, User::IGNORE_USER_RIGHTS, $expiry );
			} else {
				return self::doUnwatch( $title, $user );
			}
		}

		return Status::newGood();
	}

	/**
	 * Watch a page
	 * @since 1.22 Returns Status, $checkRights parameter added
	 * @param Title $title Page to watch/unwatch
	 * @param User $user User who is watching/unwatching
	 * @param bool $checkRights Passed through to $user->addWatch()
	 *     Pass User::CHECK_USER_RIGHTS or User::IGNORE_USER_RIGHTS.
	 * @param string|null $expiry Optional expiry timestamp in any format acceptable to wfTimestamp(),
	 *   null will not create expiries, or leave them unchanged should they already exist.
	 * @return Status
	 */
	public static function doWatch(
		Title $title,
		User $user,
		$checkRights = User::CHECK_USER_RIGHTS,
		?string $expiry = null
	) {
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
		if ( $checkRights && !$permissionManager->userHasRight( $user, 'editmywatchlist' ) ) {
			return User::newFatalPermissionDeniedStatus( 'editmywatchlist' );
		}

		$page = WikiPage::factory( $title );

		$status = Status::newFatal( 'hookaborted' );
		if ( Hooks::runner()->onWatchArticle( $user, $page, $status, $expiry ) ) {
			$status = Status::newGood();
			$user->addWatch( $title, $checkRights, $expiry );
			Hooks::runner()->onWatchArticleComplete( $user, $page );
		}

		return $status;
	}

	/**
	 * Unwatch a page
	 * @since 1.22 Returns Status
	 * @param Title $title Page to watch/unwatch
	 * @param User $user User who is watching/unwatching
	 * @return Status
	 */
	public static function doUnwatch( Title $title, User $user ) {
		if ( !MediaWikiServices::getInstance()
			->getPermissionManager()
			->userHasRight( $user, 'editmywatchlist' ) ) {
			return User::newFatalPermissionDeniedStatus( 'editmywatchlist' );
		}

		$page = WikiPage::factory( $title );

		$status = Status::newFatal( 'hookaborted' );
		if ( Hooks::runner()->onUnwatchArticle( $user, $page, $status ) ) {
			$status = Status::newGood();
			$user->removeWatch( $title );
			Hooks::runner()->onUnwatchArticleComplete( $user, $page );
		}

		return $status;
	}

	/**
	 * Get token to watch (or unwatch) a page for a user
	 *
	 * @param Title $title Title object of page to watch
	 * @param User $user User for whom the action is going to be performed
	 * @param string $action Optionally override the action to 'unwatch'
	 * @return string Token
	 * @since 1.18
	 */
	public static function getWatchToken( Title $title, User $user, $action = 'watch' ) {
		if ( $action != 'unwatch' ) {
			$action = 'watch';
		}
		// This must match ApiWatch and ResourceLoaderUserOptionsModule
		return $user->getEditToken( $action );
	}

	public function doesWrites() {
		return true;
	}
}
