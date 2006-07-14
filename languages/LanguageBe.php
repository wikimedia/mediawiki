<?php
/** Belarusian (Беларуская мова)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  * @bug 1638, 2135
  * @link http://be.wikipedia.org/wiki/Talk:LanguageBe.php
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
  * @license http://www.gnu.org/copyleft/fdl.html GNU Free Documentation License
  */

require_once('LanguageUtf8.php');

if (!$wgCachedMessageArrays) {
	require_once('MessagesBe.php');
}

class LanguageBe extends LanguageUtf8 {
	private $mMessagesBe, $mNamespaceNamesBe = null;

	private $mQuickbarSettingsBe = array(
		'Не паказваць', 'Замацаваная зьлева', 'Замацаваная справа', 'Рухомая зьлева'
	);
	
	private $mSkinNamesBe = array(
		'standard' => 'Клясычны',
		'nostalgia' => 'Настальгія',
		'cologneblue' => 'Кёльнскі смутак',
		'davinci' => 'Да Вінчы',
		'mono' => 'Мона',
		'monobook' => 'Монакніга',
		'myskin' => 'MySkin',
		'chick' => 'Цыпа'
	);
	
	private $mDateFormatsBe = array(
		MW_DATE_DEFAULT,
		'16:12, 15.01.2001',
		MW_DATE_ISO,
	);
	
