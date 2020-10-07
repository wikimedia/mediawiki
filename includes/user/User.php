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
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\SystemBlock;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Session\SessionManager;
use MediaWiki\Session\Token;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;
use MediaWiki\User\UserOptionsLookup;
use Wikimedia\IPSet;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DBExpectedError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\ScopedCallback;

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
 * @newable
 * @note marked as newable in 1.35 because the canonical way to construct an
 *       anonymous (IP) user is to call the constructor directly. A factory
 *       method for that purpose should be added to TitleFactory, see T257464.
 */
class User implements IDBAccessObject, UserIdentity {
	use ProtectedHookAccessorTrait;

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
	 * {@link $mCacheVars} or one of it's members changes.
	 */
	private const VERSION = 16;

	/**
	 * Exclude user options that are set to their default value.
	 * @deprecated since 1.35 Use UserOptionsLookup::EXCLUDE_DEFAULTS
	 * @since 1.25
	 */
	public const GETOPTIONS_EXCLUDE_DEFAULTS = UserOptionsLookup::EXCLUDE_DEFAULTS;

	/**
	 * @since 1.27
	 */
	public const CHECK_USER_RIGHTS = true;

	/**
	 * @since 1.27
	 */
	public const IGNORE_USER_RIGHTS = false;

	/**
	 * Array of Strings List of member variables which are saved to the
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
		'mEditCount',
		// actor table
		'mActorId',
	];

	/** Cache variables */
	// @{
	/** @var int */
	public $mId;
	/** @var string */
	public $mName;
	/** @var int|null */
	protected $mActorId;
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
	/** @var string */
	protected $mDatePreference;
	/**
	 * @deprecated since 1.35. Instead, use User::getBlock to get the block,
	 *  then AbstractBlock::getByName to get the blocker's name; or use the
	 *  GetUserBlock hook to set or unset a block.
	 * @var string|int -1 when the block is unset
	 */
	public $mBlockedby;
	/** @var string */
	protected $mHash;
	/**
	 * TODO: This should be removed when User::BlockedFor
	 * and AbstractBlock::getReason are hard deprecated.
	 * @var string
	 */
	protected $mBlockreason;
	/** @var AbstractBlock */
	protected $mGlobalBlock;
	/** @var bool */
	protected $mLocked;
	/**
	 * @deprecated since 1.35. Instead, use User::getBlock to get the block,
	 *  then AbstractBlock::getHideName to determine whether the block hides
	 *  the user; or use the GetUserBlock hook to hide or unhide a user.
	 * @var bool
	 */
	public $mHideName;

	/** @var WebRequest */
	private $mRequest;

	/**
	 * @deprecated since 1.35. Instead, use User::getBlock to get the block;
	 *  or the GetUserBlock hook to set or unset a block.
	 * @var AbstractBlock|null
	 */
	public $mBlock;

	/** @var bool */
	protected $mAllowUsertalk;

	/** @var AbstractBlock|bool */
	private $mBlockedFromCreateAccount = false;

	/** @var int User::READ_* constant bitfield used to load data */
	protected $queryFlagsUsed = self::READ_NORMAL;

	/** @var int[] */
	public static $idCacheByName = [];

	/**
	 * Lightweight constructor for an anonymous user.
	 * Use the User::newFrom* factory functions for other kinds of users.
	 *
	 * @stable to call
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
	 * @return string
	 */
	public function __toString() {
		return (string)$this->getName();
	}

