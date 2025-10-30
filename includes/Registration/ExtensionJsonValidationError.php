<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Registration;

use Exception;

/**
 * @newable
 * @ingroup ExtensionRegistry
 */
class ExtensionJsonValidationError extends Exception {
}

/** @deprecated class alias since 1.43 */
class_alias( ExtensionJsonValidationError::class, 'ExtensionJsonValidationError' );
