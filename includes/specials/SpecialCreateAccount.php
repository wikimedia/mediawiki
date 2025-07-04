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
 */

namespace MediaWiki\Specials;

use MediaWiki\Auth\AuthManager;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Language\FormatterFactory;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\SpecialPage\LoginSignupSpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityUtils;
use StatusValue;

/**
 * Implements Special:CreateAccount
 *
 * @ingroup SpecialPage
 * @ingroup Auth
 */
class SpecialCreateAccount extends LoginSignupSpecialPage {
	/** @inheritDoc */
	protected static $allowedActions = [
		AuthManager::ACTION_CREATE,
		AuthManager::ACTION_CREATE_CONTINUE
	];

	/** @inheritDoc */
	protected static $messages = [
		'authform-newtoken' => 'nocookiesfornew',
		'authform-notoken' => 'sessionfailure',
		'authform-wrongtoken' => 'sessionfailure',
	];

	private FormatterFactory $formatterFactory;

	private UserIdentityUtils $identityUtils;

	public function __construct(
		AuthManager $authManager,
		FormatterFactory $formatterFactory,
		UserIdentityUtils $identityUtils
	) {
		parent::__construct( 'CreateAccount', 'createaccount' );

		$this->setAuthManager( $authManager );
		$this->formatterFactory = $formatterFactory;
		$this->identityUtils = $identityUtils;
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	public function checkPermissions() {
		parent::checkPermissions();

		$performer = $this->getAuthority();
		$authManager = $this->getAuthManager();

		$status = $this->mPosted ?
			$authManager->authorizeCreateAccount( $performer ) :
			$authManager->probablyCanCreateAccount( $performer );

		if ( !$status->isGood() ) {
			$formatter = $this->formatterFactory->getStatusFormatter( $this->getContext() );
			throw new ErrorPageError(
				'createacct-error',
				$formatter->getMessage( $status )
			);
		}
	}

	/** @inheritDoc */
	protected function getLoginSecurityLevel() {
		return false;
	}

	/** @inheritDoc */
	protected function getDefaultAction( $subPage ) {
		return AuthManager::ACTION_CREATE;
	}

	/** @inheritDoc */
	public function getDescription() {
		return $this->msg( 'createaccount' );
	}

	/** @inheritDoc */
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

		$injected_html = '';
		if ( $direct ) {
			# Only save preferences if the user is not creating an account for someone else.
			if ( !$this->proxyAccountCreation ) {
				$this->getHookRunner()->onAddNewAccount( $user, false );

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

				$this->getHookRunner()->onAddNewAccount( $user, $byEmail );

				$out = $this->getOutput();
				// @phan-suppress-next-line PhanImpossibleCondition
				$out->setPageTitleMsg( $this->msg( $byEmail ? 'accmailtitle' : 'accountcreated' ) );
				// @phan-suppress-next-line PhanImpossibleCondition
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
			$this->getHookRunner()->onUserLoginComplete( $user, $injected_html, $direct );
		}

		$this->clearToken();

		# Run any hooks; display injected HTML
		$welcome_creation_msg = 'welcomecreation-msg';
		/**
		 * Let any extensions change what message is shown.
		 * @see https://www.mediawiki.org/wiki/Manual:Hooks/BeforeWelcomeCreation
		 * @since 1.18
		 */
		$this->getHookRunner()->onBeforeWelcomeCreation( $welcome_creation_msg, $injected_html );

		$this->showSuccessPage( 'signup',
			// T308471: ensure username is plaintext (aka escaped)
			$this->msg( 'welcomeuser' )->plaintextParams( $this->getUser()->getName() ),
			$welcome_creation_msg, $injected_html, $extraMessages );
	}

	/** @inheritDoc */
	protected function getToken() {
		return $this->getRequest()->getSession()->getToken( '', 'createaccount' );
	}

	protected function clearToken() {
		$this->getRequest()->getSession()->resetToken( 'createaccount' );
	}

	/** @inheritDoc */
	protected function getTokenName() {
		return 'wpCreateaccountToken';
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'users';
	}

	/** @inheritDoc */
	protected function logAuthResult( $success, UserIdentity $performer, $status = null ) {
		LoggerFactory::getInstance( 'authevents' )->info( 'Account creation attempt', [
			'event' => 'accountcreation',
			'successful' => $success,
			'accountType' => $this->identityUtils->getShortUserTypeInternal( $performer ),
			'status' => strval( $status ),
		] );
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialCreateAccount::class, 'SpecialCreateAccount' );
