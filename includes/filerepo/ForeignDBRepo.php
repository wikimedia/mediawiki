<?php

/**
 * A foreign repository with an accessible MediaWiki database
 */

class ForeignDBRepo extends LocalRepo {
	# Settings
	var $dbType, $dbServer, $dbUser, $dbPassword, $dbName, $dbFlags, 
		$tablePrefix, $hasSharedCache;
	
	# Other stuff
	var $dbConn;
	var $fileFactory = array( 'ForeignDBFile', 'newFromTitle' );

	function __construct( $info ) {
		parent::__construct( $info );
		$this->dbType = $info['dbType'];
		$this->dbServer = $info['dbServer'];
		$this->dbUser = $info['dbUser'];
		$this->dbPassword = $info['dbPassword'];
		$this->dbName = $info['dbName'];
		$this->dbFlags = $info['dbFlags'];
		$this->tablePrefix = $info['tablePrefix'];
		$this->hasSharedCache = $info['hasSharedCache'];
	}

	function getMasterDB() {
		if ( !isset( $this->dbConn ) ) {
			$class = 'Database' . ucfirst( $this->dbType );
			$this->dbConn = new $class( $this->dbServer, $this->dbUser, 
				$this->dbPassword, $this->dbName, false, $this->dbFlags, 
				$this->tablePrefix );
		}
		return $this->dbConn;
	}

	function getSlaveDB() {
		return $this->getMasterDB();
	}

	function hasSharedCache() {
		return $this->hasSharedCache;
	}

	function store( /*...*/ ) {
		throw new MWException( get_class($this) . ': write operations are not supported' );
	}
}

?>
