<?php

require_once('UserMailer.php');

function wfSpecialUserlogin()
{
	global $wgCommandLineMode;
	global $wgRequest;
	if( !$wgCommandLineMode && !isset( $_COOKIE[ini_get("session.name")] )  ) {
		User::SetupSession();
	}
	
	$fields = array( "wpName", "wpPassword", "wpName",
	  "wpPassword", "wpRetype" );
	# FIXME: UGLY HACK
	foreach( $fields as $x ) {
		$_REQUEST[$x] = $wgRequest->getText( $x );
	}

	# When switching accounts, it sucks to get automatically logged out
	global $wgLang;
	if( $wgRequest->getVal( 'returnto' ) == $wgLang->specialPage( "Userlogout" ) ) {
		$_REQUEST['returnto'] = "";
	}
	
	$wpCookieCheck = $wgRequest->getVal( "wpCookieCheck" );

	if ( isset( $wpCookieCheck ) ) {
		onCookieRedirectCheck( $wpCookieCheck );
	} else if( $wgRequest->wasPosted() ) {
		if( $wgRequest->getCheck( 'wpCreateaccount' ) ) {
			return addNewAccount();
		} else if ( $wgRequest->getCheck( 'wpCreateaccountMail' ) ) {
			return addNewAccountMailPassword();
		} else if ( $wgRequest->getCheck( 'wpMailmypassword' ) ) {
			return mailPassword();
		} else if ( "submit" == $wgRequest->getVal( 'action' ) || $wgRequest->getCheck( 'wpLoginattempt' ) ) {
			return processLogin();
		}
	}
	mainLoginForm( "" );
}


/* private */ function addNewAccountMailPassword()
{
	global $wgOut;
	
	if ("" == $_REQUEST['wpEmail']) {
		mainLoginForm( wfMsg( "noemail", $_REQUEST['wpName'] ) );
		return;
	}

	$u = addNewaccountInternal();

	if ($u == NULL) {
		return;
	}

	$u->saveSettings();
	if (mailPasswordInternal($u) == NULL) {
		return;  
	}

	$wgOut->setPageTitle( wfMsg( "accmailtitle" ) );
	$wgOut->setRobotpolicy( "noindex,nofollow" );
	$wgOut->setArticleRelated( false );

	$wgOut->addWikiText( wfMsg( "accmailtext", $u->getName(), $u->getEmail() ) );
	$wgOut->returnToMain( false );

	$u = 0;
}


/* private */ function addNewAccount()
{
	global $wgUser, $wgOut;
	global $wgDeferredUpdateList;

	$u = addNewAccountInternal();

	if ($u == NULL) {
		return;
	}

	$wgUser = $u;
	$wgUser->setCookies();

	$up = new UserUpdate();
	array_push( $wgDeferredUpdateList, $up );

	if( hasSessionCookie() ) {
		return successfulLogin( wfMsg( "welcomecreation", $wgUser->getName() ) );
	} else {
		return cookieRedirectCheck( "new" );
	}
}


/* private */ function addNewAccountInternal()
{
	global $wgUser, $wgOut;
	global $wgMaxNameChars;
	global $wgRequest;

	if (!$wgUser->isAllowedToCreateAccount()) {
		userNotPrivilegedMessage();
		return;
	}

	if ( 0 != strcmp( $_REQUEST['wpPassword'], $_REQUEST['wpRetype'] ) ) {
		mainLoginForm( wfMsg( "badretype" ) );
		return;
	}
	
	$name = trim( $_REQUEST['wpName'] );
	if ( ( "" == $name ) ||
	  preg_match( "/\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}/", $name ) ||
	  (strpos( $name, "/" ) !== false) ||
	  (strlen( $name ) > $wgMaxNameChars) ) 
	{
		mainLoginForm( wfMsg( "noname" ) );
		return;
	}
	if ( wfReadOnly() ) {
		$wgOut->readOnlyPage();
		return;
	}
	$u = User::newFromName( $name );
	
	if ( 0 != $u->idForName() ) {
		mainLoginForm( wfMsg( "userexists" ) );
		return;
	}
	$u->addToDatabase();
	$u->setPassword( $_REQUEST['wpPassword'] );
	$u->setEmail( $_REQUEST['wpEmail'] );
	if ( $wgRequest->getCheck( 'wpRemember' ) ) { $r = 1; }
	else { $r = 0; }
	$u->setOption( "rememberpassword", $r );
	
	return $u;
}




