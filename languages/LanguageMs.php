<?php
/** Malay (Bahasa Melayu)
 *
 * @package MediaWiki
 * @subpackage Language
 */

# This localisation is based on a file kindly donated by the folks at MIMOS
# http://www.asiaosc.org/enwiki/page/Knowledgebase_Home.html

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesMs.php');
}

class LanguageMs extends LanguageUtf8 {
	private $mMessagesMs, $mNamespaceNamesMs = null;

	private $mQuickbarSettingsMs = array(
		'Tiada', 'Tetap sebelah kiri', 'Tetap sebelah kanan', 'Berubah-ubah sebelah kiri'
	);
	
	function __construct() {
		parent::__construct();

		global $wgAllMessagesMs;
		$this->mMessagesMs =& $wgAllMessagesMs;

		global $wgMetaNamespace;
		$this->mNamespaceNamesMs = array(
			NS_MEDIA          => 'Media',
			NS_SPECIAL        => 'Istimewa', #Special
			NS_MAIN           => '',
			NS_TALK           => 'Perbualan',#Talk
			NS_USER           => 'Pengguna',#User
			NS_USER_TALK      => 'Perbualan_Pengguna',#User_talk
			NS_PROJECT        => $wgMetaNamespace,#Wikipedia
			NS_PROJECT_TALK   => 'Perbualan_' . $wgMetaNamespace,#Wikipedia_talk
			NS_IMAGE          => 'Imej',#Image
			NS_IMAGE_TALK     => 'Imej_Perbualan',#Image_talk
			NS_MEDIAWIKI      => 'MediaWiki',#MediaWiki
			NS_MEDIAWIKI_TALK => 'MediaWiki_Perbualan',#MediaWiki_talk
			NS_TEMPLATE       => 'Templat',#Template
			NS_TEMPLATE_TALK  => 'Perbualan_Templat',#Template_talk
			NS_CATEGORY       => 'Kategori',#Category
			NS_CATEGORY_TALK  => 'Perbualan_Kategori',#Category_talk
			NS_HELP           => 'Bantuan',#Help
			NS_HELP_TALK      => 'Perbualan_Bantuan' #Help_talk
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesMs + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsMs;
	}

	function getDateFormats() {
		return false;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesMs[$key] ) ) {
			return $this->mMessagesMs[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesMs;
	}

}

?>
