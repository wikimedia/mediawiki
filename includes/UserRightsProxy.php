<?php
/**
 * Representation of an user on a other locally-hosted wiki.
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

/**
 * Cut-down copy of User interface for local-interwiki-database
 * user rights manipulation.
 */
class UserRightsProxy {

	/**
	 * Constructor.
	 *
	 * @see newFromId()
	 * @see newFromName()
	 * @param DatabaseBase $db Db connection
	 * @param string $database Database name
	 * @param string $name User name
	 * @param int $id User ID
	 */
	private function __construct( $db, $database, $name, $id ) {
		$this->db = $db;
		$this->database = $database;
		$this->name = $name;
		$this->id = intval( $id );
		$this->newOptions = array();
	}

	/**
	 * Accessor for $this->database
	 *
	 * @return string Database name
	 */
	public function getDBName() {
		return $this->database;
	}

	/**
	 * Confirm the selected database name is a valid local interwiki database name.
	 *
	 * @param string $database Database name
	 * @return bool
	 */
	public static function validDatabase( $database ) {
		global $wgLocalDatabases;
		return in_array( $database, $wgLocalDatabases );
	}

	/**
	 * Same as User::whoIs()
	 *
	 * @param string $database Database name
	 * @param int $id User ID
	 * @param bool $ignoreInvalidDB If true, don't check if $database is in $wgLocalDatabases
	 * @return string User name or false if the user doesn't exist
	 */
	public static function whoIs( $database, $id, $ignoreInvalidDB = false ) {
		$user = self::newFromId( $database, $id, $ignoreInvalidDB );
		if ( $user ) {
			return $user->name;
		} else {
			return false;
		}
	}

	/**
	 * Factory function; get a remote user entry by ID number.
	 *
	 * @param string $database Database name
	 * @param int $id User ID
	 * @param bool $ignoreInvalidDB If true, don't check if $database is in $wgLocalDatabases
	 * @return UserRightsProxy|null If doesn't exist
	 */
	public static function newFromId( $database, $id, $ignoreInvalidDB = false ) {
		return self::newFromLookup( $database, 'user_id', intval( $id ), $ignoreInvalidDB );
	}

	/**
	 * Factory function; get a remote user entry by name.
	 *
	 * @param string $database Database name
	 * @param string $name User name
	 * @param bool $ignoreInvalidDB If true, don't check if $database is in $wgLocalDatabases
	 * @return UserRightsProxy|null If doesn't exist
	 */
	public static function newFromName( $database, $name, $ignoreInvalidDB = false ) {
		return self::newFromLookup( $database, 'user_name', $name, $ignoreInvalidDB );
	}

	/**
	 * @param string $database
	 * @param string $field
	 * @param string $value
	 * @param bool $ignoreInvalidDB
	 * @return null|UserRightsProxy
	 */
	private static function newFromLookup( $database, $field, $value, $ignoreInvalidDB = false ) {
		global $wgSharedDB, $wgSharedTables;
		// If the user table is shared, perform the user query on it,
		// but don't pass it to the UserRightsProxy,
		// as user rights are normally not shared.
		if ( $wgSharedDB && in_array( 'user', $wgSharedTables ) ) {
			$userdb = self::getDB( $wgSharedDB, $ignoreInvalidDB );
		} else {
			$userdb = self::getDB( $database, $ignoreInvalidDB );
		}

		$db = self::getDB( $database, $ignoreInvalidDB );

		if ( $db && $userdb ) {
			$row = $userdb->selectRow( 'user',
				array( 'user_id', 'user_name' ),
				array( $field => $value ),
				__METHOD__ );

			if ( $row !== false ) {
				return new UserRightsProxy( $db, $database,
					$row->user_name,
					intval( $row->user_id ) );
			}
		}
		return null;
	}

	/**
	 * Open a database connection to work on for the requested user.
	 * This may be a new connection to another database for remote users.
	 *
	 * @param string $database
	 * @param bool $ignoreInvalidDB If true, don't check if $database is in $wgLocalDatabases
	 * @return DatabaseBase|null If invalid selection
	 */
	public static function getDB( $database, $ignoreInvalidDB = false ) {
		global $wgDBname;
		if ( $ignoreInvalidDB || self::validDatabase( $database ) ) {
			if ( $database == $wgDBname ) {
				// Hmm... this shouldn't happen though. :)
				return wfGetDB( DB_MASTER );
			} else {
				return wfGetDB( DB_MASTER, array(), $database );
			}
		}
		return null;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return bool
	 */
	public function isAnon() {
		return $this->getId() == 0;
	}

	/**
	 * Same as User::getName()
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name . '@' . $this->database;
	}

	/**
	 * Same as User::getUserPage()
	 *
	 * @return Title
	 */
	public function getUserPage() {
		return Title::makeTitle( NS_USER, $this->getName() );
	}

	/**
	 * Replaces User::getUserGroups()
	 * @return array
	 */
	function getGroups() {
		$res = $this->db->select( 'user_groups',
			array( 'ug_group' ),
			array( 'ug_user' => $this->id ),
			__METHOD__ );
		$groups = array();
		foreach ( $res as $row ) {
			$groups[] = $row->ug_group;
		}
		return $groups;
	}

	/**
	 * Replaces User::addUserGroup()
	 * @param string $group
	 *
	 * @return bool
	 */
	function addGroup( $group ) {
		$this->db->insert( 'user_groups',
			array(
				'ug_user' => $this->id,
				'ug_group' => $group,
			),
			__METHOD__,
			array( 'IGNORE' ) );

		return true;
	}

	/**
	 * Replaces User::removeUserGroup()
	 * @param string $group
	 *
	 * @return bool
	 */
	function removeGroup( $group ) {
		$this->db->delete( 'user_groups',
			array(
				'ug_user' => $this->id,
				'ug_group' => $group,
			),
			__METHOD__ );

		return true;
	}

	/**
	 * Replaces User::setOption()
	 * @param string $option
	 * @param mixed $value
	 */
	public function setOption( $option, $value ) {
		$this->newOptions[$option] = $value;
	}

	public function saveSettings() {
		$rows = array();
		foreach ( $this->newOptions as $option => $value ) {
			$rows[] = array(
				'up_user' => $this->id,
				'up_property' => $option,
				'up_value' => $value,
			);
		}
		$this->db->replace( 'user_properties',
			array( array( 'up_user', 'up_property' ) ),
			$rows, __METHOD__
		);
		$this->invalidateCache();
	}

	/**
	 * Replaces User::touchUser()
	 */
	function invalidateCache() {
		$this->db->update( 'user',
			array( 'user_touched' => $this->db->timestamp() ),
			array( 'user_id' => $this->id ),
			__METHOD__ );

		global $wgMemc;
		$key = wfForeignMemcKey( $this->database, false, 'user', 'id', $this->id );
		$wgMemc->delete( $key );
	}
}
