<?php

if( !defined( 'MEDIAWIKI' ) )
        die( 1 );

require_once('WebRequest.php');

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

class AjaxCachePolicy {
	var $policy;

	function AjaxCachePolicy( $policy = null ) {
		$this->policy = $policy;
	}

	function setPolicy( $policy ) {
		$this->policy = $policy;
	}

	function writeHeader() {
		header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		if ( is_null( $this->policy ) ) {
			// Bust cache in the head
			header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
			// always modified
			header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
			header ("Pragma: no-cache");                          // HTTP/1.0
		} else {
			header ("Expires: " . gmdate( "D, d M Y H:i:s", time() + $this->policy ) . " GMT");
			header ("Cache-Control: s-max-age={$this->policy},public,max-age={$this->policy}");
		}
	}
}
			

function wfSajaxSearch( $term ) {
	global $wgContLang, $wgAjaxCachePolicy;
	$limit = 16;
	
	$l = new Linker;

	$term = str_replace( ' ', '_', $wgContLang->ucfirst( 
			$wgContLang->checkTitleEncoding( $wgContLang->recodeInput( js_unescape( $term ) ) )
		) );

	if ( strlen( str_replace( '_', '', $term ) )<3 )
		return;

	$wgAjaxCachePolicy->setPolicy( 30*60 );

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

	$term = htmlspecialchars( $term );
	return '<div style="float:right; border:solid 1px black;background:gainsboro;padding:2px;"><a onclick="Searching_Hide_Results();">' 
		. wfMsg( 'hideresults' ) . '</a></div>'
		. '<h1 class="firstHeading">'.wfMsg('search')
		. '</h1><div id="contentSub">'.wfMsg('searchquery', $term) . '</div><ul><li>'
		. $l->makeKnownLink( $wgContLang->specialPage( 'Search' ),
					wfMsg( 'searchcontaining', $term ),
					"search=$term&fulltext=Search" )
		. '</li><li>' . $l->makeKnownLink( $wgContLang->specialPage( 'Search' ),
					wfMsg( 'searchnamed', $term ) ,
					"search=$term&go=Go" )
		. "</li></ul><h2>" . wfMsg( 'articletitles', $term ) . "</h2>"
		. '<ul>' .$r .'</ul>'.$more;
}

?>
