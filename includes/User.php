<?php
# See user.doc

require_once( 'WatchedItem.php' );

class User {
	/* private */ var $mId, $mName, $mPassword, $mEmail, $mNewtalk;
	/* private */ var $mRights, $mOptions;
	/* private */ var $mDataLoaded, $mNewpassword;
	/* private */ var $mSkin;
	/* private */ var $mBlockedby, $mBlockreason;
	/* private */ var $mTouched;
	/* private */ var $mCookiePassword;
	/* private */ var $mRealName;
	/* private */ var $mHash;

	function User()	{
		$this->loadDefaults();
	}

	# Static factory method
	#
	function newFromName( $name ) {
		$u = new User();

		# Clean up name according to title rules

		$t = Title::newFromText( $name );
		$u->setName( $t->getText() );
		return $u;
	}

	/* static */ function whoIs( $id )	{
		$dbr =& wfGetDB( DB_SLAVE );
		return $dbr->getField( 'user', 'user_name', array( 'user_id' => $id ) );
	}

	/* static */ function whoIsReal( $id )	{
		$dbr =& wfGetDB( DB_SLAVE );
		return $dbr->getField( 'user', 'user_real_name', array( 'user_id' => $id ) );
	}

	/* static */ function idFromName( $name ) {
		$fname = "User::idFromName";

		$nt = Title::newFromText( $name );
		if( is_null( $nt ) ) {
			# Illegal name
			return null;
		}
		$dbr =& wfGetDB( DB_SLAVE );
		$s = $dbr->getArray( 'user', array( 'user_id' ), array( 'user_name' => $nt->getText() ), $fname );

		if ( $s === false ) {
			return 0;
		} else {
			return $s->user_id;
		}
	}

	# does the string match an anonymous user IP address?
	/* static */ function isIP( $name ) {
		return preg_match("/^\d{1,3}\.\d{1,3}.\d{1,3}\.\d{1,3}$/",$name);

	}

	/* static */ function randomPassword() {
		$pwchars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
		$l = strlen( $pwchars ) - 1;

		$np = $pwchars{mt_rand( 0, $l )} . $pwchars{mt_rand( 0, $l )} .
		  $pwchars{mt_rand( 0, $l )} . chr( mt_rand(48, 57) ) .
		  $pwchars{mt_rand( 0, $l )} . $pwchars{mt_rand( 0, $l )} .
		  $pwchars{mt_rand( 0, $l )};
		return $np;
	}

	function loadDefaults() {
		global $wgLang, $wgIP;
		global $wgNamespacesToBeSearchedDefault;

		$this->mId = $this->mNewtalk = 0;
		$this->mName = $wgIP;
		$this->mRealName = $this->mEmail = '';
		$this->mPassword = $this->mNewpassword = '';
		$this->mRights = array();
		$defOpt = $wgLang->getDefaultUserOptions() ;
		foreach ( $defOpt as $oname => $val ) {
			$this->mOptions[$oname] = $val;
		}
		foreach ($wgNamespacesToBeSearchedDefault as $nsnum => $val) {
			$this->mOptions['searchNs'.$nsnum] = $val;
		}
		unset( $this->mSkin );
		$this->mDataLoaded = false;
		$this->mBlockedby = -1; # Unset
		$this->mTouched = '0'; # Allow any pages to be cached
		$this->cookiePassword = '';
		$this->mHash = false;
	}

	/* private */ function getBlockedStatus()
	{
		global $wgIP, $wgBlockCache, $wgProxyList;

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

		# Proxy blocking
		if ( !$this->mBlockedby ) {
			if ( array_key_exists( $wgIP, $wgProxyList ) ) {
				$this->mBlockreason = wfMsg( 'proxyblockreason' );
				$this->mBlockedby = "Proxy blocker";
			}
		}
	}