	public function &__get( $name ) {
		// A shortcut for $mRights deprecation phase
		if ( $name === 'mRights' ) {
			$copy = $this->getRights();
			return $copy;
		} elseif ( $name === 'mOptions' ) {
			wfDeprecated( 'User::$mOptions', '1.35' );
			$options = $this->getOptions();
			return $options;
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
				$value === null ? [] : $value
			);
		} elseif ( $name === 'mOptions' ) {
			wfDeprecated( 'User::$mOptions', '1.35' );
			MediaWikiServices::getInstance()->getUserOptionsManager()->clearUserOptionsCache( $this );
			foreach ( $value as $key => $val ) {
				$this->setOption( $key, $val );
			}
		} elseif ( !property_exists( $this, $name ) ) {
			$this->$name = $value;
		} else {
			wfLogWarning( 'tried to set non-visible property' );
		}
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
			case 'id':
				// Make sure this thread sees its own changes, if the ID isn't 0
				if ( $this->mId != 0 ) {
					$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
					if ( $lb->hasOrMadeRecentMasterChanges() ) {
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
				if ( $lb->hasOrMadeRecentMasterChanges() ) {
					$flags |= self::READ_LATEST;
					$this->queryFlagsUsed = $flags;
				}

				list( $index, $options ) = DBAccessObjectUtils::getDBOptions( $flags );
				$row = wfGetDB( $index )->selectRow(
					'actor',
					[ 'actor_id', 'actor_user', 'actor_name' ],
					$this->mFrom === 'name' ? [ 'actor_name' => $this->mName ] : [ 'actor_id' => $this->mActorId ],
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

				$ttl = $cache->adaptiveTTL( wfTimestamp( TS_UNIX, $this->mTouched ), $ttl );

				if ( $wgFullyInitialised ) {
					$groupMemberships = MediaWikiServices::getInstance()
						->getUserGroupManager()
						->getUserGroupMemberships( $this, $this->queryFlagsUsed );

					// if a user group membership is about to expire, the cache needs to
					// expire at that time (T163691)
					foreach ( $groupMemberships as $ugm ) {
						if ( $ugm->getExpiry() ) {
							$secondsUntilExpiry =
								wfTimestamp( TS_UNIX, $ugm->getExpiry() ) - time();

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
		}

		// Create unloaded user object
		$u = new User;
		$u->mName = $name;
		$u->mFrom = 'name';
		$u->setItemLoaded( 'name' );

		return $u;
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
	 * Static factory method for creation from a given actor ID.
	 *
	 * @since 1.31
	 * @param int $id Valid actor ID
	 * @return User The corresponding User object
	 */
	public static function newFromActorId( $id ) {
		$u = new User;
		$u->mActorId = $id;
		$u->mFrom = 'actor';
		$u->setItemLoaded( 'actor' );
		return $u;
	}

	/**
	 * Returns a User object corresponding to the given UserIdentity.
	 *
	 * @since 1.32
	 *
	 * @param UserIdentity $identity
	 *
	 * @return User
	 */
	public static function newFromIdentity( UserIdentity $identity ) {
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
	 * @since 1.31
	 * @param int|null $userId User ID, if known
	 * @param string|null $userName User name, if known
	 * @param int|null $actorId Actor ID, if known
	 * @param bool|string $dbDomain remote wiki to which the User/Actor ID applies, or false if none
	 * @return User
	 */
	public static function newFromAnyId( $userId, $userName, $actorId, $dbDomain = false ) {
		// Stop-gap solution for the problem described in T222212.
		// Force the User ID and Actor ID to zero for users loaded from the database
		// of another wiki, to prevent subtle data corruption and confusing failure modes.
		if ( $dbDomain !== false ) {
			$userId = 0;
			$actorId = 0;
		}

		$user = new User;
		$user->mFrom = 'defaults';

		if ( $actorId !== null ) {
			$user->mActorId = (int)$actorId;
			if ( $user->mActorId !== 0 ) {
				$user->mFrom = 'actor';
			}
			$user->setItemLoaded( 'actor' );
		}

		if ( $userName !== null && $userName !== '' ) {
			$user->mName = $userName;
			$user->mFrom = 'name';
			$user->setItemLoaded( 'name' );
		}

		if ( $userId !== null ) {
			$user->mId = (int)$userId;
			if ( $user->mId !== 0 ) {
				$user->mFrom = 'id';
			}
			$user->setItemLoaded( 'id' );
		}

		if ( $user->mFrom === 'defaults' ) {
			throw new InvalidArgumentException(
				'Cannot create a user with no name, no ID, and no actor ID'
			);
		}

		return $user;
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
			],
			__METHOD__
		);

		return $id ? self::newFromId( $id ) : null;
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

		$dbr = wfGetDB( DB_REPLICA );
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
			// Try the master database...
			$dbw = wfGetDB( DB_MASTER );
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
			// @phan-suppress-next-line PhanImpossibleCondition
			if ( !$options['create'] ) {
				// No.
				return null;
			}

			// If it's a reserved user that had an anonymous actor created for it at
			// some point, we need special handling.
			if ( !self::isValidUserName( $name ) || self::isUsableName( $name ) ) {
				// Not reserved, so just create it.
				return self::createNew( $name, [ 'token' => self::INVALID_TOKEN ] );
			}

			// It is reserved. Check for an anonymous actor row.
			$dbw = wfGetDB( DB_MASTER );
			return $dbw->doAtomicSection( __METHOD__, function ( IDatabase $dbw, $fname ) use ( $name ) {
				$row = $dbw->selectRow(
					'actor',
					[ 'actor_id' ],
					[ 'actor_name' => $name, 'actor_user' => null ],
					$fname,
					[ 'FOR UPDATE' ]
				);
				if ( !$row ) {
					// No anonymous actor.
					return self::createNew( $name, [ 'token' => self::INVALID_TOKEN ] );
				}

				// There is an anonymous actor. Delete the actor row so we can create the user,
				// then restore the old actor_id so as to not break existing references.
				// @todo If MediaWiki ever starts using foreign keys for `actor`, this will break things.
				$dbw->delete( 'actor', [ 'actor_id' => $row->actor_id ], $fname );
				$user = self::createNew( $name, [ 'token' => self::INVALID_TOKEN ] );
				$dbw->update(
					'actor',
					[ 'actor_id' => $row->actor_id ],
					[ 'actor_id' => $user->getActorId() ],
					$fname
				);
				$user->clearInstanceCache( 'id' );
				$user->invalidateCache();
				return $user;
			} );
		}

		$user = self::newFromRow( $row );

		if ( !$user->isSystemUser() ) {
			// User exists. Steal it?
			// @phan-suppress-next-line PhanRedundantCondition
			if ( !$options['steal'] ) {
				return null;
			}

			MediaWikiServices::getInstance()->getAuthManager()->revokeAccessForUser( $name );

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
	 * @param int $flags User::READ_* constant bitfield
	 * @return int|null The corresponding user's ID, or null if user is nonexistent
	 */
	public static function idFromName( $name, $flags = self::READ_NORMAL ) {
		// Don't explode on self::$idCacheByName[$name] if $name is not a string but e.g. a User object
		$name = (string)$name;
		$nt = Title::makeTitleSafe( NS_USER, $name );
		if ( $nt === null ) {
			// Illegal name
			return null;
		}

		if ( !( $flags & self::READ_LATEST ) && array_key_exists( $name, self::$idCacheByName ) ) {
			return self::$idCacheByName[$name] === null ? null : (int)self::$idCacheByName[$name];
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
			$result = (int)$s->user_id;
		}

		if ( count( self::$idCacheByName ) >= 1000 ) {
			self::$idCacheByName = [];
		}

		self::$idCacheByName[$name] = $result;

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
	 * @deprecated since 1.35, use the UserNameUtils service
	 *    Note that UserNameUtils::isIP does not accept IPv6 ranges, while this method does
	 * @param string $name Name to match
	 * @return bool
	 */
	public static function isIP( $name ) {
		return preg_match( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.(?:xxx|\d{1,3})$/', $name )
			|| IPUtils::isIPv6( $name );
	}

	/**
	 * Is the user an IP range?
	 *
	 * @deprecated since 1.35, use the UserNameUtils service or IPUtils directly
	 * @since 1.30
	 * @return bool
	 */
	public function isIPRange() {
		return IPUtils::isValidRange( $this->mName );
	}

	/**
	 * Is the input a valid username?
	 *
	 * Checks if the input is a valid username, we don't want an empty string,
	 * an IP address, anything that contains slashes (would mess up subpages),
	 * is longer than the maximum allowed username size or doesn't begin with
	 * a capital letter.
	 *
	 * @deprecated since 1.35, use the UserNameUtils service
	 * @param string $name Name to match
	 * @return bool
	 */
	public static function isValidUserName( $name ) {
		return MediaWikiServices::getInstance()->getUserNameUtils()->isValid( $name );
	}

	/**
	 * Usernames which fail to pass this function will be blocked
	 * from user login and new account registrations, but may be used
	 * internally by batch processes.
	 *
	 * If an account already exists in this form, login will be blocked
	 * by a failure to pass this function.
	 *
	 * @deprecated since 1.35, use the UserNameUtils service
	 * @param string $name Name to match
	 * @return bool
	 */
	public static function isUsableName( $name ) {
		return MediaWikiServices::getInstance()->getUserNameUtils()->isUsable( $name );
	}

	/**
	 * Return the users who are members of the given group(s). In case of multiple groups,
	 * users who are members of at least one of them are returned.
	 *
	 * @param string|array $groups A single group name or an array of group names
	 * @param int $limit Max number of users to return. The actual limit will never exceed 5000
	 *   records; larger values are ignored.
	 * @param int|null $after ID the user to start after
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
	 * @deprecated since 1.35, use the UserNameUtils service
	 * @param string $name String to match
	 * @return bool
	 */
	public static function isCreatableName( $name ) {
		return MediaWikiServices::getInstance()->getUserNameUtils()->isCreatable( $name );
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
		global $wgPasswordPolicy;

		$upp = new UserPasswordPolicy(
			$wgPasswordPolicy['policies'],
			$wgPasswordPolicy['checks']
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
	 * Given unvalidated user input, return a canonical username, or false if
	 * the username is invalid.
	 *
	 * @deprecated since 1.35, use the UserNameUtils service
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
		// Backwards compatibility with strings / false
		$validationLevels = [
			'valid' => UserNameUtils::RIGOR_VALID,
			'usable' => UserNameUtils::RIGOR_USABLE,
			'creatable' => UserNameUtils::RIGOR_CREATABLE
		];

		if ( $validate === false ) {
			$validation = UserNameUtils::RIGOR_NONE;
		} elseif ( array_key_exists( $validate, $validationLevels ) ) {
			$validation = $validationLevels[ $validate ];
		} else {
			// Not a recognized value, probably a test for unsupported validation
			// levels, regardless, just pass it along
			$validation = $validate;
		}

		return MediaWikiServices::getInstance()
			->getUserNameUtils()
			->getCanonical( (string)$name, $validation );
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
	 * Set the 'BlockID' cookie depending on block type and user authentication status.
	 *
	 * @deprecated since 1.34 Use BlockManager::trackBlockWithCookie instead
	 */
	public function trackBlockWithCookie() {
		wfDeprecated( __METHOD__, '1.34' );
		// Obsolete.
		// MediaWiki::preOutputCommit() handles this whenever possible.
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

		list( $index, $options ) = DBAccessObjectUtils::getDBOptions( $flags );
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
		$this->getHookRunner()->onUserLoadFromDatabase( $this, $s );

		if ( $s !== false ) {
			// Initialise user table data
			$this->loadFromRow( $s );
			$this->getEditCount(); // revalidation for nulls
			return true;
		}

		// Invalid user_id
		$this->mId = 0;
		$this->loadDefaults();

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
	 *  user_properties   Array with properties out of the user_properties table
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

		if ( isset( $row->user_id ) && isset( $row->user_name ) && $row->user_name !== '' ) {
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
				MediaWikiServices::getInstance()
					->getUserGroupManager()
					->loadGroupMembershipsFromArray(
						$this,
						$data['user_groups'],
						$this->queryFlagsUsed
					);
			}
			if ( isset( $data['user_properties'] ) && is_array( $data['user_properties'] ) ) {
				MediaWikiServices::getInstance()
					->getUserOptionsManager()
					->loadUserOptions( $this, $this->queryFlagsUsed, $data['user_properties'] );
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
	 * @deprecated since 1.35 Use UserGroupManager::addUserToAutopromoteOnceGroups
	 * @see $wgAutopromoteOnce
	 */
	public function addAutopromoteOnceGroups( $event ) {
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->addUserToAutopromoteOnceGroups( $this, $event );
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
		$this->mEditCount = null;

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
	 * Combine the language default options with any site-specific options
	 * and add the default language variants.
	 *
	 * @deprecated since 1.35 Use UserOptionsLookup::getDefaultOptions instead.
	 * @return array Array of options; typically strings, possibly booleans
	 */
	public static function getDefaultOptions() {
		return MediaWikiServices::getInstance()
			->getUserOptionsLookup()
			->getDefaultOptions();
	}

	/**
	 * Get a given default option value.
	 *
	 * @deprecated since 1.35 Use UserOptionsLookup::getDefaultOption instead.
	 * @param string $opt Name of option to retrieve
	 * @return string|null Default option value
	 */
	public static function getDefaultOption( $opt ) {
		return MediaWikiServices::getInstance()
			->getUserOptionsLookup()
			->getDefaultOption( $opt );
	}

	/**
	 * Get blocking information
	 *
	 * TODO: Move this into the BlockManager, along with block-related properties.
	 *
	 * @param bool $fromReplica Whether to check the replica DB first.
	 *   To improve performance, non-critical checks are done against replica DBs.
	 *   Check when actually saving should be done against master.
	 */
	private function getBlockedStatus( $fromReplica = true ) {
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

		// The session user is set up towards the end of Setup.php. Until then,
		// assume it's a logged-out user.
		$sessionUser = RequestContext::getMain()->getUser();
		$globalUserName = $sessionUser->isSafeToLoad()
			? $sessionUser->getName()
			: IPUtils::sanitizeIP( $sessionUser->getRequest()->getIP() );

		if ( $this->getName() === $globalUserName ) {
			// This is the global user, so we need to pass the request
			$request = $this->getRequest();
		}

		$block = MediaWikiServices::getInstance()->getBlockManager()->getUserBlock(
			$this,
			$request,
			$fromReplica
		);

		if ( $block ) {
			$this->mBlock = $block;
			$this->mBlockedby = $block->getByName();
			$this->mBlockreason = $block->getReason();
			$this->mHideName = $block->getHideName();
			$this->mAllowUsertalk = $block->isUsertalkEditAllowed();
		} else {
			$this->mBlock = null;
			$this->mBlockedby = '';
			$this->mBlockreason = '';
			$this->mHideName = 0;
			$this->mAllowUsertalk = false;
		}
	}

	/**
	 * Whether the given IP is in a DNS blacklist.
	 *
	 * @deprecated since 1.34 Use BlockManager::isDnsBlacklisted.
	 * @param string $ip IP to check
	 * @param bool $checkWhitelist Whether to check the whitelist first
	 * @return bool True if blacklisted.
	 */
	public function isDnsBlacklisted( $ip, $checkWhitelist = false ) {
		wfDeprecated( __METHOD__, '1.34' );
		return MediaWikiServices::getInstance()->getBlockManager()
			->isDnsBlacklisted( $ip, $checkWhitelist );
	}

	/**
	 * Whether the given IP is in a given DNS blacklist.
	 *
	 * @deprecated since 1.34 Check via BlockManager::isDnsBlacklisted instead.
	 * @param string $ip IP to check
	 * @param string|array $bases Array of Strings: URL of the DNS blacklist
	 * @return bool True if blacklisted.
	 */
	public function inDnsBlacklist( $ip, $bases ) {
		wfDeprecated( __METHOD__, '1.34' );

		$found = false;
		// @todo FIXME: IPv6 ???  (https://bugs.php.net/bug.php?id=33170)
		if ( IPUtils::isIPv4( $ip ) ) {
			// Reverse IP, T23255
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
				}

				wfDebugLog( 'dnsblacklist', "Requested $host, not found in $basename." );
			}
		}

		return $found;
	}

	/**
	 * Check if an IP address is in the local proxy list
	 *
	 * @deprecated since 1.34 Use BlockManager::getUserBlock instead.
	 * @param string $ip
	 * @return bool
	 */
	public static function isLocallyBlockedProxy( $ip ) {
		wfDeprecated( __METHOD__, '1.34' );

		global $wgProxyList;

		if ( !$wgProxyList ) {
			return false;
		}

		if ( !is_array( $wgProxyList ) ) {
			// Load values from the specified file
			$wgProxyList = array_map( 'trim', file( $wgProxyList ) );
		}

		$proxyListIPSet = new IPSet( $wgProxyList );
		return $proxyListIPSet->match( $ip );
	}

	/**
	 * Is this user subject to rate limiting?
	 *
	 * @return bool True if rate limited
	 */
	public function isPingLimitable() {
		global $wgRateLimitsExcludedIPs;
		if ( IPUtils::isInRanges( $this->getRequest()->getIP(), $wgRateLimitsExcludedIPs ) ) {
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
		$logger = \MediaWiki\Logger\LoggerFactory::getInstance( 'ratelimit' );

		// Call the 'PingLimiter' hook
		$result = false;
		if ( !$this->getHookRunner()->onPingLimiter( $this, $action, $result, $incrBy ) ) {
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

		$logger->debug( __METHOD__ . ": limiting $action rate for {$this->getName()}" );

		$keys = [];
		$id = $this->getId();
		$isNewbie = $this->isNewbie();
		$cache = ObjectCache::getLocalClusterInstance();

		if ( $id == 0 ) {
			// "shared anon" limit, for all anons combined
			if ( isset( $limits['anon'] ) ) {
				$keys[$cache->makeKey( 'limiter', $action, 'anon' )] = $limits['anon'];
			}
		} else {
			// "global per name" limit, across sites
			if ( isset( $limits['user-global'] ) ) {
				$lookup = CentralIdLookup::factoryNonLocal();

				$centralId = $lookup
					? $lookup->centralIdFromLocalUser( $this, CentralIdLookup::AUDIENCE_RAW )
					: 0;

				if ( $centralId ) {
					// We don't have proper realms, use provider ID.
					$realm = $lookup->getProviderId();

					$globalKey = $cache->makeGlobalKey( 'limiter', $action, 'user-global',
						$realm, $centralId );
				} else {
					// Fall back to a local key for a local ID
					$globalKey = $cache->makeKey( 'limiter', $action, 'user-global',
						'local', $id );
				}
				$keys[$globalKey] = $limits['user-global'];
			}
		}

		if ( $isNewbie ) {
			// "per ip" limit for anons and newbie users
			if ( isset( $limits['ip'] ) ) {
				$ip = $this->getRequest()->getIP();
				$keys[$cache->makeGlobalKey( 'limiter', $action, 'ip', $ip )] = $limits['ip'];
			}
			// "per subnet" limit for anons and newbie users
			if ( isset( $limits['subnet'] ) ) {
				$ip = $this->getRequest()->getIP();
				$subnet = IPUtils::getSubnet( $ip );
				if ( $subnet !== false ) {
					$keys[$cache->makeGlobalKey( 'limiter', $action, 'subnet', $subnet )] = $limits['subnet'];
				}
			}
		}

		// determine the "per user account" limit
		$userLimit = false;
		if ( $id !== 0 && isset( $limits['user'] ) ) {
			// default limit for logged-in users
			$userLimit = $limits['user'];
		}
		// limits for newbie logged-in users (overrides all the normal user limits)
		if ( $id !== 0 && $isNewbie && isset( $limits['newbie'] ) ) {
			$userLimit = $limits['newbie'];
		} else {
			// Check for group-specific limits
			// If more than one group applies, use the highest allowance (if higher than the default)
			foreach ( $this->getGroups() as $group ) {
				if ( isset( $limits[$group] ) ) {
					if ( $userLimit === false
						|| $limits[$group][0] / $limits[$group][1] > $userLimit[0] / $userLimit[1]
					) {
						$userLimit = $limits[$group];
					}
				}
			}
		}

		// Set the user limit key
		if ( $userLimit !== false ) {
			// phan is confused because &can-bypass's value is a bool, so it assumes
			// that $userLimit is also a bool here.
			// @phan-suppress-next-line PhanTypeInvalidExpressionArrayDestructuring
			list( $max, $period ) = $userLimit;
			$logger->debug( __METHOD__ . ": effective user limit: $max in {$period}s" );
			$keys[$cache->makeKey( 'limiter', $action, 'user', $id )] = $userLimit;
		}

		// ip-based limits for all ping-limitable users
		if ( isset( $limits['ip-all'] ) ) {
			$ip = $this->getRequest()->getIP();
			// ignore if user limit is more permissive
			if ( $isNewbie || $userLimit === false
				|| $limits['ip-all'][0] / $limits['ip-all'][1] > $userLimit[0] / $userLimit[1] ) {
				$keys[$cache->makeGlobalKey( 'limiter', $action, 'ip-all', $ip )] = $limits['ip-all'];
			}
		}

		// subnet-based limits for all ping-limitable users
		if ( isset( $limits['subnet-all'] ) ) {
			$ip = $this->getRequest()->getIP();
			$subnet = IPUtils::getSubnet( $ip );
			if ( $subnet !== false ) {
				// ignore if user limit is more permissive
				if ( $isNewbie || $userLimit === false
					|| $limits['ip-all'][0] / $limits['ip-all'][1]
					> $userLimit[0] / $userLimit[1] ) {
					$keys[$cache->makeGlobalKey( 'limiter', $action, 'subnet-all', $subnet )] = $limits['subnet-all'];
				}
			}
		}

		// XXX: We may want to use $cache->getCurrentTime() here, but that would make it
		//      harder to test for T246991. Also $cache->getCurrentTime() is documented
		//      as being for testing only, so it apparently should not be called here.
		$now = MWTimestamp::time();
		$clockFudge = 3; // avoid log spam when a clock is slightly off

		$triggered = false;
		foreach ( $keys as $key => $limit ) {

			// Do the update in a merge callback, for atomicity.
			// To use merge(), we need to explicitly track the desired expiry timestamp.
			// This tracking was introduced to investigate T246991. Once it is no longer needed,
			// we could go back to incrWithInit(), though that has more potential for race
			// conditions between the get() and incrWithInit() calls.
			$cache->merge(
				$key,
				function ( $cache, $key, $data, &$expiry )
					use ( $action, $logger, &$triggered, $now, $clockFudge, $limit, $incrBy )
				{
					// phan is confused because &can-bypass's value is a bool, so it assumes
					// that $userLimit is also a bool here.
					// @phan-suppress-next-line PhanTypeInvalidExpressionArrayDestructuring
					list( $max, $period ) = $limit;

					$expiry = $now + (int)$period;
					$count = 0;

					// Already pinged?
					if ( $data ) {
						// NOTE: in order to investigate T246991, we write the expiry time
						//       into the payload, along with the count.
						$fields = explode( '|', $data );
						$storedCount = (int)( $fields[0] ?? 0 );
						$storedExpiry = (int)( $fields[1] ?? PHP_INT_MAX );

						// Found a stale entry. This should not happen!
						if ( $storedExpiry < ( $now + $clockFudge ) ) {
							$logger->info(
								'User::pingLimiter: '
								. 'Stale rate limit entry, cache key failed to expire (T246991)',
								[
									'action' => $action,
									'user' => $this->getName(),
									'limit' => $max,
									'period' => $period,
									'count' => $storedCount,
									'key' => $key,
									'expiry' => MWTimestamp::convert( TS_DB, $storedExpiry ),
								]
							);
						} else {
							// NOTE: We'll keep the original expiry when bumping counters,
							//       resulting in a kind of fixed-window throttle.
							$expiry = min( $storedExpiry, $now + (int)$period );
							$count = $storedCount;
						}
					}

					// Limit exceeded!
					if ( $count >= $max ) {
						if ( !$triggered ) {
							$logger->info(
								'User::pingLimiter: User tripped rate limit',
								[
									'action' => $action,
									'user' => $this->getName(),
									'ip' => $this->getRequest()->getIP(),
									'limit' => $max,
									'period' => $period,
									'count' => $count,
									'key' => $key
								]
							);
						}

						$triggered = true;
					}

					$count += $incrBy;
					$data = "$count|$expiry";
					return $data;
				}
			);
		}

		return $triggered;
	}

	/**
	 * Check if user is blocked
	 *
	 * @deprecated since 1.34, use User::getBlock() or
	 *             PermissionManager::isBlockedFrom() or
	 *             PermissionManager::userCan() instead.
	 *
	 * @param bool $fromReplica Whether to check the replica DB instead of
	 *   the master. Hacked from false due to horrible probs on site.
	 * @return bool True if blocked, false otherwise
	 */
	public function isBlocked( $fromReplica = true ) {
		return $this->getBlock( $fromReplica ) instanceof AbstractBlock;
	}

	/**
	 * Get the block affecting the user, or null if the user is not blocked
	 *
	 * @param bool $fromReplica Whether to check the replica DB instead of the master
	 * @return AbstractBlock|null
	 */
	public function getBlock( $fromReplica = true ) {
		$this->getBlockedStatus( $fromReplica );
		return $this->mBlock instanceof AbstractBlock ? $this->mBlock : null;
	}

	/**
	 * Check if user is blocked from editing a particular article
	 *
	 * @param Title $title Title to check
	 * @param bool $fromReplica Whether to check the replica DB instead of the master
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
	 */
	public function blockedBy() {
		$this->getBlockedStatus();
		return $this->mBlockedby;
	}

	/**
	 * If user is blocked, return the specified reason for the block.
	 *
	 * @deprecated since 1.35 Use AbstractBlock::getReasonComment instead
	 * @return string Blocking reason
	 */
	public function blockedFor() {
		$this->getBlockedStatus();
		return $this->mBlockreason;
	}

	/**
	 * If user is blocked, return the ID for the block
	 * @return int|false
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
	 * @return int The user's ID; 0 if the user is anonymous or nonexistent
	 */
	public function getId() {
		if ( $this->mId === null && $this->mName !== null &&
			( self::isIP( $this->mName ) || ExternalUserNames::isExternal( $this->mName ) )
		) {
			// Special case, we know the user is anonymous
			return 0;
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
	public function getName() {
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
	 * @param IDatabase|null $dbw Assign a new actor ID, using this DB handle, if none exists
	 * @return int The actor's ID, or 0 if no actor ID exists and $dbw was null
	 */
	public function getActorId( IDatabase $dbw = null ) {
		if ( !$this->isItemLoaded( 'actor' ) ) {
			$this->load();
		}

		if ( !$this->mActorId && $dbw ) {
			$migration = MediaWikiServices::getInstance()->getActorMigration();
			$this->mActorId = $migration->getNewActorId( $dbw, $this );

			$this->invalidateCache();
			$this->setItemLoaded( 'actor' );
		}

		return (int)$this->mActorId;
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
	 * @deprecated since 1.35 Use TalkPageNotificationManager::userHasNewMessages instead
	 * @return bool True if the user has new messages
	 */
	public function getNewtalk() {
		wfDeprecated( __METHOD__, '1.35' );
		return MediaWikiServices::getInstance()
			->getTalkPageNotificationManager()
			->userHasNewMessages( $this );
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
	 *
	 * @deprecated since 1.35
	 *
	 * @note This function was designed to accomodate multiple talk pages, but
	 * currently only returns a single link and revision.
	 * @return array[]
	 */
	public function getNewMessageLinks() {
		wfDeprecated( __METHOD__, '1.35' );
		$talks = [];
		if ( !$this->getHookRunner()->onUserRetrieveNewTalks( $this, $talks ) ) {
			return $talks;
		}

		$services = MediaWikiServices::getInstance();
		$userHasNewMessages = $services->getTalkPageNotificationManager()
			->userHasNewMessages( $this );
		if ( !$userHasNewMessages ) {
			return [];
		}
		$utp = $this->getTalkPage();
		$timestamp = $services->getTalkPageNotificationManager()
			->getLatestSeenMessageTimestamp( $this );
		$rev = null;
		if ( $timestamp ) {
			$revRecord = $services->getRevisionLookup()
				->getRevisionByTimestamp( $utp, $timestamp );
			if ( $revRecord ) {
				$rev = new Revision( $revRecord );
			}
		}
		return [
			[
				'wiki' => WikiMap::getCurrentWikiId(),
				'link' => $utp->getLocalURL(),
				'rev' => $rev
			]
		];
	}

	/**
	 * Get the revision ID for the last talk page revision viewed by the talk
	 * page owner.
	 * @deprecated since 1.35
	 * @return int|null Revision ID or null
	 */
	public function getNewMessageRevisionId() {
		wfDeprecated( __METHOD__, '1.35' );
		$newMessageRevisionId = null;
		$newMessageLinks = $this->getNewMessageLinks();

		// Note: getNewMessageLinks() never returns more than a single link
		// and it is always for the same wiki, but we double-check here in
		// case that changes some time in the future.
		if ( $newMessageLinks && count( $newMessageLinks ) === 1
			&& WikiMap::isCurrentWikiId( $newMessageLinks[0]['wiki'] )
			&& $newMessageLinks[0]['rev']
		) {
			/** @var Revision $newMessageRevision */
			$newMessageRevision = $newMessageLinks[0]['rev'];
			$newMessageRevisionId = $newMessageRevision->getId();
		}

		return $newMessageRevisionId;
	}

	/**
	 * Update the 'You have new messages!' status.
	 * @param bool $val Whether the user has new messages
	 * @param RevisionRecord|Revision|null $curRev New, as yet unseen revision of the
	 *   user talk page. Ignored if null or !$val
	 * @deprecated since 1.35 Use TalkPageNotificationManager::setUserHasNewMessages or
	 *   TalkPageNotificationManager::removeUserHasNewMessages
	 */
	public function setNewtalk( $val, $curRev = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		if ( $curRev && $curRev instanceof Revision ) {
			$curRev = $curRev->getRevisionRecord();
		}
		if ( $val ) {
			MediaWikiServices::getInstance()
				->getTalkPageNotificationManager()
				->setUserHasNewMessages( $this, $curRev );
		} else {
			MediaWikiServices::getInstance()
				->getTalkPageNotificationManager()
				->removeUserHasNewMessages( $this );
		}
	}

	/**
	 * Generate a current or new-future timestamp to be stored in the
	 * user_touched field when we update things.
	 *
	 * @return string Timestamp in TS_MW format
	 */
	private function newTouchedTimestamp() {
		$time = time();
		if ( $this->mTouched ) {
			$time = max( $time, wfTimestamp( TS_UNIX, $this->mTouched ) + 1 );
		}

		return wfTimestamp( TS_MW, $time );
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
			$lb->getConnectionRef( DB_MASTER )->onTransactionPreCommitOrIdle(
				function () use ( $cache, $key ) {
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
		global $wgAuthenticationTokenVersion;

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

		if ( $wgAuthenticationTokenVersion === null ) {
			// $wgAuthenticationTokenVersion not in use, so return the raw secret
			return $this->mToken;
		}

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
	 * Get the user's e-mail address
	 * @return string User's email address
	 */
	public function getEmail() {
		$this->load();
		$this->getHookRunner()->onUserGetEmail( $this, $this->mEmail );
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
	public function setEmail( $str ) {
		$this->load();
		if ( $str == $this->mEmail ) {
			return;
		}
		$this->invalidateEmail();
		$this->mEmail = $str;
		$this->getHookRunner()->onUserSetEmail( $this, $this->mEmail );
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

		if ( $wgEmailAuthentication && $type === 'changed' ) {
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
	 * @param mixed|null $defaultOverride A default value returned if the option does not exist.
	 *   Default values set via $wgDefaultUserOptions / UserGetDefaultOptions take precedence.
	 * @param bool $ignoreHidden Whether to ignore the effects of $wgHiddenPrefs
	 * @return mixed|null User's current value for the option
	 * @see getBoolOption()
	 * @see getIntOption()
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
	 * Get all user's options
	 *
	 * @param int $flags Bitwise combination of:
	 *   User::GETOPTIONS_EXCLUDE_DEFAULTS  Exclude user options that are set
	 *                                      to the default value. (Since 1.25)
	 * @return array
	 * @deprecated since 1.35 Use UserOptionsLookup::getOptions instead
	 */
	public function getOptions( $flags = 0 ) {
		return MediaWikiServices::getInstance()
			->getUserOptionsLookup()
			->getOptions( $this, $flags );
	}

	/**
	 * Get the user's current setting for a given option, as a boolean value.
	 *
	 * @param string $oname The option to check
	 * @return bool User's current value for the option
	 * @see getOption()
	 * @deprecated since 1.35 Use UserOptionsLookup::getBoolOption instead
	 */
	public function getBoolOption( $oname ) {
		return MediaWikiServices::getInstance()
			->getUserOptionsLookup()
			->getBoolOption( $this, $oname );
	}

	/**
	 * Get the user's current setting for a given option, as an integer value.
	 *
	 * @param string $oname The option to check
	 * @param int $defaultOverride A default value returned if the option does not exist
	 * @return int User's current value for the option
	 * @see getOption()
	 * @deprecated since 1.35 Use UserOptionsLookup::getIntOption instead
	 */
	public function getIntOption( $oname, $defaultOverride = 0 ) {
		if ( $oname === null ) {
			return null; // b/c
		}
		return MediaWikiServices::getInstance()
			->getUserOptionsLookup()
			->getIntOption( $this, $oname, $defaultOverride );
	}

	/**
	 * Set the given option for a user.
	 *
	 * You need to call saveSettings() to actually write to the database.
	 *
	 * @param string $oname The option to set
	 * @param mixed $val New value to set
	 * @deprecated since 1.35 Use UserOptionsManager::setOption instead
	 */
	public function setOption( $oname, $val ) {
		MediaWikiServices::getInstance()
			->getUserOptionsManager()
			->setOption( $this, $oname, $val );
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
	 * @deprecated since 1.35 Use UserOptionsManager::listOptionKinds instead
	 */
	public static function listOptionKinds() {
		return MediaWikiServices::getInstance()
			->getUserOptionsManager()
			->listOptionKinds();
	}

	/**
	 * Return an associative array mapping preferences keys to the kind of a preference they're
	 * used for. Different kinds are handled differently when setting or reading preferences.
	 *
	 * See User::listOptionKinds for the list of valid option types that can be provided.
	 *
	 * @see User::listOptionKinds
	 * @param IContextSource $context
	 * @param array|null $options Assoc. array with options keys to check as keys.
	 *   Defaults to $this->mOptions.
	 * @return array The key => kind mapping data
	 * @deprecated since 1.35 Use UserOptionsManager::getOptionKinds instead
	 */
	public function getOptionKinds( IContextSource $context, $options = null ) {
		return MediaWikiServices::getInstance()
			->getUserOptionsManager()
			->getOptionKinds( $this, $context, $options );
	}

	/**
	 * Reset certain (or all) options to the site defaults
	 *
	 * The optional parameter determines which kinds of preferences will be reset.
	 * Supported values are everything that can be reported by getOptionKinds()
	 * and 'all', which forces a reset of *all* preferences and overrides everything else.
	 *
	 * @param array|string $resetKinds Which kinds of preferences to reset. Defaults to
	 *  [ 'registered', 'registered-multiselect', 'registered-checkmatrix', 'unused' ]
	 *  for backwards-compatibility.
	 * @param IContextSource|null $context Context source used when $resetKinds
	 *  does not contain 'all', passed to getOptionKinds().
	 *  Defaults to RequestContext::getMain() when null.
	 * @deprecated since 1.35 Use UserOptionsManager::resetOptions instead.
	 */
	public function resetOptions(
		$resetKinds = [ 'registered', 'registered-multiselect', 'registered-checkmatrix', 'unused' ],
		IContextSource $context = null
	) {
		MediaWikiServices::getInstance()
			->getUserOptionsManager()
			->resetOptions(
				$this,
				$context ?? RequestContext::getMain(),
				$resetKinds
			);
	}

	/**
	 * Get the user's preferred date format.
	 * @return string User's preferred date format
	 */
	public function getDatePreference() {
		// Important migration for old data rows
		if ( $this->mDatePreference === null ) {
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
		global $wgForceHTTPS, $wgSecureLogin;
		if ( $wgForceHTTPS ) {
			return true;
		}
		if ( !$wgSecureLogin ) {
			return false;
		}
		$https = $this->getBoolOption( 'prefershttps' );
		$this->getHookRunner()->onUserRequiresHTTPS( $this, $https );
		if ( $https ) {
			$https = wfCanIPUseHTTPS( $this->getRequest()->getIP() );
		}

		return $https;
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
	 * @return string[] permission names
	 *
	 * @deprecated since 1.34, use MediaWikiServices::getInstance()->getPermissionManager()
	 * ->getUserPermissions(..) instead
	 *
	 */
	public function getRights() {
		return MediaWikiServices::getInstance()->getPermissionManager()->getUserPermissions( $this );
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
	 * Get the list of implicit group memberships this user has.
	 * This includes all explicit groups, plus 'user' if logged in,
	 * '*' for all accounts, and autopromoted groups
	 *
	 * @deprecated since 1.35 Use UserGroupManager::getUserEffectiveGroups instead
	 *
	 * @param bool $recache Whether to avoid the cache
	 * @return array Array of String internal group names
	 */
	public function getEffectiveGroups( $recache = false ) {
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->getUserEffectiveGroups( $this, $this->queryFlagsUsed, $recache );
	}

	/**
	 * Get the list of implicit group memberships this user has.
	 * This includes 'user' if logged in, '*' for all accounts,
	 * and autopromoted groups
	 *
	 * @deprecated since 1.35 Use UserGroupManager::getUserImplicitGroups instead.
	 *
	 * @param bool $recache Whether to avoid the cache
	 * @return array Array of String internal group names
	 */
	public function getAutomaticGroups( $recache = false ) {
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->getUserImplicitGroups( $this, $this->queryFlagsUsed, $recache );
	}

	/**
	 * Returns the groups the user has belonged to.
	 *
	 * The user may still belong to the returned groups. Compare with getGroups().
	 *
	 * The function will not return groups the user had belonged to before MW 1.17
	 *
	 * @deprecated since 1.35 Use UserGroupManager::getUserFormerGroups instead.
	 *
	 * @return array Names of the groups the user has belonged to.
	 */
	public function getFormerGroups() {
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->getUserFormerGroups( $this, $this->queryFlagsUsed );
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
			$this->mEditCount = MediaWikiServices::getInstance()
				->getUserEditTracker()
				->getUserEditCount( $this );
		}
		return (int)$this->mEditCount;
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
	 * Alias of isLoggedIn() with a name that describes its actual functionality. UserIdentity has
	 * only this new name and not the old isLoggedIn() variant.
	 *
	 * @return bool True if user is registered on this wiki, i.e., has a user ID. False if user is
	 *   anonymous or has no local account (which can happen when importing). This is equivalent to
	 *   getId() != 0 and is provided for code readability.
	 * @since 1.34
	 */
	public function isRegistered() {
		return $this->getId() != 0;
	}

	/**
	 * Get whether the user is logged in
	 * @return bool
	 */
	public function isLoggedIn() {
		return $this->isRegistered();
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
		if ( in_array( 'bot', $this->getGroups() ) && $this->isAllowed( 'bot' ) ) {
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
		if ( $this->mEmail || $this->mToken !== self::INVALID_TOKEN ||
			MediaWikiServices::getInstance()->getAuthManager()->userCanAuthenticate( $this->mName )
		) {
			return false;
		}
		return true;
	}

	/**
	 * Check if user is allowed to access a feature / make an action
	 *
	 * @deprecated since 1.34, use MediaWikiServices::getInstance()
	 * ->getPermissionManager()->userHasAnyRights(...) instead
	 *
	 * @param string ...$permissions Permissions to test
	 * @return bool True if user is allowed to perform *any* of the given actions
	 */
	public function isAllowedAny( ...$permissions ) {
		return MediaWikiServices::getInstance()
			->getPermissionManager()
			->userHasAnyRight( $this, ...$permissions );
	}

	/**
	 * @deprecated since 1.34, use MediaWikiServices::getInstance()
	 * ->getPermissionManager()->userHasAllRights(...) instead
	 * @param string ...$permissions Permissions to test
	 * @return bool True if the user is allowed to perform *all* of the given actions
	 */
	public function isAllowedAll( ...$permissions ) {
		return MediaWikiServices::getInstance()
			->getPermissionManager()
			->userHasAllRights( $this, ...$permissions );
	}

	/**
	 * Internal mechanics of testing a permission
	 *
	 * @deprecated since 1.34, use MediaWikiServices::getInstance()
	 * ->getPermissionManager()->userHasRight(...) instead
	 *
	 * @param string $action
	 *
	 * @return bool
	 */
	public function isAllowed( $action = '' ) {
		return MediaWikiServices::getInstance()->getPermissionManager()
			->userHasRight( $this, $action );
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
		}

		global $wgRequest;
		return $wgRequest;
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
	 * Check if the article is temporarily watched.
	 * @since 1.35
	 * @internal This, isWatched() and related User methods may be deprecated soon (T208766).
	 *     If possible, implement permissions checks and call WatchedItemStore::isTempWatched()
	 * @param Title $title Title of the article to look at
	 * @param bool $checkRights Whether to check 'viewmywatchlist'/'editmywatchlist' rights.
	 *     Pass User::CHECK_USER_RIGHTS or User::IGNORE_USER_RIGHTS.
	 * @return bool
	 */
	public function isTempWatched( $title, $checkRights = self::CHECK_USER_RIGHTS ): bool {
		if ( $title->isWatchable() && ( !$checkRights || $this->isAllowed( 'viewmywatchlist' ) ) ) {
			return MediaWikiServices::getInstance()->getWatchedItemStore()
				->isTempWatched( $this, $title );
		}
		return false;
	}

	/**
	 * Watch an article.
	 * @since 1.22 $checkRights parameter added
	 * @param Title $title Title of the article to look at
	 * @param bool $checkRights Whether to check 'viewmywatchlist'/'editmywatchlist' rights.
	 *     Pass User::CHECK_USER_RIGHTS or User::IGNORE_USER_RIGHTS.
	 * @param string|null $expiry Optional expiry timestamp in any format acceptable to wfTimestamp(),
	 *   null will not create expiries, or leave them unchanged should they already exist.
	 */
	public function addWatch(
		$title,
		$checkRights = self::CHECK_USER_RIGHTS,
		?string $expiry = null
	) {
		if ( !$checkRights || $this->isAllowed( 'editmywatchlist' ) ) {
			$store = MediaWikiServices::getInstance()->getWatchedItemStore();
			$store->addWatch( $this, $title->getSubjectPage(), $expiry );
			$store->addWatch( $this, $title->getTalkPage(), $expiry );
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
	 *
	 * @deprecated since 1.35
	 *
	 * @note If the user doesn't have 'editmywatchlist', this will do nothing.
	 * @param Title &$title Title of the article to look at
	 * @param int $oldid The revision id being viewed. If not given or 0, latest revision is assumed.
	 */
	public function clearNotification( &$title, $oldid = 0 ) {
		MediaWikiServices::getInstance()
			->getWatchlistNotificationManager()
			->clearTitleUserNotifications( $this, $title, $oldid );
	}

	/**
	 * Resets all of the given user's page-change notification timestamps.
	 * If e-notif e-mails are on, they will receive notification mails on
	 * the next change of any watched page.
	 *
	 * @deprecated since 1.35
	 *
	 * @note If the user doesn't have 'editmywatchlist', this will do nothing.
	 */
	public function clearAllNotifications() {
		wfDeprecated( __METHOD__, '1.35' );
		MediaWikiServices::getInstance()
			->getWatchlistNotificationManager()
			->clearAllUserNotifications( $this );
	}

	/**
	 * Compute experienced level based on edit count and registration date.
	 *
	 * @return string 'newcomer', 'learner', or 'experienced'
	 */
	public function getExperienceLevel() {
		global $wgLearnerEdits,
			$wgExperiencedUserEdits,
			$wgLearnerMemberSince,
			$wgExperiencedUserMemberSince;

		if ( $this->isAnon() ) {
			return false;
		}

		$editCount = $this->getEditCount();
		$registration = $this->getRegistration();
		$now = time();
		$learnerRegistration = wfTimestamp( TS_MW, $now - $wgLearnerMemberSince * 86400 );
		$experiencedRegistration = wfTimestamp( TS_MW, $now - $wgExperiencedUserMemberSince * 86400 );

		if ( $editCount < $wgLearnerEdits ||
		$registration > $learnerRegistration ) {
			return 'newcomer';
		}

		if ( $editCount > $wgExperiencedUserEdits &&
			$registration <= $experiencedRegistration
		) {
			return 'experienced';
		}

		return 'learner';
	}

	/**
	 * Persist this user's session (e.g. set cookies)
	 *
	 * @param WebRequest|null $request WebRequest object to use; $wgRequest will be used if null
	 *        is passed.
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
		if ( $this->mId == 0 ) {
			return; // anon
		}

		// Get a new user_touched that is higher than the old one.
		// This will be used for a CAS check as a last-resort safety
		// check against race conditions and replica DB lag.
		$newTouched = $this->newTouchedTimestamp();

		$dbw = wfGetDB( DB_MASTER );
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
				$from = ( $this->queryFlagsUsed & self::READ_LATEST ) ? 'master' : 'replica';
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
		} );

		$this->mTouched = $newTouched;
		MediaWikiServices::getInstance()->getUserOptionsManager()->saveOptions( $this );

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
			MediaWikiServices::getInstance()
				->getUserOptionsManager()
				->loadUserOptions( $user, $user->queryFlagsUsed, $params['options'] );
			unset( $params['options'] );
		}
		$dbw = wfGetDB( DB_MASTER );

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

		return $dbw->doAtomicSection( __METHOD__, function ( IDatabase $dbw, $fname ) use ( $fields ) {
			$dbw->insert( 'user', $fields, $fname, [ 'IGNORE' ] );
			if ( $dbw->affectedRows() ) {
				$newUser = self::newFromId( $dbw->insertId() );
				$newUser->mName = $fields['user_name'];
				$newUser->updateActorId( $dbw );
				// Load the user from master to avoid replica lag
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
	 *   if ( !$user->isLoggedIn() ) {
	 *       $user->addToDatabase();
	 *   }
	 *   // do something with $user...
	 *
	 * However, this was vulnerable to a race condition (T18020). By
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

		if ( !is_string( $this->mName ) ) {
			throw new RuntimeException( "User name field is not set." );
		}

		$this->mTouched = $this->newTouchedTimestamp();

		$dbw = wfGetDB( DB_MASTER );
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
				if ( $this->mId && $this->loadFromDatabase( self::READ_LOCKING ) ) {
					$loaded = true;
				}
				if ( !$loaded ) {
					throw new MWException( $fname . ": hit a key conflict attempting " .
						"to insert user '{$this->mName}' row, but it was not present in select!" );
				}
				return Status::newFatal( 'userexists' );
			}
			$this->mId = $dbw->insertId();
			self::$idCacheByName[$this->mName] = $this->mId;
			$this->updateActorId( $dbw );

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
	 * Update the actor ID after an insert
	 * @param IDatabase $dbw Writable database handle
	 */
	private function updateActorId( IDatabase $dbw ) {
		$dbw->insert(
			'actor',
			[ 'actor_user' => $this->mId, 'actor_name' => $this->mName ],
			__METHOD__
		);
		$this->mActorId = (int)$dbw->insertId();
	}

	/**
	 * If this user is logged-in and blocked,
	 * block any IP address they've successfully logged in from.
	 * @return bool A block was spread
	 */
	public function spreadAnyEditBlock() {
		if ( $this->isLoggedIn() && $this->getBlock() ) {
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
	 * Check given value against the token value stored in the session.
	 * A match should confirm that the form was submitted from the
	 * user's own login session, not a form submission from a third-party
	 * site.
	 *
	 * @param string $val Input value to compare
	 * @param string|array $salt Optional function-specific data for hashing
	 * @param WebRequest|null $request Object to use or null to use $wgRequest
	 * @param int|null $maxage Fail tokens older than this, in seconds
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
	 * @param string|array $salt Optional function-specific data for hashing
	 * @param WebRequest|null $request Object to use or null to use $wgRequest
	 * @param int|null $maxage Fail tokens older than this, in seconds
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
		global $wgEnableEmail, $wgEnableUserEmail;
		if ( !$wgEnableEmail || !$wgEnableUserEmail || !$this->isAllowed( 'sendemail' ) ) {
			return false;
		}
		$canSend = $this->isEmailConfirmed();
		$this->getHookRunner()->onUserCanSendEmail( $this, $canSend );
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
		// Avoid PHP 7.1 warning of passing $this by reference
		$user = $this;
		$confirmed = true;
		if ( $this->getHookRunner()->onEmailConfirmed( $user, $confirmed ) ) {
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
		}

		return $confirmed;
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
		return MediaWikiServices::getInstance()
			->getUserEditTracker()
			->getFirstEditTimestamp( $this );
	}

	/**
	 * Get the timestamp of the latest edit
	 *
	 * @since 1.33
	 * @return string|bool Timestamp of first edit, or false for
	 *  non-existent/anonymous user accounts.
	 */
	public function getLatestEditTimestamp() {
		return MediaWikiServices::getInstance()
			->getUserEditTracker()
			->getLatestEditTimestamp( $this );
	}

	/**
	 * Get the permissions associated with a given list of groups
	 *
	 * @deprecated since 1.34, use MediaWikiServices::getInstance()->getPermissionManager()
	 *             ->getGroupPermissions() instead
	 *
	 * @param array $groups Array of Strings List of internal group names
	 * @return array Array of Strings List of permission key names for given groups combined
	 */
	public static function getGroupPermissions( $groups ) {
		return MediaWikiServices::getInstance()->getPermissionManager()->getGroupPermissions( $groups );
	}

	/**
	 * Get all the groups who have a given permission
	 *
	 * @deprecated since 1.34, use MediaWikiServices::getInstance()->getPermissionManager()
	 *             ->getGroupsWithPermission() instead
	 *
	 * @param string $role Role to check
	 * @return array Array of Strings List of internal group names with the given permission
	 */
	public static function getGroupsWithPermission( $role ) {
		return MediaWikiServices::getInstance()->getPermissionManager()->getGroupsWithPermission( $role );
	}

	/**
	 * Check, if the given group has the given permission
	 *
	 * If you're wanting to check whether all users have a permission, use
	 * PermissionManager::isEveryoneAllowed() instead. That properly checks if it's revoked
	 * from anyone.
	 *
	 * @deprecated since 1.34, use MediaWikiServices::getInstance()->getPermissionManager()
	 * ->groupHasPermission(..) instead
	 *
	 * @since 1.21
	 * @param string $group Group to check
	 * @param string $role Role to check
	 * @return bool
	 */
	public static function groupHasPermission( $group, $role ) {
		return MediaWikiServices::getInstance()->getPermissionManager()
			->groupHasPermission( $group, $role );
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
	 * @deprecated since 1.34, use PermissionManager::isEveryoneAllowed() instead
	 *
	 * @param string $right Right to check
	 *
	 * @return bool
	 * @since 1.22
	 */
	public static function isEveryoneAllowed( $right ) {
		wfDeprecated( __METHOD__, '1.34' );
		return MediaWikiServices::getInstance()->getPermissionManager()->isEveryoneAllowed( $right );
	}

	/**
	 * Return the set of defined explicit groups.
	 * The implicit groups (by default *, 'user' and 'autoconfirmed')
	 * are not included, as they are defined automatically, not in the database.
	 * @deprecated since 1.35, use UserGroupManager::listAllGroups instead
	 * @return array Array of internal group names
	 */
	public static function getAllGroups() {
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->listAllGroups();
	}

	/**
	 * Get a list of all available permissions.
	 *
	 * @deprecated since 1.34, use PermissionManager::getAllPermissions() instead
	 *
	 * @return string[] Array of permission names
	 */
	public static function getAllRights() {
		wfDeprecated( __METHOD__, '1.34' );
		return MediaWikiServices::getInstance()->getPermissionManager()->getAllPermissions();
	}

	/**
	 * Get a list of implicit groups
	 * @deprecated since 1.35, use UserGroupManager::listAllImplicitGroups() instead
	 * @return array Array of Strings Array of internal group names
	 */
	public static function getImplicitGroups() {
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->listAllImplicitGroups();
	}

	/**
	 * Returns an array of the groups that a particular group can add/remove.
	 *
	 * @param string $group The group to check for whether it can add/remove
	 * @return array [ 'add' => [ addablegroups ],
	 *     'remove' => [ removablegroups ],
	 *     'add-self' => [ addablegroups to self ],
	 *     'remove-self' => [ removable groups from self ] ]
	 * @suppress PhanTypeComparisonFromArray False positives with $wgGroupsAddToSelf
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
			$groups['add-self'] = self::getAllGroups();
		} elseif ( is_array( $wgGroupsAddToSelf[$group] ) ) {
			$groups['add-self'] = $wgGroupsAddToSelf[$group];
		}

		if ( empty( $wgGroupsRemoveFromSelf[$group] ) ) {
			// Do nothing
		} elseif ( $wgGroupsRemoveFromSelf[$group] === true ) {
			$groups['remove-self'] = self::getAllGroups();
		} elseif ( is_array( $wgGroupsRemoveFromSelf[$group] ) ) {
			$groups['remove-self'] = $wgGroupsRemoveFromSelf[$group];
		}

		return $groups;
	}

	/**
	 * Returns an array of groups that this user can add and remove
	 * @return array [ 'add' => [ addablegroups ],
	 *  'remove' => [ removablegroups ],
	 *  'add-self' => [ addablegroups to self ],
	 *  'remove-self' => [ removable groups from self ] ]
	 */
	public function changeableGroups() {
		if ( $this->isAllowed( 'userrights' ) ) {
			// This group gives the right to modify everything (reverse-
			// compatibility with old "userrights lets you change
			// everything")
			// Using array_merge to make the groups reindexed
			$all = array_merge( self::getAllGroups() );
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
	 * Schedule a deferred update to update the user's edit count
	 */
	public function incEditCount() {
		if ( $this->isAnon() ) {
			return; // sanity
		}

		DeferredUpdates::addUpdate(
			new UserEditCountUpdate( $this, 1 ),
			DeferredUpdates::POSTSEND
		);
	}

	/**
	 * This method should not be called outside User/UserEditCountUpdate
	 *
	 * @param int $count
	 */
	public function setEditCountInternal( $count ) {
		$this->mEditCount = $count;
	}

	/**
	 * Initialize user_editcount from data out of the revision table
	 *
	 * @internal This method should not be called outside User/UserEditCountUpdate
	 * @param IDatabase $dbr Replica database
	 * @return int Number of edits
	 */
	public function initEditCountInternal( IDatabase $dbr ) {
		return MediaWikiServices::getInstance()
			->getUserEditTracker()
			->initializeUserEditCount( $this );
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
	 * Get the name of a given grant
	 *
	 * @since 1.29
	 * @param string $grant Grant to query
	 * @return string Localized name of the grant
	 */
	public static function getGrantName( $grant ) {
		$key = "grant-$grant";
		$msg = wfMessage( $key );
		return $msg->isDisabled() ? $grant : $msg->text();
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
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new user object.
	 * @since 1.31
	 * @return array With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
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
				->getPermissionManager()
				->getGroupsWithPermission( $permission ) as $group ) {
			$groups[] = UserGroupMembership::getLink( $group, RequestContext::getMain(), 'wiki' );
		}

		if ( $groups ) {
			return Status::newFatal( 'badaccess-groups', $wgLang->commaList( $groups ), count( $groups ) );
		}

		return Status::newFatal( 'badaccess-group0' );
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
	 * @since 1.25 ; takes a UserIdentity instead of a User since 1.32
	 * @param UserIdentity $user
	 * @return bool
	 */
	public function equals( UserIdentity $user ) {
		// XXX it's not clear whether central ID providers are supposed to obey this
		return $this->getName() === $user->getName();
	}

	/**
	 * Checks if usertalk is allowed
	 *
	 * @return bool
	 */
	public function isAllowUsertalk() {
		return $this->mAllowUsertalk;
	}
}
