<?php
/**
 * Implements the User class for the %MediaWiki software.
 * @file
 */

/**
 * \int Number of characters in user_token field.
 * @ingroup Constants
 */
define( 'USER_TOKEN_LENGTH', 32 );

/**
 * \int Serialized record version.
 * @ingroup Constants
 */
define( 'MW_USER_VERSION', 6 );

/**
 * \string Some punctuation to prevent editing from broken text-mangling proxies.
 * @ingroup Constants
 */
define( 'EDIT_TOKEN_SUFFIX', '+\\' );

/**
 * Thrown by User::setPassword() on error.
 * @ingroup Exception
 */
class PasswordError extends MWException {
	// NOP
}

/**
 * The User object encapsulates all of the user-specific settings (user_id,
 * name, rights, password, email address, options, last login time). Client
 * classes use the getXXX() functions to access these fields. These functions
 * do all the work of determining whether the user is logged in,
 * whether the requested option can be satisfied from cookies or
 * whether a database query is needed. Most of the settings needed
 * for rendering normal pages are set in the cookie to minimize use
 * of the database.
 */
class User {

	/**
	 * \type{\arrayof{\string}} A list of default user toggles, i.e., boolean user 
         * preferences that are displayed by Special:Preferences as checkboxes.
	 * This list can be extended via the UserToggles hook or by
	 * $wgContLang::getExtraUserToggles().
	 * @showinitializer
	 */
	public static $mToggles = array(
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
		'watchlisthideminor',
		'watchlisthidebots',
		'watchlisthideown',
		'watchlisthideanons',
		'watchlisthideliu',
		'ccmeonemails',
		'diffonly',
		'showhiddencats',
		'noconvertlink',
		'norollbackdiff',
	);

	/**
	 * \type{\arrayof{\string}} List of member variables which are saved to the 
	 * shared cache (memcached). Any operation which changes the 
	 * corresponding database fields must call a cache-clearing function.
	 * @showinitializer
	 */
	static $mCacheVars = array(
		// user table
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
		// user_group table
		'mGroups',
	);

	/**
	 * \type{\arrayof{\string}} Core rights.
	 * Each of these should have a corresponding message of the form 
	 * "right-$right".
	 * @showinitializer
	 */
	static $mCoreRights = array(
		'apihighlimits',
		'autoconfirmed',
		'autopatrol',
		'bigdelete',
		'block',
		'blockemail',
		'bot',
		'browsearchive',
		'createaccount',
		'createpage',
		'createtalk',
		'delete',
		'deletedhistory',
		'edit',
		'editinterface',
		'editusercssjs',
		'import',
		'importupload',
		'ipblock-exempt',
		'markbotedits',
		'minoredit',
		'move',
		'movefile',
		'move-rootuserpages',
		'move-subpages',
		'nominornewtalk',
		'noratelimit',
		'patrol',
		'protect',
		'proxyunbannable',
		'purge',
		'read',
		'reupload',
		'reupload-shared',
		'rollback',
		'siteadmin',
		'suppressredirect',
		'trackback',
		'undelete',
		'unwatchedpages',
		'upload',
		'upload_by_url',
		'userrights',
	);
	/**
	 * \string Cached results of getAllRights()
	 */
	static $mAllRights = false;

	/** @name Cache variables */
	//@{
	var $mId, $mName, $mRealName, $mPassword, $mNewpassword, $mNewpassTime,
		$mEmail, $mOptions, $mTouched, $mToken, $mEmailAuthenticated,
		$mEmailToken, $mEmailTokenExpires, $mRegistration, $mGroups;
	//@}

	/**
	 * \bool Whether the cache variables have been loaded.
	 */
	var $mDataLoaded, $mAuthLoaded;

	/**
	 * \string Initialization data source if mDataLoaded==false. May be one of:
	 *  - 'defaults'   anonymous user initialised from class defaults
	 *  - 'name'       initialise from mName
	 *  - 'id'         initialise from mId
	 *  - 'session'    log in from cookies or session if possible
	 *
	 * Use the User::newFrom*() family of functions to set this.
	 */
	var $mFrom;

	/** @name Lazy-initialized variables, invalidated with clearInstanceCache */
	//@{
	var $mNewtalk, $mDatePreference, $mBlockedby, $mHash, $mSkin, $mRights,
		$mBlockreason, $mBlock, $mEffectiveGroups, $mBlockedGlobally, 
		$mLocked, $mHideName;
	//@}

	/**
	 * Lightweight constructor for an anonymous user.
	 * Use the User::newFrom* factory functions for other kinds of users.
	 * 
	 * @see newFromName()
	 * @see newFromId()
	 * @see newFromConfirmationCode()
	 * @see newFromSession()
	 * @see newFromRow()
	 */
	function User() {
		$this->clearInstanceCache( 'defaults' );
	}

	/**
	 * Load the user table data for this object from the source given by mFrom.
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
				wfRunHooks( 'UserLoadAfterLoadFromSession', array( $this ) );
				break;
			default:
				throw new MWException( "Unrecognised value for User->mFrom: \"{$this->mFrom}\"" );
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Load user table data, given mId has already been set.
	 * @return \bool false if the ID does not exist, true otherwise
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
			$this->saveToCache();
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
	 * Save user data to the shared cache
	 */
	function saveToCache() {
		$this->load();
		$this->loadGroups();
		if ( $this->isAnon() ) {
			// Anonymous users are uncached
			return;
		}
		$data = array();
		foreach ( self::$mCacheVars as $name ) {
			$data[$name] = $this->$name;
		}
		$data['mVersion'] = MW_USER_VERSION;
		$key = wfMemcKey( 'user', 'id', $this->mId );
		global $wgMemc;
		$wgMemc->set( $key, $data );
	}
	
	
	/** @name newFrom*() static factory methods */
	//@{

	/**
	 * Static factory method for creation from username.
	 *
	 * This is slightly less efficient than newFromId(), so use newFromId() if
	 * you have both an ID and a name handy.
	 *
	 * @param $name \string Username, validated by Title::newFromText()
	 * @param $validate \mixed Validate username. Takes the same parameters as
	 *    User::getCanonicalName(), except that true is accepted as an alias
	 *    for 'valid', for BC.
	 *
	 * @return \type{User} The User object, or null if the username is invalid. If the 
	 *    username is not present in the database, the result will be a user object 
	 *    with a name, zero user ID and default settings.
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

	/**
	 * Static factory method for creation from a given user ID.
	 *
	 * @param $id \int Valid user ID
	 * @return \type{User} The corresponding User object
	 */
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
	 * @param $code \string Confirmation code
	 * @return \type{User}
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
	 * @return \type{User}
	 */
	static function newFromSession() {
		$user = new User;
		$user->mFrom = 'session';
		return $user;
	}

	/**
	 * Create a new user object from a user row.
	 * The row should have all fields from the user table in it.
	 * @param $row array A row from the user table
	 * @return \type{User}
	 */
	static function newFromRow( $row ) {
		$user = new User;
		$user->loadFromRow( $row );
		return $user;
	}
	
	//@}
	