	function isBlocked()
	{
		$this->getBlockedStatus();
		if ( 0 === $this->mBlockedby ) { return false; }
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
			require_once( 'MemcachedSessions.php' );
		} elseif( 'files' != ini_get( 'session.save_handler' ) ) {
			# If it's left on 'user' or another setting from another
			# application, it will end up failing. Try to recover.
			ini_set ( 'session.save_handler', 'files' );
		}
		session_set_cookie_params( 0, $wgCookiePath, $wgCookieDomain );
		session_cache_limiter( 'private, must-revalidate' );
		@session_start();
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
		$fname = "User::loadFromDatabase";
		if ( $this->mDataLoaded || $wgCommandLineMode ) {
			return;
		}

		# Paranoia
		$this->mId = IntVal( $this->mId );

		# check in separate table if there are changes to the talk page
		$this->mNewtalk=0; # reset talk page status
		$dbr =& wfGetDB( DB_SLAVE );
		if($this->mId) {
			$res = $dbr->select( 'user_newtalk', 1, array( 'user_id' => $this->mId ), $fname );

			if ( $dbr->numRows($res)>0 ) {
				$this->mNewtalk= 1;
			}
			$dbr->freeResult( $res );
		} else {
			global $wgDBname, $wgMemc;
			$key = "$wgDBname:newtalk:ip:{$this->mName}";
			$newtalk = $wgMemc->get( $key );
			if( ! is_integer( $newtalk ) ){
				$res = $dbr->select( 'user_newtalk', 1, array( 'user_ip' => $this->mName ), $fname );

				$this->mNewtalk = $dbr->numRows( $res ) > 0 ? 1 : 0;
				$dbr->freeResult( $res );

				$wgMemc->set( $key, $this->mNewtalk, time() ); // + 1800 );
			} else {
				$this->mNewtalk = $newtalk ? 1 : 0;
			}
		}
		if(!$this->mId) {
			$this->mDataLoaded = true;
			return;
		} # the following stuff is for non-anonymous users only

		$s = $dbr->getArray( 'user', array( 'user_name','user_password','user_newpassword','user_email',
		  'user_real_name','user_options','user_touched' ),
		  array( 'user_id' => $this->mId ), $fname );

		if ( $s !== false ) {
			$this->mName = $s->user_name;
			$this->mEmail = $s->user_email;
			$this->mRealName = $s->user_real_name;
			$this->mPassword = $s->user_password;
			$this->mNewpassword = $s->user_newpassword;
			$this->decodeOptions( $s->user_options );
			$this->mTouched = wfTimestamp(TS_MW,$s->user_touched);
			$this->mRights = explode( ",", strtolower( 
				$dbr->getField( 'user_rights', 'user_rights', array( 'user_id' => $this->mId ) )
			) );
		}

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

	function setName( $str ) {
		$this->loadFromDatabase();
		$this->mName = $str;
	}