/* private */ function processLogin()
{
	global $wgUser;
	global $wgDeferredUpdateList;
	global $wgRequest;

	if ( "" == $_REQUEST['wpName'] ) {
		mainLoginForm( wfMsg( "noname" ) );
		return;
	}
	$u = User::newFromName( $_REQUEST['wpName'] );
	$id = $u->idForName();
	if ( 0 == $id ) {
		mainLoginForm( wfMsg( "nosuchuser", $u->getName() ) );
		return;
	}
	$u->setId( $id );
	$u->loadFromDatabase();
	$ep = $u->encryptPassword( $_REQUEST['wpPassword'] );
	if ( 0 != strcmp( $ep, $u->getPassword() ) ) {
		if ( 0 != strcmp( $ep, $u->getNewpassword() ) ) {
			mainLoginForm( wfMsg( "wrongpassword" ) );
			return;
		}
	}

	# We've verified now, update the real record
	#
	if ( $wgRequest->getCheck( 'wpRemember' ) ) {
		$r = 1;
		$u->setCookiePassword( $wgRequest->getText( 'wpPassword' ) );
	} else {
		$r = 0;
	}
	$u->setOption( "rememberpassword", $r );

	$wgUser = $u;
	$wgUser->setCookies();

	$up = new UserUpdate();
	array_push( $wgDeferredUpdateList, $up );

	if( hasSessionCookie() ) {
		return successfulLogin( wfMsg( "loginsuccess", $wgUser->getName() ) );
	} else {
		return cookieRedirectCheck( "login" );
	}
}

/* private */ function mailPassword()
{
	global $wgUser, $wgDeferredUpdateList, $wgOutputEncoding;
	global $wgCookiePath, $wgCookieDomain, $wgDBname;

	if ( "" == $_REQUEST['wpName'] ) {
		mainLoginForm( wfMsg( "noname" ) );
		return;
	}
	$u = User::newFromName( $_REQUEST['wpName'] );
	$id = $u->idForName();
	if ( 0 == $id ) {
		mainLoginForm( wfMsg( "nosuchuser", $u->getName() ) );
		return;
	}
	$u->setId( $id );
	$u->loadFromDatabase();

	if (mailPasswordInternal($u) == NULL) {
		return;
	}

	mainLoginForm( wfMsg( "passwordsent", $u->getName() ) );
}


/* private */ function mailPasswordInternal( $u )
{
	global $wgDeferredUpdateList, $wgOutputEncoding;
	global $wgPasswordSender, $wgDBname, $wgIP;

	if ( "" == $u->getEmail() ) {
		mainLoginForm( wfMsg( "noemail", $u->getName() ) );
		return;
	}
	$np = User::randomPassword();
	$u->setNewpassword( $np );

	setcookie( "{$wgDBname}Password", "", time() - 3600, $wgCookiePath, $wgCookieDomain );
	$u->saveSettings();

	$ip = $wgIP;
        if ( "" == $ip ) { $ip = "(Unknown)"; }

	$m = wfMsg( "passwordremindertext", $ip, $u->getName(), $np );

	userMailer( $u->getEmail(), $wgPasswordSender, wfMsg( "passwordremindertitle" ), $m );
	  
	return $u;
}





/* private */ function successfulLogin( $msg )
{
	global $wgUser;
	global $wgDeferredUpdateList;
	global $wgOut;

	$wgOut->setPageTitle( wfMsg( "loginsuccesstitle" ) );
	$wgOut->setRobotpolicy( "noindex,nofollow" );
	$wgOut->setArticleRelated( false );
	$wgOut->addHTML( $msg . "\n<p>" );
	$wgOut->returnToMain();
}

function userNotPrivilegedMessage()
{
	global $wgOut, $wgUser, $wgLang;
	
	$wgOut->setPageTitle( wfMsg( "whitelistacctitle" ) );
	$wgOut->setRobotpolicy( "noindex,nofollow" );
	$wgOut->setArticleRelated( false );

	$wgOut->addWikiText( wfMsg( "whitelistacctext" ) );
	
	$wgOut->returnToMain( false );
}

