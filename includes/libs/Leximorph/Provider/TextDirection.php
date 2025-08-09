<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace Wikimedia\Leximorph\Provider;

use IntlChar;

/**
 * TextDirection
 *
 * Determines whether text is primarily left-to-right or right-to-left.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class TextDirection {

	/**
	 * Array of Unicode BIDI control codepoints used for directional formatting.
	 *
	 * Note: LRM (0x200E) and RLM (0x200F) are not included as they are standalone marks.
	 *
	 * @var int[]
	 */
	private const BIDI_CONTROL_CODES = [
		// LEFT-TO-RIGHT EMBEDDING (LRE)
		0x202A,
		// RIGHT-TO-LEFT EMBEDDING (RLE)
		0x202B,
		// POP DIRECTIONAL FORMATTING (PDF)
		0x202C,
		// LEFT-TO-RIGHT OVERRIDE (LRO)
		0x202D,
		// RIGHT-TO-LEFT OVERRIDE (RLO)
		0x202E,
		// LEFT-TO-RIGHT ISOLATE (LRI)
		0x2066,
		// RIGHT-TO-LEFT ISOLATE (RLI)
		0x2067,
		// FIRST-STRONG ISOLATE (FSI)
		0x2068,
		// POP DIRECTIONAL ISOLATE (PDI)
		0x2069,
	];

	/**
	 * Determines the first strong directional Unicode codepoint.
	 *
	 * Iterates over Unicode characters using IntlChar. Skips BIDI control and
	 * unallocated characters (using {@see IntlChar::isdefined}). Returns 'ltr' if a
	 * left-to-right char is found, 'rtl' for right-to-left (incl. Arabic), or
	 * null if none is found.
	 *
	 * @param string $text Text to test.
	 *
	 * @since 1.45
	 * @return ?string 'ltr', 'rtl', or null.
	 */
	public function getDirection( string $text ): ?string {
		$length = mb_strlen( $text, 'UTF-8' );
		for ( $i = 0; $i < $length; $i++ ) {
			$char = mb_substr( $text, $i, 1, 'UTF-8' );
			$code = IntlChar::ord( $char );

			// Skip BIDI control characters.
			if ( in_array( $code, self::BIDI_CONTROL_CODES, true ) ) {
				continue;
			}

			// Skip unallocated characters.
			if ( !IntlChar::isdefined( $code ) ) {
				continue;
			}

			$dir = IntlChar::charDirection( $code );
			if ( $dir === IntlChar::CHAR_DIRECTION_LEFT_TO_RIGHT ) {
				return 'ltr';
			}
			if (
				$dir === IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT ||
				$dir === IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT_ARABIC
			) {
				return 'rtl';
			}
		}

		return null;
	}
}
