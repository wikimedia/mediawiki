<?php
# See user.doc

include_once( "WatchedItem.php" );

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
		$res = wfQuery( $sql, DB_READ, "User::idFromName" );

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
		global $wgLang, $wgIP;
		global $wgNamespacesToBeSearchedDefault;

		$this->mId = $this->mNewtalk = 0;
		$this->mName = $wgIP;
		$this->mEmail = "";
		$this->mPassword = $this->mNewpassword = "";
		$this->mRights = array();
		$defOpt = $wgLang->getDefaultUserOptions() ;
		foreach ( $defOpt as $oname => $val ) {
			$this->mOptions[$oname] = $val;
		}
		foreach ($wgNamespacesToBeSearchedDefault as $nsnum => $val) {
			$this->mOptions["searchNs".$nsnum] = $val;
		}
		unset( $this->mSkin );
		$this->mDataLoaded = false;
		$this->mBlockedby = -1; # Unset
		$this->mTouched = '0'; # Allow any pages to be cached
		$this->cookiePassword = "";
	}

	/* private */ function getBlockedStatus()
	{
		global $wgIP, $wgBlockCache;

		if ( -1 != $this->mBlockedby ) { return; }
	
		$this->mBlockedby = 0;
		
		# User blocking
		if ( $this->mId ) {	
			$block = new Block();
			if ( $block->load( $wgIP , $this->mId ) ) {
				$this->mBlockedby = $block->mBy;
				$this->mBlockreason = $block->mReason;
			}
		}

		# IP/range blocking
		if ( !$this->mBlockedby ) {
			$block = $wgBlockCache->get( $wgIP );
			if ( $block !== false ) {
				$this->mBlockedby = $block->mBy;
				$this->mBlockreason = $block->mReason;
			}
		}
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

	function SetupSession() {
		global $wgSessionsInMemcached, $wgCookiePath, $wgCookieDomain;
		if( $wgSessionsInMemcached ) {
			include_once( "MemcachedSessions.php" );
		}
		session_set_cookie_params( 0, $wgCookiePath, $wgCookieDomain );
		session_cache_limiter( "private, must-revalidate" );
		session_start();
	}

	/* static */ function loadFromSession()
	{
		global $wgMemc, $wgDBname;

		if ( isset( $_SESSION['wsUserID'] ) ) {
			if ( 0 != $_SESSION['wsUserID'] ) {
				$sId = $_SESSION['wsUserID'];
			} else {
				return new User();
			}
		} else if ( isset( $_COOKIE["{$wgDBname}UserID"] ) ) {
			$sId = IntVal( $_COOKIE["{$wgDBname}UserID"] );
			$_SESSION['wsUserID'] = $sId;
		} else {
			return new User();
		}
		if ( isset( $_SESSION['wsUserName'] ) ) {
			$sName = $_SESSION['wsUserName'];
		} else if ( isset( $_COOKIE["{$wgDBname}UserName"] ) ) {
			$sName = $_COOKIE["{$wgDBname}UserName"];
			$_SESSION['wsUserName'] = $sName;
		} else {
			return new User();
		}

		$passwordCorrect = FALSE;
		$user = $wgMemc->get( $key = "$wgDBname:user:id:$sId" );
		if($makenew = !$user) {
			wfDebug( "User::loadFromSession() unable to load from memcached\n" );
			$user = new User();
			$user->mId = $sId;
			$user->loadFromDatabase();
		} else {
			wfDebug( "User::loadFromSession() got from cache!\n" );
		}

		if ( isset( $_SESSION['wsUserPassword'] ) ) {
			$passwordCorrect = $_SESSION['wsUserPassword'] == $user->mPassword;
		} else if ( isset( $_COOKIE["{$wgDBname}Password"] ) ) {
			$user->mCookiePassword = $_COOKIE["{$wgDBname}Password"];
			$_SESSION['wsUserPassword'] = $user->addSalt( $user->mCookiePassword );
			$passwordCorrect = $_SESSION['wsUserPassword'] == $user->mPassword;
		} else {
			return new User(); # Can't log in from session
		}

		if ( ( $sName == $user->mName ) && $passwordCorrect ) {
			if($makenew) {
				if($wgMemc->set( $key, $user ))
					wfDebug( "User::loadFromSession() successfully saved user\n" );
				else
					wfDebug( "User::loadFromSession() unable to save to memcached\n" );
			}
			$user->spreadBlock();
			return $user;
		}
		return new User(); # Can't log in from session
	}

	function loadFromDatabase()
	{
		global $wgCommandLineMode;
		if ( $this->mDataLoaded || $wgCommandLineMode ) {
			return;
		}

		# Paranoia
		$this->mId = IntVal( $this->mId );

		# check in separate table if there are changes to the talk page
		$this->mNewtalk=0; # reset talk page status
		if($this->mId) {
			$sql = "SELECT 1 FROM user_newtalk WHERE user_id={$this->mId}";
			$res = wfQuery ($sql, DB_READ, "User::loadFromDatabase" );

			if (wfNumRows($res)>0) {
				$this->mNewtalk= 1;
			}
			wfFreeResult( $res );
		} else {
		  	# TEST THIS @@@
			global $wgDBname, $wgMemc;
			$key = "$wgDBname:newtalk:ip:{$this->mName}";
			$newtalk = $wgMemc->get( $key );
			if( ! is_integer( $newtalk ) ){
				$sql = "SELECT 1 FROM user_newtalk WHERE user_ip='{$this->mName}'";
				$res = wfQuery ($sql, DB_READ, "User::loadFromDatabase" );

				$this->mNewtalk = (wfNumRows($res)>0) ? 1 : 0;
				wfFreeResult( $res );

				$wgMemc->set( $key, $this->mNewtalk, time() ); // + 1800 );
			} else {
				$this->mNewtalk = $newtalk ? 1 : 0;
			}
		}
		if(!$this->mId) {
			$this->mDataLoaded = true;
			return;
		} # the following stuff is for non-anonymous users only

		$sql = "SELECT user_name,user_password,user_newpassword,user_email," .
		  "user_options,user_rights,user_touched FROM user WHERE user_id=" .
		  "{$this->mId}";
		$res = wfQuery( $sql, DB_READ, "User::loadFromDatabase" );

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
		global $wgPasswordSalt;
		if($wgPasswordSalt)
			return md5( "{$this->mId}-{$p}" );
		else
			return $p;
	}

	function encryptPassword( $p )
	{
		return $this->addSalt( md5( $p ) );
	}

	function setPassword( $str )
	{
		$this->loadFromDatabase();
		$this->setCookiePassword( $str );
		$this->mPassword = $this->encryptPassword( $str );
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
		$this->mNewpassword = $this->encryptPassword( $str );
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

	function addRight( $rname )
	{
		$this->loadFromDatabase();
		array_push( $this->mRights, $rname );
		$this->invalidateCache();
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

	function isBureaucrat()
	{
		$this->loadFromDatabase();
		if ( 0 == $this->mId ) { return false; }

		return in_array( "bureaucrat", $this->mRights );
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

	function isWatched( $title ) {
		$wl = WatchedItem::fromUserTitle( $this, $title );
		return $wl->isWatched();
	}
	
	function addWatch( $title ) {
		$wl = WatchedItem::fromUserTitle( $this, $title );
		$wl->addWatch();
		$this->invalidateCache();
	}
	
	function removeWatch( $title ) {
		$wl = WatchedItem::fromUserTitle( $this, $title );
		$wl->removeWatch();
		$this->invalidateCache();
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
		global $wgCookieExpiration, $wgCookiePath, $wgCookieDomain, $wgDBname;
		if ( 0 == $this->mId ) return;
		$this->loadFromDatabase();
		$exp = time() + $wgCookieExpiration;

		$_SESSION['wsUserID'] = $this->mId;
		setcookie( "{$wgDBname}UserID", $this->mId, $exp, $wgCookiePath, $wgCookieDomain );

		$_SESSION['wsUserName'] = $this->mName;
		setcookie( "{$wgDBname}UserName", $this->mName, $exp, $wgCookiePath, $wgCookieDomain );

		$_SESSION['wsUserPassword'] = $this->mPassword;
		if ( 1 == $this->getOption( "rememberpassword" ) ) {
			setcookie( "{$wgDBname}Password", $this->mCookiePassword, $exp, $wgCookiePath, $wgCookieDomain );
		} else {
			setcookie( "{$wgDBname}Password", "", time() - 3600 );
		}
	}

	function logout()
	{
		global $wgCookiePath, $wgCookieDomain, $wgDBname;
		$this->mId = 0;

		$_SESSION['wsUserID'] = 0;

		setcookie( "{$wgDBname}UserID", "", time() - 3600, $wgCookiePath, $wgCookieDomain );
		setcookie( "{$wgDBname}Password", "", time() - 3600, $wgCookiePath, $wgCookieDomain );
	}

	function saveSettings()
	{
		global $wgMemc, $wgDBname;

		if ( ! $this->mNewtalk ) {
			if( $this->mId ) {
				$sql="DELETE FROM user_newtalk WHERE user_id={$this->mId}";
				wfQuery ($sql, DB_WRITE, "User::saveSettings");
			} else {
				$sql="DELETE FROM user_newtalk WHERE user_ip='{$this->mName}'";
				wfQuery ($sql, DB_WRITE, "User::saveSettings");
				$wgMemc->delete( "$wgDBname:newtalk:ip:{$this->mName}" );
			}
		}
		if ( 0 == $this->mId ) { return; }

		$sql = "UPDATE user SET " .
		  "user_name= '" . wfStrencode( $this->mName ) . "', " .
		  "user_password= '" . wfStrencode( $this->mPassword ) . "', " .
		  "user_newpassword= '" . wfStrencode( $this->mNewpassword ) . "', " .
		  "user_email= '" . wfStrencode( $this->mEmail ) . "', " .
		  "user_options= '" . $this->encodeOptions() . "', " .
		  "user_rights= '" . wfStrencode( implode( ",", $this->mRights ) ) . "', " .
		  "user_touched= '" . wfStrencode( $this->mTouched ) .
		  "' WHERE user_id={$this->mId}";
		wfQuery( $sql, DB_WRITE, "User::saveSettings" );
		$wgMemc->delete( "$wgDBname:user:id:$this->mId" );
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
		$res = wfQuery( $sql, DB_READ, "User::idForName" );
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
		wfQuery( $sql, DB_WRITE, "User::addToDatabase" );
		$this->mId = $this->idForName();
	}

	function spreadBlock()
	{
          	global $wgIP;
		# If the (non-anonymous) user is blocked, this function will block any IP address
		# that they successfully log on from.
		$fname = "User::spreadBlock";
		
		wfDebug( "User:spreadBlock()\n" );
		if ( $this->mId == 0 ) {
			return;
		}
		
		$userblock = Block::newFromDB( "", $this->mId );
		if ( !$userblock->isValid() ) {
			return;
		}
		
		# Check if this IP address is already blocked
		$ipblock = Block::newFromDB( $wgIP );
		if ( $ipblock->isValid() ) {
			# Just update the timestamp
			$ipblock->updateTimestamp();
			return;
		}
		
		# Make a new block object with the desired properties
		wfDebug( "Autoblocking {$this->mUserName}@{$wgIP}\n" );
		$ipblock->mAddress = $wgIP;
		$ipblock->mUser = 0;
		$ipblock->mBy = $userblock->mBy;
		$ipblock->mReason = wfMsg( "autoblocker", $this->getName(), $userblock->mReason );
		$ipblock->mTimestamp = wfTimestampNow();
		$ipblock->mAuto = 1;
		$ipblock->mExpiry = Block::getAutoblockExpiry( $ipblock->mTimestamp );

		# Insert it
		$ipblock->insert();
	
	}

	function getPageRenderingHash(){
		static $hash = false;
		if( $hash ){
			return $hash;
		}

		// stubthreshold is only included below for completeness, 
		// it will always be 0 when this function is called by parsercache.

		$confstr = 	  $this->getOption( "quickbar" );
		$confstr .= "!" . $this->getOption( "underline" );
		$confstr .= "!" . $this->getOption( "hover" );
		$confstr .= "!" . $this->getOption( "skin" );
		$confstr .= "!" . $this->getOption( "math" );
		$confstr .= "!" . $this->getOption( "highlightbroken" );
		$confstr .= "!" . $this->getOption( "stubthreshold" ); 
		$confstr .= "!" . $this->getOption( "editsection" );
		$confstr .= "!" . $this->getOption( "editsectiononrightclick" );
		$confstr .= "!" . $this->getOption( "showtoc" );
		$confstr .= "!" . $this->getOption( "date" );

		if(strlen($confstr) > 32)
			$hash = md5($confstr);
		else
			$hash = $confstr;
		return $hash;
	}

	function isAllowedToCreateAccount() 
	{
		global $wgWhitelistAccount;
		$allowed = false;
		
		if (!$wgWhitelistAccount) { return 1; }; // default behaviour
		foreach ($wgWhitelistAccount as $right => $ok) {
			$userHasRight = (!strcmp($right, "user") || in_array($right, $this->getRights()));
			$allowed |= ($ok && $userHasRight);
		}
		return $allowed;
	}



}

?>
