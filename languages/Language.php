<?php
/**
 * @package MediaWiki
 * @subpackage Language
 */

if( defined( 'MEDIAWIKI' ) ) {

#
# In general you should not make customizations in these language files
# directly, but should use the MediaWiki: special namespace to customize
# user interface messages through the wiki.
# See http://meta.wikipedia.org/wiki/MediaWiki_namespace
#
# NOTE TO TRANSLATORS: Do not copy this whole file when making translations!
# A lot of common constants and a base class with inheritable methods are
# defined here, which should not be redefined. See the other LanguageXx.php
# files for examples.
#

#--------------------------------------------------------------------------
# Language-specific text
#--------------------------------------------------------------------------

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( ' ', '_', $wgSitename );

/* private */ $wgNamespaceNamesEn = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_MAIN	            => '',
	NS_TALK	            => 'Talk',
	NS_USER             => 'User',
	NS_USER_TALK        => 'User_talk',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . '_talk',
	NS_IMAGE            => 'Image',
	NS_IMAGE_TALK       => 'Image_talk',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Help_talk',
	NS_CATEGORY         => 'Category',
	NS_CATEGORY_TALK    => 'Category_talk',
);

if(isset($wgExtraNamespaces)) {
	$wgNamespaceNamesEn=$wgNamespaceNamesEn+$wgExtraNamespaces;
}

/* private */ $wgDefaultUserOptionsEn = array(
	'quickbar' 		=> 1,
	'underline' 		=> 2,
	'cols'			=> 80,
	'rows' 			=> 25,
	'searchlimit' 		=> 20,
	'contextlines' 		=> 5,
	'contextchars' 		=> 50,
	'skin' 			=> $wgDefaultSkin,
	'math' 			=> 1,
	'rcdays' 		=> 7,
	'rclimit' 		=> 50,
	'wllimit' 		=> 250,
	'highlightbroken'	=> 1,
	'stubthreshold' 	=> 0,
	'previewontop' 		=> 1,
	'editsection'		=> 1,
	'editsectiononrightclick'=> 0,
	'showtoc'		=> 1,
 	'showtoolbar' 		=> 1,
	'date' 			=> 0,
	'imagesize' 		=> 2,
	'thumbsize'		=> 2,
	'rememberpassword' 	=> 0,
	'enotifwatchlistpages' 	=> 0,
	'enotifusertalkpages' 	=> 1,
	'enotifminoredits' 	=> 0,
	'enotifrevealaddr' 	=> 0,
	'shownumberswatching' 	=> 1,
	'fancysig' 		=> 0,
	'externaleditor' 	=> 0,
	'externaldiff' 		=> 0,
	'showjumplinks'		=> 1,
	'numberheadings'	=> 0,
	'uselivepreview'	=> 0,
	'watchlistdays' 	=> 3.0,
);

/* private */ $wgQuickbarSettingsEn = array(
	'None', 'Fixed left', 'Fixed right', 'Floating left', 'Floating right'
);

/* private */ $wgSkinNamesEn = array(
	'standard' => 'Classic',
	'nostalgia' => 'Nostalgia',
	'cologneblue' => 'Cologne Blue',
	'davinci' => 'DaVinci',
	'mono' => 'Mono',
	'monobook' => 'MonoBook',
	'myskin' => 'MySkin',
	'chick' => 'Chick'
);

/* private */ $wgMathNamesEn = array(
	MW_MATH_PNG => 'mw_math_png',
	MW_MATH_SIMPLE => 'mw_math_simple',
	MW_MATH_HTML => 'mw_math_html',
	MW_MATH_SOURCE => 'mw_math_source',
	MW_MATH_MODERN => 'mw_math_modern',
	MW_MATH_MATHML => 'mw_math_mathml'
);

/**
 * Whether to use user or default setting in Language::date()
 *
 * NOTE: the array string values are no longer important!
 * The actual date format functions are now called for the selection in
 * Special:Preferences, and the 'datedefault' message for MW_DATE_DEFAULT.
 *
 * The array keys make up the set of formats which this language allows
 * the user to select. It's exposed via Language::getDateFormats().
 *
 * @private
 */
$wgDateFormatsEn = array(
	MW_DATE_DEFAULT => 'No preference',
	MW_DATE_DMY => '16:12, 15 January 2001',
	MW_DATE_MDY => '16:12, January 15, 2001',
	MW_DATE_YMD => '16:12, 2001 January 15',
	MW_DATE_ISO => '2001-01-15 16:12:34'
);

