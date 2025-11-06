<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use LoginHelper;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\LoginSignupSpecialPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityUtils;
use StatusValue;

/**
 * Implements Special:UserLogin
 *
 * @ingroup SpecialPage
 * @ingroup Auth
 */
class SpecialUserLogin extends LoginSignupSpecialPage {
	/** @inheritDoc */
	protected static $allowedActions = [
		AuthManager::ACTION_LOGIN,
		AuthManager::ACTION_LOGIN_CONTINUE
	];

	/** @inheritDoc */
	protected static $messages = [
		'authform-newtoken' => 'nocookiesforlogin',
		'authform-notoken' => 'sessionfailure',
		'authform-wrongtoken' => 'sessionfailure',
	];

	private UserIdentityUtils $identityUtils;

	public function __construct( AuthManager $authManager, UserIdentityUtils $identityUtils ) {
		parent::__construct( 'Userlogin' );
		$this->setAuthManager( $authManager );
		$this->identityUtils = $identityUtils;
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	/** @inheritDoc */
	public function isListed() {
		return $this->getAuthManager()->canAuthenticateNow();
	}

	/** @inheritDoc */
	protected function getLoginSecurityLevel() {
		return false;
	}

	/** @inheritDoc */
	protected function getDefaultAction( $subPage ) {
		return AuthManager::ACTION_LOGIN;
	}

	/** @inheritDoc */
	public function getDescription() {
		return $this->msg( 'login' );
	}

	public function setHeaders() {
		// override the page title if we are doing a forced reauthentication
		parent::setHeaders();
		if ( $this->securityLevel && $this->getUser()->isRegistered() ) {
			$this->getOutput()->setPageTitleMsg( $this->msg( 'login-security' ) );
		}
	}

	/** @inheritDoc */
	protected function isSignup() {
		return false;
	}

	/** @inheritDoc */
	protected function beforeExecute( $subPage ) {
		if ( $subPage === 'signup' || $this->getRequest()->getText( 'type' ) === 'signup' ) {
			// B/C for old account creation URLs
			$title = SpecialPage::getTitleFor( 'CreateAccount' );
			$query = array_diff_key( $this->getRequest()->getQueryValues(),
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
		$secureLogin = $this->getConfig()->get( MainConfigNames::SecureLogin );

		$user = $this->targetUser ?: $this->getUser();
		$session = $this->getRequest()->getSession();

		$injected_html = '';
		if ( $direct ) {
			$user->touch();
			$user->debouncedDBTouch();

			$this->clearToken();

			if ( $user->requiresHTTPS() ) {
				$this->mStickHTTPS = true;
			}
			$session->setForceHTTPS( $secureLogin && $this->mStickHTTPS );

			# Run any hooks; display injected HTML if any, else redirect
			$this->getHookRunner()->onUserLoginComplete(
				$user, $injected_html, $direct );
		}

		if ( $injected_html !== '' || $extraMessages ) {
			$this->showSuccessPage( 'success', $this->msg( 'loginsuccesstitle' ),
				'loginsuccess', $injected_html, $extraMessages );
		} else {
			$helper = new LoginHelper( $this->getContext() );
			$helper->showReturnToPage( 'successredirect', $this->mReturnTo, $this->mReturnToQuery,
				$this->mStickHTTPS, $this->mReturnToAnchor );
		}
	}

	/** @inheritDoc */
	protected function getToken() {
		return $this->getRequest()->getSession()->getToken( '', 'login' );
	}

	protected function clearToken() {
		$this->getRequest()->getSession()->resetToken( 'login' );
	}

	/** @inheritDoc */
	protected function getTokenName() {
		return 'wpLoginToken';
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'login';
	}

	/** @inheritDoc */
	protected function logAuthResult( $success, UserIdentity $performer, $status = null ) {
		LoggerFactory::getInstance( 'authevents' )->info( 'Login attempt', [
			'event' => 'login',
			'successful' => $success,
			'accountType' => $this->identityUtils->getShortUserTypeInternal( $performer ),
			'status' => strval( $status ),
		] );
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUserLogin::class, 'SpecialUserLogin' );
