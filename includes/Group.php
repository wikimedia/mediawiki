<?php
/**
 * @package MediaWiki
 */

/**
 * Class to manage a group
 * @package MediaWiki
 */
class Group {
	/**#@+
	 * @access private
	 */
	/** string $name Group name */
	var $name;
	/** integer $id Group id */
	var $id;
	/** string $description Description of the group */
	var $description;
	/** boolean $dataLoaded Whereas we grabbed datas from the database */
	var $dataLoaded;
	/** string $rights Contain rights values : "foo,bar,bla" */
	var $rights;
	/**#@-*/
	
	/** Constructor */
	function Group() {
		$this->clear();
	}

	/** Clear variables */
	function clear() {
		$this->name = '';
		$this->id = 0;
		$this->description = '';
		$this->dataLoaded = false;
		$this->rights = false;
	}

	/** Load group datas from database */
	function loadFromDatabase() {
		$fname = 'Group::loadFromDatabase';

		// See if it's already loaded
		if ( $this->dataLoaded || Group::getStaticGroups() ) {
			return;
		}

		// be sure it's an integer
		$this->id = IntVal($this->id);
		
		if($this->id) {
			// By ID
			$dbr =& wfGetDB( DB_SLAVE );
			$r = $dbr->selectRow('group',
				array('group_id', 'group_name', 'group_description', 'group_rights'),
				array( 'group_id' => $this->id ),
				$fname );
			if ( $r ) {
				$this->loadFromRow( $r );
			} else {
				$this->id = 0;
				$this->dataLoaded = true;
			}
		} else {
			// By name
			$dbr =& wfGetDB( DB_SLAVE );
			$r = $dbr->selectRow('group',
				array('group_id', 'group_name', 'group_description', 'group_rights'),
				array( 'group_name' => $this->name ),
				$fname );
			if ( $r ) {
				$this->loadFromRow( $r );
			} else {
				$this->id = 0;
				$this->dataLoaded = true;
			}
		}
	}

	/** Initialise from a result row */
	function loadFromRow( &$row ) {
		$this->id = $row->group_id;
		$this->name = $row->group_name;
		$this->description = $row->group_description;
		$this->rights = $row->group_rights;
		$this->dataLoaded = true;
	}		
	
	/** Initialise a new row in the database */
	function addToDatabase() {
		if ( Group::getStaticGroups() ) {
			wfDebugDieBacktrace( "Can't modify groups in static mode" );
		}

		$fname = 'Group::addToDatabase';
		$dbw =& wfGetDB( DB_MASTER );
		$dbw->insert( 'group',
			array(
				'group_name' => $this->name,
				'group_description' => $this->description,
				'group_rights' => $this->rights
			), $fname
		);
		$this->id = $dbw->insertId();
	}

	/** Save the group datas into database */
	function save() {
		if ( Group::getStaticGroups() ) {
			wfDebugDieBacktrace( "Can't modify groups in static mode" );
		}
		if($this->id == 0) { return; }
		
		$fname = 'Group::save';
		$dbw =& wfGetDB( DB_MASTER );
		
		$dbw->update( 'group',
			array( /* SET */
				'group_name' => $this->name,
				'group_description' => $this->description,
				'group_rights' => $this->rights
			), array( /* WHERE */
				'group_id' => $this->id
			), $fname
		);		
	}

	/** Delete a group */
	function delete() {
		if ( Group::getStaticGroups() ) {
			wfDebugDieBacktrace( "Can't modify groups in static mode" );
		}
		if($this->id == 0) { return; }
		
		$fname = 'Group::delete';
		$dbw =& wfGetDB( DB_MASTER );

		// First remove all users from the group
		$dbw->delete( 'user_group', array( 'ug_group' => $this->id ), $fname );

		// Now delete the group
		$dbw->delete( 'group', array( 'group_id' => $this->id ), $fname );
	}
	
// Factories
	/**
	 * Uses Memcached if available.
	 * @param integer $id Group database id
	 */
	function newFromId($id) {
		global $wgMemc, $wgDBname;
		$fname = 'Group::newFromId';
		
		$staticGroups =& Group::getStaticGroups();
		if ( $staticGroups ) {
			if ( array_key_exists( $id, $staticGroups ) ) {
				return $staticGroups[$id];
			} else {
				return null;
			}
		}

		$key = "$wgDBname:groups:id:$id";
		if( $group = $wgMemc->get( $key ) ) {
			wfDebug( "$fname loaded group $id from cache\n" );
			return $group;
		}
		
		$group = new Group();
		$group->id = $id;
		$group->loadFromDatabase();

		if ( !$group->id ) {
			wfDebug( "$fname can't find group $id\n" );
			return null;
		} else {
			wfDebug( "$fname caching group $id (name {$group->name})\n" );
			$wgMemc->add( $key, $group, 3600 );
			return $group;
		}
	}


