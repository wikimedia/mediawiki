<?php
/**
 * See user.doc
 *
 * @package MediaWiki
 */

/**
 *
 */
require_once( 'WatchedItem.php' );
require_once( 'Group.php' );

# Number of characters in user_token field
define( 'USER_TOKEN_LENGTH', 32 );

/**
 *
 * @package MediaWiki
 */
class User {
	/**#@+
	 * @access private
	 */
	var $mId, $mName, $mPassword, $mEmail, $mNewtalk;
	var $mEmailAuthenticationtimestamp;
	var $mRights, $mOptions;
	var $mDataLoaded, $mNewpassword;
	var $mSkin;
	var $mBlockedby, $mBlockreason;
	var $mTouched;
	var $mToken;
	var $mRealName;
	var $mHash;
	/** Array of group id the user belong to */
	var $mGroups;
	/**#@-*/

	/** Construct using User:loadDefaults() */
	function User()	{
		$this->loadDefaults();
	}

	/**
	 * Static factory method
	 * @static
	 * @param string $name Username, validated by Title:newFromText()
	 */
	function newFromName( $name ) {
		$u = new User();

		# Clean up name according to title rules

		$t = Title::newFromText( $name );
		if( is_null( $t ) ) {
			return NULL;
		} else {
			$u->setName( $t->getText() );
			$u->setId( $u->idFromName( $t->getText() ) );
			return $u;
		}
	}

