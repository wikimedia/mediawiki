<?php
/** Indonesian (Bahasa Indonesia)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesId.php');
}

class LanguageId extends LanguageUtf8 {
	private $mMessagesId, $mNamespaceNamesId, $mNamespaceAlternatesId = null;

	private $mQuickbarSettingsId = array(
		'Tidak ada', 'Tetap sebelah kiri', 'Tetap sebelah kanan', 'Mengambang sebelah kiri'
	);
	
	private $mSkinNamesId = array(
		'standard'    => 'Standar',
	);
	
	private $mBookstoreListId = array(
		'Gramedia Cyberstore (via Google)' => 'http://www.google.com/search?q=%22ISBN+:+$1%22+%22product_detail%22+site:www.gramediacyberstore.com+OR+site:www.gramediaonline.com+OR+site:www.kompas.com&hl=id',
		'Bhinneka.com bookstore' => 'http://www.bhinneka.com/Buku/Engine/search.asp?fisbn=$1',
	);
	
	function __construct() {
		parent::__construct();

		global $wgAllMessagesId;
		$this->mMessagesId =& $wgAllMessagesId;

		global $wgMetaNamespace;
		$this->mNamespaceNamesId = array(
			NS_MEDIA            => 'Media',
			NS_SPECIAL          => 'Istimewa',
			NS_MAIN             => '',
			NS_TALK             => 'Bicara',
			NS_USER             => 'Pengguna',
			NS_USER_TALK        => 'Bicara_Pengguna',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => 'Pembicaraan_' . $wgMetaNamespace,
			NS_IMAGE            => 'Gambar',
			NS_IMAGE_TALK       => 'Pembicaraan_Gambar',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'Pembicaraan_MediaWiki',
			NS_TEMPLATE         => 'Templat',
			NS_TEMPLATE_TALK    => 'Pembicaraan_Templat',
			NS_HELP             => 'Bantuan',
			NS_HELP_TALK        => 'Pembicaraan_Bantuan',
			NS_CATEGORY         => 'Kategori',
			NS_CATEGORY_TALK    => 'Pembicaraan_Kategori'
		);

		# For backwards compatibility: some talk namespaces were
		# changed in 1.4.4 from their previous values, here:
		$this->mNamespaceAlternatesId = array(
			NS_IMAGE_TALK       => 'Gambar_Pembicaraan',
			NS_MEDIAWIKI_TALK   => 'MediaWiki_Pembicaraan',
			NS_TEMPLATE_TALK    => 'Templat_Pembicaraan',
			NS_HELP_TALK        => 'Bantuan_Pembicaraan',
			NS_CATEGORY_TALK    => 'Kategori_Pembicaraan'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesId + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsId;
	}

	function getSkinNames() {
		return $this->mSkinNamesId + parent::getSkinNames();
	}

	function getDateFormats() {
		return false;
	}

	function getBookstoreList() {
		return $this->mBookstoreListId;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesId[$key] ) ) {
			return $this->mMessagesId[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesId;
	}

	function getNsIndex( $text ) {
		foreach ( $this->getNamespaces() as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		foreach ( $this->mNamespaceAlternatesId as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}

	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

}

?>
