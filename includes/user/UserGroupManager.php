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

use ConfiguredReadOnlyMode;
use DBAccessObjectUtils;
use DeferredUpdates;
use IDBAccessObject;
use InvalidArgumentException;
use JobQueueGroup;
use ManualLogEntry;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerInterface;
use ReadOnlyMode;
use Sanitizer;
use User;
use UserGroupExpiryJob;
use UserGroupMembership;
use WikiMap;
use Wikimedia\Assert\Assert;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Managers user groups.
 * @since 1.35
 */
class UserGroupManager implements IDBAccessObject {

	public const CONSTRUCTOR_OPTIONS = [
		'Autopromote',
		'AutopromoteOnce',
		'AutopromoteOnceLogInRC',
		'EmailAuthentication',
		'ImplicitGroups',
		'GroupPermissions',
		'RevokePermissions',
	];

	/** @var ServiceOptions */
	private $options;

	/** @var ILBFactory */
	private $loadBalancerFactory;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var HookContainer */
	private $hookContainer;

	/** @var HookRunner */
	private $hookRunner;

	/** @var ReadOnlyMode */
	private $readOnlyMode;

	/** @var UserEditTracker */
	private $userEditTracker;

	/** @var LoggerInterface */
	private $logger;

	/** @var callable[] */
	private $clearCacheCallbacks;

	/** @var string|false */
	private $dbDomain;

	/** string key for implicit groups cache */
	private const CACHE_IMPLICIT = 'implicit';

	/** string key for effective groups cache */
	private const CACHE_EFFECTIVE = 'effective';

	/** string key for group memberships cache */
	private const CACHE_MEMBERSHIP = 'membership';

	/** string key for former groups cache */
	private const CACHE_FORMER = 'former';

	/**
	 * @var array Service caches, an assoc. array keyed after the user-keys generated
	 * by the getCacheKey method and storing values in the following format:
	 *
	 * userKey => [
	 *   self::CACHE_IMPLICIT => implicit groups cache
	 *   self::CACHE_EFFECTIVE => effective groups cache
	 *   self::CACHE_MEMBERSHIP => [ ] // Array of UserGroupMembership objects
	 *   self::CACHE_FORMER => former groups cache
	 * ]
	 */
	private $userGroupCache = [];

	/**
	 * @var array An assoc. array that stores query flags used to retrieve user groups
	 * from the database and is stored in the following format:
	 *
	 * userKey => [
	 *   self::CACHE_IMPLICIT => implicit groups query flag
	 *   self::CACHE_EFFECTIVE => effective groups query flag
	 *   self::CACHE_MEMBERSHIP  => membership groups query flag
	 *   self::CACHE_FORMER => former groups query flag
	 * ]
	 */
	private $queryFlagsUsedForCaching = [];

	/**
	 * @param ServiceOptions $options
	 * @param ConfiguredReadOnlyMode $configuredReadOnlyMode
	 * @param ILBFactory $loadBalancerFactory
	 * @param HookContainer $hookContainer
	 * @param UserEditTracker $userEditTracker
	 * @param LoggerInterface $logger
	 * @param callable[] $clearCacheCallbacks
	 * @param string|bool $dbDomain
	 */
	public function __construct(
		ServiceOptions $options,
		ConfiguredReadOnlyMode $configuredReadOnlyMode,
		ILBFactory $loadBalancerFactory,
		HookContainer $hookContainer,
		UserEditTracker $userEditTracker,
		LoggerInterface $logger,
		array $clearCacheCallbacks = [],
		$dbDomain = false
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->loadBalancerFactory = $loadBalancerFactory;
		$this->loadBalancer = $loadBalancerFactory->getMainLB( $dbDomain );
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userEditTracker = $userEditTracker;
		$this->logger = $logger;
		// Can't just inject ROM since we LB can be for foreign wiki
		$this->readOnlyMode = new ReadOnlyMode( $configuredReadOnlyMode, $this->loadBalancer );
		$this->clearCacheCallbacks = $clearCacheCallbacks;
		$this->dbDomain = $dbDomain;
	}

	/**
	 * Return the set of defined explicit groups.
	 * The implicit groups (by default *, 'user' and 'autoconfirmed')
	 * are not included, as they are defined automatically, not in the database.
	 * @return string[] Array of internal group names
	 */
	public function listAllGroups() : array {
		return array_values( array_diff(
			array_merge(
				array_keys( $this->options->get( 'GroupPermissions' ) ),
				array_keys( $this->options->get( 'RevokePermissions' ) )
			),
			$this->listAllImplicitGroups()
		) );
	}

