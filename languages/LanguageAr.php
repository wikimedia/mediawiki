<?
# See language.doc
include_once("Utf8Case.php");

class LanguageAr extends LanguageUtf8 {
	# TODO: TRANSLATION!

	# Inherit everything except...

	function isRTL() { return true; }

	function getDefaultUserOptions () {
		global $wgDefaultUserOptionsEn;
		$opt = $wgDefaultUserOptionsEn;

		# Swap sidebar to right side by default
		$opt['quickbar'] = 2;
		return $opt ;
	}

	function checkTitleEncoding( $s ) {
		global $wgInputEncoding;
		
		# Check for non-UTF-8 URLs; assume they are windows-1256?
	        $ishigh = preg_match( '/[\x80-\xff]/', $s);
		$isutf = ($ishigh ? preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
                '[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s ) : true );

		if( $ishigh and !$isutf )
			return iconv( "windows-1256", "utf-8", $s );
		
		return $s;
	}

}

?>
