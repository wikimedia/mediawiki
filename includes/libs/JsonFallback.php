<?php
/**
 * A pure PHP reimplementation of the PHP 5.5 JSON functions, useful for situations in which
 * native JSON support is not compiled into PHP.
 *
 * More information is at <https://github.com/plstand/jsonfallback>.
 *
 * Copyright © 2013 Kevin Israel
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING
 * BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @file
 */

/**
 * Holds the JSON functions and associated global state.
 */
class JsonFallback {

	const HEX_TAG = 1;
	const HEX_AMP = 2;
	const HEX_APOS = 4;
	const HEX_QUOT = 8;
	const FORCE_OBJECT = 16;
	const NUMERIC_CHECK = 32;
	const UNESCAPED_SLASHES = 64;
	const PRETTY_PRINT = 128;
	const UNESCAPED_UNICODE = 256;
	const PARTIAL_OUTPUT_ON_ERROR = 512;

	const ERROR_NONE = 0;
	const ERROR_DEPTH = 1;
	const ERROR_STATE_MISMATCH = 2;
	const ERROR_CTRL_CHAR = 3; // Not used in this library
	const ERROR_SYNTAX = 4;
	const ERROR_UTF8 = 5;
	const ERROR_RECURSION = 6;
	const ERROR_INF_OR_NAN = 7;
	const ERROR_UNSUPPORTED_TYPE = 8;

	const OBJECT_AS_ARRAY = 1; // Not used in this library
	const BIGINT_AS_STRING = 2;

	private static $lastError = self::ERROR_NONE;

	/**
	 * @see http://php.net/json_encode
	 * @param mixed $value Anything but a resource
	 * @param int $options Bitfield of JsonFallback constants
	 * @param int $depth Maximum recursion depth
	 * @return string: JSON representation of $value
	 */
	public static function encode( $value, $options = 0, $depth = 512 ) {
		$ctx = new JsonFallbackEncoder( $options );
		$json = $ctx->encode( $value, $depth );
		self::$lastError = $ctx->lastError;
		return $json;
	}

	/**
	 * @see http://php.net/json_decode
	 * @param string $json String to decode
	 * @param bool $assoc Whether to decode JSON objects as PHP arrays
	 * @param int $depth Maximum recursion depth
	 * @param int $options Either JsonFallback::BIGINT_AS_STRING or 0
	 * @return mixed: The decoded value
	 */
	public static function decode( $json, $assoc = false, $depth = 512, $options = 0 ) {
		$ctx = new JsonFallbackDecoder( $json, $assoc, $options );
		$value = $ctx->decode( $depth );
		self::$lastError = $ctx->lastError;
		return $value;
	}

	/**
	 * @see http://php.net/json_last_error
	 * @return int: One of the JsonFallback::ERROR_* constants
	 */
	public static function last_error() {
		return self::$lastError;
	}

	/**
	 * @see http://php.net/json_last_error_msg
	 * @return string: Error message
	 */
	public static function last_error_msg() {
		$errorMap = array(
			self::ERROR_NONE => 'No error',
			self::ERROR_DEPTH => 'Maximum stack depth exceeded',
			self::ERROR_STATE_MISMATCH => 'State mismatch (invalid or malformed JSON)',
			self::ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
			self::ERROR_SYNTAX => 'Syntax error',
			self::ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
			self::ERROR_RECURSION => 'Recursion detected',
			self::ERROR_INF_OR_NAN => 'Inf and NaN cannot be JSON encoded',
			self::ERROR_UNSUPPORTED_TYPE => 'Type is not supported',
		);
		return $errorMap[self::$lastError];
	}

