<?php

/**
 * Shared utilities for the Null* HTTP mock classes used in tests.
 *
 * @license GPL-2.0-or-later
 */
class NullHttpUtil {

	/**
	 * Format a truncated stack trace for inclusion in error messages.
	 *
	 * This helps developers identify WHERE an unexpected HTTP request
	 * originated, rather than just showing the Null* class assertion.
	 *
	 * @param int $maxFrames Maximum number of stack frames to include
	 * @return string Formatted stack trace
	 */
	public static function getFormattedTrace( int $maxFrames = 10 ): string {
		$trace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, $maxFrames + 2 );
		// Skip the first two frames (getFormattedTrace + the Null* class method)
		$trace = array_slice( $trace, 2 );

		$lines = [ 'Stack trace (to identify the source):' ];
		foreach ( $trace as $i => $frame ) {
			$file = $frame['file'] ?? '[internal]';
			$line = $frame['line'] ?? '?';
			$class = $frame['class'] ?? '';
			$type = $frame['type'] ?? '';
			$function = $frame['function'] ?? '';
			$call = $class . $type . $function . '()';
			$lines[] = "#$i $call at $file:$line";
		}

		return implode( "\n", $lines );
	}
}
