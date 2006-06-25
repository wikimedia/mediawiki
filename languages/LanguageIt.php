<?php
/** Italian (Italiano)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesIt.php');
}

class LanguageIt extends LanguageUtf8 {
	private $mMessagesIt, $mNamespaceNamesIt = null;
	
	private $mQuickbarSettingsIt = array(
		'Nessuno', 'Fisso a sinistra', 'Fisso a destra', 'Fluttuante a sinistra'
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesIt;
		$this->mMessagesIt =& $wgAllMessagesIt;

		global $wgMetaNamespace;
		$this->mNamespaceNamesIt = array(
			NS_MEDIA            => 'Media',
			NS_SPECIAL          => 'Speciale',
			NS_MAIN             => '',
			NS_TALK             => 'Discussione',
			NS_USER             => 'Utente',
			NS_USER_TALK        => 'Discussioni_utente',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => 'Discussioni_' . $wgMetaNamespace,
			NS_IMAGE            => 'Immagine',
			NS_IMAGE_TALK       => 'Discussioni_immagine',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'Discussioni_MediaWiki',
			NS_TEMPLATE         => 'Template',
			NS_TEMPLATE_TALK    => 'Discussioni_template',
			NS_HELP             => 'Aiuto',
			NS_HELP_TALK        => 'Discussioni_aiuto',
			NS_CATEGORY         => 'Categoria',
			NS_CATEGORY_TALK    => 'Discussioni_categoria'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesIt + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsIt;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesIt[$key] ) ) {
			return $this->mMessagesIt[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesIt;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	/**
	 * Italian numeric format is 201.511,17
	 */
	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

}

?>
