<?php

require_once( "LanguageUtf8.php" );

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( ' ', '_', $wgSitename );

/* private */ $wgNamespaceNamesnv = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_MAIN	            => '',
	NS_TALK	            => 'Naaltsoos_baa_yinísht\'į́',
	NS_USER             => 'Choinish\'įįhí',
	NS_USER_TALK        => 'Choinish\'įįhí_baa_yinísht\'į́',
	NS_WIKIPEDIA        => 'Wikiibíídiiya',
	NS_WIKIPEDIA_TALK   => 'Wikiibíídiiya_baa_yinísht\'į́',
	NS_IMAGE            => 'E\'elyaaígíí',
	NS_IMAGE_TALK       => 'E\'elyaaígíí_baa_yinísht\'į́',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_baa_yinísht\'į́',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'Aná\'álwo\'',
	NS_HELP_TALK        => 'Aná\'álwo\'_baa_yinísht\'į́',
	NS_CATEGORY         => 'T\'ááłáhági_át\'éego',
	NS_CATEGORY_TALK    => 'T\'ááłáhági_át\'éego_baa_yinísht\'į́'
);

/* private */ $wgDefaultUserOptionsnv = array(
	'quickbar' => 1, 'underline' => 1, 'hover' => 1,
	'cols' => 80, 'rows' => 25, 'searchlimit' => 20,
	'contextlines' => 5, 'contextchars' => 50,
	'skin' => $wgDefaultSkin, 'math' => 1, 'rcdays' => 7, 'rclimit' => 50,
	'highlightbroken' => 1, 'stubthreshold' => 0,
	'previewontop' => 1, 'editsection'=>1,'editsectiononrightclick'=>0, 'showtoc'=>1,
	'showtoolbar' =>1,
	'date' => 0
);

/* private */ $wgQuickbarSettingsnv = array(
	'None', 'Fixed left', 'Fixed right', 'Floating left'
);

/* private */ $wgSkinNamesnv = array(
	'standard' => 'Standard',
	'nostalgia' => 'Nostalgia',
	'cologneblue' => 'Cologne Blue',
	'davinci' => 'DaVinci',
	'mono' => 'Łáa\'ígíí',
	'monobook' => 'NaaltsoosŁáa\'ígíí',
 'myskin' => 'MySkin'
);

define( "MW_MATH_PNG",    0 );
define( "MW_MATH_SIMPLE", 1 );
define( "MW_MATH_HTML",   2 );
define( "MW_MATH_SOURCE", 3 );
define( "MW_MATH_MODERN", 4 );
define( "MW_MATH_MATHML", 5 );

/* private */ $wgMathNamesnv = array(
	MW_MATH_PNG => 'Always render PNG',
	MW_MATH_SIMPLE => 'HTML if very simple or else PNG',
	MW_MATH_HTML => 'HTML if possible or else PNG',
	MW_MATH_SOURCE => 'Leave it as TeX (for text browsers)',
	MW_MATH_MODERN => 'Recommended for modern browsers',
	MW_MATH_MATHML => 'MathML if possible (experimental)',
);

/* private */ $wgDateFormatsnv = array(
	'No preference',
	'Yas Niłt\'ees 15, 2001',
	'15 Yas Niłt\'ees 2001',
	'2001 Yas Niłt\'ees 15',
	'2001-01-15'
);

/* private */ $wgUserTogglesnv = array(
	'hover'		=> 'Show hoverbox over wiki links',
	'underline' => 'Biyaadi iissoh',
	'highlightbroken' => 'Format broken links <a href="" class="new">like
this</a> (alternative: like this<a href="" class="internal">?</a>).',
	'justify'	=> 'Justify paragraphs',
	'hideminor' => 'Hide minor edits in recent changes',
	'usenewrc' => 'Enhanced recent changes (not for all browsers)',
	'numberheadings' => 'Auto-number headings',
	'showtoolbar'=>'Show edit toolbar',
	'editondblclick' => 'Edit pages on double click (JavaScript)',
	'editsection'=>'Enable section editing via [edit] links',
	'editsectiononrightclick'=>'Enable section editing by right clicking<br /> on section titles (JavaScript)',
	'showtoc'=>'Hián-sī bo̍k-lo̍k<br />(3 ê piau-tê í-siōng ê ia̍h)',
	'rememberpassword' => 'Kì tiâu góa ê bi̍t-bé (across sessions)',
	'editwidth' => 'Pian-chi̍p keh-á thián hō· khui',
	'watchdefault' => 'Kā lí pian-chi̍p ê ia̍h ka-ji̍p kàm-sī-toaⁿ',
	'minordefault' => 'Siat-sú só·-ū ê pian-chi̍p lóng sió siu-kái',
	'previewontop' => 'Show preview before edit box and not after it',
	'nocache' => 'Naaltsoos doo nooh yishchí da'
);

