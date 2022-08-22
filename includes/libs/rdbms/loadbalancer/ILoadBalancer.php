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

/**
 * Create and track the database connections and transactions for a given database cluster.
 *
 * This class is a delegate to ILBFactory for a given database cluster (separated for the
 * LBFactoryMulti use case).
 *
 * A "cluster" is defined as a primary database with zero or more replica databases.
 * Typically, the replica DBs replicate from the primary asynchronously. The first node in the
 * "servers" configuration array is always considered the "primary". However, this class can still
 * be used when all or some of the "replica" DBs are multi-primary peers of the primary or even
 * when all the DBs are non-replicating clones of each other holding read-only data. Thus, the
 * role of "primary" is in some cases merely nominal.
 *
 * By default, each DB server uses DBO_DEFAULT for its 'flags' setting, unless explicitly set
 * otherwise in configuration. DBO_DEFAULT behavior depends on whether 'cliMode' is set:
 *   - In CLI mode, the flag has no effect with regards to LoadBalancer.
 *   - In non-CLI mode, the flag causes implicit transactions to be used; the first query on
 *     a database starts a transaction on that database. The transactions are meant to remain
 *     pending until either commitPrimaryChanges() or rollbackPrimaryChanges() is called. The
 *     application must have some point where it calls commitPrimaryChanges() near the end of
 *     the PHP request.
 * Every iteration of beginPrimaryChanges()/commitPrimaryChanges() is called a "transaction round".
 * Rounds are useful on the primary DB connections because they make single-DB (and by and large
 * multi-DB) updates in web requests all-or-nothing. Also, transactions on replica DBs are useful
 * when REPEATABLE-READ or SERIALIZABLE isolation is used because all foreign keys and constraints
 * hold across separate queries in the DB transaction since the data appears within a consistent
 * point-in-time snapshot.
 *
 * The typical caller will use LoadBalancer::getConnection( DB_* ) to yield a database
 * connection handle. The choice of which DB server to use is based on pre-defined loads for
 * weighted random selection, adjustments thereof by LoadMonitor, and the amount of replication
 * lag on each DB server. Lag checks might cause problems in certain setups, so they should be
 * tuned in the server configuration maps as follows:
 *   - Primary + N Replica(s): set 'max lag' to an appropriate threshold for avoiding any database
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
 * load balancer to talk to. One would be the 'host' of the primary server entry and another for
 * the (logical) replica server entry. The proxy could map the load balancer's "replica" DB to
 * any number of physical replica DBs.
 *
 * @since 1.28
 * @ingroup Database
 */
interface ILoadBalancer {
	/** Request a replica DB connection */
	public const DB_REPLICA = -1;
	/**
	 * Request a primary, write-enabled DB connection
	 * @since 1.36
	 */
	public const DB_PRIMARY = -2;

	// phpcs:disable MediaWiki.Usage.DeprecatedConstantUsage.DB_MASTER
	/**
	 * Request a primary, write-enabled DB connection
	 * @deprecated since 1.36, Use DB_PRIMARY instead
	 */
	public const DB_MASTER = self::DB_PRIMARY;
	// phpcs:enable MediaWiki.Usage.DeprecatedConstantUsage.DB_MASTER

	/** Domain specifier when no specific database needs to be selected */
	public const DOMAIN_ANY = '';
	/** The generic query group */
	public const GROUP_GENERIC = '';

	/** DB handle should have DBO_TRX disabled and the caller will leave it as such */
	public const CONN_TRX_AUTOCOMMIT = 1;
	/** Return null on connection failure instead of throwing an exception */
	public const CONN_SILENCE_ERRORS = 2;
	/** Caller is requesting the primary DB server for possibly writes */
	public const CONN_INTENT_WRITABLE = 4;
	/** Bypass and update any server-side read-only mode state cache */
	public const CONN_REFRESH_READ_ONLY = 8;

	/**
	 * Get the logical name of the database cluster
	 *
	 * This is useful for identifying a cluster or replicated dataset, even when:
	 *  - The primary server is sometimes swapped with another one
	 *  - The cluster/dataset is replicated among multiple datacenters, with one "primary"
	 *    datacenter having the writable primary server and the other datacenters having a
	 *    read-only replica in the "primary" server slot
	 *  - The dataset is replicated among multiple datacenters, via circular replication,
	 *    with each datacenter having its own "primary" server
	 *
	 * @return string
	 * @since 1.36
	 */
	public function getClusterName(): string;

