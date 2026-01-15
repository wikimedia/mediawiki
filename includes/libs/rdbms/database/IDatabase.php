<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

use Exception;
use Wikimedia\ScopedCallback;

/**
 * @defgroup Database Database
 * This group deals with database interface functions
 * and query specifics/optimisations.
 */

/**
 * Interface to a relational database
 *
 * This is used for both primary databases and replicas, and used for both concrete
 * connections, as well as wrappers around shared connections, like DBConnRef.
 *
 * As such, callers should not assume that the object represents a live connection
 * (when using DBConnRef, the object may lazily defer the connection to the first query),
 * and should not assume that they have complete control over the connection
 * (when using DBConnRef, multiple objects may automatically share and reuse the same
 * underlying connection).
 *
 * @ingroup Database
 */
interface IDatabase extends IReadableDatabase {
	/** Callback triggered immediately due to no active transaction */
	public const TRIGGER_IDLE = 1;
	/** Callback triggered by COMMIT */
	public const TRIGGER_COMMIT = 2;
	/** Callback triggered by ROLLBACK */
	public const TRIGGER_ROLLBACK = 3;
	/** Callback triggered by atomic section cancel (ROLLBACK TO SAVEPOINT) */
	public const TRIGGER_CANCEL = 4;

	/** Transaction is requested by regular caller outside of the DB layer */
	public const TRANSACTION_EXPLICIT = '';
	/** Transaction is requested internally via DBO_TRX/startAtomic() */
	public const TRANSACTION_INTERNAL = 'implicit';

	/** Atomic section is not cancelable */
	public const ATOMIC_NOT_CANCELABLE = '';
	/** Atomic section is cancelable */
	public const ATOMIC_CANCELABLE = 'cancelable';

	/** Commit/rollback is from outside the IDatabase handle and connection manager */
	public const FLUSHING_ONE = '';
	/**
	 * Commit/rollback is from the owning connection manager for the IDatabase handle
	 * @internal Only for use within the rdbms library
	 */
	public const FLUSHING_ALL_PEERS = 'flush';
	/**
	 * Commit/rollback is from the IDatabase handle internally
	 * @internal Only for use within the rdbms library
	 */
	public const FLUSHING_INTERNAL = 'flush-internal';

	/** Estimate total time (RTT, scanning, waiting on locks, applying) */
	public const ESTIMATE_TOTAL = 'total';
	/** Estimate time to apply (scanning, applying) */
	public const ESTIMATE_DB_APPLY = 'apply';

	/** Flag to return the lock acquisition timestamp (null if not acquired) */
	public const LOCK_TIMESTAMP = 1;

	/**
	 * Field for getLBInfo()/setLBInfo(); relevant transaction round level (1 or 0)
	 * @internal For use in the rdbms library only
	 */
	public const LB_TRX_ROUND_LEVEL = 'trxRoundLevel';
	/**
	 * Field for getLBInfo()/setLBInfo(); relevant transaction round owner name or null
	 * @internal For use in the rdbms library only
	 */
	public const LB_TRX_ROUND_FNAME = 'trxRoundOwner';
	/**
	 * Field for getLBInfo()/setLBInfo(); configured read-only mode explanation or false
	 * @internal For use in the rdbms library only
	 */
	public const LB_READ_ONLY_REASON = 'readOnlyReason';
	/**
	 * Alias to LB_TRX_ROUND_FNAME
	 * @deprecated Since 1.44
	 */
	public const LB_TRX_ROUND_ID = self::LB_TRX_ROUND_FNAME;

	/** Primary server than can stream writes to replica servers */
	public const ROLE_STREAMING_MASTER = 'streaming-master';
	/** Replica server that receives writes from a primary server */
	public const ROLE_STREAMING_REPLICA = 'streaming-replica';
	/** Replica server within a static dataset */
	public const ROLE_STATIC_CLONE = 'static-clone';
	/** Server with unknown topology role */
	public const ROLE_UNKNOWN = 'unknown';

	/**
	 * Gets the current transaction level.
	 *
	 * Historically, transactions were allowed to be "nested". This is no
	 * longer supported, so this function really only returns a boolean.
	 *
	 * @return int The previous value
	 */
	public function trxLevel();

	/**
	 * Get the UNIX timestamp of the time that the transaction was established
	 *
	 * This can be used to reason about the staleness of SELECT data in REPEATABLE-READ
	 * transaction isolation level. Callers can assume that if a view-snapshot isolation
	 * is used, then the data read by SQL queries is *at least* up to date to that point
	 * (possibly more up-to-date since the first SELECT defines the snapshot).
	 *
	 * @return float|null Returns null if there is not active transaction
	 * @since 1.25
	 */
	public function trxTimestamp();

	/**
	 * Check whether there is a transaction open at the specific request of a caller
	 *
	 * Explicit transactions are spawned by begin(), startAtomic(), and doAtomicSection().
	 * Note that explicit transactions should not be confused with explicit transaction rounds.
	 *
	 * @return bool
	 * @since 1.28
	 */
	public function explicitTrxActive();

	/**
	 * Get properties passed down from the server info array of the load balancer
	 *
	 * @internal should not be called outside of rdbms library.
	 *
	 * @param string|null $name The entry of the info array to get, or null to get the whole array
	 * @return array|mixed|null
	 */
	public function getLBInfo( $name = null );

