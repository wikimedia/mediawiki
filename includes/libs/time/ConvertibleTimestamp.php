<?php
/**
 * Creation, parsing, and conversion of timestamps.
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
 * @file
 * @since 1.20
 * @author Tyler Romeo, 2012
 */

/**
 * Library for creating, parsing, and converting timestamps. Based on the JS
 * library that does the same thing.
 *
 * @since 1.28
 */
class ConvertibleTimestamp {
	/**
	 * Standard gmdate() formats for the different timestamp types.
	 */
	private static $formats = [
		TS_UNIX => 'U',
		TS_MW => 'YmdHis',
		TS_DB => 'Y-m-d H:i:s',
		TS_ISO_8601 => 'Y-m-d\TH:i:s\Z',
		TS_ISO_8601_BASIC => 'Ymd\THis\Z',
		TS_EXIF => 'Y:m:d H:i:s', // This shouldn't ever be used, but is included for completeness
		TS_RFC2822 => 'D, d M Y H:i:s',
		TS_ORACLE => 'd-m-Y H:i:s.000000', // Was 'd-M-y h.i.s A' . ' +00:00' before r51500
		TS_POSTGRES => 'Y-m-d H:i:s',
	];

	/**
	 * The actual timestamp being wrapped (DateTime object).
	 * @var DateTime
	 */
	public $timestamp;

	/**
	 * Make a new timestamp and set it to the specified time,
	 * or the current time if unspecified.
	 *
	 * @param bool|string|int|float|DateTime $timestamp Timestamp to set, or false for current time
	 */
	public function __construct( $timestamp = false ) {
		if ( $timestamp instanceof DateTime ) {
			$this->timestamp = $timestamp;
		} else {
			$this->setTimestamp( $timestamp );
		}
	}

	/**
	 * Set the timestamp to the specified time, or the current time if unspecified.
	 *
	 * Parse the given timestamp into either a DateTime object or a Unix timestamp,
	 * and then store it.
	 *
	 * @param string|bool $ts Timestamp to store, or false for now
	 * @throws TimestampException
	 */
	public function setTimestamp( $ts = false ) {
		$m = [];
		$da = [];
		$strtime = '';

		// We want to catch 0, '', null... but not date strings starting with a letter.
		if ( !$ts || $ts === "\0\0\0\0\0\0\0\0\0\0\0\0\0\0" ) {
			$uts = time();
			$strtime = "@$uts";
		} elseif ( preg_match( '/^(\d{4})\-(\d\d)\-(\d\d) (\d\d):(\d\d):(\d\d)$/D', $ts, $da ) ) {
			# TS_DB
		} elseif ( preg_match( '/^(\d{4}):(\d\d):(\d\d) (\d\d):(\d\d):(\d\d)$/D', $ts, $da ) ) {
			# TS_EXIF
		} elseif ( preg_match( '/^(\d{4})(\d\d)(\d\d)(\d\d)(\d\d)(\d\d)$/D', $ts, $da ) ) {
			# TS_MW
		} elseif ( preg_match( '/^(-?\d{1,13})(\.\d+)?$/D', $ts, $m ) ) {
			# TS_UNIX
			$strtime = "@{$m[1]}"; // http://php.net/manual/en/datetime.formats.compound.php
		} elseif ( preg_match( '/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}.\d{6}$/', $ts ) ) {
			# TS_ORACLE // session altered to DD-MM-YYYY HH24:MI:SS.FF6
			$strtime = preg_replace( '/(\d\d)\.(\d\d)\.(\d\d)(\.(\d+))?/', "$1:$2:$3",
				str_replace( '+00:00', 'UTC', $ts ) );
		} elseif ( preg_match(
			'/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(?:\.*\d*)?Z?$/',
			$ts,
			$da
		) ) {
			# TS_ISO_8601
		} elseif ( preg_match(
			'/^(\d{4})(\d{2})(\d{2})T(\d{2})(\d{2})(\d{2})(?:\.*\d*)?Z?$/',
			$ts,
			$da
		) ) {
			# TS_ISO_8601_BASIC
		} elseif ( preg_match(
			'/^(\d{4})\-(\d\d)\-(\d\d) (\d\d):(\d\d):(\d\d)\.*\d*[\+\- ](\d\d)$/',
			$ts,
			$da
		) ) {
			# TS_POSTGRES
		} elseif ( preg_match(
			'/^(\d{4})\-(\d\d)\-(\d\d) (\d\d):(\d\d):(\d\d)\.*\d* GMT$/',
			$ts,
			$da
		) ) {
			# TS_POSTGRES
		} elseif ( preg_match(
		# Day of week
			'/^[ \t\r\n]*([A-Z][a-z]{2},[ \t\r\n]*)?' .
			# dd Mon yyyy
			'\d\d?[ \t\r\n]*[A-Z][a-z]{2}[ \t\r\n]*\d{2}(?:\d{2})?' .
			# hh:mm:ss
			'[ \t\r\n]*\d\d[ \t\r\n]*:[ \t\r\n]*\d\d[ \t\r\n]*:[ \t\r\n]*\d\d/S',
			$ts
		) ) {
			# TS_RFC2822, accepting a trailing comment.
			# See http://www.squid-cache.org/mail-archive/squid-users/200307/0122.html / r77171
			# The regex is a superset of rfc2822 for readability
			$strtime = strtok( $ts, ';' );
		} elseif ( preg_match( '/^[A-Z][a-z]{5,8}, \d\d-[A-Z][a-z]{2}-\d{2} \d\d:\d\d:\d\d/', $ts ) ) {
			# TS_RFC850
			$strtime = $ts;
		} elseif ( preg_match( '/^[A-Z][a-z]{2} [A-Z][a-z]{2} +\d{1,2} \d\d:\d\d:\d\d \d{4}/', $ts ) ) {
			# asctime
			$strtime = $ts;
		} else {
			throw new TimestampException( __METHOD__ . ": Invalid timestamp - $ts" );
		}

		if ( !$strtime ) {
			$da = array_map( 'intval', $da );
			$da[0] = "%04d-%02d-%02dT%02d:%02d:%02d.00+00:00";
			$strtime = call_user_func_array( "sprintf", $da );
		}

		try {
			$final = new DateTime( $strtime, new DateTimeZone( 'GMT' ) );
		} catch ( Exception $e ) {
			throw new TimestampException( __METHOD__ . ': Invalid timestamp format.', $e->getCode(), $e );
		}

		if ( $final === false ) {
			throw new TimestampException( __METHOD__ . ': Invalid timestamp format.' );
		}

		$this->timestamp = $final;
	}

