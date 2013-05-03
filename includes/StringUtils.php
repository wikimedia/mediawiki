<?php
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
	 * This relies internally on the mbstring function mb_check_encoding()
	 * hardcoded to check against UTF-8. Whenever the function is not available
	 * we fallback to a pure PHP implementation. Setting $disableMbstring to
	 * true will skip the use of mb_check_encoding, this is mostly intended for
	 * unit testing our internal implementation.
	 *
	 * @since 1.21
	 * @note In MediaWiki 1.21, this function did not provide proper UTF-8 validation.
	 * In particular, the pure PHP code path did not in fact check for overlong forms.
	 * Beware of this when backporting code to that version of MediaWiki.
	 *
	 * @param string $value String to check
	 * @param boolean $disableMbstring Whether to use the pure PHP
	 * implementation instead of trying mb_check_encoding. Intended for unit
	 * testing. Default: false
	 *
	 * @return boolean Whether the given $value is a valid UTF-8 encoded string
	 */
	static function isUtf8( $value, $disableMbstring = false ) {
		$value = (string)$value;

		// If the mbstring extension is loaded, use it. However, before PHP 5.4, values above
		// U+10FFFF are incorrectly allowed, so we have to check for them separately.
		if ( !$disableMbstring && function_exists( 'mb_check_encoding' ) ) {
			static $newPHP;
			if ( $newPHP === null ) {
				$newPHP = !mb_check_encoding( "\xf4\x90\x80\x80", 'UTF-8' );
			}

			return mb_check_encoding( $value, 'UTF-8' ) &&
				( $newPHP || preg_match( "/\xf4[\x90-\xbf]|[\xf5-\xff]/S", $value ) === 0 );
		}

		if ( preg_match( "/[\x80-\xff]/S", $value ) === 0 ) {
			// String contains only ASCII characters, has to be valid
			return true;
		}

		// PCRE implements repetition using recursion; to avoid a stack overflow (and segfault)
		// for large input, we check for invalid sequences (<= 5 bytes) rather than valid
		// sequences, which can be as long as the input string is. Multiple short regexes are
		// used rather than a single long regex for performance.
		static $regexes;
		if ( $regexes === null ) {
			$cont = "[\x80-\xbf]";
			$after = "(?!$cont)"; // "(?:[^\x80-\xbf]|$)" would work here
			$regexes = array(
				// Continuation byte at the start
				"/^$cont/",

				// ASCII byte followed by a continuation byte
				"/[\\x00-\x7f]$cont/S",

				// Illegal byte
				"/[\xc0\xc1\xf5-\xff]/S",

				// Invalid 2-byte sequence, or valid one then an extra continuation byte
				"/[\xc2-\xdf](?!$cont$after)/S",

				// Invalid 3-byte sequence, or valid one then an extra continuation byte
				"/\xe0(?![\xa0-\xbf]$cont$after)/",
				"/[\xe1-\xec\xee\xef](?!$cont{2}$after)/S",
				"/\xed(?![\x80-\x9f]$cont$after)/",

				// Invalid 4-byte sequence, or valid one then an extra continuation byte
				"/\xf0(?![\x90-\xbf]$cont{2}$after)/",
				"/[\xf1-\xf3](?!$cont{3}$after)/S",
				"/\xf4(?![\x80-\x8f]$cont{2}$after)/",
			);
		}

		foreach ( $regexes as $regex ) {
			if ( preg_match( $regex, $value ) !== 0 ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Perform an operation equivalent to
	 *
	 *     preg_replace( "!$startDelim(.*?)$endDelim!", $replace, $subject );
	 *
	 * except that it's worst-case O(N) instead of O(N^2)
	 *
	 * Compared to delimiterReplace(), this implementation is fast but memory-
	 * hungry and inflexible. The memory requirements are such that I don't
	 * recommend using it on anything but guaranteed small chunks of text.
	 *
	 * @param $startDelim
	 * @param $endDelim
	 * @param $replace
	 * @param $subject
	 *
	 * @return string
	 */
	static function hungryDelimiterReplace( $startDelim, $endDelim, $replace, $subject ) {
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
	 * Perform an operation equivalent to
	 *
	 *   preg_replace_callback( "!$startDelim(.*)$endDelim!s$flags", $callback, $subject )
	 *
	 * This implementation is slower than hungryDelimiterReplace but uses far less
	 * memory. The delimiters are literal strings, not regular expressions.
	 *
	 * If the start delimiter ends with an initial substring of the end delimiter,
	 * e.g. in the case of C-style comments, the behavior differs from the model
	 * regex. In this implementation, the end must share no characters with the
	 * start, so e.g. /*\/ is not considered to be both the start and end of a
	 * comment. /*\/xy/*\/ is considered to be a single comment with contents /xy/.
	 *
	 * @param string $startDelim start delimiter
	 * @param string $endDelim end delimiter
	 * @param $callback Callback: function to call on each match
	 * @param $subject String
	 * @param string $flags regular expression flags
	 * @throws MWException
	 * @return string
	 */
	static function delimiterReplaceCallback( $startDelim, $endDelim, $callback, $subject, $flags = '' ) {
		$inputPos = 0;
		$outputPos = 0;
		$output = '';
		$foundStart = false;
		$encStart = preg_quote( $startDelim, '!' );
		$encEnd = preg_quote( $endDelim, '!' );
		$strcmp = strpos( $flags, 'i' ) === false ? 'strcmp' : 'strcasecmp';
		$endLength = strlen( $endDelim );
		$m = array();

		while ( $inputPos < strlen( $subject ) &&
			preg_match( "!($encStart)|($encEnd)!S$flags", $subject, $m, PREG_OFFSET_CAPTURE, $inputPos ) )
		{
			$tokenOffset = $m[0][1];
			if ( $m[1][0] != '' ) {
				if ( $foundStart &&
					$strcmp( $endDelim, substr( $subject, $tokenOffset, $endLength ) ) == 0 )
				{
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
				throw new MWException( 'Invalid delimiter given to ' . __METHOD__ );
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
					$output .= call_user_func( $callback, array(
						substr( $subject, $outputPos, $tokenOffset + $tokenLength - $outputPos ),
						substr( $subject, $contentPos, $tokenOffset - $contentPos )
					));
					$foundStart = false;
				} else {
					# Non-matching end, write it out
					$output .= substr( $subject, $inputPos, $tokenOffset + $tokenLength - $outputPos );
				}
				$inputPos = $outputPos = $tokenOffset + $tokenLength;
			} else {
				throw new MWException( 'Invalid delimiter given to ' . __METHOD__ );
			}
		}
		if ( $outputPos < strlen( $subject ) ) {
			$output .= substr( $subject, $outputPos );
		}
		return $output;
	}

	/**
	 * Perform an operation equivalent to
	 *
	 *   preg_replace( "!$startDelim(.*)$endDelim!$flags", $replace, $subject )
	 *
	 * @param string $startDelim start delimiter regular expression
	 * @param string $endDelim end delimiter regular expression
	 * @param string $replace replacement string. May contain $1, which will be
	 *                 replaced by the text between the delimiters
	 * @param string $subject to search
	 * @param string $flags regular expression flags
	 * @return String: The string with the matches replaced
	 */
	static function delimiterReplace( $startDelim, $endDelim, $replace, $subject, $flags = '' ) {
		$replacer = new RegexlikeReplacer( $replace );
		return self::delimiterReplaceCallback( $startDelim, $endDelim,
			$replacer->cb(), $subject, $flags );
	}

	/**
	 * More or less "markup-safe" explode()
	 * Ignores any instances of the separator inside <...>
	 * @param string $separator
	 * @param string $text
	 * @return array
	 */
	static function explodeMarkup( $separator, $text ) {
		$placeholder = "\x00";

		// Remove placeholder instances
		$text = str_replace( $placeholder, '', $text );

		// Replace instances of the separator inside HTML-like tags with the placeholder
		$replacer = new DoubleReplacer( $separator, $placeholder );
		$cleaned = StringUtils::delimiterReplaceCallback( '<', '>', $replacer->cb(), $text );

		// Explode, then put the replaced separators back in
		$items = explode( $separator, $cleaned );
		foreach ( $items as $i => $str ) {
			$items[$i] = str_replace( $placeholder, $separator, $str );
		}

		return $items;
	}

	/**
	 * Escape a string to make it suitable for inclusion in a preg_replace()
	 * replacement parameter.
	 *
	 * @param string $string
	 * @return string
	 */
	static function escapeRegexReplacement( $string ) {
		$string = str_replace( '\\', '\\\\', $string );
		$string = str_replace( '$', '\\$', $string );
		return $string;
	}

	/**
	 * Workalike for explode() with limited memory usage.
	 * Returns an Iterator
	 * @param string $separator
	 * @param string $subject
	 * @return ArrayIterator|ExplodeIterator
	 */
	static function explode( $separator, $subject ) {
		if ( substr_count( $subject, $separator ) > 1000 ) {
			return new ExplodeIterator( $separator, $subject );
		} else {
			return new ArrayIterator( explode( $separator, $subject ) );
		}
	}
}

/**
 * Base class for "replacers", objects used in preg_replace_callback() and
 * StringUtils::delimiterReplaceCallback()
 */
class Replacer {

	/**
	 * @return array
	 */
	function cb() {
		return array( &$this, 'replace' );
	}
}

/**
 * Class to replace regex matches with a string similar to that used in preg_replace()
 */
class RegexlikeReplacer extends Replacer {
	var $r;

	/**
	 * @param string $r
	 */
	function __construct( $r ) {
		$this->r = $r;
	}

	/**
	 * @param array $matches
	 * @return string
	 */
	function replace( $matches ) {
		$pairs = array();
		foreach ( $matches as $i => $match ) {
			$pairs["\$$i"] = $match;
		}
		return strtr( $this->r, $pairs );
	}

}

/**
 * Class to perform secondary replacement within each replacement string
 */
class DoubleReplacer extends Replacer {

	/**
	 * @param $from
	 * @param $to
	 * @param int $index
	 */
	function __construct( $from, $to, $index = 0 ) {
		$this->from = $from;
		$this->to = $to;
		$this->index = $index;
	}

	/**
	 * @param array $matches
	 * @return mixed
	 */
	function replace( $matches ) {
		return str_replace( $this->from, $this->to, $matches[$this->index] );
	}
}

/**
 * Class to perform replacement based on a simple hashtable lookup
 */
class HashtableReplacer extends Replacer {
	var $table, $index;

	/**
	 * @param $table
	 * @param int $index
	 */
	function __construct( $table, $index = 0 ) {
		$this->table = $table;
		$this->index = $index;
	}

	/**
	 * @param array $matches
	 * @return mixed
	 */
	function replace( $matches ) {
		return $this->table[$matches[$this->index]];
	}
}

/**
 * Replacement array for FSS with fallback to strtr()
 * Supports lazy initialisation of FSS resource
 */
class ReplacementArray {
	/*mostly private*/ var $data = false;
	/*mostly private*/ var $fss = false;

	/**
	 * Create an object with the specified replacement array
	 * The array should have the same form as the replacement array for strtr()
	 * @param array $data
	 */
	function __construct( $data = array() ) {
		$this->data = $data;
	}

	/**
	 * @return array
	 */
	function __sleep() {
		return array( 'data' );
	}

	function __wakeup() {
		$this->fss = false;
	}

	/**
	 * Set the whole replacement array at once
	 * @param array $data
	 */
	function setArray( $data ) {
		$this->data = $data;
		$this->fss = false;
	}

	/**
	 * @return array|bool
	 */
	function getArray() {
		return $this->data;
	}

	/**
	 * Set an element of the replacement array
	 * @param string $from
	 * @param string $to
	 */
	function setPair( $from, $to ) {
		$this->data[$from] = $to;
		$this->fss = false;
	}

	/**
	 * @param array $data
	 */
	function mergeArray( $data ) {
		$this->data = array_merge( $this->data, $data );
		$this->fss = false;
	}

	/**
	 * @param ReplacementArray $other
	 */
	function merge( $other ) {
		$this->data = array_merge( $this->data, $other->data );
		$this->fss = false;
	}

	/**
	 * @param string $from
	 */
	function removePair( $from ) {
		unset( $this->data[$from] );
		$this->fss = false;
	}

	/**
	 * @param array $data
	 */
	function removeArray( $data ) {
		foreach ( $data as $from => $to ) {
			$this->removePair( $from );
		}
		$this->fss = false;
	}

	/**
	 * @param string $subject
	 * @return string
	 */
	function replace( $subject ) {
		if ( function_exists( 'fss_prep_replace' ) ) {
			wfProfileIn( __METHOD__ . '-fss' );
			if ( $this->fss === false ) {
				$this->fss = fss_prep_replace( $this->data );
			}
			$result = fss_exec_replace( $this->fss, $subject );
			wfProfileOut( __METHOD__ . '-fss' );
		} else {
			wfProfileIn( __METHOD__ . '-strtr' );
			$result = strtr( $subject, $this->data );
			wfProfileOut( __METHOD__ . '-strtr' );
		}
		return $result;
	}
}

/**
 * An iterator which works exactly like:
 *
 * foreach ( explode( $delim, $s ) as $element ) {
 *    ...
 * }
 *
 * Except it doesn't use 193 byte per element
 */
class ExplodeIterator implements Iterator {
	// The subject string
	var $subject, $subjectLength;

	// The delimiter
	var $delim, $delimLength;

	// The position of the start of the line
	var $curPos;

	// The position after the end of the next delimiter
	var $endPos;

	// The current token
	var $current;

	/**
	 * Construct a DelimIterator
	 * @param string $delim
	 * @param string $subject
	 */
	function __construct( $delim, $subject ) {
		$this->subject = $subject;
		$this->delim = $delim;

		// Micro-optimisation (theoretical)
		$this->subjectLength = strlen( $subject );
		$this->delimLength = strlen( $delim );

		$this->rewind();
	}

	function rewind() {
		$this->curPos = 0;
		$this->endPos = strpos( $this->subject, $this->delim );
		$this->refreshCurrent();
	}

	function refreshCurrent() {
		if ( $this->curPos === false ) {
			$this->current = false;
		} elseif ( $this->curPos >= $this->subjectLength ) {
			$this->current = '';
		} elseif ( $this->endPos === false ) {
			$this->current = substr( $this->subject, $this->curPos );
		} else {
			$this->current = substr( $this->subject, $this->curPos, $this->endPos - $this->curPos );
		}
	}

	function current() {
		return $this->current;
	}

	/**
	 * @return int|bool Current position or boolean false if invalid
	 */
	function key() {
		return $this->curPos;
	}

	/**
	 * @return string
	 */
	function next() {
		if ( $this->endPos === false ) {
			$this->curPos = false;
		} else {
			$this->curPos = $this->endPos + $this->delimLength;
			if ( $this->curPos >= $this->subjectLength ) {
				$this->endPos = false;
			} else {
				$this->endPos = strpos( $this->subject, $this->delim, $this->curPos );
			}
		}
		$this->refreshCurrent();
		return $this->current;
	}

	/**
	 * @return bool
	 */
	function valid() {
		return $this->curPos !== false;
	}
}
