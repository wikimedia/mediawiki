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

require_once( 'Revision.php' );

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
function wfSpecialExport( $page = '' ) {
	global $wgOut, $wgLang, $wgRequest;
	
	if( $wgRequest->getVal( 'action' ) == 'submit') {
		$page = $wgRequest->getText( 'pages' );
		$curonly = $wgRequest->getCheck( 'curonly' );
	} else {
		# Pre-check the 'current version only' box in the UI
		$curonly = true;
	}
	
	if( $page != '' ) {
		$wgOut->disable();
		header( "Content-type: application/xml; charset=utf-8" );
		$pages = explode( "\n", $page );
		$xml = pages2xml( $pages, $curonly );
		echo $xml;
		return;
	}
	
	$wgOut->addWikiText( wfMsg( "exporttext" ) );
	$titleObj = Title::makeTitle( NS_SPECIAL, "Export" );
	$action = $titleObj->escapeLocalURL();
	$wgOut->addHTML( "
<form method='post' action=\"$action\">
<input type='hidden' name='action' value='submit' />
<textarea name='pages' cols='40' rows='10'></textarea><br />
<label><input type='checkbox' name='curonly' value='true' checked='checked' />
" . wfMsg( "exportcuronly" ) . "</label><br />
<input type='submit' />
</form>
" );
}

function pages2xml( $pages, $curonly = false ) {
	$fname = 'pages2xml';
	wfProfileIn( $fname );
	
	global $wgContLanguageCode, $wgInputEncoding, $wgContLang;
	$xml = "<" . "?xml version=\"1.0\" encoding=\"UTF-8\" ?" . ">\n" .
		"<mediawiki version=\"0.1\" xml:lang=\"$wgContLanguageCode\">\n";
	foreach( $pages as $page ) {
		$xml .= page2xml( $page, $curonly );
	}
	$xml .= "</mediawiki>\n";
	if($wgInputEncoding != "utf-8")
		$xml = $wgContLang->iconv( $wgInputEncoding, "utf-8", $xml );
	
	wfProfileOut( $fname );
	return $xml;
}

function page2xml( $page, $curonly, $full = false ) {
	global $wgLang;
	$fname = 'page2xml';
	wfProfileIn( $fname );
	
	$title = Title::NewFromText( $page );
	if( !$title ) {
		wfProfileOut( $fname );
		return "";
	}

	$dbr =& wfGetDB( DB_SLAVE );
	$s = $dbr->selectRow( 'page',
		array( 'page_id', 'page_restrictions' ),
		array( 'page_namespace' => $title->getNamespace(),
			   'page_title'     => $title->getDbkey() ) );
	if( $s ) {
		$tl = xmlsafe( $title->getPrefixedText() );
		$xml = "  <page>\n";
		$xml .= "    <title>$tl</title>\n";
		
		if( $full ) {
			$xml .= "    <id>$s->page_id</id>\n";
		}
		if( $s->page_restrictions ) {
			$xml .= "    <restrictions>" . xmlsafe( $s->page_restrictions ) . "</restrictions>\n";
		}

		if( $curonly ) {
			$res = Revision::fetchRevision( $title );
		} else {
			$res = Revision::fetchAllRevisions( $title );
		}
		if( $res ) {
			while( $s = $res->fetchObject() ) {
				$rev = new Revision( $s );
				$xml .= revision2xml( $rev, $full, false );
			}
			$res->free();
		}
		
		$xml .= "  </page>\n";
		wfProfileOut( $fname );
		return $xml;
	} else {
		wfProfileOut( $fname );
		return "";
	}
}

/**
 * @return string
 * @param Revision $rev
 * @param bool $full
 * @access private
 */
function revision2xml( $rev, $full ) {
	$fname = 'revision2xml';
	wfProfileIn( $fname );
	
	$xml = "    <revision>\n";
	if( $full )
		$xml .= "    <id>" . $rev->getId() . "</id>\n";
	
	$ts = wfTimestamp2ISO8601( $rev->getTimestamp() );
	$xml .= "      <timestamp>$ts</timestamp>\n";
	
	if( $rev->getUser() ) {
		$u = "<username>" . xmlsafe( $rev->getUserText() ) . "</username>";
		if( $full )
			$u .= "<id>" . $rev->getUser() . "</id>";
	} else {
		$u = "<ip>" . xmlsafe( $rev->getUserText() ) . "</ip>";
	}
	$xml .= "      <contributor>$u</contributor>\n";
	
	if( $rev->isMinor() ) {
		$xml .= "      <minor/>\n";
	}
	if($rev->getComment() != "") {
		$c = xmlsafe( $rev->getComment() );
		$xml .= "      <comment>$c</comment>\n";
	}
	$t = xmlsafe( $rev->getText() );
	$xml .= "      <text>$t</text>\n";
	$xml .= "    </revision>\n";
	wfProfileOut( $fname );
	return $xml;
}

function wfTimestamp2ISO8601( $ts ) {
	#2003-08-05T18:30:02Z
	return preg_replace( '/^(....)(..)(..)(..)(..)(..)$/', '$1-$2-$3T$4:$5:$6Z', $ts );
}

function xmlsafe( $string ) {
	$fname = 'xmlsafe';
	wfProfileIn( $fname );
	
	/**
	 * The page may contain old data which has not been properly normalized.
	 * Invalid UTF-8 sequences or forbidden control characters will make our
	 * XML output invalid, so be sure to strip them out.
	 */
	global $wgUseLatin1;
	if( $wgUseLatin1 ) {
		/**
		 * We know the UTF-8 is valid since we converted outselves.
		 * Just check for forbidden controls...
		 */
		$string = preg_replace( '/[\x00-\x08\x0b-\x1f]/', '', $string );
	} else {
		$string = UtfNormal::cleanUp( $string );
	}
	
	$string = htmlspecialchars( $string );
	wfProfileOut( $fname );
	return $string;
}

?>
