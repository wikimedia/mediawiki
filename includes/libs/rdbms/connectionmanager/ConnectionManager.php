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
	 * @var string[]
	 */
	private $groups = [];

	/**
	 * @param LoadBalancer $loadBalancer
	 * @param string|bool $dbName Optional, defaults to current wiki.
	 *        This follows the convention for database names used by $loadBalancer.
	 * @param string[] $groups
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( LoadBalancer $loadBalancer, $dbName = false, array $groups = [] ) {
		if ( !is_string( $dbName ) && $dbName !== false ) {
			throw new InvalidArgumentException( '$dbName must be a string, or false.' );
		}

		$this->loadBalancer = $loadBalancer;
		$this->dbName = $dbName;
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
		return $this->loadBalancer->getConnection( $i, $groups, $this->dbName );
	}

	/**
	 * @param int $i
	 * @param string[]|null $groups
	 *
	 * @return DBConnRef
	 */
	private function getConnectionRef( $i, array $groups = null ) {
		$groups = $groups === null ? $this->groups : $groups;
		return $this->loadBalancer->getConnectionRef( $i, $groups, $this->dbName );
	}

	/**
	 * Returns a connection to the master DB, for updating. The connection should later be released
	 * by calling releaseConnection().
	 *
	 * @since 1.29
	 *
	 * @param string[]|null $groups
	 *
	 * @return Database
	 */
	public function getWriteConnection( array $groups = null ) {
		$groups = $groups === null ? $this->groups : $groups;
		return $this->getConnection( DB_MASTER, $groups );
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
	 * @param string[]|null $groups
	 *
	 * @return DBConnRef
	 */
	public function getWriteConnectionRef( array $groups = null ) {
		$groups = $groups === null ? $this->groups : $groups;
		return $this->getConnectionRef( DB_MASTER, $groups );
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

	/**
	 * Begins an atomic section and returns a database connection to the master DB, for updating.
	 *
	 * @since 1.29
	 *
	 * @note: This causes all future calls to getReadConnection() to return a connection
	 * to the master DB, even after commitAtomicSection() or rollbackAtomicSection() have
	 * been called.
	 *
	 * @param string $fname
	 * @param string[]|null $groups
	 *
	 * @return Database
	 */
	public function beginAtomicSection( $fname, array $groups = null ) {
		$groups = $groups === null ? $this->groups : $groups;
		$db = $this->getWriteConnection( $groups );
		$db->startAtomic( $fname );

		return $db;
	}

	/**
	 * @since 1.29
	 *
	 * @param IDatabase $db
	 * @param string $fname
	 */
	public function commitAtomicSection( IDatabase $db, $fname ) {
		$db->endAtomic( $fname );
		$this->releaseConnection( $db );
	}

	/**
	 * @since 1.29
	 *
	 * @param IDatabase $db
	 * @param string $fname
	 */
	public function rollbackAtomicSection( IDatabase $db, $fname ) {
		// FIXME: there does not seem to be a clean way to roll back an atomic section?!
		$db->rollback( $fname, 'flush' );
		$this->releaseConnection( $db );
	}

}
