<?php
/** Persian (فارسی)
  *
  * @package MediaWiki
  * @subpackage Language
  */

# Wikipedia localization for Persian

require_once('LanguageUtf8.php');

#--------------------------------------------------------------------------
# Language-specific text
#--------------------------------------------------------------------------

/* private */ $wgNamespaceNamesFa = array(
	NS_MEDIA          => "مدیا",
	NS_SPECIAL        => "ویژه",
	NS_MAIN	          => '',
	NS_TALK	          => "بحث",
	NS_USER           => "کاربر",
	NS_USER_TALK      => "بحث_کاربر",
	NS_PROJECT        => $wgMetaNamespace,
	NS_PROJECT_TALK   => "بحث_" . $wgMetaNamespace,
	NS_IMAGE          => "تصویر",
	NS_IMAGE_TALK     => "بحث_تصویر",
	NS_MEDIAWIKI      => "مدیاویکی",
	NS_MEDIAWIKI_TALK	=> "بحث_مدیاویکی",
	NS_TEMPLATE       => "الگو",
	NS_TEMPLATE_TALK  => "بحث_الگو",
	NS_HELP           => "راهنما",
	NS_HELP_TALK      => "بحث_راهنما",
	NS_CATEGORY       => "رده",
	NS_CATEGORY_TALK  => "بحث_رده"
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsFa = array(
	"نباشد", "ثابت چپ", "ثابت راست", "شناور چپ"
);

/* private */ $wgSkinNamesFa = array(
	'standard' => "استاندارد",
	'nostalgia' => "نوستالژی",
	'cologneblue' => "آبی کلون",
	'smarty' => "پدینگتون",
	'montparnasse' => "مون‌پارناس",
) + $wgSkinNamesEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesFa.php');
}

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageFa extends LanguageUtf8 {
	var $digitTransTable = array(
		"0" => "۰",
		"1" => "۱",
		"2" => "۲",
		"3" => "۳",
		"4" => "۴",
		"5" => "۵",
		"6" => "۶",
		"7" => "۷",
		"8" => "۸",
		"9" => "۹",
		"%" => "٪",
		"." => "٫",
		"," => "٬"
	);

	function getDefaultUserOptions() {
		$opt = Language::getDefaultUserOptions();
		$opt['quickbar'] = 2;
		$opt['underline'] = 0;
		return $opt;
	}

	function getNamespaces() {
		global $wgNamespaceNamesFa;
		return $wgNamespaceNamesFa;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsFa;
		return $wgQuickbarSettingsFa;
	}

	function getSkinNames() {
		global $wgSkinNamesFa;
		return $wgSkinNamesFa;
	}

	function getMessage( $key ) {
		global $wgAllMessagesFa;
		if(array_key_exists($key, $wgAllMessagesFa))
			return $wgAllMessagesFa[$key];
		else
			return parent::getMessage($key);
	}

	# For right-to-left language support
	function isRTL() { return true; }

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