/* private */ function mainLoginForm( $err )
{
	global $wgUser, $wgOut, $wgLang;
	global $wgRequest, $wgDBname;

	$le = wfMsg( "loginerror" );
	$yn = wfMsg( "yourname" );
	$yp = wfMsg( "yourpassword" );
	$ypa = wfMsg( "yourpasswordagain" );
	$rmp = wfMsg( "remembermypassword" );
	$nuo = wfMsg( "newusersonly" );
	$li = wfMsg( "login" );
	$ca = wfMsg( "createaccount" );
	$cam = wfMsg( "createaccountmail" );
	$ye = wfMsg( "youremail" );
	$efl = wfMsg( "emailforlost" );
	$mmp = wfMsg( "mailmypassword" );
	$endText = wfMsg( "loginend" );

	if ( $endText = "&lt;loginend&gt;" ) {
		$endText = "";
	}

	$name = $wgRequest->getText( 'wpName' );
	if ( "" == $name ) {
		if ( 0 != $wgUser->getID() ) {
			$name = $wgUser->getName();
		} else {
			$name = $_COOKIE["{$wgDBname}UserName"];
		}
	}
	$pwd = $wgRequest->getText( 'wpPassword' );

	$wgOut->setPageTitle( wfMsg( "userlogin" ) );
	$wgOut->setRobotpolicy( "noindex,nofollow" );
	$wgOut->setArticleRelated( false );

	if ( "" == $err ) {
		$lp = wfMsg( "loginprompt" );
		$wgOut->addHTML( "<h2>$li:</h2>\n<p>$lp</p>" );
	} else {
		$wgOut->addHTML( "<h2>$le:</h2>\n<font size='+1' 
color='red'>$err</font>\n" );
	}
	if ( 1 == $wgUser->getOption( "rememberpassword" ) ) {
		$checked = " checked";
	} else {
		$checked = "";
	}
	
	$q = "action=submit";
	$returnto = $wgRequest->getVal( "returnto" );
	if ( !empty( $returnto ) ) {
		$q .= "&returnto=" . wfUrlencode( $returnto );
	}
	
	$titleObj = Title::makeTitle( NS_SPECIAL, "Userlogin" );
	$action = $titleObj->escapeLocalUrl( $q );

	$encName = wfEscapeHTML( $name );
	$encPassword = wfEscapeHTML( $pwd );
	$encRetype = wfEscapeHTML( $wgRequest->getText( 'wpRetype' ) );
	$encEmail = wfEscapeHTML( $wgRequest->getVal( 'wpEmail' ) );

	if ($wgUser->getID() != 0) {
		$cambutton = "<input tabindex=6 type=submit name=\"wpCreateaccountMail\" value=\"{$cam}\">";
	} else {
		$cambutton = "";
	}

	$wgOut->addHTML( "
<form name=\"userlogin\" id=\"userlogin\" method=\"post\" action=\"{$action}\">
<table border=0><tr>
<td align=right>$yn:</td>
<td align=left>
<input tabindex=1 type=text name=\"wpName\" value=\"{$encName}\" size=20>
</td>
<td align=left>
<input tabindex=3 type=submit name=\"wpLoginattempt\" value=\"{$li}\">
</td>
</tr>
<tr>
<td align=right>$yp:</td>
<td align=left>
<input tabindex=2 type=password name=\"wpPassword\" value=\"{$encPassword}\" size=20>
</td>
<td align=left>
<input tabindex=7 type=checkbox name=\"wpRemember\" value=\"1\" id=\"wpRemember\"$checked><label for=\"wpRemember\">$rmp</label>
</td>			   
</tr>");

	if ($wgUser->isAllowedToCreateAccount()) {
		$encRetype = htmlspecialchars( $wgRequest->getText( 'wpRetype' ) );
		$encEmail = htmlspecialchars( $wgRequest->getText( 'wpEmail' ) );
$wgOut->addHTML("<tr><td colspan=3>&nbsp;</td></tr><tr>
<td align=right>$ypa:</td>
<td align=left>
<input tabindex=4 type=password name=\"wpRetype\" value=\"{$encRetype}\" 
size=20>
</td><td>$nuo</td></tr>
<tr>
<td align=right>$ye:</td>
<td align=left>
<input tabindex=5 type=text name=\"wpEmail\" value=\"{$encEmail}\" size=20>
</td><td align=left>
<input tabindex=6 type=submit name=\"wpCreateaccount\" value=\"{$ca}\">
$cambutton
</td></tr>");
	}

	$wgOut->addHTML("
<tr><td colspan=3>&nbsp;</td></tr><tr>
<td colspan=3 align=left>
<p>$efl<br>
<input tabindex=8 type=submit name=\"wpMailmypassword\" value=\"{$mmp}\">
</td></tr></table>
</form>\n" );
	$wgOut->addHTML( $endText );
}

/* private */ function hasSessionCookie()
{
	global $wgDisableCookieCheck;
	return ( $wgDisableCookieCheck ) ? true : ( "" != $_COOKIE[session_name()] );
}
  
/* private */ function cookieRedirectCheck( $type )
{
	global $wgOut, $wgLang;

	$titleObj = Title::makeTitle( NS_SPECIAL, "Userlogin" );
	$check = $titleObj->getFullURL( "wpCookieCheck=$type" );

	return $wgOut->redirect( $check );
}

/* private */ function onCookieRedirectCheck( $type ) {
	global $wgUser;

	if ( !hasSessionCookie() ) {
		if ( $type == "new" ) {
			return mainLoginForm( wfMsg( "nocookiesnew" ) );
		} else if ( $type == "login" ) {
			return mainLoginForm( wfMsg( "nocookieslogin" ) );
		} else {
			# shouldn't happen
			return mainLoginForm( wfMsg( "error" ) );
		}
	} else {
		return successfulLogin( wfMsg( "loginsuccess", $wgUser->getName() ) );
	}
}

?>
