<?php
/**
 * @license GPL-2.0-or-later
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
		?callable $lineCallback = null,
		?callable $resultCallback = null,
		$fname = false,
		?callable $inputCallback = null
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
	 * @param string $fname Calling function name @phan-mandatory-param
	 * @param callable|null $inputCallback Optional function called for each complete query sent
	 * @return bool|string
	 */
	public function sourceStream(
		$fp,
		?callable $lineCallback = null,
		?callable $resultCallback = null,
		$fname = __METHOD__,
		?callable $inputCallback = null
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
	 * @param string $table The unqualified name of a table
	 * @param string $fname @phan-mandatory-param
	 * @return bool Whether the table already existed
	 * @throws DBError If an error occurs
	 */
	public function dropTable( $table, $fname = __METHOD__ );

	/**
	 * Delete all data in a table and reset any sequences owned by that table
	 *
	 * @param string $table The unqualified name of a table
	 * @param string $fname @phan-mandatory-param
	 * @throws DBError If an error occurs
	 * @since 1.42
	 */
	public function truncateTable( $table, $fname = __METHOD__ );

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
	 * @param string $fname Calling function name @phan-mandatory-param
	 * @return bool True if operation was successful
	 * @throws RuntimeException
	 */
	public function duplicateTableStructure(
		$oldName, $newName, $temporary = false, $fname = __METHOD__
	);

	/**
	 * List all tables on the database.
	 *
	 * Since MW 1.42, this will no longer include MySQL views.
	 *
	 * @param string|null $prefix Only show tables with this prefix, e.g. mw_
	 * @param string $fname Calling function name @phan-mandatory-param
	 * @throws DBError
	 * @return array
	 */
	public function listTables( $prefix = null, $fname = __METHOD__ );

	/**
	 * Get information about a field
	 * Returns false if the field doesn't exist
	 *
	 * @param string $table The unqualified name of a table
	 * @param string $field Field name
	 *
	 * @return false|Field
	 */
	public function fieldInfo( $table, $field );

	/**
	 * Determines whether a field exists in a table
	 *
	 * @param string $table The unqualified name of a table
	 * @param string $field Field to check on that table
	 * @param string $fname Calling function name @phan-mandatory-param
	 * @return bool Whether $table has field $field
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function fieldExists( $table, $field, $fname = __METHOD__ );

	/**
	 * Determines whether an index exists
	 *
	 * @param string $table The unqualified name of a table
	 * @param string $index
	 * @param string $fname @phan-mandatory-param
	 * @return bool
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function indexExists( $table, $index, $fname = __METHOD__ );

	/**
	 * Determines if a given index is unique
	 *
	 * @param string $table The unqualified name of a table
	 * @param string $index
	 * @param string $fname Calling function name @phan-mandatory-param
	 * @return bool|null Returns null if the index does not exist
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function indexUnique( $table, $index, $fname = __METHOD__ );

	/**
	 * Query whether a given table exists
	 *
	 * @param string $table The unqualified name of a table
	 * @param string $fname @phan-mandatory-param
	 * @return bool
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function tableExists( $table, $fname = __METHOD__ );

	/**
	 * Get the primary key columns of a table
	 *
	 * @internal to be used by updater only
	 *
	 * @param string $table The unqualified name of a table
	 * @param string $fname Calling function name @phan-mandatory-param
	 * @return string[]
	 * @throws DBError If an error occurs, {@see query}
	 */
	public function getPrimaryKeyColumns( $table, $fname = __METHOD__ );
}
