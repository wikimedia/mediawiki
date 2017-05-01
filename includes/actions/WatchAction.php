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

/**
 * Page addition to a user's watchlist
 *
 * @ingroup Actions
 */
class WatchAction extends FormAction {

	public function getName() {
		return 'watch';
	}

	public function requiresUnblock() {
		return false;
	}

	/**
	 * @return string HTML
	 */
	protected function getDescription() {
		return $this->msg( 'addwatch' )->escaped();
	}

	public function onSubmit( $data ) {
		self::doWatch( $this->getTitle(), $this->getUser() );

		return true;
	}

	protected function checkCanExecute( User $user ) {
		// Must be logged in
		if ( $user->isAnon() ) {
			throw new UserNotLoggedIn( 'watchlistanontext', 'watchnologin' );
		}

		parent::checkCanExecute( $user );
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setSubmitTextMsg( 'confirm-watch-button' );
		$form->setTokenSalt( 'watch' );
	}

	protected function preText() {
		return $this->msg( 'confirm-watch-top' )->parse();
	}

	public function onSuccess() {
		$msgKey = $this->getTitle()->isTalkPage() ? 'addedwatchtext-talk' : 'addedwatchtext';
		$this->getOutput()->addWikiMsg( $msgKey, $this->getTitle()->getPrefixedText() );
	}

	/* Static utility methods */

	/**
	 * Watch or unwatch a page
	 * @since 1.22
	 * @param bool $watch Whether to watch or unwatch the page
	 * @param Title $title Page to watch/unwatch
	 * @param User $user User who is watching/unwatching
	 * @return Status
	 */
	public static function doWatchOrUnwatch( $watch, Title $title, User $user ) {
		if ( $user->isLoggedIn() &&
			$user->isWatched( $title, User::IGNORE_USER_RIGHTS ) != $watch
		) {
			// If the user doesn't have 'editmywatchlist', we still want to
			// allow them to add but not remove items via edits and such.
			if ( $watch ) {
				return self::doWatch( $title, $user, User::IGNORE_USER_RIGHTS );
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
	 * @return Status
	 */
	public static function doWatch(
		Title $title,
		User $user,
		$checkRights = User::CHECK_USER_RIGHTS
	) {
		if ( $checkRights && !$user->isAllowed( 'editmywatchlist' ) ) {
			return User::newFatalPermissionDeniedStatus( 'editmywatchlist' );
		}

		$page = WikiPage::factory( $title );

		$status = Status::newFatal( 'hookaborted' );
		if ( Hooks::run( 'WatchArticle', [ &$user, &$page, &$status ] ) ) {
			$status = Status::newGood();
			$user->addWatch( $title, $checkRights );
			Hooks::run( 'WatchArticleComplete', [ &$user, &$page ] );
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
		if ( !$user->isAllowed( 'editmywatchlist' ) ) {
			return User::newFatalPermissionDeniedStatus( 'editmywatchlist' );
		}

		$page = WikiPage::factory( $title );

		$status = Status::newFatal( 'hookaborted' );
		if ( Hooks::run( 'UnwatchArticle', [ &$user, &$page, &$status ] ) ) {
			$status = Status::newGood();
			$user->removeWatch( $title );
			Hooks::run( 'UnwatchArticleComplete', [ &$user, &$page ] );
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
		// Match ApiWatch and ResourceLoaderUserTokensModule
		return $user->getEditToken( $action );
	}

	/**
	 * Get token to unwatch (or watch) a page for a user
	 *
	 * @param Title $title Title object of page to unwatch
	 * @param User $user User for whom the action is going to be performed
	 * @param string $action Optionally override the action to 'watch'
	 * @return string Token
	 * @since 1.18
	 */
	public static function getUnwatchToken( Title $title, User $user, $action = 'unwatch' ) {
		return self::getWatchToken( $title, $user, $action );
	}

	public function doesWrites() {
		return true;
	}
}
