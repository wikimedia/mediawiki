<?php
/**
 * DatabaseBase client connection pooling manager.
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
 * @author Aaron Schulz
 */

/**
 * Helper class to manage database connections.
 *
 * This can be used to get handle wrappers that free the handle when the wrapper
 * leaves scope. This class mostly just wraps DatabaseBase and LoadBalancer methods.
 * This provides an easy way to cache connection handles that may also have state,
 * such as a handle does between begin() and commit(), and without hoarding connections.
 * The wrappers use PHP magic methods so that calling functions on them calls the
 * function of the actual DatabaseBase object handle.
 *
 * @ingroup Database
 * @since 1.22
 */
class DBConnectionPool {
	/**
	 * Get a database connection handle
	 *
	 * @see wfGetDB() for parameter information
	 *
	 * @param integer $db
	 * @param mixed $groups
	 * @param string $wiki
	 * @return DatabaseBase
	 */
	public static function getConnection( $db, $groups = array(), $wiki = false ) {
		$lb = wfGetLB( $wiki );
		return new DBConnRef( $lb, $lb->getConnection( $db, $groups, $wiki ) );
	}
}

/**
 * Helper class to handle automatically marking connectons as reusable (via RAII pattern)
 *
 * @ingroup Database
 * @since 1.22
 */
class DBConnRef {
	/** @var LoadBalancer */
	protected $lb;
	/** @var DatabaseBase */
	protected $conn;

	/**
	 * @param $lb LoadBalancer
	 * @param $conn DatabaseBase
	 */
	public function __construct( LoadBalancer $lb, DatabaseBase $conn ) {
		$this->lb = $lb;
		$this->conn = $conn;
	}

	public function __call( $name, $arguments ) {
		return call_user_func_array( array( $this->conn, $name ), $arguments );
	}

	function __destruct() {
		$this->lb->reuseConnection( $this->conn );
	}
}
