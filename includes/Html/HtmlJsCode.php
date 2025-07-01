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

namespace MediaWiki\Html;

/**
 * A wrapper class which causes Html::encodeJsVar() and Html::encodeJsCall()
 * (as well as their Xml::* counterparts) to interpret a given string as being
 * a JavaScript expression, instead of string data.
 *
 * @par Example:
 * @code
 *     Html::encodeJsVar( new HtmlJsCode( 'a + b' ) );
 * @endcode
 *
 * This returns "a + b".
 *
 * @note As of 1.21, HtmlJsCode objects cannot be nested inside objects or arrays. The sole
 *       exception is the $args argument to Html::encodeJsCall() because Html::encodeJsVar() is
 *       called for each individual element in that array. If you need to encode an object or array
 *       containing HtmlJsCode objects, use HtmlJsCode::encodeObject() to re-encode it first.
 *
 * @since 1.41 (renamed from XmlJsCode, which existed since 1.17)
 */
class HtmlJsCode {
	public string $value;

	public function __construct( string $value ) {
		$this->value = $value;
	}

	/**
	 * Encode an object containing HtmlJsCode objects.
	 *
	 * This takes an object or associative array where (some of) the values are HtmlJsCode objects,
	 * and re-encodes it as a single HtmlJsCode object.
	 *
	 * @since 1.33
	 * @phpcs:ignore MediaWiki.Commenting.FunctionComment.ObjectTypeHintParam
	 * @param object|array $obj Object or associative array to encode
	 * @param bool $pretty If true, add non-significant whitespace to improve readability.
	 * @return HtmlJsCode
	 */
	public static function encodeObject( $obj, $pretty = false ) {
		$parts = [];
		foreach ( $obj as $key => $value ) {
			$parts[] =
				( $pretty ? '    ' : '' ) .
				Html::encodeJsVar( $key, $pretty ) .
				( $pretty ? ': ' : ':' ) .
				Html::encodeJsVar( $value, $pretty );
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

/** @deprecated class alias since 1.41 */
class_alias( HtmlJsCode::class, 'XmlJsCode' );
