<?php
/**
 * @package MediaWiki
 */

/**
 * Class to manage a group
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
	/**#@- */
	
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
		global $wgCommandLineMode;
		$fname = 'Group::loadFromDatabase';
		if ( $this->dataLoaded || $wgCommandLineMode ) {
			return;
		}

		// be sure it's an integer
		$this->id = IntVal($this->id);
		
		if($this->id) {
			$dbr =& wfGetDB( DB_SLAVE );
			$r = $dbr->selectRow('group', array('group_id', 'group_name', 'group_description', 'group_rights'), array( 'group_id' => $this->id ), $fname );
			$this->id = $r->group_id;
			$this->name = $r->group_name;
			$this->description = $r->group_description;
			$this->rights = $r->group_rights;
			$this->dataLoaded = true;
		} else {
			$dbr =& wfGetDB( DB_SLAVE );
			$r = $dbr->selectRow('group', array('group_id', 'group_name', 'group_description', 'group_rights'), array( 'group_name' => $this->name ), $fname );
			$this->id = $r->group_id;
			$this->name = $r->group_name;
			$this->description = $r->group_description;
			$this->rights = $r->group_rights;
			$this->dataLoaded = true;
		}
	}
	
	/** Initialise a new row in the database */
	function addToDatabase() {
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
		$fname = 'Group::save';
		if($this->id == 0) { return; }

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
	
// Factories
	/** @param integer $id Group database id */
	function newFromId($id) {
		$fname = 'Group::newFromId';
		$g = new Group();
		$name = $g->nameFromId(IntVal($id));

		if($name == '') { return; }
		else { return $g->newFromName($name); }
	}


	/** @param string $name Group database name */
	function newFromName($name) {
		$fname = 'Group::newFromName';
		$g = new Group();
		
		$g->setId( $g->idFromName($name) );
		if( $g->getId() != 0 ) {
			return $g;
		} else { 
		 	return;
		}
	}

// Converters
	/**
	 * @param integer $id Group database id
	 * @return string Group database name
	 */
	function nameFromId($id) {
		$fname = 'Group::nameFromId';
		$dbr =& wfGetDB( DB_SLAVE );
		$r = $dbr->selectRow( 'group', array( 'group_name' ), array( 'group_id' => $id ), $fname );
		
		if($r === false) {
			return '';
		} else {
			return $r->group_name;
		}
	}

	/**
	 * @param string $name Group database name 
	 * @return integer Group database id
	 */
	function idFromName($name) {
		$fname = 'Group::idFromName';
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
	function setName($name) {
		$this->loadFromDatabase();
		$this->name = $name;
	}
	
	function getId() { return $this->id; }
	function setId($id) {
		$this->id = IntVal($id);
		$this->dataLoaded = false;
		}
	
	function getDescription() { return $this->description; }
	function setDescription($desc) {
		$this->loadFromDatabase();
		$this->description = $desc;
	}

	function getRights() { return $this->rights; }
	function setRights($rights) {
		$this->loadFromDatabase();
		$this->rights = $rights;
	}
}
?>
