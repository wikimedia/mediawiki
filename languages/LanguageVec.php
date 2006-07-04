<?php
/** Venitian ( Vèneto )
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageIt.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesVec.php');
}

class LanguageVec extends LanguageIt {
	private $mMessagesVec, $mNamespaceNamesVec = null;

	private $mQuickbarSettingsVec = array(
		'Nessun', 'Fisso a sinistra', 'Fisso a destra', 'Fluttuante a sinistra'
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesVec;
		$this->mMessagesVec =& $wgAllMessagesVec;

		global $wgMetaNamespace;
		$this->mNamespaceNamesVec = array(		
			NS_MEDIA            => 'Media',
			NS_SPECIAL          => 'Speciale',
			NS_MAIN             => '',
			NS_TALK             => 'Discussion',
			NS_USER             => 'Utente',
			NS_USER_TALK        => 'Discussion_utente',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => 'Discussion_' . $wgMetaNamespace,
			NS_IMAGE            => 'Imagine',
			NS_IMAGE_TALK       => 'Discussion_imagine',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'Discussion_MediaWiki',
			NS_TEMPLATE         => 'Template',
			NS_TEMPLATE_TALK    => 'Discussion_template',
			NS_HELP             => 'Aiuto',
			NS_HELP_TALK        => 'Discussion_aiuto',
			NS_CATEGORY         => 'Categoria',
			NS_CATEGORY_TALK    => 'Discussion_categoria'
		);

	}

	function getFallbackLanguage() {
		return 'it';
	}

	function getNamespaces() {
		return $this->mNamespaceNamesVec + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsVec;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesVec[$key] ) ) {
			return $this->mMessagesVec[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesVec;
	}

}

?>
