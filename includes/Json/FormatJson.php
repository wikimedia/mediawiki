<?php
/**
 * Wrapper for json_encode and json_decode.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Json;

use MediaWiki\Status\Status;

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
	public const UTF8_OK = 1;

	/**
	 * Skip escaping the characters '<', '>', and '&', which have special meanings in
	 * HTML and XML.
	 *
	 * @warning Do not use this option for JSON that could end up in inline scripts.
	 * - HTML 5.2, §4.12.1.3 Restrictions for contents of script elements
	 * - XML 1.0 (5th Ed.), §2.4 Character Data and Markup
	 *
	 * @since 1.22
	 */
	public const XMLMETA_OK = 2;

	/**
	 * Skip escaping as many characters as reasonably possible.
	 *
	 * @warning When generating inline script blocks, use FormatJson::UTF8_OK instead.
	 *
	 * @since 1.22
	 */
	public const ALL_OK = self::UTF8_OK | self::XMLMETA_OK;

	/**
	 * If set, treat JSON objects '{...}' as associative arrays. Without this option,
	 * JSON objects will be converted to stdClass.
	 *
	 * @since 1.24
	 */
	public const FORCE_ASSOC = 0x100;

	/**
	 * If set, attempt to fix invalid JSON.
	 *
	 * @since 1.24
	 */
	public const TRY_FIXING = 0x200;

	/**
	 * If set, strip comments from input before parsing as JSON.
	 *
	 * @since 1.25
	 */
	public const STRIP_COMMENTS = 0x400;

	/**
	 * Returns the JSON representation of a value.
	 *
	 * @note Empty arrays are encoded as numeric arrays, not as objects, so cast any associative
	 *       array that might be empty to an object before encoding it.
	 *
	 * @param mixed $value The value to encode. Can be any type except a resource.
	 * @param string|bool $pretty If a string, add non-significant whitespace to improve
	 *   readability, using that string for indentation (must consist only of whitespace
	 *   characters). If true, use the default indent string (four spaces).
	 * @param int $escaping Bitfield consisting of _OK class constants
	 * @return string|false String if successful; false upon failure
	 */
	public static function encode( $value, $pretty = false, $escaping = 0 ) {
		// PHP escapes '/' to prevent breaking out of inline script blocks using '</script>',
		// which is hardly useful when '<' and '>' are escaped (and inadequate), and such
		// escaping negatively impacts the human readability of URLs and similar strings.
		$options = JSON_UNESCAPED_SLASHES;
		if ( $pretty || is_string( $pretty ) ) {
			$options |= JSON_PRETTY_PRINT;
		}
		if ( $escaping & self::UTF8_OK ) {
			$options |= JSON_UNESCAPED_UNICODE;
		}
		if ( !( $escaping & self::XMLMETA_OK ) ) {
			$options |= JSON_HEX_TAG | JSON_HEX_AMP;
		}
		$json = json_encode( $value, $options );

		if ( is_string( $pretty ) && $pretty !== '    ' && $json !== false ) {
			// Change the four-space indent to the provided indent.
			// The regex matches four spaces either at the start of a line or immediately
			// after the previous match. $pretty should contain only whitespace characters,
			// so there should be no need to call StringUtils::escapeRegexReplacement().
			$json = preg_replace( '/ {4}|.*+\n\K {4}/A', $pretty, $json );
		}

		return $json;
	}

	/**
	 * Decodes a JSON string. It is recommended to use FormatJson::parse(),
	 * which returns more comprehensive result in case of an error, and has
	 * more parsing options.
	 *
	 * In PHP versions before 7.1, decoding a JSON string containing an empty key
	 * without passing $assoc as true results in a return object with a property
	 * named "_empty_" (because true empty properties were not supported pre-PHP-7.1).
	 * Instead, consider passing $assoc as true to return an associative array.
	 *
	 * But be aware that in all supported PHP versions, decoding an empty JSON object
	 * with $assoc = true returns an array, not an object, breaking round-trip consistency.
	 *
	 * See https://phabricator.wikimedia.org/T206411 for more details on these quirks.
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
					$value, -1, $count );
			if ( $count > 0 ) {
				$result = json_decode( $value, $assoc );
				if ( json_last_error() === JSON_ERROR_NONE ) {
					// Report warning
					$st = Status::newGood( $result );
					$st->warning( wfMessage( 'json-warn-trailing-comma' )->numParams( $count ) );
					return $st;
				}
			}
		}

		// JSON_ERROR_RECURSION, JSON_ERROR_INF_OR_NAN, JSON_ERROR_UNSUPPORTED_TYPE,
		// are all encode errors that we don't need to care about here.
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
			case JSON_ERROR_INVALID_PROPERTY_NAME:
				$msg = 'json-error-invalid-property-name';
				break;
			case JSON_ERROR_UTF16:
				$msg = 'json-error-utf16';
				break;
		}
		return Status::newFatal( $msg );
	}

	/**
	 * Remove multiline and single line comments from an otherwise valid JSON
	 * input string. This can be used as a preprocessor, to allow JSON
	 * formatted configuration files to contain comments.
	 *
	 * @param string $json
	 * @return string JSON with comments removed
	 */
	public static function stripComments( $json ) {
		// Ensure we have a string
		$str = (string)$json;
		$buffer = '';
		$maxLen = strlen( $str );
		$mark = 0;

		$inString = false;
		$inComment = false;
		$multiline = false;

		for ( $idx = 0; $idx < $maxLen; $idx++ ) {
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
						break;

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
/** @deprecated class alias since 1.43 */
class_alias( FormatJson::class, 'FormatJson' );
