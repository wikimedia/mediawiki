<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesSr_ec = array(
	NS_MEDIA            => "Медија",
	NS_SPECIAL          => "Посебно",
	NS_MAIN             => "",
	NS_TALK             => "Разговор",
	NS_USER             => "Корисник",
	NS_USER_TALK        => "Разговор_са_корисником",
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => ($wgMetaNamespaceTalk ? $wgMetaNamespaceTalk : "Разговор_о_".$wgMetaNamespace ),
	NS_IMAGE            => "Слика",
	NS_IMAGE_TALK       => "Разговор_о_слици",
	NS_MEDIAWIKI        => "МедијаВики",
	NS_MEDIAWIKI_TALK   => "Разговор_о_МедијаВикију",
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Разговор_о_шаблону',
	NS_HELP             => 'Помоћ',
	NS_HELP_TALK        => 'Разговор_о_помоћи',
	NS_CATEGORY         => 'Категорија',
	NS_CATEGORY_TALK    => 'Разговор_о_категорији',
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsSr_ec = array(
 "Никаква", "Причвршћена лево", "Причвршћена десно", "Плутајућа лево"
);

/* private */ $wgSkinNamesSr_ec = array(
 "Обична", "Носталгија", "Келнско плаво", "Педингтон", "Монпарнас"
) + $wgSkinNamesEn;

/* private */ $wgUserTogglesSr_ec = array(
	'nolangconversion',
) + $wgUserTogglesEn;

/* private */ $wgDateFormatsSr_ec = array(
	'Није битно',
	'06:12, 5. јануар 2001.',
	'06:12, 5 јануар 2001',
	'06:12, 05.01.2001.',
	'06:12, 5.1.2001.',
	'06:12, 5. јан 2001.',
	'06:12, 5 јан 2001',
	'6:12, 5. јануар 2001.',
	'6:12, 5 јануар 2001',
	'6:12, 05.01.2001.',
	'6:12, 5.1.2001.',
	'6:12, 5. јан 2001.',
	'6:12, 5 јан 2001',
);

/* NOT USED IN STABLE VERSION */
/* private */ $wgMagicWordsSr_ec = array(
#	ID                                CASE SYNONYMS
	MAG_REDIRECT             => array( 0, '#Преусмери', '#redirect', '#преусмери', '#ПРЕУСМЕРИ' ),
	MAG_NOTOC                => array( 0, '__NOTOC__', '__БЕЗСАДРЖАЈА__' ),
	MAG_FORCETOC             => array( 0, '__FORCETOC__', '__ФОРСИРАНИСАДРЖАЈ__' ),
	MAG_TOC                  => array( 0, '__TOC__', '__САДРЖАЈ__' ),
	MAG_NOEDITSECTION        => array( 0, '__NOEDITSECTION__', '__БЕЗ_ИЗМЕНА__', '__БЕЗИЗМЕНА__' ),
	MAG_START                => array( 0, '__START__', '__ПОЧЕТАК__' ),
	MAG_END                  => array( 0, '__END__', '__КРАЈ__' ),
	MAG_CURRENTMONTH         => array( 1, 'CURRENTMONTH', 'ТРЕНУТНИМЕСЕЦ' ),
	MAG_CURRENTMONTHNAME     => array( 1, 'CURRENTMONTHNAME', 'ТРЕНУТНИМЕСЕЦИМЕ' ),
	MAG_CURRENTMONTHNAMEGEN  => array( 1, 'CURRENTMONTHNAMEGEN', 'ТРЕНУТНИМЕСЕЦРОД' ),
	MAG_CURRENTMONTHABBREV   => array( 1, 'CURRENTMONTHABBREV', 'ТРЕНУТНИМЕСЕЦСКР' ),
	MAG_CURRENTDAY           => array( 1, 'CURRENTDAY', 'ТРЕНУТНИДАН' ),
	MAG_CURRENTDAYNAME       => array( 1, 'CURRENTDAYNAME', 'ТРЕНУТНИДАНИМЕ' ),
	MAG_CURRENTYEAR          => array( 1, 'CURRENTYEAR', 'ТРЕНУТНАГОДИНА' ),
	MAG_CURRENTTIME          => array( 1, 'CURRENTTIME', 'ТРЕНУТНОВРЕМЕ' ),
	MAG_NUMBEROFARTICLES     => array( 1, 'NUMBEROFARTICLES', 'БРОЈЧЛАНАКА' ),
	MAG_NUMBEROFFILES        => array( 1, 'NUMBEROFFILES', 'БРОЈДАТОТЕКА', 'БРОЈФАЈЛОВА' ),
	MAG_PAGENAME             => array( 1, 'PAGENAME', 'СТРАНИЦА' ),
	MAG_PAGENAMEE            => array( 1, 'PAGENAMEE', 'СТРАНИЦЕ' ),
	MAG_NAMESPACE            => array( 1, 'NAMESPACE', 'ИМЕНСКИПРОСТОР' ),
	MAG_NAMESPACEE           => array( 1, 'NAMESPACEE', 'ИМЕНСКИПРОСТОРИ' ),
	MAG_FULLPAGENAME         => array( 1, 'FULLPAGENAME', 'ПУНОИМЕСТРАНЕ' ),
	MAG_FULLPAGENAMEE        => array( 1, 'FULLPAGENAMEE', 'ПУНОИМЕСТРАНЕЕ' ),
	MAG_MSG                  => array( 0, 'MSG:', 'ПОР:' ),
	MAG_SUBST                => array( 0, 'SUBST:', 'ЗАМЕНИ:' ),
	MAG_MSGNW                => array( 0, 'MSGNW:', 'НВПОР:' ),
	MAG_IMG_THUMBNAIL        => array( 1, 'thumbnail', 'thumb', 'мини' ),
	MAG_IMG_MANUALTHUMB      => array( 1, 'thumbnail=$1', 'thumb=$1', 'мини=$1' ),
	MAG_IMG_RIGHT            => array( 1, 'right', 'десно', 'д' ),
	MAG_IMG_LEFT             => array( 1, 'left', 'лево', 'л' ),
	MAG_IMG_NONE             => array( 1, 'none', 'н', 'без' ),
	MAG_IMG_WIDTH            => array( 1, '$1px', '$1пискел' , '$1п' ),
	MAG_IMG_CENTER           => array( 1, 'center', 'centre', 'центар', 'ц' ),
	MAG_IMG_FRAMED           => array( 1, 'framed', 'enframed', 'frame', 'оквир', 'рам' ),
	MAG_INT                  => array( 0, 'INT:', 'ИНТ:' ),
	MAG_SITENAME             => array( 1, 'SITENAME', 'ИМЕСАЈТА' ),
	MAG_NS                   => array( 0, 'NS:', 'ИП:' ),
	MAG_LOCALURL             => array( 0, 'LOCALURL:', 'ЛОКАЛНААДРЕСА:' ),
	MAG_LOCALURLE            => array( 0, 'LOCALURLE:', 'ЛОКАЛНЕАДРЕСЕ:' ),
	MAG_SERVER               => array( 0, 'SERVER', 'СЕРВЕР' ),
	MAG_SERVERNAME           => array( 0, 'SERVERNAME', 'ИМЕСЕРВЕРА' ),
	MAG_SCRIPTPATH           => array( 0, 'SCRIPTPATH', 'СКРИПТА' ),
	MAG_GRAMMAR              => array( 0, 'GRAMMAR:', 'ГРАМАТИКА:' ),
	MAG_NOTITLECONVERT       => array( 0, '__NOTITLECONVERT__', '__NOTC__', '__БЕЗТЦ__' ),
	MAG_NOCONTENTCONVERT     => array( 0, '__NOCONTENTCONVERT__', '__NOCC__', '__БЕЗЦЦ__' ),
	MAG_CURRENTWEEK          => array( 1, 'CURRENTWEEK', 'ТРЕНУТНАНЕДЕЉА' ),
	MAG_CURRENTDOW           => array( 1, 'CURRENTDOW', 'ТРЕНУТНИДОВ' ),
	MAG_REVISIONID           => array( 1, 'REVISIONID', 'ИДРЕВИЗИЈЕ' ),
	MAG_PLURAL               => array( 0, 'PLURAL:', 'МНОЖИНА:' ),
	MAG_FULLURL              => array( 0, 'FULLURL:', 'ПУНУРЛ:' ),
	MAG_FULLURLE             => array( 0, 'FULLURLE:', 'ПУНУРЛЕ:' ),
	MAG_LCFIRST              => array( 0, 'LCFIRST:', 'ЛЦПРВИ:' ),
	MAG_UCFIRST              => array( 0, 'UCFIRST:', 'УЦПРВИ:' ),
	MAG_LC                   => array( 0, 'LC:', 'ЛЦ:' ),
	MAG_UC                   => array( 0, 'UC:', 'УЦ:' ),
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesSr_ec.php');
}

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageSr_ec extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesSr_ec;
		return $wgNamespaceNamesSr_ec;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsSr_ec;
		return $wgQuickbarSettingsSr_ec;
	}

	function getSkinNames() {
		global $wgSkinNamesSr_ec;
		return $wgSkinNamesSr_ec;
	}

	function getDateFormats() {
		global $wgDateFormatsSr_ec;
		return $wgDateFormatsSr_ec;
	}

	function getMessage( $key ) {
		global $wgAllMessagesSr_ec;
		if(array_key_exists($key, $wgAllMessagesSr_ec))
			return $wgAllMessagesSr_ec[$key];
		else
			return parent::getMessage($key);
	}

	/**
	* Exports $wgMagicWordsSr_ec
	* @return array
	*/
	function getMagicWords()  {
		global $wgMagicWordsSr_ec;
		return $wgMagicWordsSr_ec;
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

	/**
	 * @access public
	 * @param mixed  $ts the time format which needs to be turned into a
	 *               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param bool   $adj whether to adjust the time output according to the
	 *               user configured offset ($timecorrection)
	 * @param mixed  $format what format to return, if it's false output the
	 *               default one.
	 * @param string $timecorrection the time offset as returned by
	 *               validateTimeZone() in Special:Preferences
	 * @return string
	 */
	function date( $ts, $adj = false, $format = true, $timecorrection = false ) {

		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }

		$mm = substr( $ts, 4, 2 );
		$m = 0 + $mm;
		$mmmm = $this->getMonthName( $mm );
		$mmm = $this->getMonthAbbreviation( $mm );
		$dd = substr( $ts, 6, 2 );
		$d = 0 + $dd;
		$yyyy =  substr( $ts, 0, 4 );
		$yy =  substr( $ts, 2, 2 );

		switch( $format ) {
			case '2':
			case '8':
				return "$d $mmmm $yyyy";
			case '3':
			case '9':
				return "$dd.$mm.$yyyy.";
			case '4':
			case '10':
				return "$d.$m.$yyyy.";
			case '5':
			case '11':
				return "$d. $mmm $yyyy.";
			case '6':
			case '12':
				return "$d $mmm $yyyy";
			default:
				return "$d. $mmmm $yyyy.";
		}

	}

	/**
	* @access public
	* @param mixed  $ts the time format which needs to be turned into a
	*               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	* @param bool   $adj whether to adjust the time output according to the
	*               user configured offset ($timecorrection)
	* @param mixed  $format what format to return, if it's false output the
	*               default one (default true)
	* @param string $timecorrection the time offset as returned by
	*               validateTimeZone() in Special:Preferences
	* @return string
	*/
	function time( $ts, $adj = false, $format = true, $timecorrection = false ) {

		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }
		$hh = substr( $ts, 8, 2 );
		$h = 0 + $hh;
		$mm = substr( $ts, 10, 2 );
		switch( $format ) {
			case '7':
			case '8':
			case '9':
			case '10':
			case '11':
			case '12':
				return "$h:$mm";
			default:
				return "$hh:$mm";
		}
	}

	/**
	* @access public
	* @param mixed  $ts the time format which needs to be turned into a
	*               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	* @param bool   $adj whether to adjust the time output according to the
	*               user configured offset ($timecorrection)
	* @param mixed  $format what format to return, if it's false output the
	*               default one (default true)
	* @param string $timecorrection the time offset as returned by
	*               validateTimeZone() in Special:Preferences
	* @return string
	*/
	function timeanddate( $ts, $adj = false, $format = true, $timecorrection = false) {
		$datePreference = $this->dateFormat($format);
		return $this->time( $ts, $adj, $datePreference, $timecorrection ) . ', ' . $this->date( $ts, $adj, $datePreference, $timecorrection );

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
