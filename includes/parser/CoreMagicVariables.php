<?php
/**
 * Magic variable implementations provided by MediaWiki core
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
 * @ingroup Parser
 */

namespace MediaWiki\Parser;

use DateTime;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Specials\SpecialVersion;
use MediaWiki\Utils\MWTimestamp;
use Psr\Log\LoggerInterface;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Expansions of core magic variables, used by the parser.
 * @internal
 * @ingroup Parser
 */
class CoreMagicVariables {
	/** Map of (word ID => cache TTL hint) */
	private const CACHE_TTL_BY_ID = [
		'currenttime' => 3600,
		'localtime' => 3600,
		'numberofarticles' => 3600,
		'numberoffiles' => 3600,
		'numberofedits' => 3600,
		'numberofusers' => 3600,
		'numberofactiveusers' => 3600,
		'numberofpages' => 3600,
		'currentversion' => 86400,
		'currenttimestamp' => 3600,
		'localtimestamp' => 3600,
		'pagesinnamespace' => 3600,
		'numberofadmins' => 3600,
		'numberingroup' => 3600,
	];

	/** Map of (time unit => relative datetime specifier) */
	private const DEADLINE_DATE_SPEC_BY_UNIT = [
		'Y' => 'first day of January next year midnight',
		'M' => 'first day of next month midnight',
		'D' => 'next day midnight',
		// Note that this relative datetime specifier does not zero out
		// minutes/seconds, but we will do so manually in
		// ::applyUnitTimestampDeadline() when given the unit 'H'
		'H' => 'next hour'
	];
	/** Seconds of clock skew fudge factor for time-interval deadline TTLs */
	private const DEADLINE_TTL_CLOCK_FUDGE = 1;
	/** Max seconds to "randomly" add to time-interval deadline TTLs to avoid stampedes */
	private const DEADLINE_TTL_STAGGER_MAX = 15;
	/** Minimum time-interval deadline TTL */
	private const MIN_DEADLINE_TTL = 15;

