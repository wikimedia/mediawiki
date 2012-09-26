<?php
/**
 * Fallback functions for PHP installed without mbstring support.
 *
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

/**
 * Fallback functions for PHP installed without mbstring support
 */
class Fallback {

	/**
	 * @param $from
	 * @param $to
	 * @param $string
	 * @return string
	 */
	public static function iconv( $from, $to, $string ) {
		if ( substr( $to, -8 ) == '//IGNORE' ) {
			$to = substr( $to, 0, strlen( $to ) - 8 );
		}
		if ( strcasecmp( $from, $to ) == 0 ) {
			return $string;
		}
		if ( strcasecmp( $from, 'utf-8' ) == 0 ) {
			return utf8_decode( $string );
		}
		if ( strcasecmp( $to, 'utf-8' ) == 0 ) {
			return utf8_encode( $string );
		}
		return $string;
	}

	/**
	 * Fallback implementation for mb_substr, hardcoded to UTF-8.
	 * Attempts to be at least _moderately_ efficient; best optimized
	 * for relatively small offset and count values -- about 5x slower
	 * than native mb_string in my testing.
	 *
	 * Larger offsets are still fairly efficient for Latin text, but
	 * can be up to 100x slower than native if the text is heavily
	 * multibyte and we have to slog through a few hundred kb.
	 *
	 * @param $str
	 * @param $start
	 * @param $count string
	 *
	 * @return string
	 */
	public static function mb_substr( $str, $start, $count = 'end' ) {
		if ( $start != 0 ) {
			$split = self::mb_substr_split_unicode( $str, intval( $start ) );
			$str = substr( $str, $split );
		}

		if ( $count !== 'end' ) {
			$split = self::mb_substr_split_unicode( $str, intval( $count ) );
			$str = substr( $str, 0, $split );
		}

		return $str;
	}

	/**
	 * @param $str
	 * @param $splitPos
	 * @return int
	 */
	public static function mb_substr_split_unicode( $str, $splitPos ) {
		if ( $splitPos == 0 ) {
			return 0;
		}

		$byteLen = strlen( $str );

		if ( $splitPos > 0 ) {
			if ( $splitPos > 256 ) {
				// Optimize large string offsets by skipping ahead N bytes.
				// This will cut out most of our slow time on Latin-based text,
				// and 1/2 to 1/3 on East European and Asian scripts.
				$bytePos = $splitPos;
				while ( $bytePos < $byteLen && $str[$bytePos] >= "\x80" && $str[$bytePos] < "\xc0" ) {
					++$bytePos;
				}
				$charPos = mb_strlen( substr( $str, 0, $bytePos ) );
			} else {
				$charPos = 0;
				$bytePos = 0;
			}

			while ( $charPos++ < $splitPos ) {
				++$bytePos;
				// Move past any tail bytes
				while ( $bytePos < $byteLen && $str[$bytePos] >= "\x80" && $str[$bytePos] < "\xc0" ) {
					++$bytePos;
				}
			}
		} else {
			$splitPosX = $splitPos + 1;
			$charPos = 0; // relative to end of string; we don't care about the actual char position here
			$bytePos = $byteLen;
			while ( $bytePos > 0 && $charPos-- >= $splitPosX ) {
				--$bytePos;
				// Move past any tail bytes
				while ( $bytePos > 0 && $str[$bytePos] >= "\x80" && $str[$bytePos] < "\xc0" ) {
					--$bytePos;
				}
			}
		}

		return $bytePos;
	}

	/**
	 * Fallback implementation of mb_strlen, hardcoded to UTF-8.
	 * @param string $str
	 * @param string $enc optional encoding; ignored
	 * @return int
	 */
	public static function mb_strlen( $str, $enc = '' ) {
		$counts = count_chars( $str );
		$total = 0;

		// Count ASCII bytes
		for ( $i = 0; $i < 0x80; $i++ ) {
			$total += $counts[$i];
		}

		// Count multibyte sequence heads
		for ( $i = 0xc0; $i < 0xff; $i++ ) {
			$total += $counts[$i];
		}
		return $total;
	}

	/**
	 * Fallback implementation of mb_strpos, hardcoded to UTF-8.
	 * @param $haystack String
	 * @param $needle String
	 * @param string $offset optional start position
	 * @param string $encoding optional encoding; ignored
	 * @return int
	 */
	public static function mb_strpos( $haystack, $needle, $offset = 0, $encoding = '' ) {
		$needle = preg_quote( $needle, '/' );

		$ar = array();
		preg_match( '/' . $needle . '/u', $haystack, $ar, PREG_OFFSET_CAPTURE, $offset );

		if ( isset( $ar[0][1] ) ) {
			return $ar[0][1];
		} else {
			return false;
		}
	}

	/**
	 * Fallback implementation of mb_strrpos, hardcoded to UTF-8.
	 * @param $haystack String
	 * @param $needle String
	 * @param string $offset optional start position
	 * @param string $encoding optional encoding; ignored
	 * @return int
	 */
	public static function mb_strrpos( $haystack, $needle, $offset = 0, $encoding = '' ) {
		$needle = preg_quote( $needle, '/' );

		$ar = array();
		preg_match_all( '/' . $needle . '/u', $haystack, $ar, PREG_OFFSET_CAPTURE, $offset );

		if ( isset( $ar[0] ) && count( $ar[0] ) > 0 &&
			isset( $ar[0][count( $ar[0] ) - 1][1] ) ) {
			return $ar[0][count( $ar[0] ) - 1][1];
		} else {
			return false;
		}
	}
}
