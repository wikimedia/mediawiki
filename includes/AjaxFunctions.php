<?php
/**
 * @file
 * @ingroup Ajax
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
 * @param $iconv_to String destination character set will be used as second parameter 
 * in the iconv function. Default is UTF-8.
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
 * Function taken from: http://www.php.net/manual/en/function.utf8-encode.php#49336
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

	$title = Title::newFromDBkey($pagename);
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
			$ok = $article->doWatch();
			$dbw->commit();
		}
	} else {
		if($watching) {
			$dbw = wfGetDB(DB_MASTER);
			$dbw->begin();
			$ok = $article->doUnwatch();
			$dbw->commit();
		}
	}
	// Something stopped the change
	if( isset($ok) && !$ok ) {
		return '<err#>';
	}
	if( $watch ) {
		return '<w#>'.wfMsgExt( 'addedwatchtext', array( 'parse' ), $title->getPrefixedText() );
	} else {
		return '<u#>'.wfMsgExt( 'removedwatchtext', array( 'parse' ), $title->getPrefixedText() );
	}
}

/**
 * Called in some places (currently just extensions)
 * to get the thumbnail URL for a given file at a given resolution.
 */
function wfAjaxGetThumbnailUrl( $file, $width, $height ) {
	$file = wfFindFile( $file );
	
	if ( !$file || !$file->exists() )
		return null;
		
	$url = $file->getThumbnail( $width, $height )->url;
	
	return $url;
}

/**
 * Called in some places (currently just extensions)
 * to get the URL for a given file.
 */
function wfAjaxGetFileUrl( $file ) {
	$file = wfFindFile( $file );
	
	if ( !$file || !$file->exists() )
		return null;
		
	$url = $file->getUrl();
	
	return $url;
}