<?php
/**
 * @defgroup Database Database
 *
 * This file deals with database interface functions
 * and query specifics/optimisations.
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

use Wikimedia\ScopedCallback;
use Exception;
use RuntimeException;
use UnexpectedValueException;
use stdClass;

/**
 * Basic database interface for live and lazy-loaded relation database handles
 *
 * @note: IDatabase and DBConnRef should be updated to reflect any changes
 * @ingroup Database
 */
interface IDatabase {
	/** @var int Callback triggered immediately due to no active transaction */
	const TRIGGER_IDLE = 1;
	/** @var int Callback triggered by COMMIT */
	const TRIGGER_COMMIT = 2;
	/** @var int Callback triggered by ROLLBACK */
	const TRIGGER_ROLLBACK = 3;

	/** @var string Transaction is requested by regular caller outside of the DB layer */
	const TRANSACTION_EXPLICIT = '';
	/** @var string Transaction is requested internally via DBO_TRX/startAtomic() */
	const TRANSACTION_INTERNAL = 'implicit';

	/** @var string Transaction operation comes from service managing all DBs */
	const FLUSHING_ALL_PEERS = 'flush';
	/** @var string Transaction operation comes from the database class internally */
	const FLUSHING_INTERNAL = 'flush';

	/** @var string Do not remember the prior flags */
	const REMEMBER_NOTHING = '';
	/** @var string Remember the prior flags */
	const REMEMBER_PRIOR = 'remember';
	/** @var string Restore to the prior flag state */
	const RESTORE_PRIOR = 'prior';
	/** @var string Restore to the initial flag state */
	const RESTORE_INITIAL = 'initial';

	/** @var string Estimate total time (RTT, scanning, waiting on locks, applying) */
	const ESTIMATE_TOTAL = 'total';
	/** @var string Estimate time to apply (scanning, applying) */
	const ESTIMATE_DB_APPLY = 'apply';

	/** @var int Combine list with comma delimeters */
	const LIST_COMMA = 0;
	/** @var int Combine list with AND clauses */
	const LIST_AND = 1;
	/** @var int Convert map into a SET clause */
	const LIST_SET = 2;
	/** @var int Treat as field name and do not apply value escaping */
	const LIST_NAMES = 3;
	/** @var int Combine list with OR clauses */
	const LIST_OR = 4;

	/** @var int Enable debug logging */
	const DBO_DEBUG = 1;
	/** @var int Disable query buffering (only one result set can be iterated at a time) */
	const DBO_NOBUFFER = 2;
	/** @var int Ignore query errors (internal use only!) */
	const DBO_IGNORE = 4;
	/** @var int Autoatically start transaction on first query (work with ILoadBalancer rounds) */
	const DBO_TRX = 8;
	/** @var int Use DBO_TRX in non-CLI mode */
	const DBO_DEFAULT = 16;
	/** @var int Use DB persistent connections if possible */
	const DBO_PERSISTENT = 32;
	/** @var int DBA session mode; mostly for Oracle */
	const DBO_SYSDBA = 64;
	/** @var int Schema file mode; mostly for Oracle */
	const DBO_DDLMODE = 128;
	/** @var int Enable SSL/TLS in connection protocol */
	const DBO_SSL = 256;
	/** @var int Enable compression in connection protocol */
	const DBO_COMPRESS = 512;

	/**
	 * A string describing the current software version, and possibly
	 * other details in a user-friendly way. Will be listed on Special:Version, etc.
	 * Use getServerVersion() to get machine-friendly information.
	 *
	 * @return string Version information from the database server
	 */
	public function getServerInfo();

	/**
	 * Turns buffering of SQL result sets on (true) or off (false). Default is "on".
	 *
	 * Unbuffered queries are very troublesome in MySQL:
	 *
	 *   - If another query is executed while the first query is being read
	 *     out, the first query is killed. This means you can't call normal
	 *     Database functions while you are reading an unbuffered query result
	 *     from a normal Database connection.
	 *
	 *   - Unbuffered queries cause the MySQL server to use large amounts of
	 *     memory and to hold broad locks which block other queries.
	 *
	 * If you want to limit client-side memory, it's almost always better to
	 * split up queries into batches using a LIMIT clause than to switch off
	 * buffering.
	 *
	 * @param null|bool $buffer
	 * @return null|bool The previous value of the flag
	 */
	public function bufferResults( $buffer = null );

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
	 * This can be used to reason about the staleness of SELECT data
	 * in REPEATABLE-READ transaction isolation level.
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
	 * Get/set the table prefix.
	 * @param string $prefix The table prefix to set, or omitted to leave it unchanged.
	 * @return string The previous table prefix.
	 */
	public function tablePrefix( $prefix = null );

	/**
	 * Get/set the db schema.
	 * @param string $schema The database schema to set, or omitted to leave it unchanged.
	 * @return string The previous db schema.
	 */
	public function dbSchema( $schema = null );

	/**
	 * Get properties passed down from the server info array of the load
	 * balancer.
	 *
	 * @param string $name The entry of the info array to get, or null to get the
	 *   whole array
	 *
	 * @return array|mixed|null
	 */
	public function getLBInfo( $name = null );

	/**
	 * Set the LB info array, or a member of it. If called with one parameter,
	 * the LB info array is set to that parameter. If it is called with two
	 * parameters, the member with the given name is set to the given value.
	 *
	 * @param string $name
	 * @param array $value
	 */
	public function setLBInfo( $name, $value = null );

	/**
	 * Set a lazy-connecting DB handle to the master DB (for replication status purposes)
	 *
	 * @param IDatabase $conn
	 * @since 1.27
	 */
	public function setLazyMasterHandle( IDatabase $conn );