	/**
	 * Get the sequence-based ID assigned by the last query method call
	 *
	 * This method should only be called when all the following hold true:
	 *   - (a) A method call was made to insert(), upsert(), replace(), or insertSelect()
	 *   - (b) The method call attempts to insert exactly one row
	 *   - (c) The method call omits the value of exactly one auto-increment column
	 *   - (d) The method call succeeded
	 *   - (e) No subsequent method calls were made, with the exception of affectedRows(),
	 *         lastErrno(), lastError(), and getType()
	 *
	 * In all other cases, the return value is unspecified.
	 *
	 * When the query method is either insert() with "IGNORE", upsert(), or insertSelect(),
	 * callers should first check affectedRows() before calling this method, making sure that
	 * the query method actually created a row. Otherwise, an ID from a previous insert might
	 * be incorrectly assumed to belong to last insert.
	 *
	 * @return int
	 */
	public function insertId();

	/**
	 * Get the number of rows affected by the last query method call
	 *
	 * This method should only be called when all the following hold true:
	 *   - (a) A method call was made to insert(), upsert(), replace(), update(), delete(),
	 *         insertSelect(), query() with a non-SELECT statement, or queryMulti() with a
	 *         non-SELECT terminal statement
	 *   - (b) The method call succeeded
	 *   - (c) No subsequent method calls were made, with the exception of affectedRows(),
	 *         lastErrno(), lastError(), and getType()
	 *
	 * In all other cases, the return value is unspecified.
	 *
	 * UPDATE queries consider rows affected even when all their new column values match
	 * the previous values. Such rows can be excluded from the count by changing the WHERE
	 * clause to filter them out.
	 *
	 * If the last query method call was to query() or queryMulti(), then the results
	 * are based on the (last) statement provided to that call and are driver-specific.
	 *
	 * @return int
	 */
	public function affectedRows();

	/**
	 * Run an SQL query statement and return the result
	 *
	 * If a connection loss is detected, then an attempt to reconnect will be made.
	 * For queries that involve no larger transactions or locks, they will be re-issued
	 * for convenience, provided the connection was re-established.
	 *
	 * In new code, the query wrappers select(), insert(), update(), delete(),
	 * etc. should be used where possible, since they give much better DBMS
	 * independence and automatically quote or validate user input in a variety
	 * of contexts. This function is generally only useful for queries which are
	 * explicitly DBMS-dependent and are unsupported by the query wrappers, such
	 * as CREATE TABLE.
	 *
	 * However, the query wrappers themselves should call this function.
	 *
	 * Callers should avoid the use of statements like BEGIN, COMMIT, and ROLLBACK.
	 * Methods like startAtomic(), endAtomic(), and cancelAtomic() can be used instead.
	 *
	 * @param string|Query $sql Single-statement SQL query
	 * @param-taint $sql exec_sql
	 * @param string $fname Caller name; used for profiling/SHOW PROCESSLIST comments @phan-mandatory-param
	 * @param int $flags Bit field of ISQLPlatform::QUERY_* constants
	 * @return bool|IResultWrapper True for a successful write query, IResultWrapper object
	 *     for a successful read query, or false on failure if QUERY_SILENCE_ERRORS is set
	 * @return-taint tainted
	 * @throws DBQueryError If the query is issued, fails, and QUERY_SILENCE_ERRORS is not set
	 * @throws DBExpectedError If the query is not, and cannot, be issued yet (non-DBQueryError)
	 * @throws DBError If the query is inherently not allowed (non-DBExpectedError)
	 */
	public function query( $sql, $fname = __METHOD__, $flags = 0 );

	/**
	 * Get an UpdateQueryBuilder bound to this connection. This is overridden by
	 * DBConnRef.
	 *
	 * @note A new query builder must be created per query. Query builders
	 *   should not be reused since this uses a fluent interface and the state of
	 *   the builder changes during the query which may cause unexpected results.
	 *
	 * @return UpdateQueryBuilder
	 */
	public function newUpdateQueryBuilder(): UpdateQueryBuilder;

	/**
	 * Get an DeleteQueryBuilder bound to this connection. This is overridden by
	 * DBConnRef.
	 *
	 * @note A new query builder must be created per query. Query builders
	 *   should not be reused since this uses a fluent interface and the state of
	 *   the builder changes during the query which may cause unexpected results.
	 *
	 * @return DeleteQueryBuilder
	 */
	public function newDeleteQueryBuilder(): DeleteQueryBuilder;

	/**
	 * Get an InsertQueryBuilder bound to this connection. This is overridden by
	 * DBConnRef.
	 *
	 * @note A new query builder must be created per query. Query builders
	 *   should not be reused since this uses a fluent interface and the state of
	 *   the builder changes during the query which may cause unexpected results.
	 *
	 * @return InsertQueryBuilder
	 */
	public function newInsertQueryBuilder(): InsertQueryBuilder;

	/**
	 * Get an ReplaceQueryBuilder bound to this connection. This is overridden by
	 * DBConnRef.
	 *
	 * @note A new query builder must be created per query. Query builders
	 *   should not be reused since this uses a fluent interface and the state of
	 *   the builder changes during the query which may cause unexpected results.
	 *
	 * @return ReplaceQueryBuilder
	 */
	public function newReplaceQueryBuilder(): ReplaceQueryBuilder;

