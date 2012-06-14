<?php
/**
 * Caches user genders when needed to use correct namespace aliases.
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
 * @author Niklas Laxström
 * @ingroup Cache
 */

/**
 * Caches user genders when needed to use correct namespace aliases.
 *
 * @since 1.18
 */
class GenderCache {
	protected $cache = array();
	protected $default;
	protected $misses = 0;
	protected $missLimit = 1000;

	/**
	 * @return GenderCache
	 */
	public static function singleton() {
		static $that = null;
		if ( $that === null ) {
			$that = new self();
		}
		return $that;
	}

	protected function __construct() {}

	/**
	 * Returns the default gender option in this wiki.
	 * @return String
	 */
	protected function getDefault() {
		if ( $this->default === null ) {
			$this->default = User::getDefaultOption( 'gender' );
		}
		return $this->default;
	}

	/**
	 * Returns the gender for given username.
	 * @param $username String or User: username
	 * @param $caller String: the calling method
	 * @return String
	 */
	public function getGenderOf( $username, $caller = '' ) {
		global $wgUser;

		if( $username instanceof User ) {
			$username = $username->getName();
		}

		$username = self::normalizeUsername( $username );
		if ( !isset( $this->cache[$username] ) ) {

			if ( $this->misses >= $this->missLimit && $wgUser->getName() !== $username ) {
				if( $this->misses === $this->missLimit ) {
					$this->misses++;
					wfDebug( __METHOD__ . ": too many misses, returning default onwards\n" );
				}
				return $this->getDefault();

			} else {
				$this->misses++;
				$this->doQuery( $username, $caller );
			}

		}

		/* Undefined if there is a valid username which for some reason doesn't
		 * exist in the database.
		 */
		return isset( $this->cache[$username] ) ? $this->cache[$username] : $this->getDefault();
	}

	/**
	 * Wrapper for doQuery that processes raw LinkBatch data.
	 *
	 * @param $data
	 * @param $caller
	 */
	public function doLinkBatch( $data, $caller = '' ) {
		$users = array();
		foreach ( $data as $ns => $pagenames ) {
			if ( !MWNamespace::hasGenderDistinction( $ns ) ) continue;
			foreach ( array_keys( $pagenames ) as $username ) {
				$users[$username] = true;
			}
		}

		$this->doQuery( array_keys( $users ), $caller );
	}

	/**
	 * Wrapper for doQuery that processes a title or string array.
	 *
	 * @since 1.20
	 * @param $titles List: array of Title objects or strings
	 * @param $caller String: the calling method
	 */
	public function doTitlesArray( $titles, $caller = '' ) {
		$users = array();
		foreach ( $titles as $title ) {
			$titleObj = is_string( $title ) ? Title::newFromText( $title ) : $title;
			if ( !$titleObj ) {
				continue;
			}
			if ( !MWNamespace::hasGenderDistinction( $titleObj->getNamespace() ) ) {
				continue;
			}
			$users[] = $titleObj->getText();
		}

		$this->doQuery( $users, $caller );
	}

	/**
	 * Preloads genders for given list of users.
	 * @param $users List|String: usernames
	 * @param $caller String: the calling method
	 */
	public function doQuery( $users, $caller = '' ) {
		$default = $this->getDefault();

		$usersToCheck = array();
		foreach ( (array) $users as $value ) {
			$name = self::normalizeUsername( $value );
			// Skip users whose gender setting we already know
			if ( !isset( $this->cache[$name] ) ) {
				// For existing users, this value will be overwritten by the correct value
				$this->cache[$name] = $default;
				// query only for valid names, which can be in the database
				if( User::isValidUserName( $name ) ) {
					$usersToCheck[] = $name;
				}
			}
		}

		if ( count( $usersToCheck ) === 0 ) {
			return;
		}

		$dbr = wfGetDB( DB_SLAVE );
		$table = array( 'user', 'user_properties' );
		$fields = array( 'user_name', 'up_value' );
		$conds = array( 'user_name' => $usersToCheck );
		$joins = array( 'user_properties' =>
			array( 'LEFT JOIN', array( 'user_id = up_user', 'up_property' => 'gender' ) ) );

		$comment = __METHOD__;
		if ( strval( $caller ) !== '' ) {
			$comment .= "/$caller";
		}
		$res = $dbr->select( $table, $fields, $conds, $comment, array(), $joins );

		foreach ( $res as $row ) {
			$this->cache[$row->user_name] = $row->up_value ? $row->up_value : $default;
		}
	}

	private static function normalizeUsername( $username ) {
		// Strip off subpages
		$indexSlash = strpos( $username, '/' );
		if ( $indexSlash !== false ) {
			$username = substr( $username, 0, $indexSlash );
		}
		// normalize underscore/spaces
		return strtr( $username, '_', ' ' );
	}
}
