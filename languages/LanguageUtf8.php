<?php

$wgInputEncoding    = "utf-8";
$wgOutputEncoding	= "utf-8";

$wikiUpperChars = $wgMemc->get( $key1 = "$wgDBname:utf8:upper" );
$wikiLowerChars = $wgMemc->get( $key2 = "$wgDBname:utf8:lower" );

if(empty( $wikiUpperChars) || empty($wikiLowerChars )) {
	include_once( "Utf8Case.php" );
	$wgMemc->set( $key1, $wikiUpperChars );
	$wgMemc->set( $key2, $wikiLowerChars );
}

# Base stuff useful to all UTF-8 based language files
class LanguageUtf8 extends Language {

	function ucfirst( $string ) {
		# For most languages, this is a wrapper for ucfirst()
		# But that doesn't work right in a UTF-8 locale
		global $wikiUpperChars, $wikiLowerChars;
		return preg_replace (
        	"/^([\\x00-\\x7f]|[\\xc0-\\xff][\\x80-\\xbf]*)/e",
        	"strtr ( \"\$1\" , \$wikiUpperChars )",
        	$string );
	}
	
	function lcfirst( $string ) {
		global $wikiUpperChars, $wikiLowerChars;
		return preg_replace (
        	"/^([\\x00-\\x7f]|[\\xc0-\\xff][\\x80-\\xbf]*)/e",
        	"strtr ( \"\$1\" , \$wikiLowerChars )",
        	$string );
	}

	function stripForSearch( $string ) {
		# MySQL fulltext index doesn't grok utf-8, so we
		# need to fold cases and convert to hex
		global $wikiLowerChars;
		return preg_replace(
		  "/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
		  "'U8' . bin2hex( strtr( \"\$1\", \$wikiLowerChars ) )",
		  $string );
	}

	function fallback8bitEncoding() {
		# Windows codepage 1252 is a superset of iso 8859-1
		# override this to use difference source encoding to
		# translate incoming 8-bit URLs.
		return "windows-1252";
	}

	function checkTitleEncoding( $s ) {
		global $wgInputEncoding;

		# Check for non-UTF-8 URLs
		$ishigh = preg_match( '/[\x80-\xff]/', $s);
		if(!$ishigh) return $s;
		
		$isutf8 = preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
                '[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s );
		if( $isutf8 ) return $s;

		return $this->iconv( $this->fallback8bitEncoding(), "utf-8", $s );
	}
}

?>
