<?php
/**
 * Implements Special:Export
 *
 * Copyright © 2003-2008 Brion Vibber <brion@pobox.com>
 * © 2010+ Vitaliy Filippov <vitalif@mail.ru>
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
 * @ingroup SpecialPage
 */

/**
 * A special page that allows users to export pages in a file
 *
 * @ingroup SpecialPage
 */
class SpecialExport extends SpecialPage {

	private $curonly, $doExport, $templates;
	private $images;

	public function __construct() {
		parent::__construct( 'Export' );
	}

	public function execute( $par ) {
		global $wgSitename, $wgExportAllowListContributors, $wgExportFromNamespaces;
		global $wgExportAllowHistory, $wgExportMaxHistory, $wgExportMaxLinkDepth;
		global $wgExportAllowAll;

		$this->setHeaders();
		$this->outputHeader();

		// Set some variables
		$this->curonly = true;
		$this->doExport = false;
		$request = $this->getRequest();
		$this->templates = $request->getCheck( 'templates' );
		$this->images = $request->getCheck( 'images' );
		$this->pageLinkDepth = $this->validateLinkDepth(
			$request->getIntOrNull( 'pagelink-depth' )
		);
		$nsindex = '';
		$exportall = false;

		$state = $request->getValues();
		$state['errors'] = array();
		if ( !empty( $state['addcat'] ) ) {
			self::addPagesExec( $state );
			$page = $state['pages'];
		}
		elseif ( $request->wasPosted() && $par == '' ) {
			$page = $request->getText( 'pages' );
			$this->curonly = $request->getCheck( 'curonly' );
			$rawOffset = $request->getVal( 'offset' );

			if( $rawOffset ) {
				$offset = wfTimestamp( TS_MW, $rawOffset );
			} else {
				$offset = null;
			}

			$limit = $request->getInt( 'limit' );
			$dir = $request->getVal( 'dir' );
			$history = array(
				'dir' => 'asc',
				'offset' => false,
				'limit' => $wgExportMaxHistory,
			);
			$historyCheck = $request->getCheck( 'history' );

			if ( $this->curonly ) {
				$history = WikiExporter::CURRENT;
			} elseif ( !$historyCheck ) {
				if ( $limit > 0 && ($wgExportMaxHistory == 0 || $limit < $wgExportMaxHistory ) ) {
					$history['limit'] = $limit;
				}
				if ( !is_null( $offset ) ) {
					$history['offset'] = $offset;
				}
				if ( strtolower( $dir ) == 'desc' ) {
					$history['dir'] = 'desc';
				}
			}

			if( $page != '' ) {
				$this->doExport = true;
			}
		} else {
			// Default to current-only for GET requests.
			$page = $request->getText( 'pages', $par );
			$historyCheck = $request->getCheck( 'history' );

			if( $historyCheck ) {
				$history = WikiExporter::FULL;
			} else {
				$history = WikiExporter::CURRENT;
			}

			if( $page != '' ) {
				$this->doExport = true;
			}
		}

		if( !$wgExportAllowHistory ) {
			// Override
			$history = WikiExporter::CURRENT;
		}

		$list_authors = $request->getCheck( 'listauthors' );
		if ( !$this->curonly || !$wgExportAllowListContributors ) {
			$list_authors = false;
		}

		if ( $this->doExport ) {
			$this->doExport( $page, $history, $list_authors, $exportall );
			return;
		}

		$out = $this->getOutput();
		$out->addWikiMsg( 'exporttext' );

		$form = Xml::openElement( 'form', array( 'method' => 'post',
			'action' => $this->getTitle()->getLocalUrl( 'action=submit' ) ) );
		foreach ( $state['errors'] as $e ) {
			$form .= wfMsgExt( $e[0], array( 'parse' ), $e[1] );
		}

		$form .= self::addPagesForm( $state );

		$form .= Xml::element( 'textarea', array( 'name' => 'pages', 'cols' => 40, 'rows' => 10 ), $page, false );
		$form .= '<br />';

		if( $wgExportAllowHistory ) {
			$form .= Xml::checkLabel(
				$this->msg( 'exportcuronly' )->text(),
				'curonly',
				'curonly',
				$request->getCheck( 'curonly' ) ? true : false
			) . '<br />';
		} else {
			$out->addWikiMsg( 'exportnohistory' );
		}

		$form .= Xml::checkLabel( wfMsg( 'export-include-images' ), 'images', 'wpExportImages', $request->getCheck('images') ? true : false ) . '<br />';
		$form .= Xml::checkLabel( wfMsg( 'export-download' ), 'wpDownload', 'wpDownload', true ) . '<br />';
		$form .= Xml::checkLabel( wfMsg( 'export-selfcontained' ), 'selfcontained', 'wpSelfContained', $request->getCheck('selfcontained') ? true : false ) . '<br />';
		wfRunHooks( 'ExportAfterChecks', array( $this, &$form ) );

		if ( $wgExportAllowListContributors ) {
			$form .= Xml::checkLabel(
				$this->msg( 'exportlistauthors' )->text(),
				'listauthors',
				'listauthors',
				$request->wasPosted() ? $request->getCheck( 'listauthors' ) : false
			) . '<br />';
		}

		$form .= Xml::submitButton( $this->msg( 'export-submit' )->text(), Linker::tooltipAndAccesskeyAttribs( 'export' ) );
		$form .= Xml::closeElement( 'form' );

		$out->addHTML( $form );
	}

