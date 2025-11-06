<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

/**
 * @newable
 * @ingroup Database
 * @since 1.34
 */
class DBQueryDisconnectedError extends DBQueryError {

	/**
	 * @stable to call
	 * @param IDatabase $db
	 * @param string $error
	 * @param int|string $errno
	 * @param string $sql
	 * @param string $fname
	 * @param string|null $message Optional message, intended for subclasses (optional)
	 */
	public function __construct( IDatabase $db, $error, $errno, $sql, $fname, $message = null ) {
		$message ??= "A connection error occurred during a query. \n" .
			"Query: $sql\n" .
			"Function: $fname\n" .
			"Error: $errno $error\n";

		parent::__construct( $db, $error, $errno, $sql, $fname, $message );
	}
}