	/**
	 * Get the local (and default) database domain ID of connection handles
	 *
	 * @see DatabaseDomain
	 * @return string Database domain ID; this specifies DB name, schema, and table prefix
	 * @since 1.31
	 */
	public function getLocalDomainID(): string;

	/**
	 * @param DatabaseDomain|string|false $domain Database domain
	 * @return string Value of $domain if it is foreign or the local domain otherwise
	 * @since 1.32
	 */
	public function resolveDomainID( $domain ): string;

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
	 * Get the specific server index of the reader connection for a given group
	 *
	 * This takes into account load ratios and lag times. It should return a consistent
	 * index during the life time of the load balancer. This initially checks replica DBs
	 * for connectivity to avoid returning an unusable server. This means that connections
	 * might be attempted by calling this method (usually one at the most but possibly more).
	 * Subsequent calls with the same $group will not need to make new connection attempts
	 * since the acquired connection for each group is preserved.
	 *
	 * @param string|false $group Query group or false for the generic group
	 * @param string|false $domain DB domain ID or false for the local domain
	 * @return int|false Specific server index, or false if no DB handle can be obtained
	 */
	public function getReaderIndex( $group = false, $domain = false );

	/**
	 * Set the primary position to reach before the next generic group DB query
	 *
	 * If a generic replica DB connection is already open then this immediately waits
	 * for that DB to catch up to the specified replication position. Otherwise, it will
	 * do so once such a connection is opened.
	 *
	 * If a timeout happens when waiting, then getLaggedReplicaMode()/laggedReplicaUsed()
	 * will return true. This is useful for discouraging clients from taking further actions
	 * if session consistency could not be maintained with respect to their last actions.
	 *
	 * @param DBPrimaryPos|false $pos Primary position or false
	 */
	public function waitFor( $pos );

	/**
	 * Set the primary wait position and wait for ALL replica DBs to catch up to it
	 *
	 * This method is only intended for use a throttling mechanism for high-volume updates.
	 * Unlike waitFor(), failure does not effect getLaggedReplicaMode()/laggedReplicaUsed().
	 *
	 * @param DBPrimaryPos|false $pos Primary position or false
	 * @param int|null $timeout Max seconds to wait; default is mWaitTimeout
	 * @return bool Success (able to connect and no timeouts reached)
	 */
	public function waitForAll( $pos, $timeout = null );

	/**
	 * Get an existing DB handle to the given server index (on any domain)
	 *
	 * Use the CONN_TRX_AUTOCOMMIT flag to only look for connections opened with that flag.
	 *
	 * Avoid the use of begin()/commit() and startAtomic()/endAtomic() on any handle returned.
	 * This method is intended for internal RDBMS callers that issue queries that do
	 * not affect any current transaction.
	 *
	 * @internal For use by Rdbms classes only
	 * @param int $i Specific or virtual (DB_PRIMARY/DB_REPLICA) server index
	 * @param int $flags Bitfield of CONN_* class constants
	 * @return Database|false False if no such connection is open
	 */
	public function getAnyOpenConnection( $i, $flags = 0 );

