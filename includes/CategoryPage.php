<?php
/**
 * Special handling for category description pages
 * Modelled after ImagePage.php
 *
 * @package MediaWiki
 */

if( !defined( 'MEDIAWIKI' ) )
	die( 1 );

/**
 * @package MediaWiki
 */
class CategoryPage extends Article {
	function view() {
		if(!wfRunHooks('CategoryPageView', array(&$this))) return;

		if ( NS_CATEGORY == $this->mTitle->getNamespace() ) {
			$this->openShowCategory();
		}

		Article::view();

		# If the article we've just shown is in the "Image" namespace,
		# follow it with the history list and link list for the image
		# it describes.

		if ( NS_CATEGORY == $this->mTitle->getNamespace() ) {
			$this->closeShowCategory();
		}
	}

	function openShowCategory() {
		# For overloading
	}

	function closeShowCategory() {
		global $wgOut, $wgRequest;
		$from = $wgRequest->getVal( 'from' );
		$until = $wgRequest->getVal( 'until' );

		$viewer = new CategoryViewer( $this->mTitle, $from, $until );
		$wgOut->addHTML( $viewer->getHTML() );
	}
}

class CategoryViewer {
	var $title, $limit, $from, $until,
		$articles, $articles_start_char, 
		$children, $children_start_char,
		$showGallery, $gallery;

	function __construct( $title, $from = '', $until = '' ) {
		global $wgCategoryPagingLimit;
		$this->title = $title;
		$this->from = $from;
		$this->until = $until;
		$this->limit = $wgCategoryPagingLimit;
	}
	
	/**
	 * Format the category data list.
	 *
	 * @param string $from -- return only sort keys from this item on
	 * @param string $until -- don't return keys after this point.
	 * @return string HTML output
	 * @private
	 */
	function getHTML() {
		global $wgOut, $wgCategoryMagicGallery, $wgCategoryPagingLimit;
		wfProfileIn( __METHOD__ );

		$this->showGallery = $wgCategoryMagicGallery && !$wgOut->mNoGallery;

		$this->clearCategoryState();
		$this->doCategoryQuery();
		$this->finaliseCategoryState();

		$r = $this->getCategoryTop() .
			$this->getSubcategorySection() .
			$this->getPagesSection() .
			$this->getImageSection() .
			$this->getCategoryBottom();

		wfProfileOut( __METHOD__ );
		return $r;
	}

	function clearCategoryState() {
		$this->articles = array();
		$this->articles_start_char = array();
		$this->children = array();
		$this->children_start_char = array();
		if( $this->showGallery ) {
			$this->gallery = new ImageGallery();
			$this->gallery->setParsing();
		}
	}

	/**
	 * Add a subcategory to the internal lists
	 */
	function addSubcategory( $title, $sortkey, $pageLength ) {
		global $wgContLang;
		// Subcategory; strip the 'Category' namespace from the link text.
		$this->children[] = Linker::makeKnownLinkObj( 
			$title, $wgContLang->convertHtml( $title->getText() ) );

		$this->children_start_char[] = $this->getSubcategorySortChar( $title, $sortkey );
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
		
		if( $title->getPrefixedText() == $sortkey ) {
			$firstChar = $wgContLang->firstChar( $title->getDBkey() );
		} else {
			$firstChar = $wgContLang->firstChar( $sortkey );
		}
		
		return $wgContLang->convert( $firstChar );
	}

	/**
	 * Add a page in the image namespace
	 */
	function addImage( $title, $sortkey, $pageLength ) {
		if ( $this->showGallery ) {
			$image = new Image( $title );
			if( $this->flip ) {
				$this->gallery->insert( $image );
			} else {
				$this->gallery->add( $image );
			}
		} else {
			$this->addPage( $title, $sortkey, $pageLength );
		}
	}

	/**
	 * Add a miscellaneous page
	 */
	function addPage( $title, $sortkey, $pageLength ) {
		global $wgContLang;
		$this->articles[] = Linker::makeSizeLinkObj( 
			$pageLength, $title, $wgContLang->convert( $title->getPrefixedText() ) 
		);
		$this->articles_start_char[] = $wgContLang->convert( $wgContLang->firstChar( $sortkey ) );
	}

