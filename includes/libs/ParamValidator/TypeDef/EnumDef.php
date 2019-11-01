<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef;
use Wikimedia\ParamValidator\ValidationException;

/**
 * Type definition for enumeration types.
 *
 * This class expects that PARAM_TYPE is an array of allowed values. Subclasses
 * may override getEnumValues() to determine the allowed values differently.
 *
 * The result from validate() is one of the defined values.
 *
 * ValidationException codes:
 *  - 'badvalue': The value is not a recognized value. No data.
 *  - 'notmulti': PARAM_ISMULTI is not set and the unrecognized value seems to
 *     be an attempt at using multiple values. No data.
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
	 * the ValidationException. If value is null, the parameter is considered
	 * not actually deprecated.
	 *
	 * Note that this does not add any values to the enumeration, it only
	 * documents existing values as being deprecated.
	 *
	 * ValidationException codes: (non-fatal)
	 *  - 'deprecated-value': A deprecated value was encountered. Data:
	 *     - 'flag': The value from the associative array.
	 */
	const PARAM_DEPRECATED_VALUES = 'param-deprecated-values';

	public function validate( $name, $value, array $settings, array $options ) {
		$values = $this->getEnumValues( $name, $settings, $options );

		if ( in_array( $value, $values, true ) ) {
			// Set a warning if a deprecated parameter value has been passed
			if ( isset( $settings[self::PARAM_DEPRECATED_VALUES][$value] ) ) {
				$this->callbacks->recordCondition(
					new ValidationException( $name, $value, $settings, 'deprecated-value', [
						'flag' => $settings[self::PARAM_DEPRECATED_VALUES][$value],
					] ),
					$options
				);
			}

			return $value;
		}

		if ( !isset( $options['values-list'] ) &&
			count( ParamValidator::explodeMultiValue( $value, 2 ) ) > 1
		) {
			throw new ValidationException( $name, $value, $settings, 'notmulti', [] );
		} else {
			throw new ValidationException( $name, $value, $settings, 'badvalue', [] );
		}
	}

	public function getEnumValues( $name, array $settings, array $options ) {
		return $settings[ParamValidator::PARAM_TYPE];
	}

	public function stringifyValue( $name, $value, array $settings, array $options ) {
		if ( !is_array( $value ) ) {
			return parent::stringifyValue( $name, $value, $settings, $options );
		}

		foreach ( $value as $v ) {
			if ( strpos( $v, '|' ) !== false ) {
				return "\x1f" . implode( "\x1f", $value );
			}
		}
		return implode( '|', $value );
	}

}
