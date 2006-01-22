<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
# Ossetic stub localization; default to Russian instead of English.
# See language.txt

require_once( "LanguageRu.php" );

/* private */ $wgNamespaceNamesOs = array(
	NS_MEDIA            => 'Media', //чтоб не писать "Мультимедия"
	NS_SPECIAL          => 'Сæрмагонд',
	NS_MAIN             => '',
	NS_TALK             => 'Дискусси',
	NS_USER             => 'Архайæг',
	NS_USER_TALK        => 'Архайæджы_дискусси',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => 'Дискусси_'+$wgMetaNamespace,
	NS_IMAGE            => 'Ныв',
	NS_IMAGE_TALK       => 'Нывы_тыххæй_дискусси',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Дискусси_MediaWiki',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблоны_тыххæй_дискусси',
	NS_HELP             => 'Æххуыс',
	NS_HELP_TALK        => 'Æххуысы_тыххæй_дискусси',
	NS_CATEGORY         => 'Категори',
	NS_CATEGORY_TALK    => 'Категорийы_тыххæй_дискусси',
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsOs = array(
	'Ма равдис', 'Галиуырдыгæй', 'Рахизырдыгæй', 'Рахизырдыгæй ленккæнгæ'
 );

/* private */ $wgSkinNamesOs = array(
	'standard' => 'Стандартон',
	'nostalgia' => 'Æнкъард',
	'cologneblue' => 'Кёльны æрхæндæг',
	'davinci' => 'Да Винчи',
	'mono' => 'Моно',
	'monobook' => 'Моно-чиныг',
	'myskin' => 'Мæхи',
	'chick' => 'Карк'
 ) + $wgSkinNamesEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesOs.php');
}

class LanguageOs extends LanguageRu {
	function LanguageOs() {
		global $wgNamespaceNamesOs, $wgMetaNamespace;
		LanguageUtf8::LanguageUtf8();
	}

	function getNamespaces() {
		global $wgNamespaceNamesOs;
		return $wgNamespaceNamesOs;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsOs;
		return $wgQuickbarSettingsOs;
	}

	function getSkinNames() {
		global $wgSkinNamesOs;
		return $wgSkinNamesOs;
	}

	function getDateFormats() {
		global $wgDateFormatsRu;
		return $wgDateFormatsRu;
	}

	function getMessage( $key ) {
		global $wgAllMessagesOs;
		return isset($wgAllMessagesOs[$key]) ? $wgAllMessagesOs[$key] : parent::getMessage($key);
	}

	function fallback8bitEncoding() {
		return "windows-1251";
	}

	function formatNum( $number, $year = false ) {
		return !$year ? strtr($number, '.,', ', ' ) : $number;
	}

}
?>
