<?php
/**
 * Simple wrapper for json_encode and json_decode.
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
	 * Skip escaping the '/' character, commonly known as the forward slash or solidus.
	 * This can improve human readability of URLs and similar strings.
	 *
	 * @since 1.21
	 */
	const SLASH_OK = 1;

	/**
	 * Skip escaping most characters above U+007F for readability and compactness.
	 * This encoding option saves 4 to 8 bytes (uncompressed) for each such character;
	 * however, it could break compatibility with systems that incorrectly handle UTF-8.
	 *
	 * @since 1.21
	 */
	const UTF8_OK = 2;

	/**
	 * Skip escaping the characters '<', '>', and '&', which have special meanings in
	 * HTML and XML.
	 *
	 * @warning Do not use this option for JSON that could end up in inline scripts.
	 * - HTML5, §4.3.1.2 Restrictions for contents of script elements
	 * - XML 1.0 (5th Ed.), §2.4 Character Data and Markup
	 *
	 * @note This was the behavior of MediaWiki 1.20 and below.
	 *
	 * @since 1.21
	 */
	const XMLMETA_OK = 4;

	/**
	 * Skip escaping as many characters as reasonably possible.
	 *
	 * @warning When generating inline script blocks, use FormatJson::XMLMETA_FORBIDDEN instead.
	 *
	 * @since 1.21
	 */
	const ALL_OK = 7;

	/**
	 * Equivalent to FormatJson::ALL_OK & ~FormatJson::XMLMETA_OK.
	 *
	 * @since 1.21
	 */
	const XMLMETA_FORBIDDEN = 3;

	/**
	 * Returns the JSON representation of a value.
	 *
	 * @note Empty arrays are encoded as numeric arrays, not as objects, so cast any associative
	 *       array that might be empty to an object before encoding it.
	 *
	 * @param mixed $value The value to encode. Can be any type except a resource.
	 * @param bool $pretty If true, add non-significant whitespace to improve readability.
	 * @param int $escaping Bitfield consisting of _OK and/or _FORBIDDEN constants
	 * @return string|null: String if successful; null upon failure
	 */
	public static function encode( $value, $pretty = false, $escaping = 0 ) {
		if ( version_compare( PHP_VERSION, '5.4.0', '<' ) ) {
			return self::encode53( $value, $pretty, $escaping );
		}
		$options = $pretty ? JSON_PRETTY_PRINT : 0;
		$options |= ( $escaping & self::SLASH_OK ) ? JSON_UNESCAPED_SLASHES : 0;
		$options |= ( $escaping & self::UTF8_OK ) ? JSON_UNESCAPED_UNICODE : 0;
		$options |= ( $escaping & self::XMLMETA_OK ) ? 0 : ( JSON_HEX_TAG | JSON_HEX_AMP );
		$json = json_encode( $value, $options );
		if ( $json === null ) {
			return null;
		}
		return ( $escaping & self::UTF8_OK ) ? strtr( $json, self::$badChars ) : $json;
	}

	/**
	 * Decodes a JSON string.
	 *
	 * @param string $value The JSON string being decoded
	 * @param bool $assoc When true, returned objects will be converted into associative arrays.
	 *
	 * @return mixed: the value encoded in JSON in appropriate PHP type.
	 * Values `"true"`, `"false"`, and `"null"` (case-insensitive) are returned as `true`, `false`
	 * and `null` respectively. `null` is returned if the JSON cannot be
	 * decoded or if the encoded data is deeper than the recursion limit.
	 */
	public static function decode( $value, $assoc = false ) {
		return json_decode( $value, $assoc );
	}

	/**
	 * Characters problematic in JavaScript and their corresponding escape sequences.
	 *
	 * @note These are listed in ECMA-262 (5.1 Ed.), §7.3 Line Terminators along with U+000A (LF)
	 *       and U+000D (CR). However, PHP already escapes LF and CR according to RFC 4627.
	 */
	private static $badChars = array(
		"\xe2\x80\xa8" => '\u2028', // LINE SEPARATOR
		"\xe2\x80\xa9" => '\u2029', // PARAGRAPH SEPARATOR
	);

	/**
	 * Fallback encoding function called if FormatJson::encode() detects PHP < 5.4.
	 *
	 * @param mixed $value
	 * @param bool $pretty
	 * @param int $escaping
	 * @return string|null
	 */
	private static function encode53( $value, $pretty, $escaping ) {
		$options = ( $escaping & self::XMLMETA_OK ) ? 0 : ( JSON_HEX_TAG | JSON_HEX_AMP );
		$json = json_encode( $value, $options );
		if ( $json === null ) {
			return null;
		}
		if ( $escaping & self::SLASH_OK ) {
			$json = str_replace( '\\/', '/', $json );
		}
		if ( $escaping & self::UTF8_OK ) {
			$doubled = str_replace( "\\\\\\\\", "\\\\\\u005c", json_encode( $json ) );
			$json = json_decode( preg_replace( "/\\\\\\\\u(?!00[0-7])/", "\\\\u", $doubled ) );
			$json = strtr( $json, self::$badChars );
		}
		return $pretty ? self::prettyPrint( $json ) : $json;
	}

	/**
	 * Adds non-significant whitespace to an existing JSON representation of an object.
	 * Only needed for PHP < 5.4, which lacks the JSON_PRETTY_PRINT option.
	 *
	 * @param string $json
	 * @return string
	 */
	private static function prettyPrint( $json ) {
		$buf = '';
		$indent = 0;
		$json = str_replace( '\"', "\x01", $json );
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
}
