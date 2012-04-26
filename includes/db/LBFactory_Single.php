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
	 * @param $wiki bool|string
	 *
	 * @return LoadBalancer_Single
	 */
	function newMainLB( $wiki = false ) {
		return $this->lb;
	}

	/**
	 * @param $wiki bool|string
	 *
	 * @return LoadBalancer_Single
	 */
	function getMainLB( $wiki = false ) {
		return $this->lb;
	}

	/**
	 * @param $cluster
	 * @param $wiki bool|string
	 *
	 * @return LoadBalancer_Single
	 */
	function newExternalLB( $cluster, $wiki = false ) {
		return $this->lb;
	}

	/**
	 * @param $cluster
	 * @param $wiki bool|string
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
