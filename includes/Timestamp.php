<?php

/**
 * Library for creating and parsing MW-style timestamps. Based on the JS
 * library that does the same thing.
 *
 * @author Tyler Romeo, 2012
 * @since 1.20
 */
class MWTimestamp {
	const TS_UNIX = 0;
	const TS_MW = 1;
	const TS_DB = 2;
	const TS_RFC2822 = 3;
	const TS_ISO_8601 = 4;
	const TS_EXIF = 5;
	const TS_ORACLE = 6;
	const TS_POSTGRES = 7;
	const TS_DB2 = 8;
	const TS_ISO_8601_BASIC = 9;

	/**
	 * Standard gmdate() formats for the different timestamp types.
	 */
	private static $formats = array(
		self::TS_UNIX => 'U',
		self::TS_MW => 'YmdHis',
		self::TS_DB => 'Y-m-d H:i:s',
		self::TS_ISO_8601 => 'Y-m-d\TH:i:s\Z',
		self::TS_ISO_8601_BASIC => 'Ymd\THis\Z',
		self::TS_EXIF => 'Y:m:d H:i:s', // This shouldn't ever be used, but is included for completeness
		self::TS_RFC2822 => 'D, d M Y H:i:s',
		self::TS_ORACLE => 'd-m-Y H:i:s.000000', // Was 'd-M-y h.i.s A' . ' +00:00' before r51500
		self::TS_POSTGRES => 'Y-m-d H:i:s',
		self::TS_DB2 => 'Y-m-d H:i:s',
	);

	/**
	 * Different units for human readable timestamps.
	 * @see MWTimestamp::getHumanTimestamp
	 */
	private static $units = array(
		"milliseconds" => 1,
		"seconds" => 1000, // 1000 milliseconds per second
		"minutes" => 60, // 60 seconds per minute
		"hours" => 60, // 60 minutes per hour
		"days" => 24 // 24 hours per day
	);

	/**
	 * The actual timestamp being wrapped. Either a DateTime
	 * object or a string with a Unix timestamp depending on
	 * PHP.
	 */
	private $timestamp;

	/**
	 * Make a new timestamp and set it to the specified time,
	 * or the current time if unspecified.
	 *
	 * @param $timestamp Timestamp to set, or false for current time
	 */
	public function __construct( $timestamp = false ) {
		$this->setTimestamp( $timestamp );
	}

