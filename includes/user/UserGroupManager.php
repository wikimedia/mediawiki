<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\WikiMap\WikiMap;
use UserGroupExpiryJob;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\ReadOnlyMode;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Manage user group memberships.
 *
 * @since 1.35
 * @ingroup User
 */
class UserGroupManager {

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::AddGroups,
		MainConfigNames::Autopromote,
		MainConfigNames::AutopromoteOnce,
		MainConfigNames::AutopromoteOnceLogInRC,
		MainConfigNames::AutopromoteOnceRCExcludedGroups,
		MainConfigNames::ImplicitGroups,
		MainConfigNames::GroupInheritsPermissions,
		MainConfigNames::GroupPermissions,
		MainConfigNames::GroupsAddToSelf,
		MainConfigNames::GroupsRemoveFromSelf,
		MainConfigNames::RevokePermissions,
		MainConfigNames::RemoveGroups,
		MainConfigNames::PrivilegedGroups,
	];

	/**
	 * Logical operators recognized in $wgAutopromote.
	 *
	 * @since 1.42
	 * @deprecated since 1.45; use UserRequirementsConditionChecker::VALID_OPS instead
	 */
	public const VALID_OPS = UserRequirementsConditionChecker::VALID_OPS;

	private HookRunner $hookRunner;

	/** string key for implicit groups cache */
	private const CACHE_IMPLICIT = 'implicit';

	/** string key for effective groups cache */
	private const CACHE_EFFECTIVE = 'effective';

	/** string key for group memberships cache */
	private const CACHE_MEMBERSHIP = 'membership';

	/** string key for former groups cache */
	private const CACHE_FORMER = 'former';

	/** string key for former groups cache */
	private const CACHE_PRIVILEGED = 'privileged';

	/**
	 * @var array Service caches, an assoc. array keyed after the user-keys generated
	 * by the getCacheKey method and storing values in the following format:
	 *
	 * userKey => [
	 *   self::CACHE_IMPLICIT => implicit groups cache
	 *   self::CACHE_EFFECTIVE => effective groups cache
	 *   self::CACHE_MEMBERSHIP => [ ] // Array of UserGroupMembership objects
	 *   self::CACHE_FORMER => former groups cache
	 *   self::CACHE_PRIVILEGED => privileged groups cache
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
	 *   self::CACHE_MEMBERSHIP => membership groups query flag
	 *   self::CACHE_FORMER => former groups query flag
	 *   self::CACHE_PRIVILEGED => privileged groups query flag
	 * ]
	 */
	private array $queryFlagsUsedForCaching = [];

	/**
	 * @internal For use preventing an infinite loop when checking APCOND_BLOCKED
	 * @var array An associative array mapping the getCacheKey userKey to a bool indicating
	 * an ongoing condition check.
	 */
	private array $recursionMap = [];

	/**
	 * @param ServiceOptions $options
	 * @param ReadOnlyMode $readOnlyMode
	 * @param IConnectionProvider $connectionProvider
	 * @param HookContainer $hookContainer
	 * @param JobQueueGroup $jobQueueGroup
	 * @param TempUserConfig $tempUserConfig
	 * @param UserRequirementsConditionCheckerFactory $userRequirementsConditionCheckerFactory
	 * @param callable[] $clearCacheCallbacks
	 * @param string|false $wikiId
	 */
	public function __construct(
		private readonly ServiceOptions $options,
		private readonly ReadOnlyMode $readOnlyMode,
		private readonly IConnectionProvider $connectionProvider,
		private readonly HookContainer $hookContainer,
		private readonly JobQueueGroup $jobQueueGroup,
		private readonly TempUserConfig $tempUserConfig,
		private readonly UserRequirementsConditionCheckerFactory $userRequirementsConditionCheckerFactory,
		private readonly array $clearCacheCallbacks = [],
		private readonly string|false $wikiId = UserIdentity::LOCAL
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Return the set of defined explicit groups.
	 * The implicit groups (by default *, 'user' and 'autoconfirmed')
	 * are not included, as they are defined automatically, not in the database.
	 *
	 * @return string[] internal group names
	 */
	public function listAllGroups(): array {
		return array_values(
			array_diff(
				array_keys( array_merge(
					$this->options->get( MainConfigNames::GroupPermissions ),
					$this->options->get( MainConfigNames::RevokePermissions ),
					$this->options->get( MainConfigNames::GroupInheritsPermissions ),
				) ),
				$this->listAllImplicitGroups()
			)
		);
	}

	/**
	 * Get a list of all configured implicit groups
	 *
	 * @return string[]
	 */
	public function listAllImplicitGroups(): array {
		return $this->options->get( MainConfigNames::ImplicitGroups );
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
	public function newGroupMembershipFromRow( \stdClass $row ): UserGroupMembership {
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
		int $queryFlags = IDBAccessObject::READ_NORMAL
	) {
		$user->assertWiki( $this->wikiId );
		$membershipGroups = [];
		reset( $userGroups );
		foreach ( $userGroups as $row ) {
			$ugm = $this->newGroupMembershipFromRow( $row );
			$membershipGroups[ $ugm->getGroup() ] = $ugm;
		}
		$this->setCache(
			$this->getCacheKey( $user ),
			self::CACHE_MEMBERSHIP,
			$membershipGroups,
			$queryFlags
		);
	}

	/**
	 * Get the list of implicit group memberships this user has.
	 *
	 * This includes 'user' if logged in, '*' for all accounts,
	 * and any autopromote groups.
	 *
	 * @param UserIdentity $user
	 * @param int $queryFlags
	 * @param bool $recache Whether to avoid the cache
	 * @return string[] internal group names
	 */
	public function getUserImplicitGroups(
		UserIdentity $user,
		int $queryFlags = IDBAccessObject::READ_NORMAL,
		bool $recache = false
	): array {
		$user->assertWiki( $this->wikiId );
		$userKey = $this->getCacheKey( $user );
		if ( $recache ||
			!isset( $this->userGroupCache[$userKey][self::CACHE_IMPLICIT] ) ||
			!$this->canUseCachedValues( $user, self::CACHE_IMPLICIT, $queryFlags )
		) {
			$groups = [ '*' ];
			if ( $this->tempUserConfig->isTempName( $user->getName() ) ) {
				$groups[] = 'temp';
			} elseif ( $user->isRegistered() ) {
				$groups[] = 'user';
				$groups = array_unique( array_merge(
					$groups,
					$this->getUserAutopromoteGroups( $user )
				) );
			}
			$this->setCache( $userKey, self::CACHE_IMPLICIT, $groups, $queryFlags );
			if ( $recache ) {
				// Assure data consistency with rights/groups,
				// as getUserEffectiveGroups() depends on this function
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
	 * @return string[] internal group names
	 */
	public function getUserEffectiveGroups(
		UserIdentity $user,
		int $queryFlags = IDBAccessObject::READ_NORMAL,
		bool $recache = false
	): array {
		$user->assertWiki( $this->wikiId );
		$userKey = $this->getCacheKey( $user );
		// Ignore cache if the $recache flag is set, cached values can not be used
		// or the cache value is missing
		if (
			$recache ||
			!isset( $this->userGroupCache[$userKey][self::CACHE_EFFECTIVE] ) ||
			!$this->canUseCachedValues( $user, self::CACHE_EFFECTIVE, $queryFlags )
		) {
			$groups = array_unique( array_merge(
				// explicit groups
				$this->getUserGroups( $user, $queryFlags ),
				// implicit groups
				$this->getUserImplicitGroups( $user, $queryFlags, $recache )
			) );
			// TODO: Deprecate passing out user object in the hook by introducing
			// an alternative hook
			if ( $this->hookContainer->isRegistered( 'UserEffectiveGroups' ) ) {
				$userObj = User::newFromIdentity( $user );
				$userObj->load();
				// Hook for additional groups
				$this->hookRunner->onUserEffectiveGroups( $userObj, $groups );
			}
			// Force re-indexing of groups when a hook has unset one of them
			$effectiveGroups = array_values( array_unique( $groups ) );
			$this->setCache( $userKey, self::CACHE_EFFECTIVE, $effectiveGroups, $queryFlags );
		}
		return $this->userGroupCache[$userKey][self::CACHE_EFFECTIVE];
	}

	/**
	 * Returns the groups the user has belonged to.
	 *
	 * The user may still belong to the returned groups. Compare with
	 * getUserGroups().
	 *
	 * The function will not return groups the user had belonged to before MW 1.17
	 *
	 * @param UserIdentity $user
	 * @param int $queryFlags
	 * @return string[] Names of the groups the user has belonged to.
	 */
	public function getUserFormerGroups(
		UserIdentity $user,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	): array {
		$user->assertWiki( $this->wikiId );
		$userKey = $this->getCacheKey( $user );

		if (
			isset( $this->userGroupCache[$userKey][self::CACHE_FORMER] ) &&
			$this->canUseCachedValues( $user, self::CACHE_FORMER, $queryFlags )
		) {
			return $this->userGroupCache[$userKey][self::CACHE_FORMER];
		}

		if ( !$user->isRegistered() ) {
			// Anon users don't have groups stored in the database
			return [];
		}

		$res = $this->getDBConnectionRefForQueryFlags( $queryFlags )->newSelectQueryBuilder()
			->select( 'ufg_group' )
			->from( 'user_former_groups' )
			->where( [ 'ufg_user' => $user->getId( $this->wikiId ) ] )
			->caller( __METHOD__ )
			->fetchResultSet();
		$formerGroups = [];
		foreach ( $res as $row ) {
			$formerGroups[] = $row->ufg_group;
		}
		$this->setCache( $userKey, self::CACHE_FORMER, $formerGroups, $queryFlags );

		return $this->userGroupCache[$userKey][self::CACHE_FORMER];
	}

	/**
	 * Get the groups for the given user based on $wgAutopromote.
	 *
	 * @param UserIdentity $user The user to get the groups for
	 * @return string[] Array of groups to promote to.
	 *
	 * @see $wgAutopromote
	 */
	public function getUserAutopromoteGroups( UserIdentity $user ): array {
		$user->assertWiki( $this->wikiId );
		$promote = [];
		// TODO: remove the need for the full user object
		$userObj = User::newFromIdentity( $user );
		if ( $userObj->isTemp() ) {
			return [];
		}

		$checker = $this->userRequirementsConditionCheckerFactory->getUserRequirementsConditionChecker(
			$this, $this->wikiId
		);
		foreach ( $this->options->get( MainConfigNames::Autopromote ) as $group => $cond ) {
			if ( $checker->recursivelyCheckCondition( $cond, $userObj ) ) {
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
	 * @return string[] Groups the user should be promoted to.
	 *
	 * @see $wgAutopromoteOnce
	 */
	public function getUserAutopromoteOnceGroups(
		UserIdentity $user,
		string $event
	): array {
		$user->assertWiki( $this->wikiId );
		$autopromoteOnce = $this->options->get( MainConfigNames::AutopromoteOnce );
		$promote = [];

		if ( isset( $autopromoteOnce[$event] ) && count( $autopromoteOnce[$event] ) ) {
			// TODO: remove the need for the full user object
			$userObj = User::newFromIdentity( $user );
			if ( $userObj->isTemp() ) {
				return [];
			}
			$currentGroups = $this->getUserGroups( $user );
			$formerGroups = $this->getUserFormerGroups( $user );
			$checker = $this->userRequirementsConditionCheckerFactory->getUserRequirementsConditionChecker(
				$this, $this->wikiId
			);
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
				if ( $checker->recursivelyCheckCondition( $cond, $userObj ) ) {
					$promote[] = $group;
				}
			}
		}

		return $promote;
	}

	/**
	 * Returns the list of privileged groups that $user belongs to.
	 *
	 * Privileged groups are ones that can be abused.
	 *
	 * Depending on how extensions extend this method, it might return values
	 * that are not strictly user groups (ACL list names, etc.).
	 * It is meant for logging/auditing, not for passing to methods that expect group names.
	 *
	 * @param UserIdentity $user
	 * @param int $queryFlags
	 * @param bool $recache Whether to avoid the cache
	 * @return string[]
	 * @since 1.41 (also backported to 1.39.5 and 1.40.1)
	 * @see $wgPrivilegedGroups
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UserGetPrivilegedGroups
	 */
	public function getUserPrivilegedGroups(
		UserIdentity $user,
		int $queryFlags = IDBAccessObject::READ_NORMAL,
		bool $recache = false
	): array {
		$userKey = $this->getCacheKey( $user );

		if (
			!$recache &&
			isset( $this->userGroupCache[$userKey][self::CACHE_PRIVILEGED] ) &&
			$this->canUseCachedValues( $user, self::CACHE_PRIVILEGED, $queryFlags )
		) {
			return $this->userGroupCache[$userKey][self::CACHE_PRIVILEGED];
		}

		if ( !$user->isRegistered() ) {
			return [];
		}

		$groups = array_intersect(
			$this->getUserEffectiveGroups( $user, $queryFlags, $recache ),
			$this->options->get( MainConfigNames::PrivilegedGroups )
		);

		$this->hookRunner->onUserPrivilegedGroups( $user, $groups );

		$this->setCache(
			$this->getCacheKey( $user ),
			self::CACHE_PRIVILEGED,
			array_values( array_unique( $groups ) ),
			$queryFlags
		);

		return $this->userGroupCache[$userKey][self::CACHE_PRIVILEGED];
	}

	/**
	 * Add the user to the group if they meet given criteria.
	 *
	 * Contrary to autopromotion by $wgAutopromote, the group will be
	 *   possible to remove manually via Special:UserRights. In such cases, it
	 *   will not be re-added automatically. The user will also not lose the
	 *   group if they no longer meet the criteria.
	 *
	 * @param UserIdentity $user User to add to the groups
	 * @param string $event Key in $wgAutopromoteOnce (each event has groups/criteria)
	 *
	 * @return string[] Array of groups the user has been promoted to.
	 *
	 * @see $wgAutopromoteOnce
	 */
	public function addUserToAutopromoteOnceGroups(
		UserIdentity $user,
		string $event
	): array {
		$user->assertWiki( $this->wikiId );
		Assert::precondition(
			!$this->wikiId || WikiMap::isCurrentWikiDbDomain( $this->wikiId ),
			__METHOD__ . " is not supported for foreign wikis: {$this->wikiId} used"
		);

		if (
			$this->readOnlyMode->isReadOnly( $this->wikiId ) ||
			!$user->isRegistered() ||
			$this->tempUserConfig->isTempName( $user->getName() )
		) {
			return [];
		}

		$toPromote = $this->getUserAutopromoteOnceGroups( $user, $event );
		if ( $toPromote === [] ) {
			return [];
		}

		$userObj = User::newFromIdentity( $user );
		if ( !$userObj->checkAndSetTouched() ) {
			// @codeCoverageIgnoreStart
			// raced out (bug T48834)
			return [];
			// @codeCoverageIgnoreEnd
		}

		// previous groups
		$oldGroups = $this->getUserGroups( $user );
		$oldUGMs = $this->getUserGroupMemberships( $user );
		$this->addUserToMultipleGroups( $user, $toPromote );
		// all groups
		$newGroups = array_merge( $oldGroups, $toPromote );
		$newUGMs = $this->getUserGroupMemberships( $user );

		// update groups in external authentication database
		// TODO: deprecate passing full User object to hook
		$this->hookRunner->onUserGroupsChanged(
			$userObj,
			$toPromote,
			[],
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

		// Allow excluding autopromotions into select groups from RecentChanges (T377829).
		$groupsToShowInRC = array_diff(
			$toPromote,
			$this->options->get( MainConfigNames::AutopromoteOnceRCExcludedGroups )
		);

		if ( $this->options->get( MainConfigNames::AutopromoteOnceLogInRC ) && count( $groupsToShowInRC ) ) {
			$logEntry->publish( $logid );
		}

		return $toPromote;
	}

	/**
	 * Get the list of explicit group memberships this user has.
	 * The implicit * and user groups are not included.
	 *
	 * @return string[]
	 */
	public function getUserGroups(
		UserIdentity $user,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	): array {
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
		int $queryFlags = IDBAccessObject::READ_NORMAL
	): array {
		$user->assertWiki( $this->wikiId );
		$userKey = $this->getCacheKey( $user );

		if (
			$this->canUseCachedValues( $user, self::CACHE_MEMBERSHIP, $queryFlags ) &&
			isset( $this->userGroupCache[$userKey][self::CACHE_MEMBERSHIP] )
		) {
			/** @suppress PhanTypeMismatchReturn */
			return $this->userGroupCache[$userKey][self::CACHE_MEMBERSHIP];
		}

		if ( !$user->isRegistered() ) {
			// Anon users don't have groups stored in the database
			return [];
		}

		$queryBuilder = $this->newQueryBuilder( $this->getDBConnectionRefForQueryFlags( $queryFlags ) );
		$res = $queryBuilder
			->where( [ 'ug_user' => $user->getId( $this->wikiId ) ] )
			->caller( __METHOD__ )
			->fetchResultSet();

		$ugms = [];
		foreach ( $res as $row ) {
			$ugm = $this->newGroupMembershipFromRow( $row );
			if ( !$ugm->isExpired() ) {
				$ugms[$ugm->getGroup()] = $ugm;
			}
		}
		ksort( $ugms );

		$this->setCache( $userKey, self::CACHE_MEMBERSHIP, $ugms, $queryFlags );

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
		?string $expiry = null,
		bool $allowUpdate = false
	): bool {
		$user->assertWiki( $this->wikiId );
		if ( $this->readOnlyMode->isReadOnly( $this->wikiId ) ) {
			return false;
		}

		$isTemp = $this->tempUserConfig->isTempName( $user->getName() );
		if ( !$user->isRegistered() ) {
			throw new InvalidArgumentException(
				'UserGroupManager::addUserToGroup() needs a positive user ID. ' .
				'Perhaps addUserToGroup() was called before the user was added to the database.'
			);
		}
		if ( $isTemp ) {
			throw new InvalidArgumentException(
				'UserGroupManager::addUserToGroup() cannot be called on a temporary user.'
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

		$oldUgms = $this->getUserGroupMemberships( $user, IDBAccessObject::READ_LATEST );
		$dbw = $this->connectionProvider->getPrimaryDatabase( $this->wikiId );

		$dbw->startAtomic( __METHOD__ );
		$dbw->newInsertQueryBuilder()
			->insertInto( 'user_groups' )
			->ignore()
			->row( [
				'ug_user' => $user->getId( $this->wikiId ),
				'ug_group' => $group,
				'ug_expiry' => $expiry ? $dbw->timestamp( $expiry ) : null,
			] )
			->caller( __METHOD__ )->execute();

		$affected = $dbw->affectedRows();
		if ( !$affected ) {
			// A conflicting row already exists; it should be overridden if it is either expired
			// or if $allowUpdate is true and the current row is different than the loaded row.
			$conds = [
				'ug_user' => $user->getId( $this->wikiId ),
				'ug_group' => $group
			];
			if ( $allowUpdate ) {
				// Update the current row if its expiry does not match that of the loaded row
				$conds[] = $expiry
					? $dbw->expr( 'ug_expiry', '=', null )
						->or( 'ug_expiry', '!=', $dbw->timestamp( $expiry ) )
					: $dbw->expr( 'ug_expiry', '!=', null );
			} else {
				// Update the current row if it is expired
				$conds[] = $dbw->expr( 'ug_expiry', '<', $dbw->timestamp() );
			}
			$dbw->newUpdateQueryBuilder()
				->update( 'user_groups' )
				->set( [ 'ug_expiry' => $expiry ? $dbw->timestamp( $expiry ) : null ] )
				->where( $conds )
				->caller( __METHOD__ )->execute();
			$affected = $dbw->affectedRows();
		}
		$dbw->endAtomic( __METHOD__ );

		// Purge old, expired memberships from the DB
		DeferredUpdates::addCallableUpdate( function ( $fname ) {
			$dbr = $this->connectionProvider->getReplicaDatabase( $this->wikiId );
			$hasExpiredRow = (bool)$dbr->newSelectQueryBuilder()
				->select( '1' )
				->from( 'user_groups' )
				->where( [ $dbr->expr( 'ug_expiry', '<', $dbr->timestamp() ) ] )
				->caller( $fname )
				->fetchField();
			if ( $hasExpiredRow ) {
				$this->jobQueueGroup->push( new UserGroupExpiryJob( [] ) );
			}
		} );

		if ( $affected > 0 ) {
			$oldUgms[$group] = new UserGroupMembership( $user->getId( $this->wikiId ), $group, $expiry );
			if ( !$oldUgms[$group]->isExpired() ) {
				$this->setCache(
					$this->getCacheKey( $user ),
					self::CACHE_MEMBERSHIP,
					$oldUgms,
					IDBAccessObject::READ_LATEST
				);
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
	 * Add the user to the given list of groups.
	 *
	 * @since 1.37
	 *
	 * @param UserIdentity $user
	 * @param string[] $groups Names of the groups to add
	 * @param string|null $expiry Optional expiry timestamp in any format acceptable to
	 *   wfTimestamp(), or null if the group assignment should not expire
	 * @param bool $allowUpdate Whether to perform "upsert" instead of INSERT
	 *
	 * @throws InvalidArgumentException
	 */
	public function addUserToMultipleGroups(
		UserIdentity $user,
		array $groups,
		?string $expiry = null,
		bool $allowUpdate = false
	) {
		foreach ( $groups as $group ) {
			$this->addUserToGroup( $user, $group, $expiry, $allowUpdate );
		}
	}

	/**
	 * Remove the user from the given group. This takes immediate effect.
	 *
	 * @param UserIdentity $user
	 * @param string $group Name of the group to remove
	 * @throws InvalidArgumentException
	 * @return bool
	 */
	public function removeUserFromGroup( UserIdentity $user, string $group ): bool {
		$user->assertWiki( $this->wikiId );
		// TODO: Deprecate passing out user object in the hook by introducing
		// an alternative hook
		if ( $this->hookContainer->isRegistered( 'UserRemoveGroup' ) ) {
			$userObj = User::newFromIdentity( $user );
			$userObj->load();
			if ( !$this->hookRunner->onUserRemoveGroup( $userObj, $group ) ) {
				return false;
			}
		}

		if ( $this->readOnlyMode->isReadOnly( $this->wikiId ) ) {
			return false;
		}

		if ( !$user->isRegistered() ) {
			throw new InvalidArgumentException(
				'UserGroupManager::removeUserFromGroup() needs a positive user ID. ' .
				'Perhaps removeUserFromGroup() was called before the user was added to the database.'
			);
		}

		$oldUgms = $this->getUserGroupMemberships( $user, IDBAccessObject::READ_LATEST );
		$oldFormerGroups = $this->getUserFormerGroups( $user, IDBAccessObject::READ_LATEST );
		$dbw = $this->connectionProvider->getPrimaryDatabase( $this->wikiId );
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'user_groups' )
			->where( [ 'ug_user' => $user->getId( $this->wikiId ), 'ug_group' => $group ] )
			->caller( __METHOD__ )->execute();

		if ( !$dbw->affectedRows() ) {
			return false;
		}
		// Remember that the user was in this group
		$dbw->newInsertQueryBuilder()
			->insertInto( 'user_former_groups' )
			->ignore()
			->row( [ 'ufg_user' => $user->getId( $this->wikiId ), 'ufg_group' => $group ] )
			->caller( __METHOD__ )->execute();

		unset( $oldUgms[$group] );
		$userKey = $this->getCacheKey( $user );
		$this->setCache( $userKey, self::CACHE_MEMBERSHIP, $oldUgms, IDBAccessObject::READ_LATEST );
		$oldFormerGroups[] = $group;
		$this->setCache( $userKey, self::CACHE_FORMER, $oldFormerGroups, IDBAccessObject::READ_LATEST );
		$this->clearUserCacheForKind( $user, self::CACHE_EFFECTIVE );
		foreach ( $this->clearCacheCallbacks as $callback ) {
			$callback( $user );
		}
		return true;
	}

	/**
	 * @internal
	 */
	public function newQueryBuilder( IReadableDatabase $db ): SelectQueryBuilder {
		return $db->newSelectQueryBuilder()
			->select( [
				'ug_user',
				'ug_group',
				'ug_expiry',
			] )
			->from( 'user_groups' );
	}

	/**
	 * Purge expired memberships from the user_groups table
	 * @internal
	 * @note this could be slow and is intended for use in a background job
	 * @return int|false false if purging wasn't attempted (e.g. because of
	 *  readonly), the number of rows purged (might be 0) otherwise
	 */
	public function purgeExpired() {
		if ( $this->readOnlyMode->isReadOnly( $this->wikiId ) ) {
			return false;
		}

		$ticket = $this->connectionProvider->getEmptyTransactionTicket( __METHOD__ );
		$dbw = $this->connectionProvider->getPrimaryDatabase( $this->wikiId );

		// per-wiki
		$lockKey = "{$dbw->getDomainID()}:UserGroupManager:purge";
		$scopedLock = $dbw->getScopedLockAndFlush( $lockKey, __METHOD__, 0 );
		if ( !$scopedLock ) {
			// @codeCoverageIgnoreStart
			// already running
			return false;
			// @codeCoverageIgnoreEnd
		}

		$now = time();
		$purgedRows = 0;
		do {
			$dbw->startAtomic( __METHOD__ );
			$res = $this->newQueryBuilder( $dbw )
				->where( [ $dbw->expr( 'ug_expiry', '<', $dbw->timestamp( $now ) ) ] )
				->forUpdate()
				->limit( 100 )
				->caller( __METHOD__ )
				->fetchResultSet();

			if ( $res->numRows() > 0 ) {
				// array of users/groups to insert to user_former_groups
				$insertData = [];
				// array for deleting the rows that are to be moved around
				$deleteCond = [];
				foreach ( $res as $row ) {
					$insertData[] = [ 'ufg_user' => $row->ug_user, 'ufg_group' => $row->ug_group ];
					$deleteCond[] = $dbw
						->expr( 'ug_user', '=', $row->ug_user )
						->and( 'ug_group', '=', $row->ug_group );
				}
				// Delete the rows we're about to move
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'user_groups' )
					->where( $dbw->orExpr( $deleteCond ) )
					->caller( __METHOD__ )->execute();
				// Push the groups to user_former_groups
				$dbw->newInsertQueryBuilder()
					->insertInto( 'user_former_groups' )
					->ignore()
					->rows( $insertData )
					->caller( __METHOD__ )->execute();
				// Count how many rows were purged
				$purgedRows += $res->numRows();
			}

			$dbw->endAtomic( __METHOD__ );

			$this->connectionProvider->commitAndWaitForReplication( __METHOD__, $ticket );
		} while ( $res->numRows() > 0 );
		return $purgedRows;
	}

	/**
	 * @return string[]
	 */
	private function expandChangeableGroupConfig( array $config, string $group ): array {
		if ( empty( $config[$group] ) ) {
			return [];
		}

		if ( $config[$group] === true ) {
			// You get everything
			return $this->listAllGroups();
		}

		if ( is_array( $config[$group] ) ) {
			return $config[$group];
		}

		return [];
	}

	/**
	 * Returns an array of the groups that a particular group can add/remove.
	 *
	 * @since 1.37
	 * @param string $group The group to check for whether it can add/remove
	 * @return array [
	 *     'add' => [ addablegroups ],
	 *     'remove' => [ removablegroups ],
	 *     'add-self' => [ addablegroups to self ],
	 *     'remove-self' => [ removable groups from self ] ]
	 */
	public function getGroupsChangeableByGroup( string $group ): array {
		return [
			'add' => $this->expandChangeableGroupConfig(
				$this->options->get( MainConfigNames::AddGroups ), $group
			),
			'remove' => $this->expandChangeableGroupConfig(
				$this->options->get( MainConfigNames::RemoveGroups ), $group
			),
			'add-self' => $this->expandChangeableGroupConfig(
				$this->options->get( MainConfigNames::GroupsAddToSelf ), $group
			),
			'remove-self' => $this->expandChangeableGroupConfig(
				$this->options->get( MainConfigNames::GroupsRemoveFromSelf ), $group
			),
		];
	}

	/**
	 * Returns an array of groups that this $actor can add and remove.
	 *
	 * @since 1.37
	 * @param Authority $authority
	 * @return array [
	 *  'add' => [ addablegroups ],
	 *  'remove' => [ removablegroups ],
	 *  'add-self' => [ addablegroups to self ],
	 *  'remove-self' => [ removable groups from self ]
	 * ]
	 * @phan-return array{add:list<string>,remove:list<string>,add-self:list<string>,remove-self:list<string>}
	 */
	public function getGroupsChangeableBy( Authority $authority ): array {
		if ( $authority->isAllowed( 'userrights' ) ) {
			// This group gives the right to modify everything (reverse-
			// compatibility with old "userrights lets you change
			// everything")
			$all = array_values( $this->listAllGroups() );
			return [
				'add' => $all,
				'remove' => $all,
				'add-self' => [],
				'remove-self' => []
			];
		}

		// Okay, it's not so simple; we will have to go through the arrays
		$actorGroups = $this->getUserEffectiveGroups( $authority->getUser() );
		$changeableGroups = [];
		foreach ( $actorGroups as $actorGroup ) {
			$changeableGroups[] = $this->getGroupsChangeableByGroup( $actorGroup );
		}
		$groups = array_merge_recursive( [
			'add' => [],
			'remove' => [],
			'add-self' => [],
			'remove-self' => []
		], ...$changeableGroups );
		// @phan-suppress-next-line PhanTypeMismatchReturn
		return array_map( 'array_unique', $groups );
	}

	/**
	 * Cleans cached group memberships for a given user
	 */
	public function clearCache( UserIdentity $user ) {
		$user->assertWiki( $this->wikiId );
		$userKey = $this->getCacheKey( $user );
		unset( $this->userGroupCache[$userKey] );
		unset( $this->queryFlagsUsedForCaching[$userKey] );
	}

	/**
	 * Sets cached group memberships and query flags for a given user
	 *
	 * @param string $userKey
	 * @param string $cacheKind one of self::CACHE_KIND_* constants
	 * @param array $groupValue
	 * @param int $queryFlags
	 */
	private function setCache(
		string $userKey,
		string $cacheKind,
		array $groupValue,
		int $queryFlags
	) {
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
	 * @param int $recency a bit field composed of IDBAccessObject::READ_XXX flags
	 * @return IReadableDatabase
	 */
	private function getDBConnectionRefForQueryFlags( int $recency ): IReadableDatabase {
		if ( ( IDBAccessObject::READ_LATEST & $recency ) === IDBAccessObject::READ_LATEST ) {
			return $this->connectionProvider->getPrimaryDatabase( $this->wikiId );
		}
		return $this->connectionProvider->getReplicaDatabase( $this->wikiId );
	}

	private function getCacheKey( UserIdentity $user ): string {
		return $user->isRegistered() ? "u:{$user->getId( $this->wikiId )}" : "anon:{$user->getName()}";
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
	): bool {
		if ( !$user->isRegistered() ) {
			// Anon users don't have groups stored in the database,
			// so $queryFlags are ignored.
			return true;
		}
		if ( $queryFlags >= IDBAccessObject::READ_LOCKING ) {
			return false;
		}
		$userKey = $this->getCacheKey( $user );
		$queryFlagsUsed = $this->queryFlagsUsedForCaching[$userKey][$cacheKind] ?? IDBAccessObject::READ_NONE;
		return $queryFlagsUsed >= $queryFlags;
	}
}
