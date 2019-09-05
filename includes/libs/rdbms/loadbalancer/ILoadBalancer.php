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
 */
namespace Wikimedia\Rdbms;

use Exception;
use LogicException;
use InvalidArgumentException;

/**
 * Database cluster connection, tracking, load balancing, and transaction manager interface
 *
 * A "cluster" is considered to be one master database and zero or more replica databases.
 * Typically, the replica DBs replicate from the master asynchronously. The first node in the
 * "servers" configuration array is always considered the "master". However, this class can still
 * be used when all or some of the "replica" DBs are multi-master peers of the master or even
 * when all the DBs are non-replicating clones of each other holding read-only data. Thus, the
 * role of "master" is in some cases merely nominal.
 *
 * By default, each DB server uses DBO_DEFAULT for its 'flags' setting, unless explicitly set
 * otherwise in configuration. DBO_DEFAULT behavior depends on whether 'cliMode' is set:
 *   - In CLI mode, the flag has no effect with regards to LoadBalancer.
 *   - In non-CLI mode, the flag causes implicit transactions to be used; the first query on
 *     a database starts a transaction on that database. The transactions are meant to remain
 *     pending until either commitMasterChanges() or rollbackMasterChanges() is called. The
 *     application must have some point where it calls commitMasterChanges() near the end of
 *     the PHP request.
 * Every iteration of beginMasterChanges()/commitMasterChanges() is called a "transaction round".
 * Rounds are useful on the master DB connections because they make single-DB (and by and large
 * multi-DB) updates in web requests all-or-nothing. Also, transactions on replica DBs are useful
 * when REPEATABLE-READ or SERIALIZABLE isolation is used because all foriegn keys and constraints
 * hold across separate queries in the DB transaction since the data appears within a consistent
 * point-in-time snapshot.
 *
 * The typical caller will use LoadBalancer::getConnection( DB_* ) to yield a live database
 * connection handle. The choice of which DB server to use is based on pre-defined loads for
 * weighted random selection, adjustments thereof by LoadMonitor, and the amount of replication
 * lag on each DB server. Lag checks might cause problems in certain setups, so they should be
 * tuned in the server configuration maps as follows:
 *   - Master + N Replica(s): set 'max lag' to an appropriate threshold for avoiding any database
 *      lagged by this much or more. If all DBs are this lagged, then the load balancer considers
 *      the cluster to be read-only.
 *   - Galera Cluster: Seconds_Behind_Master will be 0, so there probably is nothing to tune.
 *      Note that lag is still possible depending on how wsrep-sync-wait is set server-side.
 *   - Read-only archive clones: set 'is static' in the server configuration maps. This will
 *      treat all such DBs as having 0 lag.
 *   - Externally updated dataset clones: set 'is static' in the server configuration maps.
 *      This will treat all such DBs as having 0 lag.
 *   - SQL load balancing proxy: any proxy should handle lag checks on its own, so the 'max lag'
 *      parameter should probably be set to INF in the server configuration maps. This will make
 *      the load balancer ignore whatever it detects as the lag of the logical replica is (which
 *      would probably just randomly bounce around).
 *
 * If using a SQL proxy service, it would probably be best to have two proxy hosts for the
 * load balancer to talk to. One would be the 'host' of the master server entry and another for
 * the (logical) replica server entry. The proxy could map the load balancer's "replica" DB to
 * any number of physical replica DBs.
 *
 * @since 1.28
 * @ingroup Database
 */
interface ILoadBalancer {
	/** @var int Request a replica DB connection */
	const DB_REPLICA = -1;
	/** @var int Request a master DB connection */
	const DB_MASTER = -2;

	/** @var string Domain specifier when no specific database needs to be selected */
	const DOMAIN_ANY = '';
	/** @var string The generic query group */
	const GROUP_GENERIC = '';

	/** @var int DB handle should have DBO_TRX disabled and the caller will leave it as such */
	const CONN_TRX_AUTOCOMMIT = 1;
	/** @var int Return null on connection failure instead of throwing an exception */
	const CONN_SILENCE_ERRORS = 2;
	/** @var int Caller is requesting the master DB server for possibly writes */
	const CONN_INTENT_WRITABLE = 4;
	/** @var int Bypass and update any server-side read-only mode state cache */
	const CONN_REFRESH_READ_ONLY = 8;