	/**
	 * Exports prefixed class constants and functions into the global scope.
	 *
	 * @private
	 */
	public static function addGlobals() {
		$ref = new ReflectionClass( __CLASS__ );
		foreach ( $ref->getConstants() as $k => $v ) {
			define( 'JSON_' . $k, $v );
		}

		function json_encode( $value, $options = 0, $depth = 512 ) {
			return JsonFallback::encode( $value, $options, $depth );
		}

		function json_decode( $json, $assoc = false, $depth = 512, $options = 0 ) {
			return JsonFallback::decode( $json, $assoc, $depth, $options );
		}

		function json_last_error() {
			return JsonFallback::last_error();
		}

		function json_last_error_msg() {
			return JsonFallback::last_error_msg();
		}
	}

}

/**
 * Holds the state of an encode operation.
 *
 * @private
 */
class JsonFallbackEncoder {

	public $lastError = JsonFallback::ERROR_NONE;
	private $visitedArrays = array();
	private $visitedObjects = array();
	private $options;
	private $escapeMap;

	/**
	 * @param int $options Bitfield of JsonFallback constants
	 */
	public function __construct( $options ) {
		$this->options = (int)$options;

		static $defaultEscapeMap = array();
		if ( !$defaultEscapeMap ) {
			$specials = array(
				0x08 => '\b', 0x09 => '\t', 0x0a => '\n', 0x0c => '\f', 0x0d => '\r',
				0x22 => '\"', 0x2f => '\/', 0x5c => '\\\\',
			);
			for ( $i = 0x00; $i < 0x80; $i++ ) {
				if ( isset( $specials[$i] ) ) {
					$defaultEscapeMap[$i] = $specials[$i];
				} elseif ( $i < 0x20 ) {
					$defaultEscapeMap[$i] = sprintf( '\u%04x', $i );
				} else {
					$defaultEscapeMap[$i] = chr( $i );
				}
			}
		}

		// $defaultEscapeMap contains default JSON-encoded forms of ASCII characters.
		// To reflect any specified non-default options, we modify a copy of the array.
		$this->escapeMap = $defaultEscapeMap;

		if ( $this->options & JsonFallback::HEX_QUOT ) {
			$this->escapeMap[0x22] = '\u0022';
		}
		if ( $this->options & JsonFallback::HEX_AMP ) {
			$this->escapeMap[0x26] = '\u0026';
		}
		if ( $this->options & JsonFallback::HEX_APOS ) {
			$this->escapeMap[0x27] = '\u0027';
		}
		if ( $this->options & JsonFallback::UNESCAPED_SLASHES ) {
			$this->escapeMap[0x2f] = '/';
		}
		if ( $this->options & JsonFallback::HEX_TAG ) {
			// Uppercase as in the native JSON extension
			$this->escapeMap[0x3c] = '\u003C';
			$this->escapeMap[0x3e] = '\u003E';
		}
	}

	/**
	 * Adds non-significant whitespace to an existing JSON representation of an object.
	 *
	 * @param string $json
	 * @return string
	 */
	private static function prettyPrint( $json ) {
		$buf = '';
		$indent = 0;
		$json = strtr( $json, array( '\\\\' => '\\\\', '\"' => "\x01" ) );
		for ( $i = 0, $n = strlen( $json ); $i < $n; $i += $skip ) {
			$skip = 1;
			switch ( $json[$i] ) {
				case ':':
					$buf .= ': ';
					break;
				case '[':
				case '{':
					$indent++; // falls through
				case ',':
					$buf .= $json[$i] . "\n" . str_repeat( '    ', $indent );
					break;
				case ']':
				case '}':
					$indent--;
					$buf .= "\n" . str_repeat( '    ', $indent ) . $json[$i];
					break;
				case '"':
					$skip = strcspn( $json, '"', $i + 1 ) + 2;
					$buf .= substr( $json, $i, $skip );
					break;
				default:
					$skip = strcspn( $json, ',]}"', $i + 1 ) + 1;
					$buf .= substr( $json, $i, $skip );
			}
		}
		return str_replace( "\x01", '\"', preg_replace( '/ +$/m', '', $buf ) );
	}

