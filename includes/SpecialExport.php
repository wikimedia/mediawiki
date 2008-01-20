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

	$name = $title->getDBkey();

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
 * Expand a list of pages to include templates used in those pages.
 * @input $pages string newline-separated list of page titles
 * @output string newline-separated list of page titles
 */
function wfExportGetTemplates( $pages ) {
	$pageList = array_unique( array_filter( explode( "\n", $pages ) ) );
	$output = array();
	$dbr = wfGetDB( DB_SLAVE );
	foreach( $pageList as $page ) {
		$title = Title::newFromText( $page );
		$output[$title->getPrefixedText()] = true;
		if( $title ) {
			/// @fixme May or may not be more efficient to batch these
			///        by namespace when given multiple input pages.
			$result = $dbr->select(
				array( 'page', 'templatelinks' ),
				array( 'tl_namespace', 'tl_title' ),
				array(
					'page_namespace' => $title->getNamespace(),
					'page_title' => $title->getDbKey(),
					'page_id=tl_from' ),
				__METHOD__ );
			foreach( $result as $row ) {
				$template = Title::makeTitle( $row->tl_namespace, $row->tl_title );
				$output[$template->getPrefixedText()] = true;
			}
		}
	}
	return implode( "\n", array_keys( $output ) );
}

/**
 *
 */
function wfSpecialExport( $page = '' ) {
	global $wgOut, $wgRequest, $wgSitename, $wgExportAllowListContributors;
	global $wgExportAllowHistory, $wgExportMaxHistory;

	$curonly = true;
	$doexport = false;

	if ( $wgRequest->getCheck( 'addcat' ) ) {
		$page = $wgRequest->getText( 'pages' );
		$catname = $wgRequest->getText( 'catname' );
		
		if ( $catname !== '' && $catname !== NULL && $catname !== false ) {
			$t = Title::makeTitleSafe( NS_CATEGORY, $catname );
			if ( $t ) {
				/**
				 * @fixme This can lead to hitting memory limit for very large
				 * categories. Ideally we would do the lookup synchronously
				 * during the export in a single query.
				 */
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
	
	if( $wgRequest->getCheck( 'templates' ) ) {
		$page = wfExportGetTemplates( $page );
	}

	if ( $doexport ) {
		$wgOut->disable();
		
		// Cancel output buffering and gzipping if set
		// This should provide safer streaming for pages with history
		wfResetOutputBuffers();
		header( "Content-type: application/xml; charset=utf-8" );
		if( $wgRequest->getCheck( 'wpDownload' ) ) {
			// Provide a sane filename suggestion
			$filename = urlencode( $wgSitename . '-' . wfTimestampNow() . '.xml' );
			$wgRequest->response()->header( "Content-disposition: attachment;filename={$filename}" );
		}
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

	$self = SpecialPage::getTitleFor( 'Export' );
	$wgOut->addHtml( wfMsgExt( 'exporttext', 'parse' ) );
	
	$form = Xml::openElement( 'form', array( 'method' => 'post',
		'action' => $self->getLocalUrl( 'action=submit' ) ) );
	
	$form .= Xml::inputLabel( wfMsg( 'export-addcattext' )	, 'catname', 'catname', 40 ) . '&nbsp;';
	$form .= Xml::submitButton( wfMsg( 'export-addcat' ), array( 'name' => 'addcat' ) ) . '<br />';
	
	$form .= Xml::openElement( 'textarea', array( 'name' => 'pages', 'cols' => 40, 'rows' => 10 ) );
	$form .= htmlspecialchars( $page );
	$form .= Xml::closeElement( 'textarea' );
	$form .= '<br />';
	
	if( $wgExportAllowHistory ) {
		$form .= Xml::checkLabel( wfMsg( 'exportcuronly' ), 'curonly', 'curonly', true ) . '<br />';
	} else {
		$wgOut->addHtml( wfMsgExt( 'exportnohistory', 'parse' ) );
	}
	$form .= Xml::checkLabel( wfMsg( 'export-templates' ), 'templates', 'wpExportTemplates', false ) . '<br />';
	$form .= Xml::checkLabel( wfMsg( 'export-download' ), 'wpDownload', 'wpDownload', true ) . '<br />';
	
	$form .= Xml::submitButton( wfMsg( 'export-submit' ) );
	$form .= Xml::closeElement( 'form' );
	$wgOut->addHtml( $form );
}