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
	MAG_REDIRECT             => array( 0, '#Preusmeri', '#redirect', '#preusmeri', '#PREUSMERI' ),
	MAG_NOTOC                => array( 0, '__NOTOC__', '__BEZSADRŽAJA__' ),
	MAG_FORCETOC             => array( 0, '__FORCETOC__', '__FORSIRANISADRŽAJ__' ),
	MAG_TOC                  => array( 0, '__TOC__', '__SADRŽAJ__' ),
	MAG_NOEDITSECTION        => array( 0, '__NOEDITSECTION__', '__BEZ_IZMENA__', '__BEZIZMENA__' ),
	MAG_START                => array( 0, '__START__', '__POČETAK__' ),
	MAG_END                  => array( 0, '__END__', '__KRAJ__' ),
	MAG_CURRENTMONTH         => array( 1, 'CURRENTMONTH', 'TRENUTNIMESEC' ),
	MAG_CURRENTMONTHNAME     => array( 1, 'CURRENTMONTHNAME', 'TRENUTNIMESECIME' ),
	MAG_CURRENTMONTHNAMEGEN  => array( 1, 'CURRENTMONTHNAMEGEN', 'TRENUTNIMESECROD' ),
	MAG_CURRENTMONTHABBREV   => array( 1, 'CURRENTMONTHABBREV', 'TRENUTNIMESECSKR' ),
	MAG_CURRENTDAY           => array( 1, 'CURRENTDAY', 'TRENUTNIDAN' ),
	MAG_CURRENTDAYNAME       => array( 1, 'CURRENTDAYNAME', 'TRENUTNIDANIME' ),
	MAG_CURRENTYEAR          => array( 1, 'CURRENTYEAR', 'TRENUTNAGODINA' ),
	MAG_CURRENTTIME          => array( 1, 'CURRENTTIME', 'TRENUTNOVREME' ),
	MAG_NUMBEROFARTICLES     => array( 1, 'NUMBEROFARTICLES', 'BROJČLANAKA' ),
	MAG_NUMBEROFFILES        => array( 1, 'NUMBEROFFILES', 'BROJDATOTEKA', 'BROJFAJLOVA' ),
	MAG_PAGENAME             => array( 1, 'PAGENAME', 'STRANICA' ),
	MAG_PAGENAMEE            => array( 1, 'PAGENAMEE', 'STRANICE' ),
	MAG_NAMESPACE            => array( 1, 'NAMESPACE', 'IMENSKIPROSTOR' ),
	MAG_NAMESPACEE           => array( 1, 'NAMESPACEE', 'IMENSKIPROSTORI' ),
	MAG_FULLPAGENAME         => array( 1, 'FULLPAGENAME', 'PUNOIMESTRANE' ),
	MAG_FULLPAGENAMEE        => array( 1, 'FULLPAGENAMEE', 'PUNOIMESTRANEE' ),
	MAG_MSG                  => array( 0, 'MSG:', 'POR:' ),
	MAG_SUBST                => array( 0, 'SUBST:', 'ZAMENI:' ),
	MAG_MSGNW                => array( 0, 'MSGNW:', 'NVPOR:' ),
	MAG_IMG_THUMBNAIL        => array( 1, 'thumbnail', 'thumb', 'mini' ),
	MAG_IMG_MANUALTHUMB      => array( 1, 'thumbnail=$1', 'thumb=$1', 'mini=$1' ),
	MAG_IMG_RIGHT            => array( 1, 'right', 'desno', 'd' ),
	MAG_IMG_LEFT             => array( 1, 'left', 'levo', 'l' ),
	MAG_IMG_NONE             => array( 1, 'none', 'n', 'bez' ),
	MAG_IMG_WIDTH            => array( 1, '$1px', '$1piskel' , '$1p' ),
	MAG_IMG_CENTER           => array( 1, 'center', 'centre', 'centar', 'c' ),
	MAG_IMG_FRAMED           => array( 1, 'framed', 'enframed', 'frame', 'okvir', 'ram' ),
	MAG_INT                  => array( 0, 'INT:', 'INT:' ),
	MAG_SITENAME             => array( 1, 'SITENAME', 'IMESAJTA' ),
	MAG_NS                   => array( 0, 'NS:', 'IP:' ),
	MAG_LOCALURL             => array( 0, 'LOCALURL:', 'LOKALNAADRESA:' ),
	MAG_LOCALURLE            => array( 0, 'LOCALURLE:', 'LOKALNEADRESE:' ),
	MAG_SERVER               => array( 0, 'SERVER', 'SERVER' ),
	MAG_SERVERNAME           => array( 0, 'SERVERNAME', 'IMESERVERA' ),
	MAG_SCRIPTPATH           => array( 0, 'SCRIPTPATH', 'SKRIPTA' ),
	MAG_GRAMMAR              => array( 0, 'GRAMMAR:', 'GRAMATIKA:' ),
	MAG_NOTITLECONVERT       => array( 0, '__NOTITLECONVERT__', '__NOTC__', '__BEZTC__' ),
	MAG_NOCONTENTCONVERT     => array( 0, '__NOCONTENTCONVERT__', '__NOCC__', '__BEZCC__' ),
	MAG_CURRENTWEEK          => array( 1, 'CURRENTWEEK', 'TRENUTNANEDELjA' ),
	MAG_CURRENTDOW           => array( 1, 'CURRENTDOW', 'TRENUTNIDOV' ),
	MAG_REVISIONID           => array( 1, 'REVISIONID', 'IDREVIZIJE' ),
	MAG_PLURAL               => array( 0, 'PLURAL:', 'MNOŽINA:' ),
	MAG_FULLURL              => array( 0, 'FULLURL:', 'PUNURL:' ),
	MAG_FULLURLE             => array( 0, 'FULLURLE:', 'PUNURLE:' ),
	MAG_LCFIRST              => array( 0, 'LCFIRST:', 'LCPRVI:' ),
	MAG_UCFIRST              => array( 0, 'UCFIRST:', 'UCPRVI:' ),
	MAG_LC                   => array( 0, 'LC:', 'LC:' ),
	MAG_UC                   => array( 0, 'UC:', 'UC:' ),
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
	function getMagicWords()  {
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
