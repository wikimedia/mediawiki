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

namespace MediaWiki\Permissions;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;

/**
 * A service class for looking up permissions bestowed to groups, groups bestowed with
 * permissions, and permissions bestowed by membership in a combination of groups, solely
 * according to site configuration for group permissions and inheritence thereof.
 *
 * This class does *not* account for implicit rights (which are not associated with groups).
 * Callers might want to use {@see PermissionManager} if this is an issue.
 *
 * This class does *not* infer membership in one group (e.g. '*') from membership in another
 * (e.g. 'user'). Callers must account for this when using {@see self::getGroupPermissions()}.
 *
 * @since 1.36
 * @package MediaWiki\Permissions
 */
class GroupPermissionsLookup {

	/**
	 * @internal
	 * @var string[]
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::GroupInheritsPermissions,
		MainConfigNames::GroupPermissions,
		MainConfigNames::RevokePermissions,
	];

	/** @var array[] */
	private $groupPermissions;

	/** @var array[] */
	private $revokePermissions;

	/** @var string[] */
	private $groupInheritance;

	/**
	 * @param ServiceOptions $options
	 */
	public function __construct( ServiceOptions $options ) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->groupPermissions = $options->get( MainConfigNames::GroupPermissions );
		$this->revokePermissions = $options->get( MainConfigNames::RevokePermissions );
		$this->groupInheritance = $options->get( MainConfigNames::GroupInheritsPermissions );
	}

	/**
	 * Check, if the given group has the given permission
	 *
	 * If you're wanting to check whether all users have a permission,
	 * use PermissionManager::isEveryoneAllowed() instead.
	 * That properly checks if it's revoked from anyone.
	 *
	 * @param string $group Group to check
	 * @param string $permission Role to check
	 *
	 * @return bool
	 */
	public function groupHasPermission( string $group, string $permission ): bool {
		$inheritsFrom = $this->groupInheritance[$group] ?? false;
		$has = isset( $this->groupPermissions[$group][$permission] ) &&
			$this->groupPermissions[$group][$permission];
		// If the group doesn't have the permission and inherits from somewhere,
		// check that group too
		if ( !$has && $inheritsFrom !== false ) {
			$has = isset( $this->groupPermissions[$inheritsFrom][$permission] ) &&
				$this->groupPermissions[$inheritsFrom][$permission];
		}
		if ( !$has ) {
			// If they don't have the permission, exit early
			return false;
		}

		// Check if the permission has been revoked
		$revoked = isset( $this->revokePermissions[$group][$permission] ) &&
			$this->revokePermissions[$group][$permission];
		if ( !$revoked && $inheritsFrom !== false ) {
			$revoked = isset( $this->revokePermissions[$inheritsFrom][$permission] ) &&
				$this->revokePermissions[$inheritsFrom][$permission];
		}

		return !$revoked;
	}

	/**
	 * Get a list of permissions granted to this group. This
	 * must *NOT* be used for permissions checking as it
	 * does not check whether a permission has been revoked
	 * from this group.
	 *
	 * @param string $group Group to get permissions of
	 * @return string[]
	 * @since 1.38
	 */
	public function getGrantedPermissions( string $group ): array {
		$rights = array_keys( array_filter( $this->groupPermissions[$group] ?? [] ) );
		$inheritsFrom = $this->groupInheritance[$group] ?? false;
		if ( $inheritsFrom !== false ) {
			$rights = array_merge(
				$rights,
				// array_filter removes empty items
				array_keys( array_filter( $this->groupPermissions[$inheritsFrom] ?? [] ) )
			);
		}

		return array_unique( $rights );
	}

	/**
	 * Get a list of permissions revoked from this group
	 *
	 * @param string $group Group to get revoked permissions of
	 * @return string[]
	 * @since 1.38
	 */
	public function getRevokedPermissions( string $group ): array {
		$rights = array_keys( array_filter( $this->revokePermissions[$group] ?? [] ) );
		$inheritsFrom = $this->groupInheritance[$group] ?? false;
		if ( $inheritsFrom !== false ) {
			$rights = array_merge(
				$rights,
				// array_filter removes empty items
				array_keys( array_filter( $this->revokePermissions[$inheritsFrom] ?? [] ) )
			);
		}

		return array_unique( $rights );
	}

	/**
	 * Get the permissions associated with membership in a combination of groups
	 *
	 * Group-based revocation of a permission negates all group-based assignments of that
	 * permission.
	 *
	 * @param string[] $groups internal group names
	 * @return string[] permission key names for given groups combined
	 */
	public function getGroupPermissions( array $groups ): array {
		$rights = [];
		$checkGroups = [];

		// Add inherited groups to the list of groups to check
		foreach ( $groups as $group ) {
			$checkGroups[] = $group;
			if ( isset( $this->groupInheritance[$group] ) ) {
				$checkGroups[] = $this->groupInheritance[$group];
			}
		}

		// grant every granted permission first
		foreach ( $checkGroups as $group ) {
			if ( isset( $this->groupPermissions[$group] ) ) {
				$rights = array_merge(
					$rights,
					// array_filter removes empty items
					array_keys( array_filter( $this->groupPermissions[$group] ) )
				);
			}
		}
		// now revoke the revoked permissions
		foreach ( $checkGroups as $group ) {
			if ( isset( $this->revokePermissions[$group] ) ) {
				$rights = array_diff(
					$rights,
					array_keys( array_filter( $this->revokePermissions[$group] ) )
				);
			}
		}
		return array_unique( $rights );
	}

	/**
	 * Get all the groups who have a given permission
	 *
	 * @param string $permission
	 * @return string[] internal group names with the given permission
	 */
	public function getGroupsWithPermission( string $permission ): array {
		$allowedGroups = [];
		$groups = array_unique( array_merge(
			array_keys( $this->groupPermissions ),
			array_keys( $this->groupInheritance )
		) );
		foreach ( $groups as $group ) {
			if ( $this->groupHasPermission( $group, $permission ) ) {
				$allowedGroups[] = $group;
			}
		}
		return $allowedGroups;
	}
}
