<?php

/**
 * Encapsulates the backend activities of logging a user into the wiki.
 */
class Login {

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
	
	const MAIL_READ_ONLY = 11;
	const MAIL_PASSCHANGE_FORBIDDEN = 12;
	const MAIL_BLOCKED = 13;
	const MAIL_PING_THROTTLED = 14;
	const MAIL_PASS_THROTTLED = 15;
	const MAIL_EMPTY_EMAIL = 16;
	const MAIL_BAD_IP = 17;
	const MAIL_ERROR = 18;

	var $mName, $mPassword,  $mPosted;
	var $mLoginattempt, $mRemember, $mEmail, $mDomain, $mLanguage;

	private $mExtUser = null;
	
	public $mUser;
	public $mMailResult;

	/**
	 * Constructor
	 * @param WebRequest $request A WebRequest object passed by reference.
	 *     uses $wgRequest if not given.
	 */
	public function __construct( &$request=null ) {
		global $wgRequest, $wgAuth, $wgHiddenPrefs, $wgEnableEmail, $wgRedirectOnLogin;
		if( !$request ) $request = &$wgRequest;

		$this->mName = $request->getText( 'wpName' );
		$this->mPassword = $request->getText( 'wpPassword' );
		$this->mDomain = $request->getText( 'wpDomain' );
		$this->mPosted = $request->wasPosted();
		$this->mRemember = $request->getCheck( 'wpRemember' );

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

		# Attempt to generate the User
		$this->mUser = User::newFromName( $this->mName );
	}

	/**
	 * Actually add a user to the database.
	 * Give it a User object that has been initialised with a name.
	 *
	 * @param $u User object.
	 * @param $autocreate boolean -- true if this is an autocreation via auth plugin
	 * @return User object.
	 */
	public function initUser( $autocreate ) {
		global $wgAuth;

		$this->mUser->addToDatabase();

		if ( $wgAuth->allowPasswordChange() ) {
			$this->mUser->setPassword( $this->mPassword );
		}

		$this->mUser->setEmail( $this->mEmail );
		$this->mUser->setRealName( $this->mRealName );
		$this->mUser->setToken();

		$wgAuth->initUser( $this->mUser, $autocreate );

		if( $this->mExtUser ) {
			$this->mExtUser->link( $this->mUser->getId() );
			$email = $this->mExtUser->getPref( 'emailaddress' );
			if( $email && !$this->mEmail ) {
				$this->mUser->setEmail( $email );
			}
		}

		$this->mUser->setOption( 'rememberpassword', $this->mRemember ? 1 : 0 );
		$this->mUser->saveSettings();

		# Update user count
		$ssUpdate = new SiteStatsUpdate( 0, 0, 0, 0, 1 );
		$ssUpdate->doUpdate();

		return $this->mUser;
	}
	
	public function login(){
		global $wgUser;
		$code = $this->authenticateUserData();
		if( !$code == self::SUCCESS ){
			return $code;
		}
		if( (bool)$this->mRemember != (bool)$wgUser->getOption( 'rememberpassword' ) ) {
			$wgUser->setOption( 'rememberpassword', $this->mRemember ? 1 : 0 );
			$wgUser->saveSettings();
		} else {
			$wgUser->invalidateCache();
		}
		$wgUser->setCookies();

		# Reset the throttle
		$key = wfMemcKey( 'password-throttle', wfGetIP(), md5( $this->mName ) );
		global $wgMemc;
		$wgMemc->delete( $key );
		
		$injected_html = '';
		wfRunHooks('UserLoginComplete', array(&$wgUser, &$injected_html));
		
		return self::SUCCESS;
	}

