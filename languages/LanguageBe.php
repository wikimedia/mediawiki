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

/* private */ $wgNamespaceNamesBe = array(
	NS_MEDIA		=> 'Мэдыя',
	NS_SPECIAL		=> 'Спэцыяльныя',
	NS_MAIN			=> '',
	NS_TALK			=> 'Абмеркаваньне',
	NS_USER			=> 'Удзельнік',
	NS_USER_TALK		=> 'Гутаркі_ўдзельніка',
	NS_PROJECT		=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> 'Абмеркаваньне_' . $wgMetaNamespace,
	NS_IMAGE		=> 'Выява',
	NS_IMAGE_TALK		=> 'Абмеркаваньне_выявы',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'Абмеркаваньне_MediaWiki',
	NS_TEMPLATE		=> 'Шаблён',
	NS_TEMPLATE_TALK	=> 'Абмеркаваньне_шаблёну',
	NS_HELP			=> 'Дапамога',
	NS_HELP_TALK		=> 'Абмеркаваньне_дапамогі',
	NS_CATEGORY		=> 'Катэгорыя',
	NS_CATEGORY_TALK	=> 'Абмеркаваньне_катэгорыі'
);

/* private */ $wgQuickbarSettingsBe = array(
	'Не паказваць', 'Замацаваная зьлева', 'Замацаваная справа', 'Рухомая зьлева'
);

/* private */ $wgSkinNamesBe = array(
	'standard' => 'Клясычны',
	'nostalgia' => 'Настальгія',
	'cologneblue' => 'Кёльнскі смутак',
	'davinci' => 'Да Вінчы',
	'mono' => 'Мона',
	'monobook' => 'Монакніга',
	'myskin' => 'MySkin',
	'chick' => 'Цыпа'
) + $wgSkinNamesEn;

/* private */ $wgMagicWordsBe = array(
	MAG_REDIRECT             => array( 0,    '#redirect', '#перанакіраваньне' ),
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
	MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES', 'КОЛЬКАСЬЦЬ_АРТЫКУЛАЎ' ),
	MAG_NUMBEROFFILES        => array( 1,    'NUMBEROFFILES', 'КОЛЬКАСЬЦЬ_ФАЙЛАЎ' ),
	MAG_PAGENAME             => array( 1,    'PAGENAME', 'НАЗВА_СТАРОНКІ' ),
	MAG_PAGENAMEE            => array( 1,    'PAGENAMEE', 'НАЗВА_СТАРОНКІ_2' ),
	MAG_NAMESPACE            => array( 1,    'NAMESPACE', 'ПРАСТОРА_НАЗВАЎ' ),
	MAG_NAMESPACEE           => array( 1,    'NAMESPACEE', 'ПРАСТОРА_НАЗВАЎ_2' ),
	MAG_FULLPAGENAME         => array( 1,    'FULLPAGENAME', 'ПОЎНАЯ_НАЗВА_СТАРОНКІ' ),
	MAG_FULLPAGENAMEE        => array( 1,    'FULLPAGENAMEE', 'ПОЎНАЯ_НАЗВА_СТАРОНКІ_2' ),
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
	MAG_SCRIPTPATH           => array( 0,    'SCRIPTPATH' ),
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
	MAG_RAW                  => array( 0,    'RAW:' ),
) + $wgMagicWordsEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesBe.php');
}

class LanguageBe extends LanguageUtf8 {

	// Namespaces
	function getNamespaces() {
		global $wgNamespaceNamesBe;
		return $wgNamespaceNamesBe;
	}

	// Quickbar
	function getQuickbarSettings() {
		global $wgQuickbarSettingsBe;
		return $wgQuickbarSettingsBe;
	}

	// Skins
	function getSkinNames() {
		global $wgSkinNamesBe;
		return $wgSkinNamesBe;
	}

	// Magic words
	function getMagicWords() {
		global $wgMagicWordsBe;
		return $wgMagicWordsBe;
	}

	function getDateFormats() {
		return $wgDateFormatsBe = array(
			MW_DATE_DEFAULT => MW_DATE_DEFAULT,
			'16:12, 15.01.2001' => '16:12, 15.01.2001',
			MW_DATE_ISO => MW_DATE_ISO
		);
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

	function getMessage( $key ) {
		global $wgAllMessagesBe;
		if( isset( $wgAllMessagesBe[$key] ) ) {
			return $wgAllMessagesBe[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ',.' );
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
}
?>
