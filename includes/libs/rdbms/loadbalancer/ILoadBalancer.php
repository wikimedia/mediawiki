<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

/**
 * This class is a delegate to ILBFactory for a given database cluster
 *
 * ILoadBalancer tracks the database connections and transactions for a given database cluster.
 * A "cluster" is considered to be the set of database servers that manage a given dataset.
 * Within a given cluster, each database server can have one of the following roles:
 *  - sole-primary: the server used by this datacenter for writes from the application
 *  - co-primary: one of several servers used by this datacenter for writes from the application,
 *     relying on asynchronous replication to synchronize their copies of the dataset
 *  - replica: a server used only for reads from the application, relying on asynchronous
 *     replication to apply writes from the primary server or co-primary servers
 *  - static clone: a server that only accepts reads from the application, does not replicate,
 *     and has a copy of the final dataset, which must be static (all the servers reject writes)
 *
 * Single-datacenter database clusters consist of either:
 *   - A sole-primary server and zero or more replica servers
 *   - A set of static clone servers
 *
 * Multi-datacenter database clusters either consist of either:
 *   - A sole-primary server and zero or more replica servers in the "primary datacenter"
 *     (the one datacenter meant to handle requests/jobs that mutate the database), and zero or
 *     more replica servers in each "secondary datacenter" (all the other datacenters)
 *   - A co-primary server and zero or more replica servers within each datacenter
 *   - A set of static clone servers in each datacenter
 *
 * The term "primary" refers to the server used by this datacenter for handling writes,
 * whether it is a sole-primary or co-primary.
 *
 * The "servers" configuration array contains the list of database servers to use for operations
 * originating from the the local datacenter. The first entry must refer to the server to use for
 * write and read-for-write operations (e.g. the "writer server"):
 *   - If there is a primary server, then the first entry must refer to it, even if the primary
 *     server resides in a remote datacenter
 *   - If there are co-primary servers, then the first entry must refer to the one in the local
 *     datacenter
 *   - If the servers are static clones, then the first entry can refer to any of them, since the
 *     concept of a "writer server" is merely nominal
 *
 * On an infrastructure level, circular replication setups can have more than one database server
 * act as a replication "source" within the same datacenter, provided that no more than one of the
 * servers are writable at any time, namely the "writer server". The other source servers will be
 * treated as replicas by the load balancer, but can be quickly promoted to the "writer server" by
 * the site admin as needed.
 *
 * Likewise, Galera Cluster setups still require the choice of a single "writer server" for each
 * datacenter. Limiting the number of servers that initiate transactions helps reduce the rate of
 * aborted transactions due to wsrep conflicts.
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
 *   - Sole-primary + N Replica(s): set 'max lag' to an appropriate threshold for avoiding any
 *      replica database lagged by this much or more. If all replicas are this lagged, then the
 *      load balancer considers the cluster to be read-only.
 *   - Per-datacenter co-primary + N Replica(s): set 'max lag' to an appropriate threshold for
 *      avoiding any replica database lagged by this much or more. If all replicas are this
 *      lagged, then the load balancer considers the cluster to be read-only.
 *   - Read-only archive clones: set 'is static' in the server configuration maps. This will
 *      treat all such DBs as having 0 lag.
 *   - Externally updated dataset clones: set 'is static' in the server configuration maps.
 *      This will treat all such DBs as having 0 lag.
 *   - SQL load balancing proxy: any proxy should handle lag checks on its own, so the 'max lag'
 *      parameter should probably be set to INF in the server configuration maps. This will make
 *      the load balancer ignore whatever it detects as the lag of the logical replica is (which
 *      would probably just randomly bounce around).
 *
 * If using a SQL proxy service, it would probably be best to have two proxy hosts for the load
 * balancer to talk to. One would be the 'host' of the "writer server" entry and another for the
 * (logical) replica server entry. The proxy could map the load balancer's "replica" DB to any
 * number of physical replica DBs.
 *
 * @since 1.28
 * @ingroup Database
 */
interface ILoadBalancer {
	/**
	 * Request a replica DB connection. Can't be used as a binary flag with bitwise operators!
	 */
	public const DB_REPLICA = -1;
	/**
	 * Request a primary, write-enabled DB connection. Can't be used as a binary flag with bitwise
	 * operators!
	 * @since 1.36
	 */
	public const DB_PRIMARY = -2;

	/** Domain specifier when no specific database needs to be selected */
	public const DOMAIN_ANY = '';
	/** The generic query group */
	public const GROUP_GENERIC = '';

	/** Yield a tracked autocommit-mode handle (reuse existing ones) */
	public const CONN_TRX_AUTOCOMMIT = 1;
	/**
	 * Yield an untracked, low-timeout, autocommit-mode handle (to gauge server health)
	 * @internal
	 */
	public const CONN_UNTRACKED_GAUGE = 2;
	/**
	 * Yield null on connection failure instead of throwing an exception
	 * @internal
	 */
	public const CONN_SILENCE_ERRORS = 4;

