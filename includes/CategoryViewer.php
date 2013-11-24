<?php
/**
 * List and paging of category members.
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
 */

class CategoryViewer extends ContextSource {
	var $limit, $from, $until,
		$articles, $articles_start_char,
		$children, $children_start_char,
		$showGallery, $imgsNoGalley,
		$imgsNoGallery_start_char,
		$imgsNoGallery;

	/**
	 * @var Array
	 */
	var $nextPage;

	/**
	 * @var Array
	 */
	var $flip;

	/**
	 * @var Title
	 */
	var $title;

	/**
	 * @var Collation
	 */
	var $collation;

	/**
	 * @var ImageGallery
	 */
	var $gallery;

	/**
	 * Category object for this page
	 * @var Category
	 */
	private $cat;

	/**
	 * The original query array, to be used in generating paging links.
	 * @var array
	 */
	private $query;

	/**
	 * Constructor
	 *
	 * @since 1.19 $context is a second, required parameter
	 * @param $title Title
	 * @param $context IContextSource
	 * @param array $from An array with keys page, subcat,
	 *        and file for offset of results of each section (since 1.17)
	 * @param array $until An array with 3 keys for until of each section (since 1.17)
	 * @param $query Array
	 */
	function __construct( $title, IContextSource $context, $from = array(), $until = array(), $query = array() ) {
		global $wgCategoryPagingLimit;
		$this->title = $title;
		$this->setContext( $context );
		$this->from = $from;
		$this->until = $until;
		$this->limit = $wgCategoryPagingLimit;
		$this->cat = Category::newFromTitle( $title );
		$this->query = $query;
		$this->collation = Collation::singleton();
		unset( $this->query['title'] );
	}

	/**
	 * Format the category data list.
	 *
	 * @return string HTML output
	 */
	public function getHTML() {
		global $wgCategoryMagicGallery;
		wfProfileIn( __METHOD__ );

		$this->showGallery = $wgCategoryMagicGallery && !$this->getOutput()->mNoGallery;

		$this->clearCategoryState();
		$this->doCategoryQuery();
		$this->finaliseCategoryState();

		$r = $this->getSubcategorySection() .
			$this->getPagesSection() .
			$this->getImageSection();

		if ( $r == '' ) {
			// If there is no category content to display, only
			// show the top part of the navigation links.
			// @todo FIXME: Cannot be completely suppressed because it
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
			$r = $this->msg( 'category-empty' )->parseAsBlock();
		}

		$lang = $this->getLanguage();
		$langAttribs = array( 'lang' => $lang->getCode(), 'dir' => $lang->getDir() );
		# put a div around the headings which are in the user language
		$r = Html::openElement( 'div', $langAttribs ) . $r . '</div>';

		wfProfileOut( __METHOD__ );
		return $r;
	}

	function clearCategoryState() {
		$this->articles = array();
		$this->articles_start_char = array();
		$this->children = array();
		$this->children_start_char = array();
		if ( $this->showGallery ) {
			// Note that null for mode is taken to mean use default.
			$mode = $this->getRequest()->getVal( 'gallerymode', null );
			try {
				$this->gallery = ImageGalleryBase::factory( $mode );
			} catch ( MWException $e ) {
				// User specified something invalid, fallback to default.
				$this->gallery = ImageGalleryBase::factory();
			}

			$this->gallery->setHideBadImages();
			$this->gallery->setContext( $this->getContext() );
		} else {
			$this->imgsNoGallery = array();
			$this->imgsNoGallery_start_char = array();
		}
	}

	/**
	 * Add a subcategory to the internal lists, using a Category object
	 * @param $cat Category
	 * @param $sortkey
	 * @param $pageLength
	 */
	function addSubcategoryObject( Category $cat, $sortkey, $pageLength ) {
		// Subcategory; strip the 'Category' namespace from the link text.
		$title = $cat->getTitle();

		$link = Linker::link( $title, htmlspecialchars( $title->getText() ) );
		if ( $title->isRedirect() ) {
			// This didn't used to add redirect-in-category, but might
			// as well be consistent with the rest of the sections
			// on a category page.
			$link = '<span class="redirect-in-category">' . $link . '</span>';
		}
		$this->children[] = $link;

		$this->children_start_char[] =
			$this->getSubcategorySortChar( $cat->getTitle(), $sortkey );
	}

