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
use MediaWiki\Logger\LoggerFactory;

/**
 * Implements Special:UserLogin
 *
 * @ingroup SpecialPage
 */
class LoginForm extends SpecialPage {
	const SUCCESS = 0;
	const NO_NAME = 1;
	const ILLEGAL = 2;
	const WRONG_PLUGIN_PASS = 3;
	const NOT_EXISTS = 4;
	const WRONG_PASS = 5;
	const EMPTY_PASS = 6;
	const RESET_PASS = 7;
	const ABORTED = 8;
	const CREATE_BLOCKED = 9;
	const THROTTLED = 10;
	const USER_BLOCKED = 11;
	const NEED_TOKEN = 12;
	const WRONG_TOKEN = 13;
	const USER_MIGRATED = 14;

	public static $statusCodes = array(
		self::SUCCESS => 'success',
		self::NO_NAME => 'no_name',
		self::ILLEGAL => 'illegal',
		self::WRONG_PLUGIN_PASS => 'wrong_plugin_pass',
		self::NOT_EXISTS => 'not_exists',
		self::WRONG_PASS => 'wrong_pass',
		self::EMPTY_PASS => 'empty_pass',
		self::RESET_PASS => 'reset_pass',
		self::ABORTED => 'aborted',
		self::CREATE_BLOCKED => 'create_blocked',
		self::THROTTLED => 'throttled',
		self::USER_BLOCKED => 'user_blocked',
		self::NEED_TOKEN => 'need_token',
		self::WRONG_TOKEN => 'wrong_token',
		self::USER_MIGRATED => 'user_migrated',
	);

	/**
	 * Valid error and warning messages
	 *
	 * Special:Userlogin can show an error or warning message on the form when
	 * coming from another page. This is done via the ?error= or ?warning= GET
	 * parameters.
	 *
	 * This array is the list of valid message keys. All other values will be
	 * ignored.
	 *
	 * @since 1.24
	 * @var string[]
	 */
	public static $validErrorMessages = array(
		'exception-nologin-text',
		'watchlistanontext',
		'changeemail-no-info',
		'resetpass-no-info',
		'confirmemail_needlogin',
		'prefsnologintext2',
	);

	public $mAbortLoginErrorMsg = null;

	/** @deprecated */
	protected $mUsername;
	/** @deprecated */
	protected $mPassword;
	/** @deprecated */
	protected $mRetype;
	protected $mReturnTo;
	protected $mCookieCheck;
	protected $mPosted;
	protected $mAction;
	protected $mCreateaccount;
	protected $mCreateaccountMail;
	protected $mLoginattempt;
	/** @deprecated */
	protected $mRemember;
	/** @deprecated */
	protected $mEmail;
	/** @deprecated */
	protected $mDomain;
	protected $mLanguage;
	protected $mSkipCookieCheck;
	protected $mReturnToQuery;
	protected $mToken;
	protected $mStickHTTPS;
	/** @deprecated */
	protected $mReason;
	/** @deprecated */
	protected $mRealName;
	protected $mFromHTTP;
	protected $mEntryError = '';
	protected $mEntryErrorType = 'error';

	/** @deprecated */
	private $mTempPasswordUsed;
	private $mLoaded = false;
	private $mSecureLoginUrl;

	/** @var string One of the AuthManager::ACTION_* constants */
	protected $mAuthAction;

	/** @var array A list of AuthenticationRequest class names */
	protected $mAuthTypes;

	/** @var WebRequest */
	private $mOverrideRequest = null;

	/** @var WebRequest Effective request; set at the beginning of load */
	private $mRequest = null;

	/** @var HTMLForm */
	private $authForm;

	/**
	 * @param WebRequest $request
	 */
	public function __construct( $request = null ) {
		global $wgUseMediaWikiUIEverywhere;
		parent::__construct( 'Userlogin' );

		$this->mOverrideRequest = $request;
		// Override UseMediaWikiEverywhere to true, to force login and create form to use mw ui
		$wgUseMediaWikiUIEverywhere = true;
	}

	/**
	 * Returns an array of all valid error messages.
	 *
	 * @return array
	 */
	public function getValidErrorMessages() {
		static $messages = null;
		if ( !$messages ) {
			$messages = self::$validErrorMessages;
			Hooks::run( 'LoginFormValidErrorMessages', array( &$messages ) );
		}

		return $messages;
	}

	/**
	 * Load data from request.
	 * @param string $subPage Subpage of Special:Userlogin
	 * FIXME make this private once that does not break the createaccount API
	 */
	function load( $subPage ) {
		global $wgAuth, $wgHiddenPrefs, $wgEnableEmail;

		if ( $this->mLoaded ) {
			return;
		}
		$this->mLoaded = true;

		if ( $this->mOverrideRequest === null ) {
			$request = $this->getRequest();
		} else {
			$request = $this->mOverrideRequest;
		}
		$this->mRequest = $request;

		$this->mCookieCheck = $request->getVal( 'wpCookieCheck' );
		$this->mPosted = $request->wasPosted() || $subPage === 'return';
		$this->mCreateaccountMail = $request->getCheck( 'wpCreateaccountMail' )
			&& $wgEnableEmail;
		$this->mCreateaccount = $request->getCheck( 'wpCreateaccount' ) && !$this->mCreateaccountMail;
		$this->mLoginattempt = $request->getCheck( 'wpLoginattempt' );
		$this->mAction = $request->getVal( 'action' );
		$this->mRemember = $request->getCheck( 'wpRemember' );
		$this->mFromHTTP = $request->getBool( 'fromhttp', false )
			|| $request->getBool( 'wpFromhttp', false );
		$this->mStickHTTPS = ( !$this->mFromHTTP && $request->getProtocol() === 'https' )
			|| $request->getBool( 'wpForceHttps', false );
		$this->mLanguage = $request->getText( 'uselang' );
		$this->mSkipCookieCheck = $request->getCheck( 'wpSkipCookieCheck' );
		$this->mReturnTo = $request->getVal( 'returnto', '' );
		$this->mReturnToQuery = $request->getVal( 'returntoquery', '' );

		$this->loadAuth( $subPage );

		$this->mReason = $request->getText( 'wpReason' );
		$this->mToken = $request->getVal( 'wpAuthToken' );

		// Show an error or warning passed on from a previous page
		$entryError = $this->msg( $request->getVal( 'error', '' ) );
		$entryWarning = $this->msg( $request->getVal( 'warning', '' ) );
		// bc: provide login link as a parameter for messages where the translation
		// was not updated
		$loginreqlink = Linker::linkKnown(
			$this->getPageTitle(),
			$this->msg( 'loginreqlink' )->escaped(),
			array(),
			array(
				'returnto' => $this->mReturnTo,
				'returntoquery' => $this->mReturnToQuery,
				'uselang' => $this->mLanguage,
				'fromhttp' => $this->mFromHTTP ? '1' : '0',
			)
		);

		// Only show valid error or warning messages.
		if ( $entryError->exists()
			&& in_array( $entryError->getKey(), self::getValidErrorMessages() )
		) {
			$this->mEntryErrorType = 'error';
			$this->mEntryError = $entryError->rawParams( $loginreqlink )->parse();

		} elseif ( $entryWarning->exists()
			&& in_array( $entryWarning->getKey(), self::getValidErrorMessages() )
		) {
			$this->mEntryErrorType = 'warning';
			$this->mEntryError = $entryWarning->rawParams( $loginreqlink )->parse();
		}

		# 1. When switching accounts, it sucks to get automatically logged out
		# 2. Do not return to PasswordReset after a successful password change
		#    but goto Wiki start page (Main_Page) instead ( bug 33997 )
		$returnToTitle = Title::newFromText( $this->mReturnTo );
		if ( is_object( $returnToTitle )
			&& ( $returnToTitle->isSpecial( 'Userlogout' )
				|| $returnToTitle->isSpecial( 'PasswordReset' ) )
		) {
			$this->mReturnTo = '';
			$this->mReturnToQuery = '';
		}
	}

	/**
	 * Load AuthManager-related data from the request
	 * @param string $subPage Subpage of Special:Userlogin
	 */
	protected function loadAuth( $subPage ) {
		$authManager = AuthManager::singleton();
		$type = $subPage ?: $this->mRequest->getText( 'type' ) ?: $this->mRequest->getText( 'authAction' );

		// set sane defaults in case continuation data is not present or corrupt
		$this->mAuthAction = ( $type === 'signup' ) ? AuthManager::ACTION_CREATE : AuthManager::ACTION_LOGIN;
		$this->mAuthTypes = $authManager->getAuthenticationRequestTypes( $this->mAuthAction );
		if ( $this->mAuthAction === AuthManager::ACTION_CREATE ) {
			$this->mAuthTypes[] = 'UserDataAuthenticationRequest';
		}

		$authAction = $this->mRequest->getText( 'authAction' );
		if ( !in_array( $authAction, array( AuthManager::ACTION_LOGIN, AuthManager::ACTION_CREATE,
			AuthManager::ACTION_LOGIN_CONTINUE, AuthManager::ACTION_CREATE_CONTINUE ), true )
		) {
			return;
		}
		$authTypes = explode( ',', $this->mRequest->getText( 'authTypes' ) );
		$allAuthTypes = $authManager->getAuthenticationRequestTypes( $authAction );
		if ( $authAction === AuthManager::ACTION_CREATE || $authAction === AuthManager::ACTION_CREATE_CONTINUE ) {
			$allAuthTypes[] = 'UserDataAuthenticationRequest';
		}
		if ( array_diff( $authTypes, $allAuthTypes ) ) {
			return;
		}

		$this->mAuthAction = $authAction;
		$this->mAuthTypes = $authTypes;
	}

	function getDescription() {
		if ( $this->isSignup() ) {
			return $this->msg( 'createaccount' )->text();
		} else {
			return $this->msg( 'login' )->text();
		}
	}

	protected function isSignup() {
		return in_array( $this->mAuthAction,
			array( AuthManager::ACTION_CREATE, AuthManager::ACTION_CREATE_CONTINUE ), true );
	}

	protected function isContinue() {
		return in_array( $this->mAuthAction,
			array( AuthManager::ACTION_LOGIN_CONTINUE, AuthManager::ACTION_CREATE_CONTINUE ), true );
	}

	/**
	 * Checks whether AuthManager is ready to perform the action.
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @return bool
	 * @throws Exception
	 */
	protected function isActionAllowed( $action ) {
		$authManager = AuthManager::singleton();
		switch ( $action ) {
			case AuthManager::ACTION_LOGIN:
			case AuthManager::ACTION_LOGIN_CONTINUE:
				return $authManager->canAuthenticateNow();
			case AuthManager::ACTION_CREATE:
			case AuthManager::ACTION_CREATE_CONTINUE:
				return $authManager->canCreateAccounts();
			default:
				throw new LogicException( 'invalid action' );
		}
	}

