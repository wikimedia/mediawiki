<?php
/*
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

/**
 * Represents the restrictions defined for a restricted user group.
 *
 * @since 1.46
 */
class UserGroupRestrictions {

	private readonly array $memberConditions;
	private readonly array $updaterConditions;
	private readonly bool $canBeIgnored;

	/**
	 * @param array $restrictionSpec The restriction specification for a group, typically coming from values
	 *   defined in $wgRestrictedGroups. All keys in this array are optional. The default values are:
	 *   - memberConditions: empty array (meaning no restrictions)
	 *   - updaterConditions: empty array (meaning no restrictions)
	 *   - canBeIgnored: false
	 */
	public function __construct( array $restrictionSpec ) {
		$memberConditions = $restrictionSpec['memberConditions'] ?? [];
		if ( !is_array( $memberConditions ) ) {
			$memberConditions = [ $memberConditions ];
		}
		$this->memberConditions = $memberConditions;

		$updaterConditions = $restrictionSpec['updaterConditions'] ?? [];
		if ( !is_array( $updaterConditions ) ) {
			$updaterConditions = [ $updaterConditions ];
		}
		$this->updaterConditions = $updaterConditions;

		$this->canBeIgnored = $restrictionSpec['canBeIgnored'] ?? false;
	}

	public function getMemberConditions(): array {
		return $this->memberConditions;
	}

	public function getUpdaterConditions(): array {
		return $this->updaterConditions;
	}

	public function canBeIgnored(): bool {
		return $this->canBeIgnored;
	}

	/**
	 * Checks if this group restrictions can be continuously enforced. This is the case if conditions for member
	 * are specified, and it's impossible to ignore the restrictions.
	 */
	public function continuouslyEnforced(): bool {
		return !$this->canBeIgnored() && $this->getMemberConditions() !== [];
	}

	/**
	 * Checks if there are conditions defined for this group, either for members or updaters.
	 * If there are no conditions, then the group is effectively not restricted.
	 */
	public function hasAnyConditions(): bool {
		return $this->getMemberConditions() !== [] || $this->getUpdaterConditions() !== [];
	}
}
