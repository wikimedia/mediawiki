<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
#--------------------------------------------------------------------------
# ผู้แปล (Translators)
# - วรากร อึ้งวิเชียร (Varakorn Ungvichian)
# - จักรกฤช วงศ์สระหลวง (Jakkrit Vongsraluang) / PaePae
#--------------------------------------------------------------------------

/* private */ $wgNamespaceNamesTh = array(
	NS_MEDIA            => 'สื่อ',
	NS_SPECIAL          => 'พิเศษ',
	NS_MAIN	            => '',
	NS_TALK	            => 'พูดคุย',
	NS_USER             => 'ผู้ใช้',
	NS_USER_TALK        => 'คุยกับผู้ใช้',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => 'คุยเรื่อง' . $wgMetaNamespace,
	NS_IMAGE            => 'ภาพ',
	NS_IMAGE_TALK       => 'คุยเรื่องภาพ',
	NS_MEDIAWIKI        => 'มีเดียวิกิ',
	NS_MEDIAWIKI_TALK   => 'คุยเรื่องมีเดียวิกิ',
	NS_TEMPLATE         => 'แม่แบบ',
	NS_TEMPLATE_TALK    => 'คุยเรื่องแม่แบบ',
	NS_HELP             => 'วิธีใช้',
	NS_HELP_TALK        => 'คุยเรื่องวิธีใช้',
	NS_CATEGORY         => 'หมวดหมู่',
	NS_CATEGORY_TALK    => 'คุยเรื่องหมวดหมู่',
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsTh = array(
	"ไม่มี", "อยู่ทางซ้าย", "อยู่ทางขวา", "ลอยทางซ้าย"
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesTh.php');
}

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

require_once( "LanguageUtf8.php" );

class LanguageTh extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesTh;
		return $wgNamespaceNamesTh;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsTh;
		return $wgQuickbarSettingsTh;
	}

	function getMessage( $key ) {
		global $wgAllMessagesTh;
		if( isset( $wgAllMessagesTh[$key] ) ) {
			return $wgAllMessagesTh[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		global $wgAllMessagesTh;
		return $wgAllMessagesTh;
	}

}

?>