	/**
	 * Get a list of all configured implicit groups
	 * @return string[]
	 */
	public function listAllImplicitGroups() : array {
		return $this->options->get( 'ImplicitGroups' );
	}

	/**
	 * Creates a new UserGroupMembership instance from $row.
	 * The fields required to build an instance could be
	 * found using getQueryInfo() method.
	 *
	 * @param \stdClass $row A database result object
	 *
	 * @return UserGroupMembership
	 */
	public function newGroupMembershipFromRow( \stdClass $row ) : UserGroupMembership {
		return new UserGroupMembership(
			(int)$row->ug_user,
			$row->ug_group,
			$row->ug_expiry === null ? null : wfTimestamp(
				TS_MW,
				$row->ug_expiry
			)
		);
	}

	/**
	 * Load the user groups cache from the provided user groups data
	 * @internal for use by the User object only
	 * @param UserIdentity $user
	 * @param array $userGroups an array of database query results
	 * @param int $queryFlags
	 */
	public function loadGroupMembershipsFromArray(
		UserIdentity $user,
		array $userGroups,
		int $queryFlags = self::READ_NORMAL
	) {
		$membershipGroups = [];
		reset( $userGroups );
		foreach ( $userGroups as $row ) {
			$ugm = $this->newGroupMembershipFromRow( $row );
			$membershipGroups[ $ugm->getGroup() ] = $ugm;
		}
		$this->setCache( $user, self::CACHE_MEMBERSHIP, $membershipGroups, $queryFlags );
	}

	/**
	 * Get the list of implicit group memberships this user has.
	 *
	 * This includes 'user' if logged in, '*' for all accounts,
	 * and autopromoted groups
	 *
	 * @param UserIdentity $user
	 * @param int $queryFlags
	 * @param bool $recache Whether to avoid the cache
	 * @return string[] internal group names
	 */
	public function getUserImplicitGroups(
		UserIdentity $user,
		int $queryFlags = self::READ_NORMAL,
		bool $recache = false
	)  : array {
		$userKey = $this->getCacheKey( $user );
		if ( $recache ||
			!isset( $this->userGroupCache[$userKey][self::CACHE_IMPLICIT] ) ||
			!$this->canUseCachedValues( $user, self::CACHE_IMPLICIT, $queryFlags )
		) {
			$groups = [ '*' ];
			if ( $user->isRegistered() ) {
				$groups[] = 'user';

				$groups = array_unique( array_merge(
					$groups,
					$this->getUserAutopromoteGroups( $user )
				) );
			}
			$this->setCache( $user, self::CACHE_IMPLICIT, $groups, $queryFlags );
			if ( $recache ) {
				// Assure data consistency with rights/groups,
				// as getEffectiveGroups() depends on this function
				$this->clearUserCacheForKind( $user, self::CACHE_EFFECTIVE );
			}
		}
		return $this->userGroupCache[$userKey][self::CACHE_IMPLICIT];
	}

	/**
	 * Get the list of implicit group memberships the user has.
	 *
	 * This includes all explicit groups, plus 'user' if logged in,
	 * '*' for all accounts, and autopromoted groups
	 *
	 * @param UserIdentity $user
	 * @param int $queryFlags
	 * @param bool $recache Whether to avoid the cache
	 * @return string[] Array of String internal group names
	 */
	public function getUserEffectiveGroups(
		UserIdentity $user,
		int $queryFlags = self::READ_NORMAL,
		bool $recache = false
	) : array {
		$userKey = $this->getCacheKey( $user );
		// Ignore cache if the $recache flag is set, cached values can not be used
		// or the cache value is missing
		if ( $recache ||
			!$this->canUseCachedValues( $user, self::CACHE_EFFECTIVE, $queryFlags ) ||
			!isset( $this->userGroupCache[$userKey][self::CACHE_EFFECTIVE] )
		) {
			$groups = array_unique( array_merge(
				$this->getUserGroups( $user, $queryFlags ), // explicit groups
				$this->getUserImplicitGroups( $user, $queryFlags, $recache ) // implicit groups
			) );
			// TODO: Deprecate passing out user object in the hook by introducing
			// an alternative hook
			if ( $this->hookContainer->isRegistered( 'UserEffectiveGroups' ) ) {
				$userObj = User::newFromIdentity( $user );
				$userObj->load();
				// Hook for additional groups
				$this->hookRunner->onUserEffectiveGroups( $userObj, $groups );
			}
			// Force reindexation of groups when a hook has unset one of them
			$effectiveGroups = array_values( array_unique( $groups ) );
			$this->setCache( $user, self::CACHE_EFFECTIVE, $effectiveGroups, $queryFlags );
		}
		return $this->userGroupCache[$userKey][self::CACHE_EFFECTIVE];
	}

