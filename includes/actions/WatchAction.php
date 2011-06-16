<?php
/**
 * Performs the watch and unwatch actions on a page
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

class WatchAction extends FormlessAction {

	public function getName() {
		return 'watch';
	}

	public function getRestriction() {
		return 'read';
	}

	public function requiresUnblock() {
		return false;
	}

	protected function getDescription() {
		return wfMsg( 'addedwatch' );
	}

	protected function checkCanExecute( User $user ) {

		// Must be logged in
		if ( $user->isAnon() ) {
			throw new ErrorPageError( 'watchnologin', 'watchnologintext' );
		}

		// Must have valid token for this action/title
		$salt = array( $this->getName(), $this->getTitle()->getDBkey() );

		if ( !$user->matchEditToken( $this->getRequest()->getVal( 'token' ), $salt ) ) {
			throw new ErrorPageError( 'sessionfailure-title', 'sessionfailure' );
		}
		
		return parent::checkCanExecute( $user );
	}

	public function onView() {
		wfProfileIn( __METHOD__ );

		$user = $this->getUser();
		self::doWatch( $this->getTitle(), $user );

		wfProfileOut( __METHOD__ );

		return wfMessage( 'addedwatchtext', $this->getTitle()->getPrefixedText() )->parse();
	}

	public static function doWatch( Title $title, User $user  ) {
		$page = new Article( $title );

		if ( wfRunHooks( 'WatchArticle', array( &$user, &$page ) ) ) {
			$user->addWatch( $title );
			wfRunHooks( 'WatchArticleComplete', array( &$user, &$page ) );
		}
		return true;
	}

	public static function doUnwatch( Title $title, User $user  ) {
		$page = new Article( $title );

		if ( wfRunHooks( 'UnwatchArticle', array( &$user, &$page ) ) ) {
			$user->removeWatch( $title );
			wfRunHooks( 'UnwatchArticleComplete', array( &$user, &$page ) );
		}
		return true;
	}

	/**
	 * Get token to watch (or unwatch) a page for a user
	 *
	 * @param Title $title Title object of page to watch
	 * @param User $title User for whom the action is going to be performed
	 * @param string $action Optionally override the action to 'unwatch'
	 * @return string Token
	 * @since 1.19
	 */
	public static function getWatchToken( Title $title, User $user, $action = 'watch' ) {
		if ( $action != 'unwatch' ) {
			$action = 'watch';
		}
		$salt = array( $action, $title->getDBkey() );

		// This token stronger salted and not compatible with ApiWatch
		// It's title/action specific because index.php is GET and API is POST
		return $user->editToken( $salt );
	}

	/**
	 * Get token to unwatch (or watch) a page for a user
	 *
	 * @param Title $title Title object of page to unwatch
	 * @param User $title User for whom the action is going to be performed
	 * @param string $action Optionally override the action to 'watch'
	 * @return string Token
	 * @since 1.19
	 */
	public static function getUnwatchToken( Title $title, User $user, $action = 'unwatch' ) {
		return self::getWatchToken( $title, $user, $action );
	}

}

class UnwatchAction extends WatchAction {

	public function getName() {
		return 'unwatch';
	}

	protected function getDescription() {
		return wfMsg( 'removedwatch' );
	}

	public function onView() {
		wfProfileIn( __METHOD__ );

		$user = $this->getUser();
		self::doUnwatch( $this->getTitle(), $user );

		wfProfileOut( __METHOD__ );

		return wfMessage( 'removedwatchtext', $this->getTitle()->getPrefixedText() )->parse();
	}
}