	/**
	 * Get a lazy-connecting database handle for a specific or virtual (DB_PRIMARY/DB_REPLICA) server index
	 *
	 * The server index, $i, can be one of the following:
	 *   - DB_REPLICA: a server index will be selected by the load balancer based on read
	 *      weight, connectivity, and replication lag. Note that the primary server might be
	 *      configured with read weight. If $groups is empty then it means "the generic group",
	 *      in which case all servers defined with read weight will be considered. Additional
	 *      query groups can be configured, having their own list of server indexes and read
	 *      weights. If a query group list is provided in $groups, then each recognized group
	 *      will be tried, left-to-right, until server index selection succeeds or all groups
	 *      have been tried, in which case the generic group will be tried.
	 *   - DB_PRIMARY: the primary server index will be used; the same as getWriterIndex().
	 *      The value of $groups should be [] when using this server index.
	 *   - Specific server index: a positive integer can be provided to use the server with
	 *      that index. An error will be thrown in no such server index is recognized. This
	 *      server selection method is usually only useful for internal load balancing logic.
	 *      The value of $groups should be [] when using a specific server index.
	 *
	 * Callers that get a *local* DB domain handle for the same server will share one handle for all of those
	 * callers using CONN_TRX_AUTOCOMMIT (via $flags) and one handle for all of those callers not
	 * using CONN_TRX_AUTOCOMMIT. Callers that get a *foreign* DB domain handle (via $domain) will
	 * share any handle that has the right CONN_TRX_AUTOCOMMIT mode and is already on the right
	 * DB domain. Otherwise, one of the "free for reuse" handles will be claimed or a new handle
	 * will be made if there are none.
	 *
	 * Handle sharing is particularly useful when callers get local DB domain (the default),
	 * transaction round aware (the default), DB_PRIMARY handles. All such callers will operate
	 * within a single database transaction as a consequence. Handle sharing is also useful when
	 * callers get local DB domain (the default), transaction round aware (the default), samely
	 * query grouped (the default), DB_REPLICA handles. All such callers will operate within a
	 * single database transaction as a consequence.
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
	 * @param int $i Specific (overrides $groups) or virtual (DB_PRIMARY/DB_REPLICA) server index
	 * @param string[]|string $groups Query group(s) in preference order; [] for the default group
	 * @param string|false $domain DB domain ID or false for the local domain
	 * @param int $flags Bitfield of CONN_* class constants
	 * @return IDatabase|false This returns false on failure if CONN_SILENCE_ERRORS is set
	 */
	public function getConnection( $i, $groups = [], $domain = false, $flags = 0 );

	/**
	 * Get a DB handle for a specific server index
	 *
	 * This is an internal utility method for methods like LoadBalancer::getConnectionInternal()
	 * and DBConnRef to create the underlying connection to a concrete server.
	 *
	 * The following is the responsibility of the caller:
	 *
	 * - translate any virtual server indexes (DB_PRIMARY/DB_REPLICA) to a real server index.
	 * - enforce read-only mode on primary DB handle if there is high replication lag.
	 *
	 * @see ILoadBalancer::getConnection()
	 *
	 * @internal Only for use within ILoadBalancer/ILoadMonitor
	 * @param int $i Specific server index
	 * @param string $domain Resolved DB domain
	 * @param int $flags Bitfield of class CONN_* constants
	 * @return IDatabase|false This returns false on failure if CONN_SILENCE_ERRORS is set
	 * @throws DBError If no DB handle could be obtained and CONN_SILENCE_ERRORS is not set
	 */
	public function getServerConnection( $i, $domain, $flags = 0 );

	/**
	 * @deprecated since 1.39 noop
	 * @param IDatabase $conn
	 */
	public function reuseConnection( IDatabase $conn );

	/**
	 * @internal Only for use within DBConnRef
	 * @param IDatabase $conn
	 */
	public function reuseConnectionInternal( IDatabase $conn );

	/**
	 * @deprecated since 1.39, use ILoadBalancer::getConnection() instead.
	 * @param int $i Specific or virtual (DB_PRIMARY/DB_REPLICA) server index
	 * @param string[]|string $groups Query group(s) in preference order; [] for the default group
	 * @param string|false $domain DB domain ID or false for the local domain
	 * @param int $flags Bitfield of CONN_* class constants (e.g. CONN_TRX_AUTOCOMMIT)
	 * @return DBConnRef
	 */
	public function getConnectionRef( $i, $groups = [], $domain = false, $flags = 0 ): IDatabase;

	/**
	 * @internal Only to be used by DBConnRef
	 * @param int $i Specific (overrides $groups) or virtual (DB_PRIMARY/DB_REPLICA) server index
	 * @param string[]|string $groups Query group(s) in preference order; [] for the default group
	 * @param string|false $domain DB domain ID or false for the local domain
	 * @param int $flags Bitfield of CONN_* class constants (e.g. CONN_TRX_AUTOCOMMIT)
	 * @return IDatabase
	 */
	public function getConnectionInternal( $i, $groups = [], $domain = false, $flags = 0 ): IDatabase;

