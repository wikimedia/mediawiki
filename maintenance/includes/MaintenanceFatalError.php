<?php

namespace MediaWiki\Maintenance;

use Exception;

/**
 * Exception thrown by Maintenance::fatalError if called during PHPUnit tests. This is done because
 * calling exit() would cause the test suite to stop early.
 * Use MaintenanceBaseTestCase::expectCallToFatalError() in test cases.
 *
 * @internal Do not mention in throws annotations, only for use in MaintenanceBaseTestCase
 */
class MaintenanceFatalError extends Exception {
	/**
	 * @param string $msg a string to record in the logs about what went wrong
	 * @param int $code The error code that would have been passed to exit() if the method was not
	 *   called during a PHPUnit test.
	 */
	public function __construct( string $msg, int $code ) {
		parent::__construct( $msg, $code );
	}
}
