<?php
#
# Tatarish localisation for MediaWiki
#
# This file is encoded in UTF-8, no byte order mark.
# For compatibility with Latin-1 installations, please
# don't add literal characters above U+00ff.
#

require_once( "LanguageUtf8.php" );

#--------------------------------------------------------------------------
# Language-specific text
#--------------------------------------------------------------------------

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#

/* private */ $wgNamespaceNamesTt = array(
        NS_MEDIA            => 'Media',
        NS_SPECIAL          => 'Maxsus',
        NS_MAIN             => '',
        NS_TALK             => 'Bäxäs',
        NS_USER             => 'Äğzä',
        NS_USER_TALK        => "Äğzä_bäxäse",
        NS_PROJECT          => "Wikipedia",
        NS_PROJECT_TALK     => "Wikipedia_bäxäse",
        NS_IMAGE            => "Räsem",
        NS_IMAGE_TALK       => "Räsem_bäxäse",
        NS_MEDIAWIKI        => "MediaWiki",
        NS_MEDIAWIKI_TALK   => "MediaWiki_bäxäse",
        NS_TEMPLATE         => "Ürnäk",
        NS_TEMPLATE_TALK    => "Ürnäk_bäxäse",
        NS_HELP             => "Yärdäm",
        NS_HELP_TALK        => "Yärdäm_bäxäse",
        NS_CATEGORY         => "Törkem",
        NS_CATEGORY_TALK    => "Törkem_bäxäse"
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsTt = array(
        "None", "Fixed left", "Fixed right", "Floating left"
);

/* private */ $wgSkinNamesTt = array(
        'standard' => 'Classic',
        'nostalgia' => 'Nostalgia',
        'cologneblue' => 'Cologne Blue',
        'davinci' => 'DaVinci',
        'mono' => 'Mono',
        'monobook' => 'MonoBook',
        'myskin' => 'MySkin'
);

/* private */ $wgDateFormatsTt = array(
        "köyläwsez",
        "Ğínwar 15, 2001",
        "15. Ğínwar 2001",
        "2001 Ğínwar 15",
        "2001-01-15"
);


/* private */ $wgBookstoreListTt = array(
        "AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
        "PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
        "Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
        "Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

# Read language names
global $wgLanguageNames;
require_once( "Names.php" );

$wgLanguageNamesTt =& $wgLanguageNames;

# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
/* private */ $wgMagicWordsTt = array(
#       ID                                 CASE  SYNONYMS
        MAG_REDIRECT             => array( 0,    '#yünältü'               ),
        MAG_NOTOC                => array( 0,    '__ETYUQ__'              ),
        MAG_FORCETOC             => array( 0,    '__ETTIQ__'              ),
        MAG_TOC                  => array( 0,    '__ET__'                 ),
        MAG_NOEDITSECTION        => array( 0,    '__BÜLEMTÖZÄTÜYUQ__'     ),
        MAG_START                => array( 0,    '__BAŞLAW__'             ),
        MAG_CURRENTMONTH         => array( 1,    'AĞIMDAĞI_AY'            ),
        MAG_CURRENTMONTHNAME     => array( 1,    'AĞIMDAĞI_AY_İSEME'      ),
        MAG_CURRENTDAY           => array( 1,    'AĞIMDAĞI_KÖN'           ),
        MAG_CURRENTDAYNAME       => array( 1,    'AĞIMDAĞI_KÖN_İSEME'     ),
        MAG_CURRENTYEAR          => array( 1,    'AĞIMDAĞI_YIL'           ),
        MAG_CURRENTTIME          => array( 1,    'AĞIMDAĞI_WAQIT'         ),
        MAG_NUMBEROFARTICLES     => array( 1,    'MÄQÄLÄ_SANI'            ),
        MAG_CURRENTMONTHNAMEGEN  => array( 1,    'AĞIMDAĞI_AY_İSEME_GEN'  ),
        MAG_PAGENAME             => array( 1,    'BİTİSEME'               ),
        MAG_NAMESPACE            => array( 1,    'İSEMARA'                ),
        MAG_MSG                  => array( 0,    'STR:'                   ),
        MAG_SUBST                => array( 0,    'TÖPÇEK:'                ),
        MAG_MSGNW                => array( 0,    'MSGNW:'                 ),
        MAG_END                  => array( 0,    '__AZAQ__'               ),
        MAG_IMG_THUMBNAIL        => array( 1,    'thumbnail', 'thumb'     ),
        MAG_IMG_RIGHT            => array( 1,    'uñda'                   ),
        MAG_IMG_LEFT             => array( 1,    'sulda'                  ),
        MAG_IMG_NONE             => array( 1,    'yuq'                    ),
        MAG_IMG_WIDTH            => array( 1,    '$1px'                   ),
        MAG_IMG_CENTER           => array( 1,    'center', 'centre'       ),
        MAG_IMG_FRAMED           => array( 1,    'framed', 'enframed', 'frame' ),
        MAG_INT                  => array( 0,    'EÇKE:'                   ),
        MAG_SITENAME             => array( 1,    'SÄXİFÄİSEME'            ),
        MAG_NS                   => array( 0,    'İA:'                    ),
        MAG_LOCALURL             => array( 0,    'URINLIURL:'              ),
        MAG_LOCALURLE            => array( 0,    'URINLIURLE:'             ),
        MAG_SERVER               => array( 0,    'SERVER'                 )
) + $wgMagicWordsEn;

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

/* private */ $wgAllMessagesTt = array(
'special_version_prefix' => '',
'special_version_postfix' => '',

# week days, months
'sunday' => "Yäkşämbe",
'monday' => "Düşämbe",
'tuesday' => "Sişämbe",
'wednesday' => "Çärşämbe",
'thursday' => "Pänceşämbe",
'friday' => "Comğa",
'saturday' => "Şimbä",
'january' => "Ğínwar",
'february' => "Febräl",
'march' => "Mart",
'april' => "Äpril",
'may_long' => "May",
'june' => "Yün",
'july' => "Yül",
'august' => "August",
'september' => "Sentäber",
'october' => "Öktäber",
'november' => "Nöyäber",
'december' => "Dekäber",
'jan' => "Ğín",
'feb' => "Feb",
'mar' => "Mar",
'apr' => "Äpr",
'may' => "May",
'jun' => "Yün",
'jul' => "Yül",
'aug' => "Aug",
'sep' => "Sen",
'oct' => "Ökt",
'nov' => "Nöy",
'dec' => "Dek",

# User Toggles
"tog-hover"                       => "Show hoverbox over wiki links",
"tog-underline"           => "Underline links",
"tog-highlightbroken"     => "Format broken links <a href=\"\" class=\"new\">like this</a> (alternative: like this<a href=\"\" class=\"internal\">?</a>).",
"tog-justify"             => "Justify paragraphs",
"tog-hideminor"           => "Hide minor edits in recent changes",
"tog-usenewrc"            => "Enhanced recent changes (not for all browsers)",
"tog-numberheadings"      => "Auto-number headings",
"tog-showtoolbar"         =>"Show edit toolbar",
"tog-editondblclick"      => "Edit pages on double click (JavaScript)",
"tog-editsection"         =>"Enable section editing via [edit] links",
"tog-editsectiononrightclick"=>"Enable section editing by right clicking<br> on section titles (JavaScript)",
"tog-showtoc"             =>"Show table of contents<br>(for articles with more than 3 headings)",
"tog-rememberpassword"    => "Remember password across sessions",
"tog-editwidth"           => "Edit box has full width",
"tog-watchdefault"                => "Add pages you edit to your watchlist",
"tog-minordefault"                => "Mark all edits minor by default",
"tog-previewontop"                => "Show preview before edit box and not after it",
"tog-nocache"             => "Disable page caching",

);

class LanguageTt extends LanguageUtf8 {

        function getDefaultUserOptions () {
                $opt = Language::getDefaultUserOptions();
                return $opt;
                }

        function getBookstoreList () {
                global $wgBookstoreListTt;
                return $wgBookstoreListTt;
        }

        function getNamespaces() {
                global $wgNamespaceNamesTt;
                return $wgNamespaceNamesTt;
        }

        function getNsText( $index ) {
                global $wgNamespaceNamesTt;
                return $wgNamespaceNamesTt[$index];
        }

        function getNsIndex( $text ) {
                global $wgNamespaceNamesTt;

                foreach ( $wgNamespaceNamesTt as $i => $n ) {
                        if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
                }
                return false;
        }

        function specialPage( $name ) {
                return $this->getNsText( Namespace::getSpecial() ) . ":" . $name;
        }

        function getQuickbarSettings() {
                global $wgQuickbarSettingsTt;
                return $wgQuickbarSettingsTt;
        }

        function getSkinNames() {
                global $wgSkinNamesTt;
                return $wgSkinNamesTt;
        }

        function getDateFormats() {
                global $wgDateFormatsTt;
                return $wgDateFormatsTt;
        }

        # Inherit userAdjust()

        function date( $ts, $adj = false ) {
                if ( $adj ) { $ts = $this->userAdjust( $ts ); }

                $d = (0 + substr( $ts, 6, 2 )) . ". " .
                  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . " " .
                  substr( $ts, 0, 4 );
                return $d;
        }

        function time( $ts, $adj = false ) {
                if ( $adj ) { $ts = $this->userAdjust( $ts ); }

                $t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
                return $t;
        }

        function timeanddate( $ts, $adj = false ) {
                return $this->date( $ts, $adj ) . ", " . $this->time( $ts, $adj );
        }

        # Inherit rfc1123()

        function getValidSpecialPages() {
                global $wgValidSpecialPagesTt;
                return $wgValidSpecialPagesTt;
        }

        function getSysopSpecialPages() {
                global $wgSysopSpecialPagesTt;
                return $wgSysopSpecialPagesTt;
        }

        function getDeveloperSpecialPages() {
                global $wgDeveloperSpecialPagesTt;
                return $wgDeveloperSpecialPagesTt;
        }

	function getMessage( $key ) {
		global $wgAllMessagesTt;
		if( isset( $wgAllMessagesTt[$key] ) ) {
			return $wgAllMessagesTt[$key];
		} else {
			return Language::getMessage( $key );
		}
	}

        # Inherit iconv()

        # Inherit ucfirst()

        # Inherit lcfirst()

        # Inherit checkTitleEncoding()

        # Inherit stripForSearch()

        # Inherit setAltEncoding()

        # Inherit recodeForEdit()

        # Inherit recodeInput()

        # Inherit isRTL()

        # Inherit getMagicWords()

        function fallback8bitEncoding() {
                # Windows codepage 1252 is a superset of iso 8859-1
                # override this to use difference source encoding to
                # translate incoming 8-bit URLs.
                return "windows-1254";
        }
}

?>
