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

use MediaWiki\MediaWikiServices;
use MediaWiki\Session\SessionManager;
use MediaWiki\Session\Token;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthenticationRequest;
use Wikimedia\ScopedCallback;

/**
 * String Some punctuation to prevent editing from broken text-mangling proxies.
 * @deprecated since 1.27, use \MediaWiki\Session\Token::SUFFIX
 * @ingroup Constants
 */
define( 'EDIT_TOKEN_SUFFIX', Token::SUFFIX );

/**
 * The User object encapsulates all of the user-specific settings (user_id,
 * name, rights, email address, options, last login time). Client
 * classes use the getXXX() functions to access these fields. These functions
 * do all the work of determining whether the user is logged in,
 * whether the requested option can be satisfied from cookies or
 * whether a database query is needed. Most of the settings needed
 * for rendering normal pages are set in the cookie to minimize use
 * of the database.
 */
class User implements IDBAccessObject {
	/**
	 * @const int Number of characters in user_token field.
	 */
	const TOKEN_LENGTH = 32;

	/**
	 * @const string An invalid value for user_token
	 */
	const INVALID_TOKEN = '*** INVALID ***';

	/**
	 * Global constant made accessible as class constants so that autoloader
	 * magic can be used.
	 * @deprecated since 1.27, use \MediaWiki\Session\Token::SUFFIX
	 */
	const EDIT_TOKEN_SUFFIX = EDIT_TOKEN_SUFFIX;

	/**
	 * @const int Serialized record version.
	 */
	const VERSION = 10;

	/**
	 * Exclude user options that are set to their default value.
	 * @since 1.25
	 */
	const GETOPTIONS_EXCLUDE_DEFAULTS = 1;

	/**
	 * @since 1.27
	 */
	const CHECK_USER_RIGHTS = true;

	/**
	 * @since 1.27
	 */
	const IGNORE_USER_RIGHTS = false;

	/**
	 * Array of Strings List of member variables which are saved to the
	 * shared cache (memcached). Any operation which changes the
	 * corresponding database fields must call a cache-clearing function.
	 * @showinitializer
	 */
	protected static $mCacheVars = [
		// user table
		'mId',
		'mName',
		'mRealName',
		'mEmail',
		'mTouched',
		'mToken',
		'mEmailAuthenticated',
		'mEmailToken',
		'mEmailTokenExpires',
		'mRegistration',
		'mEditCount',
		// user_groups table
		'mGroups',
		// user_properties table
		'mOptionOverrides',
	];

	/**
	 * Array of Strings Core rights.
	 * Each of these should have a corresponding message of the form
	 * "right-$right".
	 * @showinitializer
	 */
	protected static $mCoreRights = [
		'apihighlimits',
		'applychangetags',
		'autoconfirmed',
		'autocreateaccount',
		'autopatrol',
		'bigdelete',
		'block',
		'blockemail',
		'bot',
		'browsearchive',
		'changetags',
		'createaccount',
		'createpage',
		'createtalk',
		'delete',
		'deletechangetags',
		'deletedhistory',
		'deletedtext',
		'deletelogentry',
		'deleterevision',
		'edit',
		'editcontentmodel',
		'editinterface',
		'editprotected',
		'editmyoptions',
		'editmyprivateinfo',
		'editmyusercss',
		'editmyuserjs',
		'editmywatchlist',
		'editsemiprotected',
		'editusercssjs', # deprecated
		'editusercss',
		'edituserjs',
		'hideuser',
		'import',
		'importupload',
		'ipblock-exempt',
		'managechangetags',
		'markbotedits',
		'mergehistory',
		'minoredit',
		'move',
		'movefile',
		'move-categorypages',
		'move-rootuserpages',
		'move-subpages',
		'nominornewtalk',
		'noratelimit',
		'override-export-depth',
		'pagelang',
		'passwordreset',
		'patrol',
		'patrolmarks',
		'protect',
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
		'viewsuppressed',
		'writeapi',
	];

	/**
	 * String Cached results of getAllRights()
	 */
	protected static $mAllRights = false;

	/** Cache variables */
	// @{
	/** @var int */
	public $mId;
	/** @var string */
	public $mName;
	/** @var string */
	public $mRealName;

	/** @var string */
	public $mEmail;
	/** @var string TS_MW timestamp from the DB */
	public $mTouched;
	/** @var string TS_MW timestamp from cache */
	protected $mQuickTouched;
	/** @var string */
	protected $mToken;
	/** @var string */
	public $mEmailAuthenticated;
	/** @var string */
	protected $mEmailToken;
	/** @var string */
	protected $mEmailTokenExpires;
	/** @var string */
	protected $mRegistration;
	/** @var int */
	protected $mEditCount;
	/** @var array */
	public $mGroups;
	/** @var array */
	protected $mOptionOverrides;
	// @}

	/**
	 * Bool Whether the cache variables have been loaded.
	 */
	// @{
	public $mOptionsLoaded;

	/**
	 * Array with already loaded items or true if all items have been loaded.
	 */
	protected $mLoadedItems = [];
	// @}

	/**
	 * String Initialization data source if mLoadedItems!==true. May be one of:
	 *  - 'defaults'   anonymous user initialised from class defaults
	 *  - 'name'       initialise from mName
	 *  - 'id'         initialise from mId
	 *  - 'session'    log in from session if possible
	 *
	 * Use the User::newFrom*() family of functions to set this.
	 */
	public $mFrom;

	/**
	 * Lazy-initialized variables, invalidated with clearInstanceCache
	 */
	protected $mNewtalk;
	/** @var string */
	protected $mDatePreference;
	/** @var string */
	public $mBlockedby;
	/** @var string */
	protected $mHash;
	/** @var array */
	public $mRights;
	/** @var string */
	protected $mBlockreason;
	/** @var array */
	protected $mEffectiveGroups;
	/** @var array */
	protected $mImplicitGroups;
	/** @var array */
	protected $mFormerGroups;
	/** @var Block */
	protected $mGlobalBlock;
	/** @var bool */
	protected $mLocked;
	/** @var bool */
	public $mHideName;
	/** @var array */
	public $mOptions;

	/**
	 * @var WebRequest
	 */
	private $mRequest;

	/** @var Block */
	public $mBlock;

	/** @var bool */
	protected $mAllowUsertalk;

	/** @var Block */
	private $mBlockedFromCreateAccount = false;

	/** @var integer User::READ_* constant bitfield used to load data */
	protected $queryFlagsUsed = self::READ_NORMAL;

