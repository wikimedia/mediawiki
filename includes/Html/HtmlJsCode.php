<?php
/**
 * @license GPL-2.0-or-later
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