	/**
	 * Get username given an id.
	 * @param integer $id Database user id
	 * @return string Nickname of a user
	 * @static
	 */
	function whoIs( $id )	{
		$dbr =& wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'user', 'user_name', array( 'user_id' => $id ) );
	}

	/**
	 * Get real username given an id.
	 * @param integer $id Database user id
	 * @return string Realname of a user
	 * @static
	 */
	function whoIsReal( $id )	{
		$dbr =& wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'user', 'user_real_name', array( 'user_id' => $id ) );
	}

	/**
	 * Get database id given a user name
	 * @param string $name Nickname of a user
	 * @return integer|null Database user id (null: if non existent
	 * @static
	 */
	function idFromName( $name ) {
		$fname = "User::idFromName";

		$nt = Title::newFromText( $name );
		if( is_null( $nt ) ) {
			# Illegal name
			return null;
		}
		$dbr =& wfGetDB( DB_SLAVE );
		$s = $dbr->selectRow( 'user', array( 'user_id' ), array( 'user_name' => $nt->getText() ), $fname );

		if ( $s === false ) {
			return 0;
		} else {
			return $s->user_id;
		}
	}

	/**
	 * does the string match an anonymous user IP address?
	 * @param string $name Nickname of a user
	 * @static
	 */
	function isIP( $name ) {
		return preg_match("/^\d{1,3}\.\d{1,3}.\d{1,3}\.\d{1,3}$/",$name);
	}

	/**
	 * does the string match roughly an email address ?
	 * @param string $addr email address
	 * @static
	 */
	function isValidEmailAddr ( $addr ) {
		return preg_match( '/^([a-z0-9_.-]+([a-z0-9_.-]+)*\@[a-z0-9_-]+([a-z0-9_.-]+)*([a-z.]{2,})+)$/', strtolower($addr));
	}

	/**
	 * probably return a random password
	 * @return string probably a random password
	 * @static
	 * @todo Check what is doing really [AV]
	 */
	function randomPassword() {
		$pwchars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
		$l = strlen( $pwchars ) - 1;

		$np = $pwchars{mt_rand( 0, $l )} . $pwchars{mt_rand( 0, $l )} .
		  $pwchars{mt_rand( 0, $l )} . chr( mt_rand(48, 57) ) .
		  $pwchars{mt_rand( 0, $l )} . $pwchars{mt_rand( 0, $l )} .
		  $pwchars{mt_rand( 0, $l )};
		return $np;
	}

	/**
	 * Set properties to default
	 * Used at construction. It will load per language default settings only
	 * if we have an available language object.
	 */
	function loadDefaults() {
		static $n=0;
		$n++;
		$fname = 'User::loadDefaults' . $n;
		wfProfileIn( $fname );
		
		global $wgContLang, $wgIP, $wgDBname;
		global $wgNamespacesToBeSearchedDefault;

		$this->mId = 0;
		$this->mNewtalk = -1;
		$this->mName = $wgIP;
		$this->mRealName = $this->mEmail = '';
		$this->mEmailAuthenticationtimestamp = 0;
		$this->mPassword = $this->mNewpassword = '';
		$this->mRights = array();
		$this->mGroups = array();
		// Getting user defaults only if we have an available language
		if( isset( $wgContLang ) ) {
			$this->loadDefaultFromLanguage();
		}
		
		foreach( $wgNamespacesToBeSearchedDefault as $nsnum => $val ) {
			$this->mOptions['searchNs'.$nsnum] = $val;
		}
		unset( $this->mSkin );
		$this->mDataLoaded = false;
		$this->mBlockedby = -1; # Unset
		$this->setToken(); # Random
		$this->mHash = false;

		if ( isset( $_COOKIE[$wgDBname.'LoggedOut'] ) ) {
			$this->mTouched = wfTimestamp( TS_MW, $_COOKIE[$wgDBname.'LoggedOut'] );
		}
		else {
			$this->mTouched = '0'; # Allow any pages to be cached
		}

		wfProfileOut( $fname );
	}

	/**
	 * Used to load user options from a language.
	 * This is not in loadDefault() cause we sometime create user before having
	 * a language object.
	 */	
	function loadDefaultFromLanguage(){
		$this->mOptions = User::getDefaultOptions();
	}
	
	/**
	 * Combine the language default options with any site-specific options
	 * and add the default language variants.
	 *
	 * @return array
	 * @static
	 * @access private
	 */
	function getDefaultOptions() {
		/**
		 * Site defaults will override the global/language defaults
		 */
		global $wgContLang, $wgDefaultUserOptions;
		$defOpt = $wgDefaultUserOptions + $wgContLang->getDefaultUserOptions();
		
		/**
		 * default language setting
		 */
		$variant = $wgContLang->getPreferredVariant();
		$defOpt['variant'] = $variant;
		$defOpt['language'] = $variant;
		
		return $defOpt;
	}
	
	/**
	 * Get a given default option value.
	 *
	 * @param string $opt
	 * @return string
	 * @static
	 * @access public
	 */
	function getDefaultOption( $opt ) {
		$defOpts = User::getDefaultOptions();
		if( isset( $defOpts[$opt] ) ) {
			return $defOpts[$opt];
		} else {
			return '';
		}
	}

	/**
	 * Get blocking information
	 * @access private
	 * @param bool $bFromSlave Specify whether to check slave or master. To improve performance,
	 *  non-critical checks are done against slaves. Check when actually saving should be done against
	 *  master.
	 *
	 * Note that even if $bFromSlave is false, the check is done first against slave, then master.
	 * The logic is that if blocked on slave, we'll assume it's either blocked on master or
	 * just slightly outta sync and soon corrected - safer to block slightly more that less.
	 * And it's cheaper to check slave first, then master if needed, than master always.
	 */
	function getBlockedStatus() {
		global $wgIP, $wgBlockCache, $wgProxyList, $wgEnableSorbs;

		if ( -1 != $this->mBlockedby ) { return; }

		$this->mBlockedby = 0;

		# User blocking
		if ( $this->mId ) {
			$block = new Block();
			$block->forUpdate( $bFromSlave );
 			if ( $block->load( $wgIP , $this->mId ) ) {
				$this->mBlockedby = $block->mBy;
				$this->mBlockreason = $block->mReason;
				$this->spreadBlock();
			}
		}

		# IP/range blocking
		if ( !$this->mBlockedby ) {
			# Check first against slave, and optionally from master.
			$block = $wgBlockCache->get( $wgIP, true );
			if ( !$block && !$bFromSlave )
				{
				# Not blocked: check against master, to make sure.
				$wgBlockCache->clearLocal( );
				$block = $wgBlockCache->get( $wgIP, false );
				}
			if ( $block !== false ) {
				$this->mBlockedby = $block->mBy;
				$this->mBlockreason = $block->mReason;
			}
		}

		# Proxy blocking
		if ( !$this->mBlockedby ) {
			if ( array_key_exists( $wgIP, $wgProxyList ) ) {
				$this->mBlockedby = wfMsg( 'proxyblocker' );
				$this->mBlockreason = wfMsg( 'proxyblockreason' );
			}
		}

		# DNSBL
		if ( !$this->mBlockedby && $wgEnableSorbs ) {
			if ( $this->inSorbsBlacklist( $wgIP ) ) {
				$this->mBlockedby = wfMsg( 'sorbs' );
				$this->mBlockreason = wfMsg( 'sorbsreason' );
			}
		}
			
	}

	function inSorbsBlacklist( $ip ) {
		$fname = 'User::inSorbsBlacklist';
		wfProfileIn( $fname );
		
		$found = false;
		$host = '';
		
		if ( preg_match( '/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/', $ip, $m ) ) {
			# Make hostname
			for ( $i=4; $i>=1; $i-- ) {
				$host .= $m[$i] . '.';
			}
			$host .= 'http.dnsbl.sorbs.net.';

			# Send query
			$ipList = gethostbynamel( $host );
			
			if ( $ipList ) {
				wfDebug( "Hostname $host is {$ipList[0]}, it's a proxy!\n" );
				$found = true;
			} else {
				wfDebug( "Requested $host, not found.\n" );
			}
		}

		wfProfileOut( $fname );
		return $found;
	}
	
	/**
	 * Check if user is blocked
	 * @return bool True if blocked, false otherwise
	 */
	function isBlocked( $bFromSlave = false ) {
		$this->getBlockedStatus( $bFromSlave );
		if ( 0 === $this->mBlockedby ) { return false; }
		return true;
	}
	
	/**
	 * Get name of blocker
	 * @return string name of blocker
	 */
	function blockedBy() {
		$this->getBlockedStatus();
		return $this->mBlockedby;
	}
	
	/**
	 * Get blocking reason
	 * @return string Blocking reason
	 */
	function blockedFor() {
		$this->getBlockedStatus();
		return $this->mBlockreason;
	}

	/**
	 * Initialise php session
	 */
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

	/**
	 * Read datas from session
	 * @static
	 */
	function loadFromSession() {
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

		if ( isset( $_SESSION['wsToken'] ) ) {
			$passwordCorrect = $_SESSION['wsToken'] == $user->mToken;
		} else if ( isset( $_COOKIE["{$wgDBname}Token"] ) ) {
			$passwordCorrect = $user->mToken == $_COOKIE["{$wgDBname}Token"];
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
			return $user;
		}
		return new User(); # Can't log in from session
	}

	/**
	 * Load a user from the database
	 */
	function loadFromDatabase() {
		global $wgCommandLineMode, $wgAnonGroupId, $wgLoggedInGroupId;
		$fname = "User::loadFromDatabase";
		
		# Counter-intuitive, breaks various things, use User::setLoaded() if you want to suppress 
		# loading in a command line script, don't assume all command line scripts need it like this
		#if ( $this->mDataLoaded || $wgCommandLineMode ) {
		if ( $this->mDataLoaded ) {
			return;
		}

		# Paranoia
		$this->mId = IntVal( $this->mId );

		/** Anonymous user */
		if(!$this->mId) {
			/** Get rights */
			$anong = Group::newFromId($wgAnonGroupId);
			if (!$anong) 
				wfDebugDieBacktrace("Please update your database schema "
					."and populate initial group data from "
					."maintenance/archives patches");
			$anong->loadFromDatabase();
			$this->mRights = explode(',', $anong->getRights());
			$this->mDataLoaded = true;
			return;
		} # the following stuff is for non-anonymous users only
		
		$dbr =& wfGetDB( DB_SLAVE );
		$s = $dbr->selectRow( 'user', array( 'user_name','user_password','user_newpassword','user_email',
		  'user_emailauthenticationtimestamp',
		  'user_real_name','user_options','user_touched', 'user_token' ),
		  array( 'user_id' => $this->mId ), $fname );
		
		if ( $s !== false ) {
			$this->mName = $s->user_name;
			$this->mEmail = $s->user_email;
			$this->mEmailAuthenticationtimestamp = wfTimestamp(TS_MW,$s->user_emailauthenticationtimestamp);
			$this->mRealName = $s->user_real_name;
			$this->mPassword = $s->user_password;
			$this->mNewpassword = $s->user_newpassword;
			$this->decodeOptions( $s->user_options );
			$this->mTouched = wfTimestamp(TS_MW,$s->user_touched);
			$this->mToken = $s->user_token;

			// Get groups id
			$res = $dbr->select( 'user_groups', array( 'ug_group' ), array( 'ug_user' => $this->mId ) );

			while($group = $dbr->fetchRow($res)) {
				$this->mGroups[] = $group[0];
			}

			// add the default group for logged in user
			$this->mGroups[] = $wgLoggedInGroupId;

			$this->mRights = array();
			// now we merge groups rights to get this user rights
			foreach($this->mGroups as $aGroupId) {
				$g = Group::newFromId($aGroupId);
				$g->loadFromDatabase();
				$this->mRights = array_merge($this->mRights, explode(',', $g->getRights()));
			}
			
			// array merge duplicate rights which are part of several groups
			$this->mRights = array_unique($this->mRights);
			
			$dbr->freeResult($res);
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

	
	/**
	 * Return the title dbkey form of the name, for eg user pages.
	 * @return string
	 * @access public
	 */
	function getTitleKey() {
		return str_replace( ' ', '_', $this->getName() );
	}
	
	function getNewtalk() {
		$fname = 'User::getNewtalk';
		$this->loadFromDatabase();
		
		# Load the newtalk status if it is unloaded (mNewtalk=-1)
		if( $this->mNewtalk == -1 ) {
			$this->mNewtalk = 0; # reset talk page status

			# Check memcached separately for anons, who have no
			# entire User object stored in there.
			if( !$this->mId ) {
				global $wgDBname, $wgMemc;
				$key = "$wgDBname:newtalk:ip:{$this->mName}";
				$newtalk = $wgMemc->get( $key );
				if( is_integer( $newtalk ) ) {
					$this->mNewtalk = $newtalk ? 1 : 0;
					return (bool)$this->mNewtalk;
				}
			}
			
			$dbr =& wfGetDB( DB_SLAVE );
			$res = $dbr->select( 'watchlist',
				array( 'wl_user' ),
				array( 'wl_title'     => $this->getTitleKey(),
					   'wl_namespace' => NS_USER_TALK,
					   'wl_user'      => $this->mId,
					   'wl_notificationtimestamp != 0' ),
				'User::getNewtalk' );
			if( $dbr->numRows($res) > 0 ) {
				$this->mNewtalk = 1;
			}
			$dbr->freeResult( $res );
			
			if( !$this->mId ) {
				$wgMemc->set( $key, $this->mNewtalk, time() ); // + 1800 );
			}
		}

		return ( 0 != $this->mNewtalk );
	}

	function setNewtalk( $val ) {
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

	/**
	 * Salt a password.
	 * Will only be salted if $wgPasswordSalt is true
	 * @param string Password.
	 * @return string Salted password or clear password.
	 */
	function addSalt( $p ) {
		global $wgPasswordSalt;
		if($wgPasswordSalt)
			return md5( "{$this->mId}-{$p}" );
		else
			return $p;
	}

	/**
	 * Encrypt a password.
	 * It can eventuall salt a password @see User::addSalt()
	 * @param string $p clear Password.
	 * @param string Encrypted password.
	 */
	function encryptPassword( $p ) {
		return $this->addSalt( md5( $p ) );
	}

	# Set the password and reset the random token
	function setPassword( $str ) {
		$this->loadFromDatabase();
		$this->setToken();
		$this->mPassword = $this->encryptPassword( $str );
		$this->mNewpassword = '';
	}

	# Set the random token (used for persistent authentication)
	function setToken( $token = false ) {
		global $wgSecretKey, $wgProxyKey, $wgDBname;
		if ( !$token ) {
			if ( $wgSecretKey ) {
				$key = $wgSecretKey;
			} elseif ( $wgProxyKey ) {
				$key = $wgProxyKey;
			} else {
				$key = microtime();
			}
			$this->mToken = md5( $wgSecretKey . mt_rand( 0, 0x7fffffff ) . $wgDBname . $this->mId );
		} else {
			$this->mToken = $token;
		}
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

	function getEmailAuthenticationtimestamp() {
		$this->loadFromDatabase();
		return $this->mEmailAuthenticationtimestamp;
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

	function getGroups() {
		$this->loadFromDatabase();
		return $this->mGroups;
	}

	function setGroups($groups) {
		$this->loadFromDatabase();
		$this->mGroups = $groups;
		$this->invalidateCache();
	}

	/**
	 * A more legible check for non-anonymousness.
	 * Returns true if the user is not an anonymous visitor.
	 *
	 * @return bool
	 */
	function isLoggedIn() {
		return( $this->getID() != 0 );
	}
	
	/**
	 * A more legible check for anonymousness.
	 * Returns true if the user is an anonymous visitor.
	 *
	 * @return bool
	 */
	function isAnon() {
		return !$this->isLoggedIn();
	}
	
	/**
	 * Check if a user is sysop
	 * Die with backtrace. Use User:isAllowed() instead.
	 * @deprecated
	 */
	function isSysop() {
		wfDebugDieBacktrace("User::isSysop() is deprecated. Use User::isAllowed() instead");
	}

	/** @deprecated */
	function isDeveloper() {
		wfDebugDieBacktrace("User::isDeveloper() is deprecated. Use User::isAllowed() instead");
	}

	/** @deprecated */
	function isBureaucrat() {
		wfDebugDieBacktrace("User::isBureaucrat() is deprecated. Use User::isAllowed() instead");
	}

	/**
	 * Whether the user is a bot
	 * @todo need to be migrated to the new user level management sytem
	 */
	function isBot() {
		$this->loadFromDatabase();

		# Why was this here? I need a UID=0 conversion script [TS]
		# if ( 0 == $this->mId ) { return false; }

		return in_array( 'bot', $this->mRights );
	}

	/**
	 * Check if user is allowed to access a feature / make an action
	 * @param string $action Action to be checked (see $wgAvailableRights in Defines.php for possible actions).
	 * @return boolean True: action is allowed, False: action should not be allowed
	 */
	function isAllowed($action='') {
		$this->loadFromDatabase();
		return in_array( $action , $this->mRights );
	}

	/**
	 * Load a skin if it doesn't exist or return it
	 * @todo FIXME : need to check the old failback system [AV]
	 */
	function &getSkin() {
		global $IP;
		if ( ! isset( $this->mSkin ) ) {
			$fname = 'User::getSkin';
			wfProfileIn( $fname );
			
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
			$className = 'Skin'.$sn;
			if( !class_exists( $className ) ) {
				# DO NOT die if the class isn't found. This breaks maintenance
				# scripts and can cause a user account to be unrecoverable
				# except by SQL manipulation if a previously valid skin name
				# is no longer valid.
				$className = 'SkinStandard';
				require_once( $IP.'/skins/Standard.php' );
			}
			$this->mSkin =& new $className;
			wfProfileOut( $fname );
		}
		return $this->mSkin;
	}

	/**#@+
	 * @param string $title Article title to look at
	 */
	
	/**
	 * Check watched status of an article
	 * @return bool True if article is watched
	 */
	function isWatched( $title ) {
		$wl = WatchedItem::fromUserTitle( $this, $title );
		return $wl->isWatched();
	}

	/**
	 * Watch an article
	 */
	function addWatch( $title ) {
		$wl = WatchedItem::fromUserTitle( $this, $title );
		$wl->addWatch();
		$this->invalidateCache();
	}

	/**
	 * Stop watching an article
	 */
	function removeWatch( $title ) {
		$wl = WatchedItem::fromUserTitle( $this, $title );
		$wl->removeWatch();
		$this->invalidateCache();
	}

	/**
	 * Clear the user's notification timestamp for the given title.
	 * If e-notif e-mails are on, they will receive notification mails on
	 * the next change of the page if it's watched etc.
	 */
	function clearNotification( $title ) {
		$userid = $this->getId();
		if ($userid==0)
			return;
		$dbw =& wfGetDB( DB_MASTER );
		$success = $dbw->update( 'watchlist',
				array( /* SET */
					'wl_notificationtimestamp' => 0
				), array( /* WHERE */
					'wl_title' => $title->getDBkey(),
					'wl_namespace' => $title->getNamespace(),
					'wl_user' => $this->getId()
				), 'User::clearLastVisited'
		);
	}
	
	/**#@-*/

	/**
	 * Resets all of the given user's page-change notification timestamps.
	 * If e-notif e-mails are on, they will receive notification mails on
	 * the next change of any watched page.
	 *
	 * @param int $currentUser user ID number
	 * @access public
	 */
	function clearAllNotifications( $currentUser ) {
		if( $currentUser != 0 )  {
	
			$dbw =& wfGetDB( DB_MASTER );
			$success = $dbw->update( 'watchlist',
				array( /* SET */
					'wl_notificationtimestamp' => 0
				), array( /* WHERE */
					'wl_user' => $currentUser
				), 'UserMailer::clearAll'
			);

		# 	we also need to clear here the "you have new message" notification for the own user_talk page
		#	This is cleared one page view later in Article::viewUpdates();
		}
	}

	/**
	 * @access private
	 * @return string Encoding options
	 */
	function encodeOptions() {
		$a = array();
		foreach ( $this->mOptions as $oname => $oval ) {
			array_push( $a, $oname.'='.$oval );
		}
		$s = implode( "\n", $a );
		return $s;
	}

	/**
	 * @access private
	 */
	function decodeOptions( $str ) {
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

		$_SESSION['wsToken'] = $this->mToken;
		if ( 1 == $this->getOption( 'rememberpassword' ) ) {
			setcookie( $wgDBname.'Token', $this->mToken, $exp, $wgCookiePath, $wgCookieDomain );
		} else {
			setcookie( $wgDBname.'Token', '', time() - 3600 );
		}
	}

	/**
	 * Logout user
	 * It will clean the session cookie
	 */
	function logout() {
		global $wgCookiePath, $wgCookieDomain, $wgDBname, $wgIP;
		$this->loadDefaults();
		$this->setLoaded( true );

		$_SESSION['wsUserID'] = 0;

		setcookie( $wgDBname.'UserID', '', time() - 3600, $wgCookiePath, $wgCookieDomain );
		setcookie( $wgDBname.'Token', '', time() - 3600, $wgCookiePath, $wgCookieDomain );

		# Remember when user logged out, to prevent seeing cached pages
		setcookie( $wgDBname.'LoggedOut', wfTimestampNow(), time() + 86400, $wgCookiePath, $wgCookieDomain );
	}

	/**
	 * Save object settings into database
	 */
	function saveSettings() {
		global $wgMemc, $wgDBname;
		$fname = 'User::saveSettings';

		$dbw =& wfGetDB( DB_MASTER );
		if ( ! $this->getNewtalk() ) {
			# Delete the watchlist entry for user_talk page X watched by user X
			$dbw->delete( 'watchlist',
				array( 'wl_user'      => $this->mId,
					   'wl_title'     => $this->getTitleKey(),
					   'wl_namespace' => NS_USER_TALK ),
				$fname );
			if( !$this->mId ) {
				# Anon users have a separate memcache space for newtalk
				# since they don't store their own info. Trim...
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
		 		'user_emailauthenticationtimestamp' => $dbw->timestamp($this->mEmailAuthenticationtimestamp),
				'user_options' => $this->encodeOptions(),
				'user_touched' => $dbw->timestamp($this->mTouched),
				'user_token' => $this->mToken
			), array( /* WHERE */
				'user_id' => $this->mId
			), $fname
		);
		$dbw->set( 'user_rights', 'ur_rights', implode( ',', $this->mRights ),
			'ur_user='. $this->mId, $fname ); 
		$wgMemc->delete( "$wgDBname:user:id:$this->mId" );
		
		// delete old groups
		$dbw->delete( 'user_groups', array( 'ug_user' => $this->mId), $fname);
		
		// save new ones
		foreach ($this->mGroups as $group) {
			$dbw->replace( 'user_groups',
				array(array('ug_user','ug_group')),
				array(
					'ug_user' => $this->mId,
					'ug_group' => $group
				), $fname
			);
		}
	}

	
	/**
	 * Checks if a user with the given name exists, returns the ID
	 */
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

	/**
	 * Add user object to the database
	 */
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
				'user_emailauthenticationtimestamp' => $dbw->timestamp($this->mEmailAuthenticationtimestamp),
				'user_real_name' => $this->mRealName,
				'user_options' => $this->encodeOptions(),
				'user_token' => $this->mToken
			), $fname
		);
		$this->mId = $dbw->insertId();
		$dbw->insert( 'user_rights',
			array(
				'ur_user' => $this->mId,
				'ur_rights' => implode( ',', $this->mRights )
			), $fname
		);
		
		foreach ($this->mGroups as $group) {
			$dbw->insert( 'user_groups',
				array(
					'ug_user' => $this->mId,
					'ug_group' => $group
				), $fname
			);
		}
	}

	function spreadBlock() {
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

	function getPageRenderingHash() {
		global $wgContLang;
		if( $this->mHash ){
			return $this->mHash;
		}

		// stubthreshold is only included below for completeness,
		// it will always be 0 when this function is called by parsercache.

		$confstr =        $this->getOption( 'math' );
		$confstr .= '!' . $this->getOption( 'stubthreshold' );
		$confstr .= '!' . $this->getOption( 'editsection' );
		$confstr .= '!' . $this->getOption( 'date' );
		$confstr .= '!' . $this->getOption( 'numberheadings' );
		$confstr .= '!' . $this->getOption( 'language' );
		// add in language specific options, if any
		$extra = $wgContLang->getExtraHashOptions();
		$confstr .= $extra;

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

	/**
	 * Set mDataLoaded, return previous value
	 * Use this to prevent DB access in command-line scripts or similar situations
	 */
	function setLoaded( $loaded ) {
		return wfSetVar( $this->mDataLoaded, $loaded );
	}

	/**
	 * Get this user's personal page title.
	 *
	 * @return Title
	 * @access public
	 */
	function getUserPage() {
		return Title::makeTitle( NS_USER, $this->mName );
	}
	
	/**
	 * Get this user's talk page title.
	 *
	 * @return Title
	 * @access public
	 */
	function getTalkPage() {
		$title = $this->getUserPage();
		return $title->getTalkPage();
	}

	/**
	 * @static
	 */
	function getMaxID() {
		$dbr =& wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'user', 'max(user_id)', false );
	}

	/**
	 * Determine whether the user is a newbie. Newbies are either
	 * anonymous IPs, or the 1% most recently created accounts.
	 * Bots and sysops are excluded.
	 * @return bool True if it is a newbie.
	 */
	function isNewbie() {
		return $this->mId > User::getMaxID() * 0.99 && !$this->isSysop() && !$this->isBot() || $this->getID() == 0;
	}

	/**
	 * Check to see if the given clear-text password is one of the accepted passwords
	 * @param string $password User password.
	 * @return bool True if the given password is correct otherwise False.
	 */
	function checkPassword( $password ) {
		global $wgAuth;
		$this->loadFromDatabase();
		
		if( $wgAuth->authenticate( $this->getName(), $password ) ) {
			return true;
		} elseif( $wgAuth->strict() ) {
			/* Auth plugin doesn't allow local authentication */
			return false;
		}
		$ep = $this->encryptPassword( $password );
		if ( 0 == strcmp( $ep, $this->mPassword ) ) {
			return true;
		} elseif ( ($this->mNewpassword != '') && (0 == strcmp( $ep, $this->mNewpassword )) ) {
			$this->mEmailAuthenticationtimestamp = wfTimestampNow();
			$this->mNewpassword = ''; # use the temporary one-time password only once: clear it now !
			$this->saveSettings();
			return true;
		} elseif ( function_exists( 'iconv' ) ) {
			# Some wikis were converted from ISO 8859-1 to UTF-8, the passwords can't be converted
			# Check for this with iconv
			$cp1252hash = $this->encryptPassword( iconv( 'UTF-8', 'WINDOWS-1252', $password ) );
			if ( 0 == strcmp( $cp1252hash, $this->mPassword ) ) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Initialize (if necessary) and return a session token value
	 * which can be used in edit forms to show that the user's
	 * login credentials aren't being hijacked with a foreign form
	 * submission.
	 *
	 * @param mixed $salt - Optional function-specific data for hash.
	 *                      Use a string or an array of strings.
	 * @return string
	 * @access public
	 */
	function editToken( $salt = '' ) {
		if( !isset( $_SESSION['wsEditToken'] ) ) {
			$token = dechex( mt_rand() ) . dechex( mt_rand() );
			$_SESSION['wsEditToken'] = $token;
		} else {
			$token = $_SESSION['wsEditToken'];
		}
		if( is_array( $salt ) ) {
			$salt = implode( '|', $salt );
		}
		return md5( $token . $salt );
	}
	
	/**
	 * Check given value against the token value stored in the session.
	 * A match should confirm that the form was submitted from the
	 * user's own login session, not a form submission from a third-party
	 * site.
	 *
	 * @param string $val - the input value to compare
	 * @param string $salt - Optional function-specific data for hash
	 * @return bool
	 * @access public
	 */
	function matchEditToken( $val, $salt = '' ) {
		return ( $val == $this->editToken( $salt ) );
	}
}

?>
