<?php

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
	 * @param $db DatabaseBase: db connection
	 * @param $database String: database name
	 * @param $name String: user name
	 * @param $id Integer: user ID
	 */
	private function __construct( $db, $database, $name, $id ) {
		$this->db = $db;
		$this->database = $database;
		$this->name = $name;
		$this->id = intval( $id );
	}

	/**
	 * Accessor for $this->database
	 *
	 * @return String: database name
	 */
	public function getDBName() {
		return $this->database;
	}

	/**
	 * Confirm the selected database name is a valid local interwiki database name.
	 *
	 * @param $database String: database name
	 * @return Boolean
	 */
	public static function validDatabase( $database ) {
		global $wgLocalDatabases;
		return in_array( $database, $wgLocalDatabases );
	}

	/**
	 * Same as User::whoIs()
	 *
	 * @param $database String: database name
	 * @param $id Integer: user ID
	 * @return String: user name or false if the user doesn't exist
	 */
	public static function whoIs( $database, $id ) {
		$user = self::newFromId( $database, $id );
		if( $user ) {
			return $user->name;
		} else {
			return false;
		}
	}

	/**
	 * Factory function; get a remote user entry by ID number.
	 *
	 * @param $database String: database name
	 * @param $id Integer: user ID
	 * @return UserRightsProxy or null if doesn't exist
	 */
	public static function newFromId( $database, $id ) {
		return self::newFromLookup( $database, 'user_id', intval( $id ) );
	}

	/**
	 * Factory function; get a remote user entry by name.
	 *
	 * @param $database String: database name
	 * @param $name String: user name
	 * @return UserRightsProxy or null if doesn't exist
	 */
	public static function newFromName( $database, $name ) {
		return self::newFromLookup( $database, 'user_name', $name );
	}

	private static function newFromLookup( $database, $field, $value ) {
		$db = self::getDB( $database );
		if( $db ) {
			$row = $db->selectRow( 'user',
				array( 'user_id', 'user_name' ),
				array( $field => $value ),
				__METHOD__ );
			if( $row !== false ) {
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
	 * @param $database String
	 * @return DatabaseBase or null if invalid selection
	 */
	public static function getDB( $database ) {
		global $wgDBname;
		if( self::validDatabase( $database ) ) {
			if( $database == $wgDBname ) {
				// Hmm... this shouldn't happen though. :)
				return wfGetDB( DB_MASTER );
			} else {
				return wfGetDB( DB_MASTER, array(), $database );
			}
		}
		return null;
	}

	public function getId() {
		return $this->id;
	}

	public function isAnon() {
		return $this->getId() == 0;
	}

	/**
	 * Same as User::getName()
	 *
	 * @return String
	 */
	public function getName() {
		return $this->name . '@' . $this->database;
	}

	/**
	 * Same as User::getUserPage()
	 *
	 * @return Title object
	 */
	public function getUserPage() {
		return Title::makeTitle( NS_USER, $this->getName() );
	}

	/**
	 * Replaces User::getUserGroups()
	 */
	function getGroups() {
		$res = $this->db->select( 'user_groups',
			array( 'ug_group' ),
			array( 'ug_user' => $this->id ),
			__METHOD__ );
		$groups = array();
		while( $row = $this->db->fetchObject( $res ) ) {
			$groups[] = $row->ug_group;
		}
		return $groups;
	}

	/**
	 * Replaces User::addUserGroup()
	 */
	function addGroup( $group ) {
		$this->db->insert( 'user_groups',
			array(
				'ug_user' => $this->id,
				'ug_group' => $group,
			),
			__METHOD__,
			array( 'IGNORE' ) );
	}

	/**
	 * Replaces User::removeUserGroup()
	 */
	function removeGroup( $group ) {
		$this->db->delete( 'user_groups',
			array(
				'ug_user' => $this->id,
				'ug_group' => $group,
			),
			__METHOD__ );
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
