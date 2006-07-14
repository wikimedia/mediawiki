<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesSr_el = array(
	NS_MEDIA            => "Medija",
	NS_SPECIAL          => "Posebno",
	NS_MAIN             => "",
	NS_TALK             => "Razgovor",
	NS_USER             => "Korisnik",
	NS_USER_TALK        => "Razgovor_sa_korisnikom",
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => ($wgMetaNamespaceTalk ? $wgMetaNamespaceTalk : "Razgovor_o_".$wgMetaNamespace ),
	NS_IMAGE            => "Slika",
	NS_IMAGE_TALK       => "Razgovor_o_slici",
	NS_MEDIAWIKI        => "MedijaViki",
	NS_MEDIAWIKI_TALK   => "Razgovor_o_MedijaVikiju",
	NS_TEMPLATE         => 'Šablon',
	NS_TEMPLATE_TALK    => 'Razgovor_o_šablonu',
	NS_HELP             => 'Pomoć',
	NS_HELP_TALK        => 'Razgovor_o_pomoći',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Razgovor_o_kategoriji',
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsSr_el = array(
 "Nikakva", "Pričvršćena levo", "Pričvršćena desno", "Plutajuća levo"
);

/* private */ $wgSkinNamesSr_el = array(
 "Obična", "Nostalgija", "Kelnsko plavo", "Pedington", "Monparnas"
) + $wgSkinNamesEn;

/* private */ $wgUserTogglesSr_el = array(
	'nolangconversion',
) + $wgUserTogglesEn;

/* private */ $wgDateFormatsSr_el = array(
	'Nije bitno',
	'06:12, 5. januar 2001.',
	'06:12, 5 januar 2001',
	'06:12, 05.01.2001.',
	'06:12, 5.1.2001.',
	'06:12, 5. jan 2001.',
	'06:12, 5 jan 2001',
	'6:12, 5. januar 2001.',
	'6:12, 5 januar 2001',
	'6:12, 05.01.2001.',
	'6:12, 5.1.2001.',
	'6:12, 5. jan 2001.',
	'6:12, 5 jan 2001',
);

/* NOT USED IN STABLE VERSION */
/* private */ $wgMagicWordsSr_el = array(
#	ID                                CASE SYNONYMS
	'redirect'               => array( 0, '#Preusmeri', '#redirect', '#preusmeri', '#PREUSMERI' ),
	'notoc'                  => array( 0, '__NOTOC__', '__BEZSADRŽAJA__' ),
	'forcetoc'               => array( 0, '__FORCETOC__', '__FORSIRANISADRŽAJ__' ),
	'toc'                    => array( 0, '__TOC__', '__SADRŽAJ__' ),
	'noeditsection'          => array( 0, '__NOEDITSECTION__', '__BEZ_IZMENA__', '__BEZIZMENA__' ),
	'start'                  => array( 0, '__START__', '__POČETAK__' ),
	'end'                    => array( 0, '__END__', '__KRAJ__' ),
	'currentmonth'           => array( 1, 'CURRENTMONTH', 'TRENUTNIMESEC' ),
	'currentmonthname'       => array( 1, 'CURRENTMONTHNAME', 'TRENUTNIMESECIME' ),
	'currentmonthnamegen'    => array( 1, 'CURRENTMONTHNAMEGEN', 'TRENUTNIMESECROD' ),
	'currentmonthabbrev'     => array( 1, 'CURRENTMONTHABBREV', 'TRENUTNIMESECSKR' ),
	'currentday'             => array( 1, 'CURRENTDAY', 'TRENUTNIDAN' ),
	'currentdayname'         => array( 1, 'CURRENTDAYNAME', 'TRENUTNIDANIME' ),
	'currentyear'            => array( 1, 'CURRENTYEAR', 'TRENUTNAGODINA' ),
	'currenttime'            => array( 1, 'CURRENTTIME', 'TRENUTNOVREME' ),
	'numberofarticles'       => array( 1, 'NUMBEROFARTICLES', 'BROJČLANAKA' ),
	'numberoffiles'          => array( 1, 'NUMBEROFFILES', 'BROJDATOTEKA', 'BROJFAJLOVA' ),
	'pagename'               => array( 1, 'PAGENAME', 'STRANICA' ),
	'pagenamee'              => array( 1, 'PAGENAMEE', 'STRANICE' ),
	'namespace'              => array( 1, 'NAMESPACE', 'IMENSKIPROSTOR' ),
	'namespacee'             => array( 1, 'NAMESPACEE', 'IMENSKIPROSTORI' ),
	'fullpagename'           => array( 1, 'FULLPAGENAME', 'PUNOIMESTRANE' ),
	'fullpagenamee'          => array( 1, 'FULLPAGENAMEE', 'PUNOIMESTRANEE' ),
	'msg'                    => array( 0, 'MSG:', 'POR:' ),
	'subst'                  => array( 0, 'SUBST:', 'ZAMENI:' ),
	'msgnw'                  => array( 0, 'MSGNW:', 'NVPOR:' ),
	'img_thumbnail'          => array( 1, 'thumbnail', 'thumb', 'mini' ),
	'img_manualthumb'        => array( 1, 'thumbnail=$1', 'thumb=$1', 'mini=$1' ),
	'img_right'              => array( 1, 'right', 'desno', 'd' ),
	'img_left'               => array( 1, 'left', 'levo', 'l' ),
	'img_none'               => array( 1, 'none', 'n', 'bez' ),
	'img_width'              => array( 1, '$1px', '$1piskel' , '$1p' ),
	'img_center'             => array( 1, 'center', 'centre', 'centar', 'c' ),
	'img_framed'             => array( 1, 'framed', 'enframed', 'frame', 'okvir', 'ram' ),
	'int'                    => array( 0, 'INT:', 'INT:' ),
	'sitename'               => array( 1, 'SITENAME', 'IMESAJTA' ),
	'ns'                     => array( 0, 'NS:', 'IP:' ),
	'localurl'               => array( 0, 'LOCALURL:', 'LOKALNAADRESA:' ),
	'localurle'              => array( 0, 'LOCALURLE:', 'LOKALNEADRESE:' ),
	'server'                 => array( 0, 'SERVER', 'SERVER' ),
	'servername'             => array( 0, 'SERVERNAME', 'IMESERVERA' ),
	'scriptpath'             => array( 0, 'SCRIPTPATH', 'SKRIPTA' ),
	'grammar'                => array( 0, 'GRAMMAR:', 'GRAMATIKA:' ),
	'notitleconvert'         => array( 0, '__NOTITLECONVERT__', '__NOTC__', '__BEZTC__' ),
	'nocontentconvert'       => array( 0, '__NOCONTENTCONVERT__', '__NOCC__', '__BEZCC__' ),
	'currentweek'            => array( 1, 'CURRENTWEEK', 'TRENUTNANEDELjA' ),
	'currentdow'             => array( 1, 'CURRENTDOW', 'TRENUTNIDOV' ),
	'revisionid'             => array( 1, 'REVISIONID', 'IDREVIZIJE' ),
	'plural'                 => array( 0, 'PLURAL:', 'MNOŽINA:' ),
	'fullurl'                => array( 0, 'FULLURL:', 'PUNURL:' ),
	'fullurle'               => array( 0, 'FULLURLE:', 'PUNURLE:' ),
	'lcfirst'                => array( 0, 'LCFIRST:', 'LCPRVI:' ),
	'ucfirst'                => array( 0, 'UCFIRST:', 'UCPRVI:' ),
	'lc'                     => array( 0, 'LC:', 'LC:' ),
	'uc'                     => array( 0, 'UC:', 'UC:' ),
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesSr_el.php');
}

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageSr_el extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesSr_el;
		return $wgNamespaceNamesSr_el;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsSr_el;
		return $wgQuickbarSettingsSr_el;
	}

	function getSkinNames() {
		global $wgSkinNamesSr_el;
		return $wgSkinNamesSr_el;
	}

	function getDateFormats() {
		global $wgDateFormatsSr_el;
		return $wgDateFormatsSr_el;
	}

	function getMessage( $key ) {
		global $wgAllMessagesSr_el;
		if(array_key_exists($key, $wgAllMessagesSr_el))
			return $wgAllMessagesSr_el[$key];
		else
			return parent::getMessage($key);
	}

	/**
	* Exports $wgMagicWordsSr_el
	* @return array
	*/
	function &getMagicWords()  {
		global $wgMagicWordsSr_el;
		return $wgMagicWordsSr_el;
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
