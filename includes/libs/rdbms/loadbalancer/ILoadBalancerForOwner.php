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

use Exception;

/**
 * Internal interface for load balancer instances exposed to their owner
 *
 * Instances are either owned by an LBFactory object or owned by the caller that created
 * the instance using a constructor/factory function such as LBFactory::newMain().
 *
 * @internal Only for use within LBFactory/LoadMonitor/ChronologyProtector
 * @since 1.39
 * @ingroup Database
 */
interface ILoadBalancerForOwner extends ILoadBalancer {
	/** Manager of ILoadBalancer instances is running post-commit callbacks */
	public const STAGE_POSTCOMMIT_CALLBACKS = 'stage-postcommit-callbacks';
	/** Manager of ILoadBalancer instances is running post-rollback callbacks */
	public const STAGE_POSTROLLBACK_CALLBACKS = 'stage-postrollback-callbacks';

	/**
	 * @param array $params Parameter map with keys:
	 *  - servers : List of server configuration maps, starting with either the global master
	 *     or local datacenter multi-master, and optionally followed by local datacenter replicas.
	 *     Each server configuration map has the same format as the Database::factory() $params
	 *     argument, with the following additional optional fields:
	 *      - type: the DB type (sqlite, mysql, postgres,...)
	 *      - groupLoads: map of (group => weight for this server) [optional]
	 *      - max lag: per-server override of the "max lag" [optional]
	 *      - is static: whether the dataset is static *and* this server has a copy [optional]
	 *  - localDomain: A DatabaseDomain or domain ID string
	 *  - loadMonitor : LoadMonitor::__construct() parameters with "class" field [optional]
	 *  - readOnlyReason : Reason the primary server is read-only (false if not) [optional]
	 *  - srvCache : BagOStuff object for server cache [optional]
	 *  - wanCache : WANObjectCache object [optional]
	 *  - databaseFactory: DatabaseFactory object [optional]
	 *  - chronologyProtector: ChronologyProtector object [optional]
	 *  - defaultGroup: Default query group; the generic group if not specified [optional]
	 *  - hostname : The name of the current server [optional]
	 *  - cliMode: Whether the execution context is a CLI script [optional]
	 *  - profiler : Callback that takes a section name argument and returns
	 *      a ScopedCallback instance that ends the profile section in its destructor [optional]
	 *  - trxProfiler: TransactionProfiler instance [optional]
	 *  - logger: PSR-3 logger instance [optional]
	 *  - errorLogger : Callback that takes an Exception and logs it [optional]
	 *  - deprecationLogger: Callback to log a deprecation warning [optional]
	 *  - roundStage: STAGE_POSTCOMMIT_* class constant; for internal use [optional]
	 *  - clusterName: The name of the overall (single/multi-datacenter) cluster of servers
	 *     managing the dataset, regardless of 'servers' excluding replicas and
	 *     multi-masters from remote datacenter [optional]
	 *  - criticalSectionProvider: CriticalSectionProvider instance [optional]
	 *  - statsFactory: StatsFactory instance [optional]
	 */
	public function __construct( array $params );

	/**
	 * Close all connections and disable this load balancer
	 *
	 * Any attempt to open a new connection will result in a DBAccessError.
	 *
	 * @param string $fname Caller name @phan-mandatory-param
	 */
	public function disable( $fname = __METHOD__ );

	/**
	 * Close all open connections
	 *
	 * @param string $fname Caller name @phan-mandatory-param
	 *
	 */
	public function closeAll( $fname = __METHOD__ );

	/**
	 * Run pre-commit callbacks and defer execution of post-commit callbacks
	 *
	 * Use this only for multi-database commits
	 *
	 * @param string $fname Caller name @phan-mandatory-param
	 * @return int Number of pre-commit callbacks run (since 1.32)
	 * @since 1.37
	 */
	public function finalizePrimaryChanges( $fname = __METHOD__ );

	/**
	 * Perform all pre-commit checks for things like replication safety
	 *
	 * Use this only for multi-database commits
	 *
	 * @param int $maxWriteDuration : max write query duration time in seconds
	 * @param string $fname Caller name @phan-mandatory-param
	 * @throws DBTransactionError
	 * @since 1.37
	 */
	public function approvePrimaryChanges( int $maxWriteDuration, $fname = __METHOD__ );

	/**
	 * Flush any primary transaction snapshots and set DBO_TRX (if DBO_DEFAULT is set)
	 *
	 * The DBO_TRX setting will be reverted to the default in each of these methods:
	 *   - commitPrimaryChanges()
	 *   - rollbackPrimaryChanges()
	 * This allows for custom transaction rounds from any outer transaction scope.
	 *
	 * @param string $fname Caller name @phan-mandatory-param
	 * @throws DBExpectedError
	 * @since 1.37
	 */
	public function beginPrimaryChanges( $fname = __METHOD__ );

