<?php
/**
 * Metrics Format helpers
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @license GPL-2.0-or-later
 * @author Cole White
 * @since 1.41
 */

declare( strict_types=1 );

namespace Wikimedia\Metrics;

use Wikimedia\Metrics\Exceptions\UnsupportedFormatException;
use Wikimedia\Metrics\Formatters\DogStatsdFormatter;
use Wikimedia\Metrics\Formatters\FormatterInterface;
use Wikimedia\Metrics\Formatters\NullFormatter;
use Wikimedia\Metrics\Formatters\StatsdFormatter;

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
					"Format '" . $format . "' not supported. Expected one of "
					. json_encode( array_keys( self::SUPPORTED_FORMATS ) )
				);
		}
	}
}
