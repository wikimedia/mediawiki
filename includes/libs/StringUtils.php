<?php

use Wikimedia\AtEase\AtEase;

/**
 * Methods to play with strings.
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
 * A collection of static methods to play with strings.
 */
class StringUtils {
	/**
	 * Test whether a string is valid UTF-8.
	 *
	 * The function check for invalid byte sequences, overlong encoding but
	 * not for different normalisations.
	 *
	 * @note In MediaWiki 1.21, this function did not provide proper UTF-8 validation.
	 * In particular, the pure PHP code path did not in fact check for overlong forms.
	 * Beware of this when backporting code to that version of MediaWiki.
	 *
	 * @since 1.21
	 * @param string $value String to check
	 * @return bool Whether the given $value is a valid UTF-8 encoded string
	 */
	public static function isUtf8( $value ) {
		return mb_check_encoding( (string)$value, 'UTF-8' );
	}

	/**
	 * Explode a string, but ignore any instances of the separator inside
	 * the given start and end delimiters, which may optionally nest.
	 * The delimiters are literal strings, not regular expressions.
	 * @param string $startDelim Start delimiter
	 * @param string $endDelim End delimiter
	 * @param string $separator Separator string for the explode.
	 * @param string $subject Subject string to explode.
	 * @param bool $nested True iff the delimiters are allowed to nest.
	 * @return ArrayIterator
	 */
	public static function delimiterExplode( $startDelim, $endDelim, $separator,
		$subject, $nested = false ) {
		$inputPos = 0;
		$lastPos = 0;
		$depth = 0;
		$encStart = preg_quote( $startDelim, '!' );
		$encEnd = preg_quote( $endDelim, '!' );
		$encSep = preg_quote( $separator, '!' );
		$len = strlen( $subject );
		$m = [];
		$exploded = [];
		while (
			$inputPos < $len &&
			preg_match(
				"!$encStart|$encEnd|$encSep!S", $subject, $m,
				PREG_OFFSET_CAPTURE, $inputPos
			)
		) {
			$match = $m[0][0];
			$matchPos = $m[0][1];
			$inputPos = $matchPos + strlen( $match );
			if ( $match === $separator ) {
				if ( $depth === 0 ) {
					$exploded[] = substr(
						$subject, $lastPos, $matchPos - $lastPos
					);
					$lastPos = $inputPos;
				}
			} elseif ( $match === $startDelim ) {
				if ( $depth === 0 || $nested ) {
					$depth++;
				}
			} else {
				$depth--;
			}
		}
		$exploded[] = substr( $subject, $lastPos );
		// This method could be rewritten in the future to avoid creating an
		// intermediate array, since the return type is just an iterator.
		return new ArrayIterator( $exploded );
	}

	/**
	 * Perform an operation equivalent to `preg_replace()`
	 *
	 * Matches this code:
	 *
	 *     preg_replace( "!$startDelim(.*?)$endDelim!", $replace, $subject );
	 *
	 * ..except that it's worst-case O(N) instead of O(N^2). Compared to delimiterReplace(), this
	 * implementation is fast but memory-hungry and inflexible. The memory requirements are such
	 * that I don't recommend using it on anything but guaranteed small chunks of text.
	 *
	 * @param string $startDelim
	 * @param string $endDelim
	 * @param string $replace
	 * @param string $subject
	 * @return string
	 */
	public static function hungryDelimiterReplace( $startDelim, $endDelim, $replace, $subject ) {
		$segments = explode( $startDelim, $subject );
		$output = array_shift( $segments );
		foreach ( $segments as $s ) {
			$endDelimPos = strpos( $s, $endDelim );
			if ( $endDelimPos === false ) {
				$output .= $startDelim . $s;
			} else {
				$output .= $replace . substr( $s, $endDelimPos + strlen( $endDelim ) );
			}
		}

		return $output;
	}

