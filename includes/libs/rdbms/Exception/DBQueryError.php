<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

/**
 * @ingroup Database
 * @newable
 */
class DBQueryError extends DBExpectedError {
	/** @var string */
	public $error;
	/** @var int */
	public $errno;
	/** @var string */
	public $sql;
	/** @var string */
	public $fname;

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
		$message ??= "Error $errno: $error\n" .
			"Function: $fname\n" .
			"Query: $sql\n";

		parent::__construct( $db, $message );

		$this->error = $error;
		$this->errno = $errno;
		$this->sql = $sql;
		$this->fname = $fname;
	}
}
