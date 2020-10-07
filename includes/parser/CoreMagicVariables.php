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
use MediaWiki\Config\ServiceOptions;
use Psr\Log\LoggerInterface;

/**
 * Expansions of core magic variables, used by the parser.
 * @internal
 * @ingroup Parser
 */
class CoreMagicVariables {
	/** @var int Assume that no output will later be saved this many seconds after parsing */
	private const MAX_TTS = 900;

	/**
	 * Expand the magic variable given by $index.
	 * @internal
	 * @param Parser $parser
	 * @param string $id The name of the variable, and equivalently, the magic
	 *   word ID which was used to match the variable
	 * @param int $ts Timestamp to use when expanding magic variable
	 * @param NamespaceInfo $nsInfo The NamespaceInfo to use when expanding
	 * @param ServiceOptions $svcOptions Service options for the parser
	 * @param LoggerInterface $logger
	 * @return string|null The expanded value, or null to indicate the given
	 *  index wasn't a known magic variable.
	 */
	public static function expand(
		// Fundamental options
		Parser $parser,
		string $id,
		// Context passed over from the parser
		int $ts,
		NamespaceInfo $nsInfo,
		ServiceOptions $svcOptions,
		LoggerInterface $logger
	): ?string {
		$pageLang = $parser->getFunctionLang();
		$title = $parser->getTitle();

		switch ( $id ) {
			case '!':
				return '|';
			case 'currentmonth':
				return $pageLang->formatNum( MWTimestamp::getInstance( $ts )->format( 'm' ), true );
			case 'currentmonth1':
				return $pageLang->formatNum( MWTimestamp::getInstance( $ts )->format( 'n' ), true );
			case 'currentmonthname':
				return $pageLang->getMonthName( MWTimestamp::getInstance( $ts )->format( 'n' ) );
			case 'currentmonthnamegen':
				return $pageLang->getMonthNameGen( MWTimestamp::getInstance( $ts )->format( 'n' ) );
			case 'currentmonthabbrev':
				return $pageLang->getMonthAbbreviation( MWTimestamp::getInstance( $ts )->format( 'n' ) );
			case 'currentday':
				return $pageLang->formatNum( MWTimestamp::getInstance( $ts )->format( 'j' ), true );
			case 'currentday2':
				return $pageLang->formatNum( MWTimestamp::getInstance( $ts )->format( 'd' ), true );
			case 'localmonth':
				return $pageLang->formatNum( MWTimestamp::getLocalInstance( $ts )->format( 'm' ), true );
			case 'localmonth1':
				return $pageLang->formatNum( MWTimestamp::getLocalInstance( $ts )->format( 'n' ), true );
			case 'localmonthname':
				return $pageLang->getMonthName( MWTimestamp::getLocalInstance( $ts )->format( 'n' ) );
			case 'localmonthnamegen':
				return $pageLang->getMonthNameGen( MWTimestamp::getLocalInstance( $ts )->format( 'n' ) );
			case 'localmonthabbrev':
				return $pageLang->getMonthAbbreviation( MWTimestamp::getLocalInstance( $ts )->format( 'n' ) );
			case 'localday':
				return $pageLang->formatNum( MWTimestamp::getLocalInstance( $ts )->format( 'j' ), true );
			case 'localday2':
				return $pageLang->formatNum( MWTimestamp::getLocalInstance( $ts )->format( 'd' ), true );
			case 'pagename':
				return wfEscapeWikiText( $title->getText() );
			case 'pagenamee':
				return wfEscapeWikiText( $title->getPartialURL() );
			case 'fullpagename':
				return wfEscapeWikiText( $title->getPrefixedText() );
			case 'fullpagenamee':
				return wfEscapeWikiText( $title->getPrefixedURL() );
			case 'subpagename':
				return wfEscapeWikiText( $title->getSubpageText() );
			case 'subpagenamee':
				return wfEscapeWikiText( $title->getSubpageUrlForm() );
			case 'rootpagename':
				return wfEscapeWikiText( $title->getRootText() );
			case 'rootpagenamee':
				return wfEscapeWikiText( wfUrlencode( str_replace(
					' ',
					'_',
					$title->getRootText()
				) ) );
			case 'basepagename':
				return wfEscapeWikiText( $title->getBaseText() );
			case 'basepagenamee':
				return wfEscapeWikiText( wfUrlencode( str_replace(
					' ',
					'_',
					$title->getBaseText()
				) ) );
			case 'talkpagename':
				if ( $title->canHaveTalkPage() ) {
					$talkPage = $title->getTalkPage();
					return wfEscapeWikiText( $talkPage->getPrefixedText() );
				}
				return '';
			case 'talkpagenamee':
				if ( $title->canHaveTalkPage() ) {
					$talkPage = $title->getTalkPage();
					return wfEscapeWikiText( $talkPage->getPrefixedURL() );
				}
				return '';
			case 'subjectpagename':
				$subjPage = $title->getSubjectPage();
				return wfEscapeWikiText( $subjPage->getPrefixedText() );
			case 'subjectpagenamee':
				$subjPage = $title->getSubjectPage();
				return wfEscapeWikiText( $subjPage->getPrefixedURL() );
			case 'pageid': // requested in T25427
				// Inform the edit saving system that getting the canonical output
				// after page insertion requires a parse that used that exact page ID
				self::setOutputFlag( $parser, $logger, 'vary-page-id', '{{PAGEID}} used' );
				$value = $title->getArticleID();
				if ( !$value ) {
					$value = $parser->getOptions()->getSpeculativePageId();
					if ( $value ) {
						$parser->getOutput()->setSpeculativePageIdUsed( $value );
					}
				}
				return (string)$value;
			case 'revisionid':
				$namespace = $title->getNamespace();
				if (
					$svcOptions->get( 'MiserMode' ) &&
					!$parser->getOptions()->getInterfaceMessage() &&
					// @TODO: disallow this variable on all namespaces
					$nsInfo->isSubject( $namespace )
				) {
					// Use a stub result instead of the actual revision ID in order to avoid
					// double parses on page save but still allow preview detection (T137900)
					if ( $parser->getRevisionId() || $parser->getOptions()->getSpeculativeRevId() ) {
						return '-';
					} else {
						self::setOutputFlag( $parser, $logger, 'vary-revision-exists', '{{REVISIONID}} used' );
						return '';
					}
				} else {
					// Inform the edit saving system that getting the canonical output after
					// revision insertion requires a parse that used that exact revision ID
					self::setOutputFlag( $parser, $logger, 'vary-revision-id', '{{REVISIONID}} used' );
					$value = $parser->getRevisionId();
					if ( $value === 0 ) {
						$rev = $parser->getRevisionRecordObject();
						$value = $rev ? $rev->getId() : $value;
					}
					if ( !$value ) {
						$value = $parser->getOptions()->getSpeculativeRevId();
						if ( $value ) {
							$parser->getOutput()->setSpeculativeRevIdUsed( $value );
						}
					}
					return (string)$value;
				}
			case 'revisionday':
				return strval( (int)self::getRevisionTimestampSubstring(
					$parser, $logger, 6, 2, self::MAX_TTS, $id
				) );
			case 'revisionday2':
				return self::getRevisionTimestampSubstring(
					$parser, $logger, 6, 2, self::MAX_TTS, $id
				);
			case 'revisionmonth':
				return self::getRevisionTimestampSubstring(
					$parser, $logger, 4, 2, self::MAX_TTS, $id
				);
			case 'revisionmonth1':
				return strval( (int)self::getRevisionTimestampSubstring(
					$parser, $logger, 4, 2, self::MAX_TTS, $id
				) );
			case 'revisionyear':
				return self::getRevisionTimestampSubstring(
					$parser, $logger, 0, 4, self::MAX_TTS, $id
				);
			case 'revisiontimestamp':
				return self::getRevisionTimestampSubstring(
					$parser, $logger, 0, 14, self::MAX_TTS, $id
				);
			case 'revisionuser':
				// Inform the edit saving system that getting the canonical output after
				// revision insertion requires a parse that used the actual user ID
				self::setOutputFlag( $parser, $logger, 'vary-user', '{{REVISIONUSER}} used' );
				// Note that getRevisionUser() can return null; we need to
				// be sure to cast this to (an empty) string, since 'null'
				// means "magic variable not handled here".
				return (string)$parser->getRevisionUser();
			case 'revisionsize':
				return (string)$parser->getRevisionSize();
			case 'namespace':
				return str_replace( '_', ' ',
					$parser->getContentLanguage()->getNsText( $title->getNamespace() ) );
			case 'namespacee':
				return wfUrlencode( $parser->getContentLanguage()->getNsText( $title->getNamespace() ) );
			case 'namespacenumber':
				return $title->getNamespace();
			case 'talkspace':
				return $title->canHaveTalkPage()
					? str_replace( '_', ' ', $title->getTalkNsText() )
					: '';
			case 'talkspacee':
				return $title->canHaveTalkPage()
					? wfUrlencode( $title->getTalkNsText() )
					: '';
			case 'subjectspace':
				return str_replace( '_', ' ', $title->getSubjectNsText() );
			case 'subjectspacee':
				return ( wfUrlencode( $title->getSubjectNsText() ) );
			case 'currentdayname':
				return $pageLang->getWeekdayName( (int)MWTimestamp::getInstance( $ts )->format( 'w' ) + 1 );
			case 'currentyear':
				return $pageLang->formatNum( MWTimestamp::getInstance( $ts )->format( 'Y' ), true );
			case 'currenttime':
				return $pageLang->time( wfTimestamp( TS_MW, $ts ), false, false );
			case 'currenthour':
				return $pageLang->formatNum( MWTimestamp::getInstance( $ts )->format( 'H' ), true );
			case 'currentweek':
				// @bug T6594 PHP5 has it zero padded, PHP4 does not, cast to
				// int to remove the padding
				return $pageLang->formatNum( (int)MWTimestamp::getInstance( $ts )->format( 'W' ) );
			case 'currentdow':
				return $pageLang->formatNum( MWTimestamp::getInstance( $ts )->format( 'w' ) );
			case 'localdayname':
				return $pageLang->getWeekdayName(
					(int)MWTimestamp::getLocalInstance( $ts )->format( 'w' ) + 1
				);
			case 'localyear':
				return $pageLang->formatNum( MWTimestamp::getLocalInstance( $ts )->format( 'Y' ), true );
			case 'localtime':
				return $pageLang->time(
					MWTimestamp::getLocalInstance( $ts )->format( 'YmdHis' ),
					false,
					false
				);
			case 'localhour':
				return $pageLang->formatNum( MWTimestamp::getLocalInstance( $ts )->format( 'H' ), true );
			case 'localweek':
				// @bug T6594 PHP5 has it zero padded, PHP4 does not, cast to
				// int to remove the padding
				return $pageLang->formatNum( (int)MWTimestamp::getLocalInstance( $ts )->format( 'W' ) );
			case 'localdow':
				return $pageLang->formatNum( MWTimestamp::getLocalInstance( $ts )->format( 'w' ) );
			case 'numberofarticles':
				return $pageLang->formatNum( SiteStats::articles() );
			case 'numberoffiles':
				return $pageLang->formatNum( SiteStats::images() );
			case 'numberofusers':
				return $pageLang->formatNum( SiteStats::users() );
			case 'numberofactiveusers':
				return $pageLang->formatNum( SiteStats::activeUsers() );
			case 'numberofpages':
				return $pageLang->formatNum( SiteStats::pages() );
			case 'numberofadmins':
				return $pageLang->formatNum( SiteStats::numberingroup( 'sysop' ) );
			case 'numberofedits':
				return $pageLang->formatNum( SiteStats::edits() );
			case 'currenttimestamp':
				return wfTimestamp( TS_MW, $ts );
			case 'localtimestamp':
				return MWTimestamp::getLocalInstance( $ts )->format( 'YmdHis' );
			case 'currentversion':
				return SpecialVersion::getVersion();
			case 'articlepath':
				return (string)$svcOptions->get( 'ArticlePath' );
			case 'sitename':
				return (string)$svcOptions->get( 'Sitename' );
			case 'server':
				return (string)$svcOptions->get( 'Server' );
			case 'servername':
				return (string)$svcOptions->get( 'ServerName' );
			case 'scriptpath':
				return (string)$svcOptions->get( 'ScriptPath' );
			case 'stylepath':
				return (string)$svcOptions->get( 'StylePath' );
			case 'directionmark':
				return $pageLang->getDirMark();
			case 'contentlanguage':
				return (string)$svcOptions->get( 'LanguageCode' );
			case 'pagelanguage':
				return $pageLang->getCode();
			case 'cascadingsources':
				return CoreParserFunctions::cascadingsources( $parser );
			default:
				// This is not one of the core magic variables
				return null;
		}
	}

