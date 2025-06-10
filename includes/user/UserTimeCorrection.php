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

namespace MediaWiki\User;

use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;
use MediaWiki\Utils\MWTimestamp;
use Stringable;
use Wikimedia\RequestTimeout\TimeoutException;

/**
 * Utility class to parse the TimeCorrection string value.
 *
 * These values are used to specify the time offset for a user and are stored in
 * the database as a user preference and returned by the preferences APIs
 *
 * The class will correct invalid input and adjusts timezone offsets to applicable dates,
 * taking into account DST etc.
 *
 * @since 1.37
 * @ingroup User
 * @author Derk-Jan Hartman <hartman.wiki@gmail.com>
 */
class UserTimeCorrection implements Stringable {

	/**
	 * @var string (default) Time correction based on the MediaWiki's system offset from UTC.
	 * The System offset can be configured with wgLocalTimezone and/or wgLocalTZoffset
	 */
	public const SYSTEM = 'System';

	/** @var string Time correction based on a user defined offset from UTC */
	public const OFFSET = 'Offset';

	/** @var string Time correction based on a user defined timezone */
	public const ZONEINFO = 'ZoneInfo';

	/** @var DateTime */
	private $date;

	/** @var bool */
	private $valid;

	/** @var string */
	private $correctionType;

	/** @var int Offset in minutes */
	private $offset;

	/** @var DateTimeZone|null */
	private $timeZone;

	/**
	 * @param string $timeCorrection Original time correction string
	 * @param DateTime|null $relativeToDate The date used to calculate the time zone offset of.
	 *            This defaults to the current date and time.
	 * @param int $systemOffset Offset for self::SYSTEM in minutes
	 */
	public function __construct(
		string $timeCorrection,
		?DateTime $relativeToDate = null,
		int $systemOffset = 0
	) {
		$this->date = $relativeToDate ?? new DateTime( '@' . MWTimestamp::time() );
		$this->valid = false;
		$this->parse( $timeCorrection, $systemOffset );
	}

	/**
	 * Get time offset for a user
	 *
	 * @return string Offset that was applied to the user
	 */
	public function getCorrectionType(): string {
		return $this->correctionType;
	}

	/**
	 * Get corresponding time offset for this correction
	 * Note: When correcting dates/times, apply only the offset OR the time zone, not both.
	 * @return int Offset in minutes
	 */
	public function getTimeOffset(): int {
		return $this->offset;
	}

	/**
	 * Get corresponding time offset for this correction
	 * Note: When correcting dates/times, apply only the offset OR the time zone, not both.
	 * @return DateInterval Offset in minutes as a DateInterval
	 */
	public function getTimeOffsetInterval(): DateInterval {
		$offset = abs( $this->offset );
		$interval = new DateInterval( "PT{$offset}M" );
		if ( $this->offset < 1 ) {
			$interval->invert = 1;
		}
		return $interval;
	}

	/**
	 * The time zone if known
	 * Note: When correcting dates/times, apply only the offset OR the time zone, not both.
	 * @return DateTimeZone|null
	 */
	public function getTimeZone(): ?DateTimeZone {
		return $this->timeZone;
	}

	/**
	 * Was the original correction specification valid
	 */
	public function isValid(): bool {
		return $this->valid;
	}

