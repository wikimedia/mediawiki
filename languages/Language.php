<?php
/**
 * @addtogroup Language
 */

if( !defined( 'MEDIAWIKI' ) ) {
	echo "This file is part of MediaWiki, it is not a valid entry point.\n";
	exit( 1 );
}

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

# Read language names
global $wgLanguageNames;
require_once( dirname(__FILE__) . '/Names.php' ) ;

global $wgInputEncoding, $wgOutputEncoding;

/**
 * These are always UTF-8, they exist only for backwards compatibility
 */
$wgInputEncoding    = "UTF-8";
$wgOutputEncoding	= "UTF-8";

if( function_exists( 'mb_strtoupper' ) ) {
	mb_internal_encoding('UTF-8');
}

/* a fake language converter */
class FakeConverter {
	var $mLang;
	function FakeConverter($langobj) {$this->mLang = $langobj;}
	function convert($t, $i) {return $t;}
	function parserConvert($t, $p) {return $t;}
	function getVariants() { return array( $this->mLang->getCode() ); }
	function getPreferredVariant() {return $this->mLang->getCode(); }
	function findVariantLink(&$l, &$n) {}
	function getExtraHashOptions() {return '';}
	function getParsedTitle() {return '';}
	function markNoConversion($text, $noParse=false) {return $text;}
	function convertCategoryKey( $key ) {return $key; }
	function convertLinkToAllVariants($text){ return array( $this->mLang->getCode() => $text); }
	function armourMath($text){ return $text; }
}

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class Language {
	var $mConverter, $mVariants, $mCode, $mLoaded = false;

	static public $mLocalisationKeys = array( 'fallback', 'namespaceNames',
		'quickbarSettings', 'skinNames', 'mathNames', 
		'bookstoreList', 'magicWords', 'messages', 'rtl', 'digitTransformTable', 
		'separatorTransformTable', 'fallback8bitEncoding', 'linkPrefixExtension',
		'defaultUserOptionOverrides', 'linkTrail', 'namespaceAliases', 
		'dateFormats', 'datePreferences', 'datePreferenceMigrationMap', 
		'defaultDateFormat', 'extraUserToggles', 'specialPageAliases' );

	static public $mMergeableMapKeys = array( 'messages', 'namespaceNames', 'mathNames', 
		'dateFormats', 'defaultUserOptionOverrides', 'magicWords' );

	static public $mMergeableListKeys = array( 'extraUserToggles' );

	static public $mMergeableAliasListKeys = array( 'specialPageAliases' );

	static public $mLocalisationCache = array();

	static public $mWeekdayMsgs = array(
		'sunday', 'monday', 'tuesday', 'wednesday', 'thursday',
		'friday', 'saturday'
	);

	static public $mWeekdayAbbrevMsgs = array(
		'sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'
	);

	static public $mMonthMsgs = array(
		'january', 'february', 'march', 'april', 'may_long', 'june',
		'july', 'august', 'september', 'october', 'november',
		'december'
	);
	static public $mMonthGenMsgs = array(
		'january-gen', 'february-gen', 'march-gen', 'april-gen', 'may-gen', 'june-gen',
		'july-gen', 'august-gen', 'september-gen', 'october-gen', 'november-gen',
		'december-gen'
	);
	static public $mMonthAbbrevMsgs = array(
		'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug',
		'sep', 'oct', 'nov', 'dec'
	);

	/**
	 * Create a language object for a given language code
	 */
	static function factory( $code ) {
		global $IP;
		static $recursionLevel = 0;

		if ( $code == 'en' ) {
			$class = 'Language';
		} else {
			$class = 'Language' . str_replace( '-', '_', ucfirst( $code ) );
			// Preload base classes to work around APC/PHP5 bug
			if ( file_exists( "$IP/languages/classes/$class.deps.php" ) ) {
				include_once("$IP/languages/classes/$class.deps.php");
			}
			if ( file_exists( "$IP/languages/classes/$class.php" ) ) {
				include_once("$IP/languages/classes/$class.php");
			}
		}

		if ( $recursionLevel > 5 ) {
			throw new MWException( "Language fallback loop detected when creating class $class\n" );
		}	

		if( ! class_exists( $class ) ) {
			$fallback = Language::getFallbackFor( $code );
			++$recursionLevel;
			$lang = Language::factory( $fallback );
			--$recursionLevel;
			$lang->setCode( $code );
		} else {
			$lang = new $class;
		}

		return $lang;
	}

	function __construct() {
		$this->mConverter = new FakeConverter($this);
		// Set the code to the name of the descendant
		if ( get_class( $this ) == 'Language' ) {
			$this->mCode = 'en';
		} else {
			$this->mCode = str_replace( '_', '-', strtolower( substr( get_class( $this ), 8 ) ) );
		}
	}

	/**
	 * Hook which will be called if this is the content language.
	 * Descendants can use this to register hook functions or modify globals
	 */
	function initContLang() {}

	/**
	 * @deprecated
	 * @return array
	 */
	function getDefaultUserOptions() {
		return User::getDefaultOptions();
	}

	function getFallbackLanguageCode() {
		$this->load();
		return $this->fallback;
	}

	/**
	 * Exports $wgBookstoreListEn
	 * @return array
	 */
	function getBookstoreList() {
		$this->load();
		return $this->bookstoreList;
	}

	/**
	 * @return array
	 */
	function getNamespaces() {
		$this->load();
		return $this->namespaceNames;
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
		$this->load();
		$lctext = $this->lc($text);
		return isset( $this->mNamespaceIds[$lctext] ) ? $this->mNamespaceIds[$lctext] : false;
	}

	/**
	 * short names for language variants used for language conversion links.
	 *
	 * @param string $code
	 * @return string
	 */
	function getVariantname( $code ) {
		return $this->getMessageFromDB( "variantname-$code" );
	}

	function specialPage( $name ) {
		$aliases = $this->getSpecialPageAliases();
		if ( isset( $aliases[$name][0] ) ) {
			$name = $aliases[$name][0];
		}
		return $this->getNsText(NS_SPECIAL) . ':' . $name;
	}

	function getQuickbarSettings() {
		$this->load();
		return $this->quickbarSettings;
	}

	function getSkinNames() {
		$this->load();
		return $this->skinNames;
	}

	function getMathNames() {
		$this->load();
		return $this->mathNames;
	}

	function getDatePreferences() {
		$this->load();
		return $this->datePreferences;
	}
	
	function getDateFormats() {
		$this->load();
		return $this->dateFormats;
	}

	function getDefaultDateFormat() {
		$this->load();
		return $this->defaultDateFormat;
	}

	function getDatePreferenceMigrationMap() {
		$this->load();
		return $this->datePreferenceMigrationMap;
	}

	function getDefaultUserOptionOverrides() {
		$this->load();
		return $this->defaultUserOptionOverrides;
	}

	function getExtraUserToggles() {
		$this->load();
		return $this->extraUserToggles;
	}

	function getUserToggle( $tog ) {
		return $this->getMessageFromDB( "tog-$tog" );
	}

	/**
	 * Get language names, indexed by code.
	 * If $customisedOnly is true, only returns codes with a messages file
	 */
	public static function getLanguageNames( $customisedOnly = false ) {
		global $wgLanguageNames;
		if ( !$customisedOnly ) {
			return $wgLanguageNames;
		}
		
		global $IP;
		$messageFiles = glob( "$IP/languages/messages/Messages*.php" );
		$names = array();
		foreach ( $messageFiles as $file ) {
			$m = array();
			if( preg_match( '/Messages([A-Z][a-z_]+)\.php$/', $file, $m ) ) {
				$code = str_replace( '_', '-', strtolower( $m[1] ) );
				if ( isset( $wgLanguageNames[$code] ) ) {
					$names[$code] = $wgLanguageNames[$code];
				}
			}
		}
		return $names;
	}

	/**
	 * Ugly hack to get a message maybe from the MediaWiki namespace, if this
	 * language object is the content or user language.
	 */
	function getMessageFromDB( $msg ) {
		global $wgContLang, $wgLang;
		if ( $wgContLang->getCode() == $this->getCode() ) {
			# Content language
			return wfMsgForContent( $msg );
		} elseif ( $wgLang->getCode() == $this->getCode() ) {
			# User language
			return wfMsg( $msg );
		} else {
			# Neither, get from localisation
			return $this->getMessage( $msg );
		}
	}

	function getLanguageName( $code ) {
		global $wgLanguageNames;
		if ( ! array_key_exists( $code, $wgLanguageNames ) ) {
			return '';
		}
		return $wgLanguageNames[$code];
	}

	function getMonthName( $key ) {
		return $this->getMessageFromDB( self::$mMonthMsgs[$key-1] );
	}

	function getMonthNameGen( $key ) {
		return $this->getMessageFromDB( self::$mMonthGenMsgs[$key-1] );
	}

	function getMonthAbbreviation( $key ) {
		return $this->getMessageFromDB( self::$mMonthAbbrevMsgs[$key-1] );
	}

	function getWeekdayName( $key ) {
		return $this->getMessageFromDB( self::$mWeekdayMsgs[$key-1] );
	}

	function getWeekdayAbbreviation( $key ) {
		return $this->getMessageFromDB( self::$mWeekdayAbbrevMsgs[$key-1] );
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
	 * This is a workalike of PHP's date() function, but with better
	 * internationalisation, a reduced set of format characters, and a better 
	 * escaping format.
	 *
	 * Supported format characters are dDjlNwzWFmMntLYyaAgGhHiscrU. See the 
	 * PHP manual for definitions. There are a number of extensions, which 
	 * start with "x":
	 *
	 *    xn   Do not translate digits of the next numeric format character
	 *    xN   Toggle raw digit (xn) flag, stays set until explicitly unset
	 *    xr   Use roman numerals for the next numeric format character
	 *    xx   Literal x
	 *    xg   Genitive month name
	 *
	 * Characters enclosed in double quotes will be considered literal (with
	 * the quotes themselves removed). Unmatched quotes will be considered
	 * literal quotes. Example:
	 *
	 * "The month is" F       => The month is January
	 * i's"                   => 20'11"
	 *
	 * Backslash escaping is also supported.
	 * 
	 * @param string $format
	 * @param string $ts 14-character timestamp
	 *      YYYYMMDDHHMMSS
	 *      01234567890123
	 */
	function sprintfDate( $format, $ts ) {
		$s = '';
		$raw = false;
		$roman = false;
		$unix = false;
		$rawToggle = false;
		for ( $p = 0; $p < strlen( $format ); $p++ ) {
			$num = false;
			$code = $format[$p];
			if ( $code == 'x' && $p < strlen( $format ) - 1 ) {
				$code .= $format[++$p];
			}
			
			switch ( $code ) {
				case 'xx':
					$s .= 'x';
					break;
				case 'xn':
					$raw = true;
					break;
				case 'xN':
					$rawToggle = !$rawToggle;
					break;
				case 'xr':
					$roman = true;
					break;
				case 'xg':
					$s .= $this->getMonthNameGen( substr( $ts, 4, 2 ) );
					break;
				case 'd':
					$num = substr( $ts, 6, 2 );
					break;
				case 'D':
					if ( !$unix ) $unix = wfTimestamp( TS_UNIX, $ts );
					$s .= $this->getWeekdayAbbreviation( date( 'w', $unix ) + 1 );
					break;
				case 'j':
					$num = intval( substr( $ts, 6, 2 ) );
					break;
				case 'l':
					if ( !$unix ) $unix = wfTimestamp( TS_UNIX, $ts );
					$s .= $this->getWeekdayName( date( 'w', $unix ) + 1 );
					break;
				case 'N':
					if ( !$unix ) $unix = wfTimestamp( TS_UNIX, $ts );
					$w = date( 'w', $unix );
					$num = $w ? $w : 7;
					break;
				case 'w':
					if ( !$unix ) $unix = wfTimestamp( TS_UNIX, $ts );
					$num = date( 'w', $unix );
					break;
				case 'z':
					if ( !$unix ) $unix = wfTimestamp( TS_UNIX, $ts );
					$num = date( 'z', $unix );
					break;
				case 'W':
					if ( !$unix ) $unix = wfTimestamp( TS_UNIX, $ts );
					$num = date( 'W', $unix );
					break;					
				case 'F':
					$s .= $this->getMonthName( substr( $ts, 4, 2 ) );
					break;
				case 'm':
					$num = substr( $ts, 4, 2 );
					break;
				case 'M':
					$s .= $this->getMonthAbbreviation( substr( $ts, 4, 2 ) );
					break;
				case 'n':
					$num = intval( substr( $ts, 4, 2 ) );
					break;
				case 't':
					if ( !$unix ) $unix = wfTimestamp( TS_UNIX, $ts );
					$num = date( 't', $unix );
					break;
				case 'L':
					if ( !$unix ) $unix = wfTimestamp( TS_UNIX, $ts );
					$num = date( 'L', $unix );
					break;					
				case 'Y':
					$num = substr( $ts, 0, 4 );
					break;
				case 'y':
					$num = substr( $ts, 2, 2 );
					break;
				case 'a':
					$s .= intval( substr( $ts, 8, 2 ) ) < 12 ? 'am' : 'pm';
					break;
				case 'A':
					$s .= intval( substr( $ts, 8, 2 ) ) < 12 ? 'AM' : 'PM';
					break;
				case 'g':
					$h = substr( $ts, 8, 2 );
					$num = $h % 12 ? $h % 12 : 12;
					break;
				case 'G':
					$num = intval( substr( $ts, 8, 2 ) );
					break;
				case 'h':
					$h = substr( $ts, 8, 2 );
					$num = sprintf( '%02d', $h % 12 ? $h % 12 : 12 );
					break;					
				case 'H':
					$num = substr( $ts, 8, 2 );
					break;
				case 'i':
					$num = substr( $ts, 10, 2 );
					break;
				case 's':
					$num = substr( $ts, 12, 2 );
					break;
				case 'c':
					if ( !$unix ) $unix = wfTimestamp( TS_UNIX, $ts );
					$s .= date( 'c', $unix );
					break;
				case 'r':
					if ( !$unix ) $unix = wfTimestamp( TS_UNIX, $ts );
					$s .= date( 'r', $unix );
					break;
				case 'U':
					if ( !$unix ) $unix = wfTimestamp( TS_UNIX, $ts );
					$num = $unix;
					break;
				case '\\':
					# Backslash escaping
					if ( $p < strlen( $format ) - 1 ) {
						$s .= $format[++$p];
					} else {
						$s .= '\\';
					}
					break;
				case '"':
					# Quoted literal
					if ( $p < strlen( $format ) - 1 ) {
						$endQuote = strpos( $format, '"', $p + 1 );
						if ( $endQuote === false ) {
							# No terminating quote, assume literal "
							$s .= '"';
						} else {
							$s .= substr( $format, $p + 1, $endQuote - $p - 1 );
							$p = $endQuote;
						}
					} else {
						# Quote at end of string, assume literal "
						$s .= '"';
					}
					break;
				default:
					$s .= $format[$p];
			}
			if ( $num !== false ) {
				if ( $rawToggle || $raw ) {
					$s .= $num;
					$raw = false;
				} elseif ( $roman ) {
					$s .= self::romanNumeral( $num );
					$roman = false;
				} else {
					$s .= $this->formatNum( $num, true );
				}
				$num = false;
			}
		}
		return $s;
	}

	/**
	 * Roman number formatting up to 3000
	 */
	static function romanNumeral( $num ) {
		static $table = array(
			array( '', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X' ),
			array( '', 'X', 'XX', 'XXX', 'XL', 'L', 'LX', 'LXX', 'LXXX', 'XC', 'C' ),
			array( '', 'C', 'CC', 'CCC', 'CD', 'D', 'DC', 'DCC', 'DCCC', 'CM', 'M' ),
			array( '', 'M', 'MM', 'MMM' )
		);
			
		$num = intval( $num );
		if ( $num > 3000 || $num <= 0 ) {
			return $num;
		}

		$s = '';
		for ( $pow10 = 1000, $i = 3; $i >= 0; $pow10 /= 10, $i-- ) {
			if ( $num >= $pow10 ) {
				$s .= $table[$i][floor($num / $pow10)];
			}
			$num = $num % $pow10;
		}
		return $s;
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
	 * }
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
				$datePreference = $wgUser->getDatePreference();
			} else {
				$options = User::getDefaultOptions();
				$datePreference = (string)$options['date'];
			}
		} else {
			$datePreference = (string)$usePrefs;
		}

		// return int
		if( $datePreference == '' ) {
			return 'default';
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
		$this->load();
		if ( $adj ) { 
			$ts = $this->userAdjust( $ts, $timecorrection ); 
		}

		$pref = $this->dateFormat( $format );
		if( $pref == 'default' || !isset( $this->dateFormats["$pref date"] ) ) {
			$pref = $this->defaultDateFormat;
		}
		return $this->sprintfDate( $this->dateFormats["$pref date"], $ts );
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
		$this->load();
		if ( $adj ) { 
			$ts = $this->userAdjust( $ts, $timecorrection ); 
		}

		$pref = $this->dateFormat( $format );
		if( $pref == 'default' || !isset( $this->dateFormats["$pref time"] ) ) {
			$pref = $this->defaultDateFormat;
		}
		return $this->sprintfDate( $this->dateFormats["$pref time"], $ts );
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
		$this->load();
		if ( $adj ) { 
			$ts = $this->userAdjust( $ts, $timecorrection ); 
		}

		$pref = $this->dateFormat( $format );
		if( $pref == 'default' || !isset( $this->dateFormats["$pref both"] ) ) {
			$pref = $this->defaultDateFormat;
		}

		return $this->sprintfDate( $this->dateFormats["$pref both"], $ts );
	}

	function getMessage( $key ) {
		$this->load();
		return isset( $this->messages[$key] ) ? $this->messages[$key] : null;
	}

	function getAllMessages() {
		$this->load();
		return $this->messages;
	}

	function iconv( $in, $out, $string ) {
		# For most languages, this is a wrapper for iconv
		return iconv( $in, $out . '//IGNORE', $string );
	}

	// callback functions for uc(), lc(), ucwords(), ucwordbreaks()
	function ucwordbreaksCallbackAscii($matches){
		return $this->ucfirst($matches[1]);
	}
	
	function ucwordbreaksCallbackMB($matches){
		return mb_strtoupper($matches[0]);
	}
	
	function ucCallback($matches){
		list( $wikiUpperChars ) = self::getCaseMaps();
		return strtr( $matches[1], $wikiUpperChars );
	}
	
	function lcCallback($matches){
		list( , $wikiLowerChars ) = self::getCaseMaps();
		return strtr( $matches[1], $wikiLowerChars );
	}
	
	function ucwordsCallbackMB($matches){
		return mb_strtoupper($matches[0]);
	}
	
	function ucwordsCallbackWiki($matches){
		list( $wikiUpperChars ) = self::getCaseMaps();
		return strtr( $matches[0], $wikiUpperChars );
	}

	function ucfirst( $str ) {
		return self::uc( $str, true );
	}

	function uc( $str, $first = false ) {
		if ( function_exists( 'mb_strtoupper' ) ) {
			if ( $first ) {
				if ( self::isMultibyte( $str ) ) {
					return mb_strtoupper( mb_substr( $str, 0, 1 ) ) . mb_substr( $str, 1 );
				} else {
					return ucfirst( $str );
				}
			} else {
				return self::isMultibyte( $str ) ? mb_strtoupper( $str ) : strtoupper( $str );
			}
		} else {
			if ( self::isMultibyte( $str ) ) {
				list( $wikiUpperChars ) = $this->getCaseMaps();
				$x = $first ? '^' : '';
				return preg_replace_callback(
					"/$x([a-z]|[\\xc0-\\xff][\\x80-\\xbf]*)/",
					array($this,"ucCallback"),
					$str
				);
			} else {
				return $first ? ucfirst( $str ) : strtoupper( $str );
			}
		}
	}
	
	function lcfirst( $str ) {
		return self::lc( $str, true );
	}

	function lc( $str, $first = false ) {
		if ( function_exists( 'mb_strtolower' ) )
			if ( $first )
				if ( self::isMultibyte( $str ) )
					return mb_strtolower( mb_substr( $str, 0, 1 ) ) . mb_substr( $str, 1 );
				else
					return strtolower( substr( $str, 0, 1 ) ) . substr( $str, 1 );
			else
				return self::isMultibyte( $str ) ? mb_strtolower( $str ) : strtolower( $str );
		else
			if ( self::isMultibyte( $str ) ) {
				list( , $wikiLowerChars ) = self::getCaseMaps();
				$x = $first ? '^' : '';
				return preg_replace_callback(
					"/$x([A-Z]|[\\xc0-\\xff][\\x80-\\xbf]*)/",
					array($this,"lcCallback"),
					$str
				);
			} else
				return $first ? strtolower( substr( $str, 0, 1 ) ) . substr( $str, 1 ) : strtolower( $str );
	}

	function isMultibyte( $str ) {
		return (bool)preg_match( '/[\x80-\xff]/', $str );
	}

	function ucwords($str) {
		if ( self::isMultibyte( $str ) ) {
			$str = self::lc($str);

			// regexp to find first letter in each word (i.e. after each space)
			$replaceRegexp = "/^([a-z]|[\\xc0-\\xff][\\x80-\\xbf]*)| ([a-z]|[\\xc0-\\xff][\\x80-\\xbf]*)/";

			// function to use to capitalize a single char
			if ( function_exists( 'mb_strtoupper' ) )
				return preg_replace_callback(
					$replaceRegexp,
					array($this,"ucwordsCallbackMB"),
					$str
				);
			else 
				return preg_replace_callback(
					$replaceRegexp,
					array($this,"ucwordsCallbackWiki"),
					$str
				);
		}
		else
			return ucwords( strtolower( $str ) );
	}

  # capitalize words at word breaks
	function ucwordbreaks($str){
		if (self::isMultibyte( $str ) ) {
			$str = self::lc($str);

			// since \b doesn't work for UTF-8, we explicitely define word break chars
			$breaks= "[ \-\(\)\}\{\.,\?!]";

			// find first letter after word break
			$replaceRegexp = "/^([a-z]|[\\xc0-\\xff][\\x80-\\xbf]*)|$breaks([a-z]|[\\xc0-\\xff][\\x80-\\xbf]*)/";

			if ( function_exists( 'mb_strtoupper' ) )
				return preg_replace_callback(
					$replaceRegexp,
					array($this,"ucwordbreaksCallbackMB"),
					$str
				);
			else 
				return preg_replace_callback(
					$replaceRegexp,
					array($this,"ucwordsCallbackWiki"),
					$str
				);
		}
		else
			return preg_replace_callback(
			'/\b([\w\x80-\xff]+)\b/',
			array($this,"ucwordbreaksCallbackAscii"),
			$str );
	}

	/**
	 * Return a case-folded representation of $s
	 *
	 * This is a representation such that caseFold($s1)==caseFold($s2) if $s1 
	 * and $s2 are the same except for the case of their characters. It is not
	 * necessary for the value returned to make sense when displayed.
	 *
	 * Do *not* perform any other normalisation in this function. If a caller
	 * uses this function when it should be using a more general normalisation
	 * function, then fix the caller.
	 */
	function caseFold( $s ) {
		return $this->uc( $s );
	}

	function checkTitleEncoding( $s ) {
		if( is_array( $s ) ) {
			wfDebugDieBacktrace( 'Given array to checkTitleEncoding.' );
		}
		# Check for non-UTF-8 URLs
		$ishigh = preg_match( '/[\x80-\xff]/', $s);
		if(!$ishigh) return $s;

		$isutf8 = preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
                '[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s );
		if( $isutf8 ) return $s;

		return $this->iconv( $this->fallback8bitEncoding(), "utf-8", $s );
	}

	function fallback8bitEncoding() {
		$this->load();
		return $this->fallback8bitEncoding;
	}
	
	/**
	 * Some languages have special punctuation to strip out
	 * or characters which need to be converted for MySQL's
	 * indexing to grok it correctly. Make such changes here.
	 *
	 * @param string $in
	 * @return string
	 */
	function stripForSearch( $string ) {
		global $wgDBtype;
		if ( $wgDBtype != 'mysql' ) {
			return $string;
		}

		# MySQL fulltext index doesn't grok utf-8, so we
		# need to fold cases and convert to hex

		wfProfileIn( __METHOD__ );
		if( function_exists( 'mb_strtolower' ) ) {
			$out = preg_replace(
				"/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
				"'U8' . bin2hex( \"$1\" )",
				mb_strtolower( $string ) );
		} else {
			list( , $wikiLowerChars ) = self::getCaseMaps();
			$out = preg_replace(
				"/([\\xc0-\\xff][\\x80-\\xbf]*)/e",
				"'U8' . bin2hex( strtr( \"\$1\", \$wikiLowerChars ) )",
				$string );
		}
		wfProfileOut( __METHOD__ );
		return $out;
	}

	function convertForSearchResult( $termsArray ) {
		# some languages, e.g. Chinese, need to do a conversion
		# in order for search results to be displayed correctly
		return $termsArray;
	}

	/**
	 * Get the first character of a string. 
	 *
	 * @param string $s
	 * @return string
	 */
	function firstChar( $s ) {
		$matches = array();
		preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
		'[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})/', $s, $matches);

		return isset( $matches[1] ) ? $matches[1] : "";
	}

	function initEncoding() {
		# Some languages may have an alternate char encoding option
		# (Esperanto X-coding, Japanese furigana conversion, etc)
		# If this language is used as the primary content language,
		# an override to the defaults can be set here on startup.
	}

	function recodeForEdit( $s ) {
		# For some languages we'll want to explicitly specify
		# which characters make it into the edit box raw
		# or are converted in some way or another.
		# Note that if wgOutputEncoding is different from
		# wgInputEncoding, this text will be further converted
		# to wgOutputEncoding.
		global $wgEditEncoding;
		if( $wgEditEncoding == '' or
		  $wgEditEncoding == 'UTF-8' ) {
			return $s;
		} else {
			return $this->iconv( 'UTF-8', $wgEditEncoding, $s );
		}
	}

	function recodeInput( $s ) {
		# Take the previous into account.
		global $wgEditEncoding;
		if($wgEditEncoding != "") {
			$enc = $wgEditEncoding;
		} else {
			$enc = 'UTF-8';
		}
		if( $enc == 'UTF-8' ) {
			return $s;
		} else {
			return $this->iconv( $enc, 'UTF-8', $s );
		}
	}

	/**
	 * For right-to-left language support
	 *
	 * @return bool
	 */
	function isRTL() { 
		$this->load();
		return $this->rtl;
	}

	/**
	 * A hidden direction mark (LRM or RLM), depending on the language direction
	 *
	 * @return string
	 */
	function getDirMark() {
		return $this->isRTL() ? "\xE2\x80\x8F" : "\xE2\x80\x8E";
	}

	/**
	 * An arrow, depending on the language direction
	 *
	 * @return string
	 */
	function getArrow() {
		return $this->isRTL() ? '←' : '→';
	}

	/**
	 * To allow "foo[[bar]]" to extend the link over the whole word "foobar"
	 *
	 * @return bool
	 */
	function linkPrefixExtension() {
		$this->load();
		return $this->linkPrefixExtension;
	}

	function &getMagicWords() {
		$this->load();
		return $this->magicWords;
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

		if( !is_array( $rawEntry ) ) {
			error_log( "\"$rawEntry\" is not a valid magic thingie for \"$mw->mId\"" );
		}
		$mw->mCaseSensitive = $rawEntry[0];
		$mw->mSynonyms = array_slice( $rawEntry, 1 );
	}

	/**
	 * Get special page names, as an associative array
	 *   case folded alias => real name
	 */
	function getSpecialPageAliases() {
		$this->load();
		if ( !isset( $this->mExtendedSpecialPageAliases ) ) {
			$this->mExtendedSpecialPageAliases = $this->specialPageAliases;
			wfRunHooks( 'LangugeGetSpecialPageAliases', 
				array( &$this->mExtendedSpecialPageAliases, $this->getCode() ) );
		}
		return $this->mExtendedSpecialPageAliases;
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

	function parseFormattedNumber( $number ) {
		$s = $this->digitTransformTable();
		if (!is_null($s)) { $number = strtr($number, array_flip($s)); }

		$s = $this->separatorTransformTable();
		if (!is_null($s)) { $number = strtr($number, array_flip($s)); }

		$number = strtr( $number, array (',' => '') );
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
		$this->load();
		return $this->digitTransformTable;
	}

	function separatorTransformTable() {
		$this->load();
		return $this->separatorTransformTable;
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
				$s = $l[$i] . ' ' . $this->getMessageFromDB( 'and' ) . ' ' . $s;
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
	function truncate( $string, $length, $ellipsis = "" ) {
		if( $length == 0 ) {
			return $ellipsis;
		}
		if ( strlen( $string ) <= abs( $length ) ) {
			return $string;
		}
		if( $length > 0 ) {
			$string = substr( $string, 0, $length );
			$char = ord( $string[strlen( $string ) - 1] );
			$m = array();
			if ($char >= 0xc0) {
				# We got the first byte only of a multibyte char; remove it.
				$string = substr( $string, 0, -1 );
			} elseif( $char >= 0x80 &&
			          preg_match( '/^(.*)(?:[\xe0-\xef][\x80-\xbf]|' .
			                      '[\xf0-\xf7][\x80-\xbf]{1,2})$/', $string, $m ) ) {
			    # We chopped in the middle of a character; remove it
				$string = $m[1];
			}
			return $string . $ellipsis;
		} else {
			$string = substr( $string, $length );
			$char = ord( $string[0] );
			if( $char >= 0x80 && $char < 0xc0 ) {
				# We chopped in the middle of a character; remove the whole thing
				$string = preg_replace( '/^[\x80-\xbf]+/', '', $string );
			}
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
	 * @param string $wordform4 (optional)
	 * @param string $wordform5 (optional)
	 * @return string
	 */
	function convertPlural( $count, $w1, $w2, $w3, $w4, $w5) {
		return ( $count == '1' || $count == '-1' ) ? $w1 : $w2;
	}

	/**
	 * For translaing of expiry times
	 * @param string The validated block time in English
	 * @param $forContent, avoid html?
	 * @return Somehow translated block time
	 * @see LanguageFi.php for example implementation
	 */
	function translateBlockExpiry( $str, $forContent=false ) {

		$scBlockExpiryOptions = $this->getMessageFromDB( 'ipboptions' );

		if ( $scBlockExpiryOptions == '-') {
			return $str;
		}

		foreach (explode(',', $scBlockExpiryOptions) as $option) {
			if ( strpos($option, ":") === false )
				continue;
			list($show, $value) = explode(":", $option);
			if ( strcmp ( $str, $value) == 0 ) {
				if ( $forContent )
					return htmlspecialchars($str) . htmlspecialchars( trim( $show ) );
				else
					return '<span title="' . htmlspecialchars($str). '">' . htmlspecialchars( trim( $show ) ) . '</span>';
			}
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

	# Check if this is a language with variants
	function hasVariants(){
		return sizeof($this->getVariants())>1;
	}

	# Put custom tags (e.g. -{ }-) around math to prevent conversion
	function armourMath($text){ 
		return $this->mConverter->armourMath($text);
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


	function getPreferredVariant( $fromUser = true ) {
		return $this->mConverter->getPreferredVariant( $fromUser );
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
	function markNoConversion( $text, $noParse=false ) {
		return $this->mConverter->markNoConversion( $text, $noParse );
	}

	/**
	 * A regular expression to match legal word-trailing characters
	 * which should be merged onto a link of the form [[foo]]bar.
	 *
	 * @return string
	 * @public
	 */
	function linkTrail() {
		$this->load();
		return $this->linkTrail;
	}

	function getLangObj() {
		return $this;
	}

	/**
	 * Get the RFC 3066 code for this language object
	 */
	function getCode() {
		return $this->mCode;
	}

	function setCode( $code ) {
		$this->mCode = $code;
	}

	static function getFileName( $prefix = 'Language', $code, $suffix = '.php' ) {
		return $prefix . str_replace( '-', '_', ucfirst( $code ) ) . $suffix;
	}

	static function getMessagesFileName( $code ) {
		global $IP;
		return self::getFileName( "$IP/languages/messages/Messages", $code, '.php' );
	}

	static function getClassFileName( $code ) {
		global $IP;
		return self::getFileName( "$IP/languages/classes/Language", $code, '.php' );
	}
	
	static function getLocalisationArray( $code, $disableCache = false ) {
		self::loadLocalisation( $code, $disableCache );
		return self::$mLocalisationCache[$code];
	}

	/**
	 * Load localisation data for a given code into the static cache
	 *
	 * @return array Dependencies, map of filenames to mtimes
	 */
	static function loadLocalisation( $code, $disableCache = false ) {
		static $recursionGuard = array();
		global $wgMemc;

		if ( !$code ) {
			throw new MWException( "Invalid language code requested" );
		}

		if ( !$disableCache ) {
			# Try the per-process cache
			if ( isset( self::$mLocalisationCache[$code] ) ) {
				return self::$mLocalisationCache[$code]['deps'];
			}

			wfProfileIn( __METHOD__ );

			# Try the serialized directory
			$cache = wfGetPrecompiledData( self::getFileName( "Messages", $code, '.ser' ) );
			if ( $cache ) {
				self::$mLocalisationCache[$code] = $cache;
				wfDebug( "Language::loadLocalisation(): got localisation for $code from precompiled data file\n" );
				wfProfileOut( __METHOD__ );
				return self::$mLocalisationCache[$code]['deps'];
			}

			# Try the global cache
			$memcKey = wfMemcKey('localisation', $code );
			$cache = $wgMemc->get( $memcKey );
			if ( $cache ) {
				# Check file modification times
				foreach ( $cache['deps'] as $file => $mtime ) {
					if ( !file_exists( $file ) || filemtime( $file ) > $mtime ) {
						break;
					}
				}
				if ( self::isLocalisationOutOfDate( $cache ) ) {
					$wgMemc->delete( $memcKey );
					$cache = false;
					wfDebug( "Language::loadLocalisation(): localisation cache for $code had expired due to update of $file\n" );
				} else {
					self::$mLocalisationCache[$code] = $cache;
					wfDebug( "Language::loadLocalisation(): got localisation for $code from cache\n" );
					wfProfileOut( __METHOD__ );
					return $cache['deps'];
				}
			}
		} else {
			wfProfileIn( __METHOD__ );
		}

		# Default fallback, may be overridden when the messages file is included
		if ( $code != 'en' ) {
			$fallback = 'en';
		} else {
			$fallback = false;
		}

		# Load the primary localisation from the source file
		$filename = self::getMessagesFileName( $code );
		if ( !file_exists( $filename ) ) {
			wfDebug( "Language::loadLocalisation(): no localisation file for $code, using implicit fallback to en\n" );
			$cache = array();
			$deps = array();
		} else {
			$deps = array( $filename => filemtime( $filename ) );
			require( $filename );
			$cache = compact( self::$mLocalisationKeys );	
			wfDebug( "Language::loadLocalisation(): got localisation for $code from source\n" );
		}

		if ( !empty( $fallback ) ) {
			# Load the fallback localisation, with a circular reference guard
			if ( isset( $recursionGuard[$code] ) ) {
				throw new MWException( "Error: Circular fallback reference in language code $code" );
			}
			$recursionGuard[$code] = true;
			$newDeps = self::loadLocalisation( $fallback, $disableCache );
			unset( $recursionGuard[$code] );

			$secondary = self::$mLocalisationCache[$fallback];
			$deps = array_merge( $deps, $newDeps );

			# Merge the fallback localisation with the current localisation
			foreach ( self::$mLocalisationKeys as $key ) {
				if ( isset( $cache[$key] ) ) {
					if ( isset( $secondary[$key] ) ) {
						if ( in_array( $key, self::$mMergeableMapKeys ) ) {
							$cache[$key] = $cache[$key] + $secondary[$key];
						} elseif ( in_array( $key, self::$mMergeableListKeys ) ) {
							$cache[$key] = array_merge( $secondary[$key], $cache[$key] );
						} elseif ( in_array( $key, self::$mMergeableAliasListKeys ) ) {
							$cache[$key] = array_merge_recursive( $cache[$key], $secondary[$key] );
						}
					}
				} else {
					$cache[$key] = $secondary[$key];
				}
			}

			# Merge bookstore lists if requested
			if ( !empty( $cache['bookstoreList']['inherit'] ) ) {
				$cache['bookstoreList'] = array_merge( $cache['bookstoreList'], $secondary['bookstoreList'] );
			}
			if ( isset( $cache['bookstoreList']['inherit'] ) ) {
				unset( $cache['bookstoreList']['inherit'] );
			}
		}
		
		# Add dependencies to the cache entry
		$cache['deps'] = $deps;

		# Replace spaces with underscores in namespace names
		$cache['namespaceNames'] = str_replace( ' ', '_', $cache['namespaceNames'] );
		
		# Save to both caches
		self::$mLocalisationCache[$code] = $cache;
		if ( !$disableCache ) {
			$wgMemc->set( $memcKey, $cache );
		}

		wfProfileOut( __METHOD__ );
		return $deps;
	}

	/**
	 * Test if a given localisation cache is out of date with respect to the 
	 * source Messages files. This is done automatically for the global cache
	 * in $wgMemc, but is only done on certain occasions for the serialized 
	 * data file.
	 *
	 * @param $cache mixed Either a language code or a cache array
	 */
	static function isLocalisationOutOfDate( $cache ) {
		if ( !is_array( $cache ) ) {
			self::loadLocalisation( $cache );
			$cache = self::$mLocalisationCache[$cache];
		}
		$expired = false;
		foreach ( $cache['deps'] as $file => $mtime ) {
			if ( !file_exists( $file ) || filemtime( $file ) > $mtime ) {
				$expired = true;
				break;
			}
		}
		return $expired;
	}
	
	/**
	 * Get the fallback for a given language
	 */
	static function getFallbackFor( $code ) {
		self::loadLocalisation( $code );
		return self::$mLocalisationCache[$code]['fallback'];
	}

	/** 
	 * Get all messages for a given language
	 */
	static function getMessagesFor( $code ) {
		self::loadLocalisation( $code );
		return self::$mLocalisationCache[$code]['messages'];
	}

	/** 
	 * Get a message for a given language
	 */
	static function getMessageFor( $key, $code ) {
		self::loadLocalisation( $code );
		return isset( self::$mLocalisationCache[$code]['messages'][$key] ) ? self::$mLocalisationCache[$code]['messages'][$key] : null;
	}

	/**
	 * Load localisation data for this object
	 */
	function load() {
		if ( !$this->mLoaded ) {
			self::loadLocalisation( $this->getCode() );
			$cache =& self::$mLocalisationCache[$this->getCode()];
			foreach ( self::$mLocalisationKeys as $key ) {
				$this->$key = $cache[$key];
			}
			$this->mLoaded = true;

			$this->fixUpSettings();
		}
	}

	/**
	 * Do any necessary post-cache-load settings adjustment
	 */
	function fixUpSettings() {
		global $wgExtraNamespaces, $wgMetaNamespace, $wgMetaNamespaceTalk,
			$wgNamespaceAliases, $wgAmericanDates;
		wfProfileIn( __METHOD__ );
		if ( $wgExtraNamespaces ) {
			$this->namespaceNames = $wgExtraNamespaces + $this->namespaceNames;
		}

		$this->namespaceNames[NS_PROJECT] = $wgMetaNamespace;
		if ( $wgMetaNamespaceTalk ) {
			$this->namespaceNames[NS_PROJECT_TALK] = $wgMetaNamespaceTalk;
		} else {
			$talk = $this->namespaceNames[NS_PROJECT_TALK];
			$talk = str_replace( '$1', $wgMetaNamespace, $talk );

			# Allow grammar transformations
			# Allowing full message-style parsing would make simple requests 
			# such as action=raw much more expensive than they need to be. 
			# This will hopefully cover most cases.
			$talk = preg_replace_callback( '/{{grammar:(.*?)\|(.*?)}}/i', 
				array( &$this, 'replaceGrammarInNamespace' ), $talk );
			$talk = str_replace( ' ', '_', $talk );
			$this->namespaceNames[NS_PROJECT_TALK] = $talk;
		}
		
		# The above mixing may leave namespaces out of canonical order.
		# Re-order by namespace ID number...
		ksort( $this->namespaceNames );

		# Put namespace names and aliases into a hashtable.
		# If this is too slow, then we should arrange it so that it is done 
		# before caching. The catch is that at pre-cache time, the above
		# class-specific fixup hasn't been done.
		$this->mNamespaceIds = array();
		foreach ( $this->namespaceNames as $index => $name ) {
			$this->mNamespaceIds[$this->lc($name)] = $index;
		}
		if ( $this->namespaceAliases ) {
			foreach ( $this->namespaceAliases as $name => $index ) {
				$this->mNamespaceIds[$this->lc($name)] = $index;
			}
		}
		if ( $wgNamespaceAliases ) {
			foreach ( $wgNamespaceAliases as $name => $index ) {
				$this->mNamespaceIds[$this->lc($name)] = $index;
			}
		}

		if ( $this->defaultDateFormat == 'dmy or mdy' ) {
			$this->defaultDateFormat = $wgAmericanDates ? 'mdy' : 'dmy';
		}
		wfProfileOut( __METHOD__ );
	}

	function replaceGrammarInNamespace( $m ) {
		return $this->convertGrammar( trim( $m[2] ), trim( $m[1] ) );
	}

	static function getCaseMaps() {
		static $wikiUpperChars, $wikiLowerChars;
		if ( isset( $wikiUpperChars ) ) {
			return array( $wikiUpperChars, $wikiLowerChars );
		}

		wfProfileIn( __METHOD__ );
		$arr = wfGetPrecompiledData( 'Utf8Case.ser' );
		if ( $arr === false ) {
			throw new MWException( 
				"Utf8Case.ser is missing, please run \"make\" in the serialized directory\n" );
		}
		extract( $arr );
		wfProfileOut( __METHOD__ );
		return array( $wikiUpperChars, $wikiLowerChars );
	}
}

?>
