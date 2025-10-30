<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;

/**
 * A service to check whether a user can be added or removed to/from restricted user groups.
 * It checks only the restrictions defined for the group and does not perform any other permission checks.
 *
 * @since 1.46
 */
class RestrictedUserGroupChecker {

	/** @internal */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::RestrictedGroups,
	];

	/** @var array<string,array> */
	private array $restrictedGroups;

	public function __construct(
		ServiceOptions $options,
		private readonly UserRequirementsConditionChecker $userRequirementsConditionChecker,
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->restrictedGroups = $options->get( MainConfigNames::RestrictedGroups );
	}

	/**
	 * Checks whether the given group is restricted. A group is considered restricted if it has an entry
	 * defined in $wgRestrictedGroups (even if its value would be an empty array).
	 */
	public function isGroupRestricted( string $groupName ): bool {
		return isset( $this->restrictedGroups[ $groupName ] );
	}

	/**
	 * Checks whether the performer can add the target to the given restricted group.
	 *
	 * Note: This method only tests against the restrictions defined for the group. It doesn't take into account
	 * other permission checks that may apply (e.g., whether the performer has the right to edit user groups at all).
	 */
	public function canPerformerAddTargetToGroup(
		Authority $performer,
		UserIdentity $target,
		string $groupName
	): bool {
		if ( !$this->isGroupRestricted( $groupName ) ) {
			return true;
		}
		if ( $this->canPerformerIgnoreGroupRestrictions( $performer, $groupName ) ) {
			return true;
		}
		return $this->doPerformerAndTargetMeetConditionsForAddingToGroup( $performer->getUser(), $target, $groupName );
	}

	/**
	 * Checks whether both the performer and the target meet the conditions required for adding the target to
	 * the given restricted group.
	 *
	 * Note: Even if this method returns false, the performer may still be allowed to add the target to the group
	 * if they can ignore group restrictions (use {@see canPerformerAddTargetToGroup()} for that). Calling this
	 * method may be useful to inform the performer when they ignore the restrictions.
	 */
	public function doPerformerAndTargetMeetConditionsForAddingToGroup(
		UserIdentity $performer,
		UserIdentity $target,
		string $groupName
	): bool {
		$groupRestrictions = $this->getGroupRestrictions( $groupName );
		if ( !$this->doesPerformerMeetConditions( $performer, $groupRestrictions ) ) {
			return false;
		}
		return $this->doesTargetMeetConditions( $target, $groupRestrictions );
	}

	/**
	 * Checks whether the performer can remove the target from the given restricted group.
	 *
	 * Note: This method only tests against the restrictions defined for the group. It doesn't take into account
	 * other permission checks that may apply (e.g., whether the performer has the right to edit user groups at all).
	 * Note: Currently, it's unsupported to define restrictions for removing users from groups, so this method
	 * always returns true.
	 */
	public function canPerformerRemoveTargetFromGroup(
		Authority $performer,
		UserIdentity $target,
		string $groupName
	): bool {
		// This was beyond the scope of T406544, so we assume that restricted groups can always be removed
		return true;
	}

	/**
	 * Returns true if the performer can ignore the conditions for adding or removing users to/from the given group.
	 * This is the case if the group allows ignoring restrictions and the performer has the 'ignore-restricted-groups'
	 * permission.
	 */
	public function canPerformerIgnoreGroupRestrictions( Authority $performer, string $groupName ): bool {
		$groupRestrictions = $this->getGroupRestrictions( $groupName );
		if ( !$groupRestrictions['canBeIgnored'] ) {
			return false;
		}
		return $performer->isAllowed( 'ignore-restricted-groups' );
	}

	private function doesPerformerMeetConditions( UserIdentity $performer, array $groupRestrictions ): bool {
		$performerRestrictions = $groupRestrictions['updaterConditions'];
		if ( !$performerRestrictions ) {
			// No restrictions, so automatically meets the requirements
			return true;
		}

		return $this->userRequirementsConditionChecker->recursivelyCheckCondition(
			$performerRestrictions,
			$performer,
			true
		);
	}

	private function doesTargetMeetConditions( UserIdentity $target, array $groupRestrictions ): bool {
		$targetRestrictions = $groupRestrictions['memberConditions'];
		if ( !$targetRestrictions ) {
			// No restrictions, so automatically meets them
			return true;
		}

		return $this->userRequirementsConditionChecker->recursivelyCheckCondition(
			$targetRestrictions,
			$target,
			false
		);
	}

	/**
	 * Get the restrictions for a given group, ensuring all expected keys are present.
	 *
	 * The default values are:
	 * - memberConditions: empty array (meaning no restrictions)
	 * - updaterConditions: empty array (meaning no restrictions)
	 * - canBeIgnored: false
	 * @return array{memberConditions:array,updaterConditions:array,canBeIgnored:bool}
	 */
	private function getGroupRestrictions( string $groupName ): array {
		$groupRestrictions = $this->restrictedGroups[$groupName] ?? [];
		$groupRestrictions['memberConditions'] ??= [];
		$groupRestrictions['updaterConditions'] ??= [];
		$groupRestrictions['canBeIgnored'] ??= false;
		return $groupRestrictions;
	}
}
