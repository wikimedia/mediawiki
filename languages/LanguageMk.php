<?php
/** Macedonian (Македонски)
 * @package MediaWiki
 * @subpackage Language
 */

/* private */ $wgNamespaceNamesMk = array(
	NS_MEDIA			=>	'Медија',
	NS_SPECIAL			=>	'Специјални',
	NS_MAIN				=>	'',
	NS_TALK				=>	'Разговор',
	NS_USER				=>	'Корисник',
	NS_USER_TALK			=>	'Корисник_разговор',
	NS_PROJECT			=>	$wgMetaNamespace,
	NS_PROJECT_TALK			=>	$wgMetaNamespace . '_разговор',
	NS_IMAGE			=>	'Слика',
	NS_IMAGE_TALK			=>	'Слика_разговор',
	NS_MEDIAWIKI			=>	'МедијаВики',
	NS_MEDIAWIKI_TALK		=>	'МедијаВики_разговор',
	NS_TEMPLATE			=>	'Шаблон',
	NS_TEMPLATE_TALK		=>	'Шаблон_разговор',
	NS_HELP				=>	'Помош',
	NS_HELP_TALK			=>	'Помош_разговор',
	NS_CATEGORY			=>	'Категорија',
	NS_CATEGORY_TALK		=>	'Категорија_разговор',
);


/* private */ $wgQuickbarSettingsMk = array(
	"Без мени", "Фиксирано лево", "Фиксирано десно", "Пловечко лево"
);

/* private */ $wgSkinNamesMk = array(
	'standard'			=>	'Класика',
	'nostalgia'			=>	'Носталгија',
	'cologneblue'			=>	'Келнско сино',
	'davinci'			=>	'ДаВинчи',
	'mono'				=>	'Моно',
	'monobook'			=>	'Monobook',
	'myskin'			=>	'Моја маска',
	'chick'				=>	'Шик'
) + $wgSkinNamesEn;

/* private */ $wgDateFormatsMk = array(
 "Без преференции",
 "Јануари 15, 2001",
 "15 Јануари 2001",
 "2001 Јануари 15",
 "2001-01-15"
);

