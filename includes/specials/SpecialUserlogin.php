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

	var $mUsername, $mPassword, $mRetype, $mReturnTo, $mCookieCheck, $mPosted;
	var $mAction, $mCreateaccount, $mCreateaccountMail;
	var $mLoginattempt, $mRemember, $mEmail, $mDomain, $mLanguage;
	var $mSkipCookieCheck, $mReturnToQuery, $mToken, $mStickHTTPS;
	var $mType, $mReason, $mRealName;
	var $mAbortLoginErrorMsg = null;
	private $mLoaded = false;
	private $mSecureLoginUrl;

	/**
	 * @ var WebRequest
	 */
	private $mOverrideRequest = null;

	/**
	 * Effective request; set at the beginning of load
	 *
	 * @var WebRequest $mRequest
	 */
	private $mRequest = null;

	/**
	 * @param WebRequest $request
	 */
	public function __construct( $request = null ) {
		parent::__construct( 'Userlogin' );

		$this->mOverrideRequest = $request;
	}

	/**
	 * Loader
	 */
	function load() {
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

		$this->mType = $request->getText( 'type' );
		$this->mUsername = $request->getText( 'wpName' );
		$this->mPassword = $request->getText( 'wpPassword' );
		$this->mRetype = $request->getText( 'wpRetype' );
		$this->mDomain = $request->getText( 'wpDomain' );
		$this->mReason = $request->getText( 'wpReason' );
		$this->mCookieCheck = $request->getVal( 'wpCookieCheck' );
		$this->mPosted = $request->wasPosted();
		$this->mCreateaccountMail = $request->getCheck( 'wpCreateaccountMail' )
									&& $wgEnableEmail;
		$this->mCreateaccount = $request->getCheck( 'wpCreateaccount' ) && !$this->mCreateaccountMail;
		$this->mLoginattempt = $request->getCheck( 'wpLoginattempt' );
		$this->mAction = $request->getVal( 'action' );
		$this->mRemember = $request->getCheck( 'wpRemember' );
		$this->mFromHTTP = $request->getBool( 'fromhttp', false );
		$this->mStickHTTPS = ( !$this->mFromHTTP && $request->detectProtocol() === 'https' ) || $request->getBool( 'wpForceHttps', false );
		$this->mLanguage = $request->getText( 'uselang' );
		$this->mSkipCookieCheck = $request->getCheck( 'wpSkipCookieCheck' );
		$this->mToken = ( $this->mType == 'signup' ) ? $request->getVal( 'wpCreateaccountToken' ) : $request->getVal( 'wpLoginToken' );
		$this->mReturnTo = $request->getVal( 'returnto', '' );
		$this->mReturnToQuery = $request->getVal( 'returntoquery', '' );

		if ( $wgEnableEmail ) {
			$this->mEmail = $request->getText( 'wpEmail' );
		} else {
			$this->mEmail = '';
		}
		if ( !in_array( 'realname', $wgHiddenPrefs ) ) {
			$this->mRealName = $request->getText( 'wpRealName' );
		} else {
			$this->mRealName = '';
		}

		if ( !$wgAuth->validDomain( $this->mDomain ) ) {
			$this->mDomain = $wgAuth->getDomain();
		}
		$wgAuth->setDomain( $this->mDomain );

		# 1. When switching accounts, it sucks to get automatically logged out
		# 2. Do not return to PasswordReset after a successful password change
		#    but goto Wiki start page (Main_Page) instead ( bug 33997 )
		$returnToTitle = Title::newFromText( $this->mReturnTo );
		if ( is_object( $returnToTitle ) && (
			$returnToTitle->isSpecial( 'Userlogout' )
			|| $returnToTitle->isSpecial( 'PasswordReset' ) ) ) {
			$this->mReturnTo = '';
			$this->mReturnToQuery = '';
		}
	}

	function getDescription() {
		if ( $this->mType === 'signup' ) {
			return $this->msg( 'createaccount' )->text();
		} else {
			return $this->msg( 'login' )->text();
		}
	}

	/*
	 * @param $subPage string|null
	 */
	public function execute( $subPage ) {
		if ( session_id() == '' ) {
			wfSetupSession();
		}

		$this->load();

		// Check for [[Special:Userlogin/signup]]. This affects form display and
		// page title.
		if ( $subPage == 'signup' ) {
			$this->mType = 'signup';
		}
		$this->setHeaders();

		// If logging in and not on HTTPS, either redirect to it or offer a link.
		global $wgSecureLogin;
		if ( WebRequest::detectProtocol() !== 'https' ) {
			$title = $this->getFullTitle();
			$query = array(
				'returnto' => $this->mReturnTo,
				'returntoquery' => $this->mReturnToQuery,
				'title' => null,
			) + $this->mRequest->getQueryValues();
			$url = $title->getFullURL( $query, false, PROTO_HTTPS );
			if ( $wgSecureLogin && wfCanIPUseHTTPS( $this->getRequest()->getIP() ) ) {
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
		$this->mainLoginForm( '' );
	}

	/**
	 * @private
	 */
	function addNewAccountMailPassword() {
		if ( $this->mEmail == '' ) {
			$this->mainLoginForm( $this->msg( 'noemailcreate' )->escaped() );
			return;
		}

		$status = $this->addNewaccountInternal();
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

		wfRunHooks( 'AddNewAccount', array( $u, true ) );
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
			wfRunHooks( 'AddNewAccount', array( $u, false ) );
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
			$out->addReturnTo( $this->getTitle() );
			wfRunHooks( 'AddNewAccount', array( $u, false ) );
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
		global $wgAuth, $wgMemc, $wgAccountCreationThrottle,
			$wgMinimalPasswordLength, $wgEmailConfirmToEdit;

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
		$permErrors = $this->getTitle()->getUserPermissionsErrors( 'createaccount', $currentUser, true );
		if ( count( $permErrors ) ) {
				throw new PermissionsError( 'createaccount', $permErrors );
		}

		$ip = $this->getRequest()->getIP();
		if ( $currentUser->isDnsBlacklisted( $ip, true /* check $wgProxyWhitelist */ ) ) {
			return Status::newFatal( 'sorbs_create_account_reason' );
		}

		# Now create a dummy user ($u) and check if it is valid
		$name = trim( $this->mUsername );
		$u = User::newFromName( $name, 'creatable' );
		if ( !is_object( $u ) ) {
			return Status::newFatal( 'noname' );
		} elseif ( 0 != $u->idForName() ) {
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

			# check for minimal password length
			$valid = $u->getPasswordValidity( $this->mPassword );
			if ( $valid !== true ) {
				if ( !is_array( $valid ) ) {
					$valid = array( $valid, $wgMinimalPasswordLength );
				}
				return call_user_func_array( 'Status::newFatal', $valid );
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
		if ( !wfRunHooks( 'AbortNewAccount', array( $u, &$abortError ) ) ) {
			// Hook point to add extra creation throttles and blocks
			wfDebug( "LoginForm::addNewAccountInternal: a hook blocked creation\n" );
			$abortError = new RawMessage( $abortError );
			$abortError->text();
			return Status::newFatal( $abortError );
		}

		// Hook point to check for exempt from account creation throttle
		if ( !wfRunHooks( 'ExemptFromAccountCreationThrottle', array( $ip ) ) ) {
			wfDebug( "LoginForm::exemptFromAccountCreationThrottle: a hook allowed account creation w/o throttle\n" );
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
	 * @param $u User object.
	 * @param $autocreate boolean -- true if this is an autocreation via auth plugin
	 * @return Status object, with the User object in the value member on success
	 * @private
	 */
	function initUser( $u, $autocreate ) {
		global $wgAuth;

		$status = $u->addToDatabase();
		if ( !$status->isOK() ) {
			return $status;
		}

		if ( $wgAuth->allowPasswordChange() ) {
			$u->setPassword( $this->mPassword );
		}

		$u->setEmail( $this->mEmail );
		$u->setRealName( $this->mRealName );
		$u->setToken();

		$wgAuth->initUser( $u, $autocreate );

		$u->setOption( 'rememberpassword', $this->mRemember ? 1 : 0 );
		$u->saveSettings();

		# Update user count
		DeferredUpdates::addUpdate( new SiteStatsUpdate( 0, 0, 0, 0, 1 ) );

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
		if ( !( $u instanceof User ) || !User::isUsableName( $u->getName() ) ) {
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
		$msg = null;
		if ( !wfRunHooks( 'AbortLogin', array( $u, $this->mPassword, &$abort, &$msg ) ) ) {
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
				if ( !$u->isEmailConfirmed() ) {
					$u->confirmEmail();
					$u->saveSettings();
				}

				// At this point we just return an appropriate code/ indicating
				// that the UI should show a password reset form; bot inter-
				// faces etc will probably just fail cleanly here.
				$retval = self::RESET_PASS;
			} else {
				$retval = ( $this->mPassword == '' ) ? self::EMPTY_PASS : self::WRONG_PASS;
			}
		} elseif ( $wgBlockDisablesLogin && $u->isBlocked() ) {
			// If we've enabled it, make it so that a blocked user cannot login
			$retval = self::USER_BLOCKED;
		} else {
			$wgAuth->updateUser( $u );
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
				wfRunHooks( 'AuthPluginAutoCreate', array( $u ) );
			}

			$retval = self::SUCCESS;
		}
		wfRunHooks( 'LoginAuthenticateAudit', array( $u, $this->mPassword, $retval ) );
		return $retval;
	}

	/**
	 * Increment the login attempt throttle hit count for the (username,current IP)
	 * tuple unless the throttle was already reached.
	 * @param string $username The user name
	 * @return Bool|Integer The integer hit count or True if it is already at the limit
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
	 * @param $user User
	 *
	 * @return integer Status code
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
		if ( !wfRunHooks( 'AbortAutoAccount', array( $user, &$abortError ) ) ) {
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
		global $wgMemc, $wgLang, $wgSecureLogin, $wgPasswordAttemptThrottle;

		switch ( $this->authenticateUserData() ) {
			case self::SUCCESS:
				# We've verified now, update the real record
				$user = $this->getUser();
				if ( (bool)$this->mRemember != $user->getBoolOption( 'rememberpassword' ) ) {
					$user->setOption( 'rememberpassword', $this->mRemember ? 1 : 0 );
					$user->saveSettings();
				} else {
					$user->invalidateCache();
				}

				if ( $user->requiresHTTPS() ) {
					$this->mStickHTTPS = true;
				}

				if ( $wgSecureLogin && !$this->mStickHTTPS ) {
					$user->setCookies( null, false );
				} else {
					$user->setCookies();
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
					$this->successfulLogin();
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
				$this->resetLoginForm( $this->msg( $error )->text() );
				break;
			case self::CREATE_BLOCKED:
				$this->userBlockedMessage( $this->getUser()->isBlockedFromCreateAccount() );
				break;
			case self::THROTTLED:
				$error = $this->mAbortLoginErrorMsg ?: 'login-throttled';
				$this->mainLoginForm( $this->msg( $error )
				->params ( $this->getLanguage()->formatDuration( $wgPasswordAttemptThrottle['seconds'] ) )
				->text()
				);
				break;
			case self::USER_BLOCKED:
				$error = $this->mAbortLoginErrorMsg ?: 'login-userblocked';
				$this->mainLoginForm( $this->msg( $error, $this->mUsername )->escaped() );
				break;
			case self::ABORTED:
				$error = $this->mAbortLoginErrorMsg ?: 'login-abort-generic';
				$this->mainLoginForm( $this->msg( $error )->text() );
				break;
			default:
				throw new MWException( 'Unhandled case value' );
		}
	}

	/**
	 * @param $error string
	 */
	function resetLoginForm( $error ) {
		$this->getOutput()->addHTML( Xml::element( 'p', array( 'class' => 'error' ), $error ) );
		$reset = new SpecialChangePassword();
		$reset->setContext( $this->getContext() );
		$reset->execute( null );
	}

	/**
	 * @param $u User object
	 * @param $throttle Boolean
	 * @param string $emailTitle message name of email title
	 * @param string $emailText message name of email text
	 * @return Status object
	 */
	function mailPasswordInternal( $u, $throttle = true, $emailTitle = 'passwordremindertitle', $emailText = 'passwordremindertext' ) {
		global $wgCanonicalServer, $wgScript, $wgNewPasswordExpiry;

		if ( $u->getEmail() == '' ) {
			return Status::newFatal( 'noemail', $u->getName() );
		}
		$ip = $this->getRequest()->getIP();
		if ( !$ip ) {
			return Status::newFatal( 'badipaddress' );
		}

		$currentUser = $this->getUser();
		wfRunHooks( 'User::mailPasswordInternal', array( &$currentUser, &$ip, &$u ) );

		$np = $u->randomPassword();
		$u->setNewpassword( $np, $throttle );
		$u->saveSettings();
		$userLanguage = $u->getOption( 'language' );
		$m = $this->msg( $emailText, $ip, $u->getName(), $np, '<' . $wgCanonicalServer . $wgScript . '>',
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
		wfRunHooks( 'UserLoginComplete', array( &$currentUser, &$injected_html ) );

		if ( $injected_html !== '' ) {
			$this->displaySuccessfulAction( $this->msg( 'loginsuccesstitle' ),
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

		wfRunHooks( 'UserLoginComplete', array( &$currentUser, &$injected_html ) );

		/**
		 * Let any extensions change what message is shown.
		 * @see https://www.mediawiki.org/wiki/Manual:Hooks/BeforeWelcomeCreation
		 * @since 1.18
		 */
		wfRunHooks( 'BeforeWelcomeCreation', array( &$welcome_creation_msg, &$injected_html ) );

		$this->displaySuccessfulAction( $this->msg( 'welcomeuser', $this->getUser()->getName() ),
			$welcome_creation_msg, $injected_html );
	}

	/**
	 * Display an "successful action" page.
	 *
	 * @param string|Message $title page's title
	 * @param $msgname string
	 * @param $injected_html string
	 */
	private function displaySuccessfulAction( $title, $msgname, $injected_html ) {
		$out = $this->getOutput();
		$out->setPageTitle( $title );
		if ( $msgname ) {
			$out->addWikiMsg( $msgname, wfEscapeWikiText( $this->getUser()->getName() ) );
		}

		$out->addHTML( $injected_html );

		$this->executeReturnTo( 'success' );
	}

	/**
	 * Output a message that informs the user that they cannot create an account because
	 * there is a block on them or their IP which prevents account creation.  Note that
	 * User::isBlockedFromCreateAccount(), which gets this block, ignores the 'hardblock'
	 * setting on blocks (bug 13611).
	 * @param $block Block the block causing this error
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
		throw new ErrorPageError(
			'cantcreateaccounttitle',
			'cantcreateaccount-text',
			array(
				$block->getTarget(),
				$block->mReason ? $block->mReason : $this->msg( 'blockednoreason' )->text(),
				$block->getByName()
			)
		);
	}

	/**
	 * Add a "return to" link or redirect to it.
	 * Extensions can use this to reuse the "return to" logic after
	 * inject steps (such as redirection) into the login process.
	 *
	 * @param $type string, one of the following:
	 *    - error: display a return to link ignoring $wgRedirectOnLogin
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
	 * @param $type string, one of the following:
	 *    - error: display a return to link ignoring $wgRedirectOnLogin
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
	 * @private
	 */
	function mainLoginForm( $msg, $msgtype = 'error' ) {
		global $wgEnableEmail, $wgEnableUserEmail;
		global $wgHiddenPrefs, $wgLoginLanguageSelector;
		global $wgAuth, $wgEmailConfirmToEdit, $wgCookieExpiration;
		global $wgSecureLogin, $wgPasswordResetRoutes;

		$titleObj = $this->getTitle();
		$user = $this->getUser();
		$out = $this->getOutput();

		if ( $this->mType == 'signup' ) {
			// Block signup here if in readonly. Keeps user from
			// going through the process (filling out data, etc)
			// and being informed later.
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

		// Pre-fill username (if not creating an account, bug 44775).
		if ( $this->mUsername == '' && $this->mType != 'signup' ) {
			if ( $user->isLoggedIn() ) {
				$this->mUsername = $user->getName();
			} else {
				$this->mUsername = $this->getRequest()->getCookie( 'UserName' );
			}
		}

		if ( $this->mType == 'signup' ) {
			$template = new UsercreateTemplate();

			$out->addModuleStyles( array(
				'mediawiki.ui',
				'mediawiki.special.createaccount'
			) );
			// XXX hack pending RL or JS parse() support for complex content messages
			// https://bugzilla.wikimedia.org/show_bug.cgi?id=25349
			$out->addJsConfigVars( 'wgCreateacctImgcaptchaHelp',
				$this->msg( 'createacct-imgcaptcha-help' )->parse() );
			$out->addModules( array(
				'mediawiki.special.createaccount.js'
			) );
			// Must match number of benefits defined in messages
			$template->set( 'benefitCount', 3 );

			$q = 'action=submitlogin&type=signup';
			$linkq = 'type=login';
		} else {
			$template = new UserloginTemplate();

			$out->addModuleStyles( array(
				'mediawiki.ui',
				'mediawiki.special.userlogin'
			) );

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
		if ( $this->showCreateOrLoginLink( $user ) ) {
			# Pass any language selection on to the mode switch link
			if ( $wgLoginLanguageSelector && $this->mLanguage ) {
				$linkq .= '&uselang=' . $this->mLanguage;
			}
			// Supply URL, login template creates the button.
			$template->set( 'createOrLoginHref', $titleObj->getLocalURL( $linkq ) );
		} else {
			$template->set( 'link', '' );
		}

		$resetLink = $this->mType == 'signup'
			? null
			: is_array( $wgPasswordResetRoutes ) && in_array( true, array_values( $wgPasswordResetRoutes ) );

		$template->set( 'header', '' );
		$template->set( 'skin', $this->getSkin() );
		$template->set( 'name', $this->mUsername );
		$template->set( 'password', $this->mPassword );
		$template->set( 'retype', $this->mRetype );
		$template->set( 'createemailset', $this->mCreateaccountMail );
		$template->set( 'email', $this->mEmail );
		$template->set( 'realname', $this->mRealName );
		$template->set( 'domain', $this->mDomain );
		$template->set( 'reason', $this->mReason );

		$template->set( 'action', $titleObj->getLocalURL( $q ) );
		$template->set( 'message', $msg );
		$template->set( 'messagetype', $msgtype );
		$template->set( 'createemail', $wgEnableEmail && $user->isLoggedIn() );
		$template->set( 'userealname', !in_array( 'realname', $wgHiddenPrefs ) );
		$template->set( 'useemail', $wgEnableEmail );
		$template->set( 'emailrequired', $wgEmailConfirmToEdit );
		$template->set( 'emailothers', $wgEnableUserEmail );
		$template->set( 'canreset', $wgAuth->allowPasswordChange() );
		$template->set( 'resetlink', $resetLink );
		$template->set( 'canremember', ( $wgCookieExpiration > 0 ) );
		$template->set( 'usereason', $user->isLoggedIn() );
		$template->set( 'remember', $user->getOption( 'rememberpassword' ) || $this->mRemember );
		$template->set( 'cansecurelogin', ( $wgSecureLogin === true ) );
		$template->set( 'stickhttps', (int)$this->mStickHTTPS );
		$template->set( 'loggedin', $user->isLoggedIn() );
		$template->set( 'loggedinuser', $user->getName() );

		if ( $this->mType == 'signup' ) {
			if ( !self::getCreateaccountToken() ) {
				self::setCreateaccountToken();
			}
			$template->set( 'token', self::getCreateaccountToken() );
		} else {
			if ( !self::getLoginToken() ) {
				self::setLoginToken();
			}
			$template->set( 'token', self::getLoginToken() );
		}

		# Prepare language selection links as needed
		if ( $wgLoginLanguageSelector ) {
			$template->set( 'languages', $this->makeLanguageSelector() );
			if ( $this->mLanguage ) {
				$template->set( 'uselang', $this->mLanguage );
			}
		}

		$template->set( 'secureLoginUrl', $this->mSecureLoginUrl );
		// Use loginend-https for HTTPS requests if it's not blank, loginend otherwise
		// Ditto for signupend.  New forms use neither.
		$usingHTTPS = WebRequest::detectProtocol() == 'https';
		$loginendHTTPS = $this->msg( 'loginend-https' );
		$signupendHTTPS = $this->msg( 'signupend-https' );
		if ( $usingHTTPS && !$loginendHTTPS->isBlank() ) {
			$template->set( 'loginend', $loginendHTTPS->parse() );
		} else {
			$template->set( 'loginend', $this->msg( 'loginend' )->parse() );
		}
		if ( $usingHTTPS && !$signupendHTTPS->isBlank() ) {
			$template->set( 'signupend', $signupendHTTPS->parse() );
		} else {
			$template->set( 'signupend', $this->msg( 'signupend' )->parse() );
		}

		// Give authentication and captcha plugins a chance to modify the form
		$wgAuth->modifyUITemplate( $template, $this->mType );
		if ( $this->mType == 'signup' ) {
			wfRunHooks( 'UserCreateForm', array( &$template ) );
		} else {
			wfRunHooks( 'UserLoginForm', array( &$template ) );
		}

		$out->disallowUserJs(); // just in case...
		$out->addTemplate( $template );
	}

	/**
	 * Whether the login/create account form should display a link to the
	 * other form (in addition to whatever the skin provides).
	 *
	 * @param $user User
	 * @return bool
	 */
	private function showCreateOrLoginLink( &$user ) {
		if ( $this->mType == 'signup' ) {
			return true;
		} elseif ( $user->isAllowed( 'createaccount' ) ) {
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
	 * Get the login token from the current session
	 * @return Mixed
	 */
	public static function getLoginToken() {
		global $wgRequest;
		return $wgRequest->getSessionData( 'wsLoginToken' );
	}

	/**
	 * Randomly generate a new login token and attach it to the current session
	 */
	public static function setLoginToken() {
		global $wgRequest;
		// Generate a token directly instead of using $user->editToken()
		// because the latter reuses $_SESSION['wsEditToken']
		$wgRequest->setSessionData( 'wsLoginToken', MWCryptRand::generateHex( 32 ) );
	}

	/**
	 * Remove any login token attached to the current session
	 */
	public static function clearLoginToken() {
		global $wgRequest;
		$wgRequest->setSessionData( 'wsLoginToken', null );
	}

	/**
	 * Get the createaccount token from the current session
	 * @return Mixed
	 */
	public static function getCreateaccountToken() {
		global $wgRequest;
		return $wgRequest->getSessionData( 'wsCreateaccountToken' );
	}

	/**
	 * Randomly generate a new createaccount token and attach it to the current session
	 */
	public static function setCreateaccountToken() {
		global $wgRequest;
		$wgRequest->setSessionData( 'wsCreateaccountToken', MWCryptRand::generateHex( 32 ) );
	}

	/**
	 * Remove any createaccount token attached to the current session
	 */
	public static function clearCreateaccountToken() {
		global $wgRequest;
		$wgRequest->setSessionData( 'wsCreateaccountToken', null );
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
		if ( !$msg->isBlank() ) {
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
		} else {
			return '';
		}
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
		if ( $this->mType == 'signup' ) {
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
			$this->getTitle(),
			htmlspecialchars( $text ),
			$attr,
			$query
		);
	}

	protected function getGroupName() {
		return 'login';
	}
}
