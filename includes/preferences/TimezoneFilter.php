<?php
/**
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
 * @file
 */

namespace MediaWiki\Preferences;

use DateTimeZone;
use Exception;

class TimezoneFilter implements Filter {

	/**
	 * @inheritDoc
	 */
	public function filterForForm( $value ) {
		return $value;
	}

	/**
	 * @inheritDoc
	 */
	public function filterFromForm( $tz ) {
		$data = explode( '|', $tz, 3 );
		switch ( $data[0] ) {
			case 'ZoneInfo':
				$valid = false;

				if ( count( $data ) === 3 ) {
					// Make sure this timezone exists
					try {
						new DateTimeZone( $data[2] );
						// If the constructor didn't throw, we know it's valid
						$valid = true;
					} catch ( Exception $e ) {
						// Not a valid timezone
					}
				}

				if ( !$valid ) {
					// If the supplied timezone doesn't exist, fall back to the encoded offset
					return 'Offset|' . intval( $tz[1] );
				}
				return $tz;
			case 'System':
				return $tz;
			default:
				$data = explode( ':', $tz, 2 );
				if ( count( $data ) == 2 ) {
					$data[0] = intval( $data[0] );
					$data[1] = intval( $data[1] );
					$minDiff = abs( $data[0] ) * 60 + $data[1];
					if ( $data[0] < 0 ) {
						$minDiff = - $minDiff;
					}
				} else {
					$minDiff = intval( $data[0] ) * 60;
				}

				# Max is +14:00 and min is -12:00, see:
				# https://en.wikipedia.org/wiki/Timezone
				# 14:00
				$minDiff = min( $minDiff, 840 );
				# -12:00
				$minDiff = max( $minDiff, -720 );
				return 'Offset|' . $minDiff;
		}
	}
}