/* private */ $wgBookstoreListnv = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1",
	//"ISBN" => "$1"
);

# Read language names
global $wgLanguageNames;
require_once( "Names.php" );

$wgLanguageNamesnv =& $wgLanguageNames;


/* private */ $wgWeekdayNamesnv = array(
	'Damóogo', 'Damóo biiskání', 'Damóodóó naakiską́o', 'Damóodóó tágí jį́', 'Damóodóó dį́į́\' yiską́o',
	'Nda\'iiníísh', 'Yiską́ damóo'
);

/* private */ $wgWeekdayAbbreviationsJa = array(
        "Dam", "Dm1", "Dm2", "Dm3", "Dm4", "Nda", "Ysk"
);

/* private */ $wgMonthNamesnv = array(
	'Yas Niłt\'ees', 'Atsá Biyáázh', 'Wóózhch\'į́į́d', 'T\'ą́ą́chil', 'T\'ą́ą́tsoh', 'Ya\'iishjááshchilí',
	'Ya\'iishjáástsoh', 'Bini\'ant\'ą́ą́ts\'ózí', 'Bini\'ant\'ą́ą́tsoh', 'Ghąąjį', 'Níłch\'its\'ósí',
	'Níłch\'itsoh'
);

/* private */ $wgMonthAbbreviationsnv = array(
	'Ynts', 'Atsb', 'Wozh', 'Tchi', 'Ttso', 'Yjsh', 'Yjts', 'Btsz',
	'Btsx', 'Ghąj', 'Ntss', 'Ntsx'
);

# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
/* private */ $wgMagicWordsnv = array(
#   ID                                 CASE  SYNONYMS
    MAG_REDIRECT             => array( 0,    '#redirect'              ),
    MAG_NOTOC                => array( 0,    '__NOTOC__'              ),
    MAG_FORCETOC             => array( 0,    '__FORCETOC__'           ),
    MAG_NOEDITSECTION        => array( 0,    '__NOEDITSECTION__'      ),
    MAG_START                => array( 0,    '__START__'              ),
    MAG_CURRENTMONTH         => array( 1,    'CURRENTMONTH'           ),
    MAG_CURRENTMONTHNAME     => array( 1,    'CURRENTMONTHNAME'       ),
    MAG_CURRENTDAY           => array( 1,    'CURRENTDAY'             ),
    MAG_CURRENTDAYNAME       => array( 1,    'CURRENTDAYNAME'         ),
    MAG_CURRENTYEAR          => array( 1,    'CURRENTYEAR'            ),
    MAG_CURRENTTIME          => array( 1,    'CURRENTTIME'            ),
    MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES'       ),
    MAG_CURRENTMONTHNAMEGEN  => array( 1,    'CURRENTMONTHNAMEGEN'    ),
		MAG_PAGENAME             => array( 1,    'PAGENAME'               ),
		MAG_NAMESPACE            => array( 1,    'NAMESPACE'              ),
	MAG_MSG                  => array( 0,    'MSG:'                   ),
	MAG_SUBST                => array( 0,    'SUBST:'                 ),
    MAG_MSGNW                => array( 0,    'MSGNW:'                 ),
	MAG_END                  => array( 0,    '__END__'                ),
    MAG_IMG_THUMBNAIL        => array( 1,    'thumbnail', 'thumb'     ),
    MAG_IMG_RIGHT            => array( 1,    'right'                  ),
    MAG_IMG_LEFT             => array( 1,    'left'                   ),
    MAG_IMG_NONE             => array( 1,    'none'                   ),
    MAG_IMG_WIDTH            => array( 1,    '$1px'                   ),
    MAG_IMG_CENTER           => array( 1,    'center', 'centre'       ),
    MAG_IMG_FRAMED	     => array( 1,    'framed', 'enframed', 'frame' ),
    MAG_INT                  => array( 0,    'INT:'                   ),
    MAG_SITENAME             => array( 1,    'SITENAME'               ),
    MAG_NS                   => array( 0,    'NS:'                    ),
	MAG_LOCALURL             => array( 0,    'LOCALURL:'              ),
	MAG_LOCALURLE            => array( 0,    'LOCALURLE:'             ),
	MAG_SERVER               => array( 0,    'SERVER'                 )
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------
# Allowed characters in keys are: A-Z, a-z, 0-9, underscore (_) and
# hyphen (-). If you need more characters, you may be able to change
# the regex in MagicWord::initRegex

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => ""

# NOTE: To turn off "Disclaimers" in the title links,
# set "disclaimers" => ""

# NOTE: To turn off "Community portal" in the title links,
# set "portal" => ""

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class Language {
	function Language(){
		# Copies any missing values in the specified arrays from En to the current language
		$fillin = array( 'wgSysopSpecialPages', 'wgValidSpecialPages', 'wgDeveloperSpecialPages' );
		$name = get_class( $this );
		if( strpos( $name, 'language' ) == 0){
			$lang = ucfirst( substr( $name, 8 ) );
			foreach( $fillin as $arrname ){
				$langver = "{$arrname}{$lang}";
				$enver = "{$arrname}En";
				if( ! isset( $GLOBALS[$langver] ) || ! isset( $GLOBALS[$enver] ))
					continue;
				foreach($GLOBALS[$enver] as $spage => $text){
					if( ! isset( $GLOBALS[$langver][$spage] ) )
						$GLOBALS[$langver][$spage] = $text;
				}
			}
		}
	}

	function getDefaultUserOptions () {
		global $wgDefaultUserOptionsnv ;
		return $wgDefaultUserOptionsnv ;
	}

	function getBookstoreList () {
		global $wgBookstoreListnv ;
		return $wgBookstoreListnv ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesnv;
		return $wgNamespaceNamesnv;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesnv;
		return $wgNamespaceNamesnv[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesnv;

		foreach ( $wgNamespaceNamesnv as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function specialPage( $name ) {
		return $this->getNsText( Namespace::getSpecial() ) . ':' . $name;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsnv;
		return $wgQuickbarSettingsnv;
	}

	function getSkinNames() {
		global $wgSkinNamesnv;
		return $wgSkinNamesnv;
	}

	function getMathNames() {
		global $wgMathNamesnv;
		return $wgMathNamesnv;
	}

	function getDateFormats() {
		global $wgDateFormatsnv;
		return $wgDateFormatsnv;
	}

	function getUserToggles() {
		global $wgUserTogglesnv;
		return $wgUserTogglesnv;
	}

	function getUserToggle( $tog ) {
		$togs =& $this->getUserToggles();
		return $togs[$tog];
	}

	function getLanguageNames() {
		global $wgLanguageNamesnv;
		return $wgLanguageNamesnv;
	}

	function getLanguageName( $code ) {
		global $wgLanguageNamesnv;
		if ( ! array_key_exists( $code, $wgLanguageNamesnv ) ) {
			return "";
		}
		return $wgLanguageNamesnv[$code];
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesnv;
		return $wgMonthNamesnv[$key-1];
	}

	/* by default we just return base form */
	function getMonthNameGen( $key )
	{
		return $this->getMonthName( $key );
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsnv;
		return @$wgMonthAbbreviationsnv[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesnv;
		return $wgWeekdayNamesnv[$key-1];
	}

	function userAdjust( $ts )
	{
		global $wgUser, $wgLocalTZoffset;

		$tz = $wgUser->getOption( 'timecorrection' );
		if ( $tz === '' ) {
			$hrDiff = isset( $wgLocalTZoffset ) ? $wgLocalTZoffset : 0;
			$minDiff = 0;
		} elseif ( strpos( $tz, ":" ) !== false ) {
			$tzArray = explode( ":", $tz );
			$hrDiff = intval($tzArray[0]);
			$minDiff = intval($hrDiff < 0 ? -$tzArray[1] : $tzArray[1]);
		} else {
			$hrDiff = intval( $tz );
		}
		if ( 0 == $hrDiff && 0 == $minDiff ) { return $ts; }

		$t = mktime( (
		  (int)substr( $ts, 8, 2) ) + $hrDiff, # Hours
		  (int)substr( $ts, 10, 2 ) + $minDiff, # Minutes
		  (int)substr( $ts, 12, 2 ), # Seconds
		  (int)substr( $ts, 4, 2 ), # Month
		  (int)substr( $ts, 6, 2 ), # Day
		  (int)substr( $ts, 0, 4 ) ); #Year
		return date( 'YmdHis', $t );
	}

	function date( $ts, $adj = false )
	{
		global $wgAmericanDates, $wgUser, $wgUseDynamicDates;

		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		if ( $wgUseDynamicDates ) {
			$datePreference = $wgUser->getOption( 'date' );
			if ( $datePreference == 0 ) {
				$datePreference = $wgAmericanDates ? 1 : 2;
			}
		} else {
			$datePreference = $wgAmericanDates ? 1 : 2;
		}

		$month = $this->getMonthAbbreviation( substr( $ts, 4, 2 ) );
		$day = $this->formatNum( 0 + substr( $ts, 6, 2 ) );
		$year = $this->formatNum( substr( $ts, 0, 4 ) );

		switch( $datePreference ) {
			case 1: return "$month $day, $year";
			case 2: return "$day $month $year";
			default: return "$year $month $day";
		}
	}

	function time( $ts, $adj = false, $seconds = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ':' . substr( $ts, 10, 2 );
		if ( $seconds ) {
			$t .= ':' . substr( $ts, 12, 2 );
		}
		return $this->formatNum( $t );
	}

	function timeanddate( $ts, $adj = false )
	{
		return $this->time( $ts, $adj ) . ', ' . $this->date( $ts, $adj );
	}

	function rfc1123( $ts )
	{
		return date( 'D, d M Y H:i:s T', $ts );
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesnv;
		return $wgValidSpecialPagesnv;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesnv;
		return $wgSysopSpecialPagesnv;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesnv;
		return $wgDeveloperSpecialPagesnv;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesnv;
		return @$wgAllMessagesnv[$key];
	}

	function getAllMessages()
	{
		global $wgAllMessagesnv;
		return $wgAllMessagesnv;
	}

	function iconv( $in, $out, $string ) {
		# For most languages, this is a wrapper for iconv
		return iconv( $in, $out, $string );
	}

	function ucfirst( $string ) {
		# For most languages, this is a wrapper for ucfirst()
		return ucfirst( $string );
	}

	function lcfirst( $s ) {
		return strtolower( $s{0}  ). substr( $s, 1 );
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

	function stripForSearch( $in ) {
		# Some languages have special punctuation to strip out
		# or characters which need to be converted for MySQL's
		# indexing to grok it correctly. Make such changes here.
		return $in;
	}

	function firstChar( $s ) {
		# Get the first character of a string. In ASCII, return
		# first byte of the string. UTF8 and others have to
		# overload this.
		return $s[0];
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

	# For right-to-left language support
	function isRTL() { return false; }

	# To allow "foo[[bar]]" to extend the link over the whole word "foobar"
	function linkPrefixExtension() { return false; }


	function &getMagicWords()
	{
		global $wgMagicWordsnv;
		return $wgMagicWordsnv;
	}

	# Fill a MagicWord object with data from here
	function getMagic( &$mw )
	{
		$raw =& $this->getMagicWords();
		if( !isset( $raw[$mw->mId] ) ) {
			# Fall back to English if local list is incomplete
			$raw =& Language::getMagicWords();
		}
		$rawEntry = $raw[$mw->mId];
		$mw->mCaseSensitive = $rawEntry[0];
		$mw->mSynonyms = array_slice( $rawEntry, 1 );
	}

	# Italic is unsuitable for some languages
	function emphasize( $text )
	{
		return '<em>'.$text.'</em>';
	}


	# Normally we use the plain ASCII digits. Some languages such as Arabic will
	# want to output numbers using script-appropriate characters: override this
	# function with a translator. See LanguageAr.php for an example.
	function formatNum( $number ) {
		return $number;
	}

        function listToText( $l ) {
	        $s = '';
	        $m = count($l) - 1;
	        for ($i = $m; $i >= 0; $i--) {
		    if ($i == $m) {
			$s = $l[$i];
		    } else if ($i == $m - 1) {
			$s = $l[$i] . ' ' . $this->getMessage('and') . ' ' . $s;
		    } else {
			$s = $l[$i] . ', ' . $s;
		    }
		}
	        return $s;
	}
}

# This should fail gracefully if there's not a localization available
@include_once( 'Language' . ucfirst( $wgLanguageCode ) . '.php' );
?>