	/**
	 * Get a lazy-connecting database handle for a server index
	 *
	 * The CONN_TRX_AUTOCOMMIT flag is ignored for databases with ATTR_DB_LEVEL_LOCKING
	 * (e.g. sqlite) in order to avoid deadlocks. getServerAttributes()
	 * can be used to check such flags beforehand. Avoid the use of begin() or startAtomic()
	 * on any CONN_TRX_AUTOCOMMIT connections.
	 *
	 * @deprecated since 1.38, use ILoadBalancer::getConnectionRef() instead.
	 * @see ILoadBalancer::getConnection() for parameter information
	 * @param int $i Specific or virtual (DB_PRIMARY/DB_REPLICA) server index
	 * @param string[]|string $groups Query group(s) in preference order; [] for the default group
	 * @param string|false $domain DB domain ID or false for the local domain
	 * @param int $flags Bitfield of CONN_* class constants
	 * @return IDatabase
	 */
	public function getLazyConnectionRef( $i, $groups = [], $domain = false, $flags = 0 ): IDatabase;

	/**
	 * Get a DB handle, suitable for migrations and schema changes, for a server index
	 *
	 * The DBConnRef methods simply proxy an underlying IDatabase object which
	 * takes care of the actual connection and query logic.
	 *
	 * The CONN_TRX_AUTOCOMMIT flag is ignored for databases with ATTR_DB_LEVEL_LOCKING
	 * (e.g. sqlite) in order to avoid deadlocks. getServerAttributes()
	 * can be used to check such flags beforehand. Avoid the use of begin() or startAtomic()
	 * on any CONN_TRX_AUTOCOMMIT connections.
	 *
	 * @see ILoadBalancer::getConnection() for parameter information
	 * @param int $i Specific or virtual (DB_PRIMARY/DB_REPLICA) server index
	 * @param string[]|string $groups Query group(s) in preference order; [] for the default group
	 * @param string|false $domain DB domain ID or false for the local domain
	 * @param int $flags Bitfield of CONN_* class constants (e.g. CONN_TRX_AUTOCOMMIT)
	 * @return DBConnRef
	 */
	public function getMaintenanceConnectionRef( $i, $groups = [], $domain = false, $flags = 0 ): DBConnRef;

	/**
	 * Get the specific server index of the primary server
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
	 * This counts both servers using streaming replication from the primary server and
	 * servers that just have a clone of the static dataset found on the primary server
	 *
	 * @return bool
	 * @since 1.34
	 */
	public function hasReplicaServers();

	/**
	 * Whether any replica servers use streaming replication from the primary server
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
	 * If this returns false, this means that there is generally no reason to execute
	 * replication wait logic for session consistency and lag reduction.
	 *
	 * @return bool
	 * @since 1.34
	 */
	public function hasStreamingReplicaServers();

	/**
	 * Get the readable name of the server with the specified index
	 *
	 * @param int $i Specific server index
	 * @return string Readable server name, falling back to the hostname or IP address
	 */
	public function getServerName( $i ): string;

	/**
	 * Return the server configuration map for the server with the specified index
	 *
	 * @param int $i Specific server index
	 * @return array|false Server configuration map; false if the index is invalid
	 * @since 1.31
	 */
	public function getServerInfo( $i );

	/**
	 * Get the RDBMS type of the server with the specified index (e.g. "mysql", "sqlite")
	 *
	 * @param int $i Specific server index
	 * @return string One of (mysql,postgres,sqlite,...) or "unknown" for bad indexes
	 * @since 1.30
	 */
	public function getServerType( $i );

	/**
	 * Get basic attributes of the server with the specified index without connecting
	 *
	 * @param int $i Specific server index
	 * @return array (Database::ATTRIBUTE_* constant => value) for all such constants
	 * @since 1.31
	 */
	public function getServerAttributes( $i );

	/**
	 * Get the current primary replication position
	 *
	 * @return DBPrimaryPos|false Returns false if not applicable
	 * @throws DBError
	 * @since 1.37
	 */
	public function getPrimaryPos();

