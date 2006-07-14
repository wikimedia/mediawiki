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
	'redirect'               => array( 0, '#redirect', '#пренасочване', '#виж' ),
	'notoc'                  => array( 0, '__NOTOC__', '__БЕЗСЪДЪРЖАНИЕ__' ),
	'forcetoc'               => array( 0, '__FORCETOC__', '__СЪССЪДЪРЖАНИЕ__' ),
	'toc'                    => array( 0, '__TOC__', '__СЪДЪРЖАНИЕ__'      ),
	'noeditsection'          => array( 0, '__NOEDITSECTION__', '__БЕЗ_РЕДАКТИРАНЕ_НА_РАЗДЕЛИ__' ),
	'start'                  => array( 0, '__START__', '__НАЧАЛО__'         ),
	'currentmonth'           => array( 1, 'CURRENTMONTH', 'ТЕКУЩМЕСЕЦ'      ),
	'currentmonthname'       => array( 1, 'CURRENTMONTHNAME', 'ТЕКУЩМЕСЕЦИМЕ' ),
	'currentmonthnamegen'    => array( 1, 'CURRENTMONTHNAMEGEN', 'ТЕКУЩМЕСЕЦИМЕРОД' ),
	'currentmonthabbrev'     => array( 1, 'CURRENTMONTHABBREV', 'ТЕКУЩМЕСЕЦСЪКР'    ),
	'currentday'             => array( 1, 'CURRENTDAY', 'ТЕКУЩДЕН'            ),
	'currentdayname'         => array( 1, 'CURRENTDAYNAME', 'ТЕКУЩДЕНИМЕ'     ),
	'currentyear'            => array( 1, 'CURRENTYEAR', 'ТЕКУЩАГОДИНА'       ),
	'currenttime'            => array( 1, 'CURRENTTIME', 'ТЕКУЩОВРЕМЕ'        ),
	'numberofarticles'       => array( 1, 'NUMBEROFARTICLES', 'БРОЙСТАТИИ'    ),
	'numberoffiles'          => array( 1, 'NUMBEROFFILES', 'БРОЙФАЙЛОВЕ'      ),
	'pagename'               => array( 1, 'PAGENAME', 'СТРАНИЦА'              ),
	'pagenamee'              => array( 1, 'PAGENAMEE', 'СТРАНИЦАИ'            ),
	'namespace'              => array( 1, 'NAMESPACE', 'ИМЕННОПРОСТРАНСТВО'   ),
	'subst'                  => array( 0, 'SUBST:', 'ЗАМЕСТ:'            ),
	'msgnw'                  => array( 0, 'MSGNW:', 'СЪОБЩNW:'           ),
	'end'                    => array( 0, '__END__', '__КРАЙ__'            ),
	'img_thumbnail'          => array( 1, 'thumbnail', 'thumb', 'мини'     ),
	'img_manualthumb'        => array( 1, 'thumbnail=$1', 'thumb=$1', 'мини=$1'),
	'img_right'              => array( 1, 'right', 'вдясно', 'дясно', 'д'  ),
	'img_left'               => array( 1, 'left', 'вляво', 'ляво', 'л'     ),
	'img_none'               => array( 1, 'none', 'н'                  ),
	'img_width'              => array( 1, '$1px', '$1пкс' , '$1п'         ),
	'img_center'             => array( 1, 'center', 'centre', 'център', 'центр', 'ц' ),
	'img_framed'             => array( 1, 'framed', 'enframed', 'frame', 'рамка', 'врамка' ),
	'int'                    => array( 0, 'INT:'                   ),
	'sitename'               => array( 1, 'SITENAME', 'ИМЕНАСАЙТА'       ),
	'ns'                     => array( 0, 'NS:', 'ИП:'                    ),
	'localurl'               => array( 0, 'LOCALURL:', 'ЛОКАЛЕНАДРЕС:'    ),
	'localurle'              => array( 0, 'LOCALURLE:', 'ЛОКАЛЕНАДРЕСИ:'  ),
	'server'                 => array( 0, 'SERVER', 'СЪРВЪР'       ),
	'servername'             => array( 0, 'SERVERNAME', 'ИМЕНАСЪРВЪРА'    ),
	'scriptpath'             => array( 0, 'SCRIPTPATH', 'ПЪТДОСКРИПТА'    ),
	'grammar'                => array( 0, 'GRAMMAR:', 'ГРАМАТИКА:' ),
	'notitleconvert'         => array( 0, '__NOTITLECONVERT__', '__NOTC__'),
	'nocontentconvert'       => array( 0, '__NOCONTENTCONVERT__', '__NOCC__'),
	'currentweek'            => array( 1, 'CURRENTWEEK', 'ТЕКУЩАСЕДМИЦА'),
	'currentdow'             => array( 1, 'CURRENTDOW'             ),
	'revisionid'             => array( 1, 'REVISIONID'             ),
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
	function &getMagicWords()  {
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
