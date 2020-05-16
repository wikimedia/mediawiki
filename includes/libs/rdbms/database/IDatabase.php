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
use InvalidArgumentException;
use stdClass;
use Wikimedia\ScopedCallback;

/**
 * @defgroup Database Database
 * This group deals with database interface functions
 * and query specifics/optimisations.
 */
/**
 * Basic database interface for live and lazy-loaded relation database handles
 *
 * @note IDatabase and DBConnRef should be updated to reflect any changes
 * @ingroup Database
 */
interface IDatabase {
	/** @var int Callback triggered immediately due to no active transaction */
	public const TRIGGER_IDLE = 1;
	/** @var int Callback triggered by COMMIT */
	public const TRIGGER_COMMIT = 2;
	/** @var int Callback triggered by ROLLBACK */
	public const TRIGGER_ROLLBACK = 3;
	/** @var int Callback triggered by atomic section cancel (ROLLBACK TO SAVEPOINT) */
	public const TRIGGER_CANCEL = 4;

	/** @var string Transaction is requested by regular caller outside of the DB layer */
	public const TRANSACTION_EXPLICIT = '';
	/** @var string Transaction is requested internally via DBO_TRX/startAtomic() */
	public const TRANSACTION_INTERNAL = 'implicit';

	/** @var string Atomic section is not cancelable */
	public const ATOMIC_NOT_CANCELABLE = '';
	/** @var string Atomic section is cancelable */
	public const ATOMIC_CANCELABLE = 'cancelable';

	/** @var string Commit/rollback is from outside the IDatabase handle and connection manager */
	public const FLUSHING_ONE = '';
	/** @var string Commit/rollback is from the connection manager for the IDatabase handle */
	public const FLUSHING_ALL_PEERS = 'flush';
	/** @var string Commit/rollback is from the IDatabase handle internally */
	public const FLUSHING_INTERNAL = 'flush-internal';

	/** @var string Do not remember the prior flags */
	public const REMEMBER_NOTHING = '';
	/** @var string Remember the prior flags */
	public const REMEMBER_PRIOR = 'remember';
	/** @var string Restore to the prior flag state */
	public const RESTORE_PRIOR = 'prior';
	/** @var string Restore to the initial flag state */
	public const RESTORE_INITIAL = 'initial';

	/** @var string Estimate total time (RTT, scanning, waiting on locks, applying) */
	public const ESTIMATE_TOTAL = 'total';
	/** @var string Estimate time to apply (scanning, applying) */
	public const ESTIMATE_DB_APPLY = 'apply';

	/** @var int Combine list with comma delimeters */
	public const LIST_COMMA = 0;
	/** @var int Combine list with AND clauses */
	public const LIST_AND = 1;
	/** @var int Convert map into a SET clause */
	public const LIST_SET = 2;
	/** @var int Treat as field name and do not apply value escaping */
	public const LIST_NAMES = 3;
	/** @var int Combine list with OR clauses */
	public const LIST_OR = 4;

	/** @var int Enable debug logging of all SQL queries */
	public const DBO_DEBUG = 1;
	/** @var int Unused since 1.34 */
	public const DBO_NOBUFFER = 2;
	/** @var int Unused since 1.31 */
	public const DBO_IGNORE = 4;
	/** @var int Automatically start a transaction before running a query if none is active */
	public const DBO_TRX = 8;
	/** @var int Join load balancer transaction rounds (which control DBO_TRX) in non-CLI mode */
	public const DBO_DEFAULT = 16;
	/** @var int Use DB persistent connections if possible */
	public const DBO_PERSISTENT = 32;
	/** @var int DBA session mode; was used by Oracle */
	public const DBO_SYSDBA = 64;
	/** @var int Schema file mode; was used by Oracle */
	public const DBO_DDLMODE = 128;
	/** @var int Enable SSL/TLS in connection protocol */
	public const DBO_SSL = 256;
	/** @var int Enable compression in connection protocol */
	public const DBO_COMPRESS = 512;

	/** @var int Idiom for "no special flags" */
	public const QUERY_NORMAL = 0;
	/** @var int Ignore query errors and return false when they happen */
	public const QUERY_SILENCE_ERRORS = 1; // b/c for 1.32 query() argument; (int)true = 1
	/**
	 * @var int Treat the TEMPORARY table from the given CREATE query as if it is
	 *   permanent as far as write tracking is concerned. This is useful for testing.
	 */
	public const QUERY_PSEUDO_PERMANENT = 2;
	/** @var int Enforce that a query does not make effective writes */
	public const QUERY_REPLICA_ROLE = 4;
	/** @var int Ignore the current presence of any DBO_TRX flag */
	public const QUERY_IGNORE_DBO_TRX = 8;
	/** @var int Do not try to retry the query if the connection was lost */
	public const QUERY_NO_RETRY = 16;
	/** @var int Query is known to be a read-only Data Query Language query */
	public const QUERY_CHANGE_NONE = 32;
	/** @var int Query is known to be a Transaction Control Language command */
	public const QUERY_CHANGE_TRX = 64 | self::QUERY_IGNORE_DBO_TRX;
	/** @var int Query is known to be a Data Manipulation Language command */
	public const QUERY_CHANGE_ROWS = 128;
	/** @var int Query is known to be a Data Definition Language command */
	public const QUERY_CHANGE_SCHEMA = 256 | self::QUERY_IGNORE_DBO_TRX;

	/** @var bool Parameter to unionQueries() for UNION ALL */
	public const UNION_ALL = true;
	/** @var bool Parameter to unionQueries() for UNION DISTINCT */
	public const UNION_DISTINCT = false;

	/** @var string Field for getLBInfo()/setLBInfo() */
	public const LB_TRX_ROUND_ID = 'trxRoundId';
	/** @var string Field for getLBInfo()/setLBInfo() */
	public const LB_READ_ONLY_REASON = 'readOnlyReason';

	/** @var string Master server than can stream OLTP updates to replica servers */
	public const ROLE_STREAMING_MASTER = 'streaming-master';
	/** @var string Replica server that streams OLTP updates from the master server */
	public const ROLE_STREAMING_REPLICA = 'streaming-replica';
	/** @var string Replica server of a static dataset that does not get OLTP updates */
	public const ROLE_STATIC_CLONE = 'static-clone';
	/** @var string Unknown replication topology role */
	public const ROLE_UNKNOWN = 'unknown';

	/** @var string Unconditional update/delete of whole table */
	public const ALL_ROWS = '*';

	/**
	 * Get a human-readable string describing the current software version
	 *
	 * Use getServerVersion() to get machine-friendly information.
	 *
	 * @return string Version information from the database server
	 */
	public function getServerInfo();

	/**
	 * Get the replication topology role of this server
	 *
	 * @return string One of the class ROLE_* constants
	 * @since 1.34
	 */
	public function getTopologyRole();

	/**
	 * Get the host (or address) of the root master server for the replication topology
	 *
	 * @return string|null Master server name or null if not known
	 * @since 1.34
	 */
	public function getTopologyRootMaster();

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
	 * @return bool Whether an explicit transaction or atomic sections are still open
	 * @since 1.28
	 */
	public function explicitTrxActive();

	/**
	 * Assert that all explicit transactions or atomic sections have been closed
	 *
	 * @throws DBTransactionError
	 * @since 1.32
	 */
	public function assertNoOpenTransactions();

	/**
	 * Get/set the table prefix
	 *
	 * @param string|null $prefix The table prefix to set, or omitted to leave it unchanged
	 * @return string The previous table prefix
	 */
	public function tablePrefix( $prefix = null );

	/**
	 * Get/set the db schema
	 *
	 * @param string|null $schema The database schema to set, or omitted to leave it unchanged
	 * @return string The previous db schema
	 */
	public function dbSchema( $schema = null );

	/**
	 * Get properties passed down from the server info array of the load balancer
	 *
	 * @param string|null $name The entry of the info array to get, or null to get the whole array
	 * @return array|mixed|null
	 */
	public function getLBInfo( $name = null );

	/**
	 * Set the entire array or a particular key of the managing load balancer info array
	 *
	 * Keys matching the IDatabase::LB_* constants are also used internally by subclasses
	 *
	 * @param array|string $nameOrArray The new array or the name of a key to set
	 * @param array|null $value If $nameOrArray is a string, the new key value (null to unset)
	 */
	public function setLBInfo( $nameOrArray, $value = null );

	/**
	 * Returns true if this database does an implicit order by when the column has an index
	 * For example: SELECT page_title FROM page LIMIT 1
	 *
	 * @return bool
	 */
	public function implicitOrderby();

	/**
	 * Get the last query that sent on account of IDatabase::query()
	 *
	 * @return string SQL text or empty string if there was no such query
	 */
	public function lastQuery();

