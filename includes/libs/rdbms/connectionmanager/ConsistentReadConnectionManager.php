<?php

/**
 * Database connection manager.
 *
 * This manages access to master and slave databases. It also manages state that indicates whether
 * the slave databases are possibly outdated after a write operation, and thus the master database
 * should be used for subsequent read operations.
 *
 * @note: Services that access overlapping sets of database tables, or interact with logically
 * related sets of data in the database, should share a ConsistentReadConnectionManager.
 * Services accessing unrelated sets of information may prefer to not share a
 * ConsistentReadConnectionManager, so they can still perform read operations against slave
 * databases after a (unrelated, per the assumption) write operation to the master database.
 * Generally, sharing a ConsistentReadConnectionManager improves consistency (by avoiding race
 * conditions due to replication lag), but can reduce performance (by directing more read
 * operations to the master database server).
 *
 * @since 1.29
 *
 * @license GPL-2.0+
 * @author Daniel Kinzler
 * @author Addshore
 */
class ConsistentReadConnectionManager extends ConnectionManager {

	/**
	 * @var bool
	 */
	private $forceMaster = false;

	/**
	 * Forces all future calls to getReadConnection() to return a connection to the master DB.
	 * Use this before performing read operations that are critical for a future update.
	 * Calling beginAtomicSection() implies a call to forceMaster().
	 *
	 * @since 1.29
	 */
	public function forceMaster() {
		$this->forceMaster = true;
	}

	/**
	 * @since 1.29
	 *
	 * @param array $groups
	 *
	 * @return Database
	 */
	public function getReadConnection( $groups = [] ) {
		if ( $this->forceMaster ) {
			return parent::getWriteConnection( $groups );
		}

		return parent::getReadConnection( $groups );
	}

	/**
	 * @since 1.29
	 *
	 * @param array $groups
	 *
	 * @return Database
	 */
	public function getWriteConnection( $groups = [] ) {
		$this->forceMaster();
		return parent::getWriteConnection( $groups );
	}

	/**
	 * @since 1.29
	 *
	 * @param array $groups
	 *
	 * @return DBConnRef
	 */
	public function getReadConnectionRef( $groups = [] ) {
		if ( $this->forceMaster ) {
			return parent::getWriteConnectionRef( $groups );
		}

		return parent::getReadConnectionRef( $groups );
	}

	/**
	 * @since 1.29
	 *
	 * @param array $groups
	 *
	 * @return DBConnRef
	 */
	public function getWriteConnectionRef( $groups = [] ) {
		$this->forceMaster();
		return parent::getWriteConnectionRef( $groups );
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
	 * @param array $groups
	 *
	 * @return Database
	 */
	public function beginAtomicSection( $fname, $groups = [] ) {
		// Once we have written to master, do not read from slave.
		$this->forceMaster();

		return parent::beginAtomicSection( $fname, $groups );
	}

}
