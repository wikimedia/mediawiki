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
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Library for creating and parsing MW-style timestamps. Based on the JS
 * library that does the same thing.
 *
 * @since 1.20
 */
class MWTimestamp extends ConvertibleTimestamp {
	/**
	 * Get a timestamp instance in GMT
	 *
	 * @param bool|string $ts Timestamp to set, or false for current time
	 * @return MWTimestamp The instance
	 */
	public static function getInstance( $ts = false ) {
		return new static( $ts );
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
	 * @deprecated since 1.26 Use Language::getHumanTimestamp directly
	 *
	 * @param MWTimestamp|null $relativeTo The base timestamp to compare to (defaults to now)
	 * @param User|null $user User the timestamp is being generated for
	 *  (or null to use main context's user)
	 * @param Language|null $lang Language to use to make the human timestamp
	 *  (or null to use main context's language)
	 * @return string Formatted timestamp
	 */
	public function getHumanTimestamp(
		MWTimestamp $relativeTo = null, User $user = null, Language $lang = null
	) {
		if ( $lang === null ) {
			$lang = RequestContext::getMain()->getLanguage();
		}

		return $lang->getHumanTimestamp( $this, $relativeTo, $user );
	}

	/**
	 * Adjust the timestamp depending on the given user's preferences.
	 *
	 * @since 1.22
	 *
	 * @param User $user User to take preferences from
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
			if ( $wgLocalTZoffset !== null ) {
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
		array $chosenIntervals = []
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
		if ( Hooks::run(
			'GetRelativeTimestamp',
			[ &$ts, &$diff, $this, $relativeTo, $user, $lang ]
		) ) {
			$seconds = ( ( ( $diff->days * 24 + $diff->h ) * 60 + $diff->i ) * 60 + $diff->s );
			$ts = wfMessage( 'ago', $lang->formatDuration( $seconds, $chosenIntervals ) )
				->inLanguage( $lang )->text();
		}

		return $ts;
	}

	/**
	 * Get the localized timezone message, if available.
	 *
	 * Premade translations are not shipped as format() may return whatever the
	 * system uses, localized or not, so translation must be done through wiki.
	 *
	 * @since 1.27
	 * @return Message The localized timezone message
	 */
	public function getTimezoneMessage() {
		$tzMsg = $this->format( 'T' );  // might vary on DST changeover!
		$key = 'timezone-' . strtolower( trim( $tzMsg ) );
		$msg = wfMessage( $key );
		if ( $msg->exists() ) {
			return $msg;
		} else {
			return new RawMessage( $tzMsg );
		}
	}

	/**
	 * Get a timestamp instance in the server local timezone ($wgLocaltimezone)
	 *
	 * @since 1.22
	 * @param bool|string $ts Timestamp to set, or false for current time
	 * @return MWTimestamp The local instance
	 */
	public static function getLocalInstance( $ts = false ) {
		global $wgLocaltimezone;
		$timestamp = new self( $ts );
		$timestamp->setTimezone( $wgLocaltimezone );
		return $timestamp;
	}
}