	/**
	 * Convert a timestamp string to a given format.
	 *
	 * @param int $style Constant Output format for timestamp
	 * @param string $ts Timestamp
	 * @return string|bool Formatted timestamp or false on failure
	 */
	public static function convert( $style = TS_UNIX, $ts ) {
		try {
			$ct = new static( $ts );
			return $ct->getTimestamp( $style );
		} catch ( TimestampException $e ) {
			return false;
		}
	}

	/**
	 * Get the current time in the given format
	 *
	 * @param int $style Constant Output format for timestamp
	 * @return string
	 */
	public static function now( $style = TS_MW ) {
		return static::convert( $style, time() );
	}

	/**
	 * Get the timestamp represented by this object in a certain form.
	 *
	 * Convert the internal timestamp to the specified format and then
	 * return it.
	 *
	 * @param int $style Constant Output format for timestamp
	 * @throws TimestampException
	 * @return string The formatted timestamp
	 */
	public function getTimestamp( $style = TS_UNIX ) {
		if ( !isset( self::$formats[$style] ) ) {
			throw new TimestampException( __METHOD__ . ': Illegal timestamp output type.' );
		}

		$output = $this->timestamp->format( self::$formats[$style] );

		if ( ( $style == TS_RFC2822 ) || ( $style == TS_POSTGRES ) ) {
			$output .= ' GMT';
		}

		if ( $style == TS_MW && strlen( $output ) !== 14 ) {
			throw new TimestampException( __METHOD__ . ': The timestamp cannot be represented in ' .
				'the specified format' );
		}

		return $output;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->getTimestamp();
	}

	/**
	 * Calculate the difference between two ConvertibleTimestamp objects.
	 *
	 * @param ConvertibleTimestamp $relativeTo Base time to calculate difference from
	 * @return DateInterval|bool The DateInterval object representing the
	 *   difference between the two dates or false on failure
	 */
	public function diff( ConvertibleTimestamp $relativeTo ) {
		return $this->timestamp->diff( $relativeTo->timestamp );
	}

	/**
	 * Set the timezone of this timestamp to the specified timezone.
	 *
	 * @param string $timezone Timezone to set
	 * @throws TimestampException
	 */
	public function setTimezone( $timezone ) {
		try {
			$this->timestamp->setTimezone( new DateTimeZone( $timezone ) );
		} catch ( Exception $e ) {
			throw new TimestampException( __METHOD__ . ': Invalid timezone.', $e->getCode(), $e );
		}
	}

	/**
	 * Get the timezone of this timestamp.
	 *
	 * @return DateTimeZone The timezone
	 */
	public function getTimezone() {
		return $this->timestamp->getTimezone();
	}

	/**
	 * Format the timestamp in a given format.
	 *
	 * @param string $format Pattern to format in
	 * @return string The formatted timestamp
	 */
	public function format( $format ) {
		return $this->timestamp->format( $format );
	}
}
