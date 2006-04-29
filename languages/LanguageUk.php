<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesUk = array(
	NS_MEDIA            => 'Медіа',
	NS_SPECIAL          => 'Спеціальні',
	NS_MAIN             => '',
	NS_TALK             => 'Обговорення',
	NS_USER             => 'Користувач',
	NS_USER_TALK        => 'Обговорення_користувача',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => 'Обговорення_' . $wgMetaNamespace,
	NS_IMAGE            => 'Зображення',
	NS_IMAGE_TALK       => 'Обговорення_зображення',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Обговорення_MediaWiki',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Обговорення_шаблону',
	NS_HELP             => 'Довідка',
	NS_HELP_TALK        => 'Обговорення_довідки',
	NS_CATEGORY         => 'Категорія',
	NS_CATEGORY_TALK    => 'Обговорення_категорії'
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsUk = array(
	"Не показувати панель", "Фіксована зліва", "Фіксована справа", "Плаваюча зліва"
);

/* private */ $wgSkinNamesUk = array(
	'standard' => "Стандартне",
	'nostalgia' => "Ностальгія",
	'cologneblue' => "Кельнське Синє"
) + $wgSkinNamesEn;


/* private */ $wgDateFormatsUk = array(
#	"Немає значення",
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesUk.php');
}

class LanguageUk extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesUk;
		return $wgNamespaceNamesUk;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsUk;
		return $wgQuickbarSettingsUk;
	}

	function getSkinNames() {
		global $wgSkinNamesUk;
		return $wgSkinNamesUk;
	}

	function getDateFormats() {
		global $wgDateFormatsUk;
		return $wgDateFormatsUk;
	}

	function getMonthNameGen( $key ) {
		global $wgMonthNamesGenEn, $wgContLang;
		// see who called us and use the correct message function
		if( get_class( $wgContLang->getLangObj() ) == get_class( $this ) )
			return wfMsgForContent( $wgMonthNamesGenEn[$key-1] );
		else
			return wfMsg( $wgMonthNamesGenEn[$key-1] );
	}

	function getMessage( $key ) {
		global $wgAllMessagesUk;
		if( isset( $wgAllMessagesUk[$key] ) ) {
			return $wgAllMessagesUk[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function fallback8bitEncoding() {
		return "windows-1251";
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

}
?>