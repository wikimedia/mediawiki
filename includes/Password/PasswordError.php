<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types = 1 );

namespace MediaWiki\Password;

use RuntimeException;

/**
 * Show an error when any operation involving passwords fails to run.
 *
 * @newable
 * @ingroup Exception
 */
class PasswordError extends RuntimeException {
}

/** @deprecated since 1.43 use MediaWiki\\Password\\PasswordError */
class_alias( PasswordError::class, 'PasswordError' );
