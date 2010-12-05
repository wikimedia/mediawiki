<?php
/**
 * Special handling for category description pages.
 * Modelled after ImagePage.php.
 *
 * @file
 */

if ( !defined( 'MEDIAWIKI' ) )
	die( 1 );

/**
 * Special handling for category description pages, showing pages,
 * subcategories and file that belong to the category
 */
class CategoryPage extends Article {
	# Subclasses can change this to override the viewer class.
	protected $mCategoryViewerClass = 'CategoryViewer';

	function view() {
		global $wgRequest, $wgUser;

		$diff = $wgRequest->getVal( 'diff' );
		$diffOnly = $wgRequest->getBool( 'diffonly', $wgUser->getOption( 'diffonly' ) );

		if ( isset( $diff ) && $diffOnly ) {
			return parent::view();
		}

		if ( !wfRunHooks( 'CategoryPageView', array( &$this ) ) ) {
			return;
		}

		if ( NS_CATEGORY == $this->mTitle->getNamespace() ) {
			$this->openShowCategory();
		}

		parent::view();

		if ( NS_CATEGORY == $this->mTitle->getNamespace() ) {
			$this->closeShowCategory();
		}
	}

	/**
	 * Don't return a 404 for categories in use.
	 */
	function hasViewableContent() {
		if ( parent::hasViewableContent() ) {
			return true;
		} else {
			$cat = Category::newFromTitle( $this->mTitle );
			return $cat->getId() != 0;
		}
	}

	function openShowCategory() {
		# For overloading
	}

	function closeShowCategory() {
		global $wgOut;

		$from = $until = array();
		foreach ( array( 'page', 'subcat', 'file' ) as $type ) {
			# Use $_GET instead of $wgRequest, because the latter helpfully
			# normalizes Unicode, which removes nulls.  TODO: do something
			# smarter than passing nulls in URLs.  :/
			$from[$type] = isset( $_GET["{$type}from"] ) ? $_GET["{$type}from"] : null;
			$until[$type] = isset( $_GET["{$type}until"] ) ? $_GET["{$type}until"] : null;
		}

		$viewer = new $this->mCategoryViewerClass( $this->mTitle, $from, $until, $_GET );
		$wgOut->addHTML( $viewer->getHTML() );
	}
}

class CategoryViewer {
	var $title, $limit, $from, $until,
		$articles, $articles_start_char,
		$children, $children_start_char,
		$showGallery, $gallery,
		$skin;
	# Category object for this page
	private $cat;
	# The original query array, to be used in generating paging links.
	private $query;

	function __construct( $title, $from = '', $until = '', $query = array() ) {
		global $wgCategoryPagingLimit;
		$this->title = $title;
		$this->from = $from;
		$this->until = $until;
		$this->limit = $wgCategoryPagingLimit;
		$this->cat = Category::newFromTitle( $title );
		$this->query = $query;
		unset( $this->query['title'] );
	}

	/**
	 * Format the category data list.
	 *
	 * @return string HTML output
	 */
	function getHTML() {
		global $wgOut, $wgCategoryMagicGallery, $wgContLang;
		wfProfileIn( __METHOD__ );

		$this->showGallery = $wgCategoryMagicGallery && !$wgOut->mNoGallery;

		$this->clearCategoryState();
		$this->doCategoryQuery();
		$this->finaliseCategoryState();

		$r = $this->getSubcategorySection() .
			$this->getPagesSection() .
			$this->getImageSection();

		if ( $r == '' ) {
			// If there is no category content to display, only
			// show the top part of the navigation links.
			// FIXME: cannot be completely suppressed because it
			//        is unknown if 'until' or 'from' makes this
			//        give 0 results.
			$r = $r . $this->getCategoryTop();
		} else {
			$r = $this->getCategoryTop() .
				$r .
				$this->getCategoryBottom();
		}

		// Give a proper message if category is empty
		if ( $r == '' ) {
			$r = wfMsgExt( 'category-empty', array( 'parse' ) );
		}

		wfProfileOut( __METHOD__ );
		return $wgContLang->convert( $r );
	}

	function clearCategoryState() {
		$this->articles = array();
		$this->articles_start_char = array();
		$this->children = array();
		$this->children_start_char = array();
		if ( $this->showGallery ) {
			$this->gallery = new ImageGallery();
			$this->gallery->setHideBadImages();
		}
	}