	/**
	 * JSON encodes a value of arbitrary type, catching any JsonFallbackError exception.
	 *
	 * @param mixed $value Anything but a resource
	 * @param int $depth Maximum recursion depth
	 * @return string: JSON representation of $value
	 */
	public function encode( $value, $depth ) {
		$xdebugHack = new JsonFallbackXdebugHack;
		try {
			$json = $this->encodeRecursive( $value, (int)$depth );
			if ( $this->options & JsonFallback::PRETTY_PRINT ) {
				$json = self::prettyPrint( $json );
			}
		} catch ( JsonFallbackError $e ) {
			$json = false;
		}
		return $json;
	}

	/**
	 * @param mixed $value Anything but a resource
	 * @param int $depth Maximum recursion depth
	 * @return string: JSON representation of $value
	 */
	private function encodeRecursive( &$value, $depth ) {
		if ( $value === true ) {
			return 'true';
		} elseif ( $value === false ) {
			return 'false';
		} elseif ( $value === null ) {
			return 'null';
		} elseif ( is_int( $value ) ) {
			return (string)$value;
		} elseif ( is_float( $value ) ) {
			if ( $value === INF || $value === -INF || $value !== $value ) {
				return $this->error( JsonFallback::ERROR_INF_OR_NAN, '0' );
			} else {
				return strtolower( $value );
			}
		} elseif ( is_string( $value ) ) {
			if ( $this->options & JsonFallback::NUMERIC_CHECK && is_numeric( $value ) ) {
				// Can't use the plus operator here; it changes negative zero to positive zero
				$copy = $value - 0;
				return $this->encodeRecursive( $copy, $depth );
			} else {
				return $this->encodeString( $value );
			}

		} elseif ( is_object( $value ) ) {
			// Always encode PHP objects as JSON objects
			$isPHPObject = true;
			$isJSONObject = true;

			// Circular reference check
			if ( in_array( $value, $this->visitedObjects, true ) ) {
				return $this->error( JsonFallback::ERROR_RECURSION );
			}

			$this->visitedObjects[] = $value;

			if ( $value instanceof JsonSerializable ) {
				$copy = $value->jsonSerialize();
				if ( $copy !== $value ) {
					return $this->encodeRecursive( $copy, $depth );
				}
			}

			// Closures cannot have properties, and since PHP 5.3.3, they cast to arrays as
			// scalars do <https://bugs.php.net/bug.php?id=52193>. Nevertheless, they are
			// JSON encoded as what they are: empty objects.
			$copy = $value instanceof Closure ? array() : (array) $value;

		} elseif ( is_array( $value ) ) {
			// Encode PHP arrays as JSON objects when necessary, or if forced to
			$isPHPObject = false;
			$isJSONObject = $this->options & JsonFallback::FORCE_OBJECT ||
				$value && array_keys( $value ) !== range( 0, count( $value ) - 1 );

			// Circular reference check. This is rather complex because of how PHP works:
			// * Using `===` could result in a "Fatal error: Nesting level too deep [...]".
			// * In Zend PHP, copying the array would not preserve the value of the
			//   HashTable's nNextFreeElement field. This prevents temporarily setting the
			//   array to something else and later restoring it without possibly affecting
			//   how code that uses `$array[] =` would behave thereafter.
			// * Temporarily adding an element to the end of the array is not an option
			//   because the array might already contain the maximum allowable integer key.
			// * Adding an element with a known string key is not an option because the
			//   array could contain *any* key for that matter.
			// * Fortunately, an array's internal pointer can be temporarily changed and
			//   put back just the way it was when we are done checking.
			$before = array_map( 'key', $this->visitedArrays );
			key( $value ) === null ? reset( $value ) : next( $value );
			$after = array_map( 'key', $this->visitedArrays );
			key( $value ) === null ? end( $value ) : prev( $value );
			if ( $before !== $after ) {
				return $this->error( JsonFallback::ERROR_RECURSION );
			}

			$this->visitedArrays[] =& $value;

			// Iterate over a copy to preserve the internal pointer
			$copy = $value;

		} else {
			return $this->error( JsonFallback::ERROR_UNSUPPORTED_TYPE );
		}

		// Undocumented encoding depth check <https://bugs.php.net/bug.php?id=62369>
		// JsonFallback::PARTIAL_OUTPUT_ON_ERROR allows encoding to continue on here (PHP bug?)
		if ( $depth < 1 ) {
			$this->lastError = JsonFallback::ERROR_DEPTH;
			if ( !( $this->options & JsonFallback::PARTIAL_OUTPUT_ON_ERROR ) ) {
				throw new JsonFallbackError;
			}
		}

		$parts = array();
		if ( $isJSONObject ) {
			foreach ( $copy as $propName => &$propValue ) {
				// Include keys that are the wrong type (string vs. integer) for the PHP
				// value in question (possible through array/object casting), yet for
				// PHP objects, don't include properties that otherwise are inaccessible.
				$propName = (string)$propName;
				if ( !$isPHPObject || $propName >= "\x01" ) {
					// NOTE: when JsonFallback::PARTIAL_OUTPUT_ON_ERROR is specified, and the key is
					// not valid UTF-8, invalid JSON is produced. The native extension is no better.
					$parts[] = $this->encodeString( $propName ) . ':' .
						$this->encodeRecursive( $propValue, $depth - 1 );
				}
			}
			$json = '{' . implode( ',', $parts ) . '}';

		} else {
			foreach ( $copy as &$propValue ) {
				$parts[] = $this->encodeRecursive( $propValue, $depth - 1 );
			}
			$json = '[' . implode( ',', $parts ) . ']';
		}

		if ( $isPHPObject ) {
			array_pop( $this->visitedObjects );
		} else {
			array_pop( $this->visitedArrays );
		}
		return $json;
	}