/* private */ $wgMagicWordsMk = array(
	MAG_REDIRECT			=>	array( 0, '#redirect', '#пренасочување', '#види' ),
	MAG_NOTOC			=>	array( 0, '__NOTOC__', '__БЕЗСОДРЖИНА__' ),
	MAG_FORCETOC			=>	array( 0, '__FORCETOC__', '__СОСОДРЖИНА__' ),
	MAG_TOC				=>	array( 0, '__TOC__', '__СОДРЖИНА__' ),
	MAG_NOEDITSECTION		=>	array( 0, '__NOEDITSECTION__' , '__БЕЗ_УРЕДУВАЊЕ_НА_СЕКЦИИ__'),
	MAG_START			=>	array( 0, '__START__' , '__ПОЧЕТОК__' ),
	MAG_CURRENTMONTH		=>	array( 1, 'CURRENTMONTH', 'СЕГАШЕНМЕСЕЦ' ),
	MAG_CURRENTMONTHNAME		=>	array( 1, 'CURRENTMONTHNAME', 'СЕГАШЕНМЕСЕЦИМЕ' ),
	MAG_CURRENTMONTHNAMEGEN		=>	array( 1, 'CURRENTMONTHNAMEGEN', 'СЕГАШЕНМЕСЕЦИМЕРОД' ),
	MAG_CURRENTMONTHABBREV		=>	array( 1, 'CURRENTMONTHABBREV', 'СЕГАШЕНМЕСЕЦСКР' ),
	MAG_CURRENTDAY			=>	array( 1, 'CURRENTDAY', 'СЕГАШЕНДЕН' ),
	MAG_CURRENTDAYNAME		=>	array( 1, 'CURRENTDAYNAME', 'СЕГАШЕНДЕНИМЕ' ),
	MAG_CURRENTYEAR			=>	array( 1, 'CURRENTYEAR', 'СЕГАШНАГОДИНА' ),
	MAG_CURRENTTIME			=>	array( 1, 'CURRENTTIME', 'СЕГАШНОВРЕМЕ' ),
	MAG_NUMBEROFARTICLES		=>	array( 1, 'NUMBEROFARTICLES', 'БРОЈСТАТИИ' ),
	MAG_PAGENAME			=>	array( 1, 'PAGENAME', 'СТРАНИЦА' ),
	MAG_PAGENAMEE			=>	array( 1, 'PAGENAMEE', 'СТРАНИЦАИ' ),
	MAG_NAMESPACE			=>	array( 1, 'NAMESPACE', 'ИМЕПРОСТОР' ),
	MAG_SUBST			=>	array( 0, 'SUBST:', 'ЗАМЕСТ:' ),
	MAG_MSGNW			=>	array( 0, 'MSGNW:', 'ИЗВЕШТNW:' ),
	MAG_END				=>	array( 0, '', '__КРАЈ__' ),
	MAG_IMG_THUMBNAIL		=>	array( 1, 'thumbnail', 'thumb', 'мини' ),
	MAG_IMG_RIGHT			=>	array( 1, 'right', 'десно', 'д' ),
	MAG_IMG_LEFT			=>	array( 1, 'left', 'лево', 'л' ),
	MAG_IMG_NONE			=>	array( 1, 'none', 'н' ),
	MAG_IMG_WIDTH			=>	array( 1, "$1px", "$1пкс" , "$1п" ),
	MAG_IMG_CENTER			=>	array( 1, 'center', 'centre', 'центар', 'ц' ),
	MAG_IMG_FRAMED			=>	array( 1, 'framed', 'enframed', 'frame', 'рамка', 'ворамка' ),
	MAG_INT				=>	array( 0, 'INT:' ),
	MAG_SITENAME			=>	array( 1, 'SITENAME', 'ИМЕНАСАЈТ' ),
	MAG_NS				=>	array( 0, 'NS:' ),
	MAG_LOCALURL			=>	array( 0, 'LOCALURL:', 'ЛОКАЛНААДРЕСА:' ),
	MAG_LOCALURLE			=>	array( 0, 'LOCALURLE:', 'ЛОКАЛНААДРЕСАИ:' ),
	MAG_SERVER			=>	array( 0, 'SERVER', 'СЕРВЕР' ),
	MAG_GRAMMAR			=>	array( 0, 'GRAMMAR:', 'ГРАМАТИКА:' ),
	MAG_NOTITLECONVERT		=>	array( 0, '__NOTITLECONVERT__', '__NOTC__'),
	MAG_NOCONTENTCONVERT		=>	array( 0, '__NOCONTENTCONVERT__', '__NOCC__'),
	MAG_CURRENTWEEK			=>	array( 1, 'CURRENTWEEK', 'СЕГАШНАСЕДМИЦА'),
	MAG_CURRENTDOW			=>	array( 1, 'CURRENTDOW' ),
	MAG_REVISIONID			=>	array( 1, 'REVISIONID' ),
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesMk.php');
}

require_once( 'LanguageUtf8.php' );

class LanguageMk extends LanguageUtf8 {
	/**
	 * Exports $wgNamespaceNamesMk
	 * @return array
	 */
	function getNamespaces() {
		global $wgNamespaceNamesMk;
		return $wgNamespaceNamesMk;
	}

	/**
	 * Exports $wgQuickbarSettingsMk
	 * @return array
	 */
	function getQuickbarSettings() {
		global $wgQuickbarSettingsMk;
		return $wgQuickbarSettingsMk;
	}

	/**
	 * Exports $wgSkinNamesMk
	 * @return array
	 */
	function getSkinNames() {
		global $wgSkinNamesMk;
		return $wgSkinNamesMk;
	}

	/**
	 * Exports $wgDateFormatsMk
	 * @return array
	 */
	function getDateFormats() {
		global $wgDateFormatsMk;
		return $wgDateFormatsMk;
	}

	function getMessage( $key ) {
		global $wgAllMessagesMk;
		if ( isset( $wgAllMessagesMk[$key] ) ) {
			return $wgAllMessagesMk[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	/**
	 * Exports $wgMagicWordsMk
	 * @return array
	 */
	function getMagicWords() {
		global $wgMagicWordsMk;
		return $wgMagicWordsMk;
	}
}
?>
