<?php
/**
 * See user.txt
 *
 * @package MediaWiki
 */

# Number of characters in user_token field
define( 'USER_TOKEN_LENGTH', 32 );

# Serialized record version
define( 'MW_USER_VERSION', 3 );

/**
 *
 * @package MediaWiki
 */
class User {
	/*
	 * When adding a new private variable, dont forget to add it to __sleep()
	 */
	/**@{{
	 * @private
	 */
	var $mBlockedby;	//!<
	var $mBlockreason;	//!<
	var $mBlock;        //!<
	var $mDataLoaded;	//!<
	var $mEmail;		//!<
	var $mEmailAuthenticated; //!<
	var $mGroups;		//!<
	var $mHash;			//!<
	var $mId;			//!<
	var $mName;			//!<
	var $mNewpassword;	//!<
	var $mNewtalk;		//!<
	var $mOptions;		//!<
	var $mPassword;		//!<
	var $mRealName;		//!<
	var $mRegistration;	//!<
	var $mRights;		//!<
	var $mSkin;			//!<
	var $mToken;		//!<
	var $mTouched;		//!<
	var $mDatePreference; // !<
	var $mVersion;		//!< serialized version
	/**@}} */

	/**
	 * Default user options
	 * To change this array at runtime, use a UserDefaultOptions hook
	 */
	static public $mDefaultOptions = array( 
		'quickbar' 		=> 1,
		'underline' 		=> 2,
		'cols'			=> 80,
		'rows' 			=> 25,
		'searchlimit' 		=> 20,
		'contextlines' 		=> 5,
		'contextchars' 		=> 50,
		'skin' 			=> false,
		'math' 			=> 1,
		'rcdays' 		=> 7,
		'rclimit' 		=> 50,
		'wllimit' 		=> 250,
		'highlightbroken'	=> 1,
		'stubthreshold' 	=> 0,
		'previewontop' 		=> 1,
		'editsection'		=> 1,
		'editsectiononrightclick'=> 0,
		'showtoc'		=> 1,
		'showtoolbar' 		=> 1,
		'date' 			=> 'default',
		'imagesize' 		=> 2,
		'thumbsize'		=> 2,
		'rememberpassword' 	=> 0,
		'enotifwatchlistpages' 	=> 0,
		'enotifusertalkpages' 	=> 1,
		'enotifminoredits' 	=> 0,
		'enotifrevealaddr' 	=> 0,
		'shownumberswatching' 	=> 1,
		'fancysig' 		=> 0,
		'externaleditor' 	=> 0,
		'externaldiff' 		=> 0,
		'showjumplinks'		=> 1,
		'numberheadings'	=> 0,
		'uselivepreview'	=> 0,
		'watchlistdays' 	=> 3.0,
	);

	static public $mToggles = array(
		'highlightbroken',
		'justify',
		'hideminor',
		'extendwatchlist',
		'usenewrc',
		'numberheadings',
		'showtoolbar',
		'editondblclick',
		'editsection',
		'editsectiononrightclick',
		'showtoc',
		'rememberpassword',
		'editwidth',
		'watchcreations',
		'watchdefault',
		'minordefault',
		'previewontop',
		'previewonfirst',
		'nocache',
		'enotifwatchlistpages',
		'enotifusertalkpages',
		'enotifminoredits',
		'enotifrevealaddr',
		'shownumberswatching',
		'fancysig',
		'externaleditor',
		'externaldiff',
		'showjumplinks',
		'uselivepreview',
		'autopatrol',
		'forceeditsummary',
		'watchlisthideown',
		'watchlisthidebots',
	);		

	/** Constructor using User:loadDefaults() */
	function User()	{
		$this->loadDefaults();
		$this->mVersion = MW_USER_VERSION;
	}

