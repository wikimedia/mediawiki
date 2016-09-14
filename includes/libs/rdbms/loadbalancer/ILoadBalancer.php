<?php
/**
 * Database load balancing interface
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
 * @ingroup Database
 * @author Aaron Schulz
 */

/**
 * Interface for database load balancing object that manages IDatabase handles
 *
 * @since 1.28
 * @ingroup Database
 */
interface ILoadBalancer {
	/**
	 * @param array $params Array with keys:
	 *  - servers : Required. Array of server info structures.
	 *  - loadMonitor : Name of a class used to fetch server lag and load.
	 *  - readOnlyReason : Reason the master DB is read-only if so [optional]
	 *  - waitTimeout : Maximum time to wait for replicas for consistency [optional]
	 *  - srvCache : BagOStuff object for server cache [optional]
	 *  - memCache : BagOStuff object for cluster memory cache [optional]
	 *  - wanCache : WANObjectCache object [optional]
	 *  - hostname : the name of the current server [optional]
	 * @throws InvalidArgumentException
	 */
	public function __construct( array $params );

	/**
	 * Get the index of the reader connection, which may be a replica DB
	 * This takes into account load ratios and lag times. It should
	 * always return a consistent index during a given invocation
	 *
	 * Side effect: opens connections to databases
	 * @param string|bool $group Query group, or false for the generic reader
	 * @param string|bool $wiki Wiki ID, or false for the current wiki
	 * @throws DBError
	 * @return bool|int|string
	 */
	public function getReaderIndex( $group = false, $wiki = false );

	/**
	 * Set the master wait position
	 * If a DB_REPLICA connection has been opened already, waits
	 * Otherwise sets a variable telling it to wait if such a connection is opened
	 * @param DBMasterPos $pos
	 */
	public function waitFor( $pos );

	/**
	 * Set the master wait position and wait for a "generic" replica DB to catch up to it
	 *
	 * This can be used a faster proxy for waitForAll()
	 *
	 * @param DBMasterPos $pos
	 * @param int $timeout Max seconds to wait; default is mWaitTimeout
	 * @return bool Success (able to connect and no timeouts reached)
	 */
	public function waitForOne( $pos, $timeout = null );

	/**
	 * Set the master wait position and wait for ALL replica DBs to catch up to it
	 * @param DBMasterPos $pos
	 * @param int $timeout Max seconds to wait; default is mWaitTimeout
	 * @return bool Success (able to connect and no timeouts reached)
	 */
	public function waitForAll( $pos, $timeout = null );

	/**
	 * Get any open connection to a given server index, local or foreign
	 * Returns false if there is no connection open
	 *
	 * @param int $i Server index
	 * @return IDatabase|bool False on failure
	 */
	public function getAnyOpenConnection( $i );

	/**
	 * Get a connection by index
	 * This is the main entry point for this class.
	 *
	 * @param int $i Server index
	 * @param array|string|bool $groups Query group(s), or false for the generic reader
	 * @param string|bool $wiki Wiki ID, or false for the current wiki
	 *
	 * @throws DBError
	 * @return IDatabase
	 */
	public function getConnection( $i, $groups = [], $wiki = false );

	/**
	 * Mark a foreign connection as being available for reuse under a different
	 * DB name or prefix. This mechanism is reference-counted, and must be called
	 * the same number of times as getConnection() to work.
	 *
	 * @param IDatabase $conn
	 * @throws InvalidArgumentException
	 */
	public function reuseConnection( $conn );

	/**
	 * Get a database connection handle reference
	 *
	 * The handle's methods wrap simply wrap those of a IDatabase handle
	 *
	 * @see LoadBalancer::getConnection() for parameter information
	 *
	 * @param int $db
	 * @param array|string|bool $groups Query group(s), or false for the generic reader
	 * @param string|bool $wiki Wiki ID, or false for the current wiki
	 * @return DBConnRef
	 */
	public function getConnectionRef( $db, $groups = [], $wiki = false );

	/**
	 * Get a database connection handle reference without connecting yet
	 *
	 * The handle's methods wrap simply wrap those of a IDatabase handle
	 *
	 * @see LoadBalancer::getConnection() for parameter information
	 *
	 * @param int $db
	 * @param array|string|bool $groups Query group(s), or false for the generic reader
	 * @param string|bool $wiki Wiki ID, or false for the current wiki
	 * @return DBConnRef
	 */
	public function getLazyConnectionRef( $db, $groups = [], $wiki = false );