	/**
	 * Lock all rows meeting the given conditions/options FOR UPDATE
	 *
	 * @param string|string[] $table The unqualified name of table(s) (use an array for a join)
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param string|IExpression|array<string,?scalar|non-empty-array<int,?scalar>|RawSQLValue>|array<int,string|IExpression> $conds
	 *   Condition in the format of IDatabase::select() conditions
	 * @param string $fname Function name for profiling @phan-mandatory-param
	 * @param array $options Options for select ("FOR UPDATE" is added automatically)
	 * @param array $join_conds Join conditions
	 * @return int Number of matching rows found (and locked)
	 * @throws DBError If an error occurs, {@see query}
	 * @since 1.32
	 * @deprecated since 1.43; Use SelectQueryBuilder::acquireRowLocks
	 */
	public function lockForUpdate(
		$table, $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	);

	/**
	 * Insert row(s) into a table, in the provided order
	 *
	 * This operation will be seen by affectedRows()/insertId() as one query statement,
	 * regardless of how many statements are actually sent by the class implementation.
	 *
	 * @internal callers outside of rdbms library should use InsertQueryBuilder instead.
	 *
	 * @param string $table The unqualified name of a table
	 * @param array|array[] $rows Row(s) to insert, as either:
	 *   - A string-keyed map of (column name => value) defining a new row. Values are
	 *     treated as literals and quoted appropriately; null is interpreted as NULL.
	 *   - An integer-keyed list of such string-keyed maps, defining a list of new rows.
	 *     The keys in each map must be identical to each other and in the same order.
	 *     The rows must not collide with each other.
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling @phan-mandatory-param
	 * @param string|array $options Combination map/list where each string-keyed entry maps
	 *   a non-boolean option to the option parameters and each integer-keyed value is the
	 *   name of a boolean option. Supported options are:
	 *     - IGNORE: Boolean: skip insertion of rows that would cause unique key conflicts.
	 *       IDatabase::affectedRows() can be used to determine how many rows were inserted.
	 * @return bool Return true if no exception was thrown (deprecated since 1.33)
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function insert( $table, $rows, $fname = __METHOD__, $options = [] );

	/**
	 * Update all rows in a table that match a given condition
	 *
	 * This operation will be seen by affectedRows()/insertId() as one query statement,
	 * regardless of how many statements are actually sent by the class implementation.
	 *
	 * @internal callers outside of rdbms library should use UpdateQueryBuilder instead.
	 *
	 * @param string $table The unqualified name of a table
	 * @param-taint $table exec_sql
	 * @param array<string,?scalar|RawSQLValue>|array<int,string> $set
	 *   Combination map/list where each string-keyed entry maps a column
	 *   to a literal assigned value and each integer-keyed value is a SQL expression in the
	 *   format of a column assignment within UPDATE...SET. The (column => value) entries are
	 *   convenient due to automatic value quoting and conversion of null to NULL. The SQL
	 *   assignment format is useful for updates like "column = column + X". All assignments
	 *   have no defined execution order, so they should not depend on each other. Do not
	 *   modify AUTOINCREMENT or UUID columns in assignments.
	 * @param-taint $set exec_sql_numkey
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param string|IExpression|array<string,?scalar|non-empty-array<int,?scalar>|RawSQLValue>|array<int,string|IExpression> $conds
	 *   Condition in the format of IDatabase::select() conditions.
	 *   In order to prevent possible performance or replication issues or damaging a data
	 *   accidentally, an empty condition for 'update' queries isn't allowed.
	 *   IDatabase::ALL_ROWS should be passed explicitly in order to update all rows.
	 * @param-taint $conds exec_sql_numkey
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling @phan-mandatory-param
	 * @param-taint $fname exec_sql
	 * @param string|array $options Combination map/list where each string-keyed entry maps
	 *   a non-boolean option to the option parameters and each integer-keyed value is the
	 *   name of a boolean option. Supported options are:
	 *     - IGNORE: Boolean: skip update of rows that would cause unique key conflicts.
	 *       IDatabase::affectedRows() includes all matching rows,
	 *       that includes also rows not updated due to key conflict.
	 * @param-taint $options none
	 * @return bool Return true if no exception was thrown (deprecated since 1.33)
	 * @return-taint none
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function update( $table, $set, $conds, $fname = __METHOD__, $options = [] );

	/**
	 * Insert row(s) into a table, in the provided order, while deleting conflicting rows
	 *
	 * Conflicts are determined by the provided unique indexes. Note that it is possible
	 * for the provided rows to conflict even among themselves; it is preferable for the
	 * caller to de-duplicate such input beforehand.
	 *
	 * Note some important implications of the deletion semantics:
	 *   - If the table has an AUTOINCREMENT column and $rows omit that column, then any
	 *     conflicting existing rows will be replaced with newer having higher values for
	 *     that column, even if nothing else changed.
	 *   - There might be worse contention than upsert() due to the use of gap-locking.
	 *     This does not apply to RDBMS types that use predicate locking nor those that
	 *     just lock the whole table or databases anyway.
	 *
	 * This operation will be seen by affectedRows()/insertId() as one query statement,
	 * regardless of how many statements are actually sent by the class implementation.
	 *
	 * @internal callers outside of rdbms library should use ReplaceQueryBuilder instead.
	 *
	 * @param string $table The unqualified name of a table
	 * @param string|string[]|string[][] $uniqueKeys Column name or non-empty list of column
	 *   name lists that define all applicable unique keys on the table. There must only be
	 *   one such key. Each unique key on the table is "applicable" unless either:
	 *     - It involves an AUTOINCREMENT column for which no values are assigned in $rows
	 *     - It involves a UUID column for which newly generated UUIDs are assigned in $rows
	 * @param array|array[] $rows Row(s) to insert, in the form of either:
	 *   - A string-keyed map of (column name => value) defining a new row. Values are
	 *     treated as literals and quoted appropriately; null is interpreted as NULL.
	 *     Columns belonging to a key in $uniqueKeys must be defined here and non-null.
	 *   - An integer-keyed list of such string-keyed maps, defining a list of new rows.
	 *     The keys in each map must be identical to each other and in the same order.
	 *     The rows must not collide with each other.
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling @phan-mandatory-param
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function replace( $table, $uniqueKeys, $rows, $fname = __METHOD__ );

	/**
	 * Upsert row(s) into a table, in the provided order, while updating conflicting rows
	 *
	 * Conflicts are determined by the provided unique indexes. Note that it is possible
	 * for the provided rows to conflict even among themselves; it is preferable for the
	 * caller to de-duplicate such input beforehand.
	 *
	 * This operation will be seen by affectedRows()/insertId() as one query statement,
	 * regardless of how many statements are actually sent by the class implementation.
	 *
	 * @internal callers outside of rdbms library should use InsertQueryBuilder instead.
	 *
	 * @param string $table The unqualified name of a table
	 * @param array|array[] $rows Row(s) to insert, in the form of either:
	 *   - A string-keyed map of (column name => value) defining a new row. Values are
	 *     treated as literals and quoted appropriately; null is interpreted as NULL.
	 *     Columns belonging to a key in $uniqueKeys must be defined here and non-null.
	 *   - An integer-keyed list of such string-keyed maps, defining a list of new rows.
	 *     The keys in each map must be identical to each other and in the same order.
	 *     The rows must not collide with each other.
	 * @param string|string[]|string[][] $uniqueKeys Column name or non-empty list of column
	 *   name lists that define all applicable unique keys on the table. There must only be
	 *   one such key. Each unique key on the table is "applicable" unless either:
	 *     - It involves an AUTOINCREMENT column for which no values are assigned in $rows
	 *     - It involves a UUID column for which newly generated UUIDs are assigned in $rows
	 * @param array<string,?scalar|RawSQLValue>|array<int,string> $set
	 *   Combination map/list where each string-keyed entry maps a column
	 *   to a literal assigned value and each integer-keyed value is a SQL assignment expression
	 *   of the form "<unquoted alphanumeric column> = <SQL expression>". The (column => value)
	 *   entries are convenient due to automatic value quoting and conversion of null to NULL.
	 *   The SQL assignment entries are useful for updates like "column = column + X". All of
	 *   the assignments have no defined execution order, so callers should make sure that they
	 *   not depend on each other. Do not modify AUTOINCREMENT or UUID columns in assignments,
	 *   even if they are just "secondary" unique keys. For multi-row upserts, use
	 *   buildExcludedValue() to reference the value of a column from the corresponding row
	 *   in $rows that conflicts with the current row.
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling @phan-mandatory-param
	 * @throws DBError If an error occurs, {@see query}
	 * @since 1.22
	 */
	public function upsert(
		$table, array $rows, $uniqueKeys, array $set, $fname = __METHOD__
	);

