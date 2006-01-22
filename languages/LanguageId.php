<?php

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesId = array(
	NS_MEDIA            => "Media",
	NS_SPECIAL          => "Istimewa",
	NS_MAIN             => "",
	NS_TALK             => "Bicara",
	NS_USER             => "Pengguna",
	NS_USER_TALK        => "Bicara_Pengguna",
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => "Pembicaraan_" . $wgMetaNamespace,
	NS_IMAGE            => "Gambar",
	NS_IMAGE_TALK       => "Pembicaraan_Gambar",
	NS_MEDIAWIKI        => "MediaWiki",
	NS_MEDIAWIKI_TALK   => "Pembicaraan_MediaWiki",
	NS_TEMPLATE         => "Templat",
	NS_TEMPLATE_TALK    => "Pembicaraan_Templat",
	NS_HELP             => "Bantuan",
	NS_HELP_TALK        => "Pembicaraan_Bantuan",
	NS_CATEGORY         => "Kategori",
	NS_CATEGORY_TALK    => "Pembicaraan_Kategori"
) + $wgNamespaceNamesEn;

# For backwards compatibility: some talk namespaces were
# changed in 1.4.4 from their previous values, here:
$wgNamespaceAlternatesId = array(
	NS_IMAGE_TALK       => "Gambar_Pembicaraan",
	NS_MEDIAWIKI_TALK   => "MediaWiki_Pembicaraan",
	NS_TEMPLATE_TALK    => "Templat_Pembicaraan",
	NS_HELP_TALK        => "Bantuan_Pembicaraan",
	NS_CATEGORY_TALK    => "Kategori_Pembicaraan"
);

/* private */ $wgQuickbarSettingsId = array(
	"Tidak ada", "Tetap sebelah kiri", "Tetap sebelah kanan", "Mengambang sebelah kiri"
);

/* private */ $wgSkinNamesId = array(
	'standard'    => "Standar",
) + $wgSkinNamesEn;

/* private */ $wgDateFormatsId = array();

/* private */ $wgBookstoreListId = array(
	# Local bookstores
	"Gramedia Cyberstore (via Google)" => 'http://www.google.com/search?q=%22ISBN+:+$1%22+%22product_detail%22+site:www.gramediacyberstore.com+OR+site:www.gramediaonline.com+OR+site:www.kompas.com&hl=id',
	"Bhinneka.com bookstore" => 'http://www.bhinneka.com/Buku/Engine/search.asp?fisbn=$1',

	//# Default (EN) Bookstores
	//"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	//"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	//"Barnes & Noble" => "http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	//"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
) + $wgBookstoreListEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesId.php');
}

class LanguageId extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListId;
		return $wgBookstoreListId;
	}

	function getNamespaces() {
		global $wgNamespaceNamesId;
		return $wgNamespaceNamesId;
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesId, $wgNamespaceAlternatesId;

		foreach ( $wgNamespaceNamesId as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		foreach ( $wgNamespaceAlternatesId as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}

		# For backwards compatibility
		global $wgNamespaceNamesEn;
		foreach ( $wgNamespaceNamesEn as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsId;
		return $wgQuickbarSettingsId;
	}

	function getSkinNames() {
		global $wgSkinNamesId;
		return $wgSkinNamesId;
	}

	function getDateFormats() {
		global $wgDateFormatsId;
		return $wgDateFormatsId;
	}

	function getMessage( $key ) {
		global $wgAllMessagesId;
		if( isset( $wgAllMessagesId[$key] ) ) {
			return $wgAllMessagesId[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function formatNum( $number, $year = false ) {
		return !$year ? strtr($this->commafy($number), '.,', ',.' ) : $number;
	}

}

?>
