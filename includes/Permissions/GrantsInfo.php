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
 * Users can authorize applications to use their account via OAuth. Grants are used to
 * limit permissions for these application. This service allows application logic to
 * access grants.
 *
 * @since 1.38
 */
class GrantsInfo {
	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		'GrantPermissions',
		'GrantPermissionGroups',
	];

	/** @var ServiceOptions */
	private $options;

	/**
	 * @param ServiceOptions $options
	 */
	public function __construct(
		ServiceOptions $options
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
	}

	/**
	 * List all known grants.
	 * @return string[]
	 */
	public function getValidGrants(): array {
		return array_keys( $this->options->get( 'GrantPermissions' ) );
	}

	/**
	 * Map all grants to corresponding user rights.
	 * @return string[][] grant => array of rights in the grant
	 */
	public function getRightsByGrant(): array {
		$res = [];
		foreach ( $this->options->get( 'GrantPermissions' ) as $grant => $rights ) {
			$res[$grant] = array_keys( array_filter( $rights ) );
		}
		return $res;
	}

	/**
	 * Fetch the rights allowed by a set of grants.
	 * @param string[]|string $grants
	 * @return string[]
	 */
	public function getGrantRights( $grants ): array {
		$rights = [];
		foreach ( (array)$grants as $grant ) {
			if ( isset( $this->options->get( 'GrantPermissions' )[$grant] ) ) {
				$rights = array_merge(
					$rights,
					array_keys( array_filter( $this->options->get( 'GrantPermissions' )[$grant] ) )
				);
			}
		}
		return array_unique( $rights );
	}

	/**
	 * Test that all grants in the list are known.
	 * @param string[] $grants
	 * @return bool
	 */
	public function grantsAreValid( array $grants ): bool {
		return array_diff( $grants, $this->getValidGrants() ) === [];
	}

	/**
	 * Divide the grants into groups.
	 * @param string[]|null $grantsFilter
	 * @return string[][] Map of (group => (grant list))
	 */
	public function getGrantGroups( array $grantsFilter = null ): array {
		if ( is_array( $grantsFilter ) ) {
			$grantsFilter = array_fill_keys( $grantsFilter, true );
		}

		$groups = [];
		foreach ( $this->options->get( 'GrantPermissions' ) as $grant => $rights ) {
			if ( $grantsFilter !== null && !isset( $grantsFilter[$grant] ) ) {
				continue;
			}
			if ( isset( $this->options->get( 'GrantPermissionGroups' )[$grant] ) ) {
				$groups[$this->options->get( 'GrantPermissionGroups' )[$grant]][] = $grant;
			} else {
				$groups['other'][] = $grant;
			}
		}

		return $groups;
	}

	/**
	 * Get the list of grants that are hidden and should always be granted.
	 * @return string[]
	 */
	public function getHiddenGrants(): array {
		$grants = [];
		foreach ( $this->options->get( 'GrantPermissionGroups' ) as $grant => $group ) {
			if ( $group === 'hidden' ) {
				$grants[] = $grant;
			}
		}
		return $grants;
	}
}
