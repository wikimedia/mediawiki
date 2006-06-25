<?php
/** Persian (فارسی)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesFa.php');
}

class LanguageFa extends LanguageUtf8 {
	private $mMessagesFa, $mNamespaceNamesFa = null;

	private $mQuickbarSettingsFa = array(
		'نباشد', 'ثابت چپ', 'ثابت راست', 'شناور چپ'
	);
	
	private $mSkinNamesFa = array(
		'standard' => 'استاندارد',
		'nostalgia' => 'نوستالژی',
		'cologneblue' => 'آبی کلون',
		'smarty' => 'پدینگتون',
		'montparnasse' => 'مون‌پارناس',
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesFa;
		$this->mMessagesFa =& $wgAllMessagesFa;

		global $wgMetaNamespace;
		$this->mNamespaceNamesFa = array(
			NS_MEDIA          => 'مدیا',
			NS_SPECIAL        => 'ویژه',
			NS_MAIN	          => '',
			NS_TALK	          => 'بحث',
			NS_USER           => 'کاربر',
			NS_USER_TALK      => 'بحث_کاربر',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => 'بحث_' . $wgMetaNamespace,
			NS_IMAGE          => 'تصویر',
			NS_IMAGE_TALK     => 'بحث_تصویر',
			NS_MEDIAWIKI      => 'مدیاویکی',
			NS_MEDIAWIKI_TALK	=> 'بحث_مدیاویکی',
			NS_TEMPLATE       => 'الگو',
			NS_TEMPLATE_TALK  => 'بحث_الگو',
			NS_HELP           => 'راهنما',
			NS_HELP_TALK      => 'بحث_راهنما',
			NS_CATEGORY       => 'رده',
			NS_CATEGORY_TALK  => 'بحث_رده'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesFa + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsFa;
	}

	function getSkinNames() {
		return $this->mSkinNamesFa + parent::getSkinNames();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesFa[$key] ) ) {
			return $this->mMessagesFa[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesFa;
	}

	function digitTransformTable() {
		return array(
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
			"." => "٫", // wrong table?
			"," => "٬"
		);
	}

	function getDefaultUserOptions() {
		$opt = Language::getDefaultUserOptions();
		$opt['quickbar'] = 2;
		$opt['underline'] = 0;
		return $opt;
	}


	# For right-to-left language support
	function isRTL() { return true; }

}
?>
