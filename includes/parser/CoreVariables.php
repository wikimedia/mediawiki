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
 * @since 1.22
 */

/**
 * Various core variables, registered in Parser::firstCallInit()
 * @ingroup Parser
 * @since 1.22
 */
class CoreVariables {
	/**
	 * @param $parser Parser
	 * @return void
	 */
	static function register( $parser ) {
		# Syntax for arguments (see Parser::setVariableHook):
		#  "name for lookup in localized magic words array",
		#  function callback

		$parser->setVariableHook( 'numberofpages', array( __CLASS__, 'numberofpages' ) );
		$parser->setVariableHook( 'numberofusers', array( __CLASS__, 'numberofusers' ) );
		$parser->setVariableHook( 'numberofactiveusers', array( __CLASS__, 'numberofactiveusers' ) );
		$parser->setVariableHook( 'numberofarticles', array( __CLASS__, 'numberofarticles' ) );
		$parser->setVariableHook( 'numberoffiles', array( __CLASS__, 'numberoffiles' ) );
		$parser->setVariableHook( 'numberofadmins', array( __CLASS__, 'numberofadmins' ) );
		$parser->setVariableHook( 'numberofedits', array( __CLASS__, 'numberofedits' ) );
		$parser->setVariableHook( 'numberofviews', array( __CLASS__, 'numberofviews' ) );
		$parser->setVariableHook( 'namespace', array( __CLASS__, 'mwnamespace' ) );
		$parser->setVariableHook( 'namespacee', array( __CLASS__, 'namespacee' ) );
		$parser->setVariableHook( 'namespacenumber', array( __CLASS__, 'namespacenumber' ) );
		$parser->setVariableHook( 'talkspace', array( __CLASS__, 'talkspace' ) );
		$parser->setVariableHook( 'talkspacee', array( __CLASS__, 'talkspacee' ) );
		$parser->setVariableHook( 'subjectspace', array( __CLASS__, 'subjectspace' ) );
		$parser->setVariableHook( 'subjectspacee', array( __CLASS__, 'subjectspacee' ) );
		$parser->setVariableHook( 'pagename', array( __CLASS__, 'pagename' ) );
		$parser->setVariableHook( 'pagenamee', array( __CLASS__, 'pagenamee' ) );
		$parser->setVariableHook( 'fullpagename', array( __CLASS__, 'fullpagename' ) );
		$parser->setVariableHook( 'fullpagenamee', array( __CLASS__, 'fullpagenamee' ) );
		$parser->setVariableHook( 'subpagename', array( __CLASS__, 'subpagename' ) );
		$parser->setVariableHook( 'subpagenamee', array( __CLASS__, 'subpagenamee' ) );
		$parser->setVariableHook( 'rootpagename', array( __CLASS__, 'rootpagename' ) );
		$parser->setVariableHook( 'rootpagenamee', array( __CLASS__, 'rootpagenamee' ) );
		$parser->setVariableHook( 'basepagename', array( __CLASS__, 'basepagename' ) );
		$parser->setVariableHook( 'basepagenamee', array( __CLASS__, 'basepagenamee' ) );
		$parser->setVariableHook( 'talkpagename', array( __CLASS__, 'talkpagename' ) );
		$parser->setVariableHook( 'talkpagenamee', array( __CLASS__, 'talkpagenamee' ) );
		$parser->setVariableHook( 'subjectpagename', array( __CLASS__, 'subjectpagename' ) );
		$parser->setVariableHook( 'subjectpagenamee', array( __CLASS__, 'subjectpagenamee' ) );
		$parser->setVariableHook( 'currentversion', array( __CLASS__, 'currentversion' ) );
		$parser->setVariableHook( 'articlepath', array( __CLASS__, 'articlepath' ) );
		$parser->setVariableHook( 'sitename', array( __CLASS__, 'sitename' ) );
		$parser->setVariableHook( 'server', array( __CLASS__, 'server' ) );
		$parser->setVariableHook( 'servername', array( __CLASS__, 'servername' ) );
		$parser->setVariableHook( 'scriptpath', array( __CLASS__, 'scriptpath' ) );
		$parser->setVariableHook( 'stylepath', array( __CLASS__, 'stylepath' ) );
		$parser->setVariableHook( 'directionmark', array( __CLASS__, 'directionmark' ) );
		$parser->setVariableHook( 'contentlanguage', array( __CLASS__, 'contentlanguage' ) );
		$parser->setVariableHook( 'pageid', array( __CLASS__, 'pageid' ) );
		$parser->setVariableHook( 'revisionid', array( __CLASS__, 'revisionid' ) );
		$parser->setVariableHook( 'revisionday', array( __CLASS__, 'revisionday' ) );
		$parser->setVariableHook( 'revisionday2', array( __CLASS__, 'revisionday2' ) );
		$parser->setVariableHook( 'revisionmonth', array( __CLASS__, 'revisionmonth' ) );
		$parser->setVariableHook( 'revisionmonth1', array( __CLASS__, 'revisionmonth1' ) );
		$parser->setVariableHook( 'revisionyear', array( __CLASS__, 'revisionyear' ) );
		$parser->setVariableHook( 'revisiontimestamp', array( __CLASS__, 'revisiontimestamp' ) );
		$parser->setVariableHook( 'revisionuser', array( __CLASS__, 'revisionuser' ) );
		$parser->setVariableHook( 'currentmonth', array( __CLASS__, 'currentmonth' ) );
		$parser->setVariableHook( 'currentmonth1', array( __CLASS__, 'currentmonth1' ) );
		$parser->setVariableHook( 'currentmonthname', array( __CLASS__, 'currentmonthname' ) );
		$parser->setVariableHook( 'currentmonthnamegen', array( __CLASS__, 'currentmonthnamegen' ) );
		$parser->setVariableHook( 'currentmonthabbrev', array( __CLASS__, 'currentmonthabbrev' ) );
		$parser->setVariableHook( 'currentday', array( __CLASS__, 'currentday' ) );
		$parser->setVariableHook( 'currentday2', array( __CLASS__, 'currentday2' ) );
		$parser->setVariableHook( 'localmonth', array( __CLASS__, 'localmonth' ) );
		$parser->setVariableHook( 'localmonth1', array( __CLASS__, 'localmonth1' ) );
		$parser->setVariableHook( 'localmonthname', array( __CLASS__, 'localmonthname' ) );
		$parser->setVariableHook( 'localmonthnamegen', array( __CLASS__, 'localmonthnamegen' ) );
		$parser->setVariableHook( 'localmonthabbrev', array( __CLASS__, 'localmonthabbrev' ) );
		$parser->setVariableHook( 'localday', array( __CLASS__, 'localday' ) );
		$parser->setVariableHook( 'localday2', array( __CLASS__, 'localday2' ) );
		$parser->setVariableHook( 'currentdayname', array( __CLASS__, 'currentdayname' ) );
		$parser->setVariableHook( 'currentyear', array( __CLASS__, 'currentyear' ) );
		$parser->setVariableHook( 'currenttime', array( __CLASS__, 'currenttime' ) );
		$parser->setVariableHook( 'currenthour', array( __CLASS__, 'currenthour' ) );
		$parser->setVariableHook( 'currentweek', array( __CLASS__, 'currentweek' ) );
		$parser->setVariableHook( 'currentdow', array( __CLASS__, 'currentdow' ) );
		$parser->setVariableHook( 'localdayname', array( __CLASS__, 'localdayname' ) );
		$parser->setVariableHook( 'localyear', array( __CLASS__, 'localyear' ) );
		$parser->setVariableHook( 'localtime', array( __CLASS__, 'localtime' ) );
		$parser->setVariableHook( 'localhour', array( __CLASS__, 'localhour' ) );
		$parser->setVariableHook( 'localweek', array( __CLASS__, 'localweek' ) );
		$parser->setVariableHook( 'localdow', array( __CLASS__, 'localdow' ) );
		$parser->setVariableHook( 'currenttimestamp', array( __CLASS__, 'currenttimestamp' ) );
		$parser->setVariableHook( 'localtimestamp', array( __CLASS__, 'localtimestamp' ) );
	}