	/**
	 * Returns the groups the user has belonged to.
	 *
	 * The user may still belong to the returned groups. Compare with getGroups().
	 *
	 * The function will not return groups the user had belonged to before MW 1.17
	 *
	 * @param UserIdentity $user
	 * @param int $queryFlags
	 * @return array Names of the groups the user has belonged to.
	 */
	public function getUserFormerGroups(
		UserIdentity $user,
		int $queryFlags = self::READ_NORMAL
	) : array {
		$userKey = $this->getCacheKey( $user );

		if ( $this->canUseCachedValues( $user, self::CACHE_FORMER, $queryFlags ) &&
			isset( $this->userGroupCache[$userKey][self::CACHE_FORMER] )
		) {
			return $this->userGroupCache[$userKey][self::CACHE_FORMER];
		}

		if ( !$user->isRegistered() ) {
			// Anon users don't have groups stored in the database
			return [];
		}

		$db = $this->getDBConnectionRefForQueryFlags( $queryFlags );
		$res = $db->select(
			'user_former_groups',
			[ 'ufg_group' ],
			[ 'ufg_user' => $user->getId() ],
			__METHOD__
		);
		$formerGroups = [];
		foreach ( $res as $row ) {
			$formerGroups[] = $row->ufg_group;
		}
		$this->setCache( $user, self::CACHE_FORMER, $formerGroups, $queryFlags );

		return $this->userGroupCache[$userKey][self::CACHE_FORMER];
	}

	/**
	 * Get the groups for the given user based on $wgAutopromote.
	 *
	 * @param UserIdentity $user The user to get the groups for
	 * @return array Array of groups to promote to.
	 *
	 * @see $wgAutopromote
	 */
	public function getUserAutopromoteGroups( UserIdentity $user ) : array {
		$promote = [];
		// TODO: remove the need for the full user object
		$userObj = User::newFromIdentity( $user );
		foreach ( $this->options->get( 'Autopromote' ) as $group => $cond ) {
			if ( $this->recCheckCondition( $cond, $userObj ) ) {
				$promote[] = $group;
			}
		}

		$this->hookRunner->onGetAutoPromoteGroups( $userObj, $promote );
		return $promote;
	}

	/**
	 * Get the groups for the given user based on the given criteria.
	 *
	 * Does not return groups the user already belongs to or has once belonged.
	 *
	 * @param UserIdentity $user The user to get the groups for
	 * @param string $event Key in $wgAutopromoteOnce (each event has groups/criteria)
	 *
	 * @return array Groups the user should be promoted to.
	 *
	 * @see $wgAutopromoteOnce
	 */
	public function getUserAutopromoteOnceGroups(
		UserIdentity $user,
		string $event
	) : array {
		$autopromoteOnce = $this->options->get( 'AutopromoteOnce' );
		$promote = [];

		if ( isset( $autopromoteOnce[$event] ) && count( $autopromoteOnce[$event] ) ) {
			$currentGroups = $this->getUserGroups( $user );
			$formerGroups = $this->getUserFormerGroups( $user );
			// TODO: remove the need for the full user object
			$userObj = User::newFromIdentity( $user );
			foreach ( $autopromoteOnce[$event] as $group => $cond ) {
				// Do not check if the user's already a member
				if ( in_array( $group, $currentGroups ) ) {
					continue;
				}
				// Do not autopromote if the user has belonged to the group
				if ( in_array( $group, $formerGroups ) ) {
					continue;
				}
				// Finally - check the conditions
				if ( $this->recCheckCondition( $cond, $userObj ) ) {
					$promote[] = $group;
				}
			}
		}

		return $promote;
	}

