<?php

/**
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
 */
namespace Wikimedia\Rdbms;

use Exception;
use RuntimeException;

/**
 * Advanced database interface for IDatabase handles that include maintenance methods
 *
 * This is useful for type-hints used by installer, upgrader, and background scripts
 * that will make use of lower-level and longer-running queries, including schema changes.
 *
 * @ingroup Database
 * @since 1.28
 */
interface IMaintainableDatabase extends IDatabase {
	/**
	 * Format a table name ready for use in constructing an SQL query
	 *
	 * This does two important things: it quotes the table names to clean them up,
	 * and it adds a table prefix if only given a table name with no quotes.
	 *
	 * All functions of this object which require a table name call this function
	 * themselves. Pass the canonical name to such functions. This is only needed
	 * when calling query() directly.
	 *
	 * @note This function does not sanitize user input. It is not safe to use
	 *   this function to escape user input.
	 * @param string $name Database table name
	 * @param string $format One of:
	 *   quoted - Automatically pass the table name through addIdentifierQuotes()
	 *            so that it can be used in a query.
	 *   raw - Do not add identifier quotes to the table name
	 * @return string Full database name
	 */
	public function tableName( $name, $format = 'quoted' );

	/**
	 * Fetch a number of table names into an array
	 * This is handy when you need to construct SQL for joins
	 *
	 * Example:
	 * list( $user, $watchlist ) = $dbr->tableNames( 'user', 'watchlist' ) );
	 * $sql = "SELECT wl_namespace, wl_title FROM $watchlist, $user
	 *         WHERE wl_user=user_id AND wl_user=$nameWithQuotes";
	 *
	 * @return array
	 */
	public function tableNames();

	/**
	 * Fetch a number of table names into an zero-indexed numerical array
	 * This is handy when you need to construct SQL for joins
	 *
	 * Example:
	 * list( $user, $watchlist ) = $dbr->tableNamesN( 'user', 'watchlist' );
	 * $sql = "SELECT wl_namespace,wl_title FROM $watchlist,$user
	 *         WHERE wl_user=user_id AND wl_user=$nameWithQuotes";
	 *
	 * @return array
	 */
	public function tableNamesN();

	/**
	 * Returns the size of a text field, or -1 for "unlimited"
	 *
	 * @param string $table
	 * @param string $field
	 * @return int
	 */
	public function textFieldSize( $table, $field );

	/**
	 * Read and execute SQL commands from a file.
	 *
	 * Returns true on success, error string or exception on failure (depending
	 * on object's error ignore settings).
	 *
	 * @param string $filename File name to open
	 * @param callable|null $lineCallback Optional function called before reading each line
	 * @param callable|null $resultCallback Optional function called for each MySQL result
	 * @param bool|string $fname Calling function name or false if name should be
	 *   generated dynamically using $filename
	 * @param callable|null $inputCallback Optional function called for each
	 *   complete line sent
	 * @return bool|string
	 * @throws Exception
	 */
	public function sourceFile(
		$filename,
		callable $lineCallback = null,
		callable $resultCallback = null,
		$fname = false,
		callable $inputCallback = null
	);

	/**
	 * Read and execute commands from an open file handle.
	 *
	 * Returns true on success, error string or exception on failure (depending
	 * on object's error ignore settings).
	 *
	 * @param resource $fp File handle
	 * @param callable|null $lineCallback Optional function called before reading each query
	 * @param callable|null $resultCallback Optional function called for each MySQL result
	 * @param string $fname Calling function name
	 * @param callable|null $inputCallback Optional function called for each complete query sent
	 * @return bool|string
	 */
	public function sourceStream(
		$fp,
		callable $lineCallback = null,
		callable $resultCallback = null,
		$fname = __METHOD__,
		callable $inputCallback = null
	);

	/**
	 * Called by sourceStream() to check if we've reached a statement end
	 *
	 * @param string &$sql SQL assembled so far
	 * @param string &$newLine New line about to be added to $sql
	 * @return bool Whether $newLine contains end of the statement
	 */
	public function streamStatementEnd( &$sql, &$newLine );

	/**
	 * Delete a table
	 * @param string $tableName
	 * @param string $fName
	 * @return bool|ResultWrapper
	 */
	public function dropTable( $tableName, $fName = __METHOD__ );

	/**
	 * Perform a deadlock-prone transaction.
	 *
	 * This function invokes a callback function to perform a set of write
	 * queries. If a deadlock occurs during the processing, the transaction
	 * will be rolled back and the callback function will be called again.
	 *
	 * Avoid using this method outside of Job or Maintenance classes.
	 *
	 * Usage:
	 *   $dbw->deadlockLoop( callback, ... );
	 *
	 * Extra arguments are passed through to the specified callback function.
	 * This method requires that no transactions are already active to avoid
	 * causing premature commits or exceptions.
	 *
	 * Returns whatever the callback function returned on its successful,
	 * iteration, or false on error, for example if the retry limit was
	 * reached.
	 *
	 * @return mixed
	 * @throws DBUnexpectedError
	 * @throws Exception
	 */
	public function deadlockLoop();

