<?php
/**
 * See user.txt
 *
 * @package MediaWiki
 */

/**
 *
 */
require_once( 'WatchedItem.php' );

# Number of characters in user_token field
define( 'USER_TOKEN_LENGTH', 32 );

# Serialized record version
define( 'MW_USER_VERSION', 2 );

/**
 *
 * @package MediaWiki
 */
class User {
	/**#@+
	 * @access private
	 */
	var $mId, $mName, $mPassword, $mEmail, $mNewtalk;
	var $mEmailAuthenticated;
	var $mRights, $mOptions;
	var $mDataLoaded, $mNewpassword;
	var $mSkin;
	var $mBlockedby, $mBlockreason;
	var $mTouched;
	var $mToken;
	var $mRealName;
	var $mHash;
	var $mGroups;
	var $mVersion; // serialized version

	/** Construct using User:loadDefaults() */
	function User()	{
		$this->loadDefaults();
		$this->mVersion = MW_USER_VERSION;
	}

	/**
	 * Static factory method
	 * @param string $name Username, validated by Title:newFromText()
	 * @return User
	 * @static
	 */
	function newFromName( $name ) {
		# Force usernames to capital
		global $wgContLang;
		$name = $wgContLang->ucfirst( $name );

		# Clean up name according to title rules
		$t = Title::newFromText( $name );
		if( is_null( $t ) ) {
			return null;
		}

		# Reject various classes of invalid names
		$canonicalName = $t->getText();
		global $wgAuth;
		$canonicalName = $wgAuth->getCanonicalName( $t->getText() );

		if( !User::isValidUserName( $canonicalName ) ) {
			return null;
		}

		$u = new User();
		$u->setName( $canonicalName );
		$u->setId( $u->idFromName( $canonicalName ) );
		return $u;
	}

	/**
	 * Factory method to fetch whichever use has a given email confirmation code.
	 * This code is generated when an account is created or its e-mail address
	 * has changed.
	 *
	 * If the code is invalid or has expired, returns NULL.
	 *
	 * @param string $code
	 * @return User
	 * @static
	 */
	function newFromConfirmationCode( $code ) {
		$dbr =& wfGetDB( DB_SLAVE );
		$name = $dbr->selectField( 'user', 'user_name', array(
			'user_email_token' => md5( $code ),
			'user_email_token_expires > ' . $dbr->addQuotes( $dbr->timestamp() ),
			) );
		if( is_string( $name ) ) {
			return User::newFromName( $name );
		} else {
			return null;
		}
	}

