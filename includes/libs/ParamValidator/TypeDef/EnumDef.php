<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\ParamType;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\ScalarParam;
use Wikimedia\Message\ListParam;
use Wikimedia\Message\ListType;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef;

/**
 * Type definition for enumeration types.
 *
 * This class expects that PARAM_TYPE is an array of allowed values. Subclasses
 * may override getEnumValues() to determine the allowed values differently.
 *
 * The result from validate() is one of the defined values.
 *
 * Failure codes:
 *  - 'badvalue': The value is not a recognized value. No data.
 *
 * Additional codes may be generated when using certain PARAM constants. See
 * the constants' documentation for details.
 *
 * @since 1.34
 * @unstable
 */
class EnumDef extends TypeDef {

	/**
	 * (array) Associative array of deprecated values.
	 *
	 * Keys are the deprecated parameter values, values are included in
	 * the failure message. If value is null, the parameter is considered
	 * not actually deprecated.
	 *
	 * Note that this does not add any values to the enumeration, it only
	 * documents existing values as being deprecated.
	 *
	 * Failure codes: (non-fatal)
	 *  - 'deprecated-value': A deprecated value was encountered. Data:
	 *     - 'data': The value from the associative array.
	 */
	const PARAM_DEPRECATED_VALUES = 'param-deprecated-values';

	public function validate( $name, $value, array $settings, array $options ) {
		$values = $this->getEnumValues( $name, $settings, $options );

		if ( in_array( $value, $values, true ) ) {
			// Set a warning if a deprecated parameter value has been passed
			if ( isset( $settings[self::PARAM_DEPRECATED_VALUES][$value] ) ) {
				$this->failure(
					$this->failureMessage( 'deprecated-value', [
						'data' => $settings[self::PARAM_DEPRECATED_VALUES][$value],
					] ),
					$name, $value, $settings, $options,
					false
				);
			}

			return $value;
		}

		$isMulti = !isset( $options['values-list'] );
		$this->failure(
			$this->failureMessage( 'badvalue', [], $isMulti ? 'enummulti' : 'enumnotmulti' )
				->textListParams( array_map( function ( $v ) {
					return new ScalarParam( ParamType::PLAINTEXT, $v );
				}, $values ) )
				->numParams( count( $values ) ),
			$name, $value, $settings, $options
		);
	}

	public function getEnumValues( $name, array $settings, array $options ) {
		return $settings[ParamValidator::PARAM_TYPE];
	}

	public function stringifyValue( $name, $value, array $settings, array $options ) {
		if ( !is_array( $value ) ) {
			return parent::stringifyValue( $name, $value, $settings, $options );
		}

		return ParamValidator::implodeMultiValue( $value );
	}

	public function getParamInfo( $name, array $settings, array $options ) {
		$info = parent::getParamInfo( $name, $settings, $options );

		if ( !empty( $settings[self::PARAM_DEPRECATED_VALUES] ) ) {
			$deprecatedValues = array_intersect(
				array_keys( $settings[self::PARAM_DEPRECATED_VALUES] ),
				$this->getEnumValues( $name, $settings, $options )
			);
			if ( $deprecatedValues ) {
				$info['deprecatedvalues'] = array_values( $deprecatedValues );
			}
		}

		return $info;
	}

	public function getHelpInfo( $name, array $settings, array $options ) {
		$info = parent::getHelpInfo( $name, $settings, $options );

		$isMulti = !empty( $settings[ParamValidator::PARAM_ISMULTI] );

		$values = $this->getEnumValuesForHelp( $name, $settings, $options );
		$count = count( $values );

		$i = array_search( '', $values, true );
		if ( $i === false ) {
			$valuesParam = new ListParam( ListType::COMMA, $values );
		} else {
			unset( $values[$i] );
			$valuesParam = MessageValue::new( 'paramvalidator-help-type-enum-can-be-empty' )
				->commaListParams( $values )
				->numParams( count( $values ) );
		}

		$info[ParamValidator::PARAM_TYPE] = MessageValue::new( 'paramvalidator-help-type-enum' )
			->params( $isMulti ? 2 : 1 )
			->params( $valuesParam )
			->numParams( $count );

		// Suppress standard ISMULTI message, it should be incorporated into our type message.
		$info[ParamValidator::PARAM_ISMULTI] = null;

		return $info;
	}

	/**
	 * Return enum values formatted for the help message
	 *
	 * @param string $name Parameter name being described.
	 * @param array $settings Parameter settings array.
	 * @param array $options Options array.
	 * @return (MessageParam|string)[]
	 */
	protected function getEnumValuesForHelp( $name, array $settings, array $options ) {
		$values = $this->getEnumValues( $name, $settings, $options );

		// sort values by deprecation status and name
		$flags = [];
		foreach ( $values as $k => $value ) {
			$flag = 0;
			if ( isset( $settings[self::PARAM_DEPRECATED_VALUES][$value] ) ) {
				$flag |= 1;
			}
			$flags[$k] = $flag;
		}
		array_multisort( $flags, $values );

		// @todo Indicate deprecated values in some manner. Probably that needs
		// MessageValue and/or MessageParam to have a generic ability to wrap
		// values in HTML without that HTML coming out in the text format too.

		return $values;
	}

}
