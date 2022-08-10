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
 * Manager of ILoadBalancer objects, and indirectly of IDatabase connections.
 *
 * @ingroup Database
 * @since 1.28
 */
interface ILBFactory {
	/** Idiom for "no special shutdown flags" */
	public const SHUTDOWN_NORMAL = 0;
	/** Do not save "session consistency" DB replication positions */
	public const SHUTDOWN_NO_CHRONPROT = 1;

	/** @var string Default main LB cluster name (do not change this) */
	public const CLUSTER_MAIN_DEFAULT = 'DEFAULT';

	/**
	 * Sub-classes may extend the required keys in $conf with additional parameters
	 *
	 * @param array $conf Array with keys:
	 *  - localDomain: A DatabaseDomain or database domain ID string.
	 *  - readOnlyReason: Reason the primary server is read-only if so [optional]
	 *  - srvCache: BagOStuff instance for server cache [optional]
	 *  - cpStash: BagOStuff instance for ChronologyProtector store [optional]
	 *    See [ChronologyProtector requirements](@ref ChronologyProtector-storage-requirements).
	 *  - wanCache: WANObjectCache instance [optional]
	 *  - cliMode: Whether the execution context is a CLI script. [optional]
	 *  - maxLag: Try to avoid DB replicas with lag above this many seconds [optional]
	 *  - profiler: Callback that takes a section name argument and returns
	 *      a ScopedCallback instance that ends the profile section in its destructor [optional]
	 *  - trxProfiler: TransactionProfiler instance. [optional]
	 *  - replLogger: PSR-3 logger instance. [optional]
	 *  - connLogger: PSR-3 logger instance. [optional]
	 *  - queryLogger: PSR-3 logger instance. [optional]
	 *  - perfLogger: PSR-3 logger instance. [optional]
	 *  - errorLogger: Callback that takes an Exception and logs it. [optional]
	 *  - deprecationLogger: Callback to log a deprecation warning. [optional]
	 *  - secret: Secret string to use for HMAC hashing [optional]
	 *  - criticalSectionProvider: CriticalSectionProvider instance [optional]
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
	 * Get the local (and default) database domain ID of connection handles
	 *
	 * @see DatabaseDomain
	 * @return string Database domain ID; this specifies DB name, schema, and table prefix
	 * @since 1.32
	 */
	public function getLocalDomainID();

