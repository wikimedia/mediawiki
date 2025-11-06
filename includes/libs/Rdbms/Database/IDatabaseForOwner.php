<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

/**
 * Internal interface for relational database handles exposed to their owner
 *
 * Instances are either owned by a LoadBalancer object or owned by the caller that created
 * the instance using a constructor/factory function such as DatabaseFactory::create().
 *
 * @ingroup Database
 * @internal Only for use within the rdbms library
 */
interface IDatabaseForOwner extends IDatabase {

	/**
	 * Run a callback after each time any transaction commits or rolls back
	 *
	 * The callback takes two arguments:
	 *   - IDatabase::TRIGGER_COMMIT or IDatabase::TRIGGER_ROLLBACK
	 *   - This IDatabase object
	 * Callbacks must commit any transactions that they begin.
	 *
	 * Registering a callback here will not affect writesOrCallbacks() pending.
	 *
	 * Since callbacks from this or onTransactionCommitOrIdle() can start and end transactions,
	 * a single call to IDatabase::commit might trigger multiple runs of the listener callbacks.
	 *
	 * @param string $name Callback name
	 * @param callable|null $callback Use null to unset a listener
	 * @since 1.28
	 * @deprecated Since 1.44
	 */
	public function setTransactionListener( $name, ?callable $callback = null );

	/**
	 * @return bool Whether this DB server is running in server-side read-only mode
	 * @throws DBError If an error occurs, {@see query}
	 * @since 1.28
	 */
	public function serverIsReadOnly();

	/**
	 * Get the replication position of this primary DB server
	 *
	 * @return DBPrimaryPos|false Position; false if this is not a primary DB
	 * @throws DBError If an error occurs, {@see query}
	 * @since 1.37
	 */
	public function getPrimaryPos();

	/**
	 * Get the time spend running write queries for this transaction
	 *
	 * High values could be due to scanning, updates, locking, and such.
	 *
	 * @param string $type IDatabase::ESTIMATE_* constant [default: ESTIMATE_ALL]
	 * @return float|false Returns false if not transaction is active
	 * @since 1.26
	 */
	public function pendingWriteQueryDuration( $type = self::ESTIMATE_TOTAL );

	/**
	 * Whether there is a transaction open with either possible write queries
	 * or unresolved pre-commit/commit/resolution callbacks pending
	 *
	 * This does *not* count recurring callbacks, e.g. from setTransactionListener().
	 *
	 * @return bool
	 */
	public function writesOrCallbacksPending();

	/**
	 * @return bool Whether there is a transaction open with possible write queries
	 * @since 1.27
	 */
	public function writesPending();

	/**
	 * Get the list of method names that did write queries for this transaction
	 *
	 * @return array
	 * @since 1.27
	 */
	public function pendingWriteCallers();

	/**
	 * Release important session-level state (named lock, table locks) as post-rollback cleanup
	 *
	 * This should only be called by a load balancer or if the handle is not attached to one.
	 * Also, there must be no chance that a future caller will still be expecting some of the
	 * lost session state.
	 *
	 * Connection and query errors will be suppressed and logged
	 *
	 * @param string $fname Calling function name @phan-mandatory-param
	 * @param string $flush Flush flag, set to a situationally valid IDatabase::FLUSHING_*
	 *   constant to disable warnings about explicitly rolling back implicit transactions.
	 *   This will silently break any ongoing explicit transaction. Only set the flush flag
	 *   if you are sure that it is safe to ignore these warnings in your context.
	 * @throws DBError If an error occurs, {@see query}
	 * @since 1.38
	 */
	public function flushSession( $fname = __METHOD__, $flush = self::FLUSHING_ONE );

	/**
	 * Get the last time that the connection was used to commit a write
	 *
	 * @internal Should only be called from the rdbms library.
	 *
	 * @return float|null UNIX timestamp; null if no writes were committed
	 * @since 1.24
	 */
	public function lastDoneWrites();

	/**
	 * Set the entire array or a particular key of the managing load balancer info array
	 *
	 * Keys matching the IDatabase::LB_* constants are also used internally by subclasses
	 *
	 * @internal should not be called outside of rdbms library.
	 *
	 * @param array|string $nameOrArray The new array or the name of a key to set
	 * @param array|mixed|null $value If $nameOrArray is a string, the new key value (null to unset)
	 */
	public function setLBInfo( $nameOrArray, $value = null );

	/**
	 * Wait for the replica server to catch up to a given primary server position
	 *
	 * Note that this does not start any new transactions.
	 *
	 * Callers might want to flush any existing transaction before invoking this method.
	 * Upon success, this assures that replica server queries will reflect all changes up
	 * to the given position, without interference from prior REPEATABLE-READ snapshots.
	 *
	 * @param DBPrimaryPos $pos
	 * @param int $timeout The maximum number of seconds to wait for synchronisation
	 * @return int|null Zero if the replica DB server was past that position already,
	 *   greater than zero if we waited for some period of time, less than
	 *   zero if it timed out, and null on error
	 * @throws DBError If an error occurs, {@see query}
	 * @since 1.37
	 */
	public function primaryPosWait( DBPrimaryPos $pos, $timeout );
}
