<?php
/**
 * Creation and parsing of MW-style timestamps.
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
 * Library for creating and parsing MW-style timestamps. Based on the JS
 * library that does the same thing.
 *
 * @since 1.20
 */
class MWTimestamp {
	/**
	 * Standard gmdate() formats for the different timestamp types.
	 */
	private static $formats = array(
		TS_UNIX => 'U',
		TS_MW => 'YmdHis',
		TS_DB => 'Y-m-d H:i:s',
		TS_ISO_8601 => 'Y-m-d\TH:i:s\Z',
		TS_ISO_8601_BASIC => 'Ymd\THis\Z',
		TS_EXIF => 'Y:m:d H:i:s', // This shouldn't ever be used, but is included for completeness
		TS_RFC2822 => 'D, d M Y H:i:s',
		TS_ORACLE => 'd-m-Y H:i:s.000000', // Was 'd-M-y h.i.s A' . ' +00:00' before r51500
		TS_POSTGRES => 'Y-m-d H:i:s',
	);

	/**
	 * The actual timestamp being wrapped (DateTime object).
	 * @var DateTime
	 */
	public $timestamp;

	/**
	 * Make a new timestamp and set it to the specified time,
	 * or the current time if unspecified.
	 *
	 * @since 1.20
	 *
	 * @param bool|string $timestamp Timestamp to set, or false for current time
	 */
	public function __construct( $timestamp = false ) {
		$this->setTimestamp( $timestamp );
	}

