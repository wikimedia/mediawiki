<?php

namespace Wikimedia\ParamValidator\TypeDef;

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
			if ( !isset( $options['parse-limit'] ) || $options['parse-limit'] ) {
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

		return parent::normalizeSettings( $settings );
	}

}
