<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
# Kalmyk stub localization;

require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesXal = array(
	NS_MEDIA            => 'Аһар',
	NS_SPECIAL          => 'Көдлхнə',
	NS_MAIN             => '',
	NS_TALK             => 'Ухалвр',
	NS_USER             => 'Орлцач',
	NS_USER_TALK        => 'Орлцачна_тускар_ухалвр',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . '_тускар_ухалвр',
	NS_IMAGE            => 'Зург',
	NS_IMAGE_TALK       => 'Зургин_тускар_ухалвр',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_тускар_ухалвр',
	NS_TEMPLATE         => 'Зура',
	NS_TEMPLATE_TALK    => 'Зуран_тускар_ухалвр',
	NS_HELP             => 'Цəəлһлһн',
	NS_HELP_TALK        => 'Цəəлһлһин_тускар_ухалвр',
	NS_CATEGORY         => 'Янз',
	NS_CATEGORY_TALK    => 'Янзин_тускар_ухалвр',
) + $wgNamespaceNamesEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesXal.php');
}

class LanguageXal extends LanguageUtf8 {
	function getNamespaces() {
		global $wgNamespaceNamesXal;
		return $wgNamespaceNamesXal;
	}

	function getMessage( $key ) {
		global $wgAllMessagesXal;
		return isset($wgAllMessagesXal[$key]) ? $wgAllMessagesXal[$key] : parent::getMessage($key);
	}

	function fallback8bitEncoding() {
		return "windows-1251";
	}

}
?>