	function getSkin() {
		if ( !$this->skin ) {
			global $wgUser;
			$this->skin = $wgUser->getSkin();
		}
		return $this->skin;
	}

	/**
	 * Add a subcategory to the internal lists, using a Category object
	 */
	function addSubcategoryObject( Category $cat, $sortkey, $pageLength ) {
		// Subcategory; strip the 'Category' namespace from the link text.
		$this->children[] = $this->getSkin()->link(
			$cat->getTitle(),
			null,
			array(),
			array(),
			array( 'known', 'noclasses' )
		);

		$this->children_start_char[] = 
			$this->getSubcategorySortChar( $cat->getTitle(), $sortkey );
	}

	/**
	 * Add a subcategory to the internal lists, using a title object
	 * @deprecated kept for compatibility, please use addSubcategoryObject instead
	 */
	function addSubcategory( Title $title, $sortkey, $pageLength ) {
		$this->addSubcategoryObject( Category::newFromTitle( $title ), $sortkey, $pageLength );
	}

	/**
	* Get the character to be used for sorting subcategories.
	* If there's a link from Category:A to Category:B, the sortkey of the resulting
	* entry in the categorylinks table is Category:A, not A, which it SHOULD be.
	* Workaround: If sortkey == "Category:".$title, than use $title for sorting,
	* else use sortkey...
	*/
	function getSubcategorySortChar( $title, $sortkey ) {
		global $wgContLang;

		if ( $title->getPrefixedText() == $sortkey ) {
			$word = $title->getDBkey();
		} else {
			$word = $sortkey;
		}

		$firstChar = $wgContLang->firstLetterForLists( $word );

		return $wgContLang->convert( $firstChar );
	}

	/**
	 * Add a page in the image namespace
	 */
	function addImage( Title $title, $sortkey, $pageLength, $isRedirect = false ) {
		if ( $this->showGallery ) {
			$flip = $this->flip['file'];
			if ( $flip ) {
				$this->gallery->insert( $title );
			} else {
				$this->gallery->add( $title );
			}
		} else {
			$this->addPage( $title, $sortkey, $pageLength, $isRedirect );
		}
	}

	/**
	 * Add a miscellaneous page
	 */
	function addPage( $title, $sortkey, $pageLength, $isRedirect = false ) {
		global $wgContLang;
		$this->articles[] = $isRedirect
			? '<span class="redirect-in-category">' .
				$this->getSkin()->link(
					$title,
					null,
					array(),
					array(),
					array( 'known', 'noclasses' )
				) . '</span>'
			: $this->getSkin()->link( $title );

		$this->articles_start_char[] = $wgContLang->convert( $wgContLang->firstLetterForLists( $sortkey ) );
	}

	function finaliseCategoryState() {
		if ( $this->flip['subcat'] ) {
			$this->children            = array_reverse( $this->children );
			$this->children_start_char = array_reverse( $this->children_start_char );
		}
		if ( $this->flip['page'] ) {
			$this->articles            = array_reverse( $this->articles );
			$this->articles_start_char = array_reverse( $this->articles_start_char );
		}
	}

