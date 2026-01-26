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
use MediaWiki\Permissions\Authority;
use MediaWiki\Title\Title;
use MediaWiki\User\TempUser\TempUserConfig;
use Wikimedia\Timestamp\TimestampFormat as TS;

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
class UserGroupAssignmentService {

	/** @internal */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::UserrightsInterwikiDelimiter
	];

	private array $changeableGroupsCache = [];

	public function __construct(
		private readonly UserGroupManagerFactory $userGroupManagerFactory,
		private readonly UserNameUtils $userNameUtils,
		private readonly UserFactory $userFactory,
		private readonly RestrictedUserGroupChecker $restrictedGroupChecker,
		private readonly HookRunner $hookRunner,
		private readonly ServiceOptions $options,
		private readonly TempUserConfig $tempUserConfig,
	) {
		$this->options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
	}

	/**
	 * Checks whether the target user can have groups assigned at all.
	 */
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

	/**
	 * Returns the groups that the performer can add or remove from the target user.
	 * The result of this function is cached for the duration of the request.
	 * @return array [
	 *   'add' => [ addablegroups ],
	 *   'remove' => [ removablegroups ],
	 *   'restricted' => [ groupname => [
	 *     'condition-met' => bool,
	 *     'ignore-condition' => bool,
	 *     'message' => string
	 *   ] ]
	 *  ]
	 * @phan-return array{add:list<string>,remove:list<string>,restricted:array<string,array>}
	 */
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
		foreach ( $groups['add'] as $group ) {
			if ( $this->restrictedGroupChecker->isGroupRestricted( $group ) ) {
				$groups['restricted'][$group] = [
					'condition-met' => $this->restrictedGroupChecker
						->doPerformerAndTargetMeetConditionsForAddingToGroup(
							$performer->getUser(),
							$target,
							$group,
							$evaluatePrivateConditionsForRestrictedGroups
						),
					'ignore-condition' => $this->restrictedGroupChecker
						->canPerformerIgnoreGroupRestrictions(
							$performer,
							$group
						),
				];
				$canPerformerAdd = $this->restrictedGroupChecker->canPerformerAddTargetToGroup(
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
	 * @return array{0:string[],1:string[]} The groups actually added and removed
	 */
	public function saveChangesToUserGroups(
		Authority $performer,
		UserIdentity $target,
		array $addGroups,
		array $removeGroups,
		array $newExpiries,
		string $reason = '',
		array $tags = []
	): array {
		$userGroupManager = $this->userGroupManagerFactory->getUserGroupManager( $target->getWikiId() );

		$oldGroupMemberships = $userGroupManager->getUserGroupMemberships( $target );
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
			$this->addLogEntry( $performer->getUser(), $target, $reason, $tags, $oldGroupMemberships,
				$newGroupMemberships );
		}

		return [ $addGroups, $removeGroups ];
	}

	/**
	 * Validates the requested changes to user groups and returns an array, specifying if some groups are unchangeable
	 * and for what reasons.
	 * @param Authority $performer
	 * @param UserIdentity $target
	 * @param list<string> $addGroups
	 * @param list<string> $removeGroups
	 * @param array<string, ?string> $newExpiries
	 * @param array<string, UserGroupMembership> $groupMemberships
	 * @return array<string, string> Map of user groups to the reason why they cannot be given, removed or updated,
	 *     keyed by the group names. The supported reasons are: 'rights', 'restricted', 'private-condition'.
	 */
	public function validateUserGroups(
		Authority $performer,
		UserIdentity $target,
		array $addGroups,
		array $removeGroups,
		array $newExpiries,
		array $groupMemberships,
	) {
		// We have to find out which groups the user is unable to change and also whether it's due to
		// private conditions or not. In the former case, we need to be able to log access to the conditions.
		$permittedChangesNoPrivate = $this->getChangeableGroups( $performer, $target, false );
		$permittedChangesWithPrivate = $this->getChangeableGroups( $performer, $target );

		[ $unaddableNoPrivate, $irremovableNoPrivate ] = self::getDisallowedGroupChanges(
			$addGroups, $removeGroups, $newExpiries, $groupMemberships, $permittedChangesNoPrivate );
		[ $unaddableWithPrivate, $irremovableWithPrivate ] = self::getDisallowedGroupChanges(
			$addGroups, $removeGroups, $newExpiries, $groupMemberships, $permittedChangesWithPrivate );

		$unchangeableGroupsNoPrivate = array_merge( $unaddableNoPrivate, $irremovableNoPrivate );
		$unchangeableGroupsWithPrivate = array_merge( $unaddableWithPrivate, $irremovableWithPrivate );

		$unchangeableGroupsDueToPrivate = array_diff( $unchangeableGroupsWithPrivate, $unchangeableGroupsNoPrivate );

		$restrictedGroups = $permittedChangesWithPrivate['restricted'];

		$userGroupManager = $this->userGroupManagerFactory->getUserGroupManager( $target->getWikiId() );
		$knownGroups = $userGroupManager->listAllGroups();

		$result = [];
		foreach ( $unchangeableGroupsWithPrivate as $group ) {
			// Sometimes people are assigned to groups that no longer are defined. Let's ignore them for validation
			if ( !in_array( $group, $knownGroups ) ) {
				continue;
			}

			if ( in_array( $group, $unchangeableGroupsDueToPrivate ) ) {
				$result[$group] = 'private-condition';
			} elseif ( isset( $restrictedGroups[$group] ) ) {
				$result[$group] = 'restricted';
			} else {
				$result[$group] = 'rights';
			}
		}
		return $result;
	}

	/**
	 * Add a rights log entry for an action.
	 * @param UserIdentity $performer
	 * @param UserIdentity $target
	 * @param string $reason
	 * @param string[] $tags Change tags for the log entry
	 * @param array<string,UserGroupMembership> $oldUGMs Associative array of (group name => UserGroupMembership)
	 * @param array<string,UserGroupMembership> $newUGMs Associative array of (group name => UserGroupMembership)
	 */
	private function addLogEntry( UserIdentity $performer, UserIdentity $target, string $reason,
		array $tags, array $oldUGMs, array $newUGMs
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
		$logEntry->setTarget( Title::makeTitle( NS_USER, $this->getPageTitleForTargetUser( $target ) ) );
		$logEntry->setComment( $reason );
		$logEntry->setParameters( [
			'4::oldgroups' => $oldGroups,
			'5::newgroups' => $newGroups,
			'oldmetadata' => $oldUGMs,
			'newmetadata' => $newUGMs,
		] );
		$logId = $logEntry->insert();
		$logEntry->addTags( $tags );
		$logEntry->publish( $logId );
	}

	/**
	 * Returns the title of page representing the target user, suitable for use in log entries.
	 * The returned value doesn't include the namespace.
	 */
	public function getPageTitleForTargetUser( UserIdentity $target ): string {
		$targetName = $target->getName();
		if ( $target->getWikiId() !== UserIdentity::LOCAL ) {
			$targetName .= $this->options->get( MainConfigNames::UserrightsInterwikiDelimiter )
				. $target->getWikiId();
		}
		return $targetName;
	}

	/**
	 * Serialise a UserGroupMembership object for storage in the log_params section
	 * of the logging table. Only keeps essential data, removing redundant fields.
	 */
	private static function serialiseUgmForLog( UserGroupMembership $ugm ): array {
		return [ 'expiry' => $ugm->getExpiry() ];
	}

	/**
	 * Ensures that the content of $addGroups, $removeGroups and $newExpiries is compliant
	 * with the possible changes defined in $permittedChanges. If there's a change that
	 * is not permitted, it is removed from the respective array.
	 * @param list<string> &$addGroups
	 * @param list<string> &$removeGroups
	 * @param array<string, ?string> &$newExpiries
	 * @param array<string, UserGroupMembership> $existingUGMs
	 * @param array{add:list<string>,remove:list<string>} $permittedChanges
	 * @return void
	 */
	public static function enforceChangeGroupPermissions(
		array &$addGroups,
		array &$removeGroups,
		array &$newExpiries,
		array $existingUGMs,
		array $permittedChanges
	): void {
		[ $unaddableGroups, $irremovableGroups ] = self::getDisallowedGroupChanges(
			$addGroups, $removeGroups, $newExpiries, $existingUGMs, $permittedChanges
		);

		$addGroups = array_diff( $addGroups, $unaddableGroups );
		$removeGroups = array_diff( $removeGroups, $irremovableGroups );
		foreach ( $unaddableGroups as $group ) {
			unset( $newExpiries[$group] );
		}
	}

	/**
	 * Returns which of the attempted group changed are not allowed, given $permittedChanges.
	 * @param list<string> $addGroups
	 * @param list<string> $removeGroups
	 * @param array<string, ?string> $newExpiries
	 * @param array<string, UserGroupMembership> $existingUGMs
	 * @param array{add:list<string>,remove:list<string>} $permittedChanges
	 * @return array{0:list<string>,1:list<string>} List of unaddable groups and list of irremovable groups
	 */
	private static function getDisallowedGroupChanges(
		array $addGroups,
		array $removeGroups,
		array $newExpiries,
		array $existingUGMs,
		array $permittedChanges
	): array {
		$canAdd = $permittedChanges['add'];
		$canRemove = $permittedChanges['remove'];
		$involvedGroups = array_unique( array_merge( array_keys( $existingUGMs ), $addGroups, $removeGroups ) );

		// These do not reflect actual permissions, but rather the groups to remove from $addGroups and $removeGroups
		$unaddableGroups = [];
		$irremovableGroups = [];

		foreach ( $involvedGroups as $group ) {
			$hasGroup = isset( $existingUGMs[$group] );
			$wantsAddGroup = in_array( $group, $addGroups );
			$wantsRemoveGroup = in_array( $group, $removeGroups );

			// Better safe than sorry - catch it if the input is contradictory
			if (
				( !$hasGroup && $wantsRemoveGroup ) ||
				( $wantsAddGroup && $wantsRemoveGroup )
			) {
				$unaddableGroups[] = $group;
				$irremovableGroups[] = $group;
				continue;
			}
			// If there's no change, we don't have to change anything
			if ( !$hasGroup && !$wantsAddGroup ) {
				continue;
			}
			if ( $hasGroup && !$wantsRemoveGroup && !$wantsAddGroup ) {
				// We have to check for adding group, because it's set when changing expiry
				continue;
			}

			if ( $hasGroup && $wantsRemoveGroup ) {
				if ( !in_array( $group, $canRemove ) ) {
					$irremovableGroups[] = $group;
				}
			} elseif ( !$hasGroup && $wantsAddGroup ) {
				if ( !in_array( $group, $canAdd ) ) {
					$unaddableGroups[] = $group;
				}
			} elseif ( $hasGroup && $wantsAddGroup ) {
				$currentExpiry = $existingUGMs[$group]->getExpiry() ?? 'infinity';
				$wantedExpiry = $newExpiries[$group] ?? 'infinity';

				if ( $wantedExpiry > $currentExpiry ) {
					// Prolongation requires 'add' permission
					$canChange = in_array( $group, $canAdd );
				} else {
					// Shortening requires 'remove' permission
					$canChange = in_array( $group, $canRemove );
				}

				if ( !$canChange ) {
					// Restore the original group expiry if user can't change it
					$unaddableGroups[] = $group;
				}
			}
		}

		return [ $unaddableGroups, $irremovableGroups ];
	}

	/**
	 * Converts a user group membership expiry string into a timestamp. Words like
	 * 'existing' or 'other' should have been filtered out before calling this
	 * function.
	 *
	 * @param string $expiry
	 * @return string|null|false A string containing a valid timestamp, or null
	 *   if the expiry is infinite, or false if the timestamp is not valid
	 */
	public static function expiryToTimestamp( $expiry ) {
		if ( wfIsInfinity( $expiry ) ) {
			return null;
		}

		$unix = strtotime( $expiry );

		if ( !$unix || $unix === -1 ) {
			return false;
		}

		// @todo FIXME: Non-qualified absolute times are not in users specified timezone
		// and there isn't notice about it in the ui (see ProtectionForm::getExpiry)
		return wfTimestamp( TS::MW, $unix );
	}
}
