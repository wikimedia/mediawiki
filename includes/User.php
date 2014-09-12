<?php
/**
 * Implements the User class for the %MediaWiki software.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * Int Number of characters in user_token field.
 * @ingroup Constants
 */
define( 'USER_TOKEN_LENGTH', 32 );

/**
 * Int Serialized record version.
 * @ingroup Constants
 */
define( 'MW_USER_VERSION', 9 );

/**
 * String Some punctuation to prevent editing from broken text-mangling proxies.
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
	 * Global constants made accessible as class constants so that autoloader
	 * magic can be used.
	 */
	const USER_TOKEN_LENGTH = USER_TOKEN_LENGTH;
	const MW_USER_VERSION = MW_USER_VERSION;
	const EDIT_TOKEN_SUFFIX = EDIT_TOKEN_SUFFIX;

	/**
	 * Maximum items in $mWatchedItems
	 */
	const MAX_WATCHED_ITEMS_CACHE = 100;

	/**
	 * Array of Strings List of member variables which are saved to the
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
		'mTouched',
		'mToken',
		'mEmailAuthenticated',
		'mEmailToken',
		'mEmailTokenExpires',
		'mPasswordExpires',
		'mRegistration',
		'mEditCount',
		// user_groups table
		'mGroups',
		// user_properties table
		'mOptionOverrides',
	);

	/**
	 * Array of Strings Core rights.
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
		'deletedtext',
		'deletelogentry',
		'deleterevision',
		'edit',
		'editinterface',
		'editprotected',
		'editmyoptions',
		'editmyprivateinfo',
		'editmyusercss',
		'editmyuserjs',
		'editmywatchlist',
		'editsemiprotected',
		'editusercssjs', #deprecated
		'editusercss',
		'edituserjs',
		'hideuser',
		'import',
		'importupload',
		'ipblock-exempt',
		'markbotedits',
		'mergehistory',
		'minoredit',
		'move',
		'movefile',
		'move-rootuserpages',
		'move-subpages',
		'nominornewtalk',
		'noratelimit',
		'override-export-depth',
		'passwordreset',
		'patrol',
		'patrolmarks',
		'protect',
		'proxyunbannable',
		'purge',
		'read',
		'reupload',
		'reupload-own',
		'reupload-shared',
		'rollback',
		'sendemail',
		'siteadmin',
		'suppressionlog',
		'suppressredirect',
		'suppressrevision',
		'unblockself',
		'undelete',
		'unwatchedpages',
		'upload',
		'upload_by_url',
		'userrights',
		'userrights-interwiki',
		'viewmyprivateinfo',
		'viewmywatchlist',
		'writeapi',
	);
	/**
	 * String Cached results of getAllRights()
	 */
	static $mAllRights = false;

	/** @name Cache variables */
	//@{
	var $mId, $mName, $mRealName, $mPassword, $mNewpassword, $mNewpassTime,
		$mEmail, $mTouched, $mToken, $mEmailAuthenticated,
		$mEmailToken, $mEmailTokenExpires, $mRegistration, $mEditCount,
		$mGroups, $mOptionOverrides;

	protected $mPasswordExpires;
	//@}

	/**
	 * Bool Whether the cache variables have been loaded.
	 */
	//@{
	var $mOptionsLoaded;

	/**
	 * Array with already loaded items or true if all items have been loaded.
	 */
	private $mLoadedItems = array();
	//@}

	/**
	 * String Initialization data source if mLoadedItems!==true. May be one of:
	 *  - 'defaults'   anonymous user initialised from class defaults
	 *  - 'name'       initialise from mName
	 *  - 'id'         initialise from mId
	 *  - 'session'    log in from cookies or session if possible
	 *
	 * Use the User::newFrom*() family of functions to set this.
	 */
	var $mFrom;

	/**
	 * Lazy-initialized variables, invalidated with clearInstanceCache
	 */
	var $mNewtalk, $mDatePreference, $mBlockedby, $mHash, $mRights,
		$mBlockreason, $mEffectiveGroups, $mImplicitGroups, $mFormerGroups, $mBlockedGlobally,
		$mLocked, $mHideName, $mOptions;

	/**
	 * @var WebRequest
	 */
	private $mRequest;

	/**
	 * @var Block
	 */
	var $mBlock;

	/**
	 * @var bool
	 */
	var $mAllowUsertalk;

	/**
	 * @var Block
	 */
	private $mBlockedFromCreateAccount = false;

	/**
	 * @var Array
	 */
	private $mWatchedItems = array();

	static $idCacheByName = array();

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
	public function __construct() {
		$this->clearInstanceCache( 'defaults' );
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->getName();
	}

	/**
	 * Load the user table data for this object from the source given by mFrom.
	 */
	public function load() {
		if ( $this->mLoadedItems === true ) {
			return;
		}
		wfProfileIn( __METHOD__ );

		// Set it now to avoid infinite recursion in accessors
		$this->mLoadedItems = true;

		switch ( $this->mFrom ) {
			case 'defaults':
				$this->loadDefaults();
				break;
			case 'name':
				$this->mId = self::idFromName( $this->mName );
				if ( !$this->mId ) {
					// Nonexistent user placeholder object
					$this->loadDefaults( $this->mName );
				} else {
					$this->loadFromId();
				}
				break;
			case 'id':
				$this->loadFromId();
				break;
			case 'session':
				if ( !$this->loadFromSession() ) {
					// Loading from session failed. Load defaults.
					$this->loadDefaults();
				}
				wfRunHooks( 'UserLoadAfterLoadFromSession', array( $this ) );
				break;
			default:
				wfProfileOut( __METHOD__ );
				throw new MWException( "Unrecognised value for User->mFrom: \"{$this->mFrom}\"" );
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Load user table data, given mId has already been set.
	 * @return bool false if the ID does not exist, true otherwise
	 */
	public function loadFromId() {
		global $wgMemc;
		if ( $this->mId == 0 ) {
			$this->loadDefaults();
			return false;
		}

		// Try cache
		$key = wfMemcKey( 'user', 'id', $this->mId );
		$data = $wgMemc->get( $key );
		if ( !is_array( $data ) || $data['mVersion'] < MW_USER_VERSION ) {
			// Object is expired, load from DB
			$data = false;
		}

		if ( !$data ) {
			wfDebug( "User: cache miss for user {$this->mId}\n" );
			// Load from DB
			if ( !$this->loadFromDatabase() ) {
				// Can't load from ID, user is anonymous
				return false;
			}
			$this->saveToCache();
		} else {
			wfDebug( "User: got user {$this->mId} from cache\n" );
			// Restore from cache
			foreach ( self::$mCacheVars as $name ) {
				$this->$name = $data[$name];
			}
		}

		$this->mLoadedItems = true;

		return true;
	}

	/**
	 * Save user data to the shared cache
	 */
	public function saveToCache() {
		$this->load();
		$this->loadGroups();
		$this->loadOptions();
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
	 * @param string $name Username, validated by Title::newFromText()
	 * @param string|bool $validate Validate username. Takes the same parameters as
	 *  User::getCanonicalName(), except that true is accepted as an alias
	 *  for 'valid', for BC.
	 *
	 * @return User|bool User object, or false if the username is invalid
	 *  (e.g. if it contains illegal characters or is an IP address). If the
	 *  username is not present in the database, the result will be a user object
	 *  with a name, zero user ID and default settings.
	 */
	public static function newFromName( $name, $validate = 'valid' ) {
		if ( $validate === true ) {
			$validate = 'valid';
		}
		$name = self::getCanonicalName( $name, $validate );
		if ( $name === false ) {
			return false;
		} else {
			// Create unloaded user object
			$u = new User;
			$u->mName = $name;
			$u->mFrom = 'name';
			$u->setItemLoaded( 'name' );
			return $u;
		}
	}

	/**
	 * Static factory method for creation from a given user ID.
	 *
	 * @param int $id Valid user ID
	 * @return User The corresponding User object
	 */
	public static function newFromId( $id ) {
		$u = new User;
		$u->mId = $id;
		$u->mFrom = 'id';
		$u->setItemLoaded( 'id' );
		return $u;
	}

	/**
	 * Factory method to fetch whichever user has a given email confirmation code.
	 * This code is generated when an account is created or its e-mail address
	 * has changed.
	 *
	 * If the code is invalid or has expired, returns NULL.
	 *
	 * @param string $code Confirmation code
	 * @return User|null
	 */
	public static function newFromConfirmationCode( $code ) {
		$dbr = wfGetDB( DB_SLAVE );
		$id = $dbr->selectField( 'user', 'user_id', array(
			'user_email_token' => md5( $code ),
			'user_email_token_expires > ' . $dbr->addQuotes( $dbr->timestamp() ),
			) );
		if ( $id !== false ) {
			return User::newFromId( $id );
		} else {
			return null;
		}
	}

	/**
	 * Create a new user object using data from session or cookies. If the
	 * login credentials are invalid, the result is an anonymous user.
	 *
	 * @param WebRequest $request Object to use; $wgRequest will be used if omitted.
	 * @return User object
	 */
	public static function newFromSession( WebRequest $request = null ) {
		$user = new User;
		$user->mFrom = 'session';
		$user->mRequest = $request;
		return $user;
	}

	/**
	 * Create a new user object from a user row.
	 * The row should have the following fields from the user table in it:
	 * - either user_name or user_id to load further data if needed (or both)
	 * - user_real_name
	 * - all other fields (email, password, etc.)
	 * It is useless to provide the remaining fields if either user_id,
	 * user_name and user_real_name are not provided because the whole row
	 * will be loaded once more from the database when accessing them.
	 *
	 * @param stdClass $row A row from the user table
	 * @param array $data Further data to load into the object (see User::loadFromRow for valid keys)
	 * @return User
	 */
	public static function newFromRow( $row, $data = null ) {
		$user = new User;
		$user->loadFromRow( $row, $data );
		return $user;
	}

	//@}

	/**
	 * Get the username corresponding to a given user ID
	 * @param int $id User ID
	 * @return string|bool The corresponding username
	 */
	public static function whoIs( $id ) {
		return UserCache::singleton()->getProp( $id, 'name' );
	}

	/**
	 * Get the real name of a user given their user ID
	 *
	 * @param int $id User ID
	 * @return string|bool The corresponding user's real name
	 */
	public static function whoIsReal( $id ) {
		return UserCache::singleton()->getProp( $id, 'real_name' );
	}

	/**
	 * Get database id given a user name
	 * @param string $name Username
	 * @return int|null The corresponding user's ID, or null if user is nonexistent
	 */
	public static function idFromName( $name ) {
		$nt = Title::makeTitleSafe( NS_USER, $name );
		if ( is_null( $nt ) ) {
			// Illegal name
			return null;
		}

		if ( isset( self::$idCacheByName[$name] ) ) {
			return self::$idCacheByName[$name];
		}

		$dbr = wfGetDB( DB_SLAVE );
		$s = $dbr->selectRow( 'user', array( 'user_id' ), array( 'user_name' => $nt->getText() ), __METHOD__ );

		if ( $s === false ) {
			$result = null;
		} else {
			$result = $s->user_id;
		}

		self::$idCacheByName[$name] = $result;

		if ( count( self::$idCacheByName ) > 1000 ) {
			self::$idCacheByName = array();
		}

		return $result;
	}

	/**
	 * Reset the cache used in idFromName(). For use in tests.
	 */
	public static function resetIdByNameCache() {
		self::$idCacheByName = array();
	}

	/**
	 * Does the string match an anonymous IPv4 address?
	 *
	 * This function exists for username validation, in order to reject
	 * usernames which are similar in form to IP addresses. Strings such
	 * as 300.300.300.300 will return true because it looks like an IP
	 * address, despite not being strictly valid.
	 *
	 * We match "\d{1,3}\.\d{1,3}\.\d{1,3}\.xxx" as an anonymous IP
	 * address because the usemod software would "cloak" anonymous IP
	 * addresses like this, if we allowed accounts like this to be created
	 * new users could get the old edits of these anonymous users.
	 *
	 * @param string $name Name to match
	 * @return bool
	 */
	public static function isIP( $name ) {
		return preg_match( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.(?:xxx|\d{1,3})$/', $name ) || IP::isIPv6( $name );
	}

	/**
	 * Is the input a valid username?
	 *
	 * Checks if the input is a valid username, we don't want an empty string,
	 * an IP address, anything that contains slashes (would mess up subpages),
	 * is longer than the maximum allowed username size or doesn't begin with
	 * a capital letter.
	 *
	 * @param string $name Name to match
	 * @return bool
	 */
	public static function isValidUserName( $name ) {
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
		if ( is_null( $parsed )
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
		if ( preg_match( $unicodeBlacklist, $name ) ) {
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
	 * @param string $name Name to match
	 * @return bool
	 */
	public static function isUsableName( $name ) {
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
				$reserved = wfMessage( substr( $reserved, 4 ) )->inContentLanguage()->text();
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
	 * Additional blacklisting may be added here rather than in
	 * isValidUserName() to avoid disrupting existing accounts.
	 *
	 * @param string $name to match
	 * @return bool
	 */
	public static function isCreatableName( $name ) {
		global $wgInvalidUsernameCharacters;

		// Ensure that the username isn't longer than 235 bytes, so that
		// (at least for the builtin skins) user javascript and css files
		// will work. (bug 23080)
		if ( strlen( $name ) > 235 ) {
			wfDebugLog( 'username', __METHOD__ .
				": '$name' invalid due to length" );
			return false;
		}

		// Preg yells if you try to give it an empty string
		if ( $wgInvalidUsernameCharacters !== '' ) {
			if ( preg_match( '/[' . preg_quote( $wgInvalidUsernameCharacters, '/' ) . ']/', $name ) ) {
				wfDebugLog( 'username', __METHOD__ .
					": '$name' invalid due to wgInvalidUsernameCharacters" );
				return false;
			}
		}

		return self::isUsableName( $name );
	}

	/**
	 * Is the input a valid password for this user?
	 *
	 * @param string $password Desired password
	 * @return bool
	 */
	public function isValidPassword( $password ) {
		//simple boolean wrapper for getPasswordValidity
		return $this->getPasswordValidity( $password ) === true;
	}


	/**
	 * Given unvalidated password input, return error message on failure.
	 *
	 * @param string $password Desired password
	 * @return mixed: true on success, string or array of error message on failure
	 */
	public function getPasswordValidity( $password ) {
		$result = $this->checkPasswordValidity( $password );
		if ( $result->isGood() ) {
			return true;
		} else {
			$messages = array();
			foreach ( $result->getErrorsByType( 'error' ) as $error ) {
				$messages[] = $error['message'];
			}
			foreach ( $result->getErrorsByType( 'warning' ) as $warning ) {
				$messages[] = $warning['message'];
			}
			if ( count( $messages ) === 1 ) {
				return $messages[0];
			}
			return $messages;
		}
	}

	/**
	 * Check if this is a valid password for this user. Status will be good if
	 * the password is valid, or have an array of error messages if not.
	 *
	 * @param string $password Desired password
	 * @return Status
	 * @since 1.23
	 */
	public function checkPasswordValidity( $password ) {
		global $wgMinimalPasswordLength, $wgContLang;

		static $blockedLogins = array(
			'Useruser' => 'Passpass', 'Useruser1' => 'Passpass1', # r75589
			'Apitestsysop' => 'testpass', 'Apitestuser' => 'testpass' # r75605
		);

		$status = Status::newGood();

		$result = false; //init $result to false for the internal checks

		if ( !wfRunHooks( 'isValidPassword', array( $password, &$result, $this ) ) ) {
			$status->error( $result );
			return $status;
		}

		if ( $result === false ) {
			if ( strlen( $password ) < $wgMinimalPasswordLength ) {
				$status->error( 'passwordtooshort', $wgMinimalPasswordLength );
				return $status;
			} elseif ( $wgContLang->lc( $password ) == $wgContLang->lc( $this->mName ) ) {
				$status->error( 'password-name-match' );
				return $status;
			} elseif ( isset( $blockedLogins[$this->getName()] ) && $password == $blockedLogins[$this->getName()] ) {
				$status->error( 'password-login-forbidden' );
				return $status;
			} else {
				//it seems weird returning a Good status here, but this is because of the
				//initialization of $result to false above. If the hook is never run or it
				//doesn't modify $result, then we will likely get down into this if with
				//a valid password.
				return $status;
			}
		} elseif ( $result === true ) {
			return $status;
		} else {
			$status->error( $result );
			return $status; //the isValidPassword hook set a string $result and returned true
		}
	}

	/**
	 * Expire a user's password
	 * @since 1.23
	 * @param $ts Mixed: optional timestamp to convert, default 0 for the current time
	 */
	public function expirePassword( $ts = 0 ) {
		$this->load();
		$timestamp = wfTimestamp( TS_MW, $ts );
		$this->mPasswordExpires = $timestamp;
		$this->saveSettings();
	}

	/**
	 * Clear the password expiration for a user
	 * @since 1.23
	 * @param bool $load ensure user object is loaded first
	 */
	public function resetPasswordExpiration( $load = true ) {
		global $wgPasswordExpirationDays;
		if ( $load ) {
			$this->load();
		}
		$newExpire = null;
		if ( $wgPasswordExpirationDays ) {
			$newExpire = wfTimestamp(
				TS_MW,
				time() + ( $wgPasswordExpirationDays * 24 * 3600 )
			);
		}
		// Give extensions a chance to force an expiration
		wfRunHooks( 'ResetPasswordExpiration', array( $this, &$newExpire ) );
		$this->mPasswordExpires = $newExpire;
	}

	/**
	 * Check if the user's password is expired.
	 * TODO: Put this and password length into a PasswordPolicy object
	 * @since 1.23
	 * @return string|bool The expiration type, or false if not expired
	 * 	hard: A password change is required to login
	 *	soft: Allow login, but encourage password change
	 *	false: Password is not expired
	 */
	public function getPasswordExpired() {
		global $wgPasswordExpireGrace;
		$expired = false;
		$now = wfTimestamp();
		$expiration = $this->getPasswordExpireDate();
		$expUnix = wfTimestamp( TS_UNIX, $expiration );
		if ( $expiration !== null && $expUnix < $now ) {
			$expired = ( $expUnix + $wgPasswordExpireGrace < $now ) ? 'hard' : 'soft';
		}
		return $expired;
	}

	/**
	 * Get this user's password expiration date. Since this may be using
	 * the cached User object, we assume that whatever mechanism is setting
	 * the expiration date is also expiring the User cache.
	 * @since 1.23
	 * @return string|false the datestamp of the expiration, or null if not set
	 */
	public function getPasswordExpireDate() {
		$this->load();
		return $this->mPasswordExpires;
	}

	/**
	 * Does a string look like an e-mail address?
	 *
	 * This validates an email address using an HTML5 specification found at:
	 * http://www.whatwg.org/html/states-of-the-type-attribute.html#valid-e-mail-address
	 * Which as of 2011-01-24 says:
	 *
	 *     A valid e-mail address is a string that matches the ABNF production
	 *   1*( atext / "." ) "@" ldh-str *( "." ldh-str ) where atext is defined
	 *   in RFC 5322 section 3.2.3, and ldh-str is defined in RFC 1034 section
	 *   3.5.
	 *
	 * This function is an implementation of the specification as requested in
	 * bug 22449.
	 *
	 * Client-side forms will use the same standard validation rules via JS or
	 * HTML 5 validation; additional restrictions can be enforced server-side
	 * by extensions via the 'isValidEmailAddr' hook.
	 *
	 * Note that this validation doesn't 100% match RFC 2822, but is believed
	 * to be liberal enough for wide use. Some invalid addresses will still
	 * pass validation here.
	 *
	 * @param string $addr E-mail address
	 * @return bool
	 * @deprecated since 1.18 call Sanitizer::isValidEmail() directly
	 */
	public static function isValidEmailAddr( $addr ) {
		wfDeprecated( __METHOD__, '1.18' );
		return Sanitizer::validateEmail( $addr );
	}

	/**
	 * Given unvalidated user input, return a canonical username, or false if
	 * the username is invalid.
	 * @param string $name User input
	 * @param string|bool $validate type of validation to use:
	 *                - false        No validation
	 *                - 'valid'      Valid for batch processes
	 *                - 'usable'     Valid for batch processes and login
	 *                - 'creatable'  Valid for batch processes, login and account creation
	 *
	 * @throws MWException
	 * @return bool|string
	 */
	public static function getCanonicalName( $name, $validate = 'valid' ) {
		// Force usernames to capital
		global $wgContLang;
		$name = $wgContLang->ucfirst( $name );

		# Reject names containing '#'; these will be cleaned up
		# with title normalisation, but then it's too late to
		# check elsewhere
		if ( strpos( $name, '#' ) !== false ) {
			return false;
		}

		// Clean up name according to title rules
		$t = ( $validate === 'valid' ) ?
			Title::newFromText( $name ) : Title::makeTitle( NS_USER, $name );
		// Check for invalid titles
		if ( is_null( $t ) ) {
			return false;
		}

		// Reject various classes of invalid names
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
				throw new MWException( 'Invalid parameter value for $validate in ' . __METHOD__ );
		}
		return $name;
	}

	/**
	 * Count the number of edits of a user
	 *
	 * @param int $uid User ID to check
	 * @return int The user's edit count
	 *
	 * @deprecated since 1.21 in favour of User::getEditCount
	 */
	public static function edits( $uid ) {
		wfDeprecated( __METHOD__, '1.21' );
		$user = self::newFromId( $uid );
		return $user->getEditCount();
	}

	/**
	 * Return a random password.
	 *
	 * @return string New random password
	 */
	public static function randomPassword() {
		global $wgMinimalPasswordLength;
		// Decide the final password length based on our min password length, stopping at a minimum of 10 chars
		$length = max( 10, $wgMinimalPasswordLength );
		// Multiply by 1.25 to get the number of hex characters we need
		$length = $length * 1.25;
		// Generate random hex chars
		$hex = MWCryptRand::generateHex( $length );
		// Convert from base 16 to base 32 to get a proper password like string
		return wfBaseConvert( $hex, 16, 32 );
	}

	/**
	 * Set cached properties to default.
	 *
	 * @note This no longer clears uncached lazy-initialised properties;
	 *       the constructor does that instead.
	 *
	 * @param $name string|bool
	 */
	public function loadDefaults( $name = false ) {
		wfProfileIn( __METHOD__ );

		$this->mId = 0;
		$this->mName = $name;
		$this->mRealName = '';
		$this->mPassword = $this->mNewpassword = '';
		$this->mNewpassTime = null;
		$this->mEmail = '';
		$this->mOptionOverrides = null;
		$this->mOptionsLoaded = false;

		$loggedOut = $this->getRequest()->getCookie( 'LoggedOut' );
		if ( $loggedOut !== null ) {
			$this->mTouched = wfTimestamp( TS_MW, $loggedOut );
		} else {
			$this->mTouched = '1'; # Allow any pages to be cached
		}

		$this->mToken = null; // Don't run cryptographic functions till we need a token
		$this->mEmailAuthenticated = null;
		$this->mEmailToken = '';
		$this->mEmailTokenExpires = null;
		$this->mPasswordExpires = null;
		$this->resetPasswordExpiration( false );
		$this->mRegistration = wfTimestamp( TS_MW );
		$this->mGroups = array();

		wfRunHooks( 'UserLoadDefaults', array( $this, $name ) );

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Return whether an item has been loaded.
	 *
	 * @param string $item item to check. Current possibilities:
	 *              - id
	 *              - name
	 *              - realname
	 * @param string $all 'all' to check if the whole object has been loaded
	 *        or any other string to check if only the item is available (e.g.
	 *        for optimisation)
	 * @return boolean
	 */
	public function isItemLoaded( $item, $all = 'all' ) {
		return ( $this->mLoadedItems === true && $all === 'all' ) ||
			( isset( $this->mLoadedItems[$item] ) && $this->mLoadedItems[$item] === true );
	}

	/**
	 * Set that an item has been loaded
	 *
	 * @param string $item
	 */
	protected function setItemLoaded( $item ) {
		if ( is_array( $this->mLoadedItems ) ) {
			$this->mLoadedItems[$item] = true;
		}
	}

	/**
	 * Load user data from the session or login cookie.
	 * @return bool True if the user is logged in, false otherwise.
	 */
	private function loadFromSession() {
		$result = null;
		wfRunHooks( 'UserLoadFromSession', array( $this, &$result ) );
		if ( $result !== null ) {
			return $result;
		}

		$request = $this->getRequest();

		$cookieId = $request->getCookie( 'UserID' );
		$sessId = $request->getSessionData( 'wsUserID' );

		if ( $cookieId !== null ) {
			$sId = intval( $cookieId );
			if ( $sessId !== null && $cookieId != $sessId ) {
				wfDebugLog( 'loginSessions', "Session user ID ($sessId) and
					cookie user ID ($sId) don't match!" );
				return false;
			}
			$request->setSessionData( 'wsUserID', $sId );
		} elseif ( $sessId !== null && $sessId != 0 ) {
			$sId = $sessId;
		} else {
			return false;
		}

		if ( $request->getSessionData( 'wsUserName' ) !== null ) {
			$sName = $request->getSessionData( 'wsUserName' );
		} elseif ( $request->getCookie( 'UserName' ) !== null ) {
			$sName = $request->getCookie( 'UserName' );
			$request->setSessionData( 'wsUserName', $sName );
		} else {
			return false;
		}

		$proposedUser = User::newFromId( $sId );
		if ( !$proposedUser->isLoggedIn() ) {
			// Not a valid ID
			return false;
		}

		global $wgBlockDisablesLogin;
		if ( $wgBlockDisablesLogin && $proposedUser->isBlocked() ) {
			// User blocked and we've disabled blocked user logins
			return false;
		}

		if ( $request->getSessionData( 'wsToken' ) ) {
			$passwordCorrect = ( $proposedUser->getToken( false ) === $request->getSessionData( 'wsToken' ) );
			$from = 'session';
		} elseif ( $request->getCookie( 'Token' ) ) {
			# Get the token from DB/cache and clean it up to remove garbage padding.
			# This deals with historical problems with bugs and the default column value.
			$token = rtrim( $proposedUser->getToken( false ) ); // correct token
			// Make comparison in constant time (bug 61346)
			$passwordCorrect = strlen( $token ) && $this->compareSecrets( $token, $request->getCookie( 'Token' ) );
			$from = 'cookie';
		} else {
			// No session or persistent login cookie
			return false;
		}

		if ( ( $sName === $proposedUser->getName() ) && $passwordCorrect ) {
			$this->loadFromUserObject( $proposedUser );
			$request->setSessionData( 'wsToken', $this->mToken );
			wfDebug( "User: logged in from $from\n" );
			return true;
		} else {
			// Invalid credentials
			wfDebug( "User: can't log in from $from, invalid credentials\n" );
			return false;
		}
	}

	/**
	 * A comparison of two strings, not vulnerable to timing attacks
	 * @param string $answer the secret string that you are comparing against.
	 * @param string $test compare this string to the $answer.
	 * @return bool True if the strings are the same, false otherwise
	 */
	protected function compareSecrets( $answer, $test ) {
		if ( strlen( $answer ) !== strlen( $test ) ) {
			$passwordCorrect = false;
		} else {
			$result = 0;
			for ( $i = 0; $i < strlen( $answer ); $i++ ) {
				$result |= ord( $answer[$i] ) ^ ord( $test[$i] );
			}
			$passwordCorrect = ( $result == 0 );
		}
		return $passwordCorrect;
	}

	/**
	 * Load user and user_group data from the database.
	 * $this->mId must be set, this is how the user is identified.
	 *
	 * @return bool True if the user exists, false if the user is anonymous
	 */
	public function loadFromDatabase() {
		// Paranoia
		$this->mId = intval( $this->mId );

		// Anonymous user
		if ( !$this->mId ) {
			$this->loadDefaults();
			return false;
		}

		$dbr = wfGetDB( DB_MASTER );
		$s = $dbr->selectRow( 'user', self::selectFields(), array( 'user_id' => $this->mId ), __METHOD__ );

		wfRunHooks( 'UserLoadFromDatabase', array( $this, &$s ) );

		if ( $s !== false ) {
			// Initialise user table data
			$this->loadFromRow( $s );
			$this->mGroups = null; // deferred
			$this->getEditCount(); // revalidation for nulls
			return true;
		} else {
			// Invalid user_id
			$this->mId = 0;
			$this->loadDefaults();
			return false;
		}
	}

	/**
	 * Initialize this object from a row from the user table.
	 *
	 * @param stdClass $row Row from the user table to load.
	 * @param array $data Further user data to load into the object
	 *
	 *	user_groups		Array with groups out of the user_groups table
	 *	user_properties		Array with properties out of the user_properties table
	 */
	public function loadFromRow( $row, $data = null ) {
		$all = true;

		$this->mGroups = null; // deferred

		if ( isset( $row->user_name ) ) {
			$this->mName = $row->user_name;
			$this->mFrom = 'name';
			$this->setItemLoaded( 'name' );
		} else {
			$all = false;
		}

		if ( isset( $row->user_real_name ) ) {
			$this->mRealName = $row->user_real_name;
			$this->setItemLoaded( 'realname' );
		} else {
			$all = false;
		}

		if ( isset( $row->user_id ) ) {
			$this->mId = intval( $row->user_id );
			$this->mFrom = 'id';
			$this->setItemLoaded( 'id' );
		} else {
			$all = false;
		}

		if ( isset( $row->user_editcount ) ) {
			$this->mEditCount = $row->user_editcount;
		} else {
			$all = false;
		}

		if ( isset( $row->user_password ) ) {
			$this->mPassword = $row->user_password;
			$this->mNewpassword = $row->user_newpassword;
			$this->mNewpassTime = wfTimestampOrNull( TS_MW, $row->user_newpass_time );
			$this->mEmail = $row->user_email;
			$this->mTouched = wfTimestamp( TS_MW, $row->user_touched );
			$this->mToken = $row->user_token;
			if ( $this->mToken == '' ) {
				$this->mToken = null;
			}
			$this->mEmailAuthenticated = wfTimestampOrNull( TS_MW, $row->user_email_authenticated );
			$this->mEmailToken = $row->user_email_token;
			$this->mEmailTokenExpires = wfTimestampOrNull( TS_MW, $row->user_email_token_expires );
			$this->mPasswordExpires = wfTimestampOrNull( TS_MW, $row->user_password_expires );
			$this->mRegistration = wfTimestampOrNull( TS_MW, $row->user_registration );
		} else {
			$all = false;
		}

		if ( $all ) {
			$this->mLoadedItems = true;
		}

		if ( is_array( $data ) ) {
			if ( isset( $data['user_groups'] ) && is_array( $data['user_groups'] ) ) {
				$this->mGroups = $data['user_groups'];
			}
			if ( isset( $data['user_properties'] ) && is_array( $data['user_properties'] ) ) {
				$this->loadOptions( $data['user_properties'] );
			}
		}
	}

	/**
	 * Load the data for this user object from another user object.
	 *
	 * @param $user User
	 */
	protected function loadFromUserObject( $user ) {
		$user->load();
		$user->loadGroups();
		$user->loadOptions();
		foreach ( self::$mCacheVars as $var ) {
			$this->$var = $user->$var;
		}
	}

	/**
	 * Load the groups from the database if they aren't already loaded.
	 */
	private function loadGroups() {
		if ( is_null( $this->mGroups ) ) {
			$dbr = wfGetDB( DB_MASTER );
			$res = $dbr->select( 'user_groups',
				array( 'ug_group' ),
				array( 'ug_user' => $this->mId ),
				__METHOD__ );
			$this->mGroups = array();
			foreach ( $res as $row ) {
				$this->mGroups[] = $row->ug_group;
			}
		}
	}

	/**
	 * Add the user to the group if he/she meets given criteria.
	 *
	 * Contrary to autopromotion by \ref $wgAutopromote, the group will be
	 *   possible to remove manually via Special:UserRights. In such case it
	 *   will not be re-added automatically. The user will also not lose the
	 *   group if they no longer meet the criteria.
	 *
	 * @param string $event key in $wgAutopromoteOnce (each one has groups/criteria)
	 *
	 * @return array Array of groups the user has been promoted to.
	 *
	 * @see $wgAutopromoteOnce
	 */
	public function addAutopromoteOnceGroups( $event ) {
		global $wgAutopromoteOnceLogInRC, $wgAuth;

		$toPromote = array();
		if ( $this->getId() ) {
			$toPromote = Autopromote::getAutopromoteOnceGroups( $this, $event );
			if ( count( $toPromote ) ) {
				$oldGroups = $this->getGroups(); // previous groups

				foreach ( $toPromote as $group ) {
					$this->addGroup( $group );
				}
				// update groups in external authentication database
				$wgAuth->updateExternalDBGroups( $this, $toPromote );

				$newGroups = array_merge( $oldGroups, $toPromote ); // all groups

				$logEntry = new ManualLogEntry( 'rights', 'autopromote' );
				$logEntry->setPerformer( $this );
				$logEntry->setTarget( $this->getUserPage() );
				$logEntry->setParameters( array(
					'4::oldgroups' => $oldGroups,
					'5::newgroups' => $newGroups,
				) );
				$logid = $logEntry->insert();
				if ( $wgAutopromoteOnceLogInRC ) {
					$logEntry->publish( $logid );
				}
			}
		}
		return $toPromote;
	}

	/**
	 * Clear various cached data stored in this object. The cache of the user table
	 * data (i.e. self::$mCacheVars) is not cleared unless $reloadFrom is given.
	 *
	 * @param bool|string $reloadFrom Reload user and user_groups table data from a
	 *   given source. May be "name", "id", "defaults", "session", or false for
	 *   no reload.
	 */
	public function clearInstanceCache( $reloadFrom = false ) {
		$this->mNewtalk = -1;
		$this->mDatePreference = null;
		$this->mBlockedby = -1; # Unset
		$this->mHash = false;
		$this->mRights = null;
		$this->mEffectiveGroups = null;
		$this->mImplicitGroups = null;
		$this->mGroups = null;
		$this->mOptions = null;
		$this->mOptionsLoaded = false;
		$this->mEditCount = null;

		if ( $reloadFrom ) {
			$this->mLoadedItems = array();
			$this->mFrom = $reloadFrom;
		}
	}

	/**
	 * Combine the language default options with any site-specific options
	 * and add the default language variants.
	 *
	 * @return Array of String options
	 */
	public static function getDefaultOptions() {
		global $wgNamespacesToBeSearchedDefault, $wgDefaultUserOptions, $wgContLang, $wgDefaultSkin;

		static $defOpt = null;
		if ( !defined( 'MW_PHPUNIT_TEST' ) && $defOpt !== null ) {
			// Disabling this for the unit tests, as they rely on being able to change $wgContLang
			// mid-request and see that change reflected in the return value of this function.
			// Which is insane and would never happen during normal MW operation
			return $defOpt;
		}

		$defOpt = $wgDefaultUserOptions;
		// Default language setting
		$defOpt['language'] = $wgContLang->getCode();
		foreach ( LanguageConverter::$languagesWithVariants as $langCode ) {
			$defOpt[$langCode == $wgContLang->getCode() ? 'variant' : "variant-$langCode"] = $langCode;
		}
		foreach ( SearchEngine::searchableNamespaces() as $nsnum => $nsname ) {
			$defOpt['searchNs' . $nsnum] = !empty( $wgNamespacesToBeSearchedDefault[$nsnum] );
		}
		$defOpt['skin'] = $wgDefaultSkin;

		wfRunHooks( 'UserGetDefaultOptions', array( &$defOpt ) );

		return $defOpt;
	}

	/**
	 * Get a given default option value.
	 *
	 * @param string $opt Name of option to retrieve
	 * @return string Default option value
	 */
	public static function getDefaultOption( $opt ) {
		$defOpts = self::getDefaultOptions();
		if ( isset( $defOpts[$opt] ) ) {
			return $defOpts[$opt];
		} else {
			return null;
		}
	}

	/**
	 * Get blocking information
	 * @param bool $bFromSlave Whether to check the slave database first. To
	 *                    improve performance, non-critical checks are done
	 *                    against slaves. Check when actually saving should be
	 *                    done against master.
	 */
	private function getBlockedStatus( $bFromSlave = true ) {
		global $wgProxyWhitelist, $wgUser, $wgApplyIpBlocksToXff;

		if ( -1 != $this->mBlockedby ) {
			return;
		}

		wfProfileIn( __METHOD__ );
		wfDebug( __METHOD__ . ": checking...\n" );

		// Initialize data...
		// Otherwise something ends up stomping on $this->mBlockedby when
		// things get lazy-loaded later, causing false positive block hits
		// due to -1 !== 0. Probably session-related... Nothing should be
		// overwriting mBlockedby, surely?
		$this->load();

		# We only need to worry about passing the IP address to the Block generator if the
		# user is not immune to autoblocks/hardblocks, and they are the current user so we
		# know which IP address they're actually coming from
		if ( !$this->isAllowed( 'ipblock-exempt' ) && $this->getID() == $wgUser->getID() ) {
			$ip = $this->getRequest()->getIP();
		} else {
			$ip = null;
		}

		// User/IP blocking
		$block = Block::newFromTarget( $this, $ip, !$bFromSlave );

		// Proxy blocking
		if ( !$block instanceof Block && $ip !== null && !$this->isAllowed( 'proxyunbannable' )
			&& !in_array( $ip, $wgProxyWhitelist )
		) {
			// Local list
			if ( self::isLocallyBlockedProxy( $ip ) ) {
				$block = new Block;
				$block->setBlocker( wfMessage( 'proxyblocker' )->text() );
				$block->mReason = wfMessage( 'proxyblockreason' )->text();
				$block->setTarget( $ip );
			} elseif ( $this->isAnon() && $this->isDnsBlacklisted( $ip ) ) {
				$block = new Block;
				$block->setBlocker( wfMessage( 'sorbs' )->text() );
				$block->mReason = wfMessage( 'sorbsreason' )->text();
				$block->setTarget( $ip );
			}
		}

		// (bug 23343) Apply IP blocks to the contents of XFF headers, if enabled
		if ( !$block instanceof Block
			&& $wgApplyIpBlocksToXff
			&& $ip !== null
			&& !$this->isAllowed( 'proxyunbannable' )
			&& !in_array( $ip, $wgProxyWhitelist )
		) {
			$xff = $this->getRequest()->getHeader( 'X-Forwarded-For' );
			$xff = array_map( 'trim', explode( ',', $xff ) );
			$xff = array_diff( $xff, array( $ip ) );
			$xffblocks = Block::getBlocksForIPList( $xff, $this->isAnon(), !$bFromSlave );
			$block = Block::chooseBlock( $xffblocks, $xff );
			if ( $block instanceof Block ) {
				# Mangle the reason to alert the user that the block
				# originated from matching the X-Forwarded-For header.
				$block->mReason = wfMessage( 'xffblockreason', $block->mReason )->text();
			}
		}

		if ( $block instanceof Block ) {
			wfDebug( __METHOD__ . ": Found block.\n" );
			$this->mBlock = $block;
			$this->mBlockedby = $block->getByName();
			$this->mBlockreason = $block->mReason;
			$this->mHideName = $block->mHideName;
			$this->mAllowUsertalk = !$block->prevents( 'editownusertalk' );
		} else {
			$this->mBlockedby = '';
			$this->mHideName = 0;
			$this->mAllowUsertalk = false;
		}

		// Extensions
		wfRunHooks( 'GetBlockedStatus', array( &$this ) );

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Whether the given IP is in a DNS blacklist.
	 *
	 * @param string $ip IP to check
	 * @param bool $checkWhitelist whether to check the whitelist first
	 * @return bool True if blacklisted.
	 */
	public function isDnsBlacklisted( $ip, $checkWhitelist = false ) {
		global $wgEnableSorbs, $wgEnableDnsBlacklist,
			$wgSorbsUrl, $wgDnsBlacklistUrls, $wgProxyWhitelist;

		if ( !$wgEnableDnsBlacklist && !$wgEnableSorbs ) {
			return false;
		}

		if ( $checkWhitelist && in_array( $ip, $wgProxyWhitelist ) ) {
			return false;
		}

		$urls = array_merge( $wgDnsBlacklistUrls, (array)$wgSorbsUrl );
		return $this->inDnsBlacklist( $ip, $urls );
	}

	/**
	 * Whether the given IP is in a given DNS blacklist.
	 *
	 * @param string $ip IP to check
	 * @param string|array $bases of Strings: URL of the DNS blacklist
	 * @return bool True if blacklisted.
	 */
	public function inDnsBlacklist( $ip, $bases ) {
		wfProfileIn( __METHOD__ );

		$found = false;
		// @todo FIXME: IPv6 ???  (http://bugs.php.net/bug.php?id=33170)
		if ( IP::isIPv4( $ip ) ) {
			// Reverse IP, bug 21255
			$ipReversed = implode( '.', array_reverse( explode( '.', $ip ) ) );

			foreach ( (array)$bases as $base ) {
				// Make hostname
				// If we have an access key, use that too (ProjectHoneypot, etc.)
				if ( is_array( $base ) ) {
					if ( count( $base ) >= 2 ) {
						// Access key is 1, base URL is 0
						$host = "{$base[1]}.$ipReversed.{$base[0]}";
					} else {
						$host = "$ipReversed.{$base[0]}";
					}
				} else {
					$host = "$ipReversed.$base";
				}

				// Send query
				$ipList = gethostbynamel( $host );

				if ( $ipList ) {
					wfDebugLog( 'dnsblacklist', "Hostname $host is {$ipList[0]}, it's a proxy says $base!" );
					$found = true;
					break;
				} else {
					wfDebugLog( 'dnsblacklist', "Requested $host, not found in $base." );
				}
			}
		}

		wfProfileOut( __METHOD__ );
		return $found;
	}

	/**
	 * Check if an IP address is in the local proxy list
	 *
	 * @param $ip string
	 *
	 * @return bool
	 */
	public static function isLocallyBlockedProxy( $ip ) {
		global $wgProxyList;

		if ( !$wgProxyList ) {
			return false;
		}
		wfProfileIn( __METHOD__ );

		if ( !is_array( $wgProxyList ) ) {
			// Load from the specified file
			$wgProxyList = array_map( 'trim', file( $wgProxyList ) );
		}

		if ( !is_array( $wgProxyList ) ) {
			$ret = false;
		} elseif ( array_search( $ip, $wgProxyList ) !== false ) {
			$ret = true;
		} elseif ( array_key_exists( $ip, $wgProxyList ) ) {
			// Old-style flipped proxy list
			$ret = true;
		} else {
			$ret = false;
		}
		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * Is this user subject to rate limiting?
	 *
	 * @return bool True if rate limited
	 */
	public function isPingLimitable() {
		global $wgRateLimitsExcludedIPs;
		if ( in_array( $this->getRequest()->getIP(), $wgRateLimitsExcludedIPs ) ) {
			// No other good way currently to disable rate limits
			// for specific IPs. :P
			// But this is a crappy hack and should die.
			return false;
		}
		return !$this->isAllowed( 'noratelimit' );
	}

	/**
	 * Primitive rate limits: enforce maximum actions per time period
	 * to put a brake on flooding.
	 *
	 * @note When using a shared cache like memcached, IP-address
	 * last-hit counters will be shared across wikis.
	 *
	 * @param string $action Action to enforce; 'edit' if unspecified
	 * @param integer $incrBy Positive amount to increment counter by [defaults to 1]
	 * @return bool True if a rate limiter was tripped
	 */
	public function pingLimiter( $action = 'edit', $incrBy = 1 ) {
		// Call the 'PingLimiter' hook
		$result = false;
		if ( !wfRunHooks( 'PingLimiter', array( &$this, $action, &$result, $incrBy ) ) ) {
			return $result;
		}

		global $wgRateLimits;
		if ( !isset( $wgRateLimits[$action] ) ) {
			return false;
		}

		// Some groups shouldn't trigger the ping limiter, ever
		if ( !$this->isPingLimitable() ) {
			return false;
		}

		global $wgMemc;
		wfProfileIn( __METHOD__ );

		$limits = $wgRateLimits[$action];
		$keys = array();
		$id = $this->getId();
		$userLimit = false;

		if ( isset( $limits['anon'] ) && $id == 0 ) {
			$keys[wfMemcKey( 'limiter', $action, 'anon' )] = $limits['anon'];
		}

		if ( isset( $limits['user'] ) && $id != 0 ) {
			$userLimit = $limits['user'];
		}
		if ( $this->isNewbie() ) {
			if ( isset( $limits['newbie'] ) && $id != 0 ) {
				$keys[wfMemcKey( 'limiter', $action, 'user', $id )] = $limits['newbie'];
			}
			if ( isset( $limits['ip'] ) ) {
				$ip = $this->getRequest()->getIP();
				$keys["mediawiki:limiter:$action:ip:$ip"] = $limits['ip'];
			}
			if ( isset( $limits['subnet'] ) ) {
				$ip = $this->getRequest()->getIP();
				$matches = array();
				$subnet = false;
				if ( IP::isIPv6( $ip ) ) {
					$parts = IP::parseRange( "$ip/64" );
					$subnet = $parts[0];
				} elseif ( preg_match( '/^(\d+\.\d+\.\d+)\.\d+$/', $ip, $matches ) ) {
					// IPv4
					$subnet = $matches[1];
				}
				if ( $subnet !== false ) {
					$keys["mediawiki:limiter:$action:subnet:$subnet"] = $limits['subnet'];
				}
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
			list( $max, $period ) = $userLimit;
			wfDebug( __METHOD__ . ": effective user limit: $max in {$period}s\n" );
			$keys[wfMemcKey( 'limiter', $action, 'user', $id )] = $userLimit;
		}

		$triggered = false;
		foreach ( $keys as $key => $limit ) {
			list( $max, $period ) = $limit;
			$summary = "(limit $max in {$period}s)";
			$count = $wgMemc->get( $key );
			// Already pinged?
			if ( $count ) {
				if ( $count >= $max ) {
					wfDebugLog( 'ratelimit', $this->getName() . " tripped! $key at $count $summary" );
					$triggered = true;
				} else {
					wfDebug( __METHOD__ . ": ok. $key at $count $summary\n" );
				}
			} else {
				wfDebug( __METHOD__ . ": adding record for $key $summary\n" );
				if ( $incrBy > 0 ) {
					$wgMemc->add( $key, 0, intval( $period ) ); // first ping
				}
			}
			if ( $incrBy > 0 ) {
				$wgMemc->incr( $key, $incrBy );
			}
		}

		wfProfileOut( __METHOD__ );
		return $triggered;
	}

	/**
	 * Check if user is blocked
	 *
	 * @param bool $bFromSlave Whether to check the slave database instead of the master
	 * @return bool True if blocked, false otherwise
	 */
	public function isBlocked( $bFromSlave = true ) { // hacked from false due to horrible probs on site
		return $this->getBlock( $bFromSlave ) instanceof Block && $this->getBlock()->prevents( 'edit' );
	}

	/**
	 * Get the block affecting the user, or null if the user is not blocked
	 *
	 * @param bool $bFromSlave Whether to check the slave database instead of the master
	 * @return Block|null
	 */
	public function getBlock( $bFromSlave = true ) {
		$this->getBlockedStatus( $bFromSlave );
		return $this->mBlock instanceof Block ? $this->mBlock : null;
	}

	/**
	 * Check if user is blocked from editing a particular article
	 *
	 * @param Title $title Title to check
	 * @param bool $bFromSlave whether to check the slave database instead of the master
	 * @return bool
	 */
	public function isBlockedFrom( $title, $bFromSlave = false ) {
		global $wgBlockAllowsUTEdit;
		wfProfileIn( __METHOD__ );

		$blocked = $this->isBlocked( $bFromSlave );
		$allowUsertalk = ( $wgBlockAllowsUTEdit ? $this->mAllowUsertalk : false );
		// If a user's name is suppressed, they cannot make edits anywhere
		if ( !$this->mHideName && $allowUsertalk && $title->getText() === $this->getName()
			&& $title->getNamespace() == NS_USER_TALK ) {
			$blocked = false;
			wfDebug( __METHOD__ . ": self-talk page, ignoring any blocks\n" );
		}

		wfRunHooks( 'UserIsBlockedFrom', array( $this, $title, &$blocked, &$allowUsertalk ) );

		wfProfileOut( __METHOD__ );
		return $blocked;
	}

	/**
	 * If user is blocked, return the name of the user who placed the block
	 * @return string Name of blocker
	 */
	public function blockedBy() {
		$this->getBlockedStatus();
		return $this->mBlockedby;
	}

	/**
	 * If user is blocked, return the specified reason for the block
	 * @return string Blocking reason
	 */
	public function blockedFor() {
		$this->getBlockedStatus();
		return $this->mBlockreason;
	}

	/**
	 * If user is blocked, return the ID for the block
	 * @return int Block ID
	 */
	public function getBlockId() {
		$this->getBlockedStatus();
		return ( $this->mBlock ? $this->mBlock->getId() : false );
	}

	/**
	 * Check if user is blocked on all wikis.
	 * Do not use for actual edit permission checks!
	 * This is intended for quick UI checks.
	 *
	 * @param string $ip IP address, uses current client if none given
	 * @return bool True if blocked, false otherwise
	 */
	public function isBlockedGlobally( $ip = '' ) {
		if ( $this->mBlockedGlobally !== null ) {
			return $this->mBlockedGlobally;
		}
		// User is already an IP?
		if ( IP::isIPAddress( $this->getName() ) ) {
			$ip = $this->getName();
		} elseif ( !$ip ) {
			$ip = $this->getRequest()->getIP();
		}
		$blocked = false;
		wfRunHooks( 'UserIsBlockedGlobally', array( &$this, $ip, &$blocked ) );
		$this->mBlockedGlobally = (bool)$blocked;
		return $this->mBlockedGlobally;
	}

	/**
	 * Check if user account is locked
	 *
	 * @return bool True if locked, false otherwise
	 */
	public function isLocked() {
		if ( $this->mLocked !== null ) {
			return $this->mLocked;
		}
		global $wgAuth;
		StubObject::unstub( $wgAuth );
		$authUser = $wgAuth->getUserInstance( $this );
		$this->mLocked = (bool)$authUser->isLocked();
		return $this->mLocked;
	}

	/**
	 * Check if user account is hidden
	 *
	 * @return bool True if hidden, false otherwise
	 */
	public function isHidden() {
		if ( $this->mHideName !== null ) {
			return $this->mHideName;
		}
		$this->getBlockedStatus();
		if ( !$this->mHideName ) {
			global $wgAuth;
			StubObject::unstub( $wgAuth );
			$authUser = $wgAuth->getUserInstance( $this );
			$this->mHideName = (bool)$authUser->isHidden();
		}
		return $this->mHideName;
	}

	/**
	 * Get the user's ID.
	 * @return int The user's ID; 0 if the user is anonymous or nonexistent
	 */
	public function getId() {
		if ( $this->mId === null && $this->mName !== null && User::isIP( $this->mName ) ) {
			// Special case, we know the user is anonymous
			return 0;
		} elseif ( !$this->isItemLoaded( 'id' ) ) {
			// Don't load if this was initialized from an ID
			$this->load();
		}
		return $this->mId;
	}

	/**
	 * Set the user and reload all fields according to a given ID
	 * @param int $v User ID to reload
	 */
	public function setId( $v ) {
		$this->mId = $v;
		$this->clearInstanceCache( 'id' );
	}

	/**
	 * Get the user name, or the IP of an anonymous user
	 * @return string User's name or IP address
	 */
	public function getName() {
		if ( $this->isItemLoaded( 'name', 'only' ) ) {
			// Special case optimisation
			return $this->mName;
		} else {
			$this->load();
			if ( $this->mName === false ) {
				// Clean up IPs
				$this->mName = IP::sanitizeIP( $this->getRequest()->getIP() );
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
	 * @note User::newFromName() has roughly the same function, when the named user
	 * does not exist.
	 * @param string $str New user name to set
	 */
	public function setName( $str ) {
		$this->load();
		$this->mName = $str;
	}

	/**
	 * Get the user's name escaped by underscores.
	 * @return string Username escaped by underscores.
	 */
	public function getTitleKey() {
		return str_replace( ' ', '_', $this->getName() );
	}

	/**
	 * Check if the user has new messages.
	 * @return bool True if the user has new messages
	 */
	public function getNewtalk() {
		$this->load();

		// Load the newtalk status if it is unloaded (mNewtalk=-1)
		if ( $this->mNewtalk === -1 ) {
			$this->mNewtalk = false; # reset talk page status

			// Check memcached separately for anons, who have no
			// entire User object stored in there.
			if ( !$this->mId ) {
				global $wgDisableAnonTalk;
				if ( $wgDisableAnonTalk ) {
					// Anon newtalk disabled by configuration.
					$this->mNewtalk = false;
				} else {
					global $wgMemc;
					$key = wfMemcKey( 'newtalk', 'ip', $this->getName() );
					$newtalk = $wgMemc->get( $key );
					if ( strval( $newtalk ) !== '' ) {
						$this->mNewtalk = (bool)$newtalk;
					} else {
						// Since we are caching this, make sure it is up to date by getting it
						// from the master
						$this->mNewtalk = $this->checkNewtalk( 'user_ip', $this->getName(), true );
						$wgMemc->set( $key, (int)$this->mNewtalk, 1800 );
					}
				}
			} else {
				$this->mNewtalk = $this->checkNewtalk( 'user_id', $this->mId );
			}
		}

		return (bool)$this->mNewtalk;
	}

	/**
	 * Return the data needed to construct links for new talk page message
	 * alerts. If there are new messages, this will return an associative array
	 * with the following data:
	 *     wiki: The database name of the wiki
	 *     link: Root-relative link to the user's talk page
	 *     rev: The last talk page revision that the user has seen or null. This
	 *         is useful for building diff links.
	 * If there are no new messages, it returns an empty array.
	 * @note This function was designed to accomodate multiple talk pages, but
	 * currently only returns a single link and revision.
	 * @return Array
	 */
	public function getNewMessageLinks() {
		$talks = array();
		if ( !wfRunHooks( 'UserRetrieveNewTalks', array( &$this, &$talks ) ) ) {
			return $talks;
		} elseif ( !$this->getNewtalk() ) {
			return array();
		}
		$utp = $this->getTalkPage();
		$dbr = wfGetDB( DB_SLAVE );
		// Get the "last viewed rev" timestamp from the oldest message notification
		$timestamp = $dbr->selectField( 'user_newtalk',
			'MIN(user_last_timestamp)',
			$this->isAnon() ? array( 'user_ip' => $this->getName() ) : array( 'user_id' => $this->getID() ),
			__METHOD__ );
		$rev = $timestamp ? Revision::loadFromTimestamp( $dbr, $utp, $timestamp ) : null;
		return array( array( 'wiki' => wfWikiID(), 'link' => $utp->getLocalURL(), 'rev' => $rev ) );
	}

	/**
	 * Get the revision ID for the last talk page revision viewed by the talk
	 * page owner.
	 * @return int|null Revision ID or null
	 */
	public function getNewMessageRevisionId() {
		$newMessageRevisionId = null;
		$newMessageLinks = $this->getNewMessageLinks();
		if ( $newMessageLinks ) {
			// Note: getNewMessageLinks() never returns more than a single link
			// and it is always for the same wiki, but we double-check here in
			// case that changes some time in the future.
			if ( count( $newMessageLinks ) === 1
				&& $newMessageLinks[0]['wiki'] === wfWikiID()
				&& $newMessageLinks[0]['rev']
			) {
				$newMessageRevision = $newMessageLinks[0]['rev'];
				$newMessageRevisionId = $newMessageRevision->getId();
			}
		}
		return $newMessageRevisionId;
	}

	/**
	 * Internal uncached check for new messages
	 *
	 * @see getNewtalk()
	 * @param string $field 'user_ip' for anonymous users, 'user_id' otherwise
	 * @param string|int $id User's IP address for anonymous users, User ID otherwise
	 * @param bool $fromMaster true to fetch from the master, false for a slave
	 * @return bool True if the user has new messages
	 */
	protected function checkNewtalk( $field, $id, $fromMaster = false ) {
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
	 * @param string $field 'user_ip' for anonymous users, 'user_id' otherwise
	 * @param string|int $id User's IP address for anonymous users, User ID otherwise
	 * @param $curRev Revision new, as yet unseen revision of the user talk page. Ignored if null.
	 * @return bool True if successful, false otherwise
	 */
	protected function updateNewtalk( $field, $id, $curRev = null ) {
		// Get timestamp of the talk page revision prior to the current one
		$prevRev = $curRev ? $curRev->getPrevious() : false;
		$ts = $prevRev ? $prevRev->getTimestamp() : null;
		// Mark the user as having new messages since this revision
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert( 'user_newtalk',
			array( $field => $id, 'user_last_timestamp' => $dbw->timestampOrNull( $ts ) ),
			__METHOD__,
			'IGNORE' );
		if ( $dbw->affectedRows() ) {
			wfDebug( __METHOD__ . ": set on ($field, $id)\n" );
			return true;
		} else {
			wfDebug( __METHOD__ . " already set ($field, $id)\n" );
			return false;
		}
	}

	/**
	 * Clear the new messages flag for the given user
	 * @param string $field 'user_ip' for anonymous users, 'user_id' otherwise
	 * @param string|int $id User's IP address for anonymous users, User ID otherwise
	 * @return bool True if successful, false otherwise
	 */
	protected function deleteNewtalk( $field, $id ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'user_newtalk',
			array( $field => $id ),
			__METHOD__ );
		if ( $dbw->affectedRows() ) {
			wfDebug( __METHOD__ . ": killed on ($field, $id)\n" );
			return true;
		} else {
			wfDebug( __METHOD__ . ": already gone ($field, $id)\n" );
			return false;
		}
	}

	/**
	 * Update the 'You have new messages!' status.
	 * @param bool $val Whether the user has new messages
	 * @param $curRev Revision new, as yet unseen revision of the user talk page. Ignored if null or !$val.
	 */
	public function setNewtalk( $val, $curRev = null ) {
		if ( wfReadOnly() ) {
			return;
		}

		$this->load();
		$this->mNewtalk = $val;

		if ( $this->isAnon() ) {
			$field = 'user_ip';
			$id = $this->getName();
		} else {
			$field = 'user_id';
			$id = $this->getId();
		}
		global $wgMemc;

		if ( $val ) {
			$changed = $this->updateNewtalk( $field, $id, $curRev );
		} else {
			$changed = $this->deleteNewtalk( $field, $id );
		}

		if ( $this->isAnon() ) {
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
	 * @return string Timestamp in TS_MW format
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
		if ( $this->mId ) {
			global $wgMemc;
			$wgMemc->delete( wfMemcKey( 'user', 'id', $this->mId ) );
		}
	}

	/**
	 * Immediately touch the user data cache for this account.
	 * Updates user_touched field, and removes account data from memcached
	 * for reload on the next hit.
	 */
	public function invalidateCache() {
		if ( wfReadOnly() ) {
			return;
		}
		$this->load();
		if ( $this->mId ) {
			$this->mTouched = self::newTouchedTimestamp();

			$dbw = wfGetDB( DB_MASTER );
			$userid = $this->mId;
			$touched = $this->mTouched;
			$method = __METHOD__;
			$dbw->onTransactionIdle( function() use ( $dbw, $userid, $touched, $method ) {
				// Prevent contention slams by checking user_touched first
				$encTouched = $dbw->addQuotes( $dbw->timestamp( $touched ) );
				$needsPurge = $dbw->selectField( 'user', '1',
					array( 'user_id' => $userid, 'user_touched < ' . $encTouched ) );
				if ( $needsPurge ) {
					$dbw->update( 'user',
						array( 'user_touched' => $dbw->timestamp( $touched ) ),
						array( 'user_id' => $userid, 'user_touched < ' . $encTouched ),
						$method
					);
				}
			} );
			$this->clearSharedCache();
		}
	}

	/**
	 * Validate the cache for this account.
	 * @param string $timestamp A timestamp in TS_MW format
	 * @return bool
	 */
	public function validateCache( $timestamp ) {
		$this->load();
		return ( $timestamp >= $this->mTouched );
	}

	/**
	 * Get the user touched timestamp
	 * @return string timestamp
	 */
	public function getTouched() {
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
	 * @param string $str New password to set
	 * @throws PasswordError on failure
	 *
	 * @return bool
	 */
	public function setPassword( $str ) {
		global $wgAuth;

		if ( $str !== null ) {
			if ( !$wgAuth->allowPasswordChange() ) {
				throw new PasswordError( wfMessage( 'password-change-forbidden' )->text() );
			}

			if ( !$this->isValidPassword( $str ) ) {
				global $wgMinimalPasswordLength;
				$valid = $this->getPasswordValidity( $str );
				if ( is_array( $valid ) ) {
					$message = array_shift( $valid );
					$params = $valid;
				} else {
					$message = $valid;
					$params = array( $wgMinimalPasswordLength );
				}
				throw new PasswordError( wfMessage( $message, $params )->text() );
			}
		}

		if ( !$wgAuth->setPassword( $this, $str ) ) {
			throw new PasswordError( wfMessage( 'externaldberror' )->text() );
		}

		$this->setInternalPassword( $str );

		return true;
	}

	/**
	 * Set the password and reset the random token unconditionally.
	 *
	 * @param string|null $str New password to set or null to set an invalid
	 *  password hash meaning that the user will not be able to log in
	 *  through the web interface.
	 */
	public function setInternalPassword( $str ) {
		$this->load();
		$this->setToken();

		if ( $str === null ) {
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
	 * @param bool $forceCreation Force the generation of a new token if the user doesn't have one (default=true for backwards compatibility)
	 * @return string Token
	 */
	public function getToken( $forceCreation = true ) {
		$this->load();
		if ( !$this->mToken && $forceCreation ) {
			$this->setToken();
		}
		return $this->mToken;
	}

	/**
	 * Set the random token (used for persistent authentication)
	 * Called from loadDefaults() among other places.
	 *
	 * @param string|bool $token If specified, set the token to this value
	 */
	public function setToken( $token = false ) {
		$this->load();
		if ( !$token ) {
			$this->mToken = MWCryptRand::generateHex( USER_TOKEN_LENGTH );
		} else {
			$this->mToken = $token;
		}
	}

	/**
	 * Set the password for a password reminder or new account email
	 *
	 * @param $str New password to set or null to set an invalid
	 *  password hash meaning that the user will not be able to use it
	 * @param bool $throttle If true, reset the throttle timestamp to the present
	 */
	public function setNewpassword( $str, $throttle = true ) {
		$this->load();

		if ( $str === null ) {
			$this->mNewpassword = '';
			$this->mNewpassTime = null;
		} else {
			$this->mNewpassword = self::crypt( $str );
			if ( $throttle ) {
				$this->mNewpassTime = wfTimestampNow();
			}
		}
	}

	/**
	 * Has password reminder email been sent within the last
	 * $wgPasswordReminderResendTime hours?
	 * @return bool
	 */
	public function isPasswordReminderThrottled() {
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
	 * @return string User's email address
	 */
	public function getEmail() {
		$this->load();
		wfRunHooks( 'UserGetEmail', array( $this, &$this->mEmail ) );
		return $this->mEmail;
	}

	/**
	 * Get the timestamp of the user's e-mail authentication
	 * @return string TS_MW timestamp
	 */
	public function getEmailAuthenticationTimestamp() {
		$this->load();
		wfRunHooks( 'UserGetEmailAuthenticationTimestamp', array( $this, &$this->mEmailAuthenticated ) );
		return $this->mEmailAuthenticated;
	}

	/**
	 * Set the user's e-mail address
	 * @param string $str New e-mail address
	 */
	public function setEmail( $str ) {
		$this->load();
		if ( $str == $this->mEmail ) {
			return;
		}
		$this->mEmail = $str;
		$this->invalidateEmail();
		wfRunHooks( 'UserSetEmail', array( $this, &$this->mEmail ) );
	}

	/**
	 * Set the user's e-mail address and a confirmation mail if needed.
	 *
	 * @since 1.20
	 * @param string $str New e-mail address
	 * @return Status
	 */
	public function setEmailWithConfirmation( $str ) {
		global $wgEnableEmail, $wgEmailAuthentication;

		if ( !$wgEnableEmail ) {
			return Status::newFatal( 'emaildisabled' );
		}

		$oldaddr = $this->getEmail();
		if ( $str === $oldaddr ) {
			return Status::newGood( true );
		}

		$this->setEmail( $str );

		if ( $str !== '' && $wgEmailAuthentication ) {
			// Send a confirmation request to the new address if needed
			$type = $oldaddr != '' ? 'changed' : 'set';
			$result = $this->sendConfirmationMail( $type );
			if ( $result->isGood() ) {
				// Say the the caller that a confirmation mail has been sent
				$result->value = 'eauth';
			}
		} else {
			$result = Status::newGood( true );
		}

		return $result;
	}

	/**
	 * Get the user's real name
	 * @return string User's real name
	 */
	public function getRealName() {
		if ( !$this->isItemLoaded( 'realname' ) ) {
			$this->load();
		}

		return $this->mRealName;
	}

	/**
	 * Set the user's real name
	 * @param string $str New real name
	 */
	public function setRealName( $str ) {
		$this->load();
		$this->mRealName = $str;
	}

	/**
	 * Get the user's current setting for a given option.
	 *
	 * @param string $oname The option to check
	 * @param string $defaultOverride A default value returned if the option does not exist
	 * @param bool $ignoreHidden Whether to ignore the effects of $wgHiddenPrefs
	 * @return string User's current value for the option
	 * @see getBoolOption()
	 * @see getIntOption()
	 */
	public function getOption( $oname, $defaultOverride = null, $ignoreHidden = false ) {
		global $wgHiddenPrefs;
		$this->loadOptions();

		# We want 'disabled' preferences to always behave as the default value for
		# users, even if they have set the option explicitly in their settings (ie they
		# set it, and then it was disabled removing their ability to change it).  But
		# we don't want to erase the preferences in the database in case the preference
		# is re-enabled again.  So don't touch $mOptions, just override the returned value
		if ( !$ignoreHidden && in_array( $oname, $wgHiddenPrefs ) ) {
			return self::getDefaultOption( $oname );
		}

		if ( array_key_exists( $oname, $this->mOptions ) ) {
			return $this->mOptions[$oname];
		} else {
			return $defaultOverride;
		}
	}

	/**
	 * Get all user's options
	 *
	 * @return array
	 */
	public function getOptions() {
		global $wgHiddenPrefs;
		$this->loadOptions();
		$options = $this->mOptions;

		# We want 'disabled' preferences to always behave as the default value for
		# users, even if they have set the option explicitly in their settings (ie they
		# set it, and then it was disabled removing their ability to change it).  But
		# we don't want to erase the preferences in the database in case the preference
		# is re-enabled again.  So don't touch $mOptions, just override the returned value
		foreach ( $wgHiddenPrefs as $pref ) {
			$default = self::getDefaultOption( $pref );
			if ( $default !== null ) {
				$options[$pref] = $default;
			}
		}

		return $options;
	}

	/**
	 * Get the user's current setting for a given option, as a boolean value.
	 *
	 * @param string $oname The option to check
	 * @return bool User's current value for the option
	 * @see getOption()
	 */
	public function getBoolOption( $oname ) {
		return (bool)$this->getOption( $oname );
	}

	/**
	 * Get the user's current setting for a given option, as an integer value.
	 *
	 * @param string $oname The option to check
	 * @param int $defaultOverride A default value returned if the option does not exist
	 * @return int User's current value for the option
	 * @see getOption()
	 */
	public function getIntOption( $oname, $defaultOverride = 0 ) {
		$val = $this->getOption( $oname );
		if ( $val == '' ) {
			$val = $defaultOverride;
		}
		return intval( $val );
	}

	/**
	 * Set the given option for a user.
	 *
	 * @param string $oname The option to set
	 * @param mixed $val New value to set
	 */
	public function setOption( $oname, $val ) {
		$this->loadOptions();

		// Explicitly NULL values should refer to defaults
		if ( is_null( $val ) ) {
			$val = self::getDefaultOption( $oname );
		}

		$this->mOptions[$oname] = $val;
	}

	/**
	 * Get a token stored in the preferences (like the watchlist one),
	 * resetting it if it's empty (and saving changes).
	 *
	 * @param string $oname The option name to retrieve the token from
	 * @return string|bool User's current value for the option, or false if this option is disabled.
	 * @see resetTokenFromOption()
	 * @see getOption()
	 */
	public function getTokenFromOption( $oname ) {
		global $wgHiddenPrefs;
		if ( in_array( $oname, $wgHiddenPrefs ) ) {
			return false;
		}

		$token = $this->getOption( $oname );
		if ( !$token ) {
			$token = $this->resetTokenFromOption( $oname );
			$this->saveSettings();
		}
		return $token;
	}

	/**
	 * Reset a token stored in the preferences (like the watchlist one).
	 * *Does not* save user's preferences (similarly to setOption()).
	 *
	 * @param string $oname The option name to reset the token in
	 * @return string|bool New token value, or false if this option is disabled.
	 * @see getTokenFromOption()
	 * @see setOption()
	 */
	public function resetTokenFromOption( $oname ) {
		global $wgHiddenPrefs;
		if ( in_array( $oname, $wgHiddenPrefs ) ) {
			return false;
		}

		$token = MWCryptRand::generateHex( 40 );
		$this->setOption( $oname, $token );
		return $token;
	}

	/**
	 * Return a list of the types of user options currently returned by
	 * User::getOptionKinds().
	 *
	 * Currently, the option kinds are:
	 * - 'registered' - preferences which are registered in core MediaWiki or
	 *                  by extensions using the UserGetDefaultOptions hook.
	 * - 'registered-multiselect' - as above, using the 'multiselect' type.
	 * - 'registered-checkmatrix' - as above, using the 'checkmatrix' type.
	 * - 'userjs' - preferences with names starting with 'userjs-', intended to
	 *              be used by user scripts.
	 * - 'special' - "preferences" that are not accessible via User::getOptions
	 *               or User::setOptions.
	 * - 'unused' - preferences about which MediaWiki doesn't know anything.
	 *              These are usually legacy options, removed in newer versions.
	 *
	 * The API (and possibly others) use this function to determine the possible
	 * option types for validation purposes, so make sure to update this when a
	 * new option kind is added.
	 *
	 * @see User::getOptionKinds
	 * @return array Option kinds
	 */
	public static function listOptionKinds() {
		return array(
			'registered',
			'registered-multiselect',
			'registered-checkmatrix',
			'userjs',
			'special',
			'unused'
		);
	}

	/**
	 * Return an associative array mapping preferences keys to the kind of a preference they're
	 * used for. Different kinds are handled differently when setting or reading preferences.
	 *
	 * See User::listOptionKinds for the list of valid option types that can be provided.
	 *
	 * @see User::listOptionKinds
	 * @param $context IContextSource
	 * @param array $options assoc. array with options keys to check as keys. Defaults to $this->mOptions.
	 * @return array the key => kind mapping data
	 */
	public function getOptionKinds( IContextSource $context, $options = null ) {
		$this->loadOptions();
		if ( $options === null ) {
			$options = $this->mOptions;
		}

		$prefs = Preferences::getPreferences( $this, $context );
		$mapping = array();

		// Pull out the "special" options, so they don't get converted as
		// multiselect or checkmatrix.
		$specialOptions = array_fill_keys( Preferences::getSaveBlacklist(), true );
		foreach ( $specialOptions as $name => $value ) {
			unset( $prefs[$name] );
		}

		// Multiselect and checkmatrix options are stored in the database with
		// one key per option, each having a boolean value. Extract those keys.
		$multiselectOptions = array();
		foreach ( $prefs as $name => $info ) {
			if ( ( isset( $info['type'] ) && $info['type'] == 'multiselect' ) ||
					( isset( $info['class'] ) && $info['class'] == 'HTMLMultiSelectField' ) ) {
				$opts = HTMLFormField::flattenOptions( $info['options'] );
				$prefix = isset( $info['prefix'] ) ? $info['prefix'] : $name;

				foreach ( $opts as $value ) {
					$multiselectOptions["$prefix$value"] = true;
				}

				unset( $prefs[$name] );
			}
		}
		$checkmatrixOptions = array();
		foreach ( $prefs as $name => $info ) {
			if ( ( isset( $info['type'] ) && $info['type'] == 'checkmatrix' ) ||
					( isset( $info['class'] ) && $info['class'] == 'HTMLCheckMatrix' ) ) {
				$columns = HTMLFormField::flattenOptions( $info['columns'] );
				$rows = HTMLFormField::flattenOptions( $info['rows'] );
				$prefix = isset( $info['prefix'] ) ? $info['prefix'] : $name;

				foreach ( $columns as $column ) {
					foreach ( $rows as $row ) {
						$checkmatrixOptions["$prefix-$column-$row"] = true;
					}
				}

				unset( $prefs[$name] );
			}
		}

		// $value is ignored
		foreach ( $options as $key => $value ) {
			if ( isset( $prefs[$key] ) ) {
				$mapping[$key] = 'registered';
			} elseif ( isset( $multiselectOptions[$key] ) ) {
				$mapping[$key] = 'registered-multiselect';
			} elseif ( isset( $checkmatrixOptions[$key] ) ) {
				$mapping[$key] = 'registered-checkmatrix';
			} elseif ( isset( $specialOptions[$key] ) ) {
				$mapping[$key] = 'special';
			} elseif ( substr( $key, 0, 7 ) === 'userjs-' ) {
				$mapping[$key] = 'userjs';
			} else {
				$mapping[$key] = 'unused';
			}
		}

		return $mapping;
	}

	/**
	 * Reset certain (or all) options to the site defaults
	 *
	 * The optional parameter determines which kinds of preferences will be reset.
	 * Supported values are everything that can be reported by getOptionKinds()
	 * and 'all', which forces a reset of *all* preferences and overrides everything else.
	 *
	 * @param array|string $resetKinds which kinds of preferences to reset. Defaults to
	 *  array( 'registered', 'registered-multiselect', 'registered-checkmatrix', 'unused' )
	 *  for backwards-compatibility.
	 * @param $context IContextSource|null context source used when $resetKinds
	 *  does not contain 'all', passed to getOptionKinds().
	 *  Defaults to RequestContext::getMain() when null.
	 */
	public function resetOptions(
		$resetKinds = array( 'registered', 'registered-multiselect', 'registered-checkmatrix', 'unused' ),
		IContextSource $context = null
	) {
		$this->load();
		$defaultOptions = self::getDefaultOptions();

		if ( !is_array( $resetKinds ) ) {
			$resetKinds = array( $resetKinds );
		}

		if ( in_array( 'all', $resetKinds ) ) {
			$newOptions = $defaultOptions;
		} else {
			if ( $context === null ) {
				$context = RequestContext::getMain();
			}

			$optionKinds = $this->getOptionKinds( $context );
			$resetKinds = array_intersect( $resetKinds, self::listOptionKinds() );
			$newOptions = array();

			// Use default values for the options that should be deleted, and
			// copy old values for the ones that shouldn't.
			foreach ( $this->mOptions as $key => $value ) {
				if ( in_array( $optionKinds[$key], $resetKinds ) ) {
					if ( array_key_exists( $key, $defaultOptions ) ) {
						$newOptions[$key] = $defaultOptions[$key];
					}
				} else {
					$newOptions[$key] = $value;
				}
			}
		}

		$this->mOptions = $newOptions;
		$this->mOptionsLoaded = true;
	}

	/**
	 * Get the user's preferred date format.
	 * @return string User's preferred date format
	 */
	public function getDatePreference() {
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
	 * Determine based on the wiki configuration and the user's options,
	 * whether this user must be over HTTPS no matter what.
	 *
	 * @return bool
	 */
	public function requiresHTTPS() {
		global $wgSecureLogin;
		if ( !$wgSecureLogin ) {
			return false;
		} else {
			$https = $this->getBoolOption( 'prefershttps' );
			wfRunHooks( 'UserRequiresHTTPS', array( $this, &$https ) );
			if ( $https ) {
				$https = wfCanIPUseHTTPS( $this->getRequest()->getIP() );
			}
			return $https;
		}
	}

	/**
	 * Get the user preferred stub threshold
	 *
	 * @return int
	 */
	public function getStubThreshold() {
		global $wgMaxArticleSize; # Maximum article size, in Kb
		$threshold = $this->getIntOption( 'stubthreshold' );
		if ( $threshold > $wgMaxArticleSize * 1024 ) {
			// If they have set an impossible value, disable the preference
			// so we can use the parser cache again.
			$threshold = 0;
		}
		return $threshold;
	}

	/**
	 * Get the permissions this user has.
	 * @return Array of String permission names
	 */
	public function getRights() {
		if ( is_null( $this->mRights ) ) {
			$this->mRights = self::getGroupPermissions( $this->getEffectiveGroups() );
			wfRunHooks( 'UserGetRights', array( $this, &$this->mRights ) );
			// Force reindexation of rights when a hook has unset one of them
			$this->mRights = array_values( array_unique( $this->mRights ) );
		}
		return $this->mRights;
	}

	/**
	 * Get the list of explicit group memberships this user has.
	 * The implicit * and user groups are not included.
	 * @return Array of String internal group names
	 */
	public function getGroups() {
		$this->load();
		$this->loadGroups();
		return $this->mGroups;
	}

	/**
	 * Get the list of implicit group memberships this user has.
	 * This includes all explicit groups, plus 'user' if logged in,
	 * '*' for all accounts, and autopromoted groups
	 * @param bool $recache Whether to avoid the cache
	 * @return Array of String internal group names
	 */
	public function getEffectiveGroups( $recache = false ) {
		if ( $recache || is_null( $this->mEffectiveGroups ) ) {
			wfProfileIn( __METHOD__ );
			$this->mEffectiveGroups = array_unique( array_merge(
				$this->getGroups(), // explicit groups
				$this->getAutomaticGroups( $recache ) // implicit groups
			) );
			// Hook for additional groups
			wfRunHooks( 'UserEffectiveGroups', array( &$this, &$this->mEffectiveGroups ) );
			// Force reindexation of groups when a hook has unset one of them
			$this->mEffectiveGroups = array_values( array_unique( $this->mEffectiveGroups ) );
			wfProfileOut( __METHOD__ );
		}
		return $this->mEffectiveGroups;
	}

	/**
	 * Get the list of implicit group memberships this user has.
	 * This includes 'user' if logged in, '*' for all accounts,
	 * and autopromoted groups
	 * @param bool $recache Whether to avoid the cache
	 * @return Array of String internal group names
	 */
	public function getAutomaticGroups( $recache = false ) {
		if ( $recache || is_null( $this->mImplicitGroups ) ) {
			wfProfileIn( __METHOD__ );
			$this->mImplicitGroups = array( '*' );
			if ( $this->getId() ) {
				$this->mImplicitGroups[] = 'user';

				$this->mImplicitGroups = array_unique( array_merge(
					$this->mImplicitGroups,
					Autopromote::getAutopromoteGroups( $this )
				) );
			}
			if ( $recache ) {
				// Assure data consistency with rights/groups,
				// as getEffectiveGroups() depends on this function
				$this->mEffectiveGroups = null;
			}
			wfProfileOut( __METHOD__ );
		}
		return $this->mImplicitGroups;
	}

	/**
	 * Returns the groups the user has belonged to.
	 *
	 * The user may still belong to the returned groups. Compare with getGroups().
	 *
	 * The function will not return groups the user had belonged to before MW 1.17
	 *
	 * @return array Names of the groups the user has belonged to.
	 */
	public function getFormerGroups() {
		if ( is_null( $this->mFormerGroups ) ) {
			$dbr = wfGetDB( DB_MASTER );
			$res = $dbr->select( 'user_former_groups',
				array( 'ufg_group' ),
				array( 'ufg_user' => $this->mId ),
				__METHOD__ );
			$this->mFormerGroups = array();
			foreach ( $res as $row ) {
				$this->mFormerGroups[] = $row->ufg_group;
			}
		}
		return $this->mFormerGroups;
	}

	/**
	 * Get the user's edit count.
	 * @return int, null for anonymous users
	 */
	public function getEditCount() {
		if ( !$this->getId() ) {
			return null;
		}

		if ( !isset( $this->mEditCount ) ) {
			/* Populate the count, if it has not been populated yet */
			wfProfileIn( __METHOD__ );
			$dbr = wfGetDB( DB_SLAVE );
			// check if the user_editcount field has been initialized
			$count = $dbr->selectField(
				'user', 'user_editcount',
				array( 'user_id' => $this->mId ),
				__METHOD__
			);

			if ( $count === null ) {
				// it has not been initialized. do so.
				$count = $this->initEditCount();
			}
			$this->mEditCount = $count;
			wfProfileOut( __METHOD__ );
		}
		return (int)$this->mEditCount;
	}

	/**
	 * Add the user to the given group.
	 * This takes immediate effect.
	 * @param string $group Name of the group to add
	 */
	public function addGroup( $group ) {
		if ( wfRunHooks( 'UserAddGroup', array( $this, &$group ) ) ) {
			$dbw = wfGetDB( DB_MASTER );
			if ( $this->getId() ) {
				$dbw->insert( 'user_groups',
					array(
						'ug_user' => $this->getID(),
						'ug_group' => $group,
					),
					__METHOD__,
					array( 'IGNORE' ) );
			}
		}
		$this->loadGroups();
		$this->mGroups[] = $group;
		// In case loadGroups was not called before, we now have the right twice.
		// Get rid of the duplicate.
		$this->mGroups = array_unique( $this->mGroups );

		// Refresh the groups caches, and clear the rights cache so it will be
		// refreshed on the next call to $this->getRights().
		$this->getEffectiveGroups( true );
		$this->mRights = null;

		$this->invalidateCache();
	}

	/**
	 * Remove the user from the given group.
	 * This takes immediate effect.
	 * @param string $group Name of the group to remove
	 */
	public function removeGroup( $group ) {
		$this->load();
		if ( wfRunHooks( 'UserRemoveGroup', array( $this, &$group ) ) ) {
			$dbw = wfGetDB( DB_MASTER );
			$dbw->delete( 'user_groups',
				array(
					'ug_user' => $this->getID(),
					'ug_group' => $group,
				), __METHOD__ );
			// Remember that the user was in this group
			$dbw->insert( 'user_former_groups',
				array(
					'ufg_user' => $this->getID(),
					'ufg_group' => $group,
				),
				__METHOD__,
				array( 'IGNORE' ) );
		}
		$this->loadGroups();
		$this->mGroups = array_diff( $this->mGroups, array( $group ) );

		// Refresh the groups caches, and clear the rights cache so it will be
		// refreshed on the next call to $this->getRights().
		$this->getEffectiveGroups( true );
		$this->mRights = null;

		$this->invalidateCache();
	}

	/**
	 * Get whether the user is logged in
	 * @return bool
	 */
	public function isLoggedIn() {
		return $this->getID() != 0;
	}

	/**
	 * Get whether the user is anonymous
	 * @return bool
	 */
	public function isAnon() {
		return !$this->isLoggedIn();
	}

	/**
	 * Check if user is allowed to access a feature / make an action
	 *
	 * @internal param \String $varargs permissions to test
	 * @return boolean: True if user is allowed to perform *any* of the given actions
	 *
	 * @return bool
	 */
	public function isAllowedAny( /*...*/ ) {
		$permissions = func_get_args();
		foreach ( $permissions as $permission ) {
			if ( $this->isAllowed( $permission ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 *
	 * @internal param $varargs string
	 * @return bool True if the user is allowed to perform *all* of the given actions
	 */
	public function isAllowedAll( /*...*/ ) {
		$permissions = func_get_args();
		foreach ( $permissions as $permission ) {
			if ( !$this->isAllowed( $permission ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Internal mechanics of testing a permission
	 * @param string $action
	 * @return bool
	 */
	public function isAllowed( $action = '' ) {
		if ( $action === '' ) {
			return true; // In the spirit of DWIM
		}
		// Patrolling may not be enabled
		if ( $action === 'patrol' || $action === 'autopatrol' ) {
			global $wgUseRCPatrol, $wgUseNPPatrol;
			if ( !$wgUseRCPatrol && !$wgUseNPPatrol ) {
				return false;
			}
		}
		// Use strict parameter to avoid matching numeric 0 accidentally inserted
		// by misconfiguration: 0 == 'foo'
		return in_array( $action, $this->getRights(), true );
	}

	/**
	 * Check whether to enable recent changes patrol features for this user
	 * @return boolean: True or false
	 */
	public function useRCPatrol() {
		global $wgUseRCPatrol;
		return $wgUseRCPatrol && $this->isAllowedAny( 'patrol', 'patrolmarks' );
	}

	/**
	 * Check whether to enable new pages patrol features for this user
	 * @return bool True or false
	 */
	public function useNPPatrol() {
		global $wgUseRCPatrol, $wgUseNPPatrol;
		return (
			( $wgUseRCPatrol || $wgUseNPPatrol )
				&& ( $this->isAllowedAny( 'patrol', 'patrolmarks' ) )
		);
	}

	/**
	 * Get the WebRequest object to use with this object
	 *
	 * @return WebRequest
	 */
	public function getRequest() {
		if ( $this->mRequest ) {
			return $this->mRequest;
		} else {
			global $wgRequest;
			return $wgRequest;
		}
	}

	/**
	 * Get the current skin, loading it if required
	 * @return Skin The current skin
	 * @todo FIXME: Need to check the old failback system [AV]
	 * @deprecated since 1.18 Use ->getSkin() in the most relevant outputting context you have
	 */
	public function getSkin() {
		wfDeprecated( __METHOD__, '1.18' );
		return RequestContext::getMain()->getSkin();
	}

	/**
	 * Get a WatchedItem for this user and $title.
	 *
	 * @since 1.22 $checkRights parameter added
	 * @param $title Title
	 * @param $checkRights int Whether to check 'viewmywatchlist'/'editmywatchlist' rights.
	 *     Pass WatchedItem::CHECK_USER_RIGHTS or WatchedItem::IGNORE_USER_RIGHTS.
	 * @return WatchedItem
	 */
	public function getWatchedItem( $title, $checkRights = WatchedItem::CHECK_USER_RIGHTS ) {
		$key = $checkRights . ':' . $title->getNamespace() . ':' . $title->getDBkey();

		if ( isset( $this->mWatchedItems[$key] ) ) {
			return $this->mWatchedItems[$key];
		}

		if ( count( $this->mWatchedItems ) >= self::MAX_WATCHED_ITEMS_CACHE ) {
			$this->mWatchedItems = array();
		}

		$this->mWatchedItems[$key] = WatchedItem::fromUserTitle( $this, $title, $checkRights );
		return $this->mWatchedItems[$key];
	}

	/**
	 * Check the watched status of an article.
	 * @since 1.22 $checkRights parameter added
	 * @param $title Title of the article to look at
	 * @param $checkRights int Whether to check 'viewmywatchlist'/'editmywatchlist' rights.
	 *     Pass WatchedItem::CHECK_USER_RIGHTS or WatchedItem::IGNORE_USER_RIGHTS.
	 * @return bool
	 */
	public function isWatched( $title, $checkRights = WatchedItem::CHECK_USER_RIGHTS ) {
		return $this->getWatchedItem( $title, $checkRights )->isWatched();
	}

	/**
	 * Watch an article.
	 * @since 1.22 $checkRights parameter added
	 * @param $title Title of the article to look at
	 * @param $checkRights int Whether to check 'viewmywatchlist'/'editmywatchlist' rights.
	 *     Pass WatchedItem::CHECK_USER_RIGHTS or WatchedItem::IGNORE_USER_RIGHTS.
	 */
	public function addWatch( $title, $checkRights = WatchedItem::CHECK_USER_RIGHTS ) {
		$this->getWatchedItem( $title, $checkRights )->addWatch();
		$this->invalidateCache();
	}

	/**
	 * Stop watching an article.
	 * @since 1.22 $checkRights parameter added
	 * @param $title Title of the article to look at
	 * @param $checkRights int Whether to check 'viewmywatchlist'/'editmywatchlist' rights.
	 *     Pass WatchedItem::CHECK_USER_RIGHTS or WatchedItem::IGNORE_USER_RIGHTS.
	 */
	public function removeWatch( $title, $checkRights = WatchedItem::CHECK_USER_RIGHTS ) {
		$this->getWatchedItem( $title, $checkRights )->removeWatch();
		$this->invalidateCache();
	}

	/**
	 * Clear the user's notification timestamp for the given title.
	 * If e-notif e-mails are on, they will receive notification mails on
	 * the next change of the page if it's watched etc.
	 * @note If the user doesn't have 'editmywatchlist', this will do nothing.
	 * @param $title Title of the article to look at
	 * @param int $oldid The revision id being viewed. If not given or 0, latest revision is assumed.
	 */
	public function clearNotification( &$title, $oldid = 0 ) {
		global $wgUseEnotif, $wgShowUpdatedMarker;

		// Do nothing if the database is locked to writes
		if ( wfReadOnly() ) {
			return;
		}

		// Do nothing if not allowed to edit the watchlist
		if ( !$this->isAllowed( 'editmywatchlist' ) ) {
			return;
		}

		// If we're working on user's talk page, we should update the talk page message indicator
		if ( $title->getNamespace() == NS_USER_TALK && $title->getText() == $this->getName() ) {
			if ( !wfRunHooks( 'UserClearNewTalkNotification', array( &$this, $oldid ) ) ) {
				return;
			}

			$nextid = $oldid ? $title->getNextRevisionID( $oldid ) : null;

			if ( !$oldid || !$nextid ) {
				// If we're looking at the latest revision, we should definitely clear it
				$this->setNewtalk( false );
			} else {
				// Otherwise we should update its revision, if it's present
				if ( $this->getNewtalk() ) {
					// Naturally the other one won't clear by itself
					$this->setNewtalk( false );
					$this->setNewtalk( true, Revision::newFromId( $nextid ) );
				}
			}
		}

		if ( !$wgUseEnotif && !$wgShowUpdatedMarker ) {
			return;
		}

		if ( $this->isAnon() ) {
			// Nothing else to do...
			return;
		}

		// Only update the timestamp if the page is being watched.
		// The query to find out if it is watched is cached both in memcached and per-invocation,
		// and when it does have to be executed, it can be on a slave
		// If this is the user's newtalk page, we always update the timestamp
		$force = '';
		if ( $title->getNamespace() == NS_USER_TALK && $title->getText() == $this->getName() ) {
			$force = 'force';
		}

		$this->getWatchedItem( $title )->resetNotificationTimestamp( $force, $oldid );
	}

	/**
	 * Resets all of the given user's page-change notification timestamps.
	 * If e-notif e-mails are on, they will receive notification mails on
	 * the next change of any watched page.
	 * @note If the user doesn't have 'editmywatchlist', this will do nothing.
	 */
	public function clearAllNotifications() {
		if ( wfReadOnly() ) {
			return;
		}

		// Do nothing if not allowed to edit the watchlist
		if ( !$this->isAllowed( 'editmywatchlist' ) ) {
			return;
		}

		global $wgUseEnotif, $wgShowUpdatedMarker;
		if ( !$wgUseEnotif && !$wgShowUpdatedMarker ) {
			$this->setNewtalk( false );
			return;
		}
		$id = $this->getId();
		if ( $id != 0 ) {
			$dbw = wfGetDB( DB_MASTER );
			$dbw->update( 'watchlist',
				array( /* SET */ 'wl_notificationtimestamp' => null ),
				array( /* WHERE */ 'wl_user' => $id ),
				__METHOD__
			);
			// We also need to clear here the "you have new message" notification for the own user_talk page;
			// it's cleared one page view later in WikiPage::doViewUpdates().
		}
	}

	/**
	 * Set a cookie on the user's client. Wrapper for
	 * WebResponse::setCookie
	 * @param string $name Name of the cookie to set
	 * @param string $value Value to set
	 * @param int $exp Expiration time, as a UNIX time value;
	 *                   if 0 or not specified, use the default $wgCookieExpiration
	 * @param bool $secure
	 *  true: Force setting the secure attribute when setting the cookie
	 *  false: Force NOT setting the secure attribute when setting the cookie
	 *  null (default): Use the default ($wgCookieSecure) to set the secure attribute
	 * @param array $params Array of options sent passed to WebResponse::setcookie()
	 */
	protected function setCookie( $name, $value, $exp = 0, $secure = null, $params = array() ) {
		$params['secure'] = $secure;
		$this->getRequest()->response()->setcookie( $name, $value, $exp, $params );
	}

	/**
	 * Clear a cookie on the user's client
	 * @param string $name Name of the cookie to clear
	 * @param bool $secure
	 *  true: Force setting the secure attribute when setting the cookie
	 *  false: Force NOT setting the secure attribute when setting the cookie
	 *  null (default): Use the default ($wgCookieSecure) to set the secure attribute
	 * @param array $params Array of options sent passed to WebResponse::setcookie()
	 */
	protected function clearCookie( $name, $secure = null, $params = array() ) {
		$this->setCookie( $name, '', time() - 86400, $secure, $params );
	}

	/**
	 * Set the default cookies for this session on the user's client.
	 *
	 * @param $request WebRequest object to use; $wgRequest will be used if null
	 *        is passed.
	 * @param bool $secure Whether to force secure/insecure cookies or use default
	 * @param bool $rememberMe Whether to add a Token cookie for elongated sessions
	 */
	public function setCookies( $request = null, $secure = null, $rememberMe = false ) {
		if ( $request === null ) {
			$request = $this->getRequest();
		}

		$this->load();
		if ( 0 == $this->mId ) {
			return;
		}
		if ( !$this->mToken ) {
			// When token is empty or NULL generate a new one and then save it to the database
			// This allows a wiki to re-secure itself after a leak of it's user table or $wgSecretKey
			// Simply by setting every cell in the user_token column to NULL and letting them be
			// regenerated as users log back into the wiki.
			$this->setToken();
			$this->saveSettings();
		}
		$session = array(
			'wsUserID' => $this->mId,
			'wsToken' => $this->mToken,
			'wsUserName' => $this->getName()
		);
		$cookies = array(
			'UserID' => $this->mId,
			'UserName' => $this->getName(),
		);
		if ( $rememberMe ) {
			$cookies['Token'] = $this->mToken;
		} else {
			$cookies['Token'] = false;
		}

		wfRunHooks( 'UserSetCookies', array( $this, &$session, &$cookies ) );

		foreach ( $session as $name => $value ) {
			$request->setSessionData( $name, $value );
		}
		foreach ( $cookies as $name => $value ) {
			if ( $value === false ) {
				$this->clearCookie( $name );
			} else {
				$this->setCookie( $name, $value, 0, $secure );
			}
		}

		/**
		 * If wpStickHTTPS was selected, also set an insecure cookie that
		 * will cause the site to redirect the user to HTTPS, if they access
		 * it over HTTP. Bug 29898. Use an un-prefixed cookie, so it's the same
		 * as the one set by centralauth (bug 53538). Also set it to session, or
		 * standard time setting, based on if rememberme was set.
		 */
		if ( $request->getCheck( 'wpStickHTTPS' ) || $this->requiresHTTPS() ) {
			$this->setCookie(
				'forceHTTPS',
				'true',
				$rememberMe ? 0 : null,
				false,
				array( 'prefix' => '' ) // no prefix
			);
		}
	}

	/**
	 * Log this user out.
	 */
	public function logout() {
		if ( wfRunHooks( 'UserLogout', array( &$this ) ) ) {
			$this->doLogout();
		}
	}

	/**
	 * Clear the user's cookies and session, and reset the instance cache.
	 * @see logout()
	 */
	public function doLogout() {
		$this->clearInstanceCache( 'defaults' );

		$this->getRequest()->setSessionData( 'wsUserID', 0 );

		$this->clearCookie( 'UserID' );
		$this->clearCookie( 'Token' );
		$this->clearCookie( 'forceHTTPS', false, array( 'prefix' => '' ) );

		// Remember when user logged out, to prevent seeing cached pages
		$this->setCookie( 'LoggedOut', time(), time() + 86400 );
	}

	/**
	 * Save this user's settings into the database.
	 * @todo Only rarely do all these fields need to be set!
	 */
	public function saveSettings() {
		global $wgAuth;

		$this->load();
		if ( wfReadOnly() ) {
			return;
		}
		if ( 0 == $this->mId ) {
			return;
		}

		$this->mTouched = self::newTouchedTimestamp();
		if ( !$wgAuth->allowSetLocalPassword() ) {
			$this->mPassword = '';
		}

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
				'user_touched' => $dbw->timestamp( $this->mTouched ),
				'user_token' => strval( $this->mToken ),
				'user_email_token' => $this->mEmailToken,
				'user_email_token_expires' => $dbw->timestampOrNull( $this->mEmailTokenExpires ),
				'user_password_expires' => $dbw->timestampOrNull( $this->mPasswordExpires ),
			), array( /* WHERE */
				'user_id' => $this->mId
			), __METHOD__
		);

		$this->saveOptions();

		wfRunHooks( 'UserSaveSettings', array( $this ) );
		$this->clearSharedCache();
		$this->getUserPage()->invalidateCache();
	}

	/**
	 * If only this user's username is known, and it exists, return the user ID.
	 * @return int
	 */
	public function idForName() {
		$s = trim( $this->getName() );
		if ( $s === '' ) {
			return 0;
		}

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
	 * @param string $name Username to add
	 * @param array $params of Strings Non-default parameters to save to the database as user_* fields:
	 *   - password             The user's password hash. Password logins will be disabled if this is omitted.
	 *   - newpassword          Hash for a temporary password that has been mailed to the user
	 *   - email                The user's email address
	 *   - email_authenticated  The email authentication timestamp
	 *   - real_name            The user's real name
	 *   - options              An associative array of non-default options
	 *   - token                Random authentication token. Do not set.
	 *   - registration         Registration timestamp. Do not set.
	 *
	 * @return User object, or null if the username already exists
	 */
	public static function createNew( $name, $params = array() ) {
		$user = new User;
		$user->load();
		$user->setToken(); // init token
		if ( isset( $params['options'] ) ) {
			$user->mOptions = $params['options'] + (array)$user->mOptions;
			unset( $params['options'] );
		}
		$dbw = wfGetDB( DB_MASTER );
		$seqVal = $dbw->nextSequenceValue( 'user_user_id_seq' );

		$fields = array(
			'user_id' => $seqVal,
			'user_name' => $name,
			'user_password' => $user->mPassword,
			'user_newpassword' => $user->mNewpassword,
			'user_newpass_time' => $dbw->timestampOrNull( $user->mNewpassTime ),
			'user_email' => $user->mEmail,
			'user_email_authenticated' => $dbw->timestampOrNull( $user->mEmailAuthenticated ),
			'user_real_name' => $user->mRealName,
			'user_token' => strval( $user->mToken ),
			'user_registration' => $dbw->timestamp( $user->mRegistration ),
			'user_editcount' => 0,
			'user_touched' => $dbw->timestamp( self::newTouchedTimestamp() ),
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
	 * Add this existing user object to the database. If the user already
	 * exists, a fatal status object is returned, and the user object is
	 * initialised with the data from the database.
	 *
	 * Previously, this function generated a DB error due to a key conflict
	 * if the user already existed. Many extension callers use this function
	 * in code along the lines of:
	 *
	 *   $user = User::newFromName( $name );
	 *   if ( !$user->isLoggedIn() ) {
	 *       $user->addToDatabase();
	 *   }
	 *   // do something with $user...
	 *
	 * However, this was vulnerable to a race condition (bug 16020). By
	 * initialising the user object if the user exists, we aim to support this
	 * calling sequence as far as possible.
	 *
	 * Note that if the user exists, this function will acquire a write lock,
	 * so it is still advisable to make the call conditional on isLoggedIn(),
	 * and to commit the transaction after calling.
	 *
	 * @throws MWException
	 * @return Status
	 */
	public function addToDatabase() {
		$this->load();
		if ( !$this->mToken ) {
			$this->setToken(); // init token
		}

		$this->mTouched = self::newTouchedTimestamp();

		$dbw = wfGetDB( DB_MASTER );
		$inWrite = $dbw->writesOrCallbacksPending();
		$seqVal = $dbw->nextSequenceValue( 'user_user_id_seq' );
		$dbw->insert( 'user',
			array(
				'user_id' => $seqVal,
				'user_name' => $this->mName,
				'user_password' => $this->mPassword,
				'user_newpassword' => $this->mNewpassword,
				'user_newpass_time' => $dbw->timestampOrNull( $this->mNewpassTime ),
				'user_email' => $this->mEmail,
				'user_email_authenticated' => $dbw->timestampOrNull( $this->mEmailAuthenticated ),
				'user_real_name' => $this->mRealName,
				'user_token' => strval( $this->mToken ),
				'user_registration' => $dbw->timestamp( $this->mRegistration ),
				'user_editcount' => 0,
				'user_touched' => $dbw->timestamp( $this->mTouched ),
			), __METHOD__,
			array( 'IGNORE' )
		);
		if ( !$dbw->affectedRows() ) {
			if ( !$inWrite ) {
				// XXX: Get out of REPEATABLE-READ so the SELECT below works.
				// Often this case happens early in views before any writes.
				// This shows up at least with CentralAuth.
				$dbw->commit( __METHOD__, 'flush' );
			}
			$this->mId = $dbw->selectField( 'user', 'user_id',
				array( 'user_name' => $this->mName ), __METHOD__ );
			$loaded = false;
			if ( $this->mId ) {
				if ( $this->loadFromDatabase() ) {
					$loaded = true;
				}
			}
			if ( !$loaded ) {
				throw new MWException( __METHOD__ . ": hit a key conflict attempting " .
					"to insert user '{$this->mName}' row, but it was not present in select!" );
			}
			return Status::newFatal( 'userexists' );
		}
		$this->mId = $dbw->insertId();

		// Clear instance cache other than user table data, which is already accurate
		$this->clearInstanceCache();

		$this->saveOptions();
		return Status::newGood();
	}

	/**
	 * If this user is logged-in and blocked,
	 * block any IP address they've successfully logged in from.
	 * @return bool A block was spread
	 */
	public function spreadAnyEditBlock() {
		if ( $this->isLoggedIn() && $this->isBlocked() ) {
			return $this->spreadBlock();
		}
		return false;
	}

	/**
	 * If this (non-anonymous) user is blocked,
	 * block the IP address they've successfully logged in from.
	 * @return bool A block was spread
	 */
	protected function spreadBlock() {
		wfDebug( __METHOD__ . "()\n" );
		$this->load();
		if ( $this->mId == 0 ) {
			return false;
		}

		$userblock = Block::newFromTarget( $this->getName() );
		if ( !$userblock ) {
			return false;
		}

		return (bool)$userblock->doAutoblock( $this->getRequest()->getIP() );
	}

	/**
	 * Get whether the user is explicitly blocked from account creation.
	 * @return bool|Block
	 */
	public function isBlockedFromCreateAccount() {
		$this->getBlockedStatus();
		if ( $this->mBlock && $this->mBlock->prevents( 'createaccount' ) ) {
			return $this->mBlock;
		}

		# bug 13611: if the IP address the user is trying to create an account from is
		# blocked with createaccount disabled, prevent new account creation there even
		# when the user is logged in
		if ( $this->mBlockedFromCreateAccount === false && !$this->isAllowed( 'ipblock-exempt' ) ) {
			$this->mBlockedFromCreateAccount = Block::newFromTarget( null, $this->getRequest()->getIP() );
		}
		return $this->mBlockedFromCreateAccount instanceof Block && $this->mBlockedFromCreateAccount->prevents( 'createaccount' )
			? $this->mBlockedFromCreateAccount
			: false;
	}

	/**
	 * Get whether the user is blocked from using Special:Emailuser.
	 * @return bool
	 */
	public function isBlockedFromEmailuser() {
		$this->getBlockedStatus();
		return $this->mBlock && $this->mBlock->prevents( 'sendemail' );
	}

	/**
	 * Get whether the user is allowed to create an account.
	 * @return bool
	 */
	public function isAllowedToCreateAccount() {
		return $this->isAllowed( 'createaccount' ) && !$this->isBlockedFromCreateAccount();
	}

	/**
	 * Get this user's personal page title.
	 *
	 * @return Title: User's personal page title
	 */
	public function getUserPage() {
		return Title::makeTitle( NS_USER, $this->getName() );
	}

	/**
	 * Get this user's talk page title.
	 *
	 * @return Title: User's talk page title
	 */
	public function getTalkPage() {
		$title = $this->getUserPage();
		return $title->getTalkPage();
	}

	/**
	 * Determine whether the user is a newbie. Newbies are either
	 * anonymous IPs, or the most recently created accounts.
	 * @return bool
	 */
	public function isNewbie() {
		return !$this->isAllowed( 'autoconfirmed' );
	}

	/**
	 * Check to see if the given clear-text password is one of the accepted passwords
	 * @param string $password user password.
	 * @return boolean: True if the given password is correct, otherwise False.
	 */
	public function checkPassword( $password ) {
		global $wgAuth, $wgLegacyEncoding;
		$this->load();

		// Certain authentication plugins do NOT want to save
		// domain passwords in a mysql database, so we should
		// check this (in case $wgAuth->strict() is false).

		if ( $wgAuth->authenticate( $this->getName(), $password ) ) {
			return true;
		} elseif ( $wgAuth->strict() ) {
			// Auth plugin doesn't allow local authentication
			return false;
		} elseif ( $wgAuth->strictUserAuth( $this->getName() ) ) {
			// Auth plugin doesn't allow local authentication for this user name
			return false;
		}
		if ( self::comparePasswords( $this->mPassword, $password, $this->mId ) ) {
			return true;
		} elseif ( $wgLegacyEncoding ) {
			// Some wikis were converted from ISO 8859-1 to UTF-8, the passwords can't be converted
			// Check for this with iconv
			$cp1252Password = iconv( 'UTF-8', 'WINDOWS-1252//TRANSLIT', $password );
			if ( $cp1252Password != $password
				&& self::comparePasswords( $this->mPassword, $cp1252Password, $this->mId )
			) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check if the given clear-text password matches the temporary password
	 * sent by e-mail for password reset operations.
	 *
	 * @param $plaintext string
	 *
	 * @return boolean: True if matches, false otherwise
	 */
	public function checkTemporaryPassword( $plaintext ) {
		global $wgNewPasswordExpiry;

		$this->load();
		if ( self::comparePasswords( $this->mNewpassword, $plaintext, $this->getId() ) ) {
			if ( is_null( $this->mNewpassTime ) ) {
				return true;
			}
			$expiry = wfTimestamp( TS_UNIX, $this->mNewpassTime ) + $wgNewPasswordExpiry;
			return ( time() < $expiry );
		} else {
			return false;
		}
	}

	/**
	 * Alias for getEditToken.
	 * @deprecated since 1.19, use getEditToken instead.
	 *
	 * @param string|array $salt of Strings Optional function-specific data for hashing
	 * @param $request WebRequest object to use or null to use $wgRequest
	 * @return string The new edit token
	 */
	public function editToken( $salt = '', $request = null ) {
		wfDeprecated( __METHOD__, '1.19' );
		return $this->getEditToken( $salt, $request );
	}

	/**
	 * Initialize (if necessary) and return a session token value
	 * which can be used in edit forms to show that the user's
	 * login credentials aren't being hijacked with a foreign form
	 * submission.
	 *
	 * @since 1.19
	 *
	 * @param string|array $salt of Strings Optional function-specific data for hashing
	 * @param $request WebRequest object to use or null to use $wgRequest
	 * @return string The new edit token
	 */
	public function getEditToken( $salt = '', $request = null ) {
		if ( $request == null ) {
			$request = $this->getRequest();
		}

		if ( $this->isAnon() ) {
			return EDIT_TOKEN_SUFFIX;
		} else {
			$token = $request->getSessionData( 'wsEditToken' );
			if ( $token === null ) {
				$token = MWCryptRand::generateHex( 32 );
				$request->setSessionData( 'wsEditToken', $token );
			}
			if ( is_array( $salt ) ) {
				$salt = implode( '|', $salt );
			}
			return md5( $token . $salt ) . EDIT_TOKEN_SUFFIX;
		}
	}

	/**
	 * Generate a looking random token for various uses.
	 *
	 * @return string The new random token
	 * @deprecated since 1.20: Use MWCryptRand for secure purposes or wfRandomString for pseudo-randomness
	 */
	public static function generateToken() {
		return MWCryptRand::generateHex( 32 );
	}

	/**
	 * Check given value against the token value stored in the session.
	 * A match should confirm that the form was submitted from the
	 * user's own login session, not a form submission from a third-party
	 * site.
	 *
	 * @param string $val Input value to compare
	 * @param string $salt Optional function-specific data for hashing
	 * @param WebRequest $request Object to use or null to use $wgRequest
	 * @return boolean: Whether the token matches
	 */
	public function matchEditToken( $val, $salt = '', $request = null ) {
		$sessionToken = $this->getEditToken( $salt, $request );
		if ( $val != $sessionToken ) {
			wfDebug( "User::matchEditToken: broken session data\n" );
		}
		return $val == $sessionToken;
	}

	/**
	 * Check given value against the token value stored in the session,
	 * ignoring the suffix.
	 *
	 * @param string $val Input value to compare
	 * @param string $salt Optional function-specific data for hashing
	 * @param WebRequest $request object to use or null to use $wgRequest
	 * @return boolean: Whether the token matches
	 */
	public function matchEditTokenNoSuffix( $val, $salt = '', $request = null ) {
		$sessionToken = $this->getEditToken( $salt, $request );
		return substr( $sessionToken, 0, 32 ) == substr( $val, 0, 32 );
	}

	/**
	 * Generate a new e-mail confirmation token and send a confirmation/invalidation
	 * mail to the user's given address.
	 *
	 * @param string $type message to send, either "created", "changed" or "set"
	 * @return Status object
	 */
	public function sendConfirmationMail( $type = 'created' ) {
		global $wgLang;
		$expiration = null; // gets passed-by-ref and defined in next line.
		$token = $this->confirmationToken( $expiration );
		$url = $this->confirmationTokenUrl( $token );
		$invalidateURL = $this->invalidationTokenUrl( $token );
		$this->saveSettings();

		if ( $type == 'created' || $type === false ) {
			$message = 'confirmemail_body';
		} elseif ( $type === true ) {
			$message = 'confirmemail_body_changed';
		} else {
			// Messages: confirmemail_body_changed, confirmemail_body_set
			$message = 'confirmemail_body_' . $type;
		}

		return $this->sendMail( wfMessage( 'confirmemail_subject' )->text(),
			wfMessage( $message,
				$this->getRequest()->getIP(),
				$this->getName(),
				$url,
				$wgLang->timeanddate( $expiration, false ),
				$invalidateURL,
				$wgLang->date( $expiration, false ),
				$wgLang->time( $expiration, false ) )->text() );
	}

	/**
	 * Send an e-mail to this user's account. Does not check for
	 * confirmed status or validity.
	 *
	 * @param string $subject Message subject
	 * @param string $body Message body
	 * @param string $from Optional From address; if unspecified, default $wgPasswordSender will be used
	 * @param string $replyto Reply-To address
	 * @return Status
	 */
	public function sendMail( $subject, $body, $from = null, $replyto = null ) {
		if ( is_null( $from ) ) {
			global $wgPasswordSender;
			$sender = new MailAddress( $wgPasswordSender,
				wfMessage( 'emailsender' )->inContentLanguage()->text() );
		} else {
			$sender = new MailAddress( $from );
		}

		$to = new MailAddress( $this );
		return UserMailer::send( $to, $sender, $subject, $body, $replyto );
	}

	/**
	 * Generate, store, and return a new e-mail confirmation code.
	 * A hash (unsalted, since it's used as a key) is stored.
	 *
	 * @note Call saveSettings() after calling this function to commit
	 * this change to the database.
	 *
	 * @param &$expiration \mixed Accepts the expiration time
	 * @return string New token
	 */
	protected function confirmationToken( &$expiration ) {
		global $wgUserEmailConfirmationTokenExpiry;
		$now = time();
		$expires = $now + $wgUserEmailConfirmationTokenExpiry;
		$expiration = wfTimestamp( TS_MW, $expires );
		$this->load();
		$token = MWCryptRand::generateHex( 32 );
		$hash = md5( $token );
		$this->mEmailToken = $hash;
		$this->mEmailTokenExpires = $expiration;
		return $token;
	}

	/**
	 * Return a URL the user can use to confirm their email address.
	 * @param string $token Accepts the email confirmation token
	 * @return string New token URL
	 */
	protected function confirmationTokenUrl( $token ) {
		return $this->getTokenUrl( 'ConfirmEmail', $token );
	}

	/**
	 * Return a URL the user can use to invalidate their email address.
	 * @param string $token Accepts the email confirmation token
	 * @return string New token URL
	 */
	protected function invalidationTokenUrl( $token ) {
		return $this->getTokenUrl( 'InvalidateEmail', $token );
	}

	/**
	 * Internal function to format the e-mail validation/invalidation URLs.
	 * This uses a quickie hack to use the
	 * hardcoded English names of the Special: pages, for ASCII safety.
	 *
	 * @note Since these URLs get dropped directly into emails, using the
	 * short English names avoids insanely long URL-encoded links, which
	 * also sometimes can get corrupted in some browsers/mailers
	 * (bug 6957 with Gmail and Internet Explorer).
	 *
	 * @param string $page Special page
	 * @param string $token Token
	 * @return string Formatted URL
	 */
	protected function getTokenUrl( $page, $token ) {
		// Hack to bypass localization of 'Special:'
		$title = Title::makeTitle( NS_MAIN, "Special:$page/$token" );
		return $title->getCanonicalURL();
	}

	/**
	 * Mark the e-mail address confirmed.
	 *
	 * @note Call saveSettings() after calling this function to commit the change.
	 *
	 * @return bool
	 */
	public function confirmEmail() {
		// Check if it's already confirmed, so we don't touch the database
		// and fire the ConfirmEmailComplete hook on redundant confirmations.
		if ( !$this->isEmailConfirmed() ) {
			$this->setEmailAuthenticationTimestamp( wfTimestampNow() );
			wfRunHooks( 'ConfirmEmailComplete', array( $this ) );
		}
		return true;
	}

	/**
	 * Invalidate the user's e-mail confirmation, and unauthenticate the e-mail
	 * address if it was already confirmed.
	 *
	 * @note Call saveSettings() after calling this function to commit the change.
	 * @return bool Returns true
	 */
	public function invalidateEmail() {
		$this->load();
		$this->mEmailToken = null;
		$this->mEmailTokenExpires = null;
		$this->setEmailAuthenticationTimestamp( null );
		wfRunHooks( 'InvalidateEmailComplete', array( $this ) );
		return true;
	}

	/**
	 * Set the e-mail authentication timestamp.
	 * @param string $timestamp TS_MW timestamp
	 */
	public function setEmailAuthenticationTimestamp( $timestamp ) {
		$this->load();
		$this->mEmailAuthenticated = $timestamp;
		wfRunHooks( 'UserSetEmailAuthenticationTimestamp', array( $this, &$this->mEmailAuthenticated ) );
	}

	/**
	 * Is this user allowed to send e-mails within limits of current
	 * site configuration?
	 * @return bool
	 */
	public function canSendEmail() {
		global $wgEnableEmail, $wgEnableUserEmail;
		if ( !$wgEnableEmail || !$wgEnableUserEmail || !$this->isAllowed( 'sendemail' ) ) {
			return false;
		}
		$canSend = $this->isEmailConfirmed();
		wfRunHooks( 'UserCanSendEmail', array( &$this, &$canSend ) );
		return $canSend;
	}

	/**
	 * Is this user allowed to receive e-mails within limits of current
	 * site configuration?
	 * @return bool
	 */
	public function canReceiveEmail() {
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
	 * @return bool
	 */
	public function isEmailConfirmed() {
		global $wgEmailAuthentication;
		$this->load();
		$confirmed = true;
		if ( wfRunHooks( 'EmailConfirmed', array( &$this, &$confirmed ) ) ) {
			if ( $this->isAnon() ) {
				return false;
			}
			if ( !Sanitizer::validateEmail( $this->mEmail ) ) {
				return false;
			}
			if ( $wgEmailAuthentication && !$this->getEmailAuthenticationTimestamp() ) {
				return false;
			}
			return true;
		} else {
			return $confirmed;
		}
	}

	/**
	 * Check whether there is an outstanding request for e-mail confirmation.
	 * @return bool
	 */
	public function isEmailConfirmationPending() {
		global $wgEmailAuthentication;
		return $wgEmailAuthentication &&
			!$this->isEmailConfirmed() &&
			$this->mEmailToken &&
			$this->mEmailTokenExpires > wfTimestamp();
	}

	/**
	 * Get the timestamp of account creation.
	 *
	 * @return string|bool|null Timestamp of account creation, false for
	 *  non-existent/anonymous user accounts, or null if existing account
	 *  but information is not in database.
	 */
	public function getRegistration() {
		if ( $this->isAnon() ) {
			return false;
		}
		$this->load();
		return $this->mRegistration;
	}

	/**
	 * Get the timestamp of the first edit
	 *
	 * @return string|bool Timestamp of first edit, or false for
	 *  non-existent/anonymous user accounts.
	 */
	public function getFirstEditTimestamp() {
		if ( $this->getId() == 0 ) {
			return false; // anons
		}
		$dbr = wfGetDB( DB_SLAVE );
		$time = $dbr->selectField( 'revision', 'rev_timestamp',
			array( 'rev_user' => $this->getId() ),
			__METHOD__,
			array( 'ORDER BY' => 'rev_timestamp ASC' )
		);
		if ( !$time ) {
			return false; // no edits
		}
		return wfTimestamp( TS_MW, $time );
	}

	/**
	 * Get the permissions associated with a given list of groups
	 *
	 * @param array $groups of Strings List of internal group names
	 * @return Array of Strings List of permission key names for given groups combined
	 */
	public static function getGroupPermissions( $groups ) {
		global $wgGroupPermissions, $wgRevokePermissions;
		$rights = array();
		// grant every granted permission first
		foreach ( $groups as $group ) {
			if ( isset( $wgGroupPermissions[$group] ) ) {
				$rights = array_merge( $rights,
					// array_filter removes empty items
					array_keys( array_filter( $wgGroupPermissions[$group] ) ) );
			}
		}
		// now revoke the revoked permissions
		foreach ( $groups as $group ) {
			if ( isset( $wgRevokePermissions[$group] ) ) {
				$rights = array_diff( $rights,
					array_keys( array_filter( $wgRevokePermissions[$group] ) ) );
			}
		}
		return array_unique( $rights );
	}

	/**
	 * Get all the groups who have a given permission
	 *
	 * @param string $role Role to check
	 * @return Array of Strings List of internal group names with the given permission
	 */
	public static function getGroupsWithPermission( $role ) {
		global $wgGroupPermissions;
		$allowedGroups = array();
		foreach ( array_keys( $wgGroupPermissions ) as $group ) {
			if ( self::groupHasPermission( $group, $role ) ) {
				$allowedGroups[] = $group;
			}
		}
		return $allowedGroups;
	}

	/**
	 * Check, if the given group has the given permission
	 *
	 * If you're wanting to check whether all users have a permission, use
	 * User::isEveryoneAllowed() instead. That properly checks if it's revoked
	 * from anyone.
	 *
	 * @since 1.21
	 * @param string $group Group to check
	 * @param string $role Role to check
	 * @return bool
	 */
	public static function groupHasPermission( $group, $role ) {
		global $wgGroupPermissions, $wgRevokePermissions;
		return isset( $wgGroupPermissions[$group][$role] ) && $wgGroupPermissions[$group][$role]
			&& !( isset( $wgRevokePermissions[$group][$role] ) && $wgRevokePermissions[$group][$role] );
	}

	/**
	 * Check if all users have the given permission
	 *
	 * @since 1.22
	 * @param string $right Right to check
	 * @return bool
	 */
	public static function isEveryoneAllowed( $right ) {
		global $wgGroupPermissions, $wgRevokePermissions;
		static $cache = array();

		// Use the cached results, except in unit tests which rely on
		// being able change the permission mid-request
		if ( isset( $cache[$right] ) && !defined( 'MW_PHPUNIT_TEST' ) ) {
			return $cache[$right];
		}

		if ( !isset( $wgGroupPermissions['*'][$right] ) || !$wgGroupPermissions['*'][$right] ) {
			$cache[$right] = false;
			return false;
		}

		// If it's revoked anywhere, then everyone doesn't have it
		foreach ( $wgRevokePermissions as $rights ) {
			if ( isset( $rights[$right] ) && $rights[$right] ) {
				$cache[$right] = false;
				return false;
			}
		}

		// Allow extensions (e.g. OAuth) to say false
		if ( !wfRunHooks( 'UserIsEveryoneAllowed', array( $right ) ) ) {
			$cache[$right] = false;
			return false;
		}

		$cache[$right] = true;
		return true;
	}

	/**
	 * Get the localized descriptive name for a group, if it exists
	 *
	 * @param string $group Internal group name
	 * @return string Localized descriptive group name
	 */
	public static function getGroupName( $group ) {
		$msg = wfMessage( "group-$group" );
		return $msg->isBlank() ? $group : $msg->text();
	}

	/**
	 * Get the localized descriptive name for a member of a group, if it exists
	 *
	 * @param string $group Internal group name
	 * @param string $username Username for gender (since 1.19)
	 * @return string Localized name for group member
	 */
	public static function getGroupMember( $group, $username = '#' ) {
		$msg = wfMessage( "group-$group-member", $username );
		return $msg->isBlank() ? $group : $msg->text();
	}

	/**
	 * Return the set of defined explicit groups.
	 * The implicit groups (by default *, 'user' and 'autoconfirmed')
	 * are not included, as they are defined automatically, not in the database.
	 * @return Array of internal group names
	 */
	public static function getAllGroups() {
		global $wgGroupPermissions, $wgRevokePermissions;
		return array_diff(
			array_merge( array_keys( $wgGroupPermissions ), array_keys( $wgRevokePermissions ) ),
			self::getImplicitGroups()
		);
	}

	/**
	 * Get a list of all available permissions.
	 * @return Array of permission names
	 */
	public static function getAllRights() {
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
	 * @return Array of Strings Array of internal group names
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
	 * @param string $group Internal group name
	 * @return Title|bool Title of the page if it exists, false otherwise
	 */
	public static function getGroupPage( $group ) {
		$msg = wfMessage( 'grouppage-' . $group )->inContentLanguage();
		if ( $msg->exists() ) {
			$title = Title::newFromText( $msg->text() );
			if ( is_object( $title ) ) {
				return $title;
			}
		}
		return false;
	}

	/**
	 * Create a link to the group in HTML, if available;
	 * else return the group name.
	 *
	 * @param string $group Internal name of the group
	 * @param string $text The text of the link
	 * @return string HTML link to the group
	 */
	public static function makeGroupLinkHTML( $group, $text = '' ) {
		if ( $text == '' ) {
			$text = self::getGroupName( $group );
		}
		$title = self::getGroupPage( $group );
		if ( $title ) {
			return Linker::link( $title, htmlspecialchars( $text ) );
		} else {
			return $text;
		}
	}

	/**
	 * Create a link to the group in Wikitext, if available;
	 * else return the group name.
	 *
	 * @param string $group Internal name of the group
	 * @param string $text The text of the link
	 * @return string Wikilink to the group
	 */
	public static function makeGroupLinkWiki( $group, $text = '' ) {
		if ( $text == '' ) {
			$text = self::getGroupName( $group );
		}
		$title = self::getGroupPage( $group );
		if ( $title ) {
			$page = $title->getPrefixedText();
			return "[[$page|$text]]";
		} else {
			return $text;
		}
	}

	/**
	 * Returns an array of the groups that a particular group can add/remove.
	 *
	 * @param string $group the group to check for whether it can add/remove
	 * @return Array array( 'add' => array( addablegroups ),
	 *     'remove' => array( removablegroups ),
	 *     'add-self' => array( addablegroups to self),
	 *     'remove-self' => array( removable groups from self) )
	 */
	public static function changeableByGroup( $group ) {
		global $wgAddGroups, $wgRemoveGroups, $wgGroupsAddToSelf, $wgGroupsRemoveFromSelf;

		$groups = array( 'add' => array(), 'remove' => array(), 'add-self' => array(), 'remove-self' => array() );
		if ( empty( $wgAddGroups[$group] ) ) {
			// Don't add anything to $groups
		} elseif ( $wgAddGroups[$group] === true ) {
			// You get everything
			$groups['add'] = self::getAllGroups();
		} elseif ( is_array( $wgAddGroups[$group] ) ) {
			$groups['add'] = $wgAddGroups[$group];
		}

		// Same thing for remove
		if ( empty( $wgRemoveGroups[$group] ) ) {
		} elseif ( $wgRemoveGroups[$group] === true ) {
			$groups['remove'] = self::getAllGroups();
		} elseif ( is_array( $wgRemoveGroups[$group] ) ) {
			$groups['remove'] = $wgRemoveGroups[$group];
		}

		// Re-map numeric keys of AddToSelf/RemoveFromSelf to the 'user' key for backwards compatibility
		if ( empty( $wgGroupsAddToSelf['user'] ) || $wgGroupsAddToSelf['user'] !== true ) {
			foreach ( $wgGroupsAddToSelf as $key => $value ) {
				if ( is_int( $key ) ) {
					$wgGroupsAddToSelf['user'][] = $value;
				}
			}
		}

		if ( empty( $wgGroupsRemoveFromSelf['user'] ) || $wgGroupsRemoveFromSelf['user'] !== true ) {
			foreach ( $wgGroupsRemoveFromSelf as $key => $value ) {
				if ( is_int( $key ) ) {
					$wgGroupsRemoveFromSelf['user'][] = $value;
				}
			}
		}

		// Now figure out what groups the user can add to him/herself
		if ( empty( $wgGroupsAddToSelf[$group] ) ) {
		} elseif ( $wgGroupsAddToSelf[$group] === true ) {
			// No idea WHY this would be used, but it's there
			$groups['add-self'] = User::getAllGroups();
		} elseif ( is_array( $wgGroupsAddToSelf[$group] ) ) {
			$groups['add-self'] = $wgGroupsAddToSelf[$group];
		}

		if ( empty( $wgGroupsRemoveFromSelf[$group] ) ) {
		} elseif ( $wgGroupsRemoveFromSelf[$group] === true ) {
			$groups['remove-self'] = User::getAllGroups();
		} elseif ( is_array( $wgGroupsRemoveFromSelf[$group] ) ) {
			$groups['remove-self'] = $wgGroupsRemoveFromSelf[$group];
		}

		return $groups;
	}

	/**
	 * Returns an array of groups that this user can add and remove
	 * @return Array array( 'add' => array( addablegroups ),
	 *  'remove' => array( removablegroups ),
	 *  'add-self' => array( addablegroups to self),
	 *  'remove-self' => array( removable groups from self) )
	 */
	public function changeableGroups() {
		if ( $this->isAllowed( 'userrights' ) ) {
			// This group gives the right to modify everything (reverse-
			// compatibility with old "userrights lets you change
			// everything")
			// Using array_merge to make the groups reindexed
			$all = array_merge( User::getAllGroups() );
			return array(
				'add' => $all,
				'remove' => $all,
				'add-self' => array(),
				'remove-self' => array()
			);
		}

		// Okay, it's not so simple, we will have to go through the arrays
		$groups = array(
			'add' => array(),
			'remove' => array(),
			'add-self' => array(),
			'remove-self' => array()
		);
		$addergroups = $this->getEffectiveGroups();

		foreach ( $addergroups as $addergroup ) {
			$groups = array_merge_recursive(
				$groups, $this->changeableByGroup( $addergroup )
			);
			$groups['add'] = array_unique( $groups['add'] );
			$groups['remove'] = array_unique( $groups['remove'] );
			$groups['add-self'] = array_unique( $groups['add-self'] );
			$groups['remove-self'] = array_unique( $groups['remove-self'] );
		}
		return $groups;
	}

	/**
	 * Increment the user's edit-count field.
	 * Will have no effect for anonymous users.
	 */
	public function incEditCount() {
		if ( !$this->isAnon() ) {
			$dbw = wfGetDB( DB_MASTER );
			$dbw->update(
				'user',
				array( 'user_editcount=user_editcount+1' ),
				array( 'user_id' => $this->getId() ),
				__METHOD__
			);

			// Lazy initialization check...
			if ( $dbw->affectedRows() == 0 ) {
				// Now here's a goddamn hack...
				$dbr = wfGetDB( DB_SLAVE );
				if ( $dbr !== $dbw ) {
					// If we actually have a slave server, the count is
					// at least one behind because the current transaction
					// has not been committed and replicated.
					$this->initEditCount( 1 );
				} else {
					// But if DB_SLAVE is selecting the master, then the
					// count we just read includes the revision that was
					// just added in the working transaction.
					$this->initEditCount();
				}
			}
		}
		// edit count in user cache too
		$this->invalidateCache();
	}

	/**
	 * Initialize user_editcount from data out of the revision table
	 *
	 * @param $add Integer Edits to add to the count from the revision table
	 * @return integer Number of edits
	 */
	protected function initEditCount( $add = 0 ) {
		// Pull from a slave to be less cruel to servers
		// Accuracy isn't the point anyway here
		$dbr = wfGetDB( DB_SLAVE );
		$count = (int)$dbr->selectField(
			'revision',
			'COUNT(rev_user)',
			array( 'rev_user' => $this->getId() ),
			__METHOD__
		);
		$count = $count + $add;

		$dbw = wfGetDB( DB_MASTER );
		$dbw->update(
			'user',
			array( 'user_editcount' => $count ),
			array( 'user_id' => $this->getId() ),
			__METHOD__
		);

		return $count;
	}

	/**
	 * Get the description of a given right
	 *
	 * @param string $right Right to query
	 * @return string Localized description of the right
	 */
	public static function getRightDescription( $right ) {
		$key = "right-$right";
		$msg = wfMessage( $key );
		return $msg->isBlank() ? $right : $msg->text();
	}

	/**
	 * Make an old-style password hash
	 *
	 * @param string $password Plain-text password
	 * @param string $userId User ID
	 * @return string Password hash
	 */
	public static function oldCrypt( $password, $userId ) {
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
	 * @param string $password Plain-text password
	 * @param bool|string $salt Optional salt, may be random or the user ID.
	 *  If unspecified or false, will generate one automatically
	 * @return string Password hash
	 */
	public static function crypt( $password, $salt = false ) {
		global $wgPasswordSalt;

		$hash = '';
		if ( !wfRunHooks( 'UserCryptPassword', array( &$password, &$salt, &$wgPasswordSalt, &$hash ) ) ) {
			return $hash;
		}

		if ( $wgPasswordSalt ) {
			if ( $salt === false ) {
				$salt = MWCryptRand::generateHex( 8 );
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
	 * @param string $hash Password hash
	 * @param string $password Plain-text password to compare
	 * @param string|bool $userId User ID for old-style password salt
	 *
	 * @return boolean
	 */
	public static function comparePasswords( $hash, $password, $userId = false ) {
		$type = substr( $hash, 0, 3 );

		$result = false;
		if ( !wfRunHooks( 'UserComparePasswords', array( &$hash, &$password, &$userId, &$result ) ) ) {
			return $result;
		}

		if ( $type == ':A:' ) {
			// Unsalted
			return md5( $password ) === substr( $hash, 3 );
		} elseif ( $type == ':B:' ) {
			// Salted
			list( $salt, $realHash ) = explode( ':', substr( $hash, 3 ), 2 );
			return md5( $salt . '-' . md5( $password ) ) === $realHash;
		} else {
			// Old-style
			return self::oldCrypt( $password, $userId ) === $hash;
		}
	}

	/**
	 * Add a newuser log entry for this user.
	 * Before 1.19 the return value was always true.
	 *
	 * @param string|bool $action account creation type.
	 *   - String, one of the following values:
	 *     - 'create' for an anonymous user creating an account for himself.
	 *       This will force the action's performer to be the created user itself,
	 *       no matter the value of $wgUser
	 *     - 'create2' for a logged in user creating an account for someone else
	 *     - 'byemail' when the created user will receive its password by e-mail
	 *     - 'autocreate' when the user is automatically created (such as by CentralAuth).
	 *   - Boolean means whether the account was created by e-mail (deprecated):
	 *     - true will be converted to 'byemail'
	 *     - false will be converted to 'create' if this object is the same as
	 *       $wgUser and to 'create2' otherwise
	 *
	 * @param string $reason user supplied reason
	 *
	 * @return int|bool True if not $wgNewUserLog; otherwise ID of log item or 0 on failure
	 */
	public function addNewUserLogEntry( $action = false, $reason = '' ) {
		global $wgUser, $wgNewUserLog;
		if ( empty( $wgNewUserLog ) ) {
			return true; // disabled
		}

		if ( $action === true ) {
			$action = 'byemail';
		} elseif ( $action === false ) {
			if ( $this->getName() == $wgUser->getName() ) {
				$action = 'create';
			} else {
				$action = 'create2';
			}
		}

		if ( $action === 'create' || $action === 'autocreate' ) {
			$performer = $this;
		} else {
			$performer = $wgUser;
		}

		$logEntry = new ManualLogEntry( 'newusers', $action );
		$logEntry->setPerformer( $performer );
		$logEntry->setTarget( $this->getUserPage() );
		$logEntry->setComment( $reason );
		$logEntry->setParameters( array(
			'4::userid' => $this->getId(),
		) );
		$logid = $logEntry->insert();

		if ( $action !== 'autocreate' ) {
			$logEntry->publish( $logid );
		}

		return (int)$logid;
	}

	/**
	 * Add an autocreate newuser log entry for this user
	 * Used by things like CentralAuth and perhaps other authplugins.
	 * Consider calling addNewUserLogEntry() directly instead.
	 *
	 * @return bool
	 */
	public function addNewUserLogEntryAutoCreate() {
		$this->addNewUserLogEntry( 'autocreate' );

		return true;
	}

	/**
	 * Load the user options either from cache, the database or an array
	 *
	 * @param array $data Rows for the current user out of the user_properties table
	 */
	protected function loadOptions( $data = null ) {
		global $wgContLang;

		$this->load();

		if ( $this->mOptionsLoaded ) {
			return;
		}

		$this->mOptions = self::getDefaultOptions();

		if ( !$this->getId() ) {
			// For unlogged-in users, load language/variant options from request.
			// There's no need to do it for logged-in users: they can set preferences,
			// and handling of page content is done by $pageLang->getPreferredVariant() and such,
			// so don't override user's choice (especially when the user chooses site default).
			$variant = $wgContLang->getDefaultVariant();
			$this->mOptions['variant'] = $variant;
			$this->mOptions['language'] = $variant;
			$this->mOptionsLoaded = true;
			return;
		}

		// Maybe load from the object
		if ( !is_null( $this->mOptionOverrides ) ) {
			wfDebug( "User: loading options for user " . $this->getId() . " from override cache.\n" );
			foreach ( $this->mOptionOverrides as $key => $value ) {
				$this->mOptions[$key] = $value;
			}
		} else {
			if ( !is_array( $data ) ) {
				wfDebug( "User: loading options for user " . $this->getId() . " from database.\n" );
				// Load from database
				$dbr = wfGetDB( DB_SLAVE );

				$res = $dbr->select(
					'user_properties',
					array( 'up_property', 'up_value' ),
					array( 'up_user' => $this->getId() ),
					__METHOD__
				);

				$this->mOptionOverrides = array();
				$data = array();
				foreach ( $res as $row ) {
					$data[$row->up_property] = $row->up_value;
				}
			}
			foreach ( $data as $property => $value ) {
				$this->mOptionOverrides[$property] = $value;
				$this->mOptions[$property] = $value;
			}
		}

		$this->mOptionsLoaded = true;

		wfRunHooks( 'UserLoadOptions', array( $this, &$this->mOptions ) );
	}

	/**
	 * @todo document
	 */
	protected function saveOptions() {
		$this->loadOptions();

		// Not using getOptions(), to keep hidden preferences in database
		$saveOptions = $this->mOptions;

		// Allow hooks to abort, for instance to save to a global profile.
		// Reset options to default state before saving.
		if ( !wfRunHooks( 'UserSaveOptions', array( $this, &$saveOptions ) ) ) {
			return;
		}

		$userId = $this->getId();
		$insert_rows = array();
		foreach ( $saveOptions as $key => $value ) {
			// Don't bother storing default values
			$defaultOption = self::getDefaultOption( $key );
			if ( ( is_null( $defaultOption ) &&
				!( $value === false || is_null( $value ) ) ) ||
				$value != $defaultOption
			) {
				$insert_rows[] = array(
					'up_user' => $userId,
					'up_property' => $key,
					'up_value' => $value,
				);
			}
		}

		$dbw = wfGetDB( DB_MASTER );
		// Find and delete any prior preference rows...
		$res = $dbw->select( 'user_properties',
			array( 'up_property' ), array( 'up_user' => $userId ), __METHOD__ );
		$priorKeys = array();
		foreach ( $res as $row ) {
			$priorKeys[] = $row->up_property;
		}
		if ( count( $priorKeys ) ) {
			// Do the DELETE by PRIMARY KEY for prior rows.
			// In the past a very large portion of calls to this function are for setting
			// 'rememberpassword' for new accounts (a preference that has since been removed).
			// Doing a blanket per-user DELETE for new accounts with no rows in the table
			// caused gap locks on [max user ID,+infinity) which caused high contention since
			// updates would pile up on each other as they are for higher (newer) user IDs.
			// It might not be necessary these days, but it shouldn't hurt either.
			$dbw->delete( 'user_properties',
				array( 'up_user' => $userId, 'up_property' => $priorKeys ), __METHOD__ );
		}
		// Insert the new preference rows
		$dbw->insert( 'user_properties', $insert_rows, __METHOD__, array( 'IGNORE' ) );
	}

	/**
	 * Provide an array of HTML5 attributes to put on an input element
	 * intended for the user to enter a new password.  This may include
	 * required, title, and/or pattern, depending on $wgMinimalPasswordLength.
	 *
	 * Do *not* use this when asking the user to enter his current password!
	 * Regardless of configuration, users may have invalid passwords for whatever
	 * reason (e.g., they were set before requirements were tightened up).
	 * Only use it when asking for a new password, like on account creation or
	 * ResetPass.
	 *
	 * Obviously, you still need to do server-side checking.
	 *
	 * NOTE: A combination of bugs in various browsers means that this function
	 * actually just returns array() unconditionally at the moment.  May as
	 * well keep it around for when the browser bugs get fixed, though.
	 *
	 * @todo FIXME: This does not belong here; put it in Html or Linker or somewhere
	 *
	 * @return array Array of HTML attributes suitable for feeding to
	 *   Html::element(), directly or indirectly.  (Don't feed to Xml::*()!
	 *   That will get confused by the boolean attribute syntax used.)
	 */
	public static function passwordChangeInputAttribs() {
		global $wgMinimalPasswordLength;

		if ( $wgMinimalPasswordLength == 0 ) {
			return array();
		}

		# Note that the pattern requirement will always be satisfied if the
		# input is empty, so we need required in all cases.
		#
		# @todo FIXME: Bug 23769: This needs to not claim the password is required
		# if e-mail confirmation is being used.  Since HTML5 input validation
		# is b0rked anyway in some browsers, just return nothing.  When it's
		# re-enabled, fix this code to not output required for e-mail
		# registration.
		#$ret = array( 'required' );
		$ret = array();

		# We can't actually do this right now, because Opera 9.6 will print out
		# the entered password visibly in its error message!  When other
		# browsers add support for this attribute, or Opera fixes its support,
		# we can add support with a version check to avoid doing this on Opera
		# versions where it will be a problem.  Reported to Opera as
		# DSK-262266, but they don't have a public bug tracker for us to follow.
		/*
		if ( $wgMinimalPasswordLength > 1 ) {
			$ret['pattern'] = '.{' . intval( $wgMinimalPasswordLength ) . ',}';
			$ret['title'] = wfMessage( 'passwordtooshort' )
				->numParams( $wgMinimalPasswordLength )->text();
		}
		*/

		return $ret;
	}

	/**
	 * Return the list of user fields that should be selected to create
	 * a new user object.
	 * @return array
	 */
	public static function selectFields() {
		return array(
			'user_id',
			'user_name',
			'user_real_name',
			'user_password',
			'user_newpassword',
			'user_newpass_time',
			'user_email',
			'user_touched',
			'user_token',
			'user_email_authenticated',
			'user_email_token',
			'user_email_token_expires',
			'user_password_expires',
			'user_registration',
			'user_editcount',
		);
	}

	/**
	 * Factory function for fatal permission-denied errors
	 *
	 * @since 1.22
	 * @param string $permission User right required
	 * @return Status
	 */
	static function newFatalPermissionDeniedStatus( $permission ) {
		global $wgLang;

		$groups = array_map(
			array( 'User', 'makeGroupLinkWiki' ),
			User::getGroupsWithPermission( $permission )
		);

		if ( $groups ) {
			return Status::newFatal( 'badaccess-groups', $wgLang->commaList( $groups ), count( $groups ) );
		} else {
			return Status::newFatal( 'badaccess-group0' );
		}
	}
}
