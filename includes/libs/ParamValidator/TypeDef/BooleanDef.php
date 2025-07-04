<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef;

/**
 * Type definition for boolean types
 *
 * This type accepts certain defined strings to mean 'true' or 'false'.
 * The result from validate() is a PHP boolean.
 *
 * Failure codes:
 *  - 'badbool': The value is not a recognized boolean. No data.
 *
 * @since 1.34
 * @unstable
 */
class BooleanDef extends TypeDef {

	public const TRUEVALS = [ 'true', 't', 'yes', 'y', 'on', '1' ];
	public const FALSEVALS = [ 'false', 'f', 'no', 'n', 'off', '0' ];

	/** @inheritDoc */
	public function validate( $name, $value, array $settings, array $options ) {
		if ( is_bool( $value ) ) {
			return $value;
		} elseif ( $options[ self::OPT_ENFORCE_JSON_TYPES ] ?? false ) {
			$this->fatal(
				$this->failureMessage( 'badbool-type' )
					->params( gettype( $value ) ),
				$name, $value, $settings, $options
			);
		}

		$value = strtolower( $value );
		if ( in_array( $value, self::TRUEVALS, true ) ) {
			return true;
		}
		if ( $value === '' || in_array( $value, self::FALSEVALS, true ) ) {
			return false;
		}

		$this->fatal(
			$this->failureMessage( 'badbool' )
				->textListParams( array_map( $this->quoteVal( ... ), self::TRUEVALS ) )
				->numParams( count( self::TRUEVALS ) )
				->textListParams( array_merge(
					array_map( $this->quoteVal( ... ), self::FALSEVALS ),
					[ MessageValue::new( 'paramvalidator-emptystring' ) ]
				) )
				->numParams( count( self::FALSEVALS ) + 1 ),
			$name, $value, $settings, $options
		);
	}

	private function quoteVal( string $v ): ScalarParam {
		return new ScalarParam( ParamType::TEXT, "\"$v\"" );
	}

	/** @inheritDoc */
	public function stringifyValue( $name, $value, array $settings, array $options ) {
		return $value ? self::TRUEVALS[0] : self::FALSEVALS[0];
	}

	/** @inheritDoc */
	public function getHelpInfo( $name, array $settings, array $options ) {
		$info = parent::getHelpInfo( $name, $settings, $options );

		$info[ParamValidator::PARAM_TYPE] = MessageValue::new( 'paramvalidator-help-type-boolean' )
			->params( empty( $settings[ParamValidator::PARAM_ISMULTI] ) ? 1 : 2 );

		return $info;
	}

}
