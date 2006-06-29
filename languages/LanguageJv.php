<?php
/** Javanese (Basa Jawa)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 * @author Niklas Laxström
 *
 * @copyright Copyright © 2006, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesJv.php');
}

class LanguageJv extends LanguageUtf8 {
	private $mMessagesJv, $mNamespaceNamesJv, $mNamespaceAlternatesJv = null;

	private $mQuickbarSettingsJv = array(
		'Ora ana', 'Tetep sisih kiwa', 'Tetep sisih tengen', 'Ngambang sisih kiwa'
	);
	
	private $mSkinNamesJv = array(
		'standard'    => "Standar",
	);

	private $mBookstoreListJv = array(
		'Gramedia Cyberstore (via Google)' => 'http://www.google.com/search?q=%22ISBN+:+$1%22+%22product_detail%22+site:www.gramediacyberstore.com+OR+site:www.gramediaonline.com+OR+site:www.kompas.com&hl=id',
		'Bhinneka.com bookstore' => 'http://www.bhinneka.com/Buku/Engine/search.asp?fisbn=$1',
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesJv;
		$this->mMessagesJv =& $wgAllMessagesJv;

		global $wgMetaNamespace;
		$this->mNamespaceNamesJv = array(
			NS_MEDIA            => 'Media',
			NS_SPECIAL          => 'Astamiwa',
			NS_MAIN             => '',
			NS_TALK             => 'Dhiskusi',
			NS_USER             => 'Panganggo',
			NS_USER_TALK        => 'Dhiskusi_Panganggo',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => 'Dhiskusi_' . $wgMetaNamespace,
			NS_IMAGE            => 'Gambar',
			NS_IMAGE_TALK       => 'Dhiskusi_Gambar',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'Dhiskusi_MediaWiki',
			NS_TEMPLATE         => 'Cithakan',
			NS_TEMPLATE_TALK    => 'Dhiskusi_Cithakan',
			NS_HELP             => 'Pitulung',
			NS_HELP_TALK        => 'Dhiskusi_Pitulung',
			NS_CATEGORY         => 'Kategori',
			NS_CATEGORY_TALK    => 'Dhiskusi_Kategori'
		);

		$this->mNamespaceAlternatesJv = array(
			NS_IMAGE_TALK       => 'Gambar_Dhiskusi',
			NS_MEDIAWIKI_TALK   => 'MediaWiki_Dhiskusi',
			NS_TEMPLATE_TALK    => 'Cithakan_Dhiskusi',
			NS_HELP_TALK        => 'Pitulung_Dhiskusi',
			NS_CATEGORY_TALK    => 'Kategori_Dhiskusi'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesJv + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsJv;
	}

	function getSkinNames() {
		return $this->mSkinNamesJv + parent::getSkinNames();
	}

	function getBookstoreList() {
		return $this->mBookstoreListJv + parent::getBookstoreList();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesJv[$key] ) ) {
			return $this->mMessagesJv[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesJv;
	}

	function getNsIndex( $text ) {

		foreach ( $this->getNamespaces() as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		foreach ( $this->mNamespaceAlternatesJv as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}

		return false;
	}

}

?>
