<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats\Exceptions;

use InvalidArgumentException;

/**
 * InvalidConfigurationException
 *
 * This exception is raised when StatsFactory or Metric gets an associative
 * array as configuration that it cannot use to properly instantiate a Metric.
 *
 * @author Cole White
 * @since 1.41
 */
class InvalidConfigurationException extends InvalidArgumentException {
}
