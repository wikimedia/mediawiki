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
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/** */
require_once( 'Revision.php' );
require_once( 'Export.php' );

/**
 *
 */
function wfSpecialExport( $page = '' ) {
	global $wgOut, $wgRequest, $wgExportAllowListContributors;
	global $wgExportAllowHistory;

	if( $wgRequest->getVal( 'action' ) == 'submit') {
		$page = $wgRequest->getText( 'pages' );
		if( $wgExportAllowHistory ) {
			$curonly = $wgRequest->getCheck( 'curonly' );
		} else {
			$curonly = true;
		}
	} else {
		# Pre-check the 'current version only' box in the UI
		$curonly = true;
	}
	
	$list_authors = $wgRequest->getCheck( 'listauthors' );
	if ( !$curonly || !$wgExportAllowListContributors ) $list_authors = false ;

	if( $page != '' ) {
		$wgOut->disable();
		header( "Content-type: application/xml; charset=utf-8" );
		$pages = explode( "\n", $page );

		$db =& wfGetDB( DB_SLAVE );
		$history = $curonly ? MW_EXPORT_CURRENT : MW_EXPORT_FULL;
		$exporter = new WikiExporter( $db, $history );
		$exporter->list_authors = $list_authors ;
		$exporter->openStream();
		$exporter->pagesByName( $pages );
		$exporter->closeStream();
		return;
	}

	$wgOut->addWikiText( wfMsg( "exporttext" ) );
	$titleObj = Title::makeTitle( NS_SPECIAL, "Export" );
	$action = $titleObj->escapeLocalURL( 'action=submit' );
	if( $wgExportAllowHistory ) {
		$checkbox = "<label><input type='checkbox' name='curonly' value='true' checked='checked' />
" . wfMsgHtml( 'exportcuronly' ) . "</label><br />";
	} else {
		$checkbox = "";
		$wgOut->addWikiText( wfMsg( "exportnohistory" ) );
	}
	$wgOut->addHTML( "
<form method='post' action=\"$action\">
<input type='hidden' name='action' value='submit' />
<textarea name='pages' cols='40' rows='10'></textarea><br />
$checkbox
<input type='submit' />
</form>
" );
}

?>
