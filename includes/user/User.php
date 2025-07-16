<?php
/**
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

namespace MediaWiki\User;

use AllowDynamicProperties;
use ArrayIterator;
use BadMethodCallException;
use InvalidArgumentException;
use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\Block;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\SystemBlock;
use MediaWiki\Context\RequestContext;
use MediaWiki\DAO\WikiAwareEntityTrait;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Exception\MWExceptionHandler;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Mail\MailAddress;
use MediaWiki\Mail\UserEmailContact;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Password\PasswordFactory;
use MediaWiki\Password\UserPasswordPolicy;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Permissions\RateLimitSubject;
use MediaWiki\Permissions\UserAuthority;
use MediaWiki\Request\WebRequest;
use MediaWiki\Session\SessionManager;
use MediaWiki\Session\Token;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MWCryptHash;
use MWCryptRand;
use Profiler;
use RuntimeException;
use stdClass;
use Stringable;
use UnexpectedValueException;
use Wikimedia\Assert\Assert;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\DebugInfo\DebugInfoTrait;
use Wikimedia\IPUtils;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DBAccessObjectUtils;
use Wikimedia\Rdbms\DBExpectedError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\ScopedCallback;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @defgroup User User management
 */

/**
 * User class for the %MediaWiki software.
 *
 * User objects manage reading and writing of user-specific storage, including:
 * - `user` table (user_id, user_name, email, password, last login, etc.)
 * - `user_properties` table (user options)
 * - `user_groups` table (user rights and permissions)
 * - `user_newtalk` table (last-seen for your own user talk page)
 * - `watchlist` table (watched page titles by user, and their last-seen marker)
 * - `block` table, formerly known as `ipblocks` (user blocks)
 *
 * Callers use getter methods (getXXX) to read these fields. These getter functions
 * manage all higher-level responsibilities such as expanding default user options,
 * interpreting user groups into specific rights. Most user data needed when
 * rendering page views are cached (or stored in the session) to minimize repeat
 * database queries.
 *
 * New code is encouraged to use the following narrower classes instead.
 * If no replacement exist, and the User class method is not deprecated, feel
 * free to use it in new code (instead of duplicating business logic).
 *
 * - UserIdentityValue, to represent a user name/id.
 * - UserOptionsManager service, to read-write user options.
 * - Authority via RequestContext::getAuthority, to represent the current user
 *   with a easy shortcuts to interpret user permissions (can user X do Y on page Z)
 *   without needing te call low-level PermissionManager and RateLimiter services.
 *   Authority replaces methods like User::isAllowed, User::definitelyCan,
 *   and User::pingLimiter.
 * - PermissionManager service, to interpret rights and permissions of any user.
 * - TalkPageNotificationManager service, replacing User::getNewtalk.
 * - WatchlistManager service, replacing methods like User::isWatched,
 *   User::addWatch, and User::clearNotification.
 * - BlockManager service, replacing User::getBlock.
 *
 * @note User implements Authority to ease transition. Always prefer
 * using existing Authority or obtaining a proper Authority implementation.
 *
 * @ingroup User
 */
#[AllowDynamicProperties]
class User implements Stringable, Authority, UserIdentity, UserEmailContact {
	use DebugInfoTrait;
	use ProtectedHookAccessorTrait;
	use WikiAwareEntityTrait;

	/**
	 * @see IDBAccessObject::READ_EXCLUSIVE
	 */
	public const READ_EXCLUSIVE = IDBAccessObject::READ_EXCLUSIVE;

	/**
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
	/** @var AbstractBlock|false|null Null when uninitialized, false when there is no block */
	protected $mGlobalBlock;
	/** @var bool|null */
	protected $mLocked;

	/** @var WebRequest|null */
	private $mRequest;

	/** @var int IDBAccessObject::READ_* constant bitfield used to load data */
	protected $queryFlagsUsed = IDBAccessObject::READ_NORMAL;

	/**
	 * @var UserAuthority|null lazy-initialized Authority of this user
	 * @noVarDump
	 */
	private $mThisAsAuthority;

	/** @var bool|null */
	private $isTemp;

