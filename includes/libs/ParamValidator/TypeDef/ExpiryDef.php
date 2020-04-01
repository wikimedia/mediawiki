<?php

namespace Wikimedia\ParamValidator\TypeDef;

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

	public function validate( $name, $value, array $settings, array $options ) {
		$expiry = self::normalizeExpiry( $value );

		if ( $expiry === false ) {
			$this->failure( 'badexpiry', $name, $value, $settings, $options );
		}

		if ( $expiry < self::timestamp() ) {
			$this->failure( 'badexpiry-past', $name, $value, $settings, $options );
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

}