	/**
	 * Get the last time the connection may have been used for a write query
	 *
	 * @return int|float UNIX timestamp or false
	 * @since 1.24
	 */
	public function lastDoneWrites();

	/**
	 * @return bool Whether there is a transaction open with possible write queries
	 * @since 1.27
	 */
	public function writesPending();

	/**
	 * @return bool Whether there is a transaction open with pre-commit callbacks pending
	 * @since 1.32
	 */
	public function preCommitCallbacksPending();

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
	 * Get the time spend running write queries for this transaction
	 *
	 * High values could be due to scanning, updates, locking, and such.
	 *
	 * @param string $type IDatabase::ESTIMATE_* constant [default: ESTIMATE_ALL]
	 * @return float|bool Returns false if not transaction is active
	 * @since 1.26
	 */
	public function pendingWriteQueryDuration( $type = self::ESTIMATE_TOTAL );

	/**
	 * Get the list of method names that did write queries for this transaction
	 *
	 * @return array
	 * @since 1.27
	 */
	public function pendingWriteCallers();

	/**
	 * Get the number of affected rows from pending write queries
	 *
	 * @return int
	 * @since 1.30
	 */
	public function pendingWriteRowsAffected();

	/**
	 * @return bool Whether a connection to the database open
	 */
	public function isOpen();

	/**
	 * Set a flag for this connection
	 *
	 * @param int $flag One of (IDatabase::DBO_DEBUG, IDatabase::DBO_TRX)
	 * @param string $remember IDatabase::REMEMBER_* constant [default: REMEMBER_NOTHING]
	 */
	public function setFlag( $flag, $remember = self::REMEMBER_NOTHING );

	/**
	 * Clear a flag for this connection
	 *
	 * @param int $flag One of (IDatabase::DBO_DEBUG, IDatabase::DBO_TRX)
	 * @param string $remember IDatabase::REMEMBER_* constant [default: REMEMBER_NOTHING]
	 */
	public function clearFlag( $flag, $remember = self::REMEMBER_NOTHING );

	/**
	 * Restore the flags to their prior state before the last setFlag/clearFlag call
	 *
	 * @param string $state IDatabase::RESTORE_* constant. [default: RESTORE_PRIOR]
	 * @since 1.28
	 */
	public function restoreFlags( $state = self::RESTORE_PRIOR );

	/**
	 * Returns a boolean whether the flag $flag is set for this connection
	 *
	 * @param int $flag One of the class IDatabase::DBO_* constants
	 * @return bool
	 */
	public function getFlag( $flag );

	/**
	 * Return the currently selected domain ID
	 *
	 * Null components (database/schema) might change once a connection is established
	 *
	 * @return string
	 */
	public function getDomainID();

	/**
	 * Get the type of the DBMS (e.g. "mysql", "sqlite")
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * Fetch the next row from the given result object, in object form
	 *
	 * Fields can be retrieved with $row->fieldname, with fields acting like
	 * member variables. If no more rows are available, false is returned.
	 *
	 * @param IResultWrapper|stdClass $res Object as returned from IDatabase::query(), etc.
	 * @return stdClass|bool
	 */
	public function fetchObject( $res );

	/**
	 * Fetch the next row from the given result object, in associative array form
	 *
	 * Fields are retrieved with $row['fieldname'].
	 * If no more rows are available, false is returned.
	 *
	 * @param IResultWrapper $res Result object as returned from IDatabase::query(), etc.
	 * @return array|bool
	 */
	public function fetchRow( $res );

	/**
	 * Get the number of rows in a query result
	 *
	 * Returns zero if the query did not return any rows or was a write query.
	 *
	 * @param mixed $res A SQL result
	 * @return int
	 */
	public function numRows( $res );

	/**
	 * Get the number of fields in a result object
	 * @see https://www.php.net/mysql_num_fields
	 *
	 * @param mixed $res A SQL result
	 * @return int
	 */
	public function numFields( $res );

	/**
	 * Get a field name in a result object
	 * @see https://www.php.net/mysql_field_name
	 *
	 * @param mixed $res A SQL result
	 * @param int $n
	 * @return string
	 */
	public function fieldName( $res, $n );

	/**
	 * Get the inserted value of an auto-increment row
	 *
	 * This should only be called after an insert that used an auto-incremented
	 * value. If no such insert was previously done in the current database
	 * session, the return value is undefined.
	 *
	 * @return int
	 */
	public function insertId();

	/**
	 * Change the position of the cursor in a result object
	 * @see https://www.php.net/mysql_data_seek
	 *
	 * @param mixed $res A SQL result
	 * @param int $row
	 */
	public function dataSeek( $res, $row );

	/**
	 * Get the last error number
	 * @see https://www.php.net/mysql_errno
	 *
	 * @return int
	 */
	public function lastErrno();

	/**
	 * Get a description of the last error
	 * @see https://www.php.net/mysql_error
	 *
	 * @return string
	 */
	public function lastError();

	/**
	 * Get the number of rows affected by the last write query.
	 * Similar to https://www.php.net/mysql_affected_rows but includes rows matched
	 * but not changed (ie. an UPDATE which sets all fields to the same value they already have).
	 * To get the old mysql_affected_rows behavior, include non-equality of the fields in WHERE.
	 *
	 * @return int
	 */
	public function affectedRows();

	/**
	 * Returns a wikitext style link to the DB's website (e.g. "[https://www.mysql.com/ MySQL]")
	 *
	 * Should at least contain plain text, if for some reason your database has no website.
	 *
	 * @return string Wikitext of a link to the server software's web site
	 */
	public function getSoftwareLink();

	/**
	 * A string describing the current software version, like from mysql_get_server_info()
	 *
	 * @return string Version information from the database server.
	 */
	public function getServerVersion();

	/**
	 * Close the database connection
	 *
	 * This should only be called after any transactions have been resolved,
	 * aside from read-only automatic transactions (assuming no callbacks are registered).
	 * If a transaction is still open anyway, it will be rolled back.
	 *
	 * @param string $fname Caller name
	 * @param int|null $owner ID of the calling instance (e.g. the LBFactory ID)
	 * @return bool Success
	 * @throws DBError
	 */
	public function close( $fname = __METHOD__, $owner = null );

	/**
	 * Run an SQL query and return the result
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
	 * @param string $sql SQL query
	 * @param string $fname Name of the calling function, for profiling/SHOW PROCESSLIST
	 *     comment (you can use __METHOD__ or add some extra info)
	 * @param int $flags Bitfield of IDatabase::QUERY_* constants. Note that suppression
	 *     of errors is best handled by try/catch rather than using one of these flags.
	 * @return bool|IResultWrapper True for a successful write query, IResultWrapper object
	 *     for a successful read query, or false on failure if QUERY_SILENCE_ERRORS is set.
	 * @throws DBQueryError If the query is issued, fails, and QUERY_SILENCE_ERRORS is not set.
	 * @throws DBExpectedError If the query is not, and cannot, be issued yet (non-DBQueryError)
	 * @throws DBError If the query is inherently not allowed (non-DBExpectedError)
	 */
	public function query( $sql, $fname = __METHOD__, $flags = 0 );

	/**
	 * Free a result object returned by query() or select()
	 *
	 * It's usually not necessary to call this, just use unset() or let the variable
	 * holding the result object go out of scope.
	 *
	 * @param mixed $res A SQL result
	 */
	public function freeResult( $res );

	/**
	 * Create an empty SelectQueryBuilder which can be used to run queries
	 * against this connection.
	 *
	 * @return SelectQueryBuilder
	 */
	public function newSelectQueryBuilder();

	/**
	 * A SELECT wrapper which returns a single field from a single result row
	 *
	 * If no result rows are returned from the query, false is returned.
	 *
	 * @param string|array $table Table name. See IDatabase::select() for details.
	 * @param string $var The field name to select. This must be a valid SQL
	 *   fragment: do not use unvalidated user input.
	 * @param string|array $cond The condition array. See IDatabase::select() for details.
	 * @param string $fname The function name of the caller.
	 * @param string|array $options The query options. See IDatabase::select() for details.
	 * @param string|array $join_conds The query join conditions. See IDatabase::select() for details.
	 * @return mixed The value from the field
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function selectField(
		$table, $var, $cond = '', $fname = __METHOD__, $options = [], $join_conds = []
	);

	/**
	 * A SELECT wrapper which returns a list of single field values from result rows
	 *
	 * If no result rows are returned from the query, an empty array is returned.
	 *
	 * @param string|array $table Table name. See IDatabase::select() for details.
	 * @param string $var The field name to select. This must be a valid SQL
	 *   fragment: do not use unvalidated user input.
	 * @param string|array $cond The condition array. See IDatabase::select() for details.
	 * @param string $fname The function name of the caller.
	 * @param string|array $options The query options. See IDatabase::select() for details.
	 * @param string|array $join_conds The query join conditions. See IDatabase::select() for details.
	 *
	 * @return array The values from the field in the order they were returned from the DB
	 * @throws DBError If an error occurs, see IDatabase::query()
	 * @since 1.25
	 */
	public function selectFieldValues(
		$table, $var, $cond = '', $fname = __METHOD__, $options = [], $join_conds = []
	);