	/**
	 * Lists all the VIEWs in the database
	 *
	 * @param string $prefix Only show VIEWs with this prefix, eg. unit_test_
	 * @param string $fname Name of calling function
	 * @throws RuntimeException
	 * @return array
	 */
	public function listViews( $prefix = null, $fname = __METHOD__ );

	/**
	 * Creates a new table with structure copied from existing table
	 *
	 * Note that unlike most database abstraction functions, this function does not
	 * automatically append database prefix, because it works at a lower abstraction level.
	 * The table names passed to this function shall not be quoted (this function calls
	 * addIdentifierQuotes() when needed).
	 *
	 * @param string $oldName Name of table whose structure should be copied
	 * @param string $newName Name of table to be created
	 * @param bool $temporary Whether the new table should be temporary
	 * @param string $fname Calling function name
	 * @return bool True if operation was successful
	 * @throws RuntimeException
	 */
	public function duplicateTableStructure(
		$oldName, $newName, $temporary = false, $fname = __METHOD__
	);

	/**
	 * Checks if table locks acquired by lockTables() are transaction-bound in their scope
	 *
	 * Transaction-bound table locks will be released when the current transaction terminates.
	 * Table locks that are not bound to a transaction are not effected by BEGIN/COMMIT/ROLLBACK
	 * and will last until either lockTables()/unlockTables() is called or the TCP connection to
	 * the database is closed.
	 *
	 * @return bool
	 * @since 1.29
	 */
	public function tableLocksHaveTransactionScope();

	/**
	 * Lock specific tables
	 *
	 * Any pending transaction should be resolved before calling this method, since:
	 *   a) Doing so resets any REPEATABLE-READ snapshot of the data to a fresh one.
	 *   b) Previous row and table locks from the transaction or session may be released
	 *      by LOCK TABLES, which may be unsafe for the changes in such a transaction.
	 *   c) The main use case of lockTables() is to avoid deadlocks and timeouts by locking
	 *      entire tables in order to do long-running, batched, and lag-aware, updates. Batching
	 *      and replication lag checks do not work when all the updates happen in a transaction.
	 *
	 * Always get all relevant table locks up-front in one call, since LOCK TABLES might release
	 * any prior table locks on some RDBMes (e.g MySQL).
	 *
	 * For compatibility, callers should check tableLocksHaveTransactionScope() before using
	 * this method. If locks are scoped specifically to transactions then caller must either:
	 *   - a) Start a new transaction and acquire table locks for the scope of that transaction,
	 *        doing all row updates within that transaction. It will not be possible to update
	 *        rows in batches; this might result in high replication lag.
	 *   - b) Forgo table locks entirely and avoid calling this method. Careful use of hints like
	 *        LOCK IN SHARE MODE and FOR UPDATE and the use of query batching may be preferrable
	 *        to using table locks with a potentially large transaction. Use of MySQL and Postges
	 *        style REPEATABLE-READ (Snapshot Isolation with or without First-Committer-Rule) can
	 *        also be considered for certain tasks that require a consistent view of entire tables.
	 *
	 * If session scoped locks are not supported, then calling lockTables() will trigger
	 * startAtomic(), with unlockTables() triggering endAtomic(). This will automatically
	 * start a transaction if one is not already present and cause the locks to be released
	 * when the transaction finishes (normally during the unlockTables() call).
	 *
	 * In any case, avoid using begin()/commit() in code that runs while such table locks are
	 * acquired, as that breaks in case when a transaction is needed. The startAtomic() and
	 * endAtomic() methods are safe, however, since they will join any existing transaction.
	 *
	 * @param array $read Array of tables to lock for read access
	 * @param array $write Array of tables to lock for write access
	 * @param string $method Name of caller
	 * @return bool
	 * @since 1.29
	 */
	public function lockTables( array $read, array $write, $method );

	/**
	 * Unlock all tables locked via lockTables()
	 *
	 * If table locks are scoped to transactions, then locks might not be released until the
	 * transaction ends, which could happen after this method is called.
	 *
	 * @param string $method The caller
	 * @return bool
	 * @since 1.29
	 */
	public function unlockTables( $method );

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
	 * Determines if a given index is unique
	 *
	 * @param string $table
	 * @param string $index
	 *
	 * @return bool
	 */
	public function indexUnique( $table, $index );

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
}

class_alias( IMaintainableDatabase::class, 'IMaintainableDatabase' );