	/**
	 * Get the highest DB replication position for chronology control purposes
	 *
	 * If there is only a primary server then this returns false. If replication is present
	 * and correctly configured, then this returns the highest replication position of any
	 * server with an open connection. That position can later be passed to waitFor() on a
	 * new load balancer instance to make sure that queries on the new connections see data
	 * at least as up-to-date as queries (prior to this method call) on the old connections.
	 *
	 * This can be useful for implementing session consistency, where the session
	 * will be resumed across multiple HTTP requests or CLI script instances.
	 *
	 * @return DBPrimaryPos|false Replication position or false if not applicable
	 * @since 1.34
	 */
	public function getReplicaResumePos();

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
	 * @return bool Whether a primary connection is already open
	 * @since 1.37
	 */
	public function hasPrimaryConnection();

	/**
	 * Whether there are pending changes or callbacks in a transaction by this thread
	 * @return bool
	 * @since 1.37
	 */
	public function hasPrimaryChanges();

	/**
	 * Determine whether an explicit transaction is active on any open primary
	 * connection.
	 * @return bool
	 * @since 1.39
	 */
	public function explicitTrxActive();

	/**
	 * Get the timestamp of the latest write query done by this thread
	 * @return float|false UNIX timestamp or false
	 * @since 1.37
	 */
	public function lastPrimaryChangeTimestamp();

	/**
	 * Check if this load balancer object had any recent or still
	 * pending writes issued against it by this PHP thread
	 *
	 * @param float|null $age How many seconds ago is "recent" [defaults to mWaitTimeout]
	 * @return bool
	 * @since 1.37
	 */
	public function hasOrMadeRecentPrimaryChanges( $age = null );

	/**
	 * @note This method will trigger a DB connection if not yet done
	 * @param string|false $domain DB domain ID or false for the local domain
	 * @return bool Whether the database for generic connections this request is highly "lagged"
	 */
	public function getLaggedReplicaMode( $domain = false );

	/**
	 * Checks whether the database for generic connections this request was both:
	 *   - a) Already chosen due to a prior connection attempt
	 *   - b) Considered highly "lagged"
	 *
	 * @note This method will never cause a new DB connection
	 * @return bool
	 */
	public function laggedReplicaUsed();

	/**
	 * @note This method may trigger a DB connection if not yet done
	 * @param string|false $domain DB domain ID or false for the local domain
	 * @return string|false Reason the primary is read-only or false if it is not
	 */
	public function getReadOnlyReason( $domain = false );

	/**
	 * @return bool
	 */
	public function pingAll();

	/**
	 * Get the name and lag time of the most-lagged replica server
	 *
	 * This is useful for maintenance scripts that need to throttle their updates.
	 * May attempt to open connections to replica DBs on the default DB. If there is
	 * no lag, the maximum lag will be reported as -1.
	 *
	 * @param string|false $domain Domain ID or false for the default database
	 * @return array{0:string,1:float|int|false,2:int} (host, max lag, index of max lagged host)
	 */
	public function getMaxLag( $domain = false );

	/**
	 * Get an estimate of replication lag (in seconds) for each server
	 *
	 * Results are cached for a short time in memcached/process cache
	 *
	 * Values may be "false" if replication is too broken to estimate
	 *
	 * @param string|false $domain
	 * @return float[]|int[]|false[] Map of (server index => lag) in order of server index
	 */
	public function getLagTimes( $domain = false );

	/**
	 * Wait for a replica DB to reach a specified primary position
	 *
	 * If $conn is not a replica server connection, then this will return true.
	 * Otherwise, if $pos is not provided, this will connect to the primary server
	 * to get an accurate position.
	 *
	 * @param IDatabase $conn Replica DB
	 * @param DBPrimaryPos|false $pos Primary position; default: current position
	 * @param int $timeout Timeout in seconds [optional]
	 * @return bool Success
	 * @since 1.37
	 */
	public function waitForPrimaryPos( IDatabase $conn, $pos = false, $timeout = 10 );

	/**
	 * Set a callback via IDatabase::setTransactionListener() on
	 * all current and future primary connections of this load balancer
	 *
	 * @param string $name Callback name
	 * @param callable|null $callback
	 */
	public function setTransactionListener( $name, callable $callback = null );

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

	/**
	 * Convert certain database domains to alternative ones.
	 *
	 * This can be used for backwards compatibility logic.
	 *
	 * @param DatabaseDomain[]|string[] $aliases Map of (domain alias => domain)
	 * @since 1.35
	 */
	public function setDomainAliases( array $aliases );
}