	function finaliseCategoryState() {
		if( $this->flip ) {
			$this->children            = array_reverse( $this->children );
			$this->children_start_char = array_reverse( $this->children_start_char );
			$this->articles            = array_reverse( $this->articles );
			$this->articles_start_char = array_reverse( $this->articles_start_char );
		}
	}

	function doCategoryQuery() {
		$dbr =& wfGetDB( DB_SLAVE );
		if( $this->from != '' ) {
			$pageCondition = 'cl_sortkey >= ' . $dbr->addQuotes( $this->from );
			$this->flip = false;
		} elseif( $this->until != '' ) {
			$pageCondition = 'cl_sortkey < ' . $dbr->addQuotes( $this->until );
			$this->flip = true;
		} else {
			$pageCondition = '1 = 1';
			$this->flip = false;
		}
		$res = $dbr->select(
			array( 'page', 'categorylinks' ),
			array( 'page_title', 'page_namespace', 'page_len', 'cl_sortkey' ),
			array( $pageCondition,
			       'cl_from          =  page_id',
			       'cl_to'           => $this->title->getDBKey()),
			       #'page_is_redirect' => 0),
			#+ $pageCondition,
			__METHOD__,
			array( 'ORDER BY' => $this->flip ? 'cl_sortkey DESC' : 'cl_sortkey',
			       'LIMIT'    => $this->limit + 1 ) );

		$count = 0;
		$this->nextPage = null;
		while( $x = $dbr->fetchObject ( $res ) ) {
			if( ++$count > $this->limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->nextPage = $x->cl_sortkey;
				break;
			}

			$title = Title::makeTitle( $x->page_namespace, $x->page_title );

			if( $title->getNamespace() == NS_CATEGORY ) {
				$this->addSubcategory( $title, $x->cl_sortkey, $x->page_len );
			} elseif( $title->getNamespace() == NS_IMAGE ) {
				$this->addImage( $title, $x->cl_sortkey, $x->page_len );
			} else {
				$this->addPage( $title, $x->cl_sortkey, $x->page_len );
			}
		}
		$dbr->freeResult( $res );
	}

	function getCategoryTop() {
		$r = "<br style=\"clear:both;\"/>\n";
		if( $this->until != '' ) {
			$r .= $this->pagingLinks( $this->title, $this->nextPage, $this->until, $this->limit );
		} elseif( $this->nextPage != '' || $this->from != '' ) {
			$r .= $this->pagingLinks( $this->title, $this->from, $this->nextPage, $this->limit );
		}
		return $r;
	}

	function getSubcategorySection() {
		# Don't show subcategories section if there are none.
		$r = '';
		if( count( $this->children ) > 0 ) {
			# Showing subcategories
			$r .= "<div id=\"mw-subcategories\">\n";
			$r .= '<h2>' . wfMsg( 'subcategories' ) . "</h2>\n";
			$r .= wfMsgExt( 'subcategorycount', array( 'parse' ), count( $this->children) );
			$r .= $this->formatList( $this->children, $this->children_start_char );
			$r .= "\n</div>";
		}
		return $r;
	}

	function getPagesSection() {
		$ti = htmlspecialchars( $this->title->getText() );
		$r = "<div id=\"mw-pages\">\n";
		$r .= '<h2>' . wfMsg( 'category_header', $ti ) . "</h2>\n";
		$r .= wfMsgExt( 'categoryarticlecount', array( 'parse' ), count( $this->articles) );
		$r .= $this->formatList( $this->articles, $this->articles_start_char );
		$r .= "\n</div>";
		return $r;
	}

	function getImageSection() {
		if( $this->showGallery && ! $this->gallery->isEmpty() ) {
			return $this->gallery->toHTML();
		} else {
			return '';
		}
	}

	function getCategoryBottom() {
		if( $this->until != '' ) {
			return $this->pagingLinks( $this->title, $this->nextPage, $this->until, $this->limit );
		} elseif( $this->nextPage != '' || $this->from != '' ) {
			return $this->pagingLinks( $this->title, $this->from, $this->nextPage, $this->limit );
		} else {
			return '';
		}
	}

