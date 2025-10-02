<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats\Metrics;

/**
 * Null Metric Implementation
 *
 * When a request from cache yields a type other than what was requested
 * or an unrecoverable situation has occurred, an instance of this class
 * should be passed to the caller to provide an interface that suppresses
 * method calls against it.
 *
 * @author Cole White
 * @since 1.38
 */
class NullMetric {

	/**
	 * Silently suppress all undefined method calls.
	 *
	 * @param $method_name string
	 * @param $args array
	 * @return NullMetric
	 */
	public function __call( string $method_name, array $args ): NullMetric {
		return $this;
	}

}
