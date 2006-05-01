<?php
/**
 * @package MediaWiki
 * @subpackage Language
 */

$wgNamespaceNamesCsb = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Specjalnô',
	NS_MAIN             => '',
	NS_TALK             => 'Diskùsëjô',
	NS_USER             => 'Brëkòwnik',
	NS_USER_TALK        => 'Diskùsëjô_brëkòwnika',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => 'Diskùsëjô_' . $wgMetaNamespace,
	NS_IMAGE            => 'Òbrôzk',
	NS_IMAGE_TALK       => 'Diskùsëjô_òbrôzków',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Diskùsëjô_MediaWiki',
	NS_TEMPLATE         => 'Szablóna',
	NS_TEMPLATE_TALK    => 'Diskùsëjô_Szablónë',
	NS_HELP             => 'Pòmòc',
	NS_HELP_TALK        => 'Diskùsëjô_Pòmòcë',
	NS_CATEGORY         => 'Kategòrëjô',
	NS_CATEGORY_TALK    => 'Diskùsëjô_Kategòrëji'
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesCsb.php');
}

require_once( 'LanguageUtf8.php' );
class LanguageCsb extends LanguageUtf8 {
	function getNamespaces() {
		global $wgNamespaceNamesCsb;
		return $wgNamespaceNamesCsb;
	}

	function getMessage( $key ) {
		global $wgAllMessagesCsb;
		if( isset( $wgAllMessagesCsb[$key] ) ) {
			return $wgAllMessagesCsb[$key];
		} else {
			return parent::getMessage( $key );
		}
	}
}
?>