	/**
	 * JSON encodes a UTF-8 string.
	 *
	 * The UTF-8 decoding algorithm is based on that described in the WHATWG Encoding Standard
	 * <http://encoding.spec.whatwg.org/#utf-8>, with some performance tweaks. Some error handling
	 * details are omitted because invalid UTF-8 aborts the JSON encoding process anyway.
	 *
	 * @param string $string
	 * @return string
	 */
	private function encodeString( $string ) {
		$json = '"';

		// This is the range in which continuation bytes fall. It is further restricted for
		// the second byte (first continuation byte) of some 3- and 4-byte sequences.
		$lbound = 0x80;
		$ubound = 0xbf;

		for ( $i = 0, $n = strlen( $string ); $i < $n; ) {

			$codeStart = $i++;
			$code = ord( $string[$codeStart] );

			if ( $code < 0x80 ) {
				$json .= $this->escapeMap[$code];
				continue;
			} elseif ( $code < 0xc2 ) {
				// Continuation byte or overlong form of ASCII character
				return $this->error( JsonFallback::ERROR_UTF8 );
			} elseif ( $code < 0xe0 ) {
				$codeLength = 2;
				$code -= 0xc0;
			} elseif ( $code < 0xf0 ) {
				$codeLength = 3;
				$code -= 0xe0;
				if ( $code === 0x00 ) {
					$lbound = 0xa0; // Exclude 3-byte overlong forms
				} elseif ( $code === 0x0d ) {
					$ubound = 0x9f; // Exclude surrogates (U+D800..U+DFFF)
				}
			} elseif ( $code < 0xf5 ) {
				$codeLength = 4;
				$code -= 0xf0;
				if ( $code === 0x00 ) {
					$lbound = 0x90; // Exclude 4-byte overlong forms
				} elseif ( $code === 0x04 ) {
					$ubound = 0x8f; // Exclude values beyond U+10FFFF
				}
			} else {
				// Value beyond U+13FFFF
				return $this->error( JsonFallback::ERROR_UTF8 );
			}

			$need = $codeLength - 1;

			// Check for premature end of string to prevent a PHP notice
			if ( $i + $need > $n ) {
				return $this->error( JsonFallback::ERROR_UTF8 );
			}

			// Read $need continuation bytes, the first between $lbound and $ubound
			do {
				$byte = ord( $string[$i++] );
				if ( $byte < $lbound || $byte > $ubound ) {
					return $this->error( JsonFallback::ERROR_UTF8 );
				}

				$code = $code << 6 | $byte & 0x3f;

				// Restore original values
				$lbound = 0x80;
				$ubound = 0xbf;

			} while ( --$need );

			// Escape the code point (if necessary) and append it to $json
			if ( $this->options & JsonFallback::UNESCAPED_UNICODE ) {
				$json .= substr( $string, $codeStart, $codeLength );
			} elseif ( $code < 0x10000 ) {
				// Use a single UTF-16 code unit
				$json .= sprintf( '\u%04x', $code );
			} else {
				// Use a surrogate pair
				$code -= 0x10000;
				$json .= sprintf( '\u%04x\u%04x',
					0xd800 + ( $code >> 10 ), 0xdc00 + ( $code & 0x3ff ) );
			}
		}

		$json .= '"';
		return $json;
	}