	/**
	 * Execute a SELECT query constructed using the various parameters provided
	 *
	 * @param string|array $table Table name(s)
	 *
	 * May be either an array of table names, or a single string holding a table
	 * name. If an array is given, table aliases can be specified, for example:
	 *
	 *    [ 'a' => 'user' ]
	 *
	 * This includes the user table in the query, with the alias "a" available
	 * for use in field names (e.g. a.user_name).
	 *
	 * A derived table, defined by the result of selectSQLText(), requires an alias
	 * key and a Subquery instance value which wraps the SQL query, for example:
	 *
	 *    [ 'c' => new Subquery( 'SELECT ...' ) ]
	 *
	 * Joins using parentheses for grouping (since MediaWiki 1.31) may be
	 * constructed using nested arrays. For example,
	 *
	 *    [ 'tableA', 'nestedB' => [ 'tableB', 'b2' => 'tableB2' ] ]
	 *
	 * along with `$join_conds` like
	 *
	 *    [ 'b2' => [ 'JOIN', 'b_id = b2_id' ], 'nestedB' => [ 'LEFT JOIN', 'b_a = a_id' ] ]
	 *
	 * will produce SQL something like
	 *
	 *    FROM tableA LEFT JOIN (tableB JOIN tableB2 AS b2 ON (b_id = b2_id)) ON (b_a = a_id)
	 *
	 * All of the table names given here are automatically run through
	 * Database::tableName(), which causes the table prefix (if any) to be
	 * added, and various other table name mappings to be performed.
	 *
	 * Do not use untrusted user input as a table name. Alias names should
	 * not have characters outside of the Basic multilingual plane.
	 *
	 * @param string|array $vars Field name(s)
	 *
	 * May be either a field name or an array of field names. The field names
	 * can be complete fragments of SQL, for direct inclusion into the SELECT
	 * query. If an array is given, field aliases can be specified, for example:
	 *
	 *   [ 'maxrev' => 'MAX(rev_id)' ]
	 *
	 * This includes an expression with the alias "maxrev" in the query.
	 *
	 * If an expression is given, care must be taken to ensure that it is
	 * DBMS-independent.
	 *
	 * Untrusted user input must not be passed to this parameter.
	 *
	 * @param string|array $conds
	 *
	 * May be either a string containing a single condition, or an array of
	 * conditions. If an array is given, the conditions constructed from each
	 * element are combined with AND.
	 *
	 * Array elements may take one of two forms:
	 *
	 *   - Elements with a numeric key are interpreted as raw SQL fragments.
	 *   - Elements with a string key are interpreted as equality conditions,
	 *     where the key is the field name.
	 *     - If the value of such an array element is a scalar (such as a
	 *       string), it will be treated as data and thus quoted appropriately.
	 *       If it is null, an IS NULL clause will be added.
	 *     - If the value is an array, an IN (...) clause will be constructed
	 *       from its non-null elements, and an IS NULL clause will be added
	 *       if null is present, such that the field may match any of the
	 *       elements in the array. The non-null elements will be quoted.
	 *
	 * Note that expressions are often DBMS-dependent in their syntax.
	 * DBMS-independent wrappers are provided for constructing several types of
	 * expression commonly used in condition queries. See:
	 *    - IDatabase::buildLike()
	 *    - IDatabase::conditional()
	 *
	 * Untrusted user input is safe in the values of string keys, however untrusted
	 * input must not be used in the array key names or in the values of numeric keys.
	 * Escaping of untrusted input used in values of numeric keys should be done via
	 * IDatabase::addQuotes()
	 *
	 * Use an empty array, string, or IDatabase::ALL_ROWS to select all rows.
	 *
	 * @param string $fname Caller function name
	 *
	 * @param string|array $options Query options
	 *
	 * Optional: Array of query options. Boolean options are specified by
	 * including them in the array as a string value with a numeric key, for
	 * example:
	 *
	 *    [ 'FOR UPDATE' ]
	 *
	 * The supported options are:
	 *
	 *   - OFFSET: Skip this many rows at the start of the result set. OFFSET
	 *     with LIMIT can theoretically be used for paging through a result set,
	 *     but this is discouraged for performance reasons.
	 *
	 *   - LIMIT: Integer: return at most this many rows. The rows are sorted
	 *     and then the first rows are taken until the limit is reached. LIMIT
	 *     is applied to a result set after OFFSET.
	 *
	 *   - LOCK IN SHARE MODE: Boolean: lock the returned rows so that they can't be
	 *     changed until the next COMMIT. Cannot be used with aggregate functions
	 *     (COUNT, MAX, etc., but also DISTINCT).
	 *
	 *   - FOR UPDATE: Boolean: lock the returned rows so that they can't be
	 *     changed nor read with LOCK IN SHARE MODE until the next COMMIT.
	 *     Cannot be used with aggregate functions (COUNT, MAX, etc., but also DISTINCT).
	 *
	 *   - DISTINCT: Boolean: return only unique result rows.
	 *
	 *   - GROUP BY: May be either an SQL fragment string naming a field or
	 *     expression to group by, or an array of such SQL fragments.
	 *
	 *   - HAVING: May be either an string containing a HAVING clause or an array of
	 *     conditions building the HAVING clause. If an array is given, the conditions
	 *     constructed from each element are combined with AND.
	 *
	 *   - ORDER BY: May be either an SQL fragment giving a field name or
	 *     expression to order by, or an array of such SQL fragments.
	 *
	 *   - USE INDEX: This may be either a string giving the index name to use
	 *     for the query, or an array. If it is an associative array, each key
	 *     gives the table name (or alias), each value gives the index name to
	 *     use for that table. All strings are SQL fragments and so should be
	 *     validated by the caller.
	 *
	 *   - IGNORE INDEX: This may be either be a string giving an index name to
	 *     ignore for the query, or an array. If it is an associative array,
	 *     each key gives the table name (or alias), each value gives the index
	 *     name to ignore for that table. All strings are SQL fragments and so
	 *     should be validated by the caller.
	 *
	 *   - EXPLAIN: In MySQL, this causes an EXPLAIN SELECT query to be run,
	 *     instead of SELECT.
	 *
	 * And also the following boolean MySQL extensions, see the MySQL manual
	 * for documentation:
	 *
	 *    - STRAIGHT_JOIN
	 *    - SQL_BIG_RESULT
	 *    - SQL_BUFFER_RESULT
	 *    - SQL_SMALL_RESULT
	 *    - SQL_CALC_FOUND_ROWS
	 *
	 * @param string|array $join_conds Join conditions
	 *
	 * Optional associative array of table-specific join conditions. In the
	 * most common case, this is unnecessary, since the join condition can be
	 * in $conds. However, it is useful for doing a LEFT JOIN.
	 *
	 * The key of the array contains the table name or alias. The value is an
	 * array with two elements, numbered 0 and 1. The first gives the type of
	 * join, the second is the same as the $conds parameter. Thus it can be
	 * an SQL fragment, or an array where the string keys are equality and the
	 * numeric keys are SQL fragments all AND'd together. For example:
	 *
	 *    [ 'page' => [ 'LEFT JOIN', 'page_latest=rev_id' ] ]
	 *
	 * @return IResultWrapper Resulting rows
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function select(
		$table,
		$vars,
		$conds = '',
		$fname = __METHOD__,
		$options = [],
		$join_conds = []
	);

	/**
	 * Take the same arguments as IDatabase::select() and return the SQL it would use
	 *
	 * This can be useful for making UNION queries, where the SQL text of each query
	 * is needed. In general, however, callers outside of Database classes should just
	 * use select().
	 *
	 * @see IDatabase::select()
	 *
	 * @param string|array $table Table name
	 * @param string|array $vars Field names
	 * @param string|array $conds Conditions
	 * @param string $fname Caller function name
	 * @param string|array $options Query options
	 * @param string|array $join_conds Join conditions
	 * @return string SQL query string
	 */
	public function selectSQLText(
		$table,
		$vars,
		$conds = '',
		$fname = __METHOD__,
		$options = [],
		$join_conds = []
	);

