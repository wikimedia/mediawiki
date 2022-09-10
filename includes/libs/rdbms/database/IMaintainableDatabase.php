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
	 * @param string|false $fname Calling function name or false if name should be
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
	 *
	 * @param string $table
	 * @param string $fname
	 * @return bool Whether the table already existed
	 * @throws DBError If an error occurs
	 */
	public function dropTable( $table, $fname = __METHOD__ );

	/**
	 * Delete all data in a table(s) and reset any sequences owned by that table(s)
	 *
	 * @param string|string[] $tables
	 * @param string $fname
	 * @throws DBError If an error occurs
	 * @since 1.35
	 */
	public function truncate( $tables, $fname = __METHOD__ );

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
	 * @param mixed ...$args
	 * @return mixed
	 * @throws DBUnexpectedError
	 * @throws Exception
	 */
	public function deadlockLoop( ...$args );

	/**
	 * Lists all the VIEWs in the database
	 *
	 * @param string|null $prefix Only show VIEWs with this prefix, eg. unit_test_
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
	 * List all tables on the database
	 *
	 * @param string|null $prefix Only show tables with this prefix, e.g. mw_
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
	 * @param string $fname Calling function name
	 *
	 * @return bool
	 */
	public function indexUnique( $table, $index, $fname = __METHOD__ );

	/**
	 * Get information about a field
	 * Returns false if the field doesn't exist
	 *
	 * @param string $table Table name
	 * @param string $field Field name
	 *
	 * @return false|Field
	 */
	public function fieldInfo( $table, $field );

	/**
	 * Determines whether a field exists in a table
	 *
	 * @param string $table Table name
	 * @param string $field Field to check on that table
	 * @param string $fname Calling function name (optional)
	 * @return bool Whether $table has field $field
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function fieldExists( $table, $field, $fname = __METHOD__ );

	/**
	 * Determines whether an index exists
	 *
	 * @param string $table
	 * @param string $index
	 * @param string $fname
	 * @return bool|null
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function indexExists( $table, $index, $fname = __METHOD__ );

	/**
	 * Query whether a given table exists
	 *
	 * @param string $table
	 * @param string $fname
	 * @return bool
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function tableExists( $table, $fname = __METHOD__ );
}

class_alias( IMaintainableDatabase::class, 'IMaintainableDatabase' );
