<?php
# See language.doc
include_once("LanguageUtf8.php");

/* private */ $wgNamespaceNamesAr = array(
	-2 => "ملف",
	-1 => "خاص",
	0 => "",
	1 => "نقاش",
	2 => "مستخدم",
	3 => "نقاش_المستخدم",
	4 => "ويكيبيديا",
	5 => "ويكيبيديا_نقاش",
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
	var $digitTransTable = array(
		"0" => "٠",
		"1" => "١",
		"2" => "٢",
		"3" => "٣",
		"4" => "٤",
		"5" => "٥",
		"6" => "٦",
		"7" => "٧",
		"8" => "٨",
		"9" => "٩",
		"%" => "٪",
		"." => "٫",
		"," => "٬"
	);
	
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
	
	function formatNum( $number ) {
		return strtr( $number, $this->digitTransTable );
	}
}

?>