	/**
	 * Returns true if this database does an implicit sort when doing GROUP BY
	 *
	 * @return bool
	 */
	public function implicitGroupby();

	/**
	 * Returns true if this database does an implicit order by when the column has an index
	 * For example: SELECT page_title FROM page LIMIT 1
	 *
	 * @return bool
	 */
	public function implicitOrderby();

	/**
	 * Return the last query that went through IDatabase::query()
	 * @return string
	 */
	public function lastQuery();

	/**
	 * Returns true if the connection may have been used for write queries.
	 * Should return true if unsure.
	 *
	 * @return bool
	 */
	public function doneWrites();

	/**
	 * Returns the last time the connection may have been used for write queries.
	 * Should return a timestamp if unsure.
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
	 * Returns true if there is a transaction open with possible write
	 * queries or transaction pre-commit/idle callbacks waiting on it to finish.
	 * This does *not* count recurring callbacks, e.g. from setTransactionListener().
	 *
	 * @return bool
	 */
	public function writesOrCallbacksPending();

	/**
	 * Get the time spend running write queries for this transaction
	 *
	 * High times could be due to scanning, updates, locking, and such
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
	 * Is a connection to the database open?
	 * @return bool
	 */
	public function isOpen();

	/**
	 * Set a flag for this connection
	 *
	 * @param int $flag DBO_* constants from Defines.php:
	 *   - DBO_DEBUG: output some debug info (same as debug())
	 *   - DBO_NOBUFFER: don't buffer results (inverse of bufferResults())
	 *   - DBO_TRX: automatically start transactions
	 *   - DBO_DEFAULT: automatically sets DBO_TRX if not in command line mode
	 *       and removes it in command line mode
	 *   - DBO_PERSISTENT: use persistant database connection
	 * @param string $remember IDatabase::REMEMBER_* constant [default: REMEMBER_NOTHING]
	 */
	public function setFlag( $flag, $remember = self::REMEMBER_NOTHING );

	/**
	 * Clear a flag for this connection
	 *
	 * @param int $flag DBO_* constants from Defines.php:
	 *   - DBO_DEBUG: output some debug info (same as debug())
	 *   - DBO_NOBUFFER: don't buffer results (inverse of bufferResults())
	 *   - DBO_TRX: automatically start transactions
	 *   - DBO_DEFAULT: automatically sets DBO_TRX if not in command line mode
	 *       and removes it in command line mode
	 *   - DBO_PERSISTENT: use persistant database connection
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
	 * @param int $flag DBO_* constants from Defines.php:
	 *   - DBO_DEBUG: output some debug info (same as debug())
	 *   - DBO_NOBUFFER: don't buffer results (inverse of bufferResults())
	 *   - DBO_TRX: automatically start transactions
	 *   - DBO_PERSISTENT: use persistant database connection
	 * @return bool
	 */
	public function getFlag( $flag );

	/**
	 * @return string
	 */
	public function getDomainID();

	/**
	 * Alias for getDomainID()
	 *
	 * @return string
	 */
	public function getWikiID();

	/**
	 * Get the type of the DBMS, as it appears in $wgDBtype.
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * Open a connection to the database. Usually aborts on failure
	 *
	 * @param string $server Database server host
	 * @param string $user Database user name
	 * @param string $password Database user password
	 * @param string $dbName Database name
	 * @return bool
	 * @throws DBConnectionError
	 */
	public function open( $server, $user, $password, $dbName );

	/**
	 * Fetch the next row from the given result object, in object form.
	 * Fields can be retrieved with $row->fieldname, with fields acting like
	 * member variables.
	 * If no more rows are available, false is returned.
	 *
	 * @param IResultWrapper|stdClass $res Object as returned from IDatabase::query(), etc.
	 * @return stdClass|bool
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	public function fetchObject( $res );

	/**
	 * Fetch the next row from the given result object, in associative array
	 * form. Fields are retrieved with $row['fieldname'].
	 * If no more rows are available, false is returned.
	 *
	 * @param IResultWrapper $res Result object as returned from IDatabase::query(), etc.
	 * @return array|bool
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	public function fetchRow( $res );

	/**
	 * Get the number of rows in a result object
	 *
	 * @param mixed $res A SQL result
	 * @return int
	 */
	public function numRows( $res );

	/**
	 * Get the number of fields in a result object
	 * @see https://secure.php.net/mysql_num_fields
	 *
	 * @param mixed $res A SQL result
	 * @return int
	 */
	public function numFields( $res );

	/**
	 * Get a field name in a result object
	 * @see https://secure.php.net/mysql_field_name
	 *
	 * @param mixed $res A SQL result
	 * @param int $n
	 * @return string
	 */
	public function fieldName( $res, $n );

	/**
	 * Get the inserted value of an auto-increment row
	 *
	 * The value inserted should be fetched from nextSequenceValue()
	 *
	 * Example:
	 * $id = $dbw->nextSequenceValue( 'page_page_id_seq' );
	 * $dbw->insert( 'page', [ 'page_id' => $id ] );
	 * $id = $dbw->insertId();
	 *
	 * @return int
	 */
	public function insertId();

	/**
	 * Change the position of the cursor in a result object
	 * @see https://secure.php.net/mysql_data_seek
	 *
	 * @param mixed $res A SQL result
	 * @param int $row
	 */
	public function dataSeek( $res, $row );

	/**
	 * Get the last error number
	 * @see https://secure.php.net/mysql_errno
	 *
	 * @return int
	 */
	public function lastErrno();

	/**
	 * Get a description of the last error
	 * @see https://secure.php.net/mysql_error
	 *
	 * @return string
	 */
	public function lastError();

