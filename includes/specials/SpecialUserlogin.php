<?php
/**
 * @file
 * @ingroup SpecialPage
 */

/**
 * constructor
 */
function wfSpecialUserlogin( $par = '' ) {
	global $wgRequest;
	if( session_id() == '' ) {
		wfSetupSession();
	}

	$form = new LoginForm( $wgRequest, $par );
	$form->execute();
}

/**
 * implements Special:Login
 * @ingroup SpecialPage
 */
class LoginForm {

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

	var $mName, $mPassword, $mRetype, $mReturnTo, $mCookieCheck, $mPosted;
	var $mAction, $mCreateaccount, $mCreateaccountMail, $mMailmypassword;
	var $mLoginattempt, $mRemember, $mEmail, $mDomain, $mLanguage, $mSkipCookieCheck;

	/**
	 * Constructor
	 * @param WebRequest $request A WebRequest object passed by reference
	 */
	function LoginForm( &$request, $par = '' ) {
		global $wgLang, $wgAllowRealName, $wgEnableEmail;
		global $wgAuth;

		$this->mType = ( $par == 'signup' ) ? $par : $request->getText( 'type' ); # Check for [[Special:Userlogin/signup]]
		$this->mName = $request->getText( 'wpName' );
		$this->mPassword = $request->getText( 'wpPassword' );
		$this->mRetype = $request->getText( 'wpRetype' );
		$this->mDomain = $request->getText( 'wpDomain' );
		$this->mReturnTo = $request->getVal( 'returnto' );
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
		$this->mLanguage = $request->getText( 'uselang' );
		$this->mSkipCookieCheck = $request->getCheck( 'wpSkipCookieCheck' );

		if( $wgEnableEmail ) {
			$this->mEmail = $request->getText( 'wpEmail' );
		} else {
			$this->mEmail = '';
		}
		if( $wgAllowRealName ) {
		    $this->mRealName = $request->getText( 'wpRealName' );
		} else {
		    $this->mRealName = '';
		}

		if( !$wgAuth->validDomain( $this->mDomain ) ) {
			$this->mDomain = 'invaliddomain';
		}
		$wgAuth->setDomain( $this->mDomain );

		# When switching accounts, it sucks to get automatically logged out
		if( $this->mReturnTo == $wgLang->specialPage( 'Userlogout' ) ) {
			$this->mReturnTo = '';
		}
	}