	/**
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @param AuthenticationRequest[] $requests
	 * @return AuthenticationResponse
	 * @throws Exception
	 */
	protected function performAuthenticationStep( $action, array $requests ) {
		$authManager = AuthManager::singleton();
		switch ( $action ) {
			case AuthManager::ACTION_LOGIN:
				return $authManager->beginAuthentication( $requests );
			case AuthManager::ACTION_LOGIN_CONTINUE:
				return $authManager->continueAuthentication( $requests );
			case AuthManager::ACTION_CREATE:
				return $authManager->beginAccountCreation(
					AuthFrontend::getUsernameFromRequests( $requests ),
					$this->mContext->getUser(), $requests );
			case AuthManager::ACTION_CREATE_CONTINUE:
				return $authManager->continueAccountCreation( $requests );
			default:
				throw new LogicException( 'invalid action' );
		}
	}

	/**
	 * Returns URL query parameters which can be used to reload the page (or leave and return) while
	 * preserving all information that is necessary for authentication to continue.
	 * @param bool $withToken Include CSRF token
	 * @return array
	 * FIXME should not add action/type when they can be deduced anyway
	 */
	protected function getReturnToParams( $withToken = false ) {
		$params = array(
			'returnto' => $this->mReturnTo ?: null,
			'returntoquery' => $this->mReturnToQuery ?: null,
			'authAction' => $this->mAuthAction,
			'authTypes' => implode( ',', $this->mAuthTypes ),
		);
		if ( $withToken ) {
			$params['wpAuthToken'] = $this->mToken;
		}
		return $params;
	}

	/**
	 * @param string|null $subPage
	 */
	public function execute( $subPage ) {
		$this->load( $subPage );
		$this->setHeaders();

		// In the case where the user is already logged in, and was redirected to the login form from a
		// page that requires login, do not show the login page. The use case scenario for this is when
		// a user opens a large number of tabs, is redirected to the login page on all of them, and then
		// logs in on one, expecting all the others to work properly.
		//
		// However, do show the form if it was visited intentionally (no 'returnto' is present). People
		// who often switch between several accounts have grown accustomed to this behavior.
		if (
			!$this->isSignup() &&
			!$this->mPosted && !$this->mAuthTypes &&
			( $this->mReturnTo !== '' || $this->mReturnToQuery !== '' )  &&
			$this->getUser()->isLoggedIn()
		) {
			$this->successfulLogin();
		}

		// If logging in and not on HTTPS, either redirect to it or offer a link.
		global $wgSecureLogin;
		if ( $this->mRequest->getProtocol() !== 'https' ) {
			$title = $this->getFullTitle();
			$query = $this->getReturnToParams() + array(
				 'title' => null,
				 ( $this->mEntryErrorType === 'error' ? 'error' : 'warning' ) => $this->mEntryError,
			 ) + $this->mRequest->getQueryValues();
			$url = $title->getFullURL( $query, false, PROTO_HTTPS );
			if ( $wgSecureLogin
				 && wfCanIPUseHTTPS( $this->getRequest()->getIP() )
				 && !$this->mFromHTTP ) // Avoid infinite redirect
			{
				$url = wfAppendQuery( $url, 'fromhttp=1' );
				$this->getOutput()->redirect( $url );
				// Since we only do this redir to change proto, always vary
				$this->getOutput()->addVaryHeader( 'X-Forwarded-Proto' );

				return;
			} else {
				// A wiki without HTTPS login support should set $wgServer to
				// http://somehost, in which case the secure URL generated
				// above won't actually start with https://
				if ( substr( $url, 0, 8 ) === 'https://' ) {
					$this->mSecureLoginUrl = $url;
				}
			}
		}

		// FIXME cookie redirect check

		if ( !$this->isActionAllowed( $this->mAuthAction ) ) {
			// FIXME how do we explain this to the user? Should there be an AuthenticationSession::diagnose()?
			$this->mainLoginForm( array(), 'userlogin-cannot-' . $this->mAuthAction ); // TODO i18n
			return;
		}

		$status = false;
		$form = $this->getAuthForm( $this->mAuthTypes );
		$form->setSubmitCallback( array( $this, 'handleSubmit' ) );
		if ( $this->mPosted ) {
			$form->prepareForm();
			$status = $form->trySubmit(); // status handling is done via LoginForm properties
		}

		if (
			( ! $status instanceof Status && ! $status instanceof StatusValue )
			|| !$status->isGood()
		) {
			$this->mainLoginForm( $this->mAuthTypes, $this->mEntryError, $this->mEntryErrorType );
			return;
		}

		/** @var AuthenticationResponse $response */
		$response = $status->getValue();

		switch ( $response->status ) {
			case AuthenticationResponse::PASS:
				if ( $this->isSignup() ) {
					$response2 = AuthManager::singleton()->beginAuthentication( array( $response->loginRequest ) );
					if ( $response2->status !== AuthenticationResponse::PASS ) {
						LoggerFactory::getInstance( 'auth' )->error( 'Could not log in after account creation' );
					}
					$this->successfulCreation();
				} else {
					$this->successfulLogin();
				}
				break;
			case AuthenticationResponse::FAIL:
				// restart
				$this->mAuthAction = $this->isSignup() ? AuthManager::ACTION_CREATE : AuthManager::ACTION_LOGIN;
				$this->mAuthTypes = AuthManager::singleton()->getAuthenticationRequestTypes( $this->mAuthAction );
				$this->mainLoginForm( $this->mAuthTypes, $response->message );
				break;
			case AuthenticationResponse::REDIRECT:
				$this->getOutput()->redirect( $response->redirectTarget );
				// FIXME if the first submit of the form redirects, the return URL will not have
				// _CONTINUE for the action
				break;
			case AuthenticationResponse::UI:
				$this->mAuthAction = $this->isSignup() ? AuthManager::ACTION_CREATE_CONTINUE
					: AuthManager::ACTION_LOGIN_CONTINUE;
				$this->mAuthTypes = $response->neededRequests;
				$this->mainLoginForm( $response->neededRequests, $response->message );
				break;
			default:
				throw new LogicException( 'invalid AuthenticationResponse' );
		}
	}

	/**
	 * @private
	 * @param $data array Submitted data
	 * @param $form HTMLForm
	 * FIXME migrate any addNewAccount() / addNewAccountMailPassword() / processLogin() logic not included in AuthManager
	 * @return Status
	 */
	public function handleSubmit( array $data, HTMLForm $form ) {
		// FIXME ugh... unmess this by using lowercase everywhere
		$newData = array();
		foreach ( $data as $key => $value ) {
			$newData[strtolower($key)] = $value;
		}

		$requests = AuthenticationRequest::requestsFromSubmission( $this->mAuthTypes, $newData,
			$this->getPageTitle( 'return' )->getFullURL( $this->getReturnToParams( true ), false, PROTO_HTTPS ) );

		// FIXME should AuthenticationRequest::requestsFromSubmission do this?
		if ( isset( $newData['username'] ) ) {
			foreach ( $requests as $request ) {
				$request->username = $newData['username'];
			}
		}

		$response = $this->performAuthenticationStep( $this->mAuthAction, $requests );

		// we can't handle a fail status as failure here since it might require changing the form
		return Status::newGood( $response );
	}

	/**
	 * @param string|null $subPage
	 */
	public function oldexecute( $subPage ) {
		if ( !is_null( $this->mCookieCheck ) ) {
			$this->onCookieRedirectCheck( $this->mCookieCheck );

			return;
		} elseif ( $this->mPosted ) {
			if ( $this->mCreateaccount ) {
				$this->addNewAccount();

				return;
			} elseif ( $this->mCreateaccountMail ) {
				$this->addNewAccountMailPassword();

				return;
			} elseif ( ( 'submitlogin' == $this->mAction ) || $this->mLoginattempt ) {
				$this->processLogin();

				return;
			}
		}
		$this->mainLoginForm( $this->mEntryError, $this->mEntryErrorType );
	}