	/**
	 * mysql_fetch_field() wrapper
	 * Returns false if the field doesn't exist
	 *
	 * @param string $table Table name
	 * @param string $field Field name
	 *
	 * @return Field
	 */
	public function fieldInfo( $table, $field );

	/**
	 * Get the number of rows affected by the last write query
	 * @see https://secure.php.net/mysql_affected_rows
	 *
	 * @return int
	 */
	public function affectedRows();

	/**
	 * Returns a wikitext link to the DB's website, e.g.,
	 *   return "[https://www.mysql.com/ MySQL]";
	 * Should at least contain plain text, if for some reason
	 * your database has no website.
	 *
	 * @return string Wikitext of a link to the server software's web site
	 */
	public function getSoftwareLink();

	/**
	 * A string describing the current software version, like from
	 * mysql_get_server_info().
	 *
	 * @return string Version information from the database server.
	 */
	public function getServerVersion();

	/**
	 * Closes a database connection.
	 * if it is open : commits any open transactions
	 *
	 * @throws DBError
	 * @return bool Operation success. true if already closed.
	 */
	public function close();

	/**
	 * @param string $error Fallback error message, used if none is given by DB
	 * @throws DBConnectionError
	 */
	public function reportConnectionError( $error = 'Unknown error' );

	/**
	 * Run an SQL query and return the result. Normally throws a DBQueryError
	 * on failure. If errors are ignored, returns false instead.
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
	 * @param bool $tempIgnore Whether to avoid throwing an exception on errors...
	 *     maybe best to catch the exception instead?
	 * @throws DBError
	 * @return bool|IResultWrapper True for a successful write query, IResultWrapper object
	 *     for a successful read query, or false on failure if $tempIgnore set
	 */
	public function query( $sql, $fname = __METHOD__, $tempIgnore = false );

	/**
	 * Report a query error. Log the error, and if neither the object ignore
	 * flag nor the $tempIgnore flag is set, throw a DBQueryError.
	 *
	 * @param string $error
	 * @param int $errno
	 * @param string $sql
	 * @param string $fname
	 * @param bool $tempIgnore
	 * @throws DBQueryError
	 */
	public function reportQueryError( $error, $errno, $sql, $fname, $tempIgnore = false );

	/**
	 * Free a result object returned by query() or select(). It's usually not
	 * necessary to call this, just use unset() or let the variable holding
	 * the result object go out of scope.
	 *
	 * @param mixed $res A SQL result
	 */
	public function freeResult( $res );

	/**
	 * A SELECT wrapper which returns a single field from a single result row.
	 *
	 * Usually throws a DBQueryError on failure. If errors are explicitly
	 * ignored, returns false on failure.
	 *
	 * If no result rows are returned from the query, false is returned.
	 *
	 * @param string|array $table Table name. See IDatabase::select() for details.
	 * @param string $var The field name to select. This must be a valid SQL
	 *   fragment: do not use unvalidated user input.
	 * @param string|array $cond The condition array. See IDatabase::select() for details.
	 * @param string $fname The function name of the caller.
	 * @param string|array $options The query options. See IDatabase::select() for details.
	 *
	 * @return bool|mixed The value from the field, or false on failure.
	 */
	public function selectField(
		$table, $var, $cond = '', $fname = __METHOD__, $options = []
	);

	/**
	 * A SELECT wrapper which returns a list of single field values from result rows.
	 *
	 * Usually throws a DBQueryError on failure. If errors are explicitly
	 * ignored, returns false on failure.
	 *
	 * If no result rows are returned from the query, false is returned.
	 *
	 * @param string|array $table Table name. See IDatabase::select() for details.
	 * @param string $var The field name to select. This must be a valid SQL
	 *   fragment: do not use unvalidated user input.
	 * @param string|array $cond The condition array. See IDatabase::select() for details.
	 * @param string $fname The function name of the caller.
	 * @param string|array $options The query options. See IDatabase::select() for details.
	 *
	 * @return bool|array The values from the field, or false on failure
	 * @since 1.25
	 */
	public function selectFieldValues(
		$table, $var, $cond = '', $fname = __METHOD__, $options = []
	);

	/**
	 * Execute a SELECT query constructed using the various parameters provided.
	 * See below for full details of the parameters.
	 *
	 * @param string|array $table Table name
	 * @param string|array $vars Field names
	 * @param string|array $conds Conditions
	 * @param string $fname Caller function name
	 * @param array $options Query options
	 * @param array $join_conds Join conditions
	 *
	 *
	 * @param string|array $table
	 *
	 * May be either an array of table names, or a single string holding a table
	 * name. If an array is given, table aliases can be specified, for example:
	 *
	 *    [ 'a' => 'user' ]
	 *
	 * This includes the user table in the query, with the alias "a" available
	 * for use in field names (e.g. a.user_name).
	 *
	 * All of the table names given here are automatically run through
	 * Database::tableName(), which causes the table prefix (if any) to be
	 * added, and various other table name mappings to be performed.
	 *
	 * Do not use untrusted user input as a table name. Alias names should
	 * not have characters outside of the Basic multilingual plane.
	 *
	 * @param string|array $vars
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
	 * @param string|array $options
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
	 *   - FOR UPDATE: Boolean: lock the returned rows so that they can't be
	 *     changed until the next COMMIT.
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
	 *   - EXPLAIN: In MySQL, this causes an EXPLAIN SELECT query to be run,
	 *     instead of SELECT.
	 *
	 * And also the following boolean MySQL extensions, see the MySQL manual
	 * for documentation:
	 *
	 *    - LOCK IN SHARE MODE
	 *    - STRAIGHT_JOIN
	 *    - HIGH_PRIORITY
	 *    - SQL_BIG_RESULT
	 *    - SQL_BUFFER_RESULT
	 *    - SQL_SMALL_RESULT
	 *    - SQL_CALC_FOUND_ROWS
	 *    - SQL_CACHE
	 *    - SQL_NO_CACHE
	 *
	 *
	 * @param string|array $join_conds
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
	 * @return IResultWrapper|bool If the query returned no rows, a IResultWrapper
	 *   with no rows in it will be returned. If there was a query error, a
	 *   DBQueryError exception will be thrown, except if the "ignore errors"
	 *   option was set, in which case false will be returned.
	 */
	public function select(
		$table, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	);

