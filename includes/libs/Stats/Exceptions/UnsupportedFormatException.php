<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats\Exceptions;

use InvalidArgumentException;

/**
 * UnsupportedFormatException
 *
 * Raised when a metrics format is defined in the LocalSettings
 * configuration, but is not supported.
 *
 * See: OutputFormats::SUPPORTED_FORMATS and $wgStatsFormat
 *
 * @author Cole White
 * @since 1.41
 */
class UnsupportedFormatException extends InvalidArgumentException {
}