	/**
	 * Delete all rows in a table that match a condition which includes a join
	 *
	 * For safety, an empty $conds will not delete everything. If you want to
	 * delete all rows where the join condition matches, set $conds=IDatabase::ALL_ROWS.
	 *
	 * DO NOT put the join condition in $conds.
	 *
	 * This operation will be seen by affectedRows()/insertId() as one query statement,
	 * regardless of how many statements are actually sent by the class implementation.
	 *
	 * @param string $delTable The unqualified name of the table to delete rows from.
	 * @param string $joinTable The unqualified name of the reference table to join on.
	 * @param string $delVar The variable to join on, in the first table.
	 * @param string $joinVar The variable to join on, in the second table.
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param string|IExpression|array<string,?scalar|non-empty-array<int,?scalar>|RawSQLValue>|array<int,string|IExpression> $conds
	 *   Condition array of field names mapped to variables,
	 *   ANDed together in the WHERE clause
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling @phan-mandatory-param
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function deleteJoin(
		$delTable,
		$joinTable,
		$delVar,
		$joinVar,
		$conds,
		$fname = __METHOD__
	);

	/**
	 * Delete all rows in a table that match a condition
	 *
	 * This operation will be seen by affectedRows()/insertId() as one query statement,
	 * regardless of how many statements are actually sent by the class implementation.
	 *
	 * @internal callers outside of rdbms library should use DeleteQueryBuilder instead.
	 *
	 * @param string $table The unqualified name of a table
	 * @param-taint $table exec_sql
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param string|IExpression|array<string,?scalar|non-empty-array<int,?scalar>|RawSQLValue>|array<int,string|IExpression> $conds
	 *   Array of conditions. See $conds in IDatabase::select()
	 *   In order to prevent possible performance or replication issues or damaging a data
	 *   accidentally, an empty condition for 'delete' queries isn't allowed.
	 *   IDatabase::ALL_ROWS should be passed explicitly in order to delete all rows.
	 * @param-taint $conds exec_sql_numkey
	 * @param string $fname Name of the calling function @phan-mandatory-param
	 * @param-taint $fname exec_sql
	 * @return bool Return true if no exception was thrown (deprecated since 1.33)
	 * @return-taint none
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function delete( $table, $conds, $fname = __METHOD__ );

	/**
	 * INSERT SELECT wrapper
	 *
	 * @warning If the insert will use an auto-increment or sequence to
	 *  determine the value of a column, this may break replication on
	 *  databases using statement-based replication if the SELECT is not
	 *  deterministically ordered.
	 *
	 * This operation will be seen by affectedRows()/insertId() as one query statement,
	 * regardless of how many statements are actually sent by the class implementation.
	 *
	 * @param string $destTable Unqualified name of destination table
	 * @param string|array $srcTable Unqualified name of source table(s) (use an array for a join)
	 * @param array $varMap Must be an associative array of the form
	 *    [ 'dest1' => 'source1', ... ]. Source items may be literals
	 *    rather than field names, but strings should be quoted with
	 *    IDatabase::addQuotes()
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param string|IExpression|array<string,?scalar|non-empty-array<int,?scalar>|RawSQLValue>|array<int,string|IExpression> $conds
	 *    Condition array. See $conds in IDatabase::select() for
	 *    the details of the format of condition arrays. May be "*" to copy the
	 *    whole table.
	 * @param string $fname The function name of the caller, from __METHOD__ @phan-mandatory-param
	 * @param array $insertOptions Options for the INSERT part of the query, see
	 *    IDatabase::insert() for details. Also, one additional option is
	 *    available: pass 'NO_AUTO_COLUMNS' to hint that the query does not use
	 *    an auto-increment or sequence to determine any column values.
	 * @param array $selectOptions Options for the SELECT part of the query, see
	 *    IDatabase::select() for details.
	 * @param array $selectJoinConds Join conditions for the SELECT part of the query, see
	 *    IDatabase::select() for details.
	 * @return bool Return true if no exception was thrown (deprecated since 1.33)
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function insertSelect(
		$destTable,
		$srcTable,
		$varMap,
		$conds,
		$fname = __METHOD__,
		$insertOptions = [],
		$selectOptions = [],
		$selectJoinConds = []
	);

	/**
	 * Run a callback when the current transaction commits or rolls back
	 *
	 * An error is thrown if no transaction is pending.
	 *
	 * When transaction round mode (DBO_TRX) is set, the callback will run at the end
	 * of the round, just after all peer transactions COMMIT/ROLLBACK.
	 *
	 * This IDatabase instance will start off in auto-commit mode when the callback starts.
	 * The use of other IDatabase handles from the callback should be avoided unless they are
	 * known to be in auto-commit mode. Callbacks that create transactions via begin() or
	 * startAtomic() must have matching calls to commit()/endAtomic().
	 *
	 * Use this method only for the following purposes:
	 *   - (a) Release of cooperative locks on resources
	 *   - (b) Cancellation of in-process deferred tasks
	 *
	 * The callback takes the following arguments:
	 *   - How the current atomic section (if any) or overall transaction (otherwise) ended
	 *     (IDatabase::TRIGGER_COMMIT or IDatabase::TRIGGER_ROLLBACK)
	 *
	 * Callbacks will execute in the order they were enqueued.
	 *
	 * @param callable $callback
	 * @param string $fname Caller name @phan-mandatory-param
	 * @throws DBError If an error occurs, {@see query}
	 * @throws Exception If the callback runs immediately and an error occurs in it
	 * @since 1.28
	 */
	public function onTransactionResolution( callable $callback, $fname = __METHOD__ );