	function doCategoryQuery() {
		global $wgContLang;

		$dbr = wfGetDB( DB_SLAVE, 'category' );

		$this->nextPage = array(
			'page' => null,
			'subcat' => null,
			'file' => null,
		);
		$this->flip = array( 'page' => false, 'subcat' => false, 'file' => false );

		foreach ( array( 'page', 'subcat', 'file' ) as $type ) {
			# Get the sortkeys for start/end, if applicable.  Note that if
			# the collation in the database differs from the one
			# $wgContLang is using, pagination might go totally haywire.
			$extraConds = array( 'cl_type' => $type );
			if ( $this->from[$type] !== null ) {
				$extraConds[] = 'cl_sortkey >= '
					. $dbr->addQuotes( $wgContLang->convertToSortkey( $this->from[$type] ) );
			} elseif ( $this->until[$type] !== null ) {
				$extraConds[] = 'cl_sortkey < '
					. $dbr->addQuotes( $wgContLang->convertToSortkey( $this->until[$type] ) );
				$this->flip[$type] = true;
			}

			$res = $dbr->select(
				array( 'page', 'categorylinks', 'category' ),
				array( 'page_id', 'page_title', 'page_namespace', 'page_len',
					'page_is_redirect', 'cl_sortkey', 'cat_id', 'cat_title',
					'cat_subcats', 'cat_pages', 'cat_files', 'cl_sortkey_prefix' ),
				array( 'cl_to' => $this->title->getDBkey() ) + $extraConds,
				__METHOD__,
				array(
					'USE INDEX' => array( 'categorylinks' => 'cl_sortkey' ),
					'LIMIT' => $this->limit + 1,
					'ORDER BY' => $this->flip[$type] ? 'cl_sortkey DESC' : 'cl_sortkey',
				),
				array(
					'categorylinks'  => array( 'INNER JOIN', 'cl_from = page_id' ),
					'category' => array( 'LEFT JOIN', 'cat_title = page_title AND page_namespace = ' . NS_CATEGORY )
				)
			);

			$count = 0;
			foreach ( $res as $row ) {
				$title = Title::newFromRow( $row );
				$rawSortkey = $title->getCategorySortkey( $row->cl_sortkey_prefix );

				if ( ++$count > $this->limit ) {
					# We've reached the one extra which shows that there
					# are additional pages to be had. Stop here...
					$this->nextPage[$type] = $rawSortkey;
					break;
				}

				if ( $title->getNamespace() == NS_CATEGORY ) {
					$cat = Category::newFromRow( $row, $title );
					$this->addSubcategoryObject( $cat, $rawSortkey, $row->page_len );
				} elseif ( $this->showGallery && $title->getNamespace() == NS_FILE ) {
					$this->addImage( $title, $rawSortkey, $row->page_len, $row->page_is_redirect );
				} else {
					$this->addPage( $title, $rawSortkey, $row->page_len, $row->page_is_redirect );
				}
			}
		}
	}

	function getCategoryTop() {
		$r = $this->getCategoryBottom();
		return $r === ''
			? $r
			: "<br style=\"clear:both;\"/>\n" . $r;
	}

	function getSubcategorySection() {
		# Don't show subcategories section if there are none.
		$r = '';
		$rescnt = count( $this->children );
		$dbcnt = $this->cat->getSubcatCount();
		$countmsg = $this->getCountMessage( $rescnt, $dbcnt, 'subcat' );

		if ( $rescnt > 0 ) {
			# Showing subcategories
			$r .= "<div id=\"mw-subcategories\">\n";
			$r .= '<h2>' . wfMsg( 'subcategories' ) . "</h2>\n";
			$r .= $countmsg;
			$r .= $this->getSectionPagingLinks( 'subcat' );
			$r .= $this->formatList( $this->children, $this->children_start_char );
			$r .= $this->getSectionPagingLinks( 'subcat' );
			$r .= "\n</div>";
		}
		return $r;
	}

	function getPagesSection() {
		$ti = htmlspecialchars( $this->title->getText() );
		# Don't show articles section if there are none.
		$r = '';

		# FIXME, here and in the other two sections: we don't need to bother
		# with this rigamarole if the entire category contents fit on one page
		# and have already been retrieved.  We can just use $rescnt in that
		# case and save a query and some logic.
		$dbcnt = $this->cat->getPageCount() - $this->cat->getSubcatCount()
			- $this->cat->getFileCount();
		$rescnt = count( $this->articles );
		$countmsg = $this->getCountMessage( $rescnt, $dbcnt, 'article' );

		if ( $rescnt > 0 ) {
			$r = "<div id=\"mw-pages\">\n";
			$r .= '<h2>' . wfMsg( 'category_header', $ti ) . "</h2>\n";
			$r .= $countmsg;
			$r .= $this->getSectionPagingLinks( 'page' );
			$r .= $this->formatList( $this->articles, $this->articles_start_char );
			$r .= $this->getSectionPagingLinks( 'page' );
			$r .= "\n</div>";
		}
		return $r;
	}

