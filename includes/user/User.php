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

use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\Block;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\SystemBlock;
use MediaWiki\DAO\WikiAwareEntityTrait;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Mail\UserEmailContact;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Permissions\RateLimitSubject;
use MediaWiki\Permissions\UserAuthority;
use MediaWiki\Session\SessionManager;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserRigorOptions;
use Wikimedia\Assert\Assert;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DBExpectedError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\ScopedCallback;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * The User object encapsulates all of the user-specific settings (user_id,
 * name, rights, email address, options, last login time). Client
 * classes use the getXXX() functions to access these fields. These functions
 * do all the work of determining whether the user is logged in,
 * whether the requested option can be satisfied from cookies or
 * whether a database query is needed. Most of the settings needed
 * for rendering normal pages are set in the cookie to minimize use
 * of the database.
 *
 * @note User implements Authority to ease transition. Always prefer
 * using existing Authority or obtaining a proper Authority implementation.
 *
 * @newable in 1.35 only, the constructor is @internal since 1.36
 */
#[AllowDynamicProperties]
class User implements Authority, UserIdentity, UserEmailContact {
	use ProtectedHookAccessorTrait;
	use WikiAwareEntityTrait;

	/**
	 * @var int
	 * @see IDBAccessObject::READ_EXCLUSIVE
	 */
	public const READ_EXCLUSIVE = IDBAccessObject::READ_EXCLUSIVE;

	/**
	 * @var int
	 * @see IDBAccessObject::READ_LOCKING
	 */
	public const READ_LOCKING = IDBAccessObject::READ_LOCKING;

	/**
	 * Number of characters required for the user_token field.
	 */
	public const TOKEN_LENGTH = 32;

	/**
	 * An invalid string value for the user_token field.
	 */
	public const INVALID_TOKEN = '*** INVALID ***';

	/**
	 * Version number to tag cached versions of serialized User objects. Should be increased when
	 * {@link $mCacheVars} or one of its members changes.
	 */
	private const VERSION = 17;

	/**
	 * @since 1.27
	 */
	public const CHECK_USER_RIGHTS = true;

	/**
	 * @since 1.27
	 */
	public const IGNORE_USER_RIGHTS = false;

	/**
	 * Username used for various maintenance scripts.
	 * @since 1.37
	 */
	public const MAINTENANCE_SCRIPT_USER = 'Maintenance script';

	/**
	 * List of member variables which are saved to the
	 * shared cache (memcached). Any operation which changes the
	 * corresponding database fields must call a cache-clearing function.
	 * @showinitializer
	 * @var string[]
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
		// actor table
		'mActorId',
	];

	/** Cache variables */
	// Some of these are public, including for use by the UserFactory, but they generally
	// should not be set manually
	// @{
	/** @var int */
	public $mId;
	/** @var string */
	public $mName;
	/**
	 * Switched from protected to public for use in UserFactory
	 *
	 * @var int|null
	 */
	public $mActorId;
	/** @var string */
	public $mRealName;

	/** @var string */
	public $mEmail;
	/** @var string TS_MW timestamp from the DB */
	public $mTouched;
	/** @var string|null TS_MW timestamp from cache */
	protected $mQuickTouched;
	/** @var string|null */
	protected $mToken;
	/** @var string|null */
	public $mEmailAuthenticated;
	/** @var string|null */
	protected $mEmailToken;
	/** @var string|null */
	protected $mEmailTokenExpires;
	/** @var string|null */
	protected $mRegistration;
	// @}

	// @{
	/**
	 * @var array|bool Array with already loaded items or true if all items have been loaded.
	 */
	protected $mLoadedItems = [];
	// @}

	/**
	 * @var string Initialization data source if mLoadedItems!==true. May be one of:
	 *  - 'defaults'   anonymous user initialised from class defaults
	 *  - 'name'       initialise from mName
	 *  - 'id'         initialise from mId
	 *  - 'actor'      initialise from mActorId
	 *  - 'session'    log in from session if possible
	 *
	 * Use the User::newFrom*() family of functions to set this.
	 */
	public $mFrom;

	/**
	 * Lazy-initialized variables, invalidated with clearInstanceCache
	 */
	/** @var string|null */
	protected $mDatePreference;
	/**
	 * @var string|int -1 when the block is unset
	 */
	private $mBlockedby;
	/** @var string|false */
	protected $mHash;
	/**
	 * TODO: This should be removed when User::blockedFor
	 * and AbstractBlock::getReason are hard deprecated.
	 * @var string
	 */
	protected $mBlockreason;
	/** @var AbstractBlock */
	protected $mGlobalBlock;
	/** @var bool */
	protected $mLocked;
	/** @var bool */
	private $mHideName;

	/** @var WebRequest */
	private $mRequest;

	/** @var AbstractBlock|null */
	private $mBlock;

	/** @var AbstractBlock|bool */
	private $mBlockedFromCreateAccount = false;

	/** @var int User::READ_* constant bitfield used to load data */
	protected $queryFlagsUsed = self::READ_NORMAL;

	/** @var Authority|null lazy-initialized Authority of this user */
	private $mThisAsAuthority;

	/** @var bool|null */
	private $isTemp;

	/**
	 * Lightweight constructor for an anonymous user.
	 *
	 * @stable to call since 1.35
	 * @internal since 1.36, use the UserFactory service instead
	 *
	 * @see MediaWiki\User\UserFactory
	 *
	 * @see newFromName()
	 * @see newFromId()
	 * @see newFromActorId()
	 * @see newFromConfirmationCode()
	 * @see newFromSession()
	 * @see newFromRow()
	 */
	public function __construct() {
		$this->clearInstanceCache( 'defaults' );
	}

	/**
	 * Returns self::LOCAL to indicate the user is associated with the local wiki.
	 *
	 * @since 1.36
	 * @return string|false
	 */
	public function getWikiId() {
		return self::LOCAL;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->getName();
	}

	public function &__get( $name ) {
		// A shortcut for $mRights deprecation phase
		if ( $name === 'mRights' ) {
			$copy = MediaWikiServices::getInstance()
				->getPermissionManager()
				->getUserPermissions( $this );
			return $copy;
		} elseif ( $name === 'mOptions' ) {
			wfDeprecated( 'User::$mOptions', '1.35' );
			$options = MediaWikiServices::getInstance()->getUserOptionsLookup()->getOptions( $this );
			return $options;
		} elseif ( in_array( $name, [ 'mBlock', 'mBlockedby', 'mHideName' ] ) ) {
			// hard deprecated since 1.39
			wfDeprecated( "User::\$$name", '1.35' );
			$value = $this->$name;
			return $value;
		} elseif ( !property_exists( $this, $name ) ) {
			// T227688 - do not break $u->foo['bar'] = 1
			wfLogWarning( 'tried to get non-existent property' );
			$this->$name = null;
			return $this->$name;
		} else {
			wfLogWarning( 'tried to get non-visible property' );
			$null = null;
			return $null;
		}
	}

	public function __set( $name, $value ) {
		// A shortcut for $mRights deprecation phase, only known legitimate use was for
		// testing purposes, other uses seem bad in principle
		if ( $name === 'mRights' ) {
			MediaWikiServices::getInstance()->getPermissionManager()->overrideUserRightsForTesting(
				$this,
				$value ?? []
			);
		} elseif ( $name === 'mOptions' ) {
			wfDeprecated( 'User::$mOptions', '1.35' );
			$userOptionsManager = MediaWikiServices::getInstance()->getUserOptionsManager();
			$userOptionsManager->clearUserOptionsCache( $this );
			foreach ( $value as $key => $val ) {
				$userOptionsManager->setOption( $this, $key, $val );
			}
		} elseif ( in_array( $name, [ 'mBlock', 'mBlockedby', 'mHideName' ] ) ) {
			// hard deprecated since 1.39
			wfDeprecated( "User::\$$name", '1.35' );
			$this->$name = $value;
		} elseif ( !property_exists( $this, $name ) ) {
			$this->$name = $value;
		} else {
			wfLogWarning( 'tried to set non-visible property' );
		}
	}