	/** @var string Manager of ILoadBalancer instances is running post-commit callbacks */
	const STAGE_POSTCOMMIT_CALLBACKS = 'stage-postcommit-callbacks';
	/** @var string Manager of ILoadBalancer instances is running post-rollback callbacks */
	const STAGE_POSTROLLBACK_CALLBACKS = 'stage-postrollback-callbacks';

	/**
	 * Construct a manager of IDatabase connection objects
	 *
	 * @param array $params Parameter map with keys:
	 *  - servers : List of server info structures
	 *  - localDomain: A DatabaseDomain or domain ID string
	 *  - loadMonitor : Name of a class used to fetch server lag and load
	 *  - readOnlyReason : Reason the master DB is read-only if so [optional]
	 *  - waitTimeout : Maximum time to wait for replicas for consistency [optional]
	 *  - maxLag: Try to avoid DB replicas with lag above this many seconds [optional]
	 *  - srvCache : BagOStuff object for server cache [optional]
	 *  - wanCache : WANObjectCache object [optional]
	 *  - chronologyCallback: Callback to run before the first connection attempt [optional]
	 *  - defaultGroup: Default query group; the generic group if not specified [optional]
	 *  - hostname : The name of the current server [optional]
	 *  - cliMode: Whether the execution context is a CLI script [optional]
	 *  - profiler : Class name or instance with profileIn()/profileOut() methods [optional]
	 *  - trxProfiler: TransactionProfiler instance [optional]
	 *  - replLogger: PSR-3 logger instance [optional]
	 *  - connLogger: PSR-3 logger instance [optional]
	 *  - queryLogger: PSR-3 logger instance [optional]
	 *  - perfLogger: PSR-3 logger instance [optional]
	 *  - errorLogger : Callback that takes an Exception and logs it [optional]
	 *  - deprecationLogger: Callback to log a deprecation warning [optional]
	 *  - roundStage: STAGE_POSTCOMMIT_* class constant; for internal use [optional]
	 *  - ownerId: integer ID of an LBFactory instance that manages this instance [optional]
	 * @throws InvalidArgumentException
	 */
	public function __construct( array $params );

	/**
	 * Get the local (and default) database domain ID of connection handles
	 *
	 * @see DatabaseDomain
	 * @return string Database domain ID; this specifies DB name, schema, and table prefix
	 * @since 1.31
	 */
	public function getLocalDomainID();

	/**
	 * @param DatabaseDomain|string|bool $domain Database domain
	 * @return string Value of $domain if it is foreign or the local domain otherwise
	 * @since 1.32
	 */
	public function resolveDomainID( $domain );

	/**
	 * Close all connection and redefine the local domain for testing or schema creation
	 *
	 * @param DatabaseDomain|string $domain
	 * @since 1.33
	 */
	public function redefineLocalDomain( $domain );

	/**
	 * Indicate whether the tables on this domain are only temporary tables for testing
	 *
	 * In "temporary tables mode", the ILoadBalancer::CONN_TRX_AUTOCOMMIT flag is ignored
	 *
	 * @param bool $value
	 * @param string $domain
	 * @return bool Whether "temporary tables mode" was active
	 * @since 1.34
	 */
	public function setTempTablesOnlyMode( $value, $domain );

	/**
	 * Get the server index of the reader connection for a given group
	 *
	 * This takes into account load ratios and lag times. It should return a consistent
	 * index during the life time of the load balancer. This initially checks replica DBs
	 * for connectivity to avoid returning an unusable server. This means that connections
	 * might be attempted by calling this method (usally one at the most but possibly more).
	 * Subsequent calls with the same $group will not need to make new connection attempts
	 * since the acquired connection for each group is preserved.
	 *
	 * @param string|bool $group Query group or false for the generic group
	 * @param string|bool $domain DB domain ID or false for the local domain
	 * @return int|bool Returns false if no live handle can be obtained
	 */
	public function getReaderIndex( $group = false, $domain = false );