	/**
	 * Static factory method
	 * @param string $name Username, validated by Title:newFromText()
	 * @param bool $validate Validate username
	 * @return User
	 * @static
	 */
	function newFromName( $name, $validate = true ) {
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

		if( $validate && !User::isValidUserName( $canonicalName ) ) {
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
	 * silly "incomplete type" errors when skins are cached. The array should
	 * contain names of private variables (see at top of User.php).
	 */
	function __sleep() {
		return array(
'mDataLoaded',
'mEmail',
'mEmailAuthenticated',
'mGroups',
'mHash',
'mId',
'mName',
'mNewpassword',
'mNewtalk',
'mOptions',
'mPassword',
'mRealName',
'mRegistration',
'mRights',
'mToken',
'mTouched',
'mVersion',
);
	}

	/**
	 * Get username given an id.
	 * @param integer $id Database user id
	 * @return string Nickname of a user
	 * @static
	 */
	function whoIs( $id )	{
		$dbr =& wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'user', 'user_name', array( 'user_id' => $id ), 'User::whoIs' );
	}

	/**
	 * Get real username given an id.
	 * @param integer $id Database user id
	 * @return string Realname of a user
	 * @static
	 */
	function whoIsReal( $id )	{
		$dbr =& wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'user', 'user_real_name', array( 'user_id' => $id ), 'User::whoIsReal' );
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
	 * Does the string match an anonymous IPv4 address?
	 *
	 * This function exists for username validation, in order to reject
	 * usernames which are similar in form to IP addresses. Strings such
	 * as 300.300.300.300 will return true because it looks like an IP 
	 * address, despite not being strictly valid.
	 * 
	 * We match \d{1,3}\.\d{1,3}\.\d{1,3}\.xxx as an anonymous IP
	 * address because the usemod software would "cloak" anonymous IP
	 * addresses like this, if we allowed accounts like this to be created
	 * new users could get the old edits of these anonymous users.
	 *
	 * @bug 3631
	 *
	 * @static
	 * @param string $name Nickname of a user
	 * @return bool
	 */
	function isIP( $name ) {
		return preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.(?:xxx|\d{1,3})$/",$name);
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

		// Ensure that the name can't be misresolved as a different title,
		// such as with extra namespace keys at the start.
		$parsed = Title::newFromText( $name );
		if( is_null( $parsed )
			|| $parsed->getNamespace()
			|| strcmp( $name, $parsed->getPrefixedText() ) )
			return false;
		
		// Check an additional blacklist of troublemaker characters.
		// Should these be merged into the title char list?
		$unicodeBlacklist = '/[' .
			'\x{0080}-\x{009f}' . # iso-8859-1 control chars
			'\x{00a0}' .          # non-breaking space
			'\x{2000}-\x{200f}' . # various whitespace
			'\x{2028}-\x{202f}' . # breaks and control chars
			'\x{3000}' .          # ideographic space
			'\x{e000}-\x{f8ff}' . # private use
			']/u';
		if( preg_match( $unicodeBlacklist, $name ) ) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Usernames which fail to pass this function will be blocked
	 * from user login and new account registrations, but may be used
	 * internally by batch processes.
	 *
	 * If an account already exists in this form, login will be blocked
	 * by a failure to pass this function.
	 *
	 * @param string $name
	 * @return bool
	 */
	static function isUsableName( $name ) {
		global $wgReservedUsernames;
		return
			// Must be a usable username, obviously ;)
			self::isValidUserName( $name ) &&
			
			// Certain names may be reserved for batch processes.
			!in_array( $name, $wgReservedUsernames );
	}
	
	/**
	 * Usernames which fail to pass this function will be blocked
	 * from new account registrations, but may be used internally
	 * either by batch processes or by user accounts which have
	 * already been created.
	 *
	 * Additional character blacklisting may be added here
	 * rather than in isValidUserName() to avoid disrupting
	 * existing accounts.
	 *
	 * @param string $name
	 * @return bool
	 */
	static function isCreatableName( $name ) {
		return
			self::isUsableName( $name ) &&
			
			// Registration-time character blacklisting...
			strpos( $name, '@' ) === false;
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
	 * Does the string match roughly an email address ?
	 *
	 * There used to be a regular expression here, it got removed because it
	 * rejected valid addresses. Actually just check if there is '@' somewhere
	 * in the given address.
	 *
	 * @todo Check for RFC 2822 compilance
	 * @bug 959
	 *
	 * @param string $addr email address
	 * @static
	 * @return bool
	 */
	function isValidEmailAddr ( $addr ) {
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
		$fname = 'User::edits';

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

		global $wgCookiePrefix;
		global $wgNamespacesToBeSearchedDefault;

		$this->mId = 0;
		$this->mNewtalk = -1;
		$this->mName = false;
		$this->mRealName = $this->mEmail = '';
		$this->mEmailAuthenticated = null;
		$this->mPassword = $this->mNewpassword = '';
		$this->mRights = array();
		$this->mGroups = array();
		$this->mOptions = null;
		$this->mDatePreference = null;

		unset( $this->mSkin );
		$this->mDataLoaded = false;
		$this->mBlockedby = -1; # Unset
		$this->setToken(); # Random
		$this->mHash = false;

		if ( isset( $_COOKIE[$wgCookiePrefix.'LoggedOut'] ) ) {
			$this->mTouched = wfTimestamp( TS_MW, $_COOKIE[$wgCookiePrefix.'LoggedOut'] );
		}
		else {
			$this->mTouched = '0'; # Allow any pages to be cached
		}

		$this->mRegistration = wfTimestamp( TS_MW );

		wfProfileOut( $fname );
	}

	/**
	 * Combine the language default options with any site-specific options
	 * and add the default language variants.
	 *
	 * @return array
	 * @static
	 * @private
	 */
	function getDefaultOptions() {
		global $wgNamespacesToBeSearchedDefault;
		/**
		 * Site defaults will override the global/language defaults
		 */
		global $wgContLang;
		$defOpt = self::$mDefaultOptions + $wgContLang->getDefaultUserOptionOverrides();

		/**
		 * default language setting
		 */
		$variant = $wgContLang->getPreferredVariant( false );
		$defOpt['variant'] = $variant;
		$defOpt['language'] = $variant;

		foreach( $wgNamespacesToBeSearchedDefault as $nsnum => $val ) {
			$defOpt['searchNs'.$nsnum] = $val;
		}
		return $defOpt;
	}

	/**
	 * Get a given default option value.
	 *
	 * @param string $opt
	 * @return string
	 * @static
	 * @public
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
	 * Get a list of user toggle names
	 * @return array
	 */
	static function getToggles() {
		global $wgContLang;
		$extraToggles = array();
		wfRunHooks( 'UserToggles', array( &$extraToggles ) );
		return array_merge( self::$mToggles, $extraToggles, $wgContLang->getExtraUserToggles() );
	}


	/**
	 * Get blocking information
	 * @private
	 * @param bool $bFromSlave Specify whether to check slave or master. To improve performance,
	 *  non-critical checks are done against slaves. Check when actually saving should be done against
	 *  master.
	 */
	function getBlockedStatus( $bFromSlave = true ) {
		global $wgEnableSorbs, $wgProxyWhitelist;

		if ( -1 != $this->mBlockedby ) {
			wfDebug( "User::getBlockedStatus: already loaded.\n" );
			return;
		}

		$fname = 'User::getBlockedStatus';
		wfProfileIn( $fname );
		wfDebug( "$fname: checking...\n" );

		$this->mBlockedby = 0;
		$ip = wfGetIP();

		# User/IP blocking
		$this->mBlock = new Block();
		$this->mBlock->fromMaster( !$bFromSlave );
		if ( $this->mBlock->load( $ip , $this->mId ) ) {
			wfDebug( "$fname: Found block.\n" );
			$this->mBlockedby = $this->mBlock->mBy;
			$this->mBlockreason = $this->mBlock->mReason;
			if ( $this->isLoggedIn() ) {
				$this->spreadBlock();
			}
		} else {
			$this->mBlock = null;
			wfDebug( "$fname: No block.\n" );
		}

		# Proxy blocking
		if ( !$this->isAllowed('proxyunbannable') && !in_array( $ip, $wgProxyWhitelist ) ) {

			# Local list
			if ( wfIsLocallyBlockedProxy( $ip ) ) {
				$this->mBlockedby = wfMsg( 'proxyblocker' );
				$this->mBlockreason = wfMsg( 'proxyblockreason' );
			}

			# DNSBL
			if ( !$this->mBlockedby && $wgEnableSorbs && !$this->getID() ) {
				if ( $this->inSorbsBlacklist( $ip ) ) {
					$this->mBlockedby = wfMsg( 'sorbs' );
					$this->mBlockreason = wfMsg( 'sorbsreason' );
				}
			}
		}

		# Extensions
		wfRunHooks( 'GetBlockedStatus', array( &$this ) );

		wfProfileOut( $fname );
	}

	function inSorbsBlacklist( $ip ) {
		global $wgEnableSorbs;
		return $wgEnableSorbs &&
			$this->inDnsBlacklist( $ip, 'http.dnsbl.sorbs.net.' );
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
	 * @public
	 */
	function pingLimiter( $action='edit' ) {
		global $wgRateLimits, $wgRateLimitsExcludedGroups;
		if( !isset( $wgRateLimits[$action] ) ) {
			return false;
		}
		
		# Some groups shouldn't trigger the ping limiter, ever
		foreach( $this->getGroups() as $group ) {
			if( array_search( $group, $wgRateLimitsExcludedGroups ) !== false )
				return false;
		}
		
		global $wgMemc, $wgRateLimitLog;
		$fname = 'User::pingLimiter';
		wfProfileIn( $fname );

		$limits = $wgRateLimits[$action];
		$keys = array();
		$id = $this->getId();
		$ip = wfGetIP();

		if( isset( $limits['anon'] ) && $id == 0 ) {
			$keys[wfMemcKey( 'limiter', $action, 'anon' )] = $limits['anon'];
		}

		if( isset( $limits['user'] ) && $id != 0 ) {
			$keys[wfMemcKey( 'limiter', $action, 'user', $id )] = $limits['user'];
		}
		if( $this->isNewbie() ) {
			if( isset( $limits['newbie'] ) && $id != 0 ) {
				$keys[wfMemcKey( 'limiter', $action, 'user', $id )] = $limits['newbie'];
			}
			if( isset( $limits['ip'] ) ) {
				$keys["mediawiki:limiter:$action:ip:$ip"] = $limits['ip'];
			}
			if( isset( $limits['subnet'] ) && preg_match( '/^(\d+\.\d+\.\d+)\.\d+$/', $ip, $matches ) ) {
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
						@error_log( wfTimestamp( TS_MW ) . ' ' . wfWikiID() . ': ' . $this->getName() . " tripped $key at $count $summary\n", 3, $wgRateLimitLog );
					}
					$triggered = true;
				} else {
					wfDebug( "$fname: ok. $key at $count $summary\n" );
				}
			} else {
				wfDebug( "$fname: adding record for $key $summary\n" );
				$wgMemc->add( $key, 1, intval( $period ) );
			}
			$wgMemc->incr( $key );
		}

		wfProfileOut( $fname );
		return $triggered;
	}

	/**
	 * Check if user is blocked
	 * @return bool True if blocked, false otherwise
	 */
	function isBlocked( $bFromSlave = true ) { // hacked from false due to horrible probs on site
		wfDebug( "User::isBlocked: enter\n" );
		$this->getBlockedStatus( $bFromSlave );
		return $this->mBlockedby !== 0;
	}

	/**
	 * Check if user is blocked from editing a particular article
	 */
	function isBlockedFrom( $title, $bFromSlave = false ) {
		global $wgBlockAllowsUTEdit;
		$fname = 'User::isBlockedFrom';
		wfProfileIn( $fname );
		wfDebug( "$fname: enter\n" );

		if ( $wgBlockAllowsUTEdit && $title->getText() === $this->getName() &&
		  $title->getNamespace() == NS_USER_TALK )
		{
			$blocked = false;
			wfDebug( "$fname: self-talk page, ignoring any blocks\n" );
		} else {
			wfDebug( "$fname: asking isBlocked()\n" );
			$blocked = $this->isBlocked( $bFromSlave );
		}
		wfProfileOut( $fname );
		return $blocked;
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
	 * @deprecated use wfSetupSession()
	 */
	function SetupSession() {
		wfSetupSession();
	}

	/**
	 * Create a new user object using data from session
	 * @static
	 */
	function loadFromSession() {
		global $wgMemc, $wgCookiePrefix;

		if ( isset( $_SESSION['wsUserID'] ) ) {
			if ( 0 != $_SESSION['wsUserID'] ) {
				$sId = $_SESSION['wsUserID'];
			} else {
				return new User();
			}
		} else if ( isset( $_COOKIE["{$wgCookiePrefix}UserID"] ) ) {
			$sId = intval( $_COOKIE["{$wgCookiePrefix}UserID"] );
			$_SESSION['wsUserID'] = $sId;
		} else {
			return new User();
		}
		if ( isset( $_SESSION['wsUserName'] ) ) {
			$sName = $_SESSION['wsUserName'];
		} else if ( isset( $_COOKIE["{$wgCookiePrefix}UserName"] ) ) {
			$sName = $_COOKIE["{$wgCookiePrefix}UserName"];
			$_SESSION['wsUserName'] = $sName;
		} else {
			return new User();
		}

		$passwordCorrect = FALSE;
		$user = $wgMemc->get( $key = wfMemcKey( 'user', 'id', $sId ) );
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
			# Set block status to unloaded, that should be loaded every time
			$user->mBlockedby = -1;
		}

		if ( isset( $_SESSION['wsToken'] ) ) {
			$passwordCorrect = $_SESSION['wsToken'] == $user->mToken;
		} else if ( isset( $_COOKIE["{$wgCookiePrefix}Token"] ) ) {
			$passwordCorrect = $user->mToken == $_COOKIE["{$wgCookiePrefix}Token"];
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
		$fname = "User::loadFromDatabase";

		# Counter-intuitive, breaks various things, use User::setLoaded() if you want to suppress
		# loading in a command line script, don't assume all command line scripts need it like this
		#if ( $this->mDataLoaded || $wgCommandLineMode ) {
		if ( $this->mDataLoaded ) {
			return;
		}

		# Paranoia
		$this->mId = intval( $this->mId );

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
		  'user_real_name','user_options','user_touched', 'user_token', 'user_registration' ),
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
			$this->mRegistration = wfTimestampOrNull( TS_MW, $s->user_registration );

			$res = $dbr->select( 'user_groups',
				array( 'ug_group' ),
				array( 'ug_user' => $this->mId ),
				$fname );
			$this->mGroups = array();
			while( $row = $dbr->fetchObject( $res ) ) {
				$this->mGroups[] = $row->ug_group;
			}
			$implicitGroups = array( '*', 'user' );

			global $wgAutoConfirmAge;
			$accountAge = time() - wfTimestampOrNull( TS_UNIX, $this->mRegistration );
			if( $accountAge >= $wgAutoConfirmAge ) {
				$implicitGroups[] = 'autoconfirmed';
			}
			
			# Implicit group for users whose email addresses are confirmed
			global $wgEmailAuthentication;
			if( $this->isValidEmailAddr( $this->mEmail ) ) {
				if( $wgEmailAuthentication ) {
					if( $this->mEmailAuthenticated )
						$implicitGroups[] = 'emailconfirmed';
				} else {
					$implicitGroups[] = 'emailconfirmed';
				}
			}

			$effectiveGroups = array_merge( $implicitGroups, $this->mGroups );
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
		if ( $this->mName === false ) {
			$this->mName = wfGetIP();
		}
		return $this->mName;
	}

	function setName( $str ) {
		$this->loadFromDatabase();
		$this->mName = $str;
	}


	/**
	 * Return the title dbkey form of the name, for eg user pages.
	 * @return string
	 * @public
	 */
	function getTitleKey() {
		return str_replace( ' ', '_', $this->getName() );
	}

	function getNewtalk() {
		$this->loadFromDatabase();

		# Load the newtalk status if it is unloaded (mNewtalk=-1)
		if( $this->mNewtalk === -1 ) {
			$this->mNewtalk = false; # reset talk page status

			# Check memcached separately for anons, who have no
			# entire User object stored in there.
			if( !$this->mId ) {
				global $wgMemc;
				$key = wfMemcKey( 'newtalk', 'ip', $this->getName() );
				$newtalk = $wgMemc->get( $key );
				if( is_integer( $newtalk ) ) {
					$this->mNewtalk = (bool)$newtalk;
				} else {
					$this->mNewtalk = $this->checkNewtalk( 'user_ip', $this->getName() );
					$wgMemc->set( $key, $this->mNewtalk, time() ); // + 1800 );
				}
			} else {
				$this->mNewtalk = $this->checkNewtalk( 'user_id', $this->mId );
			}
		}

		return (bool)$this->mNewtalk;
	}

	/**
	 * Return the talk page(s) this user has new messages on.
	 */
	function getNewMessageLinks() {
		$talks = array();
		if (!wfRunHooks('UserRetrieveNewTalks', array(&$this, &$talks)))
			return $talks;

		if (!$this->getNewtalk())
			return array();
		$up = $this->getUserPage();
		$utp = $up->getTalkPage();
		return array(array("wiki" => wfWikiID(), "link" => $utp->getLocalURL()));
	}

		
	/**
	 * Perform a user_newtalk check on current slaves; if the memcached data
	 * is funky we don't want newtalk state to get stuck on save, as that's
	 * damn annoying.
	 *
	 * @param string $field
	 * @param mixed $id
	 * @return bool
	 * @private
	 */
	function checkNewtalk( $field, $id ) {
		$fname = 'User::checkNewtalk';
		$dbr =& wfGetDB( DB_SLAVE );
		$ok = $dbr->selectField( 'user_newtalk', $field,
			array( $field => $id ), $fname );
		return $ok !== false;
	}

	/**
	 * Add or update the
	 * @param string $field
	 * @param mixed $id
	 * @private
	 */
	function updateNewtalk( $field, $id ) {
		$fname = 'User::updateNewtalk';
		if( $this->checkNewtalk( $field, $id ) ) {
			wfDebug( "$fname already set ($field, $id), ignoring\n" );
			return false;
		}
		$dbw =& wfGetDB( DB_MASTER );
		$dbw->insert( 'user_newtalk',
			array( $field => $id ),
			$fname,
			'IGNORE' );
		wfDebug( "$fname: set on ($field, $id)\n" );
		return true;
	}

	/**
	 * Clear the new messages flag for the given user
	 * @param string $field
	 * @param mixed $id
	 * @private
	 */
	function deleteNewtalk( $field, $id ) {
		$fname = 'User::deleteNewtalk';
		if( !$this->checkNewtalk( $field, $id ) ) {
			wfDebug( "$fname: already gone ($field, $id), ignoring\n" );
			return false;
		}
		$dbw =& wfGetDB( DB_MASTER );
		$dbw->delete( 'user_newtalk',
			array( $field => $id ),
			$fname );
		wfDebug( "$fname: killed on ($field, $id)\n" );
		return true;
	}

	/**
	 * Update the 'You have new messages!' status.
	 * @param bool $val
	 */
	function setNewtalk( $val ) {
		if( wfReadOnly() ) {
			return;
		}

		$this->loadFromDatabase();
		$this->mNewtalk = $val;

		$fname = 'User::setNewtalk';

		if( $this->isAnon() ) {
			$field = 'user_ip';
			$id = $this->getName();
		} else {
			$field = 'user_id';
			$id = $this->getId();
		}

		if( $val ) {
			$changed = $this->updateNewtalk( $field, $id );
		} else {
			$changed = $this->deleteNewtalk( $field, $id );
		}

		if( $changed ) {
			if( $this->isAnon() ) {
				// Anons have a separate memcached space, since
				// user records aren't kept for them.
				global $wgMemc;
				$key = wfMemcKey( 'newtalk', 'ip', $val );
				$wgMemc->set( $key, $val ? 1 : 0 );
			} else {
				if( $val ) {
					// Make sure the user page is watched, so a notification
					// will be sent out if enabled.
					$this->addWatch( $this->getTalkPage() );
				}
			}
			$this->invalidateCache();
		}
	}
	
	/**
	 * Generate a current or new-future timestamp to be stored in the
	 * user_touched field when we update things.
	 */
	private static function newTouchedTimestamp() {
		global $wgClockSkewFudge;
		return wfTimestamp( TS_MW, time() + $wgClockSkewFudge );
	}
	
	/**
	 * Clear user data from memcached.
	 * Use after applying fun updates to the database; caller's
	 * responsibility to update user_touched if appropriate.
	 *
	 * Called implicitly from invalidateCache() and saveSettings().
	 */
	private function clearUserCache() {
		if( $this->mId ) {
			global $wgMemc;
			$wgMemc->delete( wfMemcKey( 'user', 'id', $this->mId ) );
		}
	}

	/**
	 * Immediately touch the user data cache for this account.
	 * Updates user_touched field, and removes account data from memcached
	 * for reload on the next hit.
	 */
	function invalidateCache() {
		if( $this->mId ) {
			$this->mTouched = self::newTouchedTimestamp();
			
			$dbw =& wfGetDB( DB_MASTER );
			$dbw->update( 'user',
				array( 'user_touched' => $dbw->timestamp( $this->mTouched ) ),
				array( 'user_id' => $this->mId ),
				__METHOD__ );
			
			$this->clearUserCache();
		}
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
		global $wgSecretKey, $wgProxyKey;
		if ( !$token ) {
			if ( $wgSecretKey ) {
				$key = $wgSecretKey;
			} elseif ( $wgProxyKey ) {
				$key = $wgProxyKey;
			} else {
				$key = microtime();
			}
			$this->mToken = md5( $key . mt_rand( 0, 0x7fffffff ) . wfWikiID() . $this->mId );
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

	/**
	 * @param string $oname The option to check
	 * @return string
	 */
	function getOption( $oname ) {
		$this->loadFromDatabase();
		if ( is_null( $this->mOptions ) ) {
			$this->mOptions = User::getDefaultOptions();
		}
		if ( array_key_exists( $oname, $this->mOptions ) ) {
			return trim( $this->mOptions[$oname] );
		} else {
			return '';
		}
	}

	/**
	 * Get the user's date preference, including some important migration for 
	 * old user rows.
	 */
	function getDatePreference() {
		if ( is_null( $this->mDatePreference ) ) {
			global $wgLang;
			$value = $this->getOption( 'date' );
			$map = $wgLang->getDatePreferenceMigrationMap();
			if ( isset( $map[$value] ) ) {
				$value = $map[$value];
			}
			$this->mDatePreference = $value;
		}
		return $this->mDatePreference;
	}

	/**
	 * @param string $oname The option to check
	 * @return bool False if the option is not selected, true if it is
	 */
	function getBoolOption( $oname ) {
		return (bool)$this->getOption( $oname );
	}
	
	/**
	 * Get an option as an integer value from the source string.
	 * @param string $oname The option to check
	 * @param int $default Optional value to return if option is unset/blank.
	 * @return int
	 */
	function getIntOption( $oname, $default=0 ) {
		$val = $this->getOption( $oname );
		if( $val == '' ) {
			$val = $default;
		}
		return intval( $val );
	}

	function setOption( $oname, $val ) {
		$this->loadFromDatabase();
		if ( is_null( $this->mOptions ) ) {
			$this->mOptions = User::getDefaultOptions();
		}
		if ( $oname == 'skin' ) {
			# Clear cached skin, so the new one displays immediately in Special:Preferences
			unset( $this->mSkin );
		}
		// Filter out any newlines that may have passed through input validation.
		// Newlines are used to separate items in the options blob.
		$val = str_replace( "\r\n", "\n", $val );
		$val = str_replace( "\r", "\n", $val );
		$val = str_replace( "\n", " ", $val );
		$this->mOptions[$oname] = $val;
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
	 * Add the user to the given group.
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
	 * Whether the user is a bot
	 * @deprecated
	 */
	function isBot() {
		return $this->isAllowed( 'bot' );
	}

	/**
	 * Check if user is allowed to access a feature / make an action
	 * @param string $action Action to be checked
	 * @return boolean True: action is allowed, False: action should not be allowed
	 */
	function isAllowed($action='') {
		if ( $action === '' )
			// In the spirit of DWIM
			return true;

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

			# get the user skin
			$userSkin = $this->getOption( 'skin' );
			$userSkin = $wgRequest->getVal('useskin', $userSkin);

			$this->mSkin =& Skin::newFromKey( $userSkin );
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


		if ($title->getNamespace() == NS_USER_TALK &&
			$title->getText() == $this->getName() ) {
			if (!wfRunHooks('UserClearNewTalkNotification', array(&$this)))
				return;
			$this->setNewtalk( false );
		}

		if( !$wgUseEnotif ) {
			return;
		}

		if( $this->isAnon() ) {
			// Nothing else to do...
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
						'wl_notificationtimestamp' => NULL
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
	 * @public
	 */
	function clearAllNotifications( $currentUser ) {
		global $wgUseEnotif;
		if ( !$wgUseEnotif ) {
			$this->setNewtalk( false );
			return;
		}
		if( $currentUser != 0 )  {

			$dbw =& wfGetDB( DB_MASTER );
			$success = $dbw->update( 'watchlist',
				array( /* SET */
					'wl_notificationtimestamp' => NULL
				), array( /* WHERE */
					'wl_user' => $currentUser
				), 'UserMailer::clearAll'
			);

		# 	we also need to clear here the "you have new message" notification for the own user_talk page
		#	This is cleared one page view later in Article::viewUpdates();
		}
	}

	/**
	 * @private
	 * @return string Encoding options
	 */
	function encodeOptions() {
		if ( is_null( $this->mOptions ) ) {
			$this->mOptions = User::getDefaultOptions();
		}
		$a = array();
		foreach ( $this->mOptions as $oname => $oval ) {
			array_push( $a, $oname.'='.$oval );
		}
		$s = implode( "\n", $a );
		return $s;
	}

	/**
	 * @private
	 */
	function decodeOptions( $str ) {
		global $wgLang;
		
		$this->mOptions = array();
		$a = explode( "\n", $str );
		foreach ( $a as $s ) {
			if ( preg_match( "/^(.[^=]*)=(.*)$/", $s, $m ) ) {
				$this->mOptions[$m[1]] = $m[2];
			}
		}
	}

	function setCookies() {
		global $wgCookieExpiration, $wgCookiePath, $wgCookieDomain, $wgCookieSecure, $wgCookiePrefix;
		if ( 0 == $this->mId ) return;
		$this->loadFromDatabase();
		$exp = time() + $wgCookieExpiration;

		$_SESSION['wsUserID'] = $this->mId;
		setcookie( $wgCookiePrefix.'UserID', $this->mId, $exp, $wgCookiePath, $wgCookieDomain, $wgCookieSecure );

		$_SESSION['wsUserName'] = $this->getName();
		setcookie( $wgCookiePrefix.'UserName', $this->getName(), $exp, $wgCookiePath, $wgCookieDomain, $wgCookieSecure );

		$_SESSION['wsToken'] = $this->mToken;
		if ( 1 == $this->getOption( 'rememberpassword' ) ) {
			setcookie( $wgCookiePrefix.'Token', $this->mToken, $exp, $wgCookiePath, $wgCookieDomain, $wgCookieSecure );
		} else {
			setcookie( $wgCookiePrefix.'Token', '', time() - 3600 );
		}
	}

	/**
	 * Logout user
	 * It will clean the session cookie
	 */
	function logout() {
		global $wgCookiePath, $wgCookieDomain, $wgCookieSecure, $wgCookiePrefix;
		$this->loadDefaults();
		$this->setLoaded( true );

		$_SESSION['wsUserID'] = 0;

		setcookie( $wgCookiePrefix.'UserID', '', time() - 3600, $wgCookiePath, $wgCookieDomain, $wgCookieSecure );
		setcookie( $wgCookiePrefix.'Token', '', time() - 3600, $wgCookiePath, $wgCookieDomain, $wgCookieSecure );

		# Remember when user logged out, to prevent seeing cached pages
		setcookie( $wgCookiePrefix.'LoggedOut', wfTimestampNow(), time() + 86400, $wgCookiePath, $wgCookieDomain, $wgCookieSecure );
	}

	/**
	 * Save object settings into database
	 * @fixme Only rarely do all these fields need to be set!
	 */
	function saveSettings() {
		$fname = 'User::saveSettings';

		if ( wfReadOnly() ) { return; }
		if ( 0 == $this->mId ) { return; }
		
		$this->mTouched = self::newTouchedTimestamp();

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
		$this->clearUserCache();
	}


	/**
	 * Checks if a user with the given name exists, returns the ID
	 */
	function idForName() {
		$fname = 'User::idForName';

		$gotid = 0;
		$s = trim( $this->getName() );
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
				'user_token' => $this->mToken,
				'user_registration' => $dbw->timestamp( $this->mRegistration ),
			), $fname
		);
		$this->mId = $dbw->insertId();
	}

	function spreadBlock() {
		# If the (non-anonymous) user is blocked, this function will block any IP address
		# that they successfully log on from.
		$fname = 'User::spreadBlock';

		wfDebug( "User:spreadBlock()\n" );
		if ( $this->mId == 0 ) {
			return;
		}

		$userblock = Block::newFromDB( '', $this->mId );
		if ( !$userblock ) {
			return;
		}

		# Check if this IP address is already blocked
		$ipblock = Block::newFromDB( wfGetIP() );
		if ( $ipblock ) {
			# If the user is already blocked. Then check if the autoblock would
			# excede the user block. If it would excede, then do nothing, else
			# prolong block time
			if ($userblock->mExpiry &&
				($userblock->mExpiry < Block::getAutoblockExpiry($ipblock->mTimestamp))) {
				return;
			}
			# Just update the timestamp
			$ipblock->updateTimestamp();
			return;
		} else {
			$ipblock = new Block;
		}

		# Make a new block object with the desired properties
		wfDebug( "Autoblocking {$this->mName}@" . wfGetIP() . "\n" );
		$ipblock->mAddress = wfGetIP();
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

	/**
	 * Generate a string which will be different for any combination of
	 * user options which would produce different parser output.
	 * This will be used as part of the hash key for the parser cache,
	 * so users will the same options can share the same cached data
	 * safely.
	 *
	 * Extensions which require it should install 'PageRenderingHash' hook,
	 * which will give them a chance to modify this key based on their own
	 * settings.
	 *
	 * @return string
	 */
	function getPageRenderingHash() {
		global $wgContLang, $wgUseDynamicDates;
		if( $this->mHash ){
			return $this->mHash;
		}

		// stubthreshold is only included below for completeness,
		// it will always be 0 when this function is called by parsercache.

		$confstr =        $this->getOption( 'math' );
		$confstr .= '!' . $this->getOption( 'stubthreshold' );
		if ( $wgUseDynamicDates ) {
			$confstr .= '!' . $this->getDatePreference();
		}
		$confstr .= '!' . ($this->getOption( 'numberheadings' ) ? '1' : '');
		$confstr .= '!' . $this->getOption( 'language' );
		$confstr .= '!' . $this->getOption( 'thumbsize' );
		// add in language specific options, if any
		$extra = $wgContLang->getExtraHashOptions();
		$confstr .= $extra;

		// Give a chance for extensions to modify the hash, if they have
		// extra options or other effects on the parser cache.
		wfRunHooks( 'PageRenderingHash', array( &$confstr ) );

		$this->mHash = $confstr;
		return $confstr;
	}

	function isBlockedFromCreateAccount() {
		$this->getBlockedStatus();
		return $this->mBlock && $this->mBlock->mCreateAccount;
	}

	function isAllowedToCreateAccount() {
		return $this->isAllowed( 'createaccount' ) && !$this->isBlockedFromCreateAccount();
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
	 * @public
	 */
	function getUserPage() {
		return Title::makeTitle( NS_USER, $this->getName() );
	}

	/**
	 * Get this user's talk page title.
	 *
	 * @return Title
	 * @public
	 */
	function getTalkPage() {
		$title = $this->getUserPage();
		return $title->getTalkPage();
	}

	/**
	 * @static
	 */
	function getMaxID() {
		static $res; // cache

		if ( isset( $res ) )
			return $res;
		else {
			$dbr =& wfGetDB( DB_SLAVE );
			return $res = $dbr->selectField( 'user', 'max(user_id)', false, 'User::getMaxID' );
		}
	}

	/**
	 * Determine whether the user is a newbie. Newbies are either
	 * anonymous IPs, or the most recently created accounts.
	 * @return bool True if it is a newbie.
	 */
	function isNewbie() {
		return !$this->isAllowed( 'autoconfirmed' );
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
	 * @public
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
	 * @public
	 */
	function matchEditToken( $val, $salt = '' ) {
		global $wgMemc;
		$sessionToken = $this->editToken( $salt );
		if ( $val != $sessionToken ) {
			wfDebug( "User::matchEditToken: broken session data\n" );
		}
		return $val == $sessionToken;
	}

	/**
	 * Generate a new e-mail confirmation token and send a confirmation
	 * mail to the user's given address.
	 *
	 * @return mixed True on success, a WikiError object on failure.
	 */
	function sendConfirmationMail() {
		global $wgContLang;
		$url = $this->confirmationTokenUrl( $expiration );
		return $this->sendMail( wfMsg( 'confirmemail_subject' ),
			wfMsg( 'confirmemail_body',
				wfGetIP(),
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
		$to = new MailAddress( $this );
		$sender = new MailAddress( $from );
		$error = userMailer( $to, $sender, $subject, $body );

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
	 * @private
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
	 * @private
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
		$confirmed = true;
		if( wfRunHooks( 'EmailConfirmed', array( &$this, &$confirmed ) ) ) {
			if( $this->isAnon() )
				return false;
			if( !$this->isValidEmailAddr( $this->mEmail ) )
				return false;
			if( $wgEmailAuthentication && !$this->getEmailAuthenticationTimestamp() )
				return false;
			return true;
		} else {
			return $confirmed;
		}
	}

	/**
	 * @param array $groups list of groups
	 * @return array list of permission key names for given groups combined
	 * @static
	 */
	static function getGroupPermissions( $groups ) {
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
	 * @return string localized descriptive name for group, if provided
	 * @static
	 */
	static function getGroupName( $group ) {
		$key = "group-$group";
		$name = wfMsg( $key );
		if( $name == '' || wfEmptyMsg( $key, $name ) ) {
			return $group;
		} else {
			return $name;
		}
	}

	/**
	 * @param string $group key name
	 * @return string localized descriptive name for member of a group, if provided
	 * @static
	 */
	static function getGroupMember( $group ) {
		$key = "group-$group-member";
		$name = wfMsg( $key );
		if( $name == '' || wfEmptyMsg( $key, $name ) ) {
			return $group;
		} else {
			return $name;
		}
	}

	/**
	 * Return the set of defined explicit groups.
	 * The *, 'user', 'autoconfirmed' and 'emailconfirmed'
	 * groups are not included, as they are defined
	 * automatically, not in the database.
	 * @return array
	 * @static
	 */
	static function getAllGroups() {
		global $wgGroupPermissions;
		return array_diff(
			array_keys( $wgGroupPermissions ),
			array( '*', 'user', 'autoconfirmed', 'emailconfirmed' ) );
	}

	/**
	 * Get the title of a page describing a particular group
	 *
	 * @param $group Name of the group
	 * @return mixed
	 */
	static function getGroupPage( $group ) {
		$page = wfMsgForContent( 'grouppage-' . $group );
		if( !wfEmptyMsg( 'grouppage-' . $group, $page ) ) {
			$title = Title::newFromText( $page );
			if( is_object( $title ) )
				return $title;
		}
		return false;
	}

	/**
	 * Create a link to the group in HTML, if available
	 *
	 * @param $group Name of the group
	 * @param $text The text of the link
	 * @return mixed
	 */
	static function makeGroupLinkHTML( $group, $text = '' ) {
		if( $text == '' ) {
			$text = self::getGroupName( $group );
		}
		$title = self::getGroupPage( $group );
		if( $title ) {
			global $wgUser;
			$sk = $wgUser->getSkin();
			return $sk->makeLinkObj( $title, $text );
		} else {
			return $text;
		}
	}

	/**
	 * Create a link to the group in Wikitext, if available
	 *
	 * @param $group Name of the group
	 * @param $text The text of the link (by default, the name of the group)
	 * @return mixed
	 */
	static function makeGroupLinkWiki( $group, $text = '' ) {
		if( $text == '' ) {
			$text = self::getGroupName( $group );
		}
		$title = self::getGroupPage( $group );
		if( $title ) {
			$page = $title->getPrefixedText();
			return "[[$page|$text]]";
		} else {
			return $text;
		}
	}
}

?>
