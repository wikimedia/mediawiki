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
 * @addtogroup SpecialPage
 */

function wfExportGetPagesFromCategory( $title ) {
	global $wgContLang;

	$name = $title->getDBKey();

	$dbr = wfGetDB( DB_SLAVE );

	list( $page, $categorylinks ) = $dbr->tableNamesN( 'page', 'categorylinks' );
	$sql = "SELECT page_namespace, page_title FROM $page " .
		"JOIN $categorylinks ON cl_from = page_id " .
		"WHERE cl_to = " . $dbr->addQuotes( $name );

	$pages = array();
	$res = $dbr->query( $sql, 'wfExportGetPagesFromCategory' );
	while ( $row = $dbr->fetchObject( $res ) ) {
		$n = $row->page_title;
		if ($row->page_namespace) {
			$ns = $wgContLang->getNsText( $row->page_namespace );
			$n = $ns . ':' . $n;
		}

		$pages[] = $n;
	}
	$dbr->freeResult($res);

	return $pages;
}

/**
 *
 */
function wfSpecialExport( $page = '' ) {
	global $wgOut, $wgRequest, $wgExportAllowListContributors;
	global $wgExportAllowHistory, $wgExportMaxHistory;

	$curonly = true;
	$doexport = false;

	if ( $wgRequest->getCheck( 'addcat' ) ) {
		$page = $wgRequest->getText( 'pages' );
		$catname = $wgRequest->getText( 'catname' );
		
		if ( $catname !== '' && $catname !== NULL && $catname !== false ) {
			$t = Title::makeTitleSafe( NS_CATEGORY, $catname );
			if ( $t ) {
				$catpages = wfExportGetPagesFromCategory( $t );
				if ( $catpages ) $page .= "\n" . implode( "\n", $catpages );
			}
		}
	}
	else if( $wgRequest->wasPosted() && $page == '' ) {
		$page = $wgRequest->getText( 'pages' );
		$curonly = $wgRequest->getCheck( 'curonly' );
		$rawOffset = $wgRequest->getVal( 'offset' );
		if( $rawOffset ) {
			$offset = wfTimestamp( TS_MW, $rawOffset );
		} else {
			$offset = null;
		}
		$limit = $wgRequest->getInt( 'limit' );
		$dir = $wgRequest->getVal( 'dir' );
		$history = array(
			'dir' => 'asc',
			'offset' => false,
			'limit' => $wgExportMaxHistory,
		);
		$historyCheck = $wgRequest->getCheck( 'history' );
		if ( $curonly ) {
			$history = WikiExporter::CURRENT;
		} elseif ( !$historyCheck ) {
			if ( $limit > 0 && $limit < $wgExportMaxHistory ) {
				$history['limit'] = $limit;
			}
			if ( !is_null( $offset ) ) {
				$history['offset'] = $offset;
			}
			if ( strtolower( $dir ) == 'desc' ) {
				$history['dir'] = 'desc';
			}
		}
		
		if( $page != '' ) $doexport = true;
	} else {
		// Default to current-only for GET requests
		$page = $wgRequest->getText( 'pages', $page );
		$historyCheck = $wgRequest->getCheck( 'history' );
		if( $historyCheck ) {
			$history = WikiExporter::FULL;
		} else {
			$history = WikiExporter::CURRENT;
		}
		
		if( $page != '' ) $doexport = true;
	}

	if( !$wgExportAllowHistory ) {
		// Override
		$history = WikiExporter::CURRENT;
	}
	
	$list_authors = $wgRequest->getCheck( 'listauthors' );
	if ( !$curonly || !$wgExportAllowListContributors ) $list_authors = false ;

	if ( $doexport ) {
		$wgOut->disable();
		
		// Cancel output buffering and gzipping if set
		// This should provide safer streaming for pages with history
		wfResetOutputBuffers();
		header( "Content-type: application/xml; charset=utf-8" );
		$pages = explode( "\n", $page );

		$db = wfGetDB( DB_SLAVE );
		$exporter = new WikiExporter( $db, $history );
		$exporter->list_authors = $list_authors ;
		$exporter->openStream();
		
		foreach( $pages as $page ) {
			/*
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
			}*/

			#Bug 8824: Only export pages the user can read
			$title = Title::newFromText( $page );
			if( is_null( $title ) ) continue; #TODO: perhaps output an <error> tag or something.
			if( !$title->userCan( 'read' ) ) continue; #TODO: perhaps output an <error> tag or something.

			$exporter->pageByTitle( $title );
		}
		
		$exporter->closeStream();
		return;
	}

	$wgOut->addWikiText( wfMsg( "exporttext" ) );
	$titleObj = SpecialPage::getTitleFor( "Export" );
	
	$form = wfOpenElement( 'form', array( 'method' => 'post', 'action' => $titleObj->getLocalUrl() ) );

	$form .= wfInputLabel( wfMsg( 'export-addcattext' ), 'catname', 'catname', 40 ) . ' ';
	$form .= wfSubmitButton( wfMsg( 'export-addcat' ), array( 'name' => 'addcat' ) ) . '<br />';

	$form .= wfOpenElement( 'textarea', array( 'name' => 'pages', 'cols' => 40, 'rows' => 10 ) ) . htmlspecialchars($page). '</textarea><br />';

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