	public function __sleep(): array {
		return array_diff(
			array_keys( get_object_vars( $this ) ),
			[
				'mThisAsAuthority' // memoization, will be recreated on demand.
			]
		);
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
	 * @param int $flags User::READ_* constant bitfield
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
			LoggerFactory::getInstance( 'session' )
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
			case 'id':
				// Make sure this thread sees its own changes, if the ID isn't 0
				if ( $this->mId != 0 ) {
					$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
					if ( $lb->hasOrMadeRecentPrimaryChanges() ) {
						$flags |= self::READ_LATEST;
						$this->queryFlagsUsed = $flags;
					}
				}

				$this->loadFromId( $flags );
				break;
			case 'actor':
			case 'name':
				// Make sure this thread sees its own changes
				$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
				if ( $lb->hasOrMadeRecentPrimaryChanges() ) {
					$flags |= self::READ_LATEST;
					$this->queryFlagsUsed = $flags;
				}

				[ $index, $options ] = DBAccessObjectUtils::getDBOptions( $flags );
				$row = wfGetDB( $index )->selectRow(
					'actor',
					[ 'actor_id', 'actor_user', 'actor_name' ],
					$this->mFrom === 'name'
						// make sure to use normalized form of IP for anonymous users
						? [ 'actor_name' => IPUtils::sanitizeIP( $this->mName ) ]
						: [ 'actor_id' => $this->mActorId ],
					__METHOD__,
					$options
				);

				if ( !$row ) {
					// Ugh.
					$this->loadDefaults( $this->mFrom === 'name' ? $this->mName : false );
				} elseif ( $row->actor_user ) {
					$this->mId = $row->actor_user;
					$this->loadFromId( $flags );
				} else {
					$this->loadDefaults( $row->actor_name, $row->actor_id );
				}
				break;
			case 'session':
				if ( !$this->loadFromSession() ) {
					// Loading from session failed. Load defaults.
					$this->loadDefaults();
				}
				$this->getHookRunner()->onUserLoadAfterLoadFromSession( $this );
				break;
			default:
				throw new UnexpectedValueException(
					"Unrecognised value for User->mFrom: \"{$this->mFrom}\"" );
		}
	}

	/**
	 * Load user table data, given mId has already been set.
	 * @param int $flags User::READ_* constant bitfield
	 * @return bool False if the ID does not exist, true otherwise
	 */
	public function loadFromId( $flags = self::READ_NORMAL ) {
		if ( $this->mId == 0 ) {
			// Anonymous users are not in the database (don't need cache)
			$this->loadDefaults();
			return false;
		}

		// Try cache (unless this needs data from the primary DB).
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
	 * @param string $dbDomain
	 * @param int $userId
	 */
	public static function purge( $dbDomain, $userId ) {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$key = $cache->makeGlobalKey( 'user', 'id', $dbDomain, $userId );
		$cache->delete( $key );
	}

	/**
	 * @since 1.27
	 * @param WANObjectCache $cache
	 * @return string
	 */
	protected function getCacheKey( WANObjectCache $cache ) {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();

		return $cache->makeGlobalKey( 'user', 'id', $lbFactory->getLocalDomainID(), $this->mId );
	}

	/**
	 * @param WANObjectCache $cache
	 * @return string[]
	 * @since 1.28
	 */
	public function getMutableCacheKeys( WANObjectCache $cache ) {
		$id = $this->getId();

		return $id ? [ $this->getCacheKey( $cache ) ] : [];
	}

	/**
	 * Load user data from shared cache, given mId has already been set.
	 *
	 * @return bool True
	 * @since 1.25
	 */
	protected function loadFromCache() {
		global $wgFullyInitialised;

		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$data = $cache->getWithSetCallback(
			$this->getCacheKey( $cache ),
			$cache::TTL_HOUR,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $cache, $wgFullyInitialised ) {
				$setOpts += Database::getCacheSetOptions( wfGetDB( DB_REPLICA ) );
				wfDebug( "User: cache miss for user {$this->mId}" );

				$this->loadFromDatabase( self::READ_NORMAL );

				$data = [];
				foreach ( self::$mCacheVars as $name ) {
					$data[$name] = $this->$name;
				}

				$ttl = $cache->adaptiveTTL( (int)wfTimestamp( TS_UNIX, $this->mTouched ), $ttl );

				if ( $wgFullyInitialised ) {
					$groupMemberships = MediaWikiServices::getInstance()
						->getUserGroupManager()
						->getUserGroupMemberships( $this, $this->queryFlagsUsed );

					// if a user group membership is about to expire, the cache needs to
					// expire at that time (T163691)
					foreach ( $groupMemberships as $ugm ) {
						if ( $ugm->getExpiry() ) {
							$secondsUntilExpiry =
								(int)wfTimestamp( TS_UNIX, $ugm->getExpiry() ) - time();

							if ( $secondsUntilExpiry > 0 && $secondsUntilExpiry < $ttl ) {
								$ttl = $secondsUntilExpiry;
							}
						}
					}
				}

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

	/***************************************************************************/
	// region   newFrom*() static factory methods
	/** @name   newFrom*() static factory methods
	 * @{
	 */

	/**
	 * @see UserFactory::newFromName
	 *
	 * @deprecated since 1.36, use a UserFactory instead
	 *
	 * This is slightly less efficient than newFromId(), so use newFromId() if
	 * you have both an ID and a name handy.
	 *
	 * @param string $name Username, validated by Title::newFromText()
	 * @param string|bool $validate Validate username.Type of validation to use:
	 *   - false        No validation
	 *   - 'valid'      Valid for batch processes
	 *   - 'usable'     Valid for batch processes and login
	 *   - 'creatable'  Valid for batch processes, login and account creation,
	 *  except that true is accepted as an alias for 'valid', for BC.
	 *
	 * @return User|bool User object, or false if the username is invalid
	 *  (e.g. if it contains illegal characters or is an IP address). If the
	 *  username is not present in the database, the result will be a user object
	 *  with a name, zero user ID and default settings.
	 */
	public static function newFromName( $name, $validate = 'valid' ) {
		// Backwards compatibility with strings / false
		$validationLevels = [
			'valid' => UserRigorOptions::RIGOR_VALID,
			'usable' => UserRigorOptions::RIGOR_USABLE,
			'creatable' => UserRigorOptions::RIGOR_CREATABLE
		];
		if ( $validate === true ) {
			$validate = 'valid';
		}
		if ( $validate === false ) {
			$validation = UserRigorOptions::RIGOR_NONE;
		} elseif ( array_key_exists( $validate, $validationLevels ) ) {
			$validation = $validationLevels[ $validate ];
		} else {
			// Not a recognized value, probably a test for unsupported validation
			// levels, regardless, just pass it along
			$validation = $validate;
		}

		$user = MediaWikiServices::getInstance()
			->getUserFactory()
			->newFromName( (string)$name, $validation );

		// UserFactory returns null instead of false
		if ( $user === null ) {
			$user = false;
		}
		return $user;
	}

	/**
	 * Static factory method for creation from a given user ID.
	 *
	 * @see UserFactory::newFromId
	 *
	 * @deprecated since 1.36, use a UserFactory instead
	 *
	 * @param int $id Valid user ID
	 * @return User
	 */
	public static function newFromId( $id ) {
		return MediaWikiServices::getInstance()
			->getUserFactory()
			->newFromId( (int)$id );
	}

	/**
	 * Static factory method for creation from a given actor ID.
	 *
	 * @see UserFactory::newFromActorId
	 *
	 * @deprecated since 1.36, use a UserFactory instead
	 *
	 * @since 1.31
	 * @param int $id Valid actor ID
	 * @return User
	 */
	public static function newFromActorId( $id ) {
		return MediaWikiServices::getInstance()
			->getUserFactory()
			->newFromActorId( (int)$id );
	}

	/**
	 * Returns a User object corresponding to the given UserIdentity.
	 *
	 * @see UserFactory::newFromUserIdentity
	 *
	 * @deprecated since 1.36, use a UserFactory instead
	 *
	 * @since 1.32
	 *
	 * @param UserIdentity $identity
	 *
	 * @return User
	 */
	public static function newFromIdentity( UserIdentity $identity ) {
		// Don't use the service if we already have a User object,
		// so that User::newFromIdentity calls don't break things in unit tests.
		if ( $identity instanceof User ) {
			return $identity;
		}

		return MediaWikiServices::getInstance()
			->getUserFactory()
			->newFromUserIdentity( $identity );
	}

	/**
	 * Static factory method for creation from an ID, name, and/or actor ID
	 *
	 * This does not check that the ID, name, and actor ID all correspond to
	 * the same user.
	 *
	 * @see UserFactory::newFromAnyId
	 *
	 * @deprecated since 1.36, use a UserFactory instead
	 *
	 * @since 1.31
	 * @param int|null $userId User ID, if known
	 * @param string|null $userName User name, if known
	 * @param int|null $actorId Actor ID, if known
	 * @param bool|string $dbDomain remote wiki to which the User/Actor ID applies, or false if none
	 * @return User
	 */
	public static function newFromAnyId( $userId, $userName, $actorId, $dbDomain = false ) {
		return MediaWikiServices::getInstance()
			->getUserFactory()
			->newFromAnyId( $userId, $userName, $actorId, $dbDomain );
	}

	/**
	 * Factory method to fetch whichever user has a given email confirmation code.
	 * This code is generated when an account is created or its e-mail address
	 * has changed.
	 *
	 * If the code is invalid or has expired, returns NULL.
	 *
	 * @see UserFactory::newFromConfirmationCode
	 *
	 * @deprecated since 1.36, use a UserFactory instead
	 *
	 * @param string $code Confirmation code
	 * @param int $flags User::READ_* bitfield
	 * @return User|null
	 */
	public static function newFromConfirmationCode( $code, $flags = self::READ_NORMAL ) {
		return MediaWikiServices::getInstance()
			->getUserFactory()
			->newFromConfirmationCode( (string)$code, $flags );
	}

	/**
	 * Create a new user object using data from session. If the login
	 * credentials are invalid, the result is an anonymous user.
	 *
	 * @param WebRequest|null $request Object to use; the global request will be used if omitted.
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
	 * @param array|null $data Further data to load into the object
	 *  (see User::loadFromRow for valid keys)
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
	 *  - validate: Type of validation to use:
	 *    - false        No validation
	 *    - 'valid'      Valid for batch processes
	 *    - 'usable'     Valid for batch processes and login
	 *    - 'creatable'  Valid for batch processes, login and account creation,
	 *    default 'valid'. Deprecated since 1.36.
	 *  - create: Whether to create the user if it doesn't already exist, default true
	 *  - steal: Whether to "disable" the account for normal use if it already
	 *    exists, default false
	 * @return User|null
	 * @since 1.27
	 */
	public static function newSystemUser( $name, $options = [] ) {
		$options += [
			'validate' => UserRigorOptions::RIGOR_VALID,
			'create' => true,
			'steal' => false,
		];

		// Username validation
		// Backwards compatibility with strings / false
		$validationLevels = [
			'valid' => UserRigorOptions::RIGOR_VALID,
			'usable' => UserRigorOptions::RIGOR_USABLE,
			'creatable' => UserRigorOptions::RIGOR_CREATABLE
		];
		$validate = $options['validate'];

		// @phan-suppress-next-line PhanSuspiciousValueComparison
		if ( $validate === false ) {
			$validation = UserRigorOptions::RIGOR_NONE;
		} elseif ( array_key_exists( $validate, $validationLevels ) ) {
			$validation = $validationLevels[ $validate ];
		} else {
			// Not a recognized value, probably a test for unsupported validation
			// levels, regardless, just pass it along
			$validation = $validate;
		}

		if ( $validation !== UserRigorOptions::RIGOR_VALID ) {
			wfDeprecatedMsg(
				__METHOD__ . ' options["validation"] parameter must be omitted or set to "valid".',
				'1.36'
			);
		}
		$services = MediaWikiServices::getInstance();
		$userNameUtils = $services->getUserNameUtils();

		$name = $userNameUtils->getCanonical( (string)$name, $validation );
		if ( $name === false ) {
			return null;
		}

		$loadBalancer = $services->getDBLoadBalancer();
		$dbr = $loadBalancer->getConnectionRef( DB_REPLICA );

		$userQuery = self::getQueryInfo();
		$row = $dbr->selectRow(
			$userQuery['tables'],
			$userQuery['fields'],
			[ 'user_name' => $name ],
			__METHOD__,
			[],
			$userQuery['joins']
		);
		if ( !$row ) {
			// Try the primary database...
			$dbw = $loadBalancer->getConnectionRef( DB_PRIMARY );
			$row = $dbw->selectRow(
				$userQuery['tables'],
				$userQuery['fields'],
				[ 'user_name' => $name ],
				__METHOD__,
				[],
				$userQuery['joins']
			);
		}

		if ( !$row ) {
			// No user. Create it?
			if ( !$options['create'] ) {
				// No.
				return null;
			}

			// If it's a reserved user that had an anonymous actor created for it at
			// some point, we need special handling.
			return self::insertNewUser( static function ( UserIdentity $actor, IDatabase $dbw ) {
				return MediaWikiServices::getInstance()->getActorStore()->acquireSystemActorId( $actor, $dbw );
			}, $name, [ 'token' => self::INVALID_TOKEN ] );
		}

		$user = self::newFromRow( $row );

		if ( !$user->isSystemUser() ) {
			// User exists. Steal it?
			if ( !$options['steal'] ) {
				return null;
			}

			$services->getAuthManager()->revokeAccessForUser( $name );

			$user->invalidateEmail();
			$user->mToken = self::INVALID_TOKEN;
			$user->saveSettings();
			SessionManager::singleton()->preventSessionsForUser( $user->getName() );
		}

		return $user;
	}

	/** @} */
	// endregion -- end of newFrom*() static factory methods

	/**
	 * Get the username corresponding to a given user ID
	 * @param int $id User ID
	 * @return string|false The corresponding username
	 */
	public static function whoIs( $id ) {
		return UserCache::singleton()->getProp( $id, 'name' );
	}

	/**
	 * Get the real name of a user given their user ID
	 *
	 * @param int $id User ID
	 * @return string|false The corresponding user's real name
	 */
	public static function whoIsReal( $id ) {
		return UserCache::singleton()->getProp( $id, 'real_name' );
	}

	/**
	 * Get database id given a user name
	 * @deprecated since 1.37. Use UserIdentityLookup::getUserIdentityByName instead.
	 * @param string $name Username
	 * @param int $flags User::READ_* constant bitfield
	 * @return int|null The corresponding user's ID, or null if user is nonexistent
	 */
	public static function idFromName( $name, $flags = self::READ_NORMAL ) {
		$actor = MediaWikiServices::getInstance()
			->getUserIdentityLookup()
			->getUserIdentityByName( (string)$name, $flags );
		if ( $actor && $actor->getId() ) {
			return $actor->getId();
		}
		return null;
	}

	/**
	 * Return the users who are members of the given group(s). In case of multiple groups,
	 * users who are members of at least one of them are returned.
	 *
	 * @param string|array $groups A single group name or an array of group names
	 * @param int $limit Max number of users to return. The actual limit will never exceed 5000
	 *   records; larger values are ignored.
	 * @param int|null $after ID the user to start after
	 * @return UserArrayFromResult|ArrayIterator
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
	 * Is the input a valid password for this user?
	 *
	 * @param string $password Desired password
	 * @return bool
	 */
	public function isValidPassword( $password ) {
		// simple boolean wrapper for checkPasswordValidity
		return $this->checkPasswordValidity( $password )->isGood();
	}

	/**
	 * Check if this is a valid password for this user
	 *
	 * Returns a Status object with a set of messages describing
	 * problems with the password. If the return status is fatal,
	 * the action should be refused and the password should not be
	 * checked at all (this is mainly meant for DoS mitigation).
	 * If the return value is OK but not good, the password can be checked,
	 * but the user should not be able to set their password to this.
	 * The value of the returned Status object will be an array which
	 * can have the following fields:
	 * - forceChange (bool): if set to true, the user should not be
	 *   allowed to log with this password unless they change it during
	 *   the login process (see ResetPasswordSecondaryAuthenticationProvider).
	 * - suggestChangeOnLogin (bool): if set to true, the user should be prompted for
	 *   a password change on login.
	 *
	 * @param string $password Desired password
	 * @return Status
	 * @since 1.23
	 */
	public function checkPasswordValidity( $password ) {
		$passwordPolicy = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::PasswordPolicy );

		$upp = new UserPasswordPolicy(
			$passwordPolicy['policies'],
			$passwordPolicy['checks']
		);

		$status = Status::newGood( [] );
		$result = false; // init $result to false for the internal checks

		if ( !$this->getHookRunner()->onIsValidPassword( $password, $result, $this ) ) {
			$status->error( $result );
			return $status;
		}

		if ( $result === false ) {
			$status->merge( $upp->checkUserPassword( $this, $password ), true );
			return $status;
		}

		if ( $result === true ) {
			return $status;
		}

		$status->error( $result );
		return $status; // the isValidPassword hook set a string $result and returned true
	}

	/**
	 * Set cached properties to default.
	 *
	 * @note This no longer clears uncached lazy-initialised properties;
	 *       the constructor does that instead.
	 *
	 * @param string|bool $name
	 * @param int|null $actorId
	 */
	public function loadDefaults( $name = false, $actorId = null ) {
		$this->mId = 0;
		$this->mName = $name;
		$this->mActorId = $actorId;
		$this->mRealName = '';
		$this->mEmail = '';

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

		$this->getHookRunner()->onUserLoadDefaults( $this, $name );
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
	 * @internal Only public for use in UserFactory
	 *
	 * @param string $item
	 */
	public function setItemLoaded( $item ) {
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
		// MediaWiki\Session\Session already did the necessary authentication of the user
		// returned here, so just use it if applicable.
		$session = $this->getRequest()->getSession();
		$user = $session->getUser();
		if ( $user->isRegistered() ) {
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
	 * Load user data from the database.
	 * $this->mId must be set, this is how the user is identified.
	 *
	 * @param int $flags User::READ_* constant bitfield
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

		[ $index, $options ] = DBAccessObjectUtils::getDBOptions( $flags );
		$db = wfGetDB( $index );

		$userQuery = self::getQueryInfo();
		$s = $db->selectRow(
			$userQuery['tables'],
			$userQuery['fields'],
			[ 'user_id' => $this->mId ],
			__METHOD__,
			$options,
			$userQuery['joins']
		);

		$this->queryFlagsUsed = $flags;

		// hook is hard deprecated since 1.37
		$this->getHookRunner()->onUserLoadFromDatabase( $this, $s );

		if ( $s !== false ) {
			// Initialise user table data
			$this->loadFromRow( $s );
			return true;
		}

		// Invalid user_id
		$this->mId = 0;
		$this->loadDefaults( 'Unknown user' );

		return false;
	}

	/**
	 * Initialize this object from a row from the user table.
	 *
	 * @param stdClass $row Row from the user table to load.
	 * @param array|null $data Further user data to load into the object
	 *
	 *  user_groups   Array of arrays or stdClass result rows out of the user_groups
	 *                table. Previously you were supposed to pass an array of strings
	 *                here, but we also need expiry info nowadays, so an array of
	 *                strings is ignored.
	 */
	protected function loadFromRow( $row, $data = null ) {
		if ( !is_object( $row ) ) {
			throw new InvalidArgumentException( '$row must be an object' );
		}

		$all = true;

		if ( isset( $row->actor_id ) ) {
			$this->mActorId = (int)$row->actor_id;
			if ( $this->mActorId !== 0 ) {
				$this->mFrom = 'actor';
			}
			$this->setItemLoaded( 'actor' );
		} else {
			$all = false;
		}

		if ( isset( $row->user_name ) && $row->user_name !== '' ) {
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
			if ( $this->mId !== 0 ) {
				$this->mFrom = 'id';
			}
			$this->setItemLoaded( 'id' );
		} else {
			$all = false;
		}

		if ( isset( $row->user_editcount ) ) {
			// Don't try to set edit count for anonymous users
			// We check the id here and not in UserEditTracker because calling
			// User::getId() can trigger some other loading. This will result in
			// discarding the user_editcount field for rows if the id wasn't set.
			if ( $this->mId !== null && $this->mId !== 0 ) {
				MediaWikiServices::getInstance()
					->getUserEditTracker()
					->setCachedUserEditCount( $this, (int)$row->user_editcount );
			}
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
				MediaWikiServices::getInstance()
					->getUserGroupManager()
					->loadGroupMembershipsFromArray(
						$this,
						$data['user_groups'],
						$this->queryFlagsUsed
					);
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
	 * Builds update conditions. Additional conditions may be added to $conditions to
	 * protected against race conditions using a compare-and-set (CAS) mechanism
	 * based on comparing $this->mTouched with the user_touched field.
	 *
	 * @param IDatabase $db
	 * @param array $conditions WHERE conditions for use with Database::update
	 * @return array WHERE conditions for use with Database::update
	 */
	protected function makeUpdateConditions( IDatabase $db, array $conditions ) {
		if ( $this->mTouched ) {
			// CAS check: only update if the row wasn't changed since it was loaded.
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
	 * @internal
	 * @return bool Whether user_touched was actually updated
	 * @since 1.26
	 */
	public function checkAndSetTouched() {
		$this->load();

		if ( !$this->mId ) {
			return false; // anon
		}

		// Get a new user_touched that is higher than the old one
		$newTouched = $this->newTouchedTimestamp();

		$dbw = wfGetDB( DB_PRIMARY );
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
			$this->clearSharedCache( 'changed' );
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
	 *   given source. May be "name", "id", "actor", "defaults", "session", or false for no reload.
	 */
	public function clearInstanceCache( $reloadFrom = false ) {
		global $wgFullyInitialised;

		$this->mDatePreference = null;
		$this->mBlockedby = -1; # Unset
		$this->mHash = false;
		$this->mThisAsAuthority = null;

		if ( $wgFullyInitialised && $this->mFrom ) {
			$services = MediaWikiServices::getInstance();
			$services->getPermissionManager()->invalidateUsersRightsCache( $this );
			$services->getUserOptionsManager()->clearUserOptionsCache( $this );
			$services->getTalkPageNotificationManager()->clearInstanceCache( $this );
			$services->getUserGroupManager()->clearCache( $this );
			$services->getUserEditTracker()->clearUserEditCache( $this );
		}

		if ( $reloadFrom ) {
			$this->mLoadedItems = [];
			$this->mFrom = $reloadFrom;
		}
	}

	/**
	 * Get blocking information
	 *
	 * TODO: Move this into the BlockManager, along with block-related properties.
	 *
	 * @param bool $fromReplica Whether to check the replica DB first.
	 *   To improve performance, non-critical checks are done against replica DBs.
	 *   Check when actually saving should be done against primary DB.
	 * @param bool $disableIpBlockExemptChecking This is used internally to prevent
	 *   a infinite recursion with autopromote. See T270145.
	 */
	private function getBlockedStatus( $fromReplica = true, $disableIpBlockExemptChecking = false ) {
		if ( $this->mBlockedby != -1 ) {
			return;
		}

		wfDebug( __METHOD__ . ": checking blocked status for " . $this->getName() );

		// Initialize data...
		// Otherwise something ends up stomping on $this->mBlockedby when
		// things get lazy-loaded later, causing false positive block hits
		// due to -1 !== 0. Probably session-related... Nothing should be
		// overwriting mBlockedby, surely?
		$this->load();

		// TODO: Block checking shouldn't really be done from the User object. Block
		// checking can involve checking for IP blocks, cookie blocks, and/or XFF blocks,
		// which need more knowledge of the request context than the User should have.
		// Since we do currently check blocks from the User, we have to do the following
		// here:
		// - Check if this is the user associated with the main request
		// - If so, pass the relevant request information to the block manager
		$request = null;
		if ( $this->isGlobalSessionUser() ) {
			// This is the global user, so we need to pass the request
			$request = $this->getRequest();
		}

		$block = MediaWikiServices::getInstance()->getBlockManager()->getUserBlock(
			$this,
			$request,
			$fromReplica,
			$disableIpBlockExemptChecking
		);

		if ( $block ) {
			$this->mBlock = $block;
			$this->mBlockedby = $block->getByName();
			$this->mBlockreason = $block->getReason();
			$this->mHideName = $block->getHideName();
		} else {
			$this->mBlock = null;
			$this->mBlockedby = '';
			$this->mBlockreason = '';
			$this->mHideName = false;
		}
	}

	/**
	 * Is this user subject to rate limiting?
	 *
	 * @return bool True if rate limited
	 */
	public function isPingLimitable() {
		$limiter = MediaWikiServices::getInstance()->getRateLimiter();
		$subject = $this->toRateLimitSubject();
		return !$limiter->isExempt( $subject );
	}

	/**
	 * Primitive rate limits: enforce maximum actions per time period
	 * to put a brake on flooding.
	 *
	 * The method generates both a generic profiling point and a per action one
	 * (suffix being "-$action").
	 *
	 * @note When using a shared cache like memcached, IP-address
	 * last-hit counters will be shared across wikis.
	 *
	 * @param string $action Action to enforce; 'edit' if unspecified
	 * @param int $incrBy Positive amount to increment counter by [defaults to 1]
	 *
	 * @return bool True if a rate limiter was tripped
	 * @throws MWException
	 */
	public function pingLimiter( $action = 'edit', $incrBy = 1 ) {
		$limiter = MediaWikiServices::getInstance()->getRateLimiter();
		$subject = $this->toRateLimitSubject();
		return $limiter->limit( $subject, $action, $incrBy );
	}

	private function toRateLimitSubject(): RateLimitSubject {
		$flags = [
			'exempt' => $this->isAllowed( 'noratelimit' ),
			'newbie' => $this->isNewbie(),
		];
		return new RateLimitSubject( $this, $this->getRequest()->getIP(), $flags );
	}

	/**
	 * Check if user is blocked
	 *
	 * @deprecated since 1.34, use User::getBlock() or
	 *             Authority:getBlock() or Authority:definitelyCan() or
	 *             Authority:authorizeRead() or Authority:authorizeWrite() or
	 *             PermissionManager::isBlockedFrom(), as appropriate.
	 *
	 * @param bool $fromReplica Whether to check the replica DB instead of
	 *   the primary DB. Hacked from false due to horrible probs on site.
	 * @return bool True if blocked, false otherwise
	 */
	public function isBlocked( $fromReplica = true ) {
		return $this->getBlock( $fromReplica ) !== null;
	}

	/**
	 * Get the block affecting the user, or null if the user is not blocked
	 *
	 * @param int|bool $freshness One of the Authority::READ_XXX constants.
	 *                 For backwards compatibility, a boolean is also accepted,
	 *                 with true meaning READ_NORMAL and false meaning
	 *                 READ_LATEST.
	 * @param bool $disableIpBlockExemptChecking This is used internally to prevent
	 *   a infinite recursion with autopromote. See T270145.
	 *
	 * @return ?AbstractBlock
	 */
	public function getBlock(
		$freshness = self::READ_NORMAL,
		$disableIpBlockExemptChecking = false
	): ?Block {
		if ( is_bool( $freshness ) ) {
			$fromReplica = $freshness;
		} else {
			$fromReplica = ( $freshness !== self::READ_LATEST );
		}

		$this->getBlockedStatus( $fromReplica, $disableIpBlockExemptChecking );
		return $this->mBlock instanceof AbstractBlock ? $this->mBlock : null;
	}

	/**
	 * Check if user is blocked from editing a particular article
	 *
	 * @param PageIdentity $title Title to check
	 * @param bool $fromReplica Whether to check the replica DB instead of the primary DB
	 * @return bool
	 *
	 * @deprecated since 1.33,
	 * use MediaWikiServices::getInstance()->getPermissionManager()->isBlockedFrom(..)
	 *
	 */
	public function isBlockedFrom( $title, $fromReplica = false ) {
		return MediaWikiServices::getInstance()->getPermissionManager()
			->isBlockedFrom( $this, $title, $fromReplica );
	}

	/**
	 * If user is blocked, return the name of the user who placed the block
	 * @return string Name of blocker
	 * @deprecated since 1.38
	 * Hard deprecated since 1.38.
	 */
	public function blockedBy() {
		wfDeprecated( __METHOD__, '1.38' );
		$this->getBlockedStatus();
		return $this->mBlockedby;
	}

	/**
	 * If user is blocked, return the specified reason for the block.
	 *
	 * @deprecated since 1.35 Use AbstractBlock::getReasonComment instead
	 * Hard deprecated since 1.39.
	 * @return string Blocking reason
	 */
	public function blockedFor() {
		wfDeprecated( __METHOD__, '1.35' );
		$this->getBlockedStatus();
		return $this->mBlockreason;
	}

	/**
	 * If user is blocked, return the ID for the block
	 * @return int|false
	 * @deprecated since 1.38
	 * Hard deprecated since 1.38.
	 */
	public function getBlockId() {
		wfDeprecated( __METHOD__, '1.38' );
		$this->getBlockedStatus();
		// @phan-suppress-next-line PhanTypeMismatchReturnNullable getId does not return null here
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
		return $this->getGlobalBlock( $ip ) instanceof AbstractBlock;
	}

	/**
	 * Check if user is blocked on all wikis.
	 * Do not use for actual edit permission checks!
	 * This is intended for quick UI checks.
	 *
	 * @param string $ip IP address, uses current client if none given
	 * @return AbstractBlock|null Block object if blocked, null otherwise
	 * @throws FatalError
	 * @throws MWException
	 */
	public function getGlobalBlock( $ip = '' ) {
		if ( $this->mGlobalBlock !== null ) {
			return $this->mGlobalBlock ?: null;
		}
		// User is already an IP?
		if ( IPUtils::isIPAddress( $this->getName() ) ) {
			$ip = $this->getName();
		} elseif ( !$ip ) {
			$ip = $this->getRequest()->getIP();
		}
		$blocked = false;
		$block = null;
		$this->getHookRunner()->onUserIsBlockedGlobally( $this, $ip, $blocked, $block );

		if ( $blocked && $block === null ) {
			// back-compat: UserIsBlockedGlobally didn't have $block param first
			$block = new SystemBlock( [
				'address' => $ip,
				'systemBlock' => 'global-block'
			] );
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
		// Reset for hook
		$this->mLocked = false;
		$this->getHookRunner()->onUserIsLocked( $this, $this->mLocked );
		return $this->mLocked;
	}

	/**
	 * Check if user account is hidden
	 *
	 * @return bool True if hidden, false otherwise
	 */
	public function isHidden() {
		if ( $this->mHideName !== null ) {
			return (bool)$this->mHideName;
		}
		$this->getBlockedStatus();
		return (bool)$this->mHideName;
	}

	/**
	 * Get the user's ID.
	 * @param string|false $wikiId The wiki ID expected by the caller.
	 * @return int The user's ID; 0 if the user is anonymous or nonexistent
	 */
	public function getId( $wikiId = self::LOCAL ): int {
		$this->assertWiki( $wikiId );
		if ( $this->mId === null && $this->mName !== null ) {
			$userNameUtils = MediaWikiServices::getInstance()->getUserNameUtils();
			if ( $userNameUtils->isIP( $this->mName ) || ExternalUserNames::isExternal( $this->mName ) ) {
				// Special case, we know the user is anonymous
				// Note that "external" users are "local" (they have an actor ID that is relative to
				// the local wiki).
				return 0;
			}
		}

		if ( !$this->isItemLoaded( 'id' ) ) {
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
	public function getName(): string {
		if ( $this->isItemLoaded( 'name', 'only' ) ) {
			// Special case optimisation
			return $this->mName;
		}

		$this->load();
		if ( $this->mName === false ) {
			// Clean up IPs
			$this->mName = IPUtils::sanitizeIP( $this->getRequest()->getIP() );
		}

		return $this->mName;
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
	 * Get the user's actor ID.
	 * @since 1.31
	 * @note This method was removed from the UserIdentity interface in 1.36,
	 *       but remains supported in the User class for now.
	 *       New code should use ActorNormalization::findActorId() or
	 *       ActorNormalization::acquireActorId() instead.
	 * @param IDatabase|string|false $dbwOrWikiId Deprecated since 1.36.
	 *        If a database connection is passed, a new actor ID is assigned if needed.
	 *        ActorNormalization::acquireActorId() should be used for that purpose instead.
	 * @return int The actor's ID, or 0 if no actor ID exists and $dbw was null
	 * @throws PreconditionException if $dbwOrWikiId is a string and does not match the local wiki
	 */
	public function getActorId( $dbwOrWikiId = self::LOCAL ): int {
		if ( $dbwOrWikiId ) {
			wfDeprecatedMsg( 'Passing a parameter to getActorId() is deprecated', '1.36' );
		}

		if ( is_string( $dbwOrWikiId ) ) {
			$this->assertWiki( $dbwOrWikiId );
		}

		if ( !$this->isItemLoaded( 'actor' ) ) {
			$this->load();
		}

		if ( !$this->mActorId && $dbwOrWikiId instanceof IDatabase ) {
			MediaWikiServices::getInstance()
				->getActorStoreFactory()
				->getActorNormalization( $dbwOrWikiId->getDomainID() )
				->acquireActorId( $this, $dbwOrWikiId );
			// acquireActorId will call setActorId on $this
			Assert::postcondition(
				$this->mActorId !== null,
				"Failed to acquire actor ID for user id {$this->mId} name {$this->mName}"
			);
		}

		return (int)$this->mActorId;
	}

	/**
	 * Sets the actor id.
	 * For use by ActorStore only.
	 * Should be removed once callers of getActorId() have been migrated to using ActorNormalization.
	 *
	 * @internal
	 * @deprecated since 1.36
	 * @param int $actorId
	 */
	public function setActorId( int $actorId ) {
		$this->mActorId = $actorId;
		$this->setItemLoaded( 'actor' );
	}

	/**
	 * Get the user's name escaped by underscores.
	 * @return string Username escaped by underscores.
	 */
	public function getTitleKey() {
		return str_replace( ' ', '_', $this->getName() );
	}

	/**
	 * Generate a current or new-future timestamp to be stored in the
	 * user_touched field when we update things.
	 *
	 * @return string Timestamp in TS_MW format
	 */
	private function newTouchedTimestamp() {
		$time = (int)ConvertibleTimestamp::now( TS_UNIX );
		if ( $this->mTouched ) {
			$time = max( $time, (int)ConvertibleTimestamp::convert( TS_UNIX, $this->mTouched ) + 1 );
		}

		return ConvertibleTimestamp::convert( TS_MW, $time );
	}

	/**
	 * Clear user data from memcached
	 *
	 * Use after applying updates to the database; caller's
	 * responsibility to update user_touched if appropriate.
	 *
	 * Called implicitly from invalidateCache() and saveSettings().
	 *
	 * @param string $mode Use 'refresh' to clear now or 'changed' to clear before DB commit
	 */
	public function clearSharedCache( $mode = 'refresh' ) {
		if ( !$this->getId() ) {
			return;
		}

		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$key = $this->getCacheKey( $cache );

		if ( $mode === 'refresh' ) {
			$cache->delete( $key, 1 ); // low tombstone/"hold-off" TTL
		} else {
			$lb->getConnectionRef( DB_PRIMARY )->onTransactionPreCommitOrIdle(
				static function () use ( $cache, $key ) {
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
		$this->clearSharedCache( 'changed' );
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
			$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
			$key = $cache->makeKey( 'user-quicktouched', 'id', $id );
			$cache->touchCheckKey( $key );
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
				$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
				$key = $cache->makeKey( 'user-quicktouched', 'id', $this->mId );

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
		$manager = MediaWikiServices::getInstance()->getAuthManager();
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
		$authenticationTokenVersion = MediaWikiServices::getInstance()
			->getMainConfig()->get( MainConfigNames::AuthenticationTokenVersion );

		$this->load();
		if ( !$this->mToken && $forceCreation ) {
			$this->setToken();
		}

		if ( !$this->mToken ) {
			// The user doesn't have a token, return null to indicate that.
			return null;
		}

		if ( $this->mToken === self::INVALID_TOKEN ) {
			// We return a random value here so existing token checks are very
			// likely to fail.
			return MWCryptRand::generateHex( self::TOKEN_LENGTH );
		}

		if ( $authenticationTokenVersion === null ) {
			// $wgAuthenticationTokenVersion not in use, so return the raw secret
			return $this->mToken;
		}

		// $wgAuthenticationTokenVersion in use, so hmac it.
		$ret = MWCryptHash::hmac( $authenticationTokenVersion, $this->mToken, false );

		// The raw hash can be overly long. Shorten it up.
		$len = max( 32, self::TOKEN_LENGTH );
		if ( strlen( $ret ) < $len ) {
			// Should never happen, even md5 is 128 bits
			throw new \UnexpectedValueException( 'Hmac returned less than 128 bits' );
		}

		return substr( $ret, -$len );
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
			LoggerFactory::getInstance( 'session' )
				->debug( __METHOD__ . ": Ignoring attempt to set token for system user \"$this\"" );
		} elseif ( !$token ) {
			$this->mToken = MWCryptRand::generateHex( self::TOKEN_LENGTH );
		} else {
			$this->mToken = $token;
		}
	}

	/**
	 * Get the user's e-mail address
	 * @return string User's email address
	 */
	public function getEmail(): string {
		$this->load();
		$email = $this->mEmail;
		$this->getHookRunner()->onUserGetEmail( $this, $email );
		// In case a hook handler returns e.g. null
		$this->mEmail = is_string( $email ) ? $email : '';
		return $this->mEmail;
	}

	/**
	 * Get the timestamp of the user's e-mail authentication
	 * @return string TS_MW timestamp
	 */
	public function getEmailAuthenticationTimestamp() {
		$this->load();
		$this->getHookRunner()->onUserGetEmailAuthenticationTimestamp(
			$this, $this->mEmailAuthenticated );
		return $this->mEmailAuthenticated;
	}

	/**
	 * Set the user's e-mail address
	 * @param string $str New e-mail address
	 */
	public function setEmail( string $str ) {
		$this->load();
		if ( $str == $this->getEmail() ) {
			return;
		}
		$this->invalidateEmail();
		$this->mEmail = $str;
		$this->getHookRunner()->onUserSetEmail( $this, $this->mEmail );
	}

	/**
	 * Set the user's e-mail address and send a confirmation mail if needed.
	 *
	 * @since 1.20
	 * @param string $str New e-mail address
	 * @return Status
	 */
	public function setEmailWithConfirmation( string $str ) {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$enableEmail = $config->get( MainConfigNames::EnableEmail );

		if ( !$enableEmail ) {
			return Status::newFatal( 'emaildisabled' );
		}

		$oldaddr = $this->getEmail();
		if ( $str === $oldaddr ) {
			return Status::newGood( true );
		}

		$type = $oldaddr != '' ? 'changed' : 'set';
		$notificationResult = null;

		$emailAuthentication = $config->get( MainConfigNames::EmailAuthentication );

		if ( $emailAuthentication && $type === 'changed' ) {
			// Send the user an email notifying the user of the change in registered
			// email address on their previous email address
			$change = $str != '' ? 'changed' : 'removed';
			$notificationResult = $this->sendMail(
				wfMessage( 'notificationemail_subject_' . $change )->text(),
				wfMessage( 'notificationemail_body_' . $change,
					$this->getRequest()->getIP(),
					$this->getName(),
					$str )->text()
			);
		}

		$this->setEmail( $str );

		if ( $str !== '' && $emailAuthentication ) {
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
	public function getRealName(): string {
		if ( !$this->isItemLoaded( 'realname' ) ) {
			$this->load();
		}

		return $this->mRealName;
	}

	/**
	 * Set the user's real name
	 * @param string $str New real name
	 */
	public function setRealName( string $str ) {
		$this->load();
		$this->mRealName = $str;
	}

	/**
	 * Get the user's current setting for a given option.
	 *
	 * @param string $oname The option to check
	 * @param mixed|null $defaultOverride A default value returned if the option does not exist.
	 *   Default values set via $wgDefaultUserOptions / UserGetDefaultOptions take precedence.
	 * @param bool $ignoreHidden Whether to ignore the effects of $wgHiddenPrefs
	 * @return mixed|null User's current value for the option
	 * @deprecated since 1.35 Use UserOptionsLookup::getOption instead
	 */
	public function getOption( $oname, $defaultOverride = null, $ignoreHidden = false ) {
		if ( $oname === null ) {
			return null; // b/c
		}
		return MediaWikiServices::getInstance()
			->getUserOptionsLookup()
			->getOption( $this, $oname, $defaultOverride, $ignoreHidden );
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
		$hiddenPrefs =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::HiddenPrefs );

		$id = $this->getId();
		if ( !$id || in_array( $oname, $hiddenPrefs ) ) {
			return false;
		}

		$userOptionsLookup = MediaWikiServices::getInstance()
			->getUserOptionsLookup();
		$token = $userOptionsLookup->getOption( $this, (string)$oname );
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
	 * *Does not* save user's preferences (similarly to UserOptionsManager::setOption()).
	 *
	 * @param string $oname The option name to reset the token in
	 * @return string|bool New token value, or false if this option is disabled.
	 * @see getTokenFromOption()
	 * @see UserOptionsManager::setOption
	 */
	public function resetTokenFromOption( $oname ) {
		$hiddenPrefs =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::HiddenPrefs );
		if ( in_array( $oname, $hiddenPrefs ) ) {
			return false;
		}

		$token = MWCryptRand::generateHex( 40 );
		MediaWikiServices::getInstance()
			->getUserOptionsManager()
			->setOption( $this, $oname, $token );
		return $token;
	}

	/**
	 * Get the user's preferred date format.
	 * @return string User's preferred date format
	 */
	public function getDatePreference() {
		// Important migration for old data rows
		if ( $this->mDatePreference === null ) {
			global $wgLang;
			$userOptionsLookup = MediaWikiServices::getInstance()
				->getUserOptionsLookup();
			$value = $userOptionsLookup->getOption( $this, 'date' );
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
		if ( !$this->isRegistered() ) {
			return false;
		}

		$services = MediaWikiServices::getInstance();
		$config = $services->getMainConfig();
		if ( $config->get( MainConfigNames::ForceHTTPS ) ) {
			return true;
		}
		if ( !$config->get( MainConfigNames::SecureLogin ) ) {
			return false;
		}
		return $services->getUserOptionsLookup()
			->getBoolOption( $this, 'prefershttps' );
	}

	/**
	 * Get the list of explicit group memberships this user has.
	 * The implicit * and user groups are not included.
	 *
	 * @deprecated since 1.35 Use UserGroupManager::getUserGroups instead.
	 *
	 * @return string[] Array of internal group names (sorted since 1.33)
	 */
	public function getGroups() {
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->getUserGroups( $this, $this->queryFlagsUsed );
	}

	/**
	 * Get the list of explicit group memberships this user has, stored as
	 * UserGroupMembership objects. Implicit groups are not included.
	 *
	 * @deprecated since 1.35 Use UserGroupManager::getUserGroupMemberships instead
	 *
	 * @return UserGroupMembership[] Associative array of (group name => UserGroupMembership object)
	 * @since 1.29
	 */
	public function getGroupMemberships() {
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->getUserGroupMemberships( $this, $this->queryFlagsUsed );
	}

	/**
	 * Get the user's edit count.
	 * @return int|null Null for anonymous users
	 */
	public function getEditCount() {
		return MediaWikiServices::getInstance()
			->getUserEditTracker()
			->getUserEditCount( $this );
	}

	/**
	 * Add the user to the given group. This takes immediate effect.
	 * If the user is already in the group, the expiry time will be updated to the new
	 * expiry time. (If $expiry is omitted or null, the membership will be altered to
	 * never expire.)
	 *
	 * @deprecated since 1.35 Use UserGroupManager::addUserToGroup instead
	 *
	 * @param string $group Name of the group to add
	 * @param string|null $expiry Optional expiry timestamp in any format acceptable to
	 *   wfTimestamp(), or null if the group assignment should not expire
	 * @return bool
	 */
	public function addGroup( $group, $expiry = null ) {
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->addUserToGroup( $this, $group, $expiry, true );
	}

	/**
	 * Remove the user from the given group.
	 * This takes immediate effect.
	 *
	 * @deprecated since 1.35 Use UserGroupManager::removeUserFromGroup instead.
	 *
	 * @param string $group Name of the group to remove
	 * @return bool
	 */
	public function removeGroup( $group ) {
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->removeUserFromGroup( $this, $group );
	}

	/**
	 * Get whether the user is registered.
	 *
	 * @return bool True if user is registered on this wiki, i.e., has a user ID. False if user is
	 *   anonymous or has no local account (which can happen when importing). This is equivalent to
	 *   getId() != 0 and is provided for code readability.
	 * @since 1.34
	 */
	public function isRegistered(): bool {
		return $this->getId() != 0;
	}

	/**
	 * Get whether the user is anonymous
	 * @return bool
	 */
	public function isAnon() {
		return !$this->isRegistered();
	}

	/**
	 * @return bool Whether this user is flagged as being a bot role account
	 * @since 1.28
	 */
	public function isBot() {
		$userGroupManager = MediaWikiServices::getInstance()->getUserGroupManager();
		if ( in_array( 'bot', $userGroupManager->getUserGroups( $this ) ) && $this->isAllowed( 'bot' ) ) {
			return true;
		}

		$isBot = false;
		$this->getHookRunner()->onUserIsBot( $this, $isBot );

		return $isBot;
	}

	/**
	 * Get whether the user is a system user
	 *
	 * A user is considered to exist as a non-system user if it can
	 * authenticate, or has an email set, or has a non-invalid token.
	 *
	 * @return bool Whether this user is a system user
	 * @since 1.35
	 */
	public function isSystemUser() {
		$this->load();
		if ( $this->getEmail() || $this->mToken !== self::INVALID_TOKEN ||
			MediaWikiServices::getInstance()->getAuthManager()->userCanAuthenticate( $this->mName )
		) {
			return false;
		}
		return true;
	}

	public function isAllowedAny( ...$permissions ): bool {
		return $this->getThisAsAuthority()->isAllowedAny( ...$permissions );
	}

	public function isAllowedAll( ...$permissions ): bool {
		return $this->getThisAsAuthority()->isAllowedAll( ...$permissions );
	}

	public function isAllowed( string $permission ): bool {
		return $this->getThisAsAuthority()->isAllowed( $permission );
	}

	/**
	 * Check whether to enable recent changes patrol features for this user
	 * @return bool True or false
	 */
	public function useRCPatrol() {
		$useRCPatrol =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::UseRCPatrol );
		return $useRCPatrol && $this->isAllowedAny( 'patrol', 'patrolmarks' );
	}

	/**
	 * Check whether to enable new pages patrol features for this user
	 * @return bool True or false
	 */
	public function useNPPatrol() {
		$useRCPatrol =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::UseRCPatrol );
		$useNPPatrol =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::UseNPPatrol );
		return (
			( $useRCPatrol || $useNPPatrol )
				&& ( $this->isAllowedAny( 'patrol', 'patrolmarks' ) )
		);
	}

	/**
	 * Check whether to enable new files patrol features for this user
	 * @return bool True or false
	 */
	public function useFilePatrol() {
		$useRCPatrol =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::UseRCPatrol );
		$useFilePatrol = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::UseFilePatrol );
		return (
			( $useRCPatrol || $useFilePatrol )
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
		}
		return RequestContext::getMain()->getRequest();
	}

	/**
	 * Compute experienced level based on edit count and registration date.
	 *
	 * @return string|false 'newcomer', 'learner', or 'experienced', false for anonymous users
	 */
	public function getExperienceLevel() {
		$mainConfig = MediaWikiServices::getInstance()->getMainConfig();
		$learnerEdits = $mainConfig->get( MainConfigNames::LearnerEdits );
		$experiencedUserEdits = $mainConfig->get( MainConfigNames::ExperiencedUserEdits );
		$learnerMemberSince = $mainConfig->get( MainConfigNames::LearnerMemberSince );
		$experiencedUserMemberSince =
			$mainConfig->get( MainConfigNames::ExperiencedUserMemberSince );
		if ( $this->isAnon() ) {
			return false;
		}

		$editCount = $this->getEditCount();
		$registration = $this->getRegistration();
		$now = time();
		$learnerRegistration = wfTimestamp( TS_MW, $now - $learnerMemberSince * 86400 );
		$experiencedRegistration = wfTimestamp( TS_MW, $now - $experiencedUserMemberSince * 86400 );
		if ( $registration === null ) {
			// for some very old accounts, this information is missing in the database
			// treat them as old enough to be 'experienced'
			$registration = $experiencedRegistration;
		}

		if ( $editCount < $learnerEdits ||
		$registration > $learnerRegistration ) {
			return 'newcomer';
		}

		if ( $editCount > $experiencedUserEdits &&
			$registration <= $experiencedRegistration
		) {
			return 'experienced';
		}

		return 'learner';
	}

	/**
	 * Persist this user's session (e.g. set cookies)
	 *
	 * @param WebRequest|null $request WebRequest object to use; the global request
	 *        will be used if null is passed.
	 * @param bool|null $secure Whether to force secure/insecure cookies or use default
	 * @param bool $rememberMe Whether to add a Token cookie for elongated sessions
	 */
	public function setCookies( $request = null, $secure = null, $rememberMe = false ) {
		$this->load();
		if ( $this->mId == 0 ) {
			return;
		}

		$session = $this->getRequest()->getSession();
		if ( $request && $session->getRequest() !== $request ) {
			$session = $session->sessionWithRequest( $request );
		}
		$delay = $session->delaySave();

		if ( !$session->getUser()->equals( $this ) ) {
			if ( !$session->canSetUser() ) {
				LoggerFactory::getInstance( 'session' )
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
		// Avoid PHP 7.1 warning of passing $this by reference
		$user = $this;
		if ( $this->getHookRunner()->onUserLogout( $user ) ) {
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
			LoggerFactory::getInstance( 'session' )
				->warning( __METHOD__ . ": Cannot log out of an immutable session" );
			$error = 'immutable';
		} elseif ( !$session->getUser()->equals( $this ) ) {
			LoggerFactory::getInstance( 'session' )
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
		LoggerFactory::getInstance( 'authevents' )->info( 'Logout', [
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
		if ( MediaWikiServices::getInstance()->getReadOnlyMode()->isReadOnly() ) {
			// @TODO: caller should deal with this instead!
			// This should really just be an exception.
			MWExceptionHandler::logException( new DBExpectedError(
				null,
				"Could not update user with ID '{$this->mId}'; DB is read-only."
			) );
			return;
		}

		$this->load();
		if ( $this->mId == 0 ) {
			return; // anon
		}

		// Get a new user_touched that is higher than the old one.
		// This will be used for a CAS check as a last-resort safety
		// check against race conditions and replica DB lag.
		$newTouched = $this->newTouchedTimestamp();

		$dbw = wfGetDB( DB_PRIMARY );
		$dbw->doAtomicSection( __METHOD__, function ( IDatabase $dbw, $fname ) use ( $newTouched ) {
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
				] ), $fname
			);

			if ( !$dbw->affectedRows() ) {
				// Maybe the problem was a missed cache update; clear it to be safe
				$this->clearSharedCache( 'refresh' );
				// User was changed in the meantime or loaded with stale data
				$from = ( $this->queryFlagsUsed & self::READ_LATEST ) ? 'primary' : 'replica';
				LoggerFactory::getInstance( 'preferences' )->warning(
					"CAS update failed on user_touched for user ID '{user_id}' ({db_flag} read)",
					[ 'user_id' => $this->mId, 'db_flag' => $from ]
				);
				throw new MWException( "CAS update failed on user_touched. " .
					"The version of the user to be saved is older than the current version."
				);
			}

			$dbw->update(
				'actor',
				[ 'actor_name' => $this->mName ],
				[ 'actor_user' => $this->mId ],
				$fname
			);
			MediaWikiServices::getInstance()->getActorStore()->deleteUserIdentityFromCache( $this );
		} );

		$this->mTouched = $newTouched;
		MediaWikiServices::getInstance()->getUserOptionsManager()->saveOptionsInternal( $this, $dbw );

		$this->getHookRunner()->onUserSaveSettings( $this );
		$this->clearSharedCache( 'changed' );
		$hcu = MediaWikiServices::getInstance()->getHtmlCacheUpdater();
		$hcu->purgeTitleUrls( $this->getUserPage(), $hcu::PURGE_INTENT_TXROUND_REFLECTED );
	}

	/**
	 * If only this user's username is known, and it exists, return the user ID.
	 *
	 * @param int $flags Bitfield of User:READ_* constants; useful for existence checks
	 * @return int
	 */
	public function idForName( $flags = self::READ_NORMAL ) {
		$s = trim( $this->getName() );
		if ( $s === '' ) {
			return 0;
		}

		[ $index, $options ] = DBAccessObjectUtils::getDBOptions( $flags );
		$db = wfGetDB( $index );

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
	 * @return User|null User object, or null if the username already exists.
	 */
	public static function createNew( $name, $params = [] ) {
		return self::insertNewUser( static function ( UserIdentity $actor, IDatabase $dbw ) {
			return MediaWikiServices::getInstance()->getActorStore()->createNewActor( $actor, $dbw );
		}, $name, $params );
	}

	/**
	 * See ::createNew
	 * @param callable $insertActor ( UserIdentity $actor, IDatabase $dbw ): int actor ID,
	 * @param string $name
	 * @param array $params
	 * @return User|null
	 */
	private static function insertNewUser( callable $insertActor, $name, $params = [] ) {
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
			MediaWikiServices::getInstance()
				->getUserOptionsManager()
				->loadUserOptions( $user, $user->queryFlagsUsed, $params['options'] );
			unset( $params['options'] );
		}
		$dbw = wfGetDB( DB_PRIMARY );

		$noPass = PasswordFactory::newInvalidPassword()->toString();

		$fields = [
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

		return $dbw->doAtomicSection( __METHOD__, function ( IDatabase $dbw, $fname ) use ( $fields, $insertActor ) {
			$dbw->insert( 'user', $fields, $fname, [ 'IGNORE' ] );
			if ( $dbw->affectedRows() ) {
				$newUser = self::newFromId( $dbw->insertId() );
				$newUser->mName = $fields['user_name'];
				// Don't pass $this, since calling ::getId, ::getName might force ::load
				// and this user might not be ready for the yet.
				$newUser->mActorId = $insertActor( new UserIdentityValue( $newUser->mId, $newUser->mName ), $dbw );
				// Load the user from primary DB to avoid replica lag
				$newUser->load( self::READ_LATEST );
			} else {
				$newUser = null;
			}
			return $newUser;
		} );
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
	 *   if ( !$user->isRegistered() ) {
	 *       $user->addToDatabase();
	 *   }
	 *   // do something with $user...
	 *
	 * However, this was vulnerable to a race condition (T18020). By
	 * initialising the user object if the user exists, we aim to support this
	 * calling sequence as far as possible.
	 *
	 * Note that if the user exists, this function will acquire a write lock,
	 * so it is still advisable to make the call conditional on isRegistered(),
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

		if ( !is_string( $this->mName ) ) {
			throw new RuntimeException( "User name field is not set." );
		}

		$this->mTouched = $this->newTouchedTimestamp();

		$dbw = wfGetDB( DB_PRIMARY );
		$status = $dbw->doAtomicSection( __METHOD__, function ( IDatabase $dbw, $fname ) {
			$noPass = PasswordFactory::newInvalidPassword()->toString();
			$dbw->insert( 'user',
				[
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
				], $fname,
				[ 'IGNORE' ]
			);
			if ( !$dbw->affectedRows() ) {
				// Use locking reads to bypass any REPEATABLE-READ snapshot.
				$this->mId = $dbw->selectField(
					'user',
					'user_id',
					[ 'user_name' => $this->mName ],
					$fname,
					[ 'LOCK IN SHARE MODE' ]
				);
				$loaded = false;
				if ( $this->mId && $this->loadFromDatabase( IDBAccessObject::READ_LOCKING ) ) {
					$loaded = true;
				}
				if ( !$loaded ) {
					throw new MWException( $fname . ": hit a key conflict attempting " .
						"to insert user '{$this->mName}' row, but it was not present in select!" );
				}
				return Status::newFatal( 'userexists' );
			}
			$this->mId = $dbw->insertId();

			// Don't pass $this, since calling ::getId, ::getName might force ::load
			// and this user might not be ready for the yet.
			$this->mActorId = MediaWikiServices::getInstance()
				->getActorNormalization()
				->acquireActorId( new UserIdentityValue( $this->mId, $this->mName ), $dbw );
			return Status::newGood();
		} );
		if ( !$status->isGood() ) {
			return $status;
		}

		// Clear instance cache other than user table data and actor, which is already accurate
		$this->clearInstanceCache();

		MediaWikiServices::getInstance()->getUserOptionsManager()->saveOptions( $this );
		return Status::newGood();
	}

	/**
	 * If this user is logged-in and blocked,
	 * block any IP address they've successfully logged in from.
	 * @return bool A block was spread
	 */
	public function spreadAnyEditBlock() {
		if ( $this->isRegistered() && $this->getBlock() ) {
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
		wfDebug( __METHOD__ . "()" );
		$this->load();
		if ( $this->mId == 0 ) {
			return false;
		}

		$userblock = DatabaseBlock::newFromTarget( $this->getName() );
		if ( !$userblock ) {
			return false;
		}

		return (bool)$userblock->doAutoblock( $this->getRequest()->getIP() );
	}

	/**
	 * Get whether the user is explicitly blocked from account creation.
	 * @deprecated since 1.37. Instead use Authority::authorize* for createaccount permission.
	 * @return bool|AbstractBlock
	 */
	public function isBlockedFromCreateAccount() {
		$this->getBlockedStatus();
		if ( $this->mBlock && $this->mBlock->appliesToRight( 'createaccount' ) ) {
			return $this->mBlock;
		}

		# T15611: if the IP address the user is trying to create an account from is
		# blocked with createaccount disabled, prevent new account creation there even
		# when the user is logged in
		if ( $this->mBlockedFromCreateAccount === false && !$this->isAllowed( 'ipblock-exempt' ) ) {
			$this->mBlockedFromCreateAccount = DatabaseBlock::newFromTarget(
				null, $this->getRequest()->getIP()
			);
		}
		return $this->mBlockedFromCreateAccount instanceof AbstractBlock
			&& $this->mBlockedFromCreateAccount->appliesToRight( 'createaccount' )
			? $this->mBlockedFromCreateAccount
			: false;
	}

	/**
	 * Get whether the user is blocked from using Special:Emailuser.
	 * @return bool
	 */
	public function isBlockedFromEmailuser() {
		$this->getBlockedStatus();
		return $this->mBlock && $this->mBlock->appliesToRight( 'sendemail' );
	}

	/**
	 * Get whether the user is blocked from using Special:Upload
	 *
	 * @since 1.33
	 * @return bool
	 */
	public function isBlockedFromUpload() {
		$this->getBlockedStatus();
		return $this->mBlock && $this->mBlock->appliesToRight( 'upload' );
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
	 * @return Title
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
	 * Initialize (if necessary) and return a session token value
	 * which can be used in edit forms to show that the user's
	 * login credentials aren't being hijacked with a foreign form
	 * submission.
	 *
	 * @since 1.27
	 * @deprecated since 1.37. Use CsrfTokenSet::getToken instead
	 * @param string|string[] $salt Optional function-specific data for hashing
	 * @param WebRequest|null $request WebRequest object to use, or null to use the global request
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
	 * @deprecated since 1.37. Use CsrfTokenSet::getToken instead
	 * @param string|string[] $salt Optional function-specific data for hashing
	 * @param WebRequest|null $request WebRequest object to use, or null to use the global request
	 * @return string The new edit token
	 */
	public function getEditToken( $salt = '', $request = null ) {
		return $this->getEditTokenObject( $salt, $request )->toString();
	}

	/**
	 * Check given value against the token value stored in the session.
	 * A match should confirm that the form was submitted from the
	 * user's own login session, not a form submission from a third-party
	 * site.
	 *
	 * @deprecated since 1.37. Use CsrfTokenSet::matchToken instead
	 * @param string|null $val Input value to compare
	 * @param string|array $salt Optional function-specific data for hashing
	 * @param WebRequest|null $request Object to use, or null to use the global request
	 * @param int|null $maxage Fail tokens older than this, in seconds
	 * @return bool Whether the token matches
	 */
	public function matchEditToken( $val, $salt = '', $request = null, $maxage = null ) {
		return $this->getEditTokenObject( $salt, $request )->match( $val, $maxage );
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
			$type = 'created';
		} elseif ( $type === true ) {
			$message = 'confirmemail_body_changed';
			$type = 'changed';
		} else {
			// Messages: confirmemail_body_changed, confirmemail_body_set
			$message = 'confirmemail_body_' . $type;
		}

		$mail = [
			'subject' => wfMessage( 'confirmemail_subject' )->text(),
			'body' => wfMessage( $message,
				$this->getRequest()->getIP(),
				$this->getName(),
				$url,
				$wgLang->userTimeAndDate( $expiration, $this ),
				$invalidateURL,
				$wgLang->userDate( $expiration, $this ),
				$wgLang->userTime( $expiration, $this ) )->text(),
			'from' => null,
			'replyTo' => null,
		];
		$info = [
			'type' => $type,
			'ip' => $this->getRequest()->getIP(),
			'confirmURL' => $url,
			'invalidateURL' => $invalidateURL,
			'expiration' => $expiration
		];

		$this->getHookRunner()->onUserSendConfirmationMail( $this, $mail, $info );
		return $this->sendMail( $mail['subject'], $mail['body'], $mail['from'], $mail['replyTo'] );
	}

	/**
	 * Send an e-mail to this user's account. Does not check for
	 * confirmed status or validity.
	 *
	 * @param string $subject Message subject
	 * @param string $body Message body
	 * @param User|null $from Optional sending user; if unspecified, default
	 *   $wgPasswordSender will be used.
	 * @param MailAddress|null $replyto Reply-To address
	 * @return Status
	 */
	public function sendMail( $subject, $body, $from = null, $replyto = null ) {
		$passwordSender = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::PasswordSender );

		if ( $from instanceof User ) {
			$sender = MailAddress::newFromUser( $from );
		} else {
			$sender = new MailAddress( $passwordSender,
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
	 * @param string &$expiration Accepts the expiration time @phan-output-reference
	 * @return string New token
	 */
	protected function confirmationToken( &$expiration ) {
		$userEmailConfirmationTokenExpiry = MediaWikiServices::getInstance()
			->getMainConfig()->get( MainConfigNames::UserEmailConfirmationTokenExpiry );
		$now = time();
		$expires = $now + $userEmailConfirmationTokenExpiry;
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
	 * short English names avoids really long URL-encoded links, which
	 * also sometimes can get corrupted in some browsers/mailers
	 * (T8957 with Gmail and Internet Explorer).
	 *
	 * @param string $page Special page
	 * @param string $token
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
			$this->getHookRunner()->onConfirmEmailComplete( $this );
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
		$this->getHookRunner()->onInvalidateEmailComplete( $this );
		return true;
	}

	/**
	 * Set the e-mail authentication timestamp.
	 * @param string|null $timestamp TS_MW timestamp
	 */
	public function setEmailAuthenticationTimestamp( $timestamp ) {
		$this->load();
		$this->mEmailAuthenticated = $timestamp;
		$this->getHookRunner()->onUserSetEmailAuthenticationTimestamp(
			$this, $this->mEmailAuthenticated );
	}

	/**
	 * Is this user allowed to send e-mails within limits of current
	 * site configuration?
	 * @return bool
	 */
	public function canSendEmail() {
		$enableEmail = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::EnableEmail );
		$enableUserEmail = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::EnableUserEmail );
		if ( !$enableEmail || !$enableUserEmail || !$this->isAllowed( 'sendemail' ) ) {
			return false;
		}
		$hookErr = $this->isEmailConfirmed();
		$this->getHookRunner()->onUserCanSendEmail( $this, $hookErr );
		return $hookErr;
	}

	/**
	 * Is this user allowed to receive e-mails within limits of current
	 * site configuration?
	 * @return bool
	 */
	public function canReceiveEmail() {
		$userOptionsLookup = MediaWikiServices::getInstance()
			->getUserOptionsLookup();
		return $this->isEmailConfirmed() && !$userOptionsLookup->getOption( $this, 'disablemail' );
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
	public function isEmailConfirmed(): bool {
		$emailAuthentication = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::EmailAuthentication );
		$this->load();
		// Avoid PHP 7.1 warning of passing $this by reference
		$user = $this;
		$confirmed = true;
		if ( $this->getHookRunner()->onEmailConfirmed( $user, $confirmed ) ) {
			if ( $this->isAnon() ) {
				return false;
			}
			if ( !Sanitizer::validateEmail( $this->getEmail() ) ) {
				return false;
			}
			if ( $emailAuthentication && !$this->getEmailAuthenticationTimestamp() ) {
				return false;
			}
			return true;
		}

		return $confirmed;
	}

	/**
	 * Check whether there is an outstanding request for e-mail confirmation.
	 * @return bool
	 */
	public function isEmailConfirmationPending() {
		$emailAuthentication = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::EmailAuthentication );
		return $emailAuthentication &&
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
	 * Get the permissions associated with a given list of groups
	 *
	 * @deprecated since 1.34, use GroupPermissionsLookup::getGroupPermissions() instead in 1.36+,
	 *    or PermissionManager::getGroupPermissions() in 1.34 and 1.35
	 *
	 * @param string[] $groups internal group names
	 * @return string[] permission key names for given groups combined
	 */
	public static function getGroupPermissions( $groups ) {
		return MediaWikiServices::getInstance()->getGroupPermissionsLookup()->getGroupPermissions( $groups );
	}

	/**
	 * Get all the groups who have a given permission
	 *
	 * @deprecated since 1.34, use GroupPermissionsLookup::getGroupsWithPermission() instead in 1.36+,
	 *    or PermissionManager::getGroupsWithPermission() in 1.34 and 1.35
	 *
	 * @param string $role Role to check
	 * @return string[] internal group names with the given permission
	 */
	public static function getGroupsWithPermission( $role ) {
		return MediaWikiServices::getInstance()->getGroupPermissionsLookup()->getGroupsWithPermission( $role );
	}

	/**
	 * Check, if the given group has the given permission
	 *
	 * If you're wanting to check whether all users have a permission, use
	 * PermissionManager::isEveryoneAllowed() instead. That properly checks if it's revoked
	 * from anyone.
	 *
	 * @deprecated since 1.34, use GroupPermissionsLookup::groupHasPermission() instead in 1.36+,
	 *    or PermissionManager::groupHasPermission() in 1.34 and 1.35
	 *
	 * @since 1.21
	 * @param string $group Group to check
	 * @param string $role Role to check
	 * @return bool
	 */
	public static function groupHasPermission( $group, $role ) {
		return MediaWikiServices::getInstance()->getGroupPermissionsLookup()
			->groupHasPermission( $group, $role );
	}

	/**
	 * Return the set of defined explicit groups.
	 * The implicit groups (by default *, 'user' and 'autoconfirmed')
	 * are not included, as they are defined automatically, not in the database.
	 * @deprecated since 1.35, use UserGroupManager::listAllGroups instead
	 * @return string[] internal group names
	 */
	public static function getAllGroups() {
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->listAllGroups();
	}

	/**
	 * @deprecated since 1.35, use UserGroupManager::listAllImplicitGroups() instead
	 * @return string[] internal group names
	 */
	public static function getImplicitGroups() {
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->listAllImplicitGroups();
	}

	/**
	 * Schedule a deferred update to update the user's edit count
	 * @deprecated since 1.37
	 */
	public function incEditCount() {
		MediaWikiServices::getInstance()->getUserEditTracker()->incrementUserEditCount( $this );
	}

	/**
	 * Get the description of a given right
	 *
	 * @since 1.29
	 * @param string $right Right to query
	 * @return string Localized description of the right
	 */
	public static function getRightDescription( $right ) {
		$key = "right-$right";
		$msg = wfMessage( $key );
		return $msg->isDisabled() ? $right : $msg->text();
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new user object.
	 * @since 1.31
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()` or `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()` or `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()` or `SelectQueryBuilder::joinConds`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public static function getQueryInfo() {
		$ret = [
			'tables' => [ 'user', 'user_actor' => 'actor' ],
			'fields' => [
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
				'user_actor.actor_id',
			],
			'joins' => [
				'user_actor' => [ 'JOIN', 'user_actor.actor_user = user_id' ],
			],
		];

		return $ret;
	}

	/**
	 * Factory function for fatal permission-denied errors
	 *
	 * @since 1.22
	 * @param string $permission User right required
	 * @return Status
	 */
	public static function newFatalPermissionDeniedStatus( $permission ) {
		global $wgLang;

		$groups = [];
		foreach ( MediaWikiServices::getInstance()
				->getGroupPermissionsLookup()
				->getGroupsWithPermission( $permission ) as $group ) {
			$groups[] = UserGroupMembership::getLink( $group, RequestContext::getMain(), 'wiki' );
		}

		if ( $groups ) {
			return Status::newFatal( 'badaccess-groups', $wgLang->commaList( $groups ), count( $groups ) );
		}

		return Status::newFatal( 'badaccess-group0' );
	}

	/**
	 * Get a new instance of this user that was loaded from the primary DB via a locking read
	 *
	 * Use this instead of the main context User when updating that user. This avoids races
	 * where that user was loaded from a replica DB or even the primary DB but without proper locks.
	 *
	 * @return User|null Returns null if the user was not found in the DB
	 * @since 1.27
	 */
	public function getInstanceForUpdate() {
		if ( !$this->getId() ) {
			return null; // anon
		}

		$user = self::newFromId( $this->getId() );
		if ( !$user->loadFromId( IDBAccessObject::READ_EXCLUSIVE ) ) {
			return null;
		}

		return $user;
	}

	/**
	 * Checks if two user objects point to the same user.
	 *
	 * @since 1.25 ; takes a UserIdentity instead of a User since 1.32
	 * @param UserIdentity|null $user
	 * @return bool
	 */
	public function equals( ?UserIdentity $user ): bool {
		if ( !$user ) {
			return false;
		}
		// XXX it's not clear whether central ID providers are supposed to obey this
		return $this->getName() === $user->getName();
	}

	/**
	 * @note This is only here for compatibility with the Authority interface.
	 * @since 1.36
	 * @return UserIdentity $this
	 */
	public function getUser(): UserIdentity {
		return $this;
	}

	/**
	 * @since 1.36
	 * @param string $action
	 * @param PageIdentity $target
	 * @param PermissionStatus|null $status
	 * @return bool
	 */
	public function probablyCan( string $action, PageIdentity $target, PermissionStatus $status = null ): bool {
		return $this->getThisAsAuthority()->probablyCan( $action, $target, $status );
	}

	/**
	 * @since 1.36
	 * @param string $action
	 * @param PageIdentity $target
	 * @param PermissionStatus|null $status
	 * @return bool
	 */
	public function definitelyCan( string $action, PageIdentity $target, PermissionStatus $status = null ): bool {
		return $this->getThisAsAuthority()->definitelyCan( $action, $target, $status );
	}

	/**
	 * @since 1.36
	 * @param string $action
	 * @param PageIdentity $target
	 * @param PermissionStatus|null $status
	 * @return bool
	 */
	public function authorizeRead( string $action, PageIdentity $target, PermissionStatus $status = null
	): bool {
		return $this->getThisAsAuthority()->authorizeRead( $action, $target, $status );
	}

	/**
	 * @since 1.36
	 * @param string $action
	 * @param PageIdentity $target
	 * @param PermissionStatus|null $status
	 * @return bool
	 */
	public function authorizeWrite( string $action, PageIdentity $target, PermissionStatus $status = null ): bool {
		return $this->getThisAsAuthority()->authorizeWrite( $action, $target, $status );
	}

	/**
	 * Returns the Authority of this User if it's the main request context user.
	 * This is intended to exist only for the period of transition to Authority.
	 * @return Authority
	 */
	private function getThisAsAuthority(): Authority {
		if ( !$this->mThisAsAuthority ) {
			// TODO: For users that are not User::isGlobalSessionUser,
			// creating a UserAuthority here is incorrect, since it depends
			// on global WebRequest, but that is what we've used to do before Authority.
			// When PermissionManager is refactored into Authority, we need
			// to provide base implementation, based on just user groups/rights,
			// and use it here.
			$this->mThisAsAuthority = new UserAuthority(
				$this,
				MediaWikiServices::getInstance()->getPermissionManager()
			);
		}
		return $this->mThisAsAuthority;
	}

	/**
	 * Check whether this is the global session user.
	 * @return bool
	 */
	private function isGlobalSessionUser(): bool {
		// The session user is set up towards the end of Setup.php. Until then,
		// assume it's a logged-out user.
		$sessionUser = RequestContext::getMain()->getUser();
		$globalUserName = $sessionUser->isSafeToLoad()
			? $sessionUser->getName()
			: IPUtils::sanitizeIP( $sessionUser->getRequest()->getIP() );

		return $this->getName() === $globalUserName;
	}

	/**
	 * Is the user an autocreated temporary user?
	 * @return bool
	 */
	public function isTemp(): bool {
		if ( $this->isTemp === null ) {
			$this->isTemp = MediaWikiServices::getInstance()->getUserNameUtils()
				->isTemp( $this->getName() );
		}
		return $this->isTemp;
	}

	/**
	 * Is the user a normal non-temporary registered user?
	 *
	 * @return bool
	 */
	public function isNamed(): bool {
		return $this->isRegistered() && !$this->isTemp();
	}
}
