<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Type definition for integer types
 *
 * A valid representation consists of an optional sign (`+` or `-`) followed by
 * one or more decimal digits.
 *
 * The result from validate() is a PHP integer.
 *
 * * Failure codes:
 *  - 'badinteger': The value was invalid or could not be represented as a PHP
 *    integer. No data.
 *
 * @since 1.34
 * @unstable
 */
class IntegerDef extends NumericDef {

	public function validate( $name, $value, array $settings, array $options ) {
		if ( !preg_match( '/^[+-]?\d+$/D', $value ) ) {
			$this->failure( 'badinteger', $name, $value, $settings, $options );
		}
		$ret = intval( $value, 10 );

		// intval() returns min/max on overflow, so check that
		if ( $ret === PHP_INT_MAX || $ret === PHP_INT_MIN ) {
			$tmp = ( $ret < 0 ? '-' : '' ) . ltrim( $value, '-0' );
			if ( $tmp !== (string)$ret ) {
				$this->failure( 'badinteger', $name, $value, $settings, $options );
			}
		}

		return $this->checkRange( $ret, $name, $value, $settings, $options );
	}

	public function getHelpInfo( $name, array $settings, array $options ) {
		$info = parent::getHelpInfo( $name, $settings, $options );

		$info[ParamValidator::PARAM_TYPE] = MessageValue::new( 'paramvalidator-help-type-integer' )
			->params( empty( $settings[ParamValidator::PARAM_ISMULTI] ) ? 1 : 2 );

		return $info;
	}

}
