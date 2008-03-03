<?php

if( !defined( 'MEDIAWIKI' ) )
        die( 1 );

/**
 * Function converts an Javascript escaped string back into a string with
 * specified charset (default is UTF-8).
 * Modified function from http://pure-essence.net/stuff/code/utf8RawUrlDecode.phps
 *
 * @param $source String escaped with Javascript's escape() function
 * @param $iconv_to String destination character set will be used as second paramether in the iconv function. Default is UTF-8.
 * @return string
 */
function js_unescape($source, $iconv_to = 'UTF-8') {
   $decodedStr = '';
   $pos = 0;
   $len = strlen ($source);
   while ($pos < $len) {
       $charAt = substr ($source, $pos, 1);
       if ($charAt == '%') {
           $pos++;
           $charAt = substr ($source, $pos, 1);
           if ($charAt == 'u') {
               // we got a unicode character
               $pos++;
               $unicodeHexVal = substr ($source, $pos, 4);
               $unicode = hexdec ($unicodeHexVal);
               $decodedStr .= code2utf($unicode);
               $pos += 4;
           }
           else {
               // we have an escaped ascii character
               $hexVal = substr ($source, $pos, 2);
               $decodedStr .= chr (hexdec ($hexVal));
               $pos += 2;
           }
       }
       else {
           $decodedStr .= $charAt;
           $pos++;
       }
   }

   if ($iconv_to != "UTF-8") {
       $decodedStr = iconv("UTF-8", $iconv_to, $decodedStr);
   }
  
   return $decodedStr;
}

/**
 * Function coverts number of utf char into that character.
 * Function taken from: http://sk2.php.net/manual/en/function.utf8-encode.php#49336
 *
 * @param $num Integer
 * @return utf8char
 */
function code2utf($num){
   if ( $num<128 )
   	return chr($num);
   if ( $num<2048 )
   	return chr(($num>>6)+192).chr(($num&63)+128);
   if ( $num<65536 )
   	return chr(($num>>12)+224).chr((($num>>6)&63)+128).chr(($num&63)+128);
   if ( $num<2097152 )
   	return chr(($num>>18)+240).chr((($num>>12)&63)+128).chr((($num>>6)&63)+128) .chr(($num&63)+128);
   return '';
}

function wfSajaxSearch( $term ) {
	global $wgContLang, $wgOut;
	$limit = 16;
	
	$l = new Linker;

	$term = str_replace( ' ', '_', $wgContLang->ucfirst( 
			$wgContLang->checkTitleEncoding( $wgContLang->recodeInput( js_unescape( $term ) ) )
		) );

	if ( strlen( str_replace( '_', '', $term ) )<3 )
		return;

	$db =& wfGetDB( DB_SLAVE );
	$res = $db->select( 'page', 'page_title',
			array(  'page_namespace' => 0,
				"page_title LIKE '". $db->strencode( $term) ."%'" ),
				"wfSajaxSearch",
				array( 'LIMIT' => $limit+1 )
			);

	$r = "";

	$i=0;
	while ( ( $row = $db->fetchObject( $res ) ) && ( ++$i <= $limit ) ) {
		$nt = Title::newFromDBkey( $row->page_title );
		$r .= '<li>' . $l->makeKnownLinkObj( $nt ) . "</li>\n";
	}
	if ( $i > $limit ) {
		$more = '<i>' .  $l->makeKnownLink( $wgContLang->specialPage( "Allpages" ),
		                                wfMsg('moredotdotdot'),
		                                "namespace=0&from=" . wfUrlEncode ( $term ) ) .
			'</i>';
	} else {
		$more = '';
	}

	$subtitlemsg = ( Title::newFromText($term) ? 'searchsubtitle' : 'searchsubtitleinvalid' );
	$subtitle = $wgOut->parse( wfMsg( $subtitlemsg, wfEscapeWikiText($term) ) ); #FIXME: parser is missing mTitle !

	$term = htmlspecialchars( $term );
	$html = '<div style="float:right; border:solid 1px black;background:gainsboro;padding:2px;"><a onclick="Searching_Hide_Results();">' 
		. wfMsg( 'hideresults' ) . '</a></div>'
		. '<h1 class="firstHeading">'.wfMsg('search')
		. '</h1><div id="contentSub">'. $subtitle . '</div><ul><li>'
		. $l->makeKnownLink( $wgContLang->specialPage( 'Search' ),
					wfMsg( 'searchcontaining', $term ),
					"search=$term&fulltext=Search" )
		. '</li><li>' . $l->makeKnownLink( $wgContLang->specialPage( 'Search' ),
					wfMsg( 'searchnamed', $term ) ,
					"search=$term&go=Go" )
		. "</li></ul><h2>" . wfMsg( 'articletitles', $term ) . "</h2>"
		. '<ul>' .$r .'</ul>'.$more;
		
	$response = new AjaxResponse( $html );
	
	$response->setCacheDuration( 30*60 );
		
	return $response;
}

/**
 * Called for AJAX watch/unwatch requests.
 * @param $pageID Integer ID of the page to be watched/unwatched
 * @param $watch String 'w' to watch, 'u' to unwatch
 * @return String '<w#>' or '<u#>' on successful watch or unwatch, respectively, or '<err#>' on error (invalid XML in case we want to add HTML sometime)
 */
function wfAjaxWatch($pageID = "", $watch = "") {
	if(wfReadOnly())
		return '<err#>'; // redirect to action=(un)watch, which will display the database lock message

	if(('w' !== $watch && 'u' !== $watch) || !is_numeric($pageID))
		return '<err#>';
	$watch = 'w' === $watch;
	$pageID = intval($pageID);

	$title = Title::newFromID($pageID);
	if(!$title)
		return '<err#>';
	$article = new Article($title);
	$watching = $title->userIsWatching();

	if($watch) {
		if(!$watching) {
			$dbw =& wfGetDB(DB_MASTER);
			$dbw->begin();
			$article->doWatch();
			$dbw->commit();
		}
	} else {
		if($watching) {
			$dbw =& wfGetDB(DB_MASTER);
			$dbw->begin();
			$article->doUnwatch();
			$dbw->commit();
		}
	}

	return $watch ? '<w#>' : '<u#>';
}
?>
