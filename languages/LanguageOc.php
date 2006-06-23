<?php
/** Occitan (Occitan)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( "LanguageUtf8.php" );

if (!$wgCachedMessageArrays) {
	require_once('MessagesOc.php');
}

class LanguageOc extends LanguageUtf8 {
	private $mMessagesOc, $mNamespaceNamesOc;
	
	private $mQuickbarSettingsOc = array(
		'Cap', 'Esquèr', 'Drech', 'Flotejant a esquèr'
	);
	
	private $mSkinNamesOc = array(
		'standard' => 'Normal',
		'nostalgia' => 'Nostalgia',
		'cologneblue' => 'Còlonha Blau',
	);
	
	private $mBookstoreListOc = array(
		'Amazon.fr' => 'http://www.amazon.fr/exec/obidos/ISBN=$1'
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesOc;
		$this->mMessagesOc =& $wgAllMessagesOc;

		global $wgMetaNamespace;
		$this->mNamespaceNamesOc = array(
			NS_SPECIAL        => 'Especial',
			NS_MAIN           => '',
			NS_TALK           => 'Discutir',
			NS_USER           => 'Utilisator',
			NS_USER_TALK      => 'Discutida_Utilisator',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => 'Discutida_'.$wgMetaNamespace,
			NS_IMAGE          => 'Imatge',
			NS_IMAGE_TALK     => 'Discutida_Imatge',
			NS_MEDIAWIKI      => 'Mediaòiqui',
			NS_MEDIAWIKI_TALK => 'Discutida_Mediaòiqui',
			NS_TEMPLATE       => 'Modèl',
			NS_TEMPLATE_TALK  => 'Discutida_Modèl',
			NS_HELP           => 'Ajuda',
			NS_HELP_TALK      => 'Discutida_Ajuda',
			NS_CATEGORY       => 'Categoria',
			NS_CATEGORY_TALK  => 'Discutida_Categoria',
		);
	}

	function getBookstoreList () {
		return $this->mBookstoreListOc;
	}

	function getNamespaces() {
		return $this->mNamespaceNamesOc + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsOc;
	}

	function getSkinNames() {
		return $this->mSkinNamesOc + parent::getSkinNames();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesOc[$key] ) ) {
			return $this->mMessagesOc[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesOc;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function timeBeforeDate() {
		return false;
	}

	function timeDateSeparator( $format ) {
		return ' à ';
	}

}

?>