	/**
	 * Add a subcategory to the internal lists, using a title object
	 * @deprecated since 1.17 kept for compatibility, use addSubcategoryObject instead
	 */
	function addSubcategory( Title $title, $sortkey, $pageLength ) {
		wfDeprecated( __METHOD__, '1.17' );
		$this->addSubcategoryObject( Category::newFromTitle( $title ), $sortkey, $pageLength );
	}

	/**
	 * Get the character to be used for sorting subcategories.
	 * If there's a link from Category:A to Category:B, the sortkey of the resulting
	 * entry in the categorylinks table is Category:A, not A, which it SHOULD be.
	 * Workaround: If sortkey == "Category:".$title, than use $title for sorting,
	 * else use sortkey...
	 *
	 * @param Title $title
	 * @param string $sortkey The human-readable sortkey (before transforming to icu or whatever).
	 * @return string
	 */
	function getSubcategorySortChar( $title, $sortkey ) {
		global $wgContLang;

		if ( $title->getPrefixedText() == $sortkey ) {
			$word = $title->getDBkey();
		} else {
			$word = $sortkey;
		}

		$firstChar = $this->collation->getFirstLetter( $word );

		return $wgContLang->convert( $firstChar );
	}

	/**
	 * Add a page in the image namespace
	 * @param $title Title
	 * @param $sortkey
	 * @param $pageLength
	 * @param $isRedirect bool
	 */
	function addImage( Title $title, $sortkey, $pageLength, $isRedirect = false ) {
		global $wgContLang;
		if ( $this->showGallery ) {
			$flip = $this->flip['file'];
			if ( $flip ) {
				$this->gallery->insert( $title );
			} else {
				$this->gallery->add( $title );
			}
		} else {
			$link = Linker::link( $title );
			if ( $isRedirect ) {
				// This seems kind of pointless given 'mw-redirect' class,
				// but keeping for back-compatibility with user css.
				$link = '<span class="redirect-in-category">' . $link . '</span>';
			}
			$this->imgsNoGallery[] = $link;

			$this->imgsNoGallery_start_char[] = $wgContLang->convert(
				$this->collation->getFirstLetter( $sortkey ) );
		}
	}

	/**
	 * Add a miscellaneous page
	 * @param $title
	 * @param $sortkey
	 * @param $pageLength
	 * @param $isRedirect bool
	 */
	function addPage( $title, $sortkey, $pageLength, $isRedirect = false ) {
		global $wgContLang;

		$link = Linker::link( $title );
		if ( $isRedirect ) {
			// This seems kind of pointless given 'mw-redirect' class,
			// but keeping for back-compatibility with user css.
			$link = '<span class="redirect-in-category">' . $link . '</span>';
		}
		$this->articles[] = $link;

		$this->articles_start_char[] = $wgContLang->convert(
			$this->collation->getFirstLetter( $sortkey ) );
	}

	function finaliseCategoryState() {
		if ( $this->flip['subcat'] ) {
			$this->children = array_reverse( $this->children );
			$this->children_start_char = array_reverse( $this->children_start_char );
		}
		if ( $this->flip['page'] ) {
			$this->articles = array_reverse( $this->articles );
			$this->articles_start_char = array_reverse( $this->articles_start_char );
		}
		if ( !$this->showGallery && $this->flip['file'] ) {
			$this->imgsNoGallery = array_reverse( $this->imgsNoGallery );
			$this->imgsNoGallery_start_char = array_reverse( $this->imgsNoGallery_start_char );
		}
	}