	/**
	 * Set the timestamp to the specified time, or the current time if unspecified.
	 *
	 * Parse the given timestamp into either a DateTime object or a Unix teimstamp,
	 * and then store it.
	 *
	 * @param $ts Timestamp to store, or false for now
	 */
	public function setTimestamp( $ts = false ) {
		$uts = 0;
		$da = array();
		$strtime = '';

		if ( !$ts ) { // We want to catch 0, '', null... but not date strings starting with a letter.
			$uts = time();
			$strtime = "@$uts";
		} elseif ( preg_match( '/^(\d{4})\-(\d\d)\-(\d\d) (\d\d):(\d\d):(\d\d)$/D', $ts, $da ) ) {
			# TS_DB
		} elseif ( preg_match( '/^(\d{4}):(\d\d):(\d\d) (\d\d):(\d\d):(\d\d)$/D', $ts, $da ) ) {
			# TS_EXIF
		} elseif ( preg_match( '/^(\d{4})(\d\d)(\d\d)(\d\d)(\d\d)(\d\d)$/D', $ts, $da ) ) {
			# TS_MW
		} elseif ( preg_match( '/^-?\d{1,13}$/D', $ts ) ) {
			# TS_UNIX
			$uts = $ts;
			$strtime = "@$ts"; // http://php.net/manual/en/datetime.formats.compound.php
		} elseif ( preg_match( '/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}.\d{6}$/', $ts ) ) {
			# TS_ORACLE // session altered to DD-MM-YYYY HH24:MI:SS.FF6
			$strtime = preg_replace( '/(\d\d)\.(\d\d)\.(\d\d)(\.(\d+))?/', "$1:$2:$3",
					str_replace( '+00:00', 'UTC', $ts ) );
		} elseif ( preg_match( '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(?:\.*\d*)?Z$/', $ts, $da ) ) {
			# TS_ISO_8601
		} elseif ( preg_match( '/^(\d{4})(\d{2})(\d{2})T(\d{2})(\d{2})(\d{2})(?:\.*\d*)?Z$/', $ts, $da ) ) {
			#TS_ISO_8601_BASIC
		} elseif ( preg_match( '/^(\d{4})\-(\d\d)\-(\d\d) (\d\d):(\d\d):(\d\d)\.*\d*[\+\- ](\d\d)$/', $ts, $da ) ) {
			# TS_POSTGRES
		} elseif ( preg_match( '/^(\d{4})\-(\d\d)\-(\d\d) (\d\d):(\d\d):(\d\d)\.*\d* GMT$/', $ts, $da ) ) {
			# TS_POSTGRES
		} elseif (preg_match( '/^(\d{4})\-(\d\d)\-(\d\d) (\d\d):(\d\d):(\d\d)\.\d\d\d$/', $ts, $da ) ) {
			# TS_DB2
		} elseif ( preg_match( '/^[ \t\r\n]*([A-Z][a-z]{2},[ \t\r\n]*)?' . # Day of week
								'\d\d?[ \t\r\n]*[A-Z][a-z]{2}[ \t\r\n]*\d{2}(?:\d{2})?' .  # dd Mon yyyy
								'[ \t\r\n]*\d\d[ \t\r\n]*:[ \t\r\n]*\d\d[ \t\r\n]*:[ \t\r\n]*\d\d/S', $ts ) ) { # hh:mm:ss
			# TS_RFC2822, accepting a trailing comment. See http://www.squid-cache.org/mail-archive/squid-users/200307/0122.html / r77171
			# The regex is a superset of rfc2822 for readability
			$strtime = strtok( $ts, ';' );
		} elseif ( preg_match( '/^[A-Z][a-z]{5,8}, \d\d-[A-Z][a-z]{2}-\d{2} \d\d:\d\d:\d\d/', $ts ) ) {
			# TS_RFC850
			$strtime = $ts;
		} elseif ( preg_match( '/^[A-Z][a-z]{2} [A-Z][a-z]{2} +\d{1,2} \d\d:\d\d:\d\d \d{4}/', $ts ) ) {
			# asctime
			$strtime = $ts;
		} else {
			throw new TimestampException( __METHOD__ . " : Invalid timestamp - $ts" );
		}

		if( !$strtime ) {
			$da = array_map( 'intval', $da );
			$da[0] = "%04d-%02d-%02dT%02d:%02d:%02d.00+00:00";
			$strtime = call_user_func_array( "sprintf", $da );
		}

		if( function_exists( "date_create" ) ) {
			try {
				$final = new DateTime( $strtime, new DateTimeZone( 'GMT' ) );
			} catch(Exception $e) {
				throw new TimestampException( __METHOD__ . 'Invalid timestamp format.' );
			}
		} else {
			$final = strtotime( $strtime );
		}

		if( $final === false ) {
			throw new TimestampException( __METHOD__ . 'Invalid timestamp format.' );
		}
		$this->timestamp = $final;
	}

	/**
	 * Get the timestamp represented by this object in a certain form.
	 *
	 * Convert the internal timestamp to the specified format and then
	 * return it.
	 *
	 * @param $style Output format for timestamp
	 * @return string The formatted timestamp
	 */
	public function getTimestamp( $style = self::TS_UNIX ) {
		if( !isset( self::$formats[$style] ) ) {
			throw new TimestampException( __METHOD__ . ' : Illegal timestamp output type.' );
		}

		if( is_object( $this->timestamp ) ) {
			// DateTime object was used, call DateTime::format.
			$output = $this->timestamp->format( self::$formats[$style] );
		} elseif( self::TS_UNIX == $style ) {
			// Unix timestamp was used and is wanted, just return it.
			$output = $this->timestamp;
		} else {
			// Unix timestamp was used, use gmdate().
			$output = gmdate( self::$formats[$style], $this->timestamp );
		}

		if ( ( $style == self::TS_RFC2822 ) || ( $style == self::TS_POSTGRES ) ) {
			$output .= ' GMT';
		}

		return $output;
	}

	/**
	 * Get the timestamp in a human-friendly relative format, e.g., "3 days ago".
	 *
	 * Determine the difference between the timestamp and the current time, and
	 * generate a readable timestamp by returning "<N> <units> ago", where the
	 * largest possible unit is used.
	 *
	 * @return string Formatted timestamp
	 */
	public function getHumanTimestamp() {
		$then = $this->getTimestamp( self::TS_UNIX );
		$now = time();
		$timeago = ($now - $then) * 1000;
		$message = false;

		foreach( self::$units as $unit => $factor ) {
			$next = $timeago / $factor;
			if( $next < 1 ) {
				break;
			} else {
				$timeago = $next;
				$message = array( $unit, floor( $timeago ) );
			}
		}

		if( $message ) {
			$initial = call_user_func_array( 'wfMessage', $message );
			return wfMessage( 'ago', $initial );
		} else {
			return wfMessage( 'just-now' );
		}
	}

	public function __toString() {
		return $this->getTimestamp();
	}
}

class TimestampException extends MWException {}
