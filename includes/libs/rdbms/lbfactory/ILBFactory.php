<?php
/**
 * Generator and manager of database load balancing objects
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

use InvalidArgumentException;

/**
 * An interface for generating database load balancers
 * @ingroup Database
 * @since 1.28
 */
interface ILBFactory {
	/** @var int Don't save DB positions at all */
	const SHUTDOWN_NO_CHRONPROT = 0; // don't save DB positions at all
	/** @var int Save DB positions, but don't wait on remote DCs */
	const SHUTDOWN_CHRONPROT_ASYNC = 1;
	/** @var int Save DB positions, waiting on all DCs */
	const SHUTDOWN_CHRONPROT_SYNC = 2;

	/** @var string Default main LB cluster name (do not change this) */
	const CLUSTER_MAIN_DEFAULT = 'DEFAULT';

	/**
	 * Construct a manager of ILoadBalancer objects
	 *
	 * Sub-classes will extend the required keys in $conf with additional parameters
	 *
	 * @param array $conf Array with keys:
	 *  - localDomain: A DatabaseDomain or domain ID string.
	 *  - readOnlyReason: Reason the master DB is read-only if so [optional]
	 *  - srvCache: BagOStuff object for server cache [optional]
	 *  - memStash: BagOStuff object for cross-datacenter memory storage [optional]
	 *  - wanCache: WANObjectCache object [optional]
	 *  - hostname: The name of the current server [optional]
	 *  - cliMode: Whether the execution context is a CLI script. [optional]
	 *  - maxLag: Try to avoid DB replicas with lag above this many seconds [optional]
	 *  - profiler: Class name or instance with profileIn()/profileOut() methods. [optional]
	 *  - trxProfiler: TransactionProfiler instance. [optional]
	 *  - replLogger: PSR-3 logger instance. [optional]
	 *  - connLogger: PSR-3 logger instance. [optional]
	 *  - queryLogger: PSR-3 logger instance. [optional]
	 *  - perfLogger: PSR-3 logger instance. [optional]
	 *  - errorLogger: Callback that takes an Exception and logs it. [optional]
	 *  - deprecationLogger: Callback to log a deprecation warning. [optional]
	 *  - secret: Secret string to use for HMAC hashing [optional]
	 * @throws InvalidArgumentException
	 */
	public function __construct( array $conf );

	/**
	 * Disables all load balancers. All connections are closed, and any attempt to
	 * open a new connection will result in a DBAccessError.
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
	 * @param DatabaseDomain|string|bool $domain Database domain
	 * @return string Value of $domain if provided or the local domain otherwise
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
	 * Create a new load balancer object. The resulting object will be untracked,
	 * not chronology-protected, and the caller is responsible for cleaning it up.
	 *
	 * This method is for only advanced usage and callers should almost always use
	 * getMainLB() instead. This method can be useful when a table is used as a key/value
	 * store. In that cases, one might want to query it in autocommit mode (DBO_TRX off)
	 * but still use DBO_TRX transaction rounds on other tables.
	 *
	 * @param bool|string $domain Domain ID, or false for the current domain
	 * @param int|null $owner Owner ID of the new instance (e.g. this LBFactory ID)
	 * @return ILoadBalancer
	 */
	public function newMainLB( $domain = false, $owner = null );

	/**
	 * Get a cached (tracked) load balancer object.
	 *
	 * @param bool|string $domain Domain ID, or false for the current domain
	 * @return ILoadBalancer
	 */
	public function getMainLB( $domain = false );

	/**
	 * Create a new load balancer for external storage. The resulting object will be
	 * untracked, not chronology-protected, and the caller is responsible for cleaning it up.
	 *
	 * This method is for only advanced usage and callers should almost always use
	 * getExternalLB() instead. This method can be useful when a table is used as a
	 * key/value store. In that cases, one might want to query it in autocommit mode
	 * (DBO_TRX off) but still use DBO_TRX transaction rounds on other tables.
	 *
	 * @param string $cluster External storage cluster name
	 * @param int|null $owner Owner ID of the new instance (e.g. this LBFactory ID)
	 * @return ILoadBalancer
	 */
	public function newExternalLB( $cluster, $owner = null );

	/**
	 * Get a cached (tracked) load balancer for external storage
	 *
	 * @param string $cluster External storage cluster name
	 * @return ILoadBalancer
	 */
	public function getExternalLB( $cluster );

	/**
	 * Get cached (tracked) load balancers for all main database clusters
	 *
	 * The default cluster name is ILoadBalancer::CLUSTER_MAIN_DEFAULT
	 *
	 * @return ILoadBalancer[] Map of (cluster name => ILoadBalancer)
	 * @since 1.29
	 */
	public function getAllMainLBs();

	/**
	 * Get cached (tracked) load balancers for all external database clusters
	 *
	 * @return ILoadBalancer[] Map of (cluster name => ILoadBalancer)
	 * @since 1.29
	 */
	public function getAllExternalLBs();

	/**
	 * Execute a function for each currently tracked (instantiated) load balancer
	 *
	 * The callback is called with the load balancer as the first parameter,
	 * and $params passed as the subsequent parameters.
	 *
	 * @param callable $callback
	 * @param array $params
	 */
	public function forEachLB( $callback, array $params = [] );

	/**
	 * Prepare all currently tracked (instantiated) load balancers for shutdown
	 *
	 * @param int $mode One of the class SHUTDOWN_* constants
	 * @param callable|null $workCallback Work to mask ChronologyProtector writes
	 * @param int|null &$cpIndex Position key write counter for ChronologyProtector
	 * @param string|null &$cpClientId Client ID hash for ChronologyProtector
	 */
	public function shutdown(
		$mode = self::SHUTDOWN_CHRONPROT_SYNC,
		callable $workCallback = null,
		&$cpIndex = null,
		&$cpClientId = null
	);

