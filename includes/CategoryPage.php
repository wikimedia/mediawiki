<?php
/**
 * Special handling for category description pages
 * Modelled after ImagePage.php
 *
*/

/**
 *
 */
class CategoryPage extends Article {

	function view() {
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
	
        # generate a list of subcategories and pages for a category
        # depending on wfMsg("usenewcategorypage") it either calls the new
        # or the old code. The new code will not work properly for some
        # languages due to sorting issues, so they might want to turn it
        # off.

	function closeShowCategory() {
		global $wgOut;
		$msg = wfMsg('usenewcategorypage');
		if ( '0' == @$msg[0] )
		{
			$wgOut->addHTML( $this->oldCategoryMagic() );
		} else {
			$wgOut->addHTML( $this->newCategoryMagic() );
		}
	}

        # This method generates the list of subcategories and pages for a category
        function oldCategoryMagic () {
                global $wgLang, $wgUser ;
                $fname = 'CategoryPage::oldCategoryMagic';


                $sk =& $wgUser->getSkin() ;

                $articles = array() ;
                $children = array() ;
		$r = '';
                $id = $this->mTitle->getArticleID() ;

                # FIXME: add limits
                $dbr =& wfGetDB( DB_SLAVE );
                $cur = $dbr->tableName( 'cur' );
                $categorylinks = $dbr->tableName( 'categorylinks' );

                $t = $dbr->strencode( $this->mTitle->getDBKey() );
                $sql = "SELECT DISTINCT cur_title,cur_namespace FROM $cur,$categorylinks " .
                        "WHERE cl_to='$t' AND cl_from=cur_id ORDER BY cl_sortkey" ;
                $res = $dbr->query( $sql, $fname ) ;
                # For all pages that link to this category
                while ( $x = $dbr->fetchObject ( $res ) )
                {
                        $t = $wgLang->getNsText ( $x->cur_namespace ) ;
                        if ( $t != '' ) $t .= ':' ;
                        $t .= $x->cur_title ;

                        if ( $x->cur_namespace == NS_CATEGORY ) {
                                array_push ( $children , $sk->makeLink ( $t ) ) ; # Subcategory
                        } else {
                                array_push ( $articles , $sk->makeLink ( $t ) ) ; # Page in this category
                        }
                }
                $dbr->freeResult ( $res ) ;

                # Showing subcategories
                if ( count ( $children ) > 0 ) {
                        $r .= '<h2>'.wfMsg('subcategories')."</h2>\n" ;
                        $r .= implode ( ', ' , $children ) ;
                }

                # Showing pages in this category
                if ( count ( $articles ) > 0 ) {
                        $ti = $this->mTitle->getText() ;
                        $h =  wfMsg( 'category_header', $ti );
                        $r .= "<h2>$h</h2>\n" ;
                        $r .= implode ( ', ' , $articles ) ;
                }

                return $r ;
	}

        function newCategoryMagic () {
                global $wgLang,$wgUser;

		$sk =& $wgUser->getSkin();

                $r = "<br style=\"clear:both;\"/>\n";

                $articles = array() ;
                $articles_start_char = array();
                $children = array() ;
                $children_start_char = array();
                $data = array () ;
                $id = $this->mTitle->getArticleID() ;

                # FIXME: add limits
                $dbr =& wfGetDB( DB_SLAVE );
                $cur = $dbr->tableName( 'cur' );
                $categorylinks = $dbr->tableName( 'categorylinks' );

                $t = $dbr->strencode( $this->mTitle->getDBKey() );
                $sql = "SELECT DISTINCT cur_title,cur_namespace,cl_sortkey FROM " .
                        "$cur,$categorylinks WHERE cl_to='$t' AND cl_from=cur_id ORDER BY cl_sortkey" ;
                $res = $dbr->query ( $sql ) ;
                while ( $x = $dbr->fetchObject ( $res ) )
                {
                        $t = $ns = $wgLang->getNsText ( $x->cur_namespace ) ;
                        if ( $t != '' ) $t .= ':' ;
                        $t .= $x->cur_title ;

                        if ( $x->cur_namespace == NS_CATEGORY ) {
                                $ctitle = str_replace( '_',' ',$x->cur_title );
                                array_push ( $children, $sk->makeKnownLink ( $t, $ctitle ) ) ; # Subcategory

                                // If there's a link from Category:A to Category:B, the sortkey of the resulting
                                // entry in the categorylinks table is Category:A, not A, which it SHOULD be.
                                // Workaround: If sortkey == "Category:".$title, than use $title for sorting,
                                // else use sortkey...
                                if ( ($ns.':'.$ctitle) == $x->cl_sortkey ) {
                                        array_push ( $children_start_char, $wgLang->firstChar( $x->cur_title ) );
                                } else {
                                        array_push ( $children_start_char, $wgLang->firstChar( $x->cl_sortkey ) ) ;
                                }
                        } else {
                                array_push ( $articles , $sk->makeKnownLink ( $t ) ) ; # Page in this category
                                array_push ( $articles_start_char, $wgLang->firstChar( $x->cl_sortkey ) ) ;
                        }
                }
                $dbr->freeResult ( $res ) ;

                $ti = $this->mTitle->getText() ;

                # Don't show subcategories section if there are none.
                if ( count ( $children ) > 0 )
                {
                        # Showing subcategories
                        $r .= '<h2>' . wfMsg( 'subcategories' ) . "</h2>\n";

                        $numchild = count( $children );
                        if($numchild == 1) {
                                $r .= wfMsg( 'subcategorycount1', 1 );
                        } else {
                                $r .= wfMsg( 'subcategorycount' , $numchild );
                        }
                        unset($numchild);

                        if ( count ( $children ) > 6 ) {

                                // divide list into three equal chunks
                                $chunk = (int) (count ( $children ) / 3);

                                // get and display header
                                $r .= '<table width="100%"><tr valign="top">';

                                $startChunk = 0;
                                $endChunk = $chunk;

                                // loop through the chunks
                                for($startChunk = 0, $endChunk = $chunk, $chunkIndex = 0;
                                        $chunkIndex < 3;
                                        $chunkIndex++, $startChunk = $endChunk, $endChunk += $chunk + 1)
                                {

                                        $r .= '<td><ul>';
                                        // output all subcategories to category
                                        for ($index = $startChunk ;
                                                $index < $endChunk && $index < count($children);
                                                $index++ )
                                        {
                                                // check for change of starting letter or begging of chunk
                                                if ( ($children_start_char[$index] != $children_start_char[$index - 1])
                                                        || ($index == $startChunk) )
                                                {
                                                        $r .= "</ul><h3>{$children_start_char[$index]}</h3>\n<ul>";
                                                }

                                                $r .= "<li>{$children[$index]}</li>";
                                        }
                                        $r .= '</ul></td>';


                                }
                                $r .= '</tr></table>';
                        } else {
                                // for short lists of subcategories to category.

                                $r .= "<h3>{$children_start_char[0]}</h3>\n";
                                $r .= '<ul><li>'.$children[0].'</li>';
                                for ($index = 1; $index < count($children); $index++ )
                                {
                                        if ($children_start_char[$index] != $children_start_char[$index - 1])
                                        {
                                                $r .= "</ul><h3>{$children_start_char[$index]}</h3>\n<ul>";
                                        }
                                        $r .= "<li>{$children[$index]}</li>";
                                }
                                $r .= '</ul>';
                        }
                } # END of if ( count($children) > 0 )

                $r .= '<h2>' . wfMsg( 'category_header', $ti ) . "</h2>\n";

                $numart = count( $articles );
                if($numart == 1) {
                        $r .= wfMsg( 'categoryarticlecount1', 1 );
                } else {
                        $r .= wfMsg( 'categoryarticlecount' , $numart );
                }
                unset($numart);

                # Showing articles in this category
                if ( count ( $articles ) > 6) {
                        $ti = $this->mTitle->getText() ;

                        // divide list into three equal chunks
                        $chunk = (int) (count ( $articles ) / 3);

                        // get and display header
                        $r .= '<table width="100%"><tr valign="top">';

                        // loop through the chunks
                        for($startChunk = 0, $endChunk = $chunk, $chunkIndex = 0;
                                $chunkIndex < 3;
                                $chunkIndex++, $startChunk = $endChunk, $endChunk += $chunk + 1)
                        {

                                $r .= '<td><ul>';

                                // output all articles in category
                                for ($index = $startChunk ;
                                        $index < $endChunk && $index < count($articles);
                                        $index++ )
                                {
                                        // check for change of starting letter or begging of chunk
                                        if ( ($index == $startChunk) ||
                                             ($articles_start_char[$index] != $articles_start_char[$index - 1]) )

                                        {
                                                $r .= "</ul><h3>{$articles_start_char[$index]}</h3>\n<ul>";
                                        }

                                        $r .= "<li>{$articles[$index]}</li>";
                                }
                                $r .= '</ul></td>';


                        }
                        $r .= '</tr></table>';
                } elseif ( count($articles) > 0) {
                        // for short lists of articles in categories.
                        $ti = $this->mTitle->getText() ;

                        $r .= '<h3>'.$articles_start_char[0]."</h3>\n";
                        $r .= '<ul><li>'.$articles[0].'</li>';
                        for ($index = 1; $index < count($articles); $index++ )
                        {
                                if ($articles_start_char[$index] != $articles_start_char[$index - 1])
                                {
                                        $r .= "</ul><h3>{$articles_start_char[$index]}</h3>\n<ul>";
                                }

                                $r .= "<li>{$articles[$index]}</li>";
                        }
                        $r .= '</ul>';
                }


                return $r ;
        }


}


?>