	/**
	 * Set the master position to reach before the next generic group DB handle query
	 *
	 * If a generic replica DB connection is already open then this immediately waits
	 * for that DB to catch up to the specified replication position. Otherwise, it will
	 * do so once such a connection is opened.
	 *
	 * If a timeout happens when waiting, then getLaggedReplicaMode()/laggedReplicaUsed()
	 * will return true.
	 *
	 * @param DBMasterPos|bool $pos Master position or false
	 */
	public function waitFor( $pos );

	/**
	 * Set the master wait position and wait for a generic replica DB to catch up to it
	 *
	 * This can be used a faster proxy for waitForAll()
	 *
	 * @param DBMasterPos|bool $pos Master position or false
	 * @param int|null $timeout Max seconds to wait; default is mWaitTimeout
	 * @return bool Success (able to connect and no timeouts reached)
	 */
	public function waitForOne( $pos, $timeout = null );

	/**
	 * Set the master wait position and wait for ALL replica DBs to catch up to it
	 *
	 * @param DBMasterPos|bool $pos Master position or false
	 * @param int|null $timeout Max seconds to wait; default is mWaitTimeout
	 * @return bool Success (able to connect and no timeouts reached)
	 */
	public function waitForAll( $pos, $timeout = null );

	/**
	 * Get any open connection to a given server index, local or foreign
	 *
	 * Use CONN_TRX_AUTOCOMMIT to only look for connections opened with that flag.
	 * Avoid the use of transaction methods like IDatabase::begin() or IDatabase::startAtomic()
	 * on any such connections.
	 *
	 * @param int $i Server index or DB_MASTER/DB_REPLICA
	 * @param int $flags Bitfield of CONN_* class constants
	 * @return Database|bool False if no such connection is open
	 */
	public function getAnyOpenConnection( $i, $flags = 0 );

	/**
	 * Get a live handle for a real or virtual (DB_MASTER/DB_REPLICA) server index
	 *
	 * The server index, $i, can be one of the following:
	 *   - DB_REPLICA: a server index will be selected by the load balancer based on read
	 *      weight, connectivity, and replication lag. Note that the master server might be
	 *      configured with read weight. If $groups is empty then it means "the generic group",
	 *      in which case all servers defined with read weight will be considered. Additional
	 *      query groups can be configured, having their own list of server indexes and read
	 *      weights. If a query group list is provided in $groups, then each recognized group
	 *      will be tried, left-to-right, until server index selection succeeds or all groups
	 *      have been tried, in which case the generic group will be tried.
	 *   - DB_MASTER: the master server index will be used; the same as getWriterIndex().
	 *      The value of $groups should be [] when using this server index.
	 *   - Specific server index: a positive integer can be provided to use the server with
	 *      that index. An error will be thrown in no such server index is recognized. This
	 *      server selection method is usually only useful for internal load balancing logic.
	 *      The value of $groups should be [] when using a specific server index.
	 *
	 * Handles acquired by this method, getConnectionRef(), getLazyConnectionRef(), and
	 * getMaintenanceConnectionRef() use the same set of shared connection pools. Callers that
	 * get a *local* DB domain handle for the same server will share one handle for all of those
	 * callers using CONN_TRX_AUTOCOMMIT (via $flags) and one handle for all of those callers not
	 * using CONN_TRX_AUTOCOMMIT. Callers that get a *foreign* DB domain handle (via $domain) will
	 * share any handle that has the right CONN_TRX_AUTOCOMMIT mode and is already on the right
	 * DB domain. Otherwise, one of the "free for reuse" handles will be claimed or a new handle
	 * will be made if there are none.
	 *
	 * Handle sharing is particularly useful when callers get local DB domain (the default),
	 * transaction round aware (the default), DB_MASTER handles. All such callers will operate
	 * within a single database transaction as a consequence. Handle sharing is also useful when
	 * callers get local DB domain (the default), transaction round aware (the default), samely
	 * query grouped (the default), DB_REPLICA handles. All such callers will operate within a
	 * single database transaction as a consequence.
	 *
	 * Calling functions that use $domain must call reuseConnection() once the last query of the
	 * function is executed. This lets the load balancer share this handle with other callers
	 * requesting connections on different database domains.
	 *
	 * Use CONN_TRX_AUTOCOMMIT to use a separate pool of only auto-commit handles. This flag
	 * is ignored for databases with ATTR_DB_LEVEL_LOCKING (e.g. sqlite) in order to avoid
	 * deadlocks. getServerAttributes() can be used to check such attributes beforehand. Avoid
	 * using IDatabase::begin() and IDatabase::commit() on such handles. If it is not possible
	 * to avoid using methods like IDatabase::startAtomic() and IDatabase::endAtomic(), callers
	 * should at least make sure that the atomic sections are closed on failure via try/catch
	 * and IDatabase::cancelAtomic().
	 *
	 * @see ILoadBalancer::reuseConnection()
	 * @see ILoadBalancer::getServerAttributes()
	 *
	 * @param int $i Server index (overrides $groups) or DB_MASTER/DB_REPLICA
	 * @param string[]|string $groups Query group(s) in preference order; [] for the default group
	 * @param string|bool $domain DB domain ID or false for the local domain
	 * @param int $flags Bitfield of CONN_* class constants
	 *
	 * @note This method throws DBAccessError if ILoadBalancer::disable() was called
	 *
	 * @return IDatabase|bool This returns false on failure if CONN_SILENCE_ERRORS is set
	 * @throws DBError If no live handle can be obtained and CONN_SILENCE_ERRORS is not set
	 * @throws DBAccessError If disable() was previously called
	 * @throws InvalidArgumentException
	 */
	public function getConnection( $i, $groups = [], $domain = false, $flags = 0 );