	/** @param string $name Group database name */
	function newFromName($name) {
		$fname = 'Group::newFromName';
		
		$staticGroups =& Group::getStaticGroups();
		if ( $staticGroups ) {
			$id = Group::idFromName( $name );
			if ( array_key_exists( $id, $staticGroups ) ) {
				return $staticGroups[$id];
			} else {
				return null;
			}
		}
		
		$g = new Group();
		$g->name = $name;
		$g->loadFromDatabase();

		if( $g->getId() != 0 ) {
			return $g;
		} else { 
		 	return null;
		}
	}

	/**
	 * Get an array of Group objects, one for each valid group
	 * 
	 * @static
	 */
	function &getAllGroups() {
		$staticGroups =& Group::getStaticGroups();
		if ( $staticGroups ) {
			return $staticGroups;
		}

		$fname = 'Group::getAllGroups';
		wfProfileIn( $fname );

		$dbr =& wfGetDB( DB_SLAVE );
		$groupTable = $dbr->tableName( 'group' );
		$sql = "SELECT group_id, group_name, group_description, group_rights FROM $groupTable";
		$res = $dbr->query($sql, $fname);

		$groups = array();

		while($row = $dbr->fetchObject( $res ) ) {
			$group = new Group;
			$group->loadFromRow( $row );
			$groups[$row->group_id] = $group;
		}

		wfProfileOut( $fname );
		return $groups;
	}

	/** 
	 * Get static groups, if they have been defined in LocalSettings.php
	 * 
	 * @static
	 */
	function &getStaticGroups() {
		global $wgStaticGroups;
		if ( $wgStaticGroups === false ) {
			return $wgStaticGroups;
		}

		if ( !is_array( $wgStaticGroups ) ) {
			$wgStaticGroups = unserialize( $wgStaticGroups );
		}

		return $wgStaticGroups;
	}


// Converters
	/**
	 * @param integer $id Group database id
	 * @return string Group database name
	 */
	function nameFromId($id) {
		$group = Group::newFromId( $id );
		if ( is_null( $group ) ) {
			return '';
		} else {
			return $group->getName();
		}
	}

	/**
	 * @param string $name Group database name 
	 * @return integer Group database id
	 */
	function idFromName($name) {
		$fname = 'Group::idFromName';

		$staticGroups =& Group::getStaticGroups();
		if ( $staticGroups ) {
			foreach( $staticGroups as $id => $group ) {
				if ( $group->getName() === $name ) {
					return $group->getID();
				}
			}
			return 0;
		}


		$dbr =& wfGetDB( DB_SLAVE );
		$r = $dbr->selectRow( 'group', array( 'group_id' ), array( 'group_name' => $name ), $fname );

		if($r === false) {
			return 0;
		} else {
			return $r->group_id;
		}
	}

// Accessors for private variables
	function getName() {
		$this->loadFromDatabase();
		return $this->name;
	}

	function getExpandedName() { 
		$this->loadFromDatabase();
		return $this->getMessage( $this->name );
	}
	
	function getNameForContent() {
		$this->loadFromDatabase();
		return $this->getMessageForContent( $this->name );
	}

	function setName($name) {
		$this->loadFromDatabase();
		$this->name = $name;
	}
	
	function getId() { return $this->id; }
	function setId($id) {
		$this->id = IntVal($id);
		$this->dataLoaded = false;
	}
	
	function getDescription() { 
		return $this->description;
	}

	function getExpandedDescription() {
		return $this->getMessage( $this->description );
	}

	function setDescription($desc) {
		$this->loadFromDatabase();
		$this->description = $desc;
	}

	function getRights() { return $this->rights; }
	function setRights($rights) {
		$this->loadFromDatabase();
		$this->rights = $rights;
	}

	/** 
	 * Gets a message if the text starts with a colon, otherwise returns the text itself
	 */
	function getMessage( $text ) {
		if ( strlen( $text ) && $text{0} == ':' ) {
			return wfMsg( substr( $text, 1 ) );
		} else {
			return $text;
		}
	}

	/**
	 * As for getMessage but for content
	 */
	function getMessageForContent( $text ) {
		if ( strlen( $text ) && $text{0} == ':' ) {
			return wfMsgForContent( substr( $text, 1 ) );
		} else {
			return $text;
		}
	}

}
?>
