<?php
/** Arabic (العربية)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once('LanguageUtf8.php');

/* private */ $wgNamespaceNamesAr = array(
        NS_MEDIA            => 'ملف',
        NS_SPECIAL          => 'خاص',
        NS_MAIN             => '',
        NS_TALK             => 'نقاش',
        NS_USER             => 'مستخدم',
        NS_USER_TALK        => 'نقاش_المستخدم',
        NS_PROJECT          => 'ويكيبيديا',
        NS_PROJECT_TALK     => 'نقاش_ويكيبيديا',
        NS_IMAGE            => 'صورة',
        NS_IMAGE_TALK       => 'نقاش_الصورة',
        NS_MEDIAWIKI        => 'ميدياويكي',
        NS_MEDIAWIKI_TALK   => 'نقاش_ميدياويكي',
        NS_TEMPLATE         => 'Template',
        NS_TEMPLATE_TALK    => 'نقاش_Template',
        NS_HELP             => 'مساعدة',
        NS_HELP_TALK        => 'نقاش_المساعدة',
        NS_CATEGORY         => 'تصنيف',
        NS_CATEGORY_TALK    => 'نقاش_التصنيف'
) + $wgNamespaceNamesEn;


/* private */ $wgAllMessagesAr = array(
'special_version_prefix' => '',
'special_version_postfix' => '',
# Dates
'sunday' => 'الأحد',
'monday' => 'الإثنين',
'tuesday' => 'الثلاثاء',
'wednesday' => 'الأربعاء',
'thursday' => 'الخميس',
'friday' => 'الجمعة',
'saturday' => 'السبت',
'january' => 'يناير',
'february' => 'فبراير',
'march' => 'مارس',
'april' => 'ابريل',
'may_long' => 'مايو',
'june' => 'يونيو',
'july' => 'يوليو',
'august' => 'أغسطس',
'september' => 'سبتمبر',
'november' => 'نوفمبر',
'december' => 'ديسمبر',

# Bits of text used by many pages:
#
'mainpage'		=> 'الصفحة الرئيسية',
'mytalk'		=> 'صفحة نقاشي',
'history_short' => 'تاريخ الصفحة',
'edit' => 'عدل هذه الصفحة',
'delete' => 'حذف هذه الصفحة',
'protect' => 'صفحة محمية',
'talk' => 'ناقش هذه الصفحة',

# Watchlist
#
'watch' => 'راقب هذه الصفحة',
'watchthispage'		=> 'راقب هذه الصفحة',
'unwatch' => 'توقف عن مراقبة الصفحة',
'unwatchthispage' 	=> 'توقف عن مراقبة الصفحة',
);

class LanguageAr extends LanguageUtf8 {
	var $digitTransTable = array(
		'0' => '٠',
		'1' => '١',
		'2' => '٢',
		'3' => '٣',
		'4' => '٤',
		'5' => '٥',
		'6' => '٦',
		'7' => '٧',
		'8' => '٨',
		'9' => '٩',
		'%' => '٪',
		'.' => '٫',
		',' => '٬'
	);

	function getNamespaces() {
		global $wgNamespaceNamesAr;
		return $wgNamespaceNamesAr;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesAr;
		return $wgNamespaceNamesAr[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesAr;

		foreach ( $wgNamespaceNamesAr as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return LanguageUtf8::getNsIndex( $text );
	}

	function getMonthAbbreviation( $key ) {
		/* No abbreviations in Arabic */
		return $this->getMonthName( $key );
	}

	function isRTL() {
		return true;
	}

	function linkPrefixExtension() {
		return true;
	}

	function getDefaultUserOptions() {
		$opt = parent::getDefaultUserOptions();

		# Swap sidebar to right side by default
		$opt['quickbar'] = 2;

		# Underlines seriously harm legibility. Force off:
		$opt['underline'] = 0;
		return $opt ;
	}

	function fallback8bitEncoding() {
		return 'windows-1256';
	}

	function getMessage( $key ) {
		global $wgAllMessagesAr, $wgAllMessagesEn;
		$m = $wgAllMessagesAr[$key];

		if ( '' == $m ) { return $wgAllMessagesEn[$key]; }
		else return $m;
	}

	function formatNum( $number ) {
		global $wgTranslateNumerals;
		if( $wgTranslateNumerals ) {
			return strtr( $number, $this->digitTransTable );
		} else {
			return $number;
		}
	}
}

?>