	/**
	 * @return bool
	 */
	public static function userCanOverrideExportDepth() {
		global $wgUser;
		return $wgUser->isAllowed( 'override-export-depth' );
	}

	/**
	 * Do the actual page exporting
	 *
	 * @param $page String: user input on what page(s) to export
	 * @param $history Mixed: one of the WikiExporter history export constants
	 * @param $list_authors Boolean: Whether to add distinct author list (when
	 *                      not returning full history)
	 * @param $exportall Boolean: Whether to export everything
	 */
	private function doExport( $page, $history, $list_authors, $exportall ) {
		global $wgExportMaxHistory, $wgOut, $wgSitename;

		$request = $this->getRequest();

		// Split up and normalize input
		$pages = array();
		foreach( explode( "\n", $page ) as $pageName ) {
			$pageName = trim( $pageName );
			$title = Title::newFromText( $pageName );
			if( $title && $title->getInterwiki() == '' && $title->getText() !== '' &&
			    $title->userCanRead() ) {
				// Only record each page once!
				$pages[ $title->getPrefixedText() ] = $title;
			}
		}
		$pages = array_values( $pages );

		/* Ok, let's get to it... */
		if( $history == WikiExporter::CURRENT ) {
			$lb = false;
			$db = wfGetDB( DB_SLAVE );
			$buffer = WikiExporter::BUFFER;
		} else {
			// Use an unbuffered query; histories may be very long!
			$lb = wfGetLBFactory()->newMainLB();
			$db = $lb->getConnection( DB_SLAVE );
			$buffer = WikiExporter::STREAM;

			// This might take a while... :D
			wfSuppressWarnings();
			set_time_limit(0);
			wfRestoreWarnings();
		}

		$exporter = new WikiExporter( $db, $history, $buffer, WikiExporter::TEXT,
			$list_authors, $request->getCheck( 'images' ), $request->getCheck( 'selfcontained' ) );
		$exporter->openStream();
		foreach( $pages as $title ) {
			$exporter->pageByTitle( $title );
		}
		$exporter->closeStream();
		$archive = $mimetype = $extension = '';
		if ( !$exporter->getArchive( $archive, $mimetype, $extension ) ) {
			die();
		}

		$wgOut->disable();
		// Cancel output buffering and gzipping if set
		// This should provide safer streaming for pages with history
		wfResetOutputBuffers();
		header( "Content-type: $mimetype" );
		if( $request->getCheck( 'wpDownload' ) ) {
			// Provide a sane filename suggestion
			$filename = urlencode( $wgSitename . '-' . wfTimestampNow() . '.' . $extension );
			header( "Content-disposition: attachment;filename={$filename}" );
		}
		readfile( $archive );

		if( $lb ) {
			$lb->closeAll();
		}
	}

