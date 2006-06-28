<?php
/** Macedonian (Македонски)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesMk.php');
}

class LanguageMk extends LanguageUtf8 {
	private $mMessagesMk, $mNamespaceNamesMk = null;

	private $mQuickbarSettingsMk = array(
		'Без мени', 'Фиксирано лево', 'Фиксирано десно', 'Пловечко лево'
	);
	
	private $mSkinNamesMk = array(
		'standard'    => 'Класика',
		'nostalgia'   => 'Носталгија',
		'cologneblue' => 'Келнско сино',
		'davinci'     => 'ДаВинчи',
		'mono'        => 'Моно',
		'monobook'    => 'Monobook',
		'myskin'      => 'Моја маска',
		'chick'       => 'Шик'
	);
	
	private $mDateFormatsMk = array(
		'Без преференции',
		'Јануари 15, 2001',
		'15 Јануари 2001',
		'2001 Јануари 15',
		'2001-01-15'
	);
	
	private $mMagicWordsMk = array(
		MAG_REDIRECT            => array( 0, '#redirect', '#пренасочување', '#види' ),
		MAG_NOTOC               => array( 0, '__NOTOC__', '__БЕЗСОДРЖИНА__' ),
		MAG_FORCETOC            => array( 0, '__FORCETOC__', '__СОСОДРЖИНА__' ),
		MAG_TOC                 => array( 0, '__TOC__', '__СОДРЖИНА__' ),
		MAG_NOEDITSECTION       => array( 0, '__NOEDITSECTION__' , '__БЕЗ_УРЕДУВАЊЕ_НА_СЕКЦИИ__'),
		MAG_START               => array( 0, '__START__' , '__ПОЧЕТОК__' ),
		MAG_CURRENTMONTH        => array( 1, 'CURRENTMONTH', 'СЕГАШЕНМЕСЕЦ' ),
		MAG_CURRENTMONTHNAME    => array( 1, 'CURRENTMONTHNAME', 'СЕГАШЕНМЕСЕЦИМЕ' ),
		MAG_CURRENTMONTHNAMEGEN => array( 1, 'CURRENTMONTHNAMEGEN', 'СЕГАШЕНМЕСЕЦИМЕРОД' ),
		MAG_CURRENTMONTHABBREV  => array( 1, 'CURRENTMONTHABBREV', 'СЕГАШЕНМЕСЕЦСКР' ),
		MAG_CURRENTDAY          => array( 1, 'CURRENTDAY', 'СЕГАШЕНДЕН' ),
		MAG_CURRENTDAYNAME      => array( 1, 'CURRENTDAYNAME', 'СЕГАШЕНДЕНИМЕ' ),
		MAG_CURRENTYEAR         => array( 1, 'CURRENTYEAR', 'СЕГАШНАГОДИНА' ),
		MAG_CURRENTTIME         => array( 1, 'CURRENTTIME', 'СЕГАШНОВРЕМЕ' ),
		MAG_NUMBEROFARTICLES    => array( 1, 'NUMBEROFARTICLES', 'БРОЈСТАТИИ' ),
		MAG_PAGENAME            => array( 1, 'PAGENAME', 'СТРАНИЦА' ),
		MAG_PAGENAMEE           => array( 1, 'PAGENAMEE', 'СТРАНИЦАИ' ),
		MAG_NAMESPACE           => array( 1, 'NAMESPACE', 'ИМЕПРОСТОР' ),
		MAG_SUBST               => array( 0, 'SUBST:', 'ЗАМЕСТ:' ),
		MAG_MSGNW               => array( 0, 'MSGNW:', 'ИЗВЕШТNW:' ),
		MAG_END                 => array( 0, '', '__КРАЈ__' ),
		MAG_IMG_THUMBNAIL       => array( 1, 'thumbnail', 'thumb', 'мини' ),
		MAG_IMG_RIGHT           => array( 1, 'right', 'десно', 'д' ),
		MAG_IMG_LEFT            => array( 1, 'left', 'лево', 'л' ),
		MAG_IMG_NONE            => array( 1, 'none', 'н' ),
		MAG_IMG_WIDTH           => array( 1, '$1px', '$1пкс' , '$1п' ),
		MAG_IMG_CENTER          => array( 1, 'center', 'centre', 'центар', 'ц' ),
		MAG_IMG_FRAMED          => array( 1, 'framed', 'enframed', 'frame', 'рамка', 'ворамка' ),
		MAG_INT                 => array( 0, 'INT:' ),
		MAG_SITENAME            => array( 1, 'SITENAME', 'ИМЕНАСАЈТ' ),
		MAG_NS                  => array( 0, 'NS:' ),
		MAG_LOCALURL            => array( 0, 'LOCALURL:', 'ЛОКАЛНААДРЕСА:' ),
		MAG_LOCALURLE           => array( 0, 'LOCALURLE:', 'ЛОКАЛНААДРЕСАИ:' ),
		MAG_SERVER              => array( 0, 'SERVER', 'СЕРВЕР' ),
		MAG_GRAMMAR             => array( 0, 'GRAMMAR:', 'ГРАМАТИКА:' ),
		MAG_NOTITLECONVERT      => array( 0, '__NOTITLECONVERT__', '__NOTC__'),
		MAG_NOCONTENTCONVERT    => array( 0, '__NOCONTENTCONVERT__', '__NOCC__'),
		MAG_CURRENTWEEK         => array( 1, 'CURRENTWEEK', 'СЕГАШНАСЕДМИЦА'),
		MAG_CURRENTDOW          => array( 1, 'CURRENTDOW' ),
		MAG_REVISIONID          => array( 1, 'REVISIONID' ),
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesMk;
		$this->mMessagesMk =& $wgAllMessagesMk;

		global $wgMetaNamespace;
		$this->mNamespaceNamesMk = array(
			NS_MEDIA          => 'Медија',
			NS_SPECIAL        => 'Специјални',
			NS_MAIN           => '',
			NS_TALK           => 'Разговор',
			NS_USER           => 'Корисник',
			NS_USER_TALK      => 'Разговор_со_корисник',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => 'Разговор_за_' . $wgMetaNamespace ,
			NS_IMAGE          => 'Слика',
			NS_IMAGE_TALK     => 'Разговор_за_слика',
			NS_MEDIAWIKI      => 'МедијаВики',
			NS_MEDIAWIKI_TALK => 'Разговор_за_МедијаВики',
			NS_TEMPLATE       => 'Шаблон',
			NS_TEMPLATE_TALK  => 'Разговор_за_шаблон',
			NS_HELP           => 'Помош',
			NS_HELP_TALK      => 'Разговор_за_помош',
			NS_CATEGORY       => 'Категорија',
			NS_CATEGORY_TALK  => 'Разговор_за_категорија',
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesMk + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsMk;
	}

	function getSkinNames() {
		return $this->mSkinNamesMk + parent::getSkinNames();
	}

	function getDateFormats() {
		return $this->mDateFormatsMk;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesMk[$key] ) ) {
			return $this->mMessagesMk[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesMk;
	}

	function &getMagicWords()  {
		$t = $this->mMagicWordsMk + parent::getMagicWords();
		return $t;
	}

	function linkTrail() {
		return '/^([a-zабвгдѓежзѕијклљмнњопрстќуфхцчџш]+)(.*)$/sDu';
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}


}
?>