/* private */ $wgUserTogglesEn = array(
	'highlightbroken',
	'justify',
	'hideminor',
	'extendwatchlist',
	'usenewrc',
	'numberheadings',
	'showtoolbar',
	'editondblclick',
	'editsection',
	'editsectiononrightclick',
	'showtoc',
	'rememberpassword',
	'editwidth',
	'watchcreations',
	'watchdefault',
	'minordefault',
	'previewontop',
	'previewonfirst',
	'nocache',
	'enotifwatchlistpages',
	'enotifusertalkpages',
	'enotifminoredits',
	'enotifrevealaddr',
	'shownumberswatching',
	'fancysig',
	'externaleditor',
	'externaldiff',
	'showjumplinks',
	'uselivepreview',
	'autopatrol',
	'forceeditsummary',
	'watchlisthideown',
	'watchlisthidebots',
);

/* private */ $wgBookstoreListEn = array(
	'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
	'PriceSCAN' => 'http://www.pricescan.com/books/bookDetail.asp?isbn=$1',
	'Barnes & Noble' => 'http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

# Read language names
global $wgLanguageNames;
/** */
require_once( 'Names.php' );

$wgLanguageNamesEn =& $wgLanguageNames;


/* private */ $wgWeekdayNamesEn = array(
	'sunday', 'monday', 'tuesday', 'wednesday', 'thursday',
	'friday', 'saturday'
);


/* private */ $wgMonthNamesEn = array(
	'january', 'february', 'march', 'april', 'may_long', 'june',
	'july', 'august', 'september', 'october', 'november',
	'december'
);
/* private */ $wgMonthNamesGenEn = array(
	'january-gen', 'february-gen', 'march-gen', 'april-gen', 'may-gen', 'june-gen',
	'july-gen', 'august-gen', 'september-gen', 'october-gen', 'november-gen',
	'december-gen'
);

/* private */ $wgMonthAbbreviationsEn = array(
	'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug',
	'sep', 'oct', 'nov', 'dec'
);

# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
/* private */ $wgMagicWordsEn = array(
#   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0,    '#REDIRECT'              ),
	'notoc'                  => array( 0,    '__NOTOC__'              ),
	'nogallery'  			 => array( 0,    '__NOGALLERY__'          ),
	'forcetoc'               => array( 0,    '__FORCETOC__'           ),
	'toc'                    => array( 0,    '__TOC__'                ),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__'      ),
	'start'                  => array( 0,    '__START__'              ),
	'currentmonth'           => array( 1,    'CURRENTMONTH'           ),
	'currentmonthname'       => array( 1,    'CURRENTMONTHNAME'       ),
	'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN'    ),
	'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV'     ),
	'currentday'             => array( 1,    'CURRENTDAY'             ),
	'currentday2'            => array( 1,    'CURRENTDAY2'            ),
	'currentdayname'         => array( 1,    'CURRENTDAYNAME'         ),
	'currentyear'            => array( 1,    'CURRENTYEAR'            ),
	'currenttime'            => array( 1,    'CURRENTTIME'            ),
	'numberofpages'          => array( 1,    'NUMBEROFPAGES'          ),
	'numberofarticles'       => array( 1,    'NUMBEROFARTICLES'       ),
	'numberoffiles'          => array( 1,    'NUMBEROFFILES'          ),
	'numberofusers'          => array( 1,    'NUMBEROFUSERS'          ),
	'pagename'               => array( 1,    'PAGENAME'               ),
	'pagenamee'              => array( 1,    'PAGENAMEE'              ),
	'namespace'              => array( 1,    'NAMESPACE'              ),
	'namespacee'             => array( 1,    'NAMESPACEE'             ),
	'talkspace'              => array( 1,    'TALKSPACE'              ),
	'talkspacee'             => array( 1,    'TALKSPACEE'              ),
	'subjectspace'           => array( 1,    'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'          => array( 1,    'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'           => array( 1,    'FULLPAGENAME'           ),
	'fullpagenamee'          => array( 1,    'FULLPAGENAMEE'          ),
	'subpagename'            => array( 1,    'SUBPAGENAME'            ),
	'subpagenamee'           => array( 1,    'SUBPAGENAMEE'           ),
	'basepagename'           => array( 1,    'BASEPAGENAME'           ),
	'basepagenamee'          => array( 1,    'BASEPAGENAMEE'          ),
	'talkpagename'           => array( 1,    'TALKPAGENAME'           ),
	'talkpagenamee'          => array( 1,    'TALKPAGENAMEE'          ),
	'subjectpagename'        => array( 1,    'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'       => array( 1,    'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                    => array( 0,    'MSG:'                   ),
	'subst'                  => array( 0,    'SUBST:'                 ),
	'msgnw'                  => array( 0,    'MSGNW:'                 ),
	'end'                    => array( 0,    '__END__'                ),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb'     ),
	'img_manualthumb'        => array( 1,    'thumbnail=$1', 'thumb=$1'),
	'img_right'              => array( 1,    'right'                  ),
	'img_left'               => array( 1,    'left'                   ),
	'img_none'               => array( 1,    'none'                   ),
	'img_width'              => array( 1,    '$1px'                   ),
	'img_center'             => array( 1,    'center', 'centre'       ),
	'img_framed'             => array( 1,    'framed', 'enframed', 'frame' ),
	'int'                    => array( 0,    'INT:'                   ),
	'sitename'               => array( 1,    'SITENAME'               ),
	'ns'                     => array( 0,    'NS:'                    ),
	'localurl'               => array( 0,    'LOCALURL:'              ),
	'localurle'              => array( 0,    'LOCALURLE:'             ),
	'server'                 => array( 0,    'SERVER'                 ),
	'servername'             => array( 0,    'SERVERNAME'             ),
	'scriptpath'             => array( 0,    'SCRIPTPATH'             ),
	'grammar'                => array( 0,    'GRAMMAR:'               ),
	'notitleconvert'         => array( 0,    '__NOTITLECONVERT__', '__NOTC__'),
	'nocontentconvert'       => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__'),
	'currentweek'            => array( 1,    'CURRENTWEEK'            ),
	'currentdow'             => array( 1,    'CURRENTDOW'             ),
	'revisionid'             => array( 1,    'REVISIONID'             ),
	'plural'                 => array( 0,    'PLURAL:'                ),
	'fullurl'                => array( 0,    'FULLURL:'               ),
	'fullurle'               => array( 0,    'FULLURLE:'              ),
	'lcfirst'                => array( 0,    'LCFIRST:'               ),
	'ucfirst'                => array( 0,    'UCFIRST:'               ),
	'lc'                     => array( 0,    'LC:'                    ),
	'uc'                     => array( 0,    'UC:'                    ),
	'raw'                    => array( 0,    'RAW:'                   ),
	'displaytitle'           => array( 1,    'DISPLAYTITLE'           ),
	'rawsuffix'              => array( 1,    'R'                      ),
	'newsectionlink'         => array( 1,    '__NEWSECTIONLINK__'     ),
	'currentversion'         => array( 1,    'CURRENTVERSION'         ),
	'urlencode'              => array( 0,    'URLENCODE:'             ),
	'currenttimestamp'       => array( 1,    'CURRENTTIMESTAMP'       ),
	'directionmark'          => array( 1,    'DIRECTIONMARK', 'DIRMARK' ),
	'language'               => array( 0,    '#LANGUAGE:' ),
	'contentlanguage'        => array( 1,    'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'       => array( 1,    'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'         => array( 1,    'NUMBEROFADMINS' ),
	'formatnum'              => array( 0,    'FORMATNUM' ),

);

if (!$wgCachedMessageArrays) {
	require_once('Messages.php');
}

/* a fake language converter */
class fakeConverter {
	var $mLang;
	function fakeConverter($langobj) {$this->mLang = $langobj;}
	function convert($t, $i) {return $t;}
	function parserConvert($t, $p) {return $t;}
	function getVariants() { return array( $this->mLang->getCode() ); }
	function getPreferredVariant() {return $this->mLang->getCode(); }
	function findVariantLink(&$l, &$n) {}
	function getExtraHashOptions() {return '';}
	function getParsedTitle() {return '';}
	function markNoConversion($text) {return $text;}
	function convertCategoryKey( $key ) {return $key; }
	function convertLinkToAllVariants($text){ return array( $this->mLang->getCode() => $text); }

}

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class Language {
	var $mConverter;
	function __construct() {
		$this->mConverter = new fakeConverter($this);
	}

	/**
	 * Exports the default user options as defined in
	 * $wgDefaultUserOptionsEn, user preferences can override some of these
	 * depending on what's in (Local|Default)Settings.php and some defines.
	 *
	 * @return array
	 */
	function getDefaultUserOptions() {
		global $wgDefaultUserOptionsEn;
		return $wgDefaultUserOptionsEn;
	}

	/**
	 * Exports $wgBookstoreListEn
	 * @return array
	 */
	function getBookstoreList() {
		global $wgBookstoreListEn;
		return $wgBookstoreListEn;
	}

	/**
	 * @return array
	 */
	function getNamespaces() {
		global $wgNamespaceNamesEn;
		return $wgNamespaceNamesEn;
	}

	/**
	 * A convenience function that returns the same thing as
	 * getNamespaces() except with the array values changed to ' '
	 * where it found '_', useful for producing output to be displayed
	 * e.g. in <select> forms.
	 *
	 * @return array
	 */
	function getFormattedNamespaces() {
		$ns = $this->getNamespaces();
		foreach($ns as $k => $v) {
			$ns[$k] = strtr($v, '_', ' ');
		}
		return $ns;
	}

	/**
	 * Get a namespace value by key
	 * <code>
	 * $mw_ns = $wgContLang->getNsText( NS_MEDIAWIKI );
	 * echo $mw_ns; // prints 'MediaWiki'
	 * </code>
	 *
	 * @param int $index the array key of the namespace to return
	 * @return mixed, string if the namespace value exists, otherwise false
	 */
	function getNsText( $index ) {
		$ns = $this->getNamespaces();
		return isset( $ns[$index] ) ? $ns[$index] : false;
	}

	/**
	 * A convenience function that returns the same thing as
	 * getNsText() except with '_' changed to ' ', useful for
	 * producing output.
	 *
	 * @return array
	 */
	function getFormattedNsText( $index ) {
		$ns = $this->getNsText( $index );
		return strtr($ns, '_', ' ');
	}

	/**
	 * Get a namespace key by value, case insensetive.
	 *
	 * @param string $text
	 * @return mixed An integer if $text is a valid value otherwise false
	 */
	function getNsIndex( $text ) {
		$ns = $this->getNamespaces();

		foreach ( $ns as $i => $n ) {
			if ( strcasecmp( $n, $text ) == 0)
				return $i;
		}
		return false;
	}

	/**
	 * short names for language variants used for language conversion links.
	 *
	 * @param string $code
	 * @return string
	 */
	function getVariantname( $code ) {
		return wfMsg( "variantname-$code" );
	}

	function specialPage( $name ) {
		return $this->getNsText(NS_SPECIAL) . ':' . $name;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsEn;
		return $wgQuickbarSettingsEn;
	}

	function getSkinNames() {
		global $wgSkinNamesEn;
		return $wgSkinNamesEn;
	}

	function getMathNames() {
		global $wgMathNamesEn;
		return $wgMathNamesEn;
	}

	function getDateFormats() {
		global $wgDateFormatsEn;
		return $wgDateFormatsEn;
	}

	function getUserToggles() {
		global $wgUserTogglesEn;
		return $wgUserTogglesEn;
	}

	function getUserToggle( $tog ) {
		return wfMsg( "tog-$tog" );
	}

	function getLanguageNames() {
		global $wgLanguageNamesEn;
		return $wgLanguageNamesEn;
	}

	function getLanguageName( $code ) {
		global $wgLanguageNamesEn;
		if ( ! array_key_exists( $code, $wgLanguageNamesEn ) ) {
			return '';
		}
		return $wgLanguageNamesEn[$code];
	}

	function getMonthName( $key ) {
		global $wgMonthNamesEn, $wgContLang;
		// see who called us and use the correct message function
		if( get_class( $wgContLang->getLangObj() ) == get_class( $this ) )
			return wfMsgForContent($wgMonthNamesEn[$key-1]);
		else
			return wfMsg($wgMonthNamesEn[$key-1]);
	}

	/* by default we just return base form */
	function getMonthNameGen( $key ) {
		return $this->getMonthName( $key );
	}

	function getMonthAbbreviation( $key ) {
		global $wgMonthAbbreviationsEn, $wgContLang;
		// see who called us and use the correct message function
		if( get_class( $wgContLang->getLangObj() ) == get_class( $this ) )
			return wfMsgForContent(@$wgMonthAbbreviationsEn[$key-1]);
		else
			return wfMsg(@$wgMonthAbbreviationsEn[$key-1]);
	}

	function getWeekdayName( $key ) {
		global $wgWeekdayNamesEn, $wgContLang;
		// see who called us and use the correct message function
		if( get_class( $wgContLang->getLangObj() ) == get_class( $this ) )
			return wfMsgForContent($wgWeekdayNamesEn[$key-1]);
		else
			return wfMsg($wgWeekdayNamesEn[$key-1]);
	}

	/**
	 * Used by date() and time() to adjust the time output.
	 * @public
	 * @param int   $ts the time in date('YmdHis') format
	 * @param mixed $tz adjust the time by this amount (default false,
	 *                  mean we get user timecorrection setting)
	 * @return int

	 */
	function userAdjust( $ts, $tz = false )	{
		global $wgUser, $wgLocalTZoffset;

		if (!$tz) {
			$tz = $wgUser->getOption( 'timecorrection' );
		}

		# minutes and hours differences:
		$minDiff = 0;
		$hrDiff  = 0;

		if ( $tz === '' ) {
			# Global offset in minutes.
			if( isset($wgLocalTZoffset) ) {
				$hrDiff = $wgLocalTZoffset % 60;
				$minDiff = $wgLocalTZoffset - ($hrDiff * 60);
			}
		} elseif ( strpos( $tz, ':' ) !== false ) {
			$tzArray = explode( ':', $tz );
			$hrDiff = intval($tzArray[0]);
			$minDiff = intval($hrDiff < 0 ? -$tzArray[1] : $tzArray[1]);
		} else {
			$hrDiff = intval( $tz );
		}

		# No difference ? Return time unchanged
		if ( 0 == $hrDiff && 0 == $minDiff ) { return $ts; }

		# Generate an adjusted date
		$t = mktime( (
		  (int)substr( $ts, 8, 2) ) + $hrDiff, # Hours
		  (int)substr( $ts, 10, 2 ) + $minDiff, # Minutes
		  (int)substr( $ts, 12, 2 ), # Seconds
		  (int)substr( $ts, 4, 2 ), # Month
		  (int)substr( $ts, 6, 2 ), # Day
		  (int)substr( $ts, 0, 4 ) ); #Year
		return date( 'YmdHis', $t );
	}

	/**
	 * This is meant to be used by time(), date(), and timeanddate() to get
	 * the date preference they're supposed to use, it should be used in
	 * all children.
	 *
	 *<code>
	 * function timeanddate([...], $format = true) {
	 * 	$datePreference = $this->dateFormat($format);
	 * [...]
	 *</code>
	 *
	 * @param mixed $usePrefs: if true, the user's preference is used
	 *                         if false, the site/language default is used
	 *                         if int/string, assumed to be a format.
	 * @return string
	 */
	function dateFormat( $usePrefs = true ) {
		global $wgUser;

		if( is_bool( $usePrefs ) ) {
			if( $usePrefs ) {
				$datePreference = $wgUser->getOption( 'date' );
			} else {
				$options = $this->getDefaultUserOptions();
				$datePreference = (string)$options['date'];
			}
		} else {
			$datePreference = (string)$usePrefs;
		}

		// return int
		if( $datePreference == '' ) {
			return MW_DATE_DEFAULT;
		}
		
		return $datePreference;
	}

	/**
	 * @public
	 * @param mixed  $ts the time format which needs to be turned into a
	 *               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param bool   $adj whether to adjust the time output according to the
	 *               user configured offset ($timecorrection)
	 * @param mixed  $format true to use user's date format preference
	 * @param string $timecorrection the time offset as returned by
	 *               validateTimeZone() in Special:Preferences
	 * @return string
	 */
	function date( $ts, $adj = false, $format = true, $timecorrection = false ) {
		global $wgUser, $wgAmericanDates;

		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }

		$datePreference = $this->dateFormat( $format );
		if( $datePreference == MW_DATE_DEFAULT ) {
			$datePreference = $wgAmericanDates ? MW_DATE_MDY : MW_DATE_DMY;
		}

		$month = $this->formatMonth( substr( $ts, 4, 2 ), $datePreference );
		$day = $this->formatDay( substr( $ts, 6, 2 ), $datePreference );
		$year = $this->formatNum( substr( $ts, 0, 4 ), true );

		switch( $datePreference ) {
			case MW_DATE_DMY: return "$day $month $year";
			case MW_DATE_YMD: return "$year $month $day";
			case MW_DATE_ISO: return substr($ts, 0, 4). '-' . substr($ts, 4, 2). '-' .substr($ts, 6, 2);
			default: return "$month $day, $year";
		}
	}

	/**
	* @public
	* @param mixed  $ts the time format which needs to be turned into a
	*               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	* @param bool   $adj whether to adjust the time output according to the
	*               user configured offset ($timecorrection)
	* @param mixed  $format true to use user's date format preference
	* @param string $timecorrection the time offset as returned by
	*               validateTimeZone() in Special:Preferences
	* @return string
	*/
	function time( $ts, $adj = false, $format = true, $timecorrection = false ) {
		global $wgUser;

		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }
		$datePreference = $this->dateFormat( $format );

		$sep = $this->timeSeparator( $format );

		$hh = substr( $ts, 8, 2 );
		$mm = substr( $ts, 10, 2 );
		$ss = substr( $ts, 12, 2 );

		if ( $datePreference != MW_DATE_ISO ) {
			$hh = $this->formatNum( $hh, true );
			$mm = $this->formatNum( $mm, true );
			//$ss = $this->formatNum( $ss, true );
			return $hh . $sep . $mm;
		} else {
			return $hh . ':' . $mm . ':' . $ss;
		}
	}

	/**
	 * Default separator character between hours, minutes, and seconds.
	 * Will be used by Language::time() for non-ISO formats.
	 * (ISO will always use a colon.)
	 * @return string
	 */
	function timeSeparator( $format ) {
		return ':';
	}

	/**
	 * String to insert between the time and the date in a combined
	 * string. Should include any relevant whitespace.
	 * @return string
	 */
	function timeDateSeparator( $format ) {
		return ', ';
	}

	/**
	 * Return true if the time should display before the date.
	 * @return bool
	 * @private
	 */
	function timeBeforeDate() {
		return true;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthName( $month );
	}

	function formatDay( $day, $format ) {
		return $this->formatNum( 0 + $day, true );
	}

	/**
	* @public
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
		global $wgUser;

		$datePreference = $this->dateFormat($format);
		switch ( $datePreference ) {
			case MW_DATE_ISO: return $this->date( $ts, $adj, $format, $timecorrection ) . ' ' .
				$this->time( $ts, $adj, $format, $timecorrection );
			default:
				$time = $this->time( $ts, $adj, $format, $timecorrection );
				$sep = $this->timeDateSeparator( $datePreference );
				$date = $this->date( $ts, $adj, $format, $timecorrection );
				return $this->timeBeforeDate( $datePreference )
					? $time . $sep . $date
					: $date . $sep . $time;
		}
	}

	function getMessage( $key ) {
		global $wgAllMessagesEn;
		return @$wgAllMessagesEn[$key];
	}

	function getAllMessages() {
		global $wgAllMessagesEn;
		return $wgAllMessagesEn;
	}

	function iconv( $in, $out, $string ) {
		# For most languages, this is a wrapper for iconv
		return iconv( $in, $out, $string );
	}

	function ucfirst( $string ) {
		# For most languages, this is a wrapper for ucfirst()
		return ucfirst( $string );
	}

	function uc( $str ) {
		return strtoupper( $str );
	}

	function lcfirst( $s ) {
		return strtolower( $s{0} ). substr( $s, 1 );
	}

	function lc( $str ) {
		return strtolower( $str );
	}

	function checkTitleEncoding( $s ) {
		global $wgInputEncoding;

		# Check for UTF-8 URLs; Internet Explorer produces these if you
		# type non-ASCII chars in the URL bar or follow unescaped links.
		$ishigh = preg_match( '/[\x80-\xff]/', $s);
		$isutf = ($ishigh ? preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
		         '[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s ) : true );

		if( ($wgInputEncoding != 'utf-8') and $ishigh and $isutf )
			return @iconv( 'UTF-8', $wgInputEncoding, $s );

		if( ($wgInputEncoding == 'utf-8') and $ishigh and !$isutf )
			return utf8_encode( $s );

		# Other languages can safely leave this function, or replace
		# it with one to detect and convert another legacy encoding.
		return $s;
	}

	/**
	 * Some languages have special punctuation to strip out
	 * or characters which need to be converted for MySQL's
	 * indexing to grok it correctly. Make such changes here.
	 *
	 * @param string $in
	 * @return string
	 */
	function stripForSearch( $in ) {
		return strtolower( $in );
	}

	function convertForSearchResult( $termsArray ) {
		# some languages, e.g. Chinese, need to do a conversion
		# in order for search results to be displayed correctly
		return $termsArray;
	}

	/**
	 * Get the first character of a string. In ASCII, return
	 * first byte of the string. UTF8 and others have to
	 * overload this.
	 *
	 * @param string $s
	 * @return string
	 */
	function firstChar( $s ) {
		return $s[0];
	}

	function initEncoding() {
		# Some languages may have an alternate char encoding option
		# (Esperanto X-coding, Japanese furigana conversion, etc)
		# If this language is used as the primary content language,
		# an override to the defaults can be set here on startup.
		#global $wgInputEncoding, $wgOutputEncoding, $wgEditEncoding;
	}

	function setAltEncoding() {
		# Some languages may have an alternate char encoding option
		# (Esperanto X-coding, Japanese furigana conversion, etc)
		# If 'altencoding' is checked in user prefs, this gives a
		# chance to swap out the default encoding settings.
		#global $wgInputEncoding, $wgOutputEncoding, $wgEditEncoding;
	}

	function recodeForEdit( $s ) {
		# For some languages we'll want to explicitly specify
		# which characters make it into the edit box raw
		# or are converted in some way or another.
		# Note that if wgOutputEncoding is different from
		# wgInputEncoding, this text will be further converted
		# to wgOutputEncoding.
		global $wgInputEncoding, $wgEditEncoding;
		if( $wgEditEncoding == '' or
		  $wgEditEncoding == $wgInputEncoding ) {
			return $s;
		} else {
			return $this->iconv( $wgInputEncoding, $wgEditEncoding, $s );
		}
	}

	function recodeInput( $s ) {
		# Take the previous into account.
		global $wgInputEncoding, $wgOutputEncoding, $wgEditEncoding;
		if($wgEditEncoding != "") {
			$enc = $wgEditEncoding;
		} else {
			$enc = $wgOutputEncoding;
		}
		if( $enc == $wgInputEncoding ) {
			return $s;
		} else {
			return $this->iconv( $enc, $wgInputEncoding, $s );
		}
	}

	/**
	 * For right-to-left language support
	 *
	 * @return bool
	 */
	function isRTL() { return false; }

	/**
	 * A hidden direction mark (LRM or RLM), depending on the language direction
	 *
	 * @return string
	 */
	function getDirMark() { return $this->isRTL() ? "\xE2\x80\x8F" : "\xE2\x80\x8E"; }

	/**
	 * To allow "foo[[bar]]" to extend the link over the whole word "foobar"
	 *
	 * @return bool
	 */
	function linkPrefixExtension() { return false; }

	function &getMagicWords() {
		global $wgMagicWordsEn;
		return $wgMagicWordsEn;
	}

	# Fill a MagicWord object with data from here
	function getMagic( &$mw ) {
		if ( !isset( $this->mMagicExtensions ) ) {
			$this->mMagicExtensions = array();
			wfRunHooks( 'LanguageGetMagic', array( &$this->mMagicExtensions, $this->getCode() ) );
		}
		if ( isset( $this->mMagicExtensions[$mw->mId] ) ) {
			$rawEntry = $this->mMagicExtensions[$mw->mId];
		} else {
			$magicWords =& $this->getMagicWords();
			if ( isset( $magicWords[$mw->mId] ) ) {
				$rawEntry = $magicWords[$mw->mId];
			} else {
				# Fall back to English if local list is incomplete
				$magicWords =& Language::getMagicWords();
				$rawEntry = $magicWords[$mw->mId];
			}
		}

		$mw->mCaseSensitive = $rawEntry[0];
		$mw->mSynonyms = array_slice( $rawEntry, 1 );
	}

	/**
	 * Italic is unsuitable for some languages
	 *
	 * @public
	 *
	 * @param string $text The text to be emphasized.
	 * @return string
	 */
	function emphasize( $text ) {
		return "<em>$text</em>";
	}

	 /**
	 * Normally we output all numbers in plain en_US style, that is
	 * 293,291.235 for twohundredninetythreethousand-twohundredninetyone
	 * point twohundredthirtyfive. However this is not sutable for all
	 * languages, some such as Pakaran want ੨੯੩,੨੯੫.੨੩੫ and others such as
	 * Icelandic just want to use commas instead of dots, and dots instead
	 * of commas like "293.291,235".
	 *
	 * An example of this function being called:
	 * <code>
	 * wfMsg( 'message', $wgLang->formatNum( $num ) )
	 * </code>
	 *
	 * See LanguageGu.php for the Gujarati implementation and
	 * LanguageIs.php for the , => . and . => , implementation.
	 *
	 * @todo check if it's viable to use localeconv() for the decimal
	 *       seperator thing.
	 * @public
	 * @param mixed $number the string to be formatted, should be an integer or
	 *        a floating point number.
	 * @param bool $nocommafy Set to true for special numbers like dates
	 * @return string
	 */
	function formatNum( $number, $nocommafy = false ) {
		global $wgTranslateNumerals;
		if (!$nocommafy) {
			$number = $this->commafy($number);
			$s = $this->separatorTransformTable();
			if (!is_null($s)) { $number = strtr($number, $s); }
		}

		if ($wgTranslateNumerals) {
			$s = $this->digitTransformTable();
			if (!is_null($s)) { $number = strtr($number, $s); }
		}

		return $number;
	}

	/**
	 * Adds commas to a given number
	 *
	 * @param mixed $_
	 * @return string
	 */
	function commafy($_) {
		return strrev((string)preg_replace('/(\d{3})(?=\d)(?!\d*\.)/','$1,',strrev($_)));
	}

	function digitTransformTable() {
		return null;
	}

	function separatorTransformTable() {
		return null;
	}


	/**
	 * For the credit list in includes/Credits.php (action=credits)
	 *
	 * @param array $l
	 * @return string
	 */
	function listToText( $l ) {
		$s = '';
		$m = count($l) - 1;
		for ($i = $m; $i >= 0; $i--) {
			if ($i == $m) {
				$s = $l[$i];
			} else if ($i == $m - 1) {
				$s = $l[$i] . ' ' . wfMsg('and') . ' ' . $s;
			} else {
				$s = $l[$i] . ', ' . $s;
			}
		}
		return $s;
	}

	# Crop a string from the beginning or end to a certain number of bytes.
	# (Bytes are used because our storage has limited byte lengths for some
	# columns in the database.) Multibyte charsets will need to make sure that
	# only whole characters are included!
	#
	# $length does not include the optional ellipsis.
	# If $length is negative, snip from the beginning
	function truncate( $string, $length, $ellipsis = '' ) {
		if( $length == 0 ) {
			return $ellipsis;
		}
		if ( strlen( $string ) <= abs( $length ) ) {
			return $string;
		}
		if( $length > 0 ) {
			$string = substr( $string, 0, $length );
			return $string . $ellipsis;
		} else {
			$string = substr( $string, $length );
			return $ellipsis . $string;
		}
	}

	/**
	 * Grammatical transformations, needed for inflected languages
	 * Invoked by putting {{grammar:case|word}} in a message
	 *
	 * @param string $word
	 * @param string $case
	 * @return string
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms['en'][$case][$word]) ) {
			return $wgGrammarForms['en'][$case][$word];
		}
		return $word;
	}

	/**
	 * Plural form transformations, needed for some languages.
	 * For example, where are 3 form of plural in Russian and Polish,
	 * depending on "count mod 10". See [[w:Plural]]
	 * For English it is pretty simple.
	 *
	 * Invoked by putting {{plural:count|wordform1|wordform2}}
	 * or {{plural:count|wordform1|wordform2|wordform3}}
	 *
	 * Example: {{plural:{{NUMBEROFARTICLES}}|article|articles}}
	 *
	 * @param integer $count
	 * @param string $wordform1
	 * @param string $wordform2
	 * @param string $wordform3 (optional)
	 * @return string
	 */
	function convertPlural( $count, $w1, $w2, $w3) {
		return $count == '1' ? $w1 : $w2;
	}

	/**
	 * For translaing of expiry times
	 * @param string The validated block time in English
	 * @return Somehow translated block time
	 * @see LanguageFi.php for example implementation
	 */
	function translateBlockExpiry( $str ) {

		$scBlockExpiryOptions = wfMsg( 'ipboptions' );

		if ( $scBlockExpiryOptions == '-') {
			return $str;
		}

		foreach (explode(',', $scBlockExpiryOptions) as $option) {
			if ( strpos($option, ":") === false )
				continue;
			list($show, $value) = explode(":", $option);
			if ( strcmp ( $str, $value) == 0 )
				return '<span title="' . htmlspecialchars($str). '">' .
					htmlspecialchars( trim( $show ) ) . '</span>';
		}

		return $str;
	}

	/**
	 * languages like Chinese need to be segmented in order for the diff
	 * to be of any use
	 *
	 * @param string $text
	 * @return string
	 */
	function segmentForDiff( $text ) {
		return $text;
	}

	/**
	 * and unsegment to show the result
	 *
	 * @param string $text
	 * @return string
	 */
	function unsegmentForDiff( $text ) {
		return $text;
	}

	# convert text to different variants of a language.
	function convert( $text, $isTitle = false) {
		return $this->mConverter->convert($text, $isTitle);
	}

	# Convert text from within Parser
	function parserConvert( $text, &$parser ) {
		return $this->mConverter->parserConvert( $text, $parser );
	}

	/**
	 * Perform output conversion on a string, and encode for safe HTML output.
	 * @param string $text
	 * @param bool $isTitle -- wtf?
	 * @return string
	 * @todo this should get integrated somewhere sane
	 */
	function convertHtml( $text, $isTitle = false ) {
		return htmlspecialchars( $this->convert( $text, $isTitle ) );
	}

	function convertCategoryKey( $key ) {
		return $this->mConverter->convertCategoryKey( $key );
	}

	/**
	 * get the list of variants supported by this langauge
	 * see sample implementation in LanguageZh.php
	 *
	 * @return array an array of language codes
	 */
	function getVariants() {
		return $this->mConverter->getVariants();
	}


	function getPreferredVariant() {
		return $this->mConverter->getPreferredVariant();
	}

	/**
	 * if a language supports multiple variants, it is
	 * possible that non-existing link in one variant
	 * actually exists in another variant. this function
	 * tries to find it. See e.g. LanguageZh.php
	 *
	 * @param string $link the name of the link
	 * @param mixed $nt the title object of the link
	 * @return null the input parameters may be modified upon return
	 */
	function findVariantLink( &$link, &$nt ) {
		$this->mConverter->findVariantLink($link, $nt);
	}

	/**
	 * If a language supports multiple variants, converts text
	 * into an array of all possible variants of the text:
	 *  'variant' => text in that variant
	 */

	function convertLinkToAllVariants($text){
		return $this->mConverter->convertLinkToAllVariants($text);
	}


	/**
	 * returns language specific options used by User::getPageRenderHash()
	 * for example, the preferred language variant
	 *
	 * @return string
	 * @public
	 */
	function getExtraHashOptions() {
		return $this->mConverter->getExtraHashOptions();
	}

	/**
	 * for languages that support multiple variants, the title of an
	 * article may be displayed differently in different variants. this
	 * function returns the apporiate title defined in the body of the article.
	 *
	 * @return string
	 */
	function getParsedTitle() {
		return $this->mConverter->getParsedTitle();
	}

	/**
	 * Enclose a string with the "no conversion" tag. This is used by
	 * various functions in the Parser
	 *
	 * @param string $text text to be tagged for no conversion
	 * @return string the tagged text
	*/
	function markNoConversion( $text ) {
		return $this->mConverter->markNoConversion( $text );
	}

	/**
	 * A regular expression to match legal word-trailing characters
	 * which should be merged onto a link of the form [[foo]]bar.
	 *
	 * @return string
	 * @public
	 */
	function linkTrail() {
		return $this->getMessage( 'linktrail' );
	}

	function getLangObj() {
		return $this;
	}

	/**
	 * Get the RFC 3066 code for this language object
	 */
	function getCode() {
		return str_replace( '_', '-', strtolower( substr( get_class( $this ), 8 ) ) );
	}


}

# FIXME: Merge all UTF-8 support code into Language base class.
# We no longer support Latin-1 charset.
require_once( 'LanguageUtf8.php' );

# This should fail gracefully if there's not a localization available
wfSuppressWarnings();
// Preload base classes to work around APC/PHP5 bug
include_once( 'Language' . str_replace( '-', '_', ucfirst( $wgLanguageCode ) ) . '.deps.php' );
include_once( 'Language' . str_replace( '-', '_', ucfirst( $wgLanguageCode ) ) . '.php' );
wfRestoreWarnings();

}
?>
