<?php
/**
 * @license GPL-2.0-or-later
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
		$groups ??= $this->groups;
		return $this->loadBalancer->getConnection( $i, $groups, $this->domain, $flags );
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
	 * @return IReadableDatabase
	 */
	public function getReadConnection( ?array $groups = null, int $flags = 0 ) {
		$groups ??= $this->groups;
		return $this->getConnection( DB_REPLICA, $groups, $flags );
	}
}
