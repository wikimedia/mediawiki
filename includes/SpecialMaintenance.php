<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * shortcut to get the current language "special" namespace name
 */
function sns() {
	global $wgContLang ;
	$ns = $wgContLang->getNamespaces() ;
	return $ns[NS_SPECIAL] ;
}


/**
 * Entry point
 */
function wfSpecialMaintenance( $par=NULL ) {
	global $wgUser, $wgOut, $wgContLang, $wgTitle, $wgRequest, $wgContLanguageCode;
	global $wgMiserMode;

	# This pages is expensive ressource wise
	if ( $wgMiserMode ) {
		$wgOut->addWikiText( wfMsg( 'perfdisabled' ) );
		return;
	}
	
	# Get parameters from the URL
	$submitmll = $wgRequest->getVal( 'submitmll' );

	if( $par ) {
		$subfunction = $par;
	} else {
		$subfunction = $wgRequest->getText( 'subfunction' );
	}

	# Call the subfunction requested by the user
	switch( $subfunction ) {
	case 'disambiguations': return wfSpecialDisambiguations() ; break;
	
	# doubleredirects & brokenredirects are old maintenance subpages.
	case 'doubleredirects': return wfSpecialDoubleRedirects() ; break;
	case 'brokenredirects': return wfSpecialBrokenRedirects() ; break;
	
	case 'selflinks':       return wfSpecialSelfLinks()       ; break;
	case 'mispeelings':     return wfSpecialMispeelings()     ; break;
	case 'missinglanguagelinks': return wfSpecialMissingLanguageLinks() ; break;
	}
	
	if ( !is_null( $submitmll ) ) return wfSpecialMissingLanguageLinks() ;

	$sk = $wgUser->getSkin();
	$ns = $wgContLang->getNamespaces() ;

	# Generate page output
	
	$r = wfMsg('maintnancepagetext') ;
	
	# Links to subfunctions
	$r .= "<UL>\n" ;
	$r .= "<li>".$sk->makeKnownLink( sns().':Disambiguations', wfMsg('disambiguations')) . "</li>\n";
	$r .= '<li>'.$sk->makeKnownLink( sns().':DoubleRedirects', wfMsg('doubleredirects')) . "</li>\n";
	$r .= '<li>'.$sk->makeKnownLink( sns().':BrokenRedirects', wfMsg('brokenredirects')) . "</li>\n";
	#$r .= "<li>".getMPL("selflinks")."</li>\n" ; # Doesn't work
	$r .= '<li>'.getMPL("mispeelings")."</li>\n" ;

	# Interface for the missing language links
	$r .= '<li>';
	  $l = getMPL('missinglanguagelinks');
	  $l = str_replace ( '</a>' , '' , $l ) ;
	  $l = str_replace ( '<a ' , '<FORM method="post" ' , $l ) ;
	  $l = explode ( '>' , $l ) ;
	  $l = $l[0] ;
	$r .= $l.">\n" ;
	$r .= '<input type="submit" name="submitmll" value="' ;
	$r .= htmlspecialchars(wfMsg('missinglanguagelinksbutton'), ENT_QUOTES);
	$r .= "\">\n" ;
	$r .= "<select name=\"thelang\">\n" ;
	
	$a = $wgContLang->getLanguageNames();
	$ak = array_keys ( $a ) ;
	foreach ( $ak AS $k ) {
		if ( $k != $wgContLanguageCode )
			$r .= "<option value='{$k}'>{$a[$k]}</option>\n" ;
	}
	$r .= "</select>\n" ;
	$r .= "</FORM>\n</li>" ;

	$r .= "</UL>\n" ;
	$wgOut->addHTML ( $r ) ;
}

/**
 * Generate a maintenance page link
 */
function getMPL ( $x ) {
	global $wgUser , $wgLang;
	$sk = $wgUser->getSkin() ;
	return $sk->makeKnownLink( sns().":Maintenance" , wfMsg($x), 'subfunction='.$x ) ;
}