	/**
	 * The equivalent of IDatabase::select() except that the constructed SQL
	 * is returned, instead of being immediately executed. This can be useful for
	 * doing UNION queries, where the SQL text of each query is needed. In general,
	 * however, callers outside of Database classes should just use select().
	 *
	 * @param string|array $table Table name
	 * @param string|array $vars Field names
	 * @param string|array $conds Conditions
	 * @param string $fname Caller function name
	 * @param string|array $options Query options
	 * @param string|array $join_conds Join conditions
	 *
	 * @return string SQL query string.
	 * @see IDatabase::select()
	 */
	public function selectSQLText(
		$table, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	);

	/**
	 * Single row SELECT wrapper. Equivalent to IDatabase::select(), except
	 * that a single row object is returned. If the query returns no rows,
	 * false is returned.
	 *
	 * @param string|array $table Table name
	 * @param string|array $vars Field names
	 * @param array $conds Conditions
	 * @param string $fname Caller function name
	 * @param string|array $options Query options
	 * @param array|string $join_conds Join conditions
	 *
	 * @return stdClass|bool
	 */
	public function selectRow( $table, $vars, $conds, $fname = __METHOD__,
		$options = [], $join_conds = []
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
	 * @param string $table Table name
	 * @param string $vars Unused
	 * @param array|string $conds Filters on the table
	 * @param string $fname Function name for profiling
	 * @param array $options Options for select
	 * @return int Row count
	 */
	public function estimateRowCount(
		$table, $vars = '*', $conds = '', $fname = __METHOD__, $options = []
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
	 * @param array|string $tables Table names
	 * @param string $vars Unused
	 * @param array|string $conds Filters on the table
	 * @param string $fname Function name for profiling
	 * @param array $options Options for select
	 * @param array $join_conds Join conditions (since 1.27)
	 * @return int Row count
	 */
	public function selectRowCount(
		$tables, $vars = '*', $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	);

	/**
	 * Determines whether a field exists in a table
	 *
	 * @param string $table Table name
	 * @param string $field Filed to check on that table
	 * @param string $fname Calling function name (optional)
	 * @return bool Whether $table has filed $field
	 */
	public function fieldExists( $table, $field, $fname = __METHOD__ );

	/**
	 * Determines whether an index exists
	 * Usually throws a DBQueryError on failure
	 * If errors are explicitly ignored, returns NULL on failure
	 *
	 * @param string $table
	 * @param string $index
	 * @param string $fname
	 * @return bool|null
	 */
	public function indexExists( $table, $index, $fname = __METHOD__ );

	/**
	 * Query whether a given table exists
	 *
	 * @param string $table
	 * @param string $fname
	 * @return bool
	 */
	public function tableExists( $table, $fname = __METHOD__ );

	/**
	 * Determines if a given index is unique
	 *
	 * @param string $table
	 * @param string $index
	 *
	 * @return bool
	 */
	public function indexUnique( $table, $index );

	/**
	 * INSERT wrapper, inserts an array into a table.
	 *
	 * $a may be either:
	 *
	 *   - A single associative array. The array keys are the field names, and
	 *     the values are the values to insert. The values are treated as data
	 *     and will be quoted appropriately. If NULL is inserted, this will be
	 *     converted to a database NULL.
	 *   - An array with numeric keys, holding a list of associative arrays.
	 *     This causes a multi-row INSERT on DBMSs that support it. The keys in
	 *     each subarray must be identical to each other, and in the same order.
	 *
	 * Usually throws a DBQueryError on failure. If errors are explicitly ignored,
	 * returns success.
	 *
	 * $options is an array of options, with boolean options encoded as values
	 * with numeric keys, in the same style as $options in
	 * IDatabase::select(). Supported options are:
	 *
	 *   - IGNORE: Boolean: if present, duplicate key errors are ignored, and
	 *     any rows which cause duplicate key errors are not inserted. It's
	 *     possible to determine how many rows were successfully inserted using
	 *     IDatabase::affectedRows().
	 *
	 * @param string $table Table name. This will be passed through
	 *   Database::tableName().
	 * @param array $a Array of rows to insert
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling
	 * @param array $options Array of options
	 *
	 * @return bool
	 */
	public function insert( $table, $a, $fname = __METHOD__, $options = [] );

	/**
	 * UPDATE wrapper. Takes a condition array and a SET array.
	 *
	 * @param string $table Name of the table to UPDATE. This will be passed through
	 *   Database::tableName().
	 * @param array $values An array of values to SET. For each array element,
	 *   the key gives the field name, and the value gives the data to set
	 *   that field to. The data will be quoted by IDatabase::addQuotes().
	 *   Values with integer keys form unquoted SET statements, which can be used for
	 *   things like "field = field + 1" or similar computed values.
	 * @param array $conds An array of conditions (WHERE). See
	 *   IDatabase::select() for the details of the format of condition
	 *   arrays. Use '*' to update all rows.
	 * @param string $fname The function name of the caller (from __METHOD__),
	 *   for logging and profiling.
	 * @param array $options An array of UPDATE options, can be:
	 *   - IGNORE: Ignore unique key conflicts
	 *   - LOW_PRIORITY: MySQL-specific, see MySQL manual.
	 * @return bool
	 */
	public function update( $table, $values, $conds, $fname = __METHOD__, $options = [] );

	/**
	 * Makes an encoded list of strings from an array
	 *
	 * These can be used to make conjunctions or disjunctions on SQL condition strings
	 * derived from an array (see IDatabase::select() $conds documentation).
	 *
	 * Example usage:
	 * @code
	 *     $sql = $db->makeList( [
	 *         'rev_user' => $id,
	 *         $db->makeList( [ 'rev_minor' => 1, 'rev_len' < 500 ], $db::LIST_OR ] )
	 *     ], $db::LIST_AND );
	 * @endcode
	 * This would set $sql to "rev_user = '$id' AND (rev_minor = '1' OR rev_len < '500')"
	 *
	 * @param array $a Containing the data
	 * @param int $mode IDatabase class constant:
	 *    - IDatabase::LIST_COMMA: Comma separated, no field names
	 *    - IDatabase::LIST_AND:   ANDed WHERE clause (without the WHERE).
	 *    - IDatabase::LIST_OR:    ORed WHERE clause (without the WHERE)
	 *    - IDatabase::LIST_SET:   Comma separated with field names, like a SET clause
	 *    - IDatabase::LIST_NAMES: Comma separated field names
	 * @throws DBError
	 * @return string
	 */
	public function makeList( $a, $mode = self::LIST_COMMA );

	/**
	 * Build a partial where clause from a 2-d array such as used for LinkBatch.
	 * The keys on each level may be either integers or strings.
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
	 * @return string
	 */
	public function aggregateValue( $valuedata, $valuename = 'value' );

	/**
	 * @param string $field
	 * @return string
	 */
	public function bitNot( $field );

	/**
	 * @param string $fieldLeft
	 * @param string $fieldRight
	 * @return string
	 */
	public function bitAnd( $fieldLeft, $fieldRight );

	/**
	 * @param string $fieldLeft
	 * @param string $fieldRight
	 * @return string
	 */
	public function bitOr( $fieldLeft, $fieldRight );

	/**
	 * Build a concatenation list to feed into a SQL query
	 * @param array $stringList List of raw SQL expressions; caller is
	 *   responsible for any quoting
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
	 * @param string $field Field or column to cast
	 * @return string
	 * @since 1.28
	 */
	public function buildStringCast( $field );

	/**
	 * Change the current database
	 *
	 * @param string $db
	 * @return bool Success or failure
	 */
	public function selectDB( $db );

	/**
	 * Get the current DB name
	 * @return string
	 */
	public function getDBname();

	/**
	 * Get the server hostname or IP address
	 * @return string
	 */
	public function getServer();

	/**
	 * Adds quotes and backslashes.
	 *
	 * @param string|int|null|bool|Blob $s
	 * @return string|int
	 */
	public function addQuotes( $s );

	/**
	 * LIKE statement wrapper, receives a variable-length argument list with
	 * parts of pattern to match containing either string literals that will be
	 * escaped or tokens returned by anyChar() or anyString(). Alternatively,
	 * the function could be provided with an array of aforementioned
	 * parameters.
	 *
	 * Example: $dbr->buildLike( 'My_page_title/', $dbr->anyString() ) returns
	 * a LIKE clause that searches for subpages of 'My page title'.
	 * Alternatively:
	 *   $pattern = [ 'My_page_title/', $dbr->anyString() ];
	 *   $query .= $dbr->buildLike( $pattern );
	 *
	 * @since 1.16
	 * @return string Fully built LIKE statement
	 */
	public function buildLike();

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
	 * Returns an appropriately quoted sequence value for inserting a new row.
	 * MySQL has autoincrement fields, so this is just NULL. But the PostgreSQL
	 * subclass will return an integer, and save the value for insertId()
	 *
	 * Any implementation of this function should *not* involve reusing
	 * sequence numbers created for rolled-back transactions.
	 * See https://bugs.mysql.com/bug.php?id=30767 for details.
	 * @param string $seqName
	 * @return null|int
	 */
	public function nextSequenceValue( $seqName );

	/**
	 * REPLACE query wrapper.
	 *
	 * REPLACE is a very handy MySQL extension, which functions like an INSERT
	 * except that when there is a duplicate key error, the old row is deleted
	 * and the new row is inserted in its place.
	 *
	 * We simulate this with standard SQL with a DELETE followed by INSERT. To
	 * perform the delete, we need to know what the unique indexes are so that
	 * we know how to find the conflicting rows.
	 *
	 * It may be more efficient to leave off unique indexes which are unlikely
	 * to collide. However if you do this, you run the risk of encountering
	 * errors which wouldn't have occurred in MySQL.
	 *
	 * @param string $table The table to replace the row(s) in.
	 * @param array $uniqueIndexes Is an array of indexes. Each element may be either
	 *    a field name or an array of field names
	 * @param array $rows Can be either a single row to insert, or multiple rows,
	 *    in the same format as for IDatabase::insert()
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling
	 */
	public function replace( $table, $uniqueIndexes, $rows, $fname = __METHOD__ );

	/**
	 * INSERT ON DUPLICATE KEY UPDATE wrapper, upserts an array into a table.
	 *
	 * This updates any conflicting rows (according to the unique indexes) using
	 * the provided SET clause and inserts any remaining (non-conflicted) rows.
	 *
	 * $rows may be either:
	 *   - A single associative array. The array keys are the field names, and
	 *     the values are the values to insert. The values are treated as data
	 *     and will be quoted appropriately. If NULL is inserted, this will be
	 *     converted to a database NULL.
	 *   - An array with numeric keys, holding a list of associative arrays.
	 *     This causes a multi-row INSERT on DBMSs that support it. The keys in
	 *     each subarray must be identical to each other, and in the same order.
	 *
	 * It may be more efficient to leave off unique indexes which are unlikely
	 * to collide. However if you do this, you run the risk of encountering
	 * errors which wouldn't have occurred in MySQL.
	 *
	 * Usually throws a DBQueryError on failure. If errors are explicitly ignored,
	 * returns success.
	 *
	 * @since 1.22
	 *
	 * @param string $table Table name. This will be passed through Database::tableName().
	 * @param array $rows A single row or list of rows to insert
	 * @param array $uniqueIndexes List of single field names or field name tuples
	 * @param array $set An array of values to SET. For each array element, the
	 *   key gives the field name, and the value gives the data to set that
	 *   field to. The data will be quoted by IDatabase::addQuotes().
	 *   Values with integer keys form unquoted SET statements, which can be used for
	 *   things like "field = field + 1" or similar computed values.
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling
	 * @throws Exception
	 * @return bool
	 */
	public function upsert(
		$table, array $rows, array $uniqueIndexes, array $set, $fname = __METHOD__
	);

	/**
	 * DELETE where the condition is a join.
	 *
	 * MySQL overrides this to use a multi-table DELETE syntax, in other databases
	 * we use sub-selects
	 *
	 * For safety, an empty $conds will not delete everything. If you want to
	 * delete all rows where the join condition matches, set $conds='*'.
	 *
	 * DO NOT put the join condition in $conds.
	 *
	 * @param string $delTable The table to delete from.
	 * @param string $joinTable The other table.
	 * @param string $delVar The variable to join on, in the first table.
	 * @param string $joinVar The variable to join on, in the second table.
	 * @param array $conds Condition array of field names mapped to variables,
	 *   ANDed together in the WHERE clause
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling
	 * @throws DBUnexpectedError
	 */
	public function deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds,
		$fname = __METHOD__
	);

	/**
	 * DELETE query wrapper.
	 *
	 * @param string $table Table name
	 * @param string|array $conds Array of conditions. See $conds in IDatabase::select()
	 *   for the format. Use $conds == "*" to delete all rows
	 * @param string $fname Name of the calling function
	 * @throws DBUnexpectedError
	 * @return bool|IResultWrapper
	 */
	public function delete( $table, $conds, $fname = __METHOD__ );

	/**
	 * INSERT SELECT wrapper. Takes data from a SELECT query and inserts it
	 * into another table.
	 *
	 * @param string $destTable The table name to insert into
	 * @param string|array $srcTable May be either a table name, or an array of table names
	 *    to include in a join.
	 *
	 * @param array $varMap Must be an associative array of the form
	 *    [ 'dest1' => 'source1', ... ]. Source items may be literals
	 *    rather than field names, but strings should be quoted with
	 *    IDatabase::addQuotes()
	 *
	 * @param array $conds Condition array. See $conds in IDatabase::select() for
	 *    the details of the format of condition arrays. May be "*" to copy the
	 *    whole table.
	 *
	 * @param string $fname The function name of the caller, from __METHOD__
	 *
	 * @param array $insertOptions Options for the INSERT part of the query, see
	 *    IDatabase::insert() for details.
	 * @param array $selectOptions Options for the SELECT part of the query, see
	 *    IDatabase::select() for details.
	 *
	 * @return IResultWrapper
	 */
	public function insertSelect( $destTable, $srcTable, $varMap, $conds,
		$fname = __METHOD__,
		$insertOptions = [], $selectOptions = []
	);

	/**
	 * Returns true if current database backend supports ORDER BY or LIMIT for separate subqueries
	 * within the UNION construct.
	 * @return bool
	 */
	public function unionSupportsOrderAndLimit();

	/**
	 * Construct a UNION query
	 * This is used for providing overload point for other DB abstractions
	 * not compatible with the MySQL syntax.
	 * @param array $sqls SQL statements to combine
	 * @param bool $all Use UNION ALL
	 * @return string SQL fragment
	 */
	public function unionQueries( $sqls, $all );

	/**
	 * Returns an SQL expression for a simple conditional. This doesn't need
	 * to be overridden unless CASE isn't supported in your DBMS.
	 *
	 * @param string|array $cond SQL expression which will result in a boolean value
	 * @param string $trueVal SQL expression to return if true
	 * @param string $falseVal SQL expression to return if false
	 * @return string SQL fragment
	 */
	public function conditional( $cond, $trueVal, $falseVal );

	/**
	 * Returns a comand for str_replace function in SQL query.
	 * Uses REPLACE() in MySQL
	 *
	 * @param string $orig Column to modify
	 * @param string $old Column to seek
	 * @param string $new Column to replace with
	 *
	 * @return string
	 */
	public function strreplace( $orig, $old, $new );

	/**
	 * Determines how long the server has been up
	 *
	 * @return int
	 */
	public function getServerUptime();

	/**
	 * Determines if the last failure was due to a deadlock
	 *
	 * @return bool
	 */
	public function wasDeadlock();

	/**
	 * Determines if the last failure was due to a lock timeout
	 *
	 * @return bool
	 */
	public function wasLockTimeout();

	/**
	 * Determines if the last query error was due to a dropped connection and should
	 * be dealt with by pinging the connection and reissuing the query.
	 *
	 * @return bool
	 */
	public function wasErrorReissuable();

	/**
	 * Determines if the last failure was due to the database being read-only.
	 *
	 * @return bool
	 */
	public function wasReadOnlyError();

	/**
	 * Wait for the replica DB to catch up to a given master position
	 *
	 * @param DBMasterPos $pos
	 * @param int $timeout The maximum number of seconds to wait for synchronisation
	 * @return int|null Zero if the replica DB was past that position already,
	 *   greater than zero if we waited for some period of time, less than
	 *   zero if it timed out, and null on error
	 */
	public function masterPosWait( DBMasterPos $pos, $timeout );

	/**
	 * Get the replication position of this replica DB
	 *
	 * @return DBMasterPos|bool False if this is not a replica DB.
	 */
	public function getReplicaPos();

	/**
	 * Get the position of this master
	 *
	 * @return DBMasterPos|bool False if this is not a master
	 */
	public function getMasterPos();

	/**
	 * @return bool Whether the DB is marked as read-only server-side
	 * @since 1.28
	 */
	public function serverIsReadOnly();

	/**
	 * Run a callback as soon as the current transaction commits or rolls back.
	 * An error is thrown if no transaction is pending. Queries in the function will run in
	 * AUTO-COMMIT mode unless there are begin() calls. Callbacks must commit any transactions
	 * that they begin.
	 *
	 * This is useful for combining cooperative locks and DB transactions.
	 *
	 * The callback takes one argument:
	 *   - How the transaction ended (IDatabase::TRIGGER_COMMIT or IDatabase::TRIGGER_ROLLBACK)
	 *
	 * @param callable $callback
	 * @param string $fname Caller name
	 * @return mixed
	 * @since 1.28
	 */
	public function onTransactionResolution( callable $callback, $fname = __METHOD__ );

	/**
	 * Run a callback as soon as there is no transaction pending.
	 * If there is a transaction and it is rolled back, then the callback is cancelled.
	 * Queries in the function will run in AUTO-COMMIT mode unless there are begin() calls.
	 * Callbacks must commit any transactions that they begin.
	 *
	 * This is useful for updates to different systems or when separate transactions are needed.
	 * For example, one might want to enqueue jobs into a system outside the database, but only
	 * after the database is updated so that the jobs will see the data when they actually run.
	 * It can also be used for updates that easily cause deadlocks if locks are held too long.
	 *
	 * Updates will execute in the order they were enqueued.
	 *
	 * The callback takes one argument:
	 *   - How the transaction ended (IDatabase::TRIGGER_COMMIT or IDatabase::TRIGGER_IDLE)
	 *
	 * @param callable $callback
	 * @param string $fname Caller name
	 * @since 1.20
	 */
	public function onTransactionIdle( callable $callback, $fname = __METHOD__ );

	/**
	 * Run a callback before the current transaction commits or now if there is none.
	 * If there is a transaction and it is rolled back, then the callback is cancelled.
	 * Callbacks must not start nor commit any transactions. If no transaction is active,
	 * then a transaction will wrap the callback.
	 *
	 * This is useful for updates that easily cause deadlocks if locks are held too long
	 * but where atomicity is strongly desired for these updates and some related updates.
	 *
	 * Updates will execute in the order they were enqueued.
	 *
	 * @param callable $callback
	 * @param string $fname Caller name
	 * @since 1.22
	 */
	public function onTransactionPreCommitOrIdle( callable $callback, $fname = __METHOD__ );

	/**
	 * Run a callback each time any transaction commits or rolls back
	 *
	 * The callback takes two arguments:
	 *   - IDatabase::TRIGGER_COMMIT or IDatabase::TRIGGER_ROLLBACK
	 *   - This IDatabase object
	 * Callbacks must commit any transactions that they begin.
	 *
	 * Registering a callback here will not affect writesOrCallbacks() pending
	 *
	 * @param string $name Callback name
	 * @param callable|null $callback Use null to unset a listener
	 * @return mixed
	 * @since 1.28
	 */
	public function setTransactionListener( $name, callable $callback = null );

	/**
	 * Begin an atomic section of statements
	 *
	 * If a transaction has been started already, just keep track of the given
	 * section name to make sure the transaction is not committed pre-maturely.
	 * This function can be used in layers (with sub-sections), so use a stack
	 * to keep track of the different atomic sections. If there is no transaction,
	 * start one implicitly.
	 *
	 * The goal of this function is to create an atomic section of SQL queries
	 * without having to start a new transaction if it already exists.
	 *
	 * All atomic levels *must* be explicitly closed using IDatabase::endAtomic(),
	 * and any database transactions cannot be began or committed until all atomic
	 * levels are closed. There is no such thing as implicitly opening or closing
	 * an atomic section.
	 *
	 * @since 1.23
	 * @param string $fname
	 * @throws DBError
	 */
	public function startAtomic( $fname = __METHOD__ );

	/**
	 * Ends an atomic section of SQL statements
	 *
	 * Ends the next section of atomic SQL statements and commits the transaction
	 * if necessary.
	 *
	 * @since 1.23
	 * @see IDatabase::startAtomic
	 * @param string $fname
	 * @throws DBError
	 */
	public function endAtomic( $fname = __METHOD__ );

	/**
	 * Run a callback to do an atomic set of updates for this database
	 *
	 * The $callback takes the following arguments:
	 *   - This database object
	 *   - The value of $fname
	 *
	 * If any exception occurs in the callback, then rollback() will be called and the error will
	 * be re-thrown. It may also be that the rollback itself fails with an exception before then.
	 * In any case, such errors are expected to terminate the request, without any outside caller
	 * attempting to catch errors and commit anyway. Note that any rollback undoes all prior
	 * atomic section and uncommitted updates, which trashes the current request, requiring an
	 * error to be displayed.
	 *
	 * This can be an alternative to explicit startAtomic()/endAtomic() calls.
	 *
	 * @see Database::startAtomic
	 * @see Database::endAtomic
	 *
	 * @param string $fname Caller name (usually __METHOD__)
	 * @param callable $callback Callback that issues DB updates
	 * @return mixed $res Result of the callback (since 1.28)
	 * @throws DBError
	 * @throws RuntimeException
	 * @throws UnexpectedValueException
	 * @since 1.27
	 */
	public function doAtomicSection( $fname, callable $callback );

	/**
	 * Begin a transaction. If a transaction is already in progress,
	 * that transaction will be committed before the new transaction is started.
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
	 * @throws DBError
	 */
	public function begin( $fname = __METHOD__, $mode = self::TRANSACTION_EXPLICIT );

	/**
	 * Commits a transaction previously started using begin().
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
	 *
	 *   This will trigger an exception if there is an ongoing explicit transaction.
	 *
	 *   Only set the flush flag if you are sure that these warnings are not applicable,
	 *   and no explicit transactions are open.
	 *
	 * @throws DBUnexpectedError
	 */
	public function commit( $fname = __METHOD__, $flush = '' );

	/**
	 * Rollback a transaction previously started using begin().
	 * If no transaction is in progress, a warning is issued.
	 *
	 * Only call this from code with outer transcation scope.
	 * See https://www.mediawiki.org/wiki/Database_transactions for details.
	 * Nesting of transactions is not supported. If a serious unexpected error occurs,
	 * throwing an Exception is preferrable, using a pre-installed error handler to trigger
	 * rollback (in any case, failure to issue COMMIT will cause rollback server-side).
	 *
	 * @param string $fname Calling function name
	 * @param string $flush Flush flag, set to a situationally valid IDatabase::FLUSHING_*
	 *   constant to disable warnings about calling rollback when no transaction is in
	 *   progress. This will silently break any ongoing explicit transaction. Only set the
	 *   flush flag if you are sure that it is safe to ignore these warnings in your context.
	 * @throws DBUnexpectedError
	 * @since 1.23 Added $flush parameter
	 */
	public function rollback( $fname = __METHOD__, $flush = '' );

	/**
	 * Commit any transaction but error out if writes or callbacks are pending
	 *
	 * This is intended for clearing out REPEATABLE-READ snapshots so that callers can
	 * see a new point-in-time of the database. This is useful when one of many transaction
	 * rounds finished and significant time will pass in the script's lifetime. It is also
	 * useful to call on a replica DB after waiting on replication to catch up to the master.
	 *
	 * @param string $fname Calling function name
	 * @throws DBUnexpectedError
	 * @since 1.28
	 */
	public function flushSnapshot( $fname = __METHOD__ );

	/**
	 * List all tables on the database
	 *
	 * @param string $prefix Only show tables with this prefix, e.g. mw_
	 * @param string $fname Calling function name
	 * @throws DBError
	 * @return array
	 */
	public function listTables( $prefix = null, $fname = __METHOD__ );

	/**
	 * Convert a timestamp in one of the formats accepted by wfTimestamp()
	 * to the format used for inserting into timestamp fields in this DBMS.
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
	 * Convert a timestamp in one of the formats accepted by wfTimestamp()
	 * to the format used for inserting into timestamp fields in this DBMS. If
	 * NULL is input, it is passed through, allowing NULL values to be inserted
	 * into timestamp fields.
	 *
	 * The result is unquoted, and needs to be passed through addQuotes()
	 * before it can be included in raw SQL.
	 *
	 * @param string|int $ts
	 *
	 * @return string
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
	 * Get replica DB lag. Currently supported only by MySQL.
	 *
	 * Note that this function will generate a fatal error on many
	 * installations. Most callers should use LoadBalancer::safeGetLag()
	 * instead.
	 *
	 * @return int|bool Database replication lag in seconds or false on error
	 */
	public function getLag();

	/**
	 * Get the replica DB lag when the current transaction started
	 * or a general lag estimate if not transaction is active
	 *
	 * This is useful when transactions might use snapshot isolation
	 * (e.g. REPEATABLE-READ in innodb), so the "real" lag of that data
	 * is this lag plus transaction duration. If they don't, it is still
	 * safe to be pessimistic. In AUTO-COMMIT mode, this still gives an
	 * indication of the staleness of subsequent reads.
	 *
	 * @return array ('lag': seconds or false on error, 'since': UNIX timestamp of BEGIN)
	 * @since 1.27
	 */
	public function getSessionLagStatus();

	/**
	 * Return the maximum number of items allowed in a list, or 0 for unlimited.
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
	 */
	public function encodeBlob( $b );

	/**
	 * Some DBMSs return a special placeholder object representing blob fields
	 * in result objects. Pass the object through this function to return the
	 * original string.
	 *
	 * @param string|Blob $b
	 * @return string
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
	 */
	public function setSessionOptions( array $options );

	/**
	 * Set variables to be used in sourceFile/sourceStream, in preference to the
	 * ones in $GLOBALS. If an array is set here, $GLOBALS will not be used at
	 * all. If it's set to false, $GLOBALS will be used.
	 *
	 * @param bool|array $vars Mapping variable name to value.
	 */
	public function setSchemaVars( $vars );

	/**
	 * Check to see if a named lock is available (non-blocking)
	 *
	 * @param string $lockName Name of lock to poll
	 * @param string $method Name of method calling us
	 * @return bool
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
	 * @param int $timeout Acquisition timeout in seconds
	 * @return bool
	 */
	public function lock( $lockName, $method, $timeout = 5 );

	/**
	 * Release a lock
	 *
	 * Named locks are not related to transactions
	 *
	 * @param string $lockName Name of lock to release
	 * @param string $method Name of the calling method
	 *
	 * @return int Returns 1 if the lock was released, 0 if the lock was not established
	 * by this thread (in which case the lock is not released), and NULL if the named
	 * lock did not exist
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
	 * @throws DBUnexpectedError
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
}

class_alias( IDatabase::class, 'IDatabase' );