	function execute() {
		if ( !is_null( $this->mCookieCheck ) ) {
			$this->onCookieRedirectCheck( $this->mCookieCheck );
			return;
		} else if( $this->mPosted ) {
			if( $this->mCreateaccount ) {
				return $this->addNewAccount();
			} else if ( $this->mCreateaccountMail ) {
				return $this->addNewAccountMailPassword();
			} else if ( $this->mMailmypassword ) {
				return $this->mailPassword();
			} else if ( ( 'submitlogin' == $this->mAction ) || $this->mLoginattempt ) {
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

		if ('' == $this->mEmail) {
			$this->mainLoginForm( wfMsg( 'noemail', htmlspecialchars( $this->mName ) ) );
			return;
		}

		$u = $this->addNewaccountInternal();

		if ($u == NULL) {
			return;
		}

		// Wipe the initial password and mail a temporary one
		$u->setPassword( null );
		$u->saveSettings();
		$result = $this->mailPasswordInternal( $u, false, 'createaccount-title', 'createaccount-text' );

		wfRunHooks( 'AddNewAccount', array( $u, true ) );

		$wgOut->setPageTitle( wfMsg( 'accmailtitle' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );

		if( WikiError::isError( $result ) ) {
			$this->mainLoginForm( wfMsg( 'mailerror', $result->getMessage() ) );
		} else {
			$wgOut->addWikiMsg( 'accmailtext', $u->getName(), $u->getEmail() );
			$wgOut->returnToMain( false );
		}
		$u = 0;
	}


	/**
	 * @private
	 */
	function addNewAccount() {
		global $wgUser, $wgEmailAuthentication;

		# Create the account and abort if there's a problem doing so
		$u = $this->addNewAccountInternal();
		if( $u == NULL )
			return;

		# If we showed up language selection links, and one was in use, be
		# smart (and sensible) and save that language as the user's preference
		global $wgLoginLanguageSelector;
		if( $wgLoginLanguageSelector && $this->mLanguage )
			$u->setOption( 'language', $this->mLanguage );

		# Send out an email authentication message if needed
		if( $wgEmailAuthentication && User::isValidEmailAddr( $u->getEmail() ) ) {
			global $wgOut;
			$error = $u->sendConfirmationMail();
			if( WikiError::isError( $error ) ) {
				$wgOut->addWikiMsg( 'confirmemail_sendfailed', $error->getMessage() );
			} else {
				$wgOut->addWikiMsg( 'confirmemail_oncreate' );
			}
		}

		# Save settings (including confirmation token)
		$u->saveSettings();

		# If not logged in, assume the new account as the current one and set session cookies
		# then show a "welcome" message or a "need cookies" message as needed
		if( $wgUser->isAnon() ) {
			$wgUser = $u;
			$wgUser->setCookies();
			wfRunHooks( 'AddNewAccount', array( $wgUser ) );
			if( $this->hasSessionCookie() ) {
				return $this->successfulCreation();
			} else {
				return $this->cookieRedirectCheck( 'new' );
			}
		} else {
			# Confirm that the account was created
			global $wgOut;
			$self = SpecialPage::getTitleFor( 'Userlogin' );
			$wgOut->setPageTitle( wfMsgHtml( 'accountcreated' ) );
			$wgOut->setArticleRelated( false );
			$wgOut->setRobotPolicy( 'noindex,nofollow' );
			$wgOut->addHtml( wfMsgWikiHtml( 'accountcreatedtext', $u->getName() ) );
			$wgOut->returnToMain( false, $self );
			wfRunHooks( 'AddNewAccount', array( $u ) );
			return true;
		}
	}

	/**
	 * @private
	 */
	function addNewAccountInternal() {
		global $wgUser, $wgOut;
		global $wgEnableSorbs, $wgProxyWhitelist;
		global $wgMemc, $wgAccountCreationThrottle;
		global $wgAuth, $wgMinimalPasswordLength;
		global $wgEmailConfirmToEdit;

		// If the user passes an invalid domain, something is fishy
		if( !$wgAuth->validDomain( $this->mDomain ) ) {
			$this->mainLoginForm( wfMsg( 'wrongpassword' ) );
			return false;
		}

		// If we are not allowing users to login locally, we should
		// be checking to see if the user is actually able to
		// authenticate to the authentication server before they
		// create an account (otherwise, they can create a local account
		// and login as any domain user). We only need to check this for
		// domains that aren't local.
		if( 'local' != $this->mDomain && '' != $this->mDomain ) {
			if( !$wgAuth->canCreateAccounts() && ( !$wgAuth->userExists( $this->mName ) || !$wgAuth->authenticate( $this->mName, $this->mPassword ) ) ) {
				$this->mainLoginForm( wfMsg( 'wrongpassword' ) );
				return false;
			}
		}

		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return false;
		}

		# Check permissions
		if ( !$wgUser->isAllowed( 'createaccount' ) ) {
			$this->userNotPrivilegedMessage();
			return false;
		} elseif ( $wgUser->isBlockedFromCreateAccount() ) {
			$this->userBlockedMessage();
			return false;
		}

		$ip = wfGetIP();
		if ( $wgEnableSorbs && !in_array( $ip, $wgProxyWhitelist ) &&
		  $wgUser->inSorbsBlacklist( $ip ) )
		{
			$this->mainLoginForm( wfMsg( 'sorbs_create_account_reason' ) . ' (' . htmlspecialchars( $ip ) . ')' );
			return;
		}

		# Now create a dummy user ($u) and check if it is valid
		$name = trim( $this->mName );
		$u = User::newFromName( $name, 'creatable' );
		if ( is_null( $u ) ) {
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
		if ( !$u->isValidPassword( $this->mPassword ) ) {
			if ( !$this->mCreateaccountMail ) {
				$this->mainLoginForm( wfMsgExt( 'passwordtooshort', array( 'parsemag' ), $wgMinimalPasswordLength ) );
				return false;
			} else {
				# do not force a password for account creation by email
				# set invalid password, it will be replaced later by a random generated password
				$this->mPassword = null;
			}
		}

		# if you need a confirmed email address to edit, then obviously you need an email address.
		if ( $wgEmailConfirmToEdit && empty( $this->mEmail ) ) {
			$this->mainLoginForm( wfMsg( 'noemailtitle' ) );
			return false;
		}

		if( !empty( $this->mEmail ) && !User::isValidEmailAddr( $this->mEmail ) ) {
			$this->mainLoginForm( wfMsg( 'invalidemailaddress' ) );
			return false;
		}

		# Set some additional data so the AbortNewAccount hook can be
		# used for more than just username validation
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
			$value = $wgMemc->incr( $key );
			if ( !$value ) {
				$wgMemc->set( $key, 1, 86400 );
			}
			if ( $value > $wgAccountCreationThrottle ) {
				$this->throttleHit( $wgAccountCreationThrottle );
				return false;
			}
		}

		if( !$wgAuth->addUser( $u, $this->mPassword, $this->mEmail, $this->mRealName ) ) {
			$this->mainLoginForm( wfMsg( 'externaldberror' ) );
			return false;
		}

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
	 *
	 * @public
	 */
	function authenticateUserData() {
		global $wgUser, $wgAuth;
		if ( '' == $this->mName ) {
			return self::NO_NAME;
		}
		
		global $wgPasswordAttemptThrottle;
		if ( is_array($wgPasswordAttemptThrottle) ) {
			$key = wfMemcKey( 'password-throttle', wfGetIP(), md5( $this->mName ) );
			$count = $wgPasswordAttemptThrottle['count'];
			$period = $wgPasswordAttemptThrottle['seconds'];
			
			global $wgMemc;
			$cur = $wgMemc->get($key);
			if ( !$cur ) {
				$wgMemc->add( $key, 1, $period ); // start counter
			} else if ( $cur < $count ) {
				$wgMemc->incr($key);
			} else if ( $cur >= $count ) {
				return self::THROTTLED;
			}
		}

		// Load $wgUser now, and check to see if we're logging in as the same name. 
		// This is necessary because loading $wgUser (say by calling getName()) calls
		// the UserLoadFromSession hook, which potentially creates the user in the 
		// database. Until we load $wgUser, checking for user existence using 
		// User::newFromName($name)->getId() below will effectively be using stale data.
		if ( $wgUser->getName() === $this->mName ) {
			wfDebug( __METHOD__.": already logged in as {$this->mName}\n" );
			return self::SUCCESS;
		}
		$u = User::newFromName( $this->mName );
		if( is_null( $u ) || !User::isUsableName( $u->getName() ) ) {
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
			$u->load();
		}

		// Give general extensions, such as a captcha, a chance to abort logins
		$abort = self::ABORTED;
		if( !wfRunHooks( 'AbortLogin', array( $u, $this->mPassword, &$abort ) ) ) {
			return $abort;
		}

		if (!$u->checkPassword( $this->mPassword )) {
			if( $u->checkTemporaryPassword( $this->mPassword ) ) {
				// The e-mailed temporary password should not be used
				// for actual logins; that's a very sloppy habit,
				// and insecure if an attacker has a few seconds to
				// click "search" on someone's open mail reader.
				//
				// Allow it to be used only to reset the password
				// a single time to a new value, which won't be in
				// the user's e-mail archives.
				//
				// For backwards compatibility, we'll still recognize
				// it at the login form to minimize surprises for
				// people who have been logging in with a temporary
				// password for some time.
				//
				// As a side-effect, we can authenticate the user's
				// e-mail address if it's not already done, since
				// the temporary password was sent via e-mail.
				//
				if( !$u->isEmailConfirmed() ) {
					$u->confirmEmail();
					$u->saveSettings();
				}

				// At this point we just return an appropriate code
				// indicating that the UI should show a password
				// reset form; bot interfaces etc will probably just
				// fail cleanly here.
				//
				$retval = self::RESET_PASS;
			} else {
				$retval = '' == $this->mPassword ? self::EMPTY_PASS : self::WRONG_PASS;
			}
		} else {
			$wgAuth->updateUser( $u );
			$wgUser = $u;

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
	 * Attempt to automatically create a user on login.
	 * Only succeeds if there is an external authentication method which allows it.
	 * @return integer Status code
	 */
	function attemptAutoCreate( $user ) {
		global $wgAuth, $wgUser;
		/**
		 * If the external authentication plugin allows it,
		 * automatically create a new account for users that
		 * are externally defined but have not yet logged in.
		 */
		if ( !$wgAuth->autoCreate() ) {
			return self::NOT_EXISTS;
		}
		if ( !$wgAuth->userExists( $user->getName() ) ) {
			wfDebug( __METHOD__.": user does not exist\n" );
			return self::NOT_EXISTS;
		}
		if ( !$wgAuth->authenticate( $user->getName(), $this->mPassword ) ) {
			wfDebug( __METHOD__.": \$wgAuth->authenticate() returned false, aborting\n" );
			return self::WRONG_PLUGIN_PASS;
		}
		if ( $wgUser->isBlockedFromCreateAccount() ) {
			wfDebug( __METHOD__.": user is blocked from account creation\n" );
			return self::CREATE_BLOCKED;
		}

		wfDebug( __METHOD__.": creating account\n" );
		$user = $this->initUser( $user, true );
		return self::SUCCESS;
	}

	function processLogin() {
		global $wgUser, $wgAuth;

		switch ($this->authenticateUserData())
		{
			case self::SUCCESS:
				# We've verified now, update the real record
				if( (bool)$this->mRemember != (bool)$wgUser->getOption( 'rememberpassword' ) ) {
					$wgUser->setOption( 'rememberpassword', $this->mRemember ? 1 : 0 );
					$wgUser->saveSettings();
				} else {
					$wgUser->invalidateCache();
				}
				$wgUser->setCookies();

				// Reset the throttle
				$key = wfMemcKey( 'password-throttle', wfGetIP(), md5( $this->mName ) );
				global $wgMemc;
				$wgMemc->delete( $key );

				if( $this->hasSessionCookie() || $this->mSkipCookieCheck ) {
					/* Replace the language object to provide user interface in correct
					 * language immediately on this first page load.
					 */
					global $wgLang, $wgRequest;
					$code = $wgRequest->getVal( 'uselang', $wgUser->getOption( 'language' ) );
					$wgLang = Language::factory( $code );
					return $this->successfulLogin();
				} else {
					return $this->cookieRedirectCheck( 'login' );
				}
				break;

			case self::NO_NAME:
			case self::ILLEGAL:
				$this->mainLoginForm( wfMsg( 'noname' ) );
				break;
			case self::WRONG_PLUGIN_PASS:
				$this->mainLoginForm( wfMsg( 'wrongpassword' ) );
				break;
			case self::NOT_EXISTS:
				if( $wgUser->isAllowed( 'createaccount' ) ){
					$this->mainLoginForm( wfMsg( 'nosuchuser', htmlspecialchars( $this->mName ) ) );
				} else {
					$this->mainLoginForm( wfMsg( 'nosuchusershort', htmlspecialchars( $this->mName ) ) );
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
			default:
				throw new MWException( "Unhandled case value" );
		}
	}

	function resetLoginForm( $error ) {
		global $wgOut;
		$wgOut->addWikiText( "<div class=\"errorbox\">$error</div>" );
		$reset = new PasswordResetForm( $this->mName, $this->mPassword );
		$reset->execute( null );
	}

	/**
	 * @private
	 */
	function mailPassword() {
		global $wgUser, $wgOut, $wgAuth;

		if( !$wgAuth->allowPasswordChange() ) {
			$this->mainLoginForm( wfMsg( 'resetpass_forbidden' ) );
			return;
		}

		# Check against blocked IPs
		# fixme -- should we not?
		if( $wgUser->isBlocked() ) {
			$this->mainLoginForm( wfMsg( 'blocked-mailpassword' ) );
			return;
		}

		# Check against the rate limiter
		if( $wgUser->pingLimiter( 'mailpassword' ) ) {
			$wgOut->rateLimited();
			return;
		}

		if ( '' == $this->mName ) {
			$this->mainLoginForm( wfMsg( 'noname' ) );
			return;
		}
		$u = User::newFromName( $this->mName );
		if( is_null( $u ) ) {
			$this->mainLoginForm( wfMsg( 'noname' ) );
			return;
		}
		if ( 0 == $u->getID() ) {
			$this->mainLoginForm( wfMsg( 'nosuchuser', $u->getName() ) );
			return;
		}

		# Check against password throttle
		if ( $u->isPasswordReminderThrottled() ) {
			global $wgPasswordReminderResendTime;
			# Round the time in hours to 3 d.p., in case someone is specifying minutes or seconds.
			$this->mainLoginForm( wfMsgExt( 'throttled-mailpassword', array( 'parsemag' ),
				round( $wgPasswordReminderResendTime, 3 ) ) );
			return;
		}

		$result = $this->mailPasswordInternal( $u, true, 'passwordremindertitle', 'passwordremindertext' );
		if( WikiError::isError( $result ) ) {
			$this->mainLoginForm( wfMsg( 'mailerror', $result->getMessage() ) );
		} else {
			$this->mainLoginForm( wfMsg( 'passwordsent', $u->getName() ), 'success' );
		}
	}


	/**
	 * @param object user
	 * @param bool throttle
	 * @param string message name of email title
	 * @param string message name of email text
	 * @return mixed true on success, WikiError on failure
	 * @private
	 */
	function mailPasswordInternal( $u, $throttle = true, $emailTitle = 'passwordremindertitle', $emailText = 'passwordremindertext' ) {
		global $wgCookiePath, $wgCookieDomain, $wgCookiePrefix, $wgCookieSecure;
		global $wgServer, $wgScript, $wgUser;

		if ( '' == $u->getEmail() ) {
			return new WikiError( wfMsg( 'noemail', $u->getName() ) );
		}
		$ip = wfGetIP();
		if( !$ip ) {
			return new WikiError( wfMsg( 'badipaddress' ) );
		}
		
		wfRunHooks( 'User::mailPasswordInternal', array(&$wgUser, &$ip, &$u) );

		$np = $u->randomPassword();
		$u->setNewpassword( $np, $throttle );
		$u->saveSettings();

		$m = wfMsg( $emailText, $ip, $u->getName(), $np, $wgServer . $wgScript );
		$result = $u->sendMail( wfMsg( $emailTitle ), $m );

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

		# Run any hooks; ignore injected HTML since we just redirect
		$injected_html = '';
		wfRunHooks('UserLoginComplete', array(&$wgUser, &$injected_html));

		$titleObj = Title::newFromText( $this->mReturnTo );
		if ( !$titleObj instanceof Title ) {
			$titleObj = Title::newMainPage();
		}

		$wgOut->redirect( $titleObj->getFullURL() );
	}

	/**
	 * Run any hooks registered for logins, then display a message welcoming
	 * the user.
	 *
	 * @private
	 */
	function successfulCreation() {
		global $wgUser, $wgOut;

		# Run any hooks; display injected HTML
		$injected_html = '';
		wfRunHooks('UserLoginComplete', array(&$wgUser, &$injected_html));

		$wgOut->setPageTitle( wfMsg( 'loginsuccesstitle' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );
		$wgOut->addWikiMsg( 'welcomecreation', $wgUser->getName() );
		$wgOut->addHtml( $injected_html );

		if ( !empty( $this->mReturnTo ) ) {
			$wgOut->returnToMain( null, $this->mReturnTo );
		} else {
			$wgOut->returnToMain( null );
		}
	}

	/** */
	function userNotPrivilegedMessage($errors) {
		global $wgOut;

		$wgOut->setPageTitle( wfMsg( 'permissionserrors' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );

		$wgOut->addWikitext( $wgOut->formatPermissionsErrorMessage( $errors, 'createaccount' ) );
		// Stuff that might want to be added at the end. For example, instructions if blocked.
		$wgOut->addWikiMsg( 'cantcreateaccount-nonblock-text' );

		$wgOut->returnToMain( false );
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
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );

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
		global $wgUser, $wgOut, $wgAllowRealName, $wgEnableEmail;
		global $wgCookiePrefix, $wgAuth, $wgLoginLanguageSelector;
		global $wgAuth, $wgEmailConfirmToEdit, $wgCookieExpiration;
		
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

		if ( '' == $this->mName ) {
			if ( $wgUser->isLoggedIn() ) {
				$this->mName = $wgUser->getName();
			} else {
				$this->mName = isset( $_COOKIE[$wgCookiePrefix.'UserName'] ) ? $_COOKIE[$wgCookiePrefix.'UserName'] : null;
			}
		}

		$titleObj = SpecialPage::getTitleFor( 'Userlogin' );

		if ( $this->mType == 'signup' ) {
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
			$q .= $returnto;
			$linkq .= $returnto;
		}

		# Pass any language selection on to the mode switch link
		if( $wgLoginLanguageSelector && $this->mLanguage )
			$linkq .= '&uselang=' . $this->mLanguage;

		$link = '<a href="' . htmlspecialchars ( $titleObj->getLocalUrl( $linkq ) ) . '">';
		$link .= wfMsgHtml( $linkmsg . 'link' ); # Calling either 'gotaccountlink' or 'nologinlink'
		$link .= '</a>';

		# Don't show a "create account" link if the user can't
		if( $this->showCreateOrLoginLink( $wgUser ) )
			$template->set( 'link', wfMsgHtml( $linkmsg, $link ) );
		else
			$template->set( 'link', '' );

		$template->set( 'header', '' );
		$template->set( 'name', $this->mName );
		$template->set( 'password', $this->mPassword );
		$template->set( 'retype', $this->mRetype );
		$template->set( 'email', $this->mEmail );
		$template->set( 'realname', $this->mRealName );
		$template->set( 'domain', $this->mDomain );

		$template->set( 'action', $titleObj->getLocalUrl( $q ) );
		$template->set( 'message', $msg );
		$template->set( 'messagetype', $msgtype );
		$template->set( 'createemail', $wgEnableEmail && $wgUser->isLoggedIn() );
		$template->set( 'userealname', $wgAllowRealName );
		$template->set( 'useemail', $wgEnableEmail );
		$template->set( 'emailrequired', $wgEmailConfirmToEdit );
		$template->set( 'canreset', $wgAuth->allowPasswordChange() );
		$template->set( 'canremember', ( $wgCookieExpiration > 0 ) );
		$template->set( 'remember', $wgUser->getOption( 'rememberpassword' ) or $this->mRemember  );

		# Prepare language selection links as needed
		if( $wgLoginLanguageSelector ) {
			$template->set( 'languages', $this->makeLanguageSelector() );
			if( $this->mLanguage )
				$template->set( 'uselang', $this->mLanguage );
		}

		// Give authentication and captcha plugins a chance to modify the form
		$wgAuth->modifyUITemplate( $template );
		if ( $this->mType == 'signup' ) {
			wfRunHooks( 'UserCreateForm', array( &$template ) );
		} else {
			wfRunHooks( 'UserLoginForm', array( &$template ) );
		}

		$wgOut->setPageTitle( wfMsg( 'userlogin' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );
		$wgOut->disallowUserJs();  // just in case...
		$wgOut->addTemplate( $template );
	}

	/**
	 * @private
	 */
	function showCreateOrLoginLink( &$user ) {
		if( $this->mType == 'signup' ) {
			return( true );
		} elseif( $user->isAllowed( 'createaccount' ) ) {
			return( true );
		} else {
			return( false );
		}
	}

	/**
	 * Check if a session cookie is present.
	 *
	 * This will not pick up a cookie set during _this_ request, but is
	 * meant to ensure that the client is returning the cookie which was
	 * set on a previous pass through the system.
	 *
	 * @private
	 */
	function hasSessionCookie() {
		global $wgDisableCookieCheck, $wgRequest;
		return $wgDisableCookieCheck ? true : $wgRequest->checkSessionCookie();
	}

	/**
	 * @private
	 */
	function cookieRedirectCheck( $type ) {
		global $wgOut;

		$titleObj = SpecialPage::getTitleFor( 'Userlogin' );
		$check = $titleObj->getFullURL( 'wpCookieCheck='.$type );

		return $wgOut->redirect( $check );
	}

	/**
	 * @private
	 */
	function onCookieRedirectCheck( $type ) {
		global $wgUser;

		if ( !$this->hasSessionCookie() ) {
			if ( $type == 'new' ) {
				return $this->mainLoginForm( wfMsgExt( 'nocookiesnew', array( 'parseinline' ) ) );
			} else if ( $type == 'login' ) {
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
		global $wgOut;

		$wgOut->addWikiMsg( 'acct_creation_throttle_hit', $limit );
	}

	/**
	 * Produce a bar of links which allow the user to select another language
	 * during login/registration but retain "returnto"
	 *
	 * @return string
	 */
	function makeLanguageSelector() {
		$msg = wfMsgForContent( 'loginlanguagelinks' );
		if( $msg != '' && !wfEmptyMsg( 'loginlanguagelinks', $msg ) ) {
			$langs = explode( "\n", $msg );
			$links = array();
			foreach( $langs as $lang ) {
				$lang = trim( $lang, '* ' );
				$parts = explode( '|', $lang );
				if (count($parts) >= 2) {
					$links[] = $this->makeLanguageSelectorLink( $parts[0], $parts[1] );
				}
			}
			return count( $links ) > 0 ? wfMsgHtml( 'loginlanguagelabel', implode( ' | ', $links ) ) : '';
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
		$attr[] = 'uselang=' . $lang;
		if( $this->mType == 'signup' )
			$attr[] = 'type=signup';
		if( $this->mReturnTo )
			$attr[] = 'returnto=' . $this->mReturnTo;
		$skin = $wgUser->getSkin();
		return $skin->makeKnownLinkObj( $self, htmlspecialchars( $text ), implode( '&', $attr ) );
	}
}
