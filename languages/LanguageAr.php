<?php
/** Arabic (العربية)
  *
  * @package MediaWiki
  * @subpackage Language
  */

/** This is an UTF-8 language  */
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
	NS_TEMPLATE         => 'قالب',
	NS_TEMPLATE_TALK    => 'نقاش_قالب',
	NS_HELP             => 'مساعدة',
	NS_HELP_TALK        => 'نقاش_المساعدة',
	NS_CATEGORY         => 'تصنيف',
	NS_CATEGORY_TALK    => 'نقاش_التصنيف'
) + $wgNamespaceNamesEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesAr.php');
}

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
		'.' => '٫',
		',' => '٬'
	);

	function getNamespaces() {
		global $wgNamespaceNamesAr;
		return $wgNamespaceNamesAr;
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
		global $wgAllMessagesAr;
		if( isset( $wgAllMessagesAr[$key] ) ) {
			return $wgAllMessagesAr[$key];
		} else {
			return parent::getMessage( $key );
		}
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