	/**
	 * Handles an encode error by throwing an exception or returning some dummy JSON.
	 * The relevant error code is saved.
	 *
	 * @param int $code Error code for JsonFallback::json_last_error() to return
	 * @param string $dummyJson Representation to use for the non-encodable value
	 * @return mixed: Value of $dummyJson
	 * @throws JsonFallbackError
	 */
	private function error( $code, $dummyJson = 'null' ) {
		$this->lastError = $code;
		if ( $this->options & JsonFallback::PARTIAL_OUTPUT_ON_ERROR ) {
			return $dummyJson;
		}
		throw new JsonFallbackError;
	}

}

/**
 * Holds the state of a decode operation.
 *
 * @private
 */
class JsonFallbackDecoder {

	public $lastError = JsonFallback::ERROR_NONE;
	private $s;
	private $i = 0;
	private $objectAsArray;
	private $bigIntAsString;

	/**
	 * @param string $json String to decode
	 * @param bool $assoc Whether to decode JSON objects as PHP arrays
	 * @param int $options Either JsonFallback::BIGINT_AS_STRING or 0
	 */
	public function __construct( $json, $assoc, $options ) {
		$this->s = (string)$json;
		$this->objectAsArray = (bool)$assoc;
		$this->bigIntAsString = (bool)( (int)$options & JsonFallback::BIGINT_AS_STRING );
	}

	/**
	 * Decode the JSON string, catching any JsonFallbackError exception.
	 *
	 * @param int $depth Maximum recursion depth
	 * @return mixed: The decoded value
	 */
	public function decode( $depth ) {
		$xdebugHack = new JsonFallbackXdebugHack;
		$depth = (int)$depth;
		if ( $depth < 1 ) {
			trigger_error( 'json_decode(): Depth must be greater than zero', E_USER_WARNING );
			return null; // Report JsonFallback::ERROR_NONE though
		}

		if ( $this->s === '' ) {
			return null; // Report JsonFallback::ERROR_NONE though (PHP bug?)
		}

		// NOTE: This simulates a quirk in the PHP JSON parser arising from the fact that lone
		// scalar value parsing was "bolted on", and poorly. Leading zeros are tolerated, yet
		// whitespace around true/false/null is not. And true/false/null are case insensitive;
		// that one is actually documented.
		if ( is_numeric( $this->s ) ) {
			return $this->decodeNumber( $this->s );
		} elseif ( strcasecmp( $this->s, 'true' ) === 0 ) {
			return true;
		} elseif ( strcasecmp( $this->s, 'false' ) === 0 ) {
			return false;
		} elseif ( strcasecmp( $this->s, 'null' ) === 0 ) {
			return null;
		}

		try {
			// Validate the UTF-8 before decoding. Use mb_check_encoding when possible; otherwise
			// use the slower validation code from the string encoder. In particular, PHP < 5.4
			// accepts values above U+10FFFF, so fall back to the slow way in that case.
			if ( function_exists( 'mb_check_encoding' ) &&
				!mb_check_encoding( "\xf4\x90\x80\x80", 'UTF-8' ) )
			{
				$valid = mb_check_encoding( $this->s, 'UTF-8' );
			} else {
				$ectx = new JsonFallbackEncoder(
					JsonFallback::UNESCAPED_SLASHES | JsonFallback::UNESCAPED_UNICODE );
				$valid = $ectx->encode( $this->s, 0 ) !== false;
			}

			if ( !$valid ) {
				$this->error( JsonFallback::ERROR_UTF8 );
			}

			// Lone number/true/false/null was already handled above
			$token = $this->nextToken();
			if ( $token[0] === 's' && !is_string( $token[1] ) ) {
				$this->error( JsonFallback::ERROR_SYNTAX );
			}

			$value = $this->decodeRecursive( $token, $depth );
			$token = $this->nextToken();
			if ( $token[0] === '$' ) {
				return $value;
			} elseif ( $token[0] === ']' || $token[0] === '}' ) {
				$this->lastError = JsonFallback::ERROR_STATE_MISMATCH;
			} else {
				$this->lastError = JsonFallback::ERROR_SYNTAX;
				return null;
			}

		} catch ( JsonFallbackError $e ) {
			return null;
		}
	}