	function getImageSection() {
		$r = '';
		if ( $this->showGallery && ! $this->gallery->isEmpty() ) {
			$dbcnt = $this->cat->getFileCount();
			$rescnt = $this->gallery->count();
			$countmsg = $this->getCountMessage( $rescnt, $dbcnt, 'file' );

			$r .= "<div id=\"mw-category-media\">\n";
			$r .= '<h2>' . wfMsg( 'category-media-header', htmlspecialchars( $this->title->getText() ) ) . "</h2>\n";
			$r .= $countmsg;
			$r .= $this->getSectionPagingLinks( 'file' );
			$r .= $this->gallery->toHTML();
			$r .= $this->getSectionPagingLinks( 'file' );
			$r .= "\n</div>";
		}
		return $r;
	}

	/**
	 * Get the paging links for a section (subcats/pages/files), to go at the top and bottom
	 * of the output.
	 *
	 * @param $type String: 'page', 'subcat', or 'file'
	 * @return String: HTML output, possibly empty if there are no other pages
	 */
	private function getSectionPagingLinks( $type ) {
		if ( $this->until[$type] !== null ) {
			return $this->pagingLinks( $this->nextPage[$type], $this->until[$type], $type );
		} elseif ( $this->nextPage[$type] !== null || $this->from[$type] !== null ) {
			return $this->pagingLinks( $this->from[$type], $this->nextPage[$type], $type );
		} else {
			return '';
		}
	}

	function getCategoryBottom() {
		return '';
	}

	/**
	 * Format a list of articles chunked by letter, either as a
	 * bullet list or a columnar format, depending on the length.
	 *
	 * @param $articles Array
	 * @param $articles_start_char Array
	 * @param $cutoff Int
	 * @return String
	 */
	function formatList( $articles, $articles_start_char, $cutoff = 6 ) {
		if ( count ( $articles ) > $cutoff ) {
			return self::columnList( $articles, $articles_start_char );
		} elseif ( count( $articles ) > 0 ) {
			// for short lists of articles in categories.
			return self::shortList( $articles, $articles_start_char );
		}
		return '';
	}

	/**
	 * Format a list of articles chunked by letter in a three-column
	 * list, ordered vertically.
	 *
	 * TODO: Take the headers into account when creating columns, so they're
	 * more visually equal.
	 *
	 * More distant TODO: Scrap this and use CSS columns, whenever IE finally
	 * supports those.
	 *
	 * @param $articles Array
	 * @param $articles_start_char Array
	 * @return String
	 */
	static function columnList( $articles, $articles_start_char ) {
		$columns = array_combine( $articles, $articles_start_char );
		# Split into three columns
		$columns = array_chunk( $columns, ceil( count( $columns ) / 3 ), true /* preserve keys */ );

		$ret = '<table width="100%"><tr valign="top"><td>';
		$prevchar = null;

		foreach ( $columns as $column ) {
			$colContents = array();

			# Kind of like array_flip() here, but we keep duplicates in an
			# array instead of dropping them.
			foreach ( $column as $article => $char ) {
				if ( !isset( $colContents[$char] ) ) {
					$colContents[$char] = array();
				}
				$colContents[$char][] = $article;
			}

			$first = true;
			foreach ( $colContents as $char => $articles ) {
				$ret .= '<h3>' . htmlspecialchars( $char );
				if ( $first && $char === $prevchar ) {
					# We're continuing a previous chunk at the top of a new
					# column, so add " cont." after the letter.
					$ret .= ' ' . wfMsgHtml( 'listingcontinuesabbrev' );
				}
				$ret .= "</h3>\n";

				$ret .= '<ul><li>';
				$ret .= implode( "</li>\n<li>", $articles );
				$ret .= '</li></ul>';

				$first = false;
				$prevchar = $char;
			}

			$ret .= "</td>\n<td>";
		}

		$ret .= '</td></tr></table>';
		return $ret;
	}

	/**
	 * Format a list of articles chunked by letter in a bullet list.
	 * @param $articles Array
	 * @param $articles_start_char Array
	 * @return String
	 */
	static function shortList( $articles, $articles_start_char ) {
		$r = '<h3>' . htmlspecialchars( $articles_start_char[0] ) . "</h3>\n";
		$r .= '<ul><li>' . $articles[0] . '</li>';
		for ( $index = 1; $index < count( $articles ); $index++ )
		{
			if ( $articles_start_char[$index] != $articles_start_char[$index - 1] )
			{
				$r .= "</ul><h3>" . htmlspecialchars( $articles_start_char[$index] ) . "</h3>\n<ul>";
			}

			$r .= "<li>{$articles[$index]}</li>";
		}
		$r .= '</ul>';
		return $r;
	}