	/**
	 * @param Parser $parser
	 * @param LoggerInterface $logger
	 * @param int $start
	 * @param int $len
	 * @param int $mtts Max time-till-save; sets vary-revision-timestamp if result changes by then
	 * @param string $variable Parser variable name
	 * @return string
	 */
	private static function getRevisionTimestampSubstring(
		Parser $parser,
		LoggerInterface $logger,
		int $start,
		int $len,
		int $mtts,
		string $variable
	): string {
		// Get the timezone-adjusted timestamp to be used for this revision
		$resNow = substr( $parser->getRevisionTimestamp(), $start, $len );
		// Possibly set vary-revision if there is not yet an associated revision
		if ( !$parser->getRevisionRecordObject() ) {
			// Get the timezone-adjusted timestamp $mtts seconds in the future.
			// This future is relative to the current time and not that of the
			// parser options. The rendered timestamp can be compared to that
			// of the timestamp specified by the parser options.
			$resThen = substr(
				$parser->getContentLanguage()->userAdjust( wfTimestamp( TS_MW, time() + $mtts ), '' ),
				$start,
				$len
			);

			if ( $resNow !== $resThen ) {
				// Inform the edit saving system that getting the canonical output after
				// revision insertion requires a parse that used an actual revision timestamp
				self::setOutputFlag( $parser, $logger, 'vary-revision-timestamp', "$variable used" );
			}
		}

		return $resNow;
	}

	/**
	 * Helper method borrowed from Parser.php: sets the flag on the output
	 * but also does some debug logging.
	 * @param Parser $parser
	 * @param LoggerInterface $logger
	 * @param string $flag
	 * @param string $reason
	 */
	private static function setOutputFlag(
		Parser $parser,
		LoggerInterface $logger,
		string $flag,
		string $reason
	): void {
		$parser->getOutput()->setFlag( $flag );
		$name = $parser->getTitle()->getPrefixedText();
		// This code was moved from Parser::setOutputFlag and used __METHOD__
		// originally; we've hard-coded that output here so that our refactor
		// doesn't change the messages in the logs.
		$logger->debug( "Parser::setOutputFlag: set $flag flag on '$name'; $reason" );
	}
}
