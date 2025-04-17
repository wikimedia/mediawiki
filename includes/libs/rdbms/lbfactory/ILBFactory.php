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

use Generator;
use InvalidArgumentException;

/**
 * Manager of ILoadBalancer objects and, indirectly, IDatabase connections
 *
 * Each Load balancer instances corresponds to a specific database cluster.
 * A "cluster" is the set of database servers that manage a given dataset.
 *
 * The "main" clusters are meant to colocate the most basic and highly relational application
 * data for one or more "sister projects" managed by this site. This allows for highly flexible
 * queries. Each project is identified by a database domain. Note that if there are several
 * projects stored on a cluster, then the cluster dataset is a superset of the dataset for each
 * of those projects.
 *
 * The "external" clusters are meant to provide places for bulk text storage, to colocate bulky
 * relational data from specific modules, and to colocate data from cross-project modules such
 * as authentication systems. An external cluster can have a database/schema for each project.
 *
 * @see ILoadBalancer
 *
 * @ingroup Database
 * @since 1.28
 */
interface ILBFactory extends IConnectionProvider {
	/** Idiom for "no special shutdown flags" */
	public const SHUTDOWN_NORMAL = 0;
	/** Do not save "session consistency" DB replication positions */
	public const SHUTDOWN_NO_CHRONPROT = 1;

	/** Default main cluster name (do not change this) */
	public const CLUSTER_MAIN_DEFAULT = 'DEFAULT';

	/**
	 * Sub-classes may extend the required keys in $conf with additional parameters
	 *
	 * @param array $conf Array with keys:
	 *  - localDomain: A DatabaseDomain or database domain ID string
	 *  - virtualDomains: List of virtual database domain ID strings [optional].
	 *     These can be passed to {@see ILBFactory::getPrimaryDatabase()} and
	 *     {@see ILBFactory::getReplicaDatabase()}, with the actual cluster and database
	 *     domain being automatically resolved via "virtualDomainsMapping". Virtual database
	 *     domains not defined there will resolve to the local database domain.
	 *  - virtualDomainsMapping: Map of (virtual database domain ID => config map) [optional].
	 *     Each config map has a "db" key and an optional "cluster" key. The "db" key specifies
	 *     the actual database domain configured for use, with false indicating that the local
	 *     database domain is configured for use. The "cluster" key, if provided, specifies the
	 *     name of the external cluster configured for use, otherwise, the main cluster for the
	 *     actual database domain will be used.
	 *  - chronologyProtector: ChronologyProtector instance [optional]
	 *  - readOnlyReason: Reason the primary server is read-only (false if not)
	 *  - srvCache: BagOStuff instance for server cache [optional]
	 *  - cpStash: BagOStuff instance for ChronologyProtector store [optional].
	 *    See [ChronologyProtector requirements](@ref ChronologyProtector-storage-requirements).
	 *  - wanCache: WANObjectCache instance [optional]
	 *  - cliMode: Whether the execution context is a CLI script [optional]
	 *  - profiler: Callback that takes a profile section name and returns a ScopedCallback
	 *     that ends the profile section in its destructor [optional]
	 *  - trxProfiler: TransactionProfiler instance [optional]
	 *  - logger: PSR-3 logger instance [optional]
	 *  - errorLogger: Callback that takes an Exception and logs it [optional]
	 *  - deprecationLogger: Callback to log a deprecation warning [optional]
	 *  - secret: Secret string to use for HMAC hashing [optional]
	 *  - criticalSectionProvider: CriticalSectionProvider instance [optional]
	 *  - statsFactory: StatsFactory instance [optional]
	 */
	public function __construct( array $conf );

	/**
	 * Close all connections and make further attempts to open connections result in DBAccessError
	 *
	 * This only applies to the tracked load balancer instances.
	 *
	 * @see ILoadBalancer::disable()
	 */
	public function destroy();

	/**
	 * Reload the configuration if necessary.
	 * This may or may not have any effect.
	 */
	public function autoReconfigure(): void;

	/**
	 * Get the local (and default) database domain ID of connection handles
	 *
	 * @see DatabaseDomain
	 * @return string Database domain ID; this specifies DB name, schema, and table prefix
	 * @since 1.32
	 */
	public function getLocalDomainID(): string;

