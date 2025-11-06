<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

/**
 * @newable
 * @ingroup Database
 */
class DBConnectionError extends DBExpectedError {
	/**
	 * @stable to call
	 * @param IDatabase|null $db Object throwing the error
	 * @param string $error Error text
	 */
	public function __construct( ?IDatabase $db = null, $error = 'unknown error' ) {
		$msg = 'Cannot access the database';
		if ( trim( $error ) != '' ) {
			$msg .= ": $error";
		}

		parent::__construct( $db, $msg );
	}
}