	/**
	 * @param DatabaseDomain|string|false $domain Database domain
	 * @return string Value of $domain if provided or the local domain otherwise
	 * @since 1.32
	 */
	public function resolveDomainID( $domain );

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
	 * Create a new load balancer instance for the main cluster that handles the given domain
	 *
	 * The resulting object will be untracked and the caller is responsible for cleaning it up.
	 * Database replication positions will not be saved by ChronologyProtector.
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
	 * Execute a function for each instantiated tracked load balancer instance
	 *
	 * The callback is called with the load balancer as the first parameter,
	 * and $params passed as the subsequent parameters.
	 *
	 * @deprecated since 1.39 use getAllLBs()
	 *
	 * @param callable $callback
	 * @param array $params
	 */
	public function forEachLB( $callback, array $params = [] );

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
		callable $workCallback = null,
		&$cpIndex = null,
		&$cpClientId = null
	);

	/**
	 * Commit all replica DB transactions so as to flush any REPEATABLE-READ or SSI snapshot
	 *
	 * This only applies to the instantiated tracked load balancer instances.
	 *
	 * This is useful for getting rid of stale data from an implicit transaction round
	 *
	 * @param string $fname Caller name
	 */
	public function flushReplicaSnapshots( $fname = __METHOD__ );

	/**
	 * Commit open transactions on all connections
	 *
	 * This only applies to the instantiated tracked load balancer instances.
	 *
	 * This is useful for two main cases:
	 *   - a) Committing changes to the masters
	 *   - b) Releasing the snapshot on all connections, primary and replica DBs
	 *
	 * @param string $fname Caller name
	 * @param array $options Options map:
	 *   - maxWriteDuration: abort if more than this much time was spent in write queries
	 */
	public function commitAll( $fname = __METHOD__, array $options = [] );

	/**
	 * Flush any primary transaction snapshots and set DBO_TRX (if DBO_DEFAULT is set)
	 *
	 * The DBO_TRX setting will be reverted to the default in each of these methods:
	 *   - commitPrimaryChanges()
	 *   - rollbackPrimaryChanges()
	 *   - commitAll()
	 *
	 * This only applies to the tracked load balancer instances.
	 *
	 * This allows for custom transaction rounds from any outer transaction scope.
	 *
	 * @param string $fname
	 * @throws DBTransactionError
	 * @since 1.37
	 */
	public function beginPrimaryChanges( $fname = __METHOD__ );

	/**
	 * Commit changes and clear view snapshots on all primary connections
	 *
	 * This only applies to the instantiated tracked load balancer instances.
	 *
	 * @param string $fname Caller name
	 * @param array $options Options map:
	 *   - maxWriteDuration: abort if more than this much time was spent in write queries
	 * @throws DBTransactionError
	 * @since 1.37
	 */
	public function commitPrimaryChanges( $fname = __METHOD__, array $options = [] );

	/**
	 * Rollback changes on all primary connections
	 *
	 * This only applies to the instantiated tracked load balancer instances.
	 *
	 * @param string $fname Caller name
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
	 * @param string $fname Caller name
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
	 * Determine if any lagged replica DB connection was used
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
	 * Waits for the replica DBs to catch up to the current primary position
	 *
	 * Use this when updating very large numbers of rows, as in maintenance scripts,
	 * to avoid causing too much lag. Of course, this is a no-op if there are no replica DBs.
	 *
	 * By default this waits on all DB clusters actually used in this request.
	 * This makes sense when lag being waiting on is caused by the code that does this check.
	 * In that case, setting "ifWritesSince" can avoid the overhead of waiting for clusters
	 * that were not changed since the last wait check. To forcefully wait on a specific cluster
	 * for a given domain, use the 'domain' parameter. To forcefully wait on an "external" cluster,
	 * use the "cluster" parameter.
	 *
	 * Never call this function after a large DB write that is *still* in a transaction.
	 * It only makes sense to call this after the possible lag inducing changes were committed.
	 *
	 * This only applies to the instantiated tracked load balancer instances.
	 *
	 * @param array $opts Optional fields that include:
	 *   - domain: Wait on the load balancer DBs that handles the given domain ID.
	 *   - cluster: Wait on the given external load balancer DBs.
	 *   - timeout: Max wait time. Default: 60 seconds for CLI, 1 second for web.
	 *   - ifWritesSince: Only wait if writes were done since this UNIX timestamp.
	 * @return bool True on success, false if a timeout or error occurred while waiting
	 */
	public function waitForReplication( array $opts = [] );

	/**
	 * Add a callback to be run in every call to waitForReplication() before waiting
	 *
	 * Callbacks must clear any transactions that they start.
	 *
	 * @param string $name Callback name
	 * @param callable|null $callback Use null to unset a callback
	 */
	public function setWaitForReplicationListener( $name, callable $callback = null );

	/**
	 * Get a token asserting that no transaction writes are active on tracked load balancers
	 *
	 * @param string $fname Caller name (e.g. __METHOD__)
	 * @return mixed A value to pass to commitAndWaitForReplication()
	 */
	public function getEmptyTransactionTicket( $fname );

	/**
	 * Call commitPrimaryChanges() and waitForReplication() if $ticket indicates it is safe
	 *
	 * The ticket is used to check that the caller owns the transaction round or can act on
	 * behalf of the caller that owns the transaction round.
	 *
	 * @see commitPrimaryChanges()
	 * @see waitForReplication()
	 *
	 * @param string $fname Caller name (e.g. __METHOD__)
	 * @param mixed $ticket Result of getEmptyTransactionTicket()
	 * @param array $opts Options to waitForReplication()
	 * @return bool True if the wait was successful, false on timeout
	 */
	public function commitAndWaitForReplication( $fname, $ticket, array $opts = [] );

	/**
	 * Get the UNIX timestamp when the client last touched the DB, if they did so recently
	 *
	 * @param DatabaseDomain|string|false $domain Domain ID, or false for the current domain
	 * @return float|false UNIX timestamp; false if not recent or on record
	 */
	public function getChronologyProtectorTouched( $domain = false );

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
	 * @param string $fname Caller name (e.g. __METHOD__)
	 */
	public function closeAll( $fname = __METHOD__ );

	/**
	 * @param string $agent Agent name for query profiling
	 */
	public function setAgentName( $agent );

	/**
	 * Append ?cpPosIndex parameter to a URL for ChronologyProtector purposes if needed
	 *
	 * Note that unlike cookies, this works across domains.
	 *
	 * @param string $url
	 * @param int $index Write counter index
	 * @return string
	 */
	public function appendShutdownCPIndexAsQuery( $url, $index );

	/**
	 * Get the client ID of the ChronologyProtector instance
	 *
	 * @return string Client ID
	 * @since 1.34
	 */
	public function getChronologyProtectorClientId();

	/**
	 * Inject HTTP request header/cookie information during setup of this instance
	 *
	 * @param array $info Map of fields, including:
	 *   - IPAddress : IP address
	 *   - UserAgent : User-Agent HTTP header
	 *   - ChronologyProtection : cookie/header value specifying ChronologyProtector usage
	 *   - ChronologyPositionIndex: timestamp used to get up-to-date DB positions for the agent
	 */
	public function setRequestInfo( array $info );

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
}
