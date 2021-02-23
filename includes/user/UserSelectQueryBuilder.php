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

namespace MediaWiki\User;

use Iterator;
use Wikimedia\Assert\Assert;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

class UserSelectQueryBuilder extends SelectQueryBuilder {

	/** @var ActorStore */
	private $actorStore;

	/**
	 * @internal
	 * @param IDatabase $db
	 * @param ActorStore $actorStore
	 */
	public function __construct( IDatabase $db, ActorStore $actorStore ) {
		parent::__construct( $db );
		$this->actorStore = $actorStore;
		$this->table( 'actor' );
	}

	/**
	 * Find by provided user ids.
	 *
	 * @param int|int[] $userIds
	 * @return UserSelectQueryBuilder
	 */
	public function userIds( $userIds ): self {
		Assert::parameterType( 'integer|array', $userIds, '$userIds' );
		$this->conds( [ 'actor_user' => $userIds ] );
		return $this;
	}

	/**
	 * Find by provided user names.
	 *
	 * @param string|string[] $userNames
	 * @return UserSelectQueryBuilder
	 */
	public function userNames( $userNames ): self {
		Assert::parameterType( 'string|array', $userNames, '$userIds' );
		$userNames = array_map( function ( $name ) {
			return $this->actorStore->normalizeUserName( (string)$name );
		}, (array)$userNames );
		$this->conds( [ 'actor_name' => $userNames ] );
		return $this;
	}

	/**
	 * Find users with names starting from the provided prefix.
	 *
	 * @note this could produce a huge number of results, like User00000 ... User99999,
	 * so you must set a limit when using this condition.
	 *
	 * @param string $prefix
	 * @return UserSelectQueryBuilder
	 */
	public function userNamePrefix( string $prefix ): self {
		if ( !isset( $this->options['LIMIT'] ) ) {
			throw new PreconditionException( 'Must set a limit when using a user name prefix' );
		}
		$like = $this->db->buildLike( $prefix, $this->db->anyString() );
		$this->conds( "actor_name{$like}" );
		return $this;
	}

	/**
	 * Order results by name in $direction
	 *
	 * @param string $dir one of self::SORT_ACS or self::SORT_DESC
	 * @return UserSelectQueryBuilder
	 */
	public function orderByName( string $dir = self::SORT_ASC ): self {
		$this->orderBy( 'actor_name', $dir );
		return $this;
	}

	/**
	 * Order results by user id.
	 *
	 * @param string $dir one of self::SORT_ACS or self::SORT_DESC
	 * @return UserSelectQueryBuilder
	 */
	public function orderByUserId( string $dir = self::SORT_ASC ): self {
		$this->orderBy( 'actor_user', $dir );
		return $this;
	}

	/**
	 * Only return registered users.
	 *
	 * @return UserSelectQueryBuilder
	 */
	public function registered(): self {
		$this->conds( [ 'actor_user != 0' ] );
		return $this;
	}

	/**
	 * Only return anonymous users.
	 *
	 * @return UserSelectQueryBuilder
	 */
	public function anon(): self {
		$this->conds( [ 'actor_user' => null ] );
		return $this;
	}

	/**
	 * Fetch a single UserIdentity that matches specified criteria.
	 *
	 * @return UserIdentity|null
	 */
	public function fetchUserIdentity(): ?UserIdentity {
		$this->fields( [ 'actor_id', 'actor_name', 'actor_user' ] );
		$row = $this->fetchRow();
		if ( !$row ) {
			return null;
		}
		return $this->actorStore->newActorFromRow( $row );
	}

	/**
	 * Fetch UserIdentities for the specified query.
	 *
	 * @return Iterator<UserIdentity>
	 */
	public function fetchUserIdentities(): Iterator {
		$this->fields( [ 'actor_id', 'actor_name', 'actor_user' ] );
		return call_user_func( function () {
			$result = $this->fetchResultSet();
			foreach ( $result as $row ) {
				yield $this->actorStore->newActorFromRow( $row );
			}
			$result->free();
		} );
	}

	/**
	 * Returns an array of user names matching the query.
	 *
	 * @return string[]
	 */
	public function fetchUserNames(): array {
		$this->field( 'actor_name' );
		return $this->fetchFieldValues();
	}
}
