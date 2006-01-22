<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
#
# Nederlands localisation for MediaWiki
#

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesNl = array(
	NS_MEDIA			=> "Media",
	NS_SPECIAL			=> "Speciaal",
	NS_MAIN				=> "",
	NS_TALK				=> "Overleg",
	NS_USER				=> "Gebruiker",
	NS_USER_TALK		=> "Overleg_gebruiker",
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> "Overleg_$wgMetaNamespace",
	NS_IMAGE			=> "Afbeelding",
	NS_IMAGE_TALK		=> "Overleg_afbeelding",
	NS_MEDIAWIKI		=> "MediaWiki",
	NS_MEDIAWIKI_TALK	=> "Overleg_MediaWiki",
	NS_TEMPLATE			=> "Sjabloon",
	NS_TEMPLATE_TALK	=> "Overleg_sjabloon",
	NS_HELP				=> "Help",
	NS_HELP_TALK		=> "Overleg_help",
	NS_CATEGORY			=> "Categorie",
	NS_CATEGORY_TALK	=> "Overleg_categorie"

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsNl = array(
 "Uitgeschakeld", "Links vast", "Rechts vast", "Links zwevend"
);

/* private */ $wgSkinNamesNl = array(
	'standard' => "Standaard",
	'nostalgia' => "Nostalgie",
	'cologneblue' => "Keuls blauw",
) + $wgSkinNamesEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesNl.php');
}

class LanguageNl extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesNl;
		return $wgNamespaceNamesNl;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsNl;
		return $wgQuickbarSettingsNl;
	}

	function getSkinNames() {
		global $wgSkinNamesNl;
		return $wgSkinNamesNl;
	}

	function timeBeforeDate( $format ) {
		return false;
	}

	function timeDateSeparator( $format ) {
		return ' ';
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function getMessage( $key ) {
		global $wgAllMessagesNl;
		if( isset( $wgAllMessagesNl[$key] ) ) {
			return $wgAllMessagesNl[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr( $this->commafy( $number ), '.,', ',.' );
	}
}

?>
