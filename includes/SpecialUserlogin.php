<?

function wfSpecialUserlogin()
{
	global $wpCreateaccount, $wpCreateaccountMail;
	global $wpLoginattempt, $wpMailmypassword;
	global $action;

	$fields = array( "wpName", "wpPassword", "wpName",
	  "wpPassword", "wpRetype", "wpEmail" );
	wfCleanFormFields( $fields );

	if ( isset( $wpCreateaccount ) ) {
		addNewAccount();
	} else if ( isset( $wpCreateaccountMail ) ) {
		addNewAccountMailPassword();
	} else if ( isset( $wpMailmypassword ) ) {
		mailPassword();
	} else if ( "submit" == $action || isset( $wpLoginattempt ) ) {
		processLogin();
	} else {
		mainLoginForm( "" );
	}
}


/* private */ function addNewAccountMailPassword()
{
	global $wgOut, $wpEmail, $wpName;
	
	if ("" == $wpEmail) {
		$m = str_replace( "$1", $wpName, wfMsg( "noemail" ) );
		mainLoginForm( $m );
		return;
	}

	$u = addNewaccountInternal();

	if ($u == NULL) {
		return;
	}

	$u->saveSettings();
	if (mailPasswordInternal($u) == NULL)
	{
		return;  
	}

	$wgOut->setPageTitle( wfMsg( "accmailtitle" ) );
	$wgOut->setRobotpolicy( "noindex,nofollow" );
	$wgOut->setArticleFlag( false );

	$m = str_replace( "$1", $u->getName(), wfMsg( "accmailtext" ) );
	$m = str_replace( "$2", $u->getEmail(), $m );
	$wgOut->addWikiText( $m );
	$wgOut->returnToMain( false );

	$u = 0;
}


/* private */ function addNewAccount()
{
	global $wgUser, $wgOut, $wpPassword, $wpRetype, $wpName, $wpRemember;
	global $wpEmail, $wgDeferredUpdateList;

	$u = addNewAccountInternal();

	if ($u == NULL) {
		return;
	}

	$wgUser = $u;
	$m = str_replace( "$1", $wgUser->getName(), wfMsg( "welcomecreation" ) );
	successfulLogin( $m );
}


/* private */ function addNewAccountInternal()
{
	global $wgUser, $wgOut, $wpPassword, $wpRetype, $wpName, $wpRemember;
	global $wpEmail, $wgDeferredUpdateList;

	if (!$wgUser->isAllowedToCreateAccount()) {
		userNotPrivilegedMessage();
		return;
	}

	if ( 0 != strcmp( $wpPassword, $wpRetype ) ) {
		mainLoginForm( wfMsg( "badretype" ) );
		return;
	}
	$wpName = trim( $wpName );
	if ( ( "" == $wpName ) ||
	  preg_match( "/\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}/", $wpName ) ||
	  (strpos( $wpName, "/" ) !== false) ) 
	{
		mainLoginForm( wfMsg( "noname" ) );
		return;
	}
	if ( wfReadOnly() ) {
		$wgOut->readOnlyPage();
		return;
	}
	$u = User::newFromName( $wpName );

	if ( 0 != $u->idForName() ) {
		mainLoginForm( wfMsg( "userexists" ) );
		return;
	}
	$u->addToDatabase();
	$u->setPassword( $wpPassword );
	$u->setEmail( $wpEmail );
	if ( 1 == $wpRemember ) { $r = 1; }
	else { $r = 0; }
	$u->setOption( "rememberpassword", $r );
	
	return $u;
}