	/**
	 * Expand the magic variable given by $index.
	 * @internal
	 * @param Parser $parser
	 * @param string $id The name of the variable, and equivalently, the magic
	 *   word ID which was used to match the variable
	 * @param ConvertibleTimestamp $ts Timestamp to use when expanding magic variable
	 * @param ServiceOptions $svcOptions Service options for the parser
	 * @param LoggerInterface $logger
	 * @return string|null The expanded value, as wikitext, or null to
	 *  indicate the given index wasn't a known magic variable.
	 */
	public static function expand(
		// Fundamental options
		Parser $parser,
		string $id,
		// Context passed over from the parser
		ConvertibleTimestamp $ts,
		ServiceOptions $svcOptions,
		LoggerInterface $logger
	): ?string {
		$pageLang = $parser->getTargetLanguage();

		$cacheTTL = self::CACHE_TTL_BY_ID[$id] ?? -1;
		if ( $cacheTTL > -1 ) {
			$parser->getOutput()->updateCacheExpiry( $cacheTTL );
		}

		switch ( $id ) {
			case '!':
				return '|';
			case '=':
				return '=';
			case 'currentmonth':
				self::applyUnitTimestampDeadline( $parser, $ts, 'M' );

				return $pageLang->formatNumNoSeparators( $ts->format( 'm' ) );
			case 'currentmonth1':
				self::applyUnitTimestampDeadline( $parser, $ts, 'M' );

				return $pageLang->formatNumNoSeparators( $ts->format( 'n' ) );
			case 'currentmonthname':
				self::applyUnitTimestampDeadline( $parser, $ts, 'M' );

				return $pageLang->getMonthName( (int)$ts->format( 'n' ) );
			case 'currentmonthnamegen':
				self::applyUnitTimestampDeadline( $parser, $ts, 'M' );

				return $pageLang->getMonthNameGen( (int)$ts->format( 'n' ) );
			case 'currentmonthabbrev':
				self::applyUnitTimestampDeadline( $parser, $ts, 'M' );

				return $pageLang->getMonthAbbreviation( (int)$ts->format( 'n' ) );
			case 'currentday':
				self::applyUnitTimestampDeadline( $parser, $ts, 'D' );

				return $pageLang->formatNumNoSeparators( $ts->format( 'j' ) );
			case 'currentday2':
				self::applyUnitTimestampDeadline( $parser, $ts, 'D' );

				return $pageLang->formatNumNoSeparators( $ts->format( 'd' ) );
			case 'localmonth':
				$localTs = self::makeTsLocal( $svcOptions, $ts );
				self::applyUnitTimestampDeadline( $parser, $localTs, 'M' );

				return $pageLang->formatNumNoSeparators( $localTs->format( 'm' ) );
			case 'localmonth1':
				$localTs = self::makeTsLocal( $svcOptions, $ts );
				self::applyUnitTimestampDeadline( $parser, $localTs, 'M' );

				return $pageLang->formatNumNoSeparators( $localTs->format( 'n' ) );
			case 'localmonthname':
				$localTs = self::makeTsLocal( $svcOptions, $ts );
				self::applyUnitTimestampDeadline( $parser, $localTs, 'M' );

				return $pageLang->getMonthName( (int)$localTs->format( 'n' ) );
			case 'localmonthnamegen':
				$localTs = self::makeTsLocal( $svcOptions, $ts );
				self::applyUnitTimestampDeadline( $parser, $localTs, 'M' );

				return $pageLang->getMonthNameGen( (int)$localTs->format( 'n' ) );
			case 'localmonthabbrev':
				$localTs = self::makeTsLocal( $svcOptions, $ts );
				self::applyUnitTimestampDeadline( $parser, $localTs, 'M' );

				return $pageLang->getMonthAbbreviation( (int)$localTs->format( 'n' ) );
			case 'localday':
				$localTs = self::makeTsLocal( $svcOptions, $ts );
				self::applyUnitTimestampDeadline( $parser, $localTs, 'D' );

				return $pageLang->formatNumNoSeparators( $localTs->format( 'j' ) );
			case 'localday2':
				$localTs = self::makeTsLocal( $svcOptions, $ts );
				self::applyUnitTimestampDeadline( $parser, $localTs, 'D' );

				return $pageLang->formatNumNoSeparators( $localTs->format( 'd' ) );
			case 'pagename':
			case 'pagenamee':
			case 'fullpagename':
			case 'fullpagenamee':
			case 'subpagename':
			case 'subpagenamee':
			case 'rootpagename':
			case 'rootpagenamee':
			case 'basepagename':
			case 'basepagenamee':
			case 'talkpagename':
			case 'talkpagenamee':
			case 'subjectpagename':
			case 'subjectpagenamee':
			case 'pageid':
			case 'revisionid':
			case 'revisionuser':
			case 'revisionday':
			case 'revisionday2':
			case 'revisionmonth':
			case 'revisionmonth1':
			case 'revisionyear':
			case 'revisiontimestamp':
			case 'namespace':
			case 'namespacee':
			case 'namespacenumber':
			case 'talkspace':
			case 'talkspacee':
			case 'subjectspace':
			case 'subjectspacee':
			case 'cascadingsources':
				# First argument of the corresponding parser function
				# (second argument of the PHP implementation) is
				# "title".

				# Note that for many of these {{FOO}} is subtly different
				# from {{FOO:{{PAGENAME}}}}, so we can't pass $title here
				# we have to explicitly use the "no arguments" form of the
				# parser function by passing `null` to indicate a missing
				# argument (which then defaults to the current page title).
				return CoreParserFunctions::$id( $parser, null );
			case 'revisionsize':
				return (string)$parser->getRevisionSize();
			case 'currentdayname':
				self::applyUnitTimestampDeadline( $parser, $ts, 'D' );

				return $pageLang->getWeekdayName( (int)$ts->format( 'w' ) + 1 );
			case 'currentyear':
				self::applyUnitTimestampDeadline( $parser, $ts, 'Y' );

				return $pageLang->formatNumNoSeparators( $ts->format( 'Y' ) );
			case 'currenttime':
				return $pageLang->time( $ts->getTimestamp( TS_MW ), false, false );
			case 'currenthour':
				self::applyUnitTimestampDeadline( $parser, $ts, 'H' );

				return $pageLang->formatNumNoSeparators( $ts->format( 'H' ) );
			case 'currentweek':
				self::applyUnitTimestampDeadline( $parser, $ts, 'D' );
				// @bug T6594 PHP5 has it zero padded, PHP4 does not, cast to
				// int to remove the padding
				return $pageLang->formatNum( (int)$ts->format( 'W' ) );
			case 'currentdow':
				self::applyUnitTimestampDeadline( $parser, $ts, 'D' );

				return $pageLang->formatNum( $ts->format( 'w' ) );
			case 'localdayname':
				$localTs = self::makeTsLocal( $svcOptions, $ts );
				self::applyUnitTimestampDeadline( $parser, $localTs, 'D' );

				return $pageLang->getWeekdayName( (int)$localTs->format( 'w' ) + 1 );
			case 'localyear':
				$localTs = self::makeTsLocal( $svcOptions, $ts );
				self::applyUnitTimestampDeadline( $parser, $localTs, 'Y' );

				return $pageLang->formatNumNoSeparators( $localTs->format( 'Y' ) );
			case 'localtime':
				$localTs = self::makeTsLocal( $svcOptions, $ts );

				return $pageLang->time(
					$localTs->format( 'YmdHis' ),
					false,
					false
				);
			case 'localhour':
				$localTs = self::makeTsLocal( $svcOptions, $ts );
				self::applyUnitTimestampDeadline( $parser, $localTs, 'H' );

				return $pageLang->formatNumNoSeparators( $localTs->format( 'H' ) );
			case 'localweek':
				$localTs = self::makeTsLocal( $svcOptions, $ts );
				self::applyUnitTimestampDeadline( $parser, $localTs, 'D' );
				// @bug T6594 PHP5 has it zero padded, PHP4 does not, cast to
				// int to remove the padding
				return $pageLang->formatNum( (int)$localTs->format( 'W' ) );
			case 'localdow':
				$localTs = self::makeTsLocal( $svcOptions, $ts );
				self::applyUnitTimestampDeadline( $parser, $localTs, 'D' );

				return $pageLang->formatNum( $localTs->format( 'w' ) );
			case 'numberofarticles':
			case 'numberoffiles':
			case 'numberofusers':
			case 'numberofactiveusers':
			case 'numberofpages':
			case 'numberofadmins':
			case 'numberofedits':
				# second argument is 'raw'; magic variables are "not raw"
				return CoreParserFunctions::$id( $parser, null );
			case 'currenttimestamp':
				return $ts->getTimestamp( TS_MW );
			case 'localtimestamp':
				$localTs = self::makeTsLocal( $svcOptions, $ts );

				return $localTs->format( 'YmdHis' );
			case 'currentversion':
				return SpecialVersion::getVersion();
			case 'articlepath':
				return (string)$svcOptions->get( MainConfigNames::ArticlePath );
			case 'sitename':
				return (string)$svcOptions->get( MainConfigNames::Sitename );
			case 'server':
				return (string)$svcOptions->get( MainConfigNames::Server );
			case 'servername':
				return (string)$svcOptions->get( MainConfigNames::ServerName );
			case 'scriptpath':
				return (string)$svcOptions->get( MainConfigNames::ScriptPath );
			case 'stylepath':
				return (string)$svcOptions->get( MainConfigNames::StylePath );
			case 'directionmark':
				return $pageLang->getDirMark();
			case 'contentlanguage':
				return $parser->getContentLanguage()->getCode();
			case 'pagelanguage':
				return $pageLang->getCode();
			case 'userlanguage':
				if ( $parser->getOptions()->isMessage()
					|| $svcOptions->get( MainConfigNames::ParserEnableUserLanguage )
				) {
					return $parser->getOptions()->getUserLang();
				} else {
					return $pageLang->getCode();
				}
			case 'bcp47':
			case 'dir':
			case 'language':
				# magic variables are the same as empty/default first argument
				return CoreParserFunctions::$id( $parser );
			default:
				// This is not one of the core magic variables
				return null;
		}
	}