	/**
	 * Wrapper to IDatabase::select() that only fetches one row (via LIMIT)
	 *
	 * If the query returns no rows, false is returned.
	 *
	 * This method is convenient for fetching a row based on a unique key condition.
	 *
	 * @param string|array $table Table name
	 * @param string|array $vars Field names
	 * @param string|array $conds Conditions
	 * @param string $fname Caller function name
	 * @param string|array $options Query options
	 * @param array|string $join_conds Join conditions
	 * @return stdClass|bool
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function selectRow(
		$table,
		$vars,
		$conds,
		$fname = __METHOD__,
		$options = [],
		$join_conds = []
	);

	/**
	 * Estimate the number of rows in dataset
	 *
	 * MySQL allows you to estimate the number of rows that would be returned
	 * by a SELECT query, using EXPLAIN SELECT. The estimate is provided using
	 * index cardinality statistics, and is notoriously inaccurate, especially
	 * when large numbers of rows have recently been added or deleted.
	 *
	 * For DBMSs that don't support fast result size estimation, this function
	 * will actually perform the SELECT COUNT(*).
	 *
	 * Takes the same arguments as IDatabase::select().
	 *
	 * @param string|string[] $tables Table name(s)
	 * @param string $var Column for which NULL values are not counted [default "*"]
	 * @param array|string $conds Filters on the table
	 * @param string $fname Function name for profiling
	 * @param array $options Options for select
	 * @param array|string $join_conds Join conditions
	 * @return int Row count
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function estimateRowCount(
		$tables, $var = '*', $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	);

	/**
	 * Get the number of rows in dataset
	 *
	 * This is useful when trying to do COUNT(*) but with a LIMIT for performance.
	 *
	 * Takes the same arguments as IDatabase::select().
	 *
	 * @since 1.27 Added $join_conds parameter
	 *
	 * @param string|string[] $tables Table name(s)
	 * @param string $var Column for which NULL values are not counted [default "*"]
	 * @param array|string $conds Filters on the table
	 * @param string $fname Function name for profiling
	 * @param array $options Options for select
	 * @param array $join_conds Join conditions (since 1.27)
	 * @return int Row count
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function selectRowCount(
		$tables, $var = '*', $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	);

	/**
	 * Lock all rows meeting the given conditions/options FOR UPDATE
	 *
	 * @param string|string[] $table Table name(s)
	 * @param array|string $conds Filters on the table
	 * @param string $fname Function name for profiling
	 * @param array $options Options for select ("FOR UPDATE" is added automatically)
	 * @param array $join_conds Join conditions
	 * @return int Number of matching rows found (and locked)
	 * @throws DBError If an error occurs, see IDatabase::query()
	 * @since 1.32
	 */
	public function lockForUpdate(
		$table, $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	);

	/**
	 * Determines whether a field exists in a table
	 *
	 * @param string $table Table name
	 * @param string $field Filed to check on that table
	 * @param string $fname Calling function name (optional)
	 * @return bool Whether $table has filed $field
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function fieldExists( $table, $field, $fname = __METHOD__ );

	/**
	 * Determines whether an index exists
	 *
	 * @param string $table
	 * @param string $index
	 * @param string $fname
	 * @return bool|null
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function indexExists( $table, $index, $fname = __METHOD__ );

	/**
	 * Query whether a given table exists
	 *
	 * @param string $table
	 * @param string $fname
	 * @return bool
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function tableExists( $table, $fname = __METHOD__ );

	/**
	 * Insert the given row(s) into a table
	 *
	 * @param string $table Table name
	 * @param array|array[] $rows Row(s) to insert, as either:
	 *   - A string-keyed map of (column name => value) defining a new row. Values are
	 *     treated as literals and quoted appropriately; null is interpreted as NULL.
	 *   - An integer-keyed list of such string-keyed maps, defining a list of new rows.
	 *     The keys in each map must be identical to each other and in the same order.
	 *     The rows must not collide with each other.
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling
	 * @param string|array $options Combination map/list where each string-keyed entry maps a
	 *   non-boolean option to the option parameters and each integer-keyed value is the
	 *   name of a boolean option. Supported options are:
	 *     - IGNORE: Boolean: skip insertion of rows that would cause unique key conflicts.
	 *       IDatabase::affectedRows() can be used to determine how many rows were inserted.
	 * @return bool Return true if no exception was thrown (deprecated since 1.33)
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function insert( $table, $rows, $fname = __METHOD__, $options = [] );

	/**
	 * Update all rows in a table that match a given condition
	 *
	 * @param string $table Table name
	 * @param array $set Combination map/list where each string-keyed entry maps a column
	 *   to a literal assigned value and each integer-keyed value is a SQL expression in the
	 *   format of a column assignment within UPDATE...SET. The (column => value) entries are
	 *   convenient due to automatic value quoting and conversion of null to NULL. The SQL
	 *   assignment format is useful for updates like "column = column + X". All assignments
	 *   have no defined execution order, so they should not depend on each other. Do not
	 *   modify AUTOINCREMENT or UUID columns in assignments.
	 * @param array|string $conds Condition in the format of IDatabase::select() conditions.
	 *   In order to prevent possible performance or replication issues or damaging a data
	 *   accidentally, an empty condition for 'update' queries isn't allowed.
	 *   IDatabase::ALL_ROWS should be passed explicitely in order to update all rows.
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling
	 * @param string|array $options Combination map/list where each string-keyed entry maps a
	 *   non-boolean option to the option parameters and each integer-keyed value is the
	 *   name of a boolean option. Supported options are:
	 *     - IGNORE: Boolean: skip insertion of rows that would cause unique key conflicts.
	 *       IDatabase::affectedRows() can be used to determine how many rows were inserted.
	 * @return bool Return true if no exception was thrown (deprecated since 1.33)
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function update( $table, $set, $conds, $fname = __METHOD__, $options = [] );

	/**
	 * Makes an encoded list of strings from an array
	 *
	 * These can be used to make conjunctions or disjunctions on SQL condition strings
	 * derived from an array (see IDatabase::select() $conds documentation).
	 *
	 * Example usage:
	 * @code
	 *     $sql = $db->makeList( [
	 *         'rev_page' => $id,
	 *         $db->makeList( [ 'rev_minor' => 1, 'rev_len' < 500 ], $db::LIST_OR ] )
	 *     ], $db::LIST_AND );
	 * @endcode
	 * This would set $sql to "rev_page = '$id' AND (rev_minor = '1' OR rev_len < '500')"
	 *
	 * @param array $a Containing the data
	 * @param int $mode IDatabase class constant:
	 *    - IDatabase::LIST_COMMA: Comma separated, no field names
	 *    - IDatabase::LIST_AND:   ANDed WHERE clause (without the WHERE).
	 *    - IDatabase::LIST_OR:    ORed WHERE clause (without the WHERE)
	 *    - IDatabase::LIST_SET:   Comma separated with field names, like a SET clause
	 *    - IDatabase::LIST_NAMES: Comma separated field names
	 * @throws DBError If an error occurs, see IDatabase::query()
	 * @return string
	 */
	public function makeList( array $a, $mode = self::LIST_COMMA );

	/**
	 * Build a partial where clause from a 2-d array such as used for LinkBatch.
	 *
	 * The keys on each level may be either integers or strings, however it's
	 * assumed that $baseKey is probably an integer-typed column (i.e. integer
	 * keys are unquoted in the SQL) and $subKey is string-typed (i.e. integer
	 * keys are quoted as strings in the SQL).
	 *
	 * @todo Does this actually belong in the library? It seems overly MW-specific.
	 *
	 * @param array $data Organized as 2-d
	 *    [ baseKeyVal => [ subKeyVal => [ignored], ... ], ... ]
	 * @param string $baseKey Field name to match the base-level keys to (eg 'pl_namespace')
	 * @param string $subKey Field name to match the sub-level keys to (eg 'pl_title')
	 * @return string|bool SQL fragment, or false if no items in array
	 */
	public function makeWhereFrom2d( $data, $baseKey, $subKey );

	/**
	 * Return aggregated value alias
	 *
	 * @param array $valuedata
	 * @param string $valuename
	 *
	 * @return array|string
	 * @deprecated Since 1.33
	 */
	public function aggregateValue( $valuedata, $valuename = 'value' );

	/**
	 * @param string|int $field
	 * @return string
	 */
	public function bitNot( $field );

	/**
	 * @param string|int $fieldLeft
	 * @param string|int $fieldRight
	 * @return string
	 */
	public function bitAnd( $fieldLeft, $fieldRight );

	/**
	 * @param string|int $fieldLeft
	 * @param string|int $fieldRight
	 * @return string
	 */
	public function bitOr( $fieldLeft, $fieldRight );

	/**
	 * Build a concatenation list to feed into a SQL query
	 * @param string[] $stringList Raw SQL expression list; caller is responsible for escaping
	 * @return string
	 */
	public function buildConcat( $stringList );