	/**
	 * Set the timestamp to the specified time, or the current time if unspecified.
	 *
	 * Parse the given timestamp into either a DateTime object or a Unix timestamp,
	 * and then store it.
	 *
	 * @since 1.20
	 *
	 * @param string|bool $ts Timestamp to store, or false for now
	 * @throws TimestampException
	 */
	public function setTimestamp( $ts = false ) {
		$da = array();
		$strtime = '';

		if ( !$ts || $ts === "\0\0\0\0\0\0\0\0\0\0\0\0\0\0" ) { // We want to catch 0, '', null... but not date strings starting with a letter.
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
	 * Get the timestamp represented by this object in a certain form.
	 *
	 * Convert the internal timestamp to the specified format and then
	 * return it.
	 *
	 * @since 1.20
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

		return $output;
	}

	/**
	 * Get the timestamp in a human-friendly relative format, e.g., "3 days ago".
	 *
	 * Determine the difference between the timestamp and the current time, and
	 * generate a readable timestamp by returning "<N> <units> ago", where the
	 * largest possible unit is used.
	 *
	 * @since 1.20
	 * @since 1.22 Uses Language::getHumanTimestamp to produce the timestamp
	 *
	 * @param MWTimestamp|null $relativeTo The base timestamp to compare to (defaults to now)
	 * @param User|null $user User the timestamp is being generated for (or null to use main context's user)
	 * @param Language|null $lang Language to use to make the human timestamp (or null to use main context's language)
	 * @return string Formatted timestamp
	 */
	public function getHumanTimestamp( MWTimestamp $relativeTo = null, User $user = null, Language $lang = null ) {
		if ( $relativeTo === null ) {
			$relativeTo = new self();
		}
		if ( $user === null ) {
			$user = RequestContext::getMain()->getUser();
		}
		if ( $lang === null ) {
			$lang = RequestContext::getMain()->getLanguage();
		}

		// Adjust for the user's timezone.
		$offsetThis = $this->offsetForUser( $user );
		$offsetRel = $relativeTo->offsetForUser( $user );

		$ts = '';
		if ( wfRunHooks( 'GetHumanTimestamp', array( &$ts, $this, $relativeTo, $user, $lang ) ) ) {
			$ts = $lang->getHumanTimestamp( $this, $relativeTo, $user );
		}

		// Reset the timezone on the objects.
		$this->timestamp->sub( $offsetThis );
		$relativeTo->timestamp->sub( $offsetRel );

		return $ts;
	}

	/**
	 * Adjust the timestamp depending on the given user's preferences.
	 *
	 * @since 1.22
	 *
	 * @param User $user User to take preferences from
	 * @param[out] MWTimestamp $ts Timestamp to adjust
	 * @return DateInterval Offset that was applied to the timestamp
	 */
	public function offsetForUser( User $user ) {
		global $wgLocalTZoffset;

		$option = $user->getOption( 'timecorrection' );
		$data = explode( '|', $option, 3 );

		// First handle the case of an actual timezone being specified.
		if ( $data[0] == 'ZoneInfo' ) {
			try {
				$tz = new DateTimeZone( $data[2] );
			} catch ( Exception $e ) {
				$tz = false;
			}

			if ( $tz ) {
				$this->timestamp->setTimezone( $tz );
				return new DateInterval( 'P0Y' );
			} else {
				$data[0] = 'Offset';
			}
		}

		$diff = 0;
		// If $option is in fact a pipe-separated value, check the
		// first value.
		if ( $data[0] == 'System' ) {
			// First value is System, so use the system offset.
			if ( isset( $wgLocalTZoffset ) ) {
				$diff = $wgLocalTZoffset;
			}
		} elseif ( $data[0] == 'Offset' ) {
			// First value is Offset, so use the specified offset
			$diff = (int)$data[1];
		} else {
			// $option actually isn't a pipe separated value, but instead
			// a comma separated value. Isn't MediaWiki fun?
			$data = explode( ':', $option );
			if ( count( $data ) >= 2 ) {
				// Combination hours and minutes.
				$diff = abs( (int)$data[0] ) * 60 + (int)$data[1];
				if ( (int)$data[0] < 0 ) {
					$diff *= -1;
				}
			} else {
				// Just hours.
				$diff = (int)$data[0] * 60;
			}
		}

		$interval = new DateInterval( 'PT' . abs( $diff ) . 'M' );
		if ( $diff < 1 ) {
			$interval->invert = 1;
		}

		$this->timestamp->add( $interval );
		return $interval;
	}

	/**
	 * Generate a purely relative timestamp, i.e., represent the time elapsed between
	 * the given base timestamp and this object.
	 *
	 * @param MWTimestamp $relativeTo Relative base timestamp (defaults to now)
	 * @param User $user Use to use offset for
	 * @param Language $lang Language to use
	 * @param array $chosenIntervals Intervals to use to represent it
	 * @return string Relative timestamp
	 */
	public function getRelativeTimestamp(
		MWTimestamp $relativeTo = null,
		User $user = null,
		Language $lang = null,
		array $chosenIntervals = array()
	) {
		if ( $relativeTo === null ) {
			$relativeTo = new self;
		}
		if ( $user === null ) {
			$user = RequestContext::getMain()->getUser();
		}
		if ( $lang === null ) {
			$lang = RequestContext::getMain()->getLanguage();
		}

		$ts = '';
		$diff = $this->diff( $relativeTo );
		if ( wfRunHooks( 'GetRelativeTimestamp', array( &$ts, &$diff, $this, $relativeTo, $user, $lang ) ) ) {
			$seconds = ( ( ( $diff->days * 24 + $diff->h ) * 60 + $diff->i ) * 60 + $diff->s );
			$ts = wfMessage( 'ago', $lang->formatDuration( $seconds, $chosenIntervals ) )
				->inLanguage( $lang )
				->text();
		}

		return $ts;
	}

	/**
	 * @since 1.20
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->getTimestamp();
	}

	/**
	 * Calculate the difference between two MWTimestamp objects.
	 *
	 * @since 1.22
	 * @param MWTimestamp $relativeTo Base time to calculate difference from
	 * @return DateInterval|bool The DateInterval object representing the difference between the two dates or false on failure
	 */
	public function diff( MWTimestamp $relativeTo ) {
		return $this->timestamp->diff( $relativeTo->timestamp );
	}

	/**
	 * Set the timezone of this timestamp to the specified timezone.
	 *
	 * @since 1.22
	 * @param String $timezone Timezone to set
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
	 * @since 1.22
	 * @return DateTimeZone The timezone
	 */
	public function getTimezone() {
		return $this->timestamp->getTimezone();
	}

	/**
	 * Format the timestamp in a given format.
	 *
	 * @since 1.22
	 * @param string $format Pattern to format in
	 * @return string The formatted timestamp
	 */
	public function format( $format ) {
		return $this->timestamp->format( $format );
	}

	/**
	 * Get a timestamp instance in the server local timezone ($wgLocaltimezone)
	 *
	 * @since 1.22
	 * @param bool|string $ts Timestamp to set, or false for current time
	 * @return MWTimestamp the local instance
	 */
	public static function getLocalInstance( $ts = false ) {
		global $wgLocaltimezone;
		$timestamp = new self( $ts );
		$timestamp->setTimezone( $wgLocaltimezone );
		return $timestamp;
	}

	/**
	 * Get a timestamp instance in GMT
	 *
	 * @since 1.22
	 * @param bool|string $ts Timestamp to set, or false for current time
	 * @return MWTimestamp the instance
	 */
	public static function getInstance( $ts = false ) {
		return new self( $ts );
	}
}

/**
 * @since 1.20
 */
class TimestampException extends MWException {}
