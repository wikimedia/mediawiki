<?php

/**
 * An LBFactory class that always returns a single database object.
 */
class LBFactory_Single extends LBFactory {
	protected $lb;

	/**
	 * @param $conf array An associative array with one member:
	 *  - connection: The DatabaseBase connection object
	 */
	function __construct( $conf ) {
		$this->lb = new LoadBalancer_Single( $conf );
	}

	/**
	 * @param $wiki
	 *
	 * @return LoadBalancer_Single
	 */
	function newMainLB( $wiki = false ) {
		return $this->lb;
	}

	/**
	 * @param $wiki
	 *
	 * @return LoadBalancer_Single
	 */
	function getMainLB( $wiki = false ) {
		return $this->lb;
	}

	/**
	 * @param $cluster
	 * @param $wiki
	 *
	 * @return LoadBalancer_Single
	 */
	function newExternalLB( $cluster, $wiki = false ) {
		return $this->lb;
	}

	/**
	 * @param $cluster
	 * @param $wiki
	 *
	 * @return LoadBalancer_Single
	 */
	function &getExternalLB( $cluster, $wiki = false ) {
		return $this->lb;
	}

	/**
	 * @param $callback string|array
	 * @param $params array
	 */
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

	/**
	 * @param $params array
	 */
	function __construct( $params ) {
		$this->db = $params['connection'];
		parent::__construct( array( 'servers' => array( array(
			'type' => $this->db->getType(),
			'host' => $this->db->getServer(),
			'dbname' => $this->db->getDBname(),
			'load' => 1,
		) ) ) );
	}

	/**
	 *
	 * @param $server string
	 * @param $dbNameOverride bool
	 *
	 * @return DatabaseBase
	 */
	function reallyOpenConnection( $server, $dbNameOverride = false ) {
		return $this->db;
	}
}