	/**
	 * Issue COMMIT on all open primary connections to flush changes and view snapshots
	 * @param string $fname Caller name @phan-mandatory-param
	 * @throws DBExpectedError
	 * @since 1.37
	 */
	public function commitPrimaryChanges( $fname = __METHOD__ );

	/**
	 * Consume and run all pending post-COMMIT/ROLLBACK callbacks and commit dangling transactions
	 *
	 * @param string $fname Caller name @phan-mandatory-param
	 * @return Exception|null The first exception or null if there were none
	 * @since 1.37
	 */
	public function runPrimaryTransactionIdleCallbacks( $fname = __METHOD__ );

	/**
	 * Run all recurring post-COMMIT/ROLLBACK listener callbacks
	 *
	 * @param string $fname Caller name @phan-mandatory-param
	 * @return Exception|null The first exception or null if there were none
	 * @since 1.37
	 */
	public function runPrimaryTransactionListenerCallbacks( $fname = __METHOD__ );

	/**
	 * Issue ROLLBACK only on primary, only if queries were done on connection
	 *
	 * @param string $fname Caller name @phan-mandatory-param
	 * @throws DBExpectedError
	 * @since 1.37
	 */
	public function rollbackPrimaryChanges( $fname = __METHOD__ );

	/**
	 * Release/destroy session-level named locks, table locks, and temp tables
	 *
	 * Only call this function right after calling rollbackPrimaryChanges()
	 *
	 * @param string $fname Caller name @phan-mandatory-param
	 * @throws DBExpectedError
	 * @since 1.38
	 */
	public function flushPrimarySessions( $fname = __METHOD__ );

	/**
	 * Commit all replica DB transactions so as to flush any REPEATABLE-READ or SSI snapshots
	 *
	 * @param string $fname Caller name @phan-mandatory-param
	 */
	public function flushReplicaSnapshots( $fname = __METHOD__ );

	/**
	 * Commit all primary DB transactions so as to flush any REPEATABLE-READ or SSI snapshots
	 *
	 * An error will be thrown if a connection has pending writes or callbacks
	 *
	 * @param string $fname Caller name @phan-mandatory-param
	 * @since 1.37
	 */
	public function flushPrimarySnapshots( $fname = __METHOD__ );

	/**
	 * Get the list of callers that have pending primary changes
	 *
	 * @return string[] List of method names
	 * @since 1.37
	 */
	public function pendingPrimaryChangeCallers();

	/**
	 * Set a new table prefix for the existing local domain ID for testing
	 *
	 * @param string $prefix
	 * @since 1.33
	 */
	public function setLocalDomainPrefix( $prefix );

	/**
	 * Reconfigure using the given config array.
	 * If the config changed, this invalidates all existing connections.
	 *
	 * @warning This must only be called in top level code, typically via
	 * LBFactory::reconfigure.
	 *
	 * @since 1.39
	 *
	 * @param array $params A configuration array, using the same structure as
	 *        the one passed to the LoadBalancer's constructor.
	 */
	public function reconfigure( array $params );

	/**
	 * Close all connection and redefine the local domain for testing or schema creation
	 *
	 * @param DatabaseDomain|string $domain
	 * @since 1.33
	 */
	public function redefineLocalDomain( $domain );

	/**
	 * @return bool Whether a primary connection is already open
	 * @since 1.37
	 */
	public function hasPrimaryConnection();

	/**
	 * Convert certain index names to alternative names before querying the DB
	 *
	 * Note that this applies to indexes regardless of the table they belong to.
	 *
	 * This can be employed when an index was renamed X => Y in code, but the new Y-named
	 * indexes were not yet built on all DBs. After all the Y-named ones are added by the DBA,
	 * the aliases can be removed, and then the old X-named indexes dropped.
	 *
	 * @param string[] $aliases
	 * @since 1.31
	 */
	public function setIndexAliases( array $aliases );

	/**
	 * Get the last time that a tracked connection was used to commit a write
	 *
	 * @internal Should only be called from the rdbms library.
	 *
	 * @return float|null UNIX timestamp, or, false (if no writes were committed)
	 * @since 1.37
	 */
	public function lastPrimaryChangeTimestamp();

	/**
	 * Set the primary wait position and wait for ALL replica DBs to catch up to it
	 *
	 * This method is only intended for use a throttling mechanism for high-volume updates.
	 * Unlike waitFor(), failure does not effect laggedReplicaUsed().
	 *
	 * @param DBPrimaryPos $pos Primary position
	 * @param int|null $timeout Max seconds to wait; default is mWaitTimeout
	 * @return bool Success (able to connect and no timeouts reached)
	 */
	public function waitForAll( DBPrimaryPos $pos, $timeout = null );

	/**
	 * Whether a highly "lagged" replica database connection was queried.
	 *
	 * @note This method will never cause a new DB connection
	 * @return bool
	 */
	public function laggedReplicaUsed();
}
