<?php

/** 
 * @package MediaWiki
 * @addtogroup Ajax
 */

if( !defined( 'MEDIAWIKI' ) ) {
	die( 1 );
}

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
			} else {
				// we have an escaped ascii character
				$hexVal = substr ($source, $pos, 2);
				$decodedStr .= chr (hexdec ($hexVal));
				$pos += 2;
			}
		} else {
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
	global $wgContLang, $wgOut, $wgUser;
	$limit = 16;
	$sk = $wgUser->getSkin();

	$term = trim( $term );
	$term = str_replace( ' ', '_', $wgContLang->ucfirst( 
			$wgContLang->checkTitleEncoding( $wgContLang->recodeInput( js_unescape( $term ) ) )
		) );
	$term_title = Title::newFromText( $term );

	$r = $more = '';
	$canSearch = true;
	if( $term_title && $term_title->getNamespace() != NS_SPECIAL ) {
		$db = wfGetDB( DB_SLAVE );
		$res = $db->select( 'page', array( 'page_title', 'page_namespace' ),
				array(  'page_namespace' => $term_title->getNamespace(),
					"page_title LIKE '". $db->strencode( $term_title->getDBKey() ) ."%'" ),
					"wfSajaxSearch",
					array( 'LIMIT' => $limit+1 )
				);

		$i = 0;
		while ( ( $row = $db->fetchObject( $res ) ) && ( ++$i <= $limit ) ) {
			$nt = Title::newFromText( $row->page_title, $row->page_namespace );
			$r .= '<li>' . $sk->makeKnownLinkObj( $nt ) . "</li>\n";
		}
		if ( $i > $limit ) {
			$more = '<i>' .  $sk->makeKnownLink( $wgContLang->specialPage( "Allpages" ),
											wfMsg('moredotdotdot'),
											"namespace=0&from=" . wfUrlEncode ( $term ) ) .
				'</i>';
		}
	} else if( $term_title && $term_title->getNamespace() == NS_SPECIAL ) {
		SpecialPage::initList();
		SpecialPage::initAliasList();
		$specialPages = array_merge(
			array_keys( SpecialPage::$mList ),
			array_keys( SpecialPage::$mAliases )
		);

		foreach( $specialPages as $page ) {
			if( $wgContLang->uc( $page ) != $page && strpos( $page, $term_title->getText() ) === 0 ) {
				$r .= '<li>' . $sk->makeKnownLinkObj( Title::newFromText( $page, NS_SPECIAL ) ) . '</li>';
			}
		}

		$canSearch = false;
	}

	$valid = (bool) $term_title;
	$term_url = urlencode( $term );
	$term_diplay = htmlspecialchars( $valid ? $term_title->getFullText() : str_replace( '_', ' ', $term ) );
	$subtitlemsg = ( $valid ? 'searchsubtitle' : 'searchsubtitleinvalid' );
	$subtitle = wfMsgWikiHtml( $subtitlemsg, $term_diplay );
	$html = '<div id="searchTargetHide"><a onclick="Searching_Hide_Results();">'
		. wfMsgHtml( 'hideresults' ) . '</a></div>'
		. '<h1 class="firstHeading">'.wfMsgHtml('search')
		. '</h1><div id="contentSub">'. $subtitle . '</div>';
	if( $canSearch ) {
		$html .= '<ul><li>'
			. $sk->makeKnownLink( $wgContLang->specialPage( 'Search' ),
						wfMsgHtml( 'searchcontaining', $term_diplay ),
						"search={$term_url}&fulltext=Search" )
			. '</li><li>' . $sk->makeKnownLink( $wgContLang->specialPage( 'Search' ),
						wfMsgHtml( 'searchnamed', $term_diplay ) ,
						"search={$term_url}&go=Go" )
			. "</li></ul>";
	}
	if( $r ) {
		$html .= "<h2>" . wfMsgHtml( 'articletitles', $term_diplay ) . "</h2>"
			. '<ul>' .$r .'</ul>' . $more;
	}

	$response = new AjaxResponse( $html );

	$response->setCacheDuration( 30*60 );

	return $response;
}

/**
 * Called for AJAX watch/unwatch requests.
 * @param $pagename Prefixed title string for page to watch/unwatch
 * @param $watch String 'w' to watch, 'u' to unwatch
 * @return String '<w#>' or '<u#>' on successful watch or unwatch, 
 *   respectively, followed by an HTML message to display in the alert box; or
 *   '<err#>' on error
 */
function wfAjaxWatch($pagename = "", $watch = "") {
	if(wfReadOnly()) {
		// redirect to action=(un)watch, which will display the database lock
		// message
		return '<err#>'; 
	}

	if('w' !== $watch && 'u' !== $watch) {
		return '<err#>';
	}
	$watch = 'w' === $watch;

	$title = Title::newFromText($pagename);
	if(!$title) {
		// Invalid title
		return '<err#>';
	}
	$article = new Article($title);
	$watching = $title->userIsWatching();

	if($watch) {
		if(!$watching) {
			$dbw = wfGetDB(DB_MASTER);
			$dbw->begin();
			$article->doWatch();
			$dbw->commit();
		}
	} else {
		if($watching) {
			$dbw = wfGetDB(DB_MASTER);
			$dbw->begin();
			$article->doUnwatch();
			$dbw->commit();
		}
	}
	if( $watch ) {
		return '<w#>'.wfMsgExt( 'addedwatchtext', array( 'parse' ), $title->getPrefixedText() );
	} else {
		return '<u#>'.wfMsgExt( 'removedwatchtext', array( 'parse' ), $title->getPrefixedText() );
	}
}

