<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once('UserMailer.php');

/**
 * consutrctor
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
		
		if ('' == $this->mEmail) {
			$this->mainLoginForm( wfMsg( 'noemail', htmlspecialchars( $this->mName ) ) );
			return;
		}

		$u = $this->addNewaccountInternal();

		if ($u == NULL) {
			return;
		}

		$u->saveSettings();
		$error = $this->mailPasswordInternal($u);

		$wgOut->setPageTitle( wfMsg( 'accmailtitle' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );
	
		if ( $error === '' ) {
			$wgOut->addWikiText( wfMsg( 'accmailtext', $u->getName(), $u->getEmail() ) );
			$wgOut->returnToMain( false );
		} else {
			$this->mainLoginForm( wfMsg( 'mailerror', $error ) );
		}

		$u = 0;
	}


	/**
	 * @access private
	 */
	function addNewAccount() {
		global $wgUser, $wgOut;
		global $wgDeferredUpdateList;

		$u = $this->addNewAccountInternal();

		if ($u == NULL) {
			return;
		}

		$wgUser = $u;
		$wgUser->setCookies();

		$up = new UserUpdate();
		array_push( $wgDeferredUpdateList, $up );

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
		  preg_match( "/\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}/", $name ) ||
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
		global $wgUser;
		global $wgDeferredUpdateList;

		if ( '' == $this->mName ) {
			$this->mainLoginForm( wfMsg( 'noname' ) );
			return;
		}
		$u = User::newFromName( $this->mName );
		if( is_null( $u ) ) {
			$this->mainLoginForm( wfMsg( 'noname' ) );
			return;
		}
		$id = $u->idForName();
		if ( 0 == $id ) {
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
			$u->setId( $id );
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

		$wgUser = $u;
		$wgUser->setCookies();

		$up = new UserUpdate();
		array_push( $wgDeferredUpdateList, $up );

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
		$id = $u->idForName();
		if ( 0 == $id ) {
			$this->mainLoginForm( wfMsg( 'nosuchuser', $u->getName() ) );
			return;
		}
		$u->setId( $id );
		$u->loadFromDatabase();

		$error = $this->mailPasswordInternal( $u );
		if ($error === '') {
			$this->mainLoginForm( wfMsg( 'passwordsent', $u->getName() ) );
		} else {
			$this->mainLoginForm( wfMsg( 'mailerror', $error ) );
		}

	}


	/**
	 * @access private
	 */
	function mailPasswordInternal( $u ) {
		global $wgDeferredUpdateList, $wgOutputEncoding;
		global $wgPasswordSender, $wgDBname, $wgIP;
		global $wgCookiePath, $wgCookieDomain;

		if ( '' == $u->getEmail() ) {
			return wfMsg( 'noemail', $u->getName() );
		}
		$np = User::randomPassword();
		$u->setNewpassword( $np );

		setcookie( "{$wgDBname}Token", '', time() - 3600, $wgCookiePath, $wgCookieDomain );
		$u->saveSettings();

		$ip = $wgIP;
		if ( '' == $ip ) { $ip = '(Unknown)'; }

		$m = wfMsg( 'passwordremindertext', $ip, $u->getName(), $np );

		$error = userMailer( $u->getEmail(), $wgPasswordSender, wfMsg( 'passwordremindertitle' ), $m );
		
		return htmlspecialchars( $error );
	}


	/**
	 * @access private
	 */
	function successfulLogin( $msg ) {
		global $wgUser;
		global $wgDeferredUpdateList;
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
		$template->set( 'createemail', $wgEnableEmail && $wgUser->getID() != 0 );
		$template->set( 'userealname', $wgAllowRealName );
		$template->set( 'useemail', $wgEnableEmail );
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
