<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once("LanguageUtf8.php");

/* private */ $wgNamespaceNamesHe = array(
	NS_MEDIA          => "מדיה",
	NS_SPECIAL        => "מיוחד",
	NS_MAIN           => "",
	NS_TALK           => "שיחה",
	NS_USER           => "משתמש",
	NS_USER_TALK      => "שיחת_משתמש",
	NS_PROJECT        => $wgMetaNamespace,
	NS_PROJECT_TALK   => "שיחת_" . $wgMetaNamespace,
	NS_IMAGE          => "תמונה",
	NS_IMAGE_TALK     => "שיחת_תמונה",
	NS_MEDIAWIKI      => "מדיה_ויקי",
	NS_MEDIAWIKI_TALK => "שיחת_מדיה_ויקי",
	NS_TEMPLATE       => "תבנית",
	NS_TEMPLATE_TALK  => "שיחת_תבנית",
	NS_HELP           => "עזרה",
	NS_HELP_TALK      => "שיחת_עזרה",
	NS_CATEGORY       => "קטגוריה",
	NS_CATEGORY_TALK  => "שיחת_קטגוריה",
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsHe = array(
	"ללא", "קבוע משמאל", "קבוע מימין", "צף משמאל", "צף מימין"
);

/* private */ $wgSkinNamesHe = array(
	'standard' => "רגיל",
	'nostalgia' => "נוסטלגי",
	'cologneblue' => "מים כחולים",
	'davinci' => "דה-וינצ'י",
	'mono' => 'Mono',
	'monobook' => 'MonoBook',
	'myskin' => 'MySkin',
	'chick' => 'Chick'
) + $wgSkinNamesEn;

/* private */ $wgBookstoreListHe = array(
	"מיתוס" => "http://www.mitos.co.il/ ",
	"iBooks" => "http://www.ibooks.co.il/",
	"Barnes & Noble" => "http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesHe.php');
}

class LanguageHe extends LanguageUtf8 {

	function getDefaultUserOptions () {
		$opt = Language::getDefaultUserOptions();
		$opt["quickbar"]=2;
		return $opt;
	}

	function getBookstoreList() {
		global $wgBookstoreListHe ;
		return $wgBookstoreListHe ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesHe;
		return $wgNamespaceNamesHe;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsHe;
		return $wgQuickbarSettingsHe;
	}

	function getSkinNames() {
		global $wgSkinNamesHe;
		return $wgSkinNamesHe;
	}

	function getMessage( $key ) {
		global $wgAllMessagesHe;
		if(array_key_exists($key, $wgAllMessagesHe))
			return $wgAllMessagesHe[$key];
		else
			return parent::getMessage($key);
	}

	function isRTL() { return true; }

	function fallback8bitEncoding() { return "iso8859-8"; }

}

?>
