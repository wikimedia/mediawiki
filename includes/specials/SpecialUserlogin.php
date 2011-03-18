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
	var $mAction, $mCreateaccount, $mCreateaccountMail, $mMailmypassword;
	var $mLoginattempt, $mRemember, $mEmail, $mDomain, $mLanguage;
	var $mSkipCookieCheck, $mReturnToQuery, $mToken, $mStickHTTPS;
	var $mType, $mReason, $mRealName;
	var $mAbortLoginErrorMsg = 'login-abort-generic';

	/**
	 * @var ExternalUser
	 */
	private $mExtUser = null;

	/**
	 * @param WebRequest $request
	 */
	public function __construct( $request = null ) {
		parent::__construct( 'Userlogin' );

		if ( $request === null ) {
			global $wgRequest;
			$this->load( $wgRequest );
		} else {
			$this->load( $request );
		}
	}

	/**
	 * Loader
	 *
	 * @param $request WebRequest object
	 */
	function load( $request ) {
		global $wgAuth, $wgHiddenPrefs, $wgEnableEmail, $wgRedirectOnLogin;

		$this->mType = $request->getText( 'type' );
		$this->mUsername = $request->getText( 'wpName' );
		$this->mPassword = $request->getText( 'wpPassword' );
		$this->mRetype = $request->getText( 'wpRetype' );
		$this->mDomain = $request->getText( 'wpDomain' );
		$this->mReason = $request->getText( 'wpReason' );
		$this->mReturnTo = $request->getVal( 'returnto' );
		$this->mReturnToQuery = $request->getVal( 'returntoquery' );
		$this->mCookieCheck = $request->getVal( 'wpCookieCheck' );
		$this->mPosted = $request->wasPosted();
		$this->mCreateaccount = $request->getCheck( 'wpCreateaccount' );
		$this->mCreateaccountMail = $request->getCheck( 'wpCreateaccountMail' )
									&& $wgEnableEmail;
		$this->mMailmypassword = $request->getCheck( 'wpMailmypassword' )
								 && $wgEnableEmail;
		$this->mLoginattempt = $request->getCheck( 'wpLoginattempt' );
		$this->mAction = $request->getVal( 'action' );
		$this->mRemember = $request->getCheck( 'wpRemember' );
		$this->mStickHTTPS = $request->getCheck( 'wpStickHTTPS' );
		$this->mLanguage = $request->getText( 'uselang' );
		$this->mSkipCookieCheck = $request->getCheck( 'wpSkipCookieCheck' );
		$this->mToken = ( $this->mType == 'signup' ) ? $request->getVal( 'wpCreateaccountToken' ) : $request->getVal( 'wpLoginToken' );

		if ( $wgRedirectOnLogin ) {
			$this->mReturnTo = $wgRedirectOnLogin;
			$this->mReturnToQuery = '';
		}

		if( $wgEnableEmail ) {
			$this->mEmail = $request->getText( 'wpEmail' );
		} else {
			$this->mEmail = '';
		}
		if( !in_array( 'realname', $wgHiddenPrefs ) ) {
			$this->mRealName = $request->getText( 'wpRealName' );
		} else {
			$this->mRealName = '';
		}

		if( !$wgAuth->validDomain( $this->mDomain ) ) {
			$this->mDomain = 'invaliddomain';
		}
		$wgAuth->setDomain( $this->mDomain );

		# When switching accounts, it sucks to get automatically logged out
		$returnToTitle = Title::newFromText( $this->mReturnTo );
		if( is_object( $returnToTitle ) && $returnToTitle->isSpecial( 'Userlogout' ) ) {
			$this->mReturnTo = '';
			$this->mReturnToQuery = '';
		}
	}

	public function execute( $par ) {
		if ( session_id() == '' ) {
			wfSetupSession();
		}

		if ( $par == 'signup' ) { # Check for [[Special:Userlogin/signup]]
			$this->mType = 'signup';
		}

		if ( !is_null( $this->mCookieCheck ) ) {
			$this->onCookieRedirectCheck( $this->mCookieCheck );
			return;
		} elseif( $this->mPosted ) {
			if( $this->mCreateaccount ) {
				return $this->addNewAccount();
			} elseif ( $this->mCreateaccountMail ) {
				return $this->addNewAccountMailPassword();
			} elseif ( $this->mMailmypassword ) {
				return $this->mailPassword();
			} elseif ( ( 'submitlogin' == $this->mAction ) || $this->mLoginattempt ) {
				return $this->processLogin();
			}
		}
		$this->mainLoginForm( '' );
	}

	/**
	 * @private
	 */
	function addNewAccountMailPassword() {
		global $wgOut;

		if ( $this->mEmail == '' ) {
			$this->mainLoginForm( wfMsgExt( 'noemail', array( 'parsemag', 'escape' ), $this->mUsername ) );
			return;
		}

		$u = $this->addNewaccountInternal();

		if ( $u == null ) {
			return;
		}

		// Wipe the initial password and mail a temporary one
		$u->setPassword( null );
		$u->saveSettings();
		$result = $this->mailPasswordInternal( $u, false, 'createaccount-title', 'createaccount-text' );

		wfRunHooks( 'AddNewAccount', array( $u, true ) );
		$u->addNewUserLogEntry( true, $this->mReason );

		$wgOut->setPageTitle( wfMsg( 'accmailtitle' ) );

		if( !$result->isGood() ) {
			$this->mainLoginForm( wfMsg( 'mailerror', $result->getWikiText() ) );
		} else {
			$wgOut->addWikiMsg( 'accmailtext', $u->getName(), $u->getEmail() );
			$wgOut->returnToMain( false );
		}
	}

	/**
	 * @private
	 */
	function addNewAccount() {
		global $wgUser, $wgEmailAuthentication, $wgOut;

		# Create the account and abort if there's a problem doing so
		$u = $this->addNewAccountInternal();
		if( $u == null ) {
			return;
		}

		# If we showed up language selection links, and one was in use, be
		# smart (and sensible) and save that language as the user's preference
		global $wgLoginLanguageSelector;
		if( $wgLoginLanguageSelector && $this->mLanguage ) {
			$u->setOption( 'language', $this->mLanguage );
		}

		# Send out an email authentication message if needed
		if( $wgEmailAuthentication && User::isValidEmailAddr( $u->getEmail() ) ) {
			$status = $u->sendConfirmationMail();
			if( $status->isGood() ) {
				$wgOut->addWikiMsg( 'confirmemail_oncreate' );
			} else {
				$wgOut->addWikiText( $status->getWikiText( 'confirmemail_sendfailed' ) );
			}
		}

		# Save settings (including confirmation token)
		$u->saveSettings();

		# If not logged in, assume the new account as the current one and set
		# session cookies then show a "welcome" message or a "need cookies"
		# message as needed
		if( $wgUser->isAnon() ) {
			$wgUser = $u;
			$wgUser->setCookies();
			wfRunHooks( 'AddNewAccount', array( $wgUser, false ) );
			$wgUser->addNewUserLogEntry();
			if( $this->hasSessionCookie() ) {
				return $this->successfulCreation();
			} else {
				return $this->cookieRedirectCheck( 'new' );
			}
		} else {
			# Confirm that the account was created
			$self = SpecialPage::getTitleFor( 'Userlogin' );
			$wgOut->setPageTitle( wfMsgHtml( 'accountcreated' ) );
			$wgOut->addWikiMsg( 'accountcreatedtext', $u->getName() );
			$wgOut->returnToMain( false, $self );
			wfRunHooks( 'AddNewAccount', array( $u, false ) );
			$u->addNewUserLogEntry( false, $this->mReason );
			return true;
		}
	}

	/**
	 * @private
	 */
	function addNewAccountInternal() {
		global $wgUser, $wgOut;
		global $wgMemc, $wgAccountCreationThrottle;
		global $wgAuth, $wgMinimalPasswordLength;
		global $wgEmailConfirmToEdit;

		// If the user passes an invalid domain, something is fishy
		if( !$wgAuth->validDomain( $this->mDomain ) ) {
			$this->mainLoginForm( wfMsg( 'wrongpassword' ) );
			return false;
		}

		// If we are not allowing users to login locally, we should be checking
		// to see if the user is actually able to authenticate to the authenti-
		// cation server before they create an account (otherwise, they can
		// create a local account and login as any domain user). We only need
		// to check this for domains that aren't local.
		if( 'local' != $this->mDomain && $this->mDomain != '' ) {
			if( !$wgAuth->canCreateAccounts() && ( !$wgAuth->userExists( $this->mUsername )
				|| !$wgAuth->authenticate( $this->mUsername, $this->mPassword ) ) ) {
				$this->mainLoginForm( wfMsg( 'wrongpassword' ) );
				return false;
			}
		}

		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return false;
		}

		# Request forgery checks.
		if ( !self::getCreateaccountToken() ) {
			self::setCreateaccountToken();
			$this->mainLoginForm( wfMsgExt( 'nocookiesfornew', array( 'parseinline' ) ) );
			return false;
		}

		# The user didn't pass a createaccount token
		if ( !$this->mToken ) {
			$this->mainLoginForm( wfMsg( 'sessionfailure' ) );
			return false;
		}

		# Validate the createaccount token
		if ( $this->mToken !== self::getCreateaccountToken() ) {
			$this->mainLoginForm( wfMsg( 'sessionfailure' ) );
			return false;
		}

		# Check permissions
		if ( !$wgUser->isAllowed( 'createaccount' ) ) {
			$wgOut->permissionRequired( 'createaccount' );
			return false;
		} elseif ( $wgUser->isBlockedFromCreateAccount() ) {
			$this->userBlockedMessage();
			return false;
		}

		$ip = wfGetIP();
		if ( $wgUser->isDnsBlacklisted( $ip, true /* check $wgProxyWhitelist */ ) ) {
			$this->mainLoginForm( wfMsg( 'sorbs_create_account_reason' ) . ' (' . htmlspecialchars( $ip ) . ')' );
			return false;
		}

		# Now create a dummy user ($u) and check if it is valid
		$name = trim( $this->mUsername );
		$u = User::newFromName( $name, 'creatable' );
		if ( !is_object( $u ) ) {
			$this->mainLoginForm( wfMsg( 'noname' ) );
			return false;
		}

		if ( 0 != $u->idForName() ) {
			$this->mainLoginForm( wfMsg( 'userexists' ) );
			return false;
		}

		if ( 0 != strcmp( $this->mPassword, $this->mRetype ) ) {
			$this->mainLoginForm( wfMsg( 'badretype' ) );
			return false;
		}

		# check for minimal password length
		$valid = $u->getPasswordValidity( $this->mPassword );
		if ( $valid !== true ) {
			if ( !$this->mCreateaccountMail ) {
				if ( is_array( $valid ) ) {
					$message = array_shift( $valid );
					$params = $valid;
				} else {
					$message = $valid;
					$params = array( $wgMinimalPasswordLength );
				}
				$this->mainLoginForm( wfMsgExt( $message, array( 'parsemag' ), $params ) );
				return false;
			} else {
				# do not force a password for account creation by email
				# set invalid password, it will be replaced later by a random generated password
				$this->mPassword = null;
			}
		}

		# if you need a confirmed email address to edit, then obviously you
		# need an email address.
		if ( $wgEmailConfirmToEdit && empty( $this->mEmail ) ) {
			$this->mainLoginForm( wfMsg( 'noemailtitle' ) );
			return false;
		}

		if( !empty( $this->mEmail ) && !User::isValidEmailAddr( $this->mEmail ) ) {
			$this->mainLoginForm( wfMsg( 'invalidemailaddress' ) );
			return false;
		}

		# Set some additional data so the AbortNewAccount hook can be used for
		# more than just username validation
		$u->setEmail( $this->mEmail );
		$u->setRealName( $this->mRealName );

		$abortError = '';
		if( !wfRunHooks( 'AbortNewAccount', array( $u, &$abortError ) ) ) {
			// Hook point to add extra creation throttles and blocks
			wfDebug( "LoginForm::addNewAccountInternal: a hook blocked creation\n" );
			$this->mainLoginForm( $abortError );
			return false;
		}

		if ( $wgAccountCreationThrottle && $wgUser->isPingLimitable() ) {
			$key = wfMemcKey( 'acctcreate', 'ip', $ip );
			$value = $wgMemc->get( $key );
			if ( !$value ) {
				$wgMemc->set( $key, 0, 86400 );
			}
			if ( $value >= $wgAccountCreationThrottle ) {
				$this->throttleHit( $wgAccountCreationThrottle );
				return false;
			}
			$wgMemc->incr( $key );
		}

		if( !$wgAuth->addUser( $u, $this->mPassword, $this->mEmail, $this->mRealName ) ) {
			$this->mainLoginForm( wfMsg( 'externaldberror' ) );
			return false;
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
	 * @return User object.
	 * @private
	 */
	function initUser( $u, $autocreate ) {
		global $wgAuth;

		$u->addToDatabase();

		if ( $wgAuth->allowPasswordChange() ) {
			$u->setPassword( $this->mPassword );
		}

		$u->setEmail( $this->mEmail );
		$u->setRealName( $this->mRealName );
		$u->setToken();

		$wgAuth->initUser( $u, $autocreate );

		if ( $this->mExtUser ) {
			$this->mExtUser->linkToLocal( $u->getId() );
			$email = $this->mExtUser->getPref( 'emailaddress' );
			if ( $email && !$this->mEmail ) {
				$u->setEmail( $email );
			}
		}

		$u->setOption( 'rememberpassword', $this->mRemember ? 1 : 0 );
		$u->saveSettings();

		# Update user count
		$ssUpdate = new SiteStatsUpdate( 0, 0, 0, 0, 1 );
		$ssUpdate->doUpdate();

		return $u;
	}

	/**
	 * Internally authenticate the login request.
	 *
	 * This may create a local account as a side effect if the
	 * authentication plugin allows transparent local account
	 * creation.
	 */
	public function authenticateUserData() {
		global $wgUser, $wgAuth, $wgMemc;

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

		global $wgPasswordAttemptThrottle;

		$throttleCount = 0;
		if ( is_array( $wgPasswordAttemptThrottle ) ) {
			$throttleKey = wfMemcKey( 'password-throttle', wfGetIP(), md5( $this->mUsername ) );
			$count = $wgPasswordAttemptThrottle['count'];
			$period = $wgPasswordAttemptThrottle['seconds'];

			$throttleCount = $wgMemc->get( $throttleKey );
			if ( !$throttleCount ) {
				$wgMemc->add( $throttleKey, 1, $period ); // start counter
			} elseif ( $throttleCount < $count ) {
				$wgMemc->incr( $throttleKey );
			} elseif ( $throttleCount >= $count ) {
				return self::THROTTLED;
			}
		}

		// Validate the login token
		if ( $this->mToken !== self::getLoginToken() ) {
			return self::WRONG_TOKEN;
		}

		// Load $wgUser now, and check to see if we're logging in as the same
		// name. This is necessary because loading $wgUser (say by calling
		// getName()) calls the UserLoadFromSession hook, which potentially
		// creates the user in the database. Until we load $wgUser, checking
		// for user existence using User::newFromName($name)->getId() below
		// will effectively be using stale data.
		if ( $wgUser->getName() === $this->mUsername ) {
			wfDebug( __METHOD__ . ": already logged in as {$this->mUsername}\n" );
			return self::SUCCESS;
		}

		$this->mExtUser = ExternalUser::newFromName( $this->mUsername );

		# TODO: Allow some magic here for invalid external names, e.g., let the
		# user choose a different wiki name.
		$u = User::newFromName( $this->mUsername );
		if( !( $u instanceof User ) || !User::isUsableName( $u->getName() ) ) {
			return self::ILLEGAL;
		}

		$isAutoCreated = false;
		if ( 0 == $u->getID() ) {
			$status = $this->attemptAutoCreate( $u );
			if ( $status !== self::SUCCESS ) {
				return $status;
			} else {
				$isAutoCreated = true;
			}
		} else {
			global $wgExternalAuthType, $wgAutocreatePolicy;
			if ( $wgExternalAuthType && $wgAutocreatePolicy != 'never'
			&& is_object( $this->mExtUser )
			&& $this->mExtUser->authenticate( $this->mPassword ) ) {
				# The external user and local user have the same name and
				# password, so we assume they're the same.
				$this->mExtUser->linkToLocal( $u->getID() );
			}

			$u->load();
		}

		// Give general extensions, such as a captcha, a chance to abort logins
		$abort = self::ABORTED;
		if( !wfRunHooks( 'AbortLogin', array( $u, $this->mPassword, &$abort, &$this->mAbortLoginErrorMsg ) ) ) {
			return $abort;
		}

		global $wgBlockDisablesLogin;
		if ( !$u->checkPassword( $this->mPassword ) ) {
			if( $u->checkTemporaryPassword( $this->mPassword ) ) {
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
				if( !$u->isEmailConfirmed() ) {
					$u->confirmEmail();
					$u->saveSettings();
				}

				// At this point we just return an appropriate code/ indicating
				// that the UI should show a password reset form; bot inter-
				// faces etc will probably just fail cleanly here.
				$retval = self::RESET_PASS;
			} else {
				$retval = ( $this->mPassword  == '' ) ? self::EMPTY_PASS : self::WRONG_PASS;
			}
		} elseif ( $wgBlockDisablesLogin && $u->isBlocked() ) {
			// If we've enabled it, make it so that a blocked user cannot login
			$retval = self::USER_BLOCKED;
		} else {
			$wgAuth->updateUser( $u );
			$wgUser = $u;

			// Please reset throttle for successful logins, thanks!
			if( $throttleCount ) {
				$wgMemc->delete( $throttleKey );
			}

			if ( $isAutoCreated ) {
				// Must be run after $wgUser is set, for correct new user log
				wfRunHooks( 'AuthPluginAutoCreate', array( $wgUser ) );
			}

			$retval = self::SUCCESS;
		}
		wfRunHooks( 'LoginAuthenticateAudit', array( $u, $this->mPassword, $retval ) );
		return $retval;
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
		global $wgAuth, $wgUser, $wgAutocreatePolicy;

		if ( $wgUser->isBlockedFromCreateAccount() ) {
			wfDebug( __METHOD__ . ": user is blocked from account creation\n" );
			return self::CREATE_BLOCKED;
		}

		/**
		 * If the external authentication plugin allows it, automatically cre-
		 * ate a new account for users that are externally defined but have not
		 * yet logged in.
		 */
		if ( $this->mExtUser ) {
			# mExtUser is neither null nor false, so use the new ExternalAuth
			# system.
			if ( $wgAutocreatePolicy == 'never' ) {
				return self::NOT_EXISTS;
			}
			if ( !$this->mExtUser->authenticate( $this->mPassword ) ) {
				return self::WRONG_PLUGIN_PASS;
			}
		} else {
			# Old AuthPlugin.
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
		}

		wfDebug( __METHOD__ . ": creating account\n" );
		$this->initUser( $user, true );
		return self::SUCCESS;
	}

	function processLogin() {
		global $wgUser;

		switch ( $this->authenticateUserData() ) {
			case self::SUCCESS:
				# We've verified now, update the real record
				if( (bool)$this->mRemember != (bool)$wgUser->getOption( 'rememberpassword' ) ) {
					$wgUser->setOption( 'rememberpassword', $this->mRemember ? 1 : 0 );
					$wgUser->saveSettings();
				} else {
					$wgUser->invalidateCache();
				}
				$wgUser->setCookies();
				self::clearLoginToken();

				// Reset the throttle
				$key = wfMemcKey( 'password-throttle', wfGetIP(), md5( $this->mUsername ) );
				global $wgMemc;
				$wgMemc->delete( $key );

				if( $this->hasSessionCookie() || $this->mSkipCookieCheck ) {
					/* Replace the language object to provide user interface in
					 * correct language immediately on this first page load.
					 */
					global $wgLang, $wgRequest;
					$code = $wgRequest->getVal( 'uselang', $wgUser->getOption( 'language' ) );
					$wgLang = Language::factory( $code );
					return $this->successfulLogin();
				} else {
					return $this->cookieRedirectCheck( 'login' );
				}
				break;

			case self::NEED_TOKEN:
				$this->mainLoginForm( wfMsgExt( 'nocookiesforlogin', array( 'parseinline' ) ) );
				break;
			case self::WRONG_TOKEN:
				$this->mainLoginForm( wfMsg( 'sessionfailure' ) );
				break;
			case self::NO_NAME:
			case self::ILLEGAL:
				$this->mainLoginForm( wfMsg( 'noname' ) );
				break;
			case self::WRONG_PLUGIN_PASS:
				$this->mainLoginForm( wfMsg( 'wrongpassword' ) );
				break;
			case self::NOT_EXISTS:
				if( $wgUser->isAllowed( 'createaccount' ) ) {
					$this->mainLoginForm( wfMsgExt( 'nosuchuser', 'parseinline', $this->mUsername ) );
				} else {
					$this->mainLoginForm( wfMsg( 'nosuchusershort', htmlspecialchars( $this->mUsername ) ) );
				}
				break;
			case self::WRONG_PASS:
				$this->mainLoginForm( wfMsg( 'wrongpassword' ) );
				break;
			case self::EMPTY_PASS:
				$this->mainLoginForm( wfMsg( 'wrongpasswordempty' ) );
				break;
			case self::RESET_PASS:
				$this->resetLoginForm( wfMsg( 'resetpass_announce' ) );
				break;
			case self::CREATE_BLOCKED:
				$this->userBlockedMessage();
				break;
			case self::THROTTLED:
				$this->mainLoginForm( wfMsg( 'login-throttled' ) );
				break;
			case self::USER_BLOCKED:
				$this->mainLoginForm( wfMsgExt( 'login-userblocked',
					array( 'parsemag', 'escape' ), $this->mUsername ) );
				break;
			case self::ABORTED:
				$this->mainLoginForm( wfMsg( $this->mAbortLoginErrorMsg  ) );
				break;
			default:
				throw new MWException( 'Unhandled case value' );
		}
	}

	function resetLoginForm( $error ) {
		global $wgOut;
		$wgOut->addHTML( Xml::element('p', array( 'class' => 'error' ), $error ) );
		$reset = new SpecialResetpass();
		$reset->execute( null );
	}

	/**
	 * @private
	 */
	function mailPassword() {
		global $wgUser, $wgOut, $wgAuth;

		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return false;
		}

		if( !$wgAuth->allowPasswordChange() ) {
			$this->mainLoginForm( wfMsg( 'resetpass_forbidden' ) );
			return;
		}

		# Check against blocked IPs so blocked users can't flood admins
		# with password resets
		if( $wgUser->isBlocked() ) {
			$this->mainLoginForm( wfMsg( 'blocked-mailpassword' ) );
			return;
		}

		# Check for hooks
		$error = null;
		if ( !wfRunHooks( 'UserLoginMailPassword', array( $this->mUsername, &$error ) ) ) {
			$this->mainLoginForm( $error );
			return;
		}

		# If the user doesn't have a login token yet, set one.
		if ( !self::getLoginToken() ) {
			self::setLoginToken();
			$this->mainLoginForm( wfMsg( 'sessionfailure' ) );
			return;
		}

		# If the user didn't pass a login token, tell them we need one
		if ( !$this->mToken ) {
			$this->mainLoginForm( wfMsg( 'sessionfailure' ) );
			return;
		}

		# Check against the rate limiter
		if( $wgUser->pingLimiter( 'mailpassword' ) ) {
			$wgOut->rateLimited();
			return;
		}

		if ( $this->mUsername == '' ) {
			$this->mainLoginForm( wfMsg( 'noname' ) );
			return;
		}
		$u = User::newFromName( $this->mUsername );
		if( !$u instanceof User ) {
			$this->mainLoginForm( wfMsg( 'noname' ) );
			return;
		}
		if ( 0 == $u->getID() ) {
			$this->mainLoginForm( wfMsgExt( 'nosuchuser', 'parseinline', $u->getName() ) );
			return;
		}

		# Validate the login token
		if ( $this->mToken !== self::getLoginToken() ) {
			$this->mainLoginForm( wfMsg( 'sessionfailure' ) );
			return;
		}

		# Check against password throttle
		if ( $u->isPasswordReminderThrottled() ) {
			global $wgPasswordReminderResendTime;
			# Round the time in hours to 3 d.p., in case someone is specifying
			# minutes or seconds.
			$this->mainLoginForm( wfMsgExt( 'throttled-mailpassword', array( 'parsemag' ),
				round( $wgPasswordReminderResendTime, 3 ) ) );
			return;
		}

		$result = $this->mailPasswordInternal( $u, true, 'passwordremindertitle', 'passwordremindertext' );
		if( $result->isGood() ) {
			$this->mainLoginForm( wfMsg( 'passwordsent', $u->getName() ), 'success' );
			self::clearLoginToken();
		} else {
			$this->mainLoginForm( $result->getWikiText( 'mailerror' ) );
		}
	}


	/**
	 * @param $u User object
	 * @param $throttle Boolean
	 * @param $emailTitle String: message name of email title
	 * @param $emailText String: message name of email text
	 * @return Status object
	 * @private
	 */
	function mailPasswordInternal( $u, $throttle = true, $emailTitle = 'passwordremindertitle', $emailText = 'passwordremindertext' ) {
		global $wgServer, $wgScript, $wgUser, $wgNewPasswordExpiry;

		if ( $u->getEmail() == '' ) {
			return Status::newFatal( 'noemail', $u->getName() );
		}
		$ip = wfGetIP();
		if( !$ip ) {
			return Status::newFatal( 'badipaddress' );
		}

		wfRunHooks( 'User::mailPasswordInternal', array( &$wgUser, &$ip, &$u ) );

		$np = $u->randomPassword();
		$u->setNewpassword( $np, $throttle );
		$u->saveSettings();
		$userLanguage = $u->getOption( 'language' );
		$m = wfMsgExt( $emailText, array( 'parsemag', 'language' => $userLanguage ), $ip, $u->getName(), $np,
				$wgServer . $wgScript, round( $wgNewPasswordExpiry / 86400 ) );
		$result = $u->sendMail( wfMsgExt( $emailTitle, array( 'parsemag', 'language' => $userLanguage ) ), $m );

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
		global $wgUser, $wgOut;

		# Run any hooks; display injected HTML if any, else redirect
		$injected_html = '';
		wfRunHooks( 'UserLoginComplete', array( &$wgUser, &$injected_html ) );

		if( $injected_html !== '' ) {
			$this->displaySuccessfulLogin( 'loginsuccess', $injected_html );
		} else {
			$titleObj = Title::newFromText( $this->mReturnTo );
			if ( !$titleObj instanceof Title ) {
				$titleObj = Title::newMainPage();
			}
			$redirectUrl = $titleObj->getFullURL( $this->mReturnToQuery );
			global $wgSecureLogin;
			if( $wgSecureLogin && !$this->mStickHTTPS ) {
				$redirectUrl = preg_replace( '/^https:/', 'http:', $redirectUrl );
			}
			$wgOut->redirect( $redirectUrl );
		}
	}

	/**
	 * Run any hooks registered for logins, then display a message welcoming
	 * the user.
	 *
	 * @private
	 */
	function successfulCreation() {
		global $wgUser;
		# Run any hooks; display injected HTML
		$injected_html = '';
		wfRunHooks( 'UserLoginComplete', array( &$wgUser, &$injected_html ) );

		$this->displaySuccessfulLogin( 'welcomecreation', $injected_html );
	}

	/**
	 * Display a "login successful" page.
	 */
	private function displaySuccessfulLogin( $msgname, $injected_html ) {
		global $wgOut, $wgUser;

		$wgOut->setPageTitle( wfMsg( 'loginsuccesstitle' ) );
		$wgOut->addWikiMsg( $msgname, $wgUser->getName() );
		$wgOut->addHTML( $injected_html );

		if ( !empty( $this->mReturnTo ) ) {
			$wgOut->returnToMain( null, $this->mReturnTo, $this->mReturnToQuery );
		} else {
			$wgOut->returnToMain( null );
		}
	}

	/** */
	function userBlockedMessage() {
		global $wgOut, $wgUser;

		# Let's be nice about this, it's likely that this feature will be used
		# for blocking large numbers of innocent people, e.g. range blocks on
		# schools. Don't blame it on the user. There's a small chance that it
		# really is the user's fault, i.e. the username is blocked and they
		# haven't bothered to log out before trying to create an account to
		# evade it, but we'll leave that to their guilty conscience to figure
		# out.

		$wgOut->setPageTitle( wfMsg( 'cantcreateaccounttitle' ) );

		$ip = wfGetIP();
		$blocker = User::whoIs( $wgUser->mBlock->mBy );
		$block_reason = $wgUser->mBlock->mReason;

		if ( strval( $block_reason ) === '' ) {
			$block_reason = wfMsg( 'blockednoreason' );
		}
		$wgOut->addWikiMsg( 'cantcreateaccount-text', $ip, $block_reason, $blocker );
		$wgOut->returnToMain( false );
	}

	/**
	 * @private
	 */
	function mainLoginForm( $msg, $msgtype = 'error' ) {
		global $wgUser, $wgOut, $wgHiddenPrefs;
		global $wgEnableEmail, $wgEnableUserEmail;
		global $wgRequest, $wgLoginLanguageSelector;
		global $wgAuth, $wgEmailConfirmToEdit, $wgCookieExpiration;
		global $wgSecureLogin;

		$titleObj = SpecialPage::getTitleFor( 'Userlogin' );

		if ( $this->mType == 'signup' ) {
			// Block signup here if in readonly. Keeps user from
			// going through the process (filling out data, etc)
			// and being informed later.
			if ( wfReadOnly() ) {
				$wgOut->readOnlyPage();
				return;
			} elseif ( $wgUser->isBlockedFromCreateAccount() ) {
				$this->userBlockedMessage();
				return;
			} elseif ( count( $permErrors = $titleObj->getUserPermissionsErrors( 'createaccount', $wgUser, true ) )>0 ) {
				$wgOut->showPermissionsErrorPage( $permErrors, 'createaccount' );
				return;
			}
		}

		if ( $this->mUsername == '' ) {
			if ( $wgUser->isLoggedIn() ) {
				$this->mUsername = $wgUser->getName();
			} else {
				$this->mUsername = $wgRequest->getCookie( 'UserName' );
			}
		}

		if ( $this->mType == 'signup' ) {
			global $wgLivePasswordStrengthChecks;
			if ( $wgLivePasswordStrengthChecks ) {
				$wgOut->addPasswordSecurity( 'wpPassword2', 'wpRetype' );
			}
			$template = new UsercreateTemplate();
			$q = 'action=submitlogin&type=signup';
			$linkq = 'type=login';
			$linkmsg = 'gotaccount';
		} else {
			$template = new UserloginTemplate();
			$q = 'action=submitlogin&type=login';
			$linkq = 'type=signup';
			$linkmsg = 'nologin';
		}

		if ( !empty( $this->mReturnTo ) ) {
			$returnto = '&returnto=' . wfUrlencode( $this->mReturnTo );
			if ( !empty( $this->mReturnToQuery ) ) {
				$returnto .= '&returntoquery=' .
					wfUrlencode( $this->mReturnToQuery );
			}
			$q .= $returnto;
			$linkq .= $returnto;
		}

		# Pass any language selection on to the mode switch link
		if( $wgLoginLanguageSelector && $this->mLanguage ) {
			$linkq .= '&uselang=' . $this->mLanguage;
		}

		$link = '<a href="' . htmlspecialchars ( $titleObj->getLocalURL( $linkq ) ) . '">';
		$link .= wfMsgHtml( $linkmsg . 'link' ); # Calling either 'gotaccountlink' or 'nologinlink'
		$link .= '</a>';

		# Don't show a "create account" link if the user can't
		if( $this->showCreateOrLoginLink( $wgUser ) ) {
			$template->set( 'link', wfMsgExt( $linkmsg, array( 'parseinline', 'replaceafter' ), $link ) );
		} else {
			$template->set( 'link', '' );
		}

		$template->set( 'header', '' );
		$template->set( 'name', $this->mUsername );
		$template->set( 'password', $this->mPassword );
		$template->set( 'retype', $this->mRetype );
		$template->set( 'email', $this->mEmail );
		$template->set( 'realname', $this->mRealName );
		$template->set( 'domain', $this->mDomain );
		$template->set( 'reason', $this->mReason );

		$template->set( 'action', $titleObj->getLocalURL( $q ) );
		$template->set( 'message', $msg );
		$template->set( 'messagetype', $msgtype );
		$template->set( 'createemail', $wgEnableEmail && $wgUser->isLoggedIn() );
		$template->set( 'userealname', !in_array( 'realname', $wgHiddenPrefs ) );
		$template->set( 'useemail', $wgEnableEmail );
		$template->set( 'emailrequired', $wgEmailConfirmToEdit );
		$template->set( 'emailothers', $wgEnableUserEmail );
		$template->set( 'canreset', $wgAuth->allowPasswordChange() );
		$template->set( 'canremember', ( $wgCookieExpiration > 0 ) );
		$template->set( 'usereason', $wgUser->isLoggedIn() );
		$template->set( 'remember', $wgUser->getOption( 'rememberpassword' ) || $this->mRemember );
		$template->set( 'cansecurelogin', ( $wgSecureLogin === true ) );
		$template->set( 'stickHTTPS', $this->mStickHTTPS );

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
		if( $wgLoginLanguageSelector ) {
			$template->set( 'languages', $this->makeLanguageSelector() );
			if( $this->mLanguage )
				$template->set( 'uselang', $this->mLanguage );
		}

		// Give authentication and captcha plugins a chance to modify the form
		$wgAuth->modifyUITemplate( $template, $this->mType );
		if ( $this->mType == 'signup' ) {
			wfRunHooks( 'UserCreateForm', array( &$template ) );
		} else {
			wfRunHooks( 'UserLoginForm', array( &$template ) );
		}

		// Changes the title depending on permissions for creating account
		if ( $wgUser->isAllowed( 'createaccount' ) ) {
			$wgOut->setPageTitle( wfMsg( 'userlogin' ) );
		} else {
			$wgOut->setPageTitle( wfMsg( 'userloginnocreate' ) );
		}

		$wgOut->disallowUserJs(); // just in case...
		$wgOut->addTemplate( $template );
	}

	/**
	 * @private
	 *
	 * @param $user User
	 *
	 * @return Boolean
	 */
	function showCreateOrLoginLink( &$user ) {
		if( $this->mType == 'signup' ) {
			return true;
		} elseif( $user->isAllowed( 'createaccount' ) ) {
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
	 */
	function hasSessionCookie() {
		global $wgDisableCookieCheck, $wgRequest;
		return $wgDisableCookieCheck ? true : $wgRequest->checkSessionCookie();
	}

	/**
	 * Get the login token from the current session
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
		// Use User::generateToken() instead of $user->editToken()
		// because the latter reuses $_SESSION['wsEditToken']
		$wgRequest->setSessionData( 'wsLoginToken', User::generateToken() );
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
		$wgRequest->setSessionData( 'wsCreateaccountToken', User::generateToken() );
	}

	/**
	 * Remove any createaccount token attached to the current session
	 */
	public static function clearCreateaccountToken() {
		global $wgRequest;
		$wgRequest->setSessionData( 'wsCreateaccountToken', null );
	}

	/**
	 * @private
	 */
	function cookieRedirectCheck( $type ) {
		global $wgOut;

		$titleObj = SpecialPage::getTitleFor( 'Userlogin' );
		$query = array( 'wpCookieCheck' => $type );
		if ( $this->mReturnTo ) {
			$query['returnto'] = $this->mReturnTo;
		}
		$check = $titleObj->getFullURL( $query );

		return $wgOut->redirect( $check );
	}

	/**
	 * @private
	 */
	function onCookieRedirectCheck( $type ) {
		if ( !$this->hasSessionCookie() ) {
			if ( $type == 'new' ) {
				return $this->mainLoginForm( wfMsgExt( 'nocookiesnew', array( 'parseinline' ) ) );
			} elseif ( $type == 'login' ) {
				return $this->mainLoginForm( wfMsgExt( 'nocookieslogin', array( 'parseinline' ) ) );
			} else {
				# shouldn't happen
				return $this->mainLoginForm( wfMsg( 'error' ) );
			}
		} else {
			return $this->successfulLogin();
		}
	}

	/**
	 * @private
	 */
	function throttleHit( $limit ) {
		$this->mainLoginForm( wfMsgExt( 'acct_creation_throttle_hit', array( 'parseinline' ), $limit ) );
	}

	/**
	 * Produce a bar of links which allow the user to select another language
	 * during login/registration but retain "returnto"
	 *
	 * @return string
	 */
	function makeLanguageSelector() {
		global $wgLang;

		$msg = wfMessage( 'loginlanguagelinks' )->inContentLanguage();
		if( !$msg->isBlank() ) {
			$langs = explode( "\n", $msg->text() );
			$links = array();
			foreach( $langs as $lang ) {
				$lang = trim( $lang, '* ' );
				$parts = explode( '|', $lang );
				if ( count( $parts ) >= 2 ) {
					$links[] = $this->makeLanguageSelectorLink( $parts[0], $parts[1] );
				}
			}
			return count( $links ) > 0 ? wfMsgHtml( 'loginlanguagelabel', $wgLang->pipeList( $links ) ) : '';
		} else {
			return '';
		}
	}

	/**
	 * Create a language selector link for a particular language
	 * Links back to this page preserving type and returnto
	 *
	 * @param $text Link text
	 * @param $lang Language code
	 */
	function makeLanguageSelectorLink( $text, $lang ) {
		global $wgUser;
		$self = SpecialPage::getTitleFor( 'Userlogin' );
		$attr = array( 'uselang' => $lang );
		if( $this->mType == 'signup' ) {
			$attr['type'] = 'signup';
		}
		if( $this->mReturnTo ) {
			$attr['returnto'] = $this->mReturnTo;
		}
		$skin = $wgUser->getSkin();
		return $skin->linkKnown(
			$self,
			htmlspecialchars( $text ),
			array(),
			$attr
		);
	}
}