/* private */ function processLogin()
{
	global $wgUser, $wpName, $wpPassword, $wpRemember;
	global $returnto;

	if ( "" == $wpName ) {
		mainLoginForm( wfMsg( "noname" ) );
		return;
	}
	$u = User::newFromName( $wpName );
	$id = $u->idForName();
	if ( 0 == $id ) {
		$m = str_replace( "$1", $u->getName(), wfMsg( "nosuchuser" ) );
		mainLoginForm( $m );
		return;
	}
	$u->setId( $id );
	$u->loadFromDatabase();
	$ep = $u->encryptPassword( $wpPassword );
	if ( 0 != strcmp( $ep, $u->getPassword() ) ) {
		if ( 0 != strcmp( $ep, $u->getNewpassword() ) ) {
			mainLoginForm( wfMsg( "wrongpassword" ) );
			return;
		}
	}

	# We've verified now, update the real record
	#
	if ( 1 == $wpRemember ) {
		$r = 1;
		$u->setCookiePassword( $wpPassword );
	} else {
		$r = 0;
	}
	$u->setOption( "rememberpassword", $r );

	$wgUser = $u;
	$m = str_replace( "$1", $wgUser->getName(), wfMsg( "loginsuccess" ) );
	successfulLogin( $m );
}

/* private */ function mailPassword()
{
	global $wgUser, $wpName, $wgDeferredUpdateList, $wgOutputEncoding;
	global $wgCookiePath, $wgCookieDomain, $wgDBname;
	
	if ( "" == $wpName ) {
		mainLoginForm( wfMsg( "noname" ) );
		return;
	}
	$u = User::newFromName( $wpName );
	$id = $u->idForName();
	if ( 0 == $id ) {
		$m = str_replace( "$1", $u->getName(), wfMsg( "nosuchuser" ) );
		mainLoginForm( $m );
		return;
	}
	$u->setId( $id );
	$u->loadFromDatabase();

	if (mailPasswordInternal($u) == NULL) {
		return;
	}

	$m = str_replace( "$1", $u->getName(), wfMsg( "passwordsent" ) );
	mainLoginForm( $m );
}


/* private */ function mailPasswordInternal( $u )
{
	global $wpName, $wgDeferredUpdateList, $wgOutputEncoding;
	global $wgPasswordSender;

	if ( "" == $u->getEmail() ) {
		$m = str_replace( "$1", $u->getName(), wfMsg( "noemail" ) );
		mainLoginForm( $m );
		return;
	}
	$np = User::randomPassword();
	$u->setNewpassword( $np );

	setcookie( "{$wgDBname}Password", "", time() - 3600, $wgCookiePath, $wgCookieDomain );
	$u->saveSettings();

	$ip = getenv( "REMOTE_ADDR" );
	if ( "" == $ip ) { $ip = "(Unknown)"; }

	$m = str_replace( "$1", $ip, wfMsg( "passwordremindertext" ) );
	$m = str_replace( "$2", $u->getName(), $m );
	$m = str_replace( "$3", $np, $m );

	mail( $u->getEmail(), wfMsg( "passwordremindertitle" ), $m,
	  "MIME-Version: 1.0\r\n" .
	  "Content-type: text/plain; charset={$wgOutputEncoding}\r\n" .
	  "Content-transfer-encoding: 8bit\r\n" .
	  "From: $wgPasswordSender" );
	  
	return $u;
}





/* private */ function successfulLogin( $msg )
{
	global $wgUser, $wgOut, $returnto;
	global $wgDeferredUpdateList;

	$wgUser->setCookies();
	$up = new UserUpdate();
	array_push( $wgDeferredUpdateList, $up );

	$wgOut->setPageTitle( wfMsg( "loginsuccesstitle" ) );
	$wgOut->setRobotpolicy( "noindex,nofollow" );
	$wgOut->setArticleFlag( false );
	$wgOut->addHTML( $msg . "\n<p>" );
	$wgOut->returnToMain();
}





function userNotPrivilegedMessage()
{
	global $wgOut, $wgUser, $wgLang;

	$wgOut->setPageTitle( wfMsg( "whitelistacctitle" ) );
	$wgOut->setRobotpolicy( "noindex,nofollow" );
	$wgOut->setArticleFlag( false );

	$wgOut->addWikiText( wfMsg( "whitelistacctext" ) );
	$wgOut->returnToMain( false );
}




