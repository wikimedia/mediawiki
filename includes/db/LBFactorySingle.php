<?php
/**
 * Simple generator of database connections that always returns the same object.
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
 * @ingroup Database
 */

/**
 * An LBFactory class that always returns a single database object.
 */
class LBFactorySingle extends LBFactory {
	/** @var LoadBalancerSingle */
	protected $lb;

	/**
	 * @param array $conf An associative array with one member:
	 *  - connection: The DatabaseBase connection object
	 */
	function __construct( $conf ) {
		$this->lb = new LoadBalancerSingle( $conf );
	}

	/**
	 * @param bool|string $wiki
	 * @return LoadBalancerSingle
	 */
	function newMainLB( $wiki = false ) {
		return $this->lb;
	}

	/**
	 * @param bool|string $wiki
	 * @return LoadBalancerSingle
	 */
	function getMainLB( $wiki = false ) {
		return $this->lb;
	}

	/**
	 * @param string $cluster External storage cluster, or false for core
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancerSingle
	 */
	function newExternalLB( $cluster, $wiki = false ) {
		return $this->lb;
	}

	/**
	 * @param string $cluster external storage cluster, or false for core
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancerSingle
	 */
	function &getExternalLB( $cluster, $wiki = false ) {
		return $this->lb;
	}

	/**
	 * @param string|array $callback
	 * @param array $params
	 */
	function forEachLB( $callback, $params = array() ) {
		call_user_func_array( $callback, array_merge( array( $this->lb ), $params ) );
	}
}

/**
 * Helper class for LBFactorySingle.
 */
class LoadBalancerSingle extends LoadBalancer {
	/** @var DatabaseBase */
	protected $db;

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
	 * @param string $server
	 * @param bool $dbNameOverride
	 *
	 * @return DatabaseBase
	 */
	function reallyOpenConnection( $server, $dbNameOverride = false ) {
		return $this->db;
	}
}
