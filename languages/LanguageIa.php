<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

/* private */ $wgNamespaceNamesIa = array(
	NS_MEDIA          => "Media",
	NS_SPECIAL        => "Special",
	NS_MAIN           => "",
	NS_TALK           => "Discussion",
	NS_USER           => "Usator",
	NS_USER_TALK      => "Discussion_Usator",
	NS_PROJECT        => $wgMetaNamespace,
	NS_PROJECT_TALK   => "Discussion_". $wgMetaNamespace,
	NS_IMAGE          => "Imagine",
	NS_IMAGE_TALK     => "Discussion_Imagine",
	NS_MEDIAWIKI      => "MediaWiki",
	NS_MEDIAWIKI_TALK => "Discussion_MediaWiki",
	NS_TEMPLATE       => "Template",
	NS_TEMPLATE_TALK  => "Template_talk"
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsIa = array(
	"Necun", "Fixe a sinistra", "Fixe a dextera", "Flottante a sinistra"
);

/* private */ $wgSkinNamesIa = array(
	'cologneblue' => "Blau Colonia",
) + $wgSkinNamesEn;


if (!$wgCachedMessageArrays) {
	require_once('MessagesIa.php');
}

require_once( "LanguageUtf8.php" );

class LanguageIa extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesIa;
		return $wgNamespaceNamesIa;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsIa;
		return $wgQuickbarSettingsIa;
	}

	function getSkinNames() {
		global $wgSkinNamesIa;
		return $wgSkinNamesIa;
	}

	function getMessage( $key ) {
		global $wgAllMessagesIa;
		if( isset( $wgAllMessagesIa[$key] ) ) {
			return $wgAllMessagesIa[$key];
		} else {
			return parent::getMessage( $key );
		}
	}


}

?>
