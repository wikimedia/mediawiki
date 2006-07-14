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
		'redirect'              => array( 0, '#redirect', '#пренасочување', '#види' ),
		'notoc'                 => array( 0, '__NOTOC__', '__БЕЗСОДРЖИНА__' ),
		'forcetoc'              => array( 0, '__FORCETOC__', '__СОСОДРЖИНА__' ),
		'toc'                   => array( 0, '__TOC__', '__СОДРЖИНА__' ),
		'noeditsection'         => array( 0, '__NOEDITSECTION__' , '__БЕЗ_УРЕДУВАЊЕ_НА_СЕКЦИИ__'),
		'start'                 => array( 0, '__START__' , '__ПОЧЕТОК__' ),
		'currentmonth'          => array( 1, 'CURRENTMONTH', 'СЕГАШЕНМЕСЕЦ' ),
		'currentmonthname'      => array( 1, 'CURRENTMONTHNAME', 'СЕГАШЕНМЕСЕЦИМЕ' ),
		'currentmonthnamegen'   => array( 1, 'CURRENTMONTHNAMEGEN', 'СЕГАШЕНМЕСЕЦИМЕРОД' ),
		'currentmonthabbrev'    => array( 1, 'CURRENTMONTHABBREV', 'СЕГАШЕНМЕСЕЦСКР' ),
		'currentday'            => array( 1, 'CURRENTDAY', 'СЕГАШЕНДЕН' ),
		'currentdayname'        => array( 1, 'CURRENTDAYNAME', 'СЕГАШЕНДЕНИМЕ' ),
		'currentyear'           => array( 1, 'CURRENTYEAR', 'СЕГАШНАГОДИНА' ),
		'currenttime'           => array( 1, 'CURRENTTIME', 'СЕГАШНОВРЕМЕ' ),
		'numberofarticles'      => array( 1, 'NUMBEROFARTICLES', 'БРОЈСТАТИИ' ),
		'pagename'              => array( 1, 'PAGENAME', 'СТРАНИЦА' ),
		'pagenamee'             => array( 1, 'PAGENAMEE', 'СТРАНИЦАИ' ),
		'namespace'             => array( 1, 'NAMESPACE', 'ИМЕПРОСТОР' ),
		'subst'                 => array( 0, 'SUBST:', 'ЗАМЕСТ:' ),
		'msgnw'                 => array( 0, 'MSGNW:', 'ИЗВЕШТNW:' ),
		'end'                   => array( 0, '', '__КРАЈ__' ),
		'img_thumbnail'         => array( 1, 'thumbnail', 'thumb', 'мини' ),
		'img_right'             => array( 1, 'right', 'десно', 'д' ),
		'img_left'              => array( 1, 'left', 'лево', 'л' ),
		'img_none'              => array( 1, 'none', 'н' ),
		'img_width'             => array( 1, '$1px', '$1пкс' , '$1п' ),
		'img_center'            => array( 1, 'center', 'centre', 'центар', 'ц' ),
		'img_framed'            => array( 1, 'framed', 'enframed', 'frame', 'рамка', 'ворамка' ),
		'int'                   => array( 0, 'INT:' ),
		'sitename'              => array( 1, 'SITENAME', 'ИМЕНАСАЈТ' ),
		'ns'                    => array( 0, 'NS:' ),
		'localurl'              => array( 0, 'LOCALURL:', 'ЛОКАЛНААДРЕСА:' ),
		'localurle'             => array( 0, 'LOCALURLE:', 'ЛОКАЛНААДРЕСАИ:' ),
		'server'                => array( 0, 'SERVER', 'СЕРВЕР' ),
		'grammar'               => array( 0, 'GRAMMAR:', 'ГРАМАТИКА:' ),
		'notitleconvert'        => array( 0, '__NOTITLECONVERT__', '__NOTC__'),
		'nocontentconvert'      => array( 0, '__NOCONTENTCONVERT__', '__NOCC__'),
		'currentweek'           => array( 1, 'CURRENTWEEK', 'СЕГАШНАСЕДМИЦА'),
		'currentdow'            => array( 1, 'CURRENTDOW' ),
		'revisionid'            => array( 1, 'REVISIONID' ),
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
