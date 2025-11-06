<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

use RuntimeException;

/**
 * Database error base class.
 *
 * Catching and silencing this class or its subclasses is strongly discouraged.
 * Most code should not catch DB errors at all,
 * but let them bubble to the MediaWiki exception handler.
 * If necessary, cleanup can be done in a finally block;
 * catching the exception and then rethrowing it is also acceptable.
 *
 * @newable
 * @ingroup Database
 */
class DBError extends RuntimeException {
	/** @var IDatabase|null */
	public $db;

	/**
	 * Construct a database error
	 * @stable to call
	 * @param IDatabase|null $db Object which threw the error
	 * @param string $error A simple error message to be used for debugging
	 * @param \Throwable|null $prev Previous throwable
	 */
	public function __construct( ?IDatabase $db, $error, ?\Throwable $prev = null ) {
		parent::__construct( $error, 0, $prev );
		$this->db = $db;
	}
}
