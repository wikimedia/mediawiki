<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats;

use Wikimedia\Stats\Emitters\EmitterInterface;
use Wikimedia\Stats\Emitters\NullEmitter;
use Wikimedia\Stats\Emitters\UDPEmitter;
use Wikimedia\Stats\Exceptions\UnsupportedFormatException;
use Wikimedia\Stats\Formatters\DogStatsdFormatter;
use Wikimedia\Stats\Formatters\FormatterInterface;
use Wikimedia\Stats\Formatters\NullFormatter;
use Wikimedia\Stats\Formatters\StatsdFormatter;

/**
 * Metrics Format and Output Helpers
 *
 * @author Cole White
 * @since 1.41
 */
class OutputFormats {

	public const NULL = 1;
	public const STATSD = 2;
	public const DOGSTATSD = 3;

	private const SUPPORTED_FORMATS = [
		'null' => self::NULL,
		'statsd' => self::STATSD,
		'dogstatsd' => self::DOGSTATSD
	];

	/**
	 * Convert friendly format name to integer.
	 *
	 * @param string $format
	 * @return int
	 */
	public static function getFormatFromString( string $format ): int {
		if ( self::SUPPORTED_FORMATS[$format] ?? false ) {
			return self::SUPPORTED_FORMATS[$format];
		}
		throw new UnsupportedFormatException(
			"Format '" . $format . "' not supported. Expected one of "
			. json_encode( array_keys( self::SUPPORTED_FORMATS ) )
		);
	}

	/**
	 * Returns an instance of the requested formatter.
	 *
	 * @param int $format
	 * @return FormatterInterface
	 */
	public static function getNewFormatter( int $format ): FormatterInterface {
		switch ( $format ) {
			case self::DOGSTATSD:
				return new DogStatsdFormatter();
			case self::STATSD:
				return new StatsdFormatter();
			case self::NULL:
				return new NullFormatter();
			default:
				throw new UnsupportedFormatException(
					"Unsupported metrics format '{$format}' - See OutputFormats::class."
				);
		}
	}

	/**
	 * Returns an emitter instance appropriate the formatter instance.
	 *
	 * @param string $prefix
	 * @param StatsCache $cache
	 * @param FormatterInterface $formatter
	 * @param string|null $target
	 * @return EmitterInterface
	 */
	public static function getNewEmitter(
		string $prefix,
		StatsCache $cache,
		FormatterInterface $formatter,
		?string $target = null
	): EmitterInterface {
		$formatterClass = get_class( $formatter );
		switch ( $formatterClass ) {
			case StatsdFormatter::class:
			case DogStatsdFormatter::class:
				return new UDPEmitter( $prefix, $cache, $formatter, $target );
			case NullFormatter::class:
				return new NullEmitter;
			default:
				throw new UnsupportedFormatException( "Unsupported metrics formatter '{$formatterClass}'" );
		}
	}
}
