<?
# See user.doc

class User {
	/* private */ var $mId, $mName, $mPassword, $mEmail, $mNewtalk;
	/* private */ var $mRights, $mOptions;
	/* private */ var $mDataLoaded, $mNewpassword;
	/* private */ var $mSkin;
	/* private */ var $mBlockedby, $mBlockreason;
	/* private */ var $mTouched;
	/* private */ var $mCookiePassword;

	function User()
	{
		$this->loadDefaults();
	}

	# Static factory method
	#
	function newFromName( $name )
	{
		$u = new User();

		# Clean up name according to title rules

		$t = Title::newFromText( $name );
		$u->setName( $t->getText() );
		return $u;
	}

	/* static */ function whoIs( $id )
	{
		return wfGetSQL( "user", "user_name", "user_id=$id" );
	}

	/* static */ function idFromName( $name )
	{
		$nt = Title::newFromText( $name );
		$sql = "SELECT user_id FROM user WHERE user_name='" .
		  wfStrencode( $nt->getText() ) . "'";
		$res = wfQuery( $sql, "User::idFromName" );

		if ( 0 == wfNumRows( $res ) ) { return 0; }
		else {
			$s = wfFetchObject( $res );
			return $s->user_id;
		}
	}

	# does the string match an anonymous user IP address?
	/* static */ function isIP( $name ) {
		return preg_match("/^\d{1,3}\.\d{1,3}.\d{1,3}\.\d{1,3}$/",$name);

	}



	/* static */ function randomPassword()
	{
		$pwchars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz";
		$l = strlen( $pwchars ) - 1;

		wfSeedRandom();
		$np = $pwchars{mt_rand( 0, $l )} . $pwchars{mt_rand( 0, $l )} .
		  $pwchars{mt_rand( 0, $l )} . chr( mt_rand(48, 57) ) .
		  $pwchars{mt_rand( 0, $l )} . $pwchars{mt_rand( 0, $l )} .
		  $pwchars{mt_rand( 0, $l )};
		return $np;
	}

	function loadDefaults()
	{
		global $wgLang ;

		$this->mId = $this->mNewtalk = 0;
		$this->mName = getenv( "REMOTE_ADDR" );
		$this->mEmail = "";
		$this->mPassword = $this->mNewpassword = "";
		$this->mRights = array();
		$defOpt = $wgLang->getDefaultUserOptions() ;
		foreach ( $defOpt as $oname => $val ) {
			$this->mOptions[$oname] = $val;
		}
		unset( $this->mSkin );
		$this->mDataLoaded = false;
		$this->mBlockedby = -1; # Unset
		$this->mTouched = '0'; # Allow any pages to be cached
		$this->cookiePassword = "";
	}

	/* private */ function getBlockedStatus()
	{
		if ( -1 != $this->mBlockedby ) { return; }

		$remaddr = getenv( "REMOTE_ADDR" );
		if ( 0 == $this->mId ) {
			$sql = "SELECT ipb_by,ipb_reason FROM ipblocks WHERE " .
			  "ipb_address='$remaddr'";
		} else {
			$sql = "SELECT ipb_by,ipb_reason FROM ipblocks WHERE " .
			  "(ipb_address='$remaddr' OR ipb_user={$this->mId})";
		}
		$res = wfQuery( $sql, "User::getBlockedStatus" );
		if ( 0 == wfNumRows( $res ) ) {
			$this->mBlockedby = 0;
			return;
		}
		$s = wfFetchObject( $res );
		$this->mBlockedby = $s->ipb_by;
		$this->mBlockreason = $s->ipb_reason;
	}

	function isBlocked()
	{
		$this->getBlockedStatus();
		if ( 0 == $this->mBlockedby ) { return false; }
		return true;
	}

	function blockedBy() {
		$this->getBlockedStatus();
		return $this->mBlockedby;
	}

	function blockedFor() {
		$this->getBlockedStatus();
		return $this->mBlockreason;
	}

