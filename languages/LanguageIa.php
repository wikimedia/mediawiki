<?php
/** Interlingua (Interlingua)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesIa.php');
}

class LanguageIa extends LanguageUtf8 {
	private $mMessagesIa, $mNamespaceNamesIa = null;

	private $mQuickbarSettingsIa = array(
		'Nulle', 'Fixe a sinistra', 'Fixe a dextera', 'Flottante a sinistra'
	);
	
	private $mSkinNamesIa = array(
		'cologneblue' => 'Blau Colonia',
	);


	function __construct() {
		parent::__construct();

		global $wgAllMessagesIa;
		$this->mMessagesIa =& $wgAllMessagesIa;

		global $wgMetaNamespace;
		$this->mNamespaceNamesIa = array(
			NS_MEDIA          => 'Media',
			NS_SPECIAL        => 'Special',
			NS_MAIN           => '',
			NS_TALK           => 'Discussion',
			NS_USER           => 'Usator',
			NS_USER_TALK      => 'Discussion_Usator',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => 'Discussion_'. $wgMetaNamespace,
			NS_IMAGE          => 'Imagine',
			NS_IMAGE_TALK     => 'Discussion_Imagine',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'Discussion_MediaWiki',
			NS_TEMPLATE       => 'Patrono',
			NS_TEMPLATE_TALK  => 'Discussion_Patrono',
			NS_HELP           => 'Adjuta',
			NS_HELP_TALK      => 'Discussion_Adjuta',
			NS_CATEGORY       => 'Categoria',
			NS_CATEGORY_TALK  => 'Discussion_Categoria'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesIa + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsIa;
	}

	function getSkinNames() {
		return $this->mSkinNamesIa + parent::getSkinNames();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesIa[$key] ) ) {
			return $this->mMessagesIa[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesIa;
	}

}

?>
