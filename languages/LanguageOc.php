<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesOc = array(
	NS_SPECIAL        => "Especial",
	NS_MAIN           => "",
	NS_TALK           => "Discutir",
	NS_USER           => "Utilisator",
	NS_USER_TALK      => "Discutida_Utilisator",
	NS_PROJECT        => $wgMetaNamespace,
	NS_PROJECT_TALK   => 'Discutida_'.$wgMetaNamespace,
	NS_IMAGE          => "Imatge",
	NS_IMAGE_TALK     => "Discutida_Imatge",
	NS_MEDIAWIKI      => "Mediaòiqui",
	NS_MEDIAWIKI_TALK => "Discutida_Mediaòiqui",
	NS_TEMPLATE       => "Modèl",
	NS_TEMPLATE_TALK  => "Discutida_Modèl",
	NS_HELP => 'Ajuda',
	NS_HELP_TALK => 'Discutida_Ajuda',
	NS_CATEGORY => 'Categoria',
	NS_CATEGORY_TALK=> 'Discutida_Categoria',
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsOc = array(
	"Cap", "Esquèr", "Drech", "Flotejant a esquèr"
);

/* private */ $wgSkinNamesOc = array(
	'standard' => "Normal",
	'nostalgia' => "Nostalgia",
	'cologneblue' => "Còlonha Blau",
) + $wgSkinNamesEn;


/* private */ $wgBookstoreListOc = array(
	"Amazon.fr" => "http://www.amazon.fr/exec/obidos/ISBN=$1"
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesOc.php');
}

class LanguageOc extends LanguageUtf8{

	function getBookstoreList () {
		global $wgBookstoreListOc ;
		return $wgBookstoreListOc ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesOc;
		return $wgNamespaceNamesOc;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsOc;
		return $wgQuickbarSettingsOc;
	}

	function getSkinNames() {
		global $wgSkinNamesOc;
		return $wgSkinNamesOc;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function timeBeforeDate( $format ) {
		return false;
	}

	function timeDateSeparator( $format ) {
		return " à ";
	}

	function getMessage( $key ) {
		global $wgAllMessagesOc;
		if( isset( $wgAllMessagesOc[$key] ) ) {
			return $wgAllMessagesOc[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

}

?>
