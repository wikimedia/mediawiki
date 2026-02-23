<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\Permissions\Authority;

/**
 * A service to check whether a user can be added or removed to/from restricted user groups.
 * It checks only the restrictions defined for the group and does not perform any other permission checks.
 *
 * @since 1.46
 */
class RestrictedUserGroupChecker {

	/**
	 * @param array<string,UserGroupRestrictions> $groupRestrictions
	 * @param UserRequirementsConditionChecker $userRequirementsConditionChecker
	 */
	public function __construct(
		private readonly array $groupRestrictions,
		private readonly UserRequirementsConditionChecker $userRequirementsConditionChecker,
	) {
	}

	/**
	 * Checks whether the given group is restricted. A group is considered restricted if it has an entry
	 * defined in $wgRestrictedGroups (even if its value would be an empty array).
	 */
	public function isGroupRestricted( string $groupName ): bool {
		return isset( $this->groupRestrictions[ $groupName ] );
	}

	/**
	 * Checks whether the performer can add the target to the given restricted group.
	 *
	 * Note: This method only tests against the restrictions defined for the group. It doesn't take into account
	 * other permission checks that may apply (e.g., whether the performer has the right to edit user groups at all).
	 *
	 * This method will return null if $evaluatePrivateConditions is set to false and the result depends on the
	 * private conditions.
	 */
	public function canPerformerAddTargetToGroup(
		Authority $performer,
		UserIdentity $target,
		string $groupName,
		bool $evaluatePrivateConditions = true
	): ?bool {
		if ( !$this->isGroupRestricted( $groupName ) ) {
			return true;
		}
		if ( $this->canPerformerIgnoreGroupRestrictions( $performer, $groupName ) ) {
			return true;
		}
		return $this->doPerformerAndTargetMeetConditionsForAddingToGroup(
			$performer->getUser(),
			$target,
			$groupName,
			$evaluatePrivateConditions
		);
	}

	/**
	 * Checks whether both the performer and the target meet the conditions required for adding the target to
	 * the given restricted group.
	 *
	 * Note: Even if this method returns false, the performer may still be allowed to add the target to the group
	 * if they can ignore group restrictions (use {@see canPerformerAddTargetToGroup()} for that). Calling this
	 * method may be useful to inform the performer when they ignore the restrictions.
	 *
	 * This method will return null if $evaluatePrivateConditions is set to false and the result depends on the
	 * private conditions.
	 */
	public function doPerformerAndTargetMeetConditionsForAddingToGroup(
		UserIdentity $performer,
		UserIdentity $target,
		string $groupName,
		bool $evaluatePrivateConditions = true
	): ?bool {
		$groupRestrictions = $this->getGroupRestrictions( $groupName );
		if ( !$this->doesPerformerMeetConditions( $performer, $groupRestrictions ) ) {
			return false;
		}
		return $this->doesTargetMeetConditions( $target, $groupRestrictions, $evaluatePrivateConditions );
	}

	/**
	 * Returns true if the performer can ignore the conditions for adding or removing users to/from the given group.
	 * This is the case if the group allows ignoring restrictions and the performer has the 'ignore-restricted-groups'
	 * permission.
	 */
	public function canPerformerIgnoreGroupRestrictions( Authority $performer, string $groupName ): bool {
		$groupRestrictions = $this->getGroupRestrictions( $groupName );
		if ( !$groupRestrictions->canBeIgnored() ) {
			return false;
		}
		return $performer->isAllowed( 'ignore-restricted-groups' );
	}

	private function doesPerformerMeetConditions(
		UserIdentity $performer,
		UserGroupRestrictions $groupRestrictions
	): bool {
		$performerRestrictions = $groupRestrictions->getUpdaterConditions();
		if ( !$performerRestrictions ) {
			// No restrictions, so automatically meets the requirements
			return true;
		}

		// Here, we assume that the private conditions are always evaluated. There's no point in hiding
		// data about the current request performer, as it doesn't leak anything to a third party.
		return (bool)$this->userRequirementsConditionChecker->recursivelyCheckCondition(
			$performerRestrictions,
			$performer
		);
	}

	private function doesTargetMeetConditions(
		UserIdentity $target,
		UserGroupRestrictions $groupRestrictions,
		bool $evaluatePrivateConditions
	): ?bool {
		$targetRestrictions = $groupRestrictions->getMemberConditions();
		if ( !$targetRestrictions ) {
			// No restrictions, so automatically meets them
			return true;
		}

		return $this->userRequirementsConditionChecker->recursivelyCheckCondition(
			$targetRestrictions,
			$target,
			$evaluatePrivateConditions
		);
	}

	/**
	 * Get the restrictions defined for a given group.
	 */
	public function getGroupRestrictions( string $groupName ): UserGroupRestrictions {
		return $this->groupRestrictions[$groupName] ?? new UserGroupRestrictions( [] );
	}

	/**
	 * Returns a list of private conditions that apply to members of the specified group.
	 * @param string $groupName
	 * @return list<mixed>
	 */
	public function getPrivateConditionsForGroup( string $groupName ): array {
		if ( !$this->isGroupRestricted( $groupName ) ) {
			return [];
		}

		$groupRestrictions = $this->getGroupRestrictions( $groupName );
		return $this->userRequirementsConditionChecker->extractPrivateConditions(
			$groupRestrictions->getMemberConditions() );
	}
}
