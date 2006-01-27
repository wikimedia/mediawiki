<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

# This localisation is based on a file kindly donated by the folks at MIMOS
# http://www.asiaosc.org/enwiki/page/Knowledgebase_Home.html

/* private */ $wgNamespaceNamesMs = array(
	NS_MEDIA          => "Media",
	NS_SPECIAL        => "Istimewa", #Special
	NS_MAIN           => "",
	NS_CATEGORY       => "Kategori",#Category
	NS_CATEGORY_TALK  => "Perbualan_Kategori",#Category_talk
	NS_HELP           => "Bantuan",#Help
	NS_HELP_TALK      => "Perbualan_Bantuan", #Help_talk
	NS_TALK           => "Perbualan", #Talk
	NS_USER           => "Pengguna", #User
	NS_USER_TALK      => "Perbualan_Pengguna", #User_talk
	NS_PROJECT        => "Wikipedia", #Wikipedia
	NS_PROJECT_TALK   => "Perbualan_Wikipedia", #Wikipedia_talk
	NS_IMAGE          => "Imej", #Image
	NS_IMAGE_TALK     => "Perbualan_Imej", #Image_talk
	NS_MEDIAWIKI      => "MediaWiki",
	NS_MEDIAWIKI_TALK => "Perbualan_MediaWiki", #MediaWiki_talk
	NS_TEMPLATE       => "Templat", #Template
	NS_TEMPLATE_TALK  => "Perbualan_Templat" #Template_talk
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsMs = array(
	"Tiada", "Kiri", "Kanan", "Mengapung ke kiri", "Mengapung ke kanan",
);

/* private */ $wgDateFormatsMs = array(
#	"Tiada kecenderungan", # "No preference",
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesMs.php');
}

require_once( "LanguageUtf8.php" );

class LanguageMs extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesMs;
		return $wgNamespaceNamesMs;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsMs;
		return $wgQuickbarSettingsMs;
	}

	function getDateFormats() {
		global $wgDateFormatsMs;
		return $wgDateFormatsMs;
	}

	function getMessage( $key ) {
		global $wgAllMessagesMs;
		if( isset( $wgAllMessagesMs[$key] ) ) {
			return $wgAllMessagesMs[$key];
		} else {
			return parent::getMessage( $key );
		}
	}
}

?>