	/**
	 * Build a GROUP_CONCAT or equivalent statement for a query.
	 *
	 * This is useful for combining a field for several rows into a single string.
	 * NULL values will not appear in the output, duplicated values will appear,
	 * and the resulting delimiter-separated values have no defined sort order.
	 * Code using the results may need to use the PHP unique() or sort() methods.
	 *
	 * @param string $delim Glue to bind the results together
	 * @param string|array $table Table name
	 * @param string $field Field name
	 * @param string|array $conds Conditions
	 * @param string|array $join_conds Join conditions
	 * @return string SQL text
	 * @since 1.23
	 */
	public function buildGroupConcatField(
		$delim, $table, $field, $conds = '', $join_conds = []
	);

	/**
	 * Build a GREATEST function statement comparing columns/values
	 *
	 * Integer and float values in $values will not be quoted
	 *
	 * If $fields is an array, then each value with a string key is treated as an expression
	 * (which must be manually quoted); such string keys do not appear in the SQL and are only
	 * descriptive aliases.
	 *
	 * @param string|string[] $fields Name(s) of column(s) with values to compare
	 * @param string|int|float|string[]|int[]|float[] $values Values to compare
	 * @return mixed
	 * @since 1.35
	 */
	public function buildGreatest( $fields, $values );

	/**
	 * Build a LEAST function statement comparing columns/values
	 *
	 * Integer and float values in $values will not be quoted
	 *
	 * If $fields is an array, then each value with a string key is treated as an expression
	 * (which must be manually quoted); such string keys do not appear in the SQL and are only
	 * descriptive aliases.
	 *
	 * @param string|string[] $fields Name(s) of column(s) with values to compare
	 * @param string|int|float|string[]|int[]|float[] $values Values to compare
	 * @return mixed
	 * @since 1.35
	 */
	public function buildLeast( $fields, $values );

	/**
	 * Build a SUBSTRING function
	 *
	 * Behavior for non-ASCII values is undefined.
	 *
	 * @param string $input Field name
	 * @param int $startPosition Positive integer
	 * @param int|null $length Non-negative integer length or null for no limit
	 * @throws InvalidArgumentException
	 * @return string SQL text
	 * @since 1.31
	 */
	public function buildSubString( $input, $startPosition, $length = null );

	/**
	 * @param string $field Field or column to cast
	 * @return string
	 * @since 1.28
	 */
	public function buildStringCast( $field );

	/**
	 * @param string $field Field or column to cast
	 * @return string
	 * @since 1.31
	 */
	public function buildIntegerCast( $field );

	/**
	 * Equivalent to IDatabase::selectSQLText() except wraps the result in Subquery
	 *
	 * @see IDatabase::selectSQLText()
	 *
	 * @param string|array $table Table name
	 * @param string|array $vars Field names
	 * @param string|array $conds Conditions
	 * @param string $fname Caller function name
	 * @param string|array $options Query options
	 * @param string|array $join_conds Join conditions
	 * @return Subquery
	 * @since 1.31
	 */
	public function buildSelectSubquery(
		$table,
		$vars,
		$conds = '',
		$fname = __METHOD__,
		$options = [],
		$join_conds = []
	);

	/**
	 * Construct a LIMIT query with optional offset
	 *
	 * The SQL should be adjusted so that only the first $limit rows
	 * are returned. If $offset is provided as well, then the first $offset
	 * rows should be discarded, and the next $limit rows should be returned.
	 * If the result of the query is not ordered, then the rows to be returned
	 * are theoretically arbitrary.
	 *
	 * $sql is expected to be a SELECT, if that makes a difference.
	 *
	 * @param string $sql SQL query we will append the limit too
	 * @param int $limit The SQL limit
	 * @param int|bool $offset The SQL offset (default false)
	 * @return string
	 * @since 1.34
	 */
	public function limitResult( $sql, $limit, $offset = false );

	/**
	 * Returns true if DBs are assumed to be on potentially different servers
	 *
	 * In systems like mysql/mariadb, different databases can easily be referenced on a single
	 * connection merely by name, even in a single query via JOIN. On the other hand, Postgres
	 * treats databases as logically separate, with different database users, requiring special
	 * mechanisms like postgres_fdw to "mount" foreign DBs. This is true even among DBs on the
	 * same server. Changing the selected database via selectDomain() requires a new connection.
	 *
	 * @return bool
	 * @since 1.29
	 */
	public function databasesAreIndependent();

	/**
	 * Change the current database
	 *
	 * This should only be called by a load balancer or if the handle is not attached to one
	 *
	 * @param string $db
	 * @return bool True unless an exception was thrown
	 * @throws DBConnectionError If databasesAreIndependent() is true and connection change fails
	 * @throws DBError On query error or if database changes are disallowed
	 * @deprecated Since 1.32 Use selectDomain() instead
	 */
	public function selectDB( $db );

	/**
	 * Set the current domain (database, schema, and table prefix)
	 *
	 * This will throw an error for some database types if the database is unspecified
	 *
	 * This should only be called by a load balancer or if the handle is not attached to one
	 *
	 * @param string|DatabaseDomain $domain
	 * @throws DBConnectionError If databasesAreIndependent() is true and connection change fails
	 * @throws DBError On query error, if domain changes are disallowed, or the domain is invalid
	 * @since 1.32
	 */
	public function selectDomain( $domain );

	/**
	 * Get the current DB name
	 * @return string|null
	 */
	public function getDBname();

	/**
	 * Get the server hostname or IP address
	 * @return string
	 */
	public function getServer();

	/**
	 * Escape and quote a raw value string for use in a SQL query
	 *
	 * @param string|int|float|null|bool|Blob $s
	 * @return string
	 */
	public function addQuotes( $s );

	/**
	 * Escape a SQL identifier (e.g. table, column, database) for use in a SQL query
	 *
	 * Depending on the database this will either be `backticks` or "double quotes"
	 *
	 * @param string $s
	 * @return string
	 * @since 1.33
	 */
	public function addIdentifierQuotes( $s );

	/**
	 * LIKE statement wrapper
	 *
	 * This takes a variable-length argument list with parts of pattern to match
	 * containing either string literals that will be escaped or tokens returned by
	 * anyChar() or anyString(). Alternatively, the function could be provided with
	 * an array of aforementioned parameters.
	 *
	 * Example: $dbr->buildLike( 'My_page_title/', $dbr->anyString() ) returns
	 * a LIKE clause that searches for subpages of 'My page title'.
	 * Alternatively:
	 *   $pattern = [ 'My_page_title/', $dbr->anyString() ];
	 *   $query .= $dbr->buildLike( $pattern );
	 *
	 * @since 1.16
	 * @param array[]|string|LikeMatch $param
	 * @param string|LikeMatch ...$params
	 * @return string Fully built LIKE statement
	 */
	public function buildLike( $param, ...$params );

	/**
	 * Returns a token for buildLike() that denotes a '_' to be used in a LIKE query
	 *
	 * @return LikeMatch
	 */
	public function anyChar();

	/**
	 * Returns a token for buildLike() that denotes a '%' to be used in a LIKE query
	 *
	 * @return LikeMatch
	 */
	public function anyString();

	/**
	 * Deprecated method, calls should be removed
	 *
	 * This was formerly used for PostgreSQL to handle
	 * self::insertId() auto-incrementing fields. It is no longer necessary
	 * since DatabasePostgres::insertId() has been reimplemented using
	 * `lastval()`
	 *
	 * Implementations should return null if inserting `NULL` into an
	 * auto-incrementing field works, otherwise it should return an instance of
	 * NextSequenceValue and filter it on calls to relevant methods.
	 *
	 * @deprecated since 1.30, no longer needed
	 * @param string $seqName
	 * @return null|NextSequenceValue
	 */
	public function nextSequenceValue( $seqName );

