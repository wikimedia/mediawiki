<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests;

/**
 * Terminal that supports ANSI escape sequences.
 *
 * @ingroup Testing
 */
class AnsiTermColorer {
	/**
	 * Return ANSI terminal escape code for changing text attribs/color
	 *
	 * @param string|int $color Semicolon-separated list of attribute/color codes, e.g. 4 for
	 *  underline, 30 to 37 for 3-bit foreground colors
	 * @return string
	 */
	public function color( string|int $color ): string {
		global $wgCommandLineDarkBg;

		// 1 for increased intensity, 0 to reset all previous attributes
		return "\x1b[" . ( $wgCommandLineDarkBg ? '1' : '0' ) . ";{$color}m";
	}

	/**
	 * Return ANSI terminal escape code for restoring default text attributes
	 *
	 * @return string
	 */
	public function reset(): string {
		return "\x1b[0m";
	}
}
