<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

use Throwable;

/**
 * @since 1.39
 * @newable
 * @ingroup Database
 */
class DBLanguageError extends DBUnexpectedError {
	public function __construct( string $error, ?Throwable $prev = null ) {
		parent::__construct( null, $error, $prev );
	}
}
