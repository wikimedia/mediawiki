<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

/**
 * Database connection manager.
 *
 * This manages access to primary and replica databases. It also manages state that indicates whether
 * the replica databases are possibly outdated after a write operation, and thus the primary database
 * should be used for subsequent read operations.
 *
 * @note Services that access overlapping sets of database tables, or interact with logically
 * related sets of data in the database, should share a SessionConsistentConnectionManager.
 * Services accessing unrelated sets of information may prefer to not share a
 * SessionConsistentConnectionManager, so they can still perform read operations against replica
 * databases after a (unrelated, per the assumption) write operation to the primary database.
 * Generally, sharing a SessionConsistentConnectionManager improves consistency (by avoiding race
 * conditions due to replication lag), but can reduce performance (by directing more read
 * operations to the primary database server).
 *
 * @since 1.29
 * @ingroup Database
 * @author Daniel Kinzler
 * @author Addshore
 */
class SessionConsistentConnectionManager extends ConnectionManager {

	/**
	 * @var bool
	 */
	private $forceWriteConnection = false;

	/**
	 * Forces all future calls to getReadConnection() to return a write connection.
	 * Use this before performing read operations that are critical for a future update.
	 *
	 * @since 1.29
	 */
	public function prepareForUpdates() {
		$this->forceWriteConnection = true;
	}

	/**
	 * @since 1.29
	 * @since 1.37 Added optional $flags parameter
	 *
	 * @param string[]|null $groups
	 * @param int $flags
	 *
	 * @return IReadableDatabase
	 */
	public function getReadConnection( ?array $groups = null, int $flags = 0 ) {
		if ( $this->forceWriteConnection ) {
			return parent::getWriteConnection( $flags );
		}

		return parent::getReadConnection( $groups, $flags );
	}

	/**
	 * @since 1.29
	 * @since 1.37 Added optional $flags parameter
	 *
	 * @param int $flags
	 *
	 * @return IDatabase
	 */
	public function getWriteConnection( int $flags = 0 ) {
		$this->prepareForUpdates();
		return parent::getWriteConnection( $flags );
	}

}
