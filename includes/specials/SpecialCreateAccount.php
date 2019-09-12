<?php
/**
 * Implements Special:CreateAccount
 *
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
 * @ingroup SpecialPage
 */

use MediaWiki\Auth\AuthManager;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

/**
 * Implements Special:CreateAccount
 *
 * @ingroup SpecialPage
 */
class SpecialCreateAccount extends LoginSignupSpecialPage {
	protected static $allowedActions = [
		AuthManager::ACTION_CREATE,
		AuthManager::ACTION_CREATE_CONTINUE
	];

	protected static $messages = [
		'authform-newtoken' => 'nocookiesfornew',
		'authform-notoken' => 'sessionfailure',
		'authform-wrongtoken' => 'sessionfailure',
	];

	public function __construct() {
		parent::__construct( 'CreateAccount' );
	}

	public function doesWrites() {
		return true;
	}

	public function isRestricted() {
		return !MediaWikiServices::getInstance()
			->getPermissionManager()
			->groupHasPermission( '*', 'createaccount' );
	}

	public function userCanExecute( User $user ) {
		return MediaWikiServices::getInstance()
			->getPermissionManager()
			->userHasRight( $user, 'createaccount' );
	}

	public function checkPermissions() {
		parent::checkPermissions();

		$user = $this->getUser();
		$status = AuthManager::singleton()->checkAccountCreatePermissions( $user );
		if ( !$status->isGood() ) {
			// Track block with a cookie if it doesn't exist already
			if ( $user->isBlockedFromCreateAccount() ) {
				MediaWikiServices::getInstance()->getBlockManager()->trackBlockWithCookie( $user );
			}
			throw new ErrorPageError( 'createacct-error', $status->getMessage() );
		}
	}

	protected function getLoginSecurityLevel() {
		return false;
	}

	protected function getDefaultAction( $subPage ) {
		return AuthManager::ACTION_CREATE;
	}

	public function getDescription() {
		return $this->msg( 'createaccount' )->text();
	}

	protected function isSignup() {
		return true;
	}

	/**
	 * Run any hooks registered for logins, then display a message welcoming
	 * the user.
	 * @param bool $direct True if the action was successful just now; false if that happened
	 *    pre-redirection (so this handler was called already)
	 * @param StatusValue|null $extraMessages
	 */
	protected function successfulAction( $direct = false, $extraMessages = null ) {
		$session = $this->getRequest()->getSession();
		$user = $this->targetUser ?: $this->getUser();

		if ( $direct ) {
			# Only save preferences if the user is not creating an account for someone else.
			if ( !$this->proxyAccountCreation ) {
				Hooks::run( 'AddNewAccount', [ $user, false ] );

				// If the user does not have a session cookie at this point, they probably need to
				// do something to their browser.
				if ( !$this->hasSessionCookie() ) {
					$this->mainLoginForm( [ /*?*/ ], $session->getProvider()->whyNoSession() );
					// TODO something more specific? This used to use nocookiesnew
					// FIXME should redirect to login page instead?
					return;
				}
			} else {
				$byEmail = false; // FIXME no way to set this

				Hooks::run( 'AddNewAccount', [ $user, $byEmail ] );

				$out = $this->getOutput();
				$out->setPageTitle( $this->msg( $byEmail ? 'accmailtitle' : 'accountcreated' ) );
				if ( $byEmail ) {
					$out->addWikiMsg( 'accmailtext', $user->getName(), $user->getEmail() );
				} else {
					$out->addWikiMsg( 'accountcreatedtext', $user->getName() );
				}

				$rt = Title::newFromText( $this->mReturnTo );
				$out->addReturnTo(
					( $rt && !$rt->isExternal() ) ? $rt : $this->getPageTitle(),
					wfCgiToArray( $this->mReturnToQuery )
				);
				return;
			}
		}

		$this->clearToken();

		# Run any hooks; display injected HTML
		$injected_html = '';
		$welcome_creation_msg = 'welcomecreation-msg';
		Hooks::run( 'UserLoginComplete', [ &$user, &$injected_html, $direct ] );

		/**
		 * Let any extensions change what message is shown.
		 * @see https://www.mediawiki.org/wiki/Manual:Hooks/BeforeWelcomeCreation
		 * @since 1.18
		 */
		Hooks::run( 'BeforeWelcomeCreation', [ &$welcome_creation_msg, &$injected_html ] );

		$this->showSuccessPage( 'signup', $this->msg( 'welcomeuser', $this->getUser()->getName() ),
			$welcome_creation_msg, $injected_html, $extraMessages );
	}

	protected function getToken() {
		return $this->getRequest()->getSession()->getToken( '', 'createaccount' );
	}

	protected function clearToken() {
		return $this->getRequest()->getSession()->resetToken( 'createaccount' );
	}

	protected function getTokenName() {
		return 'wpCreateaccountToken';
	}

	protected function getGroupName() {
		return 'login';
	}

	protected function logAuthResult( $success, $status = null ) {
		LoggerFactory::getInstance( 'authevents' )->info( 'Account creation attempt', [
			'event' => 'accountcreation',
			'successful' => $success,
			'status' => $status,
		] );
	}
}