function getMaintenancePageBacklink( $subfunction ) {
	global $wgUser , $wgContLang;
	$sk = $wgUser->getSkin() ;
	$ns = $wgContLang->getNamespaces() ;
	$r = $sk->makeKnownLink (
		$ns[-1].':Maintenance',
		wfMsg( 'maintenancebacklink' ) ) ;
	$t = wfMsg ( $subfunction ) ;
	
	$s = '<table width="100%" border="0"><tr><td>';
	$s .= '<h2>'.$t.'</h2></td><td align="right">';
	$s .= "{$r}</td></tr></table>\n" ;
	return $s ;
}


/**#@+
 * Disambiguations, DoubleRedirects and BrokenRedirects are now using the
 * QueryPage class. Code is in a Special*.php file.
 *
 * @deprecated
 */
function wfSpecialDoubleRedirects() {
	global $wgOut;
	$t = Title::makeTitle( NS_SPECIAL, "DoubleRedirects" );
	$wgOut->redirect ($t->getFullURL());
}

function wfSpecialBrokenRedirects() {
	global $wgOut;
	$t = Title::makeTitle( NS_SPECIAL, "BrokenRedirects" );
	$wgOut->redirect ($t->getFullURL());
}

function wfSpecialDisambiguations() {
	global $wgOut;
	$t = Title::makeTitle( NS_SPECIAL, "Disambiguations" );
	$wgOut->redirect ($t->getFullURL());
}
/**#@-*/


/**
 * This doesn't really work anymore, because self-links are now displayed as
 * unlinked bold text, and are not entered into the link table.
 *
 * @deprecated
 */
function wfSpecialSelfLinks() {
	global $wgUser, $wgOut, $wgLang, $wgTitle;
	$fname = 'wfSpecialSelfLinks';

	list( $limit, $offset ) = wfCheckLimits();

	$sql = "SELECT cur_namespace,cur_title FROM cur,links " . 
	  "WHERE l_from=l_to AND l_to=cur_id " . 
	  "LIMIT {$offset}, {$limit}";

	$res = wfQuery( $sql, DB_SLAVE, $fname );

	$top = getMaintenancePageBacklink( 'selflinks' );
	$top .= '<p>'.wfMsg('selflinkstext')."</p><br>\n";
	$top .= wfShowingResults( $offset, $limit );
	$wgOut->addHTML( "<p>{$top}\n" );

	$sl = wfViewPrevNext( $offset, $limit, 'REPLACETHIS' ) ;
	$sl = str_replace ( 'REPLACETHIS' , sns().":Maintenance&subfunction=selflinks" , $sl ) ;
	$wgOut->addHTML( "<br>{$sl}\n" );

	$sk = $wgUser->getSkin();
	$s = '<ol start=' . ( $offset + 1 ) . '>';
	while ( $obj = wfFetchObject( $res ) ) {
		$title = Title::makeTitle( $obj->cur_namespace, $obj->cur_title );
		$s .= "<li>".$sk->makeKnownLinkObj( $title )."</li>\n" ;
	}
	wfFreeResult( $res );
	$s .= '</ol>';
	$wgOut->addHTML( $s );
	$wgOut->addHTML( "<p>{$sl}\n" );
}

/**
 * 
 */
