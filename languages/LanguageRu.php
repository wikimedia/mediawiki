<?
# See language.doc
global $IP;
include_once("$IP/Utf8Case.php");

class LanguageRu extends LanguageUtf8 {
	# Inherit everything

	function checkTitleEncoding( $s ) {
		global $wgInputEncoding;
		
		# Check for non-UTF-8 URLs; assume they are Windows-1251
	        $ishigh = preg_match( '/[\x80-\xff]/', $s);
		$isutf = ($ishigh ? preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
                '[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s ) : true );

		if( $ishigh and !$isutf )
			return iconv( "windows-1251", "utf-8", $s );
		
		return $s;
	}

}

?>
