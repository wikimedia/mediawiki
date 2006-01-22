<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
require_once( "LanguageUtf8.php" );
require_once( "LanguageZh_cn.php" );

/* private */ $wgNamespaceNamesZh_tw = array(
	NS_MEDIA            => "媒體",
	NS_SPECIAL          => "特殊",
	NS_MAIN             => "",
	NS_TALK             => "討論",
	NS_USER             => "用戶",
	NS_USER_TALK        => "用戶討論",
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . "討論",
	NS_IMAGE            => "圖像",
	NS_IMAGE_TALK       => "圖像討論",
	NS_MEDIAWIKI        => "媒體維基",
	NS_MEDIAWIKI_TALK   => "媒體維基討論",
	NS_TEMPLATE         => "樣板",
	NS_TEMPLATE_TALK    => "樣板討論",
	NS_HELP             => "幫助",
	NS_HELP_TALK        => "幫助討論",
	NS_CATEGORY         => "分類",
	NS_CATEGORY_TALK    => "分類討論"
);

/* private */ $wgQuickbarSettingsZh_tw = array(
        "無", /* "None" */
	"左側固定", /* "Fixed left" */
	"右側固定", /* "Fixed right" */
	"左側漂移" /* "Floating left" */
);

/* private */ $wgSkinNamesZh_tw = array(
        "標準",/* "Standard" */
	"懷舊",/* "Nostalgia" */
	"科隆香水藍" /* "Cologne Blue" */
) + $wgSkinNamesEn;

/* private */ $wgBookstoreListZh_tw = array(
	"博客來書店" => "http://www.books.com.tw/exep/openfind_book_keyword.php?cat1=4&key1=$1",
	"三民書店" => "http://www.sanmin.com.tw/page-qsearch.asp?ct=search_isbn&qu=$1",
	"天下書店" => "http://www.cwbook.com.tw/cw/TS.jsp?schType=product.isbn&schStr=$1",
	"新絲書店" => "http://www.silkbook.com/function/Search_List_Book.asp?item=5&text=$1"
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesZh_tw.php');
}

class LanguageZh_tw extends LanguageZh_cn {
	function getBookstoreList () {
		global $wgBookstoreListZh_tw ;
		return $wgBookstoreListZh_tw ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesZh_tw;
		return $wgNamespaceNamesZh_tw;
	}


	function getNsIndex( $text ) {
		global $wgNamespaceNamesZh_tw;

		foreach ( $wgNamespaceNamesZh_tw as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		# Aliases
		if ( 0 == strcasecmp( "對話", $text ) ) { return 1; }
		if ( 0 == strcasecmp( "用戶對話", $text ) ) { return 3; }
		if ( 0 == strcasecmp( "維基百科對話", $text ) ) { return 5; }
		if ( 0 == strcasecmp( "圖像對話", $text ) ) { return 7; }
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsZh_tw;
		return $wgQuickbarSettingsZh_tw;
	}

	function getSkinNames() {
		global $wgSkinNamesZh_tw;
		return $wgSkinNamesZh_tw;
	}

	function getMessage( $key ) {
		global $wgAllMessagesZh_tw;
		if(array_key_exists($key, $wgAllMessagesZh_tw))
			return $wgAllMessagesZh_tw[$key];
		else
			return parent::getMessage( $key );
	}

}


?>
