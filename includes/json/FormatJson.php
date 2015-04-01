<?php
/**
 * Wrapper for json_encode and json_decode.
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
 * JSON formatter wrapper class
 */
class FormatJson {
	/**
	 * Skip escaping most characters above U+007F for readability and compactness.
	 * This encoding option saves 3 to 8 bytes (uncompressed) for each such character;
	 * however, it could break compatibility with systems that incorrectly handle UTF-8.
	 *
	 * @since 1.22
	 */
	const UTF8_OK = 1;

	/**
	 * Skip escaping the characters '<', '>', and '&', which have special meanings in
	 * HTML and XML.
	 *
	 * @warning Do not use this option for JSON that could end up in inline scripts.
	 * - HTML5, §4.3.1.2 Restrictions for contents of script elements
	 * - XML 1.0 (5th Ed.), §2.4 Character Data and Markup
	 *
	 * @since 1.22
	 */
	const XMLMETA_OK = 2;

	/**
	 * Skip escaping as many characters as reasonably possible.
	 *
	 * @warning When generating inline script blocks, use FormatJson::UTF8_OK instead.
	 *
	 * @since 1.22
	 */
	const ALL_OK = 3;

	/**
	 * If set, treat json objects '{...}' as associative arrays. Without this option,
	 * json objects will be converted to stdClass.
	 * The value is set to 1 to be backward compatible with 'true' that was used before.
	 *
	 * @since 1.24
	 */
	const FORCE_ASSOC = 0x100;

	/**
	 * If set, attempts to fix invalid json.
	 *
	 * @since 1.24
	 */
	const TRY_FIXING = 0x200;

	/**
	 * If set, strip comments from input before parsing as JSON.
	 *
	 * @since 1.25
	 */
	const STRIP_COMMENTS = 0x400;

	/**
	 * Regex that matches whitespace inside empty arrays and objects.
	 *
	 * This doesn't affect regular strings inside the JSON because those can't
	 * have a real line break (\n) in them, at this point they are already escaped
	 * as the string "\n" which this doesn't match.
	 *
	 * @private
	 */
	const WS_CLEANUP_REGEX = '/(?<=[\[{])\n\s*+(?=[\]}])/';

	/**
	 * Characters problematic in JavaScript.
	 *
	 * @note These are listed in ECMA-262 (5.1 Ed.), §7.3 Line Terminators along with U+000A (LF)
	 *       and U+000D (CR). However, PHP already escapes LF and CR according to RFC 4627.
	 */
	private static $badChars = array(
		"\xe2\x80\xa8", // U+2028 LINE SEPARATOR
		"\xe2\x80\xa9", // U+2029 PARAGRAPH SEPARATOR
	);

	/**
	 * Escape sequences for characters listed in FormatJson::$badChars.
	 */
	private static $badCharsEscaped = array(
		'\u2028', // U+2028 LINE SEPARATOR
		'\u2029', // U+2029 PARAGRAPH SEPARATOR
	);

	/**
	 * Returns the JSON representation of a value.
	 *
	 * @note Empty arrays are encoded as numeric arrays, not as objects, so cast any associative
	 *       array that might be empty to an object before encoding it.
	 *
	 * @note In pre-1.22 versions of MediaWiki, using this function for generating inline script
	 *       blocks may result in an XSS vulnerability, and quite likely will in XML documents
	 *       (cf. FormatJson::XMLMETA_OK). Use Xml::encodeJsVar() instead in such cases.
	 *
	 * @param mixed $value The value to encode. Can be any type except a resource.
	 * @param string|bool $pretty If a string, add non-significant whitespace to improve
	 *   readability, using that string for indentation. If true, use the default indent
	 *   string (four spaces).
	 * @param int $escaping Bitfield consisting of _OK class constants
	 * @return string|false String if successful; false upon failure
	 */
	public static function encode( $value, $pretty = false, $escaping = 0 ) {
		if ( !is_string( $pretty ) ) {
			$pretty = $pretty ? '    ' : false;
		}

		if ( defined( 'JSON_UNESCAPED_UNICODE' ) ) {
			return self::encode54( $value, $pretty, $escaping );
		}

		return self::encode53( $value, $pretty, $escaping );
	}

	/**
	 * Decodes a JSON string. It is recommended to use FormatJson::parse(),
	 * which returns more comprehensive result in case of an error, and has
	 * more parsing options.
	 *
	 * @param string $value The JSON string being decoded
	 * @param bool $assoc When true, returned objects will be converted into associative arrays.
	 *
	 * @return mixed The value encoded in JSON in appropriate PHP type.
	 * `null` is returned if $value represented `null`, if $value could not be decoded,
	 * or if the encoded data was deeper than the recursion limit.
	 * Use FormatJson::parse() to distinguish between types of `null` and to get proper error code.
	 */
	public static function decode( $value, $assoc = false ) {
		return json_decode( $value, $assoc );
	}

