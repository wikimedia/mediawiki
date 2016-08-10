<?php
/**
 * Implements Special:UserLogin
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

/**
 * Implements Special:UserLogin
 *
 * @ingroup SpecialPage
 */
class SpecialUserLogin extends LoginSignupSpecialPage {
	protected static $allowedActions = [
		AuthManager::ACTION_LOGIN,
		AuthManager::ACTION_LOGIN_CONTINUE
	];

	protected static $messages = [
		'authform-newtoken' => 'nocookiesforlogin',
		'authform-notoken' => 'sessionfailure',
		'authform-wrongtoken' => 'sessionfailure',
	];

	public function __construct() {
		parent::__construct( 'Userlogin' );
	}

	public function doesWrites() {
		return true;
	}

	protected function getLoginSecurityLevel() {
		return false;
	}

	protected function getDefaultAction( $subPage ) {
		return AuthManager::ACTION_LOGIN;
	}

	public function getDescription() {
		return $this->msg( 'login' )->text();
	}

	public function setHeaders() {
		// override the page title if we are doing a forced reauthentication
		parent::setHeaders();
		if ( $this->securityLevel && $this->getUser()->isLoggedIn() ) {
			$this->getOutput()->setPageTitle( $this->msg( 'login-security' ) );
		}
	}

	protected function isSignup() {
		return false;
	}

	protected function beforeExecute( $subPage ) {
		if ( $subPage === 'signup' || $this->getRequest()->getText( 'type' ) === 'signup' ) {
			// B/C for old account creation URLs
			$title = SpecialPage::getTitleFor( 'CreateAccount' );
			$query = array_diff_key( $this->getRequest()->getValues(),
				array_fill_keys( [ 'type', 'title' ], true ) );
			$url = $title->getFullURL( $query, false, PROTO_CURRENT );
			$this->getOutput()->redirect( $url );
			return false;
		}
		return parent::beforeExecute( $subPage );
	}

	/**
	 * Run any hooks registered for logins, then HTTP redirect to
	 * $this->mReturnTo (or Main Page if that's undefined).  Formerly we had a
	 * nice message here, but that's really not as useful as just being sent to
	 * wherever you logged in from.  It should be clear that the action was
	 * successful, given the lack of error messages plus the appearance of your
	 * name in the upper right.
	 * @param bool $direct True if the action was successful just now; false if that happened
	 *    pre-redirection (so this handler was called already)
	 * @param StatusValue|null $extraMessages
	 */
	protected function successfulAction( $direct = false, $extraMessages = null ) {
		global $wgSecureLogin;

		$user = $this->targetUser ?: $this->getUser();
		$session = $this->getRequest()->getSession();

		if ( $direct ) {
			$user->touch();

			$this->clearToken();

			if ( $user->requiresHTTPS() ) {
				$this->mStickHTTPS = true;
			}
			$session->setForceHTTPS( $wgSecureLogin && $this->mStickHTTPS );

			// If the user does not have a session cookie at this point, they probably need to
			// do something to their browser.
			if ( !$this->hasSessionCookie() ) {
				$this->mainLoginForm( [ /*?*/ ], $session->getProvider()->whyNoSession() );
				// TODO something more specific? This used to use nocookieslogin
				return;
			}
		}

		# Run any hooks; display injected HTML if any, else redirect
		$injected_html = '';
		Hooks::run( 'UserLoginComplete', [ &$user, &$injected_html, $direct ] );

		if ( $injected_html !== '' || $extraMessages ) {
			$this->showSuccessPage( 'success', $this->msg( 'loginsuccesstitle' ),
				'loginsuccess', $injected_html, $extraMessages );
		} else {
			$helper = new LoginHelper( $this->getContext() );
			$helper->showReturnToPage( 'successredirect', $this->mReturnTo, $this->mReturnToQuery,
				$this->mStickHTTPS );
		}
	}

	protected function getToken() {
		return $this->getRequest()->getSession()->getToken( '', 'login' );
	}

	protected function clearToken() {
		return $this->getRequest()->getSession()->resetToken( 'login' );
	}

	protected function getTokenName() {
		return 'wpLoginToken';
	}

	protected function getGroupName() {
		return 'login';
	}

	protected function logAuthResult( $success, $status = null ) {
		LoggerFactory::getInstance( 'authevents' )->info( 'Login attempt', [
			'event' => 'login',
			'successful' => $success,
			'status' => $status,
		] );
	}
}
