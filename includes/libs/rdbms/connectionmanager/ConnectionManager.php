<?php
/**
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
 */
namespace Wikimedia\Rdbms;

use InvalidArgumentException;

/**
 * Database connection manager.
 *
 * This manages access to primary and replica databases.
 *
 * @since 1.29
 * @ingroup Database
 * @author Addshore
 */
class ConnectionManager {

	/**
	 * @var ILoadBalancer
	 */
	private $loadBalancer;

	/**
	 * The symbolic name of the target database, or false for the local wiki's database.
	 *
	 * @var string|false
	 */
	private $domain;

	/**
	 * @var string[]
	 */
	private $groups = [];

	/**
	 * @param ILoadBalancer $loadBalancer
	 * @param string|false $domain Optional logical DB name, defaults to current wiki.
	 *        This follows the convention for database names used by $loadBalancer.
	 * @param string[] $groups see LoadBalancer::getConnection
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( ILoadBalancer $loadBalancer, $domain = false, array $groups = [] ) {
		if ( !is_string( $domain ) && $domain !== false ) {
			throw new InvalidArgumentException( '$dbName must be a string, or false.' );
		}

		$this->loadBalancer = $loadBalancer;
		$this->domain = $domain;
		$this->groups = $groups;
	}

	/**
	 * @param int $i
	 * @param string[]|null $groups
	 * @param int $flags
	 * @return IDatabase
	 */
	private function getConnection( $i, ?array $groups = null, int $flags = 0 ) {
		$groups = $groups ?? $this->groups;
		return $this->loadBalancer->getConnection( $i, $groups, $this->domain, $flags );
	}

	/**
	 * @param int $i
	 * @param string[]|null $groups
	 * @return DBConnRef
	 */
	private function getConnectionRef( $i, array $groups = null ) {
		$groups = $groups ?? $this->groups;
		return $this->loadBalancer->getConnectionRef( $i, $groups, $this->domain );
	}

	/**
	 * Returns a connection to the primary DB, for updating.
	 *
	 * @since 1.29
	 * @since 1.37 Added optional $flags parameter
	 * @param int $flags
	 * @return IDatabase
	 */
	public function getWriteConnection( int $flags = 0 ) {
		return $this->getConnection( DB_PRIMARY, null, $flags );
	}

	/**
	 * Returns a database connection for reading.
	 *
	 * @since 1.29
	 * @since 1.37 Added optional $flags parameter
	 * @param string[]|null $groups
	 * @param int $flags
	 * @return IDatabase
	 */
	public function getReadConnection( ?array $groups = null, int $flags = 0 ) {
		$groups = $groups ?? $this->groups;
		return $this->getConnection( DB_REPLICA, $groups, $flags );
	}

	/**
	 * @since 1.29
	 * @param IDatabase $db
	 * @deprecated since 1.38
	 */
	public function releaseConnection( IDatabase $db ) {
		$this->loadBalancer->reuseConnection( $db );
	}

	/**
	 * Returns a connection ref to the primary DB, for updating.
	 *
	 * @since 1.29
	 *
	 * @return DBConnRef
	 * @deprecated since 1.39; Use getWriteConnection()
	 */
	public function getWriteConnectionRef() {
		return $this->getConnectionRef( DB_PRIMARY );
	}

	/**
	 * Returns a database connection ref for reading.
	 *
	 * @since 1.29
	 * @param string[]|null $groups
	 * @return DBConnRef
	 * @deprecated since 1.38; Use getReadConnection()
	 */
	public function getReadConnectionRef( array $groups = null ) {
		$groups = $groups ?? $this->groups;
		return $this->getConnectionRef( DB_REPLICA, $groups );
	}

	/**
	 * Returns a lazy-connecting database connection ref for updating.
	 *
	 * @since 1.38
	 * @return DBConnRef
	 * @deprecated since 1.39; Use getWriteConnection()
	 */
	public function getLazyWriteConnectionRef(): DBConnRef {
		return $this->getConnectionRef( DB_PRIMARY );
	}

	/**
	 * Returns a lazy-connecting database connection ref for reading.
	 *
	 * @since 1.37
	 * @param string[]|null $groups
	 * @return DBConnRef
	 * @deprecated since 1.39; Use getReadConnection()
	 */
	public function getLazyReadConnectionRef( array $groups = null ) {
		$groups = $groups ?? $this->groups;
		return $this->getConnectionRef( DB_REPLICA, $groups );
	}

}
