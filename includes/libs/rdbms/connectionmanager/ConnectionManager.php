<?php

/**
 * Database connection manager.
 *
 * This manages access to master and slave databases.
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
	private $dbName;

	/**
	 * @var int Db to use for write connections (can be overridden in subclasses)
	 */
	protected $writeDbIndex = DB_MASTER;

	/**
	 * @var int Db to use for read connections (can be overridden in subclasses)
	 */
	protected $readDbIndex = DB_REPLICA;

	/**
	 * @param LoadBalancer $loadBalancer
	 * @param string|bool $dbName Optional, defaults to current wiki.
	 *        This follows the convention for database names used by $loadBalancer.
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( LoadBalancer $loadBalancer, $dbName = false ) {
		if ( !is_string( $dbName ) && $dbName !== false ) {
			throw new InvalidArgumentException( '$dbName must be a string, or false.' );
		}

		$this->loadBalancer = $loadBalancer;
		$this->dbName = $dbName;
	}

	/**
	 * @param int $i
	 *
	 * @return Database
	 */
	private function getConnection( $i ) {
		return $this->loadBalancer->getConnection( $i, [], $this->dbName );
	}

	/**
	 * @param int $i
	 *
	 * @return DBConnRef
	 */
	private function getConnectionRef( $i ) {
		return $this->loadBalancer->getConnectionRef( $i, [], $this->dbName );
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
		return $this->getConnection( $this->writeDbIndex );
	}

	/**
	 * Returns a database connection for reading. The connection should later be released by
	 * calling releaseConnection().
	 *
	 * @since 1.29
	 *
	 * @return Database
	 */
	public function getReadConnection() {
		return $this->getConnection( $this->readDbIndex );
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
		return $this->getConnectionRef( $this->writeDbIndex );
	}

	/**
	 * Returns a database connection ref for reading.
	 *
	 * @since 1.29
	 *
	 * @return DBConnRef
	 */
	public function getReadConnectionRef() {
		return $this->getConnectionRef( $this->readDbIndex );
	}

}
