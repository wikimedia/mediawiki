<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
#--------------------------------------------------------------------------
# Translated from English by Varakorn Ungvichian
# แปลงจากภาษาอังกฤษโดย นาย วรากร อึ้งวิเชียร
#--------------------------------------------------------------------------

/* private */ $wgNamespaceNamesTh = array(
	NS_MEDIA		=> "Media",
	NS_SPECIAL		=> "พิเศษ",
	NS_MAIN			=> "",
	NS_TALK			=> "พูดคุย",
	NS_USER			=> "ผู้ใช้",
	NS_USER_TALK		=> "คุยเกี่ยวกับผู้ใช้",
	NS_PROJECT		=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> $wgMetaNamespace . "_talk",
	NS_IMAGE		=> "ภาพ",
	NS_IMAGE_TALK		=> "คุยเกี่ยวกับภาพ",
	NS_MEDIAWIKI		=> "MediaWiki",
	NS_MEDIAWIKI_TALK	=> "คุยเกี่ยวกับ_MediaWiki",

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
