<?php

function wfSpecialCategories()
{
	global $wgUser, $wgOut , $wgLang;

	$sk = $wgUser->getSkin() ;
	$sc = "Special:Categories" ;

	# List all existant categories.
	# Note: this list could become *very large*
	$r = "<ol>\n" ;
	$sql = "SELECT cur_title FROM cur WHERE cur_namespace=".Namespace::getCategory() ;
	$res = wfQuery ( $sql, DB_READ ) ;
	while ( $x = wfFetchObject ( $res ) ) {
		$title =& Title::makeTitle( NS_CATEGORY, $x->cur_title );
	    $r .= "<li>" ;
	    $r .= $sk->makeKnownLinkObj ( $title, $title->getText() ) ;
	    $r .= "</li>\n" ;
	  }
	wfFreeResult ( $res ) ;
	$r .= "</ol>\n" ;

	$r .= "<hr />\n" ;
	
	# Links to category pages that haven't been created.
	# FIXME: This could be slow if there are a lot, but the title index should
	# make it reasonably snappy since we're using an index.
	$cat = wfStrencode( $wgLang->getNsText( NS_CATEGORY ) );
	$sql = "SELECT DISTINCT bl_to FROM brokenlinks WHERE bl_to LIKE \"{$cat}:%\"" ;
	$res = wfQuery ( $sql, DB_READ ) ;
	$r .= "<ol>\n" ;
	while ( $x = wfFetchObject ( $res ) )
	  {
	    $t = explode ( ":" , $x->bl_to , 2 ) ;
	    $t = $t[1] ;
	    $r .= "<li>" ;
	    $r .= $sk->makeBrokenLink( $x->bl_to, $t ) ;
	    $r .= "</li>\n" ;
	  }
	wfFreeResult ( $res ) ;
	$r .= "</ol>\n" ;

	$wgOut->addHTML ( $r ) ;
}

?>