function wfSpecialMispeelings () {
	global $wgUser, $wgOut, $wgContLang, $wgTitle;
	$sk = $wgUser->getSkin();
	$fname = 'wfSpecialMispeelings';

	list( $limit, $offset ) = wfCheckLimits();
	$dbr =& wfGetDB( DB_SLAVE );
	extract( $dbr->tableNames( 'cur', 'searchindex' ) );

	# Determine page name
	$ms = wfMsg ( 'mispeelingspage' ) ;
	$mss = str_replace ( ' ' , '_' , $ms );
	$msp = $wgContLang->getNsText(4).':'.$ms ;
	$msl = $sk->makeKnownLink ( $msp ) ;

	# Load list from database
	$l = $dbr->selectField( 'cur', 'cur_text', array( 'cur_title' => $mss, 'cur_namespace' => 4 ), $fname );
	$l = explode ( "\n" , $l ) ;
	$a = array () ;
	foreach ( $l as $x )
		if ( substr ( trim ( $x ) , 0 , 1 ) == '*' )
			$a[] = strtolower ( trim ( substr ( trim ( $x ) , 1 ) ) );
	asort ( $a ) ;

	$cnt = 0 ;
	$b = array () ;
	foreach ( $a AS $x ) {
		if ( $cnt < $offset+$limit && $x != '' ) {
			$y = $x ;
			$x = preg_replace( '/^(\S+).*$/', '$1', $x );
			$sql = "SELECT DISTINCT cur_title FROM $cur,$searchindex WHERE cur_id=si_page AND ".
				"cur_namespace=0 AND cur_is_redirect=0 AND " .
				"(MATCH(si_text) AGAINST ('" . $dbr->strencode( $wgContLang->stripForSearch( $x ) ) . "'))" ;
			$res = $dbr->query( $sql, $fname );
			while ( $obj = $dbr->fetchObject ( $res ) ) {
				if ( $cnt >= $offset AND $cnt < $offset+$limit ) {
					if ( $y != '' ) {
						if ( count ( $b ) > 0 ) $b[] = "</OL>\n" ;
						$b[] = "<H3>{$y}</H3>\n<OL start=".($cnt+1).">\n" ;
						$y = '' ;
					}
					$b[] = '<li>'.
						$sk->makeKnownLink ( $obj->cur_title ).
						' ('.
						$sk->makeBrokenLink ( $obj->cur_title , wfMsg ( "qbedit" ) ).
						")</li>\n" ;
				}
				$cnt++ ;
			}
		}
	}
	$top = getMaintenancePageBacklink( 'mispeelings' );
	$top .= "<p>".wfMsg( 'mispeelingstext', $msl )."</p><br>\n";
	$top .= wfShowingResults( $offset, $limit );
	$wgOut->addHTML( "<p>{$top}\n" );

	$sl = wfViewPrevNext( $offset, $limit, 'REPLACETHIS' ) ;
	$sl = str_replace ( 'REPLACETHIS' , sns().":Maintenance&subfunction=mispeelings" , $sl ) ;
	$wgOut->addHTML( "<br>{$sl}\n" );

	$s = implode ( '' , $b ) ;
	if ( count ( $b ) > 0 ) $s .= '</ol>';
	$wgOut->addHTML( $s );
	$wgOut->addHTML( "<p>{$sl}\n" );
}

/**
 *
 */
function wfSpecialMissingLanguageLinks() {
	global $wgUser, $wgOut, $wgContLang, $wgTitle, $wgRequest;
	
	$fname = 'wfSpecialMissingLanguageLinks';
	$thelang = $wgRequest->getText( 'thelang' );
	if ( $thelang == 'w' ) $thelang = 'en' ; # Fix for international wikis

	list( $limit, $offset ) = wfCheckLimits();
	$dbr =& wfGetDB( DB_SLAVE );
	$cur = $dbr->tableName( 'cur' );

	$sql = "SELECT cur_title FROM $cur " .
	  "WHERE cur_namespace=0 AND cur_is_redirect=0 " .
	  "AND cur_title NOT LIKE '%/%' AND cur_text NOT LIKE '%[[{$thelang}:%' " .
	  "LIMIT {$offset}, {$limit}";

	$res = $dbr->query( $sql, $fname );


	$mll = wfMsg( 'missinglanguagelinkstext', $wgContLang->getLanguageName($thelang) );

	$top = getMaintenancePageBacklink( 'missinglanguagelinks' );
	$top .= "<p>$mll</p><br>";
	$top .= wfShowingResults( $offset, $limit );
	$wgOut->addHTML( "<p>{$top}\n" );

	$sl = wfViewPrevNext( $offset, $limit, 'REPLACETHIS' ) ;
	$sl = str_replace ( 'REPLACETHIS' , sns().":Maintenance&subfunction=missinglanguagelinks&thelang={$thelang}" , $sl ) ;
	$wgOut->addHTML( "<br>{$sl}\n" );

	$sk = $wgUser->getSkin();
	$s = '<ol start=' . ( $offset + 1 ) . '>';
	while ( $obj = $dbr->fetchObject( $res ) )
		$s .= "<li>".$sk->makeKnownLink ( $obj->cur_title )."</li>\n" ;
	$dbr->freeResult( $res );
	$s .= '</ol>';
	$wgOut->addHTML( $s );
	$wgOut->addHTML( "<p>{$sl}\n" );
}

?>