	/**
	 * @param array $token Initial token to examine
	 * @param int $depth Maximum recursion depth
	 * @return mixed: The decoded value
	 */
	private function decodeRecursive( $token, $depth ) {
		if ( $token[0] === 's' ) {
			return $token[1];
		}

		if ( $depth < 2 ) {
			$this->error( JsonFallback::ERROR_DEPTH );
		}

		if ( $token[0] === '[' ) {
			$retval = array();
			$token = $this->nextToken();
			if ( $token[0] === ']' ) {
				// Special case, empty array
				return $retval;
			}

			while ( true ) {
				$retval[] = $this->decodeRecursive( $token, $depth - 1 );
				$token = $this->nextToken();
				if ( $token[0] === ',' ) {
					$token = $this->nextToken();
				} elseif ( $token[0] === ']' ) {
					return $retval;
				} elseif ( $token[0] === '}' ) {
					$this->error( JsonFallback::ERROR_STATE_MISMATCH );
				} else {
					$this->error( JsonFallback::ERROR_SYNTAX );
				}
			}

		} elseif ( $token[0] === '{' ) {
			$retval = $this->objectAsArray ? array() : new stdClass;
			$token = $this->nextToken();
			if ( $token[0] === '}' ) {
				// Special case, empty object
				return $retval;
			}

			while ( true ) {
				if ( $token[0] !== 's' || !is_string( $token[1] ) ) {
					$this->error( JsonFallback::ERROR_SYNTAX );
				}

				// NOTE: As in the native extension, object keys beginning with \u0000 can cause
				// fatal errors (PHP bug?), and duplicate keys are happily accepted here.
				if ( !$this->objectAsArray && $token[1] === '' ) {
					$key = '_empty_';
				} else {
					$key = $token[1];
				}

				$token = $this->nextToken();
				if ( $token[0] !== ':' ) {
					$this->error( JsonFallback::ERROR_SYNTAX );
				}

				$value = $this->decodeRecursive( $this->nextToken(), $depth - 1 );
				if ( $this->objectAsArray ) {
					$retval[$key] = $value;
				} else {
					$retval->$key = $value;
				}

				$token = $this->nextToken();
				if ( $token[0] === ',' ) {
					$token = $this->nextToken();
				} elseif ( $token[0] === '}' ) {
					return $retval;
				} elseif ( $token[0] === ']' ) {
					$this->error( JsonFallback::ERROR_STATE_MISMATCH );
				} else {
					$this->error( JsonFallback::ERROR_SYNTAX );
				}
			}

		} else {
			$this->error( JsonFallback::ERROR_SYNTAX );
		}
	}

