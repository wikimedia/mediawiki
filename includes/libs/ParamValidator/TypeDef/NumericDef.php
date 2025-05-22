<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef;
use Wikimedia\ParamValidator\ValidationException;

/**
 * Type definition base class for numeric types
 *
 * * Failure codes:
 *  - 'outofrange': The value was outside of the allowed range. Data:
 *     - 'min': Minimum allowed, or null if there is no limit.
 *     - 'curmax': Current maximum allowed, or null if there is no limit.
 *     - 'max': Normal maximum allowed, or null if there is no limit.
 *     - 'highmax': High limits maximum allowed, or null if there is no limit.
 *
 * @stable to extend
 * @since 1.35
 * @unstable
 */
abstract class NumericDef extends TypeDef {

	/**
	 * (bool) Whether to enforce the specified range.
	 *
	 * If set and truthy, the 'outofrange' failure is non-fatal.
	 */
	public const PARAM_IGNORE_RANGE = 'param-ignore-range';

	/**
	 * (int|float) Minimum allowed value.
	 */
	public const PARAM_MIN = 'param-min';

	/**
	 * (int|float) Maximum allowed value (normal limits)
	 */
	public const PARAM_MAX = 'param-max';

	/**
	 * (int|float) Maximum allowed value (high limits)
	 */
	public const PARAM_MAX2 = 'param-max2';

	/** @var string PHP type (as from `gettype()`) of values this NumericDef handles */
	protected $valueType = 'integer';

	/**
	 * Check the range of a value
	 * @param int|float $value Value to check.
	 * @param string $name Parameter name being validated.
	 * @param mixed $origValue Original value being validated.
	 * @param array $settings Parameter settings array.
	 * @param array $options Options array.
	 * @return int|float Validated value, may differ from $value if
	 *   PARAM_IGNORE_RANGE was set.
	 * @throws ValidationException if the value out of range, and PARAM_IGNORE_RANGE wasn't set.
	 */
	protected function checkRange( $value, $name, $origValue, array $settings, array $options ) {
		$min = $settings[self::PARAM_MIN] ?? null;
		$max1 = $settings[self::PARAM_MAX] ?? null;
		$max2 = $settings[self::PARAM_MAX2] ?? $max1;
		$err = false;

		if ( $min !== null && $value < $min ) {
			$err = true;
			$value = $min;
		} elseif ( $max1 !== null && $value > $max1 ) {
			if ( $max2 > $max1 && $this->callbacks->useHighLimits( $options ) ) {
				if ( $value > $max2 ) {
					$err = true;
					$value = $max2;
				}
			} else {
				$err = true;
				$value = $max1;
			}
		}
		if ( $err ) {
			$what = '';
			if ( $min !== null ) {
				$what .= 'min';
			}
			if ( $max1 !== null ) {
				$what .= 'max';
			}
			$max = $max2 !== null && $max2 > $max1 && $this->callbacks->useHighLimits( $options )
				? $max2 : $max1;
			$this->failure(
				$this->failureMessage( 'outofrange', [
					'min' => $min,
					'curmax' => $max,
					'max' => $max1,
					'highmax' => $max2,
				], $what )->numParams( $min ?? '', $max ?? '' ),
				$name, $origValue, $settings, $options,
				empty( $settings[self::PARAM_IGNORE_RANGE] )
			);
		}

		return $value;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
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

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function checkSettings( string $name, $settings, array $options, array $ret ): array {
		$ret = parent::checkSettings( $name, $settings, $options, $ret );

		$ret['allowedKeys'][] = self::PARAM_IGNORE_RANGE;
		$ret['allowedKeys'][] = self::PARAM_MIN;
		$ret['allowedKeys'][] = self::PARAM_MAX;
		$ret['allowedKeys'][] = self::PARAM_MAX2;

		if ( !is_bool( $settings[self::PARAM_IGNORE_RANGE] ?? false ) ) {
			$ret['issues'][self::PARAM_IGNORE_RANGE] = 'PARAM_IGNORE_RANGE must be boolean, got '
				. gettype( $settings[self::PARAM_IGNORE_RANGE] );
		}

		$min = $settings[self::PARAM_MIN] ?? null;
		$max = $settings[self::PARAM_MAX] ?? null;
		$max2 = $settings[self::PARAM_MAX2] ?? null;
		if ( $min !== null && gettype( $min ) !== $this->valueType ) {
			$ret['issues'][self::PARAM_MIN] = "PARAM_MIN must be $this->valueType, got " . gettype( $min );
		}
		if ( $max !== null && gettype( $max ) !== $this->valueType ) {
			$ret['issues'][self::PARAM_MAX] = "PARAM_MAX must be $this->valueType, got " . gettype( $max );
		}
		if ( $max2 !== null && gettype( $max2 ) !== $this->valueType ) {
			$ret['issues'][self::PARAM_MAX2] = "PARAM_MAX2 must be $this->valueType, got "
				. gettype( $max2 );
		}

		if ( $min !== null && $max !== null && $min > $max ) {
			$ret['issues'][] = "PARAM_MIN must be less than or equal to PARAM_MAX, but $min > $max";
		}
		if ( $max2 !== null ) {
			if ( $max === null ) {
				$ret['issues'][] = 'PARAM_MAX2 cannot be used without PARAM_MAX';
			} elseif ( $max2 < $max ) {
				$ret['issues'][] = "PARAM_MAX2 must be greater than or equal to PARAM_MAX, but $max2 < $max";
			}
		}

		return $ret;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getParamInfo( $name, array $settings, array $options ) {
		$info = parent::getParamInfo( $name, $settings, $options );

		$info['min'] = $settings[self::PARAM_MIN] ?? null;
		$info['max'] = $settings[self::PARAM_MAX] ?? null;
		$info['highmax'] = $settings[self::PARAM_MAX2] ?? $info['max'];
		if ( $info['max'] === null || $info['highmax'] <= $info['max'] ) {
			unset( $info['highmax'] );
		}

		return $info;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getHelpInfo( $name, array $settings, array $options ) {
		$info = parent::getHelpInfo( $name, $settings, $options );

		$min = '−∞';
		$max = '∞';
		$msg = '';
		if ( isset( $settings[self::PARAM_MIN] ) ) {
			$msg .= 'min';
			$min = new ScalarParam( ParamType::NUM, $settings[self::PARAM_MIN] );
		}
		if ( isset( $settings[self::PARAM_MAX] ) ) {
			$msg .= 'max';
			$max = $settings[self::PARAM_MAX];
			if ( isset( $settings[self::PARAM_MAX2] ) && $settings[self::PARAM_MAX2] > $max &&
				$this->callbacks->useHighLimits( $options )
			) {
				$max = $settings[self::PARAM_MAX2];
			}
			$max = new ScalarParam( ParamType::NUM, $max );
		}
		if ( $msg !== '' ) {
			$isMulti = !empty( $settings[ParamValidator::PARAM_ISMULTI] );

			// Messages: paramvalidator-help-type-number-min, paramvalidator-help-type-number-max,
			// paramvalidator-help-type-number-minmax
			$info[self::PARAM_MIN] = MessageValue::new( "paramvalidator-help-type-number-$msg" )
				->params( $isMulti ? 2 : 1, $min, $max );
		}

		return $info;
	}

}
