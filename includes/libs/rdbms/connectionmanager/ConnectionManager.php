<?php

namespace Wikimedia\Rdbms;

use InvalidArgumentException;

/**
 * Database connection manager.
 *
 * This manages access to master and replica databases.
 *
 * @since 1.29
 *
 * @license GPL-2.0+
 * @author Addshore
 */
class ConnectionManager {

	/**
	 * @var LoadBalancer
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
	 * @param LoadBalancer $loadBalancer
	 * @param string|bool $domain Optional logical DB name, defaults to current wiki.
	 *        This follows the convention for database names used by $loadBalancer.
	 * @param string[] $groups see LoadBalancer::getConnection
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( LoadBalancer $loadBalancer, $domain = false, array $groups = [] ) {
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
	 *
	 * @return Database
	 */
	private function getConnection( $i, array $groups = null ) {
		$groups = $groups === null ? $this->groups : $groups;
		return $this->loadBalancer->getConnection( $i, $groups, $this->domain );
	}

	/**
	 * @param int $i
	 * @param string[]|null $groups
	 *
	 * @return DBConnRef
	 */
	private function getConnectionRef( $i, array $groups = null ) {
		$groups = $groups === null ? $this->groups : $groups;
		return $this->loadBalancer->getConnectionRef( $i, $groups, $this->domain );
	}

	/**
	 * Returns a connection to the master DB, for updating. The connection should later be released
	 * by calling releaseConnection().
	 *
	 * @since 1.29
	 *
	 * @return Database
	 */
	public function getWriteConnection() {
		return $this->getConnection( DB_MASTER );
	}

	/**
	 * Returns a database connection for reading. The connection should later be released by
	 * calling releaseConnection().
	 *
	 * @since 1.29
	 *
	 * @param string[]|null $groups
	 *
	 * @return Database
	 */
	public function getReadConnection( array $groups = null ) {
		$groups = $groups === null ? $this->groups : $groups;
		return $this->getConnection( DB_REPLICA, $groups );
	}

	/**
	 * @since 1.29
	 *
	 * @param IDatabase $db
	 */
	public function releaseConnection( IDatabase $db ) {
		$this->loadBalancer->reuseConnection( $db );
	}

	/**
	 * Returns a connection ref to the master DB, for updating.
	 *
	 * @since 1.29
	 *
	 * @return DBConnRef
	 */
	public function getWriteConnectionRef() {
		return $this->getConnectionRef( DB_MASTER );
	}

	/**
	 * Returns a database connection ref for reading.
	 *
	 * @since 1.29
	 *
	 * @param string[]|null $groups
	 *
	 * @return DBConnRef
	 */
	public function getReadConnectionRef( array $groups = null ) {
		$groups = $groups === null ? $this->groups : $groups;
		return $this->getConnectionRef( DB_REPLICA, $groups );
	}

}