	/**
	 * Internally authenticate the login request.
	 *
	 * This may create a local account as a side effect if the
	 * authentication plugin allows transparent local account
	 * creation.
	 */
	public function authenticateUserData() {
		global $wgUser, $wgAuth;
		if ( '' == $this->mName ) {
			return self::NO_NAME;
		}
		
		global $wgPasswordAttemptThrottle;

		$throttleCount = 0;
		if ( is_array( $wgPasswordAttemptThrottle ) ) {
			$throttleKey = wfMemcKey( 'password-throttle', wfGetIP(), md5( $this->mName ) );
			$count = $wgPasswordAttemptThrottle['count'];
			$period = $wgPasswordAttemptThrottle['seconds'];
			
			global $wgMemc;
			$throttleCount = $wgMemc->get( $throttleKey );
			if ( !$throttleCount ) {
				$wgMemc->add( $throttleKey, 1, $period ); // start counter
			} else if ( $throttleCount < $count ) {
				$wgMemc->incr($throttleKey);
			} else if ( $throttleCount >= $count ) {
				return self::THROTTLED;
			}
		}

		# Load $wgUser now, and check to see if we're logging in as the same
		# name. This is necessary because loading $wgUser (say by calling
		# getName()) calls the UserLoadFromSession hook, which potentially
		# creates the user in the database. Until we load $wgUser, checking
		# for user existence using User::newFromName($name)->getId() below
		# will effectively be using stale data.
		if ( $wgUser->getName() === $this->mName ) {
			wfDebug( __METHOD__.": already logged in as {$this->mName}\n" );
			return self::SUCCESS;
		}

		$this->mExtUser = ExternalUser::newFromName( $this->mName );

		# TODO: Allow some magic here for invalid external names, e.g., let the
		# user choose a different wiki name.
		if( is_null( $this->mUser ) || !User::isUsableName( $this->mUser->getName() ) ) {
			return self::ILLEGAL;
		}

		$isAutoCreated = false;
		if ( 0 == $this->mUser->getID() ) {
			$status = $this->attemptAutoCreate( $this->mUser );
			if ( $status !== self::SUCCESS ) {
				return $status;
			} else {
				$isAutoCreated = true;
			}
		} else {
			$this->mUser->load();
		}

		# Give general extensions, such as a captcha, a chance to abort logins
		$abort = self::ABORTED;
		if( !wfRunHooks( 'AbortLogin', array( $this->mUser, $this->mPassword, &$abort ) ) ) {
			return $abort;
		}

		if( !$this->mUser->checkPassword( $this->mPassword ) ) {
			if( $this->mUser->checkTemporaryPassword( $this->mPassword ) ) {
				# The e-mailed temporary password should not be used for actual
				# logins; that's a very sloppy habit, and insecure if an
				# attacker has a few seconds to click "search" on someone's 
				# open mail reader.
				#
				# Allow it to be used only to reset the password a single time
				# to a new value, which won't be in the user's e-mail archives
				#
				# For backwards compatibility, we'll still recognize it at the
				# login form to minimize surprises for people who have been
				# logging in with a temporary password for some time.
				#
				# As a side-effect, we can authenticate the user's e-mail ad-
				# dress if it's not already done, since the temporary password
				# was sent via e-mail.
				if( !$this->mUser->isEmailConfirmed() ) {
					$this->mUser->confirmEmail();
					$this->mUser->saveSettings();
				}

				# At this point we just return an appropriate code/ indicating
				# that the UI should show a password reset form; bot interfaces
				# etc will probably just fail cleanly here.
				$retval = self::RESET_PASS;
			} else {
				$retval = '' == $this->mPassword ? self::EMPTY_PASS : self::WRONG_PASS;
			}
		} else {
			$wgAuth->updateUser( $this->mUser );
			$wgUser = $this->mUser;

			# Reset throttle after a successful login
			if( $throttleCount ) {
				$wgMemc->delete( $throttleKey );
			}

			if( $isAutoCreated ) {
				# Must be run after $wgUser is set, for correct new user log
				wfRunHooks( 'AuthPluginAutoCreate', array( $wgUser ) );
			}

			$retval = self::SUCCESS;
		}
		wfRunHooks( 'LoginAuthenticateAudit', array( $this->mUser, $this->mPassword, $retval ) );
		return $retval;
	}

