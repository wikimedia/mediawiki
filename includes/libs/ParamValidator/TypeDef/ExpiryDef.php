<?php

namespace Wikimedia\ParamValidator\TypeDef;

use InvalidArgumentException;
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
		$this->failIfNotString( $name, $value, $settings, $options );

		try {
			$expiry = self::normalizeExpiry( $value, TS_ISO_8601 );
		} catch ( InvalidArgumentException ) {
			$this->failure( 'badexpiry', $name, $value, $settings, $options );
		}

		if ( $expiry !== 'infinity' && $expiry < ConvertibleTimestamp::now( TS_ISO_8601 ) ) {
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

			return self::normalizeExpiry( $max, TS_ISO_8601 );
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
				array_map( static function ( $val ) {
					return "\"$val\"";
				}, self::INFINITY_VALS )
			);

		return $info;
	}

	/**
	 * Normalize a user-inputted expiry in ConvertibleTimestamp.
	 * @param string|null $expiry
	 * @param int|null $style null or in a format acceptable to ConvertibleTimestamp (TS_* constants)
	 *
	 * @return ConvertibleTimestamp|string|null Timestamp as ConvertibleTimestamp if $style is null, a string
	 *  timestamp in $style is not null, 'infinity' if $expiry is one of the self::INFINITY_VALS,
	 *  or null if $expiry is null.
	 *
	 * @throws InvalidArgumentException if $expiry is invalid
	 */
	public static function normalizeExpiry( ?string $expiry = null, ?int $style = null ) {
		if ( $expiry === null ) {
			return null;
		}
		if ( in_array( $expiry, self::INFINITY_VALS, true ) ) {
			return 'infinity';
		}

		// ConvertibleTimestamp::time() used so we can fake the current time in ExpiryDefTest.
		$unix = strtotime( $expiry, ConvertibleTimestamp::time() );
		if ( $unix === false || !self::isInMwRange( $unix ) ) {
			// Invalid expiry.
			throw new InvalidArgumentException( "Invalid expiry value: {$expiry}" );
		}

		// Don't pass 0, since ConvertibleTimestamp interprets that to mean the current timestamp.
		// '00' does the right thing. Without this check, calling normalizeExpiry()
		// with 1970-01-01T00:00:00Z incorrectly returns the current time.
		$expiryConvertibleTimestamp = new ConvertibleTimestamp( $unix === 0 ? '00' : $unix );

		if ( $style !== null ) {
			return $expiryConvertibleTimestamp->getTimestamp( $style );
		}

		return $expiryConvertibleTimestamp;
	}

	/**
	 * Check if a UNIX timestamp is in the range representable by MediaWiki 14 character strings
	 *
	 * @param int $unix
	 * @return bool
	 */
	private static function isInMwRange( int $unix ): bool {
		return $unix > strtotime( '0000-01-01T00:00:00Z' )
			&& $unix < strtotime( '9999-12-31T00:00:00Z' );
	}

	/**
	 * Returns a normalized expiry or the max expiry if the given expiry exceeds it.
	 * @param string|null $expiry
	 * @param string|null $maxExpiryDuration
	 * @param int|null $style null or in a format acceptable to ConvertibleTimestamp (TS_* constants)
	 * @return ConvertibleTimestamp|string|null Timestamp as ConvertibleTimestamp if $style is null, a string
	 *  timestamp in $style is not null, 'infinity' if $expiry is one of the self::INFINITY_VALS,
	 *  or null if $expiry is null.
	 */
	public static function normalizeUsingMaxExpiry( ?string $expiry, ?string $maxExpiryDuration, ?int $style ) {
		if ( self::expiryExceedsMax( $expiry, $maxExpiryDuration ) ) {
			return self::normalizeExpiry( $maxExpiryDuration, $style );
		}
		return self::normalizeExpiry( $expiry, $style );
	}

	public function checkSettings( string $name, $settings, array $options, array $ret ): array {
		$ret = parent::checkSettings( $name, $settings, $options, $ret );

		$ret['allowedKeys'][] = self::PARAM_USE_MAX;
		$ret['allowedKeys'][] = self::PARAM_MAX;

		return $ret;
	}

	/**
	 * Given an expiry, test if the normalized value exceeds the given maximum.
	 *
	 * @param string|null $expiry
	 * @param string|null $max Relative maximum duration acceptable by strtotime() (i.e. '6 months')
	 * @return bool
	 */
	private static function expiryExceedsMax( ?string $expiry, ?string $max = null ): bool {
		$expiry = self::normalizeExpiry( $expiry );
		$max = self::normalizeExpiry( $max );

		if ( !$max || !$expiry || $expiry === 'infinity' ) {
			// Either there is no max or given expiry was invalid.
			return false;
		}

		return $expiry > $max;
	}

}
