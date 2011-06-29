<?php
/**
 * Handler functions for Ajax requests
 *
 * @file
 * @ingroup Ajax
 */

if ( !defined( 'MEDIAWIKI' ) ) {
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
function js_unescape( $source, $iconv_to = 'UTF-8' ) {
	$decodedStr = '';
	$pos = 0;
	$len = strlen ( $source );

	while ( $pos < $len ) {
		$charAt = substr ( $source, $pos, 1 );
		if ( $charAt == '%' ) {
			$pos++;
			$charAt = substr ( $source, $pos, 1 );

			if ( $charAt == 'u' ) {
				// we got a unicode character
				$pos++;
				$unicodeHexVal = substr ( $source, $pos, 4 );
				$unicode = hexdec ( $unicodeHexVal );
				$decodedStr .= codepointToUtf8( $unicode );
				$pos += 4;
			} else {
				// we have an escaped ascii character
				$hexVal = substr ( $source, $pos, 2 );
				$decodedStr .= chr ( hexdec ( $hexVal ) );
				$pos += 2;
			}
		} else {
			$decodedStr .= $charAt;
			$pos++;
		}
	}

	if ( $iconv_to != "UTF-8" ) {
		$decodedStr = iconv( "utf-8", $iconv_to, $decodedStr );
	}

	return $decodedStr;
}