	/**
	 * Close all connections and redefine the local database domain
	 *
	 * This only applies to the tracked load balancer instances.
	 *
	 * This method is only intended for use with schema creation or integration testing
	 *
	 * @param DatabaseDomain|string $domain
	 * @since 1.33
	 */
	public function redefineLocalDomain( $domain );

	/**
	 * Get the tracked load balancer instance for a given domain.
	 *
	 * If no tracked instances exists, then one will be instantiated.
	 *
	 * This method accepts virtual domains
	 * ({@see \MediaWiki\MainConfigSchema::VirtualDomainsMapping}).
	 *
	 * @since 1.43
	 * @param string|false $domain Domain ID, or false for the current domain
	 * @return ILoadBalancer
	 */
	public function getLoadBalancer( $domain = false ): ILoadBalancer;

	/**
	 * Create a new load balancer instance for the main cluster that handles the given domain
	 *
	 * The resulting object is considered to be owned by the caller. Namely, it will be
	 * untracked, the caller is responsible for cleaning it up, and replication positions
	 * from it will not be saved by ChronologyProtector.
	 *
	 * This method is for only advanced usage and callers should almost always use
	 * getMainLB() instead. This method can be useful when a table is used as a key/value
	 * store. In that cases, one might want to query it in autocommit mode (DBO_TRX off)
	 * but still use DBO_TRX transaction rounds on other tables.
	 *
	 * @note The local/default database domain used by the load balancer instance will
	 * still inherit from this ILBFactory instance, regardless of the $domain parameter.
	 *
	 * @param string|false $domain Domain ID, or false for the current domain
	 * @return ILoadBalancerForOwner
	 */
	public function newMainLB( $domain = false ): ILoadBalancerForOwner;

	/**
	 * Get the tracked load balancer instance for the main cluster that handles the given domain
	 *
	 * If no tracked instances exists, then one will be instantiated
	 *
	 * @note The local/default database domain used by the load balancer instance will
	 * still inherit from this ILBFactory instance, regardless of the $domain parameter.
	 *
	 * @param string|false $domain Domain ID, or false for the current domain
	 * @return ILoadBalancer
	 */
	public function getMainLB( $domain = false ): ILoadBalancer;

	/**
	 * Create a new load balancer instance for an external cluster
	 *
	 * The resulting object will be untracked and the caller is responsible for cleaning it up.
	 * Database replication positions will not be saved by ChronologyProtector.
	 *
	 * This method is for only advanced usage and callers should almost always use
	 * getExternalLB() instead. This method can be useful when a table is used as a
	 * key/value store. In that cases, one might want to query it in autocommit mode
	 * (DBO_TRX off) but still use DBO_TRX transaction rounds on other tables.
	 *
	 * @param string $cluster External cluster name
	 * @throws InvalidArgumentException If $cluster is not recognized
	 * @return ILoadBalancerForOwner
	 */
	public function newExternalLB( $cluster ): ILoadBalancerForOwner;

	/**
	 * Get the tracked load balancer instance for an external cluster
	 *
	 * If no tracked instances exists, then one will be instantiated
	 *
	 * @param string $cluster External cluster name
	 * @throws InvalidArgumentException If $cluster is not recognized
	 * @return ILoadBalancer
	 */
	public function getExternalLB( $cluster ): ILoadBalancer;

	/**
	 * Get the tracked load balancer instances for all main clusters
	 *
	 * If no tracked instance exists for a cluster, then one will be instantiated
	 *
	 * Note that default main cluster name is ILoadBalancer::CLUSTER_MAIN_DEFAULT
	 *
	 * @return ILoadBalancer[] Map of (cluster name => ILoadBalancer)
	 * @since 1.29
	 */
	public function getAllMainLBs(): array;

	/**
	 * Get the tracked load balancer instances for all external clusters
	 *
	 * If no tracked instance exists for a cluster, then one will be instantiated
	 *
	 * @return ILoadBalancer[] Map of (cluster name => ILoadBalancer)
	 * @since 1.29
	 */
	public function getAllExternalLBs(): array;

	/**
	 * Get all tracked load balancer instances (generator)
	 *
	 * @return Generator|ILoadBalancer[]
	 * @since 1.39
	 */
	public function getAllLBs();