	/**
	 * Get a live handle for a server index
	 *
	 * This is a simpler version of getConnection() that does not accept virtual server
	 * indexes (e.g. DB_MASTER/DB_REPLICA), does not assure that master DB handles have
	 * read-only mode when there is high replication lag, and can only trigger attempts
	 * to connect to a single server (the one with the specified server index).
	 *
	 * @see ILoadBalancer::getConnection()
	 *
	 * @param int $i Specific server index
	 * @param string $domain Resolved DB domain
	 * @param int $flags Bitfield of class CONN_* constants
	 * @return IDatabase|bool
	 */
	public function getServerConnection( $i, $domain, $flags = 0 );

	/**
	 * Mark a live handle as being available for reuse under a different database domain
	 *
	 * This mechanism is reference-counted, and must be called the same number of times as
	 * getConnection() to work. Never call this on handles acquired via getConnectionRef(),
	 * getLazyConnectionRef(), and getMaintenanceConnectionRef(), as they already manage
	 * the logic of calling this method when they fall out of scope in PHP.
	 *
	 * @see ILoadBalancer::getConnection()
	 *
	 * @param IDatabase $conn
	 * @throws LogicException
	 */
	public function reuseConnection( IDatabase $conn );

	/**
	 * Get a live database handle reference for a real or virtual (DB_MASTER/DB_REPLICA) server index
	 *
	 * The CONN_TRX_AUTOCOMMIT flag is ignored for databases with ATTR_DB_LEVEL_LOCKING
	 * (e.g. sqlite) in order to avoid deadlocks. getServerAttributes()
	 * can be used to check such flags beforehand. Avoid the use of begin() or startAtomic()
	 * on any CONN_TRX_AUTOCOMMIT connections.
	 *
	 * @see ILoadBalancer::getConnection() for parameter information
	 *
	 * @param int $i Server index or DB_MASTER/DB_REPLICA
	 * @param string[]|string $groups Query group(s) in preference order; [] for the default group
	 * @param string|bool $domain DB domain ID or false for the local domain
	 * @param int $flags Bitfield of CONN_* class constants (e.g. CONN_TRX_AUTOCOMMIT)
	 * @return DBConnRef
	 */
	public function getConnectionRef( $i, $groups = [], $domain = false, $flags = 0 );