	/**
	 * Recursively check a condition.  Conditions are in the form
	 *   [ '&' or '|' or '^' or '!', cond1, cond2, ... ]
	 * where cond1, cond2, ... are themselves conditions; *OR*
	 *   APCOND_EMAILCONFIRMED, *OR*
	 *   [ APCOND_EMAILCONFIRMED ], *OR*
	 *   [ APCOND_EDITCOUNT, number of edits ], *OR*
	 *   [ APCOND_AGE, seconds since registration ], *OR*
	 *   similar constructs defined by extensions.
	 * This function evaluates the former type recursively, and passes off to
	 * checkCondition for evaluation of the latter type.
	 *
	 * @param mixed $cond A condition, possibly containing other conditions
	 * @param User $user The user to check the conditions against
	 * @return bool Whether the condition is true
	 */
	private function recCheckCondition( $cond, User $user ) : bool {
		$validOps = [ '&', '|', '^', '!' ];

		if ( is_array( $cond ) && count( $cond ) >= 2 && in_array( $cond[0], $validOps ) ) {
			// Recursive condition
			if ( $cond[0] == '&' ) { // AND (all conds pass)
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					if ( !$this->recCheckCondition( $subcond, $user ) ) {
						return false;
					}
				}

				return true;
			} elseif ( $cond[0] == '|' ) { // OR (at least one cond passes)
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					if ( $this->recCheckCondition( $subcond, $user ) ) {
						return true;
					}
				}

				return false;
			} elseif ( $cond[0] == '^' ) { // XOR (exactly one cond passes)
				if ( count( $cond ) > 3 ) {
					$this->logger->warning(
						'recCheckCondition() given XOR ("^") condition on three or more conditions.' .
						' Check your $wgAutopromote and $wgAutopromoteOnce settings.'
					);
				}
				return $this->recCheckCondition( $cond[1], $user )
					xor $this->recCheckCondition( $cond[2], $user );
			} elseif ( $cond[0] == '!' ) { // NOT (no conds pass)
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					if ( $this->recCheckCondition( $subcond, $user ) ) {
						return false;
					}
				}