	/**
	 * Create paging links, as a helper method to getSectionPagingLinks().
	 *
	 * @param $first String The 'until' parameter for the generated URL
	 * @param $last String The 'from' parameter for the genererated URL
	 * @param $type String A prefix for parameters, 'page' or 'subcat' or
	 *     'file'
	 * @return String HTML
	 */
	private function pagingLinks( $first, $last, $type = '' ) {
		global $wgLang;
		$sk = $this->getSkin();
		$limitText = $wgLang->formatNum( $this->limit );

		$prevLink = wfMsgExt( 'prevn', array( 'escape', 'parsemag' ), $limitText );

		if ( $first != '' ) {
			$prevQuery = $this->query;
			$prevQuery["{$type}until"] = $first;
			unset( $prevQuery["{$type}from"] );
			$prevLink = $sk->linkKnown(
				$this->title,
				$prevLink,
				array(),
				$prevQuery
			);
		}

		$nextLink = wfMsgExt( 'nextn', array( 'escape', 'parsemag' ), $limitText );

		if ( $last != '' ) {
			$lastQuery = $this->query;
			$lastQuery["{$type}from"] = $last;
			unset( $lastQuery["{$type}until"] );
			$nextLink = $sk->linkKnown(
				$this->title,
				$nextLink,
				array(),
				$lastQuery
			);
		}

		return "($prevLink) ($nextLink)";
	}

	/**
	 * What to do if the category table conflicts with the number of results
	 * returned?  This function says what.  It works the same whether the
	 * things being counted are articles, subcategories, or files.
	 *
	 * Note for grepping: uses the messages category-article-count,
	 * category-article-count-limited, category-subcat-count,
	 * category-subcat-count-limited, category-file-count,
	 * category-file-count-limited.
	 *
	 * @param $rescnt Int: The number of items returned by our database query.
	 * @param $dbcnt Int: The number of items according to the category table.
	 * @param $type String: 'subcat', 'article', or 'file'
	 * @return String: A message giving the number of items, to output to HTML.
	 */
	private function getCountMessage( $rescnt, $dbcnt, $type ) {
		global $wgLang;
		# There are three cases:
		#   1) The category table figure seems sane.  It might be wrong, but
		#      we can't do anything about it if we don't recalculate it on ev-
		#      ery category view.
		#   2) The category table figure isn't sane, like it's smaller than the
		#      number of actual results, *but* the number of results is less
		#      than $this->limit and there's no offset.  In this case we still
		#      know the right figure.
		#   3) We have no idea.
		$totalrescnt = count( $this->articles ) + count( $this->children ) +
			( $this->showGallery ? $this->gallery->count() : 0 );

		# Check if there's a "from" or "until" for anything
		$fromOrUntil = false;
		foreach ( array( 'page', 'subcat', 'file' ) as $t ) {
			if ( $this->from[$t] !== null || $this->until[$t] !== null ) {
				$fromOrUntil = true;
				break;
			}
		}

		if ( $dbcnt == $rescnt || ( ( $totalrescnt == $this->limit || $fromOrUntil )
			&& $dbcnt > $rescnt ) )
		{
			# Case 1: seems sane.
			$totalcnt = $dbcnt;
		} elseif ( $totalrescnt < $this->limit && !$fromOrUntil ) {
			# Case 2: not sane, but salvageable.  Use the number of results.
			# Since there are fewer than 200, we can also take this opportunity
			# to refresh the incorrect category table entry -- which should be
			# quick due to the small number of entries.
			$totalcnt = $rescnt;
			$this->cat->refreshCounts();
		} else {
			# Case 3: hopeless.  Don't give a total count at all.
			return wfMsgExt( "category-$type-count-limited", 'parse',
				$wgLang->formatNum( $rescnt ) );
		}
		return wfMsgExt(
			"category-$type-count",
			'parse',
			$wgLang->formatNum( $rescnt ),
			$wgLang->formatNum( $totalcnt )
		);
	}
}
