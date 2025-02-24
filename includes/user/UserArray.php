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

use Countable;
use Iterator;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Class to walk into a list of User objects.
 */
abstract class UserArray implements Iterator, Countable {
	/**
	 * @note Try to avoid in new code, in case getting UserIdentity batch is enough,
	 * use {@link \MediaWiki\User\UserIdentityLookup::newSelectQueryBuilder()}.
	 * In case you need full User objects, you can keep using this method, but it's
	 * moving towards deprecation.
	 *
	 * @param IResultWrapper $res
	 * @return self
	 */
	public static function newFromResult( $res ): self {
		$userArray = null;
		$hookRunner = new HookRunner( MediaWikiServices::getInstance()->getHookContainer() );
		if ( !$hookRunner->onUserArrayFromResult( $userArray, $res ) ) {
			return new UserArrayFromResult( new FakeResultWrapper( [] ) );
		}
		return $userArray ?? new UserArrayFromResult( $res );
	}

	/**
	 * @note Try to avoid in new code, in case getting UserIdentity batch is enough,
	 * use {@link \MediaWiki\User\UserIdentityLookup::newSelectQueryBuilder()}.
	 * In case you need full User objects, you can keep using this method, but it's
	 * moving towards deprecation.
	 *
	 * @param int[] $ids
	 * @return self
	 */
	public static function newFromIDs( array $ids ): self {
		$ids = array_map( 'intval', $ids ); // paranoia
		if ( !$ids ) {
			// Database::select() doesn't like empty arrays
			return new UserArrayFromResult( new FakeResultWrapper( [] ) );
		}
		$dbr = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
		$res = User::newQueryBuilder( $dbr )
			->where( [ 'user_id' => array_unique( $ids ) ] )
			->caller( __METHOD__ )
			->fetchResultSet();
		return self::newFromResult( $res );
	}

	/**
	 * @note Try to avoid in new code, in case getting UserIdentity batch is enough,
	 * use {@link \MediaWiki\User\UserIdentityLookup::newSelectQueryBuilder()}.
	 * In case you need full User objects, you can keep using this method, but it's
	 * moving towards deprecation.
	 *
	 * @since 1.25
	 * @param string[] $names
	 * @return self
	 */
	public static function newFromNames( array $names ): self {
		$names = array_map( 'strval', $names ); // paranoia
		if ( !$names ) {
			// Database::select() doesn't like empty arrays
			return new UserArrayFromResult( new FakeResultWrapper( [] ) );
		}
		$dbr = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
		$res = User::newQueryBuilder( $dbr )
			->where( [ 'user_name' => array_unique( $names ) ] )
			->caller( __METHOD__ )
			->fetchResultSet();
		return self::newFromResult( $res );
	}

	abstract public function count(): int;

	abstract public function current(): User;

	abstract public function key(): int;
}

/** @deprecated class alias since 1.41 */
class_alias( UserArray::class, 'UserArray' );