	function loadFromSession()
	{
		global $HTTP_COOKIE_VARS, $wsUserID, $wsUserName, $wsUserPassword;

		if ( isset( $wsUserID ) ) {
			if ( 0 != $wsUserID ) {
				$sId = $wsUserID;
			} else {
				$this->mId = 0;
				return;
			}
		} else if ( isset( $HTTP_COOKIE_VARS["wcUserID"] ) ) {
			$sId = $HTTP_COOKIE_VARS["wcUserID"];
			$wsUserID = $sId;
		} else {
			$this->mId = 0;
			return;
		}
		if ( isset( $wsUserName ) ) {
			$sName = $wsUserName;
		} else if ( isset( $HTTP_COOKIE_VARS["wcUserName"] ) ) {
			$sName = $HTTP_COOKIE_VARS["wcUserName"];
			$wsUserName = $sName;
		} else {
			$this->mId = 0;
			return;
		}

		$passwordCorrect = FALSE;
		$this->mId = $sId;
		$this->loadFromDatabase();

		if ( isset( $wsUserPassword ) ) {
			$passwordCorrect = $wsUserPassword == $this->mPassword;
		} else if ( isset( $HTTP_COOKIE_VARS["wcUserPassword"] ) ) {
			$this->mCookiePassword = $HTTP_COOKIE_VARS["wcUserPassword"];
			$wsUserPassword = User::addSalt($this->mCookiePassword);
			$passwordCorrect = $wsUserPassword == $this->mPassword;
		} else {
			$this->mId = 0;
			return;
		}

		if ( ( $sName == $this->mName ) && $passwordCorrect ) {
			return;
		}
		$this->loadDefaults(); # Can't log in from session
	}

	function loadFromDatabase()
	{
		if ( $this->mDataLoaded ) { return; }
		# check in separate table if there are changes to the talk page
		$this->mNewtalk=0; # reset talk page status
		if($this->mId) {
			$sql = "SELECT 1 FROM user_newtalk WHERE user_id={$this->mId}";
			$res = wfQuery ($sql,  "User::loadFromDatabase" );

			if (wfNumRows($res)>0) {
				$this->mNewtalk= 1;
			}
			wfFreeResult( $res );
		} else {
			$sql = "SELECT 1 FROM user_newtalk WHERE user_ip='{$this->mName}'";
			$res = wfQuery ($sql,  "User::loadFromDatabase" );

			if (wfNumRows($res)>0) {
				$this->mNewtalk= 1;
			}
			wfFreeResult( $res );
		}
		if(!$this->mId) {
			$this->mDataLoaded = true;
			return;
		} # the following stuff is for non-anonymous users only

		$sql = "SELECT user_name,user_password,user_newpassword,user_email," .
		  "user_options,user_rights,user_touched FROM user WHERE user_id=" .
		  "{$this->mId}";
		$res = wfQuery( $sql, "User::loadFromDatabase" );

		if ( wfNumRows( $res ) > 0 ) {
			$s = wfFetchObject( $res );
			$this->mName = $s->user_name;
			$this->mEmail = $s->user_email;
			$this->mPassword = $s->user_password;
			$this->mNewpassword = $s->user_newpassword;
			$this->decodeOptions( $s->user_options );
			$this->mRights = explode( ",", strtolower( $s->user_rights ) );
			$this->mTouched = $s->user_touched;
		}

		wfFreeResult( $res );
		$this->mDataLoaded = true;
	}

	function getID() { return $this->mId; }
	function setID( $v ) {
		$this->mId = $v;
		$this->mDataLoaded = false;
	}

	function getName() {
		$this->loadFromDatabase();
		return $this->mName;
	}

	function setName( $str )
	{
		$this->loadFromDatabase();
		$this->mName = $str;
	}

	function getNewtalk()
	{
		$this->loadFromDatabase();
		return ( 0 != $this->mNewtalk );
	}

	function setNewtalk( $val )
	{
		$this->loadFromDatabase();
		$this->mNewtalk = $val;
		$this->invalidateCache();
	}

	function invalidateCache() {
		$this->loadFromDatabase();
		$this->mTouched = wfTimestampNow();
		# Don't forget to save the options after this or
		# it won't take effect!
	}

