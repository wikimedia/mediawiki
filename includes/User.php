<?php
/**
 * See user.txt
 *
 */

# Number of characters in user_token field
define( 'USER_TOKEN_LENGTH', 32 );

# Serialized record version
define( 'MW_USER_VERSION', 5 );

# Some punctuation to prevent editing from broken text-mangling proxies.
# FIXME: this is embedded unescaped into HTML attributes in various
# places, so we can't safely include ' or " even though we really should.
define( 'EDIT_TOKEN_SUFFIX', '\\' );

/**
 * Thrown by User::setPassword() on error
 */
class PasswordError extends MWException {
	// NOP
}

/**
 *
 */
class User {

	/**
	 * A list of default user toggles, i.e. boolean user preferences that are 
	 * displayed by Special:Preferences as checkboxes. This list can be 
	 * extended via the UserToggles hook or $wgContLang->getExtraUserToggles().
	 */
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
		'watchmoves',
		'watchdeletion',
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
		'forceeditsummary',
		'watchlisthideown',
		'watchlisthidebots',
		'watchlisthideminor',
		'ccmeonemails',
		'diffonly',
	);

	/**
	 * List of member variables which are saved to the shared cache (memcached).
	 * Any operation which changes the corresponding database fields must 
	 * call a cache-clearing function.
	 */
	static $mCacheVars = array(
		# user table
		'mId',
		'mName',
		'mRealName',
		'mPassword',
		'mNewpassword',
		'mNewpassTime',
		'mEmail',
		'mOptions',
		'mTouched',
		'mToken',
		'mEmailAuthenticated',
		'mEmailToken',
		'mEmailTokenExpires',
		'mRegistration',
		'mEditCount',
		# user_group table
		'mGroups',
	);

	/**
	 * The cache variable declarations
	 */
	var $mId, $mName, $mRealName, $mPassword, $mNewpassword, $mNewpassTime, 
		$mEmail, $mOptions, $mTouched, $mToken, $mEmailAuthenticated, 
		$mEmailToken, $mEmailTokenExpires, $mRegistration, $mGroups;

	/**
	 * Whether the cache variables have been loaded
	 */
	var $mDataLoaded;

	/**
	 * Initialisation data source if mDataLoaded==false. May be one of:
	 *    defaults      anonymous user initialised from class defaults
	 *    name          initialise from mName
	 *    id            initialise from mId
	 *    session       log in from cookies or session if possible
	 *
	 * Use the User::newFrom*() family of functions to set this.
	 */
	var $mFrom;

	/**
	 * Lazy-initialised variables, invalidated with clearInstanceCache
	 */
	var $mNewtalk, $mDatePreference, $mBlockedby, $mHash, $mSkin, $mRights,
		$mBlockreason, $mBlock, $mEffectiveGroups;

	/** 
	 * Lightweight constructor for anonymous user
	 * Use the User::newFrom* factory functions for other kinds of users
	 */
	function User() {
		$this->clearInstanceCache( 'defaults' );
	}

	/**
	 * Load the user table data for this object from the source given by mFrom
	 */
	function load() {
		if ( $this->mDataLoaded ) {
			return;
		}
		wfProfileIn( __METHOD__ );

		# Set it now to avoid infinite recursion in accessors
		$this->mDataLoaded = true;

		switch ( $this->mFrom ) {
			case 'defaults':
				$this->loadDefaults();
				break;
			case 'name':
				$this->mId = self::idFromName( $this->mName );
				if ( !$this->mId ) {
					# Nonexistent user placeholder object
					$this->loadDefaults( $this->mName );
				} else {
					$this->loadFromId();
				}
				break;
			case 'id':
				$this->loadFromId();
				break;
			case 'session':
				$this->loadFromSession();
				break;
			default:
				throw new MWException( "Unrecognised value for User->mFrom: \"{$this->mFrom}\"" );
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Load user table data given mId
	 * @return false if the ID does not exist, true otherwise
	 * @private
	 */
	function loadFromId() {
		global $wgMemc;
		if ( $this->mId == 0 ) {
			$this->loadDefaults();
			return false;
		} 

		# Try cache
		$key = wfMemcKey( 'user', 'id', $this->mId );
		$data = $wgMemc->get( $key );
		if ( !is_array( $data ) || $data['mVersion'] < MW_USER_VERSION ) {
			# Object is expired, load from DB
			$data = false;
		}
		
		if ( !$data ) {
			wfDebug( "Cache miss for user {$this->mId}\n" );
			# Load from DB
			if ( !$this->loadFromDatabase() ) {
				# Can't load from ID, user is anonymous
				return false;
			}

			# Save to cache
			$data = array();
			foreach ( self::$mCacheVars as $name ) {
				$data[$name] = $this->$name;
			}
			$data['mVersion'] = MW_USER_VERSION;
			$wgMemc->set( $key, $data );
		} else {
			wfDebug( "Got user {$this->mId} from cache\n" );
			# Restore from cache
			foreach ( self::$mCacheVars as $name ) {
				$this->$name = $data[$name];
			}
		}
		return true;
	}

	/**
	 * Static factory method for creation from username.
	 *
	 * This is slightly less efficient than newFromId(), so use newFromId() if
	 * you have both an ID and a name handy. 
	 *
	 * @param string $name Username, validated by Title:newFromText()
	 * @param mixed $validate Validate username. Takes the same parameters as 
	 *    User::getCanonicalName(), except that true is accepted as an alias 
	 *    for 'valid', for BC.
	 * 
	 * @return User object, or null if the username is invalid. If the username 
	 *    is not present in the database, the result will be a user object with
	 *    a name, zero user ID and default settings. 
	 * @static
	 */
	static function newFromName( $name, $validate = 'valid' ) {
		if ( $validate === true ) {
			$validate = 'valid';
		}
		$name = self::getCanonicalName( $name, $validate );
		if ( $name === false ) {
			return null;
		} else {
			# Create unloaded user object
			$u = new User;
			$u->mName = $name;
			$u->mFrom = 'name';
			return $u;
		}
	}

	static function newFromId( $id ) {
		$u = new User;
		$u->mId = $id;
		$u->mFrom = 'id';
		return $u;
	}

	/**
	 * Factory method to fetch whichever user has a given email confirmation code.
	 * This code is generated when an account is created or its e-mail address
	 * has changed.
	 *
	 * If the code is invalid or has expired, returns NULL.
	 *
	 * @param string $code
	 * @return User
	 * @static
	 */
	static function newFromConfirmationCode( $code ) {
		$dbr = wfGetDB( DB_SLAVE );
		$id = $dbr->selectField( 'user', 'user_id', array(
			'user_email_token' => md5( $code ),
			'user_email_token_expires > ' . $dbr->addQuotes( $dbr->timestamp() ),
			) );
		if( $id !== false ) {
			return User::newFromId( $id );
		} else {
			return null;
		}
	}
	
	/**
	 * Create a new user object using data from session or cookies. If the
	 * login credentials are invalid, the result is an anonymous user.
	 *
	 * @return User
	 * @static
	 */
	static function newFromSession() {
		$user = new User;
		$user->mFrom = 'session';
		return $user;
	}

	/**
	 * Get username given an id.
	 * @param integer $id Database user id
	 * @return string Nickname of a user
	 * @static
	 */
	static function whoIs( $id ) {
		$dbr = wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'user', 'user_name', array( 'user_id' => $id ), 'User::whoIs' );
	}

	/**
	 * Get real username given an id.
	 * @param integer $id Database user id
	 * @return string Realname of a user
	 * @static
	 */
	static function whoIsReal( $id ) {
		$dbr = wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'user', 'user_real_name', array( 'user_id' => $id ), 'User::whoIsReal' );
	}

	/**
	 * Get database id given a user name
	 * @param string $name Nickname of a user
	 * @return integer|null Database user id (null: if non existent
	 * @static
	 */
	static function idFromName( $name ) {
		$nt = Title::newFromText( $name );
		if( is_null( $nt ) ) {
			# Illegal name
			return null;
		}
		$dbr = wfGetDB( DB_SLAVE );
		$s = $dbr->selectRow( 'user', array( 'user_id' ), array( 'user_name' => $nt->getText() ), __METHOD__ );

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
	static function isIP( $name ) {
		return preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.(?:xxx|\d{1,3})$/',$name);
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
	static function isValidUserName( $name ) {
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
	static function isValidPassword( $password ) {
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
	static function isValidEmailAddr ( $addr ) {
		return ( trim( $addr ) != '' ) &&
			(false !== strpos( $addr, '@' ) );
	}

	/**
	 * Given unvalidated user input, return a canonical username, or false if 
	 * the username is invalid.
	 * @param string $name
	 * @param mixed $validate Type of validation to use:
	 *                         false        No validation
	 *                         'valid'      Valid for batch processes
	 *                         'usable'     Valid for batch processes and login
	 *                         'creatable'  Valid for batch processes, login and account creation
	 */
	static function getCanonicalName( $name, $validate = 'valid' ) {
		# Force usernames to capital
		global $wgContLang;
		$name = $wgContLang->ucfirst( $name );

		# Clean up name according to title rules
		$t = Title::newFromText( $name );
		if( is_null( $t ) ) {
			return false;
		}

		# Reject various classes of invalid names
		$name = $t->getText();
		global $wgAuth;
		$name = $wgAuth->getCanonicalName( $t->getText() );

		switch ( $validate ) {
			case false:
				break;
			case 'valid':
				if ( !User::isValidUserName( $name ) ) {
					$name = false;
				}
				break;
			case 'usable':
				if ( !User::isUsableName( $name ) ) {
					$name = false;
				}
				break;
			case 'creatable':
				if ( !User::isCreatableName( $name ) ) {
					$name = false;
				}
				break;
			default:
				throw new MWException( 'Invalid parameter value for $validate in '.__METHOD__ );
		}
		return $name;
	}

	/**
	 * Count the number of edits of a user
	 *
	 * It should not be static and some day should be merged as proper member function / deprecated -- domas
	 * 
	 * @param int $uid The user ID to check
	 * @return int
	 * @static
	 */
	static function edits( $uid ) {
		wfProfileIn( __METHOD__ );
		$dbr = wfGetDB( DB_SLAVE );
		// check if the user_editcount field has been initialized
		$field = $dbr->selectField(
			'user', 'user_editcount',
			array( 'user_id' => $uid ),
			__METHOD__
		);

		if( $field === null ) { // it has not been initialized. do so.
			$dbw = wfGetDb( DB_MASTER );
			$count = $dbr->selectField(
				'revision', 'count(*)',
				array( 'rev_user' => $uid ),
				__METHOD__
			);
			$dbw->update(
				'user',
				array( 'user_editcount' => $count ),
				array( 'user_id' => $uid ),
				__METHOD__
			);
		} else {
			$count = $field;
		}
		wfProfileOut( __METHOD__ );
		return $count;
	}

	/**
	 * Return a random password. Sourced from mt_rand, so it's not particularly secure.
	 * @todo: hash random numbers to improve security, like generateToken()
	 *
	 * @return string
	 * @static
	 */
	static function randomPassword() {
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
	 * Set cached properties to default. Note: this no longer clears 
	 * uncached lazy-initialised properties. The constructor does that instead.
	 *
	 * @private
	 */
	function loadDefaults( $name = false ) {
		wfProfileIn( __METHOD__ );

		global $wgCookiePrefix;

		$this->mId = 0;
		$this->mName = $name;
		$this->mRealName = '';
		$this->mPassword = $this->mNewpassword = '';
		$this->mNewpassTime = null;
		$this->mEmail = '';
		$this->mOptions = null; # Defer init

		if ( isset( $_COOKIE[$wgCookiePrefix.'LoggedOut'] ) ) {
			$this->mTouched = wfTimestamp( TS_MW, $_COOKIE[$wgCookiePrefix.'LoggedOut'] );
		} else {
			$this->mTouched = '0'; # Allow any pages to be cached
		}

		$this->setToken(); # Random
		$this->mEmailAuthenticated = null;
		$this->mEmailToken = '';
		$this->mEmailTokenExpires = null;
		$this->mRegistration = wfTimestamp( TS_MW );
		$this->mGroups = array();

		wfProfileOut( __METHOD__ );
	}
	
	/**
	 * Initialise php session
	 * @deprecated use wfSetupSession()
	 */
	function SetupSession() {
		wfSetupSession();
	}

	/**
	 * Load user data from the session or login cookie. If there are no valid
	 * credentials, initialises the user as an anon.
	 * @return true if the user is logged in, false otherwise
	 * 
	 * @private
	 */
	function loadFromSession() {
		global $wgMemc, $wgCookiePrefix;

		if ( isset( $_SESSION['wsUserID'] ) ) {
			if ( 0 != $_SESSION['wsUserID'] ) {
				$sId = $_SESSION['wsUserID'];
			} else {
				$this->loadDefaults();
				return false;
			}
		} else if ( isset( $_COOKIE["{$wgCookiePrefix}UserID"] ) ) {
			$sId = intval( $_COOKIE["{$wgCookiePrefix}UserID"] );
			$_SESSION['wsUserID'] = $sId;
		} else {
			$this->loadDefaults();
			return false;
		}
		if ( isset( $_SESSION['wsUserName'] ) ) {
			$sName = $_SESSION['wsUserName'];
		} else if ( isset( $_COOKIE["{$wgCookiePrefix}UserName"] ) ) {
			$sName = $_COOKIE["{$wgCookiePrefix}UserName"];
			$_SESSION['wsUserName'] = $sName;
		} else {
			$this->loadDefaults();
			return false;
		}

		$passwordCorrect = FALSE;
		$this->mId = $sId;
		if ( !$this->loadFromId() ) {
			# Not a valid ID, loadFromId has switched the object to anon for us
			return false;
		}
		
		if ( isset( $_SESSION['wsToken'] ) ) {
			$passwordCorrect = $_SESSION['wsToken'] == $this->mToken;
			$from = 'session';
		} else if ( isset( $_COOKIE["{$wgCookiePrefix}Token"] ) ) {
			$passwordCorrect = $this->mToken == $_COOKIE["{$wgCookiePrefix}Token"];
			$from = 'cookie';
		} else {
			# No session or persistent login cookie
			$this->loadDefaults();
			return false;
		}

		if ( ( $sName == $this->mName ) && $passwordCorrect ) {
			wfDebug( "Logged in from $from\n" );
			return true;
		} else {
			# Invalid credentials
			wfDebug( "Can't log in from $from, invalid credentials\n" );
			$this->loadDefaults();
			return false;
		}
	}
	
	/**
	 * Load user and user_group data from the database
	 * $this->mId must be set, this is how the user is identified.
	 * 
	 * @return true if the user exists, false if the user is anonymous
	 * @private
	 */
	function loadFromDatabase() {
		# Paranoia
		$this->mId = intval( $this->mId );

		/** Anonymous user */
		if( !$this->mId ) {
			$this->loadDefaults();
			return false;
		}

		$dbr = wfGetDB( DB_MASTER );
		$s = $dbr->selectRow( 'user', '*', array( 'user_id' => $this->mId ), __METHOD__ );

		if ( $s !== false ) {
			# Initialise user table data
			$this->mName = $s->user_name;
			$this->mRealName = $s->user_real_name;
			$this->mPassword = $s->user_password;
			$this->mNewpassword = $s->user_newpassword;
			$this->mNewpassTime = wfTimestampOrNull( TS_MW, $s->user_newpass_time );
			$this->mEmail = $s->user_email;
			$this->decodeOptions( $s->user_options );
			$this->mTouched = wfTimestamp(TS_MW,$s->user_touched);
			$this->mToken = $s->user_token;
			$this->mEmailAuthenticated = wfTimestampOrNull( TS_MW, $s->user_email_authenticated );
			$this->mEmailToken = $s->user_email_token;
			$this->mEmailTokenExpires = wfTimestampOrNull( TS_MW, $s->user_email_token_expires );
			$this->mRegistration = wfTimestampOrNull( TS_MW, $s->user_registration );
			$this->mEditCount = $s->user_editcount; 
			$this->getEditCount(); // revalidation for nulls

			# Load group data
			$res = $dbr->select( 'user_groups',
				array( 'ug_group' ),
				array( 'ug_user' => $this->mId ),
				__METHOD__ );
			$this->mGroups = array();
			while( $row = $dbr->fetchObject( $res ) ) {
				$this->mGroups[] = $row->ug_group;
			}
			return true;
		} else {
			# Invalid user_id
			$this->mId = 0;
			$this->loadDefaults();
			return false;
		}
	}

	/**
	 * Clear various cached data stored in this object. 
	 * @param string $reloadFrom Reload user and user_groups table data from a 
	 *   given source. May be "name", "id", "defaults", "session" or false for 
	 *   no reload.
	 */
	function clearInstanceCache( $reloadFrom = false ) {
		$this->mNewtalk = -1;
		$this->mDatePreference = null;
		$this->mBlockedby = -1; # Unset
		$this->mHash = false;
		$this->mSkin = null;
		$this->mRights = null;
		$this->mEffectiveGroups = null;

		if ( $reloadFrom ) {
			$this->mDataLoaded = false;
			$this->mFrom = $reloadFrom;
		}
	}

	/**
	 * Combine the language default options with any site-specific options
	 * and add the default language variants.
	 * Not really private cause it's called by Language class
	 * @return array
	 * @static
	 * @private
	 */
	static function getDefaultOptions() {
		global $wgNamespacesToBeSearchedDefault;
		/**
		 * Site defaults will override the global/language defaults
		 */
		global $wgDefaultUserOptions, $wgContLang;
		$defOpt = $wgDefaultUserOptions + $wgContLang->getDefaultUserOptionOverrides();

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

		wfProfileIn( __METHOD__ );
		wfDebug( __METHOD__.": checking...\n" );

		$this->mBlockedby = 0;
		$ip = wfGetIP();

		if ($this->isAllowed( 'ipblock-exempt' ) ) {
			# Exempt from all types of IP-block
			$ip = '';
		}

		# User/IP blocking
		$this->mBlock = new Block();
		$this->mBlock->fromMaster( !$bFromSlave );
		if ( $this->mBlock->load( $ip , $this->mId ) ) {
			wfDebug( __METHOD__.": Found block.\n" );
			$this->mBlockedby = $this->mBlock->mBy;
			$this->mBlockreason = $this->mBlock->mReason;
			if ( $this->isLoggedIn() ) {
				$this->spreadBlock();
			}
		} else {
			$this->mBlock = null;
			wfDebug( __METHOD__.": No block.\n" );
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

		wfProfileOut( __METHOD__ );
	}

	function inSorbsBlacklist( $ip ) {
		global $wgEnableSorbs, $wgSorbsUrl;

		return $wgEnableSorbs &&
			$this->inDnsBlacklist( $ip, $wgSorbsUrl );
	}

	function inDnsBlacklist( $ip, $base ) {
		wfProfileIn( __METHOD__ );

		$found = false;
		$host = '';

		$m = array();
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

		wfProfileOut( __METHOD__ );
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
	
		# Call the 'PingLimiter' hook
		$result = false;
		if( !wfRunHooks( 'PingLimiter', array( &$this, $action, $result ) ) ) {
			return $result;
		}
		
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
		wfProfileIn( __METHOD__ );

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
			$matches = array();
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
					wfDebug( __METHOD__.": tripped! $key at $count $summary\n" );
					if( $wgRateLimitLog ) {
						@error_log( wfTimestamp( TS_MW ) . ' ' . wfWikiID() . ': ' . $this->getName() . " tripped $key at $count $summary\n", 3, $wgRateLimitLog );
					}
					$triggered = true;
				} else {
					wfDebug( __METHOD__.": ok. $key at $count $summary\n" );
				}
			} else {
				wfDebug( __METHOD__.": adding record for $key $summary\n" );
				$wgMemc->add( $key, 1, intval( $period ) );
			}
			$wgMemc->incr( $key );
		}

		wfProfileOut( __METHOD__ );
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
		wfProfileIn( __METHOD__ );
		wfDebug( __METHOD__.": enter\n" );

		if ( $wgBlockAllowsUTEdit && $title->getText() === $this->getName() &&
		  $title->getNamespace() == NS_USER_TALK )
		{
			$blocked = false;
			wfDebug( __METHOD__.": self-talk page, ignoring any blocks\n" );
		} else {
			wfDebug( __METHOD__.": asking isBlocked()\n" );
			$blocked = $this->isBlocked( $bFromSlave );
		}
		wfProfileOut( __METHOD__ );
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
	 * Get the user ID. Returns 0 if the user is anonymous or nonexistent.
	 */
	function getID() { 
		$this->load();
		return $this->mId; 
	}

	/**
	 * Set the user and reload all fields according to that ID
	 * @deprecated use User::newFromId()
	 */
	function setID( $v ) {
		$this->mId = $v;
		$this->clearInstanceCache( 'id' );
	}

	/**
	 * Get the user name, or the IP for anons
	 */
	function getName() {
		if ( !$this->mDataLoaded && $this->mFrom == 'name' ) {
			# Special case optimisation
			return $this->mName;
		} else {
			$this->load();
			if ( $this->mName === false ) {
				$this->mName = wfGetIP();
			}
			return $this->mName;
		}
	}

	/**
	 * Set the user name. 
	 *
	 * This does not reload fields from the database according to the given 
	 * name. Rather, it is used to create a temporary "nonexistent user" for
	 * later addition to the database. It can also be used to set the IP 
	 * address for an anonymous user to something other than the current 
	 * remote IP.
	 *
	 * User::newFromName() has rougly the same function, when the named user
	 * does not exist.
	 */
	function setName( $str ) {
		$this->load();
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
		$this->load();

		# Load the newtalk status if it is unloaded (mNewtalk=-1)
		if( $this->mNewtalk === -1 ) {
			$this->mNewtalk = false; # reset talk page status

			# Check memcached separately for anons, who have no
			# entire User object stored in there.
			if( !$this->mId ) {
				global $wgMemc;
				$key = wfMemcKey( 'newtalk', 'ip', $this->getName() );
				$newtalk = $wgMemc->get( $key );
				if( $newtalk != "" ) {
					$this->mNewtalk = (bool)$newtalk;
				} else {
					$this->mNewtalk = $this->checkNewtalk( 'user_ip', $this->getName() );
					$wgMemc->set( $key, (int)$this->mNewtalk, time() + 1800 );
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
		$dbr = wfGetDB( DB_SLAVE );
		$ok = $dbr->selectField( 'user_newtalk', $field,
			array( $field => $id ), __METHOD__ );
		return $ok !== false;
	}

	/**
	 * Add or update the
	 * @param string $field
	 * @param mixed $id
	 * @private
	 */
	function updateNewtalk( $field, $id ) {
		if( $this->checkNewtalk( $field, $id ) ) {
			wfDebug( __METHOD__." already set ($field, $id), ignoring\n" );
			return false;
		}
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert( 'user_newtalk',
			array( $field => $id ),
			__METHOD__,
			'IGNORE' );
		wfDebug( __METHOD__.": set on ($field, $id)\n" );
		return true;
	}

	/**
	 * Clear the new messages flag for the given user
	 * @param string $field
	 * @param mixed $id
	 * @private
	 */
	function deleteNewtalk( $field, $id ) {
		if( !$this->checkNewtalk( $field, $id ) ) {
			wfDebug( __METHOD__.": already gone ($field, $id), ignoring\n" );
			return false;
		}
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'user_newtalk',
			array( $field => $id ),
			__METHOD__ );
		wfDebug( __METHOD__.": killed on ($field, $id)\n" );
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

		$this->load();
		$this->mNewtalk = $val;

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
	private function clearSharedCache() {
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
		$this->load();
		if( $this->mId ) {
			$this->mTouched = self::newTouchedTimestamp();
			
			$dbw = wfGetDB( DB_MASTER );
			$dbw->update( 'user',
				array( 'user_touched' => $dbw->timestamp( $this->mTouched ) ),
				array( 'user_id' => $this->mId ),
				__METHOD__ );
			
			$this->clearSharedCache();
		}
	}

	function validateCache( $timestamp ) {
		$this->load();
		return ($timestamp >= $this->mTouched);
	}

	/**
	 * Encrypt a password.
	 * It can eventuall salt a password @see User::addSalt()
	 * @param string $p clear Password.
	 * @return string Encrypted password.
	 */
	function encryptPassword( $p ) {
		$this->load();
		return wfEncryptPassword( $this->mId, $p );
	}

	/**
	 * Set the password and reset the random token
	 * Calls through to authentication plugin if necessary;
	 * will have no effect if the auth plugin refuses to
	 * pass the change through or if the legal password
	 * checks fail.
	 *
	 * As a special case, setting the password to null
	 * wipes it, so the account cannot be logged in until
	 * a new password is set, for instance via e-mail.
	 *
	 * @param string $str
	 * @throws PasswordError on failure
	 */
	function setPassword( $str ) {
		global $wgAuth;
		
		if( $str !== null ) {
			if( !$wgAuth->allowPasswordChange() ) {
				throw new PasswordError( wfMsg( 'password-change-forbidden' ) );
			}
		
			if( !$this->isValidPassword( $str ) ) {
				global $wgMinimalPasswordLength;
				throw new PasswordError( wfMsg( 'passwordtooshort',
					$wgMinimalPasswordLength ) );
			}
		}
		
		if( !$wgAuth->setPassword( $this, $str ) ) {
			throw new PasswordError( wfMsg( 'externaldberror' ) );
		}
		
		$this->load();
		$this->setToken();
		
		if( $str === null ) {
			// Save an invalid hash...
			$this->mPassword = '';
		} else {
			$this->mPassword = $this->encryptPassword( $str );
		}
		$this->mNewpassword = '';
		$this->mNewpassTime = null;
		
		return true;
	}

	/**
	 * Set the random token (used for persistent authentication)
	 * Called from loadDefaults() among other places.
	 * @private
	 */
	function setToken( $token = false ) {
		global $wgSecretKey, $wgProxyKey;
		$this->load();
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
		$this->load();
		$this->mCookiePassword = md5( $str );
	}

	/**
	 * Set the password for a password reminder or new account email
	 * Sets the user_newpass_time field if $throttle is true
	 */
	function setNewpassword( $str, $throttle = true ) {
		$this->load();
		$this->mNewpassword = $this->encryptPassword( $str );
		if ( $throttle ) {
			$this->mNewpassTime = wfTimestampNow();
		}
	}

	/**
	 * Returns true if a password reminder email has already been sent within
	 * the last $wgPasswordReminderResendTime hours
	 */
	function isPasswordReminderThrottled() {
		global $wgPasswordReminderResendTime;
		$this->load();
		if ( !$this->mNewpassTime || !$wgPasswordReminderResendTime ) {
			return false;
		}
		$expiry = wfTimestamp( TS_UNIX, $this->mNewpassTime ) + $wgPasswordReminderResendTime * 3600;
		return time() < $expiry;
	}
	
	function getEmail() {
		$this->load();
		return $this->mEmail;
	}

	function getEmailAuthenticationTimestamp() {
		$this->load();
		return $this->mEmailAuthenticated;
	}

	function setEmail( $str ) {
		$this->load();
		$this->mEmail = $str;
	}

	function getRealName() {
		$this->load();
		return $this->mRealName;
	}

	function setRealName( $str ) {
		$this->load();
		$this->mRealName = $str;
	}

	/**
	 * @param string $oname The option to check
	 * @param string $defaultOverride A default value returned if the option does not exist
	 * @return string
	 */
	function getOption( $oname, $defaultOverride = '' ) {
		$this->load();

		if ( is_null( $this->mOptions ) ) {
			if($defaultOverride != '') {
				return $defaultOverride;
			}
			$this->mOptions = User::getDefaultOptions();
		}

		if ( array_key_exists( $oname, $this->mOptions ) ) {
			return trim( $this->mOptions[$oname] );
		} else {
			return $defaultOverride;
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
		$this->load();
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
		if ( is_null( $this->mRights ) ) {
			$this->mRights = self::getGroupPermissions( $this->getEffectiveGroups() );
		}
		return $this->mRights;
	}

	/**
	 * Get the list of explicit group memberships this user has.
	 * The implicit * and user groups are not included.
	 * @return array of strings
	 */
	function getGroups() {
		$this->load();
		return $this->mGroups;
	}

	/**
	 * Get the list of implicit group memberships this user has.
	 * This includes all explicit groups, plus 'user' if logged in
	 * and '*' for all accounts.
	 * @param boolean $recache Don't use the cache
	 * @return array of strings
	 */
	function getEffectiveGroups( $recache = false ) {
		if ( $recache || is_null( $this->mEffectiveGroups ) ) {
			$this->load();
			$this->mEffectiveGroups = $this->mGroups;
			$this->mEffectiveGroups[] = '*';
			if( $this->mId ) {
				$this->mEffectiveGroups[] = 'user';
				
				global $wgAutoConfirmAge, $wgAutoConfirmCount;

				$accountAge = time() - wfTimestampOrNull( TS_UNIX, $this->mRegistration );
				if( $accountAge >= $wgAutoConfirmAge && $this->getEditCount() >= $wgAutoConfirmCount ) {
					$this->mEffectiveGroups[] = 'autoconfirmed';
				}
				# Implicit group for users whose email addresses are confirmed
				global $wgEmailAuthentication;
				if( self::isValidEmailAddr( $this->mEmail ) ) {
					if( $wgEmailAuthentication ) {
						if( $this->mEmailAuthenticated )
							$this->mEffectiveGroups[] = 'emailconfirmed';
					} else {
						$this->mEffectiveGroups[] = 'emailconfirmed';
					}
				}
			}
		}
		return $this->mEffectiveGroups;
	}
	
	/* Return the edit count for the user. This is where User::edits should have been */
	function getEditCount() {
		if ($this->mId) {
			if ( !isset( $this->mEditCount ) ) {
				/* Populate the count, if it has not been populated yet */
				$this->mEditCount = User::edits($this->mId);
			} 
			return $this->mEditCount;
		} else {
			/* nil */
			return null;
		}
	}
	
	/**
	 * Add the user to the given group.
	 * This takes immediate effect.
	 * @string $group
	 */
	function addGroup( $group ) {
		$this->load();
		$dbw = wfGetDB( DB_MASTER );
		if( $this->getId() ) {
			$dbw->insert( 'user_groups',
				array(
					'ug_user'  => $this->getID(),
					'ug_group' => $group,
				),
				'User::addGroup',
				array( 'IGNORE' ) );
		}

		$this->mGroups[] = $group;
		$this->mRights = User::getGroupPermissions( $this->getEffectiveGroups( true ) );

		$this->invalidateCache();
	}

	/**
	 * Remove the user from the given group.
	 * This takes immediate effect.
	 * @string $group
	 */
	function removeGroup( $group ) {
		$this->load();
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'user_groups',
			array(
				'ug_user'  => $this->getID(),
				'ug_group' => $group,
			),
			'User::removeGroup' );

		$this->mGroups = array_diff( $this->mGroups, array( $group ) );
		$this->mRights = User::getGroupPermissions( $this->getEffectiveGroups( true ) );

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

		return in_array( $action, $this->getRights() );
	}

	/**
	 * Load a skin if it doesn't exist or return it
	 * @todo FIXME : need to check the old failback system [AV]
	 */
	function &getSkin() {
		global $wgRequest;
		if ( ! isset( $this->mSkin ) ) {
			wfProfileIn( __METHOD__ );

			# get the user skin
			$userSkin = $this->getOption( 'skin' );
			$userSkin = $wgRequest->getVal('useskin', $userSkin);

			$this->mSkin =& Skin::newFromKey( $userSkin );
			wfProfileOut( __METHOD__ );
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

		# Do nothing if the database is locked to writes
		if( wfReadOnly() ) {
			return;
		}

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
			$dbw = wfGetDB( DB_MASTER );
			$dbw->update( 'watchlist',
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

			$dbw = wfGetDB( DB_MASTER );
			$dbw->update( 'watchlist',
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
		$this->load();
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
		$this->mOptions = array();
		$a = explode( "\n", $str );
		foreach ( $a as $s ) {
			$m = array();
			if ( preg_match( "/^(.[^=]*)=(.*)$/", $s, $m ) ) {
				$this->mOptions[$m[1]] = $m[2];
			}
		}
	}

	function setCookies() {
		global $wgCookieExpiration, $wgCookiePath, $wgCookieDomain, $wgCookieSecure, $wgCookiePrefix;
		$this->load();
		if ( 0 == $this->mId ) return;
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
	 * Clears the cookies and session, resets the instance cache
	 */
	function logout() {
		global $wgCookiePath, $wgCookieDomain, $wgCookieSecure, $wgCookiePrefix;
		$this->clearInstanceCache( 'defaults' );

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
		$this->load();
		if ( wfReadOnly() ) { return; }
		if ( 0 == $this->mId ) { return; }
		
		$this->mTouched = self::newTouchedTimestamp();

		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'user',
			array( /* SET */
				'user_name' => $this->mName,
				'user_password' => $this->mPassword,
				'user_newpassword' => $this->mNewpassword,
				'user_newpass_time' => $dbw->timestampOrNull( $this->mNewpassTime ),
				'user_real_name' => $this->mRealName,
		 		'user_email' => $this->mEmail,
		 		'user_email_authenticated' => $dbw->timestampOrNull( $this->mEmailAuthenticated ),
				'user_options' => $this->encodeOptions(),
				'user_touched' => $dbw->timestamp($this->mTouched),
				'user_token' => $this->mToken
			), array( /* WHERE */
				'user_id' => $this->mId
			), __METHOD__
		);
		$this->clearSharedCache();
	}


	/**
	 * Checks if a user with the given name exists, returns the ID
	 */
	function idForName() {
		$s = trim( $this->getName() );
		if ( 0 == strcmp( '', $s ) ) return 0;

		$dbr = wfGetDB( DB_SLAVE );
		$id = $dbr->selectField( 'user', 'user_id', array( 'user_name' => $s ), __METHOD__ );
		if ( $id === false ) {
			$id = 0;
		}
		return $id;
	}

	/**
	 * Add a user to the database, return the user object
	 *
	 * @param string $name The user's name
	 * @param array $params Associative array of non-default parameters to save to the database:
	 *     password             The user's password. Password logins will be disabled if this is omitted.
	 *     newpassword          A temporary password mailed to the user
	 *     email                The user's email address
	 *     email_authenticated  The email authentication timestamp
	 *     real_name            The user's real name
	 *     options              An associative array of non-default options
	 *     token                Random authentication token. Do not set.
	 *     registration         Registration timestamp. Do not set.
	 *
	 * @return User object, or null if the username already exists
	 */
	static function createNew( $name, $params = array() ) {
		$user = new User;
		$user->load();
		if ( isset( $params['options'] ) ) {
			$user->mOptions = $params['options'] + $user->mOptions;
			unset( $params['options'] );
		}
		$dbw = wfGetDB( DB_MASTER );
		$seqVal = $dbw->nextSequenceValue( 'user_user_id_seq' );
		$fields = array(
			'user_id' => $seqVal,
			'user_name' => $name,
			'user_password' => $user->mPassword,
			'user_newpassword' => $user->mNewpassword,
			'user_newpass_time' => $dbw->timestamp( $user->mNewpassTime ),
			'user_email' => $user->mEmail,
			'user_email_authenticated' => $dbw->timestampOrNull( $user->mEmailAuthenticated ),
			'user_real_name' => $user->mRealName,
			'user_options' => $user->encodeOptions(),
			'user_token' => $user->mToken,
			'user_registration' => $dbw->timestamp( $user->mRegistration ),
			'user_editcount' => 0,
		);
		foreach ( $params as $name => $value ) {
			$fields["user_$name"] = $value;
		}
		$dbw->insert( 'user', $fields, __METHOD__, array( 'IGNORE' ) );
		if ( $dbw->affectedRows() ) {
			$newUser = User::newFromId( $dbw->insertId() );
		} else {
			$newUser = null;
		}
		return $newUser;
	}
	
	/**
	 * Add an existing user object to the database
	 */
	function addToDatabase() {
		$this->load();
		$dbw = wfGetDB( DB_MASTER );
		$seqVal = $dbw->nextSequenceValue( 'user_user_id_seq' );
		$dbw->insert( 'user',
			array(
				'user_id' => $seqVal,
				'user_name' => $this->mName,
				'user_password' => $this->mPassword,
				'user_newpassword' => $this->mNewpassword,
				'user_newpass_time' => $dbw->timestamp( $this->mNewpassTime ),
				'user_email' => $this->mEmail,
				'user_email_authenticated' => $dbw->timestampOrNull( $this->mEmailAuthenticated ),
				'user_real_name' => $this->mRealName,
				'user_options' => $this->encodeOptions(),
				'user_token' => $this->mToken,
				'user_registration' => $dbw->timestamp( $this->mRegistration ),
				'user_editcount' => 0,
			), __METHOD__
		);
		$this->mId = $dbw->insertId();

		# Clear instance cache other than user table data, which is already accurate
		$this->clearInstanceCache();
	}

	/**
	 * If the (non-anonymous) user is blocked, this function will block any IP address
	 * that they successfully log on from.
	 */
	function spreadBlock() {
		wfDebug( __METHOD__."()\n" );
		$this->load();
		if ( $this->mId == 0 ) {
			return;
		}

		$userblock = Block::newFromDB( '', $this->mId );
		if ( !$userblock ) {
			return;
		}

		$userblock->doAutoblock( wfGetIp() );

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
		global $wgContLang, $wgUseDynamicDates, $wgLang;
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
		$confstr .= '!' . $wgLang->getCode();
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
	 * @deprecated
	 */
	function setLoaded( $loaded ) {}

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
			$dbr = wfGetDB( DB_SLAVE );
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
		global $wgAuth;
		$this->load();

		// Even though we stop people from creating passwords that
		// are shorter than this, doesn't mean people wont be able
		// to. Certain authentication plugins do NOT want to save
		// domain passwords in a mysql database, so we should
		// check this (incase $wgAuth->strict() is false).
		if( !$this->isValidPassword( $password ) ) {
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
		} elseif ( function_exists( 'iconv' ) ) {
			# Some wikis were converted from ISO 8859-1 to UTF-8, the passwords can't be converted
			# Check for this with iconv
			$cp1252hash = $this->encryptPassword( iconv( 'UTF-8', 'WINDOWS-1252//TRANSLIT', $password ) );
			if ( 0 == strcmp( $cp1252hash, $this->mPassword ) ) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Check if the given clear-text password matches the temporary password
	 * sent by e-mail for password reset operations.
	 * @return bool
	 */
	function checkTemporaryPassword( $plaintext ) {
		$hash = $this->encryptPassword( $plaintext );
		return $hash === $this->mNewpassword;
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
		return md5( $token . $salt ) . EDIT_TOKEN_SUFFIX;
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
		$expiration = null; // gets passed-by-ref and defined in next line.
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
		$now = time();
		$expires = $now + 7 * 24 * 60 * 60;
		$expiration = wfTimestamp( TS_MW, $expires );

		$token = $this->generateToken( $this->mId . $this->mEmail . $expires );
		$hash = md5( $token );

		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'user',
			array( 'user_email_token'         => $hash,
			       'user_email_token_expires' => $dbw->timestamp( $expires ) ),
			array( 'user_id'                  => $this->mId ),
			__METHOD__ );

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
		$title = SpecialPage::getTitleFor( 'Confirmemail', $token );
		return $title->getFullUrl();
	}

	/**
	 * Mark the e-mail address confirmed and save.
	 */
	function confirmEmail() {
		$this->load();
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
		$this->load();
		$confirmed = true;
		if( wfRunHooks( 'EmailConfirmed', array( &$this, &$confirmed ) ) ) {
			if( $this->isAnon() )
				return false;
			if( !self::isValidEmailAddr( $this->mEmail ) )
				return false;
			if( $wgEmailAuthentication && !$this->getEmailAuthenticationTimestamp() )
				return false;
			return true;
		} else {
			return $confirmed;
		}
	}
	
	/**
	 * Return true if there is an outstanding request for e-mail confirmation.
	 * @return bool
	 */
	function isEmailConfirmationPending() {
		global $wgEmailAuthentication;
		return $wgEmailAuthentication &&
			!$this->isEmailConfirmed() &&
			$this->mEmailToken &&
			$this->mEmailTokenExpires > wfTimestamp();
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
			return $sk->makeLinkObj( $title, htmlspecialchars( $text ) );
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
	
	/**
	 * Increment the user's edit-count field.
	 * Will have no effect for anonymous users.
	 */
	function incEditCount() {
		if( !$this->isAnon() ) {
			$dbw = wfGetDB( DB_MASTER );
			$dbw->update( 'user',
				array( 'user_editcount=user_editcount+1' ),
				array( 'user_id' => $this->getId() ),
				__METHOD__ );
			
			// Lazy initialization check...
			if( $dbw->affectedRows() == 0 ) {
				// Pull from a slave to be less cruel to servers
				// Accuracy isn't the point anyway here
				$dbr = wfGetDB( DB_SLAVE );
				$count = $dbr->selectField( 'revision',
					'COUNT(rev_user)',
					array( 'rev_user' => $this->getId() ),
					__METHOD__ );
				
				// Now here's a goddamn hack...
				if( $dbr !== $dbw ) {
					// If we actually have a slave server, the count is
					// at least one behind because the current transaction
					// has not been committed and replicated.
					$count++;
				} else {
					// But if DB_SLAVE is selecting the master, then the
					// count we just read includes the revision that was
					// just added in the working transaction.
				}
				
				$dbw->update( 'user',
					array( 'user_editcount' => $count ),
					array( 'user_id' => $this->getId() ),
					__METHOD__ );
			}
		}
		// edit count in user cache too
		$this->invalidateCache();
	}
}

?>
