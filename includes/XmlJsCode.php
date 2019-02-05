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

/**
 * A wrapper class which causes Xml::encodeJsVar() and Xml::encodeJsCall() to
 * interpret a given string as being a JavaScript expression, instead of string
 * data.
 *
 * @par Example:
 * @code
 *     Xml::encodeJsVar( new XmlJsCode( 'a + b' ) );
 * @endcode
 *
 * This returns "a + b".
 *
 * @note As of 1.21, XmlJsCode objects cannot be nested inside objects or arrays. The sole
 *       exception is the $args argument to Xml::encodeJsCall() because Xml::encodeJsVar() is
 *       called for each individual element in that array. If you need to encode an object or array
 *       containing XmlJsCode objects, use XmlJsCode::encodeObject() to re-encode it first.
 *
 * @since 1.17
 */
class XmlJsCode {
	public $value;

	function __construct( $value ) {
		$this->value = $value;
	}

	/**
	 * Encode an object containing XmlJsCode objects.
	 *
	 * This takes an object or associative array where (some of) the values are XmlJsCode objects,
	 * and re-encodes it as a single XmlJsCode object.
	 *
	 * @since 1.33
	 * @param object|array $obj Object or associative array to encode
	 * @param bool $pretty If true, add non-significant whitespace to improve readability.
	 * @return XmlJsCode
	 */
	public static function encodeObject( $obj, $pretty = false ) {
		$parts = [];
		foreach ( $obj as $key => $value ) {
			$parts[] =
				( $pretty ? '    ' : '' ) .
				Xml::encodeJsVar( $key, $pretty ) .
				( $pretty ? ': ' : ':' ) .
				Xml::encodeJsVar( $value, $pretty );
		}
		return new self(
			'{' .
			( $pretty ? "\n" : '' ) .
			implode( $pretty ? ",\n" : ',', $parts ) .
			( $pretty ? "\n" : '' ) .
			'}'
		);
	}
}