	/**
	 * Insert row(s) into a table, deleting all conflicting rows beforehand
	 *
	 * Note some important implications of the deletion semantics:
	 *   - If the table has an AUTOINCREMENT column and $rows omit that column, then any
	 *     conflicting existing rows will be replaced with newer having higher values for
	 *     that column, even if nothing else changed.
	 *   - There might be worse contention than upsert() due to the use of gap-locking.
	 *     This does not apply to RDBMS types that use predicate locking nor those that
	 *     just lock the whole table or databases anyway.
	 *
	 * @param string $table The table name
	 * @param string|string[]|string[][] $uniqueKeys Column name or non-empty list of column
	 *   name lists that define all applicable unique keys on the table. Each unique key on the
	 *   table is "applicable" unless either:
	 *     - It involves an AUTOINCREMENT column for which no values are assigned in $rows
	 *     - It involves a UUID column for which newly generated UUIDs are assigned in $rows
	 * @param array|array[] $rows Row(s) to insert, in the form of either:
	 *   - A string-keyed map of (column name => value) defining a new row. Values are
	 *     treated as literals and quoted appropriately; null is interpreted as NULL.
	 *     Columns belonging to a key in $uniqueIndexes must be defined here and non-null.
	 *   - An integer-keyed list of such string-keyed maps, defining a list of new rows.
	 *     The keys in each map must be identical to each other and in the same order.
	 *     The rows must not collide with each other.
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function replace( $table, $uniqueKeys, $rows, $fname = __METHOD__ );

	/**
	 * Upsert the given row(s) into a table
	 *
	 * This updates any existing rows that conflict with the provided rows and inserts
	 * any of the provided rows that do not conflict with existing rows. Conflicts are
	 * determined by the provided unique indexes.
	 *
	 * @param string $table Table name
	 * @param array|array[] $rows Row(s) to insert, in the form of either:
	 *   - A string-keyed map of (column name => value) defining a new row. Values are
	 *     treated as literals and quoted appropriately; null is interpreted as NULL.
	 *     Columns belonging to a key in $uniqueIndexes must be defined here and non-null.
	 *   - An integer-keyed list of such string-keyed maps, defining a list of new rows.
	 *     The keys in each map must be identical to each other and in the same order.
	 *     The rows must not collide with each other.
	 * @param string|string[]|string[][] $uniqueKeys Column name or non-empty list of column
	 *   name lists that define all applicable unique keys on the table. Each unique key on the
	 *   table is "applicable" unless either:
	 *     - It involves an AUTOINCREMENT column for which no values are assigned in $rows
	 *     - It involves a UUID column for which newly generated UUIDs are assigned in $rows
	 * @param array $set Combination map/list where each string-keyed entry maps a column
	 *   to a literal assigned value and each integer-keyed value is a SQL expression in the
	 *   format of a column assignment within UPDATE...SET. The (column => value) entries are
	 *   convenient due to automatic value quoting and conversion of null to NULL. The SQL
	 *   assignment format is useful for updates like "column = column + X". All assignments
	 *   have no defined execution order, so they should not depend on each other. Do not
	 *   modified AUTOINCREMENT or UUID columns in assignments.
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling
	 * @return bool Return true if no exception was thrown (deprecated since 1.33)
	 * @throws DBError If an error occurs, see IDatabase::query()
	 * @since 1.22
	 */
	public function upsert(
		$table, array $rows, $uniqueKeys, array $set, $fname = __METHOD__
	);

	/**
	 * DELETE where the condition is a join.
	 *
	 * MySQL overrides this to use a multi-table DELETE syntax, in other databases
	 * we use sub-selects
	 *
	 * For safety, an empty $conds will not delete everything. If you want to
	 * delete all rows where the join condition matches, set $conds=IDatabase::ALL_ROWS.
	 *
	 * DO NOT put the join condition in $conds.
	 *
	 * @param string $delTable The table to delete from.
	 * @param string $joinTable The other table.
	 * @param string $delVar The variable to join on, in the first table.
	 * @param string $joinVar The variable to join on, in the second table.
	 * @param array|string $conds Condition array of field names mapped to variables,
	 *   ANDed together in the WHERE clause
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling
	 * @throws DBError If an error occurs, see IDatabase::query()
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
	 * @param string $table Table name
	 * @param string|array $conds Array of conditions. See $conds in IDatabase::select()
	 *   In order to prevent possible performance or replication issues or damaging a data
	 *   accidentally, an empty condition for 'delete' queries isn't allowed.
	 *   IDatabase::ALL_ROWS should be passed explicitely in order to delete all rows.
	 * @param string $fname Name of the calling function
	 * @return bool Return true if no exception was thrown (deprecated since 1.33)
	 * @throws DBError If an error occurs, see IDatabase::query()
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
	 * @param string $destTable The table name to insert into
	 * @param string|array $srcTable May be either a table name, or an array of table names
	 *    to include in a join.
	 * @param array $varMap Must be an associative array of the form
	 *    [ 'dest1' => 'source1', ... ]. Source items may be literals
	 *    rather than field names, but strings should be quoted with
	 *    IDatabase::addQuotes()
	 * @param array $conds Condition array. See $conds in IDatabase::select() for
	 *    the details of the format of condition arrays. May be "*" to copy the
	 *    whole table.
	 * @param string $fname The function name of the caller, from __METHOD__
	 * @param array $insertOptions Options for the INSERT part of the query, see
	 *    IDatabase::insert() for details. Also, one additional option is
	 *    available: pass 'NO_AUTO_COLUMNS' to hint that the query does not use
	 *    an auto-increment or sequence to determine any column values.
	 * @param array $selectOptions Options for the SELECT part of the query, see
	 *    IDatabase::select() for details.
	 * @param array $selectJoinConds Join conditions for the SELECT part of the query, see
	 *    IDatabase::select() for details.
	 * @return bool Return true if no exception was thrown (deprecated since 1.33)
	 * @throws DBError If an error occurs, see IDatabase::query()
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
	 * Determine if the RDBMS supports ORDER BY and LIMIT for separate subqueries within UNION
	 *
	 * @return bool
	 */
	public function unionSupportsOrderAndLimit();

	/**
	 * Construct a UNION query
	 *
	 * This is used for providing overload point for other DB abstractions
	 * not compatible with the MySQL syntax.
	 * @param array $sqls SQL statements to combine
	 * @param bool $all Either IDatabase::UNION_ALL or IDatabase::UNION_DISTINCT
	 * @return string SQL fragment
	 */
	public function unionQueries( $sqls, $all );

	/**
	 * Construct a UNION query for permutations of conditions
	 *
	 * Databases sometimes have trouble with queries that have multiple values
	 * for multiple condition parameters combined with limits and ordering.
	 * This method constructs queries for the Cartesian product of the
	 * conditions and unions them all together.
	 *
	 * @see IDatabase::select()
	 * @param string|array $table Table name
	 * @param string|array $vars Field names
	 * @param array $permute_conds Conditions for the Cartesian product. Keys
	 *  are field names, values are arrays of the possible values for that
	 *  field.
	 * @param string|array $extra_conds Additional conditions to include in the
	 *  query.
	 * @param string $fname Caller function name
	 * @param string|array $options Query options. In addition to the options
	 *  recognized by IDatabase::select(), the following may be used:
	 *   - NOTALL: Set to use UNION instead of UNION ALL.
	 *   - INNER ORDER BY: If specified and supported, subqueries will use this
	 *     instead of ORDER BY.
	 * @param string|array $join_conds Join conditions
	 * @return string SQL query string.
	 * @since 1.30
	 */
	public function unionConditionPermutations(
		$table,
		$vars,
		array $permute_conds,
		$extra_conds = '',
		$fname = __METHOD__,
		$options = [],
		$join_conds = []
	);

	/**
	 * Returns an SQL expression for a simple conditional
	 *
	 * This doesn't need to be overridden unless CASE isn't supported in the RDBMS.
	 *
	 * @param string|array $cond SQL expression which will result in a boolean value
	 * @param string $trueVal SQL expression to return if true
	 * @param string $falseVal SQL expression to return if false
	 * @return string SQL fragment
	 */
	public function conditional( $cond, $trueVal, $falseVal );

	/**
	 * Returns a SQL expression for simple string replacement (e.g. REPLACE() in mysql)
	 *
	 * @param string $orig Column to modify
	 * @param string $old Column to seek
	 * @param string $new Column to replace with
	 * @return string
	 */
	public function strreplace( $orig, $old, $new );

	/**
	 * Determines how long the server has been up
	 *
	 * @return int
	 * @throws DBError
	 */
	public function getServerUptime();

	/**
	 * Determines if the last failure was due to a deadlock
	 *
	 * Note that during a deadlock, the prior transaction will have been lost
	 *
	 * @return bool
	 */
	public function wasDeadlock();

	/**
	 * Determines if the last failure was due to a lock timeout
	 *
	 * Note that during a lock wait timeout, the prior transaction will have been lost
	 *
	 * @return bool
	 */
	public function wasLockTimeout();

	/**
	 * Determines if the last query error was due to a dropped connection
	 *
	 * Note that during a connection loss, the prior transaction will have been lost
	 *
	 * @return bool
	 * @since 1.31
	 */
	public function wasConnectionLoss();

	/**
	 * Determines if the last failure was due to the database being read-only
	 *
	 * @return bool
	 */
	public function wasReadOnlyError();

	/**
	 * Determines if the last query error was due to something outside of the query itself
	 *
	 * Note that the transaction may have been lost, discarding prior writes and results
	 *
	 * @return bool
	 */
	public function wasErrorReissuable();

