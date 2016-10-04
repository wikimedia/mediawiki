<?php
/**
 * Variables provided by MediaWiki core
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
 * @since 1.28
 */

/**
 * Various core variables, registered in Parser::firstCallInit()
 * @ingroup Parser
 * @since 1.28
 */
class CoreVariables {
	/**
	 * @param $parser Parser
	 */
	public static function register( $parser ) {
		// Syntax for arguments (see Parser::setVariableHook):
		//  "name for lookup in localized magic words array",
		//  function callback

		$parser->setVariableHook( '!', [ __CLASS__, 'pipe' ] );
		$parser->setVariableHook( 'currentmonth', [ __CLASS__, 'currentmonth' ] );
		$parser->setVariableHook( 'currentmonth1', [ __CLASS__, 'currentmonth1' ] );
		$parser->setVariableHook( 'currentmonthname', [ __CLASS__, 'currentmonthname' ] );
		$parser->setVariableHook( 'currentmonthnamegen', [ __CLASS__, 'currentmonthnamegen' ] );
		$parser->setVariableHook( 'currentmonthabbrev', [ __CLASS__, 'currentmonthabbrev' ] );
		$parser->setVariableHook( 'currentday', [ __CLASS__, 'currentday' ] );
		$parser->setVariableHook( 'currentday2', [ __CLASS__, 'currentday2' ] );
		$parser->setVariableHook( 'localmonth', [ __CLASS__, 'localmonth' ] );
		$parser->setVariableHook( 'localmonth1', [ __CLASS__, 'localmonth1' ] );
		$parser->setVariableHook( 'localmonthname', [ __CLASS__, 'localmonthname' ] );
		$parser->setVariableHook( 'localmonthnamegen', [ __CLASS__, 'localmonthnamegen' ] );
		$parser->setVariableHook( 'localmonthabbrev', [ __CLASS__, 'localmonthabbrev' ] );
		$parser->setVariableHook( 'localday', [ __CLASS__, 'localday' ] );
		$parser->setVariableHook( 'localday2', [ __CLASS__, 'localday2' ] );
		$parser->setVariableHook( 'pagename', [ __CLASS__, 'pagename' ] );
		$parser->setVariableHook( 'pagenamee', [ __CLASS__, 'pagenamee' ] );
		$parser->setVariableHook( 'fullpagename', [ __CLASS__, 'fullpagename' ] );
		$parser->setVariableHook( 'fullpagenamee', [ __CLASS__, 'fullpagenamee' ] );
		$parser->setVariableHook( 'subpagename', [ __CLASS__, 'subpagename' ] );
		$parser->setVariableHook( 'subpagenamee', [ __CLASS__, 'subpagenamee' ] );
		$parser->setVariableHook( 'rootpagename', [ __CLASS__, 'rootpagename' ] );
		$parser->setVariableHook( 'rootpagenamee', [ __CLASS__, 'rootpagenamee' ] );
		$parser->setVariableHook( 'basepagename', [ __CLASS__, 'basepagename' ] );
		$parser->setVariableHook( 'basepagenamee', [ __CLASS__, 'basepagenamee' ] );
		$parser->setVariableHook( 'talkpagename', [ __CLASS__, 'talkpagename' ] );
		$parser->setVariableHook( 'talkpagenamee', [ __CLASS__, 'talkpagenamee' ] );
		$parser->setVariableHook( 'subjectpagename', [ __CLASS__, 'subjectpagename' ] );
		$parser->setVariableHook( 'subjectpagenamee', [ __CLASS__, 'subjectpagenamee' ] );
		$parser->setVariableHook( 'pageid', [ __CLASS__, 'pageid' ] );
		$parser->setVariableHook( 'revisionid', [ __CLASS__, 'revisionid' ] );
		$parser->setVariableHook( 'revisionday', [ __CLASS__, 'revisionday' ] );
		$parser->setVariableHook( 'revisionday2', [ __CLASS__, 'revisionday2' ] );
		$parser->setVariableHook( 'revisionmonth', [ __CLASS__, 'revisionmonth' ] );
		$parser->setVariableHook( 'revisionmonth1', [ __CLASS__, 'revisionmonth1' ] );
		$parser->setVariableHook( 'revisionyear', [ __CLASS__, 'revisionyear' ] );
		$parser->setVariableHook( 'revisiontimestamp', [ __CLASS__, 'revisiontimestamp' ] );
		$parser->setVariableHook( 'revisionuser', [ __CLASS__, 'revisionuser' ] );
		$parser->setVariableHook( 'revisionsize', [ __CLASS__, 'revisionsize' ] );
		$parser->setVariableHook( 'namespace', [ __CLASS__, 'mwnamespace' ] );
		$parser->setVariableHook( 'namespacee', [ __CLASS__, 'namespacee' ] );
		$parser->setVariableHook( 'namespacenumber', [ __CLASS__, 'namespacenumber' ] );
		$parser->setVariableHook( 'talkspace', [ __CLASS__, 'talkspace' ] );
		$parser->setVariableHook( 'talkspacee', [ __CLASS__, 'talkspacee' ] );
		$parser->setVariableHook( 'subjectspace', [ __CLASS__, 'subjectspace' ] );
		$parser->setVariableHook( 'subjectspacee', [ __CLASS__, 'subjectspacee' ] );
		$parser->setVariableHook( 'currentdayname', [ __CLASS__, 'currentdayname' ] );
		$parser->setVariableHook( 'currentyear', [ __CLASS__, 'currentyear' ] );
		$parser->setVariableHook( 'currenttime', [ __CLASS__, 'currenttime' ] );
		$parser->setVariableHook( 'currenthour', [ __CLASS__, 'currenthour' ] );
		$parser->setVariableHook( 'currentweek', [ __CLASS__, 'currentweek' ] );
		$parser->setVariableHook( 'currentdow', [ __CLASS__, 'currentdow' ] );
		$parser->setVariableHook( 'localdayname', [ __CLASS__, 'localdayname' ] );
		$parser->setVariableHook( 'localyear', [ __CLASS__, 'localyear' ] );
		$parser->setVariableHook( 'localtime', [ __CLASS__, 'localtime' ] );
		$parser->setVariableHook( 'localhour', [ __CLASS__, 'localhour' ] );
		$parser->setVariableHook( 'localweek', [ __CLASS__, 'localweek' ] );
		$parser->setVariableHook( 'localdow', [ __CLASS__, 'localdow' ] );
		$parser->setVariableHook( 'numberofarticles', [ __CLASS__, 'numberofarticles' ] );
		$parser->setVariableHook( 'numberoffiles', [ __CLASS__, 'numberoffiles' ] );
		$parser->setVariableHook( 'numberofusers', [ __CLASS__, 'numberofusers' ] );
		$parser->setVariableHook( 'numberofactiveusers', [ __CLASS__, 'numberofactiveusers' ] );
		$parser->setVariableHook( 'numberofpages', [ __CLASS__, 'numberofpages' ] );
		$parser->setVariableHook( 'numberofadmins', [ __CLASS__, 'numberofadmins' ] );
		$parser->setVariableHook( 'numberofedits', [ __CLASS__, 'numberofedits' ] );
		$parser->setVariableHook( 'currenttimestamp', [ __CLASS__, 'currenttimestamp' ] );
		$parser->setVariableHook( 'localtimestamp', [ __CLASS__, 'localtimestamp' ] );
		$parser->setVariableHook( 'currentversion', [ __CLASS__, 'currentversion' ] );
		$parser->setVariableHook( 'articlepath', [ __CLASS__, 'articlepath' ] );
		$parser->setVariableHook( 'sitename', [ __CLASS__, 'sitename' ] );
		$parser->setVariableHook( 'server', [ __CLASS__, 'server' ] );
		$parser->setVariableHook( 'servername', [ __CLASS__, 'servername' ] );
		$parser->setVariableHook( 'scriptpath', [ __CLASS__, 'scriptpath' ] );
		$parser->setVariableHook( 'stylepath', [ __CLASS__, 'stylepath' ] );
		$parser->setVariableHook( 'directionmark', [ __CLASS__, 'directionmark' ] );
		$parser->setVariableHook( 'contentlanguage', [ __CLASS__, 'contentlanguage' ] );
		$parser->setVariableHook( 'cascadingsources', [ __CLASS__, 'cascadingsources' ] );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function pipe( $parser ) {
		return '|';
	}

	/**
	 * Run the ParserGetVariableValueTs hook to allow extension modification of the timestamp
	 * @param Parser $parser
	 */
	private static function getVariableValueTs( $parser ) {
		$ts = wfTimestamp( TS_UNIX, $parser->getOptions()->getTimestamp() );
		Hooks::run( 'ParserGetVariableValueTs', [ &$parser, &$ts ] );
		return $ts;
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function currentmonth( $parser ) {
		return $parser->getFunctionLang()->formatNum(
			MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'm' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function currentmonth1( $parser ) {
		return $parser->getFunctionLang()->formatNum(
			MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'n' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function currentmonthname( $parser ) {
		return $parser->getFunctionLang()->getMonthName(
			MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'n' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function currentmonthnamegen( $parser ) {
		return $parser->getFunctionLang()->getMonthNameGen(
			MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'n' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function currentmonthabbrev( $parser ) {
		return $parser->getFunctionLang()->getMonthAbbreviation(
			MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'n' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function currentday( $parser ) {
		return $parser->getFunctionLang()->formatNum(
			MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'j' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function currentday2( $parser ) {
		return $parser->getFunctionLang()->formatNum(
			MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'd' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function localmonth( $parser ) {
		return $parser->getFunctionLang()->formatNum(
			MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'm' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function localmonth1( $parser ) {
		return $parser->getFunctionLang()->formatNum(
			MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'n' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function localmonthname( $parser ) {
		return $parser->getFunctionLang()->getMonthName(
			MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'n' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function localmonthnamegen( $parser ) {
		return $parser->getFunctionLang()->getMonthNameGen(
			MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'n' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function localmonthabbrev( $parser ) {
		return $parser->getFunctionLang()->getMonthAbbreviation(
			MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'n' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function localday( $parser ) {
		return $parser->getFunctionLang()->formatNum(
			MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'j' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function localday2( $parser ) {
		return $parser->getFunctionLang()->formatNum(
			MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'd' ) );
	}

	/**
	 * Functions to get the pagename of the parser
	 * @param Parser $parser
	 * @return string
	 */
	public static function pagename( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getText() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function pagenamee( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getPartialURL() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function fullpagename( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getPrefixedText() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function fullpagenamee( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getPrefixedURL() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function subpagename( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getSubpageText() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function subpagenamee( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getSubpageUrlForm() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function rootpagename( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getRootText() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function rootpagenamee( $parser ) {
		return wfEscapeWikiText( wfUrlEncode( str_replace(
			' ',
			'_',
			$parser->getTitle()->getRootText()
		) ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function basepagename( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getBaseText() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function basepagenamee( $parser ) {
		return wfEscapeWikiText( wfUrlEncode( str_replace(
			' ',
			'_',
			$parser->getTitle()->getBaseText()
		) ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function talkpagename( $parser ) {
		if ( $parser->getTitle()->canTalk() ) {
			$talkPage = $parser->getTitle()->getTalkPage();
			return wfEscapeWikiText( $talkPage->getPrefixedText() );
		}
		return '';
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function talkpagenamee( $parser ) {
		if ( $parser->getTitle()->canTalk() ) {
			$talkPage = $parser->getTitle()->getTalkPage();
			return wfEscapeWikiText( $talkPage->getPrefixedURL() );
		}
		return '';
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function subjectpagename( $parser ) {
		$subjPage = $parser->getTitle()->getSubjectPage();
		return wfEscapeWikiText( $subjPage->getPrefixedText() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function subjectpagenamee( $parser ) {
		$subjPage = $parser->getTitle()->getSubjectPage();
		return wfEscapeWikiText( $subjPage->getPrefixedURL() );
	}

	/**
	 * Requested in T25427
	 * @param Parser $parser
	 * @return string
	 */
	public static function pageid( $parser ) {
		$pageid = $parser->getTitle()->getArticleID();
		if ( $pageid == 0 ) {
			# 0 means the page doesn't exist in the database,
			# which means the user is previewing a new page.
			# The vary-revision flag must be set, because the magic word
			# will have a different value once the page is saved.
			$parser->getOutput()->setFlag( 'vary-revision' );
			wfDebug( __METHOD__ . ": {{PAGEID}} used in a new page, setting vary-revision...\n" );
		}
		return $pageid ? $pageid : null;
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function revisionid( $parser ) {
		# Let the edit saving system know we should parse the page
		# *after* a revision ID has been assigned.
		$parser->getOutput()->setFlag( 'vary-revision-id' );
		wfDebug( __METHOD__ . ": {{REVISIONID}} used, setting vary-revision-id...\n" );
		$value = $parser->getRevisionId();
		if ( !$value && $parser->getOptions()->getSpeculativeRevIdCallback() ) {
			$value = call_user_func( $parser->getOptions()->getSpeculativeRevIdCallback() );
			$parser->getOutput()->setSpeculativeRevIdUsed( $value );
		}
		return $value;
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function revisionday( $parser ) {
		# Let the edit saving system know we should parse the page
		# *after* a revision ID has been assigned. This is for null edits.
		$parser->getOutput()->setFlag( 'vary-revision' );
		wfDebug( __METHOD__ . ": {{REVISIONDAY}} used, setting vary-revision...\n" );
		return intval( substr( $parser->getRevisionTimestamp(), 6, 2 ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function revisionday2( $parser ) {
		# Let the edit saving system know we should parse the page
		# *after* a revision ID has been assigned. This is for null edits.
		$parser->getOutput()->setFlag( 'vary-revision' );
		wfDebug( __METHOD__ . ": {{REVISIONDAY2}} used, setting vary-revision...\n" );
		return substr( $parser->getRevisionTimestamp(), 6, 2 );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function revisionmonth( $parser ) {
		# Let the edit saving system know we should parse the page
		# *after* a revision ID has been assigned. This is for null edits.
		$parser->getOutput()->setFlag( 'vary-revision' );
		wfDebug( __METHOD__ . ": {{REVISIONMONTH}} used, setting vary-revision...\n" );
		return substr( $parser->getRevisionTimestamp(), 4, 2 );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function revisionmonth1( $parser ) {
		# Let the edit saving system know we should parse the page
		# *after* a revision ID has been assigned. This is for null edits.
		$parser->getOutput()->setFlag( 'vary-revision' );
		wfDebug( __METHOD__ . ": {{REVISIONMONTH1}} used, setting vary-revision...\n" );
		return intval( substr( $parser->getRevisionTimestamp(), 4, 2 ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function revisionyear( $parser ) {
		# Let the edit saving system know we should parse the page
		# *after* a revision ID has been assigned. This is for null edits.
		$parser->getOutput()->setFlag( 'vary-revision' );
		wfDebug( __METHOD__ . ": {{REVISIONYEAR}} used, setting vary-revision...\n" );
		return substr( $parser->getRevisionTimestamp(), 0, 4 );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function revisiontimestamp( $parser ) {
		# Let the edit saving system know we should parse the page
		# *after* a revision ID has been assigned. This is for null edits.
		$parser->getOutput()->setFlag( 'vary-revision' );
		wfDebug( __METHOD__ . ": {{REVISIONTIMESTAMP}} used, setting vary-revision...\n" );
		return $parser->getRevisionTimestamp();
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function revisionuser( $parser ) {
		# Let the edit saving system know we should parse the page
		# *after* a revision ID has been assigned for null edits.
		$parser->getOutput()->setFlag( 'vary-revision' );
		wfDebug( __METHOD__ . ": {{REVISIONUSER}} used, setting vary-user...\n" );
		return $parser->getRevisionUser();
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function revisionsize( $parser ) {
		return $parser->getRevisionSize();
	}

	/**
	 * return the namespace name of the title set on the parser
	 * Note: function name changed to "mwnamespace" rather than "namespace"
	 * to not break PHP 5.3
	 * @param Parser $parser
	 * @return string
	 */
	public static function mwnamespace( $parser ) {
		global $wgContLang;
		return str_replace( '_', ' ',
			$wgContLang->getNsText( $parser->getTitle()->getNamespace() ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function namespacee( $parser ) {
		global $wgContLang;
		return wfUrlencode( $wgContLang->getNsText( $parser->getTitle()->getNamespace() ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function namespacenumber( $parser ) {
		return $parser->getTitle()->getNamespace();
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function talkspace( $parser ) {
		return $parser->getTitle()->canTalk()
			? str_replace( '_', ' ', $parser->getTitle()->getTalkNsText() )
			: '';
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function talkspacee( $parser ) {
		return $parser->getTitle()->canTalk()
			? wfUrlencode( $parser->getTitle()->getTalkNsText() )
			: '';
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function subjectspace( $parser ) {
		return str_replace( '_', ' ', $parser->getTitle()->getSubjectNsText() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function subjectspacee( $parser ) {
		return wfUrlencode( $parser->getTitle()->getSubjectNsText() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function currentdayname( $parser ) {
		return $parser->getFunctionLang()->getWeekdayName(
			MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'w' ) + 1 );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function currentyear( $parser ) {
		return $parser->getFunctionLang()->formatNum(
			MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'Y' ), true );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function currenttime( $parser ) {
		return $parser->getFunctionLang()->time(
			wfTimestamp( TS_MW, self::getVariableValueTs( $parser ) ), false, false );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function currenthour( $parser ) {
		return $parser->getFunctionLang()->formatNum(
			MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'H' ), true );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function currentweek( $parser ) {
		# @bug 4594 PHP5 has it zero padded, PHP4 does not, cast to
		# int to remove the padding
		return $parser->getFunctionLang()->formatNum(
			(int)MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'W' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function currentdow( $parser ) {
		return $parser->getFunctionLang()->formatNum(
			MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'w' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function localdayname( $parser ) {
		return $parser->getFunctionLang()->getWeekdayName(
			MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'w' ) + 1
		);
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function localyear( $parser ) {
		return $parser->getFunctionLang()->formatNum(
			MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'Y' ),
			true
		);
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function localtime( $parser ) {
		return $parser->getFunctionLang()->time(
			MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'YmdHis' ),
			false,
			false
		);
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function localhour( $parser ) {
		return $parser->getFunctionLang()->formatNum(
			MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'H' ),
			true
		);
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function localweek( $parser ) {
		# @bug 4594 PHP5 has it zero padded, PHP4 does not, cast to
		# int to remove the padding
		return $parser->getFunctionLang()->formatNum(
			(int)MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'W' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function localdow( $parser ) {
		return $parser->getFunctionLang()->formatNum(
			MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'w' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function numberofarticles( $parser ) {
		return $parser->getFunctionLang()->formatNum( SiteStats::articles() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function numberoffiles( $parser ) {
		return $parser->getFunctionLang()->formatNum( SiteStats::images() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function numberofusers( $parser ) {
		return $parser->getFunctionLang()->formatNum( SiteStats::users() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function numberofactiveusers( $parser ) {
		return $parser->getFunctionLang()->formatNum( SiteStats::activeUsers() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function numberofpages( $parser ) {
		return $parser->getFunctionLang()->formatNum( SiteStats::pages() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function numberofadmins( $parser ) {
		return $parser->getFunctionLang()->formatNum( SiteStats::numberingroup( 'sysop' ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function numberofedits( $parser ) {
		return $parser->getFunctionLang()->formatNum( SiteStats::edits() );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function currenttimestamp( $parser ) {
		return wfTimestamp( TS_MW, self::getVariableValueTs( $parser ) );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function localtimestamp( $parser ) {
		return MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'YmdHis' );
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function currentversion( $parser ) {
		return SpecialVersion::getVersion();
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function articlepath( $parser ) {
		global $wgArticlePath;
		return $wgArticlePath;
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function sitename( $parser ) {
		global $wgSitename;
		return $wgSitename;
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function server( $parser ) {
		global $wgServer;
		return $wgServer;
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function servername( $parser ) {
		global $wgServerName;
		return $wgServerName;
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function scriptpath( $parser ) {
		global $wgScriptPath;
		return $wgScriptPath;
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function stylepath( $parser ) {
		global $wgStylePath;
		return $wgStylePath;
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function directionmark( $parser ) {
		return $parser->getFunctionLang()->getDirMark();
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function contentlanguage( $parser ) {
		global $wgLanguageCode;
		return $wgLanguageCode;
	}

	/**
	 * @param Parser $parser
	 * @return string
	 */
	public static function cascadingsources( $parser ) {
		return CoreParserFunctions::cascadingsources( $parser );
	}
}