	/**
	 * @return array: Token type at offset 0; for scalars, value at offset 1
	 */
	private function nextToken() {
		$regex = '! [\t\n\r ]*+ (?:
			( true ) | ( false ) | ( null ) | # simple keyword (1-3)

			(                                 # number (4)
				-? (?> 0 | [1-9][0-9]* )      #   integer part
				(?: \.[0-9]* )?+              #   fractional part (laxity: "1." is accepted)
				(?: [Ee] [+-]? [0-9]+ )?      #   exponent
			) |

			( " ) |                           # string (5)
			( [\[{\]}:,] ) |                  # structural character (6)
			( $ )                             # end of string (7)
		) !Ax';

		if ( !preg_match( $regex, $this->s, $matches, 0, $this->i ) ) {
			$this->error( JsonFallback::ERROR_SYNTAX );
		}

		$this->i += strlen( $matches[0] );

		// Determine token type and value using the technique described in
		// <http://nikic.github.io/2011/10/23/Improving-lexing-performance-in-PHP.html>
		$type = count( $matches ) - 1;
		switch ( $type ) {
			case 1:
				return array( 's', true );
			case 2:
				return array( 's', false );
			case 3:
				return array( 's', null );
			case 4:
				return array( 's', $this->decodeNumber( $matches[4] ) );
			case 5:
				return array( 's', $this->decodeString() );
			case 6:
				return array( $matches[6] );
			case 7:
				return array( '$' );
			default:
				// Should not be possible even for invalid input
				$this->error( JsonFallback::ERROR_SYNTAX );
		}
	}

	/**
	 * @param string $string
	 * @return int|float
	 */
	private function decodeNumber( $string ) {
		// Can't use the plus operator here; it changes negative zero to positive zero
		$number = $string - 0;

		if ( $this->bigIntAsString ) {
			// Intentionally disallow whitespace (for the lone scalar value parsing)
			if ( is_float( $number ) && ltrim( $string, '-0..9' ) === '' ) {
				$number = $string;
			}
		}

		return $number;
	}