	/**
	 * Run a callback when the current transaction commits or now if there is none
	 *
	 * If there is a transaction and it is rolled back, then the callback is cancelled.
	 *
	 * When transaction round mode (DBO_TRX) is set, the callback will run at the end
	 * of the round, just after all peer transactions COMMIT. If the transaction round
	 * is rolled back, then the callback is cancelled.
	 *
	 * This IDatabase instance will start off in auto-commit mode when the callback starts.
	 * The use of other IDatabase handles from the callback should be avoided unless they are
	 * known to be in auto-commit mode. Callbacks that create transactions via begin() or
	 * startAtomic() must have matching calls to commit()/endAtomic().
	 *
	 * Use this method only for the following purposes:
	 *   - (a) RDBMS updates, prone to lock timeouts/deadlocks, that do not require
	 *         atomicity with respect to the updates in the current transaction (if any)
	 *   - (b) Purges to lightweight cache services due to RDBMS updates
	 *   - (c) Updates to secondary DBs/stores that must only commit once the updates in
	 *         the current transaction (if any) are committed (e.g. insert user account row
	 *         to DB1, then, initialize corresponding LDAP account)
	 *
	 * The callback takes the following arguments:
	 *   - How the transaction ended (IDatabase::TRIGGER_COMMIT or IDatabase::TRIGGER_IDLE)
	 *
	 * Callbacks will execute in the order they were enqueued.
	 *
	 * @param callable $callback
	 * @param string $fname Caller name @phan-mandatory-param
	 * @throws DBError If an error occurs, {@see query}
	 * @throws Exception If the callback runs immediately and an error occurs in it
	 * @since 1.32
	 */
	public function onTransactionCommitOrIdle( callable $callback, $fname = __METHOD__ );

	/**
	 * Run a callback before the current transaction commits or now if there is none
	 *
	 * If there is a transaction and it is rolled back, then the callback is cancelled.
	 *
	 * When transaction round mode (DBO_TRX) is set, the callback will run at the end
	 * of the round, just after all peer transactions COMMIT. If the transaction round
	 * is rolled back, then the callback is cancelled.
	 *
	 * If there is no current transaction, one will be created to wrap the callback.
	 * Callbacks cannot use begin()/commit() to manage transactions. The use of other
	 * IDatabase handles from the callback should be avoided.
	 *
	 * Use this method only for the following purposes:
	 *   - a) RDBMS updates, prone to lock timeouts/deadlocks, that require atomicity
	 *        with respect to the updates in the current transaction (if any)
	 *   - b) Purges to lightweight cache services due to RDBMS updates
	 *
	 * Callbacks will execute in the order they were enqueued.
	 *
	 * @param callable $callback
	 * @param string $fname Caller name @phan-mandatory-param
	 * @throws DBError If an error occurs, {@see query}
	 * @throws Exception If the callback runs immediately and an error occurs in it
	 * @since 1.22
	 */
	public function onTransactionPreCommitOrIdle( callable $callback, $fname = __METHOD__ );