				return true;
			}
		}
		// If we got here, the array presumably does not contain other conditions;
		// it's not recursive. Pass it off to checkCondition.
		if ( !is_array( $cond ) ) {
			$cond = [ $cond ];
		}

		return $this->checkCondition( $cond, $user );
	}

	/**
	 * As recCheckCondition, but *not* recursive.  The only valid conditions
	 * are those whose first element is one of APCOND_* defined in Defines.php.
	 * Other types will throw an exception if no extension evaluates them.
	 *
	 * @param array $cond A condition, which must not contain other conditions
	 * @param User $user The user to check the condition against
	 * @return bool Whether the condition is true for the user
	 * @throws InvalidArgumentException if autopromote condition was not recognized.
	 */
	private function checkCondition( array $cond, User $user ) : bool {
		if ( count( $cond ) < 1 ) {
			return false;
		}

		switch ( $cond[0] ) {
			case APCOND_EMAILCONFIRMED:
				if ( Sanitizer::validateEmail( $user->getEmail() ) ) {
					if ( $this->options->get( 'EmailAuthentication' ) ) {
						return (bool)$user->getEmailAuthenticationTimestamp();
					} else {
						return true;
					}
				}
				return false;
			case APCOND_EDITCOUNT:
				$reqEditCount = $cond[1];

				// T157718: Avoid edit count lookup if specified edit count is 0 or invalid
				if ( $reqEditCount <= 0 ) {
					return true;
				}
				return $user->isRegistered() && $this->userEditTracker->getUserEditCount( $user ) >= $reqEditCount;
			case APCOND_AGE:
				$age = time() - (int)wfTimestampOrNull( TS_UNIX, $user->getRegistration() );
				return $age >= $cond[1];
			case APCOND_AGE_FROM_EDIT:
				$age = time() - (int)wfTimestampOrNull(
					TS_UNIX, $this->userEditTracker->getFirstEditTimestamp( $user ) );
				return $age >= $cond[1];
			case APCOND_INGROUPS:
				$groups = array_slice( $cond, 1 );
				return count( array_intersect( $groups, $this->getUserGroups( $user ) ) ) == count( $groups );
			case APCOND_ISIP:
				return $cond[1] == $user->getRequest()->getIP();
			case APCOND_IPINRANGE:
				return IPUtils::isInRange( $user->getRequest()->getIP(), $cond[1] );
			case APCOND_BLOCKED:
				return $user->getBlock() && $user->getBlock()->isSitewide();
			case APCOND_ISBOT:
				// TODO: Injecting permission manager will cause a cyclic dependency. T254537
				return in_array( 'bot', MediaWikiServices::getInstance()
					->getPermissionManager()
					->getGroupPermissions( $this->getUserGroups( $user ) ) );
			default:
				$result = null;
				$this->hookRunner->onAutopromoteCondition( $cond[0],
					array_slice( $cond, 1 ), $user, $result );
				if ( $result === null ) {
					throw new InvalidArgumentException(
						"Unrecognized condition {$cond[0]} for autopromotion!"
					);
				}

				return (bool)$result;
		}
	}

	/**
	 * Add the user to the group if he/she meets given criteria.
	 *
	 * Contrary to autopromotion by $wgAutopromote, the group will be
	 *   possible to remove manually via Special:UserRights. In such case it
	 *   will not be re-added automatically. The user will also not lose the
	 *   group if they no longer meet the criteria.
	 *
	 * @param UserIdentity $user User to add to the groups
	 * @param string $event Key in $wgAutopromoteOnce (each event has groups/criteria)
	 *
	 * @return array Array of groups the user has been promoted to.
	 *
	 * @see $wgAutopromoteOnce
	 */
	public function addUserToAutopromoteOnceGroups(
		UserIdentity $user,
		string $event
	) : array {
		Assert::precondition(
			!$this->dbDomain || WikiMap::isCurrentWikiDbDomain( $this->dbDomain ),
			__METHOD__ . " is not supported for foreign domains: {$this->dbDomain} used"
		);

		if ( $this->readOnlyMode->isReadOnly() || !$user->getId() ) {
			return [];
		}

		$toPromote = $this->getUserAutopromoteOnceGroups( $user, $event );
		if ( $toPromote === [] ) {
			return [];
		}

		$userObj = User::newFromIdentity( $user );
		if ( !$userObj->checkAndSetTouched() ) {
			return []; // raced out (bug T48834)
		}

		$oldGroups = $this->getUserGroups( $user ); // previous groups
		$oldUGMs = $this->getUserGroupMemberships( $user );
		foreach ( $toPromote as $group ) {
			$this->addUserToGroup( $user, $group );
		}
		$newGroups = array_merge( $oldGroups, $toPromote ); // all groups
		$newUGMs = $this->getUserGroupMemberships( $user );

		// update groups in external authentication database
		// TODO: deprecate passing full User object to hook
		$this->hookRunner->onUserGroupsChanged(
			$userObj,
			$toPromote, [],
			false,
			false,
			$oldUGMs,
			$newUGMs
		);

		$logEntry = new ManualLogEntry( 'rights', 'autopromote' );
		$logEntry->setPerformer( $user );
		$logEntry->setTarget( $userObj->getUserPage() );
		$logEntry->setParameters( [
			'4::oldgroups' => $oldGroups,
			'5::newgroups' => $newGroups,
		] );
		$logid = $logEntry->insert();
		if ( $this->options->get( 'AutopromoteOnceLogInRC' ) ) {
			$logEntry->publish( $logid );
		}

		return $toPromote;
	}

	/**
	 * Get the list of explicit group memberships this user has.
	 * The implicit * and user groups are not included.
	 *
	 * @param UserIdentity $user
	 * @param int $queryFlags
	 * @return string[]
	 */
	public function getUserGroups(
		UserIdentity $user,
		int $queryFlags = self::READ_NORMAL
	) : array {
		return array_keys( $this->getUserGroupMemberships( $user, $queryFlags ) );
	}

	/**
	 * Loads and returns UserGroupMembership objects for all the groups a user currently
	 * belongs to.
	 *
	 * @param UserIdentity $user the user to search for
	 * @param int $queryFlags
	 * @return UserGroupMembership[] Associative array of (group name => UserGroupMembership object)
	 */
	public function getUserGroupMemberships(
		UserIdentity $user,
		int $queryFlags = self::READ_NORMAL
	) : array {
		$userKey = $this->getCacheKey( $user );

		if ( $this->canUseCachedValues( $user, self::CACHE_MEMBERSHIP, $queryFlags ) &&
			isset( $this->userGroupCache[$userKey][self::CACHE_MEMBERSHIP] )
		) {
			/** @suppress PhanTypeMismatchReturn */
			return $this->userGroupCache[$userKey][self::CACHE_MEMBERSHIP];
		}

		if ( !$user->isRegistered() ) {
			// Anon users don't have groups stored in the database
			return [];
		}

		$db = $this->getDBConnectionRefForQueryFlags( $queryFlags );
		$queryInfo = $this->getQueryInfo();
		$res = $db->select(
			$queryInfo['tables'],
			$queryInfo['fields'],
			[ 'ug_user' => $user->getId() ],
			__METHOD__,
			[],
			$queryInfo['joins']
		);

		$ugms = [];
		foreach ( $res as $row ) {
			$ugm = $this->newGroupMembershipFromRow( $row );
			if ( !$ugm->isExpired() ) {
				$ugms[$ugm->getGroup()] = $ugm;
			}
		}
		ksort( $ugms );

		$this->setCache( $user, self::CACHE_MEMBERSHIP, $ugms, $queryFlags );

		return $ugms;
	}

	/**
	 * Add the user to the given group. This takes immediate effect.
	 * If the user is already in the group, the expiry time will be updated to the new
	 * expiry time. (If $expiry is omitted or null, the membership will be altered to
	 * never expire.)
	 *
	 * @param UserIdentity $user
	 * @param string $group Name of the group to add
	 * @param string|null $expiry Optional expiry timestamp in any format acceptable to
	 *   wfTimestamp(), or null if the group assignment should not expire
	 * @param bool $allowUpdate Whether to perform "upsert" instead of INSERT
	 *
	 * @throws InvalidArgumentException
	 * @return bool
	 */
	public function addUserToGroup(
		UserIdentity $user,
		string $group,
		string $expiry = null,
		bool $allowUpdate = false
	) : bool {
		if ( $this->readOnlyMode->isReadOnly() ) {
			return false;
		}

		if ( !$user->isRegistered() ) {
			throw new InvalidArgumentException(
				'UserGroupManager::addUserToGroup() needs a positive user ID. ' .
				'Perhaps addGroup() was called before the user was added to the database.'
			);
		}

		if ( $expiry ) {
			$expiry = wfTimestamp( TS_MW, $expiry );
		}

		// TODO: Deprecate passing out user object in the hook by introducing
		// an alternative hook
		if ( $this->hookContainer->isRegistered( 'UserAddGroup' ) ) {
			$userObj = User::newFromIdentity( $user );
			$userObj->load();
			if ( !$this->hookRunner->onUserAddGroup( $userObj, $group, $expiry ) ) {
				return false;
			}
		}

		$oldUgms = $this->getUserGroupMemberships( $user, self::READ_LATEST );
		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER, [], $this->dbDomain );

		$dbw->startAtomic( __METHOD__ );
		$dbw->insert(
			'user_groups',
			[
				'ug_user' => $user->getId(),
				'ug_group' => $group,
				'ug_expiry' => $expiry ? $dbw->timestamp( $expiry ) : null,
			],
			__METHOD__,
			[ 'IGNORE' ]
		);

		$affected = $dbw->affectedRows();
		if ( !$affected ) {
			// Conflicting row already exists; it should be overridden if it is either expired
			// or if $allowUpdate is true and the current row is different than the loaded row.
			$conds = [
				'ug_user' => $user->getId(),
				'ug_group' => $group
			];
			if ( $allowUpdate ) {
				// Update the current row if its expiry does not match that of the loaded row
				$conds[] = $expiry
					? "ug_expiry IS NULL OR ug_expiry != {$dbw->addQuotes( $dbw->timestamp( $expiry ) )}"
					: 'ug_expiry IS NOT NULL';
			} else {
				// Update the current row if it is expired
				$conds[] = "ug_expiry < {$dbw->addQuotes( $dbw->timestamp() )}";
			}
			$dbw->update(
				'user_groups',
				[ 'ug_expiry' => $expiry ? $dbw->timestamp( $expiry ) : null ],
				$conds,
				__METHOD__
			);
			$affected = $dbw->affectedRows();
		}
		$dbw->endAtomic( __METHOD__ );

		// Purge old, expired memberships from the DB
		$fname = __METHOD__;
		DeferredUpdates::addCallableUpdate( function () use ( $fname ) {
			$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
			$hasExpiredRow = $dbr->selectField(
				'user_groups',
				'1',
				[ "ug_expiry < {$dbr->addQuotes( $dbr->timestamp() )}" ],
				$fname
			);
			if ( $hasExpiredRow ) {
				JobQueueGroup::singleton( $this->dbDomain )->push( new UserGroupExpiryJob( [] ) );
			}
		} );

		if ( $affected > 0 ) {
			$oldUgms[$group] = new UserGroupMembership( $user->getId(), $group, $expiry );
			if ( !$oldUgms[$group]->isExpired() ) {
				$this->setCache( $user, self::CACHE_MEMBERSHIP,
					$oldUgms, self::READ_LATEST );
				$this->clearUserCacheForKind( $user, self::CACHE_EFFECTIVE );
			}
			foreach ( $this->clearCacheCallbacks as $callback ) {
				$callback( $user );
			}
			return true;
		}
		return false;
	}

	/**
	 * Remove the user from the given group. This takes immediate effect.
	 *
	 * @param UserIdentity $user
	 * @param string $group Name of the group to remove
	 * @throws InvalidArgumentException
	 * @return bool
	 */
	public function removeUserFromGroup( UserIdentity $user, string $group ) : bool {
		// TODO: Deprecate passing out user object in the hook by introducing
		// an alternative hook
		if ( $this->hookContainer->isRegistered( 'UserRemoveGroup' ) ) {
			$userObj = User::newFromIdentity( $user );
			$userObj->load();
			if ( !$this->hookRunner->onUserRemoveGroup( $userObj, $group ) ) {
				return false;
			}
		}

		if ( $this->readOnlyMode->isReadOnly() ) {
			return false;
		}

		if ( !$user->isRegistered() ) {
			throw new InvalidArgumentException(
				'UserGroupManager::removeUserFromGroup() needs a positive user ID. ' .
				'Perhaps removeUserFromGroup() was called before the user was added to the database.'
			);
		}

		$oldUgms = $this->getUserGroupMemberships( $user, self::READ_LATEST );
		$oldFormerGroups = $this->getUserFormerGroups( $user, self::READ_LATEST );
		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER, [], $this->dbDomain );
		$dbw->delete(
			'user_groups',
			[ 'ug_user' => $user->getId(), 'ug_group' => $group ],
			__METHOD__
		);

		if ( !$dbw->affectedRows() ) {
			return false;
		}
		// Remember that the user was in this group
		$dbw->insert(
			'user_former_groups',
			[ 'ufg_user' => $user->getId(), 'ufg_group' => $group ],
			__METHOD__,
			[ 'IGNORE' ]
		);

		unset( $oldUgms[$group] );
		$this->setCache( $user, self::CACHE_MEMBERSHIP, $oldUgms, self::READ_LATEST );
		$oldFormerGroups[] = $group;
		$this->setCache( $user, self::CACHE_FORMER, $oldFormerGroups, self::READ_LATEST );
		$this->clearUserCacheForKind( $user, self::CACHE_EFFECTIVE );
		foreach ( $this->clearCacheCallbacks as $callback ) {
			$callback( $user );
		}
		return true;
	}

	/**
	 * Return the tables and fields to be selected to construct new UserGroupMembership object
	 * using newGroupMembershipFromRow method.
	 *
	 * @return array With three keys:
	 *  - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *  - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *  - joins: (string[]) to include in the `$joins` to `IDatabase->select()`
	 * @internal
	 * @phan-return array{tables:string[],fields:string[],joins:string[]}
	 */
	public function getQueryInfo() : array {
		return [
			'tables' => [ 'user_groups' ],
			'fields' => [
				'ug_user',
				'ug_group',
				'ug_expiry',
			],
			'joins' => []
		];
	}

	/**
	 * Purge expired memberships from the user_groups table
	 * @internal
	 * @note this could be slow and is intended for use in a background job
	 * @return int|bool false if purging wasn't attempted (e.g. because of
	 *  readonly), the number of rows purged (might be 0) otherwise
	 */
	public function purgeExpired() {
		if ( $this->readOnlyMode->isReadOnly() ) {
			return false;
		}

		$ticket = $this->loadBalancerFactory->getEmptyTransactionTicket( __METHOD__ );
		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );

		$lockKey = "{$dbw->getDomainID()}:UserGroupManager:purge"; // per-wiki
		$scopedLock = $dbw->getScopedLockAndFlush( $lockKey, __METHOD__, 0 );
		if ( !$scopedLock ) {
			return false; // already running
		}

		$now = time();
		$purgedRows = 0;
		$queryInfo = $this->getQueryInfo();
		do {
			$dbw->startAtomic( __METHOD__ );

			$res = $dbw->select(
				$queryInfo['tables'],
				$queryInfo['fields'],
				[ 'ug_expiry < ' . $dbw->addQuotes( $dbw->timestamp( $now ) ) ],
				__METHOD__,
				[ 'FOR UPDATE', 'LIMIT' => 100 ],
				$queryInfo['joins']
			);

			if ( $res->numRows() > 0 ) {
				$insertData = []; // array of users/groups to insert to user_former_groups
				$deleteCond = []; // array for deleting the rows that are to be moved around
				foreach ( $res as $row ) {
					$insertData[] = [ 'ufg_user' => $row->ug_user, 'ufg_group' => $row->ug_group ];
					$deleteCond[] = $dbw->makeList(
						[ 'ug_user' => $row->ug_user, 'ug_group' => $row->ug_group ],
						$dbw::LIST_AND
					);
				}
				// Delete the rows we're about to move
				$dbw->delete(
					'user_groups',
					$dbw->makeList( $deleteCond, $dbw::LIST_OR ),
					__METHOD__
				);
				// Push the groups to user_former_groups
				$dbw->insert(
					'user_former_groups',
					$insertData,
					__METHOD__,
					[ 'IGNORE' ]
				);
				// Count how many rows were purged
				$purgedRows += $res->numRows();
			}

			$dbw->endAtomic( __METHOD__ );

			$this->loadBalancerFactory->commitAndWaitForReplication( __METHOD__, $ticket );
		} while ( $res->numRows() > 0 );
		return $purgedRows;
	}

	/**
	 * Cleans cached group memberships for a given user
	 *
	 * @param UserIdentity $user
	 */
	public function clearCache( UserIdentity $user ) {
		$userKey = $this->getCacheKey( $user );
		unset( $this->userGroupCache[$userKey] );
		unset( $this->queryFlagsUsedForCaching[$userKey] );
	}

	/**
	 * Sets cached group memberships and query flags for a given user
	 *
	 * @param UserIdentity $user
	 * @param string $cacheKind one of self::CACHE_KIND_* constants
	 * @param array $groupValue
	 * @param int $queryFlags
	 */
	private function setCache(
		UserIdentity $user,
		string $cacheKind,
		array $groupValue,
		int $queryFlags
	) {
		$userKey = $this->getCacheKey( $user );
		$this->userGroupCache[$userKey][$cacheKind] = $groupValue;
		$this->queryFlagsUsedForCaching[$userKey][$cacheKind] = $queryFlags;
	}

	/**
	 * Clears a cached group membership and query key for a given user
	 *
	 * @param UserIdentity $user
	 * @param string $cacheKind one of self::CACHE_* constants
	 */
	private function clearUserCacheForKind( UserIdentity $user, string $cacheKind ) {
		$userKey = $this->getCacheKey( $user );
		unset( $this->userGroupCache[$userKey][$cacheKind] );
		unset( $this->queryFlagsUsedForCaching[$userKey][$cacheKind] );
	}

	/**
	 * @param int $queryFlags a bit field composed of READ_XXX flags
	 * @return DBConnRef
	 */
	private function getDBConnectionRefForQueryFlags( int $queryFlags ) : DBConnRef {
		list( $mode, ) = DBAccessObjectUtils::getDBOptions( $queryFlags );
		return $this->loadBalancer->getConnectionRef( $mode, [], $this->dbDomain );
	}

	/**
	 * Gets a unique key for various caches.
	 * @param UserIdentity $user
	 * @return string
	 */
	private function getCacheKey( UserIdentity $user ) : string {
		return $user->isRegistered() ? "u:{$user->getId()}" : "anon:{$user->getName()}";
	}

	/**
	 * Determines if it's ok to use cached options values for a given user and query flags
	 * @param UserIdentity $user
	 * @param string $cacheKind one of self::CACHE_* constants
	 * @param int $queryFlags
	 * @return bool
	 */
	private function canUseCachedValues(
		UserIdentity $user,
		string $cacheKind,
		int $queryFlags
	) : bool {
		if ( !$user->isRegistered() ) {
			// Anon users don't have groups stored in the database,
			// so $queryFlags are ignored.
			return true;
		}
		if ( $queryFlags >= self::READ_LOCKING ) {
			return false;
		}
		$userKey = $this->getCacheKey( $user );
		$queryFlagsUsed = $this->queryFlagsUsedForCaching[$userKey][$cacheKind] ?? self::READ_NONE;
		return $queryFlagsUsed >= $queryFlags;
	}
}