	/**
	 * Prepare all instantiated tracked load balancer instances for shutdown
	 *
	 * @param int $flags Bit field of ILBFactory::SHUTDOWN_* constants
	 * @param callable|null $workCallback Work to mask ChronologyProtector writes
	 * @param int|null &$cpIndex Position key write counter for ChronologyProtector [returned]
	 * @param string|null &$cpClientId Client ID hash for ChronologyProtector [returned]
	 */
	public function shutdown(
		$flags = self::SHUTDOWN_NORMAL,
		?callable $workCallback = null,
		&$cpIndex = null,
		&$cpClientId = null
	);

	/**
	 * Commit all replica database server transactions, clearing any point-in-time view snapshots
	 *
	 * This only applies to the instantiated tracked load balancer instances.
	 *
	 * This is useful for getting rid of stale data from an implicit transaction round
	 *
	 * @param string $fname Caller name @phan-mandatory-param
	 * @deprecated Since 1.43
	 */
	public function flushReplicaSnapshots( $fname = __METHOD__ );

	/**
	 * Wrap subsequent queries for all transaction round aware primary connections in a transaction
	 *
	 * Each of these transactions will be owned by this ILBFactory instance such that direct
	 * calls to {@link IDatabase::commit()} or {@link IDatabase::rollback()} will be disabled.
	 * These transactions get resolved by a single call to either {@link commitPrimaryChanges()}
	 * or {@link rollbackPrimaryChanges()}, after which, the transaction wrapping and ownership
	 * behavior revert back to the default. When there are multiple connections involved, these
	 * methods perform best-effort distributed transactions. When using distributed transactions,
	 * the RDBMS should be configured to used pessimistic concurrency control such that the commit
	 * step of each transaction is unlikely to fail.
	 *
	 * Transactions on replication connections are flushed so that future reads will not keep
	 * using the same point-in-time view snapshots (e.g. from MySQL REPEATABLE-READ). However,
	 * this does not wait for replication to catch up, so subsequent reads from replicas might
	 * not reflect recently committed changes.
	 *
	 * This only applies to the tracked load balancer instances.
	 *
	 * This allows for custom transaction rounds from any outer transaction scope.
	 *
	 * @param string $fname @phan-mandatory-param
	 * @throws DBTransactionError
	 * @since 1.37
	 */
	public function beginPrimaryChanges( $fname = __METHOD__ );

	/**
	 * Commit all primary connection transactions and flush all replica connection transactions
	 *
	 * Transactions on replication connections are flushed so that future reads will not keep
	 * using the same point-in-time view snapshots (e.g. from MySQL REPEATABLE-READ). However,
	 * this does not wait for replication to catch up, so subsequent reads from replicas might
	 * not reflect the committed changes.
	 *
	 * This only applies to the instantiated tracked load balancer instances.
	 *
	 * @param string $fname Caller name @phan-mandatory-param
	 * @param int $maxWriteDuration abort if more than this much time was spent in write queries
	 * @throws DBTransactionError
	 * @since 1.37
	 */
	public function commitPrimaryChanges( $fname = __METHOD__, int $maxWriteDuration = 0 );

	/**
	 * Rollback all primary connection transactions and flush all replica connection transactions
	 *
	 * This only applies to the instantiated tracked load balancer instances.
	 *
	 * @param string $fname Caller name @phan-mandatory-param
	 * @since 1.37
	 */
	public function rollbackPrimaryChanges( $fname = __METHOD__ );

	/**
	 * Release important session-level state (named lock, table locks) as post-rollback cleanup
	 *
	 * This only applies to the instantiated tracked load balancer instances.
	 *
	 * This should only be called by application entry point functions, since there must be
	 * no chance that a future caller will still be expecting some of the lost session state.
	 *
	 * @param string $fname Caller name @phan-mandatory-param
	 * @since 1.38
	 */
	public function flushPrimarySessions( $fname = __METHOD__ );

	/**
	 * Check if an explicit transaction round is active
	 *
	 * @return bool
	 * @since 1.29
	 */
	public function hasTransactionRound();

	/**
	 * Check if transaction rounds can be started, committed, or rolled back right now
	 *
	 * This can be used as a recursion guard to avoid exceptions in transaction callbacks.
	 *
	 * @return bool
	 * @since 1.32
	 */
	public function isReadyForRoundOperations();

	/**
	 * Determine if any primary connection has pending changes
	 *
	 * This only applies to the instantiated tracked load balancer instances.
	 *
	 * @return bool
	 * @since 1.37
	 */
	public function hasPrimaryChanges();

	/**
	 * Determine if any lagged replica database server connection was used.
	 *
	 * This only applies to the instantiated tracked load balancer instances.
	 *
	 * @return bool
	 */
	public function laggedReplicaUsed();