	/**
	 * Get a database handle reference for a real or virtual (DB_MASTER/DB_REPLICA) server index
	 *
	 * The handle's methods simply proxy to those of an underlying IDatabase handle which
	 * takes care of the actual connection and query logic.
	 *
	 * The CONN_TRX_AUTOCOMMIT flag is ignored for databases with ATTR_DB_LEVEL_LOCKING
	 * (e.g. sqlite) in order to avoid deadlocks. getServerAttributes()
	 * can be used to check such flags beforehand. Avoid the use of begin() or startAtomic()
	 * on any CONN_TRX_AUTOCOMMIT connections.
	 *
	 * @see ILoadBalancer::getConnection() for parameter information
	 *
	 * @param int $i Server index or DB_MASTER/DB_REPLICA
	 * @param string[]|string $groups Query group(s) in preference order; [] for the default group
	 * @param string|bool $domain DB domain ID or false for the local domain
	 * @param int $flags Bitfield of CONN_* class constants (e.g. CONN_TRX_AUTOCOMMIT)
	 * @return DBConnRef
	 */
	public function getLazyConnectionRef( $i, $groups = [], $domain = false, $flags = 0 );

	/**
	 * Get a live database handle for a real or virtual (DB_MASTER/DB_REPLICA) server index
	 * that can be used for data migrations and schema changes
	 *
	 * The handle's methods simply proxy to those of an underlying IDatabase handle which
	 * takes care of the actual connection and query logic.
	 *
	 * The CONN_TRX_AUTOCOMMIT flag is ignored for databases with ATTR_DB_LEVEL_LOCKING
	 * (e.g. sqlite) in order to avoid deadlocks. getServerAttributes()
	 * can be used to check such flags beforehand. Avoid the use of begin() or startAtomic()
	 * on any CONN_TRX_AUTOCOMMIT connections.
	 *
	 * @see ILoadBalancer::getConnection() for parameter information
	 *
	 * @param int $i Server index or DB_MASTER/DB_REPLICA
	 * @param string[]|string $groups Query group(s) in preference order; [] for the default group
	 * @param string|bool $domain DB domain ID or false for the local domain
	 * @param int $flags Bitfield of CONN_* class constants (e.g. CONN_TRX_AUTOCOMMIT)
	 * @return MaintainableDBConnRef
	 */
	public function getMaintenanceConnectionRef( $i, $groups = [], $domain = false, $flags = 0 );

	/**
	 * Get the server index of the master server
	 *
	 * @return int
	 */
	public function getWriterIndex();

	/**
	 * Get the number of servers defined in configuration
	 *
	 * @return int
	 */
	public function getServerCount();

	/**
	 * Whether there are any replica servers configured
	 *
	 * This counts both servers using streaming replication from the master server and
	 * servers that just have a clone of the static dataset found on the master server
	 *
	 * @return int
	 * @since 1.34
	 */
	public function hasReplicaServers();

	/**
	 * Whether any replica servers use streaming replication from the master server
	 *
	 * Generally this is one less than getServerCount(), though it might otherwise
	 * return a lower number if some of the servers are configured with "is static".
	 * That flag is used when both the server has no active replication setup and the
	 * dataset is either read-only or occasionally updated out-of-band. For example,
	 * a script might import a new geographic information dataset each week by writing
	 * it to each server and later directing the application to use the new version.
	 *
	 * It is possible for some replicas to be configured with "is static" but not
	 * others, though it generally should either be set for all or none of the replicas.
	 *
	 * If this returns zero, this means that there is generally no reason to execute
	 * replication wait logic for session consistency and lag reduction.
	 *
	 * @return int
	 * @since 1.34
	 */
	public function hasStreamingReplicaServers();

	/**
	 * Get the host name or IP address of the server with the specified index
	 *
	 * @param int $i
	 * @return string Readable name if available or IP/host otherwise
	 */
	public function getServerName( $i );

	/**
	 * Return the server info structure for a given index or false if the index is invalid.
	 * @param int $i
	 * @return array|bool
	 * @since 1.31
	 */
	public function getServerInfo( $i );

	/**
	 * Get DB type of the server with the specified index
	 *
	 * @param int $i
	 * @return string One of (mysql,postgres,sqlite,...) or "unknown" for bad indexes
	 * @since 1.30
	 */
	public function getServerType( $i );

	/**
	 * @param int $i Server index
	 * @return array (Database::ATTRIBUTE_* constant => value) for all such constants
	 * @since 1.31
	 */
	public function getServerAttributes( $i );

	/**
	 * Get the current master replication position
	 *
	 * @return DBMasterPos|bool Returns false if not applicable
	 * @throws DBError
	 */
	public function getMasterPos();

