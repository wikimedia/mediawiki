<?php
/**
 * A foreign repository with a MediaWiki database accessible via the configured LBFactory
 *
 * @file
 * @ingroup FileRepo
 */

/**
 * A foreign repository with a MediaWiki database accessible via the configured LBFactory
 *
 * @ingroup FileRepo
 */
class ForeignDBViaLBRepo extends LocalRepo {
	var $wiki, $dbName, $tablePrefix;
	var $fileFactory = array( 'ForeignDBFile', 'newFromTitle' );
	var $fileFromRowFactory = array( 'ForeignDBFile', 'newFromRow' );

	function __construct( $info ) {
		parent::__construct( $info );
		$this->wiki = $info['wiki'];
		list( $this->dbName, $this->tablePrefix ) = wfSplitWikiID( $this->wiki );
		$this->hasSharedCache = $info['hasSharedCache'];
	}

	function getMasterDB() {
		return wfGetDB( DB_MASTER, array(), $this->wiki );
	}

	function getSlaveDB() {
		return wfGetDB( DB_SLAVE, array(), $this->wiki );
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
			array_unshift( $args, $this->wiki );
			return implode( ':', $args );
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
	function deleteBatch( $fileMap ) {
		throw new MWException( get_class($this) . ': write operations are not supported' );
	}
}