	/**
	 * Determine if any primary connection has pending/written changes from this request
	 *
	 * This only applies to the instantiated tracked load balancer instances.
	 *
	 * @param float|null $age How many seconds ago is "recent" [defaults to LB lag wait timeout]
	 * @return bool
	 */
	public function hasOrMadeRecentPrimaryChanges( $age = null );

	/**
	 * Waits for the replica database server to catch up to the current primary position
	 *
	 * Use this when updating very large numbers of rows, as in maintenance scripts, to
	 * avoid causing too much lag. This is a no-op if there are no replica database servers.
	 *
	 * By default this waits on all DB clusters actually used in this request.
	 * This makes sense when lag being waiting on is caused by the code that does this check.
	 * In that case, setting "ifWritesSince" can avoid the overhead of waiting for clusters
	 * that were not changed since the last wait check.
	 *
	 * Never call this function after a large DB write that is *still* in a transaction.
	 * It only makes sense to call this after the possible lag inducing changes were committed.
	 *
	 * This only applies to the instantiated tracked load balancer instances.
	 *
	 * @param array $opts Optional fields that include:
	 *   - timeout: Max wait time. Default: 60 seconds for CLI, 1 second for web.
	 *   - ifWritesSince: Only wait if writes were done since this UNIX timestamp.
	 * @return bool True on success, false if a timeout or error occurred while waiting
	 */
	public function waitForReplication( array $opts = [] );

	/**
	 * Add a callback to be run in every call to waitForReplication() prior to any waiting
	 *
	 * Callbacks must clear any transactions that they start.
	 *
	 * @param string $name Callback name
	 * @param callable|null $callback Use null to unset a callback
	 * @deprecated Since 1.44
	 */
	public function setWaitForReplicationListener( $name, ?callable $callback = null );

	/**
	 * Disable the ChronologyProtector on all instantiated tracked load balancer instances
	 *
	 * This can be called at the start of special API entry points.
	 */
	public function disableChronologyProtection();

	/**
	 * Set a new table prefix for the existing local domain ID for testing
	 *
	 * @param string $prefix
	 * @since 1.33
	 */
	public function setLocalDomainPrefix( $prefix );

	/**
	 * Close all connections on instantiated tracked load balancer instances
	 *
	 * @param string $fname Caller name (e.g. __METHOD__) @phan-mandatory-param
	 */
	public function closeAll( $fname = __METHOD__ );

	/**
	 * @param string $agent Agent name for query profiling
	 */
	public function setAgentName( $agent );

	/**
	 * Whether it has streaming replica servers.
	 *
	 * @since 1.41
	 * @return bool
	 */
	public function hasStreamingReplicaServers();

	/**
	 * Set the default timeout for replication wait checks
	 *
	 * @param int $seconds Timeout, in seconds
	 * @return int The previous default timeout
	 * @since 1.35
	 */
	public function setDefaultReplicationWaitTimeout( $seconds );

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
	 * @since 1.31
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
	 * @param string[] $aliases Map of (index alias => index name)
	 * @since 1.31
	 */
	public function setIndexAliases( array $aliases );

	/**
	 * Convert certain database domains to alternative ones
	 *
	 * This can be used for backwards compatibility logic.
	 *
	 * @param DatabaseDomain[]|string[] $aliases Map of (domain alias => domain)
	 * @since 1.35
	 */
	public function setDomainAliases( array $aliases );

	/**
	 * Get the TransactionProfiler used by this instance
	 *
	 * @return TransactionProfiler
	 * @since 1.35
	 */
	public function getTransactionProfiler(): TransactionProfiler;

	/**
	 * Like {@link IConnectionProvider::getPrimaryDatabase()} but with AUTOCOMMIT mode.
	 *
	 * This is useful for whether the caller needs to use AUTOCOMMIT (no transaction wrapping)
	 * or it needs a new connection outside of the current transaction to bypass REPEATABLE READ
	 * isolation.
	 *
	 * This method accepts virtual domains
	 * ({@see \MediaWiki\MainConfigSchema::VirtualDomainsMapping}).
	 *
	 * @since 1.44
	 * @param string|false $domain Domain ID, or false for the current domain
	 * @return IDatabase
	 */
	public function getAutoCommitPrimaryConnection( $domain = false ): IDatabase;
}
