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
use Wikimedia\Rdbms\IConnectionProvider;

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

	private IConnectionProvider $dbProvider;
	private UserNameUtils $userNameUtils;

	/**
	 * @param IConnectionProvider $dbProvider
	 * @param UserNameUtils $userNameUtils
	 */
	public function __construct(
		IConnectionProvider $dbProvider,
		UserNameUtils $userNameUtils
	) {
		$this->dbProvider = $dbProvider;
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

		$dbr = $this->dbProvider->getReplicaDatabase();
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( 'user_name' )
			->from( 'user' )
			->where( [ 'user_name ' . $dbr->buildLike( $prefix, $dbr->anyString() ) ] )
			->orderBy( 'user_name' )
			->limit( $limit )
			->offset( $offset );

		// Filter out hidden user names
		if ( $audience === self::AUDIENCE_PUBLIC || !$audience->isAllowed( 'hideuser' ) ) {
			$queryBuilder->leftJoin( 'ipblocks', null, 'user_id=ipb_user' );
			$queryBuilder->andWhere( [ 'ipb_deleted' => [ 0, null ] ] );
		}

		return $queryBuilder->caller( __METHOD__ )->fetchFieldValues();
	}
}
