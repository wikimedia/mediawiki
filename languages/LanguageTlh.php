<?php
/** Klingon (tlhIngan-Hol)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesTlh = array(
	NS_MEDIA            => "Doch",
	NS_SPECIAL          => "le'",
	NS_MAIN             => "",
	NS_TALK             => "ja'chuq",
	NS_USER             => "lo'wI'",
	NS_USER_TALK        => "lo'wI'_ja'chuq",
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . "_ja'chuq",
	NS_IMAGE            => "nagh_beQ",
	NS_IMAGE_TALK       => "nagh_beQ_ja'chuq",
	NS_MEDIAWIKI        => "MediaWiki",
	NS_MEDIAWIKI_TALK   => "MediaWiki_ja'chuq",
	NS_TEMPLATE         => "chen'ay'",
	NS_TEMPLATE_TALK    => "chen'ay'_ja'chuq",
	NS_HELP             => "QaH",
	NS_HELP_TALK        => "QaH_ja'chuq",
	NS_CATEGORY         => "Segh",
	NS_CATEGORY_TALK    => "Segh_ja'chuq"
) + $wgNamespaceNamesEn;

class LanguageTlh extends LanguageUtf8 {
	function getNamespaces() {
		global $wgNamespaceNamesTlh;
		return $wgNamespaceNamesTlh;
	}
}

?>