	/**
	 * Decodes a JSON string.
	 * Unlike FormatJson::decode(), if $value represents null value, it will be
	 * properly decoded as valid.
	 *
	 * @param string $value The JSON string being decoded
	 * @param int $options A bit field that allows FORCE_ASSOC, TRY_FIXING,
	 * STRIP_COMMENTS
	 * @return Status If valid JSON, the value is available in $result->getValue()
	 */
	public static function parse( $value, $options = 0 ) {
		if ( $options & self::STRIP_COMMENTS ) {
			$value = self::stripComments( $value );
		}
		$assoc = ( $options & self::FORCE_ASSOC ) !== 0;
		$result = json_decode( $value, $assoc );
		$code = json_last_error();

		if ( $code === JSON_ERROR_SYNTAX && ( $options & self::TRY_FIXING ) !== 0 ) {
			// The most common error is the trailing comma in a list or an object.
			// We cannot simply replace /,\s*[}\]]/ because it could be inside a string value.
			// But we could use the fact that JSON does not allow multi-line string values,
			// And remove trailing commas if they are et the end of a line.
			// JSON only allows 4 control characters: [ \t\r\n].  So we must not use '\s' for matching.
			// Regex match   ,]<any non-quote chars>\n   or   ,\n]   with optional spaces/tabs.
			$count = 0;
			$value =
				preg_replace( '/,([ \t]*[}\]][^"\r\n]*([\r\n]|$)|[ \t]*[\r\n][ \t\r\n]*[}\]])/', '$1',
					$value, - 1, $count );
			if ( $count > 0 ) {
				$result = json_decode( $value, $assoc );
				if ( JSON_ERROR_NONE === json_last_error() ) {
					// Report warning
					$st = Status::newGood( $result );
					$st->warning( wfMessage( 'json-warn-trailing-comma' )->numParams( $count ) );
					return $st;
				}
			}
		}

		switch ( $code ) {
			case JSON_ERROR_NONE:
				return Status::newGood( $result );
			default:
				return Status::newFatal( wfMessage( 'json-error-unknown' )->numParams( $code ) );
			case JSON_ERROR_DEPTH:
				$msg = 'json-error-depth';
				break;
			case JSON_ERROR_STATE_MISMATCH:
				$msg = 'json-error-state-mismatch';
				break;
			case JSON_ERROR_CTRL_CHAR:
				$msg = 'json-error-ctrl-char';
				break;
			case JSON_ERROR_SYNTAX:
				$msg = 'json-error-syntax';
				break;
			case JSON_ERROR_UTF8:
				$msg = 'json-error-utf8';
				break;
			case JSON_ERROR_RECURSION:
				$msg = 'json-error-recursion';
				break;
			case JSON_ERROR_INF_OR_NAN:
				$msg = 'json-error-inf-or-nan';
				break;
			case JSON_ERROR_UNSUPPORTED_TYPE:
				$msg = 'json-error-unsupported-type';
				break;
		}
		return Status::newFatal( $msg );
	}

	/**
	 * JSON encoder wrapper for PHP >= 5.4, which supports useful encoding options.
	 *
	 * @param mixed $value
	 * @param string|bool $pretty
	 * @param int $escaping
	 * @return string|false
	 */
	private static function encode54( $value, $pretty, $escaping ) {
		static $bug66021;
		if ( $pretty !== false && $bug66021 === null ) {
			$bug66021 = json_encode( array(), JSON_PRETTY_PRINT ) !== '[]';
		}

		// PHP escapes '/' to prevent breaking out of inline script blocks using '</script>',
		// which is hardly useful when '<' and '>' are escaped (and inadequate), and such
		// escaping negatively impacts the human readability of URLs and similar strings.
		$options = JSON_UNESCAPED_SLASHES;
		$options |= $pretty !== false ? JSON_PRETTY_PRINT : 0;
		$options |= ( $escaping & self::UTF8_OK ) ? JSON_UNESCAPED_UNICODE : 0;
		$options |= ( $escaping & self::XMLMETA_OK ) ? 0 : ( JSON_HEX_TAG | JSON_HEX_AMP );
		$json = json_encode( $value, $options );
		if ( $json === false ) {
			return false;
		}

		if ( $pretty !== false ) {
			// Workaround for <https://bugs.php.net/bug.php?id=66021>
			if ( $bug66021 ) {
				$json = preg_replace( self::WS_CLEANUP_REGEX, '', $json );
			}
			if ( $pretty !== '    ' ) {
				// Change the four-space indent to a tab indent
				$json = str_replace( "\n    ", "\n\t", $json );
				while ( strpos( $json, "\t    " ) !== false ) {
					$json = str_replace( "\t    ", "\t\t", $json );
				}

				if ( $pretty !== "\t" ) {
					// Change the tab indent to the provided indent
					$json = str_replace( "\t", $pretty, $json );
				}
			}
		}
		if ( $escaping & self::UTF8_OK ) {
			$json = str_replace( self::$badChars, self::$badCharsEscaped, $json );
		}

		return $json;
	}