/* private */ function mainLoginForm( $err )
{
	global $wgUser, $wgOut, $wgLang, $returnto;
	global $wpName, $wpPassword, $wpRetype, $wpRemember;
	global $wpEmail, $HTTP_COOKIE_VARS, $wgDBname;

	$le = wfMsg( "loginerror" );
	$yn = wfMsg( "yourname" );
	$yp = wfMsg( "yourpassword" );
	$ypa = wfMsg( "yourpasswordagain" );
	$rmp = wfMsg( "remembermypassword" );
	$ayn = wfMsg( "areyounew" );
	$nuo = wfMsg( "newusersonly" );
	$li = wfMsg( "login" );
	$ca = wfMsg( "createaccount" );
	$cam = wfMsg( "createaccountmail" );
	$ye = wfMsg( "youremail" );
	$efl = wfMsg( "emailforlost" );
	$mmp = wfMsg( "mailmypassword" );

	$name = $wpName;
	if ( "" == $name ) {
		if ( 0 != $wgUser->getID() ) {
			$name = $wgUser->getName();
		} else {
			$name = $HTTP_COOKIE_VARS["{$wgDBname}UserName"];
		}
	}
	$pwd = $wpPassword;

	$wgOut->setPageTitle( wfMsg( "userlogin" ) );
	$wgOut->setRobotpolicy( "noindex,nofollow" );
	$wgOut->setArticleFlag( false );

	if ( "" == $err ) {
		$wgOut->addHTML( "<h2>$li:</h2>\n" );
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
	if ( "" != $returnto ) { $q .= "&returnto=" . wfUrlencode($returnto); }
	$action = wfLocalUrlE( $wgLang->specialPage( "Userlogin" ), $q );

	$wpName = wfEscapeHTML( $wpName );
	$wpPassword = wfEscapeHTML( $wpPassword );
	$wpRetype = wfEscapeHTML( $wpRetype );
	$wpEmail = wfEscapeHTML( $wpEmail );

	if ($wgUser->getID() != 0) {
		$cambutton = "<input tabindex=6 type=submit name=\"wpCreateaccountMail\" value=\"{$cam}\">";
	}

	$wgOut->addHTML( "
<form name=\"userlogin\" id=\"userlogin\" method=\"post\" action=\"{$action}\">
<table border=0><tr>
<td align=right>$yn:</td>
<td colspan=2 align=left>
<input tabindex=1 type=text name=\"wpName\" value=\"{$name}\" size=20>
</td></tr><tr>
<td align=right>$yp:</td>
<td align=left>
<input tabindex=2 type=password name=\"wpPassword\" value=\"{$pwd}\" size=20>
</td>
<td align=left>
<input tabindex=3 type=submit name=\"wpLoginattempt\" value=\"{$li}\">
</td></tr>");

	if ($wgUser->isAllowedToCreateAccount()) {

$wgOut->addHTML("<tr><td colspan=3>&nbsp;</td></tr><tr>
<td align=right>$ypa:</td>
<td align=left>
<input tabindex=4 type=password name=\"wpRetype\" value=\"{$wpRetype}\" 
size=20>
</td><td>$nuo</td></tr>
<tr>
<td align=right>$ye:</td>
<td align=left>
<input tabindex=5 type=text name=\"wpEmail\" value=\"{$wpEmail}\" size=20>
</td><td align=left>
<input tabindex=6 type=submit name=\"wpCreateaccount\" value=\"{$ca}\">
$cambutton
</td></tr>");
	}

	$wgOut->addHTML("
<tr>
<td colspan=3 align=left>
<input tabindex=7 type=checkbox name=\"wpRemember\" value=\"1\" id=\"wpRemember\"$checked><label for=\"wpRemember\">$rmp</label>
</td></tr>
<tr><td colspan=3>&nbsp;</td></tr><tr>
<td colspan=3 align=left>
<p>$efl<br>
<input tabindex=8 type=submit name=\"wpMailmypassword\" value=\"{$mmp}\">
</td></tr></table>
</form>\n" );



}

?>
