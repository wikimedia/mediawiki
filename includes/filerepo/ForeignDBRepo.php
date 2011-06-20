<?php
/**
 * A foreign repository with an accessible MediaWiki database
 *
 * @file
 * @ingroup FileRepo
 */

/**
 * A foreign repository with an accessible MediaWiki database
 *
 * @ingroup FileRepo
 */
class ForeignDBRepo extends LocalRepo {
	# Settings
	var $dbType, $dbServer, $dbUser, $dbPassword, $dbName, $dbFlags,
		$tablePrefix, $hasSharedCache;

	# Other stuff
	var $dbConn;
	var $fileFactory = array( 'ForeignDBFile', 'newFromTitle' );
	var $fileFromRowFactory = array( 'ForeignDBFile', 'newFromRow' );

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
			$this->dbConn = DatabaseBase::factory( $this->dbType,
				array(
					'host' => $this->dbServer,
					'user'   => $this->dbUser,
					'password' => $this->dbPassword,
					'dbname' => $this->dbName,
					'flags' => $this->dbFlags,
					'tablePrefix' => $this->tablePrefix
				)
			);
		}
		return $this->dbConn;
	}

	function getSlaveDB() {
		return $this->getMasterDB();
	}

	function hasSharedCache() {
		return $this->hasSharedCache;
	}

	/**
	 * Get a key on the primary cache for this repository.
	 * Returns false if the repository's cache is not accessible at this site. 
	 * The parameters are the parts of the key, as for wfMemcKey().
	 */
	function getSharedCacheKey( /*...*/ ) {
		if ( $this->hasSharedCache() ) {
			$args = func_get_args();
			array_unshift( $args, $this->dbName, $this->tablePrefix );
			return call_user_func_array( 'wfForeignMemcKey', $args );
		} else {
			return false;
		}
	}

	function store( $srcPath, $dstZone, $dstRel, $flags = 0 ) {
		throw new MWException( get_class($this) . ': write operations are not supported' );
	}
	function publish( $srcPath, $dstRel, $archiveRel, $flags = 0 ) {
		throw new MWException( get_class($this) . ': write operations are not supported' );
	}
	function deleteBatch( $sourceDestPairs ) {
		throw new MWException( get_class($this) . ': write operations are not supported' );
	}
}