	/**
	 * Get the highest DB replication position for chronology control purposes
	 *
	 * If there is only a master server then this returns false. If replication is present
	 * and correctly configured, then this returns the highest replication position of any
	 * server with an open connection. That position can later be passed to waitFor() on a
	 * new load balancer instance to make sure that queries on the new connections see data
	 * at least as up-to-date as queries (prior to this method call) on the old connections.
	 *
	 * This can be useful for implementing session consistency, where the session
	 * will be resumed accross multiple HTTP requests or CLI script instances.
	 *
	 * @return DBMasterPos|bool Replication position or false if not applicable
	 * @since 1.34
	 */
	public function getReplicaResumePos();

	/**
	 * Close all connections and disable this load balancer
	 *
	 * Any attempt to open a new connection will result in a DBAccessError.
	 *
	 * @param string $fname Caller name
	 * @param int|null $owner ID of the calling instance (e.g. the LBFactory ID)
	 */
	public function disable( $fname = __METHOD__, $owner = null );

	/**
	 * Close all open connections
	 *
	 * @param string $fname Caller name
	 * @param int|null $owner ID of the calling instance (e.g. the LBFactory ID)
	 */
	public function closeAll( $fname = __METHOD__, $owner = null );

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
	 * @param int|null $owner ID of the calling instance (e.g. the LBFactory ID)
	 * @throws DBExpectedError
	 */
	public function commitAll( $fname = __METHOD__, $owner = null );

	/**
	 * Run pre-commit callbacks and defer execution of post-commit callbacks
	 *
	 * Use this only for mutli-database commits
	 *
	 * @param string $fname Caller name
	 * @param int|null $owner ID of the calling instance (e.g. the LBFactory ID)
	 * @return int Number of pre-commit callbacks run (since 1.32)
	 */
	public function finalizeMasterChanges( $fname = __METHOD__, $owner = null );

	/**
	 * Perform all pre-commit checks for things like replication safety
	 *
	 * Use this only for mutli-database commits
	 *
	 * @param array $options Includes:
	 *   - maxWriteDuration : max write query duration time in seconds
	 * @param string $fname Caller name
	 * @param int|null $owner ID of the calling instance (e.g. the LBFactory ID)
	 * @throws DBTransactionError
	 */
	public function approveMasterChanges( array $options, $fname, $owner = null );

	/**
	 * Flush any master transaction snapshots and set DBO_TRX (if DBO_DEFAULT is set)
	 *
	 * The DBO_TRX setting will be reverted to the default in each of these methods:
	 *   - commitMasterChanges()
	 *   - rollbackMasterChanges()
	 *   - commitAll()
	 * This allows for custom transaction rounds from any outer transaction scope.
	 *
	 * @param string $fname Caller name
	 * @param int|null $owner ID of the calling instance (e.g. the LBFactory ID)
	 * @throws DBExpectedError
	 */
	public function beginMasterChanges( $fname = __METHOD__, $owner = null );

	/**
	 * Issue COMMIT on all open master connections to flush changes and view snapshots
	 * @param string $fname Caller name
	 * @param int|null $owner ID of the calling instance (e.g. the LBFactory ID)
	 * @throws DBExpectedError
	 */
	public function commitMasterChanges( $fname = __METHOD__, $owner = null );

	/**
	 * Consume and run all pending post-COMMIT/ROLLBACK callbacks and commit dangling transactions
	 *
	 * @param string $fname Caller name
	 * @param int|null $owner ID of the calling instance (e.g. the LBFactory ID)
	 * @return Exception|null The first exception or null if there were none
	 */
	public function runMasterTransactionIdleCallbacks( $fname = __METHOD__, $owner = null );

	/**
	 * Run all recurring post-COMMIT/ROLLBACK listener callbacks
	 *
	 * @param string $fname Caller name
	 * @param int|null $owner ID of the calling instance (e.g. the LBFactory ID)
	 * @return Exception|null The first exception or null if there were none
	 */
	public function runMasterTransactionListenerCallbacks( $fname = __METHOD__, $owner = null );

	/**
	 * Issue ROLLBACK only on master, only if queries were done on connection
	 * @param string $fname Caller name
	 * @param int|null $owner ID of the calling instance (e.g. the LBFactory ID)
	 * @throws DBExpectedError
	 */
	public function rollbackMasterChanges( $fname = __METHOD__, $owner = null );

