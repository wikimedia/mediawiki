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
	$s = $dbr->selectRow( 'cur', array( 'cur_id as id','cur_timestamp as timestamp','cur_user as user',
		'cur_user_text as user_text', 'cur_restrictions as restrictions','cur_comment as comment',
		'cur_text as text' ), $title->curCond(), $fname );
	if( $s !== false ) {
		$tl = xmlsafe( $title->getPrefixedText() );
		$xml = "  <page>\n";
		$xml .= "    <title>$tl</title>\n";
		if( $full ) {
			$xml .= "    <id>$s->id</id>\n";
		}
		if( $s->restrictions ) {
			$xml .= "    <restrictions>$s->restrictions</restrictions>\n";
		}
		if( !$curonly ) {
			$res = $dbr->select( 'old', array( 'old_id as id','old_timestamp as timestamp', 
				'old_user as user', 'old_user_text as user_text', 'old_comment as comment', 
				'old_text as text', 'old_flags as flags' ), $title->oldCond(), 
				$fname, array( 'ORDER BY' => 'old_timestamp' )
			);

			while( $s2 = $dbr->fetchObject( $res ) ) {
				$xml .= revision2xml( $s2, $full, false );
			}
		}
		$xml .= revision2xml( $s, $full, true );
		$xml .= "  </page>\n";
		wfProfileOut( $fname );
		return $xml;
	} else {
		wfProfileOut( $fname );
		return "";
	}
}

function revision2xml( $s, $full, $cur ) {
	$fname = 'revision2xml';
	wfProfileIn( $fname );
	
	$ts = wfTimestamp2ISO8601( $s->timestamp );
	$xml = "    <revision>\n";
	if($full && !$cur)
		$xml .= "    <id>$s->id</id>\n";
	$xml .= "      <timestamp>$ts</timestamp>\n";
	if($s->user) {
		$u = "<username>" . xmlsafe( $s->user_text ) . "</username>";
		if($full)
			$u .= "<id>$s->user</id>";
	} else {
		$u = "<ip>" . xmlsafe( $s->user_text ) . "</ip>";
	}
	$xml .= "      <contributor>$u</contributor>\n";
	if( !empty( $s->minor ) ) {
		$xml .= "      <minor/>\n";
	}
	if($s->comment != "") {
		$c = xmlsafe( $s->comment );
		$xml .= "      <comment>$c</comment>\n";
	}
	$t = xmlsafe( Article::getRevisionText( $s, "" ) );
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
