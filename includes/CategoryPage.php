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
		
		$wgOut->addHTML( $this->doCategoryMagic( $from, $until ) );
	}

	/**
	 * Format the category data list.
	 *
	 * @param string $from -- return only sort keys from this item on
	 * @param string $until -- don't return keys after this point.
	 * @return string HTML output
	 * @private
	 */
	function doCategoryMagic( $from = '', $until = '' ) {
		global $wgOut;
		global $wgContLang,$wgUser, $wgCategoryMagicGallery, $wgCategoryPagingLimit;
		$fname = 'CategoryPage::doCategoryMagic';
		wfProfileIn( $fname );

		$articles = array();
		$articles_start_char = array();
		$children = array();
		$children_start_char = array();
		
		$showGallery = $wgCategoryMagicGallery && !$wgOut->mNoGallery;
		if( $showGallery ) {
			$ig = new ImageGallery();
			$ig->setParsing();
		}

		$dbr =& wfGetDB( DB_SLAVE );
		if( $from != '' ) {
			$pageCondition = 'cl_sortkey >= ' . $dbr->addQuotes( $from );
			$flip = false;
		} elseif( $until != '' ) {
			$pageCondition = 'cl_sortkey < ' . $dbr->addQuotes( $until );
			$flip = true;
		} else {
			$pageCondition = '1 = 1';
			$flip = false;
		}
		$limit = $wgCategoryPagingLimit;
		$res = $dbr->select(
			array( 'page', 'categorylinks' ),
			array( 'page_title', 'page_namespace', 'page_len', 'cl_sortkey' ),
			array( $pageCondition,
			       'cl_from          =  page_id',
			       'cl_to'           => $this->mTitle->getDBKey()),
			       #'page_is_redirect' => 0),
			#+ $pageCondition,
			$fname,
			array( 'ORDER BY' => $flip ? 'cl_sortkey DESC' : 'cl_sortkey',
			       'LIMIT'    => $limit + 1 ) );

		$sk =& $wgUser->getSkin();
		$r = "<br style=\"clear:both;\"/>\n";
		$count = 0;
		$nextPage = null;
		while( $x = $dbr->fetchObject ( $res ) ) {
			if( ++$count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$nextPage = $x->cl_sortkey;
				break;
			}

			$title = Title::makeTitle( $x->page_namespace, $x->page_title );

			if( $title->getNamespace() == NS_CATEGORY ) {
				// Subcategory; strip the 'Category' namespace from the link text.
				array_push( $children, $sk->makeKnownLinkObj( $title, $wgContLang->convertHtml( $title->getText() ) ) );

				// If there's a link from Category:A to Category:B, the sortkey of the resulting
				// entry in the categorylinks table is Category:A, not A, which it SHOULD be.
				// Workaround: If sortkey == "Category:".$title, than use $title for sorting,
				// else use sortkey...
				$sortkey='';
				if( $title->getPrefixedText() == $x->cl_sortkey ) {
					$sortkey=$wgContLang->firstChar( $x->page_title );
				} else {
					$sortkey=$wgContLang->firstChar( $x->cl_sortkey );
				}
				array_push( $children_start_char, $wgContLang->convert( $sortkey ) ) ;
			} elseif( $showGallery && $title->getNamespace() == NS_IMAGE ) {
				// Show thumbnails of categorized images, in a separate chunk
				if( $flip ) {
					$ig->insert( Image::newFromTitle( $title ) );
				} else {
					$ig->add( Image::newFromTitle( $title ) );
				}
			} else {
				// Page in this category
				array_push( $articles, $sk->makeSizeLinkObj( $x->page_len, $title, $wgContLang->convert( $title->getPrefixedText() ) ) ) ;
				array_push( $articles_start_char, $wgContLang->convert( $wgContLang->firstChar( $x->cl_sortkey ) ) );
			}
		}
		$dbr->freeResult( $res );

		if( $flip ) {
			$children            = array_reverse( $children );
			$children_start_char = array_reverse( $children_start_char );
			$articles            = array_reverse( $articles );
			$articles_start_char = array_reverse( $articles_start_char );
		}

		if( $until != '' ) {
			$r .= $this->pagingLinks( $this->mTitle, $nextPage, $until, $limit );
		} elseif( $nextPage != '' || $from != '' ) {
			$r .= $this->pagingLinks( $this->mTitle, $from, $nextPage, $limit );
		}

		# Don't show subcategories section if there are none.
		if( count( $children ) > 0 ) {
			# Showing subcategories
			$r .= '<h2>' . wfMsg( 'subcategories' ) . "</h2>\n";
			$r .= wfMsgExt( 'subcategorycount', array( 'parse' ), count( $children) );
			$r .= $this->formatList( $children, $children_start_char );
		}

		# Showing articles in this category
		$ti = htmlspecialchars( $this->mTitle->getText() );
		$r .= '<h2>' . wfMsg( 'category_header', $ti ) . "</h2>\n";
		$r .= wfMsgExt( 'categoryarticlecount', array( 'parse' ), count( $articles) );
		$r .= $this->formatList( $articles, $articles_start_char );

		if( $showGallery && ! $ig->isEmpty() ) {
			$r.= $ig->toHTML();
		}

		if( $until != '' ) {
			$r .= $this->pagingLinks( $this->mTitle, $nextPage, $until, $limit );
		} elseif( $nextPage != '' || $from != '' ) {
			$r .= $this->pagingLinks( $this->mTitle, $from, $nextPage, $limit );
		}

		wfProfileOut( $fname );
		return $r;
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
		$sk =& $wgUser->getSkin();
		$limitText = $wgLang->formatNum( $limit );

		$prevLink = htmlspecialchars( wfMsg( 'prevn', $limitText ) );
		if( $first != '' ) {
			$prevLink = $sk->makeLinkObj( $title, $prevLink,
				wfArrayToCGI( $query + array( 'until' => $first ) ) );
		}
		$nextLink = htmlspecialchars( wfMsg( 'nextn', $limitText ) );
		if( $last != '' ) {
			$nextLink = $sk->makeLinkObj( $title, $nextLink,
				wfArrayToCGI( $query + array( 'from' => $last ) ) );
		}

		return "($prevLink) ($nextLink)";
	}
}


?>
