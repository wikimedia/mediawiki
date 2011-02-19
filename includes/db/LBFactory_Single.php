<?php

/**
 * An LBFactory class that always returns a single database object.
 */
class LBFactory_Single extends LBFactory {
	protected $lb;

	/**
	 * @param $conf An associative array with one member:
	 *  - connection: The DatabaseBase connection object
	 */
	function __construct( $conf ) {
		$this->lb = new LoadBalancer_Single( $conf );
	}

	function newMainLB( $wiki = false ) {
		return $this->lb;
	}

	function getMainLB( $wiki = false ) {
		return $this->lb;
	}

	function newExternalLB( $cluster, $wiki = false ) {
		return $this->lb;
	}

	function &getExternalLB( $cluster, $wiki = false ) {
		return $this->lb;
	}

	function forEachLB( $callback, $params = array() ) {
		call_user_func_array( $callback, array_merge( array( $this->lb ), $params ) );
	}
}

/**
 * Helper class for LBFactory_Single.
 */
class LoadBalancer_Single extends LoadBalancer {

	/**
	 * @var DatabaseBase
	 */
	var $db;

	function __construct( $params ) {
		$this->db = $params['connection'];
		parent::__construct( array( 'servers' => array( array(
			'type' => $this->db->getType(),
			'host' => $this->db->getServer(),
			'dbname' => $this->db->getDBname(),
			'load' => 1,
		) ) ) );
	}

	function reallyOpenConnection( $server, $dbNameOverride = false ) {
		return $this->db;
	}
}
