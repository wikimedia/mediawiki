<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Type definition for a floating-point type
 *
 * A valid representation consists of:
 *  - an optional sign (`+` or `-`)
 *  - a decimal number, using `.` as the decimal separator and no grouping
 *  - an optional E-notation suffix: the letter 'e' or 'E', an optional
 *    sign, and an integer
 *
 * Thus, for example, "12", "-.4", "6.022e23", or "+1.7e-10".
 *
 * The result from validate() is a PHP float.
 *
 * Failure codes:
 *  - 'badfloat': The value was invalid. No data.
 *  - 'badfloat-notfinite': The value was in a valid format, but conversion resulted in
 *    infinity or NAN.
 *
 * @since 1.34
 * @unstable
 */
class FloatDef extends NumericDef {

	/** @inheritDoc */
	protected $valueType = 'double';

	/** @inheritDoc */
	public function validate( $name, $value, array $settings, array $options ) {
		if ( is_float( $value ) ) {
			$ret = $value;
		} elseif ( is_int( $value ) ) {
			$ret = (float)$value;
		} elseif ( $options[ self::OPT_ENFORCE_JSON_TYPES ] ?? false ) {
			$this->fatal(
				$this->failureMessage( 'badfloat-type' )
					->params( gettype( $value ) ),
				$name, $value, $settings, $options
			);
		} else {
			if ( !preg_match( '/^[+-]?(?:\d*\.)?\d+(?:[eE][+-]?\d+)?$/D', $value ) ) {
				// Use a regex to avoid any potential oddness PHP's default conversion might allow.
				$this->fatal( 'badfloat', $name, $value, $settings, $options );
			}

			$ret = (float)$value;
		}

		if ( !is_finite( $ret ) ) {
			$this->fatal( 'badfloat-notfinite', $name, $value, $settings, $options );
		}

		return $this->checkRange( $ret, $name, $value, $settings, $options );
	}

	/** @inheritDoc */
	public function stringifyValue( $name, $value, array $settings, array $options ) {
		// Ensure sufficient precision for round-tripping
		$digits = PHP_FLOAT_DIG;
		return sprintf( "%.{$digits}g", $value );
	}

	/** @inheritDoc */
	public function getHelpInfo( $name, array $settings, array $options ) {
		$info = parent::getHelpInfo( $name, $settings, $options );

		$info[ParamValidator::PARAM_TYPE] = MessageValue::new( 'paramvalidator-help-type-float' )
			->params( empty( $settings[ParamValidator::PARAM_ISMULTI] ) ? 1 : 2 );

		return $info;
	}

}
