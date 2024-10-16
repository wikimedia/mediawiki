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
 * Users can authorize applications to use their account via OAuth. Grants are used to
 * limit permissions for these application. This service allows application logic to
 * access grants.
 *
 * @since 1.38
 */
class GrantsInfo {
	/**
	 * Risk level classification for grants which aren't particularly risky. These grants might
	 * be abused, e.g. for vandalism, but the effect is easy to undo and the efficiency of abusing
	 * them isn't particularly different from registering new user accounts and using those for
	 * abuse.
	 * Note that risk levels depend on the use case; the default classification is meant for
	 * "normal" (public, open registration) wikis. Classification for e.g. a private wiki holding
	 * confidential information could be quite different.
	 */
	public const RISK_LOW = 'low';

	/**
	 * Risk level classification for grants which can be used for disruptive vandalism or other
	 * kinds of abuse that couldn't be achieved just by registering new accounts, such as main
	 * page vandalism, vandalism of popular templates, page merge vandalism, or blocks.
	 */
	public const RISK_VANDALISM = 'vandalism';

	/**
	 * Risk level classification for grants which can be used to cause damage that is hard or
	 * impossible to undo, such as exfiltrating sensitive private data or creating security
	 * vulnerabilities.
	 */
	public const RISK_SECURITY = 'security';

	/**
	 * Risk level classification for grants which are used for internal purposes and should not
	 * be handed out.
	 */
	public const RISK_INTERNAL = 'internal';

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::GrantPermissions,
		MainConfigNames::GrantPermissionGroups,
		MainConfigNames::GrantRiskGroups,
	];

	private ServiceOptions $options;

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
		return array_keys( $this->options->get( MainConfigNames::GrantPermissions ) );
	}

	/**
	 * Map all grants to corresponding user rights.
	 * @return string[][] grant => array of rights in the grant
	 */
	public function getRightsByGrant(): array {
		$res = [];
		foreach ( $this->options->get( MainConfigNames::GrantPermissions ) as $grant => $rights ) {
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
			if ( isset( $this->options->get( MainConfigNames::GrantPermissions )[$grant] ) ) {
				$rights = array_merge(
					$rights,
					array_keys( array_filter( $this->options->get( MainConfigNames::GrantPermissions )[$grant] ) )
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
	public function getGrantGroups( ?array $grantsFilter = null ): array {
		if ( is_array( $grantsFilter ) ) {
			$grantsFilter = array_fill_keys( $grantsFilter, true );
		}

		$groups = [];
		foreach ( $this->options->get( MainConfigNames::GrantPermissions ) as $grant => $rights ) {
			if ( $grantsFilter !== null && !isset( $grantsFilter[$grant] ) ) {
				continue;
			}
			if ( isset( $this->options->get( MainConfigNames::GrantPermissionGroups )[$grant] ) ) {
				$groups[$this->options->get( MainConfigNames::GrantPermissionGroups )[$grant]][] = $grant;
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
		foreach ( $this->options->get( MainConfigNames::GrantPermissionGroups ) as $grant => $group ) {
			if ( $group === 'hidden' ) {
				$grants[] = $grant;
			}
		}
		return $grants;
	}

	/**
	 * Returns a map of grant name => risk group. The risk groups are the GrantsInfo::RISK_*
	 * constants, plus $default for grants where the risk level is not defined.
	 * @param string $default Default risk group to assign to grants for which no risk group
	 * is configured. $default does not have to be one of the RISK_* constants.
	 * @return string[]
	 * @since 1.42
	 */
	public function getRiskGroupsByGrant( string $default = 'unknown' ): array {
		$res = [];
		$grantRiskGroups = $this->options->get( MainConfigNames::GrantRiskGroups );
		foreach ( $this->options->get( MainConfigNames::GrantPermissions ) as $grant => $_ ) {
			$res[$grant] = $grantRiskGroups[$grant] ?? $default;
		}
		return $res;
	}
}