	/**
	 * Get the name of the overall cluster of database servers managing the dataset
	 *
	 * Note that the cluster might contain servers in multiple datacenters.
	 * The load balancer instance only needs to be aware of the local replica servers,
	 * along with either the sole-primary server or the local co-primary server.
	 *
	 * This is useful for identifying a cluster or replicated dataset, even when:
	 *  - The primary server is sometimes swapped with another one
	 *  - The cluster/dataset is replicated among multiple datacenters, with one "primary"
	 *    datacenter having the writable primary server and the other datacenters having a
	 *    read-only replica in the "primary" server slot
	 *  - The dataset is replicated among multiple datacenters, via circular replication,
	 *    with each datacenter having its own "co-primary" server
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
	public function resolveDomainID( DatabaseDomain|string|false $domain ): string;

	/**
	 * Indicate whether the tables on this domain are only temporary tables for testing
	 *
	 * In "temporary tables mode", the CONN_TRX_AUTOCOMMIT flag is ignored
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
	 * @return int|false Specific server index, or false if no DB handle can be obtained
	 */
	public function getReaderIndex( $group = false );

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
	 *   - DB_PRIMARY: the primary server index will be used; the same as ServerInfo::WRITER_INDEX.
	 *      The value of $groups should be [] when using this server index.
	 *   - Specific server index: a positive integer can be provided to use the server with
	 *      that index. An error will be thrown in no such server index is recognized. This
	 *      server selection method is usually only useful for internal load balancing logic.
	 *      The value of $groups should be [] when using a specific server index.
	 *
	 * Handle sharing is very useful when callers get DB_PRIMARY handles that are transaction
	 * round aware (the default). All such callers will operate within a single transaction as
	 * a consequence. The same applies to DB_REPLICA that are samely query grouped (the default)
	 * and  transaction round aware (the default).
	 *
	 * Use CONN_TRX_AUTOCOMMIT to use a separate pool of only autocommit handles. This flag is
	 * ignored for databases with ATTR_DB_LEVEL_LOCKING (e.g. sqlite) in order to avoid deadlocks.
	 * getServerAttributes() can be used to check this attribute beforehand. Avoid using begin()
	 * and commit() on such handles. If handle methods like startAtomic() and endAtomic() must be
	 * used on the handles, callers should at least make sure that the atomic sections are closed
	 * on failure via try/catch and cancelAtomic().
	 *
	 * Use CONN_UNTRACKED_GAUGE to get a new, untracked, handle, that uses a low connection timeout, a low
	 * read timeout, and autocommit mode. This flag is intended for use only be internal callers.
	 *
	 * CONN_UNTRACKED_GAUGE and CONN_TRX_AUTOCOMMIT are incompatible.
	 *
	 * @see ILoadBalancer::getServerAttributes()
	 *
	 * @param int $i Specific (overrides $groups) or virtual (DB_PRIMARY/DB_REPLICA) server index
	 * @param string[]|string $groups Query group(s) in preference order; [] for the default group
	 * @param string|false $domain DB domain ID or false for the local domain
	 * @param int $flags Bitfield of CONN_* class constants
	 * @return IDatabase|false This returns false on failure if CONN_SILENCE_ERRORS is set
	 */
	public function getConnection( $i, $groups = [], string|false $domain = false, $flags = 0 );

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
	 * @return IDatabaseForOwner|false This returns false on failure if CONN_SILENCE_ERRORS is set
	 * @throws DBError If no DB handle could be obtained and CONN_SILENCE_ERRORS is not set
	 */
	public function getServerConnection( $i, $domain, $flags = 0 );

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
	 * Get the number of servers defined in configuration
	 *
	 * @return int
	 */
	public function getServerCount();

	/**
	 * Whether there are any replica servers configured
	 *
	 * This scans the list of servers defined in configuration, checking for:
	 *  - Servers that are listed after the primary and not flagged with "is static";
	 *    such servers are assumed to be typical streaming replicas
	 *  - Servers that are listed after the primary and flagged with "is static";
	 *    such servers are assumed to have a clone of the static dataset (matching the primary)
	 *
	 * @return bool
	 * @since 1.34
	 */
	public function hasReplicaServers();

	/**
	 * Whether any replica servers use streaming replication from the primary server
	 *
	 * This scans the list of servers defined in configuration, checking for:
	 *  - Servers that are listed after the primary and not flagged with "is static";
	 *    such servers are assumed to be typical streaming replicas
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
	 * Check if this load balancer object had any recent or still
	 * pending writes issued against it by this PHP thread
	 *
	 * @param float|null $age How many seconds ago is "recent" [defaults to mWaitTimeout]
	 * @return bool
	 * @since 1.37
	 */
	public function hasOrMadeRecentPrimaryChanges( $age = null );

	/**
	 * @note This method may trigger a DB connection if not yet done
	 * @return string|false Reason the primary is read-only or false if it is not
	 */
	public function getReadOnlyReason();

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
	 * @return array{0:string,1:float|int|false,2:int} (host, max lag, index of max lagged host)
	 */
	public function getMaxLag();

	/**
	 * Get an estimate of replication lag (in seconds) for each server
	 *
	 * Results are cached for a short time in memcached/process cache
	 *
	 * Values may be "false" if replication is too broken to estimate
	 *
	 * @return float[]|int[]|false[] Map of (server index => lag) in order of server index
	 */
	public function getLagTimes();

	/**
	 * Wait for a replica DB to reach a specified primary position
	 *
	 * If $conn is not a replica server connection, then this will return true.
	 * Otherwise, if $pos is not provided, this will connect to the primary server
	 * to get an accurate position.
	 *
	 * @param IDatabase $conn Replica DB
	 * @return bool Success
	 * @since 1.37
	 */
	public function waitForPrimaryPos( IDatabase $conn );

	/**
	 * Set a callback via IDatabase::setTransactionListener() on
	 * all current and future primary connections of this load balancer
	 *
	 * @param string $name Callback name
	 * @param callable|null $callback
	 * @deprecated Since 1.44
	 */
	public function setTransactionListener( $name, ?callable $callback = null );

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
	 * Convert certain database domains to alternative ones.
	 *
	 * This can be used for backwards compatibility logic.
	 *
	 * @param DatabaseDomain[]|string[] $aliases Map of (domain alias => domain)
	 * @since 1.35
	 */
	public function setDomainAliases( array $aliases );
}
