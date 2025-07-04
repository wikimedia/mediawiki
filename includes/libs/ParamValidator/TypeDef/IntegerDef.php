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

	/** @inheritDoc */
	public function validate( $name, $value, array $settings, array $options ) {
		if ( is_int( $value ) ) {
			$ret = $value;
		} elseif ( is_float( $value ) ) {
			// Since JSON does not distinguish between integers and floats,
			// floats without a fractional parts can be treated as integers.
			// This is in line with the definition of the "integer" type in
			// JSON Schema, see https://json-schema.org/understanding-json-schema/reference/numeric.
			$ret = intval( $value );
			if ( $ret - $value !== 0.0 ) {
				$this->fatal(
					$this->failureMessage( 'badinteger-fraction' )
						->params( gettype( $value ) ),
					$name, $value, $settings, $options
				);
			}
		} elseif ( $options[ self::OPT_ENFORCE_JSON_TYPES ] ?? false ) {
			$this->fatal(
				$this->failureMessage( 'badinteger-type' )
					->params( gettype( $value ) ),
				$name, $value, $settings, $options
			);
		} else {
			if ( is_array( $value ) || !preg_match( '/^[+-]?\d+$/D', $value ) ) {
				$this->fatal( 'badinteger', $name, $value, $settings, $options );
			} else {
				$ret = intval( $value, 10 );
			}
		}

		// intval() returns min/max on overflow, so check that
		if ( $ret === PHP_INT_MAX || $ret === PHP_INT_MIN ) {
			$tmp = ( $ret < 0 ? '-' : '' ) . ltrim( $value, '-0' );
			if ( $tmp !== (string)$ret ) {
				$this->failure( 'badinteger', $name, $value, $settings, $options );
			}
		}

		return $this->checkRange( $ret, $name, $value, $settings, $options );
	}

	/** @inheritDoc */
	public function getHelpInfo( $name, array $settings, array $options ) {
		$info = parent::getHelpInfo( $name, $settings, $options );

		$info[ParamValidator::PARAM_TYPE] = MessageValue::new( 'paramvalidator-help-type-integer' )
			->params( empty( $settings[ParamValidator::PARAM_ISMULTI] ) ? 1 : 2 );

		return $info;
	}

	/** @inheritDoc */
	public function stringifyValue( $name, $value, array $settings, array $options ) {
		if ( !is_array( $value ) ) {
			return parent::stringifyValue( $name, $value, $settings, $options );
		}

		return ParamValidator::implodeMultiValue( $value );
	}

}