	/**
	 * Wait for the replica DB to catch up to a given master position
	 *
	 * Note that this does not start any new transactions. If any existing transaction
	 * is flushed, and this is called, then queries will reflect the point the DB was synced
	 * up to (on success) without interference from REPEATABLE-READ snapshots.
	 *
	 * @param DBMasterPos $pos
	 * @param int $timeout The maximum number of seconds to wait for synchronisation
	 * @return int|null Zero if the replica DB was past that position already,
	 *   greater than zero if we waited for some period of time, less than
	 *   zero if it timed out, and null on error
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function masterPosWait( DBMasterPos $pos, $timeout );

	/**
	 * Get the replication position of this replica DB
	 *
	 * @return DBMasterPos|bool False if this is not a replica DB
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function getReplicaPos();

	/**
	 * Get the position of this master
	 *
	 * @return DBMasterPos|bool False if this is not a master
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function getMasterPos();

	/**
	 * @return bool Whether the DB is marked as read-only server-side
	 * @since 1.28
	 */
	public function serverIsReadOnly();

	/**
	 * Run a callback as soon as the current transaction commits or rolls back
	 *
	 * An error is thrown if no transaction is pending. Queries in the function will run in
	 * AUTOCOMMIT mode unless there are begin() calls. Callbacks must commit any transactions
	 * that they begin.
	 *
	 * This is useful for combining cooperative locks and DB transactions.
	 *
	 * Note this is called when the whole transaction is resolved. To take action immediately
	 * when an atomic section is cancelled, use onAtomicSectionCancel().
	 *
	 * @note do not assume that *other* IDatabase instances will be AUTOCOMMIT mode
	 *
	 * The callback takes the following arguments:
	 *   - How the transaction ended (IDatabase::TRIGGER_COMMIT or IDatabase::TRIGGER_ROLLBACK)
	 *   - This IDatabase instance (since 1.32)
	 *
	 * @param callable $callback
	 * @param string $fname Caller name
	 * @throws DBError If an error occurs, see IDatabase::query()
	 * @throws Exception If the callback runs immediately and an error occurs in it
	 * @since 1.28
	 */
	public function onTransactionResolution( callable $callback, $fname = __METHOD__ );

	/**
	 * Run a callback as soon as there is no transaction pending
	 *
	 * If there is a transaction and it is rolled back, then the callback is cancelled.
	 *
	 * When transaction round mode (DBO_TRX) is set, the callback will run at the end
	 * of the round, just after all peer transactions COMMIT. If the transaction round
	 * is rolled back, then the callback is cancelled.
	 *
	 * Queries in the function will run in AUTOCOMMIT mode unless there are begin() calls.
	 * Callbacks must commit any transactions that they begin.
	 *
	 * This is useful for updates to different systems or when separate transactions are needed.
	 * For example, one might want to enqueue jobs into a system outside the database, but only
	 * after the database is updated so that the jobs will see the data when they actually run.
	 * It can also be used for updates that easily suffer from lock timeouts and deadlocks,
	 * but where atomicity is not essential.
	 *
	 * Avoid using IDatabase instances aside from this one in the callback, unless such instances
	 * never have IDatabase::DBO_TRX set. This keeps callbacks from interfering with one another.
	 *
	 * Updates will execute in the order they were enqueued.
	 *
	 * @note do not assume that *other* IDatabase instances will be AUTOCOMMIT mode
	 *
	 * The callback takes the following arguments:
	 *   - How the transaction ended (IDatabase::TRIGGER_COMMIT or IDatabase::TRIGGER_IDLE)
	 *   - This IDatabase instance (since 1.32)
	 *
	 * @param callable $callback
	 * @param string $fname Caller name
	 * @throws DBError If an error occurs, see IDatabase::query()
	 * @throws Exception If the callback runs immediately and an error occurs in it
	 * @since 1.32
	 */
	public function onTransactionCommitOrIdle( callable $callback, $fname = __METHOD__ );

	/**
	 * Alias for onTransactionCommitOrIdle() for backwards-compatibility
	 *
	 * @param callable $callback
	 * @param string $fname
	 * @since 1.20
	 * @deprecated Since 1.32
	 */
	public function onTransactionIdle( callable $callback, $fname = __METHOD__ );

	/**
	 * Run a callback before the current transaction commits or now if there is none
	 *
	 * If there is a transaction and it is rolled back, then the callback is cancelled.
	 *
	 * When transaction round mode (DBO_TRX) is set, the callback will run at the end
	 * of the round, just before all peer transactions COMMIT. If the transaction round
	 * is rolled back, then the callback is cancelled.
	 *
	 * Callbacks must not start nor commit any transactions. If no transaction is active,
	 * then a transaction will wrap the callback.
	 *
	 * This is useful for updates that easily suffer from lock timeouts and deadlocks,
	 * but where atomicity is strongly desired for these updates and some related updates.
	 *
	 * Updates will execute in the order they were enqueued.
	 *
	 * The callback takes the one argument:
	 *   - This IDatabase instance (since 1.32)
	 *
	 * @param callable $callback
	 * @param string $fname Caller name
	 * @throws DBError If an error occurs, see IDatabase::query()
	 * @throws Exception If the callback runs immediately and an error occurs in it
	 * @since 1.22
	 */
	public function onTransactionPreCommitOrIdle( callable $callback, $fname = __METHOD__ );

	/**
	 * Run a callback when the atomic section is cancelled
	 *
	 * The callback is run just after the current atomic section, any outer
	 * atomic section, or the whole transaction is rolled back.
	 *
	 * An error is thrown if no atomic section is pending. The atomic section
	 * need not have been created with the ATOMIC_CANCELABLE flag.
	 *
	 * Queries in the function may be running in the context of an outer
	 * transaction or may be running in AUTOCOMMIT mode. The callback should
	 * use atomic sections if necessary.
	 *
	 * @note do not assume that *other* IDatabase instances will be AUTOCOMMIT mode
	 *
	 * The callback takes the following arguments:
	 *   - IDatabase::TRIGGER_CANCEL or IDatabase::TRIGGER_ROLLBACK
	 *   - This IDatabase instance
	 *
	 * @param callable $callback
	 * @param string $fname Caller name
	 * @since 1.34
	 */
	public function onAtomicSectionCancel( callable $callback, $fname = __METHOD__ );

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
	 */
	public function setTransactionListener( $name, callable $callback = null );

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
	 * @param string $fname
	 * @param string $cancelable Pass self::ATOMIC_CANCELABLE to use a
	 *  savepoint and enable self::cancelAtomic() for this section.
	 * @return AtomicSectionIdentifier section ID token
	 * @throws DBError If an error occurs, see IDatabase::query()
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
	 * @param string $fname
	 * @throws DBError If an error occurs, see IDatabase::query()
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
	 * @note As a micro-optimization to save a few DB calls, this method may only
	 *  be called when startAtomic() was called with the ATOMIC_CANCELABLE flag.
	 * @since 1.31
	 * @see IDatabase::startAtomic
	 * @param string $fname
	 * @param AtomicSectionIdentifier|null $sectionId Section ID from startAtomic();
	 *   passing this enables cancellation of unclosed nested sections [optional]
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function cancelAtomic( $fname = __METHOD__, AtomicSectionIdentifier $sectionId = null );

	/**
	 * Perform an atomic section of reversable SQL statements from a callback
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
	 *         // blob store throughs StoreFailureException on failure
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
	 * @param string $fname Caller name (usually __METHOD__)
	 * @param callable $callback Callback that issues DB updates
	 * @param string $cancelable Pass self::ATOMIC_CANCELABLE to use a
	 *  savepoint and enable self::cancelAtomic() for this section.
	 * @return mixed $res Result of the callback (since 1.28)
	 * @throws DBError If an error occurs, see IDatabase::query()
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
	 * Only call this from code with outer transcation scope.
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
	 * @param string $fname Calling function name
	 * @param string $mode A situationally valid IDatabase::TRANSACTION_* constant [optional]
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function begin( $fname = __METHOD__, $mode = self::TRANSACTION_EXPLICIT );

	/**
	 * Commits a transaction previously started using begin()
	 *
	 * If no transaction is in progress, a warning is issued.
	 *
	 * Only call this from code with outer transcation scope.
	 * See https://www.mediawiki.org/wiki/Database_transactions for details.
	 * Nesting of transactions is not supported.
	 *
	 * @param string $fname
	 * @param string $flush Flush flag, set to situationally valid IDatabase::FLUSHING_*
	 *   constant to disable warnings about explicitly committing implicit transactions,
	 *   or calling commit when no transaction is in progress.
	 *   This will trigger an exception if there is an ongoing explicit transaction.
	 *   Only set the flush flag if you are sure that these warnings are not applicable,
	 *   and no explicit transactions are open.
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function commit( $fname = __METHOD__, $flush = self::FLUSHING_ONE );

	/**
	 * Rollback a transaction previously started using begin()
	 *
	 * Only call this from code with outer transcation scope.
	 * See https://www.mediawiki.org/wiki/Database_transactions for details.
	 * Nesting of transactions is not supported. If a serious unexpected error occurs,
	 * throwing an Exception is preferrable, using a pre-installed error handler to trigger
	 * rollback (in any case, failure to issue COMMIT will cause rollback server-side).
	 *
	 * Query, connection, and onTransaction* callback errors will be suppressed and logged.
	 *
	 * @param string $fname Calling function name
	 * @param string $flush Flush flag, set to a situationally valid IDatabase::FLUSHING_*
	 *   constant to disable warnings about calling rollback when no transaction is in
	 *   progress. This will silently break any ongoing explicit transaction. Only set the
	 *   flush flag if you are sure that it is safe to ignore these warnings in your context.
	 * @throws DBError If an error occurs, see IDatabase::query()
	 * @since 1.23 Added $flush parameter
	 */
	public function rollback( $fname = __METHOD__, $flush = self::FLUSHING_ONE );