	// Execute page selection form, save page list to $state['pages'] and errors to $state['errors']
	static function addPagesExec( &$state ) {
		// Split up and normalize input
		$pageSet = array();
		foreach( explode( "\n", $state['pages'] ) as $pageName ) {
			$pageName = trim( $pageName );
			$title = Title::newFromText( $pageName );
			if( $title && $title->getInterwiki() == '' && $title->getText() !== '' ) {
				// Only record each page once!
				$pageSet[ $title->getPrefixedText() ] = $title;
			}
		}

		// Validate parameter values
		$catname     = isset( $state['catname'] )     ? $state['catname']     : '';
		$notcategory = isset( $state['notcategory'] ) ? $state['notcategory'] : '';
		$namespace   = isset( $state['namespace'] )   ? $state['namespace']   : '';
		$modifydate  = isset( $state['modifydate'] )  ? $state['modifydate']  : '';
		if ( !strlen( $modifydate ) || !( $modifydate = wfTimestampOrNull( TS_MW, $modifydate ) ) ) {
			$modifydate = NULL;
		}
		if ( !strlen( $catname ) || !( $catname = Title::newFromText( $catname, NS_CATEGORY ) ) ||
			$catname->getNamespace() != NS_CATEGORY ) {
			$catname = NULL;
		}
		if ( !strlen( $notcategory ) || !( $notcategory = Title::newFromText( $notcategory, NS_CATEGORY ) ) ||
			$notcategory->getNamespace() != NS_CATEGORY ) {
			$notcategory = NULL;
		}
		if ( $namespace === 'Main' || $namespace == '(Main)' || $namespace === wfMsg( 'blanknamespace' ) ) {
			$namespace = 0;
		} elseif ( $namespace === '' || !( $namespace = Title::newFromText( "$namespace:Dummy", NS_MAIN ) ) ) {
			$namespace = NULL;
		} else {
			$namespace = $namespace->getNamespace();
		}

		// Add pages from requested category and/or namespace
		if ( $modifydate !== NULL || $namespace !== NULL || $catname !== NULL ) {
			$catpages = self::getPagesFromCategory( $catname, !empty( $state['closure'] ), $namespace, $modifydate );
			foreach ( $catpages as $title ) {
				$pageSet[ $title->getPrefixedText() ] = $title;
			}
		}

		// Look up any linked pages if asked...
		$linkDepth = self::validateLinkDepth( !empty( $state['link-depth'] ) ? $state['link-depth'] : 0 );
		$t = !empty( $state[ 'templates' ] );
		$p = !empty( $state[ 'pagelinks' ] );
		$i = !empty( $state[ 'images' ] );
		$s = !empty( $state[ 'subpages' ] );
		$r = !empty( $state[ 'redirects' ] );
		$step = 0;
		do {
			// Loop as there may be more than one closure type
			$added = 0;
			if( $t ) $added += self::getTemplates( $pageSet );
			if( $p ) $added += self::getPagelinks( $pageSet );
			if( $i ) $added += self::getImages( $pageSet );
			if( $s ) $added += self::getSubpages( $pageSet );
			if( $r ) $added += self::getRedirects( $pageSet );
			$step++;
		} while( $t+$p+$i+$s > 1 && $added > 0 && ( !$linkDepth || $step < $linkDepth ) );

		// Filter user-readable pages (also MW Bug 8824)
		foreach ( $pageSet as $key => $title ) {
			if ( !$title->userCanRead() ) {
				unset( $pageSet[ $key ] );
			}
		}

		// Filter pages by $modifydate
		if ( $modifydate !== NULL && $pageSet ) {
			$ids = array();
			foreach ( $pageSet as $key => $title ) {
				$ids[ $title->getArticleId() ] = $title;
			}
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select( array( 'page', 'revision' ), 'page_id',
				array(
					'page_latest=rev_id',
					'page_id' => array_keys( $ids ),
					'rev_timestamp > '.$dbr->timestamp( $modifydate )
				), __METHOD__ );
			foreach ( $res as $row ) {
				unset( $ids[ $row->page_id ] );
			}
			foreach ( $ids as $title ) {
				unset( $pageSet[ $title->getPrefixedText() ] );
			}
		}

		// Filter pages from requested NOT-category
		if ( $notcategory !== NULL ) {
			$notlist = self::getPagesFromCategory( $notcategory );
			foreach ( $notlist as $title ) {
				unset( $pageSet[ $title->getPrefixedText() ] );
			}
		}

		// Save resulting page list
		$pages = array_keys( $pageSet );
		sort( $pages );
		$state['pages'] = implode( "\n", $pages );

		// Save errors
		$state['errors'] = array();
		if ( !$catname && isset( $state['catname'] ) && $state['catname'] !== '' ) {
			$state['errors'][] = array( 'export-invalid-catname', $state['catname'] );
		}
		if ( !$notcategory && isset( $state['notcategory'] ) && $state['notcategory'] !== '' ) {
			$state['errors'][] = array( 'export-invalid-notcategory', $state['notcategory'] );
		}
		if ( $modifydate ) {
			$state['modifydate'] = wfTimestamp( TS_DB, $modifydate );
		} elseif ( isset( $state['modifydate'] ) && $state['modifydate'] !== '' ) {
			$state['errors'][] = array( 'export-invalid-modifydate', $state['modifydate'] );
		}
		if ( !$namespace && isset( $state['namespace'] ) && $state['namespace'] !== '' ) {
			$state['errors'][] = array( 'export-invalid-namespace', $state['namespace'] );
		}
	}

