<?php
/** Limburgian (Limburgs)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesLi.php');
}

class LanguageLi extends LanguageUtf8 {
	private $mMessagesLi, $mNamespaceNamesLi = null;

	private $mQuickbarSettingsLi = array(
		'Oetgesjakeld', 'Links vas', 'Rechts vas', 'Links zwevend'
	);
	
	private $mSkinNamesLi = array(
		'standard' => 'Standaard',
		'nostalgia' => 'Nostalgie',
		'cologneblue' => 'Keuls blauw',
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesLi;
		$this->mMessagesLi =& $wgAllMessagesLi;

		global $wgMetaNamespace;
		$this->mNamespaceNamesLi = array(
			NS_MEDIA          => 'Media',
			NS_SPECIAL        => 'Speciaal',
			NS_MAIN           => '',
			NS_TALK           => 'Euverlik',
			NS_USER           => 'Gebroeker',
			NS_USER_TALK      => 'Euverlik_gebroeker',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => 'Euverlik_' . $wgMetaNamespace,
			NS_IMAGE          => 'Aafbeilding',
			NS_IMAGE_TALK     => 'Euverlik_afbeelding',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'Euverlik_MediaWiki',
			NS_TEMPLATE       => 'Sjabloon',
			NS_TEMPLATE_TALK  => 'Euverlik_sjabloon',
			NS_HELP           => 'Help',
			NS_HELP_TALK      => 'Euverlik_help',
			NS_CATEGORY       => 'Kategorie',
			NS_CATEGORY_TALK  => 'Euverlik_kategorie'
		);
	}

	function getNamespaces() {
		return $this->mNamespaceNamesLi + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsLi;
	}

	function getSkinNames() {
		return $this->mSkinNamesLi + parent::getSkinNames();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesLi[$key] ) ) {
			return $this->mMessagesLi[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesLi;
	}

	function timeBeforeDate( ) {
		return false;
	}

	function timeDateSeparator( $format ) {
		return ' ';
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

}
?>