	function validateCache( $timestamp ) {
		$this->loadFromDatabase();
		return ($timestamp >= $this->mTouched);
	}

	function getPassword()
	{
		$this->loadFromDatabase();
		return $this->mPassword;
	}

	function getNewpassword()
	{
		$this->loadFromDatabase();
		return $this->mNewpassword;
	}

	function addSalt( $p )
	{
		return md5( "wikipedia{$this->mId}-{$p}" );
	}

	function encryptPassword( $p )
	{
		return User::addSalt( md5( $p ) );
	}

	function setPassword( $str )
	{
		$this->loadFromDatabase();
		$this->setCookiePassword( $str );
		$this->mPassword = User::encryptPassword( $str );
		$this->mNewpassword = "";
	}

	function setCookiePassword( $str )
	{
		$this->loadFromDatabase();
		$this->mCookiePassword = md5( $str );
	}

	function setNewpassword( $str )
	{
		$this->loadFromDatabase();
		$this->mNewpassword = User::encryptPassword( $str );
	}

	function getEmail()
	{
		$this->loadFromDatabase();
		return $this->mEmail;
	}

	function setEmail( $str )
	{
		$this->loadFromDatabase();
		$this->mEmail = $str;
	}

	function getOption( $oname )
	{
		$this->loadFromDatabase();
		if ( array_key_exists( $oname, $this->mOptions ) ) {
			return $this->mOptions[$oname];
		} else {
			return "";
		}
	}

	function setOption( $oname, $val )
	{
		$this->loadFromDatabase();
		$this->mOptions[$oname] = $val;
		$this->invalidateCache();
	}

	function getRights()
	{
		$this->loadFromDatabase();
		return $this->mRights;
	}

	function isSysop()
	{
		$this->loadFromDatabase();
		if ( 0 == $this->mId ) { return false; }

		return in_array( "sysop", $this->mRights );
	}

	function isDeveloper()
	{
		$this->loadFromDatabase();
		if ( 0 == $this->mId ) { return false; }

		return in_array( "developer", $this->mRights );
	}

	function isBot()
	{
		$this->loadFromDatabase();
		if ( 0 == $this->mId ) { return false; }

		return in_array( "bot", $this->mRights );
	}

	function &getSkin()
	{
		if ( ! isset( $this->mSkin ) ) {
			$skinNames = Skin::getSkinNames();
			$s = $this->getOption( "skin" );
			if ( "" == $s ) { $s = 0; }

			if ( $s >= count( $skinNames ) ) { $sn = "SkinStandard"; }
			else $sn = "Skin" . $skinNames[$s];
			$this->mSkin = new $sn;
		}
		return $this->mSkin;
	}

	function isWatched( $title )
	{
		# Note - $title should be a Title _object_
		# Pages and their talk pages are considered equivalent for watching;
		# remember that talk namespaces are numbered as page namespace+1.
		if( $this->mId ) {
			$sql = "SELECT 1 FROM watchlist
			  WHERE wl_user={$this->mId} AND
			  wl_namespace = " . ($title->getNamespace() & ~1) . " AND
			  wl_title='" . wfStrencode( $title->getDBkey() ) . "'";
			$res = wfQuery( $sql );
			return (wfNumRows( $res ) > 0);
		} else {
			return false;
		}
	}

