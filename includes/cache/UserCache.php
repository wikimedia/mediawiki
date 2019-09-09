<?php
/**
 * Caches current user names and other info based on user IDs.
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
 * @ingroup Cache
 */

/**
 * @since 1.20
 */
class UserCache {
	protected $cache = []; // (uid => property => value)
	protected $typesCached = []; // (uid => cache type => 1)

	/**
	 * @return UserCache
	 */
	public static function singleton() {
		static $instance = null;
		if ( $instance === null ) {
			$instance = new self();
		}

		return $instance;
	}

	protected function __construct() {
	}

	/**
	 * Get a property of a user based on their user ID
	 *
	 * @param int $userId User ID
	 * @param string $prop User property
	 * @return mixed|bool The property or false if the user does not exist
	 */
	public function getProp( $userId, $prop ) {
		if ( !isset( $this->cache[$userId][$prop] ) ) {
			wfDebug( __METHOD__ . ": querying DB for prop '$prop' for user ID '$userId'.\n" );
			$this->doQuery( [ $userId ] ); // cache miss
		}

		return $this->cache[$userId][$prop] ?? false; // user does not exist?
	}

	/**
	 * Get the name of a user or return $ip if the user ID is 0
	 *
	 * @param int $userId
	 * @param string $ip
	 * @return string
	 * @since 1.22
	 */
	public function getUserName( $userId, $ip ) {
		return $userId > 0 ? $this->getProp( $userId, 'name' ) : $ip;
	}

	/**
	 * Preloads user names for given list of users.
	 * @param array $userIds List of user IDs
	 * @param array $options Option flags; include 'userpage' and 'usertalk'
	 * @param string $caller The calling method
	 */
	public function doQuery( array $userIds, $options = [], $caller = '' ) {
		$usersToCheck = [];
		$usersToQuery = [];

		$userIds = array_unique( $userIds );

		foreach ( $userIds as $userId ) {
			$userId = (int)$userId;
			if ( $userId <= 0 ) {
				continue; // skip anons
			}
			if ( isset( $this->cache[$userId]['name'] ) ) {
				$usersToCheck[$userId] = $this->cache[$userId]['name']; // already have name
			} else {
				$usersToQuery[] = $userId; // we need to get the name
			}
		}

		// Lookup basic info for users not yet loaded...
		if ( count( $usersToQuery ) ) {
			$dbr = wfGetDB( DB_REPLICA );
			$tables = [ 'user', 'actor' ];
			$conds = [ 'user_id' => $usersToQuery ];
			$fields = [ 'user_name', 'user_real_name', 'user_registration', 'user_id', 'actor_id' ];
			$joinConds = [
				'actor' => [ 'JOIN', 'actor_user = user_id' ],
			];

			$comment = __METHOD__;
			if ( strval( $caller ) !== '' ) {
				$comment .= "/$caller";
			}

			$res = $dbr->select( $tables, $fields, $conds, $comment, [], $joinConds );
			foreach ( $res as $row ) { // load each user into cache
				$userId = (int)$row->user_id;
				$this->cache[$userId]['name'] = $row->user_name;
				$this->cache[$userId]['real_name'] = $row->user_real_name;
				$this->cache[$userId]['registration'] = $row->user_registration;
				$this->cache[$userId]['actor'] = $row->actor_id;
				$usersToCheck[$userId] = $row->user_name;
			}
		}

		$lb = new LinkBatch();
		foreach ( $usersToCheck as $userId => $name ) {
			if ( $this->queryNeeded( $userId, 'userpage', $options ) ) {
				$lb->add( NS_USER, $name );
				$this->typesCached[$userId]['userpage'] = 1;
			}
			if ( $this->queryNeeded( $userId, 'usertalk', $options ) ) {
				$lb->add( NS_USER_TALK, $name );
				$this->typesCached[$userId]['usertalk'] = 1;
			}
		}
		$lb->execute();
	}

	/**
	 * Check if a cache type is in $options and was not loaded for this user
	 *
	 * @param int $uid User ID
	 * @param string $type Cache type
	 * @param array $options Requested cache types
	 * @return bool
	 */
	protected function queryNeeded( $uid, $type, array $options ) {
		return ( in_array( $type, $options ) && !isset( $this->typesCached[$uid][$type] ) );
	}
}
