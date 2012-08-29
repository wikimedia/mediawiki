<?php
/**
 * Simple wrapper for json_econde and json_decode that falls back on Services_JSON class.
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

require_once __DIR__ . '/Services_JSON.php';

/**
 * JSON formatter wrapper class
 */
class FormatJson {

	/**
	 * Returns the JSON representation of a value.
	 *
	 * @param $value Mixed: the value being encoded. Can be any type except a resource.
	 * @param $isHtml Boolean
	 *
	 * @todo FIXME: "$isHtml" parameter's purpose is not documented. It appears to
	 *        map to a parameter labeled "pretty-print output with indents and
	 *        newlines" in Services_JSON::encode(), which has no string relation
	 *        to HTML output.
	 *
	 * @return string
	 */
	public static function encode( $value, $isHtml = false ) {
		if ( !function_exists( 'json_encode' ) || ( $isHtml && version_compare( PHP_VERSION, '5.4.0', '<' ) ) ) {
			$json = new Services_JSON();
			return $json->encode( $value, $isHtml );
		} else {
			return json_encode( $value, $isHtml ? JSON_PRETTY_PRINT : 0 );
		}
	}

	/**
	 * Decodes a JSON string.
	 *
	 * @param $value String: the json string being decoded.
	 * @param $assoc Boolean: when true, returned objects will be converted into associative arrays.
	 *
	 * @return Mixed: the value encoded in json in appropriate PHP type.
	 * Values true, false and null (case-insensitive) are returned as true, false
	 * and "&null;" respectively. "&null;" is returned if the json cannot be
	 * decoded or if the encoded data is deeper than the recursion limit.
	 */
	public static function decode( $value, $assoc = false ) {
		if ( !function_exists( 'json_decode' ) ) {
			$json = $assoc ? new Services_JSON( SERVICES_JSON_LOOSE_TYPE ) :
				new Services_JSON();
			$jsonDec = $json->decode( $value );
			return $jsonDec;
		} else {
			return json_decode( $value, $assoc );
		}
	}

}