	/**
	 * Begin an atomic section of SQL statements
	 *
	 * Start an implicit transaction if no transaction is already active, set a savepoint
	 * (if $cancelable is ATOMIC_CANCELABLE), and track the given section name to enforce
	 * that the transaction is not committed prematurely. The end of the section must be
	 * signified exactly once, either by endAtomic() or cancelAtomic(). Sections can have
	 * have layers of inner sections (sub-sections), but all sections must be ended in order
	 * of innermost to outermost. Transactions cannot be started or committed until all
	 * atomic sections are closed.
	 *
	 * ATOMIC_CANCELABLE is useful when the caller needs to handle specific failure cases
	 * by discarding the section's writes.  This should not be used for failures when:
	 *   - upsert() could easily be used instead
	 *   - insert() with IGNORE could easily be used instead
	 *   - select() with FOR UPDATE could be checked before issuing writes instead
	 *   - The failure is from code that runs after the first write but doesn't need to
	 *   - The failures are from contention solvable via onTransactionPreCommitOrIdle()
	 *   - The failures are deadlocks; the RDBMs usually discard the whole transaction
	 *
	 * @note callers must use additional measures for situations involving two or more
	 *   (peer) transactions (e.g. updating two database servers at once). The transaction
	 *   and savepoint logic of this method only applies to this specific IDatabase instance.
	 *
	 * Example usage:
	 * @code
	 *     // Start a transaction if there isn't one already
	 *     $dbw->startAtomic( __METHOD__ );
	 *     // Serialize these thread table updates
	 *     $dbw->select( 'thread', '1', [ 'td_id' => $tid ], __METHOD__, 'FOR UPDATE' );
	 *     // Add a new comment for the thread
	 *     $dbw->insert( 'comment', $row, __METHOD__ );
	 *     $cid = $db->insertId();
	 *     // Update thread reference to last comment
	 *     $dbw->update( 'thread', [ 'td_latest' => $cid ], [ 'td_id' => $tid ], __METHOD__ );
	 *     // Demark the end of this conceptual unit of updates
	 *     $dbw->endAtomic( __METHOD__ );
	 * @endcode
	 *
	 * Example usage (atomic changes that might have to be discarded):
	 * @code
	 *     // Start a transaction if there isn't one already
	 *     $sectionId = $dbw->startAtomic( __METHOD__, $dbw::ATOMIC_CANCELABLE );
	 *     // Create new record metadata row
	 *     $dbw->insert( 'records', $row, __METHOD__ );
	 *     // Figure out where to store the data based on the new row's ID
	 *     $path = $recordDirectory . '/' . $dbw->insertId();
	 *     // Write the record data to the storage system
	 *     $status = $fileBackend->create( [ 'dst' => $path, 'content' => $data ] );
	 *     if ( $status->isOK() ) {
	 *         // Try to cleanup files orphaned by transaction rollback
	 *         $dbw->onTransactionResolution(
	 *             function ( $type ) use ( $fileBackend, $path ) {
	 *                 if ( $type === IDatabase::TRIGGER_ROLLBACK ) {
	 *                     $fileBackend->delete( [ 'src' => $path ] );
	 *                 }
	 *             },
	 *             __METHOD__
	 *         );
	 *         // Demark the end of this conceptual unit of updates
	 *         $dbw->endAtomic( __METHOD__ );
	 *     } else {
	 *         // Discard these writes from the transaction (preserving prior writes)
	 *         $dbw->cancelAtomic( __METHOD__, $sectionId );
	 *     }
	 * @endcode
	 *
	 * @since 1.23
	 * @param string $fname @phan-mandatory-param
	 * @param string $cancelable Pass self::ATOMIC_CANCELABLE to use a
	 *  savepoint and enable self::cancelAtomic() for this section.
	 * @return AtomicSectionIdentifier section ID token
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function startAtomic( $fname = __METHOD__, $cancelable = self::ATOMIC_NOT_CANCELABLE );

	/**
	 * Ends an atomic section of SQL statements
	 *
	 * Ends the next section of atomic SQL statements and commits the transaction
	 * if necessary.
	 *
	 * @since 1.23
	 * @see IDatabase::startAtomic
	 * @param string $fname @phan-mandatory-param
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function endAtomic( $fname = __METHOD__ );

	/**
	 * Cancel an atomic section of SQL statements
	 *
	 * This will roll back only the statements executed since the start of the
	 * most recent atomic section, and close that section. If a transaction was
	 * open before the corresponding startAtomic() call, any statements before
	 * that call are *not* rolled back and the transaction remains open. If the
	 * corresponding startAtomic() implicitly started a transaction, that
	 * transaction is rolled back.
	 *
	 * @note callers must use additional measures for situations involving two or more
	 *   (peer) transactions (e.g. updating two database servers at once). The transaction
	 *   and savepoint logic of startAtomic() are bound to specific IDatabase instances.
	 *
	 * Note that a call to IDatabase::rollback() will also roll back any open atomic sections.
	 *
	 * @note As an optimization to save rountrips, this method may only be called
	 *   when startAtomic() was called with the ATOMIC_CANCELABLE flag.
	 * @since 1.31
	 * @see IDatabase::startAtomic
	 * @param string $fname @phan-mandatory-param
	 * @param AtomicSectionIdentifier|null $sectionId Section ID from startAtomic();
	 *   passing this enables cancellation of unclosed nested sections [optional]
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function cancelAtomic( $fname = __METHOD__, ?AtomicSectionIdentifier $sectionId = null );

	/**
	 * Perform an atomic section of reversible SQL statements from a callback
	 *
	 * The $callback takes the following arguments:
	 *   - This database object
	 *   - The value of $fname
	 *
	 * This will execute the callback inside a pair of startAtomic()/endAtomic() calls.
	 * If any exception occurs during execution of the callback, it will be handled as follows:
	 *   - If $cancelable is ATOMIC_CANCELABLE, cancelAtomic() will be called to back out any
	 *     (and only) statements executed during the atomic section. If that succeeds, then the
	 *     exception will be re-thrown; if it fails, then a different exception will be thrown
	 *     and any further query attempts will fail until rollback() is called.
	 *   - If $cancelable is ATOMIC_NOT_CANCELABLE, cancelAtomic() will be called to mark the
	 *     end of the section and the error will be re-thrown. Any further query attempts will
	 *     fail until rollback() is called.
	 *
	 * This method is convenient for letting calls to the caller of this method be wrapped
	 * in a try/catch blocks for exception types that imply that the caller failed but was
	 * able to properly discard the changes it made in the transaction. This method can be
	 * an alternative to explicit calls to startAtomic()/endAtomic()/cancelAtomic().
	 *
	 * Example usage, "RecordStore::save" method:
	 * @code
	 *     $dbw->doAtomicSection( __METHOD__, function ( $dbw ) use ( $record ) {
	 *         // Create new record metadata row
	 *         $dbw->insert( 'records', $record->toArray(), __METHOD__ );
	 *         // Figure out where to store the data based on the new row's ID
	 *         $path = $this->recordDirectory . '/' . $dbw->insertId();
	 *         // Write the record data to the storage system;
	 *         // blob store throws StoreFailureException on failure
	 *         $this->blobStore->create( $path, $record->getJSON() );
	 *         // Try to cleanup files orphaned by transaction rollback
	 *         $dbw->onTransactionResolution(
	 *             function ( $type ) use ( $path ) {
	 *                 if ( $type === IDatabase::TRIGGER_ROLLBACK ) {
	 *                     $this->blobStore->delete( $path );
	 *                 }
	 *             },
	 *             __METHOD__
	 *          );
	 *     }, $dbw::ATOMIC_CANCELABLE );
	 * @endcode
	 *
	 * Example usage, caller of the "RecordStore::save" method:
	 * @code
	 *     $dbw->startAtomic( __METHOD__ );
	 *     // ...various SQL writes happen...
	 *     try {
	 *         $recordStore->save( $record );
	 *     } catch ( StoreFailureException $e ) {
	 *         // ...various SQL writes happen...
	 *     }
	 *     // ...various SQL writes happen...
	 *     $dbw->endAtomic( __METHOD__ );
	 * @endcode
	 *
	 * @see Database::startAtomic
	 * @see Database::endAtomic
	 * @see Database::cancelAtomic
	 *
	 * @param string $fname Caller name (usually __METHOD__) @phan-mandatory-param
	 * @param callable $callback Callback that issues write queries
	 * @param string $cancelable Pass self::ATOMIC_CANCELABLE to use a
	 *  savepoint and enable self::cancelAtomic() for this section.
	 * @return mixed Result of the callback (since 1.28)
	 * @throws DBError If an error occurs, {@see query}
	 * @throws Exception If an error occurs in the callback
	 * @since 1.27; prior to 1.31 this did a rollback() instead of
	 *  cancelAtomic(), and assumed no callers up the stack would ever try to
	 *  catch the exception.
	 */
	public function doAtomicSection(
		$fname, callable $callback, $cancelable = self::ATOMIC_NOT_CANCELABLE
	);