	function getNewtalk() {
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

	function addSalt( $p ) {
		global $wgPasswordSalt;
		if($wgPasswordSalt)
			return md5( "{$this->mId}-{$p}" );
		else
			return $p;
	}

	function encryptPassword( $p ) {
		return $this->addSalt( md5( $p ) );
	}

	function setPassword( $str ) {
		$this->loadFromDatabase();
		$this->setCookiePassword( $str );
		$this->mPassword = $this->encryptPassword( $str );
		$this->mNewpassword = '';
	}

	function setCookiePassword( $str ) {
		$this->loadFromDatabase();
		$this->mCookiePassword = md5( $str );
	}

	function setNewpassword( $str ) {
		$this->loadFromDatabase();
		$this->mNewpassword = $this->encryptPassword( $str );
	}

	function getEmail() {
		$this->loadFromDatabase();
		return $this->mEmail;
	}

	function setEmail( $str ) {
		$this->loadFromDatabase();
		$this->mEmail = $str;
	}

	function getRealName() {
		$this->loadFromDatabase();
		return $this->mRealName;
	}

	function setRealName( $str ) {
		$this->loadFromDatabase();
		$this->mRealName = $str;
	}

	function getOption( $oname ) {
		$this->loadFromDatabase();
		if ( array_key_exists( $oname, $this->mOptions ) ) {
			return $this->mOptions[$oname];
		} else {
			return '';
		}
	}

	function setOption( $oname, $val ) {
		$this->loadFromDatabase();
		if ( $oname == 'skin' ) {
			# Clear cached skin, so the new one displays immediately in Special:Preferences
			unset( $this->mSkin );
		}
		$this->mOptions[$oname] = $val;
		$this->invalidateCache();
	}

	function getRights() {
		$this->loadFromDatabase();
		return $this->mRights;
	}

	function addRight( $rname )	{
		$this->loadFromDatabase();
		array_push( $this->mRights, $rname );
		$this->invalidateCache();
	}

	function isSysop() {
		$this->loadFromDatabase();
		if ( 0 == $this->mId ) { return false; }

		return in_array( 'sysop', $this->mRights );
	}

	function isDeveloper() {
		$this->loadFromDatabase();
		if ( 0 == $this->mId ) { return false; }

		return in_array( 'developer', $this->mRights );
	}

	function isBureaucrat() {
		$this->loadFromDatabase();
		if ( 0 == $this->mId ) { return false; }

		return in_array( 'bureaucrat', $this->mRights );
	}

	function isBot() {
		$this->loadFromDatabase();

		# Why was this here? I need a UID=0 conversion script [TS]
		# if ( 0 == $this->mId ) { return false; }

		return in_array( 'bot', $this->mRights );
	}

	# Load a skin if it doesn't exist or return it
	# FIXME : need to check the old failback system [AV]
	function &getSkin() {
		global $IP, $wgUsePHPTal;
		if ( ! isset( $this->mSkin ) ) {
			# get all skin names available
			$skinNames = Skin::getSkinNames();
			# get the user skin
			$userSkin = $this->getOption( 'skin' );
			if ( $userSkin == '' ) { $userSkin = 'standard'; }

			if ( !isset( $skinNames[$userSkin] ) ) {
				# in case the user skin could not be found find a replacement
				$fallback = array(
					0 => 'Standard',
					1 => 'Nostalgia',
					2 => 'CologneBlue');
				# if phptal is enabled we should have monobook skin that
				# superseed the good old SkinStandard.
				if ( isset( $skinNames['monobook'] ) ) {
					$fallback[0] = 'MonoBook';
				}

				if(is_numeric($userSkin) && isset( $fallback[$userSkin]) ){
					$sn = $fallback[$userSkin];
				} else {
					$sn = 'Standard';
				}
			} else {
				# The user skin is available
				$sn = $skinNames[$userSkin];
			}

			# Grab the skin class and initialise it. Each skin checks for PHPTal
			# and will not load if it's not enabled.
			require_once( $IP.'/skins/'.$sn.'.php' );
			
			# Check if we got if not failback to default skin
			$sn = 'Skin'.$sn;
			if(!class_exists($sn)) {
				#FIXME : should we print an error message instead of loading
				# standard skin ?
				$sn = 'SkinStandard';
				require_once( $IP.'/skins/Standard.php' );
			}
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


	/* private */ function encodeOptions() {
		$a = array();
		foreach ( $this->mOptions as $oname => $oval ) {
			array_push( $a, $oname.'='.$oval );
		}
		$s = implode( "\n", $a );
		return $s;
	}

	/* private */ function decodeOptions( $str ) {
		$a = explode( "\n", $str );
		foreach ( $a as $s ) {
			if ( preg_match( "/^(.[^=]*)=(.*)$/", $s, $m ) ) {
				$this->mOptions[$m[1]] = $m[2];
			}
		}
	}

	function setCookies() {
		global $wgCookieExpiration, $wgCookiePath, $wgCookieDomain, $wgDBname;
		if ( 0 == $this->mId ) return;
		$this->loadFromDatabase();
		$exp = time() + $wgCookieExpiration;

		$_SESSION['wsUserID'] = $this->mId;
		setcookie( $wgDBname.'UserID', $this->mId, $exp, $wgCookiePath, $wgCookieDomain );

		$_SESSION['wsUserName'] = $this->mName;
		setcookie( $wgDBname.'UserName', $this->mName, $exp, $wgCookiePath, $wgCookieDomain );

		$_SESSION['wsUserPassword'] = $this->mPassword;
		if ( 1 == $this->getOption( 'rememberpassword' ) ) {
			setcookie( $wgDBname.'Password', $this->mCookiePassword, $exp, $wgCookiePath, $wgCookieDomain );
		} else {
			setcookie( $wgDBname.'Password', '', time() - 3600 );
		}
	}

	function logout() {
		global $wgCookiePath, $wgCookieDomain, $wgDBname;
		$this->mId = 0;

		$_SESSION['wsUserID'] = 0;

		setcookie( $wgDBname.'UserID', '', time() - 3600, $wgCookiePath, $wgCookieDomain );
		setcookie( $wgDBname.'Password', '', time() - 3600, $wgCookiePath, $wgCookieDomain );
	}

	function saveSettings() {
		global $wgMemc, $wgDBname;
		$fname = 'User::saveSettings';

		$dbw =& wfGetDB( DB_MASTER );
		if ( ! $this->mNewtalk ) {
			# Delete user_newtalk row
			if( $this->mId ) {
				$dbw->delete( 'user_newtalk', array( 'user_id' => $this->mId ), $fname );
			} else {
				$dbw->delete( 'user_newtalk', array( 'user_ip' => $this->mName ), $fname );
				$wgMemc->delete( "$wgDBname:newtalk:ip:{$this->mName}" );
			}
		}
		if ( 0 == $this->mId ) { return; }

		$dbw->update( 'user',
			array( /* SET */
				'user_name' => $this->mName,
				'user_password' => $this->mPassword,
				'user_newpassword' => $this->mNewpassword,
				'user_real_name' => $this->mRealName,
		 		'user_email' => $this->mEmail,
				'user_options' => $this->encodeOptions(),
				'user_touched' => $dbw->timestamp($this->mTouched)
			), array( /* WHERE */
				'user_id' => $this->mId
			), $fname
		);
		$dbw->set( 'user_rights', 'user_rights', implode( ",", $this->mRights ),
			'user_id='. $this->mId, $fname ); 
		$wgMemc->delete( "$wgDBname:user:id:$this->mId" );
	}

	# Checks if a user with the given name exists, returns the ID
	#
	function idForName() {
		$fname = 'User::idForName';

		$gotid = 0;
		$s = trim( $this->mName );
		if ( 0 == strcmp( '', $s ) ) return 0;

		$dbr =& wfGetDB( DB_SLAVE );
		$id = $dbr->selectField( 'user', 'user_id', array( 'user_name' => $s ), $fname );
		if ( $id === false ) {
			$id = 0;
		}
		return $id;
	}

	function addToDatabase() {
		$fname = 'User::addToDatabase';
		$dbw =& wfGetDB( DB_MASTER );
		$seqVal = $dbw->nextSequenceValue( 'user_user_id_seq' );
		$dbw->insert( 'user',
			array(
				'user_id' => $seqVal,
				'user_name' => $this->mName,
				'user_password' => $this->mPassword,
				'user_newpassword' => $this->mNewpassword,
				'user_email' => $this->mEmail,
				'user_real_name' => $this->mRealName,
				'user_options' => $this->encodeOptions()
			), $fname
		);
		$this->mId = $dbw->insertId();
		$dbw->insert( 'user_rights',
			array(
				'user_id' => $this->mId,
				'user_rights' => implode( ',', $this->mRights )
			), $fname
		);
				
	}

	function spreadBlock()
	{
		global $wgIP;
		# If the (non-anonymous) user is blocked, this function will block any IP address
		# that they successfully log on from.
		$fname = 'User::spreadBlock';

		wfDebug( "User:spreadBlock()\n" );
		if ( $this->mId == 0 ) {
			return;
		}

		$userblock = Block::newFromDB( '', $this->mId );
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
		wfDebug( "Autoblocking {$this->mName}@{$wgIP}\n" );
		$ipblock->mAddress = $wgIP;
		$ipblock->mUser = 0;
		$ipblock->mBy = $userblock->mBy;
		$ipblock->mReason = wfMsg( 'autoblocker', $this->getName(), $userblock->mReason );
		$ipblock->mTimestamp = wfTimestampNow();
		$ipblock->mAuto = 1;
		# If the user is already blocked with an expiry date, we don't
		# want to pile on top of that!
		if($userblock->mExpiry) {
			$ipblock->mExpiry = min ( $userblock->mExpiry, Block::getAutoblockExpiry( $ipblock->mTimestamp ));
		} else {
			$ipblock->mExpiry = Block::getAutoblockExpiry( $ipblock->mTimestamp );
		}

		# Insert it
		$ipblock->insert();

	}

	function getPageRenderingHash(){
		if( $this->mHash ){
			return $this->mHash;
		}

		// stubthreshold is only included below for completeness,
		// it will always be 0 when this function is called by parsercache.

		$confstr =        $this->getOption( 'math' );
		$confstr .= '!' . $this->getOption( 'highlightbroken' );
		$confstr .= '!' . $this->getOption( 'stubthreshold' );
		$confstr .= '!' . $this->getOption( 'editsection' );
		$confstr .= '!' . $this->getOption( 'editsectiononrightclick' );
		$confstr .= '!' . $this->getOption( 'showtoc' );
		$confstr .= '!' . $this->getOption( 'date' );
		$confstr .= '!' . $this->getOption( 'numberheadings' );

		$this->mHash = $confstr;
		return $confstr ;
	}

	function isAllowedToCreateAccount() {
		global $wgWhitelistAccount;
		$allowed = false;

		if (!$wgWhitelistAccount) { return 1; }; // default behaviour
		foreach ($wgWhitelistAccount as $right => $ok) {
			$userHasRight = (!strcmp($right, 'user') || in_array($right, $this->getRights()));
			$allowed |= ($ok && $userHasRight);
		}
		return $allowed;
	}

	# Set mDataLoaded, return previous value
	# Use this to prevent DB access in command-line scripts or similar situations
	function setLoaded( $loaded )
	{
		return wfSetVar( $this->mDataLoaded, $loaded );
	}

	function getUserPage() {
		return Title::makeTitle( NS_USER, $this->mName );
	}

	/* static */ function getMaxID() {
		$dbr =& wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'user', 'max(user_id)', false );
	}

	function isNewbie() {
		return $this->mId > User::getMaxID() * 0.99 && !$this->isSysop() && !$this->isBot() || $this->getID() == 0;
	}

	# Check to see if the given clear-text password is one of the accepted passwords
	function checkPassword( $password ) {
		$this->loadFromDatabase();
		$ep = $this->encryptPassword( $password );
		if ( 0 == strcmp( $ep, $this->mPassword ) ) {
			return true;
		} elseif ( 0 == strcmp( $ep, $this->mNewpassword ) ) {
			return true;
		} elseif ( function_exists( 'iconv' ) ) {
			# Some wikis were converted from ISO 8859-1 to UTF-8, the passwords can't be converted
			# Check for this with iconv
/*			$cp1252hash = $this->encryptPassword( iconv( 'UTF-8', 'WINDOWS-1252', $password ) );
			if ( 0 == strcmp( $cp1252hash, $this->mPassword ) ) {
				return true;
			}*/
		}
		return false;
	}
}

?>