	/**
	 * @private
	 */
	function addNewAccountMailPassword() {
		if ( $this->mEmail == '' ) {
			$this->mainLoginForm( $this->msg( 'noemailcreate' )->escaped() );

			return;
		}

		$status = $this->addNewAccountInternal();
		LoggerFactory::getInstance( 'authmanager' )->info( 'Account creation attempt with mailed password', array(
			'event' => 'accountcreation',
			'status' => $status,
		) );
		if ( !$status->isGood() ) {
			$error = $status->getMessage();
			$this->mainLoginForm( $error->toString() );

			return;
		}

		$u = $status->getValue();

		// Wipe the initial password and mail a temporary one
		$u->setPassword( null );
		$u->saveSettings();
		$result = $this->mailPasswordInternal( $u, false, 'createaccount-title', 'createaccount-text' );

		Hooks::run( 'AddNewAccount', array( $u, true ) );
		$u->addNewUserLogEntry( 'byemail', $this->mReason );

		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'accmailtitle' ) );

		if ( !$result->isGood() ) {
			$this->mainLoginForm( $this->msg( 'mailerror', $result->getWikiText() )->text() );
		} else {
			$out->addWikiMsg( 'accmailtext', $u->getName(), $u->getEmail() );
			$this->executeReturnTo( 'success' );
		}
	}

	/**
	 * @private
	 * @return bool
	 */
	function addNewAccount() {
		global $wgContLang, $wgUser, $wgEmailAuthentication, $wgLoginLanguageSelector;

		# Create the account and abort if there's a problem doing so
		$status = $this->addNewAccountInternal();
		LoggerFactory::getInstance( 'authmanager' )->info( 'Account creation attempt', array(
			'event' => 'accountcreation',
			'status' => $status,
		) );

		if ( !$status->isGood() ) {
			$error = $status->getMessage();
			$this->mainLoginForm( $error->toString() );

			return false;
		}

		$u = $status->getValue();

		# Only save preferences if the user is not creating an account for someone else.
		if ( $this->getUser()->isAnon() ) {
			# If we showed up language selection links, and one was in use, be
			# smart (and sensible) and save that language as the user's preference
			if ( $wgLoginLanguageSelector && $this->mLanguage ) {
				$u->setOption( 'language', $this->mLanguage );
			} else {

				# Otherwise the user's language preference defaults to $wgContLang,
				# but it may be better to set it to their preferred $wgContLang variant,
				# based on browser preferences or URL parameters.
				$u->setOption( 'language', $wgContLang->getPreferredVariant() );
			}
			if ( $wgContLang->hasVariants() ) {
				$u->setOption( 'variant', $wgContLang->getPreferredVariant() );
			}
		}

		$out = $this->getOutput();

		# Send out an email authentication message if needed
		if ( $wgEmailAuthentication && Sanitizer::validateEmail( $u->getEmail() ) ) {
			$status = $u->sendConfirmationMail();
			if ( $status->isGood() ) {
				$out->addWikiMsg( 'confirmemail_oncreate' );
			} else {
				$out->addWikiText( $status->getWikiText( 'confirmemail_sendfailed' ) );
			}
		}

		# Save settings (including confirmation token)
		$u->saveSettings();

		# If not logged in, assume the new account as the current one and set
		# session cookies then show a "welcome" message or a "need cookies"
		# message as needed
		if ( $this->getUser()->isAnon() ) {
			$u->setCookies();
			$wgUser = $u;
			// This should set it for OutputPage and the Skin
			// which is needed or the personal links will be
			// wrong.
			$this->getContext()->setUser( $u );
			Hooks::run( 'AddNewAccount', array( $u, false ) );
			$u->addNewUserLogEntry( 'create' );
			if ( $this->hasSessionCookie() ) {
				$this->successfulCreation();
			} else {
				$this->cookieRedirectCheck( 'new' );
			}
		} else {
			# Confirm that the account was created
			$out->setPageTitle( $this->msg( 'accountcreated' ) );
			$out->addWikiMsg( 'accountcreatedtext', $u->getName() );
			$out->addReturnTo( $this->getPageTitle() );
			Hooks::run( 'AddNewAccount', array( $u, false ) );
			$u->addNewUserLogEntry( 'create2', $this->mReason );
		}

		return true;
	}

	/**
	 * Make a new user account using the loaded data.
	 * @private
	 * @throws PermissionsError|ReadOnlyError
	 * @return Status
	 */
	public function addNewAccountInternal() {
		global $wgAuth, $wgMemc, $wgAccountCreationThrottle, $wgEmailConfirmToEdit;

		// If the user passes an invalid domain, something is fishy
		if ( !$wgAuth->validDomain( $this->mDomain ) ) {
			return Status::newFatal( 'wrongpassword' );
		}

		// If we are not allowing users to login locally, we should be checking
		// to see if the user is actually able to authenticate to the authenti-
		// cation server before they create an account (otherwise, they can
		// create a local account and login as any domain user). We only need
		// to check this for domains that aren't local.
		if ( 'local' != $this->mDomain && $this->mDomain != '' ) {
			if (
				!$wgAuth->canCreateAccounts() &&
				(
					!$wgAuth->userExists( $this->mUsername ) ||
					!$wgAuth->authenticate( $this->mUsername, $this->mPassword )
				)
			) {
				return Status::newFatal( 'wrongpassword' );
			}
		}

		if ( wfReadOnly() ) {
			throw new ReadOnlyError;
		}

		# Request forgery checks.
		if ( !self::getCreateaccountToken() ) {
			self::setCreateaccountToken();

			return Status::newFatal( 'nocookiesfornew' );
		}

		# The user didn't pass a createaccount token
		if ( !$this->mToken ) {
			return Status::newFatal( 'sessionfailure' );
		}

		# Validate the createaccount token
		if ( $this->mToken !== self::getCreateaccountToken() ) {
			return Status::newFatal( 'sessionfailure' );
		}

		# Check permissions
		$currentUser = $this->getUser();
		$creationBlock = $currentUser->isBlockedFromCreateAccount();
		if ( !$currentUser->isAllowed( 'createaccount' ) ) {
			throw new PermissionsError( 'createaccount' );
		} elseif ( $creationBlock instanceof Block ) {
			// Throws an ErrorPageError.
			$this->userBlockedMessage( $creationBlock );

			// This should never be reached.
			return false;
		}

		# Include checks that will include GlobalBlocking (Bug 38333)
		$permErrors = $this->getPageTitle()->getUserPermissionsErrors(
			'createaccount',
			$currentUser,
			true
		);

		if ( count( $permErrors ) ) {
			throw new PermissionsError( 'createaccount', $permErrors );
		}

		$ip = $this->getRequest()->getIP();
		if ( $currentUser->isDnsBlacklisted( $ip, true /* check $wgProxyWhitelist */ ) ) {
			return Status::newFatal( 'sorbs_create_account_reason' );
		}

		# Now create a dummy user ($u) and check if it is valid
		$u = User::newFromName( $this->mUsername, 'creatable' );
		if ( !$u ) {
			return Status::newFatal( 'noname' );
		}

		# Make sure the user does not exist already
		$lock = $wgMemc->getScopedLock( wfGlobalCacheKey( 'account', md5( $this->mUsername ) ) );
		if ( !$lock ) {
			return Status::newFatal( 'usernameinprogress' );
		} elseif ( $u->idForName( User::READ_LOCKING ) ) {
			return Status::newFatal( 'userexists' );
		}

		if ( $this->mCreateaccountMail ) {
			# do not force a password for account creation by email
			# set invalid password, it will be replaced later by a random generated password
			$this->mPassword = null;
		} else {
			if ( $this->mPassword !== $this->mRetype ) {
				return Status::newFatal( 'badretype' );
			}

			# check for password validity, return a fatal Status if invalid
			$validity = $u->checkPasswordValidity( $this->mPassword, 'create' );
			if ( !$validity->isGood() ) {
				$validity->ok = false; // make sure this Status is fatal
				return $validity;
			}
		}

		# if you need a confirmed email address to edit, then obviously you
		# need an email address.
		if ( $wgEmailConfirmToEdit && strval( $this->mEmail ) === '' ) {
			return Status::newFatal( 'noemailtitle' );
		}

		if ( strval( $this->mEmail ) !== '' && !Sanitizer::validateEmail( $this->mEmail ) ) {
			return Status::newFatal( 'invalidemailaddress' );
		}

		# Set some additional data so the AbortNewAccount hook can be used for
		# more than just username validation
		$u->setEmail( $this->mEmail );
		$u->setRealName( $this->mRealName );

		$abortError = '';
		$abortStatus = null;
		if ( !Hooks::run( 'AbortNewAccount', array( $u, &$abortError, &$abortStatus ) ) ) {
			// Hook point to add extra creation throttles and blocks
			wfDebug( "LoginForm::addNewAccountInternal: a hook blocked creation\n" );
			if ( $abortStatus === null ) {
				// Report back the old string as a raw message status.
				// This will report the error back as 'createaccount-hook-aborted'
				// with the given string as the message.
				// To return a different error code, return a Status object.
				$abortError = new Message( 'createaccount-hook-aborted', array( $abortError ) );
				$abortError->text();

				return Status::newFatal( $abortError );
			} else {
				// For MediaWiki 1.23+ and updated hooks, return the Status object
				// returned from the hook.
				return $abortStatus;
			}
		}

		// Hook point to check for exempt from account creation throttle
		if ( !Hooks::run( 'ExemptFromAccountCreationThrottle', array( $ip ) ) ) {
			wfDebug( "LoginForm::exemptFromAccountCreationThrottle: a hook " .
				"allowed account creation w/o throttle\n" );
		} else {
			if ( ( $wgAccountCreationThrottle && $currentUser->isPingLimitable() ) ) {
				$key = wfMemcKey( 'acctcreate', 'ip', $ip );
				$value = $wgMemc->get( $key );
				if ( !$value ) {
					$wgMemc->set( $key, 0, 86400 );
				}
				if ( $value >= $wgAccountCreationThrottle ) {
					return Status::newFatal( 'acct_creation_throttle_hit', $wgAccountCreationThrottle );
				}
				$wgMemc->incr( $key );
			}
		}

		if ( !$wgAuth->addUser( $u, $this->mPassword, $this->mEmail, $this->mRealName ) ) {
			return Status::newFatal( 'externaldberror' );
		}

		self::clearCreateaccountToken();

		return $this->initUser( $u, false );
	}

	/**
	 * Actually add a user to the database.
	 * Give it a User object that has been initialised with a name.
	 *
	 * @param User $u
	 * @param bool $autocreate True if this is an autocreation via auth plugin
	 * @return Status Status object, with the User object in the value member on success
	 * @private
	 */
	function initUser( $u, $autocreate ) {
		global $wgAuth;

		$status = $u->addToDatabase();
		if ( !$status->isOK() ) {
			if ( $status->hasMessage( 'userexists' ) ) {
				// AuthManager probably just added the user.
				$u->saveSettings();
			} else {
				return $status;
			}
		}

		if ( $wgAuth->allowPasswordChange() ) {
			$u->setPassword( $this->mPassword );
		}

		$u->setEmail( $this->mEmail );
		$u->setRealName( $this->mRealName );
		$u->setToken();

		Hooks::run( 'LocalUserCreated', array( $u, $autocreate ) );
		if ( $wgAuth && !$wgAuth instanceof AuthManagerAuthPlugin ) {
			$oldUser = $u;
			$wgAuth->initUser( $u, $autocreate );
			if ( $oldUser !== $u ) {
				wfWarn( get_class( $wgAuth ) . '::initUser() replaced the user object' );
			}
		}

		$u->saveSettings();

		// Update user count
		DeferredUpdates::addUpdate( new SiteStatsUpdate( 0, 0, 0, 0, 1 ) );

		// Watch user's userpage and talk page
		$u->addWatch( $u->getUserPage(), WatchedItem::IGNORE_USER_RIGHTS );

		return Status::newGood( $u );
	}

	/**
	 * Internally authenticate the login request.
	 *
	 * This may create a local account as a side effect if the
	 * authentication plugin allows transparent local account
	 * creation.
	 * @return int
	 */
	public function authenticateUserData() {
		global $wgUser, $wgAuth;

		$this->load();

		if ( $this->mUsername == '' ) {
			return self::NO_NAME;
		}

		// We require a login token to prevent login CSRF
		// Handle part of this before incrementing the throttle so
		// token-less login attempts don't count towards the throttle
		// but wrong-token attempts do.

		// If the user doesn't have a login token yet, set one.
		if ( !self::getLoginToken() ) {
			self::setLoginToken();

			return self::NEED_TOKEN;
		}
		// If the user didn't pass a login token, tell them we need one
		if ( !$this->mToken ) {
			return self::NEED_TOKEN;
		}

		$throttleCount = self::incLoginThrottle( $this->mUsername );
		if ( $throttleCount === true ) {
			return self::THROTTLED;
		}

		// Validate the login token
		if ( $this->mToken !== self::getLoginToken() ) {
			return self::WRONG_TOKEN;
		}

		// Load the current user now, and check to see if we're logging in as
		// the same name. This is necessary because loading the current user
		// (say by calling getName()) calls the UserLoadFromSession hook, which
		// potentially creates the user in the database. Until we load $wgUser,
		// checking for user existence using User::newFromName($name)->getId() below
		// will effectively be using stale data.
		if ( $this->getUser()->getName() === $this->mUsername ) {
			wfDebug( __METHOD__ . ": already logged in as {$this->mUsername}\n" );

			return self::SUCCESS;
		}

		$u = User::newFromName( $this->mUsername );
		if ( $u === false ) {
			return self::ILLEGAL;
		}

		$msg = null;
		// Give extensions a way to indicate the username has been updated,
		// rather than telling the user the account doesn't exist.
		if ( !Hooks::run( 'LoginUserMigrated', array( $u, &$msg ) ) ) {
			$this->mAbortLoginErrorMsg = $msg;
			return self::USER_MIGRATED;
		}

		if ( !User::isUsableName( $u->getName() ) ) {
			return self::ILLEGAL;
		}

		$isAutoCreated = false;
		if ( $u->getID() == 0 ) {
			$status = $this->attemptAutoCreate( $u );
			if ( $status !== self::SUCCESS ) {
				return $status;
			} else {
				$isAutoCreated = true;
			}
		} else {
			$u->load();
		}

		// Give general extensions, such as a captcha, a chance to abort logins
		$abort = self::ABORTED;
		if ( !Hooks::run( 'AbortLogin', array( $u, $this->mPassword, &$abort, &$msg ) ) ) {
			$this->mAbortLoginErrorMsg = $msg;

			return $abort;
		}

		global $wgBlockDisablesLogin;
		if ( !$u->checkPassword( $this->mPassword ) ) {
			if ( $u->checkTemporaryPassword( $this->mPassword ) ) {
				// The e-mailed temporary password should not be used for actu-
				// al logins; that's a very sloppy habit, and insecure if an
				// attacker has a few seconds to click "search" on someone's o-
				// pen mail reader.
				//
				// Allow it to be used only to reset the password a single time
				// to a new value, which won't be in the user's e-mail ar-
				// chives.
				//
				// For backwards compatibility, we'll still recognize it at the
				// login form to minimize surprises for people who have been
				// logging in with a temporary password for some time.
				//
				// As a side-effect, we can authenticate the user's e-mail ad-
				// dress if it's not already done, since the temporary password
				// was sent via e-mail.
				if ( !$u->isEmailConfirmed() && !wfReadOnly() ) {
					$u->confirmEmail();
					$u->saveSettings();
				}

				// At this point we just return an appropriate code/ indicating
				// that the UI should show a password reset form; bot inter-
				// faces etc will probably just fail cleanly here.
				$this->mAbortLoginErrorMsg = 'resetpass-temp-emailed';
				$this->mTempPasswordUsed = true;
				$retval = self::RESET_PASS;
			} else {
				$retval = ( $this->mPassword == '' ) ? self::EMPTY_PASS : self::WRONG_PASS;
			}
		} elseif ( $wgBlockDisablesLogin && $u->isBlocked() ) {
			// If we've enabled it, make it so that a blocked user cannot login
			$retval = self::USER_BLOCKED;
		} elseif ( $this->checkUserPasswordExpired( $u ) == 'hard' ) {
			// Force reset now, without logging in
			$retval = self::RESET_PASS;
			$this->mAbortLoginErrorMsg = 'resetpass-expired';
		} else {
			Hooks::run( 'UserLoggedIn', array( $u ) );
			if ( $wgAuth && !$wgAuth instanceof AuthManagerAuthPlugin ) {
				$oldUser = $u;
				$wgAuth->updateUser( $u );
				if ( $oldUser !== $u ) {
					wfWarn( get_class( $wgAuth ) . '::updateUser() replaced the user object' );
				}
			}
			$wgUser = $u;
			// This should set it for OutputPage and the Skin
			// which is needed or the personal links will be
			// wrong.
			$this->getContext()->setUser( $u );

			// Please reset throttle for successful logins, thanks!
			if ( $throttleCount ) {
				self::clearLoginThrottle( $this->mUsername );
			}

			if ( $isAutoCreated ) {
				// Must be run after $wgUser is set, for correct new user log
				Hooks::run( 'AuthPluginAutoCreate', array( $u ) );
			}

			$retval = self::SUCCESS;
		}
		Hooks::run( 'LoginAuthenticateAudit', array( $u, $this->mPassword, $retval ) );

		return $retval;
	}

	/**
	 * Increment the login attempt throttle hit count for the (username,current IP)
	 * tuple unless the throttle was already reached.
	 * @param string $username The user name
	 * @return bool|int The integer hit count or True if it is already at the limit
	 */
	public static function incLoginThrottle( $username ) {
		global $wgPasswordAttemptThrottle, $wgMemc, $wgRequest;
		$username = trim( $username ); // sanity

		$throttleCount = 0;
		if ( is_array( $wgPasswordAttemptThrottle ) ) {
			$throttleKey = wfMemcKey( 'password-throttle', $wgRequest->getIP(), md5( $username ) );
			$count = $wgPasswordAttemptThrottle['count'];
			$period = $wgPasswordAttemptThrottle['seconds'];

			$throttleCount = $wgMemc->get( $throttleKey );
			if ( !$throttleCount ) {
				$wgMemc->add( $throttleKey, 1, $period ); // start counter
			} elseif ( $throttleCount < $count ) {
				$wgMemc->incr( $throttleKey );
			} elseif ( $throttleCount >= $count ) {
				return true;
			}
		}

		return $throttleCount;
	}

	/**
	 * Clear the login attempt throttle hit count for the (username,current IP) tuple.
	 * @param string $username The user name
	 * @return void
	 */
	public static function clearLoginThrottle( $username ) {
		global $wgMemc, $wgRequest;
		$username = trim( $username ); // sanity

		$throttleKey = wfMemcKey( 'password-throttle', $wgRequest->getIP(), md5( $username ) );
		$wgMemc->delete( $throttleKey );
	}

	/**
	 * Attempt to automatically create a user on login. Only succeeds if there
	 * is an external authentication method which allows it.
	 *
	 * @param User $user
	 *
	 * @return int Status code
	 */
	function attemptAutoCreate( $user ) {
		global $wgAuth;

		if ( $this->getUser()->isBlockedFromCreateAccount() ) {
			wfDebug( __METHOD__ . ": user is blocked from account creation\n" );

			return self::CREATE_BLOCKED;
		}

		if ( !$wgAuth->autoCreate() ) {
			return self::NOT_EXISTS;
		}

		if ( !$wgAuth->userExists( $user->getName() ) ) {
			wfDebug( __METHOD__ . ": user does not exist\n" );

			return self::NOT_EXISTS;
		}

		if ( !$wgAuth->authenticate( $user->getName(), $this->mPassword ) ) {
			wfDebug( __METHOD__ . ": \$wgAuth->authenticate() returned false, aborting\n" );

			return self::WRONG_PLUGIN_PASS;
		}

		$abortError = '';
		if ( !Hooks::run( 'AbortAutoAccount', array( $user, &$abortError ) ) ) {
			// Hook point to add extra creation throttles and blocks
			wfDebug( "LoginForm::attemptAutoCreate: a hook blocked creation: $abortError\n" );
			$this->mAbortLoginErrorMsg = $abortError;

			return self::ABORTED;
		}

		wfDebug( __METHOD__ . ": creating account\n" );
		$status = $this->initUser( $user, true );

		if ( !$status->isOK() ) {
			$errors = $status->getErrorsByType( 'error' );
			$this->mAbortLoginErrorMsg = $errors[0]['message'];

			return self::ABORTED;
		}

		return self::SUCCESS;
	}

	function processLogin() {
		global $wgMemc, $wgLang, $wgSecureLogin, $wgPasswordAttemptThrottle,
			$wgInvalidPasswordReset;

		$authRes = $this->authenticateUserData();
		switch ( $authRes ) {
			case self::SUCCESS:
				# We've verified now, update the real record
				$user = $this->getUser();
				$user->touch();

				if ( $user->requiresHTTPS() ) {
					$this->mStickHTTPS = true;
				}

				if ( $wgSecureLogin && !$this->mStickHTTPS ) {
					$user->setCookies( $this->mRequest, false, $this->mRemember );
				} else {
					$user->setCookies( $this->mRequest, null, $this->mRemember );
				}
				self::clearLoginToken();

				// Reset the throttle
				$request = $this->getRequest();
				$key = wfMemcKey( 'password-throttle', $request->getIP(), md5( $this->mUsername ) );
				$wgMemc->delete( $key );

				if ( $this->hasSessionCookie() || $this->mSkipCookieCheck ) {
					/* Replace the language object to provide user interface in
					 * correct language immediately on this first page load.
					 */
					$code = $request->getVal( 'uselang', $user->getOption( 'language' ) );
					$userLang = Language::factory( $code );
					$wgLang = $userLang;
					$this->getContext()->setLanguage( $userLang );
					// Reset SessionID on Successful login (bug 40995)
					$this->renewSessionId();
					if ( $this->checkUserPasswordExpired( $this->getUser() ) == 'soft' ) {
						$this->resetLoginForm( $this->msg( 'resetpass-expired-soft' ) );
					} elseif ( $wgInvalidPasswordReset
						&& !$user->isValidPassword( $this->mPassword )
					) {
						$status = $user->checkPasswordValidity(
							$this->mPassword,
							'login'
						);
						$this->resetLoginForm(
							$status->getMessage( 'resetpass-validity-soft' )
						);
					} else {
						$this->successfulLogin();
					}
				} else {
					$this->cookieRedirectCheck( 'login' );
				}
				break;

			case self::NEED_TOKEN:
				$error = $this->mAbortLoginErrorMsg ?: 'nocookiesforlogin';
				$this->mainLoginForm( $this->msg( $error )->parse() );
				break;
			case self::WRONG_TOKEN:
				$error = $this->mAbortLoginErrorMsg ?: 'sessionfailure';
				$this->mainLoginForm( $this->msg( $error )->text() );
				break;
			case self::NO_NAME:
			case self::ILLEGAL:
				$error = $this->mAbortLoginErrorMsg ?: 'noname';
				$this->mainLoginForm( $this->msg( $error )->text() );
				break;
			case self::WRONG_PLUGIN_PASS:
				$error = $this->mAbortLoginErrorMsg ?: 'wrongpassword';
				$this->mainLoginForm( $this->msg( $error )->text() );
				break;
			case self::NOT_EXISTS:
				if ( $this->getUser()->isAllowed( 'createaccount' ) ) {
					$error = $this->mAbortLoginErrorMsg ?: 'nosuchuser';
					$this->mainLoginForm( $this->msg( $error,
						wfEscapeWikiText( $this->mUsername ) )->parse() );
				} else {
					$error = $this->mAbortLoginErrorMsg ?: 'nosuchusershort';
					$this->mainLoginForm( $this->msg( $error,
						wfEscapeWikiText( $this->mUsername ) )->text() );
				}
				break;
			case self::WRONG_PASS:
				$error = $this->mAbortLoginErrorMsg ?: 'wrongpassword';
				$this->mainLoginForm( $this->msg( $error )->text() );
				break;
			case self::EMPTY_PASS:
				$error = $this->mAbortLoginErrorMsg ?: 'wrongpasswordempty';
				$this->mainLoginForm( $this->msg( $error )->text() );
				break;
			case self::RESET_PASS:
				$error = $this->mAbortLoginErrorMsg ?: 'resetpass_announce';
				$this->resetLoginForm( $this->msg( $error ) );
				break;
			case self::CREATE_BLOCKED:
				$this->userBlockedMessage( $this->getUser()->isBlockedFromCreateAccount() );
				break;
			case self::THROTTLED:
				$error = $this->mAbortLoginErrorMsg ?: 'login-throttled';
				$this->mainLoginForm( $this->msg( $error )
					->params( $this->getLanguage()->formatDuration( $wgPasswordAttemptThrottle['seconds'] ) )
					->text()
				);
				break;
			case self::USER_BLOCKED:
				$error = $this->mAbortLoginErrorMsg ?: 'login-userblocked';
				$this->mainLoginForm( $this->msg( $error, $this->mUsername )->escaped() );
				break;
			case self::ABORTED:
				$error = $this->mAbortLoginErrorMsg ?: 'login-abort-generic';
				$this->mainLoginForm( $this->msg( $error,
						wfEscapeWikiText( $this->mUsername ) )->text() );
				break;
			case self::USER_MIGRATED:
				$error = $this->mAbortLoginErrorMsg ?: 'login-migrated-generic';
				$params = array();
				if ( is_array( $error ) ) {
					$error = array_shift( $this->mAbortLoginErrorMsg );
					$params = $this->mAbortLoginErrorMsg;
				}
				$this->mainLoginForm( $this->msg( $error, $params )->text() );
				break;
			default:
				throw new MWException( 'Unhandled case value' );
		}

		LoggerFactory::getInstance( 'authmanager' )->info( 'Login attempt', array(
			'event' => 'login',
			'successful' => $authRes === self::SUCCESS,
			'status' => LoginForm::$statusCodes[$authRes],
		) );
	}

	/**
	 * Show the Special:ChangePassword form, with custom message
	 * @param Message $msg
	 */
	protected function resetLoginForm( Message $msg ) {
		// Allow hooks to explain this password reset in more detail
		Hooks::run( 'LoginPasswordResetMessage', array( &$msg, $this->mUsername ) );
		$reset = new SpecialChangePassword();
		$derivative = new DerivativeContext( $this->getContext() );
		$derivative->setTitle( $reset->getPageTitle() );
		$reset->setContext( $derivative );
		if ( !$this->mTempPasswordUsed ) {
			$reset->setOldPasswordMessage( 'oldpassword' );
		}
		$reset->setChangeMessage( $msg );
		$reset->execute( null );
	}

	/**
	 * @param User $u
	 * @param bool $throttle
	 * @param string $emailTitle Message name of email title
	 * @param string $emailText Message name of email text
	 * @return Status
	 */
	function mailPasswordInternal( $u, $throttle = true, $emailTitle = 'passwordremindertitle',
		$emailText = 'passwordremindertext'
	) {
		global $wgNewPasswordExpiry, $wgMinimalPasswordLength;

		if ( $u->getEmail() == '' ) {
			return Status::newFatal( 'noemail', $u->getName() );
		}
		$ip = $this->getRequest()->getIP();
		if ( !$ip ) {
			return Status::newFatal( 'badipaddress' );
		}

		$currentUser = $this->getUser();
		Hooks::run( 'User::mailPasswordInternal', array( &$currentUser, &$ip, &$u ) );

		$np = PasswordFactory::generateRandomPasswordString( $wgMinimalPasswordLength );
		$u->setNewpassword( $np, $throttle );
		$u->saveSettings();
		$userLanguage = $u->getOption( 'language' );

		$mainPage = Title::newMainPage();
		$mainPageUrl = $mainPage->getCanonicalURL();

		$m = $this->msg( $emailText, $ip, $u->getName(), $np, '<' . $mainPageUrl . '>',
			round( $wgNewPasswordExpiry / 86400 ) )->inLanguage( $userLanguage )->text();
		$result = $u->sendMail( $this->msg( $emailTitle )->inLanguage( $userLanguage )->text(), $m );

		return $result;
	}

	/**
	 * Run any hooks registered for logins, then HTTP redirect to
	 * $this->mReturnTo (or Main Page if that's undefined).  Formerly we had a
	 * nice message here, but that's really not as useful as just being sent to
	 * wherever you logged in from.  It should be clear that the action was
	 * successful, given the lack of error messages plus the appearance of your
	 * name in the upper right.
	 *
	 * @private
	 */
	function successfulLogin() {
		# Run any hooks; display injected HTML if any, else redirect
		$currentUser = $this->getUser();
		$injected_html = '';
		Hooks::run( 'UserLoginComplete', array( &$currentUser, &$injected_html ) );

		if ( $injected_html !== '' ) {
			$this->displaySuccessfulAction( 'success', $this->msg( 'loginsuccesstitle' ),
				'loginsuccess', $injected_html );
		} else {
			$this->executeReturnTo( 'successredirect' );
		}
	}

	/**
	 * Run any hooks registered for logins, then display a message welcoming
	 * the user.
	 *
	 * @private
	 */
	function successfulCreation() {
		# Run any hooks; display injected HTML
		$currentUser = $this->getUser();
		$injected_html = '';
		$welcome_creation_msg = 'welcomecreation-msg';

		Hooks::run( 'UserLoginComplete', array( &$currentUser, &$injected_html ) );

		/**
		 * Let any extensions change what message is shown.
		 * @see https://www.mediawiki.org/wiki/Manual:Hooks/BeforeWelcomeCreation
		 * @since 1.18
		 */
		Hooks::run( 'BeforeWelcomeCreation', array( &$welcome_creation_msg, &$injected_html ) );

		$this->displaySuccessfulAction(
			'signup',
			$this->msg( 'welcomeuser', $this->getUser()->getName() ),
			$welcome_creation_msg, $injected_html
		);
	}

	/**
	 * Display a "successful action" page.
	 *
	 * @param string $type Condition of return to; see `executeReturnTo`
	 * @param string|Message $title Page's title
	 * @param string $msgname
	 * @param string $injected_html
	 */
	private function displaySuccessfulAction( $type, $title, $msgname, $injected_html ) {
		$out = $this->getOutput();
		$out->setPageTitle( $title );
		if ( $msgname ) {
			$out->addWikiMsg( $msgname, wfEscapeWikiText( $this->getUser()->getName() ) );
		}

		$out->addHTML( $injected_html );

		$this->executeReturnTo( $type );
	}

	/**
	 * Output a message that informs the user that they cannot create an account because
	 * there is a block on them or their IP which prevents account creation.  Note that
	 * User::isBlockedFromCreateAccount(), which gets this block, ignores the 'hardblock'
	 * setting on blocks (bug 13611).
	 * @param Block $block The block causing this error
	 * @throws ErrorPageError
	 */
	function userBlockedMessage( Block $block ) {
		# Let's be nice about this, it's likely that this feature will be used
		# for blocking large numbers of innocent people, e.g. range blocks on
		# schools. Don't blame it on the user. There's a small chance that it
		# really is the user's fault, i.e. the username is blocked and they
		# haven't bothered to log out before trying to create an account to
		# evade it, but we'll leave that to their guilty conscience to figure
		# out.
		$errorParams = array(
			$block->getTarget(),
			$block->mReason ? $block->mReason : $this->msg( 'blockednoreason' )->text(),
			$block->getByName()
		);

		if ( $block->getType() === Block::TYPE_RANGE ) {
			$errorMessage = 'cantcreateaccount-range-text';
			$errorParams[] = $this->getRequest()->getIP();
		} else {
			$errorMessage = 'cantcreateaccount-text';
		}

		throw new ErrorPageError(
			'cantcreateaccounttitle',
			$errorMessage,
			$errorParams
		);
	}

	/**
	 * Add a "return to" link or redirect to it.
	 * Extensions can use this to reuse the "return to" logic after
	 * inject steps (such as redirection) into the login process.
	 *
	 * @param string $type One of the following:
	 *    - error: display a return to link ignoring $wgRedirectOnLogin
	 *    - signup: display a return to link using $wgRedirectOnLogin if needed
	 *    - success: display a return to link using $wgRedirectOnLogin if needed
	 *    - successredirect: send an HTTP redirect using $wgRedirectOnLogin if needed
	 * @param string $returnTo
	 * @param array|string $returnToQuery
	 * @param bool $stickHTTPs Keep redirect link on HTTPs
	 * @since 1.22
	 */
	public function showReturnToPage(
		$type, $returnTo = '', $returnToQuery = '', $stickHTTPs = false
	) {
		$this->mReturnTo = $returnTo;
		$this->mReturnToQuery = $returnToQuery;
		$this->mStickHTTPS = $stickHTTPs;
		$this->executeReturnTo( $type );
	}

	/**
	 * Add a "return to" link or redirect to it.
	 *
	 * @param string $type One of the following:
	 *    - error: display a return to link ignoring $wgRedirectOnLogin
	 *    - signup: display a return to link using $wgRedirectOnLogin if needed
	 *    - success: display a return to link using $wgRedirectOnLogin if needed
	 *    - successredirect: send an HTTP redirect using $wgRedirectOnLogin if needed
	 */
	private function executeReturnTo( $type ) {
		global $wgRedirectOnLogin, $wgSecureLogin;

		if ( $type != 'error' && $wgRedirectOnLogin !== null ) {
			$returnTo = $wgRedirectOnLogin;
			$returnToQuery = array();
		} else {
			$returnTo = $this->mReturnTo;
			$returnToQuery = wfCgiToArray( $this->mReturnToQuery );
		}

		// Allow modification of redirect behavior
		Hooks::run( 'PostLoginRedirect', array( &$returnTo, &$returnToQuery, &$type ) );

		$returnToTitle = Title::newFromText( $returnTo );
		if ( !$returnToTitle ) {
			$returnToTitle = Title::newMainPage();
		}

		if ( $wgSecureLogin && !$this->mStickHTTPS ) {
			$options = array( 'http' );
			$proto = PROTO_HTTP;
		} elseif ( $wgSecureLogin ) {
			$options = array( 'https' );
			$proto = PROTO_HTTPS;
		} else {
			$options = array();
			$proto = PROTO_RELATIVE;
		}

		if ( $type == 'successredirect' ) {
			$redirectUrl = $returnToTitle->getFullURL( $returnToQuery, false, $proto );
			$this->getOutput()->redirect( $redirectUrl );
		} else {
			$this->getOutput()->addReturnTo( $returnToTitle, $returnToQuery, null, $options );
		}
	}

	/**
	 * @param AuthenticationRequest[]|string[] $requestsOrTypes A list of AuthorizationRequest
	 *   objects or subclass names, used to generate the form fields. An empty array means a fatal
	 *   error (authentication cannot continue).
	 * @param string $msg
	 * @param string $msgtype
	 * @throws ErrorPageError
	 * @throws Exception
	 * @throws FatalError
	 * @throws MWException
	 * @throws PermissionsError
	 * @throws ReadOnlyError
	 * @private
	 */
	protected function mainLoginForm( array $requestsOrTypes, $msg = '', $msgtype = 'error' ) {
		$titleObj = $this->getPageTitle();
		$user = $this->getUser();
		$out = $this->getOutput();

		if ( $this->isSignup() ) {
			// Block signup here if in readonly. Keeps user from
			// going through the process (filling out data, etc)
			// and being informed later.
			// FIXME should this be part of AuthManager::canCreateAccount()?
			$permErrors = $titleObj->getUserPermissionsErrors( 'createaccount', $user, true );
			if ( count( $permErrors ) ) {
				throw new PermissionsError( 'createaccount', $permErrors );
			} elseif ( $user->isBlockedFromCreateAccount() ) {
				$this->userBlockedMessage( $user->isBlockedFromCreateAccount() );
				return;
			} elseif ( wfReadOnly() ) {
				throw new ReadOnlyError;
			}
		}
		// TODO handle empty $requestsOrTypes - no form, just an error message

		// Generic styles and scripts for both login and signup form
		$out->addModuleStyles( array(
			'mediawiki.ui',
			'mediawiki.ui.button',
			'mediawiki.ui.checkbox',
			'mediawiki.ui.input',
			'mediawiki.special.userlogin.common.styles'
		) );
		if ( $this->isSignup() ) {
			// XXX hack pending RL or JS parse() support for complex content messages T27349
			$out->addJsConfigVars( 'wgCreateacctImgcaptchaHelp',
				$this->msg( 'createacct-imgcaptcha-help' )->parse() );

			// Additional styles and scripts for signup form
			$out->addModules( array(
				'mediawiki.special.userlogin.signup.js'
			) );
			$out->addModuleStyles( array(
				'mediawiki.special.userlogin.signup.styles'
			) );
		} else {
			// Additional styles for login form
			$out->addModuleStyles( array(
				'mediawiki.special.userlogin.login.styles'
			) );
		}
		$out->disallowUserJs(); // just in case...

		$form = $this->getAuthForm( $requestsOrTypes, $msg, $msgtype );
		$form->prepareForm();
		$formHtml = $form->getHTML( $msg ? Status::newFatal( $msg ) : false );

		$out->addHTML( $this->getPageHtml( $formHtml ) );
	}

	/**
	 * Add page elements which are outside the form.
	 * FIXME this should probably be a template, but use a sane language (handlebars?)
	 * @param string $formHtml
	 * @return string
	 */
	protected function getPageHtml( $formHtml ) {
		global $wgLoginLanguageSelector;

		$loginPrompt = $this->isSignup() ? '' : Html::rawElement( 'div',
			array( 'id' => 'userloginprompt' ), $this->msg('loginprompt')->parseAsBlock() );
		$languageLinks = $wgLoginLanguageSelector ? $this->makeLanguageSelector() : '';
		$signupStartMsg = $this->msg( 'signupstart' );
		$signupStart = ( $this->isSignup() && !$signupStartMsg->isDisabled() )
			? Html::rawElement( 'div', array( 'id' => 'signupstart' ), $signupStartMsg->parseAsBlock() ) : '';
		if ( $languageLinks ) {
			$languageLinks = Html::rawElement( 'div', array( 'id' => 'languagelinks' ),
				Html::rawElement( 'p', array(), $languageLinks )
			);
		}

		$benefitsContainer = '';
		if ( $this->isSignup() ) {
			// messages used:
			// createacct-benefit-icon1 createacct-benefit-head1 createacct-benefit-body1
			// createacct-benefit-icon2 createacct-benefit-head2 createacct-benefit-body2
			// createacct-benefit-icon3 createacct-benefit-head3 createacct-benefit-body3
			$benefitCount = 3;
			$benefitList = '';
			for ( $benefitIdx = 1; $benefitIdx <= $benefitCount; $benefitIdx++ ) {
				$headUnescaped = $this->msg( "createacct-benefit-head$benefitIdx" )->text();
				$iconClass = $this->msg( "createacct-benefit-icon$benefitIdx" )->escaped();
				$benefitList .= Html::rawElement( 'div', array( 'class' => "mw-number-text $iconClass"),
					Html::rawElement( 'h3', array(),
						$this->msg( "createacct-benefit-head$benefitIdx" )->escaped()
					)
					. Html::rawElement( 'p', array(),
						$this->msg( "createacct-benefit-body$benefitIdx" )->params( $headUnescaped )->escaped()
					)
				);
			}
			$benefitsContainer = Html::rawElement( 'div', array( 'class' => 'mw-createacct-benefits-container' ),
				Html::rawElement( 'h2', array(), $this->msg( 'createacct-benefit-heading' )->escaped() )
				. Html::rawElement( 'div', array( 'class' => 'mw-createacct-benefits-list' ),
					$benefitList
				)
			);
		}

		$html = Html::rawElement( 'div', array( 'class' => 'mw-ui-container' ),
			$loginPrompt
			. $languageLinks
			. $signupStart
			. Html::rawElement( 'div', array( 'id' => 'userloginForm' ),
				$formHtml
			)
			. $benefitsContainer
		);

		return $html;
	}

	/**
	 * Generates a form from the given request types.
	 * @param AuthenticationRequest[]|string[] $requestsOrTypes An array of AuthorizationRequest
	 *   objects or subclass names.
	 * @param string $msg
	 * @param string $msgType
	 * @return HTMLForm
	 */
	protected function getAuthForm( array $requestsOrTypes, $msg = '', $msgType = 'error' ) {
		global $wgSecureLogin, $wgPasswordResetRoutes, $wgEnableEmail, $wgLoginLanguageSelector;

		if ( $this->authForm ) {
			//return $this->authForm;
		}

		$usingHTTPS = $this->mRequest->getProtocol() === 'https';

		// get basic form description from the auth logic
		$fieldInfo = AuthFrontend::mergeFieldInfo( $requestsOrTypes );
		$fakeTemplate = $this->getFakeTemplate( $msg, $msgType );
		$fieldDefinitions = $this->getFieldDefinitions( $fakeTemplate );
		$formDescriptor = AuthFrontend::fieldInfoToFormDescriptor( $fieldInfo,
			$this->mAuthAction, $fieldDefinitions );

		$form = HTMLForm::factory( 'vform', $formDescriptor, $this->getContext() );

		// maintain state
		if ( $requestsOrTypes && is_object( $requestsOrTypes[0] ) ) {
			$types = array_map( 'get_class', $requestsOrTypes );
			// inherit data from previous request
			foreach ( $requestsOrTypes as $request ) {
				foreach ( $request->getFieldInfo() as $field => $_ ) {
					if ( $formDescriptor[$field]['type'] === 'password' ) {
						continue;
					}
					$formDescriptor[$field]['default'] = $request->$field;
				}
			}
		} else {
			$types = $requestsOrTypes;
		}
		$form->addHiddenField( 'authAction', $this->mAuthAction );
		$form->addHiddenField( 'authTypes', implode( ',', $types ) );
		if ( $wgLoginLanguageSelector ) {
			$form->addHiddenField( 'uselang', $this->mLanguage );
		}
		$form->addHiddenField( 'wpAuthToken', self::getAuthenticationToken() );
		if ( $wgSecureLogin === true ) {
			// If using HTTPS coming from HTTP, then the 'fromhttp' parameter must be preserved
			if ( !$this->isSignup() ) {
				$form->addHiddenField( 'wpForceHttps', (int)$this->mStickHTTPS );
				$form->addHiddenField( 'wpFromhttp', $usingHTTPS );
			}
		}

		// set properties of the form itself
		$form->setAction( $this->getPageTitle()->getLocalURL( 'action=submitlogin&type='
			. ( $this->isSignup() ? 'signup' : 'login' ) . $this->getReturnToQueryStringFragment() ) );
		$form->setName( 'userlogin' . ( $this->isSignup() ? '2' : '' ) );
		if ( $this->isSignup() ) {
			$form->setId( 'userlogin2' );
		}

		// add pre/post text
		// header used by ConfirmEdit/Account, Persona, WikimediaIncubator, SemanticSignup
		// should be above the error message but HTMLForm doesn't support that
		$form->addHeaderText( $fakeTemplate->html( 'header' ) );

		// FIXME broken since the form uses its own error display
		// maybe subclass it and move this there?
		/*
		if ( $this->isSignup() && false ) {
			// used by the mediawiki.special.userlogin.signup.js module
			$statusAreaAttribs = array( 'id' => 'mw-createacct-status-area' );
			$statusAreaAttribs += $msg ? array( 'class' => "{$msgType}box" ) : array( 'style' => 'display: none;' );
			$form->addHeaderText( Html::element( 'div', $statusAreaAttribs ) );
		}
		*/

		$form->addHeaderText( $fakeTemplate->html( 'formheader' ) ); // header used by MobileFrontend
		if ( $this->isSignup() ) {
			// Use signupend-https for HTTPS requests if it's not blank, signupend otherwise
			$signupendMsg = $this->msg( 'signupend' );
			$signupendHttpsMsg = $this->msg( 'signupend-https' );
			if ( !$signupendMsg->isDisabled() ) {
				$signupendText = ( $usingHTTPS && !$signupendHttpsMsg->isBlank() )
					? $signupendHttpsMsg ->parse() : $signupendMsg->parse();
				$form->addPostText( Html::rawElement( 'div', array( 'id' => 'signupend' ), $signupendText ) );
			}
		}
		if ( !$this->isSignup() && $this->getUser()->isLoggedIn() ) {
			$form->addHeaderText( Html::rawElement( 'div', array( 'class' => 'warningbox' ),
				$this->msg( 'userlogin-loggedin' )->params( $this->getUser()->getName() )->parse() ) );
		}
		if ( !$this->isSignup() ) {
			$form->addFooterText( Html::rawElement( 'div',
				array( 'class' => 'mw-ui-vform-field mw-form-related-link-container' ),
				Linker::link(
					SpecialPage::getTitleFor( 'PasswordReset' ),
					$this->msg( 'userlogin-resetpassword-link' )->escaped()
				)
			) );

			$allowedChangeTypes = AuthManager::singleton()->getAuthenticationRequestTypes( AuthManager::ACTION_CHANGE );
			$allowPasswordChange = (bool)array_filter( $allowedChangeTypes, function ( $type) {
				return $type instanceof PasswordAuthenticationRequest;
			} );
			if (
				$wgEnableEmail && $allowPasswordChange && is_array( $wgPasswordResetRoutes )
				&& in_array( true, array_values( $wgPasswordResetRoutes ) )
			) {
				$form->addFooterText( Html::rawElement(
					'div',
					array( 'class' => 'mw-ui-vform-field mw-form-related-link-container' ),
					Linker::link(
						SpecialPage::getTitleFor( 'PasswordReset' ),
						$this->msg( 'userlogin-resetpassword-link' )->escaped()
					)
				) );
			}

			// link to the other action
			$linkq = $this->isSignup() ? 'type=login' : 'type=signup';
			$linkq .= $this->getReturnToQueryStringFragment();
			// Don't show a "create account" link if the user can't.
			if ( $this->showCreateOrLoginLink() ) {
				// Pass any language selection on to the mode switch link
				if ( $wgLoginLanguageSelector && $this->mLanguage ) {
					$linkq .= '&uselang=' . $this->mLanguage;
				}
				$createOrLoginHref = $this->getPageTitle()->getLocalURL( $linkq );
				if ( $this->getUser()->isLoggedIn() ) {
					$createOrLoginHtml = Html::rawElement( 'div',
						array( 'class' => 'mw-ui-vform-field mw-form-related-link-container' ),
						Html::element( 'a',
							array( 'id' => 'mw-createaccount-join', 'href' => $createOrLoginHref ),
							$this->msg( 'userlogin-createanother' )->escaped()
						)
					);
				} else {
					$createOrLoginHtml = Html::rawElement( 'div',
						array( 'id' => 'mw-createaccount-cta', 'class' => 'mw-ui-vform-field mw-form-related-link-container' ),
						$this->msg( 'userlogin-noaccount' )->escaped()
						. Html::element( 'a',
							array(
								'id' => 'mw-createaccount-join', 'href' => $createOrLoginHref,
								'class' => 'mw-ui-button mw-ui-progressive',
							),
							$this->msg( 'userlogin-joinproject' )->escaped()
						)
					);
				}
				$form->addFooterText( $createOrLoginHtml );
			}
		}

		$form->suppressDefaultSubmit();

		$this->authForm = $form;

		return $form;
	}

	/**
	 * Temporary B/C method to handle extensions using the UserLoginForm/UserCreateForm hooks.
	 * @param string $msg
	 * @param string $msgType
	 * @return FakeAuthTemplate
	 */
	protected function getFakeTemplate( $msg, $msgType ) {
		global $wgAuth, $wgEnableEmail, $wgHiddenPrefs, $wgEmailConfirmToEdit, $wgEnableUserEmail,
			   $wgCookieExpiration, $wgExtendedLoginCookieExpiration, $wgSecureLogin,
			   $wgLoginLanguageSelector, $wgPasswordResetRoutes ;

		// Preserves a bunch of logic from the old code that was rewritten in getAuthForm().
		// There is no code reuse to make this easier to remove .
		// If an extension tries to change any of these values, they are out of luck - we only
		// actually use the domain/usedomain/domainnames, extraInput and extrafields keys.
		// FIXME instead of most of the $this->m* stuff we should probably pass request data directly

		$titleObj = $this->getPageTitle();
		$user = $this->getUser();
		$template = new FakeAuthTemplate();

		// Pre-fill username (if not creating an account, bug 44775).
		if ( $this->mUsername == '' && $this->isSignup() ) {
			if ( $user->isLoggedIn() ) {
				$this->mUsername = $user->getName();
			} else {
				$this->mUsername = $this->getRequest()->getCookie( 'UserName' );
			}
		}

		if ( $this->isSignup() ) {
			// Must match number of benefits defined in messages
			$template->set( 'benefitCount', 3 );

			$q = 'action=submitlogin&type=signup';
			$linkq = 'type=login';
		} else {
			$q = 'action=submitlogin&type=login';
			$linkq = 'type=signup';
		}

		if ( $this->mReturnTo !== '' ) {
			$returnto = '&returnto=' . wfUrlencode( $this->mReturnTo );
			if ( $this->mReturnToQuery !== '' ) {
				$returnto .= '&returntoquery=' .
							 wfUrlencode( $this->mReturnToQuery );
			}
			$q .= $returnto;
			$linkq .= $returnto;
		}

		# Don't show a "create account" link if the user can't.
		if ( $this->showCreateOrLoginLink() ) {
			# Pass any language selection on to the mode switch link
			if ( $wgLoginLanguageSelector && $this->mLanguage ) {
				$linkq .= '&uselang=' . $this->mLanguage;
			}
			// Supply URL, login template creates the button.
			$template->set( 'createOrLoginHref', $titleObj->getLocalURL( $linkq ) );
		} else {
			$template->set( 'link', '' );
		}

		$resetLink = $this->isSignup()
			? null
			: is_array( $wgPasswordResetRoutes ) && in_array( true, array_values( $wgPasswordResetRoutes ) );

		$template->set( 'header', '' );
		$template->set( 'formheader', '' );
		$template->set( 'skin', $this->getSkin() );

		$template->set( 'name', $this->mUsername );
		$template->set( 'password', $this->mPassword );
		$template->set( 'retype', $this->mRetype );
		$template->set( 'createemailset', $this->mCreateaccountMail );
		$template->set( 'email', $this->mEmail );
		$template->set( 'realname', $this->mRealName );
		$template->set( 'domain', $this->mDomain );
		$template->set( 'reason', $this->mReason );
		$template->set( 'remember', $this->mRemember );

		$template->set( 'action', $titleObj->getLocalURL( $q ) );
		$template->set( 'message', $msg );
		$template->set( 'messagetype', $msgType );
		$template->set( 'createemail', $wgEnableEmail && $user->isLoggedIn() );
		$template->set( 'userealname', !in_array( 'realname', $wgHiddenPrefs ) );
		$template->set( 'useemail', $wgEnableEmail );
		$template->set( 'emailrequired', $wgEmailConfirmToEdit );
		$template->set( 'emailothers', $wgEnableUserEmail );
		$template->set( 'canreset', $wgAuth->allowPasswordChange() );
		$template->set( 'resetlink', $resetLink );
		$template->set( 'canremember', $wgExtendedLoginCookieExpiration === null ? ( $wgCookieExpiration > 0 ) : ( $wgExtendedLoginCookieExpiration > 0 ) );
		$template->set( 'usereason', $user->isLoggedIn() );
		$template->set( 'cansecurelogin', ( $wgSecureLogin === true ) );
		$template->set( 'stickhttps', (int)$this->mStickHTTPS );
		$template->set( 'loggedin', $user->isLoggedIn() );
		$template->set( 'loggedinuser', $user->getName() );

		$action = $this->isSignup() ? 'signup' : 'login';
		$wgAuth->modifyUITemplate( $template, $action );

		$oldTemplate = $template;
		$hookName = $this->isSignup() ? 'UserCreateForm' : 'UserLoginForm';
		Hooks::run( $hookName, array( &$template ) );
		if ( $oldTemplate !== $template ) {
			wfDeprecated( "reference in $hookName hook", '1.27' );
		}

		return $template;

	}

	/**
	 * Create a HTMLForm descriptor for the core login fields.
	 * @param FakeAuthTemplate $template B/C data (not used but needed by getBCFieldDefinitions)
	 * @return array
	 */
	protected function getFieldDefinitions( $template ) {
		global $wgEmailConfirmToEdit, $wgCookieExpiration, $wgExtendedLoginCookieExpiration,
			$wgHiddenPrefs, $wgEnableEmail;

		$isLoggedIn = $this->getUser()->isLoggedIn();
		$continuePart = $this->isContinue() ? 'continue-' : '';
		$anotherPart = $isLoggedIn ? 'another-' : '';
		$expirationDays = ceil( $wgCookieExpiration / ( 3600 * 24 ) );
		$secureLoginLink = '';
		if ( $this->mSecureLoginUrl ) {
			$secureLoginLink = Html::element( 'a', array(
				'href' => $this->mSecureLoginUrl,
				'class' => 'mw-ui-flush-right mw-secure',
			), $this->msg( 'userlogin-signwithsecure' )->text() );
		}

		if ( $this->isSignup() ) {
			$fieldDefinitions = array(
				'Username' => array(),
				'CreateaccountMail' => array(
					// create account without providing password, a temporary one will be mailed
					'type' => 'check',
					'label-message' => 'createaccountmail',
					'id' => 'wpCreateaccountMail',
				),
				'Password' => array(),
				'Domain' => array(),
				'Retype' => array(
					'type' => 'password',
					'label-message' => 'createacct-yourpasswordagain',
					'id' => 'wpRetype',
					'cssclass' => 'loginPassword',
					'size' => 20,
					'required' => true,
					'placeholder-message' => 'createacct-yourpasswordagain-ph',
				),
				'Email' => array(
					'type' => 'email',
					'label-message' => $wgEmailConfirmToEdit ? 'createacct-emailrequired'
						: 'createacct-emailoptional',
					'id' => 'wpEmail',
					'cssclass' => 'loginText',
					'size' => '20',
					'required' => $wgEmailConfirmToEdit,
					'placeholder-message' => 'createacct-' . $anotherPart . 'email-ph',
				),
				'RealName' => array(
					'type' => 'text',
					'help-message' => $isLoggedIn ? 'createacct-another-realname-tip'
						: 'prefs-help-realname',
					'label-message' => 'createacct-realname',
					'cssclass' => 'loginText',
					'size' => 20,
					'id' => 'wpRealName',
				),
				'Reason' => array(
					// comment for the user creation log
					'type' => 'text',
					'label-message' => 'createacct-reason',
					'cssclass' => 'loginText',
					'id' => 'wpReason',
					'size' => '20',
					'placeholder-message' => 'createacct-reason-ph',
				),
				'extraInput' => array(), // placeholder for fields coming from the template
				'Createaccount' => array(
					// submit button
					'type' => 'submit',
					'default' => $this->msg( 'createacct-' . $anotherPart . $continuePart .
						'submit' )->text(),
					'id' => 'wpCreateaccount',
					'weight' => 100,
				),
			);
		} else {
			$fieldDefinitions = array(
				'Username' => array(),
				'Password' => array(),
				'Domain' => array(),
				'extraInput' => array(),
				'Remember' => array(
					// option for saving the user token to a cookie
					'type' => 'check',
					'label-message' => $this->msg( 'userlogin-remembermypassword' )
						->numParams( $expirationDays ),
					'id' => 'wpRemember',
				),
				'LoginAttempt' => array(
					// submit button
					'type' => 'submit',
					'default' => $this->msg( 'pt-login-' . $continuePart . 'button' )->text(),
					'id' => 'wpLoginAttempt',
					'weight' => 100,
				),
				'LinkContainer' => array(
					// help link
					'type' => 'info',
					'cssclass' => 'mw-form-related-link-container',
					'id' => 'mw-userlogin-help',
					'raw' => true,
					'default' => Html::element( 'a', array(
						'href' => Skin::makeInternalOrExternalUrl( wfMessage( 'helplogin-url' )
							->inContentLanguage()
							->text() ),
					), $this->msg( 'userlogin-helplink2' )->text() ),
					'weight' => 200,
				),
			);
		}
		$fieldDefinitions = array_merge( $fieldDefinitions, array(
			'Username' => array(
				'type' => 'text',
				'name' => 'wpName',
				'label' => $this->msg( 'userlogin-yourname' ) . $secureLoginLink,
				'help-message' => 'createacct-helpusername', // FIXME help-message does not match old formatting
				'cssclass' => 'loginText',
				'id' => $this->isSignup() ? 'wpName2' : 'wpName1',
				'size' => 20,
				'required' => true,
				'tabindex' => 1,
				'placeholder-message' => $isLoggedIn ? 'createacct-another-username-ph'
					: 'userlogin-yourname-ph',
			),
			'Password' => array(
				'type' => 'password',
				'label-message' => 'userlogin-yourpassword',
				'cssclass' => 'loginPassword',
				'id' => $this->isSignup() ? 'wpPassword2' : 'wpPassword1',
				'size' => 20,
				'required' => true,
				'placeholder-message' => 'createacct-yourpassword-ph',
			),
		) );

		// Pre-fill username (if not creating an account, T46775).
		if (
			isset( $formDescriptor['Username'] ) &&
			!isset( $formDescriptor['Username']['default'] ) &&
			!$this->isSignup()
		) {
			$user = $this->getUser();
			if ( $user->isLoggedIn() ) {
				$formDescriptor['Username']['default'] = $user->getName();
			} else {
				// TODO use the SessionManager method once this is rebased
				$formDescriptor['Username']['default'] = $this->getRequest()->getCookie( 'UserName' );
			}
		}

		if ( !$this->isSignup() ) {
			if (
				// FIXME HACK don't focus on non-empty field
				// maybe there should be an autofocus-if similar to hide-if?
				!empty( $fieldDefinitions['Username']['default'] )
				|| RequestContext::getMain()->getRequest()->getText( 'wpName' )
			) {
				$fieldDefinitions['Password']['autofocus'] = true;
			} else {
				$fieldDefinitions['Username']['autofocus'] = true;
			}
		}

		$canRemember = $wgExtendedLoginCookieExpiration === null ? ( $wgCookieExpiration > 0 )
			: ( $wgExtendedLoginCookieExpiration > 0 );
		$createEmail = $wgEnableEmail && $isLoggedIn;
		$useRealName = !in_array( 'realname', $wgHiddenPrefs, true );
		if ( !$canRemember ) {
			unset( $fieldDefinitions['Remember'] );
		}
		if ( !$createEmail ) {
			unset( $fieldDefinitions['CreateaccountMail']);
		}
		if ( !$isLoggedIn ) {
			unset( $fieldDefinitions['Reason'] );
		}
		if ( !$useRealName ) {
			unset( $fieldDefinitions['RealName'] );
		}
		if ( !$wgEnableEmail ) {
			unset( $fieldDefinitions['Email'] );
		}

		$fieldDefinitions = $this->getBCFieldDefinitions( $fieldDefinitions, $template );
		$fieldDefinitions = array_filter( $fieldDefinitions );

		$i = 1;
		foreach ( $fieldDefinitions as &$definition ) {
			if ( $definition['type'] !== 'info' ) {
				$definition['tabindex'] = $i;
				$i++;
			}
		}

		return $fieldDefinitions;
	}

	/**
	 * Adds fields provided via the deprecated UserLoginForm / UserCreateForm hooks
	 * @param $fieldDefinitions array
	 * @param FakeAuthTemplate $template
	 * @return array
	 */
	protected function getBCFieldDefinitions( $fieldDefinitions, $template ) {
		if ( $template->get( 'usedomain', false ) ) {
			// TODO probably should be translated to the new domain notation in AuthManager
			$fieldDefinitions['Domain'] = array(
				'type' => 'select',
				'label-message' => 'yourdomainname',
				'options' => array_combine( $template->get( 'domainnames', array() ),
					$template->get( 'domainnames', array() ) ),
				'default' => $template->get( 'domain', '' ),
				// FIXME id => 'mw-user-domain-section' on the parent div
			);
		}

		// poor man's associative array_splice
		$extraInputPos = array_search( 'extraInput', array_keys( $fieldDefinitions ), true );
		$fieldDefinitions = array_slice( $fieldDefinitions, 0, $extraInputPos, true)
			+ $template->getExtraInputDefinitions()
			+ array_slice( $fieldDefinitions, $extraInputPos + 1, null, true);

		return $fieldDefinitions;
	}

	/**
	 * Returns a string that can be appended to the URL (without encoding) to preserve the
	 * return target.
	 */
	protected function getReturnToQueryStringFragment() {
		$returnto = '';
		if ( $this->mReturnTo !== '' ) {
			$returnto = '&returnto=' . wfUrlencode( $this->mReturnTo );
			if ( $this->mReturnToQuery !== '' ) {
				$returnto .= '&returntoquery=' . wfUrlencode( $this->mReturnToQuery );
			}
		}
		return $returnto;
	}

	/**
	 * Whether the login/create account form should display a link to the
	 * other form (in addition to whatever the skin provides).
	 * @return bool
	 */
	private function showCreateOrLoginLink() {
		if ( $this->isSignup() ) {
			return true;
		} elseif ( $this->getUser()->isAllowed( 'createaccount' ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Check if a session cookie is present.
	 *
	 * This will not pick up a cookie set during _this_ request, but is meant
	 * to ensure that the client is returning the cookie which was set on a
	 * previous pass through the system.
	 *
	 * @private
	 * @return bool
	 */
	function hasSessionCookie() {
		global $wgDisableCookieCheck;

		return $wgDisableCookieCheck ? true : $this->getRequest()->checkSessionCookie();
	}

	/**
	 * Get the authn token from the current session
	 * @return mixed
	 */
	public static function getAuthenticationToken() {
		global $wgRequest;
		return $wgRequest->getSessionData( 'wsAuthToken' );
	}

	/**
	 * Randomly generate a new authn token and attach it to the current session
	 */
	public static function setAuthenticationToken() {
		global $wgRequest;
		// Generate a token directly instead of using $user->getEditToken()
		// because the latter reuses $_SESSION['wsEditToken']
		$wgRequest->setSessionData( 'wsAuthToken', MWCryptRand::generateHex( 32 ) );
	}

	/**
	 * Remove any authn token attached to the current session
	 */
	public static function clearAuthenticationToken() {
		global $wgRequest;
		$wgRequest->setSessionData( 'wsAuthToken', null );
	}

	/**
	 * Renew the user's session id, using strong entropy
	 */
	private function renewSessionId() {
		global $wgSecureLogin, $wgCookieSecure;
		if ( $wgSecureLogin && !$this->mStickHTTPS ) {
			$wgCookieSecure = false;
		}

		wfResetSessionID();
	}

	/**
	 * @param string $type
	 * @private
	 */
	function cookieRedirectCheck( $type ) {
		$titleObj = SpecialPage::getTitleFor( 'Userlogin' );
		$query = array( 'wpCookieCheck' => $type );
		if ( $this->mReturnTo !== '' ) {
			$query['returnto'] = $this->mReturnTo;
			$query['returntoquery'] = $this->mReturnToQuery;
		}
		$check = $titleObj->getFullURL( $query );

		$this->getOutput()->redirect( $check );
	}

	/**
	 * @param string $type
	 * @private
	 */
	function onCookieRedirectCheck( $type ) {
		if ( !$this->hasSessionCookie() ) {
			if ( $type == 'new' ) {
				$this->mainLoginForm( $this->msg( 'nocookiesnew' )->parse() );
			} elseif ( $type == 'login' ) {
				$this->mainLoginForm( $this->msg( 'nocookieslogin' )->parse() );
			} else {
				# shouldn't happen
				$this->mainLoginForm( $this->msg( 'error' )->text() );
			}
		} else {
			$this->successfulLogin();
		}
	}

	/**
	 * Produce a bar of links which allow the user to select another language
	 * during login/registration but retain "returnto"
	 *
	 * @return string
	 */
	function makeLanguageSelector() {
		$msg = $this->msg( 'loginlanguagelinks' )->inContentLanguage();
		if ( $msg->isBlank() ) {
			return '';
		}
		$langs = explode( "\n", $msg->text() );
		$links = array();
		foreach ( $langs as $lang ) {
			$lang = trim( $lang, '* ' );
			$parts = explode( '|', $lang );
			if ( count( $parts ) >= 2 ) {
				$links[] = $this->makeLanguageSelectorLink( $parts[0], trim( $parts[1] ) );
			}
		}

		return count( $links ) > 0 ? $this->msg( 'loginlanguagelabel' )->rawParams(
			$this->getLanguage()->pipeList( $links ) )->escaped() : '';
	}

	/**
	 * Create a language selector link for a particular language
	 * Links back to this page preserving type and returnto
	 *
	 * @param string $text Link text
	 * @param string $lang Language code
	 * @return string
	 */
	function makeLanguageSelectorLink( $text, $lang ) {
		if ( $this->getLanguage()->getCode() == $lang ) {
			// no link for currently used language
			return htmlspecialchars( $text );
		}
		$query = array( 'uselang' => $lang );
		if ( $this->isSignup() ) {
			$query['type'] = 'signup';
		}
		if ( $this->mReturnTo !== '' ) {
			$query['returnto'] = $this->mReturnTo;
			$query['returntoquery'] = $this->mReturnToQuery;
		}

		$attr = array();
		$targetLanguage = Language::factory( $lang );
		$attr['lang'] = $attr['hreflang'] = $targetLanguage->getHtmlCode();

		return Linker::linkKnown(
			$this->getPageTitle(),
			htmlspecialchars( $text ),
			$attr,
			$query
		);
	}

	protected function getGroupName() {
		return 'login';
	}

	/**
	 * Private function to check password expiration, until this is rewritten for AuthManager.
	 * @param User $user
	 * @return string|bool
	 */
	private function checkUserPasswordExpired( User $user ) {
		global $wgPasswordExpireGrace;
		$dbr = wfGetDB( DB_SLAVE );
		$ts = $dbr->selectField( 'user', 'user_password_expires', array( 'user_id' => $user->getId() ) );

		$expired = false;
		$now = wfTimestamp();
		$expUnix = wfTimestamp( TS_UNIX, $ts );
		if ( $ts !== null && $expUnix < $now ) {
			$expired = ( $expUnix + $wgPasswordExpireGrace < $now ) ? 'hard' : 'soft';
		}
		return $expired;
	}
}

/**
 * B/C class to try handling login template modifications even though login does not actually
 * happen through a template anymore. Just collects extra field definitions and allows some other
 * class to do the rendering.
 * TODO find the right place for this, if one even exists... probably a pre-auth provider?
 */
class FakeAuthTemplate extends BaseTemplate {
	public function execute() {
		throw new LogicException( 'not used' );
	}

	/**
	 * Extensions (AntiSpoof and TitleBlacklist) call this in response to
	 * UserCreateForm hook to add checkboxes to the create account form.
	 */
	public function addInputItem( $name, $value, $type, $msg, $helptext = false ) {
		// use the same indexes as UserCreateForm just in case someone adds an item manually
		$this->data['extraInput'][] = array(
			'name' => $name,
			'value' => $value,
			'type' => $type,
			'msg' => $msg,
			'helptext' => $helptext,
		);
	}

	/**
	 * Turns addInputItem-style field definitions into HTMLForm field definitions.
	 * @return array
	 */
	public function getExtraInputDefinitions() {
		$definitions = array();

		foreach ( $this->get( 'extraInput', array() ) as $field ) {
			$definition = array(
				'type' => $field['type'] === 'checkbox' ? 'check' : $field['type'],
				'name' => $field['name'],
				'value' => $field['value'],
				'id' => $field['name'],
			);
			if ( $field['msg'] ) {
				$definition['label-message'] = $this->getMsg( $field['msg'] );
			}
			if ( $field['helptext'] ) {
				$definition['help'] = $this->msgWiki( $field['helptext'] );
			}

			// the array key doesn't matter much when name is defined explicitly but
			//let's try and follow HTMLForm conventions
			$name = preg_replace( '/^wp(?=[A-Z])/', '', $field['name'] );
			$definitions[$name] = $definition;
		}

		if ( $this->haveData( 'extrafields' ) ) {
			$definitions['ExtraFields'] = array(
				'type' => 'info',
				'raw' => true,
				'default' => $this->get( 'extrafields' ),
			);
		}

		return $definitions;
	}
}