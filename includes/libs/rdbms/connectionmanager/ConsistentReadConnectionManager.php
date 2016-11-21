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
 */
class ConsistentReadConnectionManager extends ConnectionManager {

	/**
	 * Forces all future calls to getReadConnection() to return a connection to the master DB.
	 * Use this before performing read operations that are critical for a future update.
	 * Calling beginAtomicSection() implies a call to forceMaster().
	 *
	 * @since 1.29
	 */
	public function forceMaster() {
		$this->readDbIndex = $this->writeDbIndex;
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
	 *
	 * @return Database
	 */
	public function beginAtomicSection( $fname ) {
		// Once we have written to master, do not read from slave.
		$this->forceMaster();

		$db = $this->getWriteConnection();
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