	// Display page selection form, enclosed into a <fieldset>
	static function addPagesForm( $state ) {
		global $wgExportMaxLinkDepth, $wgRequest;
		$form = '<fieldset class="addpages">';
		$form .= '<legend>' . wfMsgExt( 'export-addpages', 'parse' ) . '</legend>';
		$textboxes = array(
			'catname'     => 20,
			'namespace'   => 20,
			'modifydate'  => 18,
			'notcategory' => 20,
		);
		// Textboxes:
		foreach ( $textboxes as $k => $size ) {
			$form .= '<div class="ap_'.$k.'">' .
				Xml::inputLabel( wfMsg( "export-$k" ), $k, "ap-$k", $size, !empty( $state[ $k ] ) ? $state[ $k ] : '' ) . '</div>';
		}
		if( $wgExportMaxLinkDepth || self::userCanOverrideExportDepth() ) {
			$form .= Xml::inputLabel( wfMsg( 'export-link-depth' ), 'link-depth', 'link-depth', 20, $wgRequest->getVal('link-depth') ) . '<br />';
		}
		// Checkboxes:
		foreach ( array( 'closure', 'templates', 'images', 'pagelinks', 'subpages', 'redirects' ) as $k ) {
			$form .= '<div class="ap_'.$k.'">' . Xml::checkLabel(
				wfMsg( "export-$k" ), $k, "ap-$k", !empty( $state[ $k ] ),
				array( 'style' => 'vertical-align: middle' )
			) . '</div>';
		}
		// Submit button:
		$form .= '<div class="ap_submit">' . Xml::submitButton( wfMsg( 'export-addcat' ), array( 'name' => 'addcat' ) ) . '</div>';
		$form .= '</fieldset>';
		return $form;
	}

	// Get pages from ((category possibly with subcategories) and/or namespace), or (modified after $modifydate)
	static function getPagesFromCategory( $categories, $closure = false, $namespace = NULL, $modifydate = NULL ) {
		$dbr = wfGetDB( DB_SLAVE );

		if ( $categories ) {
			if ( is_object( $categories ) ) {
				$categories = $categories->getDBkey();
			}
			$cats = array();
			foreach ( ( is_array( $categories ) ? $categories : array( $categories ) ) as $c ) {
				$cats[ $c ] = true;
			}
			// Get subcategories
			while ( $categories && $closure ) {
				$res = $dbr->select( array( 'page', 'categorylinks' ), 'page_title',
					array( 'cl_from=page_id', 'cl_to' => $categories, 'page_namespace' => NS_CATEGORY ),
					__METHOD__ );
				$categories = array();
				foreach ( $res as $row ) {
					if ( !$cats[ $row->page_title ] ) {
						$categories[] = $row->page_title;
						$cats[ $row->page_title ] = $row;
					}
				}
			}
			$categories = array_keys( $cats );
		}

		// Get pages
		$tables = array( 'page' );
		$fields = 'page.*';
		$where = array();
		if ( $categories ) {
			$tables[] = 'categorylinks';
			$where[] = 'cl_from=page_id';
			$where['cl_to'] = $categories;
		}
		if ( $namespace !== NULL ) {
			$where['page_namespace'] = $namespace;
		} elseif ( $categories === NULL && $modifydate !== NULL ) {
			$where[] = 'page_touched >= '.$dbr->timestamp( $modifydate );
		}
		$res = $dbr->select( $tables, $fields, $where, __METHOD__ );
		$pages = array();
		foreach ( $res as $row ) {
			$pages[] = Title::newFromRow( $row );
		}

		return array_values( $pages );
	}

	/**
	 * Validate link depth setting, if available.
	 */
	public static function validateLinkDepth( $depth ) {
		global $wgExportMaxLinkDepth, $wgExportMaxLinkDepthLimit;
		if( $depth <= 0 ) {
			return 0;
		}
		if ( !self::userCanOverrideExportDepth() &&
			$depth > $wgExportMaxLinkDepth ) {
			return $wgExportMaxLinkDepth;
		}
		return $depth;
	}