	/**
	 * Commit all replica DB transactions so as to flush any REPEATABLE-READ or SSI snapshot
	 *
	 * This is useful for getting rid of stale data from an implicit transaction round
	 *
	 * @param string $fname Caller name
	 */
	public function flushReplicaSnapshots( $fname = __METHOD__ );

	/**
	 * Commit open transactions on all connections. This is useful for two main cases:
	 *   - a) To commit changes to the masters.
	 *   - b) To release the snapshot on all connections, master and replica DBs.
	 * @param string $fname Caller name
	 * @param array $options Options map:
	 *   - maxWriteDuration: abort if more than this much time was spent in write queries
	 */
	public function commitAll( $fname = __METHOD__, array $options = [] );

	/**
	 * Flush any master transaction snapshots and set DBO_TRX (if DBO_DEFAULT is set)
	 *
	 * The DBO_TRX setting will be reverted to the default in each of these methods:
	 *   - commitMasterChanges()
	 *   - rollbackMasterChanges()
	 *   - commitAll()
	 *
	 * This allows for custom transaction rounds from any outer transaction scope.
	 *
	 * @param string $fname
	 * @throws DBTransactionError
	 */
	public function beginMasterChanges( $fname = __METHOD__ );

	/**
	 * Commit changes and clear view snapshots on all master connections
	 * @param string $fname Caller name
	 * @param array $options Options map:
	 *   - maxWriteDuration: abort if more than this much time was spent in write queries
	 * @throws DBTransactionError
	 */
	public function commitMasterChanges( $fname = __METHOD__, array $options = [] );

	/**
	 * Rollback changes on all master connections
	 * @param string $fname Caller name
	 */
	public function rollbackMasterChanges( $fname = __METHOD__ );

	/**
	 * Check if an explicit transaction round is active
	 * @return bool
	 * @since 1.29
	 */
	public function hasTransactionRound();

	/**
	 * Check if transaction rounds can be started, committed, or rolled back right now
	 *
	 * This can be used as a recusion guard to avoid exceptions in transaction callbacks
	 *
	 * @return bool
	 * @since 1.32
	 */
	public function isReadyForRoundOperations();

	/**
	 * Determine if any master connection has pending changes
	 * @return bool
	 */
	public function hasMasterChanges();

	/**
	 * Detemine if any lagged replica DB connection was used
	 * @return bool
	 */
	public function laggedReplicaUsed();

	/**
	 * Determine if any master connection has pending/written changes from this request
	 * @param float|null $age How many seconds ago is "recent" [defaults to LB lag wait timeout]
	 * @return bool
	 */
	public function hasOrMadeRecentMasterChanges( $age = null );

	/**
	 * Waits for the replica DBs to catch up to the current master position
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
	 * @param array $opts Optional fields that include:
	 *   - domain : wait on the load balancer DBs that handles the given domain ID
	 *   - cluster : wait on the given external load balancer DBs
	 *   - timeout : Max wait time. Default: 60 seconds for CLI, 1 second for web.
	 *   - ifWritesSince: Only wait if writes were done since this UNIX timestamp
	 * @return bool True on success, false if a timeout or error occurred while waiting
	 */
	public function waitForReplication( array $opts = [] );

	/**
	 * Add a callback to be run in every call to waitForReplication() before waiting
	 *
	 * Callbacks must clear any transactions that they start
	 *
	 * @param string $name Callback name
	 * @param callable|null $callback Use null to unset a callback
	 */
	public function setWaitForReplicationListener( $name, callable $callback = null );

	/**
	 * Get a token asserting that no transaction writes are active
	 *
	 * @param string $fname Caller name (e.g. __METHOD__)
	 * @return mixed A value to pass to commitAndWaitForReplication()
	 */
	public function getEmptyTransactionTicket( $fname );

	/**
	 * Convenience method for safely running commitMasterChanges()/waitForReplication()
	 *
	 * This will commit and wait unless $ticket indicates it is unsafe to do so
	 *
	 * @param string $fname Caller name (e.g. __METHOD__)
	 * @param mixed $ticket Result of getEmptyTransactionTicket()
	 * @param array $opts Options to waitForReplication()
	 * @return bool True if the wait was successful, false on timeout
	 */
	public function commitAndWaitForReplication( $fname, $ticket, array $opts = [] );

	/**
	 * @param string $dbName DB master name (e.g. "db1052")
	 * @return float|bool UNIX timestamp when client last touched the DB or false if not recent
	 */
	public function getChronologyProtectorTouched( $dbName );

	/**
	 * Disable the ChronologyProtector for all load balancers
	 *
	 * This can be called at the start of special API entry points
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
	 * Close all open database connections on all open load balancers.
	 */
	public function closeAll();

	/**
	 * @param string $agent Agent name for query profiling
	 */
	public function setAgentName( $agent );

	/**
	 * Append ?cpPosIndex parameter to a URL for ChronologyProtector purposes if needed
	 *
	 * Note that unlike cookies, this works across domains
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
	 * @param array $info Map of fields, including:
	 *   - IPAddress : IP address
	 *   - UserAgent : User-Agent HTTP header
	 *   - ChronologyProtection : cookie/header value specifying ChronologyProtector usage
	 *   - ChronologyPositionIndex: timestamp used to get up-to-date DB positions for the agent
	 */
	public function setRequestInfo( array $info );

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
	 * @param string[] $aliases
	 * @since 1.31
	 */
	public function setIndexAliases( array $aliases );
}
