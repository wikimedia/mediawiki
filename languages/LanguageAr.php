<?
# See language.doc
include_once("LanguageUtf8.php");

$wgNamespaceNamesAr = array(
	"-2" => "ملف",
	"-1" => "خاص",
	"0" => "",
	"1" => "نقاش",
	"2" => "مستخدم",
	"3" => "نقاش_المستخدم",
	"4" => "ويكيبيديا",
	"5" => "ويكيبيديا_نقاش",
	"6" => "صورة",
	"7" => "نقاش_الصورة",
	"8" => "MediaWiki",
	"9" => "MediaWiki_talk",
);

class LanguageAr extends LanguageUtf8 {
	# TODO: TRANSLATION!

	# Inherit everything except...

	function getNamespaces()
	{
		global $wgNamespaceNamesAr;
		return $wgNamespaceNamesAr;
	}


	function getNsText( $index )
	{
		global $wgNamespaceNamesAr;
		return $wgNamespaceNamesAr[$index];
	}

	function getNsIndex( $text ) 
	{
		global $wgNamespaceNamesAr;

		foreach ( $wgNamespaceNamesAr as $i => $n ) 
		{
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return LanguageUtf8::getNsIndex( $text );
	}

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