	/**
	 * JSON encoder wrapper for PHP 5.3, which lacks native support for some encoding options.
	 * Therefore, the missing options are implemented here purely in PHP code.
	 *
	 * @param mixed $value
	 * @param string|bool $pretty
	 * @param int $escaping
	 * @return string|false
	 */
	private static function encode53( $value, $pretty, $escaping ) {
		$options = ( $escaping & self::XMLMETA_OK ) ? 0 : ( JSON_HEX_TAG | JSON_HEX_AMP );
		$json = json_encode( $value, $options );
		if ( $json === false ) {
			return false;
		}

		// Emulate JSON_UNESCAPED_SLASHES. Because the JSON contains no unescaped slashes
		// (only escaped slashes), a simple string replacement works fine.
		$json = str_replace( '\/', '/', $json );

		if ( $escaping & self::UTF8_OK ) {
			// JSON hex escape sequences follow the format \uDDDD, where DDDD is four hex digits
			// indicating the equivalent UTF-16 code unit's value. To most efficiently unescape
			// them, we exploit the JSON extension's built-in decoder.
			// * We escape the input a second time, so any such sequence becomes \\uDDDD.
			// * To avoid interpreting escape sequences that were in the original input,
			//   each double-escaped backslash (\\\\) is replaced with \\\u005c.
			// * We strip one of the backslashes from each of the escape sequences to unescape.
			// * Then the JSON decoder can perform the actual unescaping.
			$json = str_replace( "\\\\\\\\", "\\\\\\u005c", addcslashes( $json, '\"' ) );
			$json = json_decode( preg_replace( "/\\\\\\\\u(?!00[0-7])/", "\\\\u", "\"$json\"" ) );
			$json = str_replace( self::$badChars, self::$badCharsEscaped, $json );
		}

		if ( $pretty !== false ) {
			return self::prettyPrint( $json, $pretty );
		}

		return $json;
	}

	/**
	 * Adds non-significant whitespace to an existing JSON representation of an object.
	 * Only needed for PHP < 5.4, which lacks the JSON_PRETTY_PRINT option.
	 *
	 * @param string $json
	 * @param string $indentString
	 * @return string
	 */
	private static function prettyPrint( $json, $indentString ) {
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
					++$indent;
					// falls through
				case ',':
					$buf .= $json[$i] . "\n" . str_repeat( $indentString, $indent );
					break;
				case ']':
				case '}':
					$buf .= "\n" . str_repeat( $indentString, --$indent ) . $json[$i];
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
		$buf = preg_replace( self::WS_CLEANUP_REGEX, '', $buf );

		return str_replace( "\x01", '\"', $buf );
	}

	/**
	 * Remove multiline and single line comments from an otherwise valid JSON
	 * input string. This can be used as a preprocessor for to allow JSON
	 * formatted configuration files to contain comments.
	 *
	 * @param string $json
	 * @return string JSON with comments removed
	 */
	public static function stripComments( $json ) {
		// Ensure we have a string
		$str = (string) $json;
		$buffer = '';
		$maxLen = strlen( $str );
		$mark = 0;

		$inString = false;
		$inComment = false;
		$multiline = false;

		for ($idx = 0; $idx < $maxLen; $idx++) {
			switch ( $str[$idx] ) {
				case '"':
					$lookBehind = ( $idx - 1 >= 0 ) ? $str[$idx - 1] : '';
					if ( !$inComment && $lookBehind !== '\\' ) {
						// Either started or ended a string
						$inString = !$inString;
					}
					break;

				case '/':
					$lookAhead = ( $idx + 1 < $maxLen ) ? $str[$idx + 1] : '';
					$lookBehind = ( $idx - 1 >= 0 ) ? $str[$idx - 1] : '';
					if ( $inString ) {
						continue;

					} elseif ( !$inComment &&
						( $lookAhead === '/' || $lookAhead === '*' )
					) {
						// Transition into a comment
						// Add characters seen to buffer
						$buffer .= substr( $str, $mark, $idx - $mark );
						// Consume the look ahead character
						$idx++;
						// Track state
						$inComment = true;
						$multiline = $lookAhead === '*';

					} elseif ( $multiline && $lookBehind === '*' ) {
						// Found the end of the current comment
						$mark = $idx + 1;
						$inComment = false;
						$multiline = false;
					}
					break;

				case "\n":
					if ( $inComment && !$multiline ) {
						// Found the end of the current comment
						$mark = $idx + 1;
						$inComment = false;
					}
					break;
			}
		}
		if ( $inComment ) {
			// Comment ends with input
			// Technically we should check to ensure that we aren't in
			// a multiline comment that hasn't been properly ended, but this
			// is a strip filter, not a validating parser.
			$mark = $maxLen;
		}
		// Add final chunk to buffer before returning
		return $buffer . substr( $str, $mark, $maxLen - $mark );
	}
}
