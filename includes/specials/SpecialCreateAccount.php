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
use Psr\Log\LogLevel;

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
		return !User::groupHasPermission( '*', 'createaccount' );
	}

	public function userCanExecute( User $user ) {
		return $user->isAllowed( 'createaccount' );
	}

	// FIXME this should be done via AuthManger::can* as AuthManager might be configured to ignore
	// blocks, but that would require returning messages from that function.
	public function checkPermissions() {
		$user = $this->getUser();

		// Do a bunch of checks which will be done in AuthManager again, but that would result
		// in an error message after the user filled and submitted the form, and it's nicer
		// to inform them up ahead that there is no point wasting time with that.
		$this->checkReadOnly();
		$permErrors = $this->getFullTitle()->getUserPermissionsErrors( 'createaccount', $user, 'secure' );
		if ( $permErrors ) {
			throw new PermissionsError( 'createaccount', $permErrors );
		} elseif ( $user->isBlockedFromCreateAccount() ) {
			// TODO
			return;
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
		global $wgLoginLanguageSelector, $wgContLang;

		$session = $this->getRequest()->getSession();
		$user = $this->createdAccount;

		if ( $direct ) {
			# Only save preferences if the user is not creating an account for someone else.
			if ( !$this->proxyAccountCreation ) {
				// FIXME this should happen in authmanager
				# If we showed up language selection links, and one was in use, be
				# smart (and sensible) and save that language as the user's preference
				if ( $wgLoginLanguageSelector && $this->mLanguage ) {
					$user->setOption( 'language', $this->mLanguage );
				} else {
					# Otherwise the user's language preference defaults to $wgContLang,
					# but it may be better to set it to their preferred $wgContLang variant,
					# based on browser preferences or URL parameters.
					$user->setOption( 'language', $wgContLang->getPreferredVariant() );
				}
				if ( $wgContLang->hasVariants() ) {
					$user->setOption( 'variant', $wgContLang->getPreferredVariant() );
				}

				Hooks::run( 'AddNewAccount', [ $user, false ] );

				// If the user does not have a session cookie at this point, they probably need to
				// do something to their browser.
				if ( !$this->hasSessionCookie() ) {
					$this->mainLoginForm( [/*?*/ ], $session->getProvider()->whyNoSession() );
					// TODO something more specific? This used to use nocookiesnew
					// FIXME should redirect to login page instead?
					return;
				}

				$this->setUserForCurrentRequest( $user );
			} else {
				$byEmail = false; // FIXME no way to set this

				Hooks::run( 'AddNewAccount', [ $user, $byEmail ] );

				$out = $this->getOutput();
				$out->setPageTitle( $this->msg( $byEmail ? 'accmailtitle' : 'accountcreated' ) );
				if ( $byEmail ) {
					$out->addWikiMsg( 'accmailtext', $user->getName(), $user->getEmail() );
					$this->executeReturnTo( 'signup' );
				} else {
					$out->addWikiMsg( 'accountcreatedtext', $user->getName() );
					$this->executeReturnTo( 'signup' );
				}
				return;
			}
		}

		$this->clearToken();

		# Run any hooks; display injected HTML
		$injected_html = '';
		$welcome_creation_msg = 'welcomecreation-msg';
		Hooks::run( 'UserLoginComplete', [ &$user, &$injected_html ] );

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
		LoggerFactory::getInstance( 'authmanager-stats' )->info( 'Account creation attempt', [
			'event' => 'accountcreation',
			'successful' => $success,
			'status' => $status,
		] );
	}
}