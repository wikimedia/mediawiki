<?php

namespace MediaWiki\Maintenance;

use Exception;

/**
 * Exception thrown by Maintenance::fatalError if called during PHPUnit tests. This is done because
 * calling exit() would cause the test suite to stop early.
 *
 * @since 1.43
 */
class MaintenanceFatalError extends Exception {
	/**
	 * @param int $code The error code that would have been passed to exit() if the method was not
	 *   called during a PHPUnit test.
	 */
	public function __construct( $code ) {
		parent::__construct( "", $code );
	}
}