	/**
	 * Perform an operation equivalent to `preg_replace_callback()`
	 *
	 * Matches this code:
	 *
	 *     preg_replace_callback( "!$startDelim(.*)$endDelim!s$flags", $callback, $subject );
	 *
	 * If the start delimiter ends with an initial substring of the end delimiter,
	 * e.g. in the case of C-style comments, the behavior differs from the model
	 * regex. In this implementation, the end must share no characters with the
	 * start, so e.g. `/*\/` is not considered to be both the start and end of a
	 * comment. `/*\/xy/*\/` is considered to be a single comment with contents `/xy/`.
	 *
	 * The implementation of delimiterReplaceCallback() is slower than hungryDelimiterReplace()
	 * but uses far less memory. The delimiters are literal strings, not regular expressions.
	 *
	 * @param string $startDelim Start delimiter
	 * @param string $endDelim End delimiter
	 * @param callable $callback Function to call on each match
	 * @param string $subject
	 * @param string $flags Regular expression flags
	 * @throws InvalidArgumentException
	 * @return string
	 */
	private static function delimiterReplaceCallback( $startDelim, $endDelim, $callback,
		$subject, $flags = ''
	) {
		$inputPos = 0;
		$outputPos = 0;
		$contentPos = 0;
		$output = '';
		$foundStart = false;
		$encStart = preg_quote( $startDelim, '!' );
		$encEnd = preg_quote( $endDelim, '!' );
		$strcmp = strpos( $flags, 'i' ) === false ? 'strcmp' : 'strcasecmp';
		$endLength = strlen( $endDelim );
		$m = [];

		while ( $inputPos < strlen( $subject ) &&
			preg_match( "!($encStart)|($encEnd)!S$flags", $subject, $m, PREG_OFFSET_CAPTURE, $inputPos )
		) {
			$tokenOffset = $m[0][1];
			if ( $m[1][0] != '' ) {
				if ( $foundStart &&
					$strcmp( $endDelim, substr( $subject, $tokenOffset, $endLength ) ) == 0
				) {
					# An end match is present at the same location
					$tokenType = 'end';
					$tokenLength = $endLength;
				} else {
					$tokenType = 'start';
					$tokenLength = strlen( $m[0][0] );
				}
			} elseif ( $m[2][0] != '' ) {
				$tokenType = 'end';
				$tokenLength = strlen( $m[0][0] );
			} else {
				throw new InvalidArgumentException( 'Invalid delimiter given to ' . __METHOD__ );
			}

			if ( $tokenType == 'start' ) {
				# Only move the start position if we haven't already found a start
				# This means that START START END matches outer pair
				if ( !$foundStart ) {
					# Found start
					$inputPos = $tokenOffset + $tokenLength;
					# Write out the non-matching section
					$output .= substr( $subject, $outputPos, $tokenOffset - $outputPos );
					$outputPos = $tokenOffset;
					$contentPos = $inputPos;
					$foundStart = true;
				} else {
					# Move the input position past the *first character* of START,
					# to protect against missing END when it overlaps with START
					$inputPos = $tokenOffset + 1;
				}
			} elseif ( $tokenType == 'end' ) {
				if ( $foundStart ) {
					# Found match
					$output .= $callback( [
						substr( $subject, $outputPos, $tokenOffset + $tokenLength - $outputPos ),
						substr( $subject, $contentPos, $tokenOffset - $contentPos )
					] );
					$foundStart = false;
				} else {
					# Non-matching end, write it out
					$output .= substr( $subject, $inputPos, $tokenOffset + $tokenLength - $outputPos );
				}
				$inputPos = $outputPos = $tokenOffset + $tokenLength;
			} else {
				throw new InvalidArgumentException( 'Invalid delimiter given to ' . __METHOD__ );
			}
		}
		if ( $outputPos < strlen( $subject ) ) {
			$output .= substr( $subject, $outputPos );
		}

		return $output;
	}

	/**
	 * Perform an operation equivalent to `preg_replace()` with flags.
	 *
	 * Matches this code:
	 *
	 *     preg_replace( "!$startDelim(.*)$endDelim!$flags", $replace, $subject );
	 *
	 * @param string $startDelim Start delimiter regular expression
	 * @param string $endDelim End delimiter regular expression
	 * @param string $replace Replacement string. May contain $1, which will be
	 *  replaced by the text between the delimiters
	 * @param string $subject String to search
	 * @param string $flags Regular expression flags
	 * @return string The string with the matches replaced
	 */
	public static function delimiterReplace(
		$startDelim, $endDelim, $replace, $subject, $flags = ''
	) {
		return self::delimiterReplaceCallback(
			$startDelim, $endDelim,
			function ( array $matches ) use ( $replace ) {
				return strtr( $replace, [ '$0' => $matches[0], '$1' => $matches[1] ] );
			},
			$subject, $flags
		);
	}

	/**
	 * More or less "markup-safe" str_replace()
	 * Ignores any instances of the separator inside `<...>`
	 * @param string $search
	 * @param string $replace
	 * @param string $text
	 * @return string
	 */
	public static function replaceMarkup( $search, $replace, $text ) {
		$placeholder = "\x00";

		// Remove placeholder instances
		$text = str_replace( $placeholder, '', $text );

		// Replace instances of the separator inside HTML-like tags with the placeholder
		$cleaned = self::delimiterReplaceCallback(
			'<', '>',
			function ( array $matches ) use ( $search, $placeholder ) {
				return str_replace( $search, $placeholder, $matches[0] );
			},
			$text
		);

		// Explode, then put the replaced separators back in
		$cleaned = str_replace( $search, $replace, $cleaned );
		$text = str_replace( $placeholder, $search, $cleaned );

		return $text;
	}

	/**
	 * Utility function to check if the given string is a valid PCRE regex. Avoids
	 * manually calling suppressWarnings and restoreWarnings, and provides a
	 * one-line solution without the need to use @.
	 *
	 * @since 1.34
	 * @param string $string The string you want to check being a valid regex
	 * @return bool
	 */
	public static function isValidPCRERegex( $string ) {
		AtEase::suppressWarnings();
		// @phan-suppress-next-line PhanParamSuspiciousOrder False positive
		$isValid = preg_match( $string, '' );
		AtEase::restoreWarnings();
		return $isValid !== false;
	}

	/**
	 * Escape a string to make it suitable for inclusion in a preg_replace()
	 * replacement parameter.
	 *
	 * @param string $string
	 * @return string
	 */
	public static function escapeRegexReplacement( $string ) {
		$string = str_replace( '\\', '\\\\', $string );
		return str_replace( '$', '\\$', $string );
	}

	/**
	 * Workalike for explode() with limited memory usage.
	 *
	 * @param string $separator
	 * @param string $subject
	 * @return ArrayIterator|ExplodeIterator
	 */
	public static function explode( $separator, $subject ) {
		if ( substr_count( $subject, $separator ) > 1000 ) {
			return new ExplodeIterator( $separator, $subject );
		} else {
			return new ArrayIterator( explode( $separator, $subject ) );
		}
	}
}
