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

/**
 * Lookup permissions for groups and groups with permissions.
 * @since 1.36
 * @package MediaWiki\Permissions
 */
class GroupPermissionsLookup {

	/**
	 * @internal
	 * @var string[]
	 */
	public const CONSTRUCTOR_OPTIONS = [
		'GroupPermissions',
		'RevokePermissions',
	];

	/** @var array */
	private $groupPermissions;

	/** @var array */
	private $revokePermissions;

	/*
	 * @param ServiceOptions $options
	 */
	public function __construct( ServiceOptions $options ) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->groupPermissions = $options->get( 'GroupPermissions' );
		$this->revokePermissions = $options->get( 'RevokePermissions' );
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
		return isset( $this->groupPermissions[$group][$permission] ) &&
			$this->groupPermissions[$group][$permission] &&
			!( isset( $this->revokePermissions[$group][$permission] ) &&
				$this->revokePermissions[$group][$permission] );
	}

	/**
	 * Get the permissions associated with a given list of groups
	 *
	 * @param string[] $groups internal group names
	 * @return string[] permission key names for given groups combined
	 */
	public function getGroupPermissions( array $groups ): array {
		$rights = [];
		// grant every granted permission first

		foreach ( $groups as $group ) {
			if ( isset( $this->groupPermissions[$group] ) ) {
				$rights = array_merge(
					$rights,
					// array_filter removes empty items
					array_keys( array_filter( $this->groupPermissions[$group] ) )
				);
			}
		}
		// now revoke the revoked permissions
		foreach ( $groups as $group ) {
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
		foreach ( array_keys( $this->groupPermissions ) as $group ) {
			if ( $this->groupHasPermission( $group, $permission ) ) {
				$allowedGroups[] = $group;
			}
		}
		return $allowedGroups;
	}
}
