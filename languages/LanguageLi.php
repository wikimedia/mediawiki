<?php
/** Limburgish (Limburgs)
  *
  * @package MediaWiki
  * @subpackage Language
  */
/**
 * @access private
 */
$wgNamespaceNamesLi = array(
	NS_MEDIA			=> 'Media',
	NS_SPECIAL			=> 'Speciaal',
	NS_MAIN				=> '',
	NS_TALK				=> 'Euverlik',
	NS_USER				=> 'Gebroeker',
	NS_USER_TALK		=> 'Euverlik_gebroeker',
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> 'Euverlik_'.$wgMetaNamespace,
	NS_IMAGE			=> 'Aafbeilding',
	NS_IMAGE_TALK		=> 'Euverlik_afbeelding',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'Euverlik_MediaWiki',
	NS_TEMPLATE			=> 'Sjabloon',
	NS_TEMPLATE_TALK	=> 'Euverlik_sjabloon',
	NS_HELP				=> 'Help',
	NS_HELP_TALK		=> 'Euverlik_help',
	NS_CATEGORY			=> 'Kategorie',
	NS_CATEGORY_TALK	=> 'Euverlik_kategorie'

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsLi = array(
 "Oetgesjakeld", "Links vas", "Rechts vas", "Links zwevend"
);

/* private */ $wgSkinNamesLi = array(
	'standard' => "Standaard",
	'nostalgia' => "Nostalgie",
	'cologneblue' => "Keuls blauw",
) + $wgSkinNamesEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesLi.php');
}


require_once( "LanguageUtf8.php" );

class LanguageLi extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesLi;
		return $wgNamespaceNamesLi;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsLi;
		return $wgQuickbarSettingsLi;
	}

	function getSkinNames() {
		global $wgSkinNamesLi;
		return $wgSkinNamesLi;
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
		global $wgAllMessagesLi;
		if( isset( $wgAllMessagesLi[$key] ) ) {
			return $wgAllMessagesLi[$key];
		} else {
			return parent::getMessage( $key );
		}
	}
}
?>
