<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

/**
 * Error thrown when a query times out
 *
 * @newable
 * @ingroup Database
 */
class DBQueryTimeoutError extends DBQueryError {

	/**
	 * @stable to call
	 *
	 * @param IDatabase $db
	 * @param string $error
	 * @param int|string $errno
	 * @param string $sql
	 * @param string $fname
	 */
	public function __construct( IDatabase $db, $error, $errno, $sql, $fname ) {
		$message = "A database query timeout has occurred. \n" .
			"Query: $sql\n" .
			"Function: $fname\n" .
			"Error: $errno $error\n";

		parent::__construct( $db, $error, $errno, $sql, $fname, $message );
	}

	public function getKey(): string {
		return 'transaction-max-statement-time-exceeded';
	}

}
