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
		MAG_REDIRECT             => array( 0,    '#перанакіраваньне', '#redirect' ),
		MAG_NOTOC                => array( 0,    '__NOTOC__', '__БЯЗЬ_ЗЬМЕСТУ__' ),
		MAG_FORCETOC             => array( 0,    '__FORCETOC__', '__ЗЬМЕСТ_ПРЫМУСАМ__' ),
		MAG_TOC                  => array( 0,    '__TOC__', '__ЗЬМЕСТ__' ),
		MAG_NOEDITSECTION        => array( 0,    '__NOEDITSECTION__', '__БЕЗ_РЭДАГАВАНЬНЯ_СЭКЦЫІ__' ),
		MAG_START                => array( 0,    '__START__', '__ПАЧАТАК__' ),
		MAG_CURRENTMONTH         => array( 1,    'CURRENTMONTH', 'БЯГУЧЫ_МЕСЯЦ' ),
		MAG_CURRENTMONTHNAME     => array( 1,    'CURRENTMONTHNAME', 'НАЗВА_БЯГУЧАГА_МЕСЯЦА' ),
		MAG_CURRENTMONTHNAMEGEN  => array( 1,    'CURRENTMONTHNAMEGEN', 'НАЗВА_БЯГУЧАГА_МЕСЯЦА_Ў_РОДНЫМ_СКЛОНЕ' ),
		MAG_CURRENTMONTHABBREV   => array( 1,    'CURRENTMONTHABBREV', 'СКАРОЧАНАЯ_НАЗВА_БЯГУЧАГА_МЕСЯЦА' ),
		MAG_CURRENTDAY           => array( 1,    'CURRENTDAY', 'БЯГУЧЫ_ДЗЕНЬ' ),
		MAG_CURRENTDAY2          => array( 1,    'CURRENTDAY2', 'БЯГУЧЫ_ДЗЕНЬ_2' ),
		MAG_CURRENTDAYNAME       => array( 1,    'CURRENTDAYNAME', 'НАЗВА_БЯГУЧАГА_ДНЯ' ),
		MAG_CURRENTYEAR          => array( 1,    'CURRENTYEAR', 'БЯГУЧЫ_ГОД' ),
		MAG_CURRENTTIME          => array( 1,    'CURRENTTIME', 'БЯГУЧЫ_ЧАС' ),
		MAG_NUMBEROFPAGES        => array( 1,    'NUMBEROFPAGES', 'КОЛЬКАСЬЦЬ_СТАРОНАК' ),
		MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES', 'КОЛЬКАСЬЦЬ_АРТЫКУЛАЎ' ),
		MAG_NUMBEROFFILES        => array( 1,    'NUMBEROFFILES', 'КОЛЬКАСЬЦЬ_ФАЙЛАЎ' ),
		MAG_NUMBEROFUSERS        => array( 1,    'NUMBEROFUSERS', 'КОЛЬКАСЬЦЬ_УДЗЕЛЬНІКАЎ' ),
		MAG_PAGENAME             => array( 1,    'PAGENAME', 'НАЗВА_СТАРОНКІ' ),
		MAG_PAGENAMEE            => array( 1,    'PAGENAMEE', 'НАЗВА_СТАРОНКІ_2' ),
		MAG_NAMESPACE            => array( 1,    'NAMESPACE', 'ПРАСТОРА_НАЗВАЎ' ),
		MAG_NAMESPACEE           => array( 1,    'NAMESPACEE', 'ПРАСТОРА_НАЗВАЎ_2' ),
		MAG_TALKSPACE            => array( 1,    'TALKSPACE', 'ПРАСТОРА_НАЗВАЎ_АБМЕРКАВАНЬНЯ' ),
		MAG_TALKSPACEE           => array( 1,    'TALKSPACEE', 'ПРАСТОРА_НАЗВАЎ_АБМЕРКАВАНЬНЯ_2' ),
		MAG_SUBJECTSPACE         => array( 1,    'SUBJECTSPACE', 'ARTICLESPACE', 'ПРАСТОРА_НАЗВАЎ_ПРАДМЕТУ', 'ПРАСТОРА_НАЗВАЎ_АРТЫКУЛА' ),
		MAG_SUBJECTSPACEE        => array( 1,    'SUBJECTSPACEE', 'ARTICLESPACEE', 'ПРАСТОРА_НАЗВАЎ_ПРАДМЕТУ_2', 'ПРАСТОРА_НАЗВАЎ_АРТЫКУЛА_2' ),
		MAG_FULLPAGENAME         => array( 1,    'FULLPAGENAME', 'ПОЎНАЯ_НАЗВА_СТАРОНКІ' ),
		MAG_FULLPAGENAMEE        => array( 1,    'FULLPAGENAMEE', 'ПОЎНАЯ_НАЗВА_СТАРОНКІ_2' ),
		MAG_SUBPAGENAME	         => array( 1,    'SUBPAGENAME', 'НАЗВА_ПАДСТАРОНКІ' ),
		MAG_SUBPAGENAMEE         => array( 1,    'SUBPAGENAMEE', 'НАЗВА_ПАДСТАРОНКІ_2' ),
		MAG_BASEPAGENAME         => array( 1,    'BASEPAGENAME', 'НАЗВА_БАЗАВАЙ_СТАРОНКІ' ),
		MAG_BASEPAGENAMEE        => array( 1,    'BASEPAGENAMEE', 'НАЗВА_БАЗАВАЙ_СТАРОНКІ_2' ),
		MAG_TALKPAGENAME         => array( 1,    'TALKPAGENAME', 'НАЗВА_СТАРОНКІ_АБМЕРКАВАНЬНЯ' ),
		MAG_TALKPAGENAMEE        => array( 1,    'TALKPAGENAMEE', 'НАЗВА_СТАРОНКІ_АБМЕРКАВАНЬНЯ_2' ),
		MAG_SUBJECTPAGENAME      => array( 1,    'SUBJECTPAGENAME', 'ARTICLEPAGENAME', 'НАЗВА_СТАРОНКІ_ПРАДМЕТУ', 'НАЗВА_СТАРОНКІ_АРТЫКУЛА' ),
		MAG_SUBJECTPAGENAMEE     => array( 1,    'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE', 'НАЗВА_СТАРОНКІ_ПРАДМЕТУ_2', 'НАЗВА_СТАРОНКІ_АРТЫКУЛА_2' ),
		MAG_MSG                  => array( 0,    'MSG:', 'ПАВЕДАМЛЕНЬНЕ:' ),
		MAG_SUBST                => array( 0,    'SUBST:', 'ПАДСТАНОЎКА:' ),
		MAG_MSGNW                => array( 0,    'MSGNW:', 'ПАВЕДАМЛЕНЬНЕ_БЯЗЬ_ВІКІ:' ),
		MAG_END                  => array( 0,    '__END__', '__КАНЕЦ__' ),
		MAG_IMG_THUMBNAIL        => array( 1,    'thumbnail', 'thumb', 'значак', 'міні' ),
		MAG_IMG_MANUALTHUMB      => array( 1,    'thumbnail=$1', 'thumb=$1', 'значак=$1', 'міні=$1' ),
		MAG_IMG_RIGHT            => array( 1,    'right', 'справа' ),
		MAG_IMG_LEFT             => array( 1,    'left', 'зьлева' ),
		MAG_IMG_NONE             => array( 1,    'none', 'няма' ),
		MAG_IMG_WIDTH            => array( 1,    '$1px', '$1пкс' ),
		MAG_IMG_CENTER           => array( 1,    'center', 'centre', 'цэнтар' ),
		MAG_IMG_FRAMED           => array( 1,    'framed', 'enframed', 'frame', 'рамка' ),
		MAG_INT                  => array( 0,    'INT:' ),
		MAG_SITENAME             => array( 1,    'SITENAME', 'НАЗВА_САЙТУ' ),
		MAG_NS                   => array( 0,    'NS:', 'ПН:' ),
		MAG_LOCALURL             => array( 0,    'LOCALURL:', 'ЛЯКАЛЬНЫ_АДРАС:' ),
		MAG_LOCALURLE            => array( 0,    'LOCALURLE:', 'ЛЯКАЛЬНЫ_АДРАС_2:' ),
		MAG_SERVER               => array( 0,    'SERVER', 'СЭРВЭР' ),
		MAG_SERVERNAME           => array( 0,    'SERVERNAME', 'НАЗВА_СЭРВЭРА' ),
		MAG_SCRIPTPATH           => array( 0,    'SCRIPTPATH', 'ШЛЯХ_ДА_СКРЫПТА' ),
		MAG_GRAMMAR              => array( 0,    'GRAMMAR:', 'ГРАМАТЫКА:' ),
		MAG_NOTITLECONVERT       => array( 0,    '__NOTITLECONVERT__', '__NOTC__', '__БЕЗ_КАНВЭРТАЦЫІ_НАЗВЫ__' ),
		MAG_NOCONTENTCONVERT     => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__', '__БЕЗ_КАНВЭРТАЦЫІ_ТЭКСТУ__' ),
		MAG_CURRENTWEEK          => array( 1,    'CURRENTWEEK', 'БЯГУЧЫ_ТЫДЗЕНЬ' ),
		MAG_CURRENTDOW           => array( 1,    'CURRENTDOW', 'БЯГУЧЫ_ДЗЕНЬ_ТЫДНЯ' ),
		MAG_REVISIONID           => array( 1,    'REVISIONID', 'ID_ВЭРСІІ' ),
		MAG_PLURAL               => array( 0,    'PLURAL:', 'МНОЖНЫ_ЛІК:'),
		MAG_FULLURL              => array( 0,    'FULLURL:', 'ПОЎНЫ_АДРАС:' ),
		MAG_FULLURLE             => array( 0,    'FULLURLE:', 'ПОЎНЫ_АДРАС_2:' ),
		MAG_LCFIRST              => array( 0,    'LCFIRST:', 'ПЕРШАЯ_ЛІТАРА_МАЛАЯ:' ),
		MAG_UCFIRST              => array( 0,    'UCFIRST:', 'ПЕРШАЯ_ЛІТАРА_ВЯЛІКАЯ:' ),
		MAG_LC                   => array( 0,    'LC:', 'МАЛЫМІ_ЛІТАРАМІ:' ),
		MAG_UC                   => array( 0,    'UC:', 'ВЯЛІКІМІ_ЛІТАРАМІ:' ),
		MAG_RAW                  => array( 0,    'RAW:', 'НЕАПРАЦАВАНЫ' ),
		MAG_DISPLAYTITLE         => array( 1,    'DISPLAYTITLE', 'АДЛЮСТРАВАНАЯ_НАЗВА' ),
		MAG_RAWSUFFIX            => array( 1,    'R', 'Н' ),
		MAG_NEWSECTIONLINK       => array( 1,    '__NEWSECTIONLINK__', '__СПАСЫЛКА_НА_НОВУЮ_СЭКЦЫЮ__' ),
		MAG_CURRENTVERSION       => array( 1,    'CURRENTVERSION', 'БЯГУЧАЯ_ВЭРСІЯ' ),
		MAG_URLENCODE            => array( 0,    'URLENCODE:' ),
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