	static function numberofpages( $parser ) {
		return $parser->getFunctionLang()->formatNum( SiteStats::pages() );
	}
	static function numberofusers( $parser ) {
		return $parser->getFunctionLang()->formatNum( SiteStats::users() );
	}
	static function numberofactiveusers( $parser ) {
		return $parser->getFunctionLang()->formatNum( SiteStats::activeUsers() );
	}
	static function numberofarticles( $parser ) {
		return $parser->getFunctionLang()->formatNum( SiteStats::articles() );
	}
	static function numberoffiles( $parser ) {
		return $parser->getFunctionLang()->formatNum( SiteStats::images() );
	}
	static function numberofadmins( $parser ) {
		return $parser->getFunctionLang()->formatNum( SiteStats::numberingroup( 'sysop' ) );
	}
	static function numberofedits( $parser ) {
		return $parser->getFunctionLang()->formatNum( SiteStats::edits() );
	}
	static function numberofviews( $parser ) {
		global $wgDisableCounters;
		return !$wgDisableCounters ? $parser->getFunctionLang()->formatNum( SiteStats::views() ) : '';
	}

	/**
	 * return the namespace name of the title set on the parser
	 * Note: function name changed to "mwnamespace" rather than "namespace"
	 * to not break PHP 5.3
	 * @return mixed|string
	 */
	static function mwnamespace( $parser ) {
		global $wgContLang;
		return str_replace( '_', ' ', $wgContLang->getNsText( $parser->getTitle()->getNamespace() ) );
	}
	static function namespacee( $parser ) {
		global $wgContLang;
		return wfUrlencode( $wgContLang->getNsText( $parser->getTitle()->getNamespace() ) );
	}
	static function namespacenumber( $parser ) {
		return $parser->getTitle()->getNamespace();
	}
	static function talkspace( $parser ) {
		return $parser->getTitle()->canTalk() ? str_replace( '_', ' ', $parser->getTitle()->getTalkNsText() ) : '';
	}
	static function talkspacee( $parser ) {
		return $parser->getTitle()->canTalk() ? wfUrlencode( $parser->getTitle()->getTalkNsText() ) : '';
	}
	static function subjectspace( $parser ) {
		return $parser->getTitle()->getSubjectNsText();
	}
	static function subjectspacee( $parser ) {
		return wfUrlencode( $parser->getTitle()->getSubjectNsText() );
	}