	/**
	 * Begin a transaction
	 *
	 * Only call this from code with outer transaction scope.
	 * See https://www.mediawiki.org/wiki/Database_transactions for details.
	 * Nesting of transactions is not supported.
	 *
	 * Note that when the DBO_TRX flag is set (which is usually the case for web
	 * requests, but not for maintenance scripts), any previous database query
	 * will have started a transaction automatically.
	 *
	 * Nesting of transactions is not supported. Attempts to nest transactions
	 * will cause a warning, unless the current transaction was started
	 * automatically because of the DBO_TRX flag.
	 *
	 * @param string $fname Calling function name @phan-mandatory-param
	 * @param string $mode A situationally valid IDatabase::TRANSACTION_* constant [optional]
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function begin( $fname = __METHOD__, $mode = self::TRANSACTION_EXPLICIT );

	/**
	 * Commits a transaction previously started using begin()
	 *
	 * If no transaction is in progress, a warning is issued.
	 *
	 * Only call this from code with outer transaction scope.
	 * See https://www.mediawiki.org/wiki/Database_transactions for details.
	 * Nesting of transactions is not supported.
	 *
	 * @param string $fname @phan-mandatory-param
	 * @param string $flush Flush flag, set to situationally valid IDatabase::FLUSHING_*
	 *   constant to disable warnings about explicitly committing implicit transactions,
	 *   or calling commit when no transaction is in progress.
	 *   This will trigger an exception if there is an ongoing explicit transaction.
	 *   Only set the flush flag if you are sure that these warnings are not applicable,
	 *   and no explicit transactions are open.
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function commit( $fname = __METHOD__, $flush = self::FLUSHING_ONE );

	/**
	 * Rollback a transaction previously started using begin()
	 *
	 * Only call this from code with outer transaction scope.
	 * See https://www.mediawiki.org/wiki/Database_transactions for details.
	 * Nesting of transactions is not supported. If a serious unexpected error occurs,
	 * throwing an Exception is preferable, using a pre-installed error handler to trigger
	 * rollback (in any case, failure to issue COMMIT will cause rollback server-side).
	 *
	 * Query, connection, and onTransaction* callback errors will be suppressed and logged.
	 *
	 * @param string $fname Calling function name @phan-mandatory-param
	 * @param string $flush Flush flag, set to a situationally valid IDatabase::FLUSHING_*
	 *   constant to disable warnings about explicitly rolling back implicit transactions.
	 *   This will silently break any ongoing explicit transaction. Only set the flush flag
	 *   if you are sure that it is safe to ignore these warnings in your context.
	 * @throws DBError If an error occurs, {@see query}
	 * @since 1.23 Added $flush parameter
	 */
	public function rollback( $fname = __METHOD__, $flush = self::FLUSHING_ONE );