	/**
	 * Open a connection to the server given by the specified index
	 * Index must be an actual index into the array.
	 * If the server is already open, returns it.
	 *
	 * On error, returns false, and the connection which caused the
	 * error will be available via $this->mErrorConnection.
	 *
	 * @note If disable() was called on this LoadBalancer, this method will throw a DBAccessError.
	 *
	 * @param int $i Server index
	 * @param string|bool $wiki Wiki ID, or false for the current wiki
	 * @return IDatabase|bool Returns false on errors
	 */
	public function openConnection( $i, $wiki = false );

	/**
	 * @return int
	 */
	public function getWriterIndex();

	/**
	 * Returns true if the specified index is a valid server index
	 *
	 * @param string $i
	 * @return bool
	 */
	public function haveIndex( $i );

	/**
	 * Returns true if the specified index is valid and has non-zero load
	 *
	 * @param string $i
	 * @return bool
	 */
	public function isNonZeroLoad( $i );

	/**
	 * Get the number of defined servers (not the number of open connections)
	 *
	 * @return int
	 */
	public function getServerCount();

	/**
	 * Get the host name or IP address of the server with the specified index
	 * Prefer a readable name if available.
	 * @param string $i
	 * @return string
	 */
	public function getServerName( $i );

	/**
	 * Return the server info structure for a given index, or false if the index is invalid.
	 * @param int $i
	 * @return array|bool
	 */
	public function getServerInfo( $i );

	/**
	 * Sets the server info structure for the given index. Entry at index $i
	 * is created if it doesn't exist
	 * @param int $i
	 * @param array $serverInfo
	 */
	public function setServerInfo( $i, array $serverInfo );

	/**
	 * Get the current master position for chronology control purposes
	 * @return DBMasterPos|bool Returns false if not applicable
	 */
	public function getMasterPos();

	/**
	 * Disable this load balancer. All connections are closed, and any attempt to
	 * open a new connection will result in a DBAccessError.
	 */
	public function disable();

	/**
	 * Close all open connections
	 */
	public function closeAll();

	/**
	 * Close a connection
	 *
	 * Using this function makes sure the LoadBalancer knows the connection is closed.
	 * If you use $conn->close() directly, the load balancer won't update its state.
	 *
	 * @param IDatabase $conn
	 */
	public function closeConnection( IDatabase $conn );

	/**
	 * Commit transactions on all open connections
	 * @param string $fname Caller name
	 * @throws DBExpectedError
	 */
	public function commitAll( $fname = __METHOD__ );

	/**
	 * Perform all pre-commit callbacks that remain part of the atomic transactions
	 * and disable any post-commit callbacks until runMasterPostTrxCallbacks()
	 *
	 * Use this only for mutli-database commits
	 */
	public function finalizeMasterChanges();

	/**
	 * Perform all pre-commit checks for things like replication safety
	 *
	 * Use this only for mutli-database commits
	 *
	 * @param array $options Includes:
	 *   - maxWriteDuration : max write query duration time in seconds
	 * @throws DBTransactionError
	 */
	public function approveMasterChanges( array $options );

	/**
	 * Flush any master transaction snapshots and set DBO_TRX (if DBO_DEFAULT is set)
	 *
	 * The DBO_TRX setting will be reverted to the default in each of these methods:
	 *   - commitMasterChanges()
	 *   - rollbackMasterChanges()
	 *   - commitAll()
	 * This allows for custom transaction rounds from any outer transaction scope.
	 *
	 * @param string $fname
	 * @throws DBExpectedError
	 */
	public function beginMasterChanges( $fname = __METHOD__ );

	/**
	 * Issue COMMIT on all master connections where writes where done
	 * @param string $fname Caller name
	 * @throws DBExpectedError
	 */
	public function commitMasterChanges( $fname = __METHOD__ );

	/**
	 * Issue all pending post-COMMIT/ROLLBACK callbacks
	 *
	 * Use this only for mutli-database commits
	 *
	 * @param integer $type IDatabase::TRIGGER_* constant
	 * @return Exception|null The first exception or null if there were none
	 */
	public function runMasterPostTrxCallbacks( $type );

	/**
	 * Issue ROLLBACK only on master, only if queries were done on connection
	 * @param string $fname Caller name
	 * @throws DBExpectedError
	 */
	public function rollbackMasterChanges( $fname = __METHOD__ );

	/**
	 * Suppress all pending post-COMMIT/ROLLBACK callbacks
	 *
	 * Use this only for mutli-database commits
	 *
	 * @return Exception|null The first exception or null if there were none
	 */
	public function suppressTransactionEndCallbacks();

	/**
	 * Commit all replica DB transactions so as to flush any REPEATABLE-READ or SSI snapshot
	 *
	 * @param string $fname Caller name
	 */
	public function flushReplicaSnapshots( $fname = __METHOD__ );