	/**
	 * @return string
	 */
	private function decodeString() {
		$twoCharUnescaped = array(
			'\\' => '\\', '"' => '"', 'b' => "\x08", 'f' => "\f", 'n' => "\n", 'r' => "\r",
			't' => "\t", '/' => '/',
		);

		$buf = '';

		for ( $n = strlen( $this->s ); $this->i < $n; $this->i += $skip ) {
			// Unescaped double quote ends the string
			if ( $this->s[$this->i] === '"' ) {
				$this->i++;
				return $buf;
			}

			// Unescaped control characters, including CR and LF, are illegal
			if ( $this->s[$this->i] < ' ' ) {
				$this->error( JsonFallback::ERROR_SYNTAX );
			}

			// Decode other unescaped characters as-is
			if ( $this->s[$this->i] !== '\\' ) {
				$skip = strcspn( $this->s, '\"', $this->i );
				$unescaped = substr( $this->s, $this->i, $skip );
				if ( ltrim( $unescaped, " ..\xff" ) !== '' ) {
					$this->error( JsonFallback::ERROR_SYNTAX );
				}
				$buf .= $unescaped;
				continue;
			}

			// Handle two-character escape sequences using a hash lookup
			if ( $this->i + 1 >= $n ) {
				break;
			}
			if ( $this->s[$this->i + 1] !== 'u' ) {
				$key = $this->s[$this->i + 1];
				if ( !isset( $twoCharUnescaped[$key] ) ) {
					$this->error( JsonFallback::ERROR_SYNTAX );
				}
				$buf .= $twoCharUnescaped[$key];
				$skip = 2;
				continue;
			}

			// Handle Unicode escape sequences by hex-decoding the code units
			// and re-encoding using UTF-8
			if ( $this->i + 5 >= $n ) {
				break;
			}
			$hex = substr( $this->s, $this->i + 2, 4 );
			if ( ltrim( $hex, '0..9A..Fa..f' ) !== '' ) {
				$this->error( JsonFallback::ERROR_SYNTAX );
			}
			$code = hexdec( $hex );
			$skip = 6;

			// U+0000..U+007F (ASCII characters): equal to UTF-8 byte value
			if ( $code < 0x80 ) {
				$buf .= chr( $code );
				continue;
			}

			// U+0080..U+07FF: decode as two UTF-8 bytes
			if ( $code <= 0x07ff ) {
				$buf .= pack( 'C*', 0xc0 | $code >> 6, 0x80 | $code & 0x3f );
				continue;
			}

			// U+D800..U+DBFF when paired with U+DC00..U+DFFF: decode as four UTF-8 bytes
			if ( $code >= 0xd800 && $code <= 0xdbff && $this->i + 11 < $n
				&& $this->s[$this->i + 6] === '\\' && $this->s[$this->i + 7] === 'u'
			) {
				$hex = substr( $this->s, $this->i + 8, 4 );
				if ( ltrim( $hex, '0..9A..Fa..f' ) !== '' ) {
					$this->error( JsonFallback::ERROR_SYNTAX );
				}
				$low = hexdec( $hex );
				if ( $low >= 0xdc00 && $low <= 0xdfff ) {
					$code = 0x10000 + ( ( $code & 0x3ff ) << 10 | $low & 0x3ff );
					$buf .= pack( 'C*', 0xf0 | $code >> 18, 0x80 | ( $code >> 12 ) & 0x3f,
						0x80 | ( $code >> 6 ) & 0x3f, 0x80 | $code & 0x3f );
					$skip = 12;
					continue;
				}
			}

			// Everything else in U+0800..U+FFFF: decode as three UTF-8 bytes
			// NOTE: This includes unpaired surrogates (U+D800..U+DFFF), which should never
			// appear in Unicode text yet are decoded by the native JSON extension anyway
			// <https://bugs.php.net/bug.php?id=41067#1176758113> (separate PHP bug?)
			$buf .= pack( 'C*', 0xe0 | $code >> 12, 0x80 | ( $code >> 6 ) & 0x3f,
				0x80 | $code & 0x3f );
		}

		// Unexpected end of input string
		$this->error( JsonFallback::ERROR_SYNTAX );
	}

	/**
	 * @param int $code: One of the JsonFallback::ERROR_* constants
	 * @throws JsonFallbackError
	 */
	private function error( $code ) {
		$this->lastError = $code;
		throw new JsonFallbackError;
	}

}

/**
 * Exception thrown when a value cannot be processed; should never propagate to user code.
 * Necessary because the library must not catch exceptions thrown by jsonSerialize functions.
 *
 * @private
 */
class JsonFallbackError extends Exception {
}

/**
 * Hack to temporarily disable xdebug's protection against infinite recursion. This is to
 * prevent fatal errors when parsing deeply nested data structures, especially because xdebug
 * defaults to a rather low setting.
 *
 * @private
 */
class JsonFallbackXdebugHack {

	private $oldValue;

	public function __construct() {
		$this->oldValue = ini_get( 'xdebug.max_nesting_level' );
		if ( (int)$this->oldValue > 0 ) {
			ini_set( 'xdebug.max_nesting_level', 0 );
		}
	}

	public function __destruct() {
		if ( (int)$this->oldValue > 0 ) {
			ini_set( 'xdebug.max_nesting_level', $this->oldValue );
		}
	}

}

define( 'JSONFALLBACK_POLYFILL', !function_exists( 'json_encode' ) );
define( 'JSONFALLBACK_SERIALIZABLE', !interface_exists( 'JsonSerializable', false ) );

if ( JSONFALLBACK_POLYFILL ) {
	JsonFallback::addGlobals();
}

if ( JSONFALLBACK_SERIALIZABLE ) {
	interface JsonSerializable {
		public function jsonSerialize();
	}
}