	/**
	 * Commit any transaction but error out if writes or callbacks are pending
	 *
	 * This is intended for clearing out REPEATABLE-READ snapshots so that callers can
	 * see a new point-in-time of the database. This is useful when one of many transaction
	 * rounds finished and significant time will pass in the script's lifetime. It is also
	 * useful to call on a replica server after waiting on replication to catch up to the
	 * primary server.
	 *
	 * @param string $fname Calling function name @phan-mandatory-param
	 * @param string $flush Flush flag, set to situationally valid IDatabase::FLUSHING_*
	 *   constant to disable warnings about explicitly committing implicit transactions,
	 *   or calling commit when no transaction is in progress.
	 *   This will trigger an exception if there is an ongoing explicit transaction.
	 *   Only set the flush flag if you are sure that these warnings are not applicable,
	 *   and no explicit transactions are open.
	 * @throws DBError If an error occurs, {@see query}
	 * @since 1.28
	 * @since 1.34 Added $flush parameter
	 */
	public function flushSnapshot( $fname = __METHOD__, $flush = self::FLUSHING_ONE );

	/**
	 * Override database's default behavior.
	 * Not all options are supported on all database backends;
	 * unsupported options are silently ignored.
	 *
	 * $options include:
	 * - 'connTimeout': Set the connection timeout value in seconds.
	 *   May be useful for very long batch queries such as full-wiki dumps,
	 *   where a single query reads out over hours or days.
	 *   Only supported on MySQL and MariaDB.
	 * - 'groupConcatMaxLen': Maximum length of a GROUP_CONCAT() result.
	 *   Only supported on MySQL and MariaDB.
	 *
	 * @param array $options
	 * @return void
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function setSessionOptions( array $options );

	/**
	 * Check to see if a named lock is not locked by any thread (non-blocking)
	 *
	 * @param string $lockName Name of lock to poll
	 * @param string $method Name of method calling us
	 * @return bool
	 * @throws DBError If an error occurs, {@see query}
	 * @since 1.20
	 */
	public function lockIsFree( $lockName, $method );

	/**
	 * Acquire a named lock
	 *
	 * Named locks are not related to transactions
	 *
	 * @param string $lockName Name of lock to acquire
	 * @param string $method Name of the calling method
	 * @param int $timeout Acquisition timeout in seconds (0 means non-blocking)
	 * @param int $flags Bit field of IDatabase::LOCK_* constants
	 * @return bool|float|null Success (bool); acquisition time (float/null) if LOCK_TIMESTAMP
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function lock( $lockName, $method, $timeout = 5, $flags = 0 );

	/**
	 * Release a lock
	 *
	 * Named locks are not related to transactions
	 *
	 * @param string $lockName Name of lock to release
	 * @param string $method Name of the calling method
	 * @return bool Success
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function unlock( $lockName, $method );

	/**
	 * Acquire a named lock, flush any transaction, and return an RAII style unlocker object
	 *
	 * Only call this from outer transaction scope and when only one DB server will be affected.
	 * See https://www.mediawiki.org/wiki/Database_transactions for details.
	 *
	 * This is suitable for transactions that need to be serialized using cooperative locks,
	 * where each transaction can see each others' changes. Any transaction is flushed to clear
	 * out stale REPEATABLE-READ snapshot data. Once the returned object falls out of PHP scope,
	 * the lock will be released unless a transaction is active. If one is active, then the lock
	 * will be released when it either commits or rolls back.
	 *
	 * If the lock acquisition failed, then no transaction flush happens, and null is returned.
	 *
	 * @param string $lockKey Name of lock to release
	 * @param string $fname Name of the calling method
	 * @param int $timeout Acquisition timeout in seconds
	 * @return ScopedCallback|null
	 * @throws DBError If an error occurs, {@see query}
	 * @since 1.27
	 */
	public function getScopedLockAndFlush( $lockKey, $fname, $timeout );

	/**
	 * Check if this DB server is marked as read-only according to load balancer info
	 *
	 * @note LoadBalancer checks serverIsReadOnly() when setting the load balancer info array
	 *
	 * @return bool
	 * @since 1.27
	 */
	public function isReadOnly();
}