	/**
	 * Serialze sleep function, for better cache efficiency and avoidance of
	 * silly "incomplete type" errors when skins are cached
	 */
	function __sleep() {
		return array( 'mId', 'mName', 'mPassword', 'mEmail', 'mNewtalk',
			'mEmailAuthenticated', 'mRights', 'mOptions', 'mDataLoaded',
			'mNewpassword', 'mBlockedby', 'mBlockreason', 'mTouched',
			'mToken', 'mRealName', 'mHash', 'mGroups' );
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
	 * does the string match an anonymous IPv4 address?
	 *
	 * @static
	 * @param string $name Nickname of a user
	 * @return bool
	 */
	function isIP( $name ) {
		return preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/",$name);
		/*return preg_match("/^
			(?:[01]?\d{1,2}|2(:?[0-4]\d|5[0-5]))\.
			(?:[01]?\d{1,2}|2(:?[0-4]\d|5[0-5]))\.
			(?:[01]?\d{1,2}|2(:?[0-4]\d|5[0-5]))\.
			(?:[01]?\d{1,2}|2(:?[0-4]\d|5[0-5]))
		$/x", $name);*/
	}

	/**
	 * Is the input a valid username?
	 *
	 * Checks if the input is a valid username, we don't want an empty string,
	 * an IP address, anything that containins slashes (would mess up subpages),
	 * is longer than the maximum allowed username size or doesn't begin with
	 * a capital letter.
	 *
	 * @param string $name
	 * @return bool
	 * @static
	 */
	function isValidUserName( $name ) {
		global $wgContLang, $wgMaxNameChars;

		if ( $name == ''
		|| User::isIP( $name )
		|| strpos( $name, '/' ) !== false
		|| strlen( $name ) > $wgMaxNameChars
		|| $name != $wgContLang->ucfirst( $name ) )
			return false;
		else
			return true;
	}

	/**
	 * Is the input a valid password?
	 *
	 * @param string $password
	 * @return bool
	 * @static
	 */
	function isValidPassword( $password ) {
		global $wgMinimalPasswordLength;
		return strlen( $password ) >= $wgMinimalPasswordLength;
	}

	/**
	 * does the string match roughly an email address ?
	 *
	 * @todo Check for RFC 2822 compilance
	 * @bug 959
	 *
	 * @param string $addr email address
	 * @static
	 * @return bool
	 */
	function isValidEmailAddr ( $addr ) {
		# There used to be a regular expression here, it got removed because it
		# rejected valid addresses.
		return ( trim( $addr ) != '' ) &&
			(false !== strpos( $addr, '@' ) );
	}

	/**
	 * Count the number of edits of a user
	 *
	 * @param int $uid The user ID to check
	 * @return int
	 */
	function edits( $uid ) {
		$fname = 'User::editCount';

		$dbr =& wfGetDB( DB_SLAVE );
		return $dbr->selectField(
			'revision', 'count(*)',
			array( 'rev_user' => $uid ),
			$fname
		);
	}

	/**
	 * probably return a random password
	 * @return string probably a random password
	 * @static
	 * @todo Check what is doing really [AV]
	 */
	function randomPassword() {
		global $wgMinimalPasswordLength;
		$pwchars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
		$l = strlen( $pwchars ) - 1;

		$pwlength = max( 7, $wgMinimalPasswordLength );
		$digit = mt_rand(0, $pwlength - 1);
		$np = '';
		for ( $i = 0; $i < $pwlength; $i++ ) {
			$np .= $i == $digit ? chr( mt_rand(48, 57) ) : $pwchars{ mt_rand(0, $l)};
		}
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
		$this->mEmailAuthenticated = null;
		$this->mPassword = $this->mNewpassword = '';
		$this->mRights = array();
		$this->mGroups = array();
		$this->mOptions = User::getDefaultOptions();

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
	function getBlockedStatus( $bFromSlave = true ) {
		global $wgIP, $wgBlockCache, $wgProxyList, $wgEnableSorbs, $wgProxyWhitelist;

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
		if ( !$this->isSysop() && !in_array( $wgIP, $wgProxyWhitelist ) ) {

			# Local list
			if ( array_key_exists( $wgIP, $wgProxyList ) ) {
				$this->mBlockedby = wfMsg( 'proxyblocker' );
				$this->mBlockreason = wfMsg( 'proxyblockreason' );
			}

			# DNSBL
			if ( !$this->mBlockedby && $wgEnableSorbs && !$this->getID() ) {
				if ( $this->inSorbsBlacklist( $wgIP ) ) {
					$this->mBlockedby = wfMsg( 'sorbs' );
					$this->mBlockreason = wfMsg( 'sorbsreason' );
				}
			}
		}
	}

	function inSorbsBlacklist( $ip ) {
		global $wgEnableSorbs;
		return $wgEnableSorbs &&
			$this->inDnsBlacklist( $ip, 'http.dnsbl.sorbs.net.' );
	}

	function inOpmBlacklist( $ip ) {
		global $wgEnableOpm;
		return $wgEnableOpm &&
			$this->inDnsBlacklist( $ip, 'opm.blitzed.org.' );
	}

	function inDnsBlacklist( $ip, $base ) {
		$fname = 'User::inDnsBlacklist';
		wfProfileIn( $fname );

		$found = false;
		$host = '';

		if ( preg_match( '/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/', $ip, $m ) ) {
			# Make hostname
			for ( $i=4; $i>=1; $i-- ) {
				$host .= $m[$i] . '.';
			}
			$host .= $base;

			# Send query
			$ipList = gethostbynamel( $host );

			if ( $ipList ) {
				wfDebug( "Hostname $host is {$ipList[0]}, it's a proxy says $base!\n" );
				$found = true;
			} else {
				wfDebug( "Requested $host, not found in $base.\n" );
			}
		}

		wfProfileOut( $fname );
		return $found;
	}

	/**
	 * Primitive rate limits: enforce maximum actions per time period
	 * to put a brake on flooding.
	 *
	 * Note: when using a shared cache like memcached, IP-address
	 * last-hit counters will be shared across wikis.
	 *
	 * @return bool true if a rate limiter was tripped
	 * @access public
	 */
	function pingLimiter( $action='edit' ) {
		global $wgRateLimits;
		if( !isset( $wgRateLimits[$action] ) ) {
			return false;
		}
		if( $this->isAllowed( 'delete' ) ) {
			// goddam cabal
			return false;
		}

		global $wgMemc, $wgIP, $wgDBname, $wgRateLimitLog;
		$fname = 'User::pingLimiter';
		$limits = $wgRateLimits[$action];
		$keys = array();
		$id = $this->getId();

		if( isset( $limits['anon'] ) && $id == 0 ) {
			$keys["$wgDBname:limiter:$action:anon"] = $limits['anon'];
		}

		if( isset( $limits['user'] ) && $id != 0 ) {
			$keys["$wgDBname:limiter:$action:user:$id"] = $limits['user'];
		}
		if( $this->isNewbie() ) {
			if( isset( $limits['newbie'] ) && $id != 0 ) {
				$keys["$wgDBname:limiter:$action:user:$id"] = $limits['newbie'];
			}
			if( isset( $limits['ip'] ) ) {
				$keys["mediawiki:limiter:$action:ip:$wgIP"] = $limits['ip'];
			}
			if( isset( $limits['subnet'] ) && preg_match( '/^(\d+\.\d+\.\d+)\.\d+$/', $wgIP, $matches ) ) {
				$subnet = $matches[1];
				$keys["mediawiki:limiter:$action:subnet:$subnet"] = $limits['subnet'];
			}
		}

		$triggered = false;
		foreach( $keys as $key => $limit ) {
			list( $max, $period ) = $limit;
			$summary = "(limit $max in {$period}s)";
			$count = $wgMemc->get( $key );
			if( $count ) {
				if( $count > $max ) {
					wfDebug( "$fname: tripped! $key at $count $summary\n" );
					if( $wgRateLimitLog ) {
						@error_log( wfTimestamp( TS_MW ) . ' ' . $wgDBname . ': ' . $this->getName() . " tripped $key at $count $summary\n", 3, $wgRateLimitLog );
					}
					$triggered = true;
				} else {
					wfDebug( "$fname: ok. $key at $count $summary\n" );
				}
			} else {
				wfDebug( "$fname: adding record for $key $summary\n" );
				$wgMemc->add( $key, 1, IntVal( $period ) );
			}
			$wgMemc->incr( $key );
		}

		return $triggered;
	}

	/**
	 * Check if user is blocked
	 * @return bool True if blocked, false otherwise
	 */
	function isBlocked( $bFromSlave = true ) { // hacked from false due to horrible probs on site
		$this->getBlockedStatus( $bFromSlave );
		return $this->mBlockedby !== 0;
	}

	/**
	 * Check if user is blocked from editing a particular article
	 */
	function isBlockedFrom( $title, $bFromSlave = false ) {
		global $wgBlockAllowsUTEdit;
		if ( $wgBlockAllowsUTEdit && $title->getText() === $this->getName() &&
		  $title->getNamespace() == NS_USER_TALK )
		{
			return false;
		} else {
			return $this->isBlocked( $bFromSlave );
		}
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
		if( !is_object( $user ) || $user->mVersion < MW_USER_VERSION ) {
			# Expire old serialized objects; they may be corrupt.
			$user = false;
		}
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
		global $wgCommandLineMode;
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
		if( !$this->mId ) {
			/** Get rights */
			$this->mRights = $this->getGroupPermissions( array( '*' ) );
			$this->mDataLoaded = true;
			return;
		} # the following stuff is for non-anonymous users only

		$dbr =& wfGetDB( DB_SLAVE );
		$s = $dbr->selectRow( 'user', array( 'user_name','user_password','user_newpassword','user_email',
		  'user_email_authenticated',
		  'user_real_name','user_options','user_touched', 'user_token' ),
		  array( 'user_id' => $this->mId ), $fname );

		if ( $s !== false ) {
			$this->mName = $s->user_name;
			$this->mEmail = $s->user_email;
			$this->mEmailAuthenticated = wfTimestampOrNull( TS_MW, $s->user_email_authenticated );
			$this->mRealName = $s->user_real_name;
			$this->mPassword = $s->user_password;
			$this->mNewpassword = $s->user_newpassword;
			$this->decodeOptions( $s->user_options );
			$this->mTouched = wfTimestamp(TS_MW,$s->user_touched);
			$this->mToken = $s->user_token;

			$res = $dbr->select( 'user_groups',
				array( 'ug_group' ),
				array( 'ug_user' => $this->mId ),
				$fname );
			$this->mGroups = array();
			while( $row = $dbr->fetchObject( $res ) ) {
				$this->mGroups[] = $row->ug_group;
			}
			$effectiveGroups = array_merge( array( '*', 'user' ), $this->mGroups );
			$this->mRights = $this->getGroupPermissions( $effectiveGroups );
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
		global $wgUseEnotif;
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
			if ( $wgUseEnotif ) {
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
			} elseif ( $this->mId ) {
				$res = $dbr->select( 'user_newtalk', 1, array( 'user_id' => $this->mId ), $fname );

				if ( $dbr->numRows($res)>0 ) {
					$this->mNewtalk= 1;
				}
				$dbr->freeResult( $res );
			} else {
				$res = $dbr->select( 'user_newtalk', 1, array( 'user_ip' => $this->mName ), $fname );
				$this->mNewtalk = $dbr->numRows( $res ) > 0 ? 1 : 0;
				$dbr->freeResult( $res );
			}

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
		global $wgClockSkewFudge;
		$this->loadFromDatabase();
		$this->mTouched = wfTimestamp(TS_MW, time() + $wgClockSkewFudge );
		# Don't forget to save the options after this or
		# it won't take effect!
	}

	function validateCache( $timestamp ) {
		$this->loadFromDatabase();
		return ($timestamp >= $this->mTouched);
	}

	/**
	 * Encrypt a password.
	 * It can eventuall salt a password @see User::addSalt()
	 * @param string $p clear Password.
	 * @return string Encrypted password.
	 */
	function encryptPassword( $p ) {
		return wfEncryptPassword( $this->mId, $p );
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
			$this->mToken = md5( $key . mt_rand( 0, 0x7fffffff ) . $wgDBname . $this->mId );
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

	function getEmailAuthenticationTimestamp() {
		$this->loadFromDatabase();
		return $this->mEmailAuthenticated;
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
			return trim( $this->mOptions[$oname] );
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

	/**
	 * Get the list of explicit group memberships this user has.
	 * The implicit * and user groups are not included.
	 * @return array of strings
	 */
	function getGroups() {
		$this->loadFromDatabase();
		return $this->mGroups;
	}

	/**
	 * Get the list of implicit group memberships this user has.
	 * This includes all explicit groups, plus 'user' if logged in
	 * and '*' for all accounts.
	 * @return array of strings
	 */
	function getEffectiveGroups() {
		$base = array( '*' );
		if( $this->isLoggedIn() ) {
			$base[] = 'user';
		}
		return array_merge( $base, $this->getGroups() );
	}

	/**
	 * Remove the user from the given group.
	 * This takes immediate effect.
	 * @string $group
	 */
	function addGroup( $group ) {
		$dbw =& wfGetDB( DB_MASTER );
		$dbw->insert( 'user_groups',
			array(
				'ug_user'  => $this->getID(),
				'ug_group' => $group,
			),
			'User::addGroup',
			array( 'IGNORE' ) );

		$this->mGroups = array_merge( $this->mGroups, array( $group ) );
		$this->mRights = User::getGroupPermissions( $this->getEffectiveGroups() );

		$this->invalidateCache();
		$this->saveSettings();
	}

	/**
	 * Remove the user from the given group.
	 * This takes immediate effect.
	 * @string $group
	 */
	function removeGroup( $group ) {
		$dbw =& wfGetDB( DB_MASTER );
		$dbw->delete( 'user_groups',
			array(
				'ug_user'  => $this->getID(),
				'ug_group' => $group,
			),
			'User::removeGroup' );

		$this->mGroups = array_diff( $this->mGroups, array( $group ) );
		$this->mRights = User::getGroupPermissions( $this->getEffectiveGroups() );

		$this->invalidateCache();
		$this->saveSettings();
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
		return $this->isAllowed( 'protect' );
	}

	/** @deprecated */
	function isDeveloper() {
		return $this->isAllowed( 'siteadmin' );
	}

	/** @deprecated */
	function isBureaucrat() {
		return $this->isAllowed( 'makesysop' );
	}

	/**
	 * Whether the user is a bot
	 * @todo need to be migrated to the new user level management sytem
	 */
	function isBot() {
		$this->loadFromDatabase();
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
		global $IP, $wgRequest;
		if ( ! isset( $this->mSkin ) ) {
			$fname = 'User::getSkin';
			wfProfileIn( $fname );

			# get all skin names available
			$skinNames = Skin::getSkinNames();

			# get the user skin
			$userSkin = $this->getOption( 'skin' );
			$userSkin = $wgRequest->getText('useskin', $userSkin);
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
	function clearNotification( &$title ) {
		global $wgUser, $wgUseEnotif;

		if ( !$wgUseEnotif ) {
			return;
		}

		$userid = $this->getID();
		if ($userid==0) {
			return;
		}

		// Only update the timestamp if the page is being watched.
		// The query to find out if it is watched is cached both in memcached and per-invocation,
		// and when it does have to be executed, it can be on a slave
		// If this is the user's newtalk page, we always update the timestamp
		if ($title->getNamespace() == NS_USER_TALK &&
			$title->getText() == $wgUser->getName())
		{
			$watched = true;
		} elseif ( $this->getID() == $wgUser->getID() ) {
			$watched = $title->userIsWatching();
		} else {
			$watched = true;
		}

		// If the page is watched by the user (or may be watched), update the timestamp on any
		// any matching rows
		if ( $watched ) {
			$dbw =& wfGetDB( DB_MASTER );
			$success = $dbw->update( 'watchlist',
					array( /* SET */
						'wl_notificationtimestamp' => 0
					), array( /* WHERE */
						'wl_title' => $title->getDBkey(),
						'wl_namespace' => $title->getNamespace(),
						'wl_user' => $this->getID()
					), 'User::clearLastVisited'
			);
		}
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
		global $wgUseEnotif;
		if ( !$wgUseEnotif ) {
			return;
		}
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
		global $wgMemc, $wgDBname, $wgUseEnotif;
		$fname = 'User::saveSettings';

		if ( wfReadOnly() ) { return; }
		$this->saveNewtalk();
		if ( 0 == $this->mId ) { return; }

		$dbw =& wfGetDB( DB_MASTER );
		$dbw->update( 'user',
			array( /* SET */
				'user_name' => $this->mName,
				'user_password' => $this->mPassword,
				'user_newpassword' => $this->mNewpassword,
				'user_real_name' => $this->mRealName,
		 		'user_email' => $this->mEmail,
		 		'user_email_authenticated' => $dbw->timestampOrNull( $this->mEmailAuthenticated ),
				'user_options' => $this->encodeOptions(),
				'user_touched' => $dbw->timestamp($this->mTouched),
				'user_token' => $this->mToken
			), array( /* WHERE */
				'user_id' => $this->mId
			), $fname
		);
		$wgMemc->delete( "$wgDBname:user:id:$this->mId" );
	}

	/**
	 * Save value of new talk flag.
	 */
	function saveNewtalk() {
		global $wgDBname, $wgMemc, $wgUseEnotif;

		$fname = 'User::saveNewtalk';

		$changed = false;

		if ( wfReadOnly() ) { return ; }
		$dbr =& wfGetDB( DB_SLAVE );
		$dbw =& wfGetDB( DB_MASTER );
		$changed = false;
		if ( $wgUseEnotif ) {
			if ( ! $this->getNewtalk() ) {
				# Delete the watchlist entry for user_talk page X watched by user X
				$dbw->delete( 'watchlist',
					array( 'wl_user'      => $this->mId,
						   'wl_title'     => $this->getTitleKey(),
						   'wl_namespace' => NS_USER_TALK ),
					$fname );
				if ( $dbw->affectedRows() ) {
					$changed = true;
				}
				if( !$this->mId ) {
					# Anon users have a separate memcache space for newtalk
					# since they don't store their own info. Trim...
					$wgMemc->delete( "$wgDBname:newtalk:ip:{$this->mName}" );
				}
			}
		} else {
			if ($this->getID() != 0) {
				$field = 'user_id';
				$value = $this->getID();
				$key = false;
			} else {
				$field = 'user_ip';
				$value = $this->mName;
				$key = "$wgDBname:newtalk:ip:$this->mName";
			}

			$dbr =& wfGetDB( DB_SLAVE );
			$dbw =& wfGetDB( DB_MASTER );

			$res = $dbr->selectField('user_newtalk', $field,
									 array($field => $value), $fname);

			$changed = true;
			if ($res !== false && $this->mNewtalk == 0) {
				$dbw->delete('user_newtalk', array($field => $value), $fname);
				if ( $key ) {
					$wgMemc->set( $key, 0 );
				}
			} else if ($res === false && $this->mNewtalk == 1) {
				$dbw->insert('user_newtalk', array($field => $value), $fname);
				if ( $key ) {
					$wgMemc->set( $key, 1 );
				}
			} else {
				$changed = false;
			}
		}

		# Update user_touched, so that newtalk notifications in the client cache are invalidated
		if ( $changed && $this->getID() ) {
			$dbw->update('user',
				/*SET*/ array( 'user_touched' => $this->mTouched ),
				/*WHERE*/ array( 'user_id' => $this->getID() ),
				$fname);
			$wgMemc->set( "$wgDBname:user:id:{$this->mId}", $this, 86400 );
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
				'user_email_authenticated' => $dbw->timestampOrNull( $this->mEmailAuthenticated ),
				'user_real_name' => $this->mRealName,
				'user_options' => $this->encodeOptions(),
				'user_token' => $this->mToken
			), $fname
		);
		$this->mId = $dbw->insertId();
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
		$confstr .= '!' . $this->getOption( 'date' );
		$confstr .= '!' . $this->getOption( 'numberheadings' );
		$confstr .= '!' . $this->getOption( 'language' );
		$confstr .= '!' . $this->getOption( 'thumbsize' );
		// add in language specific options, if any
		$extra = $wgContLang->getExtraHashOptions();
		$confstr .= $extra;

		$this->mHash = $confstr;
		return $confstr ;
	}

	function isAllowedToCreateAccount() {
		return $this->isAllowed( 'createaccount' );
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
		return $this->isAnon() || $this->mId > User::getMaxID() * 0.99 && !$this->isAllowed( 'delete' ) && !$this->isBot();
	}

	/**
	 * Check to see if the given clear-text password is one of the accepted passwords
	 * @param string $password User password.
	 * @return bool True if the given password is correct otherwise False.
	 */
	function checkPassword( $password ) {
		global $wgAuth, $wgMinimalPasswordLength;
		$this->loadFromDatabase();

		// Even though we stop people from creating passwords that
		// are shorter than this, doesn't mean people wont be able
		// to. Certain authentication plugins do NOT want to save
		// domain passwords in a mysql database, so we should
		// check this (incase $wgAuth->strict() is false).
		if( strlen( $password ) < $wgMinimalPasswordLength ) {
			return false;
		}

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
			$token = $this->generateToken();
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
	 * Generate a hex-y looking random token for various uses.
	 * Could be made more cryptographically sure if someone cares.
	 * @return string
	 */
	function generateToken( $salt = '' ) {
		$token = dechex( mt_rand() ) . dechex( mt_rand() );
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
		global $wgMemc;

/*
		if ( !isset( $_SESSION['wsEditToken'] ) ) {
			$logfile = '/home/wikipedia/logs/session_debug/session.log';
			$mckey = memsess_key( session_id() );
			$uname = @posix_uname();
			$msg = "wsEditToken not set!\n" .
			'apache server=' . $uname['nodename'] . "\n" .
			'session_id = ' . session_id() . "\n" .
			'$_SESSION=' . var_export( $_SESSION, true ) . "\n" .
			'$_COOKIE=' . var_export( $_COOKIE, true ) . "\n" .
			"mc get($mckey) = " . var_export( $wgMemc->get( $mckey ), true ) . "\n\n\n";

			@error_log( $msg, 3, $logfile );
		}
*/
		return ( $val == $this->editToken( $salt ) );
	}

	/**
	 * Generate a new e-mail confirmation token and send a confirmation
	 * mail to the user's given address.
	 *
	 * @return mixed True on success, a WikiError object on failure.
	 */
	function sendConfirmationMail() {
		global $wgIP, $wgContLang;
		$url = $this->confirmationTokenUrl( $expiration );
		return $this->sendMail( wfMsg( 'confirmemail_subject' ),
			wfMsg( 'confirmemail_body',
				$wgIP,
				$this->getName(),
				$url,
				$wgContLang->timeanddate( $expiration, false ) ) );
	}

	/**
	 * Send an e-mail to this user's account. Does not check for
	 * confirmed status or validity.
	 *
	 * @param string $subject
	 * @param string $body
	 * @param strong $from Optional from address; default $wgPasswordSender will be used otherwise.
	 * @return mixed True on success, a WikiError object on failure.
	 */
	function sendMail( $subject, $body, $from = null ) {
		if( is_null( $from ) ) {
			global $wgPasswordSender;
			$from = $wgPasswordSender;
		}

		require_once( 'UserMailer.php' );
		$error = userMailer( $this->getEmail(), $from, $subject, $body );

		if( $error == '' ) {
			return true;
		} else {
			return new WikiError( $error );
		}
	}

	/**
	 * Generate, store, and return a new e-mail confirmation code.
	 * A hash (unsalted since it's used as a key) is stored.
	 * @param &$expiration mixed output: accepts the expiration time
	 * @return string
	 * @access private
	 */
	function confirmationToken( &$expiration ) {
		$fname = 'User::confirmationToken';

		$now = time();
		$expires = $now + 7 * 24 * 60 * 60;
		$expiration = wfTimestamp( TS_MW, $expires );

		$token = $this->generateToken( $this->mId . $this->mEmail . $expires );
		$hash = md5( $token );

		$dbw =& wfGetDB( DB_MASTER );
		$dbw->update( 'user',
			array( 'user_email_token'         => $hash,
			       'user_email_token_expires' => $dbw->timestamp( $expires ) ),
			array( 'user_id'                  => $this->mId ),
			$fname );

		return $token;
	}

	/**
	 * Generate and store a new e-mail confirmation token, and return
	 * the URL the user can use to confirm.
	 * @param &$expiration mixed output: accepts the expiration time
	 * @return string
	 * @access private
	 */
	function confirmationTokenUrl( &$expiration ) {
		$token = $this->confirmationToken( $expiration );
		$title = Title::makeTitle( NS_SPECIAL, 'Confirmemail/' . $token );
		return $title->getFullUrl();
	}

	/**
	 * Mark the e-mail address confirmed and save.
	 */
	function confirmEmail() {
		$this->loadFromDatabase();
		$this->mEmailAuthenticated = wfTimestampNow();
		$this->saveSettings();
		return true;
	}

	/**
	 * Is this user allowed to send e-mails within limits of current
	 * site configuration?
	 * @return bool
	 */
	function canSendEmail() {
		return $this->isEmailConfirmed();
	}

	/**
	 * Is this user allowed to receive e-mails within limits of current
	 * site configuration?
	 * @return bool
	 */
	function canReceiveEmail() {
		return $this->canSendEmail() && !$this->getOption( 'disablemail' );
	}

	/**
	 * Is this user's e-mail address valid-looking and confirmed within
	 * limits of the current site configuration?
	 *
	 * If $wgEmailAuthentication is on, this may require the user to have
	 * confirmed their address by returning a code or using a password
	 * sent to the address from the wiki.
	 *
	 * @return bool
	 */
	function isEmailConfirmed() {
		global $wgEmailAuthentication;
		$this->loadFromDatabase();
		if( $this->isAnon() )
			return false;
		if( !$this->isValidEmailAddr( $this->mEmail ) )
			return false;
		if( $wgEmailAuthentication && !$this->getEmailAuthenticationTimestamp() )
			return false;
		return true;
	}

	/**
	 * @param array $groups list of groups
	 * @return array list of permission key names for given groups combined
	 * @static
	 */
	function getGroupPermissions( $groups ) {
		global $wgGroupPermissions;
		$rights = array();
		foreach( $groups as $group ) {
			if( isset( $wgGroupPermissions[$group] ) ) {
				$rights = array_merge( $rights,
					array_keys( array_filter( $wgGroupPermissions[$group] ) ) );
			}
		}
		return $rights;
	}

	/**
	 * @param string $group key name
	 * @return string localized descriptive name, if provided
	 * @static
	 */
	function getGroupName( $group ) {
		$key = "group-$group-name";
		$name = wfMsg( $key );
		if( $name == '' || $name == "&lt;$key&gt;" ) {
			return $group;
		} else {
			return $name;
		}
	}

	/**
	 * Return the set of defined explicit groups.
	 * The * and 'user' groups are not included.
	 * @return array
	 * @static
	 */
	function getAllGroups() {
		global $wgGroupPermissions;
		return array_diff(
			array_keys( $wgGroupPermissions ),
			array( '*', 'user' ) );
	}

}

?>
