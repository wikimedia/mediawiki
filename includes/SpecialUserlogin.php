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
	if( !$wgCommandLineMode && !isset( $_COOKIE[session_name()] )  ) {
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
	var $mLoginattempt, $mRemember, $mEmail, $mDomain;
	
	/**
	 * Constructor
	 * @param webrequest $request A webrequest object passed by reference
	 */
	function LoginForm( &$request ) {
		global $wgLang, $wgAllowRealName, $wgEnableEmail;
		global $wgAuth;

		$this->mName = $request->getText( 'wpName' );
		$this->mPassword = $request->getText( 'wpPassword' );
		$this->mRetype = $request->getText( 'wpRetype' );
		$this->mDomain = $request->getText( 'wpDomain' );
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
		
		if( !$wgAuth->validDomain( $this->mDomain ) ) {
			$this->mDomain = 'invaliddomain';
		}
		$wgAuth->setDomain( $this->mDomain );
 
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
			} else if ( ( 'submitlogin' == $this->mAction ) || $this->mLoginattempt ) {
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
		
		if ('' == $this->mEmail) {
			$this->mainLoginForm( wfMsg( 'noemail', htmlspecialchars( $this->mName ) ) );
			return;
		}

		$u = $this->addNewaccountInternal();

		if ($u == NULL) {
			return;
		}

		$u->saveSettings();
		$result = $this->mailPasswordInternal($u);
		
		$wgOut->setPageTitle( wfMsg( 'accmailtitle' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );
	
		if( WikiError::isError( $result ) ) {
			$this->mainLoginForm( wfMsg( 'mailerror', $result->getMessage() ) );
		} else {
			$wgOut->addWikiText( wfMsg( 'accmailtext', $u->getName(), $u->getEmail() ) );
			$wgOut->returnToMain( false );
		}
		$u = 0;
	}


	/**
	 * @access private
	 */
	function addNewAccount() {
		global $wgUser, $wgOut, $wgEmailAuthentication;

		$u = $this->addNewAccountInternal();

		if ($u == NULL) {
			return;
		}

		$wgUser = $u;
		$wgUser->setCookies();

		$wgUser->saveSettings();
		if( $wgEmailAuthentication && $wgUser->isValidEmailAddr( $wgUser->getEmail() ) ) {
			$wgUser->sendConfirmationMail();
		}

		wfRunHooks( 'AddNewAccount' );

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
		global $wgUseLatin1, $wgEnableSorbs, $wgProxyWhitelist;
		global $wgMemc, $wgAccountCreationThrottle, $wgDBname, $wgIP;
		global $wgAuth;

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

		if (!$wgUser->isAllowedToCreateAccount()) {
			$this->userNotPrivilegedMessage();
			return false;
		}

		if ( $wgEnableSorbs && !in_array( $wgIP, $wgProxyWhitelist ) && 
		  $wgUser->inSorbsBlacklist( $wgIP ) ) 
		{
			$this->mainLoginForm( wfMsg( 'sorbs_create_account_reason' ) . ' (' . htmlspecialchars( $wgIP ) . ')' );
			return;
		}


		if ( 0 != strcmp( $this->mPassword, $this->mRetype ) ) {
			$this->mainLoginForm( wfMsg( 'badretype' ) );
			return false;
		}
		
		$name = trim( $this->mName );
		$u = User::newFromName( $name );
		if ( is_null( $u ) ) {
			$this->mainLoginForm( wfMsg( 'noname' ) );
			return false;
		}
		
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return false;
		}
		
		if ( 0 != $u->idForName() ) {
			$this->mainLoginForm( wfMsg( 'userexists' ) );
			return false;
		}

		if ( !$wgUser->isValidPassword( $this->mPassword ) ) {
			$this->mainLoginForm( wfMsg( 'passwordtooshort', $wgMinimalPasswordLength ) );
			return false;
		}

		if ( $wgAccountCreationThrottle ) {
			$key = $wgDBname.':acctcreate:ip:'.$wgIP;
			$value = $wgMemc->incr( $key );
			if ( !$value ) {
				$wgMemc->set( $key, 1, 86400 );
			}
			if ( $value > $wgAccountCreationThrottle ) {
				$this->throttleHit( $wgAccountCreationThrottle );
				return false;
			}
		}

		if( !$wgAuth->addUser( $u, $this->mPassword ) ) {
			$this->mainLoginForm( wfMsg( 'externaldberror' ) );
			return false;
		}

		# Update user count
		$ssUpdate = new SiteStatsUpdate( 0, 0, 0, 0, 1 );
		$ssUpdate->doUpdate();

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
		$u->setToken();
		
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
		global $wgUser;
		global $wgAuth;

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
			if ( $wgAuth->autoCreate() && $wgAuth->userExists( $u->getName() ) ) {
				if ( $wgAuth->authenticate( $u->getName(), $this->mPassword ) ) {
					$u =& $this->initUser( $u );
				} else {
					$this->mainLoginForm( wfMsg( 'wrongpassword' ) );
					return;
				}
			} else {
				$this->mainLoginForm( wfMsg( 'nosuchuser', $u->getName() ) );
				return;
			}
		} else {
			$u->loadFromDatabase();
		}

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

		$wgAuth->updateUser( $u );

		$wgUser = $u;
		$wgUser->setCookies();

		$wgUser->saveSettings();
		
		if( $this->hasSessionCookie() ) {
			return $this->successfulLogin( wfMsg( 'loginsuccess', $wgUser->getName() ) );
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

		$result = $this->mailPasswordInternal( $u );
		if( WikiError::isError( $result ) ) {
			$this->mainLoginForm( wfMsg( 'mailerror', $result->getMessage() ) );
		} else {
			$this->mainLoginForm( wfMsg( 'passwordsent', $u->getName() ) );
		}
	}


	/**
	 * @return mixed true on success, WikiError on failure
	 * @access private
	 */
	function mailPasswordInternal( $u ) {
		global $wgPasswordSender, $wgDBname, $wgIP;
		global $wgCookiePath, $wgCookieDomain;

		if ( '' == $u->getEmail() ) {
			return wfMsg( 'noemail', $u->getName() );
		}

		$np = $u->randomPassword();
		$u->setNewpassword( $np );

		setcookie( "{$wgDBname}Token", '', time() - 3600, $wgCookiePath, $wgCookieDomain );

		$u->saveSettings();

		$ip = $wgIP;
		if ( '' == $ip ) { $ip = '(Unknown)'; }

		$m = wfMsg( 'passwordremindertext', $ip, $u->getName(), $np );

		$result = $u->sendMail( wfMsg( 'passwordremindertitle' ), $m );
		return $result;
	}


	/**
	 * @param string $msg Message that will be shown on success.
	 * @access private
	 */
	function successfulLogin( $msg ) {
		global $wgUser;
		global $wgOut;

		# Run any hooks; ignore results
		
		wfRunHooks('UserLoginComplete', array(&$wgUser));
		
		$wgOut->setPageTitle( wfMsg( 'loginsuccesstitle' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );
		$wgOut->addWikiText( $msg );
		$wgOut->returnToMain();
	}

	/** */
	function userNotPrivilegedMessage() {
		global $wgOut;
		
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
		global $wgAuth;

		if ( '' == $this->mName ) {
			if ( $wgUser->isLoggedIn() ) {
				$this->mName = $wgUser->getName();
			} else {
				$this->mName = @$_COOKIE[$wgDBname.'UserName'];
			}
		}

		$q = 'action=submitlogin';
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
		$template->set( 'domain', $this->mDomain );

		$template->set( 'action', $titleObj->getLocalUrl( $q ) );
		$template->set( 'error', $err );
		$template->set( 'create', $wgUser->isAllowedToCreateAccount() );
		$template->set( 'createemail', $wgEnableEmail && $wgUser->isLoggedIn() );
		$template->set( 'userealname', $wgAllowRealName );
		$template->set( 'useemail', $wgEnableEmail );
		$template->set( 'remember', $wgUser->getOption( 'rememberpassword' ) or $this->mRemember  );
		$wgAuth->modifyUITemplate( $template );
		
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
		return ( $wgDisableCookieCheck ) ? true : ( isset( $_COOKIE[session_name()] ) );
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
