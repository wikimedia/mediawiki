<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
# Udmurt stub localization; default to Russian instead of English.
# See language.txt

require_once( "LanguageRu.php" );

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( ' ', '_', $wgSitename );

/* private */ $wgNamespaceNamesUdm = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Панель',
	NS_MAIN             => '',
	NS_TALK             => 'Вераськон',
	NS_USER             => 'Викиавтор',
	NS_USER_TALK        => 'Викиавтор_сярысь_вераськон',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace+'_сярысь_вераськон',
	NS_IMAGE            => 'Суред',
	NS_IMAGE_TALK       => 'Суред_сярысь_вераськон',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_сярысь_вераськон',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблон_сярысь_вераськон',
	NS_HELP             => 'Валэктон',
	NS_HELP_TALK        => 'Валэктон_сярысь_вераськон',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категория_сярысь_вераськон',
) + $wgNamespaceNamesEn;


/* private */ $wgAllMessagesUdm = array(
'article' => 'Статья',
'createaccount' => 'выль вики-авторлэн регистрациез',
'edit' => 'тупатыны',
'hist' => 'история',
'history' => 'Бамлэн историез',
'history_short' => 'история',
'login' => 'Википедие пырон',
'mycontris' => 'мынам статьяосы',
'mytalk' => 'викиавтор сярысь вераськон',
'nstab-user' => 'викиавтор',
'preferences' => 'настройкаос',
);

class LanguageUdm extends LanguageRu {
	function LanguageUdm() {
		global $wgNamespaceNamesUdm, $wgMetaNamespace;
		LanguageUtf8::LanguageUtf8();
	}

	function getNamespaces() {
		global $wgNamespaceNamesUdm;
		return $wgNamespaceNamesUdm;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsRu;
		return $wgQuickbarSettingsRu;
	}

	function getSkinNames() {
		global $wgSkinNamesRu;
		return $wgSkinNamesRu;
	}

	function getDateFormats() {
		global $wgDateFormatsRu;
		return $wgDateFormatsRu;
	}

	function getMessage( $key ) {
		global $wgAllMessagesUdm;
		return isset($wgAllMessagesUdm[$key]) ? $wgAllMessagesUdm[$key] : parent::getMessage($key);
	}

	function fallback8bitEncoding() {
		return "windows-1251";
	}

	function formatNum( $number ) {
		global $wgTranslateNumerals;
		return $wgTranslateNumerals ? strtr($number, '.,', ', ' ) : $number;
	}

}
?>