	/**
	 * Expand a list of pages to include templates used in those pages.
	 * @param &$pageSet array, associative array indexed by titles
	 * @return array associative array index by titles
	 */
	public static function getTemplates( &$pageSet ) {
		return self::getLinks(
			$pageSet, 'templatelinks', 'tl_from',
			array( 'page_namespace=tl_namespace', 'page_title=tl_title' )
		);
	}

	/**
	 * Expand a list of pages to include pages linked to from that page.
	 * @param &$pageSet array, associative array indexed by title prefixed text for output
	 * @return int count of added pages
	 */
	public static function getPageLinks( &$pageSet ) {
		return self::getLinks(
			$pageSet, 'pagelinks', 'pl_from',
			array( 'page_namespace=pl_namespace', 'page_title=pl_title' )
		);
	}

	/**
	 * Expand a list of pages to include images used in those pages.
	 * @param &$pageSet array, associative array indexed by title prefixed text for output
	 * @return int count of added pages
	 */
	public static function getImages( &$pageSet ) {
		return self::getLinks(
			$pageSet, 'imagelinks', 'il_from',
			array( 'page_namespace='.NS_FILE, 'page_title=il_to' )
		);
	}

	/**
	 * Expand a list of pages to include all their subpages.
	 * @param &$pageSet array, associative array indexed by title prefixed text for output
	 * @return int count of added pages
	 */
	public static function getSubpages( &$pageSet ) {
		$dbr = wfGetDB( DB_SLAVE );
		$where = array();
		$ids = array();
		foreach ( $pageSet as $title ) {
			$ids[ $title->getArticleId() ] = true;
			$where[ $title->getNamespace() ][] = 'page_title LIKE '.$dbr->addQuotes( $title->getDBkey().'/%' );
		}
		foreach ( $where as $ns => &$w ) {
			$w = '( page_namespace='.$ns.' AND ( '.implode( ' OR ', $w ).' ) )';
		}
		$where = '( '.implode( ' OR ', $where ).' )';
		$result = $dbr->select( 'page', '*', array( $where ), __METHOD__ );
		$added = 0;
		foreach( $result as $row ) {
			if( empty( $ids[ $row->page_id ] ) ) {
				$add = Title::newFromRow( $row );
				$pageSet[ $add->getPrefixedText() ] = $add;
				$added++;
			}
		}
		return $added;
	}

	/**
	 * Expand a list of pages to include redirects linking to them.
	 * @param &$pageSet array, associative array indexed by title prefixed text for output
	 * @return int count of added pages
	 */
	public static function getRedirects( &$pageSet ) {
		$dbr = wfGetDB( DB_SLAVE );
		$where = array();
		$ids = array();
		foreach ( $pageSet as $title ) {
			$ids[ $title->getArticleId() ] = true;
			$where[ $title->getNamespace() ][] = $title->getDBkey();
		}
		foreach ( $where as $ns => &$w ) {
			$w = '( rd_namespace='.$ns.' AND rd_title IN ( ' . $dbr->makeList( $w ) . ' ) )';
		}
		$where = '( '.implode( ' OR ', $where ).' )';
		$result = $dbr->select( array( 'page', 'redirect' ), 'page.*', array( 'page_id=rd_from', $where ), __METHOD__ );
		$added = 0;
		foreach( $result as $row ) {
			if( empty( $ids[ $row->page_id ] ) ) {
				$add = Title::newFromRow( $row );
				$pageSet[ $add->getPrefixedText() ] = $add;
				$added++;
			}
		}
		return $added;
	}

	/**
	 * Expand a list of pages to include items used in those pages.
	 * @private
	 */
	private static function getLinks( &$pageSet, $table, $id_field, $join ) {
		if ( !$pageSet ) {
			return 0;
		}
		$dbr = wfGetDB( DB_SLAVE );
		$ids = array();
		foreach( $pageSet as $title ) {
			$ids[ $title->getArticleId() ] = true;
		}
		$result = $dbr->select(
			array( 'page', $table ), 'page.*',
			$join + array( $id_field => array_keys( $ids ) ),
			__METHOD__,
			array( 'GROUP BY' => 'page_id' )
		);
		$added = 0;
		foreach( $result as $row ) {
			if( empty( $ids[ $row->page_id ] ) ) {
				$add = Title::newFromRow( $row );
				$pageSet[ $add->getPrefixedText() ] = $add;
				$added++;
			}
		}
		return $added;
	}

}
