<?php

/**
 * Base class for objects that allow access to other wiki's databases using
 * the foreign database access mechanism implemented by LBFactoryMulti.
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
 * @since 1.21
 *
 * @file
 * @ingroup Database
 *
 * @licence GNU GPL v2+
 * @author Daniel Kinzler
 */
abstract class DBAccessBase implements IDBAccessObject {
	/**
	 * @var string|bool $wiki The target wiki's name. This must be an ID
	 * that LBFactory can understand.
	 */
	protected $wiki = false;

	/**
	 * @param string|bool $wiki The target wiki's name. This must be an ID
	 * that LBFactory can understand.
	 */
	public function __construct( $wiki = false ) {
		$this->wiki = $wiki;
	}

	/**
	 * Returns a database connection.
	 *
	 * @see wfGetDB()
	 * @see LoadBalancer::getConnection()
	 *
	 * @since 1.21
	 *
	 * @param int $id Which connection to use
	 * @param array $groups Query groups
	 *
	 * @return DatabaseBase
	 */
	protected function getConnection( $id, $groups = [] ) {
		$loadBalancer = wfGetLB( $this->wiki );

		return $loadBalancer->getConnection( $id, $groups, $this->wiki );
	}

	/**
	 * Releases a database connection and makes it available for recycling.
	 *
	 * @see LoadBalancer::reuseConnection()
	 *
	 * @since 1.21
	 *
	 * @param DatabaseBase $db The database connection to release.
	 */
	protected function releaseConnection( DatabaseBase $db ) {
		if ( $this->wiki !== false ) {
			$loadBalancer = $this->getLoadBalancer();
			$loadBalancer->reuseConnection( $db );
		}
	}

	/**
	 * Get the database type used for read operations.
	 *
	 * @see wfGetLB
	 *
	 * @since 1.21
	 *
	 * @return LoadBalancer The database load balancer object
	 */
	public function getLoadBalancer() {
		return wfGetLB( $this->wiki );
	}
}