	private $mMagicWordsBe = array(
		'redirect'               => array( 0,    '#перанакіраваньне', '#redirect' ),
		'notoc'                  => array( 0,    '__NOTOC__', '__БЯЗЬ_ЗЬМЕСТУ__' ),
		'forcetoc'               => array( 0,    '__FORCETOC__', '__ЗЬМЕСТ_ПРЫМУСАМ__' ),
		'toc'                    => array( 0,    '__TOC__', '__ЗЬМЕСТ__' ),
		'noeditsection'          => array( 0,    '__NOEDITSECTION__', '__БЕЗ_РЭДАГАВАНЬНЯ_СЭКЦЫІ__' ),
		'start'                  => array( 0,    '__START__', '__ПАЧАТАК__' ),
		'currentmonth'           => array( 1,    'CURRENTMONTH', 'БЯГУЧЫ_МЕСЯЦ' ),
		'currentmonthname'       => array( 1,    'CURRENTMONTHNAME', 'НАЗВА_БЯГУЧАГА_МЕСЯЦА' ),
		'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN', 'НАЗВА_БЯГУЧАГА_МЕСЯЦА_Ў_РОДНЫМ_СКЛОНЕ' ),
		'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV', 'СКАРОЧАНАЯ_НАЗВА_БЯГУЧАГА_МЕСЯЦА' ),
		'currentday'             => array( 1,    'CURRENTDAY', 'БЯГУЧЫ_ДЗЕНЬ' ),
		'currentday2'            => array( 1,    'CURRENTDAY2', 'БЯГУЧЫ_ДЗЕНЬ_2' ),
		'currentdayname'         => array( 1,    'CURRENTDAYNAME', 'НАЗВА_БЯГУЧАГА_ДНЯ' ),
		'currentyear'            => array( 1,    'CURRENTYEAR', 'БЯГУЧЫ_ГОД' ),
		'currenttime'            => array( 1,    'CURRENTTIME', 'БЯГУЧЫ_ЧАС' ),
		'numberofpages'          => array( 1,    'NUMBEROFPAGES', 'КОЛЬКАСЬЦЬ_СТАРОНАК' ),
		'numberofarticles'       => array( 1,    'NUMBEROFARTICLES', 'КОЛЬКАСЬЦЬ_АРТЫКУЛАЎ' ),
		'numberoffiles'          => array( 1,    'NUMBEROFFILES', 'КОЛЬКАСЬЦЬ_ФАЙЛАЎ' ),
		'numberofusers'          => array( 1,    'NUMBEROFUSERS', 'КОЛЬКАСЬЦЬ_УДЗЕЛЬНІКАЎ' ),
		'pagename'               => array( 1,    'PAGENAME', 'НАЗВА_СТАРОНКІ' ),
		'pagenamee'              => array( 1,    'PAGENAMEE', 'НАЗВА_СТАРОНКІ_2' ),
		'namespace'              => array( 1,    'NAMESPACE', 'ПРАСТОРА_НАЗВАЎ' ),
		'namespacee'             => array( 1,    'NAMESPACEE', 'ПРАСТОРА_НАЗВАЎ_2' ),
		'talkspace'              => array( 1,    'TALKSPACE', 'ПРАСТОРА_НАЗВАЎ_АБМЕРКАВАНЬНЯ' ),
		'talkspacee'             => array( 1,    'TALKSPACEE', 'ПРАСТОРА_НАЗВАЎ_АБМЕРКАВАНЬНЯ_2' ),
		'subjectspace'           => array( 1,    'SUBJECTSPACE', 'ARTICLESPACE', 'ПРАСТОРА_НАЗВАЎ_ПРАДМЕТУ', 'ПРАСТОРА_НАЗВАЎ_АРТЫКУЛА' ),
		'subjectspacee'          => array( 1,    'SUBJECTSPACEE', 'ARTICLESPACEE', 'ПРАСТОРА_НАЗВАЎ_ПРАДМЕТУ_2', 'ПРАСТОРА_НАЗВАЎ_АРТЫКУЛА_2' ),
		'fullpagename'           => array( 1,    'FULLPAGENAME', 'ПОЎНАЯ_НАЗВА_СТАРОНКІ' ),
		'fullpagenamee'          => array( 1,    'FULLPAGENAMEE', 'ПОЎНАЯ_НАЗВА_СТАРОНКІ_2' ),
		'subpagename'  	         => array( 1,    'SUBPAGENAME', 'НАЗВА_ПАДСТАРОНКІ' ),
		'subpagenamee'           => array( 1,    'SUBPAGENAMEE', 'НАЗВА_ПАДСТАРОНКІ_2' ),
		'basepagename'           => array( 1,    'BASEPAGENAME', 'НАЗВА_БАЗАВАЙ_СТАРОНКІ' ),
		'basepagenamee'          => array( 1,    'BASEPAGENAMEE', 'НАЗВА_БАЗАВАЙ_СТАРОНКІ_2' ),
		'talkpagename'           => array( 1,    'TALKPAGENAME', 'НАЗВА_СТАРОНКІ_АБМЕРКАВАНЬНЯ' ),
		'talkpagenamee'          => array( 1,    'TALKPAGENAMEE', 'НАЗВА_СТАРОНКІ_АБМЕРКАВАНЬНЯ_2' ),
		'subjectpagename'        => array( 1,    'SUBJECTPAGENAME', 'ARTICLEPAGENAME', 'НАЗВА_СТАРОНКІ_ПРАДМЕТУ', 'НАЗВА_СТАРОНКІ_АРТЫКУЛА' ),
		'subjectpagenamee'       => array( 1,    'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE', 'НАЗВА_СТАРОНКІ_ПРАДМЕТУ_2', 'НАЗВА_СТАРОНКІ_АРТЫКУЛА_2' ),
		'msg'                    => array( 0,    'MSG:', 'ПАВЕДАМЛЕНЬНЕ:' ),
		'subst'                  => array( 0,    'SUBST:', 'ПАДСТАНОЎКА:' ),
		'msgnw'                  => array( 0,    'MSGNW:', 'ПАВЕДАМЛЕНЬНЕ_БЯЗЬ_ВІКІ:' ),
		'end'                    => array( 0,    '__END__', '__КАНЕЦ__' ),
		'img_thumbnail'          => array( 1,    'thumbnail', 'thumb', 'значак', 'міні' ),
		'img_manualthumb'        => array( 1,    'thumbnail=$1', 'thumb=$1', 'значак=$1', 'міні=$1' ),
		'img_right'              => array( 1,    'right', 'справа' ),
		'img_left'               => array( 1,    'left', 'зьлева' ),
		'img_none'               => array( 1,    'none', 'няма' ),
		'img_width'              => array( 1,    '$1px', '$1пкс' ),
		'img_center'             => array( 1,    'center', 'centre', 'цэнтар' ),
		'img_framed'             => array( 1,    'framed', 'enframed', 'frame', 'рамка' ),
		'int'                    => array( 0,    'INT:' ),
		'sitename'               => array( 1,    'SITENAME', 'НАЗВА_САЙТУ' ),
		'ns'                     => array( 0,    'NS:', 'ПН:' ),
		'localurl'               => array( 0,    'LOCALURL:', 'ЛЯКАЛЬНЫ_АДРАС:' ),
		'localurle'              => array( 0,    'LOCALURLE:', 'ЛЯКАЛЬНЫ_АДРАС_2:' ),
		'server'                 => array( 0,    'SERVER', 'СЭРВЭР' ),
		'servername'             => array( 0,    'SERVERNAME', 'НАЗВА_СЭРВЭРА' ),
		'scriptpath'             => array( 0,    'SCRIPTPATH', 'ШЛЯХ_ДА_СКРЫПТА' ),
		'grammar'                => array( 0,    'GRAMMAR:', 'ГРАМАТЫКА:' ),
		'notitleconvert'         => array( 0,    '__NOTITLECONVERT__', '__NOTC__', '__БЕЗ_КАНВЭРТАЦЫІ_НАЗВЫ__' ),
		'nocontentconvert'       => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__', '__БЕЗ_КАНВЭРТАЦЫІ_ТЭКСТУ__' ),
		'currentweek'            => array( 1,    'CURRENTWEEK', 'БЯГУЧЫ_ТЫДЗЕНЬ' ),
		'currentdow'             => array( 1,    'CURRENTDOW', 'БЯГУЧЫ_ДЗЕНЬ_ТЫДНЯ' ),
		'revisionid'             => array( 1,    'REVISIONID', 'ID_ВЭРСІІ' ),
		'plural'                 => array( 0,    'PLURAL:', 'МНОЖНЫ_ЛІК:'),
		'fullurl'                => array( 0,    'FULLURL:', 'ПОЎНЫ_АДРАС:' ),
		'fullurle'               => array( 0,    'FULLURLE:', 'ПОЎНЫ_АДРАС_2:' ),
		'lcfirst'                => array( 0,    'LCFIRST:', 'ПЕРШАЯ_ЛІТАРА_МАЛАЯ:' ),
		'ucfirst'                => array( 0,    'UCFIRST:', 'ПЕРШАЯ_ЛІТАРА_ВЯЛІКАЯ:' ),
		'lc'                     => array( 0,    'LC:', 'МАЛЫМІ_ЛІТАРАМІ:' ),
		'uc'                     => array( 0,    'UC:', 'ВЯЛІКІМІ_ЛІТАРАМІ:' ),
		'raw'                    => array( 0,    'RAW:', 'НЕАПРАЦАВАНЫ' ),
		'displaytitle'           => array( 1,    'DISPLAYTITLE', 'АДЛЮСТРАВАНАЯ_НАЗВА' ),
		'rawsuffix'              => array( 1,    'R', 'Н' ),
		'newsectionlink'         => array( 1,    '__NEWSECTIONLINK__', '__СПАСЫЛКА_НА_НОВУЮ_СЭКЦЫЮ__' ),
		'currentversion'         => array( 1,    'CURRENTVERSION', 'БЯГУЧАЯ_ВЭРСІЯ' ),
		'urlencode'              => array( 0,    'URLENCODE:' ),
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesBe;
		$this->mMessagesBe =& $wgAllMessagesBe;

		global $wgMetaNamespace;
		$this->mNamespaceNamesBe = array(
			NS_MEDIA          => 'Мэдыя',
			NS_SPECIAL        => 'Спэцыяльныя',
			NS_MAIN           => '',
			NS_TALK           => 'Абмеркаваньне',
			NS_USER           => 'Удзельнік',
			NS_USER_TALK      => 'Гутаркі_ўдзельніка',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => 'Абмеркаваньне_' . $wgMetaNamespace,
			NS_IMAGE          => 'Выява',
			NS_IMAGE_TALK     => 'Абмеркаваньне_выявы',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'Абмеркаваньне_MediaWiki',
			NS_TEMPLATE       => 'Шаблён',
			NS_TEMPLATE_TALK  => 'Абмеркаваньне_шаблёну',
			NS_HELP           => 'Дапамога',
			NS_HELP_TALK      => 'Абмеркаваньне_дапамогі',
			NS_CATEGORY       => 'Катэгорыя',
			NS_CATEGORY_TALK  => 'Абмеркаваньне_катэгорыі'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesBe + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsBe;
	}

	function getSkinNames() {
		return $this->mSkinNamesBe + parent::getSkinNames();
	}

	function &getMagicWords()  {
		$t = $this->mMagicWordsBe + parent::getMagicWords();
		return $t;
	}

	function getDateFormats() {
		return $this->mDateFormatsBe;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesBe[$key] ) ) {
			return $this->mMessagesBe[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesBe;
	}

	// The date and time format
	function date( $ts, $adj = false, $format = true, $timecorrection = false ) {
		$datePreference = $this->dateFormat( $format );
		if( $datePreference == MW_DATE_ISO ) {
			return parent::date( $ts, $adj, $datePreference, $timecorrection );
		} else {
			if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); } # Adjust based on the timezone setting.
			// 20050310001506 => 10.03.2005
			$date = (substr( $ts, 6, 2 )) . '.' . substr( $ts, 4, 2 ) . '.' . substr( $ts, 0, 4 );
			return $date;
		}
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

	function convertPlural( $count, $wordform1, $wordform2, $wordform3) {
		$count = str_replace ('.', '', $count);
		if ($count > 10 && floor(($count % 100) / 10) == 1) {
			return $wordform3;
		} else {
			switch ($count % 10) {
				case 1: return $wordform1;
				case 2:
				case 3:
				case 4: return $wordform2;
				default: return $wordform3;
			}
		}
	}

	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	/**
   * Cases: родны, вінавальны, месны
   */
	function convertGrammar( $word, $case ) {
		switch ( $case ) {
			case 'родны': # genitive
				if ( $word == 'Вікіпэдыя' ) {
					$word = 'Вікіпэдыі';
				} elseif ( $word == 'ВікіСлоўнік' ) {
					$word = 'ВікіСлоўніка';
				} elseif ( $word == 'ВікіКнігі' ) {
					$word = 'ВікіКніг';
				} elseif ( $word == 'ВікіКрыніца' ) {
					$word = 'ВікіКрыніцы';
				} elseif ( $word == 'ВікіНавіны' ) {
					$word = 'ВікіНавін';
				} elseif ( $word == 'ВікіВіды' ) {
					$word = 'ВікіВідаў';
				}
			break;
			case 'вінавальны': # akusative
				if ( $word == 'Вікіпэдыя' ) {
					$word = 'Вікіпэдыю';
				} elseif ( $word == 'ВікіСлоўнік' ) {
					$word = 'ВікіСлоўнік';
				} elseif ( $word == 'ВікіКнігі' ) {
					$word = 'ВікіКнігі';
				} elseif ( $word == 'ВікіКрыніца' ) {
					$word = 'ВікіКрыніцу';
				} elseif ( $word == 'ВікіНавіны' ) {
					$word = 'ВікіНавіны';
				} elseif ( $word == 'ВікіВіды' ) {
					$word = 'ВікіВіды';
				}
			break;
			case 'месны': # prepositional
				if ( $word == 'Вікіпэдыя' ) {
					$word = 'Вікіпэдыі';
				} elseif ( $word == 'ВікіСлоўнік' ) {
					$word = 'ВікіСлоўніку';
				} elseif ( $word == 'ВікіКнігі' ) {
					$word = 'ВікіКнігах';
				} elseif ( $word == 'ВікіКрыніца' ) {
					$word = 'ВікіКрыніцы';
				} elseif ( $word == 'ВікіНавіны' ) {
					$word = 'ВікіНавінах';
				} elseif ( $word == 'ВікіВіды' ) {
					$word = 'ВікіВідах';
				}
			break;
		}

		return $word; # this will return the original value for 'назоўны' (nominative) and all undefined case values
	}

}

?>
