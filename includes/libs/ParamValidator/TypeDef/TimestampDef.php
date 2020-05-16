<?php

namespace Wikimedia\ParamValidator\TypeDef;

use InvalidArgumentException;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\Callbacks;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef;
use Wikimedia\ParamValidator\ValidationException;
use Wikimedia\Timestamp\ConvertibleTimestamp;
use Wikimedia\Timestamp\TimestampException;

/**
 * Type definition for timestamp types
 *
 * This uses the wikimedia/timestamp library for parsing and formatting the
 * timestamps.
 *
 * The result from validate() is a ConvertibleTimestamp by default, but this
 * may be changed by both a constructor option and a PARAM constant.
 *
 * Failure codes:
 *  - 'badtimestamp': The timestamp is not valid. No data, but the
 *    TimestampException is available via Exception::getPrevious().
 *  - 'unclearnowtimestamp': Non-fatal. The value is the empty string or "0".
 *    Use 'now' instead if you really want the current timestamp. No data.
 *
 * @since 1.34
 * @unstable
 */
class TimestampDef extends TypeDef {

	/**
	 * (string|int) Timestamp format to return from validate()
	 *
	 * Values include:
	 *  - 'ConvertibleTimestamp': A ConvertibleTimestamp object.
	 *  - 'DateTime': A PHP DateTime object
	 *  - One of ConvertibleTimestamp's TS_* constants.
	 *
	 * This does not affect the format returned by stringifyValue().
	 */
	public const PARAM_TIMESTAMP_FORMAT = 'param-timestamp-format';

	/** @var string|int */
	protected $defaultFormat;

	/** @var int */
	protected $stringifyFormat;

	/**
	 * @param Callbacks $callbacks
	 * @param array $options Options:
	 *  - defaultFormat: (string|int) Default for PARAM_TIMESTAMP_FORMAT.
	 *    Default if not specified is 'ConvertibleTimestamp'.
	 *  - stringifyFormat: (int) Format to use for stringifyValue().
	 *    Default is TS_ISO_8601.
	 */
	public function __construct( Callbacks $callbacks, array $options = [] ) {
		parent::__construct( $callbacks );

		$this->defaultFormat = $options['defaultFormat'] ?? 'ConvertibleTimestamp';
		$this->stringifyFormat = $options['stringifyFormat'] ?? TS_ISO_8601;

		// Check values by trying to convert 0
		if ( $this->defaultFormat !== 'ConvertibleTimestamp' && $this->defaultFormat !== 'DateTime' &&
			ConvertibleTimestamp::convert( $this->defaultFormat, 0 ) === false
		) {
			throw new InvalidArgumentException( 'Invalid value for $options[\'defaultFormat\']' );
		}
		if ( ConvertibleTimestamp::convert( $this->stringifyFormat, 0 ) === false ) {
			throw new InvalidArgumentException( 'Invalid value for $options[\'stringifyFormat\']' );
		}
	}

	public function validate( $name, $value, array $settings, array $options ) {
		// Confusing synonyms for the current time accepted by ConvertibleTimestamp
		if ( !$value ) {
			$this->failure( 'unclearnowtimestamp', $name, $value, $settings, $options, false );
			$value = 'now';
		}

		try {
			$timestamp = new ConvertibleTimestamp( $value === 'now' ? false : $value );
		} catch ( TimestampException $ex ) {
			// $this->failure() doesn't handle passing a previous exception
			throw new ValidationException(
				$this->failureMessage( 'badtimestamp' )->plaintextParams( $name, $value ),
				$name, $value, $settings, $ex
			);
		}

		$format = $settings[self::PARAM_TIMESTAMP_FORMAT] ?? $this->defaultFormat;
		switch ( $format ) {
			case 'ConvertibleTimestamp':
				return $timestamp;

			case 'DateTime':
				// Eew, no getter.
				return $timestamp->timestamp;

			default:
				return $timestamp->getTimestamp( $format );
		}
	}

	public function checkSettings( string $name, $settings, array $options, array $ret ) : array {
		$ret = parent::checkSettings( $name, $settings, $options, $ret );

		$ret['allowedKeys'] = array_merge( $ret['allowedKeys'], [
			self::PARAM_TIMESTAMP_FORMAT,
		] );

		$f = $settings[self::PARAM_TIMESTAMP_FORMAT] ?? $this->defaultFormat;
		if ( $f !== 'ConvertibleTimestamp' && $f !== 'DateTime' &&
			ConvertibleTimestamp::convert( $f, 0 ) === false
		) {
			$ret['issues'][self::PARAM_TIMESTAMP_FORMAT] = 'Value for PARAM_TIMESTAMP_FORMAT is not valid';
		}

		return $ret;
	}

	public function stringifyValue( $name, $value, array $settings, array $options ) {
		if ( !$value instanceof ConvertibleTimestamp ) {
			$value = new ConvertibleTimestamp( $value );
		}
		return $value->getTimestamp( $this->stringifyFormat );
	}

	public function getHelpInfo( $name, array $settings, array $options ) {
		$info = parent::getHelpInfo( $name, $settings, $options );

		$info[ParamValidator::PARAM_TYPE] = MessageValue::new( 'paramvalidator-help-type-timestamp' )
			->params( empty( $settings[ParamValidator::PARAM_ISMULTI] ) ? 1 : 2 );

		return $info;
	}

}