	function addWatch( $title )
	{
		if( $this->mId ) {
			# REPLACE instead of INSERT because occasionally someone
			# accidentally reloads a watch-add operation.
			$sql = "REPLACE INTO watchlist (wl_user, wl_namespace,wl_title)
			  VALUES ({$this->mId}," . (($title->getNamespace() | 1) - 1) .
			  ",'" . wfStrencode( $title->getDBkey() ) . "')";
			wfQuery( $sql );
			$this->invalidateCache();
		}
	}

	function removeWatch( $title )
	{
		if( $this->mId ) {
			$sql = "DELETE FROM watchlist WHERE wl_user={$this->mId} AND
			  wl_namespace=" . (($title->getNamespace() | 1) - 1) .
			  " AND wl_title='" . wfStrencode( $title->getDBkey() ) . "'";
			wfQuery( $sql );
            $this->invalidateCache();
		}
	}


	/* private */ function encodeOptions()
	{
		$a = array();
		foreach ( $this->mOptions as $oname => $oval ) {
			array_push( $a, "{$oname}={$oval}" );
		}
		$s = implode( "\n", $a );
		return wfStrencode( $s );
	}

	/* private */ function decodeOptions( $str )
	{
		$a = explode( "\n", $str );
		foreach ( $a as $s ) {
			if ( preg_match( "/^(.[^=]*)=(.*)$/", $s, $m ) ) {
				$this->mOptions[$m[1]] = $m[2];
			}
		}
	}

	function setCookies()
	{
		global $wsUserID, $wsUserName, $wsUserPassword;
		global $wgCookieExpiration;
		if ( 0 == $this->mId ) return;
		$this->loadFromDatabase();
		$exp = time() + $wgCookieExpiration;

		$wsUserID = $this->mId;
		setcookie( "wcUserID", $this->mId, $exp, "/" );

		$wsUserName = $this->mName;
		setcookie( "wcUserName", $this->mName, $exp, "/" );

		$wsUserPassword = $this->mPassword;
		if ( 1 == $this->getOption( "rememberpassword" ) ) {
			setcookie( "wcUserPassword", $this->mCookiePassword, $exp, "/" );
		} else {
			setcookie( "wcUserPassword", "", time() - 3600 );
		}
	}

	function logout()
	{
		global $wsUserID;
		$this->mId = 0;

		$wsUserID = 0;

		setcookie( "wcUserID", "", time() - 3600 );
		setcookie( "wcUserPassword", "", time() - 3600 );
	}

	function saveSettings()
	{
		global $wgUser;

		if(!$this->mNewtalk) {

			if($this->mId) {
				$sql="DELETE FROM user_newtalk WHERE user_id={$this->mId}";
				wfQuery ($sql,"User::saveSettings");
			} else {


				$sql="DELETE FROM user_newtalk WHERE user_ip='{$this->mName}'";
				wfQuery ($sql,"User::saveSettings");

			}
		}

		if ( 0 == $this->mId ) { return; }

		$sql = "UPDATE user SET " .
		  "user_name= '" . wfStrencode( $this->mName ) . "', " .
		  "user_password= '" . wfStrencode( $this->mPassword ) . "', " .
		  "user_newpassword= '" . wfStrencode( $this->mNewpassword ) . "', " .
		  "user_email= '" . wfStrencode( $this->mEmail ) . "', " .
		  "user_options= '" . $this->encodeOptions() . "', " .
		  "user_rights= '" . wfStrencode( implode( ",", $this->mRights ) ) . "', " 
.
		  "user_touched= '" . wfStrencode( $this->mTouched ) .
		  "' WHERE user_id={$this->mId}";
		wfQuery( $sql, "User::saveSettings" );
	}

	# Checks if a user with the given name exists
	#
	function idForName()
	{
		$gotid = 0;
		$s = trim( $this->mName );
		if ( 0 == strcmp( "", $s ) ) return 0;

		$sql = "SELECT user_id FROM user WHERE user_name='" .
		  wfStrencode( $s ) . "'";
		$res = wfQuery( $sql, "User::idForName" );
		if ( 0 == wfNumRows( $res ) ) { return 0; }

		$s = wfFetchObject( $res );
		if ( "" == $s ) return 0;

		$gotid = $s->user_id;
		wfFreeResult( $res );
		return $gotid;
	}

	function addToDatabase()
	{
		$sql = "INSERT INTO user (user_name,user_password,user_newpassword," .
		  "user_email, user_rights, user_options) " .
		  " VALUES ('" . wfStrencode( $this->mName ) . "', '" .
		  wfStrencode( $this->mPassword ) . "', '" .
		  wfStrencode( $this->mNewpassword ) . "', '" .
		  wfStrencode( $this->mEmail ) . "', '" .
		  wfStrencode( implode( ",", $this->mRights ) ) . "', '" .
		  $this->encodeOptions() . "')";
		wfQuery( $sql, "User::addToDatabase" );
		$this->mId = $this->idForName();
	}
}

?>
