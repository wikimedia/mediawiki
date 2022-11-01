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
}