	/**
	 * Attempt to automatically create a user on login. Only succeeds if there
	 * is an external authentication method which allows it.
	 * @return integer Status code
	 */
	public function attemptAutoCreate( $user ) {
		global $wgAuth, $wgUser, $wgAutocreatePolicy;

		if( $wgUser->isBlockedFromCreateAccount() ) {
			wfDebug( __METHOD__.": user is blocked from account creation\n" );
			return self::CREATE_BLOCKED;
		}

		# If the external authentication plugin allows it, automatically cre-
		# ate a new account for users that are externally defined but have not
		# yet logged in.
		if( $this->mExtUser ) {
			# mExtUser is neither null nor false, so use the new ExternalAuth
			# system.
			if( $wgAutocreatePolicy == 'never' ) {
				return self::NOT_EXISTS;
			}
			if( !$this->mExtUser->authenticate( $this->mPassword ) ) {
				return self::WRONG_PLUGIN_PASS;
			}
		} else {
			# Old AuthPlugin.
			if( !$wgAuth->autoCreate() ) {
				return self::NOT_EXISTS;
			}
			if( !$wgAuth->userExists( $user->getName() ) ) {
				wfDebug( __METHOD__.": user does not exist\n" );
				return self::NOT_EXISTS;
			}
			if( !$wgAuth->authenticate( $user->getName(), $this->mPassword ) ) {
				wfDebug( __METHOD__.": \$wgAuth->authenticate() returned false, aborting\n" );
				return self::WRONG_PLUGIN_PASS;
			}
		}

		wfDebug( __METHOD__.": creating account\n" );
		$this->initUser( true );
		return self::SUCCESS;
	}

	/**
	 * Email the user a new password, if appropriate to do so.
	 * @param $text String message key
	 * @param $title String message key
	 * @return Status code
	 */
	public function mailPassword( $text='passwordremindertext', $title='passwordremindertitle' ) {
		global $wgUser, $wgOut, $wgAuth, $wgServer, $wgScript, $wgNewPasswordExpiry;

		if( wfReadOnly() ) 
			return self::MAIL_READ_ONLY;

		if( !$wgAuth->allowPasswordChange() ) 
			return self::MAIL_PASSCHANGE_FORBIDDEN;

		# Check against blocked IPs
		# FIXME: -- should we not?
		if( $wgUser->isBlocked() )
			return self::MAIL_BLOCKED;

		# Check for hooks
		$error = null;
		if ( ! wfRunHooks( 'UserLoginMailPassword', array( $this->mName, &$error ) ) )
			return $error;

		# Check against the rate limiter
		if( $wgUser->pingLimiter( 'mailpassword' ) )
			return self::MAIL_PING_THROTTLED;

		# Check for a valid name
		if ( '' == $this->mName )
			return self::NO_NAME;
		$this->mUser = User::newFromName( $this->mName );
		if( is_null( $this->mUser ) )
			return self::NO_NAME;

		# And that the resulting user actually exists
		if ( 0 == $this->mUser->getId() )
			return self::NOT_EXISTS;

		# Check against password throttle
		if ( $this->mUser->isPasswordReminderThrottled() )
			return self::MAIL_PASS_THROTTLED;
		
		# User doesn't have email address set
		if ( '' == $this->mUser->getEmail() )
			return self::MAIL_EMPTY_EMAIL;

		# Don't send to people who are acting fishily by hiding their IP
		$ip = wfGetIP();
		if( !$ip )
			return self::MAIL_BAD_IP;

		# Let hooks do things with the data
		wfRunHooks( 'User::mailPasswordInternal', array(&$wgUser, &$ip, &$this->mUser) );

		$newpass = $this->mUser->randomPassword();
		$this->mUser->setNewpassword( $newpass, true );
		$this->mUser->saveSettings();

		$message = wfMsgExt( $text, array( 'parsemag' ), $ip, $this->mUser->getName(), $newpass,
				$wgServer . $wgScript, round( $wgNewPasswordExpiry / 86400 ) );
		$this->mMailResult = $this->mUser->sendMail( wfMsg( $title ), $message );
		
		if( WikiError::isError( $this->mMailResult ) ) {
			var_dump( $message );
			return self::SUCCESS;
			#return self::MAIL_ERROR;
		} else {
			return self::SUCCESS;
		}
	}
}

/**
 * For backwards compatibility, mainly with the state constants, which
 * could be referred to in old extensions with the old class name.
 * @deprecated
 */
class LoginForm extends Login {}