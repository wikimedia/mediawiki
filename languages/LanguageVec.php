<?php
/** Venitian ( Vèneto )
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageIt.php' );

/* private */ $wgNamespaceNamesVec = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciale',
	NS_MAIN             => '',
	NS_TALK             => 'Discussion',
	NS_USER             => 'Utente',
	NS_USER_TALK        => 'Discussion_utente',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => 'Discussion_'.$wgMetaNamespace,
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

/* private */ $wgQuickbarSettingsIt = array(
	'Nessun', 'Fisso a sinistra', 'Fisso a destra', 'Fluttuante a sinistra'
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesVec.php');
}

class LanguageVec extends LanguageIt {
	#FIXME: inherit almost everything for now

	function getNamespaces() {
		global $wgNamespaceNamesVec;
		return $wgNamespaceNamesVec;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsVec;
		return $wgQuickbarSettingsVec;
	}

	function getMessage( $key ) {
		global $wgAllMessagesVec;
		if(array_key_exists($key, $wgAllMessagesVec))
			return $wgAllMessagesVec[$key];
		else
			return parent::getMessage($key);
	}
}
?>