	public static $idCacheByName = [];

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
		return (string)$this->getName();
	}

	/**
	 * Test if it's safe to load this User object.
	 *
	 * You should typically check this before using $wgUser or
	 * RequestContext::getUser in a method that might be called before the
	 * system has been fully initialized. If the object is unsafe, you should
	 * use an anonymous user:
	 * \code
	 * $user = $wgUser->isSafeToLoad() ? $wgUser : new User;
	 * \endcode
	 *
	 * @since 1.27
	 * @return bool
	 */
	public function isSafeToLoad() {
		global $wgFullyInitialised;

		// The user is safe to load if:
		// * MW_NO_SESSION is undefined AND $wgFullyInitialised is true (safe to use session data)
		// * mLoadedItems === true (already loaded)
		// * mFrom !== 'session' (sessions not involved at all)

		return ( !defined( 'MW_NO_SESSION' ) && $wgFullyInitialised ) ||
			$this->mLoadedItems === true || $this->mFrom !== 'session';
	}

	/**
	 * Load the user table data for this object from the source given by mFrom.
	 *
	 * @param integer $flags User::READ_* constant bitfield
	 */
	public function load( $flags = self::READ_NORMAL ) {
		global $wgFullyInitialised;

		if ( $this->mLoadedItems === true ) {
			return;
		}

		// Set it now to avoid infinite recursion in accessors
		$oldLoadedItems = $this->mLoadedItems;
		$this->mLoadedItems = true;
		$this->queryFlagsUsed = $flags;

		// If this is called too early, things are likely to break.
		if ( !$wgFullyInitialised && $this->mFrom === 'session' ) {
			\MediaWiki\Logger\LoggerFactory::getInstance( 'session' )
				->warning( 'User::loadFromSession called before the end of Setup.php', [
					'exception' => new Exception( 'User::loadFromSession called before the end of Setup.php' ),
				] );
			$this->loadDefaults();
			$this->mLoadedItems = $oldLoadedItems;
			return;
		}

		switch ( $this->mFrom ) {
			case 'defaults':
				$this->loadDefaults();
				break;
			case 'name':
				// Make sure this thread sees its own changes
				if ( wfGetLB()->hasOrMadeRecentMasterChanges() ) {
					$flags |= self::READ_LATEST;
					$this->queryFlagsUsed = $flags;
				}

				$this->mId = self::idFromName( $this->mName, $flags );
				if ( !$this->mId ) {
					// Nonexistent user placeholder object
					$this->loadDefaults( $this->mName );
				} else {
					$this->loadFromId( $flags );
				}
				break;
			case 'id':
				$this->loadFromId( $flags );
				break;
			case 'session':
				if ( !$this->loadFromSession() ) {
					// Loading from session failed. Load defaults.
					$this->loadDefaults();
				}
				Hooks::run( 'UserLoadAfterLoadFromSession', [ $this ] );
				break;
			default:
				throw new UnexpectedValueException(
					"Unrecognised value for User->mFrom: \"{$this->mFrom}\"" );
		}
	}

	/**
	 * Load user table data, given mId has already been set.
	 * @param integer $flags User::READ_* constant bitfield
	 * @return bool False if the ID does not exist, true otherwise
	 */
	public function loadFromId( $flags = self::READ_NORMAL ) {
		if ( $this->mId == 0 ) {
			// Anonymous users are not in the database (don't need cache)
			$this->loadDefaults();
			return false;
		}

		// Try cache (unless this needs data from the master DB).
		// NOTE: if this thread called saveSettings(), the cache was cleared.
		$latest = DBAccessObjectUtils::hasFlags( $flags, self::READ_LATEST );
		if ( $latest ) {
			if ( !$this->loadFromDatabase( $flags ) ) {
				// Can't load from ID
				return false;
			}
		} else {
			$this->loadFromCache();
		}

		$this->mLoadedItems = true;
		$this->queryFlagsUsed = $flags;

		return true;
	}

	/**
	 * @since 1.27
	 * @param string $wikiId
	 * @param integer $userId
	 */
	public static function purge( $wikiId, $userId ) {
		$cache = ObjectCache::getMainWANInstance();
		$key = $cache->makeGlobalKey( 'user', 'id', $wikiId, $userId );
		$cache->delete( $key );
	}

	/**
	 * @since 1.27
	 * @param WANObjectCache $cache
	 * @return string
	 */
	protected function getCacheKey( WANObjectCache $cache ) {
		return $cache->makeGlobalKey( 'user', 'id', wfWikiID(), $this->mId );
	}

	/**
	 * Load user data from shared cache, given mId has already been set.
	 *
	 * @return bool True
	 * @since 1.25
	 */
	protected function loadFromCache() {
		$cache = ObjectCache::getMainWANInstance();
		$data = $cache->getWithSetCallback(
			$this->getCacheKey( $cache ),
			$cache::TTL_HOUR,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $cache ) {
				$setOpts += Database::getCacheSetOptions( wfGetDB( DB_REPLICA ) );
				wfDebug( "User: cache miss for user {$this->mId}\n" );

				$this->loadFromDatabase( self::READ_NORMAL );
				$this->loadGroups();
				$this->loadOptions();

				$data = [];
				foreach ( self::$mCacheVars as $name ) {
					$data[$name] = $this->$name;
				}

				$ttl = $cache->adaptiveTTL( wfTimestamp( TS_UNIX, $this->mTouched ), $ttl );

				return $data;

			},
			[ 'pcTTL' => $cache::TTL_PROC_LONG, 'version' => self::VERSION ]
		);

		// Restore from cache
		foreach ( self::$mCacheVars as $name ) {
			$this->$name = $data[$name];
		}

		return true;
	}

	/** @name newFrom*() static factory methods */
	// @{

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
	 * @param int $flags User::READ_* bitfield
	 * @return User|null
	 */
	public static function newFromConfirmationCode( $code, $flags = 0 ) {
		$db = ( $flags & self::READ_LATEST ) == self::READ_LATEST
			? wfGetDB( DB_MASTER )
			: wfGetDB( DB_REPLICA );

		$id = $db->selectField(
			'user',
			'user_id',
			[
				'user_email_token' => md5( $code ),
				'user_email_token_expires > ' . $db->addQuotes( $db->timestamp() ),
			]
		);

		return $id ? User::newFromId( $id ) : null;
	}

	/**
	 * Create a new user object using data from session. If the login
	 * credentials are invalid, the result is an anonymous user.
	 *
	 * @param WebRequest|null $request Object to use; $wgRequest will be used if omitted.
	 * @return User
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
	 * - all other fields (email, etc.)
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

	/**
	 * Static factory method for creation of a "system" user from username.
	 *
	 * A "system" user is an account that's used to attribute logged actions
	 * taken by MediaWiki itself, as opposed to a bot or human user. Examples
	 * might include the 'Maintenance script' or 'Conversion script' accounts
	 * used by various scripts in the maintenance/ directory or accounts such
	 * as 'MediaWiki message delivery' used by the MassMessage extension.
	 *
	 * This can optionally create the user if it doesn't exist, and "steal" the
	 * account if it does exist.
	 *
	 * "Stealing" an existing user is intended to make it impossible for normal
	 * authentication processes to use the account, effectively disabling the
	 * account for normal use:
	 * - Email is invalidated, to prevent account recovery by emailing a
	 *   temporary password and to disassociate the account from the existing
	 *   human.
	 * - The token is set to a magic invalid value, to kill existing sessions
	 *   and to prevent $this->setToken() calls from resetting the token to a
	 *   valid value.
	 * - SessionManager is instructed to prevent new sessions for the user, to
	 *   do things like deauthorizing OAuth consumers.
	 * - AuthManager is instructed to revoke access, to invalidate or remove
	 *   passwords and other credentials.
	 *
	 * @param string $name Username
	 * @param array $options Options are:
	 *  - validate: As for User::getCanonicalName(), default 'valid'
	 *  - create: Whether to create the user if it doesn't already exist, default true
	 *  - steal: Whether to "disable" the account for normal use if it already
	 *    exists, default false
	 * @return User|null
	 * @since 1.27
	 */
	public static function newSystemUser( $name, $options = [] ) {
		$options += [
			'validate' => 'valid',
			'create' => true,
			'steal' => false,
		];

		$name = self::getCanonicalName( $name, $options['validate'] );
		if ( $name === false ) {
			return null;
		}

		$fields = self::selectFields();

		$dbw = wfGetDB( DB_MASTER );
		$row = $dbw->selectRow(
			'user',
			$fields,
			[ 'user_name' => $name ],
			__METHOD__
		);
		if ( !$row ) {
			// No user. Create it?
			return $options['create'] ? self::createNew( $name ) : null;
		}
		$user = self::newFromRow( $row );

		// A user is considered to exist as a non-system user if it can
		// authenticate, or has an email set, or has a non-invalid token.
		if ( $user->mEmail || $user->mToken !== self::INVALID_TOKEN ||
			AuthManager::singleton()->userCanAuthenticate( $name )
		) {
			// User exists. Steal it?
			if ( !$options['steal'] ) {
				return null;
			}

			AuthManager::singleton()->revokeAccessForUser( $name );

			$user->invalidateEmail();
			$user->mToken = self::INVALID_TOKEN;
			$user->saveSettings();
			SessionManager::singleton()->preventSessionsForUser( $user->getName() );
		}

		return $user;
	}

	// @}

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
	 * @param integer $flags User::READ_* constant bitfield
	 * @return int|null The corresponding user's ID, or null if user is nonexistent
	 */
	public static function idFromName( $name, $flags = self::READ_NORMAL ) {
		$nt = Title::makeTitleSafe( NS_USER, $name );
		if ( is_null( $nt ) ) {
			// Illegal name
			return null;
		}

		if ( !( $flags & self::READ_LATEST ) && isset( self::$idCacheByName[$name] ) ) {
			return self::$idCacheByName[$name];
		}

		list( $index, $options ) = DBAccessObjectUtils::getDBOptions( $flags );
		$db = wfGetDB( $index );

		$s = $db->selectRow(
			'user',
			[ 'user_id' ],
			[ 'user_name' => $nt->getText() ],
			__METHOD__,
			$options
		);

		if ( $s === false ) {
			$result = null;
		} else {
			$result = $s->user_id;
		}

		self::$idCacheByName[$name] = $result;

		if ( count( self::$idCacheByName ) > 1000 ) {
			self::$idCacheByName = [];
		}

		return $result;
	}

	/**
	 * Reset the cache used in idFromName(). For use in tests.
	 */
	public static function resetIdByNameCache() {
		self::$idCacheByName = [];
	}

	/**
	 * Does the string match an anonymous IP address?
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
		return preg_match( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.(?:xxx|\d{1,3})$/', $name )
			|| IP::isIPv6( $name );
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
			|| $name != $wgContLang->ucfirst( $name )
		) {
			return false;
		}

		// Ensure that the name can't be misresolved as a different title,
		// such as with extra namespace keys at the start.
		$parsed = Title::newFromText( $name );
		if ( is_null( $parsed )
			|| $parsed->getNamespace()
			|| strcmp( $name, $parsed->getPrefixedText() ) ) {
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
			Hooks::run( 'UserGetReservedNames', [ &$reservedUsernames ] );
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
	 * Return the users who are members of the given group(s). In case of multiple groups,
	 * users who are members of at least one of them are returned.
	 *
	 * @param string|array $groups A single group name or an array of group names
	 * @param int $limit Max number of users to return. The actual limit will never exceed 5000
	 *   records; larger values are ignored.
	 * @param int $after ID the user to start after
	 * @return UserArrayFromResult
	 */
	public static function findUsersByGroup( $groups, $limit = 5000, $after = null ) {
		if ( $groups === [] ) {
			return UserArrayFromResult::newFromIDs( [] );
		}

		$groups = array_unique( (array)$groups );
		$limit = min( 5000, $limit );

		$conds = [ 'ug_group' => $groups ];
		if ( $after !== null ) {
			$conds[] = 'ug_user > ' . (int)$after;
		}

		$dbr = wfGetDB( DB_REPLICA );
		$ids = $dbr->selectFieldValues(
			'user_groups',
			'ug_user',
			$conds,
			__METHOD__,
			[
				'DISTINCT' => true,
				'ORDER BY' => 'ug_user',
				'LIMIT' => $limit,
			]
		) ?: [];
		return UserArray::newFromIDs( $ids );
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
	 * @param string $name String to match
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
		// simple boolean wrapper for getPasswordValidity
		return $this->getPasswordValidity( $password ) === true;
	}

	/**
	 * Given unvalidated password input, return error message on failure.
	 *
	 * @param string $password Desired password
	 * @return bool|string|array True on success, string or array of error message on failure
	 */
	public function getPasswordValidity( $password ) {
		$result = $this->checkPasswordValidity( $password );
		if ( $result->isGood() ) {
			return true;
		} else {
			$messages = [];
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
	 * Check if this is a valid password for this user
	 *
	 * Create a Status object based on the password's validity.
	 * The Status should be set to fatal if the user should not
	 * be allowed to log in, and should have any errors that
	 * would block changing the password.
	 *
	 * If the return value of this is not OK, the password
	 * should not be checked. If the return value is not Good,
	 * the password can be checked, but the user should not be
	 * able to set their password to this.
	 *
	 * @param string $password Desired password
	 * @param string $purpose one of 'login', 'create', 'reset'
	 * @return Status
	 * @since 1.23
	 */
	public function checkPasswordValidity( $password, $purpose = 'login' ) {
		global $wgPasswordPolicy;

		$upp = new UserPasswordPolicy(
			$wgPasswordPolicy['policies'],
			$wgPasswordPolicy['checks']
		);

		$status = Status::newGood();
		$result = false; // init $result to false for the internal checks

		if ( !Hooks::run( 'isValidPassword', [ $password, &$result, $this ] ) ) {
			$status->error( $result );
			return $status;
		}

		if ( $result === false ) {
			$status->merge( $upp->checkUserPassword( $this, $password, $purpose ) );
			return $status;
		} elseif ( $result === true ) {
			return $status;
		} else {
			$status->error( $result );
			return $status; // the isValidPassword hook set a string $result and returned true
		}
	}

	/**
	 * Given unvalidated user input, return a canonical username, or false if
	 * the username is invalid.
	 * @param string $name User input
	 * @param string|bool $validate Type of validation to use:
	 *   - false        No validation
	 *   - 'valid'      Valid for batch processes
	 *   - 'usable'     Valid for batch processes and login
	 *   - 'creatable'  Valid for batch processes, login and account creation
	 *
	 * @throws InvalidArgumentException
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

		// Clean up name according to title rules,
		// but only when validation is requested (bug 12654)
		$t = ( $validate !== false ) ?
			Title::newFromText( $name, NS_USER ) : Title::makeTitle( NS_USER, $name );
		// Check for invalid titles
		if ( is_null( $t ) || $t->getNamespace() !== NS_USER || $t->isExternal() ) {
			return false;
		}

		// Reject various classes of invalid names
		$name = AuthManager::callLegacyAuthPlugin(
			'getCanonicalName', [ $t->getText() ], $t->getText()
		);

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
				throw new InvalidArgumentException(
					'Invalid parameter value for $validate in ' . __METHOD__ );
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
	 * @deprecated since 1.27, use PasswordFactory::generateRandomPasswordString()
	 * @return string New random password
	 */
	public static function randomPassword() {
		global $wgMinimalPasswordLength;
		return PasswordFactory::generateRandomPasswordString( $wgMinimalPasswordLength );
	}

	/**
	 * Set cached properties to default.
	 *
	 * @note This no longer clears uncached lazy-initialised properties;
	 *       the constructor does that instead.
	 *
	 * @param string|bool $name
	 */
	public function loadDefaults( $name = false ) {
		$this->mId = 0;
		$this->mName = $name;
		$this->mRealName = '';
		$this->mEmail = '';
		$this->mOptionOverrides = null;
		$this->mOptionsLoaded = false;

		$loggedOut = $this->mRequest && !defined( 'MW_NO_SESSION' )
			? $this->mRequest->getSession()->getLoggedOutTimestamp() : 0;
		if ( $loggedOut !== 0 ) {
			$this->mTouched = wfTimestamp( TS_MW, $loggedOut );
		} else {
			$this->mTouched = '1'; # Allow any pages to be cached
		}

		$this->mToken = null; // Don't run cryptographic functions till we need a token
		$this->mEmailAuthenticated = null;
		$this->mEmailToken = '';
		$this->mEmailTokenExpires = null;
		$this->mRegistration = wfTimestamp( TS_MW );
		$this->mGroups = [];

		Hooks::run( 'UserLoadDefaults', [ $this, $name ] );
	}

	/**
	 * Return whether an item has been loaded.
	 *
	 * @param string $item Item to check. Current possibilities:
	 *   - id
	 *   - name
	 *   - realname
	 * @param string $all 'all' to check if the whole object has been loaded
	 *   or any other string to check if only the item is available (e.g.
	 *   for optimisation)
	 * @return bool
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
	 * Load user data from the session.
	 *
	 * @return bool True if the user is logged in, false otherwise.
	 */
	private function loadFromSession() {
		// Deprecated hook
		$result = null;
		Hooks::run( 'UserLoadFromSession', [ $this, &$result ], '1.27' );
		if ( $result !== null ) {
			return $result;
		}

		// MediaWiki\Session\Session already did the necessary authentication of the user
		// returned here, so just use it if applicable.
		$session = $this->getRequest()->getSession();
		$user = $session->getUser();
		if ( $user->isLoggedIn() ) {
			$this->loadFromUserObject( $user );
			// Other code expects these to be set in the session, so set them.
			$session->set( 'wsUserID', $this->getId() );
			$session->set( 'wsUserName', $this->getName() );
			$session->set( 'wsToken', $this->getToken() );
			return true;
		}

		return false;
	}

	/**
	 * Load user and user_group data from the database.
	 * $this->mId must be set, this is how the user is identified.
	 *
	 * @param integer $flags User::READ_* constant bitfield
	 * @return bool True if the user exists, false if the user is anonymous
	 */
	public function loadFromDatabase( $flags = self::READ_LATEST ) {
		// Paranoia
		$this->mId = intval( $this->mId );

		if ( !$this->mId ) {
			// Anonymous users are not in the database
			$this->loadDefaults();
			return false;
		}

		list( $index, $options ) = DBAccessObjectUtils::getDBOptions( $flags );
		$db = wfGetDB( $index );

		$s = $db->selectRow(
			'user',
			self::selectFields(),
			[ 'user_id' => $this->mId ],
			__METHOD__,
			$options
		);

		$this->queryFlagsUsed = $flags;
		Hooks::run( 'UserLoadFromDatabase', [ $this, &$s ] );

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
	protected function loadFromRow( $row, $data = null ) {
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

		if ( isset( $row->user_id ) && isset( $row->user_name ) ) {
			self::$idCacheByName[$row->user_name] = $row->user_id;
		}

		if ( isset( $row->user_editcount ) ) {
			$this->mEditCount = $row->user_editcount;
		} else {
			$all = false;
		}

		if ( isset( $row->user_touched ) ) {
			$this->mTouched = wfTimestamp( TS_MW, $row->user_touched );
		} else {
			$all = false;
		}

		if ( isset( $row->user_token ) ) {
			// The definition for the column is binary(32), so trim the NULs
			// that appends. The previous definition was char(32), so trim
			// spaces too.
			$this->mToken = rtrim( $row->user_token, " \0" );
			if ( $this->mToken === '' ) {
				$this->mToken = null;
			}
		} else {
			$all = false;
		}

		if ( isset( $row->user_email ) ) {
			$this->mEmail = $row->user_email;
			$this->mEmailAuthenticated = wfTimestampOrNull( TS_MW, $row->user_email_authenticated );
			$this->mEmailToken = $row->user_email_token;
			$this->mEmailTokenExpires = wfTimestampOrNull( TS_MW, $row->user_email_token_expires );
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
	 * @param User $user
	 */
	protected function loadFromUserObject( $user ) {
		$user->load();
		foreach ( self::$mCacheVars as $var ) {
			$this->$var = $user->$var;
		}
	}

	/**
	 * Load the groups from the database if they aren't already loaded.
	 */
	private function loadGroups() {
		if ( is_null( $this->mGroups ) ) {
			$db = ( $this->queryFlagsUsed & self::READ_LATEST )
				? wfGetDB( DB_MASTER )
				: wfGetDB( DB_REPLICA );
			$res = $db->select( 'user_groups',
				[ 'ug_group' ],
				[ 'ug_user' => $this->mId ],
				__METHOD__ );
			$this->mGroups = [];
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
	 * @param string $event Key in $wgAutopromoteOnce (each one has groups/criteria)
	 *
	 * @return array Array of groups the user has been promoted to.
	 *
	 * @see $wgAutopromoteOnce
	 */
	public function addAutopromoteOnceGroups( $event ) {
		global $wgAutopromoteOnceLogInRC;

		if ( wfReadOnly() || !$this->getId() ) {
			return [];
		}

		$toPromote = Autopromote::getAutopromoteOnceGroups( $this, $event );
		if ( !count( $toPromote ) ) {
			return [];
		}

		if ( !$this->checkAndSetTouched() ) {
			return []; // raced out (bug T48834)
		}

		$oldGroups = $this->getGroups(); // previous groups
		foreach ( $toPromote as $group ) {
			$this->addGroup( $group );
		}
		// update groups in external authentication database
		Hooks::run( 'UserGroupsChanged', [ $this, $toPromote, [], false, false ] );
		AuthManager::callLegacyAuthPlugin( 'updateExternalDBGroups', [ $this, $toPromote ] );

		$newGroups = array_merge( $oldGroups, $toPromote ); // all groups

		$logEntry = new ManualLogEntry( 'rights', 'autopromote' );
		$logEntry->setPerformer( $this );
		$logEntry->setTarget( $this->getUserPage() );
		$logEntry->setParameters( [
			'4::oldgroups' => $oldGroups,
			'5::newgroups' => $newGroups,
		] );
		$logid = $logEntry->insert();
		if ( $wgAutopromoteOnceLogInRC ) {
			$logEntry->publish( $logid );
		}

		return $toPromote;
	}

	/**
	 * Builds update conditions. Additional conditions may be added to $conditions to
	 * protected against race conditions using a compare-and-set (CAS) mechanism
	 * based on comparing $this->mTouched with the user_touched field.
	 *
	 * @param Database $db
	 * @param array $conditions WHERE conditions for use with Database::update
	 * @return array WHERE conditions for use with Database::update
	 */
	protected function makeUpdateConditions( Database $db, array $conditions ) {
		if ( $this->mTouched ) {
			// CAS check: only update if the row wasn't changed sicne it was loaded.
			$conditions['user_touched'] = $db->timestamp( $this->mTouched );
		}

		return $conditions;
	}

	/**
	 * Bump user_touched if it didn't change since this object was loaded
	 *
	 * On success, the mTouched field is updated.
	 * The user serialization cache is always cleared.
	 *
	 * @return bool Whether user_touched was actually updated
	 * @since 1.26
	 */
	protected function checkAndSetTouched() {
		$this->load();

		if ( !$this->mId ) {
			return false; // anon
		}

		// Get a new user_touched that is higher than the old one
		$newTouched = $this->newTouchedTimestamp();

		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'user',
			[ 'user_touched' => $dbw->timestamp( $newTouched ) ],
			$this->makeUpdateConditions( $dbw, [
				'user_id' => $this->mId,
			] ),
			__METHOD__
		);
		$success = ( $dbw->affectedRows() > 0 );

		if ( $success ) {
			$this->mTouched = $newTouched;
			$this->clearSharedCache();
		} else {
			// Clears on failure too since that is desired if the cache is stale
			$this->clearSharedCache( 'refresh' );
		}

		return $success;
	}

	/**
	 * Clear various cached data stored in this object. The cache of the user table
	 * data (i.e. self::$mCacheVars) is not cleared unless $reloadFrom is given.
	 *
	 * @param bool|string $reloadFrom Reload user and user_groups table data from a
	 *   given source. May be "name", "id", "defaults", "session", or false for no reload.
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
			$this->mLoadedItems = [];
			$this->mFrom = $reloadFrom;
		}
	}

	/**
	 * Combine the language default options with any site-specific options
	 * and add the default language variants.
	 *
	 * @return array Array of String options
	 */
	public static function getDefaultOptions() {
		global $wgNamespacesToBeSearchedDefault, $wgDefaultUserOptions, $wgContLang, $wgDefaultSkin;

		static $defOpt = null;
		static $defOptLang = null;

		if ( $defOpt !== null && $defOptLang === $wgContLang->getCode() ) {
			// $wgContLang does not change (and should not change) mid-request,
			// but the unit tests change it anyway, and expect this method to
			// return values relevant to the current $wgContLang.
			return $defOpt;
		}

		$defOpt = $wgDefaultUserOptions;
		// Default language setting
		$defOptLang = $wgContLang->getCode();
		$defOpt['language'] = $defOptLang;
		foreach ( LanguageConverter::$languagesWithVariants as $langCode ) {
			$defOpt[$langCode == $wgContLang->getCode() ? 'variant' : "variant-$langCode"] = $langCode;
		}

		// NOTE: don't use SearchEngineConfig::getSearchableNamespaces here,
		// since extensions may change the set of searchable namespaces depending
		// on user groups/permissions.
		foreach ( $wgNamespacesToBeSearchedDefault as $nsnum => $val ) {
			$defOpt['searchNs' . $nsnum] = (boolean)$val;
		}
		$defOpt['skin'] = Skin::normalizeKey( $wgDefaultSkin );

		Hooks::run( 'UserGetDefaultOptions', [ &$defOpt ] );

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
	 * @param bool $bFromSlave Whether to check the replica DB first.
	 *   To improve performance, non-critical checks are done against replica DBs.
	 *   Check when actually saving should be done against master.
	 */
	private function getBlockedStatus( $bFromSlave = true ) {
		global $wgProxyWhitelist, $wgUser, $wgApplyIpBlocksToXff;

		if ( -1 != $this->mBlockedby ) {
			return;
		}

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
		$ip = null;
		if ( !$this->isAllowed( 'ipblock-exempt' ) ) {
			// $wgUser->getName() only works after the end of Setup.php. Until
			// then, assume it's a logged-out user.
			$globalUserName = $wgUser->isSafeToLoad()
				? $wgUser->getName()
				: IP::sanitizeIP( $wgUser->getRequest()->getIP() );
			if ( $this->getName() === $globalUserName ) {
				$ip = $this->getRequest()->getIP();
			}
		}

		// User/IP blocking
		$block = Block::newFromTarget( $this, $ip, !$bFromSlave );

		// Proxy blocking
		if ( !$block instanceof Block && $ip !== null && !in_array( $ip, $wgProxyWhitelist ) ) {
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
			&& !in_array( $ip, $wgProxyWhitelist )
		) {
			$xff = $this->getRequest()->getHeader( 'X-Forwarded-For' );
			$xff = array_map( 'trim', explode( ',', $xff ) );
			$xff = array_diff( $xff, [ $ip ] );
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
		Hooks::run( 'GetBlockedStatus', [ &$this ] );

	}

	/**
	 * Whether the given IP is in a DNS blacklist.
	 *
	 * @param string $ip IP to check
	 * @param bool $checkWhitelist Whether to check the whitelist first
	 * @return bool True if blacklisted.
	 */
	public function isDnsBlacklisted( $ip, $checkWhitelist = false ) {
		global $wgEnableDnsBlacklist, $wgDnsBlacklistUrls, $wgProxyWhitelist;

		if ( !$wgEnableDnsBlacklist ) {
			return false;
		}

		if ( $checkWhitelist && in_array( $ip, $wgProxyWhitelist ) ) {
			return false;
		}

		return $this->inDnsBlacklist( $ip, $wgDnsBlacklistUrls );
	}

	/**
	 * Whether the given IP is in a given DNS blacklist.
	 *
	 * @param string $ip IP to check
	 * @param string|array $bases Array of Strings: URL of the DNS blacklist
	 * @return bool True if blacklisted.
	 */
	public function inDnsBlacklist( $ip, $bases ) {

		$found = false;
		// @todo FIXME: IPv6 ???  (http://bugs.php.net/bug.php?id=33170)
		if ( IP::isIPv4( $ip ) ) {
			// Reverse IP, bug 21255
			$ipReversed = implode( '.', array_reverse( explode( '.', $ip ) ) );

			foreach ( (array)$bases as $base ) {
				// Make hostname
				// If we have an access key, use that too (ProjectHoneypot, etc.)
				$basename = $base;
				if ( is_array( $base ) ) {
					if ( count( $base ) >= 2 ) {
						// Access key is 1, base URL is 0
						$host = "{$base[1]}.$ipReversed.{$base[0]}";
					} else {
						$host = "$ipReversed.{$base[0]}";
					}
					$basename = $base[0];
				} else {
					$host = "$ipReversed.$base";
				}

				// Send query
				$ipList = gethostbynamel( $host );

				if ( $ipList ) {
					wfDebugLog( 'dnsblacklist', "Hostname $host is {$ipList[0]}, it's a proxy says $basename!" );
					$found = true;
					break;
				} else {
					wfDebugLog( 'dnsblacklist', "Requested $host, not found in $basename." );
				}
			}
		}

		return $found;
	}

	/**
	 * Check if an IP address is in the local proxy list
	 *
	 * @param string $ip
	 *
	 * @return bool
	 */
	public static function isLocallyBlockedProxy( $ip ) {
		global $wgProxyList;

		if ( !$wgProxyList ) {
			return false;
		}

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
	 * The method generates both a generic profiling point and a per action one
	 * (suffix being "-$action".
	 *
	 * @note When using a shared cache like memcached, IP-address
	 * last-hit counters will be shared across wikis.
	 *
	 * @param string $action Action to enforce; 'edit' if unspecified
	 * @param int $incrBy Positive amount to increment counter by [defaults to 1]
	 * @return bool True if a rate limiter was tripped
	 */
	public function pingLimiter( $action = 'edit', $incrBy = 1 ) {
		// Call the 'PingLimiter' hook
		$result = false;
		if ( !Hooks::run( 'PingLimiter', [ &$this, $action, &$result, $incrBy ] ) ) {
			return $result;
		}

		global $wgRateLimits;
		if ( !isset( $wgRateLimits[$action] ) ) {
			return false;
		}

		$limits = array_merge(
			[ '&can-bypass' => true ],
			$wgRateLimits[$action]
		);

		// Some groups shouldn't trigger the ping limiter, ever
		if ( $limits['&can-bypass'] && !$this->isPingLimitable() ) {
			return false;
		}

		$keys = [];
		$id = $this->getId();
		$userLimit = false;
		$isNewbie = $this->isNewbie();

		if ( $id == 0 ) {
			// limits for anons
			if ( isset( $limits['anon'] ) ) {
				$keys[wfMemcKey( 'limiter', $action, 'anon' )] = $limits['anon'];
			}
		} else {
			// limits for logged-in users
			if ( isset( $limits['user'] ) ) {
				$userLimit = $limits['user'];
			}
			// limits for newbie logged-in users
			if ( $isNewbie && isset( $limits['newbie'] ) ) {
				$keys[wfMemcKey( 'limiter', $action, 'user', $id )] = $limits['newbie'];
			}
		}

		// limits for anons and for newbie logged-in users
		if ( $isNewbie ) {
			// ip-based limits
			if ( isset( $limits['ip'] ) ) {
				$ip = $this->getRequest()->getIP();
				$keys["mediawiki:limiter:$action:ip:$ip"] = $limits['ip'];
			}
			// subnet-based limits
			if ( isset( $limits['subnet'] ) ) {
				$ip = $this->getRequest()->getIP();
				$subnet = IP::getSubnet( $ip );
				if ( $subnet !== false ) {
					$keys["mediawiki:limiter:$action:subnet:$subnet"] = $limits['subnet'];
				}
			}
		}

		// Check for group-specific permissions
		// If more than one group applies, use the group with the highest limit ratio (max/period)
		foreach ( $this->getGroups() as $group ) {
			if ( isset( $limits[$group] ) ) {
				if ( $userLimit === false
					|| $limits[$group][0] / $limits[$group][1] > $userLimit[0] / $userLimit[1]
				) {
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

		// ip-based limits for all ping-limitable users
		if ( isset( $limits['ip-all'] ) ) {
			$ip = $this->getRequest()->getIP();
			// ignore if user limit is more permissive
			if ( $isNewbie || $userLimit === false
				|| $limits['ip-all'][0] / $limits['ip-all'][1] > $userLimit[0] / $userLimit[1] ) {
				$keys["mediawiki:limiter:$action:ip-all:$ip"] = $limits['ip-all'];
			}
		}

		// subnet-based limits for all ping-limitable users
		if ( isset( $limits['subnet-all'] ) ) {
			$ip = $this->getRequest()->getIP();
			$subnet = IP::getSubnet( $ip );
			if ( $subnet !== false ) {
				// ignore if user limit is more permissive
				if ( $isNewbie || $userLimit === false
					|| $limits['ip-all'][0] / $limits['ip-all'][1]
					> $userLimit[0] / $userLimit[1] ) {
					$keys["mediawiki:limiter:$action:subnet-all:$subnet"] = $limits['subnet-all'];
				}
			}
		}

		$cache = ObjectCache::getLocalClusterInstance();

		$triggered = false;
		foreach ( $keys as $key => $limit ) {
			list( $max, $period ) = $limit;
			$summary = "(limit $max in {$period}s)";
			$count = $cache->get( $key );
			// Already pinged?
			if ( $count ) {
				if ( $count >= $max ) {
					wfDebugLog( 'ratelimit', "User '{$this->getName()}' " .
						"(IP {$this->getRequest()->getIP()}) tripped $key at $count $summary" );
					$triggered = true;
				} else {
					wfDebug( __METHOD__ . ": ok. $key at $count $summary\n" );
				}
			} else {
				wfDebug( __METHOD__ . ": adding record for $key $summary\n" );
				if ( $incrBy > 0 ) {
					$cache->add( $key, 0, intval( $period ) ); // first ping
				}
			}
			if ( $incrBy > 0 ) {
				$cache->incr( $key, $incrBy );
			}
		}

		return $triggered;
	}

	/**
	 * Check if user is blocked
	 *
	 * @param bool $bFromSlave Whether to check the replica DB instead of
	 *   the master. Hacked from false due to horrible probs on site.
	 * @return bool True if blocked, false otherwise
	 */
	public function isBlocked( $bFromSlave = true ) {
		return $this->getBlock( $bFromSlave ) instanceof Block && $this->getBlock()->prevents( 'edit' );
	}

	/**
	 * Get the block affecting the user, or null if the user is not blocked
	 *
	 * @param bool $bFromSlave Whether to check the replica DB instead of the master
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
	 * @param bool $bFromSlave Whether to check the replica DB instead of the master
	 * @return bool
	 */
	public function isBlockedFrom( $title, $bFromSlave = false ) {
		global $wgBlockAllowsUTEdit;

		$blocked = $this->isBlocked( $bFromSlave );
		$allowUsertalk = ( $wgBlockAllowsUTEdit ? $this->mAllowUsertalk : false );
		// If a user's name is suppressed, they cannot make edits anywhere
		if ( !$this->mHideName && $allowUsertalk && $title->getText() === $this->getName()
			&& $title->getNamespace() == NS_USER_TALK ) {
			$blocked = false;
			wfDebug( __METHOD__ . ": self-talk page, ignoring any blocks\n" );
		}

		Hooks::run( 'UserIsBlockedFrom', [ $this, $title, &$blocked, &$allowUsertalk ] );

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
		return $this->getGlobalBlock( $ip ) instanceof Block;
	}

	/**
	 * Check if user is blocked on all wikis.
	 * Do not use for actual edit permission checks!
	 * This is intended for quick UI checks.
	 *
	 * @param string $ip IP address, uses current client if none given
	 * @return Block|null Block object if blocked, null otherwise
	 * @throws FatalError
	 * @throws MWException
	 */
	public function getGlobalBlock( $ip = '' ) {
		if ( $this->mGlobalBlock !== null ) {
			return $this->mGlobalBlock ?: null;
		}
		// User is already an IP?
		if ( IP::isIPAddress( $this->getName() ) ) {
			$ip = $this->getName();
		} elseif ( !$ip ) {
			$ip = $this->getRequest()->getIP();
		}
		$blocked = false;
		$block = null;
		Hooks::run( 'UserIsBlockedGlobally', [ &$this, $ip, &$blocked, &$block ] );

		if ( $blocked && $block === null ) {
			// back-compat: UserIsBlockedGlobally didn't have $block param first
			$block = new Block;
			$block->setTarget( $ip );
		}

		$this->mGlobalBlock = $blocked ? $block : false;
		return $this->mGlobalBlock ?: null;
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
		$authUser = AuthManager::callLegacyAuthPlugin( 'getUserInstance', [ &$this ], null );
		$this->mLocked = $authUser && $authUser->isLocked();
		Hooks::run( 'UserIsLocked', [ $this, &$this->mLocked ] );
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
			$authUser = AuthManager::callLegacyAuthPlugin( 'getUserInstance', [ &$this ], null );
			$this->mHideName = $authUser && $authUser->isHidden();
			Hooks::run( 'UserIsHidden', [ $this, &$this->mHideName ] );
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

		return (int)$this->mId;
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
					$this->mNewtalk = $this->checkNewtalk( 'user_ip', $this->getName() );
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
	 * @return array
	 */
	public function getNewMessageLinks() {
		$talks = [];
		if ( !Hooks::run( 'UserRetrieveNewTalks', [ &$this, &$talks ] ) ) {
			return $talks;
		} elseif ( !$this->getNewtalk() ) {
			return [];
		}
		$utp = $this->getTalkPage();
		$dbr = wfGetDB( DB_REPLICA );
		// Get the "last viewed rev" timestamp from the oldest message notification
		$timestamp = $dbr->selectField( 'user_newtalk',
			'MIN(user_last_timestamp)',
			$this->isAnon() ? [ 'user_ip' => $this->getName() ] : [ 'user_id' => $this->getId() ],
			__METHOD__ );
		$rev = $timestamp ? Revision::loadFromTimestamp( $dbr, $utp, $timestamp ) : null;
		return [ [ 'wiki' => wfWikiID(), 'link' => $utp->getLocalURL(), 'rev' => $rev ] ];
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
				/** @var Revision $newMessageRevision */
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
	 * @return bool True if the user has new messages
	 */
	protected function checkNewtalk( $field, $id ) {
		$dbr = wfGetDB( DB_REPLICA );

		$ok = $dbr->selectField( 'user_newtalk', $field, [ $field => $id ], __METHOD__ );

		return $ok !== false;
	}

	/**
	 * Add or update the new messages flag
	 * @param string $field 'user_ip' for anonymous users, 'user_id' otherwise
	 * @param string|int $id User's IP address for anonymous users, User ID otherwise
	 * @param Revision|null $curRev New, as yet unseen revision of the user talk page. Ignored if null.
	 * @return bool True if successful, false otherwise
	 */
	protected function updateNewtalk( $field, $id, $curRev = null ) {
		// Get timestamp of the talk page revision prior to the current one
		$prevRev = $curRev ? $curRev->getPrevious() : false;
		$ts = $prevRev ? $prevRev->getTimestamp() : null;
		// Mark the user as having new messages since this revision
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert( 'user_newtalk',
			[ $field => $id, 'user_last_timestamp' => $dbw->timestampOrNull( $ts ) ],
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
			[ $field => $id ],
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
	 * @param Revision $curRev New, as yet unseen revision of the user talk
	 *   page. Ignored if null or !$val.
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

		if ( $val ) {
			$changed = $this->updateNewtalk( $field, $id, $curRev );
		} else {
			$changed = $this->deleteNewtalk( $field, $id );
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
	private function newTouchedTimestamp() {
		global $wgClockSkewFudge;

		$time = wfTimestamp( TS_MW, time() + $wgClockSkewFudge );
		if ( $this->mTouched && $time <= $this->mTouched ) {
			$time = wfTimestamp( TS_MW, wfTimestamp( TS_UNIX, $this->mTouched ) + 1 );
		}

		return $time;
	}

	/**
	 * Clear user data from memcached
	 *
	 * Use after applying updates to the database; caller's
	 * responsibility to update user_touched if appropriate.
	 *
	 * Called implicitly from invalidateCache() and saveSettings().
	 *
	 * @param string $mode Use 'refresh' to clear now; otherwise before DB commit
	 */
	public function clearSharedCache( $mode = 'changed' ) {
		if ( !$this->getId() ) {
			return;
		}

		$cache = ObjectCache::getMainWANInstance();
		$key = $this->getCacheKey( $cache );
		if ( $mode === 'refresh' ) {
			$cache->delete( $key, 1 );
		} else {
			wfGetDB( DB_MASTER )->onTransactionPreCommitOrIdle(
				function() use ( $cache, $key ) {
					$cache->delete( $key );
				},
				__METHOD__
			);
		}
	}

	/**
	 * Immediately touch the user data cache for this account
	 *
	 * Calls touch() and removes account data from memcached
	 */
	public function invalidateCache() {
		$this->touch();
		$this->clearSharedCache();
	}

	/**
	 * Update the "touched" timestamp for the user
	 *
	 * This is useful on various login/logout events when making sure that
	 * a browser or proxy that has multiple tenants does not suffer cache
	 * pollution where the new user sees the old users content. The value
	 * of getTouched() is checked when determining 304 vs 200 responses.
	 * Unlike invalidateCache(), this preserves the User object cache and
	 * avoids database writes.
	 *
	 * @since 1.25
	 */
	public function touch() {
		$id = $this->getId();
		if ( $id ) {
			$key = wfMemcKey( 'user-quicktouched', 'id', $id );
			ObjectCache::getMainWANInstance()->touchCheckKey( $key );
			$this->mQuickTouched = null;
		}
	}

	/**
	 * Validate the cache for this account.
	 * @param string $timestamp A timestamp in TS_MW format
	 * @return bool
	 */
	public function validateCache( $timestamp ) {
		return ( $timestamp >= $this->getTouched() );
	}

	/**
	 * Get the user touched timestamp
	 *
	 * Use this value only to validate caches via inequalities
	 * such as in the case of HTTP If-Modified-Since response logic
	 *
	 * @return string TS_MW Timestamp
	 */
	public function getTouched() {
		$this->load();

		if ( $this->mId ) {
			if ( $this->mQuickTouched === null ) {
				$key = wfMemcKey( 'user-quicktouched', 'id', $this->mId );
				$cache = ObjectCache::getMainWANInstance();

				$this->mQuickTouched = wfTimestamp( TS_MW, $cache->getCheckKeyTime( $key ) );
			}

			return max( $this->mTouched, $this->mQuickTouched );
		}

		return $this->mTouched;
	}

	/**
	 * Get the user_touched timestamp field (time of last DB updates)
	 * @return string TS_MW Timestamp
	 * @since 1.26
	 */
	public function getDBTouched() {
		$this->load();

		return $this->mTouched;
	}

	/**
	 * @deprecated Removed in 1.27.
	 * @return Password
	 * @since 1.24
	 */
	public function getPassword() {
		throw new BadMethodCallException( __METHOD__ . ' has been removed in 1.27' );
	}

	/**
	 * @deprecated Removed in 1.27.
	 * @return Password
	 * @since 1.24
	 */
	public function getTemporaryPassword() {
		throw new BadMethodCallException( __METHOD__ . ' has been removed in 1.27' );
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
	 * @deprecated since 1.27, use AuthManager instead
	 * @param string $str New password to set
	 * @throws PasswordError On failure
	 * @return bool
	 */
	public function setPassword( $str ) {
		return $this->setPasswordInternal( $str );
	}

	/**
	 * Set the password and reset the random token unconditionally.
	 *
	 * @deprecated since 1.27, use AuthManager instead
	 * @param string|null $str New password to set or null to set an invalid
	 *  password hash meaning that the user will not be able to log in
	 *  through the web interface.
	 */
	public function setInternalPassword( $str ) {
		$this->setPasswordInternal( $str );
	}

	/**
	 * Actually set the password and such
	 * @since 1.27 cannot set a password for a user not in the database
	 * @param string|null $str New password to set or null to set an invalid
	 *  password hash meaning that the user will not be able to log in
	 *  through the web interface.
	 * @return bool Success
	 */
	private function setPasswordInternal( $str ) {
		$manager = AuthManager::singleton();

		// If the user doesn't exist yet, fail
		if ( !$manager->userExists( $this->getName() ) ) {
			throw new LogicException( 'Cannot set a password for a user that is not in the database.' );
		}

		$status = $this->changeAuthenticationData( [
			'username' => $this->getName(),
			'password' => $str,
			'retype' => $str,
		] );
		if ( !$status->isGood() ) {
			\MediaWiki\Logger\LoggerFactory::getInstance( 'authentication' )
				->info( __METHOD__ . ': Password change rejected: '
					. $status->getWikiText( null, null, 'en' ) );
			return false;
		}

		$this->setOption( 'watchlisttoken', false );
		SessionManager::singleton()->invalidateSessionsForUser( $this );

		return true;
	}

	/**
	 * Changes credentials of the user.
	 *
	 * This is a convenience wrapper around AuthManager::changeAuthenticationData.
	 * Note that this can return a status that isOK() but not isGood() on certain types of failures,
	 * e.g. when no provider handled the change.
	 *
	 * @param array $data A set of authentication data in fieldname => value format. This is the
	 *   same data you would pass the changeauthenticationdata API - 'username', 'password' etc.
	 * @return Status
	 * @since 1.27
	 */
	public function changeAuthenticationData( array $data ) {
		$manager = AuthManager::singleton();
		$reqs = $manager->getAuthenticationRequests( AuthManager::ACTION_CHANGE, $this );
		$reqs = AuthenticationRequest::loadRequestsFromSubmission( $reqs, $data );

		$status = Status::newGood( 'ignored' );
		foreach ( $reqs as $req ) {
			$status->merge( $manager->allowsAuthenticationDataChange( $req ), true );
		}
		if ( $status->getValue() === 'ignored' ) {
			$status->warning( 'authenticationdatachange-ignored' );
		}

		if ( $status->isGood() ) {
			foreach ( $reqs as $req ) {
				$manager->changeAuthenticationData( $req );
			}
		}
		return $status;
	}

	/**
	 * Get the user's current token.
	 * @param bool $forceCreation Force the generation of a new token if the
	 *   user doesn't have one (default=true for backwards compatibility).
	 * @return string|null Token
	 */
	public function getToken( $forceCreation = true ) {
		global $wgAuthenticationTokenVersion;

		$this->load();
		if ( !$this->mToken && $forceCreation ) {
			$this->setToken();
		}

		if ( !$this->mToken ) {
			// The user doesn't have a token, return null to indicate that.
			return null;
		} elseif ( $this->mToken === self::INVALID_TOKEN ) {
			// We return a random value here so existing token checks are very
			// likely to fail.
			return MWCryptRand::generateHex( self::TOKEN_LENGTH );
		} elseif ( $wgAuthenticationTokenVersion === null ) {
			// $wgAuthenticationTokenVersion not in use, so return the raw secret
			return $this->mToken;
		} else {
			// $wgAuthenticationTokenVersion in use, so hmac it.
			$ret = MWCryptHash::hmac( $wgAuthenticationTokenVersion, $this->mToken, false );

			// The raw hash can be overly long. Shorten it up.
			$len = max( 32, self::TOKEN_LENGTH );
			if ( strlen( $ret ) < $len ) {
				// Should never happen, even md5 is 128 bits
				throw new \UnexpectedValueException( 'Hmac returned less than 128 bits' );
			}
			return substr( $ret, -$len );
		}
	}

	/**
	 * Set the random token (used for persistent authentication)
	 * Called from loadDefaults() among other places.
	 *
	 * @param string|bool $token If specified, set the token to this value
	 */
	public function setToken( $token = false ) {
		$this->load();
		if ( $this->mToken === self::INVALID_TOKEN ) {
			\MediaWiki\Logger\LoggerFactory::getInstance( 'session' )
				->debug( __METHOD__ . ": Ignoring attempt to set token for system user \"$this\"" );
		} elseif ( !$token ) {
			$this->mToken = MWCryptRand::generateHex( self::TOKEN_LENGTH );
		} else {
			$this->mToken = $token;
		}
	}

	/**
	 * Set the password for a password reminder or new account email
	 *
	 * @deprecated Removed in 1.27. Use PasswordReset instead.
	 * @param string $str New password to set or null to set an invalid
	 *  password hash meaning that the user will not be able to use it
	 * @param bool $throttle If true, reset the throttle timestamp to the present
	 */
	public function setNewpassword( $str, $throttle = true ) {
		throw new BadMethodCallException( __METHOD__ . ' has been removed in 1.27' );
	}

	/**
	 * Has password reminder email been sent within the last
	 * $wgPasswordReminderResendTime hours?
	 * @deprecated Removed in 1.27. See above.
	 * @return bool
	 */
	public function isPasswordReminderThrottled() {
		throw new BadMethodCallException( __METHOD__ . ' has been removed in 1.27' );
	}

	/**
	 * Get the user's e-mail address
	 * @return string User's email address
	 */
	public function getEmail() {
		$this->load();
		Hooks::run( 'UserGetEmail', [ $this, &$this->mEmail ] );
		return $this->mEmail;
	}

	/**
	 * Get the timestamp of the user's e-mail authentication
	 * @return string TS_MW timestamp
	 */
	public function getEmailAuthenticationTimestamp() {
		$this->load();
		Hooks::run( 'UserGetEmailAuthenticationTimestamp', [ $this, &$this->mEmailAuthenticated ] );
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
		$this->invalidateEmail();
		$this->mEmail = $str;
		Hooks::run( 'UserSetEmail', [ $this, &$this->mEmail ] );
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

		$type = $oldaddr != '' ? 'changed' : 'set';
		$notificationResult = null;

		if ( $wgEmailAuthentication ) {
			// Send the user an email notifying the user of the change in registered
			// email address on their previous email address
			if ( $type == 'changed' ) {
				$change = $str != '' ? 'changed' : 'removed';
				$notificationResult = $this->sendMail(
					wfMessage( 'notificationemail_subject_' . $change )->text(),
					wfMessage( 'notificationemail_body_' . $change,
						$this->getRequest()->getIP(),
						$this->getName(),
						$str )->text()
				);
			}
		}

		$this->setEmail( $str );

		if ( $str !== '' && $wgEmailAuthentication ) {
			// Send a confirmation request to the new address if needed
			$result = $this->sendConfirmationMail( $type );

			if ( $notificationResult !== null ) {
				$result->merge( $notificationResult );
			}

			if ( $result->isGood() ) {
				// Say to the caller that a confirmation and notification mail has been sent
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
	 * @param int $flags Bitwise combination of:
	 *   User::GETOPTIONS_EXCLUDE_DEFAULTS  Exclude user options that are set
	 *                                      to the default value. (Since 1.25)
	 * @return array
	 */
	public function getOptions( $flags = 0 ) {
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

		if ( $flags & self::GETOPTIONS_EXCLUDE_DEFAULTS ) {
			$options = array_diff_assoc( $options, self::getDefaultOptions() );
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
	 * You need to call saveSettings() to actually write to the database.
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
	 * @deprecated since 1.26 Applications should use the OAuth extension
	 */
	public function getTokenFromOption( $oname ) {
		global $wgHiddenPrefs;

		$id = $this->getId();
		if ( !$id || in_array( $oname, $wgHiddenPrefs ) ) {
			return false;
		}

		$token = $this->getOption( $oname );
		if ( !$token ) {
			// Default to a value based on the user token to avoid space
			// wasted on storing tokens for all users. When this option
			// is set manually by the user, only then is it stored.
			$token = hash_hmac( 'sha1', "$oname:$id", $this->getToken() );
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
		return [
			'registered',
			'registered-multiselect',
			'registered-checkmatrix',
			'userjs',
			'special',
			'unused'
		];
	}

	/**
	 * Return an associative array mapping preferences keys to the kind of a preference they're
	 * used for. Different kinds are handled differently when setting or reading preferences.
	 *
	 * See User::listOptionKinds for the list of valid option types that can be provided.
	 *
	 * @see User::listOptionKinds
	 * @param IContextSource $context
	 * @param array $options Assoc. array with options keys to check as keys.
	 *   Defaults to $this->mOptions.
	 * @return array The key => kind mapping data
	 */
	public function getOptionKinds( IContextSource $context, $options = null ) {
		$this->loadOptions();
		if ( $options === null ) {
			$options = $this->mOptions;
		}

		$prefs = Preferences::getPreferences( $this, $context );
		$mapping = [];

		// Pull out the "special" options, so they don't get converted as
		// multiselect or checkmatrix.
		$specialOptions = array_fill_keys( Preferences::getSaveBlacklist(), true );
		foreach ( $specialOptions as $name => $value ) {
			unset( $prefs[$name] );
		}

		// Multiselect and checkmatrix options are stored in the database with
		// one key per option, each having a boolean value. Extract those keys.
		$multiselectOptions = [];
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
		$checkmatrixOptions = [];
		foreach ( $prefs as $name => $info ) {
			if ( ( isset( $info['type'] ) && $info['type'] == 'checkmatrix' ) ||
					( isset( $info['class'] ) && $info['class'] == 'HTMLCheckMatrix' ) ) {
				$columns = HTMLFormField::flattenOptions( $info['columns'] );
				$rows = HTMLFormField::flattenOptions( $info['rows'] );
				$prefix = isset( $info['prefix'] ) ? $info['prefix'] : $name;

				foreach ( $columns as $column ) {
					foreach ( $rows as $row ) {
						$checkmatrixOptions["$prefix$column-$row"] = true;
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
	 * @param array|string $resetKinds Which kinds of preferences to reset. Defaults to
	 *  array( 'registered', 'registered-multiselect', 'registered-checkmatrix', 'unused' )
	 *  for backwards-compatibility.
	 * @param IContextSource|null $context Context source used when $resetKinds
	 *  does not contain 'all', passed to getOptionKinds().
	 *  Defaults to RequestContext::getMain() when null.
	 */
	public function resetOptions(
		$resetKinds = [ 'registered', 'registered-multiselect', 'registered-checkmatrix', 'unused' ],
		IContextSource $context = null
	) {
		$this->load();
		$defaultOptions = self::getDefaultOptions();

		if ( !is_array( $resetKinds ) ) {
			$resetKinds = [ $resetKinds ];
		}

		if ( in_array( 'all', $resetKinds ) ) {
			$newOptions = $defaultOptions;
		} else {
			if ( $context === null ) {
				$context = RequestContext::getMain();
			}

			$optionKinds = $this->getOptionKinds( $context );
			$resetKinds = array_intersect( $resetKinds, self::listOptionKinds() );
			$newOptions = [];

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

		Hooks::run( 'UserResetAllOptions', [ $this, &$newOptions, $this->mOptions, $resetKinds ] );

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
			Hooks::run( 'UserRequiresHTTPS', [ $this, &$https ] );
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
	 * @return array Array of String permission names
	 */
	public function getRights() {
		if ( is_null( $this->mRights ) ) {
			$this->mRights = self::getGroupPermissions( $this->getEffectiveGroups() );
			Hooks::run( 'UserGetRights', [ $this, &$this->mRights ] );

			// Deny any rights denied by the user's session, unless this
			// endpoint has no sessions.
			if ( !defined( 'MW_NO_SESSION' ) ) {
				$allowedRights = $this->getRequest()->getSession()->getAllowedUserRights();
				if ( $allowedRights !== null ) {
					$this->mRights = array_intersect( $this->mRights, $allowedRights );
				}
			}

			// Force reindexation of rights when a hook has unset one of them
			$this->mRights = array_values( array_unique( $this->mRights ) );

			// If block disables login, we should also remove any
			// extra rights blocked users might have, in case the
			// blocked user has a pre-existing session (T129738).
			// This is checked here for cases where people only call
			// $user->isAllowed(). It is also checked in Title::checkUserBlock()
			// to give a better error message in the common case.
			$config = RequestContext::getMain()->getConfig();
			if (
				$this->isLoggedIn() &&
				$config->get( 'BlockDisablesLogin' ) &&
				$this->isBlocked()
			) {
				$anon = new User;
				$this->mRights = array_intersect( $this->mRights, $anon->getRights() );
			}
		}
		return $this->mRights;
	}

	/**
	 * Get the list of explicit group memberships this user has.
	 * The implicit * and user groups are not included.
	 * @return array Array of String internal group names
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
	 * @return array Array of String internal group names
	 */
	public function getEffectiveGroups( $recache = false ) {
		if ( $recache || is_null( $this->mEffectiveGroups ) ) {
			$this->mEffectiveGroups = array_unique( array_merge(
				$this->getGroups(), // explicit groups
				$this->getAutomaticGroups( $recache ) // implicit groups
			) );
			// Hook for additional groups
			Hooks::run( 'UserEffectiveGroups', [ &$this, &$this->mEffectiveGroups ] );
			// Force reindexation of groups when a hook has unset one of them
			$this->mEffectiveGroups = array_values( array_unique( $this->mEffectiveGroups ) );
		}
		return $this->mEffectiveGroups;
	}

	/**
	 * Get the list of implicit group memberships this user has.
	 * This includes 'user' if logged in, '*' for all accounts,
	 * and autopromoted groups
	 * @param bool $recache Whether to avoid the cache
	 * @return array Array of String internal group names
	 */
	public function getAutomaticGroups( $recache = false ) {
		if ( $recache || is_null( $this->mImplicitGroups ) ) {
			$this->mImplicitGroups = [ '*' ];
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
		$this->load();

		if ( is_null( $this->mFormerGroups ) ) {
			$db = ( $this->queryFlagsUsed & self::READ_LATEST )
				? wfGetDB( DB_MASTER )
				: wfGetDB( DB_REPLICA );
			$res = $db->select( 'user_former_groups',
				[ 'ufg_group' ],
				[ 'ufg_user' => $this->mId ],
				__METHOD__ );
			$this->mFormerGroups = [];
			foreach ( $res as $row ) {
				$this->mFormerGroups[] = $row->ufg_group;
			}
		}

		return $this->mFormerGroups;
	}

	/**
	 * Get the user's edit count.
	 * @return int|null Null for anonymous users
	 */
	public function getEditCount() {
		if ( !$this->getId() ) {
			return null;
		}

		if ( $this->mEditCount === null ) {
			/* Populate the count, if it has not been populated yet */
			$dbr = wfGetDB( DB_REPLICA );
			// check if the user_editcount field has been initialized
			$count = $dbr->selectField(
				'user', 'user_editcount',
				[ 'user_id' => $this->mId ],
				__METHOD__
			);

			if ( $count === null ) {
				// it has not been initialized. do so.
				$count = $this->initEditCount();
			}
			$this->mEditCount = $count;
		}
		return (int)$this->mEditCount;
	}

	/**
	 * Add the user to the given group.
	 * This takes immediate effect.
	 * @param string $group Name of the group to add
	 * @return bool
	 */
	public function addGroup( $group ) {
		$this->load();

		if ( !Hooks::run( 'UserAddGroup', [ $this, &$group ] ) ) {
			return false;
		}

		$dbw = wfGetDB( DB_MASTER );
		if ( $this->getId() ) {
			$dbw->insert( 'user_groups',
				[
					'ug_user' => $this->getId(),
					'ug_group' => $group,
				],
				__METHOD__,
				[ 'IGNORE' ] );
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

		return true;
	}

	/**
	 * Remove the user from the given group.
	 * This takes immediate effect.
	 * @param string $group Name of the group to remove
	 * @return bool
	 */
	public function removeGroup( $group ) {
		$this->load();
		if ( !Hooks::run( 'UserRemoveGroup', [ $this, &$group ] ) ) {
			return false;
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'user_groups',
			[
				'ug_user' => $this->getId(),
				'ug_group' => $group,
			], __METHOD__
		);
		// Remember that the user was in this group
		$dbw->insert( 'user_former_groups',
			[
				'ufg_user' => $this->getId(),
				'ufg_group' => $group,
			],
			__METHOD__,
			[ 'IGNORE' ]
		);

		$this->loadGroups();
		$this->mGroups = array_diff( $this->mGroups, [ $group ] );

		// Refresh the groups caches, and clear the rights cache so it will be
		// refreshed on the next call to $this->getRights().
		$this->getEffectiveGroups( true );
		$this->mRights = null;

		$this->invalidateCache();

		return true;
	}

	/**
	 * Get whether the user is logged in
	 * @return bool
	 */
	public function isLoggedIn() {
		return $this->getId() != 0;
	}

	/**
	 * Get whether the user is anonymous
	 * @return bool
	 */
	public function isAnon() {
		return !$this->isLoggedIn();
	}

	/**
	 * @return bool Whether this user is flagged as being a bot role account
	 * @since 1.28
	 */
	public function isBot() {
		if ( in_array( 'bot', $this->getGroups() ) && $this->isAllowed( 'bot' ) ) {
			return true;
		}

		$isBot = false;
		Hooks::run( "UserIsBot", [ $this, &$isBot ] );

		return $isBot;
	}

	/**
	 * Check if user is allowed to access a feature / make an action
	 *
	 * @param string ... Permissions to test
	 * @return bool True if user is allowed to perform *any* of the given actions
	 */
	public function isAllowedAny() {
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
	 * @param string ... Permissions to test
	 * @return bool True if the user is allowed to perform *all* of the given actions
	 */
	public function isAllowedAll() {
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
		// Use strict parameter to avoid matching numeric 0 accidentally inserted
		// by misconfiguration: 0 == 'foo'
		return in_array( $action, $this->getRights(), true );
	}

	/**
	 * Check whether to enable recent changes patrol features for this user
	 * @return bool True or false
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
	 * Check whether to enable new files patrol features for this user
	 * @return bool True or false
	 */
	public function useFilePatrol() {
		global $wgUseRCPatrol, $wgUseFilePatrol;
		return (
			( $wgUseRCPatrol || $wgUseFilePatrol )
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
	 * Check the watched status of an article.
	 * @since 1.22 $checkRights parameter added
	 * @param Title $title Title of the article to look at
	 * @param bool $checkRights Whether to check 'viewmywatchlist'/'editmywatchlist' rights.
	 *     Pass User::CHECK_USER_RIGHTS or User::IGNORE_USER_RIGHTS.
	 * @return bool
	 */
	public function isWatched( $title, $checkRights = self::CHECK_USER_RIGHTS ) {
		if ( $title->isWatchable() && ( !$checkRights || $this->isAllowed( 'viewmywatchlist' ) ) ) {
			return MediaWikiServices::getInstance()->getWatchedItemStore()->isWatched( $this, $title );
		}
		return false;
	}

	/**
	 * Watch an article.
	 * @since 1.22 $checkRights parameter added
	 * @param Title $title Title of the article to look at
	 * @param bool $checkRights Whether to check 'viewmywatchlist'/'editmywatchlist' rights.
	 *     Pass User::CHECK_USER_RIGHTS or User::IGNORE_USER_RIGHTS.
	 */
	public function addWatch( $title, $checkRights = self::CHECK_USER_RIGHTS ) {
		if ( !$checkRights || $this->isAllowed( 'editmywatchlist' ) ) {
			MediaWikiServices::getInstance()->getWatchedItemStore()->addWatchBatchForUser(
				$this,
				[ $title->getSubjectPage(), $title->getTalkPage() ]
			);
		}
		$this->invalidateCache();
	}

	/**
	 * Stop watching an article.
	 * @since 1.22 $checkRights parameter added
	 * @param Title $title Title of the article to look at
	 * @param bool $checkRights Whether to check 'viewmywatchlist'/'editmywatchlist' rights.
	 *     Pass User::CHECK_USER_RIGHTS or User::IGNORE_USER_RIGHTS.
	 */
	public function removeWatch( $title, $checkRights = self::CHECK_USER_RIGHTS ) {
		if ( !$checkRights || $this->isAllowed( 'editmywatchlist' ) ) {
			$store = MediaWikiServices::getInstance()->getWatchedItemStore();
			$store->removeWatch( $this, $title->getSubjectPage() );
			$store->removeWatch( $this, $title->getTalkPage() );
		}
		$this->invalidateCache();
	}

	/**
	 * Clear the user's notification timestamp for the given title.
	 * If e-notif e-mails are on, they will receive notification mails on
	 * the next change of the page if it's watched etc.
	 * @note If the user doesn't have 'editmywatchlist', this will do nothing.
	 * @param Title $title Title of the article to look at
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
			if ( !Hooks::run( 'UserClearNewTalkNotification', [ &$this, $oldid ] ) ) {
				return;
			}

			// Try to update the DB post-send and only if needed...
			DeferredUpdates::addCallableUpdate( function() use ( $title, $oldid ) {
				if ( !$this->getNewtalk() ) {
					return; // no notifications to clear
				}

				// Delete the last notifications (they stack up)
				$this->setNewtalk( false );

				// If there is a new, unseen, revision, use its timestamp
				$nextid = $oldid
					? $title->getNextRevisionID( $oldid, Title::GAID_FOR_UPDATE )
					: null;
				if ( $nextid ) {
					$this->setNewtalk( true, Revision::newFromId( $nextid ) );
				}
			} );
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
		// and when it does have to be executed, it can be on a replica DB
		// If this is the user's newtalk page, we always update the timestamp
		$force = '';
		if ( $title->getNamespace() == NS_USER_TALK && $title->getText() == $this->getName() ) {
			$force = 'force';
		}

		MediaWikiServices::getInstance()->getWatchedItemStore()
			->resetNotificationTimestamp( $this, $title, $force, $oldid );
	}

	/**
	 * Resets all of the given user's page-change notification timestamps.
	 * If e-notif e-mails are on, they will receive notification mails on
	 * the next change of any watched page.
	 * @note If the user doesn't have 'editmywatchlist', this will do nothing.
	 */
	public function clearAllNotifications() {
		global $wgUseEnotif, $wgShowUpdatedMarker;
		// Do nothing if not allowed to edit the watchlist
		if ( wfReadOnly() || !$this->isAllowed( 'editmywatchlist' ) ) {
			return;
		}

		if ( !$wgUseEnotif && !$wgShowUpdatedMarker ) {
			$this->setNewtalk( false );
			return;
		}

		$id = $this->getId();
		if ( !$id ) {
			return;
		}

		$dbw = wfGetDB( DB_MASTER );
		$asOfTimes = array_unique( $dbw->selectFieldValues(
			'watchlist',
			'wl_notificationtimestamp',
			[ 'wl_user' => $id, 'wl_notificationtimestamp IS NOT NULL' ],
			__METHOD__,
			[ 'ORDER BY' => 'wl_notificationtimestamp DESC', 'LIMIT' => 500 ]
		) );
		if ( !$asOfTimes ) {
			return;
		}
		// Immediately update the most recent touched rows, which hopefully covers what
		// the user sees on the watchlist page before pressing "mark all pages visited"....
		$dbw->update(
			'watchlist',
			[ 'wl_notificationtimestamp' => null ],
			[ 'wl_user' => $id, 'wl_notificationtimestamp' => $asOfTimes ],
			__METHOD__
		);
		// ...and finish the older ones in a post-send update with lag checks...
		DeferredUpdates::addUpdate( new AutoCommitUpdate(
			$dbw,
			__METHOD__,
			function () use ( $dbw, $id ) {
				global $wgUpdateRowsPerQuery;

				$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
				$ticket = $lbFactory->getEmptyTransactionTicket( __METHOD__ );
				$asOfTimes = array_unique( $dbw->selectFieldValues(
					'watchlist',
					'wl_notificationtimestamp',
					[ 'wl_user' => $id, 'wl_notificationtimestamp IS NOT NULL' ],
					__METHOD__
				) );
				foreach ( array_chunk( $asOfTimes, $wgUpdateRowsPerQuery ) as $asOfTimeBatch ) {
					$dbw->update(
						'watchlist',
						[ 'wl_notificationtimestamp' => null ],
						[ 'wl_user' => $id, 'wl_notificationtimestamp' => $asOfTimeBatch ],
						__METHOD__
					);
					$lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
				}
			}
		) );
		// We also need to clear here the "you have new message" notification for the own
		// user_talk page; it's cleared one page view later in WikiPage::doViewUpdates().
	}

	/**
	 * Set a cookie on the user's client. Wrapper for
	 * WebResponse::setCookie
	 * @deprecated since 1.27
	 * @param string $name Name of the cookie to set
	 * @param string $value Value to set
	 * @param int $exp Expiration time, as a UNIX time value;
	 *                   if 0 or not specified, use the default $wgCookieExpiration
	 * @param bool $secure
	 *  true: Force setting the secure attribute when setting the cookie
	 *  false: Force NOT setting the secure attribute when setting the cookie
	 *  null (default): Use the default ($wgCookieSecure) to set the secure attribute
	 * @param array $params Array of options sent passed to WebResponse::setcookie()
	 * @param WebRequest|null $request WebRequest object to use; $wgRequest will be used if null
	 *        is passed.
	 */
	protected function setCookie(
		$name, $value, $exp = 0, $secure = null, $params = [], $request = null
	) {
		wfDeprecated( __METHOD__, '1.27' );
		if ( $request === null ) {
			$request = $this->getRequest();
		}
		$params['secure'] = $secure;
		$request->response()->setCookie( $name, $value, $exp, $params );
	}

	/**
	 * Clear a cookie on the user's client
	 * @deprecated since 1.27
	 * @param string $name Name of the cookie to clear
	 * @param bool $secure
	 *  true: Force setting the secure attribute when setting the cookie
	 *  false: Force NOT setting the secure attribute when setting the cookie
	 *  null (default): Use the default ($wgCookieSecure) to set the secure attribute
	 * @param array $params Array of options sent passed to WebResponse::setcookie()
	 */
	protected function clearCookie( $name, $secure = null, $params = [] ) {
		wfDeprecated( __METHOD__, '1.27' );
		$this->setCookie( $name, '', time() - 86400, $secure, $params );
	}

	/**
	 * Set an extended login cookie on the user's client. The expiry of the cookie
	 * is controlled by the $wgExtendedLoginCookieExpiration configuration
	 * variable.
	 *
	 * @see User::setCookie
	 *
	 * @deprecated since 1.27
	 * @param string $name Name of the cookie to set
	 * @param string $value Value to set
	 * @param bool $secure
	 *  true: Force setting the secure attribute when setting the cookie
	 *  false: Force NOT setting the secure attribute when setting the cookie
	 *  null (default): Use the default ($wgCookieSecure) to set the secure attribute
	 */
	protected function setExtendedLoginCookie( $name, $value, $secure ) {
		global $wgExtendedLoginCookieExpiration, $wgCookieExpiration;

		wfDeprecated( __METHOD__, '1.27' );

		$exp = time();
		$exp += $wgExtendedLoginCookieExpiration !== null
			? $wgExtendedLoginCookieExpiration
			: $wgCookieExpiration;

		$this->setCookie( $name, $value, $exp, $secure );
	}

	/**
	 * Persist this user's session (e.g. set cookies)
	 *
	 * @param WebRequest|null $request WebRequest object to use; $wgRequest will be used if null
	 *        is passed.
	 * @param bool $secure Whether to force secure/insecure cookies or use default
	 * @param bool $rememberMe Whether to add a Token cookie for elongated sessions
	 */
	public function setCookies( $request = null, $secure = null, $rememberMe = false ) {
		$this->load();
		if ( 0 == $this->mId ) {
			return;
		}

		$session = $this->getRequest()->getSession();
		if ( $request && $session->getRequest() !== $request ) {
			$session = $session->sessionWithRequest( $request );
		}
		$delay = $session->delaySave();

		if ( !$session->getUser()->equals( $this ) ) {
			if ( !$session->canSetUser() ) {
				\MediaWiki\Logger\LoggerFactory::getInstance( 'session' )
					->warning( __METHOD__ .
						": Cannot save user \"$this\" to a user \"{$session->getUser()}\"'s immutable session"
					);
				return;
			}
			$session->setUser( $this );
		}

		$session->setRememberUser( $rememberMe );
		if ( $secure !== null ) {
			$session->setForceHTTPS( $secure );
		}

		$session->persist();

		ScopedCallback::consume( $delay );
	}

	/**
	 * Log this user out.
	 */
	public function logout() {
		if ( Hooks::run( 'UserLogout', [ &$this ] ) ) {
			$this->doLogout();
		}
	}

	/**
	 * Clear the user's session, and reset the instance cache.
	 * @see logout()
	 */
	public function doLogout() {
		$session = $this->getRequest()->getSession();
		if ( !$session->canSetUser() ) {
			\MediaWiki\Logger\LoggerFactory::getInstance( 'session' )
				->warning( __METHOD__ . ": Cannot log out of an immutable session" );
			$error = 'immutable';
		} elseif ( !$session->getUser()->equals( $this ) ) {
			\MediaWiki\Logger\LoggerFactory::getInstance( 'session' )
				->warning( __METHOD__ .
					": Cannot log user \"$this\" out of a user \"{$session->getUser()}\"'s session"
				);
			// But we still may as well make this user object anon
			$this->clearInstanceCache( 'defaults' );
			$error = 'wronguser';
		} else {
			$this->clearInstanceCache( 'defaults' );
			$delay = $session->delaySave();
			$session->unpersist(); // Clear cookies (T127436)
			$session->setLoggedOutTimestamp( time() );
			$session->setUser( new User );
			$session->set( 'wsUserID', 0 ); // Other code expects this
			$session->resetAllTokens();
			ScopedCallback::consume( $delay );
			$error = false;
		}
		\MediaWiki\Logger\LoggerFactory::getInstance( 'authevents' )->info( 'Logout', [
			'event' => 'logout',
			'successful' => $error === false,
			'status' => $error ?: 'success',
		] );
	}

	/**
	 * Save this user's settings into the database.
	 * @todo Only rarely do all these fields need to be set!
	 */
	public function saveSettings() {
		if ( wfReadOnly() ) {
			// @TODO: caller should deal with this instead!
			// This should really just be an exception.
			MWExceptionHandler::logException( new DBExpectedError(
				null,
				"Could not update user with ID '{$this->mId}'; DB is read-only."
			) );
			return;
		}

		$this->load();
		if ( 0 == $this->mId ) {
			return; // anon
		}

		// Get a new user_touched that is higher than the old one.
		// This will be used for a CAS check as a last-resort safety
		// check against race conditions and replica DB lag.
		$newTouched = $this->newTouchedTimestamp();

		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'user',
			[ /* SET */
				'user_name' => $this->mName,
				'user_real_name' => $this->mRealName,
				'user_email' => $this->mEmail,
				'user_email_authenticated' => $dbw->timestampOrNull( $this->mEmailAuthenticated ),
				'user_touched' => $dbw->timestamp( $newTouched ),
				'user_token' => strval( $this->mToken ),
				'user_email_token' => $this->mEmailToken,
				'user_email_token_expires' => $dbw->timestampOrNull( $this->mEmailTokenExpires ),
			], $this->makeUpdateConditions( $dbw, [ /* WHERE */
				'user_id' => $this->mId,
			] ), __METHOD__
		);

		if ( !$dbw->affectedRows() ) {
			// Maybe the problem was a missed cache update; clear it to be safe
			$this->clearSharedCache( 'refresh' );
			// User was changed in the meantime or loaded with stale data
			$from = ( $this->queryFlagsUsed & self::READ_LATEST ) ? 'master' : 'replica';
			throw new MWException(
				"CAS update failed on user_touched for user ID '{$this->mId}' (read from $from);" .
				" the version of the user to be saved is older than the current version."
			);
		}

		$this->mTouched = $newTouched;
		$this->saveOptions();

		Hooks::run( 'UserSaveSettings', [ $this ] );
		$this->clearSharedCache();
		$this->getUserPage()->invalidateCache();
	}

	/**
	 * If only this user's username is known, and it exists, return the user ID.
	 *
	 * @param int $flags Bitfield of User:READ_* constants; useful for existence checks
	 * @return int
	 */
	public function idForName( $flags = 0 ) {
		$s = trim( $this->getName() );
		if ( $s === '' ) {
			return 0;
		}

		$db = ( ( $flags & self::READ_LATEST ) == self::READ_LATEST )
			? wfGetDB( DB_MASTER )
			: wfGetDB( DB_REPLICA );

		$options = ( ( $flags & self::READ_LOCKING ) == self::READ_LOCKING )
			? [ 'LOCK IN SHARE MODE' ]
			: [];

		$id = $db->selectField( 'user',
			'user_id', [ 'user_name' => $s ], __METHOD__, $options );

		return (int)$id;
	}

	/**
	 * Add a user to the database, return the user object
	 *
	 * @param string $name Username to add
	 * @param array $params Array of Strings Non-default parameters to save to
	 *   the database as user_* fields:
	 *   - email: The user's email address.
	 *   - email_authenticated: The email authentication timestamp.
	 *   - real_name: The user's real name.
	 *   - options: An associative array of non-default options.
	 *   - token: Random authentication token. Do not set.
	 *   - registration: Registration timestamp. Do not set.
	 *
	 * @return User|null User object, or null if the username already exists.
	 */
	public static function createNew( $name, $params = [] ) {
		foreach ( [ 'password', 'newpassword', 'newpass_time', 'password_expires' ] as $field ) {
			if ( isset( $params[$field] ) ) {
				wfDeprecated( __METHOD__ . " with param '$field'", '1.27' );
				unset( $params[$field] );
			}
		}

		$user = new User;
		$user->load();
		$user->setToken(); // init token
		if ( isset( $params['options'] ) ) {
			$user->mOptions = $params['options'] + (array)$user->mOptions;
			unset( $params['options'] );
		}
		$dbw = wfGetDB( DB_MASTER );
		$seqVal = $dbw->nextSequenceValue( 'user_user_id_seq' );

		$noPass = PasswordFactory::newInvalidPassword()->toString();

		$fields = [
			'user_id' => $seqVal,
			'user_name' => $name,
			'user_password' => $noPass,
			'user_newpassword' => $noPass,
			'user_email' => $user->mEmail,
			'user_email_authenticated' => $dbw->timestampOrNull( $user->mEmailAuthenticated ),
			'user_real_name' => $user->mRealName,
			'user_token' => strval( $user->mToken ),
			'user_registration' => $dbw->timestamp( $user->mRegistration ),
			'user_editcount' => 0,
			'user_touched' => $dbw->timestamp( $user->newTouchedTimestamp() ),
		];
		foreach ( $params as $name => $value ) {
			$fields["user_$name"] = $value;
		}
		$dbw->insert( 'user', $fields, __METHOD__, [ 'IGNORE' ] );
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

		$this->mTouched = $this->newTouchedTimestamp();

		$noPass = PasswordFactory::newInvalidPassword()->toString();

		$dbw = wfGetDB( DB_MASTER );
		$seqVal = $dbw->nextSequenceValue( 'user_user_id_seq' );
		$dbw->insert( 'user',
			[
				'user_id' => $seqVal,
				'user_name' => $this->mName,
				'user_password' => $noPass,
				'user_newpassword' => $noPass,
				'user_email' => $this->mEmail,
				'user_email_authenticated' => $dbw->timestampOrNull( $this->mEmailAuthenticated ),
				'user_real_name' => $this->mRealName,
				'user_token' => strval( $this->mToken ),
				'user_registration' => $dbw->timestamp( $this->mRegistration ),
				'user_editcount' => 0,
				'user_touched' => $dbw->timestamp( $this->mTouched ),
			], __METHOD__,
			[ 'IGNORE' ]
		);
		if ( !$dbw->affectedRows() ) {
			// Use locking reads to bypass any REPEATABLE-READ snapshot.
			$this->mId = $dbw->selectField(
				'user',
				'user_id',
				[ 'user_name' => $this->mName ],
				__METHOD__,
				[ 'LOCK IN SHARE MODE' ]
			);
			$loaded = false;
			if ( $this->mId ) {
				if ( $this->loadFromDatabase( self::READ_LOCKING ) ) {
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
		self::$idCacheByName[$this->mName] = $this->mId;

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
		return $this->mBlockedFromCreateAccount instanceof Block
			&& $this->mBlockedFromCreateAccount->prevents( 'createaccount' )
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
	 * @return Title User's personal page title
	 */
	public function getUserPage() {
		return Title::makeTitle( NS_USER, $this->getName() );
	}

	/**
	 * Get this user's talk page title.
	 *
	 * @return Title User's talk page title
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
	 * @deprecated since 1.27, use AuthManager instead
	 * @param string $password User password
	 * @return bool True if the given password is correct, otherwise False
	 */
	public function checkPassword( $password ) {
		$manager = AuthManager::singleton();
		$reqs = AuthenticationRequest::loadRequestsFromSubmission(
			$manager->getAuthenticationRequests( AuthManager::ACTION_LOGIN ),
			[
				'username' => $this->getName(),
				'password' => $password,
			]
		);
		$res = AuthManager::singleton()->beginAuthentication( $reqs, 'null:' );
		switch ( $res->status ) {
			case AuthenticationResponse::PASS:
				return true;
			case AuthenticationResponse::FAIL:
				// Hope it's not a PreAuthenticationProvider that failed...
				\MediaWiki\Logger\LoggerFactory::getInstance( 'authentication' )
					->info( __METHOD__ . ': Authentication failed: ' . $res->message->plain() );
				return false;
			default:
				throw new BadMethodCallException(
					'AuthManager returned a response unsupported by ' . __METHOD__
				);
		}
	}

	/**
	 * Check if the given clear-text password matches the temporary password
	 * sent by e-mail for password reset operations.
	 *
	 * @deprecated since 1.27, use AuthManager instead
	 * @param string $plaintext
	 * @return bool True if matches, false otherwise
	 */
	public function checkTemporaryPassword( $plaintext ) {
		// Can't check the temporary password individually.
		return $this->checkPassword( $plaintext );
	}

	/**
	 * Initialize (if necessary) and return a session token value
	 * which can be used in edit forms to show that the user's
	 * login credentials aren't being hijacked with a foreign form
	 * submission.
	 *
	 * @since 1.27
	 * @param string|array $salt Array of Strings Optional function-specific data for hashing
	 * @param WebRequest|null $request WebRequest object to use or null to use $wgRequest
	 * @return MediaWiki\Session\Token The new edit token
	 */
	public function getEditTokenObject( $salt = '', $request = null ) {
		if ( $this->isAnon() ) {
			return new LoggedOutEditToken();
		}

		if ( !$request ) {
			$request = $this->getRequest();
		}
		return $request->getSession()->getToken( $salt );
	}

	/**
	 * Initialize (if necessary) and return a session token value
	 * which can be used in edit forms to show that the user's
	 * login credentials aren't being hijacked with a foreign form
	 * submission.
	 *
	 * The $salt for 'edit' and 'csrf' tokens is the default (empty string).
	 *
	 * @since 1.19
	 * @param string|array $salt Array of Strings Optional function-specific data for hashing
	 * @param WebRequest|null $request WebRequest object to use or null to use $wgRequest
	 * @return string The new edit token
	 */
	public function getEditToken( $salt = '', $request = null ) {
		return $this->getEditTokenObject( $salt, $request )->toString();
	}

	/**
	 * Get the embedded timestamp from a token.
	 * @deprecated since 1.27, use \MediaWiki\Session\Token::getTimestamp instead.
	 * @param string $val Input token
	 * @return int|null
	 */
	public static function getEditTokenTimestamp( $val ) {
		wfDeprecated( __METHOD__, '1.27' );
		return MediaWiki\Session\Token::getTimestamp( $val );
	}

	/**
	 * Check given value against the token value stored in the session.
	 * A match should confirm that the form was submitted from the
	 * user's own login session, not a form submission from a third-party
	 * site.
	 *
	 * @param string $val Input value to compare
	 * @param string $salt Optional function-specific data for hashing
	 * @param WebRequest|null $request Object to use or null to use $wgRequest
	 * @param int $maxage Fail tokens older than this, in seconds
	 * @return bool Whether the token matches
	 */
	public function matchEditToken( $val, $salt = '', $request = null, $maxage = null ) {
		return $this->getEditTokenObject( $salt, $request )->match( $val, $maxage );
	}

	/**
	 * Check given value against the token value stored in the session,
	 * ignoring the suffix.
	 *
	 * @param string $val Input value to compare
	 * @param string $salt Optional function-specific data for hashing
	 * @param WebRequest|null $request Object to use or null to use $wgRequest
	 * @param int $maxage Fail tokens older than this, in seconds
	 * @return bool Whether the token matches
	 */
	public function matchEditTokenNoSuffix( $val, $salt = '', $request = null, $maxage = null ) {
		$val = substr( $val, 0, strspn( $val, '0123456789abcdef' ) ) . Token::SUFFIX;
		return $this->matchEditToken( $val, $salt, $request, $maxage );
	}

	/**
	 * Generate a new e-mail confirmation token and send a confirmation/invalidation
	 * mail to the user's given address.
	 *
	 * @param string $type Message to send, either "created", "changed" or "set"
	 * @return Status
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
				$wgLang->userTimeAndDate( $expiration, $this ),
				$invalidateURL,
				$wgLang->userDate( $expiration, $this ),
				$wgLang->userTime( $expiration, $this ) )->text() );
	}

	/**
	 * Send an e-mail to this user's account. Does not check for
	 * confirmed status or validity.
	 *
	 * @param string $subject Message subject
	 * @param string $body Message body
	 * @param User|null $from Optional sending user; if unspecified, default
	 *   $wgPasswordSender will be used.
	 * @param string $replyto Reply-To address
	 * @return Status
	 */
	public function sendMail( $subject, $body, $from = null, $replyto = null ) {
		global $wgPasswordSender;

		if ( $from instanceof User ) {
			$sender = MailAddress::newFromUser( $from );
		} else {
			$sender = new MailAddress( $wgPasswordSender,
				wfMessage( 'emailsender' )->inContentLanguage()->text() );
		}
		$to = MailAddress::newFromUser( $this );

		return UserMailer::send( $to, $sender, $subject, $body, [
			'replyTo' => $replyto,
		] );
	}

	/**
	 * Generate, store, and return a new e-mail confirmation code.
	 * A hash (unsalted, since it's used as a key) is stored.
	 *
	 * @note Call saveSettings() after calling this function to commit
	 * this change to the database.
	 *
	 * @param string &$expiration Accepts the expiration time
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
			Hooks::run( 'ConfirmEmailComplete', [ $this ] );
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
		$this->mEmail = '';
		Hooks::run( 'InvalidateEmailComplete', [ $this ] );
		return true;
	}

	/**
	 * Set the e-mail authentication timestamp.
	 * @param string $timestamp TS_MW timestamp
	 */
	public function setEmailAuthenticationTimestamp( $timestamp ) {
		$this->load();
		$this->mEmailAuthenticated = $timestamp;
		Hooks::run( 'UserSetEmailAuthenticationTimestamp', [ $this, &$this->mEmailAuthenticated ] );
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
		Hooks::run( 'UserCanSendEmail', [ &$this, &$canSend ] );
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
		if ( Hooks::run( 'EmailConfirmed', [ &$this, &$confirmed ] ) ) {
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
		$dbr = wfGetDB( DB_REPLICA );
		$time = $dbr->selectField( 'revision', 'rev_timestamp',
			[ 'rev_user' => $this->getId() ],
			__METHOD__,
			[ 'ORDER BY' => 'rev_timestamp ASC' ]
		);
		if ( !$time ) {
			return false; // no edits
		}
		return wfTimestamp( TS_MW, $time );
	}

	/**
	 * Get the permissions associated with a given list of groups
	 *
	 * @param array $groups Array of Strings List of internal group names
	 * @return array Array of Strings List of permission key names for given groups combined
	 */
	public static function getGroupPermissions( $groups ) {
		global $wgGroupPermissions, $wgRevokePermissions;
		$rights = [];
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
	 * @return array Array of Strings List of internal group names with the given permission
	 */
	public static function getGroupsWithPermission( $role ) {
		global $wgGroupPermissions;
		$allowedGroups = [];
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
	 * Check if all users may be assumed to have the given permission
	 *
	 * We generally assume so if the right is granted to '*' and isn't revoked
	 * on any group. It doesn't attempt to take grants or other extension
	 * limitations on rights into account in the general case, though, as that
	 * would require it to always return false and defeat the purpose.
	 * Specifically, session-based rights restrictions (such as OAuth or bot
	 * passwords) are applied based on the current session.
	 *
	 * @since 1.22
	 * @param string $right Right to check
	 * @return bool
	 */
	public static function isEveryoneAllowed( $right ) {
		global $wgGroupPermissions, $wgRevokePermissions;
		static $cache = [];

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

		// Remove any rights that aren't allowed to the global-session user,
		// unless there are no sessions for this endpoint.
		if ( !defined( 'MW_NO_SESSION' ) ) {
			$allowedRights = SessionManager::getGlobalSession()->getAllowedUserRights();
			if ( $allowedRights !== null && !in_array( $right, $allowedRights, true ) ) {
				$cache[$right] = false;
				return false;
			}
		}

		// Allow extensions to say false
		if ( !Hooks::run( 'UserIsEveryoneAllowed', [ $right ] ) ) {
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
	 * @return array Array of internal group names
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
	 * @return string[] Array of permission names
	 */
	public static function getAllRights() {
		if ( self::$mAllRights === false ) {
			global $wgAvailableRights;
			if ( count( $wgAvailableRights ) ) {
				self::$mAllRights = array_unique( array_merge( self::$mCoreRights, $wgAvailableRights ) );
			} else {
				self::$mAllRights = self::$mCoreRights;
			}
			Hooks::run( 'UserGetAllRights', [ &self::$mAllRights ] );
		}
		return self::$mAllRights;
	}

	/**
	 * Get a list of implicit groups
	 * @return array Array of Strings Array of internal group names
	 */
	public static function getImplicitGroups() {
		global $wgImplicitGroups;

		$groups = $wgImplicitGroups;
		# Deprecated, use $wgImplicitGroups instead
		Hooks::run( 'UserGetImplicitGroups', [ &$groups ], '1.25' );

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
			return htmlspecialchars( $text );
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
			$page = $title->getFullText();
			return "[[$page|$text]]";
		} else {
			return $text;
		}
	}

	/**
	 * Returns an array of the groups that a particular group can add/remove.
	 *
	 * @param string $group The group to check for whether it can add/remove
	 * @return array Array( 'add' => array( addablegroups ),
	 *     'remove' => array( removablegroups ),
	 *     'add-self' => array( addablegroups to self),
	 *     'remove-self' => array( removable groups from self) )
	 */
	public static function changeableByGroup( $group ) {
		global $wgAddGroups, $wgRemoveGroups, $wgGroupsAddToSelf, $wgGroupsRemoveFromSelf;

		$groups = [
			'add' => [],
			'remove' => [],
			'add-self' => [],
			'remove-self' => []
		];

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
			// Do nothing
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
			// Do nothing
		} elseif ( $wgGroupsAddToSelf[$group] === true ) {
			// No idea WHY this would be used, but it's there
			$groups['add-self'] = User::getAllGroups();
		} elseif ( is_array( $wgGroupsAddToSelf[$group] ) ) {
			$groups['add-self'] = $wgGroupsAddToSelf[$group];
		}

		if ( empty( $wgGroupsRemoveFromSelf[$group] ) ) {
			// Do nothing
		} elseif ( $wgGroupsRemoveFromSelf[$group] === true ) {
			$groups['remove-self'] = User::getAllGroups();
		} elseif ( is_array( $wgGroupsRemoveFromSelf[$group] ) ) {
			$groups['remove-self'] = $wgGroupsRemoveFromSelf[$group];
		}

		return $groups;
	}

	/**
	 * Returns an array of groups that this user can add and remove
	 * @return array Array( 'add' => array( addablegroups ),
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
			return [
				'add' => $all,
				'remove' => $all,
				'add-self' => [],
				'remove-self' => []
			];
		}

		// Okay, it's not so simple, we will have to go through the arrays
		$groups = [
			'add' => [],
			'remove' => [],
			'add-self' => [],
			'remove-self' => []
		];
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
	 * Deferred version of incEditCountImmediate()
	 */
	public function incEditCount() {
		wfGetDB( DB_MASTER )->onTransactionPreCommitOrIdle(
			function () {
				$this->incEditCountImmediate();
			},
			__METHOD__
		);
	}

	/**
	 * Increment the user's edit-count field.
	 * Will have no effect for anonymous users.
	 * @since 1.26
	 */
	public function incEditCountImmediate() {
		if ( $this->isAnon() ) {
			return;
		}

		$dbw = wfGetDB( DB_MASTER );
		// No rows will be "affected" if user_editcount is NULL
		$dbw->update(
			'user',
			[ 'user_editcount=user_editcount+1' ],
			[ 'user_id' => $this->getId(), 'user_editcount IS NOT NULL' ],
			__METHOD__
		);
		// Lazy initialization check...
		if ( $dbw->affectedRows() == 0 ) {
			// Now here's a goddamn hack...
			$dbr = wfGetDB( DB_REPLICA );
			if ( $dbr !== $dbw ) {
				// If we actually have a replica DB server, the count is
				// at least one behind because the current transaction
				// has not been committed and replicated.
				$this->mEditCount = $this->initEditCount( 1 );
			} else {
				// But if DB_REPLICA is selecting the master, then the
				// count we just read includes the revision that was
				// just added in the working transaction.
				$this->mEditCount = $this->initEditCount();
			}
		} else {
			if ( $this->mEditCount === null ) {
				$this->getEditCount();
				$dbr = wfGetDB( DB_REPLICA );
				$this->mEditCount += ( $dbr !== $dbw ) ? 1 : 0;
			} else {
				$this->mEditCount++;
			}
		}
		// Edit count in user cache too
		$this->invalidateCache();
	}

	/**
	 * Initialize user_editcount from data out of the revision table
	 *
	 * @param int $add Edits to add to the count from the revision table
	 * @return int Number of edits
	 */
	protected function initEditCount( $add = 0 ) {
		// Pull from a replica DB to be less cruel to servers
		// Accuracy isn't the point anyway here
		$dbr = wfGetDB( DB_REPLICA );
		$count = (int)$dbr->selectField(
			'revision',
			'COUNT(rev_user)',
			[ 'rev_user' => $this->getId() ],
			__METHOD__
		);
		$count = $count + $add;

		$dbw = wfGetDB( DB_MASTER );
		$dbw->update(
			'user',
			[ 'user_editcount' => $count ],
			[ 'user_id' => $this->getId() ],
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
	 * Make a new-style password hash
	 *
	 * @param string $password Plain-text password
	 * @param bool|string $salt Optional salt, may be random or the user ID.
	 *  If unspecified or false, will generate one automatically
	 * @return string Password hash
	 * @deprecated since 1.24, use Password class
	 */
	public static function crypt( $password, $salt = false ) {
		wfDeprecated( __METHOD__, '1.24' );
		$passwordFactory = new PasswordFactory();
		$passwordFactory->init( RequestContext::getMain()->getConfig() );
		$hash = $passwordFactory->newFromPlaintext( $password );
		return $hash->toString();
	}

	/**
	 * Compare a password hash with a plain-text password. Requires the user
	 * ID if there's a chance that the hash is an old-style hash.
	 *
	 * @param string $hash Password hash
	 * @param string $password Plain-text password to compare
	 * @param string|bool $userId User ID for old-style password salt
	 *
	 * @return bool
	 * @deprecated since 1.24, use Password class
	 */
	public static function comparePasswords( $hash, $password, $userId = false ) {
		wfDeprecated( __METHOD__, '1.24' );

		// Check for *really* old password hashes that don't even have a type
		// The old hash format was just an md5 hex hash, with no type information
		if ( preg_match( '/^[0-9a-f]{32}$/', $hash ) ) {
			global $wgPasswordSalt;
			if ( $wgPasswordSalt ) {
				$password = ":B:{$userId}:{$hash}";
			} else {
				$password = ":A:{$hash}";
			}
		}

		$passwordFactory = new PasswordFactory();
		$passwordFactory->init( RequestContext::getMain()->getConfig() );
		$hash = $passwordFactory->newFromCiphertext( $hash );
		return $hash->equals( $password );
	}

	/**
	 * Add a newuser log entry for this user.
	 * Before 1.19 the return value was always true.
	 *
	 * @deprecated since 1.27, AuthManager handles logging
	 * @param string|bool $action Account creation type.
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
	 * @param string $reason User supplied reason
	 * @return bool true
	 */
	public function addNewUserLogEntry( $action = false, $reason = '' ) {
		return true; // disabled
	}

	/**
	 * Add an autocreate newuser log entry for this user
	 * Used by things like CentralAuth and perhaps other authplugins.
	 * Consider calling addNewUserLogEntry() directly instead.
	 *
	 * @deprecated since 1.27, AuthManager handles logging
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
				$dbr = ( $this->queryFlagsUsed & self::READ_LATEST )
					? wfGetDB( DB_MASTER )
					: wfGetDB( DB_REPLICA );

				$res = $dbr->select(
					'user_properties',
					[ 'up_property', 'up_value' ],
					[ 'up_user' => $this->getId() ],
					__METHOD__
				);

				$this->mOptionOverrides = [];
				$data = [];
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

		Hooks::run( 'UserLoadOptions', [ $this, &$this->mOptions ] );
	}

	/**
	 * Saves the non-default options for this user, as previously set e.g. via
	 * setOption(), in the database's "user_properties" (preferences) table.
	 * Usually used via saveSettings().
	 */
	protected function saveOptions() {
		$this->loadOptions();

		// Not using getOptions(), to keep hidden preferences in database
		$saveOptions = $this->mOptions;

		// Allow hooks to abort, for instance to save to a global profile.
		// Reset options to default state before saving.
		if ( !Hooks::run( 'UserSaveOptions', [ $this, &$saveOptions ] ) ) {
			return;
		}

		$userId = $this->getId();

		$insert_rows = []; // all the new preference rows
		foreach ( $saveOptions as $key => $value ) {
			// Don't bother storing default values
			$defaultOption = self::getDefaultOption( $key );
			if ( ( $defaultOption === null && $value !== false && $value !== null )
				|| $value != $defaultOption
			) {
				$insert_rows[] = [
					'up_user' => $userId,
					'up_property' => $key,
					'up_value' => $value,
				];
			}
		}

		$dbw = wfGetDB( DB_MASTER );

		$res = $dbw->select( 'user_properties',
			[ 'up_property', 'up_value' ], [ 'up_user' => $userId ], __METHOD__ );

		// Find prior rows that need to be removed or updated. These rows will
		// all be deleted (the latter so that INSERT IGNORE applies the new values).
		$keysDelete = [];
		foreach ( $res as $row ) {
			if ( !isset( $saveOptions[$row->up_property] )
				|| strcmp( $saveOptions[$row->up_property], $row->up_value ) != 0
			) {
				$keysDelete[] = $row->up_property;
			}
		}

		if ( count( $keysDelete ) ) {
			// Do the DELETE by PRIMARY KEY for prior rows.
			// In the past a very large portion of calls to this function are for setting
			// 'rememberpassword' for new accounts (a preference that has since been removed).
			// Doing a blanket per-user DELETE for new accounts with no rows in the table
			// caused gap locks on [max user ID,+infinity) which caused high contention since
			// updates would pile up on each other as they are for higher (newer) user IDs.
			// It might not be necessary these days, but it shouldn't hurt either.
			$dbw->delete( 'user_properties',
				[ 'up_user' => $userId, 'up_property' => $keysDelete ], __METHOD__ );
		}
		// Insert the new preference rows
		$dbw->insert( 'user_properties', $insert_rows, __METHOD__, [ 'IGNORE' ] );
	}

	/**
	 * Lazily instantiate and return a factory object for making passwords
	 *
	 * @deprecated since 1.27, create a PasswordFactory directly instead
	 * @return PasswordFactory
	 */
	public static function getPasswordFactory() {
		wfDeprecated( __METHOD__, '1.27' );
		$ret = new PasswordFactory();
		$ret->init( RequestContext::getMain()->getConfig() );
		return $ret;
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
	 * @deprecated since 1.27
	 * @return array Array of HTML attributes suitable for feeding to
	 *   Html::element(), directly or indirectly.  (Don't feed to Xml::*()!
	 *   That will get confused by the boolean attribute syntax used.)
	 */
	public static function passwordChangeInputAttribs() {
		global $wgMinimalPasswordLength;

		if ( $wgMinimalPasswordLength == 0 ) {
			return [];
		}

		# Note that the pattern requirement will always be satisfied if the
		# input is empty, so we need required in all cases.

		# @todo FIXME: Bug 23769: This needs to not claim the password is required
		# if e-mail confirmation is being used.  Since HTML5 input validation
		# is b0rked anyway in some browsers, just return nothing.  When it's
		# re-enabled, fix this code to not output required for e-mail
		# registration.
		# $ret = array( 'required' );
		$ret = [];

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
		return [
			'user_id',
			'user_name',
			'user_real_name',
			'user_email',
			'user_touched',
			'user_token',
			'user_email_authenticated',
			'user_email_token',
			'user_email_token_expires',
			'user_registration',
			'user_editcount',
		];
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
			[ 'User', 'makeGroupLinkWiki' ],
			User::getGroupsWithPermission( $permission )
		);

		if ( $groups ) {
			return Status::newFatal( 'badaccess-groups', $wgLang->commaList( $groups ), count( $groups ) );
		} else {
			return Status::newFatal( 'badaccess-group0' );
		}
	}

	/**
	 * Get a new instance of this user that was loaded from the master via a locking read
	 *
	 * Use this instead of the main context User when updating that user. This avoids races
	 * where that user was loaded from a replica DB or even the master but without proper locks.
	 *
	 * @return User|null Returns null if the user was not found in the DB
	 * @since 1.27
	 */
	public function getInstanceForUpdate() {
		if ( !$this->getId() ) {
			return null; // anon
		}

		$user = self::newFromId( $this->getId() );
		if ( !$user->loadFromId( self::READ_EXCLUSIVE ) ) {
			return null;
		}

		return $user;
	}

	/**
	 * Checks if two user objects point to the same user.
	 *
	 * @since 1.25
	 * @param User $user
	 * @return bool
	 */
	public function equals( User $user ) {
		return $this->getName() === $user->getName();
	}
}