	/**
	 * @return bool Whether a master connection is already open
	 */
	public function hasMasterConnection();

	/**
	 * Determine if there are pending changes in a transaction by this thread
	 * @return bool
	 */
	public function hasMasterChanges();

	/**
	 * Get the timestamp of the latest write query done by this thread
	 * @return float|bool UNIX timestamp or false
	 */
	public function lastMasterChangeTimestamp();

	/**
	 * Check if this load balancer object had any recent or still
	 * pending writes issued against it by this PHP thread
	 *
	 * @param float $age How many seconds ago is "recent" [defaults to mWaitTimeout]
	 * @return bool
	 */
	public function hasOrMadeRecentMasterChanges( $age = null );

	/**
	 * Get the list of callers that have pending master changes
	 *
	 * @return string[] List of method names
	 */
	public function pendingMasterChangeCallers();

	/**
	 * @note This method will trigger a DB connection if not yet done
	 * @param string|bool $wiki Wiki ID, or false for the current wiki
	 * @return bool Whether the generic connection for reads is highly "lagged"
	 */
	public function getLaggedReplicaMode( $wiki = false );

	/**
	 * @note This method will never cause a new DB connection
	 * @return bool Whether any generic connection used for reads was highly "lagged"
	 */
	public function laggedReplicaUsed();

	/**
	 * @note This method may trigger a DB connection if not yet done
	 * @param string|bool $wiki Wiki ID, or false for the current wiki
	 * @param IDatabase|null DB master connection; used to avoid loops [optional]
	 * @return string|bool Reason the master is read-only or false if it is not
	 */
	public function getReadOnlyReason( $wiki = false, IDatabase $conn = null );

	/**
	 * Disables/enables lag checks
	 * @param null|bool $mode
	 * @return bool
	 */
	public function allowLagged( $mode = null );

	/**
	 * @return bool
	 */
	public function pingAll();

	/**
	 * Call a function with each open connection object
	 * @param callable $callback
	 * @param array $params
	 */
	public function forEachOpenConnection( $callback, array $params = [] );

	/**
	 * Call a function with each open connection object to a master
	 * @param callable $callback
	 * @param array $params
	 */
	public function forEachOpenMasterConnection( $callback, array $params = [] );

	/**
	 * Call a function with each open replica DB connection object
	 * @param callable $callback
	 * @param array $params
	 */
	public function forEachOpenReplicaConnection( $callback, array $params = [] );

	/**
	 * Get the hostname and lag time of the most-lagged replica DB
	 *
	 * This is useful for maintenance scripts that need to throttle their updates.
	 * May attempt to open connections to replica DBs on the default DB. If there is
	 * no lag, the maximum lag will be reported as -1.
	 *
	 * @param bool|string $wiki Wiki ID, or false for the default database
	 * @return array ( host, max lag, index of max lagged host )
	 */
	public function getMaxLag( $wiki = false );

	/**
	 * Get an estimate of replication lag (in seconds) for each server
	 *
	 * Results are cached for a short time in memcached/process cache
	 *
	 * Values may be "false" if replication is too broken to estimate
	 *
	 * @param string|bool $wiki
	 * @return int[] Map of (server index => float|int|bool)
	 */
	public function getLagTimes( $wiki = false );

	/**
	 * Get the lag in seconds for a given connection, or zero if this load
	 * balancer does not have replication enabled.
	 *
	 * This should be used in preference to Database::getLag() in cases where
	 * replication may not be in use, since there is no way to determine if
	 * replication is in use at the connection level without running
	 * potentially restricted queries such as SHOW SLAVE STATUS. Using this
	 * function instead of Database::getLag() avoids a fatal error in this
	 * case on many installations.
	 *
	 * @param IDatabase $conn
	 * @return int|bool Returns false on error
	 */
	public function safeGetLag( IDatabase $conn );

	/**
	 * Wait for a replica DB to reach a specified master position
	 *
	 * This will connect to the master to get an accurate position if $pos is not given
	 *
	 * @param IDatabase $conn Replica DB
	 * @param DBMasterPos|bool $pos Master position; default: current position
	 * @param integer|null $timeout Timeout in seconds [optional]
	 * @return bool Success
	 */
	public function safeWaitForMasterPos( IDatabase $conn, $pos = false, $timeout = null );

	/**
	 * Clear the cache for slag lag delay times
	 *
	 * This is only used for testing
	 */
	public function clearLagTimeCache();

	/**
	 * Set a callback via IDatabase::setTransactionListener() on
	 * all current and future master connections of this load balancer
	 *
	 * @param string $name Callback name
	 * @param callable|null $callback
	 */
	public function setTransactionListener( $name, callable $callback = null );
}
