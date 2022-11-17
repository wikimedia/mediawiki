<?php

namespace Wikimedia\Rdbms;

use Countable;
use OutOfBoundsException;
use SeekableIterator;
use stdClass;

/**
 * Result wrapper for grabbing data queried from an IDatabase object
 *
 * Note that using the Iterator methods in combination with the non-Iterator
 * DB result iteration functions may cause rows to be skipped or repeated.
 *
 * By default, this will use the iteration methods of the IDatabase handle if provided.
 * Subclasses can override methods to make it solely work on the result resource instead.
 * If no database is provided, and the subclass does not override the DB iteration methods,
 * then a RuntimeException will be thrown when iteration is attempted.
 *
 * The result resource field should not be accessed from non-Database related classes.
 * It is database class specific and is stored here to associate iterators with queries.
 *
 * @ingroup Database
 */
interface IResultWrapper extends Countable, SeekableIterator {
	/**
	 * Get the number of rows in a result object
	 *
	 * @return int
	 */
	public function numRows();

	/**
	 * Get the number of rows in a result object
	 *
	 * @return int
	 */
	public function count(): int;

	/**
	 * Fetch the next row from the given result object, in object form. Fields can be retrieved with
	 * $row->fieldname, with fields acting like member variables. If no more rows are available,
	 * false is returned.
	 *
	 * @return stdClass|false
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	public function fetchObject();

	/**
	 * Fetch the next row from the given result object, in associative array form. Fields are
	 * retrieved with $row['fieldname']. If no more rows are available, false is returned.
	 *
	 * @return array|false
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	public function fetchRow();

	/**
	 * Change the position of the cursor in a result object.
	 * See mysql_data_seek()
	 *
	 * @throws OutOfBoundsException
	 * @param int $pos
	 */
	public function seek( $pos ): void;

	/**
	 * Free a result object
	 *
	 * This either saves memory in PHP (buffered queries) or on the server (unbuffered queries).
	 * In general, queries are not large enough in result sets for this to be worth calling.
	 */
	public function free();

	/**
	 * @return stdClass|array|false
	 */
	#[\ReturnTypeWillChange]
	public function current();

	/**
	 * @return int
	 */
	public function key(): int;

	/**
	 * @return void
	 */
	public function next(): void;

	/**
	 * Get the names of the fields in the result
	 *
	 * @since 1.37
	 * @return string[]
	 */
	public function getFieldNames();
}
