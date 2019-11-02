<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\TypeDef;
use Wikimedia\ParamValidator\ValidationException;

/**
 * Type definition for boolean types
 *
 * This type accepts certain defined strings to mean 'true' or 'false'.
 * The result from validate() is a PHP boolean.
 *
 * ValidationException codes:
 *  - 'badbool': The value is not a recognized boolean. Data:
 *     - 'truevals': List of recognized values for "true".
 *     - 'falsevals': List of recognized values for "false".
 *
 * @since 1.34
 * @unstable
 */
class BooleanDef extends TypeDef {

	public static $TRUEVALS = [ 'true', 't', 'yes', 'y', 'on', '1' ];
	public static $FALSEVALS = [ 'false', 'f', 'no', 'n', 'off', '0' ];

	public function validate( $name, $value, array $settings, array $options ) {
		$value = strtolower( $value );
		if ( in_array( $value, self::$TRUEVALS, true ) ) {
			return true;
		}
		if ( $value === '' || in_array( $value, self::$FALSEVALS, true ) ) {
			return false;
		}

		throw new ValidationException( $name, $value, $settings, 'badbool', [
			'truevals' => self::$TRUEVALS,
			'falsevals' => array_merge( self::$FALSEVALS, [ 'the empty string' ] ),
		] );
	}

	public function stringifyValue( $name, $value, array $settings, array $options ) {
		return $value ? self::$TRUEVALS[0] : self::$FALSEVALS[0];
	}

}