	/**
	 * Functions to get the pagename of the parser
	 * @return String
	 */
	static function pagename( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getText() );
	}
	static function pagenamee( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getPartialURL() );
	}
	static function fullpagename( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getPrefixedText() );
	}
	static function fullpagenamee( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getPrefixedURL() );
	}
	static function subpagename( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getSubpageText() );
	}
	static function subpagenamee( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getSubpageUrlForm() );
	}
	static function rootpagename( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getRootText() );
	}
	static function rootpagenamee( $parser ) {
		return wfEscapeWikiText( wfUrlEncode( str_replace( ' ', '_', $parser->getTitle()->getRootText() ) ) );
	}
	static function basepagename( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getBaseText() );
	}
	static function basepagenamee( $parser ) {
		return wfEscapeWikiText( wfUrlEncode( str_replace( ' ', '_', $parser->getTitle()->getBaseText() ) ) );
	}
	static function talkpagename( $parser ) {
		return $parser->getTitle()->canTalk() ? wfEscapeWikiText( $parser->getTitle()->getTalkPage()->getPrefixedText() ) : '';
	}
	static function talkpagenamee( $parser ) {
		return $parser->getTitle()->canTalk() ? wfEscapeWikiText( $parser->getTitle()->getTalkPage()->getPrefixedURL() ) : '';
	}
	static function subjectpagename( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getSubjectPage()->getPrefixedText() );
	}
	static function subjectpagenamee( $parser ) {
		return wfEscapeWikiText( $parser->getTitle()->getSubjectPage()->getPrefixedURL() );
	}

	static function pageid( $parser ) { // requested in bug 23427
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
	static function revisionid( $parser ) {
		# Let the edit saving system know we should parse the page
		# *after* a revision ID has been assigned.
		$parser->getOutput()->setFlag( 'vary-revision' );
		wfDebug( __METHOD__ . ": {{REVISIONID}} used, setting vary-revision...\n" );
		return $parser->getRevisionId();
	}
	static function revisionday( $parser ) {
		# Let the edit saving system know we should parse the page
		# *after* a revision ID has been assigned. This is for null edits.
		$parser->getOutput()->setFlag( 'vary-revision' );
		wfDebug( __METHOD__ . ": {{REVISIONDAY}} used, setting vary-revision...\n" );
		return intval( substr( $parser->getRevisionTimestamp(), 6, 2 ) );
	}
	static function revisionday2( $parser ) {
		# Let the edit saving system know we should parse the page
		# *after* a revision ID has been assigned. This is for null edits.
		$parser->getOutput()->setFlag( 'vary-revision' );
		wfDebug( __METHOD__ . ": {{REVISIONDAY2}} used, setting vary-revision...\n" );
		return substr( $parser->getRevisionTimestamp(), 6, 2 );
	}
	static function revisionmonth( $parser ) {
		# Let the edit saving system know we should parse the page
		# *after* a revision ID has been assigned. This is for null edits.
		$parser->getOutput()->setFlag( 'vary-revision' );
		wfDebug( __METHOD__ . ": {{REVISIONMONTH}} used, setting vary-revision...\n" );
		return substr( $parser->getRevisionTimestamp(), 4, 2 );
	}
	static function revisionmonth1( $parser ) {
		# Let the edit saving system know we should parse the page
		# *after* a revision ID has been assigned. This is for null edits.
		$parser->getOutput()->setFlag( 'vary-revision' );
		wfDebug( __METHOD__ . ": {{REVISIONMONTH1}} used, setting vary-revision...\n" );
		return intval( substr( $parser->getRevisionTimestamp(), 4, 2 ) );
	}
	static function revisionyear( $parser ) {
		# Let the edit saving system know we should parse the page
		# *after* a revision ID has been assigned. This is for null edits.
		$parser->getOutput()->setFlag( 'vary-revision' );
		wfDebug( __METHOD__ . ": {{REVISIONYEAR}} used, setting vary-revision...\n" );
		return substr( $parser->getRevisionTimestamp(), 0, 4 );
	}
	static function revisiontimestamp( $parser ) {
		# Let the edit saving system know we should parse the page
		# *after* a revision ID has been assigned. This is for null edits.
		$parser->getOutput()->setFlag( 'vary-revision' );
		wfDebug( __METHOD__ . ": {{REVISIONTIMESTAMP}} used, setting vary-revision...\n" );
		return $parser->getRevisionTimestamp();
	}
	static function revisionuser( $parser ) {
		# Let the edit saving system know we should parse the page
		# *after* a revision ID has been assigned. This is for null edits.
		$parser->getOutput()->setFlag( 'vary-revision' );
		wfDebug( __METHOD__ . ": {{REVISIONUSER}} used, setting vary-revision...\n" );
		return $parser->getRevisionUser();
	}

	static function currentversion( $parser ) {
		return SpecialVersion::getVersion();
	}
	static function articlepath( $parser ) {
		global $wgArticlePath;
		return $wgArticlePath;
	}
	static function sitename( $parser ) {
		global $wgSitename;
		return $wgSitename;
	}
	static function server( $parser ) {
		global $wgServer;
		return $wgServer;
	}
	static function servername( $parser ) {
		global $wgServer;
		$serverParts = wfParseUrl( $wgServer );
		return $serverParts && isset( $serverParts['host'] ) ? $serverParts['host'] : $wgServer;
	}
	static function scriptpath( $parser ) {
		global $wgScriptPath;
		return $wgScriptPath;
	}
	static function stylepath( $parser ) {
		global $wgStylePath;
		return $wgStylePath;
	}
	static function directionmark( $parser ) {
		return $parser->getFunctionLang()->getDirMark();
	}
	static function contentlanguage( $parser ) {
		global $wgLanguageCode;
		return $wgLanguageCode;
	}

	/**
	 * Run the ParserGetVariableValueTs hook to allow extension modification of the timestamp
	 */
	private static function getVariableValueTs( $parser ) {
		$ts = wfTimestamp( TS_UNIX, $parser->getOptions()->getTimestamp() );
		wfRunHooks( 'ParserGetVariableValueTs', array( &$parser, &$ts ) );
		return $ts;
	}

	static function currentmonth( $parser ) {
		return $parser->getFunctionLang()->formatNum( MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'm' ) );
	}
	static function currentmonth1( $parser ) {
		return $parser->getFunctionLang()->formatNum( MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'n' ) );
	}
	static function currentmonthname( $parser ) {
		return $parser->getFunctionLang()->getMonthName( MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'n' ) );
	}
	static function currentmonthnamegen( $parser ) {
		return $parser->getFunctionLang()->getMonthNameGen( MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'n' ) );
	}
	static function currentmonthabbrev( $parser ) {
		return $parser->getFunctionLang()->getMonthAbbreviation( MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'n' ) );
	}
	static function currentday( $parser ) {
		return $parser->getFunctionLang()->formatNum( MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'j' ) );
	}
	static function currentday2( $parser ) {
		return $parser->getFunctionLang()->formatNum( MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'd' ) );
	}
	static function localmonth( $parser ) {
		return $parser->getFunctionLang()->formatNum( MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'm' ) );
	}
	static function localmonth1( $parser ) {
		return $parser->getFunctionLang()->formatNum( MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'n' ) );
	}
	static function localmonthname( $parser ) {
		return $parser->getFunctionLang()->getMonthName( MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'n' ) );
	}
	static function localmonthnamegen( $parser ) {
		return $parser->getFunctionLang()->getMonthNameGen( MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'n' ) );
	}
	static function localmonthabbrev( $parser ) {
		return $parser->getFunctionLang()->getMonthAbbreviation( MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'n' ) );
	}
	static function localday( $parser ) {
		return $parser->getFunctionLang()->formatNum( MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'j' ) );
	}
	static function localday2( $parser ) {
		return $parser->getFunctionLang()->formatNum( MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'd' ) );
	}
	static function currentdayname( $parser ) {
		return $parser->getFunctionLang()->getWeekdayName( MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'w' ) + 1 );
	}
	static function currentyear( $parser ) {
		return $parser->getFunctionLang()->formatNum( MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'Y' ), true );
	}
	static function currenttime( $parser ) {
		return $parser->getFunctionLang()->time( wfTimestamp( TS_MW, self::getVariableValueTs( $parser ) ), false, false );
	}
	static function currenthour( $parser ) {
		return $parser->getFunctionLang()->formatNum( MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'H' ), true );
	}
	static function currentweek( $parser ) {
		# @bug 4594 PHP5 has it zero padded, PHP4 does not, cast to
		# int to remove the padding
		return $parser->getFunctionLang()->formatNum( (int)MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'W' ) );
	}
	static function currentdow( $parser ) {
		return $parser->getFunctionLang()->formatNum( MWTimestamp::getInstance( self::getVariableValueTs( $parser ) )->format( 'w' ) );
	}
	static function localdayname( $parser ) {
		return $parser->getFunctionLang()->getWeekdayName( MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'w' ) + 1 );
	}
	static function localyear( $parser ) {
		return $parser->getFunctionLang()->formatNum( MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'Y' ), true );
	}
	static function localtime( $parser ) {
		return $parser->getFunctionLang()->time( MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'YmdHis' ), false, false );
	}
	static function localhour( $parser ) {
		return $parser->getFunctionLang()->formatNum( MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'H' ), true );
	}
	static function localweek( $parser ) {
		# @bug 4594 PHP5 has it zero padded, PHP4 does not, cast to
		# int to remove the padding
		return $parser->getFunctionLang()->formatNum( (int)MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'W' ) );
	}
	static function localdow( $parser ) {
		return $parser->getFunctionLang()->formatNum( MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'w' ) );
	}
	static function currenttimestamp( $parser ) {
		return wfTimestamp( TS_MW, self::getVariableValueTs( $parser ) );
	}
	static function localtimestamp( $parser ) {
		return MWTimestamp::getLocalInstance( self::getVariableValueTs( $parser ) )->format( 'YmdHis' );
	}
}
