<?php
/**
  * Language file for Basque (Euskara)
  * Inherit from english
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesEu = array(
	NS_MEDIA			=> 'Media',
	NS_SPECIAL			=> 'Aparteko',
	NS_MAIN				=> '',
	NS_TALK				=> 'Eztabaida',
	NS_USER				=> 'Lankide',
	NS_USER_TALK		=> 'Lankide_eztabaida',
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> $wgMetaNamespace.'_eztabaida',
	NS_IMAGE			=> 'Irudi',
	NS_IMAGE_TALK		=> 'Irudi_eztabaida',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'MediaWiki_eztabaida',
	NS_TEMPLATE			=> 'Txantiloi',
	NS_TEMPLATE_TALK	=> 'Txantiloi_eztabaida',

	NS_CATEGORY			=> 'Kategoria',
	NS_CATEGORY_TALK	=> 'Kategoria_eztabaida',
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsEu = array(
	'Ezein ere', 'Eskuinean', 'Ezkerrean', 'Ezkerrean mugikor'
);

/* private */ $wgSkinNamesEu = array(
	'standard'		=> 'Lehenetsia',
	'nostalgia'		=> 'Nostalgia',
	'cologneblue'	=> 'Cologne Blue',
	'smarty'		=> 'Paddington',
	'montparnasse'	=> 'Montparnasse'
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesEu.php');
}

class LanguageEu extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesEu;
		return $wgNamespaceNamesEu;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsEu;
		return $wgQuickbarSettingsEu;
	}

	function getSkinNames() {
		global $wgSkinNamesEu;
		return $wgSkinNamesEu;
	}

	function getMessage( $key ) {
		global $wgAllMessagesEu;
		if( isset( $wgAllMessagesEu[$key] ) ) {
			return $wgAllMessagesEu[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

}

?>
