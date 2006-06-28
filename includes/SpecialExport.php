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
require_once( 'Export.php' );

/**
 *
 */
function wfSpecialExport( $page = '' ) {
	global $wgOut, $wgRequest, $wgExportAllowListContributors;
	global $wgExportAllowHistory, $wgExportMaxHistory;

	$curonly = true;
	if( $wgRequest->getVal( 'action' ) == 'submit') {
		$page = $wgRequest->getText( 'pages' );
		$curonly = $wgRequest->getCheck( 'curonly' );
	}
	if( $wgRequest->getCheck( 'history' ) ) {
		$curonly = false;
	}
	if( !$wgExportAllowHistory ) {
		// Override
		$curonly = true;
	}
	
	$list_authors = $wgRequest->getCheck( 'listauthors' );
	if ( !$curonly || !$wgExportAllowListContributors ) $list_authors = false ;

	if( $page != '' ) {
		$wgOut->disable();
		
		// Cancel output buffering and gzipping if set
		// This should provide safer streaming for pages with history
		while( $status = ob_get_status() ) {
			ob_end_clean();
			if( $status['name'] == 'ob_gzhandler' ) {
				header( 'Content-Encoding:' );
			}
		}
		header( "Content-type: application/xml; charset=utf-8" );
		$pages = explode( "\n", $page );

		$db =& wfGetDB( DB_SLAVE );
		$history = $curonly ? MW_EXPORT_CURRENT : MW_EXPORT_FULL;
		$exporter = new WikiExporter( $db, $history );
		$exporter->list_authors = $list_authors ;
		$exporter->openStream();
		
		foreach( $pages as $page ) {
			if( $wgExportMaxHistory && !$curonly ) {
				$title = Title::newFromText( $page );
				if( $title ) {
					$count = Revision::countByTitle( $db, $title );
					if( $count > $wgExportMaxHistory ) {
						wfDebug( __FUNCTION__ .
							": Skipped $page, $count revisions too big\n" );
						continue;
					}
				}
			}
			$exporter->pageByName( $page );
		}
		
		$exporter->closeStream();
		return;
	}

	$wgOut->addWikiText( wfMsg( "exporttext" ) );
	$titleObj = Title::makeTitle( NS_SPECIAL, "Export" );
	
	$form = wfOpenElement( 'form', array( 'method' => 'post', 'action' => $titleObj->getLocalUrl() ) );
	$form .= wfOpenElement( 'textarea', array( 'name' => 'pages', 'cols' => 40, 'rows' => 10 ) ) . '</textarea><br />';
	if( $wgExportAllowHistory ) {
		$form .= wfCheck( 'curonly', true, array( 'value' => 'true', 'id' => 'curonly' ) );
		$form .= wfLabel( wfMsg( 'exportcuronly' ), 'curonly' ) . '<br />';
	} else {
		$wgOut->addWikiText( wfMsg( 'exportnohistory' ) );
	}
	$form .= wfHidden( 'action', 'submit' );
	$form .= wfSubmitButton( wfMsg( 'export-submit' ) ) . '</form>';
	$wgOut->addHtml( $form );
}

?>