	function doCategoryQuery() {
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
			# set in $wgCategoryCollation, pagination might go totally haywire.
			$extraConds = array( 'cl_type' => $type );
			if ( isset( $this->from[$type] ) && $this->from[$type] !== null ) {
				$extraConds[] = 'cl_sortkey >= '
					. $dbr->addQuotes( $this->collation->getSortKey( $this->from[$type] ) );
			} elseif ( isset( $this->until[$type] ) && $this->until[$type] !== null ) {
				$extraConds[] = 'cl_sortkey < '
					. $dbr->addQuotes( $this->collation->getSortKey( $this->until[$type] ) );
				$this->flip[$type] = true;
			}

			$res = $dbr->select(
				array( 'page', 'categorylinks', 'category' ),
				array( 'page_id', 'page_title', 'page_namespace', 'page_len',
					'page_is_redirect', 'cl_sortkey', 'cat_id', 'cat_title',
					'cat_subcats', 'cat_pages', 'cat_files',
					'cl_sortkey_prefix', 'cl_collation' ),
				array_merge( array( 'cl_to' => $this->title->getDBkey() ), $extraConds ),
				__METHOD__,
				array(
					'USE INDEX' => array( 'categorylinks' => 'cl_sortkey' ),
					'LIMIT' => $this->limit + 1,
					'ORDER BY' => $this->flip[$type] ? 'cl_sortkey DESC' : 'cl_sortkey',
				),
				array(
					'categorylinks' => array( 'INNER JOIN', 'cl_from = page_id' ),
					'category' => array( 'LEFT JOIN', array(
						'cat_title = page_title',
						'page_namespace' => NS_CATEGORY
					))
				)
			);

			$count = 0;
			foreach ( $res as $row ) {
				$title = Title::newFromRow( $row );
				if ( $row->cl_collation === '' ) {
					// Hack to make sure that while updating from 1.16 schema
					// and db is inconsistent, that the sky doesn't fall.
					// See r83544. Could perhaps be removed in a couple decades...
					$humanSortkey = $row->cl_sortkey;
				} else {
					$humanSortkey = $title->getCategorySortkey( $row->cl_sortkey_prefix );
				}

				if ( ++$count > $this->limit ) {
					# We've reached the one extra which shows that there
					# are additional pages to be had. Stop here...
					$this->nextPage[$type] = $humanSortkey;
					break;
				}

				if ( $title->getNamespace() == NS_CATEGORY ) {
					$cat = Category::newFromRow( $row, $title );
					$this->addSubcategoryObject( $cat, $humanSortkey, $row->page_len );
				} elseif ( $title->getNamespace() == NS_FILE ) {
					$this->addImage( $title, $humanSortkey, $row->page_len, $row->page_is_redirect );
				} else {
					$this->addPage( $title, $humanSortkey, $row->page_len, $row->page_is_redirect );
				}
			}
		}
	}

	/**
	 * @return string
	 */
	function getCategoryTop() {
		$r = $this->getCategoryBottom();
		return $r === ''
			? $r
			: "<br style=\"clear:both;\"/>\n" . $r;
	}

	/**
	 * @return string
	 */
	function getSubcategorySection() {
		# Don't show subcategories section if there are none.
		$r = '';
		$rescnt = count( $this->children );
		$dbcnt = $this->cat->getSubcatCount();
		$countmsg = $this->getCountMessage( $rescnt, $dbcnt, 'subcat' );

		if ( $rescnt > 0 ) {
			# Showing subcategories
			$r .= "<div id=\"mw-subcategories\">\n";
			$r .= '<h2>' . $this->msg( 'subcategories' )->text() . "</h2>\n";
			$r .= $countmsg;
			$r .= $this->getSectionPagingLinks( 'subcat' );
			$r .= $this->formatList( $this->children, $this->children_start_char );
			$r .= $this->getSectionPagingLinks( 'subcat' );
			$r .= "\n</div>";
		}
		return $r;
	}

	/**
	 * @return string
	 */
	function getPagesSection() {
		$ti = wfEscapeWikiText( $this->title->getText() );
		# Don't show articles section if there are none.
		$r = '';

		# @todo FIXME: Here and in the other two sections: we don't need to bother
		# with this rigmarole if the entire category contents fit on one page
		# and have already been retrieved.  We can just use $rescnt in that
		# case and save a query and some logic.
		$dbcnt = $this->cat->getPageCount() - $this->cat->getSubcatCount()
			- $this->cat->getFileCount();
		$rescnt = count( $this->articles );
		$countmsg = $this->getCountMessage( $rescnt, $dbcnt, 'article' );

		if ( $rescnt > 0 ) {
			$r = "<div id=\"mw-pages\">\n";
			$r .= '<h2>' . $this->msg( 'category_header', $ti )->text() . "</h2>\n";
			$r .= $countmsg;
			$r .= $this->getSectionPagingLinks( 'page' );
			$r .= $this->formatList( $this->articles, $this->articles_start_char );
			$r .= $this->getSectionPagingLinks( 'page' );
			$r .= "\n</div>";
		}
		return $r;
	}

	/**
	 * @return string
	 */
	function getImageSection() {
		$r = '';
		$rescnt = $this->showGallery ? $this->gallery->count() : count( $this->imgsNoGallery );
		if ( $rescnt > 0 ) {
			$dbcnt = $this->cat->getFileCount();
			$countmsg = $this->getCountMessage( $rescnt, $dbcnt, 'file' );

			$r .= "<div id=\"mw-category-media\">\n";
			$r .= '<h2>' . $this->msg( 'category-media-header', wfEscapeWikiText( $this->title->getText() ) )->text() . "</h2>\n";
			$r .= $countmsg;
			$r .= $this->getSectionPagingLinks( 'file' );
			if ( $this->showGallery ) {
				$r .= $this->gallery->toHTML();
			} else {
				$r .= $this->formatList( $this->imgsNoGallery, $this->imgsNoGallery_start_char );
			}
			$r .= $this->getSectionPagingLinks( 'file' );
			$r .= "\n</div>";
		}
		return $r;
	}

	/**
	 * Get the paging links for a section (subcats/pages/files), to go at the top and bottom
	 * of the output.
	 *
	 * @param string $type 'page', 'subcat', or 'file'
	 * @return String: HTML output, possibly empty if there are no other pages
	 */
	private function getSectionPagingLinks( $type ) {
		if ( isset( $this->until[$type] ) && $this->until[$type] !== null ) {
			return $this->pagingLinks( $this->nextPage[$type], $this->until[$type], $type );
		} elseif ( $this->nextPage[$type] !== null || ( isset( $this->from[$type] ) && $this->from[$type] !== null ) ) {
			return $this->pagingLinks( $this->from[$type], $this->nextPage[$type], $type );
		} else {
			return '';
		}
	}

	/**
	 * @return string
	 */
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
	 * @private
	 */
	function formatList( $articles, $articles_start_char, $cutoff = 6 ) {
		$list = '';
		if ( count( $articles ) > $cutoff ) {
			$list = self::columnList( $articles, $articles_start_char );
		} elseif ( count( $articles ) > 0 ) {
			// for short lists of articles in categories.
			$list = self::shortList( $articles, $articles_start_char );
		}

		$pageLang = $this->title->getPageLanguage();
		$attribs = array( 'lang' => $pageLang->getCode(), 'dir' => $pageLang->getDir(),
			'class' => 'mw-content-' . $pageLang->getDir() );
		$list = Html::rawElement( 'div', $attribs, $list );

		return $list;
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
	 * @private
	 */
	static function columnList( $articles, $articles_start_char ) {
		$columns = array_combine( $articles, $articles_start_char );
		# Split into three columns
		$columns = array_chunk( $columns, ceil( count( $columns ) / 3 ), true /* preserve keys */ );

		$ret = '<table style="width: 100%;"><tr style="vertical-align: top;">';
		$prevchar = null;

		foreach ( $columns as $column ) {
			$ret .= '<td style="width: 33.3%;">';
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
				# Change space to non-breaking space to keep headers aligned
				$h3char = $char === ' ' ? '&#160;' : htmlspecialchars( $char );

				$ret .= '<h3>' . $h3char;
				if ( $first && $char === $prevchar ) {
					# We're continuing a previous chunk at the top of a new
					# column, so add " cont." after the letter.
					$ret .= ' ' . wfMessage( 'listingcontinuesabbrev' )->escaped();
				}
				$ret .= "</h3>\n";

				$ret .= '<ul><li>';
				$ret .= implode( "</li>\n<li>", $articles );
				$ret .= '</li></ul>';

				$first = false;
				$prevchar = $char;
			}

			$ret .= "</td>\n";
		}

		$ret .= '</tr></table>';
		return $ret;
	}

	/**
	 * Format a list of articles chunked by letter in a bullet list.
	 * @param $articles Array
	 * @param $articles_start_char Array
	 * @return String
	 * @private
	 */
	static function shortList( $articles, $articles_start_char ) {
		$r = '<h3>' . htmlspecialchars( $articles_start_char[0] ) . "</h3>\n";
		$r .= '<ul><li>' . $articles[0] . '</li>';
		for ( $index = 1; $index < count( $articles ); $index++ ) {
			if ( $articles_start_char[$index] != $articles_start_char[$index - 1] ) {
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
	 * @param string $first The 'until' parameter for the generated URL
	 * @param string $last The 'from' parameter for the generated URL
	 * @param string $type A prefix for parameters, 'page' or 'subcat' or
	 *     'file'
	 * @return String HTML
	 */
	private function pagingLinks( $first, $last, $type = '' ) {
		$prevLink = $this->msg( 'prevn' )->numParams( $this->limit )->escaped();

		if ( $first != '' ) {
			$prevQuery = $this->query;
			$prevQuery["{$type}until"] = $first;
			unset( $prevQuery["{$type}from"] );
			$prevLink = Linker::linkKnown(
				$this->addFragmentToTitle( $this->title, $type ),
				$prevLink,
				array(),
				$prevQuery
			);
		}

		$nextLink = $this->msg( 'nextn' )->numParams( $this->limit )->escaped();

		if ( $last != '' ) {
			$lastQuery = $this->query;
			$lastQuery["{$type}from"] = $last;
			unset( $lastQuery["{$type}until"] );
			$nextLink = Linker::linkKnown(
				$this->addFragmentToTitle( $this->title, $type ),
				$nextLink,
				array(),
				$lastQuery
			);
		}

		return $this->msg( 'categoryviewer-pagedlinks' )->rawParams( $prevLink, $nextLink )->escaped();
	}

	/**
	 * Takes a title, and adds the fragment identifier that
	 * corresponds to the correct segment of the category.
	 *
	 * @param Title $title: The title (usually $this->title)
	 * @param string $section: Which section
	 * @throws MWException
	 * @return Title
	 */
	private function addFragmentToTitle( $title, $section ) {
		switch ( $section ) {
			case 'page':
				$fragment = 'mw-pages';
				break;
			case 'subcat':
				$fragment = 'mw-subcategories';
				break;
			case 'file':
				$fragment = 'mw-category-media';
				break;
			default:
				throw new MWException( __METHOD__ .
					" Invalid section $section." );
		}

		return Title::makeTitle( $title->getNamespace(),
			$title->getDBkey(), $fragment );
	}

	/**
	 * What to do if the category table conflicts with the number of results
	 * returned?  This function says what. Each type is considered independently
	 * of the other types.
	 *
	 * @param int $rescnt The number of items returned by our database query.
	 * @param int $dbcnt The number of items according to the category table.
	 * @param string $type 'subcat', 'article', or 'file'
	 * @return string: A message giving the number of items, to output to HTML.
	 */
	private function getCountMessage( $rescnt, $dbcnt, $type ) {
		// There are three cases:
		//   1) The category table figure seems sane.  It might be wrong, but
		//      we can't do anything about it if we don't recalculate it on ev-
		//      ery category view.
		//   2) The category table figure isn't sane, like it's smaller than the
		//      number of actual results, *but* the number of results is less
		//      than $this->limit and there's no offset.  In this case we still
		//      know the right figure.
		//   3) We have no idea.

		// Check if there's a "from" or "until" for anything

		// This is a little ugly, but we seem to use different names
		// for the paging types then for the messages.
		if ( $type === 'article' ) {
			$pagingType = 'page';
		} else {
			$pagingType = $type;
		}

		$fromOrUntil = false;
		if ( ( isset( $this->from[$pagingType] ) && $this->from[$pagingType] !== null ) ||
			( isset( $this->until[$pagingType] ) && $this->until[$pagingType] !== null )
		) {
			$fromOrUntil = true;
		}

		if ( $dbcnt == $rescnt ||
			( ( $rescnt == $this->limit || $fromOrUntil ) && $dbcnt > $rescnt )
		) {
			// Case 1: seems sane.
			$totalcnt = $dbcnt;
		} elseif ( $rescnt < $this->limit && !$fromOrUntil ) {
			// Case 2: not sane, but salvageable.  Use the number of results.
			// Since there are fewer than 200, we can also take this opportunity
			// to refresh the incorrect category table entry -- which should be
			// quick due to the small number of entries.
			$totalcnt = $rescnt;
			$this->cat->refreshCounts();
		} else {
			// Case 3: hopeless.  Don't give a total count at all.
			// Messages: category-subcat-count-limited, category-article-count-limited,
			// category-file-count-limited
			return $this->msg( "category-$type-count-limited" )->numParams( $rescnt )->parseAsBlock();
		}
		// Messages: category-subcat-count, category-article-count, category-file-count
		return $this->msg( "category-$type-count" )->numParams( $rescnt, $totalcnt )->parseAsBlock();
	}
}
