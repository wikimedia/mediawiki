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

	protected function getDescription() {
		return $this->msg( 'addwatch' )->escaped();
	}

	/**
	 * Just get an empty form with a single submit button
	 * @return array
	 */
	protected function getFormFields() {
		return array();
	}

	public function onSubmit( $data ) {
		self::doWatch( $this->getTitle(), $this->getUser() );

		return true;
	}

	/**
	 * This can be either formed or formless depending on the session token given
	 */
	public function show() {
		$this->setHeaders();

		$user = $this->getUser();
		// This will throw exceptions if there's a problem
		$this->checkCanExecute( $user );

		// Must have valid token for this action/title
		$salt = array( $this->getName(), $this->getTitle()->getDBkey() );

		if ( $user->matchEditToken( $this->getRequest()->getVal( 'token' ), $salt ) ) {
			$this->onSubmit( array() );
			$this->onSuccess();
		} else {
			$form = $this->getForm();
			if ( $form->show() ) {
				$this->onSuccess();
			}
		}
	}

	protected function checkCanExecute( User $user ) {
		// Must be logged in
		if ( $user->isAnon() ) {
			throw new UserNotLoggedIn( 'watchlistanontext', 'watchnologin' );
		}

		parent::checkCanExecute( $user );
	}

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
			$user->isWatched( $title, WatchedItem::IGNORE_USER_RIGHTS ) != $watch
		) {
			// If the user doesn't have 'editmywatchlist', we still want to
			// allow them to add but not remove items via edits and such.
			if ( $watch ) {
				return self::doWatch( $title, $user, WatchedItem::IGNORE_USER_RIGHTS );
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
	 * @param int $checkRights Passed through to $user->addWatch()
	 * @return Status
	 */
	public static function doWatch( Title $title, User $user,
		$checkRights = WatchedItem::CHECK_USER_RIGHTS
	) {
		if ( $checkRights !== WatchedItem::IGNORE_USER_RIGHTS &&
			!$user->isAllowed( 'editmywatchlist' )
		) {
			return User::newFatalPermissionDeniedStatus( 'editmywatchlist' );
		}

		$page = WikiPage::factory( $title );

		$status = Status::newFatal( 'hookaborted' );
		if ( Hooks::run( 'WatchArticle', array( &$user, &$page, &$status ) ) ) {
			$status = Status::newGood();
			$user->addWatch( $title, $checkRights );
			Hooks::run( 'WatchArticleComplete', array( &$user, &$page ) );
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
		if ( Hooks::run( 'UnwatchArticle', array( &$user, &$page, &$status ) ) ) {
			$status = Status::newGood();
			$user->removeWatch( $title );
			Hooks::run( 'UnwatchArticleComplete', array( &$user, &$page ) );
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
		$salt = array( $action, $title->getPrefixedDBkey() );

		// This token stronger salted and not compatible with ApiWatch
		// It's title/action specific because index.php is GET and API is POST
		return $user->getEditToken( $salt );
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

	protected function alterForm( HTMLForm $form ) {
		$form->setSubmitTextMsg( 'confirm-watch-button' );
	}

	protected function preText() {
		return $this->msg( 'confirm-watch-top' )->parse();
	}

	public function onSuccess() {
		$this->getOutput()->addWikiMsg( 'addedwatchtext', $this->getTitle()->getPrefixedText() );
	}
}
