<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Type definition for expiry timestamps.
 *
 * @since 1.35
 */
class ExpiryDef extends TypeDef {

	/** @var array Possible values that mean "doesn't expire". */
	public const INFINITY_VALS = [ 'infinite', 'indefinite', 'infinity', 'never' ];

	/**
	 * (bool) If truthy, the value given for the PARAM_MAX setting is used if the provided expiry
	 * exceeds it, and the 'badexpiry-duration' message is shown as a warning.
	 *
	 * If false, 'badexpiry-duration' is shown and is fatal.
	 */
	public const PARAM_USE_MAX = 'param-use-max';

	/**
	 * (int|float) Maximum non-infinity duration.
	 */
	public const PARAM_MAX = 'param-max';

	public function validate( $name, $value, array $settings, array $options ) {
		$expiry = self::normalizeExpiry( $value );

		if ( $expiry === false ) {
			$this->failure( 'badexpiry', $name, $value, $settings, $options );
		}

		if ( $expiry < self::timestamp() ) {
			$this->failure( 'badexpiry-past', $name, $value, $settings, $options );
		}

		$max = $settings[self::PARAM_MAX] ?? null;

		if ( self::expiryExceedsMax( $expiry, $max ) ) {
			$dontUseMax = empty( $settings[self::PARAM_USE_MAX] );
			// Show warning that expiry exceeds the max, and that the max is being used instead.
			$msg = DataMessageValue::new(
				$dontUseMax
					? 'paramvalidator-badexpiry-duration'
					: 'paramvalidator-badexpiry-duration-max',
				[ $max ]
			);
			$this->failure( $msg, $name, $value, $settings, $options, $dontUseMax );

			return self::normalizeExpiry( $max );
		}

		return $expiry;
	}

	public function getHelpInfo( $name, array $settings, array $options ) {
		$info = parent::getHelpInfo( $name, $settings, $options );

		$info[ParamValidator::PARAM_TYPE] = MessageValue::new( 'paramvalidator-help-type-expiry' )
			->params( empty( $settings[ParamValidator::PARAM_ISMULTI] ) ? 1 : 2 )
			->textListParams(
				// Should be quoted or monospace for presentation purposes,
				//   but textListParams() doesn't do this.
				array_map( function ( $val ) {
					return "\"$val\"";
				}, self::INFINITY_VALS )
			);

		return $info;
	}

	/**
	 * Normalize a user-inputted expiry.
	 * @param string|null $expiry
	 * @return string|false|null Timestamp as TS_ISO_8601, 'infinity', false if invalid,
	 *   or null when given null.
	 */
	public static function normalizeExpiry( ?string $expiry ) {
		if ( $expiry === null ) {
			return null;
		}
		if ( in_array( $expiry, self::INFINITY_VALS, true ) ) {
			return 'infinity';
		}

		// ConvertibleTimestamp::time() used so we can fake the current time in ExpiryDefTest.
		$unix = strtotime( $expiry, ConvertibleTimestamp::time() );
		if ( $unix === false ) {
			// Invalid expiry.
			return false;
		}

		return self::timestamp( $unix );
	}

	/**
	 * Convert to or get the current time in TS_ISO_8601.
	 * @param bool|int $unix
	 * @return string|false
	 */
	protected static function timestamp( $unix = false ) {
		// Don't pass 0, since ConvertibleTimestamp interprets that to mean the current timestamp.
		// '00' does the right thing. Without this check, calling normalizeExpiry()
		// with 1970-01-01T00:00:00Z incorrectly returns the current time.
		return ConvertibleTimestamp::convert( TS_ISO_8601, $unix === 0 ? '00' : $unix );
	}

	/**
	 * Given an expiry, test if the normalized value exceeds the given maximum.
	 *
	 * @param string|null $expiry
	 * @param string|null $max Relative maximum duration acceptable by strtotime() (i.e. '6 months')
	 * @return bool
	 */
	public static function expiryExceedsMax( ?string $expiry, ?string $max = null ): bool {
		// Normalize the max and given expiries to TS_ISO_8601 format.
		$expiry = self::normalizeExpiry( $expiry );
		$max = self::normalizeExpiry( $max );

		if ( !$max || !$expiry || $expiry === 'infinity' ) {
			// Either there is no max or given expiry was invalid.
			return false;
		}

		return $expiry > $max;
	}

}
