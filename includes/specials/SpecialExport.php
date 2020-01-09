<?php
/**
 * Implements Special:Export
 *
 * Copyright © 2003-2008 Brion Vibber <brion@pobox.com>
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

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

/**
 * A special page that allows users to export pages in a XML file
 *
 * @ingroup SpecialPage
 */
class SpecialExport extends SpecialPage {
	protected $curonly, $doExport, $pageLinkDepth, $templates;

	public function __construct() {
		parent::__construct( 'Export' );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$config = $this->getConfig();

		// Set some variables
		$this->curonly = true;
		$this->doExport = false;
		$request = $this->getRequest();
		$this->templates = $request->getCheck( 'templates' );
		$this->pageLinkDepth = $this->validateLinkDepth(
			$request->getIntOrNull( 'pagelink-depth' )
		);
		$nsindex = '';
		$exportall = false;

		if ( $request->getCheck( 'addcat' ) ) {
			$page = $request->getText( 'pages' );
			$catname = $request->getText( 'catname' );

			if ( $catname !== '' && $catname !== null && $catname !== false ) {
				$t = Title::makeTitleSafe( NS_MAIN, $catname );
				if ( $t ) {
					/**
					 * @todo FIXME: This can lead to hitting memory limit for very large
					 * categories. Ideally we would do the lookup synchronously
					 * during the export in a single query.
					 */
					$catpages = $this->getPagesFromCategory( $t );
					if ( $catpages ) {
						if ( $page !== '' ) {
							$page .= "\n";
						}
						$page .= implode( "\n", $catpages );
					}
				}
			}
		} elseif ( $request->getCheck( 'addns' ) && $config->get( 'ExportFromNamespaces' ) ) {
			$page = $request->getText( 'pages' );
			$nsindex = $request->getText( 'nsindex', '' );

			if ( strval( $nsindex ) !== '' ) {
				/**
				 * Same implementation as above, so same @todo
				 */
				$nspages = $this->getPagesFromNamespace( $nsindex );
				if ( $nspages ) {
					$page .= "\n" . implode( "\n", $nspages );
				}
			}
		} elseif ( $request->getCheck( 'exportall' ) && $config->get( 'ExportAllowAll' ) ) {
			$this->doExport = true;
			$exportall = true;

			/* Although $page and $history are not used later on, we
			nevertheless set them to avoid that PHP notices about using
			undefined variables foul up our XML output (see call to
			doExport(...) further down) */
			$page = '';
			$history = '';
		} elseif ( $request->wasPosted() && $par == '' ) {
			// Log to see if certain parameters are actually used.
			// If not, we could deprecate them and do some cleanup, here and in WikiExporter.
			LoggerFactory::getInstance( 'export' )->debug(
				'Special:Export POST, dir: [{dir}], offset: [{offset}], limit: [{limit}]', [
				'dir' => $request->getRawVal( 'dir' ),
				'offset' => $request->getRawVal( 'offset' ),
				'limit' => $request->getRawVal( 'limit' ),
			] );

			$page = $request->getText( 'pages' );
			$this->curonly = $request->getCheck( 'curonly' );
			$rawOffset = $request->getVal( 'offset' );

			if ( $rawOffset ) {
				$offset = wfTimestamp( TS_MW, $rawOffset );
			} else {
				$offset = null;
			}

			$maxHistory = $config->get( 'ExportMaxHistory' );
			$limit = $request->getInt( 'limit' );
			$dir = $request->getVal( 'dir' );
			$history = [
				'dir' => 'asc',
				'offset' => false,
				'limit' => $maxHistory,
			];
			$historyCheck = $request->getCheck( 'history' );

			if ( $this->curonly ) {
				$history = WikiExporter::CURRENT;
			} elseif ( !$historyCheck ) {
				if ( $limit > 0 && ( $maxHistory == 0 || $limit < $maxHistory ) ) {
					$history['limit'] = $limit;
				}

				if ( $offset !== null ) {
					$history['offset'] = $offset;
				}

				if ( strtolower( $dir ) == 'desc' ) {
					$history['dir'] = 'desc';
				}
			}

			if ( $page != '' ) {
				$this->doExport = true;
			}
		} else {
			// Default to current-only for GET requests.
			$page = $request->getText( 'pages', $par );
			$historyCheck = $request->getCheck( 'history' );

			if ( $historyCheck ) {
				$history = WikiExporter::FULL;
			} else {
				$history = WikiExporter::CURRENT;
			}

			if ( $page != '' ) {
				$this->doExport = true;
			}
		}

		if ( !$config->get( 'ExportAllowHistory' ) ) {
			// Override
			$history = WikiExporter::CURRENT;
		}

		$list_authors = $request->getCheck( 'listauthors' );
		if ( !$this->curonly || !$config->get( 'ExportAllowListContributors' ) ) {
			$list_authors = false;
		}

		if ( $this->doExport ) {
			$this->getOutput()->disable();

			// Cancel output buffering and gzipping if set
			// This should provide safer streaming for pages with history
			wfResetOutputBuffers();
			$request->response()->header( 'Content-type: application/xml; charset=utf-8' );
			$request->response()->header( 'X-Robots-Tag: noindex,nofollow' );

			if ( $request->getCheck( 'wpDownload' ) ) {
				// Provide a sane filename suggestion
				$filename = urlencode( $config->get( 'Sitename' ) . '-' . wfTimestampNow() . '.xml' );
				$request->response()->header( "Content-disposition: attachment;filename={$filename}" );
			}

			$this->doExport( $page, $history, $list_authors, $exportall );

			return;
		}

		$out = $this->getOutput();
		$out->addWikiMsg( 'exporttext' );

		if ( $page == '' ) {
			$categoryName = $request->getText( 'catname' );
		} else {
			$categoryName = '';
		}

		$formDescriptor = [
			'catname' => [
				'type' => 'textwithbutton',
				'name' => 'catname',
				'horizontal-label' => true,
				'label-message' => 'export-addcattext',
				'default' => $categoryName,
				'size' => 40,
				'buttontype' => 'submit',
				'buttonname' => 'addcat',
				'buttondefault' => $this->msg( 'export-addcat' )->text(),
				'hide-if' => [ '===', 'exportall', '1' ],
			],
		];
		if ( $config->get( 'ExportFromNamespaces' ) ) {
			$formDescriptor += [
				'nsindex' => [
					'type' => 'namespaceselectwithbutton',
					'default' => $nsindex,
					'label-message' => 'export-addnstext',
					'horizontal-label' => true,
					'name' => 'nsindex',
					'id' => 'namespace',
					'cssclass' => 'namespaceselector',
					'buttontype' => 'submit',
					'buttonname' => 'addns',
					'buttondefault' => $this->msg( 'export-addns' )->text(),
					'hide-if' => [ '===', 'exportall', '1' ],
				],
			];
		}

		if ( $config->get( 'ExportAllowAll' ) ) {
			$formDescriptor += [
				'exportall' => [
					'type' => 'check',
					'label-message' => 'exportall',
					'name' => 'exportall',
					'id' => 'exportall',
					'default' => $request->wasPosted() ? $request->getCheck( 'exportall' ) : false,
				],
			];
		}

		$formDescriptor += [
			'textarea' => [
				'class' => HTMLTextAreaField::class,
				'name' => 'pages',
				'label-message' => 'export-manual',
				'nodata' => true,
				'rows' => 10,
				'default' => $page,
				'hide-if' => [ '===', 'exportall', '1' ],
			],
		];

		if ( $config->get( 'ExportAllowHistory' ) ) {
			$formDescriptor += [
				'curonly' => [
					'type' => 'check',
					'label-message' => 'exportcuronly',
					'name' => 'curonly',
					'id' => 'curonly',
					'default' => $request->wasPosted() ? $request->getCheck( 'curonly' ) : true,
				],
			];
		} else {
			$out->addWikiMsg( 'exportnohistory' );
		}

		$formDescriptor += [
			'templates' => [
				'type' => 'check',
				'label-message' => 'export-templates',
				'name' => 'templates',
				'id' => 'wpExportTemplates',
				'default' => $request->wasPosted() ? $request->getCheck( 'templates' ) : false,
			],
		];

		if ( $config->get( 'ExportMaxLinkDepth' ) || $this->userCanOverrideExportDepth() ) {
			$formDescriptor += [
				'pagelink-depth' => [
					'type' => 'text',
					'name' => 'pagelink-depth',
					'id' => 'pagelink-depth',
					'label-message' => 'export-pagelinks',
					'default' => '0',
					'size' => 20,
				],
			];
		}

		$formDescriptor += [
			'wpDownload' => [
				'type' => 'check',
				'name' => 'wpDownload',
				'id' => 'wpDownload',
				'default' => $request->wasPosted() ? $request->getCheck( 'wpDownload' ) : true,
				'label-message' => 'export-download',
			],
		];

		if ( $config->get( 'ExportAllowListContributors' ) ) {
			$formDescriptor += [
				'listauthors' => [
					'type' => 'check',
					'label-message' => 'exportlistauthors',
					'default' => $request->wasPosted() ? $request->getCheck( 'listauthors' ) : false,
					'name' => 'listauthors',
					'id' => 'listauthors',
				],
			];
		}

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() );
		$htmlForm->setSubmitTextMsg( 'export-submit' );
		$htmlForm->prepareForm()->displayForm( false );
		$this->addHelpLink( 'Help:Export' );
	}

	/**
	 * @return bool
	 */
	protected function userCanOverrideExportDepth() {
		return MediaWikiServices::getInstance()
			->getPermissionManager()
			->userHasRight( $this->getUser(), 'override-export-depth' );
	}

	/**
	 * Do the actual page exporting
	 *
	 * @param string $page User input on what page(s) to export
	 * @param int $history One of the WikiExporter history export constants
	 * @param bool $list_authors Whether to add distinct author list (when
	 *   not returning full history)
	 * @param bool $exportall Whether to export everything
	 */
	protected function doExport( $page, $history, $list_authors, $exportall ) {
		// If we are grabbing everything, enable full history and ignore the rest
		if ( $exportall ) {
			$history = WikiExporter::FULL;
		} else {
			$pageSet = []; // Inverted index of all pages to look up

			// Split up and normalize input
			foreach ( explode( "\n", $page ) as $pageName ) {
				$pageName = trim( $pageName );
				$title = Title::newFromText( $pageName );
				if ( $title && !$title->isExternal() && $title->getText() !== '' ) {
					// Only record each page once!
					$pageSet[$title->getPrefixedText()] = true;
				}
			}

			// Set of original pages to pass on to further manipulation...
			$inputPages = array_keys( $pageSet );

			// Look up any linked pages if asked...
			if ( $this->templates ) {
				$pageSet = $this->getTemplates( $inputPages, $pageSet );
			}
			$linkDepth = $this->pageLinkDepth;
			if ( $linkDepth ) {
				$pageSet = $this->getPageLinks( $inputPages, $pageSet, $linkDepth );
			}

			$pages = array_keys( $pageSet );

			// Normalize titles to the same format and remove dupes, see T19374
			foreach ( $pages as $k => $v ) {
				$pages[$k] = str_replace( ' ', '_', $v );
			}

			$pages = array_unique( $pages );
		}

		/* Ok, let's get to it... */
		$db = wfGetDB( DB_REPLICA );

		$exporter = new WikiExporter( $db, $history );
		$exporter->list_authors = $list_authors;
		$exporter->openStream();

		if ( $exportall ) {
			$exporter->allPages();
		} else {
			$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

			foreach ( $pages as $page ) {
				# T10824: Only export pages the user can read
				$title = Title::newFromText( $page );
				if ( $title === null ) {
					// @todo Perhaps output an <error> tag or something.
					continue;
				}

				if ( !$permissionManager->userCan( 'read', $this->getUser(), $title ) ) {
					// @todo Perhaps output an <error> tag or something.
					continue;
				}

				$exporter->pageByTitle( $title );
			}
		}

		$exporter->closeStream();
	}

	/**
	 * @param Title $title
	 * @return string[]
	 */
	protected function getPagesFromCategory( $title ) {
		$maxPages = $this->getConfig()->get( 'ExportPagelistLimit' );

		$name = $title->getDBkey();

		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select(
			[ 'page', 'categorylinks' ],
			[ 'page_namespace', 'page_title' ],
			[ 'cl_from=page_id', 'cl_to' => $name ],
			__METHOD__,
			[ 'LIMIT' => $maxPages ]
		);

		$pages = [];

		foreach ( $res as $row ) {
			$pages[] = Title::makeName( $row->page_namespace, $row->page_title );
		}

		return $pages;
	}

	/**
	 * @param int $nsindex
	 * @return string[]
	 */
	protected function getPagesFromNamespace( $nsindex ) {
		$maxPages = $this->getConfig()->get( 'ExportPagelistLimit' );

		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select(
			'page',
			[ 'page_namespace', 'page_title' ],
			[ 'page_namespace' => $nsindex ],
			__METHOD__,
			[ 'LIMIT' => $maxPages ]
		);

		$pages = [];

		foreach ( $res as $row ) {
			$pages[] = Title::makeName( $row->page_namespace, $row->page_title );
		}

		return $pages;
	}

	/**
	 * Expand a list of pages to include templates used in those pages.
	 * @param array $inputPages List of titles to look up
	 * @param array $pageSet Associative array indexed by titles for output
	 * @return array Associative array index by titles
	 */
	protected function getTemplates( $inputPages, $pageSet ) {
		return $this->getLinks( $inputPages, $pageSet,
			'templatelinks',
			[ 'namespace' => 'tl_namespace', 'title' => 'tl_title' ],
			[ 'page_id=tl_from' ]
		);
	}

	/**
	 * Validate link depth setting, if available.
	 * @param int $depth
	 * @return int
	 */
	protected function validateLinkDepth( $depth ) {
		if ( $depth < 0 ) {
			return 0;
		}

		if ( !$this->userCanOverrideExportDepth() ) {
			$maxLinkDepth = $this->getConfig()->get( 'ExportMaxLinkDepth' );
			if ( $depth > $maxLinkDepth ) {
				return $maxLinkDepth;
			}
		}

		/*
		 * There's a HARD CODED limit of 5 levels of recursion here to prevent a
		 * crazy-big export from being done by someone setting the depth
		 * number too high. In other words, last resort safety net.
		 */

		return intval( min( $depth, 5 ) );
	}

	/**
	 * Expand a list of pages to include pages linked to from that page.
	 * @param array $inputPages
	 * @param array $pageSet
	 * @param int $depth
	 * @return array
	 */
	protected function getPageLinks( $inputPages, $pageSet, $depth ) {
		for ( ; $depth > 0; --$depth ) {
			$pageSet = $this->getLinks(
				$inputPages, $pageSet, 'pagelinks',
				[ 'namespace' => 'pl_namespace', 'title' => 'pl_title' ],
				[ 'page_id=pl_from' ]
			);
			$inputPages = array_keys( $pageSet );
		}

		return $pageSet;
	}

	/**
	 * Expand a list of pages to include items used in those pages.
	 * @param array $inputPages Array of page titles
	 * @param array $pageSet
	 * @param string $table
	 * @param array $fields Array of field names
	 * @param array $join
	 * @return array
	 */
	protected function getLinks( $inputPages, $pageSet, $table, $fields, $join ) {
		$dbr = wfGetDB( DB_REPLICA );

		foreach ( $inputPages as $page ) {
			$title = Title::newFromText( $page );

			if ( $title ) {
				$pageSet[$title->getPrefixedText()] = true;
				/// @todo FIXME: May or may not be more efficient to batch these
				///        by namespace when given multiple input pages.
				$result = $dbr->select(
					[ 'page', $table ],
					$fields,
					array_merge(
						$join,
						[
							'page_namespace' => $title->getNamespace(),
							'page_title' => $title->getDBkey()
						]
					),
					__METHOD__
				);

				foreach ( $result as $row ) {
					$template = Title::makeTitle( $row->namespace, $row->title );
					$pageSet[$template->getPrefixedText()] = true;
				}
			}
		}

		return $pageSet;
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}
