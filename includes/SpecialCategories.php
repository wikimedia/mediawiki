<?php

function wfSpecialCategories()
{
	global $wgUser, $wgOut , $wgLang ;
	global $article , $category ;
	$sk = $wgUser->getSkin() ;
	$sc = "Special:Categories" ;
	$r = "" ;
	$r .= "<OL>\n" ;
	$cat = ucfirst ( wfMsg ( "category" ) ) ;
	$sql = "SELECT cur_title FROM cur WHERE cur_title LIKE \"{$cat}:%\"" ;
	$res = wfQuery ( $sql, DB_READ ) ;
	while ( $x = wfFetchObject ( $res ) )
	  {
	    $t = explode ( ":" , $x->cur_title , 2 ) ;
	    $t = $t[1] ;
	    $r .= "<li>" ;
	    $r .= $sk->makeKnownLink ( $x->cur_title , $t ) ;
	    $r .= "</li>\n" ;
	  }
	wfFreeResult ( $res ) ;
	$r .= "</OL>\n" ;

	$r .= "<hr>\n" ;
	$sql = "SELECT DISTINCT bl_to FROM brokenlinks WHERE bl_to LIKE \"{$cat}:%\"" ;
	$res = wfQuery ( $sql, DB_READ ) ;
	$r .= "<OL>\n" ;
	while ( $x = wfFetchObject ( $res ) )
	  {
	    $t = explode ( ":" , $x->bl_to , 2 ) ;
	    $t = $t[1] ;
	    $r .= "<li>" ;
	    $r .= $sk->makeBrokenLink ( $x->bl_to , $t ) ;
	    $r .= "</li>\n" ;
	  }
	wfFreeResult ( $res ) ;
	$r .= "</OL>\n" ;

	$wgOut->addHTML ( $r ) ;
}

?>
