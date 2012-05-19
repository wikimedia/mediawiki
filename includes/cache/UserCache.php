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
	 * Get a user's current user name based on their user ID.
	 * @param integer $userId User ID
	 * @param string $defaultName Name to use if the user does not exist
	 * @return string|null May return null if $defaultName was not given
	 */
	public function getName( $userId, $defaultName = null ) {
		$userId = (int)$userId;
		if ( !$userId ) {
			return $defaultName; // IP user or user from imported edit
		}
		if ( !isset( $this->cache[$userId]['name'] ) ) {
			$this->doQuery( array( $userId ) ); // cache miss
		}
		return isset( $this->cache[$userId]['name'] )
			? $this->cache[$userId]['name']
			: $defaultName; // user does not exist
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
		foreach ( $userIds as $userId ) {
			$userId = (int)$userId;
			// Skip anons and users already in cache
			if ( $userId > 0 && !isset( $this->cache[$userId] ) ) {
				$usersToCheck[] = $userId;
			}
		}

		$dbr = wfGetDB( DB_SLAVE );
		$table = array( 'user' );
		$fields = array( 'user_name', 'user_id' );
		$conds = array( 'user_id' => $usersToCheck );

		$comment = __METHOD__;
		if ( strval( $caller ) !== '' ) {
			$comment .= "/$caller";
		}
		$res = $dbr->select( $table, $fields, $conds, $comment );

		$lb = new LinkBatch();
		foreach ( $res as $row ) {
			$userId = (int)$row->user_id;
			$this->cache[$userId] = array();
			$this->cache[$userId]['name'] = $row->user_name;
			if ( in_array( 'userpage', $options ) ) {
				$lb->add( NS_USER, str_replace( ' ', '_', $row->user_name ) );
			}
			if ( in_array( 'usertalk', $options ) ) {
				$lb->add( NS_USER_TALK, str_replace( ' ', '_', $row->user_name ) );
			}
		}
		$lb->execute();

		wfProfileOut( __METHOD__ );
	}
}
