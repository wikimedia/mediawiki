<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

if( defined( "MEDIAWIKI" ) ) {

# This file and LanguageLatin1.php may be included from within functions, so
# we need to have global statements

global $wgInputEncoding, $wgOutputEncoding, $wikiUpperChars, $wikiLowerChars;
global $wgDBname, $wgMemc;

$wgInputEncoding    = "UTF-8";
$wgOutputEncoding	= "UTF-8";

if( function_exists( 'mb_strtoupper' ) ) {
	mb_internal_encoding('UTF-8');
} else {
	# Hack our own case conversion routines

	# Loading serialized arrays is faster than parsing code :P
	$wikiUpperChars = $wgMemc->get( $key1 = "$wgDBname:utf8:upper" );
	$wikiLowerChars = $wgMemc->get( $key2 = "$wgDBname:utf8:lower" );

	if(empty( $wikiUpperChars) || empty($wikiLowerChars )) {
		require_once( "includes/Utf8Case.php" );
		$wgMemc->set( $key1, $wikiUpperChars );
		$wgMemc->set( $key2, $wikiLowerChars );
	}
}

/**
 * Base stuff useful to all UTF-8 based language files
 * @package MediaWiki
 */
class LanguageUtf8 extends Language {

	# These functions use mbstring library, if it is loaded
	# or compiled and character mapping arrays otherwise.
	# In case of language-specific character mismatch
	# it should be dealt with in Language classes.

	function ucfirst( $str ) {
		return LanguageUtf8::uc( $str, true );
	}

	function uc( $str, $first = false ) {
		if ( function_exists( 'mb_strtoupper' ) )
			if ( $first )
				if ( LanguageUtf8::isMultibyte( $str ) )
					return mb_strtoupper( mb_substr( $str, 0, 1 ) ) . mb_substr( $str, 1 );
				else
					return ucfirst( $str );
			else
				return LanguageUtf8::isMultibyte( $str ) ? mb_strtoupper( $str ) : strtoupper( $str );
		else
			if ( LanguageUtf8::isMultibyte( $str ) ) {
				global $wikiUpperChars;
				$x = $first ? '^' : '';
				return preg_replace(
					"/$x([a-z]|[\\xc0-\\xff][\\x80-\\xbf]*)/e",
					"strtr( \"\$1\" , \$wikiUpperChars )",
					$str
				);
			} else
				return $first ? ucfirst( $str ) : strtoupper( $str );
	}

	function lcfirst( $str ) {
		return LanguageUtf8::lc( $str, true );
	}

	function lc( $str, $first = false ) {
		if ( function_exists( 'mb_strtolower' ) )
			if ( $first )
				if ( LanguageUtf8::isMultibyte( $str ) )
					return mb_strtolower( mb_substr( $str, 0, 1 ) ) . mb_substr( $str, 1 );
				else
					return strtolower( substr( $str, 0, 1 ) ) . substr( $str, 1 );
			else
				return LanguageUtf8::isMultibyte( $str ) ? mb_strtolower( $str ) : strtolower( $str );
		else
			if ( LanguageUtf8::isMultibyte( $str ) ) {
				global $wikiLowerChars;
				$x = $first ? '^' : '';
				return preg_replace(
					"/$x([A-Z]|[\\xc0-\\xff][\\x80-\\xbf]*)/e",
					"strtr( \"\$1\" , \$wikiLowerChars )",
					$str
				);
			} else
				return $first ? strtolower( substr( $str, 0, 1 ) ) . substr( $str, 1 ) : strtolower( $str );
	}

	function isMultibyte( $str ) {
		return (bool)preg_match( '/^[\x80-\xff]/', $str );
	}

	function stripForSearch( $string ) {
		# MySQL fulltext index doesn't grok utf-8, so we
		# need to fold cases and convert to hex

		# In Language:: it just returns lowercase, maybe
		# all strtolower on stripped output or argument
		# should be removed and all stripForSearch
		# methods adjusted to that.

		wfProfileIn( "LanguageUtf8::stripForSearch" );
		if( function_exists( 'mb_strtolower' ) ) {
			$out = preg_replace(
				"/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
				"'U8' . bin2hex( \"$1\" )",
				mb_strtolower( $string ) );
		} else {
			global $wikiLowerChars;
			$out = preg_replace(
				"/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
				"'U8' . bin2hex( strtr( \"\$1\", \$wikiLowerChars ) )",
				$string );
		}
		wfProfileOut( "LanguageUtf8::stripForSearch" );
		return $out;
	}

	function fallback8bitEncoding() {
		# Windows codepage 1252 is a superset of iso 8859-1
		# override this to use difference source encoding to
		# translate incoming 8-bit URLs.
		return "windows-1252";
	}

	function checkTitleEncoding( $s ) {
		global $wgInputEncoding;

		if( is_array( $s ) ) {
			wfDebugDieBacktrace( 'Given array to checkTitleEncoding.' );
		}
		# Check for non-UTF-8 URLs
		$ishigh = preg_match( '/[\x80-\xff]/', $s);
		if(!$ishigh) return $s;

		$isutf8 = preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
                '[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s );
		if( $isutf8 ) return $s;

		return $this->iconv( $this->fallback8bitEncoding(), "utf-8", $s );
	}

	function firstChar( $s ) {
		preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
		'[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})/', $s, $matches);

		return isset( $matches[1] ) ? $matches[1] : "";
	}

	# Crop a string from the beginning or end to a certain number of bytes.
	# (Bytes are used because our storage has limited byte lengths for some
	# columns in the database.) Multibyte charsets will need to make sure that
	# only whole characters are included!
	#
	# $length does not include the optional ellipsis.
	# If $length is negative, snip from the beginning
	function truncate( $string, $length, $ellipsis = "" ) {
		if( $length == 0 ) {
			return $ellipsis;
		}
		if ( strlen( $string ) <= abs( $length ) ) {
			return $string;
		}
		if( $length > 0 ) {
			$string = substr( $string, 0, $length );
			$char = ord( $string[strlen( $string ) - 1] );
			if ($char >= 0xc0) {
				# We got the first byte only of a multibyte char; remove it.
				$string = substr( $string, 0, -1 );
			} elseif( $char >= 0x80 &&
			          preg_match( '/^(.*)(?:[\xe0-\xef][\x80-\xbf]|' .
			                      '[\xf0-\xf7][\x80-\xbf]{1,2})$/', $string, $m ) ) {
			    # We chopped in the middle of a character; remove it
				$string = $m[1];
			}
			return $string . $ellipsis;
		} else {
			$string = substr( $string, $length );
			$char = ord( $string[0] );
			if( $char >= 0x80 && $char < 0xc0 ) {
				# We chopped in the middle of a character; remove the whole thing
				$string = preg_replace( '/^[\x80-\xbf]+/', '', $string );
			}
			return $ellipsis . $string;
		}
	}
}

} # ifdef MEDIAWIKI

?>
