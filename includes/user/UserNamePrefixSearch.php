<?php
/**
 * Prefix search of user names.
 *
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

namespace MediaWiki\User;

use InvalidArgumentException;
use MediaWiki\Permissions\Authority;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Handles searching prefixes of user names
 *
 * @note There are two classes called UserNamePrefixSearch.  You should use this first one, in
 * namespace MediaWiki\User, which is a service.  \UserNamePrefixSearch is a deprecated static wrapper
 * that forwards to the global service.
 *
 * @since 1.36 as a service in the current namespace
 * @author DannyS712
 */
class UserNamePrefixSearch {

	/** @var string */
	public const AUDIENCE_PUBLIC = 'public';

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var UserNameUtils */
	private $userNameUtils;

	/**
	 * @param ILoadBalancer $loadBalancer
	 * @param UserNameUtils $userNameUtils
	 */
	public function __construct(
		ILoadBalancer $loadBalancer,
		UserNameUtils $userNameUtils
	) {
		$this->loadBalancer = $loadBalancer;
		$this->userNameUtils = $userNameUtils;
	}

	/**
	 * Do a prefix search of user names and return a list of matching user names.
	 *
	 * @param string|Authority $audience Either AUDIENCE_PUBLIC or a user to
	 *    show the search for
	 * @param string $search
	 * @param int $limit
	 * @param int $offset How many results to offset from the beginning
	 * @return string[]
	 * @throws InvalidArgumentException if $audience is invalid
	 */
	public function search( $audience, string $search, int $limit, int $offset = 0 ): array {
		if ( $audience !== self::AUDIENCE_PUBLIC &&
			!( $audience instanceof Authority )
		) {
			throw new InvalidArgumentException(
				'$audience must be AUDIENCE_PUBLIC or an Authority object'
			);
		}

		// Invalid user names are treated as empty strings
		$prefix = $this->userNameUtils->getCanonical( $search ) ?: '';

		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );

		$tables = [ 'user' ];
		$conds = [ 'user_name ' . $dbr->buildLike( $prefix, $dbr->anyString() ) ];
		$joinConds = [];

		// Filter out hidden user names
		if ( $audience === self::AUDIENCE_PUBLIC || !$audience->isAllowed( 'hideuser' ) ) {
			$tables[] = 'ipblocks';
			$conds['ipb_deleted'] = [ 0, null ];
			$joinConds['ipblocks'] = [ 'LEFT JOIN', 'user_id=ipb_user' ];
		}

		$res = $dbr->selectFieldValues(
			$tables,
			'user_name',
			$conds,
			__METHOD__,
			[
				'LIMIT' => $limit,
				'ORDER BY' => 'user_name',
				'OFFSET' => $offset
			],
			$joinConds
		);

		return $res;
	}
}
