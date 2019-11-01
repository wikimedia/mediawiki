<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\TypeDef;
use Wikimedia\ParamValidator\ValidationException;

/**
 * Type definition for integer types
 *
 * A valid representation consists of an optional sign (`+` or `-`) followed by
 * one or more decimal digits.
 *
 * The result from validate() is a PHP integer.
 *
 * * ValidationException codes:
 *  - 'badinteger': The value was invalid or could not be represented as a PHP
 *    integer. No data.
 *
 * Additional codes may be generated when using certain PARAM constants. See
 * the constants' documentation for details.
 *
 * @since 1.34
 * @unstable
 */
class IntegerDef extends TypeDef {

	/**
	 * (bool) Whether to enforce the specified range.
	 *
	 * If set and truthy, ValidationExceptions from PARAM_MIN, PARAM_MAX, and
	 * PARAM_MAX2 are non-fatal.
	 */
	const PARAM_IGNORE_RANGE = 'param-ignore-range';

	/**
	 * (int) Minimum allowed value.
	 *
	 * ValidationException codes:
	 *  - 'belowminimum': The value was below the allowed minimum. Data:
	 *     - 'min': Allowed minimum, or empty string if there is none.
	 *     - 'max': Allowed (normal) maximum, or empty string if there is none.
	 *     - 'max2': Allowed (high limits) maximum, or empty string if there is none.
	 */
	const PARAM_MIN = 'param-min';

	/**
	 * (int) Maximum allowed value (normal limits)
	 *
	 * ValidationException codes:
	 *  - 'abovemaximum': The value was above the allowed maximum. Data:
	 *     - 'min': Allowed minimum, or empty string if there is none.
	 *     - 'max': Allowed (normal) maximum, or empty string if there is none.
	 *     - 'max2': Allowed (high limits) maximum, or empty string if there is none.
	 */
	const PARAM_MAX = 'param-max';

	/**
	 * (int) Maximum allowed value (high limits)
	 *
	 * If not specified, PARAM_MAX will be enforced for all users. Ignored if
	 * PARAM_MAX is not set.
	 *
	 * ValidationException codes:
	 *  - 'abovehighmaximum': The value was above the allowed maximum. Data:
	 *     - 'min': Allowed minimum, or empty string if there is none.
	 *     - 'max': Allowed (normal) maximum, or empty string if there is none.
	 *     - 'max2': Allowed (high limits) maximum, or empty string if there is none.
	 */
	const PARAM_MAX2 = 'param-max2';

	public function validate( $name, $value, array $settings, array $options ) {
		if ( !preg_match( '/^[+-]?\d+$/D', $value ) ) {
			throw new ValidationException( $name, $value, $settings, 'badinteger', [] );
		}
		$ret = intval( $value, 10 );

		// intval() returns min/max on overflow, so check that
		if ( $ret === PHP_INT_MAX || $ret === PHP_INT_MIN ) {
			$tmp = ( $ret < 0 ? '-' : '' ) . ltrim( $value, '-0' );
			if ( $tmp !== (string)$ret ) {
				throw new ValidationException( $name, $value, $settings, 'badinteger', [] );
			}
		}

		$min = $settings[self::PARAM_MIN] ?? null;
		$max = $settings[self::PARAM_MAX] ?? null;
		$max2 = $settings[self::PARAM_MAX2] ?? null;
		$err = null;

		if ( $min !== null && $ret < $min ) {
			$err = 'belowminimum';
			$ret = $min;
		} elseif ( $max !== null && $ret > $max ) {
			if ( $max2 !== null && $this->callbacks->useHighLimits( $options ) ) {
				if ( $ret > $max2 ) {
					$err = 'abovehighmaximum';
					$ret = $max2;
				}
			} else {
				$err = 'abovemaximum';
				$ret = $max;
			}
		}
		if ( $err !== null ) {
			$ex = new ValidationException( $name, $value, $settings, $err, [
				'min' => $min === null ? '' : $min,
				'max' => $max === null ? '' : $max,
				'max2' => $max2 === null ? '' : $max2,
			] );
			if ( empty( $settings[self::PARAM_IGNORE_RANGE] ) ) {
				throw $ex;
			}
			$this->callbacks->recordCondition( $ex, $options );
		}

		return $ret;
	}

	public function normalizeSettings( array $settings ) {
		if ( !isset( $settings[self::PARAM_MAX] ) ) {
			unset( $settings[self::PARAM_MAX2] );
		}

		if ( isset( $settings[self::PARAM_MAX2] ) && isset( $settings[self::PARAM_MAX] ) &&
			$settings[self::PARAM_MAX2] < $settings[self::PARAM_MAX]
		) {
			$settings[self::PARAM_MAX2] = $settings[self::PARAM_MAX];
		}

		return parent::normalizeSettings( $settings );
	}

	public function describeSettings( $name, array $settings, array $options ) {
		$info = parent::describeSettings( $name, $settings, $options );

		$min = $settings[self::PARAM_MIN] ?? '';
		$max = $settings[self::PARAM_MAX] ?? '';
		$max2 = $settings[self::PARAM_MAX2] ?? '';
		if ( $max === '' || $max2 !== '' && $max2 <= $max ) {
			$max2 = '';
		}

		if ( empty( $options['compact'] ) ) {
			if ( $min !== '' ) {
				$info['min'] = $min;
			}
			if ( $max !== '' ) {
				$info['max'] = $max;
			}
			if ( $max2 !== '' ) {
				$info['max2'] = $max2;
			}
		} else {
			$key = '';
			if ( $min !== '' ) {
				$key = 'min';
			}
			if ( $max2 !== '' ) {
				$key .= 'max2';
			} elseif ( $max !== '' ) {
				$key .= 'max';
			}
			if ( $key !== '' ) {
				$info[$key] = [ 'min' => $min, 'max' => $max, 'max2' => $max2 ];
			}
		}

		return $info;
	}

}
