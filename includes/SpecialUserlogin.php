<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * constructor
 */
function wfSpecialUserlogin() {
	global $wgCommandLineMode;
	global $wgRequest;
	if( !$wgCommandLineMode && !isset( $_COOKIE[ini_get('session.name')] )  ) {
		User::SetupSession();
	}
	
	$form = new LoginForm( $wgRequest );
	$form->execute();
}

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class LoginForm {
	var $mName, $mPassword, $mRetype, $mReturnto, $mCookieCheck, $mPosted;
	var $mAction, $mCreateaccount, $mCreateaccountMail, $mMailmypassword;
	var $mLoginattempt, $mRemember, $mEmail;
	
	function LoginForm( &$request ) {
		global $wgLang, $wgAllowRealName, $wgEnableEmail;
		global $wgEmailAuthentication;

		$this->mName = $request->getText( 'wpName' );
		$this->mPassword = $request->getText( 'wpPassword' );
		$this->mRetype = $request->getText( 'wpRetype' );
		$this->mReturnto = $request->getVal( 'returnto' );
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
	    
		# When switching accounts, it sucks to get automatically logged out
		if( $this->mReturnto == $wgLang->specialPage( 'Userlogout' ) ) {
			$this->mReturnto = '';
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
			} else if ( ( 'submit' == $this->mAction ) || $this->mLoginattempt ) {
				return $this->processLogin();
			}
		}
		$this->mainLoginForm( '' );
	}

	/**
	 * @access private
	 */
	function addNewAccountMailPassword() {
		global $wgOut;
 		global $wgEmailAuthentication;
		
		if ('' == $this->mEmail) {
			$this->mainLoginForm( wfMsg( 'noemail', htmlspecialchars( $this->mName ) ) );
			return;
		}

		$u = $this->addNewaccountInternal();

		if ($u == NULL) {
			return;
		}

		$newadr = strtolower($this->mEmail);

		# prepare for authentication and mail a temporary password to newadr
		if ( !$u->isValidEmailAddr( $newadr ) ) {
			return $this->mainLoginForm( wfMsg( 'invalidemailaddress', $error ) );
		}
		$u->mEmail = $newadr; # new behaviour: set this new emailaddr from login-page into user database record
		$u->mEmailAuthenticationtimestamp = 0; # but flag as "dirty" = unauthenticated

		if ($wgEmailAuthentication) {
			$error = $this->mailPasswordInternal( $u, true, $dummy ); # mail a temporary password to the dirty address
		}

		$wgOut->setPageTitle( wfMsg( 'accmailtitle' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );
	
		if ($wgEmailAuthentication) {
			if ($error === '') {
				return $this->mainLoginForm( wfMsg( 'passwordsentforemailauthentication', $u->getName() ) );
		} else {
				return $this->mainLoginForm( wfMsg( 'mailerror', $error ) );
		}
			# if user returns, that new email address gets authenticated in checkpassword()
		}
#		if ( $error === '' ) {
#			$wgOut->addWikiText( wfMsg( 'accmailtext', $u->getName(), $u->getEmail() ) );
#			$wgOut->returnToMain( false );
#		} else {
#			$this->mainLoginForm( wfMsg( 'mailerror', $error ) );
#		}
		$u = 0;
	}


	/**
	 * @access private
	 */
	function addNewAccount() {
		global $wgUser, $wgOut;
		global $wgEmailAuthentication;

		$u = $this->addNewAccountInternal();

		if ($u == NULL) {
			return;
		}

		$newadr = strtolower($this->mEmail);
		if ($newadr != '') {		# prepare for authentication and mail a temporary password to newadr
			if ( !$u->isValidEmailAddr( $newadr ) ) {
				return $this->mainLoginForm( wfMsg( 'invalidemailaddress', $error ) );
			}
			$u->mEmail = $newadr; # new behaviour: set this new emailaddr from login-page into user database record
			$u->mEmailAuthenticationtimestamp = 0; # but flag as "dirty" = unauthenticated

			if ($wgEmailAuthentication) {
				# mail a temporary password to the dirty address

				$error = $this->mailPasswordInternal( $u, true, $dummy );
				if ($error === '') {
					return $this->mainLoginForm( wfMsg( 'passwordsentforemailauthentication', $u->getName() ) );
				} else {
					return $this->mainLoginForm( wfMsg( 'mailerror', $error ) );
				}
				# if user returns, that new email address gets authenticated in checkpassword()
			}
		}

		$wgUser = $u;
		$wgUser->setCookies();

		$wgUser->saveSettings();

		if( $this->hasSessionCookie() ) {
			return $this->successfulLogin( wfMsg( 'welcomecreation', $wgUser->getName() ) );
		} else {
			return $this->cookieRedirectCheck( 'new' );
		}
	}

	/**
	 * @access private
	 */
	function addNewAccountInternal() {
		global $wgUser, $wgOut;
		global $wgMaxNameChars;
		global $wgMemc, $wgAccountCreationThrottle, $wgDBname, $wgIP;

		if (!$wgUser->isAllowedToCreateAccount()) {
			$this->userNotPrivilegedMessage();
			return;
		}

		if ( 0 != strcmp( $this->mPassword, $this->mRetype ) ) {
			$this->mainLoginForm( wfMsg( 'badretype' ) );
			return;
		}
		
		$name = trim( $this->mName );
		$u = User::newFromName( $name );
		if ( is_null( $u ) ||
		  ( '' == $name ) ||
		  $wgUser->isIP( $name ) ||
		  (strpos( $name, "/" ) !== false) ||
		  (strlen( $name ) > $wgMaxNameChars) ||
		  ucFirst($name) != $u->getName() ) 
		{
			$this->mainLoginForm( wfMsg( 'noname' ) );
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		
		if ( 0 != $u->idForName() ) {
			$this->mainLoginForm( wfMsg( 'userexists' ) );
			return;
		}

		if ( $wgAccountCreationThrottle ) {
			$key = $wgDBname.':acctcreate:ip:'.$wgIP;
			$value = $wgMemc->incr( $key );
			if ( !$value ) {
				$wgMemc->set( $key, 1, 86400 );
			}
			if ( $value > $wgAccountCreationThrottle ) {
				$this->throttleHit( $wgAccountCreationThrottle );
				return;
			}
		}

		return $this->initUser( $u );
	}
	
	/**
	 * Actually add a user to the database.
	 * Give it a User object that has been initialised with a name.
	 *
	 * @param User $u
	 * @return User
	 * @access private
	 */
	function &initUser( &$u ) {
		$u->addToDatabase();
		$u->setPassword( $this->mPassword );
		$u->setEmail( $this->mEmail );
		$u->setRealName( $this->mRealName );
		
		global $wgAuth;
		$wgAuth->initUser( $u );

		if ( $this->mRemember ) { $r = 1; }
		else { $r = 0; }
		$u->setOption( 'rememberpassword', $r );
		
		return $u;
	}

	/**
	 * @access private
	 */
	function processLogin() {
		global $wgUser, $wgLang;
		global $wgEmailAuthentication;

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
			global $wgAuth;
			/**
			 * If the external authentication plugin allows it,
			 * automatically create a new account for users that
			 * are externally defined but have not yet logged in.
			 */
			if( $wgAuth->autoCreate() &&
			    $wgAuth->userExists( $u->getName() ) &&
			    $wgAuth->authenticate( $u->getName(), $this->mPassword ) ) {
			    $u =& $this->initUser( $u );
			} else {
				$this->mainLoginForm( wfMsg( 'nosuchuser', $u->getName() ) );
				return;
			}
		} else {
			$u->loadFromDatabase();
		}

		# store temporarily the status before the password check is performed
		$mailmsg = '';
		$oldadr = strtolower($u->getEmail());
		$newadr = strtolower($this->mEmail);
		$alreadyauthenticated = (( $u->mEmailAuthenticationtimestamp != 0 ) || ($oldadr == '')) ;

		# checkPassword sets EmailAuthenticationtimestamp, if the newPassword is used

		if (!$u->checkPassword( $this->mPassword )) {
			$this->mainLoginForm( wfMsg( 'wrongpassword' ) );
			return;
		}

		# We've verified now, update the real record
		#
		if ( $this->mRemember ) {
			$r = 1;
		} else {
			$r = 0;
		}
		$u->setOption( 'rememberpassword', $r );

		/* check if user with correct password has entered a new email address */
		if (($newadr <> '') && ($newadr <> $oldadr)) { # the user supplied a new email address on the login page

			# prepare for authentication and mail a temporary password to newadr
			if ( !$u->isValidEmailAddr( $newadr ) ) {
				return $this->mainLoginForm( wfMsg( 'invalidemailaddress', $error ) );
			}
			$u->mEmail = $newadr; # new behaviour: store this new emailaddr from login-page now into user database record ...
			$u->mEmailAuthenticationtimestamp = 0; # ... but flag the address as "dirty" (unauthenticated)
			$alreadyauthenticated = false;

			if ($wgEmailAuthentication) {

				# mail a temporary one-time password to the dirty address and return here to complete the user login
				# if the user returns now or later using this temp. password, then the new email address $newadr
				# - which is already stored in his user record - gets authenticated in checkpassword()

				$error = $this->mailPasswordInternal( $u, false, $newpassword_temp);
				$u->mNewpassword = $newpassword_temp;

				#	The temporary password is mailed. The user is logged-in as he entered his correct password
				#	This appears to be more intuitive than alternative 2.

				if ($error === '') {
					$mailmsg = '<br>' . wfMsg( 'passwordsentforemailauthentication', $u->getName() );
				} else {
					$mailmsg = '<br>' . wfMsg( 'mailerror', $error ) ;
				}
			}
		}

		$wgUser = $u;
		$wgUser->setCookies();

		# save all settings (incl. new email address and/or temporary password, if applicable)
		$wgUser->saveSettings();
		
		if ( !$wgEmailAuthentication || $alreadyauthenticated ) {
			$authenticated = '';
			$mailmsg = '';
		} elseif ($u->mEmailAuthenticationtimestamp != 0) {
				$authenticated = ' ' . wfMsg( 'emailauthenticated', $wgLang->timeanddate( $u->mEmailAuthenticationtimestamp, true ) );
			} else {
				$authenticated = ' ' . wfMsg( 'emailnotauthenticated' );
			}

		if( $this->hasSessionCookie() ) {
			return $this->successfulLogin( wfMsg( 'loginsuccess', $wgUser->getName() ) . $authenticated .  $mailmsg );
		} else {
			return $this->cookieRedirectCheck( 'login' );
		}
	}

	/**
	 * @access private
	 */
	function mailPassword() {
		global $wgUser, $wgDeferredUpdateList, $wgOutputEncoding;
		global $wgCookiePath, $wgCookieDomain, $wgDBname;

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

		$u->loadFromDatabase();

		$error = $this->mailPasswordInternal( $u, true, $dummy );
		if ($error === '') {
			$this->mainLoginForm( wfMsg( 'passwordsent', $u->getName() ) );
		} else {
			$this->mainLoginForm( wfMsg( 'mailerror', $error ) );
		}
		return;
	}


	/**
	 * @access private
	 */
	function mailPasswordInternal( $u, $savesettings = true, &$newpassword_out ) {
		global $wgPasswordSender, $wgDBname, $wgIP;
		global $wgCookiePath, $wgCookieDomain;

		if ( '' == $u->getEmail() ) {
			return wfMsg( 'noemail', $u->getName() );
		}

		$np = $u->randomPassword();
		$u->setNewpassword( $np );

		# we want to store this new password together with other values in the calling function
		$newpassword_out = $u->mNewpassword;

		# WHY IS THIS HERE ? SHOULDN'T IT BE User::setcookie ???
		setcookie( "{$wgDBname}Token", '', time() - 3600, $wgCookiePath, $wgCookieDomain );

		if ($savesettings) {
		$u->saveSettings();
		}

		$ip = $wgIP;
		if ( '' == $ip ) { $ip = '(Unknown)'; }

		$m = wfMsg( 'passwordremindermailbody', $ip, $u->getName(), wfUrlencode($u->getName()), $np );

		require_once('UserMailer.php');
		$error = userMailer( $u->getEmail(), $wgPasswordSender, wfMsg( 'passwordremindermailsubject' ), $m );
		
		return htmlspecialchars( $error );
	}


	/**
	 * @access private
	 */
	function successfulLogin( $msg ) {
		global $wgUser;
		global $wgOut;

		# Run any hooks; ignore results
		
		wfRunHooks('UserLoginComplete', $wgUser);
		
		$wgOut->setPageTitle( wfMsg( 'loginsuccesstitle' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );
		$wgOut->addWikiText( $msg );
		$wgOut->returnToMain();
	}

	function userNotPrivilegedMessage() {
		global $wgOut, $wgUser, $wgLang;
		
		$wgOut->setPageTitle( wfMsg( 'whitelistacctitle' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );

		$wgOut->addWikiText( wfMsg( 'whitelistacctext' ) );
		
		$wgOut->returnToMain( false );
	}

	/**
	 * @access private
	 */
	function mainLoginForm( $err ) {
		global $wgUser, $wgOut, $wgLang;
		global $wgDBname, $wgAllowRealName, $wgEnableEmail;
		global $wgEmailAuthentication;

		if ( '' == $this->mName ) {
			if ( 0 != $wgUser->getID() ) {
				$this->mName = $wgUser->getName();
			} else {
				$this->mName = @$_COOKIE[$wgDBname.'UserName'];
			}
		}

		$q = 'action=submit';
		if ( !empty( $this->mReturnto ) ) {
			$q .= '&returnto=' . wfUrlencode( $this->mReturnto );
		}
		$titleObj = Title::makeTitle( NS_SPECIAL, 'Userlogin' );

		require_once( 'templates/Userlogin.php' );
		$template =& new UserloginTemplate();
		
		$template->set( 'name', $this->mName );
		$template->set( 'password', $this->mPassword );
		$template->set( 'retype', $this->mRetype );
		$template->set( 'email', $this->mEmail );
		$template->set( 'realname', $this->mRealName );

		$template->set( 'action', $titleObj->getLocalUrl( $q ) );
		$template->set( 'error', $err );
		$template->set( 'create', $wgUser->isAllowedToCreateAccount() );
		$template->set( 'createemail', $wgEnableEmail && ($wgUser->getID() != 0) );
		$template->set( 'userealname', $wgAllowRealName );
		$template->set( 'useemail', $wgEnableEmail );
		$template->set( 'useemailauthent', $wgEmailAuthentication );
		$template->set( 'remember', $wgUser->getOption( 'rememberpassword' ) );
		
		$wgOut->setPageTitle( wfMsg( 'userlogin' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );
		$wgOut->addTemplate( $template );
	}

	/**
	 * @access private
	 */
	function hasSessionCookie() {
		global $wgDisableCookieCheck;
		return ( $wgDisableCookieCheck ) ? true : ( '' != $_COOKIE[session_name()] );
	}
	  
	/**
	 * @access private
	 */
	function cookieRedirectCheck( $type ) {
		global $wgOut, $wgLang;

		$titleObj = Title::makeTitle( NS_SPECIAL, 'Userlogin' );
		$check = $titleObj->getFullURL( 'wpCookieCheck='.$type );

		return $wgOut->redirect( $check );
	}

	/**
	 * @access private
	 */
	function onCookieRedirectCheck( $type ) {
		global $wgUser;

		if ( !$this->hasSessionCookie() ) {
			if ( $type == 'new' ) {
				return $this->mainLoginForm( wfMsg( 'nocookiesnew' ) );
			} else if ( $type == 'login' ) {
				return $this->mainLoginForm( wfMsg( 'nocookieslogin' ) );
			} else {
				# shouldn't happen
				return $this->mainLoginForm( wfMsg( 'error' ) );
			}
		} else {
			return $this->successfulLogin( wfMsg( 'loginsuccess', $wgUser->getName() ) );
		}
	}

	/**
	 * @access private
	 */
	function throttleHit( $limit ) {
		global $wgOut;

		$wgOut->addWikiText( wfMsg( 'acct_creation_throttle_hit', $limit ) );
	}
}
?>
