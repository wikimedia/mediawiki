<?php
# See language.doc
include_once("LanguageUtf8.php");

if ( $wgSitename == "Wikipedia" ) {
	$wgSitename = "ويكيبيديا";
}
if ( $wgMetaNamespace == "Wikipedia" ) {
	$wgMetaNamespace = "ويكيبيديا";
}

/* private */ $wgNamespaceNamesAr = array(
	-2 => "ملف",
	-1 => "خاص",
	0 => "",
	1 => "نقاش",
	2 => "مستخدم",
	3 => "نقاش_المستخدم",
	4 => $wgMetaNamespace,
	5 => "{$wgMetaNamespace}_نقاش",
	6 => "صورة",
	7 => "نقاش_الصورة",
	8 => "MediaWiki",
	9 => "MediaWiki_talk",
);

/* private */ $wgWeekdayNamesAr = array(
	"الأحد", "الإثنين", "الثلاثاء", "الأربعاء", "الخميس",
	"الجمعة", "السبت"
);

/* private */ $wgMonthNamesAr = array(
	"يناير", "فبراير", "مارس", "ابريل", "مايو", "يونيو",
	"يوليو", "أغسطس", "سبتمبر", "اكتوبر", "نوفمبر",
	"ديسمبر"
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

	function getMonthName( $key )
	{
		global $wgMonthNamesAr;
		return $wgMonthNamesAr[$key-1];
	}

	function getMonthAbbreviation( $key )
	{
		/* No abbreviations in Arabic */
		return $this->getMonthName( $key );
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesAr;
		return $wgWeekdayNamesAr[$key-1];
	}

	function isRTL() { return true; }

	function linkPrefixExtension() { return true; }

	function getDefaultUserOptions () {
		global $wgDefaultUserOptionsEn;
		$opt = $wgDefaultUserOptionsEn;

		# Swap sidebar to right side by default
		$opt['quickbar'] = 2;
		
		# Underlines seriously harm legibility. Force off:
		$opt['underline'] = 0;
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
