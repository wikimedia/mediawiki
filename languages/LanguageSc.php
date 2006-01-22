<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesSc = array(
	NS_SPECIAL         => 'Speciale',
	NS_MAIN            => '',
	NS_TALK            => 'Contièndha',
	NS_USER            => 'Utente',
	NS_USER_TALK       => 'Utente_discussioni',
	NS_PROJECT         => $wgMetaNamespace,
	NS_PROJECT_TALK    => $wgMetaNamespace . '_discussioni',
	NS_IMAGE           => 'Immàgini',
	NS_IMAGE_TALK      => 'Immàgini_contièndha'
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsSc = array(
	"Nessuno", "Fisso a sinistra", "Fisso a destra", "Fluttuante a sinistra"
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesSc.php');
}

class LanguageSc extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesSc;
		return $wgNamespaceNamesSc;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsSc;
		return $wgQuickbarSettingsSc;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function getMessage( $key ) {
		global $wgAllMessagesSc;
		if(array_key_exists($key, $wgAllMessagesSc))
			return $wgAllMessagesSc[$key];
		else
			return parent::getMessage($key);
	}

}

?>
