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
use Wikimedia\Timestamp\TimestampFormat;

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
	 * (TimestampFormat|string|int) Timestamp format to return from validate()
	 *
	 * Values include:
	 *  - 'ConvertibleTimestamp': A ConvertibleTimestamp object.
	 *  - 'DateTime': A PHP DateTime object
	 *  - A value from the {@link TimestampFormat} enum.
	 *  - (deprecated) One of ConvertibleTimestamp's TS_* constants.
	 *
	 * This does not affect the format returned by stringifyValue().
	 */
	public const PARAM_TIMESTAMP_FORMAT = 'param-timestamp-format';

	/** @var TimestampFormat|string|int */
	protected $defaultFormat;

	/** @var TimestampFormat|int */
	protected $stringifyFormat;

	/**
	 * @param Callbacks $callbacks
	 * @param array $options Options:
	 *  - defaultFormat: (TimestampFormat|string|int) Default for PARAM_TIMESTAMP_FORMAT.
	 *    Default if not specified is 'ConvertibleTimestamp'.
	 *  - stringifyFormat: (TimestampFormat|int) Format to use for stringifyValue().
	 *    Default is TimestampFormat::ISO_8601.
	 */
	public function __construct( Callbacks $callbacks, array $options = [] ) {
		parent::__construct( $callbacks );

		$this->defaultFormat = $options['defaultFormat'] ?? 'ConvertibleTimestamp';
		$this->stringifyFormat = $options['stringifyFormat'] ?? TimestampFormat::ISO_8601;

		if ( !$this->isSpecialFormat( $this->defaultFormat ) && !$this->isValidFormat( $this->defaultFormat ) ) {
			throw new InvalidArgumentException( 'Invalid value for $options[\'defaultFormat\']' );
		}
		if ( !$this->isValidFormat( $this->stringifyFormat ) ) {
			throw new InvalidArgumentException( 'Invalid value for $options[\'stringifyFormat\']' );
		}
	}

	private function isSpecialFormat( mixed $format ): bool {
		return $format === 'ConvertibleTimestamp' || $format === 'DateTime';
	}

	private function isValidFormat( mixed $format ): bool {
		// Leave validation up to the wikimedia/timestamp library.
		$ts = new ConvertibleTimestamp();
		try {
			$ts->getTimestamp( $format );
		} catch ( InvalidArgumentException ) {
			return false;
		}
		return true;
	}

	/** @inheritDoc */
	public function validate( $name, $value, array $settings, array $options ) {
		// Confusing synonyms for the current time accepted by ConvertibleTimestamp
		if ( !$value ) {
			$this->failure( 'unclearnowtimestamp', $name, $value, $settings, $options, false );
			$value = 'now';
		}

		/** @var TimestampFormat|string|int $format */
		$format = $settings[self::PARAM_TIMESTAMP_FORMAT] ?? $this->defaultFormat;

		try {
			$timestampObj = new ConvertibleTimestamp( $value === 'now' ? false : $value );

			$timestamp = $this->isSpecialFormat( $format ) ? null : $timestampObj->getTimestamp( $format );
		} catch ( TimestampException $ex ) {
			// $this->failure() doesn't handle passing a previous exception
			throw new ValidationException(
				$this->failureMessage( 'badtimestamp' )->plaintextParams( $name, $value ),
				$name, $value, $settings, $ex
			);
		}

		return match ( $format ) {
			'ConvertibleTimestamp' => $timestampObj,
			// Eew, no getter.
			'DateTime' => $timestampObj->timestamp,
			default => $timestamp,
		};
	}

	/** @inheritDoc */
	public function checkSettings( string $name, $settings, array $options, array $ret ): array {
		$ret = parent::checkSettings( $name, $settings, $options, $ret );

		$ret['allowedKeys'][] = self::PARAM_TIMESTAMP_FORMAT;

		$f = $settings[self::PARAM_TIMESTAMP_FORMAT] ?? $this->defaultFormat;
		if ( !$this->isSpecialFormat( $f ) && !$this->isValidFormat( $f ) ) {
			$ret['issues'][self::PARAM_TIMESTAMP_FORMAT] = 'Value for PARAM_TIMESTAMP_FORMAT is not valid';
		}

		return $ret;
	}

	/** @inheritDoc */
	public function stringifyValue( $name, $value, array $settings, array $options ) {
		if ( !$value instanceof ConvertibleTimestamp ) {
			$value = new ConvertibleTimestamp( $value );
		}
		return $value->getTimestamp( $this->stringifyFormat );
	}

	/** @inheritDoc */
	public function getHelpInfo( $name, array $settings, array $options ) {
		$info = parent::getHelpInfo( $name, $settings, $options );

		$info[ParamValidator::PARAM_TYPE] = MessageValue::new( 'paramvalidator-help-type-timestamp' )
			->params( empty( $settings[ParamValidator::PARAM_ISMULTI] ) ? 1 : 2 );

		return $info;
	}

}
