<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageStoreFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Title\Title;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * This class represents a service that provides high-level operations on user groups.
 * Contrary to UserGroupManager, this class is not interested in details of how user groups
 * are stored or defined, but rather in the business logic of assigning and removing groups.
 *
 * Therefore, it combines group management with logging and provides permission checks.
 * Additionally, the method interfaces are designed to be suitable for calls from user-facing code.
 *
 * @since 1.45
 * @ingroup User
 */
class UserGroupAssignmentService extends UserGroupAssignmentServiceBase {

	/** @internal */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::UserrightsInterwikiDelimiter
	];

	private array $changeableGroupsCache = [];

	public function __construct(
		private readonly UserGroupManagerFactory $userGroupManagerFactory,
		private readonly UserNameUtils $userNameUtils,
		private readonly UserFactory $userFactory,
		private readonly RestrictedUserGroupCheckerFactory $restrictedGroupCheckerFactory,
		private readonly HookRunner $hookRunner,
		private readonly ServiceOptions $options,
		private readonly TempUserConfig $tempUserConfig,
		private readonly IConnectionProvider $connectionProvider,
		private readonly PageStoreFactory $pageStoreFactory,
	) {
		parent::__construct( $this->hookRunner );
		$this->options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
	}

	public function targetCanHaveUserGroups( UserIdentity $target ): bool {
		// Basic stuff - don't assign groups to anons and temp. accounts
		if ( !$target->isRegistered() ) {
			return false;
		}
		if ( $this->userNameUtils->isTemp( $target->getName() ) ) {
			return false;
		}

		// We also need to make sure that we don't assign groups to remote temp. accounts if they
		// are disabled on the current wiki
		if (
			$target->getWikiId() !== UserIdentity::LOCAL &&
			!$this->tempUserConfig->isKnown() &&
			$this->tempUserConfig->isReservedName( $target->getName() )
		) {
			return false;
		}

		return true;
	}

	/**
	 * Check whether the given user can change the target user's rights.
	 *
	 * @param Authority $performer User who is attempting to change the target's rights
	 * @param UserIdentity $target User whose rights are being changed
	 */
	public function userCanChangeRights( Authority $performer, UserIdentity $target ): bool {
		if ( !$this->targetCanHaveUserGroups( $target ) ) {
			return false;
		}

		// Don't evaluate private conditions for this check, as it could leak the underlying value of these
		// conditions through the "View groups" / "Change groups" toolbox links.
		$available = $this->getChangeableGroups( $performer, $target, false );

		// getChangeableGroups already checks for self-assignments, so no need to do that here.
		if ( $available['add'] || $available['remove'] ) {
			return true;
		}
		return false;
	}

	/** @inheritDoc */
	public function getChangeableGroups(
		Authority $performer,
		UserIdentity $target,
		bool $evaluatePrivateConditionsForRestrictedGroups = true
	): array {
		// In order not to run multiple hooks every time this method is called in a request,
		// we cache the result based on performer and target.
		$cacheKey = $performer->getUser()->getName() . ':' . $target->getName() . ':' . $target->getWikiId() .
			':' . ( $evaluatePrivateConditionsForRestrictedGroups ? 'private' : 'public' );

		if ( !isset( $this->changeableGroupsCache[$cacheKey] ) ) {
			$this->changeableGroupsCache[$cacheKey] = $this->computeChangeableGroups(
				$performer, $target, $evaluatePrivateConditionsForRestrictedGroups );
		}
		return $this->changeableGroupsCache[$cacheKey];
	}

	/**
	 * Backend for {@see getChangeableGroups}, does actual computation without caching.
	 */
	private function computeChangeableGroups(
		Authority $performer,
		UserIdentity $target,
		bool $evaluatePrivateConditionsForRestrictedGroups
	): array {
		// If the target is an interwiki user, ensure that the performer is entitled to such changes
		// It assumes that the target wiki exists at all
		if (
			$target->getWikiId() !== UserIdentity::LOCAL &&
			!$performer->isAllowed( 'userrights-interwiki' )
		) {
			return [ 'add' => [], 'remove' => [], 'restricted' => [] ];
		}

		$localUserGroupManager = $this->userGroupManagerFactory->getUserGroupManager();
		$groups = $localUserGroupManager->getGroupsChangeableBy( $performer );
		$groups['restricted'] = [];

		$isSelf = $performer->getUser()->equals( $target );
		if ( $isSelf ) {
			$groups['add'] = array_unique( array_merge( $groups['add'], $groups['add-self'] ) );
			$groups['remove'] = array_unique( array_merge( $groups['remove'], $groups['remove-self'] ) );
		}
		unset( $groups['add-self'], $groups['remove-self'] );

		$cannotAdd = [];
		$restrictedGroupChecker = $this->getRestrictedGroupChecker( $target );
		foreach ( $groups['add'] as $group ) {
			if ( $restrictedGroupChecker->isGroupRestricted( $group ) ) {
				$groups['restricted'][$group] = [
					'condition-met' => $restrictedGroupChecker
						->doPerformerAndTargetMeetConditionsForAddingToGroup(
							$performer->getUser(),
							$target,
							$group,
							$evaluatePrivateConditionsForRestrictedGroups
						),
					'ignore-condition' => $restrictedGroupChecker
						->canPerformerIgnoreGroupRestrictions(
							$performer,
							$group
						),
				];
				$canPerformerAdd = $restrictedGroupChecker->canPerformerAddTargetToGroup(
					$performer, $target, $group, $evaluatePrivateConditionsForRestrictedGroups );
				// If null was returned, keep the group in addable, as it's potentially addable
				// Caller will be able to differentiate between true and null through the 'condition-met'
				// value in $groups['restricted'][$group]
				if ( $canPerformerAdd === false ) {
					$cannotAdd[] = $group;
				}
			}
		}
		$groups['add'] = array_diff( $groups['add'], $cannotAdd );

		return $groups;
	}

	/**
	 * Changes the user groups, ensuring that the performer has the necessary permissions
	 * and that the changes are logged.
	 *
	 * @param Authority $performer
	 * @param UserIdentity $target
	 * @param list<string> $addGroups The groups to add (or change expiry of)
	 * @param list<string> $removeGroups The groups to remove
	 * @param array<string, ?string> $newExpiries Map of group name to new expiry (string timestamp or null
	 *   for infinite). If a group is in $addGroups but not in this array, it won't expire.
	 * @param string $reason
	 * @param array $tags
	 * @param list<string> $logAtAdditionalWikis List of wiki IDs where the log entry should be added, in addition
	 *   to the current wiki and the target user's wiki. Wikis to which the log entry will be added are deduplicated,
	 *   so no double logging will happen.
	 * @return array{0:string[],1:string[]} The groups actually added and removed
	 */
	public function saveChangesToUserGroups(
		Authority $performer,
		UserIdentity $target,
		array $addGroups,
		array $removeGroups,
		array $newExpiries,
		string $reason = '',
		array $tags = [],
		array $logAtAdditionalWikis = []
	): array {
		$userGroupManager = $this->userGroupManagerFactory->getUserGroupManager( $target->getWikiId() );
		$oldGroupMemberships = $userGroupManager->getUserGroupMemberships( $target );

		$this->logAccessToPrivateConditions( $performer, $target, $addGroups, $newExpiries, $oldGroupMemberships );

		$changeable = $this->getChangeableGroups( $performer, $target );
		self::enforceChangeGroupPermissions( $addGroups, $removeGroups, $newExpiries,
			$oldGroupMemberships, $changeable );

		if ( $target->getWikiId() === UserIdentity::LOCAL ) {
			// For compatibility local changes are provided as User object to the hook
			$hookUser = $this->userFactory->newFromUserIdentity( $target );
		} else {
			$hookUser = $target;
		}

		// Hooks expect User object as performer; everywhere else use Authority for ease of mocking
		if ( $performer instanceof User ) {
			$performerUser = $performer;
		} else {
			$performerUser = $this->userFactory->newFromUserIdentity( $performer->getUser() );
		}
		$this->hookRunner->onChangeUserGroups( $performerUser, $hookUser, $addGroups, $removeGroups );

		// Remove groups, then add new ones/update expiries of existing ones
		foreach ( $removeGroups as $index => $group ) {
			if ( !$userGroupManager->removeUserFromGroup( $target, $group ) ) {
				unset( $removeGroups[$index] );
			}
		}
		foreach ( $addGroups as $index => $group ) {
			$expiry = $newExpiries[$group] ?? null;
			if ( !$userGroupManager->addUserToGroup( $target, $group, $expiry, true ) ) {
				unset( $addGroups[$index] );
			}
		}
		$newGroupMemberships = $userGroupManager->getUserGroupMemberships( $target );

		// Ensure that caches are cleared
		$this->userFactory->invalidateCache( $target );

		// Allow other code to react to the user groups change
		$this->hookRunner->onUserGroupsChanged( $hookUser, $addGroups, $removeGroups,
			$performerUser, $reason, $oldGroupMemberships, $newGroupMemberships );

		// Only add a log entry if something actually changed
		if ( $newGroupMemberships != $oldGroupMemberships ) {
			$this->addRightsLogEntry( $performer->getUser(), $target, $reason, $tags, $oldGroupMemberships,
				$newGroupMemberships, $logAtAdditionalWikis );
		}

		return [ $addGroups, $removeGroups ];
	}

	/** @inheritDoc */
	protected function getKnownGroups( UserIdentity $target ): array {
		$userGroupManager = $this->userGroupManagerFactory->getUserGroupManager( $target->getWikiId() );
		return $userGroupManager->listAllGroups();
	}

	/** @inheritDoc */
	protected function getRestrictedGroupChecker( UserIdentity $target ): RestrictedUserGroupChecker {
		return $this->restrictedGroupCheckerFactory->getRestrictedUserGroupChecker( $target->getWikiId() );
	}

	/**
	 * Add a rights log entry for rights change on all relevant wikis.
	 * The relevant wikis are: the current wiki and the wiki where user's rights are changed.
	 * @param UserIdentity $performer
	 * @param UserIdentity $target
	 * @param string $reason
	 * @param string[] $tags Change tags for the log entry
	 * @param array<string,UserGroupMembership> $oldUGMs Associative array of (group name => UserGroupMembership)
	 * @param array<string,UserGroupMembership> $newUGMs Associative array of (group name => UserGroupMembership)
	 * @param list<string> $additionalWikis List of additional wiki IDs where the log entry should be added
	 *   Values in this array are deduplicated; no double logging will happen
	 */
	private function addRightsLogEntry( UserIdentity $performer, UserIdentity $target, string $reason,
		array $tags, array $oldUGMs, array $newUGMs, array $additionalWikis = []
	) {
		$wikis = array_merge(
			[ UserIdentity::LOCAL, $target->getWikiId() ],
			$additionalWikis
		);
		// Deduplicate wikis, ensure that explicit and implicit references to the current wiki are treated the same
		$wikis = array_unique( array_map(
			static fn ( $wiki ) => WikiMap::isCurrentWikiId( $wiki ) ? UserIdentity::LOCAL : $wiki,
			$wikis
		) );

		$currentWiki = WikiMap::getCurrentWikiId();
		foreach ( $wikis as $wiki ) {
			$logPerformer = $performer;
			if ( $wiki !== UserIdentity::LOCAL ) {
				$logPerformer = UserIdentityValue::newExternal( $currentWiki, $performer->getName(), $wiki );
			}
			$this->addRightsLogEntryOnWiki( $logPerformer, $target, $reason, $tags, $oldUGMs, $newUGMs, $wiki );
		}
	}

	/**
	 * Add a rights log entry for an action.
	 * @param UserIdentity $performer
	 * @param UserIdentity $target
	 * @param string $reason
	 * @param string[] $tags Change tags for the log entry
	 * @param array<string,UserGroupMembership> $oldUGMs Associative array of (group name => UserGroupMembership)
	 * @param array<string,UserGroupMembership> $newUGMs Associative array of (group name => UserGroupMembership)
	 * @param string|false $wiki The wiki to add the log entry to.
	 */
	private function addRightsLogEntryOnWiki( UserIdentity $performer, UserIdentity $target, string $reason,
		array $tags, array $oldUGMs, array $newUGMs, string|false $wiki = UserIdentity::LOCAL
	) {
		ksort( $oldUGMs );
		ksort( $newUGMs );
		$oldUGMs = array_map( self::serialiseUgmForLog( ... ), $oldUGMs );
		$oldGroups = array_keys( $oldUGMs );
		$oldUGMs = array_values( $oldUGMs );
		$newUGMs = array_map( self::serialiseUgmForLog( ... ), $newUGMs );
		$newGroups = array_keys( $newUGMs );
		$newUGMs = array_values( $newUGMs );

		$logEntry = new ManualLogEntry( 'rights', 'rights' );
		$logEntry->setPerformer( $performer );
		$logEntry->setTarget( $this->getPageForTargetUser( $target, $wiki ) );
		$logEntry->setComment( $reason );
		$logEntry->setParameters( [
			'4::oldgroups' => $oldGroups,
			'5::newgroups' => $newGroups,
			'oldmetadata' => $oldUGMs,
			'newmetadata' => $newUGMs,
		] );
		$logId = $logEntry->insert(
			$this->connectionProvider->getPrimaryDatabase( $wiki )
		);
		if ( $wiki === UserIdentity::LOCAL || WikiMap::isCurrentWikiId( $wiki ) ) {
			// These methods are supported only on the local wiki
			$logEntry->addTags( $tags );
			$logEntry->publish( $logId );
		}
	}

	/**
	 * Returns a PageIdentity referring to the target user page. The title is created from the perspective of the
	 * reference wiki. If target is from reference wiki, this function will try to match an actual page.
	 */
	private function getPageForTargetUser( UserIdentity $target, string|false $referenceWiki ): PageIdentity {
		$pageTitle = $this->getPageTitleForTargetUser( $target, $referenceWiki );
		if ( $referenceWiki === UserIdentity::LOCAL || WikiMap::isCurrentWikiId( $referenceWiki ) ) {
			return Title::makeTitle( NS_USER, $pageTitle );
		}
		$pageStore = $this->pageStoreFactory->getPageStore( $referenceWiki );
		$pageTitle = strtr( $pageTitle, ' ', '_' );
		$page = $pageStore->getPageByName( NS_USER, $pageTitle );
		return $page ?? new PageIdentityValue( 0, NS_USER, $pageTitle, $referenceWiki );
	}

	/**
	 * Returns the title of page representing the target user, suitable for use in log entries.
	 * The returned value doesn't include the namespace.
	 *
	 * Interwiki suffix will be added to the title only if the target user's wiki is different from the reference wiki.
	 * @param UserIdentity $target The target user (only name and wiki ID are relevant for this method)
	 * @param string|false $referenceWiki The wiki to use as a reference for formatting the title.
	 *     If the wiki is different from the target user's wiki, the returned title will include the interwiki suffix.
	 */
	public function getPageTitleForTargetUser(
		UserIdentity $target, string|false $referenceWiki = UserIdentity::LOCAL
	): string {
		$targetName = $target->getName();
		$targetWiki = $target->getWikiId() === UserIdentity::LOCAL ? WikiMap::getCurrentWikiId() : $target->getWikiId();
		$referenceWiki = $referenceWiki === UserIdentity::LOCAL ? WikiMap::getCurrentWikiId() : $referenceWiki;
		if ( $referenceWiki === $targetWiki ) {
			return $targetName;
		}
		$delimiter = $this->options->get( MainConfigNames::UserrightsInterwikiDelimiter );
		return $targetName . $delimiter . $targetWiki;
	}

	/**
	 * Serialise a UserGroupMembership object for storage in the log_params section
	 * of the logging table. Only keeps essential data, removing redundant fields.
	 */
	private static function serialiseUgmForLog( UserGroupMembership $ugm ): array {
		return [ 'expiry' => $ugm->getExpiry() ];
	}
}
