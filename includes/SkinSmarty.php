<?php
# Copyright (C) 2003 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or 
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

include_once( "Smarty.class.php" );

class SkinSmarty extends Skin {
	var $template;
	
	function SkinSmarty() {
	}
	
	function initPage() {
		$this->template = "paddington";
	}
	
	function outputPage( &$out ) {
		global $wgTitle, $wgArticle, $wgUser, $wgLang;
		global $wgScriptPath, $wgStyleSheetPath, $wgLanguageCode;

		$this->initPage();
		
		$smarty = new Smarty();
		$smarty->register_function( "wikimsg",
			array( &$this,"smarty_function_wikimsg" ), true );
		$smarty->register_function( "wikilink",
			array( &$this, "smarty_function_wikilink" ) );
		
		$smarty->assign( "title", $wgTitle->getPrefixedText() ); // ?
		$smarty->assign( "thispage", $wgTitle->getPrefixedDbKey() );
		$smarty->assign( "subtitle", $out->getSubtitle() );
		
		$smarty->assign( "editable", ($wgTitle->getNamespace != Namespace::getSpecial() ) );
		$smarty->assign( "exists", $wgTitle->getArticleID() != 0 );
		$smarty->assign( "watch", $wgTitle->userIsWatching() ? "unwatch" : "watch" );
		$smarty->assign( "protect", count($wgTitle->getRestrictions()) ? "unprotect" : "protect" );

		$smarty->assign( "searchaction", $wgScriptPath );
		$smarty->assign( "stylepath", $wgStyleSheetPath );
		$smarty->assign( "lang", $wgLanguageCode );
		$smarty->assign( "langname", $wgLang->getLanguageName( $wgLanguageCode ) );

		$smarty->assign( "username", $wgUser->getName() );
		$smarty->assign( "userpage", $wgLang->getNsText( Namespace::getUser() ) . ":" . $wgUser->getName() );
		$smarty->assign( "loggedin", $wgUser->getID() != 0 );
		$smarty->assign( "sysop", $wgUser->isSysop() );
		if( $wgUser->getNewtalk() ) {
			$ntl = wfMsg( "newmessages",
				$this->makeKnownLink(
					$wgLang->getNsText( Namespace::getTalk( Namespace::getUser() ) )
						. ":" . $wgUser->getName(),
					wfMsg("newmessageslink")
				)
			);
		} else {
			$ntl = "";
		}
		$smarty->assign( "newtalk", $ntl );

		$smarty->assign( "logo", $this->logoText() );
		$smarty->assign( "pagestats", $this->pageStats() );
		$smarty->assign( "otherlanguages", $this->otherLanguages() );

		$smarty->assign( "debug", $out->mDebugtext );
		$smarty->assign( "reporttime", $out->reportTime() );
		
		$smarty->assign( "bodytext", $out->mBodytext );
		
		$smarty->display( $this->template . ".tpl" );
	}

	function smarty_function_wikimsg( $params, &$smarty ) {
		return wfMsg( $params['key'] );
	}
	
	function smarty_function_wikilink( $params, &$smarty ) {
		global $wgLang;
		$action = "";
		$popup = "";
		$page = "";
		
		if($params['action']) $action = "action=" . $params['action'];
		
		if($params['special']) {
			$page = $wgLang->specialPage( $params['special'] );
			#$text = $wgLang->getSpecialPageName( $params['special'] );
			$text = $popup = $params['special'];
			if($params['target']) $action .= "target=" . urlencode( $params['target'] );
		} else {
			if( $params['keypage'] )
				$title = Title::newFromText( wfMsg( $params['keypage'] ) );
			else
				$title = Title::newFromText( $params['name'] );
			if(isset($params["talk"])) {
				$title = Title::makeTitle( $title->getNamespace() ^ 1, $title->getDbKey() );
			}
			$text = $popup = $title->getPrefixedText();
			$page = urlencode( $title->getPrefixedUrl() );
		}
		
		$url = wfLocalUrlE( $page, $action );
		
		if($params['text']) $text = $params['text'];
		if($params['key']) $text = wfMsg( $params['key'] );
		if($popup) $popup = ' title="' . htmlspecialchars( $popup ) . '"';
		
		return "<a href=\"$url\"$popup>$text</a>";
	}

/*
	function Skin()
	function getSkinNames()
	function getStylesheet()
	
	function qbSetting()
	function initPage()
	function getHeadScripts() {
	function getUserStyles()
	function doGetUserStyles()
	function getBodyOptions()
	function getExternalLinkAttributes( $link, $text )
	function getInternalLinkAttributes( $link, $text, $broken = false )

	function getLogo()
	function beforeContent()
	function doBeforeContent()
	function getQuickbarCompensator( $rows = 1 )
	function afterContent()
	function doAfterContent()
	function pageTitleLinks()
	function printableLink()
	function pageTitle()
	function pageSubtitle()
	function nameAndLogin()
	function searchForm()
	function topLinks()
	function bottomLinks()
	function pageStats()
	function lastModified()
	function logoText( $align = "" )
	function quickBar()
	function specialPagesList()
	function mainPageLink()
	function copyrightLink()
	function aboutLink()
	function editThisPage()
	function deleteThisPage()
	function protectThisPage()
	function watchThisPage()
	function moveThisPage()
	function historyLink()
	function whatLinksHere()
	function userContribsLink()
	function emailUserLink()
	function watchPageLinksLink()
	function otherLanguages()
	function bugReportsLink()
	function dateLink()
	function talkLink()
	function transformContent( $text )

	function makeLink( $title, $text= "", $query = "", $trail = "" )
	function makeKnownLink( $title, $text = "", $query = "", $trail = "" )
	function makeBrokenLink( $title, $text = "", $query = "", $trail = "" )
	function makeStubLink( $title, $text = "", $query = "", $trail = "" )
	function fnamePart( $url )
	function makeImage( $url, $alt = "" )
	function makeImageLink( $name, $url, $alt = "" )
	function makeMediaLink( $name, $url, $alt = "" )
	function specialLink( $name, $key = "" )

	function beginHistoryList()
	function beginImageHistoryList()
	function endRecentChangesList()
	function endHistoryList()
	function endImageHistoryList()
	function historyLine( $ts, $u, $ut, $ns, $ttl, $oid, $c, $isminor )
	function imageHistoryLine( $iscur, $ts, $img, $u, $ut, $size, $c )

	
	function beginRecentChangesList()
	function recentChangesBlockLine ( $y ) {
	function recentChangesBlockGroup ( $y ) {
	function recentChangesBlock ()
	function recentChangesLine( $ts, $u, $ut, $ns, $ttl, $c, $isminor, $isnew, $watched = false, $oldid = 0 , $diffid = 0 )
	function recentChangesLineOld( $ts, $u, $ut, $ns, $ttl, $c, $isminor, $isnew, $watched = false, $oldid = 0, $diffid = 0 )
	function recentChangesLineNew( $ts, $u, $ut, $ns, $ttl, $c, $isminor, $isnew, $watched = false, $oldid = 0 , $diffid = 0 )

	function tocIndent($level) {
	function tocUnindent($level) {
	function tocLine($anchor,$tocline,$level) {
	function tocTable($toc) {
	function editSectionScript($section,$head) {
	function editSectionLink($section) {
*/

}

class SkinMontparnasse extends SkinSmarty {
	function initPage() {
		SkinSmarty::initPage();
		$this->template = "montparnasse";
	}
}

?>