	/**
	 * Format a list of articles chunked by letter, either as a
	 * bullet list or a columnar format, depending on the length.
	 *
	 * @param array $articles
	 * @param array $articles_start_char
	 * @param int   $cutoff
	 * @return string
	 * @private
	 */
	function formatList( $articles, $articles_start_char, $cutoff = 6 ) {
		if ( count ( $articles ) > $cutoff ) {
			return $this->columnList( $articles, $articles_start_char );
		} elseif ( count($articles) > 0) {
			// for short lists of articles in categories.
			return $this->shortList( $articles, $articles_start_char );
		}
		return '';
	}

	/**
	 * Format a list of articles chunked by letter in a three-column
	 * list, ordered vertically.
	 *
	 * @param array $articles
	 * @param array $articles_start_char
	 * @return string
	 * @private
	 */
	function columnList( $articles, $articles_start_char ) {
		// divide list into three equal chunks
		$chunk = (int) (count ( $articles ) / 3);

		// get and display header
		$r = '<table width="100%"><tr valign="top">';

		$prev_start_char = 'none';

		// loop through the chunks
		for($startChunk = 0, $endChunk = $chunk, $chunkIndex = 0;
			$chunkIndex < 3;
			$chunkIndex++, $startChunk = $endChunk, $endChunk += $chunk + 1)
		{
			$r .= "<td>\n";
			$atColumnTop = true;

			// output all articles in category
			for ($index = $startChunk ;
				$index < $endChunk && $index < count($articles);
				$index++ )
			{
				// check for change of starting letter or begining of chunk
				if ( ($index == $startChunk) ||
					 ($articles_start_char[$index] != $articles_start_char[$index - 1]) )

				{
					if( $atColumnTop ) {
						$atColumnTop = false;
					} else {
						$r .= "</ul>\n";
					}
					$cont_msg = "";
					if ( $articles_start_char[$index] == $prev_start_char )
						$cont_msg = wfMsgHtml('listingcontinuesabbrev');
					$r .= "<h3>" . htmlspecialchars( $articles_start_char[$index] ) . "$cont_msg</h3>\n<ul>";
					$prev_start_char = $articles_start_char[$index];
				}

				$r .= "<li>{$articles[$index]}</li>";
			}
			if( !$atColumnTop ) {
				$r .= "</ul>\n";
			}
			$r .= "</td>\n";


		}
		$r .= '</tr></table>';
		return $r;
	}

	/**
	 * Format a list of articles chunked by letter in a bullet list.
	 * @param array $articles
	 * @param array $articles_start_char
	 * @return string
	 * @private
	 */
	function shortList( $articles, $articles_start_char ) {
		$r = '<h3>' . htmlspecialchars( $articles_start_char[0] ) . "</h3>\n";
		$r .= '<ul><li>'.$articles[0].'</li>';
		for ($index = 1; $index < count($articles); $index++ )
		{
			if ($articles_start_char[$index] != $articles_start_char[$index - 1])
			{
				$r .= "</ul><h3>" . htmlspecialchars( $articles_start_char[$index] ) . "</h3>\n<ul>";
			}

			$r .= "<li>{$articles[$index]}</li>";
		}
		$r .= '</ul>';
		return $r;
	}

	/**
	 * @param Title  $title
	 * @param string $first
	 * @param string $last
	 * @param int    $limit
	 * @param array  $query - additional query options to pass
	 * @return string
	 * @private
	 */
	function pagingLinks( $title, $first, $last, $limit, $query = array() ) {
		global $wgUser, $wgLang;
		$limitText = $wgLang->formatNum( $limit );

		$prevLink = htmlspecialchars( wfMsg( 'prevn', $limitText ) );
		if( $first != '' ) {
			$prevLink = Linker::makeLinkObj( $title, $prevLink,
				wfArrayToCGI( $query + array( 'until' => $first ) ) );
		}
		$nextLink = htmlspecialchars( wfMsg( 'nextn', $limitText ) );
		if( $last != '' ) {
			$nextLink = Linker::makeLinkObj( $title, $nextLink,
				wfArrayToCGI( $query + array( 'from' => $last ) ) );
		}

		return "($prevLink) ($nextLink)";
	}
}


?>