	/**
	 * Parse the timecorrection string as stored in the database for a user
	 * or as entered into the Preferences form field
	 *
	 * There can be two forms of these strings:
	 * 1. A pipe separated tuple of a maximum of 3 fields
	 *    - Field 1 is the type of offset definition
	 *    - Field 2 is the offset in minutes from UTC (ignored for System type)
	 *      FIXME Since it's ignored, remove the offset from System everywhere.
	 *    - Field 3 is a timezone identifier from the tz database (only required for ZoneInfo type)
	 *    - The offset for a ZoneInfo type is unreliable because of DST.
	 *      After retrieving it from the database, it should be recalculated based on the TZ identifier.
	 *    Examples:
	 *    - System
	 *    - System|60
	 *    - Offset|60
	 *    - ZoneInfo|60|Europe/Amsterdam
	 *
	 * 2. The following form provides an offset in hours and minutes
	 *    This currently should only be used by the preferences input field,
	 *    but historically they were present in the database.
	 *    TODO: write a maintenance script to migrate these old db values
	 *    Examples:
	 *    - 16:00
	 *    - 10
	 *
	 * @param string $timeCorrection
	 * @param int $systemOffset
	 */
	private function parse( string $timeCorrection, int $systemOffset ) {
		$data = explode( '|', $timeCorrection, 3 );

		// First handle the case of an actual timezone being specified.
		if ( $data[0] === self::ZONEINFO ) {
			try {
				$this->correctionType = self::ZONEINFO;
				$this->timeZone = new DateTimeZone( $data[2] );
				$this->offset = (int)floor( $this->timeZone->getOffset( $this->date ) / 60 );
				$this->valid = true;
				return;
			} catch ( TimeoutException $e ) {
				throw $e;
			} catch ( Exception ) {
				// Not a valid/known timezone.
				// Fall back to any specified offset
			}
		}

		// If $timeCorrection is in fact a pipe-separated value, check the
		// first value.
		switch ( $data[0] ) {
			case self::OFFSET:
			case self::ZONEINFO:
				$this->correctionType = self::OFFSET;
				// First value is Offset, so use the specified offset
				$this->offset = (int)( $data[1] ?? 0 );
				// If this is ZoneInfo, then we didn't recognize the TimeZone
				$this->valid = isset( $data[1] ) && $data[0] === self::OFFSET;
				break;
			case self::SYSTEM:
				$this->correctionType = self::SYSTEM;
				$this->offset = $systemOffset;
				$this->valid = true;
				break;
			default:
				// $timeCorrection actually isn't a pipe separated value, but instead
				// a colon separated value. This is only used by the HTMLTimezoneField userinput
				// but can also still be present in the Db. (but shouldn't be)
				$this->correctionType = self::OFFSET;
				$data = explode( ':', $timeCorrection, 2 );
				if ( count( $data ) >= 2 ) {
					// Combination hours and minutes.
					$this->offset = abs( (int)$data[0] ) * 60 + (int)$data[1];
					if ( (int)$data[0] < 0 ) {
						$this->offset *= -1;
					}
					$this->valid = true;
				} elseif ( preg_match( '/^[+-]?\d+$/', $data[0] ) ) {
					// Just hours.
					$this->offset = (int)$data[0] * 60;
					$this->valid = true;
				} else {
					// We really don't know this. Fallback to System
					$this->correctionType = self::SYSTEM;
					$this->offset = $systemOffset;
					return;
				}
				break;
		}

		// Max is +14:00 and min is -12:00, see:
		// https://en.wikipedia.org/wiki/Timezone
		if ( $this->offset < -12 * 60 || $this->offset > 14 * 60 ) {
			$this->valid = false;
		}
		// 14:00
		$this->offset = min( $this->offset, 14 * 60 );
		// -12:00
		$this->offset = max( $this->offset, -12 * 60 );
	}

	/**
	 * Converts a timezone offset in minutes (e.g., "120") to an hh:mm string like "+02:00".
	 * @param int $offset
	 * @return string
	 */
	public static function formatTimezoneOffset( int $offset ): string {
		$hours = $offset > 0 ? floor( $offset / 60 ) : ceil( $offset / 60 );
		return sprintf( '%+03d:%02d', $hours, abs( $offset ) % 60 );
	}

	/**
	 * Note: The string value of this object might not be equal to the original value
	 * @return string a timecorrection string representing this value
	 */
	public function toString(): string {
		switch ( $this->correctionType ) {
			case self::ZONEINFO:
				if ( $this->timeZone ) {
					return "ZoneInfo|{$this->offset}|{$this->timeZone->getName()}";
				}
				// If not, fallback:
			case self::SYSTEM:
			case self::OFFSET:
			default:
				return "{$this->correctionType}|{$this->offset}";
		}
	}

	public function __toString() {
		return $this->toString();
	}
}
