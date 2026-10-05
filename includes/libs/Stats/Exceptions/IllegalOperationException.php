<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats\Exceptions;

use RuntimeException;

/**
 * Thrown when an unresolvable configuration condition has been requested.
 *
 * @author Cole White
 * @since 1.41
 */
class IllegalOperationException extends RuntimeException {
}
