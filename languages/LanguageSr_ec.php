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
	'redirect'               => array( 0, '#Преусмери', '#redirect', '#преусмери', '#ПРЕУСМЕРИ' ),
	'notoc'                  => array( 0, '__NOTOC__', '__БЕЗСАДРЖАЈА__' ),
	'forcetoc'               => array( 0, '__FORCETOC__', '__ФОРСИРАНИСАДРЖАЈ__' ),
	'toc'                    => array( 0, '__TOC__', '__САДРЖАЈ__' ),
	'noeditsection'          => array( 0, '__NOEDITSECTION__', '__БЕЗ_ИЗМЕНА__', '__БЕЗИЗМЕНА__' ),
	'start'                  => array( 0, '__START__', '__ПОЧЕТАК__' ),
	'end'                    => array( 0, '__END__', '__КРАЈ__' ),
	'currentmonth'           => array( 1, 'CURRENTMONTH', 'ТРЕНУТНИМЕСЕЦ' ),
	'currentmonthname'       => array( 1, 'CURRENTMONTHNAME', 'ТРЕНУТНИМЕСЕЦИМЕ' ),
	'currentmonthnamegen'    => array( 1, 'CURRENTMONTHNAMEGEN', 'ТРЕНУТНИМЕСЕЦРОД' ),
	'currentmonthabbrev'     => array( 1, 'CURRENTMONTHABBREV', 'ТРЕНУТНИМЕСЕЦСКР' ),
	'currentday'             => array( 1, 'CURRENTDAY', 'ТРЕНУТНИДАН' ),
	'currentdayname'         => array( 1, 'CURRENTDAYNAME', 'ТРЕНУТНИДАНИМЕ' ),
	'currentyear'            => array( 1, 'CURRENTYEAR', 'ТРЕНУТНАГОДИНА' ),
	'currenttime'            => array( 1, 'CURRENTTIME', 'ТРЕНУТНОВРЕМЕ' ),
	'numberofarticles'       => array( 1, 'NUMBEROFARTICLES', 'БРОЈЧЛАНАКА' ),
	'numberoffiles'          => array( 1, 'NUMBEROFFILES', 'БРОЈДАТОТЕКА', 'БРОЈФАЈЛОВА' ),
	'pagename'               => array( 1, 'PAGENAME', 'СТРАНИЦА' ),
	'pagenamee'              => array( 1, 'PAGENAMEE', 'СТРАНИЦЕ' ),
	'namespace'              => array( 1, 'NAMESPACE', 'ИМЕНСКИПРОСТОР' ),
	'namespacee'             => array( 1, 'NAMESPACEE', 'ИМЕНСКИПРОСТОРИ' ),
	'fullpagename'           => array( 1, 'FULLPAGENAME', 'ПУНОИМЕСТРАНЕ' ),
	'fullpagenamee'          => array( 1, 'FULLPAGENAMEE', 'ПУНОИМЕСТРАНЕЕ' ),
	'msg'                    => array( 0, 'MSG:', 'ПОР:' ),
	'subst'                  => array( 0, 'SUBST:', 'ЗАМЕНИ:' ),
	'msgnw'                  => array( 0, 'MSGNW:', 'НВПОР:' ),
	'img_thumbnail'          => array( 1, 'thumbnail', 'thumb', 'мини' ),
	'img_manualthumb'        => array( 1, 'thumbnail=$1', 'thumb=$1', 'мини=$1' ),
	'img_right'              => array( 1, 'right', 'десно', 'д' ),
	'img_left'               => array( 1, 'left', 'лево', 'л' ),
	'img_none'               => array( 1, 'none', 'н', 'без' ),
	'img_width'              => array( 1, '$1px', '$1пискел' , '$1п' ),
	'img_center'             => array( 1, 'center', 'centre', 'центар', 'ц' ),
	'img_framed'             => array( 1, 'framed', 'enframed', 'frame', 'оквир', 'рам' ),
	'int'                    => array( 0, 'INT:', 'ИНТ:' ),
	'sitename'               => array( 1, 'SITENAME', 'ИМЕСАЈТА' ),
	'ns'                     => array( 0, 'NS:', 'ИП:' ),
	'localurl'               => array( 0, 'LOCALURL:', 'ЛОКАЛНААДРЕСА:' ),
	'localurle'              => array( 0, 'LOCALURLE:', 'ЛОКАЛНЕАДРЕСЕ:' ),
	'server'                 => array( 0, 'SERVER', 'СЕРВЕР' ),
	'servername'             => array( 0, 'SERVERNAME', 'ИМЕСЕРВЕРА' ),
	'scriptpath'             => array( 0, 'SCRIPTPATH', 'СКРИПТА' ),
	'grammar'                => array( 0, 'GRAMMAR:', 'ГРАМАТИКА:' ),
	'notitleconvert'         => array( 0, '__NOTITLECONVERT__', '__NOTC__', '__БЕЗТЦ__' ),
	'nocontentconvert'       => array( 0, '__NOCONTENTCONVERT__', '__NOCC__', '__БЕЗЦЦ__' ),
	'currentweek'            => array( 1, 'CURRENTWEEK', 'ТРЕНУТНАНЕДЕЉА' ),
	'currentdow'             => array( 1, 'CURRENTDOW', 'ТРЕНУТНИДОВ' ),
	'revisionid'             => array( 1, 'REVISIONID', 'ИДРЕВИЗИЈЕ' ),
	'plural'                 => array( 0, 'PLURAL:', 'МНОЖИНА:' ),
	'fullurl'                => array( 0, 'FULLURL:', 'ПУНУРЛ:' ),
	'fullurle'               => array( 0, 'FULLURLE:', 'ПУНУРЛЕ:' ),
	'lcfirst'                => array( 0, 'LCFIRST:', 'ЛЦПРВИ:' ),
	'ucfirst'                => array( 0, 'UCFIRST:', 'УЦПРВИ:' ),
	'lc'                     => array( 0, 'LC:', 'ЛЦ:' ),
	'uc'                     => array( 0, 'UC:', 'УЦ:' ),
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
	function &getMagicWords()  {
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