	/**
	 * Get the username corresponding to a given user ID
	 * @param $id \int User ID
	 * @return \string The corresponding username
	 */
	static function whoIs( $id ) {
		$dbr = wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'user', 'user_name', array( 'user_id' => $id ), 'User::whoIs' );
	}

	/**
	 * Get the real name of a user given their user ID
	 *
	 * @param $id \int User ID
	 * @return \string The corresponding user's real name
	 */
	static function whoIsReal( $id ) {
		$dbr = wfGetDB( DB_SLAVE );
		return $dbr->selectField( 'user', 'user_real_name', array( 'user_id' => $id ), __METHOD__ );
	}

	/**
	 * Get database id given a user name
	 * @param $name \string Username
	 * @return \types{\int,\null} The corresponding user's ID, or null if user is nonexistent
	 */
	static function idFromName( $name ) {
		$nt = Title::makeTitleSafe( NS_USER, $name );
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
	 * @param $name \string String to match
	 * @return \bool True or false
	 */
	static function isIP( $name ) {
		return preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.(?:xxx|\d{1,3})$/',$name) || IP::isIPv6($name);
	}

	/**
	 * Is the input a valid username?
	 *
	 * Checks if the input is a valid username, we don't want an empty string,
	 * an IP address, anything that containins slashes (would mess up subpages),
	 * is longer than the maximum allowed username size or doesn't begin with
	 * a capital letter.
	 *
	 * @param $name \string String to match
	 * @return \bool True or false
	 */
	static function isValidUserName( $name ) {
		global $wgContLang, $wgMaxNameChars;

		if ( $name == ''
		|| User::isIP( $name )
		|| strpos( $name, '/' ) !== false
		|| strlen( $name ) > $wgMaxNameChars
		|| $name != $wgContLang->ucfirst( $name ) ) {
			wfDebugLog( 'username', __METHOD__ .
				": '$name' invalid due to empty, IP, slash, length, or lowercase" );
			return false;
		}

		// Ensure that the name can't be misresolved as a different title,
		// such as with extra namespace keys at the start.
		$parsed = Title::newFromText( $name );
		if( is_null( $parsed )
			|| $parsed->getNamespace()
			|| strcmp( $name, $parsed->getPrefixedText() ) ) {
			wfDebugLog( 'username', __METHOD__ .
				": '$name' invalid due to ambiguous prefixes" );
			return false;
		}

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
			wfDebugLog( 'username', __METHOD__ .
				": '$name' invalid due to blacklisted characters" );
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
	 * @param $name \string String to match
	 * @return \bool True or false
	 */
	static function isUsableName( $name ) {
		global $wgReservedUsernames;
		// Must be a valid username, obviously ;)
		if ( !self::isValidUserName( $name ) ) {
			return false;
		}

		static $reservedUsernames = false;
		if ( !$reservedUsernames ) {
			$reservedUsernames = $wgReservedUsernames;
			wfRunHooks( 'UserGetReservedNames', array( &$reservedUsernames ) );
		}

		// Certain names may be reserved for batch processes.
		foreach ( $reservedUsernames as $reserved ) {
			if ( substr( $reserved, 0, 4 ) == 'msg:' ) {
				$reserved = wfMsgForContent( substr( $reserved, 4 ) );
			}
			if ( $reserved == $name ) {
				return false;
			}
		}
		return true;
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
	 * @param $name \string String to match
	 * @return \bool True or false
	 */
	static function isCreatableName( $name ) {
		return
			self::isUsableName( $name ) &&

			// Registration-time character blacklisting...
			strpos( $name, '@' ) === false;
	}

	/**
	 * Is the input a valid password for this user?
	 *
	 * @param $password \string Desired password
	 * @return \bool True or false
	 */
	function isValidPassword( $password ) {
		global $wgMinimalPasswordLength, $wgContLang;

		$result = null;
		if( !wfRunHooks( 'isValidPassword', array( $password, &$result, $this ) ) )
			return $result;
		if( $result === false )
			return false;

		// Password needs to be long enough, and can't be the same as the username
		return strlen( $password ) >= $wgMinimalPasswordLength
			&& $wgContLang->lc( $password ) !== $wgContLang->lc( $this->mName );
	}

	/**
	 * Does a string look like an e-mail address?
	 *
	 * There used to be a regular expression here, it got removed because it
	 * rejected valid addresses. Actually just check if there is '@' somewhere
	 * in the given address.
	 *
	 * @todo Check for RFC 2822 compilance (bug 959)
	 *
	 * @param $addr \string E-mail address
	 * @return \bool True or false
	 */
	public static function isValidEmailAddr( $addr ) {
		$result = null;
		if( !wfRunHooks( 'isValidEmailAddr', array( $addr, &$result ) ) ) {
			return $result;
		}

		return strpos( $addr, '@' ) !== false;
	}

	/**
	 * Given unvalidated user input, return a canonical username, or false if
	 * the username is invalid.
	 * @param $name \string User input
	 * @param $validate \types{\string,\bool} Type of validation to use:
	 *                - false        No validation
	 *                - 'valid'      Valid for batch processes
	 *                - 'usable'     Valid for batch processes and login
	 *                - 'creatable'  Valid for batch processes, login and account creation
	 */
	static function getCanonicalName( $name, $validate = 'valid' ) {
		# Force usernames to capital
		global $wgContLang;
		$name = $wgContLang->ucfirst( $name );

		# Reject names containing '#'; these will be cleaned up
		# with title normalisation, but then it's too late to
		# check elsewhere
		if( strpos( $name, '#' ) !== false )
			return false;

		# Clean up name according to title rules
		$t = ($validate === 'valid') ? 
			Title::newFromText( $name ) : Title::makeTitle( NS_USER, $name );
		# Check for invalid titles
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
	 * @todo It should not be static and some day should be merged as proper member function / deprecated -- domas
	 *
	 * @param $uid \int User ID to check
	 * @return \int The user's edit count
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
			$dbw = wfGetDB( DB_MASTER );
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
	 * @todo hash random numbers to improve security, like generateToken()
	 *
	 * @return \string New random password
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
	 * Set cached properties to default. 
	 *
	 * @note This no longer clears uncached lazy-initialised properties; 
	 *       the constructor does that instead.
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

		wfRunHooks( 'UserLoadDefaults', array( $this, $name ) );

		wfProfileOut( __METHOD__ );
	}

	/**
	 * @deprecated Use wfSetupSession().
	 */
	function SetupSession() {
		wfDeprecated( __METHOD__ );
		wfSetupSession();
	}

	/**
	 * Load user data from the session or login cookie. If there are no valid
	 * credentials, initialises the user as an anonymous user.
	 * @return \bool True if the user is logged in, false otherwise.
	 */
	private function loadFromSession() {
		global $wgMemc, $wgCookiePrefix;

		$result = null;
		wfRunHooks( 'UserLoadFromSession', array( $this, &$result ) );
		if ( $result !== null ) {
			return $result;
		}

		if ( isset( $_COOKIE["{$wgCookiePrefix}UserID"] ) ) {
			$sId = intval( $_COOKIE["{$wgCookiePrefix}UserID"] );
			if( isset( $_SESSION['wsUserID'] ) && $sId != $_SESSION['wsUserID'] ) {
				$this->loadDefaults(); // Possible collision!
				wfDebugLog( 'loginSessions', "Session user ID ({$_SESSION['wsUserID']}) and 
					cookie user ID ($sId) don't match!" );
				return false;
			}
			$_SESSION['wsUserID'] = $sId;
		} else if ( isset( $_SESSION['wsUserID'] ) ) {
			if ( $_SESSION['wsUserID'] != 0 ) {
				$sId = $_SESSION['wsUserID'];
			} else {
				$this->loadDefaults();
				return false;
			}
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
			$_SESSION['wsToken'] = $this->mToken;
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
	 * Load user and user_group data from the database.
	 * $this::mId must be set, this is how the user is identified.
	 *
	 * @return \bool True if the user exists, false if the user is anonymous
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

		wfRunHooks( 'UserLoadFromDatabase', array( $this, &$s ) );

		if ( $s !== false ) {
			# Initialise user table data
			$this->loadFromRow( $s );
			$this->mGroups = null; // deferred
			$this->getEditCount(); // revalidation for nulls
			return true;
		} else {
			# Invalid user_id
			$this->mId = 0;
			$this->loadDefaults();
			return false;
		}
	}

	/**
	 * Initialize this object from a row from the user table.
	 *
	 * @param $row \type{\arrayof{\mixed}} Row from the user table to load.
	 */
	function loadFromRow( $row ) {
		$this->mDataLoaded = true;

		if ( isset( $row->user_id ) ) {
			$this->mId = $row->user_id;
		}
		$this->mName = $row->user_name;
		$this->mRealName = $row->user_real_name;
		$this->mPassword = $row->user_password;
		$this->mNewpassword = $row->user_newpassword;
		$this->mNewpassTime = wfTimestampOrNull( TS_MW, $row->user_newpass_time );
		$this->mEmail = $row->user_email;
		$this->decodeOptions( $row->user_options );
		$this->mTouched = wfTimestamp(TS_MW,$row->user_touched);
		$this->mToken = $row->user_token;
		$this->mEmailAuthenticated = wfTimestampOrNull( TS_MW, $row->user_email_authenticated );
		$this->mEmailToken = $row->user_email_token;
		$this->mEmailTokenExpires = wfTimestampOrNull( TS_MW, $row->user_email_token_expires );
		$this->mRegistration = wfTimestampOrNull( TS_MW, $row->user_registration );
		$this->mEditCount = $row->user_editcount; 
	}

	/**
	 * Load the groups from the database if they aren't already loaded.
	 * @private
	 */
	function loadGroups() {
		if ( is_null( $this->mGroups ) ) {
			$dbr = wfGetDB( DB_MASTER );
			$res = $dbr->select( 'user_groups',
				array( 'ug_group' ),
				array( 'ug_user' => $this->mId ),
				__METHOD__ );
			$this->mGroups = array();
			while( $row = $dbr->fetchObject( $res ) ) {
				$this->mGroups[] = $row->ug_group;
			}
		}
	}

	/**
	 * Clear various cached data stored in this object.
	 * @param $reloadFrom \string Reload user and user_groups table data from a
	 *   given source. May be "name", "id", "defaults", "session", or false for
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
	 *
	 * @return \type{\arrayof{\string}} Array of options
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
	 * @param $opt \string Name of option to retrieve
	 * @return \string Default option value
	 */
	public static function getDefaultOption( $opt ) {
		$defOpts = self::getDefaultOptions();
		if( isset( $defOpts[$opt] ) ) {
			return $defOpts[$opt];
		} else {
			return '';
		}
	}

	/**
	 * Get a list of user toggle names
	 * @return \type{\arrayof{\string}} Array of user toggle names
	 */
	static function getToggles() {
		global $wgContLang, $wgUseRCPatrol;
		$extraToggles = array();
		wfRunHooks( 'UserToggles', array( &$extraToggles ) );
		if( $wgUseRCPatrol ) {
			$extraToggles[] = 'hidepatrolled';
			$extraToggles[] = 'watchlisthidepatrolled';
		}
		return array_merge( self::$mToggles, $extraToggles, $wgContLang->getExtraUserToggles() );
	}


	/**
	 * Get blocking information
	 * @private
	 * @param $bFromSlave \bool Whether to check the slave database first. To
	 *                    improve performance, non-critical checks are done
	 *                    against slaves. Check when actually saving should be
	 *                    done against master.
	 */
	function getBlockedStatus( $bFromSlave = true ) {
		global $wgEnableSorbs, $wgProxyWhitelist;

		if ( -1 != $this->mBlockedby ) {
			wfDebug( "User::getBlockedStatus: already loaded.\n" );
			return;
		}

		wfProfileIn( __METHOD__ );
		wfDebug( __METHOD__.": checking...\n" );

		// Initialize data...
		// Otherwise something ends up stomping on $this->mBlockedby when
		// things get lazy-loaded later, causing false positive block hits
		// due to -1 !== 0. Probably session-related... Nothing should be
		// overwriting mBlockedby, surely?
		$this->load();
		
		$this->mBlockedby = 0;
		$this->mHideName = 0;
		$this->mAllowUsertalk = 0;
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
			$this->mHideName = $this->mBlock->mHideName;
			$this->mAllowUsertalk = $this->mBlock->mAllowUsertalk;
			if ( $this->isLoggedIn() ) {
				$this->spreadBlock();
			}
		} else {
			// Bug 13611: don't remove mBlock here, to allow account creation blocks to 
			// apply to users. Note that the existence of $this->mBlock is not used to 
			// check for edit blocks, $this->mBlockedby is instead.
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

	/**
	 * Whether the given IP is in the SORBS blacklist.
	 *
	 * @param $ip \string IP to check
	 * @return \bool True if blacklisted.
	 */
	function inSorbsBlacklist( $ip ) {
		global $wgEnableSorbs, $wgSorbsUrl;

		return $wgEnableSorbs &&
			$this->inDnsBlacklist( $ip, $wgSorbsUrl );
	}

	/**
	 * Whether the given IP is in a given DNS blacklist.
	 *
	 * @param $ip \string IP to check
	 * @param $base \string URL of the DNS blacklist
	 * @return \bool True if blacklisted.
	 */
	function inDnsBlacklist( $ip, $base ) {
		wfProfileIn( __METHOD__ );

		$found = false;
		$host = '';
		// FIXME: IPv6 ???  (http://bugs.php.net/bug.php?id=33170)
		if( IP::isIPv4($ip) ) {
			# Make hostname
			$host = "$ip.$base";

			# Send query
			$ipList = gethostbynamel( $host );

			if( $ipList ) {
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
	 * Is this user subject to rate limiting?
	 *
	 * @return \bool True if rate limited
	 */
	public function isPingLimitable() {
		global $wgRateLimitsExcludedGroups;
		if( array_intersect( $this->getEffectiveGroups(), $wgRateLimitsExcludedGroups ) ) {
			// Deprecated, but kept for backwards-compatibility config
			return false;
		}
		return !$this->isAllowed('noratelimit');
	}

	/**
	 * Primitive rate limits: enforce maximum actions per time period
	 * to put a brake on flooding.
	 *
	 * @note When using a shared cache like memcached, IP-address
	 * last-hit counters will be shared across wikis.
	 *
	 * @param $action \string Action to enforce; 'edit' if unspecified
	 * @return \bool True if a rate limiter was tripped
	 */
	function pingLimiter( $action='edit' ) {

		# Call the 'PingLimiter' hook
		$result = false;
		if( !wfRunHooks( 'PingLimiter', array( &$this, $action, $result ) ) ) {
			return $result;
		}

		global $wgRateLimits;
		if( !isset( $wgRateLimits[$action] ) ) {
			return false;
		}

		# Some groups shouldn't trigger the ping limiter, ever
		if( !$this->isPingLimitable() )
			return false;

		global $wgMemc, $wgRateLimitLog;
		wfProfileIn( __METHOD__ );

		$limits = $wgRateLimits[$action];
		$keys = array();
		$id = $this->getId();
		$ip = wfGetIP();
		$userLimit = false;

		if( isset( $limits['anon'] ) && $id == 0 ) {
			$keys[wfMemcKey( 'limiter', $action, 'anon' )] = $limits['anon'];
		}

		if( isset( $limits['user'] ) && $id != 0 ) {
			$userLimit = $limits['user'];
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
		// Check for group-specific permissions
		// If more than one group applies, use the group with the highest limit
		foreach ( $this->getGroups() as $group ) {
			if ( isset( $limits[$group] ) ) {
				if ( $userLimit === false || $limits[$group] > $userLimit ) {
					$userLimit = $limits[$group];
				}
			}
		}
		// Set the user limit key
		if ( $userLimit !== false ) {
			wfDebug( __METHOD__.": effective user limit: $userLimit\n" );
			$keys[ wfMemcKey( 'limiter', $action, 'user', $id ) ] = $userLimit;
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
	 * 
	 * @param $bFromSlave \bool Whether to check the slave database instead of the master
	 * @return \bool True if blocked, false otherwise
	 */
	function isBlocked( $bFromSlave = true ) { // hacked from false due to horrible probs on site
		wfDebug( "User::isBlocked: enter\n" );
		$this->getBlockedStatus( $bFromSlave );
		return $this->mBlockedby !== 0;
	}

	/**
	 * Check if user is blocked from editing a particular article
	 * 
	 * @param $title      \string Title to check
	 * @param $bFromSlave \bool   Whether to check the slave database instead of the master
	 * @return \bool True if blocked, false otherwise
	 */
	function isBlockedFrom( $title, $bFromSlave = false ) {
		global $wgBlockAllowsUTEdit;
		wfProfileIn( __METHOD__ );
		wfDebug( __METHOD__.": enter\n" );

		wfDebug( __METHOD__.": asking isBlocked()\n" );
		$blocked = $this->isBlocked( $bFromSlave );
		$allowUsertalk = ($wgBlockAllowsUTEdit ? $this->mAllowUsertalk : false);
		# If a user's name is suppressed, they cannot make edits anywhere
		if ( !$this->mHideName && $allowUsertalk && $title->getText() === $this->getName() &&
		  $title->getNamespace() == NS_USER_TALK ) {
			$blocked = false;
			wfDebug( __METHOD__.": self-talk page, ignoring any blocks\n" );
		}
		wfProfileOut( __METHOD__ );
		return $blocked;
	}

	/**
	 * If user is blocked, return the name of the user who placed the block
	 * @return \string name of blocker
	 */
	function blockedBy() {
		$this->getBlockedStatus();
		return $this->mBlockedby;
	}

	/**
	 * If user is blocked, return the specified reason for the block
	 * @return \string Blocking reason
	 */
	function blockedFor() {
		$this->getBlockedStatus();
		return $this->mBlockreason;
	}
	
	/**
	 * Check if user is blocked on all wikis.
	 * Do not use for actual edit permission checks!
	 * This is intented for quick UI checks.
	 * 
	 * @param $ip \type{\string} IP address, uses current client if none given
	 * @return \type{\bool} True if blocked, false otherwise
	 */
	function isBlockedGlobally( $ip = '' ) {
		if( $this->mBlockedGlobally !== null ) {
			return $this->mBlockedGlobally;
		}
		// User is already an IP?
		if( IP::isIPAddress( $this->getName() ) ) {
			$ip = $this->getName();
		} else if( !$ip ) {
			$ip = wfGetIP();
		}
		$blocked = false;
		wfRunHooks( 'UserIsBlockedGlobally', array( &$this, $ip, &$blocked ) );
		$this->mBlockedGlobally = (bool)$blocked;
		return $this->mBlockedGlobally;
	}
	
	/**
	 * Check if user account is locked
	 * 
	 * @return \type{\bool} True if locked, false otherwise
	 */
	function isLocked() {
		if( $this->mLocked !== null ) {
			return $this->mLocked;
		}
		global $wgAuth;
		$authUser = $wgAuth->getUserInstance( $this );
		$this->mLocked = (bool)$authUser->isLocked();
		return $this->mLocked;
	}
	
	/**
	 * Check if user account is hidden
	 * 
	 * @return \type{\bool} True if hidden, false otherwise
	 */
	function isHidden() {
		if( $this->mHideName !== null ) {
			return $this->mHideName;
		}
		$this->getBlockedStatus();
		if( !$this->mHideName ) {
			global $wgAuth;
			$authUser = $wgAuth->getUserInstance( $this );
			$this->mHideName = (bool)$authUser->isHidden();
		}
		return $this->mHideName;
	}

	/**
	 * Get the user's ID.
	 * @return \int The user's ID; 0 if the user is anonymous or nonexistent
	 */
	function getId() {
		if( $this->mId === null and $this->mName !== null
		and User::isIP( $this->mName ) ) {
			// Special case, we know the user is anonymous
			return 0;
		} elseif( $this->mId === null ) {
			// Don't load if this was initialized from an ID
			$this->load();
		}
		return $this->mId;
	}

	/**
	 * Set the user and reload all fields according to a given ID
	 * @param $v \int User ID to reload
	 */
	function setId( $v ) {
		$this->mId = $v;
		$this->clearInstanceCache( 'id' );
	}

	/**
	 * Get the user name, or the IP of an anonymous user
	 * @return \string User's name or IP address
	 */
	function getName() {
		if ( !$this->mDataLoaded && $this->mFrom == 'name' ) {
			# Special case optimisation
			return $this->mName;
		} else {
			$this->load();
			if ( $this->mName === false ) {
				# Clean up IPs
				$this->mName = IP::sanitizeIP( wfGetIP() );
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
	 * @note User::newFromName() has rougly the same function, when the named user
	 * does not exist.
	 * @param $str \string New user name to set
	 */
	function setName( $str ) {
		$this->load();
		$this->mName = $str;
	}

	/**
	 * Get the user's name escaped by underscores.
	 * @return \string Username escaped by underscores.
	 */
	function getTitleKey() {
		return str_replace( ' ', '_', $this->getName() );
	}

	/**
	 * Check if the user has new messages.
	 * @return \bool True if the user has new messages
	 */
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
				if( strval( $newtalk ) !== '' ) {
					$this->mNewtalk = (bool)$newtalk;
				} else {
					// Since we are caching this, make sure it is up to date by getting it
					// from the master
					$this->mNewtalk = $this->checkNewtalk( 'user_ip', $this->getName(), true );
					$wgMemc->set( $key, (int)$this->mNewtalk, 1800 );
				}
			} else {
				$this->mNewtalk = $this->checkNewtalk( 'user_id', $this->mId );
			}
		}

		return (bool)$this->mNewtalk;
	}

	/**
	 * Return the talk page(s) this user has new messages on.
	 * @return \type{\arrayof{\string}} Array of page URLs
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
	 * Internal uncached check for new messages
	 *
	 * @see getNewtalk()
	 * @param $field \string 'user_ip' for anonymous users, 'user_id' otherwise
	 * @param $id \types{\string,\int} User's IP address for anonymous users, User ID otherwise
	 * @param $fromMaster \bool true to fetch from the master, false for a slave
	 * @return \bool True if the user has new messages
	 * @private
	 */
	function checkNewtalk( $field, $id, $fromMaster = false ) {
		if ( $fromMaster ) {
			$db = wfGetDB( DB_MASTER );
		} else {
			$db = wfGetDB( DB_SLAVE );
		}
		$ok = $db->selectField( 'user_newtalk', $field,
			array( $field => $id ), __METHOD__ );
		return $ok !== false;
	}

	/**
	 * Add or update the new messages flag
	 * @param $field \string 'user_ip' for anonymous users, 'user_id' otherwise
	 * @param $id \types{\string,\int} User's IP address for anonymous users, User ID otherwise
	 * @return \bool True if successful, false otherwise
	 * @private
	 */
	function updateNewtalk( $field, $id ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert( 'user_newtalk',
			array( $field => $id ),
			__METHOD__,
			'IGNORE' );
		if ( $dbw->affectedRows() ) {
			wfDebug( __METHOD__.": set on ($field, $id)\n" );
			return true;
		} else {
			wfDebug( __METHOD__." already set ($field, $id)\n" );
			return false;
		}
	}

	/**
	 * Clear the new messages flag for the given user
	 * @param $field \string 'user_ip' for anonymous users, 'user_id' otherwise
	 * @param $id \types{\string,\int} User's IP address for anonymous users, User ID otherwise
	 * @return \bool True if successful, false otherwise
	 * @private
	 */
	function deleteNewtalk( $field, $id ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'user_newtalk',
			array( $field => $id ),
			__METHOD__ );
		if ( $dbw->affectedRows() ) {
			wfDebug( __METHOD__.": killed on ($field, $id)\n" );
			return true;
		} else {
			wfDebug( __METHOD__.": already gone ($field, $id)\n" );
			return false;
		}
	}

	/**
	 * Update the 'You have new messages!' status.
	 * @param $val \bool Whether the user has new messages
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
		global $wgMemc;

		if( $val ) {
			$changed = $this->updateNewtalk( $field, $id );
		} else {
			$changed = $this->deleteNewtalk( $field, $id );
		}

		if( $this->isAnon() ) {
			// Anons have a separate memcached space, since
			// user records aren't kept for them.
			$key = wfMemcKey( 'newtalk', 'ip', $id );
			$wgMemc->set( $key, $val ? 1 : 0, 1800 );
		}
		if ( $changed ) {
			$this->invalidateCache();
		}
	}

	/**
	 * Generate a current or new-future timestamp to be stored in the
	 * user_touched field when we update things.
	 * @return \string Timestamp in TS_MW format
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
		$this->load();
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

	/**
	 * Validate the cache for this account.
	 * @param $timestamp \string A timestamp in TS_MW format
	 */
	function validateCache( $timestamp ) {
		$this->load();
		return ($timestamp >= $this->mTouched);
	}

	/**
	 * Get the user touched timestamp
	 */
	function getTouched() {
		$this->load();
		return $this->mTouched;
	}

	/**
	 * Set the password and reset the random token.
	 * Calls through to authentication plugin if necessary;
	 * will have no effect if the auth plugin refuses to
	 * pass the change through or if the legal password
	 * checks fail.
	 *
	 * As a special case, setting the password to null
	 * wipes it, so the account cannot be logged in until
	 * a new password is set, for instance via e-mail.
	 *
	 * @param $str \string New password to set
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
				throw new PasswordError( wfMsgExt( 'passwordtooshort', array( 'parsemag' ),
					$wgMinimalPasswordLength ) );
			}
		}

		if( !$wgAuth->setPassword( $this, $str ) ) {
			throw new PasswordError( wfMsg( 'externaldberror' ) );
		}

		$this->setInternalPassword( $str );

		return true;
	}

	/**
	 * Set the password and reset the random token unconditionally.
	 *
	 * @param $str \string New password to set
	 */
	function setInternalPassword( $str ) {
		$this->load();
		$this->setToken();

		if( $str === null ) {
			// Save an invalid hash...
			$this->mPassword = '';
		} else {
			$this->mPassword = self::crypt( $str );
		}
		$this->mNewpassword = '';
		$this->mNewpassTime = null;
	}
	
	/**
	 * Get the user's current token.
	 * @return \string Token
	 */
	function getToken() {
		$this->load();
		return $this->mToken;
	}
	
	/**
	 * Set the random token (used for persistent authentication)
	 * Called from loadDefaults() among other places.
	 *
	 * @param $token \string If specified, set the token to this value
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
	
	/**
	 * Set the cookie password
	 *
	 * @param $str \string New cookie password
	 * @private
	 */
	function setCookiePassword( $str ) {
		$this->load();
		$this->mCookiePassword = md5( $str );
	}

	/**
	 * Set the password for a password reminder or new account email
	 *
	 * @param $str \string New password to set
	 * @param $throttle \bool If true, reset the throttle timestamp to the present
	 */
	function setNewpassword( $str, $throttle = true ) {
		$this->load();
		$this->mNewpassword = self::crypt( $str );
		if ( $throttle ) {
			$this->mNewpassTime = wfTimestampNow();
		}
	}

	/**
	 * Has password reminder email been sent within the last 
	 * $wgPasswordReminderResendTime hours?
	 * @return \bool True or false
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

	/**
	 * Get the user's e-mail address
	 * @return \string User's email address
	 */
	function getEmail() {
		$this->load();
		wfRunHooks( 'UserGetEmail', array( $this, &$this->mEmail ) );
		return $this->mEmail;
	}

	/**
	 * Get the timestamp of the user's e-mail authentication
	 * @return \string TS_MW timestamp
	 */
	function getEmailAuthenticationTimestamp() {
		$this->load();
		wfRunHooks( 'UserGetEmailAuthenticationTimestamp', array( $this, &$this->mEmailAuthenticated ) );
		return $this->mEmailAuthenticated;
	}

	/**
	 * Set the user's e-mail address
	 * @param $str \string New e-mail address
	 */
	function setEmail( $str ) {
		$this->load();
		$this->mEmail = $str;
		wfRunHooks( 'UserSetEmail', array( $this, &$this->mEmail ) );
	}

	/**
	 * Get the user's real name
	 * @return \string User's real name
	 */
	function getRealName() {
		$this->load();
		return $this->mRealName;
	}

	/**
	 * Set the user's real name
	 * @param $str \string New real name
	 */
	function setRealName( $str ) {
		$this->load();
		$this->mRealName = $str;
	}

	/**
	 * Get the user's current setting for a given option.
	 *
	 * @param $oname \string The option to check
	 * @param $defaultOverride \string A default value returned if the option does not exist
	 * @return \string User's current value for the option
	 * @see getBoolOption()
	 * @see getIntOption()
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
	 * Get the user's current setting for a given option, as a boolean value.
	 *
	 * @param $oname \string The option to check
	 * @return \bool User's current value for the option
	 * @see getOption()
	 */
	function getBoolOption( $oname ) {
		return (bool)$this->getOption( $oname );
	}

	
	/**
	 * Get the user's current setting for a given option, as a boolean value.
	 *
	 * @param $oname \string The option to check
	 * @param $defaultOverride \int A default value returned if the option does not exist
	 * @return \int User's current value for the option
	 * @see getOption()
	 */
	function getIntOption( $oname, $defaultOverride=0 ) {
		$val = $this->getOption( $oname );
		if( $val == '' ) {
			$val = $defaultOverride;
		}
		return intval( $val );
	}

	/**
	 * Set the given option for a user.
	 *
	 * @param $oname \string The option to set
	 * @param $val \mixed New value to set
	 */
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
		if( $val ) {
			$val = str_replace( "\r\n", "\n", $val );
			$val = str_replace( "\r", "\n", $val );
			$val = str_replace( "\n", " ", $val );
		}
		// Explicitly NULL values should refer to defaults
		global $wgDefaultUserOptions;
		if( is_null($val) && isset($wgDefaultUserOptions[$oname]) ) {
			$val = $wgDefaultUserOptions[$oname];
		}
		$this->mOptions[$oname] = $val;
	}
	
	/**
	 * Reset all options to the site defaults
	 */	
	function restoreOptions() {
		$this->mOptions = User::getDefaultOptions();
	}

	/**
	 * Get the user's preferred date format.
	 * @return \string User's preferred date format
	 */
	function getDatePreference() {
		// Important migration for old data rows
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
	 * Get the permissions this user has.
	 * @return \type{\arrayof{\string}} Array of permission names
	 */
	function getRights() {
		if ( is_null( $this->mRights ) ) {
			$this->mRights = self::getGroupPermissions( $this->getEffectiveGroups() );
			wfRunHooks( 'UserGetRights', array( $this, &$this->mRights ) );
			// Force reindexation of rights when a hook has unset one of them
			$this->mRights = array_values( $this->mRights );
		}
		return $this->mRights;
	}

	/**
	 * Get the list of explicit group memberships this user has.
	 * The implicit * and user groups are not included.
	 * @return \type{\arrayof{\string}} Array of internal group names
	 */
	function getGroups() {
		$this->load();
		return $this->mGroups;
	}

	/**
	 * Get the list of implicit group memberships this user has.
	 * This includes all explicit groups, plus 'user' if logged in,
	 * '*' for all accounts and autopromoted groups
	 * @param $recache \bool Whether to avoid the cache
	 * @return \type{\arrayof{\string}} Array of internal group names
	 */
	function getEffectiveGroups( $recache = false ) {
		if ( $recache || is_null( $this->mEffectiveGroups ) ) {
			$this->mEffectiveGroups = $this->getGroups();
			$this->mEffectiveGroups[] = '*';
			if( $this->getId() ) {
				$this->mEffectiveGroups[] = 'user';

				$this->mEffectiveGroups = array_unique( array_merge(
					$this->mEffectiveGroups,
					Autopromote::getAutopromoteGroups( $this )
				) );

				# Hook for additional groups
				wfRunHooks( 'UserEffectiveGroups', array( &$this, &$this->mEffectiveGroups ) );
			}
		}
		return $this->mEffectiveGroups;
	}

	/**
	 * Get the user's edit count.
	 * @return \int User'e edit count
	 */
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
	 * @param $group \string Name of the group to add
	 */
	function addGroup( $group ) {
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

		$this->loadGroups();
		$this->mGroups[] = $group;
		$this->mRights = User::getGroupPermissions( $this->getEffectiveGroups( true ) );

		$this->invalidateCache();
	}

	/**
	 * Remove the user from the given group.
	 * This takes immediate effect.
	 * @param $group \string Name of the group to remove
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

		$this->loadGroups();
		$this->mGroups = array_diff( $this->mGroups, array( $group ) );
		$this->mRights = User::getGroupPermissions( $this->getEffectiveGroups( true ) );

		$this->invalidateCache();
	}


	/**
	 * Get whether the user is logged in
	 * @return \bool True or false
	 */
	function isLoggedIn() {
		return $this->getID() != 0;
	}

	/**
	 * Get whether the user is anonymous
	 * @return \bool True or false
	 */
	function isAnon() {
		return !$this->isLoggedIn();
	}

	/**
	 * Get whether the user is a bot
	 * @return \bool True or false
	 * @deprecated
	 */
	function isBot() {
		wfDeprecated( __METHOD__ );
		return $this->isAllowed( 'bot' );
	}

	/**
	 * Check if user is allowed to access a feature / make an action
	 * @param $action \string action to be checked
	 * @return \bool True if action is allowed, else false
	 */
	function isAllowed( $action = '' ) {
		if ( $action === '' )
			return true; // In the spirit of DWIM
		# Patrolling may not be enabled
		if( $action === 'patrol' || $action === 'autopatrol' ) {
			global $wgUseRCPatrol, $wgUseNPPatrol;
			if( !$wgUseRCPatrol && !$wgUseNPPatrol )
				return true;
		}
		# Use strict parameter to avoid matching numeric 0 accidentally inserted 
		# by misconfiguration: 0 == 'foo'
		return in_array( $action, $this->getRights(), true );
	}

	/**
	* Check whether to enable recent changes patrol features for this user
	* @return \bool True or false
	*/
	public function useRCPatrol() {
		global $wgUseRCPatrol;
		return( $wgUseRCPatrol && ($this->isAllowed('patrol') || $this->isAllowed('patrolmarks')) );
	}

	/**
	* Check whether to enable new pages patrol features for this user
	* @return \bool True or false
	*/
	public function useNPPatrol() {
		global $wgUseRCPatrol, $wgUseNPPatrol;
		return( ($wgUseRCPatrol || $wgUseNPPatrol) && ($this->isAllowed('patrol') || $this->isAllowed('patrolmarks')) );
	}

	/**
	 * Get the current skin, loading it if required
	 * @return \type{Skin} Current skin
	 * @todo FIXME : need to check the old failback system [AV]
	 */
	function &getSkin() {
		global $wgRequest, $wgAllowUserSkin, $wgDefaultSkin;
		if ( ! isset( $this->mSkin ) ) {
			wfProfileIn( __METHOD__ );

			if( $wgAllowUserSkin ) {
				# get the user skin
				$userSkin = $this->getOption( 'skin' );
				$userSkin = $wgRequest->getVal('useskin', $userSkin);
			} else {
				# if we're not allowing users to override, then use the default
				$userSkin = $wgDefaultSkin;
			}
			
			$this->mSkin =& Skin::newFromKey( $userSkin );
			wfProfileOut( __METHOD__ );
		}
		return $this->mSkin;
	}

	/**
	 * Check the watched status of an article.
	 * @param $title \type{Title} Title of the article to look at
	 * @return \bool True if article is watched
	 */
	function isWatched( $title ) {
		$wl = WatchedItem::fromUserTitle( $this, $title );
		return $wl->isWatched();
	}

	/**
	 * Watch an article.
	 * @param $title \type{Title} Title of the article to look at
	 */
	function addWatch( $title ) {
		$wl = WatchedItem::fromUserTitle( $this, $title );
		$wl->addWatch();
		$this->invalidateCache();
	}

	/**
	 * Stop watching an article.
	 * @param $title \type{Title} Title of the article to look at
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
	 * @param $title \type{Title} Title of the article to look at
	 */
	function clearNotification( &$title ) {
		global $wgUser, $wgUseEnotif, $wgShowUpdatedMarker;

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

		if( !$wgUseEnotif && !$wgShowUpdatedMarker ) {
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
		} elseif ( $this->getId() == $wgUser->getId() ) {
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
					), __METHOD__
			);
		}
	}

	/**
	 * Resets all of the given user's page-change notification timestamps.
	 * If e-notif e-mails are on, they will receive notification mails on
	 * the next change of any watched page.
	 *
	 * @param $currentUser \int User ID
	 */
	function clearAllNotifications( $currentUser ) {
		global $wgUseEnotif, $wgShowUpdatedMarker;
		if ( !$wgUseEnotif && !$wgShowUpdatedMarker ) {
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
				), __METHOD__
			);
		# 	We also need to clear here the "you have new message" notification for the own user_talk page
		#	This is cleared one page view later in Article::viewUpdates();
		}
	}

	/**
	 * Encode this user's options as a string
	 * @return \string Encoded options
	 * @private
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
	 * Set this user's options from an encoded string
	 * @param $str \string Encoded options to import
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
	
	/**
	 * Set a cookie on the user's client. Wrapper for 
	 * WebResponse::setCookie
	 * @param $name \string Name of the cookie to set
	 * @param $value \string Value to set
	 * @param $exp \int Expiration time, as a UNIX time value; 
	 *                   if 0 or not specified, use the default $wgCookieExpiration
	 */
	protected function setCookie( $name, $value, $exp=0 ) {
		global $wgRequest;
		$wgRequest->response()->setcookie( $name, $value, $exp );
	}
	
	/**
	 * Clear a cookie on the user's client
	 * @param $name \string Name of the cookie to clear
	 */
	protected function clearCookie( $name ) {
		$this->setCookie( $name, '', time() - 86400 );
	}

	/**
	 * Set the default cookies for this session on the user's client.
	 */
	function setCookies() {
		$this->load();
		if ( 0 == $this->mId ) return;
		$session = array( 
			'wsUserID' => $this->mId,
			'wsToken' => $this->mToken,
			'wsUserName' => $this->getName()
		);
		$cookies = array(
			'UserID' => $this->mId,
			'UserName' => $this->getName(),
		);
		if ( 1 == $this->getOption( 'rememberpassword' ) ) {
			$cookies['Token'] = $this->mToken;
		} else {
			$cookies['Token'] = false;
		}
		
		wfRunHooks( 'UserSetCookies', array( $this, &$session, &$cookies ) );
		#check for null, since the hook could cause a null value 
		if ( !is_null( $session ) && isset( $_SESSION ) ){
			$_SESSION = $session + $_SESSION;
		}
		foreach ( $cookies as $name => $value ) {
			if ( $value === false ) {
				$this->clearCookie( $name );
			} else {
				$this->setCookie( $name, $value );
			}
		}
	}

	/**
	 * Log this user out.
	 */
	function logout() {
		global $wgUser;
		if( wfRunHooks( 'UserLogout', array(&$this) ) ) {
			$this->doLogout();
		}
	}

	/**
	 * Clear the user's cookies and session, and reset the instance cache.
	 * @private
	 * @see logout()
	 */
	function doLogout() {
		$this->clearInstanceCache( 'defaults' );

		$_SESSION['wsUserID'] = 0;

		$this->clearCookie( 'UserID' );
		$this->clearCookie( 'Token' );

		# Remember when user logged out, to prevent seeing cached pages
		$this->setCookie( 'LoggedOut', wfTimestampNow(), time() + 86400 );
	}

	/**
	 * Save this user's settings into the database.
	 * @todo Only rarely do all these fields need to be set!
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
				'user_token' => $this->mToken,
				'user_email_token' => $this->mEmailToken,
				'user_email_token_expires' => $dbw->timestampOrNull( $this->mEmailTokenExpires ),
			), array( /* WHERE */
				'user_id' => $this->mId
			), __METHOD__
		);
		wfRunHooks( 'UserSaveSettings', array( $this ) );
		$this->clearSharedCache();
		$this->getUserPage()->invalidateCache();
	}

	/**
	 * If only this user's username is known, and it exists, return the user ID.
	 */
	function idForName() {
		$s = trim( $this->getName() );
		if ( $s === '' ) return 0;

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
	 * @param $name \string Username to add
	 * @param $params \type{\arrayof{\string}} Non-default parameters to save to the database:
	 *   - password             The user's password. Password logins will be disabled if this is omitted.
	 *   - newpassword          A temporary password mailed to the user
	 *   - email                The user's email address
	 *   - email_authenticated  The email authentication timestamp
	 *   - real_name            The user's real name
	 *   - options              An associative array of non-default options
	 *   - token                Random authentication token. Do not set.
	 *   - registration         Registration timestamp. Do not set.
	 *
	 * @return \type{User} A new User object, or null if the username already exists
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
	 * Add this existing user object to the database
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

		// Clear instance cache other than user table data, which is already accurate
		$this->clearInstanceCache();
	}

	/**
	 * If this (non-anonymous) user is blocked, block any IP address
	 * they've successfully logged in from.
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
	 * @return \string Page rendering hash
	 */
	function getPageRenderingHash() {
		global $wgUseDynamicDates, $wgRenderHashAppend, $wgLang, $wgContLang;
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

		$confstr .= $wgRenderHashAppend;

		// Give a chance for extensions to modify the hash, if they have
		// extra options or other effects on the parser cache.
		wfRunHooks( 'PageRenderingHash', array( &$confstr ) );

		// Make it a valid memcached key fragment
		$confstr = str_replace( ' ', '_', $confstr );
		$this->mHash = $confstr;
		return $confstr;
	}

	/**
	 * Get whether the user is explicitly blocked from account creation.
	 * @return \bool True if blocked
	 */
	function isBlockedFromCreateAccount() {
		$this->getBlockedStatus();
		return $this->mBlock && $this->mBlock->mCreateAccount;
	}

	/**
	 * Get whether the user is blocked from using Special:Emailuser.
	 * @return \bool True if blocked
	 */
	function isBlockedFromEmailuser() {
		$this->getBlockedStatus();
		return $this->mBlock && $this->mBlock->mBlockEmail;
	}

	/**
	 * Get whether the user is allowed to create an account.
	 * @return \bool True if allowed
	 */
	function isAllowedToCreateAccount() {
		return $this->isAllowed( 'createaccount' ) && !$this->isBlockedFromCreateAccount();
	}

	/**
	 * @deprecated
	 */
	function setLoaded( $loaded ) {
		wfDeprecated( __METHOD__ );
	}

	/**
	 * Get this user's personal page title.
	 *
	 * @return \type{Title} User's personal page title
	 */
	function getUserPage() {
		return Title::makeTitle( NS_USER, $this->getName() );
	}

	/**
	 * Get this user's talk page title.
	 *
	 * @return \type{Title} User's talk page title
	 */
	function getTalkPage() {
		$title = $this->getUserPage();
		return $title->getTalkPage();
	}

	/**
	 * Get the maximum valid user ID.
	 * @return \int User ID
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
	 * @return \bool True if the user is a newbie
	 */
	function isNewbie() {
		return !$this->isAllowed( 'autoconfirmed' );
	}
	
	/**
	 * Is the user active? We check to see if they've made at least
	 * X number of edits in the last Y days.
	 * 
	 * @return \bool True if the user is active, false if not.
	 */
	public function isActiveEditor() {
		global $wgActiveUserEditCount, $wgActiveUserDays;
		$dbr = wfGetDB( DB_SLAVE );
		
		// Stolen without shame from RC
		$cutoff_unixtime = time() - ( $wgActiveUserDays * 86400 );
		$cutoff_unixtime = $cutoff_unixtime - ( $cutoff_unixtime % 86400 );
		$oldTime = $dbr->addQuotes( $dbr->timestamp( $cutoff_unixtime ) );
		
		$res = $dbr->select( 'revision', '1',
				array( 'rev_user_text' => $this->getName(), "rev_timestamp > $oldTime"),
				__METHOD__,
				array('LIMIT' => $wgActiveUserEditCount ) );
		
		$count = $dbr->numRows($res);
		$dbr->freeResult($res);

		return $count == $wgActiveUserEditCount;
	}

	/**
	 * Check to see if the given clear-text password is one of the accepted passwords
	 * @param $password \string user password.
	 * @return \bool True if the given password is correct, otherwise False.
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
		} elseif( $wgAuth->strictUserAuth( $this->getName() ) ) {
			/* Auth plugin doesn't allow local authentication for this user name */
			return false;
		}
		if ( self::comparePasswords( $this->mPassword, $password, $this->mId ) ) {
			return true;
		} elseif ( function_exists( 'iconv' ) ) {
			# Some wikis were converted from ISO 8859-1 to UTF-8, the passwords can't be converted
			# Check for this with iconv
			$cp1252Password = iconv( 'UTF-8', 'WINDOWS-1252//TRANSLIT', $password );
			if ( self::comparePasswords( $this->mPassword, $cp1252Password, $this->mId ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check if the given clear-text password matches the temporary password
	 * sent by e-mail for password reset operations.
	 * @return \bool True if matches, false otherwise
	 */
	function checkTemporaryPassword( $plaintext ) {
		global $wgNewPasswordExpiry;
		if( self::comparePasswords( $this->mNewpassword, $plaintext, $this->getId() ) ) {
			$this->load();
			$expiry = wfTimestamp( TS_UNIX, $this->mNewpassTime ) + $wgNewPasswordExpiry;
			return ( time() < $expiry );
		} else {
			return false;
		}
	}

	/**
	 * Initialize (if necessary) and return a session token value
	 * which can be used in edit forms to show that the user's
	 * login credentials aren't being hijacked with a foreign form
	 * submission.
	 *
	 * @param $salt \types{\string,\arrayof{\string}} Optional function-specific data for hashing
	 * @return \string The new edit token
	 */
	function editToken( $salt = '' ) {
		if ( $this->isAnon() ) {
			return EDIT_TOKEN_SUFFIX;
		} else {
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
	}

	/**
	 * Generate a looking random token for various uses.
	 *
	 * @param $salt \string Optional salt value
	 * @return \string The new random token
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
	 * @param $val \string Input value to compare
	 * @param $salt \string Optional function-specific data for hashing
	 * @return \bool Whether the token matches
	 */
	function matchEditToken( $val, $salt = '' ) {
		$sessionToken = $this->editToken( $salt );
		if ( $val != $sessionToken ) {
			wfDebug( "User::matchEditToken: broken session data\n" );
		}
		return $val == $sessionToken;
	}

	/**
	 * Check given value against the token value stored in the session,
	 * ignoring the suffix.
	 *
	 * @param $val \string Input value to compare
	 * @param $salt \string Optional function-specific data for hashing
	 * @return \bool Whether the token matches
	 */
	function matchEditTokenNoSuffix( $val, $salt = '' ) {
		$sessionToken = $this->editToken( $salt );
		return substr( $sessionToken, 0, 32 ) == substr( $val, 0, 32 );
	}

	/**
	 * Generate a new e-mail confirmation token and send a confirmation/invalidation
	 * mail to the user's given address.
	 *
	 * @return \types{\bool,\type{WikiError}} True on success, a WikiError object on failure.
	 */
	function sendConfirmationMail() {
		global $wgLang;
		$expiration = null; // gets passed-by-ref and defined in next line.
		$token = $this->confirmationToken( $expiration );
		$url = $this->confirmationTokenUrl( $token );
		$invalidateURL = $this->invalidationTokenUrl( $token );
		$this->saveSettings();
		
		return $this->sendMail( wfMsg( 'confirmemail_subject' ),
			wfMsg( 'confirmemail_body',
				wfGetIP(),
				$this->getName(),
				$url,
				$wgLang->timeanddate( $expiration, false ),
				$invalidateURL ) );
	}

	/**
	 * Send an e-mail to this user's account. Does not check for
	 * confirmed status or validity.
	 *
	 * @param $subject \string Message subject
	 * @param $body \string Message body
	 * @param $from \string Optional From address; if unspecified, default $wgPasswordSender will be used
	 * @param $replyto \string Reply-To address
	 * @return \types{\bool,\type{WikiError}} True on success, a WikiError object on failure
	 */
	function sendMail( $subject, $body, $from = null, $replyto = null ) {
		if( is_null( $from ) ) {
			global $wgPasswordSender;
			$from = $wgPasswordSender;
		}

		$to = new MailAddress( $this );
		$sender = new MailAddress( $from );
		return UserMailer::send( $to, $sender, $subject, $body, $replyto );
	}

	/**
	 * Generate, store, and return a new e-mail confirmation code.
	 * A hash (unsalted, since it's used as a key) is stored.
	 *
	 * @note Call saveSettings() after calling this function to commit
	 * this change to the database.
	 *
	 * @param[out] &$expiration \mixed Accepts the expiration time
	 * @return \string New token
	 * @private
	 */
	function confirmationToken( &$expiration ) {
		$now = time();
		$expires = $now + 7 * 24 * 60 * 60;
		$expiration = wfTimestamp( TS_MW, $expires );
		$token = $this->generateToken( $this->mId . $this->mEmail . $expires );
		$hash = md5( $token );
		$this->load();
		$this->mEmailToken = $hash;
		$this->mEmailTokenExpires = $expiration;
		return $token;
	}

	/**
	* Return a URL the user can use to confirm their email address.
	 * @param $token \string Accepts the email confirmation token
	 * @return \string New token URL
	 * @private
	 */
	function confirmationTokenUrl( $token ) {
		return $this->getTokenUrl( 'ConfirmEmail', $token );
	}
	/**
	 * Return a URL the user can use to invalidate their email address.
	 * @param $token \string Accepts the email confirmation token
	 * @return \string New token URL
	 * @private
	 */
	function invalidationTokenUrl( $token ) {
		return $this->getTokenUrl( 'Invalidateemail', $token );
	}
	
	/**
	 * Internal function to format the e-mail validation/invalidation URLs.
	 * This uses $wgArticlePath directly as a quickie hack to use the
	 * hardcoded English names of the Special: pages, for ASCII safety.
	 *
	 * @note Since these URLs get dropped directly into emails, using the
	 * short English names avoids insanely long URL-encoded links, which
	 * also sometimes can get corrupted in some browsers/mailers
	 * (bug 6957 with Gmail and Internet Explorer).
	 *
	 * @param $page \string Special page
	 * @param $token \string Token
	 * @return \string Formatted URL
	 */
	protected function getTokenUrl( $page, $token ) {
		global $wgArticlePath;
		return wfExpandUrl(
			str_replace(
				'$1',
				"Special:$page/$token",
				$wgArticlePath ) );
	}

	/**
	 * Mark the e-mail address confirmed.
	 *
	 * @note Call saveSettings() after calling this function to commit the change.
	 */
	function confirmEmail() {
		$this->setEmailAuthenticationTimestamp( wfTimestampNow() );
		return true;
	}

	/**
	 * Invalidate the user's e-mail confirmation, and unauthenticate the e-mail
	 * address if it was already confirmed.
	 *
	 * @note Call saveSettings() after calling this function to commit the change.
	 */
	function invalidateEmail() {
		$this->load();
		$this->mEmailToken = null;
		$this->mEmailTokenExpires = null;
		$this->setEmailAuthenticationTimestamp( null );
		return true;
	}

	/**
	 * Set the e-mail authentication timestamp.
	 * @param $timestamp \string TS_MW timestamp
	 */
	function setEmailAuthenticationTimestamp( $timestamp ) {
		$this->load();
		$this->mEmailAuthenticated = $timestamp;
		wfRunHooks( 'UserSetEmailAuthenticationTimestamp', array( $this, &$this->mEmailAuthenticated ) );
	}

	/**
	 * Is this user allowed to send e-mails within limits of current
	 * site configuration?
	 * @return \bool True if allowed
	 */
	function canSendEmail() {
		global $wgEnableEmail, $wgEnableUserEmail;
		if( !$wgEnableEmail || !$wgEnableUserEmail ) {
			return false;
		}
		$canSend = $this->isEmailConfirmed();
		wfRunHooks( 'UserCanSendEmail', array( &$this, &$canSend ) );
		return $canSend;
	}

	/**
	 * Is this user allowed to receive e-mails within limits of current
	 * site configuration?
	 * @return \bool True if allowed
	 */
	function canReceiveEmail() {
		return $this->isEmailConfirmed() && !$this->getOption( 'disablemail' );
	}

	/**
	 * Is this user's e-mail address valid-looking and confirmed within
	 * limits of the current site configuration?
	 *
	 * @note If $wgEmailAuthentication is on, this may require the user to have
	 * confirmed their address by returning a code or using a password
	 * sent to the address from the wiki.
	 *
	 * @return \bool True if confirmed
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
	 * Check whether there is an outstanding request for e-mail confirmation.
	 * @return \bool True if pending
	 */
	function isEmailConfirmationPending() {
		global $wgEmailAuthentication;
		return $wgEmailAuthentication &&
			!$this->isEmailConfirmed() &&
			$this->mEmailToken &&
			$this->mEmailTokenExpires > wfTimestamp();
	}

	/**
	 * Get the timestamp of account creation.
	 *
	 * @return \types{\string,\bool} string Timestamp of account creation, or false for
	 *                                non-existent/anonymous user accounts.
	 */
	public function getRegistration() {
		return $this->getId() > 0
			? $this->mRegistration
			: false;
	}
	
	/**
	 * Get the timestamp of the first edit
	 *
	 * @return \types{\string,\bool} string Timestamp of first edit, or false for
	 *                                non-existent/anonymous user accounts.
	 */
	public function getFirstEditTimestamp() {
		if( $this->getId() == 0 ) return false; // anons
		$dbr = wfGetDB( DB_SLAVE );
		$time = $dbr->selectField( 'revision', 'rev_timestamp',
			array( 'rev_user' => $this->getId() ),
			__METHOD__,
			array( 'ORDER BY' => 'rev_timestamp ASC' )
		);
		if( !$time ) return false; // no edits
		return wfTimestamp( TS_MW, $time );
	}	

	/**
	 * Get the permissions associated with a given list of groups
	 *
	 * @param $groups \type{\arrayof{\string}} List of internal group names
	 * @return \type{\arrayof{\string}} List of permission key names for given groups combined
	 */
	static function getGroupPermissions( $groups ) {
		global $wgGroupPermissions;
		$rights = array();
		foreach( $groups as $group ) {
			if( isset( $wgGroupPermissions[$group] ) ) {
				$rights = array_merge( $rights,
					// array_filter removes empty items
					array_keys( array_filter( $wgGroupPermissions[$group] ) ) );
			}
		}
		return array_unique($rights);
	}
	
	/**
	 * Get all the groups who have a given permission
	 * 
	 * @param $role \string Role to check
	 * @return \type{\arrayof{\string}} List of internal group names with the given permission
	 */
	static function getGroupsWithPermission( $role ) {
		global $wgGroupPermissions;
		$allowedGroups = array();
		foreach ( $wgGroupPermissions as $group => $rights ) {
			if ( isset( $rights[$role] ) && $rights[$role] ) {
				$allowedGroups[] = $group;
			}
		}
		return $allowedGroups;
	}

	/**
	 * Get the localized descriptive name for a group, if it exists
	 *
	 * @param $group \string Internal group name
	 * @return \string Localized descriptive group name
	 */
	static function getGroupName( $group ) {
		global $wgMessageCache;
		$wgMessageCache->loadAllMessages();
		$key = "group-$group";
		$name = wfMsg( $key );
		return $name == '' || wfEmptyMsg( $key, $name )
			? $group
			: $name;
	}

	/**
	 * Get the localized descriptive name for a member of a group, if it exists
	 *
	 * @param $group \string Internal group name
	 * @return \string Localized name for group member
	 */
	static function getGroupMember( $group ) {
		global $wgMessageCache;
		$wgMessageCache->loadAllMessages();
		$key = "group-$group-member";
		$name = wfMsg( $key );
		return $name == '' || wfEmptyMsg( $key, $name )
			? $group
			: $name;
	}

	/**
	 * Return the set of defined explicit groups.
	 * The implicit groups (by default *, 'user' and 'autoconfirmed')
	 * are not included, as they are defined automatically, not in the database.
	 * @return \type{\arrayof{\string}} Array of internal group names
	 */
	static function getAllGroups() {
		global $wgGroupPermissions;
		return array_diff(
			array_keys( $wgGroupPermissions ),
			self::getImplicitGroups()
		);
	}

	/**
	 * Get a list of all available permissions.
	 * @return \type{\arrayof{\string}} Array of permission names
	 */
	static function getAllRights() {
		if ( self::$mAllRights === false ) {
			global $wgAvailableRights;
			if ( count( $wgAvailableRights ) ) {
				self::$mAllRights = array_unique( array_merge( self::$mCoreRights, $wgAvailableRights ) );
			} else {
				self::$mAllRights = self::$mCoreRights;
			}
			wfRunHooks( 'UserGetAllRights', array( &self::$mAllRights ) );
		}
		return self::$mAllRights;
	}

	/**
	 * Get a list of implicit groups
	 * @return \type{\arrayof{\string}} Array of internal group names
	 */
	public static function getImplicitGroups() {
		global $wgImplicitGroups;
		$groups = $wgImplicitGroups;
		wfRunHooks( 'UserGetImplicitGroups', array( &$groups ) );	#deprecated, use $wgImplictGroups instead
		return $groups;
	}

	/**
	 * Get the title of a page describing a particular group
	 *
	 * @param $group \string Internal group name
	 * @return \types{\type{Title},\bool} Title of the page if it exists, false otherwise
	 */
	static function getGroupPage( $group ) {
		global $wgMessageCache;
		$wgMessageCache->loadAllMessages();
		$page = wfMsgForContent( 'grouppage-' . $group );
		if( !wfEmptyMsg( 'grouppage-' . $group, $page ) ) {
			$title = Title::newFromText( $page );
			if( is_object( $title ) )
				return $title;
		}
		return false;
	}

	/**
	 * Create a link to the group in HTML, if available; 
	 * else return the group name.
	 *
	 * @param $group \string Internal name of the group
	 * @param $text \string The text of the link
	 * @return \string HTML link to the group
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
	 * Create a link to the group in Wikitext, if available; 
	 * else return the group name.
	 *
	 * @param $group \string Internal name of the group
	 * @param $text \string The text of the link
	 * @return \string Wikilink to the group
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
	
	/**
	 * Get the description of a given right
	 *
	 * @param $right \string Right to query
	 * @return \string Localized description of the right
	 */
	static function getRightDescription( $right ) {
		global $wgMessageCache;
		$wgMessageCache->loadAllMessages();
		$key = "right-$right";
		$name = wfMsg( $key );
		return $name == '' || wfEmptyMsg( $key, $name )
			? $right
			: $name;
	}

	/**
	 * Make an old-style password hash
	 *
	 * @param $password \string Plain-text password
	 * @param $userId \string User ID
	 * @return \string Password hash
	 */
	static function oldCrypt( $password, $userId ) {
		global $wgPasswordSalt;
		if ( $wgPasswordSalt ) {
			return md5( $userId . '-' . md5( $password ) );
		} else {
			return md5( $password );
		}
	}

	/**
	 * Make a new-style password hash
	 *
	 * @param $password \string Plain-text password
	 * @param $salt \string Optional salt, may be random or the user ID. 
	 *                     If unspecified or false, will generate one automatically
	 * @return \string Password hash
	 */
	static function crypt( $password, $salt = false ) {
		global $wgPasswordSalt;

		$hash = '';
		if( !wfRunHooks( 'UserCryptPassword', array( &$password, &$salt, &$wgPasswordSalt, &$hash ) ) ) {
			return $hash;
		}
		
		if( $wgPasswordSalt ) {
			if ( $salt === false ) {
				$salt = substr( wfGenerateToken(), 0, 8 );
			}
			return ':B:' . $salt . ':' . md5( $salt . '-' . md5( $password ) );
		} else {
			return ':A:' . md5( $password );
		}
	}

	/**
	 * Compare a password hash with a plain-text password. Requires the user
	 * ID if there's a chance that the hash is an old-style hash.
	 *
	 * @param $hash \string Password hash
	 * @param $password \string Plain-text password to compare
	 * @param $userId \string User ID for old-style password salt
	 * @return \bool
	 */
	static function comparePasswords( $hash, $password, $userId = false ) {
		$m = false;
		$type = substr( $hash, 0, 3 );
		
		$result = false;
		if( !wfRunHooks( 'UserComparePasswords', array( &$hash, &$password, &$userId, &$result ) ) ) {
			return $result;
		}
		
		if ( $type == ':A:' ) {
			# Unsalted
			return md5( $password ) === substr( $hash, 3 );
		} elseif ( $type == ':B:' ) {
			# Salted
			list( $salt, $realHash ) = explode( ':', substr( $hash, 3 ), 2 );
			return md5( $salt.'-'.md5( $password ) ) == $realHash;
		} else {
			# Old-style
			return self::oldCrypt( $password, $userId ) === $hash;
		}
	}
	
	/**
	 * Add a newuser log entry for this user
	 * @param $byEmail Boolean: account made by email?
	 */
	public function addNewUserLogEntry( $byEmail = false ) {
		global $wgUser, $wgContLang, $wgNewUserLog;
		if( empty($wgNewUserLog) ) {
			return true; // disabled
		}
		$talk = $wgContLang->getFormattedNsText( NS_TALK );
		if( $this->getName() == $wgUser->getName() ) {
			$action = 'create';
			$message = '';
		} else {
			$action = 'create2';
			$message = $byEmail ? wfMsgForContent( 'newuserlog-byemail' ) : '';
		}
		$log = new LogPage( 'newusers' );
		$log->addEntry( $action, $this->getUserPage(), $message, array( $this->getId() ) );
		return true;
	}

	/**
	 * Add an autocreate newuser log entry for this user
	 * Used by things like CentralAuth and perhaps other authplugins.
	 */
	public function addNewUserLogEntryAutoCreate() {
		global $wgNewUserLog;
		if( empty($wgNewUserLog) ) {
			return true; // disabled
		}
		$log = new LogPage( 'newusers', false );
		$log->addEntry( 'autocreate', $this->getUserPage(), '', array( $this->getId() ) );
		return true;
	}

}
