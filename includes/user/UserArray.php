<?php
/**
 * Class to walk into a list of User objects.
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

use Wikimedia\Rdbms\IResultWrapper;

abstract class UserArray implements Iterator {
	/**
	 * @param IResultWrapper $res
	 * @return UserArrayFromResult
	 */
	static function newFromResult( $res ) {
		$userArray = null;
		if ( !Hooks::run( 'UserArrayFromResult', [ &$userArray, $res ] ) ) {
			return null;
		}
		return $userArray ?? new UserArrayFromResult( $res );
	}

	/**
	 * @param array $ids
	 * @return UserArrayFromResult|ArrayIterator
	 */
	static function newFromIDs( $ids ) {
		$ids = array_map( 'intval', (array)$ids ); // paranoia
		if ( !$ids ) {
			// Database::select() doesn't like empty arrays
			return new ArrayIterator( [] );
		}
		$dbr = wfGetDB( DB_REPLICA );
		$userQuery = User::getQueryInfo();
		$res = $dbr->select(
			$userQuery['tables'],
			$userQuery['fields'],
			[ 'user_id' => array_unique( $ids ) ],
			__METHOD__,
			[],
			$userQuery['joins']
		);
		return self::newFromResult( $res );
	}

	/**
	 * @since 1.25
	 * @param array $names
	 * @return UserArrayFromResult|ArrayIterator
	 */
	static function newFromNames( $names ) {
		$names = array_map( 'strval', (array)$names ); // paranoia
		if ( !$names ) {
			// Database::select() doesn't like empty arrays
			return new ArrayIterator( [] );
		}
		$dbr = wfGetDB( DB_REPLICA );
		$userQuery = User::getQueryInfo();
		$res = $dbr->select(
			$userQuery['tables'],
			$userQuery['fields'],
			[ 'user_name' => array_unique( $names ) ],
			__METHOD__,
			[],
			$userQuery['joins']
		);
		return self::newFromResult( $res );
	}
}
