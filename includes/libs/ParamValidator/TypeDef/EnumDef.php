<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\Message\ListParam;
use Wikimedia\Message\ListType;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;
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
	 * Keys are the deprecated parameter values. Value is one of the following:
	 *  - null: Parameter isn't actually deprecated.
	 *  - true: Parameter is deprecated.
	 *  - MessageValue: Parameter is deprecated, and this message (converted to a DataMessageValue)
	 *    is used in place of the default for passing to $this->failure().
	 *
	 * Note that this does not add any values to the enumeration, it only
	 * documents existing values as being deprecated.
	 *
	 * Failure codes: (non-fatal)
	 *  - 'deprecated-value': A deprecated value was encountered. No data.
	 */
	public const PARAM_DEPRECATED_VALUES = 'param-deprecated-values';

	/** @inheritDoc */
	public function validate( $name, $value, array $settings, array $options ) {
		$values = $this->getEnumValues( $name, $settings, $options );

		if ( in_array( $value, $values, true ) ) {
			// Set a warning if a deprecated parameter value has been passed
			if ( empty( $options['is-default'] ) &&
				isset( $settings[self::PARAM_DEPRECATED_VALUES][$value] )
			) {
				$msg = $settings[self::PARAM_DEPRECATED_VALUES][$value];
				if ( $msg instanceof MessageValue ) {
					$message = DataMessageValue::new(
						$msg->getKey(),
						$msg->getParams(),
						'deprecated-value',
						$msg instanceof DataMessageValue ? $msg->getData() : null
					);
				} else {
					$message = $this->failureMessage( 'deprecated-value' );
				}
				$this->failure( $message, $name, $value, $settings, $options, false );
			}

			return $value;
		}

		$isMulti = isset( $options['values-list'] );
		$this->failure(
			$this->failureMessage( 'badvalue', [], $isMulti ? 'enummulti' : 'enumnotmulti' )
				->textListParams( array_map( static function ( $v ) {
					return new ScalarParam( ParamType::PLAINTEXT, $v );
				}, $values ) )
				->numParams( count( $values ) ),
			$name, $value, $settings, $options
		);
	}

	/** @inheritDoc */
	public function checkSettings( string $name, $settings, array $options, array $ret ): array {
		$ret = parent::checkSettings( $name, $settings, $options, $ret );

		$ret['allowedKeys'][] = self::PARAM_DEPRECATED_VALUES;

		$dv = $settings[self::PARAM_DEPRECATED_VALUES] ?? [];
		if ( !is_array( $dv ) ) {
			$ret['issues'][self::PARAM_DEPRECATED_VALUES] = 'PARAM_DEPRECATED_VALUES must be an array, got '
				. gettype( $dv );
		} else {
			$values = array_map( function ( $v ) use ( $name, $settings, $options ) {
				return $this->stringifyValue( $name, $v, $settings, $options );
			}, $this->getEnumValues( $name, $settings, $options ) );
			foreach ( $dv as $k => $v ) {
				$k = $this->stringifyValue( $name, $k, $settings, $options );
				if ( !in_array( $k, $values, true ) ) {
					$ret['issues'][] = "PARAM_DEPRECATED_VALUES contains \"$k\", which is not "
						. 'one of the enumerated values';
				} elseif ( $v instanceof MessageValue ) {
					$ret['messages'][] = $v;
				} elseif ( $v !== null && $v !== true ) {
					$type = $v === false ? 'false' : ( is_object( $v ) ? get_class( $v ) : gettype( $v ) );
					$ret['issues'][] = 'Values in PARAM_DEPRECATED_VALUES must be null, true, or MessageValue, '
						. "but value for \"$k\" is $type";
				}
			}
		}

		return $ret;
	}

	/** @inheritDoc */
	public function getEnumValues( $name, array $settings, array $options ) {
		return array_values( $settings[ParamValidator::PARAM_TYPE] );
	}

	/** @inheritDoc */
	public function stringifyValue( $name, $value, array $settings, array $options ) {
		if ( !is_array( $value ) ) {
			return parent::stringifyValue( $name, $value, $settings, $options );
		}

		return ParamValidator::implodeMultiValue( $value );
	}

	/** @inheritDoc */
	public function getParamInfo( $name, array $settings, array $options ) {
		$info = parent::getParamInfo( $name, $settings, $options );

		$info['type'] = $this->sortEnumValues(
			$name,
			$this->getEnumValues( $name, $settings, $options ),
			$settings,
			$options
		);

		if ( !empty( $settings[self::PARAM_DEPRECATED_VALUES] ) ) {
			$deprecatedValues = array_intersect(
				array_keys( $settings[self::PARAM_DEPRECATED_VALUES] ),
				$this->getEnumValues( $name, $settings, $options )
			);
			if ( $deprecatedValues ) {
				$deprecatedValues = $this->sortEnumValues( $name, $deprecatedValues, $settings, $options );
				$info['deprecatedvalues'] = array_values( $deprecatedValues );
			}
		}

		return $info;
	}

	/** @inheritDoc */
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
	 * Sort enum values for help/param info output
	 *
	 * @param string $name Parameter name being described.
	 * @param string[] $values Values being sorted
	 * @param array $settings Parameter settings array.
	 * @param array $options Options array.
	 * @return string[]
	 */
	protected function sortEnumValues(
		string $name, array $values, array $settings, array $options
	): array {
		// sort values by deprecation status and name
		$flags = [];
		foreach ( $values as $k => $value ) {
			$flag = 0;
			if ( isset( $settings[self::PARAM_DEPRECATED_VALUES][$value] ) ) {
				$flag |= 1;
			}
			$flags[$k] = $flag;
		}
		array_multisort( $flags, $values, SORT_NATURAL );

		return $values;
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
		$values = $this->sortEnumValues( $name, $values, $settings, $options );

		// @todo Indicate deprecated values in some manner. Probably that needs
		// MessageValue and/or MessageParam to have a generic ability to wrap
		// values in HTML without that HTML coming out in the text format too.

		return $values;
	}

}