	/**
	 * Commit any transaction but error out if writes or callbacks are pending
	 *
	 * This is intended for clearing out REPEATABLE-READ snapshots so that callers can
	 * see a new point-in-time of the database. This is useful when one of many transaction
	 * rounds finished and significant time will pass in the script's lifetime. It is also
	 * useful to call on a replica DB after waiting on replication to catch up to the master.
	 *
	 * @param string $fname Calling function name
	 * @param string $flush Flush flag, set to situationally valid IDatabase::FLUSHING_*
	 *   constant to disable warnings about explicitly committing implicit transactions,
	 *   or calling commit when no transaction is in progress.
	 *   This will trigger an exception if there is an ongoing explicit transaction.
	 *   Only set the flush flag if you are sure that these warnings are not applicable,
	 *   and no explicit transactions are open.
	 * @throws DBError If an error occurs, see IDatabase::query()
	 * @since 1.28
	 * @since 1.34 Added $flush parameter
	 */
	public function flushSnapshot( $fname = __METHOD__, $flush = self::FLUSHING_ONE );

	/**
	 * Convert a timestamp in one of the formats accepted by ConvertibleTimestamp
	 * to the format used for inserting into timestamp fields in this DBMS
	 *
	 * The result is unquoted, and needs to be passed through addQuotes()
	 * before it can be included in raw SQL.
	 *
	 * @param string|int $ts
	 *
	 * @return string
	 */
	public function timestamp( $ts = 0 );

	/**
	 * Convert a timestamp in one of the formats accepted by ConvertibleTimestamp
	 * to the format used for inserting into timestamp fields in this DBMS
	 *
	 * If NULL is input, it is passed through, allowing NULL values to be inserted
	 * into timestamp fields.
	 *
	 * The result is unquoted, and needs to be passed through addQuotes()
	 * before it can be included in raw SQL.
	 *
	 * @param string|int|null $ts
	 *
	 * @return string|null
	 */
	public function timestampOrNull( $ts = null );

	/**
	 * Ping the server and try to reconnect if it there is no connection
	 *
	 * @param float|null &$rtt Value to store the estimated RTT [optional]
	 * @return bool Success or failure
	 */
	public function ping( &$rtt = null );

	/**
	 * Get the amount of replication lag for this database server
	 *
	 * Callers should avoid using this method while a transaction is active
	 *
	 * @return int|bool Database replication lag in seconds or false on error
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function getLag();

	/**
	 * Get the replica DB lag when the current transaction started
	 * or a general lag estimate if not transaction is active
	 *
	 * This is useful when transactions might use snapshot isolation
	 * (e.g. REPEATABLE-READ in innodb), so the "real" lag of that data
	 * is this lag plus transaction duration. If they don't, it is still
	 * safe to be pessimistic. In AUTOCOMMIT mode, this still gives an
	 * indication of the staleness of subsequent reads.
	 *
	 * @return array ('lag': seconds or false on error, 'since': UNIX timestamp of BEGIN)
	 * @throws DBError If an error occurs, see IDatabase::query()
	 * @since 1.27
	 */
	public function getSessionLagStatus();

	/**
	 * Return the maximum number of items allowed in a list, or 0 for unlimited
	 *
	 * @return int
	 */
	public function maxListLen();

	/**
	 * Some DBMSs have a special format for inserting into blob fields, they
	 * don't allow simple quoted strings to be inserted. To insert into such
	 * a field, pass the data through this function before passing it to
	 * IDatabase::insert().
	 *
	 * @param string $b
	 * @return string|Blob
	 * @throws DBError
	 */
	public function encodeBlob( $b );

	/**
	 * Some DBMSs return a special placeholder object representing blob fields
	 * in result objects. Pass the object through this function to return the
	 * original string.
	 *
	 * @param string|Blob $b
	 * @return string
	 * @throws DBError
	 */
	public function decodeBlob( $b );

	/**
	 * Override database's default behavior. $options include:
	 *     'connTimeout' : Set the connection timeout value in seconds.
	 *                     May be useful for very long batch queries such as
	 *                     full-wiki dumps, where a single query reads out over
	 *                     hours or days.
	 *
	 * @param array $options
	 * @return void
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function setSessionOptions( array $options );

	/**
	 * Set schema variables to be used when streaming commands from SQL files or stdin
	 *
	 * Variables appear as SQL comments and are substituted by their corresponding values
	 *
	 * @param array|null $vars Map of (variable => value) or null to use the defaults
	 */
	public function setSchemaVars( $vars );

	/**
	 * Check to see if a named lock is not locked by any thread (non-blocking)
	 *
	 * @param string $lockName Name of lock to poll
	 * @param string $method Name of method calling us
	 * @return bool
	 * @throws DBError If an error occurs, see IDatabase::query()
	 * @since 1.20
	 */
	public function lockIsFree( $lockName, $method );

	/**
	 * Acquire a named lock
	 *
	 * Named locks are not related to transactions
	 *
	 * @param string $lockName Name of lock to aquire
	 * @param string $method Name of the calling method
	 * @param int $timeout Acquisition timeout in seconds (0 means non-blocking)
	 * @return bool Success
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function lock( $lockName, $method, $timeout = 5 );

	/**
	 * Release a lock
	 *
	 * Named locks are not related to transactions
	 *
	 * @param string $lockName Name of lock to release
	 * @param string $method Name of the calling method
	 * @return bool Success
	 * @throws DBError If an error occurs, see IDatabase::query()
	 */
	public function unlock( $lockName, $method );

	/**
	 * Acquire a named lock, flush any transaction, and return an RAII style unlocker object
	 *
	 * Only call this from outer transcation scope and when only one DB will be affected.
	 * See https://www.mediawiki.org/wiki/Database_transactions for details.
	 *
	 * This is suitiable for transactions that need to be serialized using cooperative locks,
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
	 * @throws DBError If an error occurs, see IDatabase::query()
	 * @since 1.27
	 */
	public function getScopedLockAndFlush( $lockKey, $fname, $timeout );

	/**
	 * Check to see if a named lock used by lock() use blocking queues
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function namedLocksEnqueue();

	/**
	 * Find out when 'infinity' is. Most DBMSes support this. This is a special
	 * keyword for timestamps in PostgreSQL, and works with CHAR(14) as well
	 * because "i" sorts after all numbers.
	 *
	 * @return string
	 */
	public function getInfinity();

	/**
	 * Encode an expiry time into the DBMS dependent format
	 *
	 * @param string $expiry Timestamp for expiry, or the 'infinity' string
	 * @return string
	 */
	public function encodeExpiry( $expiry );

	/**
	 * Decode an expiry time into a DBMS independent format
	 *
	 * @param string $expiry DB timestamp field value for expiry
	 * @param int $format TS_* constant, defaults to TS_MW
	 * @return string
	 */
	public function decodeExpiry( $expiry, $format = TS_MW );

	/**
	 * Allow or deny "big selects" for this session only. This is done by setting
	 * the sql_big_selects session variable.
	 *
	 * This is a MySQL-specific feature.
	 *
	 * @param bool|string $value True for allow, false for deny, or "default" to
	 *   restore the initial value
	 */
	public function setBigSelects( $value = true );

	/**
	 * @return bool Whether this DB is read-only
	 * @since 1.27
	 */
	public function isReadOnly();

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
	 * @since 1.28
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
	 * Get a debugging string that mentions the database type, the ID of this instance,
	 * and the ID of any underlying connection resource or driver object if one is present
	 *
	 * @return string "<db type> object #<X>" or "<db type> object #<X> (resource/handle id #<Y>)"
	 * @since 1.34
	 */
	public function __toString();
}

/**
 * @deprecated since 1.29
 */
class_alias( IDatabase::class, 'IDatabase' );
