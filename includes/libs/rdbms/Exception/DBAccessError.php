<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

/**
 * Exception class for attempted DB access
 *
 * @newable
 * @ingroup Database
 */
class DBAccessError extends DBUnexpectedError {
	/**
	 * @stable to call
	 */
	public function __construct() {
		parent::__construct( null, "Database access has been disabled." );
	}
}
