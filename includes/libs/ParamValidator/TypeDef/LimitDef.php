<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Type definition for "limit" types
 *
 * A limit type is an integer type that also accepts the magic value "max".
 * IntegerDef::PARAM_MIN defaults to 0 for this type.
 *
 * @see IntegerDef
 * @since 1.34
 * @unstable
 */
class LimitDef extends IntegerDef {

	/**
	 * @inheritDoc
	 *
	 * Additional `$options` accepted:
	 *  - 'parse-limit': (bool) Default true, set false to return 'max' rather
	 *    than determining the effective value.
	 */
	public function validate( $name, $value, array $settings, array $options ) {
		if ( $value === 'max' ) {
			if ( $options['parse-limit'] ?? true ) {
				$value = $this->callbacks->useHighLimits( $options )
					? $settings[self::PARAM_MAX2] ?? $settings[self::PARAM_MAX] ?? PHP_INT_MAX
					: $settings[self::PARAM_MAX] ?? PHP_INT_MAX;
			}
			return $value;
		}

		return parent::validate( $name, $value, $settings, $options );
	}

	public function normalizeSettings( array $settings ) {
		$settings += [
			self::PARAM_MIN => 0,
		];

		// Cannot be multi-valued
		$settings[ParamValidator::PARAM_ISMULTI] = false;

		return parent::normalizeSettings( $settings );
	}

	public function checkSettings( string $name, $settings, array $options, array $ret ) : array {
		$ret = parent::checkSettings( $name, $settings, $options, $ret );

		if ( !empty( $settings[ParamValidator::PARAM_ISMULTI] ) &&
			!isset( $ret['issues'][ParamValidator::PARAM_ISMULTI] )
		) {
			$ret['issues'][ParamValidator::PARAM_ISMULTI] =
				'PARAM_ISMULTI cannot be used for limit-type parameters';
		}

		if ( ( $settings[self::PARAM_MIN] ?? 0 ) < 0 ) {
			$ret['issues'][] = 'PARAM_MIN must be greater than or equal to 0';
		}
		if ( !isset( $settings[self::PARAM_MAX] ) ) {
			$ret['issues'][] = 'PARAM_MAX must be set';
		}

		return $ret;
	}

	public function getHelpInfo( $name, array $settings, array $options ) {
		$info = parent::getHelpInfo( $name, $settings, $options );

		$info[ParamValidator::PARAM_TYPE] = MessageValue::new( 'paramvalidator-help-type-limit' )
			->params( 1 );

		return $info;
	}

}