	/**
	 * Commit all replica DB transactions so as to flush any REPEATABLE-READ or SSI snapshots
	 *
	 * @param string $fname Caller name
	 * @param int|null $owner ID of the calling instance (e.g. the LBFactory ID)
	 */
	public function flushReplicaSnapshots( $fname = __METHOD__, $owner = null );

	/**
	 * Commit all master DB transactions so as to flush any REPEATABLE-READ or SSI snapshots
	 *
	 * An error will be thrown if a connection has pending writes or callbacks
	 *
	 * @param string $fname Caller name
	 * @param int|null $owner ID of the calling instance (e.g. the LBFactory ID)
	 */
	public function flushMasterSnapshots( $fname = __METHOD__, $owner = null );

	/**
	 * @return bool Whether a master connection is already open
	 */
	public function hasMasterConnection();

	/**
	 * Whether there are pending changes or callbacks in a transaction by this thread
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
	 * @param float|null $age How many seconds ago is "recent" [defaults to mWaitTimeout]
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
	 * @param string|bool $domain DB domain ID or false for the local domain
	 * @return bool Whether the database for generic connections this request is highly "lagged"
	 */
	public function getLaggedReplicaMode( $domain = false );

	/**
	 * Checks whether the database for generic connections this request was both:
	 *   - a) Already choosen due to a prior connection attempt
	 *   - b) Considered highly "lagged"
	 *
	 * @note This method will never cause a new DB connection
	 * @return bool
	 */
	public function laggedReplicaUsed();

	/**
	 * @note This method may trigger a DB connection if not yet done
	 * @param string|bool $domain DB domain ID or false for the local domain
	 * @return string|bool Reason the master is read-only or false if it is not
	 */
	public function getReadOnlyReason( $domain = false );

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
	 * Get the hostname and lag time of the most-lagged replica server
	 *
	 * This is useful for maintenance scripts that need to throttle their updates.
	 * May attempt to open connections to replica DBs on the default DB. If there is
	 * no lag, the maximum lag will be reported as -1.
	 *
	 * @param bool|string $domain Domain ID or false for the default database
	 * @return array ( host, max lag, index of max lagged host )
	 */
	public function getMaxLag( $domain = false );

	/**
	 * Get an estimate of replication lag (in seconds) for each server
	 *
	 * Results are cached for a short time in memcached/process cache
	 *
	 * Values may be "false" if replication is too broken to estimate
	 *
	 * @param string|bool $domain
	 * @return int[] Map of (server index => float|int|bool)
	 */
	public function getLagTimes( $domain = false );

	/**
	 * Wait for a replica DB to reach a specified master position
	 *
	 * If $conn is not a replica server connection, then this will return true.
	 * Otherwise, if $pos is not provided, this will connect to the master server
	 * to get an accurate position.
	 *
	 * @param IDatabase $conn Replica DB
	 * @param DBMasterPos|bool $pos Master position; default: current position
	 * @param int $timeout Timeout in seconds [optional]
	 * @return bool Success
	 * @since 1.34
	 */
	public function waitForMasterPos( IDatabase $conn, $pos = false, $timeout = 10 );

	/**
	 * Set a callback via IDatabase::setTransactionListener() on
	 * all current and future master connections of this load balancer
	 *
	 * @param string $name Callback name
	 * @param callable|null $callback
	 */
	public function setTransactionListener( $name, callable $callback = null );

	/**
	 * Set a new table prefix for the existing local domain ID for testing
	 *
	 * @param string $prefix
	 * @since 1.33
	 */
	public function setLocalDomainPrefix( $prefix );

	/**
	 * Make certain table names use their own database, schema, and table prefix
	 * when passed into SQL queries pre-escaped and without a qualified database name
	 *
	 * For example, "user" can be converted to "myschema.mydbname.user" for convenience.
	 * Appearances like `user`, somedb.user, somedb.someschema.user will used literally.
	 *
	 * Calling this twice will completely clear any old table aliases. Also, note that
	 * callers are responsible for making sure the schemas and databases actually exist.
	 *
	 * @param array[] $aliases Map of (table => (dbname, schema, prefix) map)
	 */
	public function setTableAliases( array $aliases );

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
}
