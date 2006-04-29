<?php
/** Bulgarian (Български)
 *
 * @package MediaWiki
 * @subpackage Language
 */

/* private */ $wgNamespaceNamesBg = array(
	NS_MEDIA            => 'Медия',
	NS_SPECIAL          => 'Специални',
	NS_MAIN             => '',
	NS_TALK             => 'Беседа',
	NS_USER             => 'Потребител',
	NS_USER_TALK        => 'Потребител_беседа',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . '_беседа',
	NS_IMAGE            => 'Картинка',
	NS_IMAGE_TALK       => 'Картинка_беседа',
	NS_MEDIAWIKI        => 'МедияУики',
	NS_MEDIAWIKI_TALK   => 'МедияУики_беседа',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблон_беседа',
	NS_HELP             => 'Помощ',
	NS_HELP_TALK        => 'Помощ_беседа',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категория_беседа'
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsBg = array(
	'Без меню', 'Неподвижно вляво', 'Неподвижно вдясно', 'Плаващо вляво', 'Плаващо вдясно'
);

/* private */ $wgSkinNamesBg = array(
	'standard' => 'Класика',
	'nostalgia' => 'Носталгия',
	'cologneblue' => 'Кьолнско синьо',
	'smarty' => 'Падингтън',
	'montparnasse' => 'Монпарнас',
	'davinci' => 'ДаВинчи',
	'mono' => 'Моно',
	'monobook' => 'Монобук',
	'myskin' => 'Мой облик',
);

/* private */ $wgDateFormatsBg = array();

/* private */ $wgBookstoreListBg = array(
	'books.bg'       => 'http://www.books.bg/ISBN/$1',
);

/* private */ $wgMagicWordsBg = array(
#   ID                                 CASE  SYNONYMS
	MAG_REDIRECT             => array( 0, '#redirect', '#пренасочване', '#виж' ),
	MAG_NOTOC                => array( 0, '__NOTOC__', '__БЕЗСЪДЪРЖАНИЕ__' ),
	MAG_FORCETOC             => array( 0, '__FORCETOC__', '__СЪССЪДЪРЖАНИЕ__' ),
	MAG_TOC                  => array( 0, '__TOC__', '__СЪДЪРЖАНИЕ__'      ),
	MAG_NOEDITSECTION        => array( 0, '__NOEDITSECTION__', '__БЕЗ_РЕДАКТИРАНЕ_НА_РАЗДЕЛИ__' ),
	MAG_START                => array( 0, '__START__', '__НАЧАЛО__'         ),
	MAG_CURRENTMONTH         => array( 1, 'CURRENTMONTH', 'ТЕКУЩМЕСЕЦ'      ),
	MAG_CURRENTMONTHNAME     => array( 1, 'CURRENTMONTHNAME', 'ТЕКУЩМЕСЕЦИМЕ' ),
	MAG_CURRENTMONTHNAMEGEN  => array( 1, 'CURRENTMONTHNAMEGEN', 'ТЕКУЩМЕСЕЦИМЕРОД' ),
	MAG_CURRENTMONTHABBREV   => array( 1, 'CURRENTMONTHABBREV', 'ТЕКУЩМЕСЕЦСЪКР'    ),
	MAG_CURRENTDAY           => array( 1, 'CURRENTDAY', 'ТЕКУЩДЕН'            ),
	MAG_CURRENTDAYNAME       => array( 1, 'CURRENTDAYNAME', 'ТЕКУЩДЕНИМЕ'     ),
	MAG_CURRENTYEAR          => array( 1, 'CURRENTYEAR', 'ТЕКУЩАГОДИНА'       ),
	MAG_CURRENTTIME          => array( 1, 'CURRENTTIME', 'ТЕКУЩОВРЕМЕ'        ),
	MAG_NUMBEROFARTICLES     => array( 1, 'NUMBEROFARTICLES', 'БРОЙСТАТИИ'    ),
	MAG_NUMBEROFFILES        => array( 1, 'NUMBEROFFILES', 'БРОЙФАЙЛОВЕ'      ),
	MAG_PAGENAME             => array( 1, 'PAGENAME', 'СТРАНИЦА'              ),
	MAG_PAGENAMEE            => array( 1, 'PAGENAMEE', 'СТРАНИЦАИ'            ),
	MAG_NAMESPACE            => array( 1, 'NAMESPACE', 'ИМЕННОПРОСТРАНСТВО'   ),
	MAG_SUBST                => array( 0, 'SUBST:', 'ЗАМЕСТ:'            ),
	MAG_MSGNW                => array( 0, 'MSGNW:', 'СЪОБЩNW:'           ),
	MAG_END                  => array( 0, '__END__', '__КРАЙ__'            ),
	MAG_IMG_THUMBNAIL        => array( 1, 'thumbnail', 'thumb', 'мини'     ),
	MAG_IMG_MANUALTHUMB      => array( 1, 'thumbnail=$1', 'thumb=$1', 'мини=$1'),
	MAG_IMG_RIGHT            => array( 1, 'right', 'вдясно', 'дясно', 'д'  ),
	MAG_IMG_LEFT             => array( 1, 'left', 'вляво', 'ляво', 'л'     ),
	MAG_IMG_NONE             => array( 1, 'none', 'н'                  ),
	MAG_IMG_WIDTH            => array( 1, '$1px', '$1пкс' , '$1п'         ),
	MAG_IMG_CENTER           => array( 1, 'center', 'centre', 'център', 'центр', 'ц' ),
	MAG_IMG_FRAMED           => array( 1, 'framed', 'enframed', 'frame', 'рамка', 'врамка' ),
	MAG_INT                  => array( 0, 'INT:'                   ),
	MAG_SITENAME             => array( 1, 'SITENAME', 'ИМЕНАСАЙТА'       ),
	MAG_NS                   => array( 0, 'NS:', 'ИП:'                    ),
	MAG_LOCALURL             => array( 0, 'LOCALURL:', 'ЛОКАЛЕНАДРЕС:'    ),
	MAG_LOCALURLE            => array( 0, 'LOCALURLE:', 'ЛОКАЛЕНАДРЕСИ:'  ),
	MAG_SERVER               => array( 0, 'SERVER', 'СЪРВЪР'       ),
	MAG_SERVERNAME           => array( 0, 'SERVERNAME', 'ИМЕНАСЪРВЪРА'    ),
	MAG_SCRIPTPATH           => array( 0, 'SCRIPTPATH', 'ПЪТДОСКРИПТА'    ),
	MAG_GRAMMAR              => array( 0, 'GRAMMAR:', 'ГРАМАТИКА:' ),
	MAG_NOTITLECONVERT       => array( 0, '__NOTITLECONVERT__', '__NOTC__'),
	MAG_NOCONTENTCONVERT     => array( 0, '__NOCONTENTCONVERT__', '__NOCC__'),
	MAG_CURRENTWEEK          => array( 1, 'CURRENTWEEK', 'ТЕКУЩАСЕДМИЦА'),
	MAG_CURRENTDOW           => array( 1, 'CURRENTDOW'             ),
	MAG_REVISIONID           => array( 1, 'REVISIONID'             ),
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesBg.php');
}

/** This is an UTF-8 language */
require_once( 'LanguageUtf8.php' );

/**
 * @package MediaWiki
 * @subpackage Language
 */
class LanguageBg extends LanguageUtf8 {

	/**
	* Exports $wgBookstoreListBg
	* @return array
	*/
	function getBookstoreList () {
		global $wgBookstoreListBg;
		return $wgBookstoreListBg;
	}

	/**
	* Exports $wgNamespaceNamesBg
	* @return array
	*/
	function getNamespaces() {
		global $wgNamespaceNamesBg;
		return $wgNamespaceNamesBg;
	}

	/**
	* Exports $wgQuickbarSettingsBg
	* @return array
	*/
	function getQuickbarSettings() {
		global $wgQuickbarSettingsBg;
		return $wgQuickbarSettingsBg;
	}

	/**
	* Exports $wgSkinNamesBg
	* @return array
	*/
	function getSkinNames() {
		global $wgSkinNamesBg;
		return $wgSkinNamesBg;
	}

	/**
	* Exports $wgDateFormatsBg
	* @return array
	*/
	function getDateFormats() {
		global $wgDateFormatsBg;
		return $wgDateFormatsBg;
	}

	function getMessage( $key ) {
		global $wgAllMessagesBg;
		if ( isset( $wgAllMessagesBg[$key] ) ) {
			return $wgAllMessagesBg[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	/**
	* Exports $wgMagicWordsBg
	* @return array
	*/
	function getMagicWords()  {
		global $wgMagicWordsBg;
		return $wgMagicWordsBg;
	}


	function separatorTransformTable() {
		return array(',' => "\xc2\xa0", '.' => ',' );
	}

	/**
	 * ISO number formatting: 123 456 789,99.
	 * Avoid tripple grouping by numbers with whole part up to 4 digits.
	 */
	function commafy($_) {
		if (!preg_match('/^\d{1,4}$/',$_)) {
			return strrev((string)preg_replace('/(\d{3})(?=\d)(?!\d*\.)/','$1,',strrev($_)));
		} else {
			return $_;
		}
	}

}
?>
