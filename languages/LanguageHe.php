<?
# See language.doc
include_once("Utf8Case.php");

class LanguageHe extends LanguageUtf8 {
	# Inherit everything

	# TODO: right-to-left support functions...?
	function isRTL() { return true; }

	# There may be a partial translation; get this merged in.

	function checkTitleEncoding( $s ) {
		global $wgInputEncoding;
		
		# Check for non-UTF-8 URLs; assume they are 8859-8?
	        $ishigh = preg_match( '/[\x80-\xff]/', $s);
		$isutf = ($ishigh ? preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
                '[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s ) : true );

		if( $ishigh and !$isutf )
			return iconv( "iso8859-8", "utf-8", $s );
		
		return $s;
	}

}

?>
