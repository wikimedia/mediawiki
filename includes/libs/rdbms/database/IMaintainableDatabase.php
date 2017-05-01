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
 * @ingroup Database
 */

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
	 * extract( $dbr->tableNames( 'user', 'watchlist' ) );
	 * $sql = "SELECT wl_namespace,wl_title FROM $watchlist,$user
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
}
