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
	protected $cache = array(); // (uid => property => value)
	protected $typesCached = array(); // (uid => cache type => 1)

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

	protected function __construct() {}

	/**
	 * Get a property of a user based on their user ID
	 *
	 * @param $userId integer User ID
	 * @param $prop string User property
	 * @return mixed The property or false if the user does not exist
	 */
	public function getProp( $userId, $prop ) {
		if ( !isset( $this->cache[$userId][$prop] ) ) {
			wfDebug( __METHOD__ . ": querying DB for prop '$prop' for user ID '$userId'.\n" );
			$this->doQuery( array( $userId ) ); // cache miss
		}
		return isset( $this->cache[$userId][$prop] )
			? $this->cache[$userId][$prop]
			: false; // user does not exist?
	}

	/**
	 * Preloads user names for given list of users.
	 * @param $userIds Array List of user IDs
	 * @param $options Array Option flags; include 'userpage' and 'usertalk'
	 * @param $caller String: the calling method
	 */
	public function doQuery( array $userIds, $options = array(), $caller = '' ) {
		wfProfileIn( __METHOD__ );

		$usersToCheck = array();
		$usersToQuery = array();

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
			$dbr = wfGetDB( DB_SLAVE );
			$table = array( 'user' );
			$conds = array( 'user_id' => $usersToQuery );
			$fields = array( 'user_name', 'user_real_name', 'user_registration', 'user_id' );

			$comment = __METHOD__;
			if ( strval( $caller ) !== '' ) {
				$comment .= "/$caller";
			}

			$res = $dbr->select( $table, $fields, $conds, $comment );
			foreach ( $res as $row ) { // load each user into cache
				$userId = (int)$row->user_id;
				$this->cache[$userId]['name'] = $row->user_name;
				$this->cache[$userId]['real_name'] = $row->user_real_name;
				$this->cache[$userId]['registration'] = $row->user_registration;
				$usersToCheck[$userId] = $row->user_name;
			}
		}

		$lb = new LinkBatch();
		foreach ( $usersToCheck as $userId => $name ) {
			if ( $this->queryNeeded( $userId, 'userpage', $options ) ) {
				$lb->add( NS_USER, str_replace( ' ', '_', $row->user_name ) );
				$this->typesCached[$userId]['userpage'] = 1;
			}
			if ( $this->queryNeeded( $userId, 'usertalk', $options ) ) {
				$lb->add( NS_USER_TALK, str_replace( ' ', '_', $row->user_name ) );
				$this->typesCached[$userId]['usertalk'] = 1;
			}
		}
		$lb->execute();

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Check if a cache type is in $options and was not loaded for this user
	 *
	 * @param $uid integer user ID
	 * @param $type string Cache type
	 * @param $options Array Requested cache types
	 * @return bool
	 */
	protected function queryNeeded( $uid, $type, array $options ) {
		return ( in_array( $type, $options ) && !isset( $this->typesCached[$uid][$type] ) );
	}
}