	/**
	 * Helper to convert a timestamp instance to local time
	 * @see MWTimestamp::getLocalInstance()
	 * @param ServiceOptions $svcOptions Service options for the parser
	 * @param ConvertibleTimestamp $ts Timestamp to convert
	 * @return ConvertibleTimestamp
	 */
	private static function makeTsLocal( $svcOptions, $ts ) {
		$localtimezone = $svcOptions->get( MainConfigNames::Localtimezone );
		$ts->setTimezone( $localtimezone );
		return $ts;
	}

	/**
	 * Adjust the cache expiry to account for a dynamic timestamp displayed in output
	 *
	 * @param Parser $parser
	 * @param ConvertibleTimestamp $ts Current timestamp with the display timezone
	 * @param string $unit The unit the timestamp is expressed in; one of ("Y", "M", "D", "H")
	 */
	private static function applyUnitTimestampDeadline(
		Parser $parser,
		ConvertibleTimestamp $ts,
		string $unit
	) {
		$tsUnix = (int)$ts->getTimestamp( TS_UNIX );

		$date = new DateTime( "@$tsUnix" );
		$date->setTimezone( $ts->getTimezone() );
		$date->modify( self::DEADLINE_DATE_SPEC_BY_UNIT[$unit] );
		if ( $unit === 'H' ) {
			// Zero out the minutes/seconds
			$date->setTime( intval( $date->format( 'H' ), 10 ), 0, 0 );
		} else {
			$date->setTime( 0, 0, 0 );
		}
		$deadlineUnix = (int)$date->format( 'U' );

		$ttl = max( $deadlineUnix - $tsUnix, self::MIN_DEADLINE_TTL );
		$ttl += self::DEADLINE_TTL_CLOCK_FUDGE;
		$ttl += ( $tsUnix % self::DEADLINE_TTL_STAGGER_MAX );

		$parser->getOutput()->updateCacheExpiry( $ttl );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( CoreMagicVariables::class, 'CoreMagicVariables' );