	/**
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
		// By default, this is a lightweight constructor representing
		// an anonymous user from the current web request and IP.
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
			// hard deprecated since 1.40
			wfDeprecated( 'User::$mRights', '1.34' );
			$copy = MediaWikiServices::getInstance()
				->getPermissionManager()
				->getUserPermissions( $this );
			return $copy;
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
			// hard deprecated since 1.40
			wfDeprecated( 'User::$mRights', '1.34' );
			MediaWikiServices::getInstance()->getPermissionManager()->overrideUserRightsForTesting(
				$this,
				$value ?? []
			);
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
	 * @param int $flags IDBAccessObject::READ_* constant bitfield
	 */
	public function load( $flags = IDBAccessObject::READ_NORMAL ) {
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
					'exception' => new RuntimeException(
						'User::loadFromSession called before the end of Setup.php'
					),
				] );
			$this->loadDefaults();
			$this->mLoadedItems = $oldLoadedItems;
			return;
		} elseif ( $this->mFrom === 'session'
			&& defined( 'MW_NO_SESSION' ) && MW_NO_SESSION !== 'warn'
		) {
			// Even though we are throwing an exception, make sure the User object is left in a
			// clean state as sometimes these exceptions are caught and the object accessed again.
			$this->loadDefaults();
			$this->mLoadedItems = $oldLoadedItems;
			$ep = defined( 'MW_ENTRY_POINT' ) ? MW_ENTRY_POINT : 'this';
			throw new BadMethodCallException( "Sessions are disabled for $ep entry point" );
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
						$flags |= IDBAccessObject::READ_LATEST;
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
					$flags |= IDBAccessObject::READ_LATEST;
					$this->queryFlagsUsed = $flags;
				}

				$dbr = DBAccessObjectUtils::getDBFromRecency(
					MediaWikiServices::getInstance()->getDBLoadBalancerFactory(),
					$flags
				);
				$queryBuilder = $dbr->newSelectQueryBuilder()
					->select( [ 'actor_id', 'actor_user', 'actor_name' ] )
					->from( 'actor' )
					->recency( $flags );
				if ( $this->mFrom === 'name' ) {
					// make sure to use normalized form of IP for anonymous users
					$queryBuilder->where( [ 'actor_name' => IPUtils::sanitizeIP( $this->mName ) ] );
				} else {
					$queryBuilder->where( [ 'actor_id' => $this->mActorId ] );
				}
				$row = $queryBuilder->caller( __METHOD__ )->fetchRow();

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
	 * @param int $flags IDBAccessObject::READ_* constant bitfield
	 * @return bool False if the ID does not exist, true otherwise
	 */
	public function loadFromId( $flags = IDBAccessObject::READ_NORMAL ) {
		if ( $this->mId == 0 ) {
			// Anonymous users are not in the database (don't need cache)
			$this->loadDefaults();
			return false;
		}

		// Try cache (unless this needs data from the primary DB).
		// NOTE: if this thread called saveSettings(), the cache was cleared.
		$latest = DBAccessObjectUtils::hasFlags( $flags, IDBAccessObject::READ_LATEST );
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

		return $cache->makeGlobalKey( 'user', 'id',
			$lbFactory->getLocalDomainID(), $this->mId );
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
				$setOpts += Database::getCacheSetOptions(
					MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase()
				);
				wfDebug( "User: cache miss for user {$this->mId}" );

				$this->loadFromDatabase( IDBAccessObject::READ_NORMAL );

				$data = [];
				foreach ( self::$mCacheVars as $name ) {
					$data[$name] = $this->$name;
				}

				$ttl = $cache->adaptiveTTL(
					(int)wfTimestamp( TS_UNIX, $this->mTouched ),
					$ttl
				);

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
	 * @return User|false User object, or false if the username is invalid
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

		return MediaWikiServices::getInstance()->getUserFactory()
			->newFromName( (string)$name, $validation ) ?? false;
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
	 * @param string|false $dbDomain remote wiki to which the User/Actor ID
	 *   applies, or false if none
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
	 * @param int $flags IDBAccessObject::READ_* bitfield
	 * @return User|null
	 */
	public static function newFromConfirmationCode( $code, $flags = IDBAccessObject::READ_NORMAL ) {
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
	public static function newFromSession( ?WebRequest $request = null ) {
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
	 * System users should usually be listed in $wgReservedUsernames.
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
	 * @see self::isSystemUser()
	 * @see MainConfigSchema::ReservedUsernames
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

		$dbProvider = $services->getDBLoadBalancerFactory();
		$dbr = $dbProvider->getReplicaDatabase();

		$userQuery = self::newQueryBuilder( $dbr )
			->where( [ 'user_name' => $name ] )
			->caller( __METHOD__ );
		$row = $userQuery->fetchRow();
		if ( !$row ) {
			// Try the primary database
			$userQuery->connection( $dbProvider->getPrimaryDatabase() );
			// Lock the row to prevent insertNewUser() returning null due to race conditions
			$userQuery->forUpdate();
			$row = $userQuery->fetchRow();
		}

		if ( !$row ) {
			// No user. Create it?
			// @phan-suppress-next-line PhanImpossibleCondition
			if ( !$options['create'] ) {
				// No.
				return null;
			}

			// If it's a reserved user that had an anonymous actor created for it at
			// some point, we need special handling.
			return self::insertNewUser( static function ( UserIdentity $actor, IDatabase $dbw ) {
				return MediaWikiServices::getInstance()->getActorStore()
					->acquireSystemActorId( $actor, $dbw );
			}, $name, [ 'token' => self::INVALID_TOKEN ] );
		}

		$user = self::newFromRow( $row );

		if ( !$user->isSystemUser() ) {
			// User exists. Steal it?
			// @phan-suppress-next-line PhanRedundantCondition
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
	 * Return the users who are members of the given group(s). In case of multiple groups,
	 * users who are members of at least one of them are returned.
	 *
	 * @param string|array $groups A single group name or an array of group names
	 * @param int $limit Max number of users to return. The actual limit will never exceed 5000
	 *   records; larger values are ignored.
	 * @param int|null $after ID the user to start after
	 * @return UserArray|ArrayIterator
	 */
	public static function findUsersByGroup( $groups, $limit = 5000, $after = null ) {
		if ( $groups === [] ) {
			return UserArrayFromResult::newFromIDs( [] );
		}
		$dbr = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( 'ug_user' )
			->distinct()
			->from( 'user_groups' )
			->where( [ 'ug_group' => array_unique( (array)$groups ) ] )
			->orderBy( 'ug_user' )
			->limit( min( 5000, $limit ) );

		if ( $after !== null ) {
			$queryBuilder->andWhere( $dbr->expr( 'ug_user', '>', (int)$after ) );
		}

		$ids = $queryBuilder->caller( __METHOD__ )->fetchFieldValues() ?: [];
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
		$services = MediaWikiServices::getInstance();
		$userNameUtils = $services->getUserNameUtils();
		if ( $userNameUtils->isTemp( $this->getName() ) ) {
			return Status::newFatal( 'error-temporary-accounts-cannot-have-passwords' );
		}

		$passwordPolicy = $services->getMainConfig()->get( MainConfigNames::PasswordPolicy );

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
	 * @param string|false $name
	 * @param int|null $actorId
	 */
	public function loadDefaults( $name = false, $actorId = null ) {
		$this->mId = 0;
		$this->mName = $name;
		$this->mActorId = $actorId;
		$this->mRealName = '';
		$this->mEmail = '';
		$this->isTemp = null;

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
	 * @param int $flags IDBAccessObject::READ_* constant bitfield
	 * @return bool True if the user exists, false if the user is anonymous
	 */
	public function loadFromDatabase( $flags = IDBAccessObject::READ_LATEST ) {
		// Paranoia
		$this->mId = intval( $this->mId );

		if ( !$this->mId ) {
			// Anonymous users are not in the database
			$this->loadDefaults();
			return false;
		}

		$db = DBAccessObjectUtils::getDBFromRecency(
			MediaWikiServices::getInstance()->getDBLoadBalancerFactory(),
			$flags
		);
		$row = self::newQueryBuilder( $db )
			->where( [ 'user_id' => $this->mId ] )
			->recency( $flags )
			->caller( __METHOD__ )
			->fetchRow();

		$this->queryFlagsUsed = $flags;

		if ( $row !== false ) {
			// Initialise user table data
			$this->loadFromRow( $row );
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
		if ( !( $row instanceof stdClass ) ) {
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
	 * @param IReadableDatabase $db
	 * @param array $conditions WHERE conditions for use with Database::update
	 * @return array WHERE conditions for use with Database::update
	 */
	protected function makeUpdateConditions( IReadableDatabase $db, array $conditions ) {
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

		$dbw = MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase();
		$dbw->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_touched' => $dbw->timestamp( $newTouched ) ] )
			->where( $this->makeUpdateConditions( $dbw, [
				'user_id' => $this->mId,
			] ) )
			->caller( __METHOD__ )->execute();
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
		$this->mThisAsAuthority = null;

		if ( $wgFullyInitialised && $this->mFrom ) {
			$services = MediaWikiServices::getInstance();

			if ( $services->peekService( 'PermissionManager' ) ) {
				$services->getPermissionManager()->invalidateUsersRightsCache( $this );
			}

			if ( $services->peekService( 'UserOptionsManager' ) ) {
				$services->getUserOptionsManager()->clearUserOptionsCache( $this );
			}

			if ( $services->peekService( 'TalkPageNotificationManager' ) ) {
				$services->getTalkPageNotificationManager()->clearInstanceCache( $this );
			}

			if ( $services->peekService( 'UserGroupManager' ) ) {
				$services->getUserGroupManager()->clearCache( $this );
			}

			if ( $services->peekService( 'UserEditTracker' ) ) {
				$services->getUserEditTracker()->clearUserEditCache( $this );
			}

			if ( $services->peekService( 'BlockManager' ) ) {
				$services->getBlockManager()->clearUserCache( $this );
			}
		}

		if ( $reloadFrom ) {
			if ( in_array( $reloadFrom, [ 'name', 'id', 'actor' ] ) ) {
				$this->mLoadedItems = [ $reloadFrom => true ];
			} else {
				$this->mLoadedItems = [];
			}
			$this->mFrom = $reloadFrom;
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
	 */
	public function pingLimiter( $action = 'edit', $incrBy = 1 ) {
		return $this->getThisAsAuthority()->limit( $action, $incrBy, null );
	}

	/**
	 * @internal for use by UserAuthority only!
	 * @return RateLimitSubject
	 */
	public function toRateLimitSubject(): RateLimitSubject {
		$flags = [
			'exempt' => $this->isAllowed( 'noratelimit' ),
			'newbie' => $this->isNewbie(),
		];

		return new RateLimitSubject( $this, $this->getRequest()->getIP(), $flags );
	}

	/**
	 * Get the block affecting the user, or null if the user is not blocked
	 *
	 * @param int|bool $freshness One of the IDBAccessObject::READ_XXX constants.
	 *                 For backwards compatibility, a boolean is also accepted,
	 *                 with true meaning READ_NORMAL and false meaning
	 *                 READ_LATEST.
	 * @param bool $disableIpBlockExemptChecking This is used internally to prevent
	 *   a infinite recursion with autopromote. See T270145.
	 *
	 * @return ?AbstractBlock
	 */
	public function getBlock(
		$freshness = IDBAccessObject::READ_NORMAL,
		$disableIpBlockExemptChecking = false
	): ?Block {
		if ( is_bool( $freshness ) ) {
			$fromReplica = $freshness;
		} else {
			$fromReplica = ( $freshness !== IDBAccessObject::READ_LATEST );
		}

		if ( $disableIpBlockExemptChecking ) {
			$isExempt = false;
		} else {
			$isExempt = $this->isAllowed( 'ipblock-exempt' );
		}

		// TODO: Block checking shouldn't really be done from the User object. Block
		// checking can involve checking for IP blocks, cookie blocks, and/or XFF blocks,
		// which need more knowledge of the request context than the User should have.
		// Since we do currently check blocks from the User, we have to do the following
		// here:
		// - Check if this is the user associated with the main request
		// - If so, pass the relevant request information to the block manager
		$request = null;
		if ( !$isExempt && $this->isGlobalSessionUser() ) {
			// This is the global user, so we need to pass the request
			$request = $this->getRequest();
		}

		return MediaWikiServices::getInstance()->getBlockManager()->getBlock(
			$this,
			$request,
			$fromReplica,
		);
	}

	/**
	 * Check if user is blocked on all wikis.
	 * Do not use for actual edit permission checks!
	 * This is intended for quick UI checks.
	 *
	 * @param string $ip IP address, uses current client if none given
	 * @return bool True if blocked, false otherwise
	 * @deprecated since 1.40, emits deprecation warnings since 1.43. Use getBlock instead.
	 */
	public function isBlockedGlobally( $ip = '' ) {
		wfDeprecated( __METHOD__, '1.40' );
		return $this->getGlobalBlock( $ip ) instanceof AbstractBlock;
	}

	/**
	 * Check if user is blocked on all wikis.
	 * Do not use for actual edit permission checks!
	 * This is intended for quick UI checks.
	 *
	 * @param string $ip IP address, uses current client if none given
	 * @return AbstractBlock|null Block object if blocked, null otherwise
	 * @deprecated since 1.40. Use getBlock instead
	 */
	public function getGlobalBlock( $ip = '' ) {
		wfDeprecated( __METHOD__, '1.40' );
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
				'target' => MediaWikiServices::getInstance()->getBlockTargetFactory()
					->newAnonIpBlockTarget( $ip ),
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
		$block = $this->getBlock();
		return $block ? $block->getHideName() : false;
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
	public function getTitleKey(): string {
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

		$dbProvider = MediaWikiServices::getInstance()->getConnectionProvider();
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$key = $this->getCacheKey( $cache );

		if ( $mode === 'refresh' ) {
			$cache->delete( $key, 1 ); // low tombstone/"hold-off" TTL
		} else {
			$dbProvider->getPrimaryDatabase()->onTransactionPreCommitOrIdle(
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
	 * @param string|false $token If specified, set the token to this value
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

		if ( $emailAuthentication && $type === 'changed' && $this->isEmailConfirmed() ) {
			// Send the user an email notifying the user of the change in registered
			// email address on their previous verified email address
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
	 * Get a token stored in the preferences (like the watchlist one),
	 * resetting it if it's empty (and saving changes).
	 *
	 * @param string $oname The option name to retrieve the token from
	 * @return string|false User's current value for the option, or false if this option is disabled.
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
	 * @return string|false New token value, or false if this option is disabled.
	 * @see getTokenFromOption()
	 * @see \MediaWiki\User\Options\UserOptionsManager::setOption
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
	 * Get the user's edit count.
	 * @return int|null Null for anonymous users
	 */
	public function getEditCount() {
		return MediaWikiServices::getInstance()
			->getUserEditTracker()
			->getUserEditCount( $this );
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
		if ( in_array( 'bot', $userGroupManager->getUserGroups( $this ) )
			&& $this->isAllowed( 'bot' )
		) {
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

	public function isAllowed( string $permission, ?PermissionStatus $status = null ): bool {
		return $this->getThisAsAuthority()->isAllowed( $permission, $status );
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
	 */
	public function getRequest(): WebRequest {
		return $this->mRequest ?? RequestContext::getMain()->getRequest();
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
						": Cannot save user \"$this\" to a user " .
						"\"{$session->getUser()}\"'s immutable session"
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
		if ( $this->getHookRunner()->onUserLogout( $this ) ) {
			$this->doLogout();
		}
	}

	/**
	 * Clear the user's session, and reset the instance cache.
	 * @see logout()
	 */
	public function doLogout() {
		$session = $this->getRequest()->getSession();
		$accountType = MediaWikiServices::getInstance()->getUserIdentityUtils()->getShortUserTypeInternal( $this );
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
			'accountType' => $accountType,
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

		$dbw = MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase();
		$dbw->doAtomicSection( __METHOD__, function ( IDatabase $dbw, $fname ) use ( $newTouched ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'user' )
				->set( [
					'user_name' => $this->mName,
					'user_real_name' => $this->mRealName,
					'user_email' => $this->mEmail,
					'user_email_authenticated' => $dbw->timestampOrNull( $this->mEmailAuthenticated ),
					'user_touched' => $dbw->timestamp( $newTouched ),
					'user_token' => strval( $this->mToken ),
					'user_email_token' => $this->mEmailToken,
					'user_email_token_expires' => $dbw->timestampOrNull( $this->mEmailTokenExpires ),
				] )
				->where( $this->makeUpdateConditions( $dbw, [ /* WHERE */
					'user_id' => $this->mId,
				] ) )
				->caller( $fname )->execute();

			if ( !$dbw->affectedRows() ) {
				// Maybe the problem was a missed cache update; clear it to be safe
				$this->clearSharedCache( 'refresh' );
				// User was changed in the meantime or loaded with stale data
				$from = ( $this->queryFlagsUsed & IDBAccessObject::READ_LATEST ) ? 'primary' : 'replica';
				LoggerFactory::getInstance( 'preferences' )->warning(
					"CAS update failed on user_touched for user ID '{user_id}' ({db_flag} read)",
					[ 'user_id' => $this->mId, 'db_flag' => $from ]
				);
				throw new RuntimeException( "CAS update failed on user_touched. " .
					"The version of the user to be saved is older than the current version."
				);
			}

			$dbw->newUpdateQueryBuilder()
				->update( 'actor' )
				->set( [ 'actor_name' => $this->mName ] )
				->where( [ 'actor_user' => $this->mId ] )
				->caller( $fname )->execute();
			MediaWikiServices::getInstance()->getActorStore()->deleteUserIdentityFromCache( $this );
		} );

		$this->mTouched = $newTouched;
		if ( $this->isNamed() ) {
			MediaWikiServices::getInstance()->getUserOptionsManager()->saveOptionsInternal( $this );
		}

		$this->getHookRunner()->onUserSaveSettings( $this );
		$this->clearSharedCache( 'changed' );
		$hcu = MediaWikiServices::getInstance()->getHtmlCacheUpdater();
		$hcu->purgeTitleUrls( $this->getUserPage(), $hcu::PURGE_INTENT_TXROUND_REFLECTED );
	}

	/**
	 * If only this user's username is known, and it exists, return the user ID.
	 *
	 * @param int $flags Bitfield of IDBAccessObject::READ_* constants; useful for existence checks
	 * @return int
	 */
	public function idForName( $flags = IDBAccessObject::READ_NORMAL ) {
		$s = trim( $this->getName() );
		if ( $s === '' ) {
			return 0;
		}

		$db = DBAccessObjectUtils::getDBFromRecency(
			MediaWikiServices::getInstance()->getDBLoadBalancerFactory(),
			$flags
		);
		$id = $db->newSelectQueryBuilder()
			->select( 'user_id' )
			->from( 'user' )
			->where( [ 'user_name' => $s ] )
			->recency( $flags )
			->caller( __METHOD__ )->fetchField();

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
	 * @return User|null User object, or null if the username already exists.
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
		$dbw = MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase();

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

		return $dbw->doAtomicSection( __METHOD__, static function ( IDatabase $dbw, $fname )
			use ( $fields, $insertActor )
		{
			$dbw->newInsertQueryBuilder()
				->insertInto( 'user' )
				->ignore()
				->row( $fields )
				->caller( $fname )->execute();
			if ( $dbw->affectedRows() ) {
				$newUser = self::newFromId( $dbw->insertId() );
				$newUser->mName = $fields['user_name'];
				// Don't pass $this, since calling ::getId, ::getName might force ::load
				// and this user might not be ready for the yet.
				$newUser->mActorId = $insertActor(
					new UserIdentityValue( $newUser->mId, $newUser->mName ),
					$dbw
				);
				// Load the user from primary DB to avoid replica lag
				$newUser->load( IDBAccessObject::READ_LATEST );
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

		$dbw = MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase();
		$status = $dbw->doAtomicSection( __METHOD__, function ( IDatabase $dbw, $fname ) {
			$noPass = PasswordFactory::newInvalidPassword()->toString();
			$dbw->newInsertQueryBuilder()
				->insertInto( 'user' )
				->ignore()
				->row( [
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
					'user_is_temp' => $this->isTemp(),
				] )
				->caller( $fname )->execute();
			if ( !$dbw->affectedRows() ) {
				// Use locking reads to bypass any REPEATABLE-READ snapshot.
				$this->mId = $dbw->newSelectQueryBuilder()
					->select( 'user_id' )
					->lockInShareMode()
					->from( 'user' )
					->where( [ 'user_name' => $this->mName ] )
					->caller( $fname )->fetchField();
				$loaded = false;
				if ( $this->mId && $this->loadFromDatabase( IDBAccessObject::READ_LOCKING ) ) {
					$loaded = true;
				}
				if ( !$loaded ) {
					throw new RuntimeException( $fname . ": hit a key conflict attempting " .
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

		if ( $this->isNamed() ) {
			MediaWikiServices::getInstance()->getUserOptionsManager()->saveOptions( $this );
		}
		return Status::newGood();
	}

	/**
	 * Schedule a deferred update which will block the IP address of the current
	 * user, if they are blocked with the autoblocking option.
	 *
	 * @since 1.45
	 */
	public function scheduleSpreadBlock() {
		DeferredUpdates::addCallableUpdate( function () {
			// Permit master queries in a GET request
			$scope = Profiler::instance()->getTransactionProfiler()->silenceForScope();
			$this->spreadAnyEditBlock();
			ScopedCallback::consume( $scope );
		} );
	}

	/**
	 * If this user is logged-in and blocked, block any IP address they've successfully logged in from.
	 * Calls the "SpreadAnyEditBlock" hook, so this may block the IP address using a non-core blocking mechanism.
	 *
	 * @return bool A block was spread
	 */
	public function spreadAnyEditBlock() {
		if ( !$this->isRegistered() ) {
			return false;
		}

		$blockWasSpread = false;
		$this->getHookRunner()->onSpreadAnyEditBlock( $this, $blockWasSpread );

		$block = $this->getBlock();
		if ( $block ) {
			$blockWasSpread = $blockWasSpread || $this->spreadBlock( $block );
		}

		return $blockWasSpread;
	}

	/**
	 * If this (non-anonymous) user is blocked,
	 * block the IP address they've successfully logged in from.
	 * @param Block $block The active block on the user
	 * @return bool A block was spread
	 */
	protected function spreadBlock( Block $block ): bool {
		wfDebug( __METHOD__ . "()" );
		$this->load();
		if ( $this->mId == 0 ) {
			return false;
		}

		$blockStore = MediaWikiServices::getInstance()->getDatabaseBlockStore();
		foreach ( $block->toArray() as $singleBlock ) {
			if ( $singleBlock instanceof DatabaseBlock && $singleBlock->isAutoblocking() ) {
				return (bool)$blockStore->doAutoblock( $singleBlock, $this->getRequest()->getIP() );
			}
		}
		return false;
	}

	/**
	 * Get whether the user is blocked from using Special:Emailuser.
	 * @return bool
	 * @deprecated since 1.41, emits deprecation warnings since 1.43. EmailUser::canSend
	 *   checks blocks amongst other things. If you only need this check, use
	 *   ::getBlock()->appliesToRight( 'sendemail' ).
	 */
	public function isBlockedFromEmailuser() {
		wfDeprecated( __METHOD__, '1.41' );
		$block = $this->getBlock();
		return $block && $block->appliesToRight( 'sendemail' );
	}

	/**
	 * Get whether the user is blocked from using Special:Upload
	 *
	 * @since 1.33
	 * @return bool
	 */
	public function isBlockedFromUpload() {
		$block = $this->getBlock();
		return $block && $block->appliesToRight( 'upload' );
	}

	/**
	 * Get whether the user is allowed to create an account.
	 * @return bool
	 */
	public function isAllowedToCreateAccount() {
		return $this->getThisAsAuthority()->isDefinitelyAllowed( 'createaccount' );
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
	 * Determine whether the user is a newbie. Newbies are one of:
	 * - IP address editors
	 * - temporary accounts
	 * - most recently created full accounts.
	 * @return bool
	 */
	public function isNewbie() {
		// IP users and temp account users are excluded from the autoconfirmed group.
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
	 * @return Token The new edit token
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
	 * Any preexisting e-mail confirmation token will be invalidated.
	 *
	 * @param string $type Message to send, either "created", "changed" or "set"
	 * @return Status
	 */
	public function sendConfirmationMail( $type = 'created' ) {
		global $wgLang;
		$expiration = null; // gets passed-by-ref and defined in next line.
		$token = $this->getConfirmationToken( $expiration );
		$url = $this->getConfirmationTokenUrl( $token );
		$invalidateURL = $this->getInvalidationTokenUrl( $token );
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
	 * @param string|string[] $body Message body or array containing keys text and html
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

		if ( is_array( $body ) ) {
			$bodyText = $body['text'] ?? '';
			$bodyHtml = $body['html'] ?? null;
		} else {
			$bodyText = $body;
			$bodyHtml = null;
		}

		return Status::wrap( MediaWikiServices::getInstance()->getEmailer()
			->send(
				[ $to ],
				$sender,
				$subject,
				$bodyText,
				$bodyHtml,
				[ 'replyTo' => $replyto ]
			) );
	}

	/**
	 * Generate, store, and return a new e-mail confirmation code.
	 * A hash (unsalted, since it's used as a key) is stored.
	 * Any preexisting e-mail confirmation token will be invalidated.
	 *
	 * @note Call saveSettings() after calling this function to commit
	 * this change to the database.
	 *
	 * @since 1.45
	 *
	 * @param null|string &$expiration Timestamp at which the generated token expires @phan-output-reference
	 * @param int|null $tokenLifeTimeSeconds Optional lifetime of the token in seconds.
	 * Defaults to the value of $wgUserEmailConfirmationTokenExpiry if not set.
	 * @return string New token
	 */
	public function getConfirmationToken(
		?string &$expiration,
		?int $tokenLifeTimeSeconds = null
	): string {
		$tokenLifeTimeSeconds ??= MediaWikiServices::getInstance()
			->getMainConfig()->get( MainConfigNames::UserEmailConfirmationTokenExpiry );
		$now = ConvertibleTimestamp::time();

		$expires = $now + $tokenLifeTimeSeconds;
		$expiration = wfTimestamp( TS_MW, $expires );
		$this->load();
		$token = MWCryptRand::generateHex( 32 );
		$hash = md5( $token );
		$this->mEmailToken = $hash;
		$this->mEmailTokenExpires = $expiration;
		return $token;
	}

	/**
	 * Deprecated alias for getConfirmationToken() for CentralAuth.
	 * @deprecated Use getConfirmationToken() instead.
	 * @param string|null &$expiration @phan-output-reference
	 * @return string
	 */
	protected function confirmationToken( &$expiration ) {
		return $this->getConfirmationToken( $expiration );
	}

	/**
	 * Check if the given email confirmation token is well-formed (to detect mangling by
	 * email clients). This does not check whether the token is valid.
	 * @param string $token
	 * @return bool
	 */
	public static function isWellFormedConfirmationToken( string $token ): bool {
		return preg_match( '/^[a-f0-9]{32}$/', $token );
	}

	/**
	 * Return a URL the user can use to confirm their email address.
	 *
	 * @since 1.45
	 * @param string $token Accepts the email confirmation token
	 * @return string New token URL
	 */
	public function getConfirmationTokenUrl( string $token ): string {
		return $this->getTokenUrl( 'ConfirmEmail', $token );
	}

	/**
	 * Return a URL the user can use to invalidate their email address.
	 *
	 * @since 1.45
	 * @param string $token Accepts the email confirmation token
	 * @return string New token URL
	 */
	public function getInvalidationTokenUrl( string $token ): string {
		return $this->getTokenUrl( 'InvalidateEmail', $token );
	}

	/**
	 * Deprecated alias for getInvalidationTokenUrl() for CentralAuth.
	 *
	 * @deprecated Use getInvalidationTokenUrl() instead.
	 * @param string $token Accepts the email confirmation token
	 * @return string New token URL
	 */
	protected function invalidationTokenUrl( $token ) {
		return $this->getTokenUrl( 'InvalidateEmail', $token );
	}

	/**
	 * Function to create a special page URL with a token path parameter.
	 * This uses a quickie hack to use the
	 * hardcoded English names of the Special: pages, for ASCII safety.
	 *
	 * @note Since these URLs get dropped directly into emails, using the
	 * short English names avoids really long URL-encoded links, which
	 * also sometimes can get corrupted in some browsers/mailers
	 * (T8957 with Gmail and Internet Explorer).
	 *
	 * @since 1.45
	 * @param string $page Special page
	 * @param string $token
	 * @return string Formatted URL
	 */
	public function getTokenUrl( string $page, string $token ): string {
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
	 * @deprecated since 1.41, emits deprecation warnings since 1.43.
	 *   Use EmailUser::canSend() instead.
	 * @return bool
	 */
	public function canSendEmail() {
		wfDeprecated( __METHOD__, '1.41' );
		$permError = MediaWikiServices::getInstance()->getEmailUserFactory()
			->newEmailUser( $this->getThisAsAuthority() )
			->canSend();
		return $permError->isGood();
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
		$confirmed = true;
		if ( $this->getHookRunner()->onEmailConfirmed( $this, $confirmed ) ) {
			return !$this->isAnon() &&
				Sanitizer::validateEmail( $this->getEmail() ) &&
				( !$emailAuthentication || $this->getEmailAuthenticationTimestamp() );
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
	 * @return string|false|null Timestamp of account creation, false for
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
	 * Get the description of a given right as wikitext
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
	 * Get the description of a given right as rendered HTML
	 *
	 * @since 1.42
	 * @param string $right Right to query
	 * @return string HTML description of the right
	 */
	public static function getRightDescriptionHtml( $right ) {
		return wfMessage( "right-$right" )->parse();
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new user object.
	 * @since 1.31
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *     or `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *     or `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 *     or `SelectQueryBuilder::joinConds`
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
	 * Get a SelectQueryBuilder with the tables, fields and join conditions
	 * needed to create a new User object.
	 *
	 * The return value is a plain SelectQueryBuilder, not a UserSelectQueryBuilder.
	 * That way, there is no need for an ActorStore.
	 *
	 * @return SelectQueryBuilder
	 */
	public static function newQueryBuilder( IReadableDatabase $db ) {
		return $db->newSelectQueryBuilder()
			->select( [
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
			] )
			->from( 'user' )
			->join( 'actor', 'user_actor', 'user_actor.actor_user = user_id' );
	}

	/**
	 * Factory function for fatal permission-denied errors
	 *
	 * @since 1.22
	 * @deprecated since 1.41, use Authority::isAllowed instead.
	 * Core code can also use PermissionManager::newFatalPermissionDeniedStatus.
	 *
	 * @param string $permission User right required
	 * @return Status
	 */
	public static function newFatalPermissionDeniedStatus( $permission ) {
		return Status::wrap( MediaWikiServices::getInstance()->getPermissionManager()
			->newFatalPermissionDeniedStatus(
				$permission,
				RequestContext::getMain()
			) );
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
	public function probablyCan(
		string $action,
		PageIdentity $target,
		?PermissionStatus $status = null
	): bool {
		return $this->getThisAsAuthority()->probablyCan( $action, $target, $status );
	}

	/**
	 * @since 1.36
	 * @param string $action
	 * @param PageIdentity $target
	 * @param PermissionStatus|null $status
	 * @return bool
	 */
	public function definitelyCan(
		string $action,
		PageIdentity $target,
		?PermissionStatus $status = null
	): bool {
		return $this->getThisAsAuthority()->definitelyCan( $action, $target, $status );
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.41
	 * @param string $action
	 * @param PermissionStatus|null $status
	 * @return bool
	 */
	public function isDefinitelyAllowed( string $action, ?PermissionStatus $status = null ): bool {
		return $this->getThisAsAuthority()->isDefinitelyAllowed( $action, $status );
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.41
	 * @param string $action
	 * @param PermissionStatus|null $status
	 * @return bool
	 */
	public function authorizeAction( string $action, ?PermissionStatus $status = null ): bool {
		return $this->getThisAsAuthority()->authorizeAction( $action, $status );
	}

	/**
	 * @since 1.36
	 * @param string $action
	 * @param PageIdentity $target
	 * @param PermissionStatus|null $status
	 * @return bool
	 */
	public function authorizeRead(
		string $action,
		PageIdentity $target,
		?PermissionStatus $status = null
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
	public function authorizeWrite(
		string $action, PageIdentity $target,
		?PermissionStatus $status = null
	): bool {
		return $this->getThisAsAuthority()->authorizeWrite( $action, $target, $status );
	}

	/**
	 * Returns the Authority of this User if it's the main request context user.
	 * This is intended to exist only for the period of transition to Authority.
	 */
	private function getThisAsAuthority(): UserAuthority {
		if ( !$this->mThisAsAuthority ) {
			// TODO: For users that are not User::isGlobalSessionUser,
			// creating a UserAuthority here is incorrect, since it depends
			// on global WebRequest, but that is what we've used to do before Authority.
			// When PermissionManager is refactored into Authority, we need
			// to provide base implementation, based on just user groups/rights,
			// and use it here.
			$request = $this->getRequest();
			$uiContext = RequestContext::getMain();

			$services = MediaWikiServices::getInstance();
			$this->mThisAsAuthority = new UserAuthority(
				$this,
				$request,
				$uiContext,
				$services->getPermissionManager(),
				$services->getRateLimiter(),
				$services->getFormatterFactory()->getBlockErrorFormatter( $uiContext )
			);
		}

		return $this->mThisAsAuthority;
	}

	/**
	 * Check whether this is the global session user.
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
	 * @since 1.39
	 * @return bool
	 */
	public function isTemp(): bool {
		if ( $this->isTemp === null ) {
			$this->isTemp = MediaWikiServices::getInstance()->getUserIdentityUtils()
				->isTemp( $this );
		}
		return $this->isTemp;
	}

	/**
	 * Is the user a normal non-temporary registered user?
	 *
	 * @since 1.39
	 * @return bool
	 */
	public function isNamed(): bool {
		return $this->isRegistered() && !$this->isTemp();
	}
}

/** @deprecated class alias since 1.41 */
class_alias( User::class, 'User' );
