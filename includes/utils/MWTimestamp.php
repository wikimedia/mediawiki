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

namespace MediaWiki\Utils;

use DateInterval;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\Language;
use MediaWiki\Language\RawMessage;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserTimeCorrection;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Library for creating and parsing MW-style timestamps. Based on the JS
 * library that does the same thing.
 *
 * @newable
 *
 * @since 1.20
 */
class MWTimestamp extends ConvertibleTimestamp {
	/**
	 * Get a timestamp instance in GMT
	 *
	 * @param bool|string $ts Timestamp to set, or false for current time
	 */
	public static function getInstance( $ts = false ): static {
		return new static( $ts );
	}

	/**
	 * Adjust the timestamp depending on the given user's preferences.
	 *
	 * @since 1.22
	 *
	 * @param UserIdentity $user User to take preferences from
	 * @return DateInterval Offset that was applied to the timestamp
	 */
	public function offsetForUser( UserIdentity $user ) {
		$option = MediaWikiServices::getInstance()->getUserOptionsLookup()->getOption( $user, 'timecorrection' );

		$value = new UserTimeCorrection(
			$option,
			$this->timestamp,
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::LocalTZoffset )
		);
		$tz = $value->getTimeZone();
		if ( $tz ) {
			$this->timestamp->setTimezone( $tz );
			return new DateInterval( 'P0Y' );
		}
		$interval = $value->getTimeOffsetInterval();
		$this->timestamp->add( $interval );
		return $interval;
	}

	/**
	 * Generate a purely relative timestamp, i.e., represent the time elapsed between
	 * the given base timestamp and this object.
	 *
	 * @param MWTimestamp|null $relativeTo Relative base timestamp (defaults to now)
	 * @param UserIdentity|null $user Use to use offset for
	 * @param Language|null $lang Language to use
	 * @param array $chosenIntervals Intervals to use to represent it
	 * @return string Relative timestamp
	 */
	public function getRelativeTimestamp(
		?MWTimestamp $relativeTo = null,
		?UserIdentity $user = null,
		?Language $lang = null,
		array $chosenIntervals = []
	) {
		$relativeTo ??= new self();
		$user ??= RequestContext::getMain()->getUser();
		$lang ??= RequestContext::getMain()->getLanguage();

		$ts = '';
		$diff = $this->diff( $relativeTo );

		$user = User::newFromIdentity( $user ); // For compatibility with the hook signature
		if ( ( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )->onGetRelativeTimestamp(
			$ts,
			$diff,
			$this,
			$relativeTo,
			$user,
			$lang
		) ) {
			$seconds = ( ( ( $diff->days * 24 + $diff->h ) * 60 + $diff->i ) * 60 + $diff->s );
			$ts = wfMessage( 'ago', $lang->formatDuration( $seconds, $chosenIntervals ) )->inLanguage( $lang )->text();
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
		}

		return new RawMessage( $tzMsg );
	}

	/**
	 * Get a timestamp instance in the server local timezone ($wgLocaltimezone)
	 *
	 * @since 1.22
	 * @param bool|string $ts Timestamp to set, or false for current time
	 * @return MWTimestamp The local instance
	 */
	public static function getLocalInstance( $ts = false ) {
		$localtimezone = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::Localtimezone );
		$timestamp = new self( $ts );
		$timestamp->setTimezone( $localtimezone );
		return $timestamp;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( MWTimestamp::class, 'MWTimestamp' );
