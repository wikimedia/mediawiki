<?php

# Navajo language file
# No messages at the moment, just all the other stuff

require_once( "LanguageUtf8.php" );

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( ' ', '_', $wgSitename );

/* private */ $wgNamespaceNamesNv = array(
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

/* private */ $wgDefaultUserOptionsNv = array(
	'quickbar' => 1, 'underline' => 1, 'hover' => 1,
	'cols' => 80, 'rows' => 25, 'searchlimit' => 20,
	'contextlines' => 5, 'contextchars' => 50,
	'skin' => $wgDefaultSkin, 'math' => 1, 'rcdays' => 7, 'rclimit' => 50,
	'highlightbroken' => 1, 'stubthreshold' => 0,
	'previewontop' => 1, 'editsection'=>1,'editsectiononrightclick'=>0, 'showtoc'=>1,
	'showtoolbar' =>1,
	'date' => 0
);

/* private */ $wgQuickbarSettingsNv = array(
	'None', 'Fixed left', 'Fixed right', 'Floating left'
);

/* private */ $wgSkinNamesNv = array(
	'standard' => 'Standard',
	'nostalgia' => 'Nostalgia',
	'cologneblue' => 'Cologne Blue',
	'davinci' => 'DaVinci',
	'mono' => 'Łáa\'ígíí',
	'monobook' => 'NaaltsoosŁáa\'ígíí',
 'myskin' => 'MySkin'
);

/* private */ $wgMathNamesNv = array(
	MW_MATH_PNG => 'Always render PNG',
	MW_MATH_SIMPLE => 'HTML if very simple or else PNG',
	MW_MATH_HTML => 'HTML if possible or else PNG',
	MW_MATH_SOURCE => 'Leave it as TeX (for text browsers)',
	MW_MATH_MODERN => 'Recommended for modern browsers',
	MW_MATH_MATHML => 'MathML if possible (experimental)',
);

/* private */ $wgDateFormatsNv = array(
	'No preference',
	'Yas Niłt\'ees 15, 2001',
	'15 Yas Niłt\'ees 2001',
	'2001 Yas Niłt\'ees 15',
	'2001-01-15'
);

/* private */ $wgUserTogglesNv = array(
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

/* private */ $wgBookstoreListNv = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1",
	//"ISBN" => "$1"
);

/* private */ $wgWeekdayNamesNv = array(
	'Damóogo', 'Damóo biiskání', 'Damóodóó naakiską́o', 'Damóodóó tágí jį́', 'Damóodóó dį́į́\' yiską́o',
	'Nda\'iiníísh', 'Yiską́ damóo'
);

/* private */ $wgMonthNamesNv = array(
	'Yas Niłt\'ees', 'Atsá Biyáázh', 'Wóózhch\'į́į́d', 'T\'ą́ą́chil', 'T\'ą́ą́tsoh', 'Ya\'iishjááshchilí',
	'Ya\'iishjáástsoh', 'Bini\'ant\'ą́ą́ts\'ózí', 'Bini\'ant\'ą́ą́tsoh', 'Ghąąjį', 'Níłch\'its\'ósí',
	'Níłch\'itsoh'
);

/* private */ $wgMonthAbbreviationsNv = array(
	'Ynts', 'Atsb', 'Wozh', 'Tchi', 'Ttso', 'Yjsh', 'Yjts', 'Btsz',
	'Btsx', 'Ghąj', 'Ntss', 'Ntsx'
);

# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
/* private */ $wgMagicWordsNv = array(
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
    MAG_IMG_FRAMED           => array( 1,    'framed', 'enframed', 'frame' ),
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

class LanguageNv extends LanguageUtf8 {

	function getDefaultUserOptions () {
		global $wgDefaultUserOptionsNv ;
		return $wgDefaultUserOptionsNv ;
	}

	function getBookstoreList () {
		global $wgBookstoreListNv ;
		return $wgBookstoreListNv ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesNv;
		return $wgNamespaceNamesNv;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesNv;
		return $wgNamespaceNamesNv[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesNv;

		foreach ( $wgNamespaceNamesNv as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsNv;
		return $wgQuickbarSettingsNv;
	}

	function getSkinNames() {
		global $wgSkinNamesNv;
		return $wgSkinNamesNv;
	}

	function getMathNames() {
		global $wgMathNamesNv;
		return $wgMathNamesNv;
	}

	function getDateFormats() {
		global $wgDateFormatsNv;
		return $wgDateFormatsNv;
	}

	function getUserToggles() {
		global $wgUserTogglesNv;
		return $wgUserTogglesNv;
	}

	function getUserToggle( $tog ) {
		$togs =& $this->getUserToggles();
		return $togs[$tog];
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesNv;
		return $wgMonthNamesNv[$key-1];
	}

	/* by default we just return base form */
	function getMonthNameGen( $key )
	{
		return $this->getMonthName( $key );
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsNv;
		return @$wgMonthAbbreviationsNv[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesNv;
		return $wgWeekdayNamesNv[$key-1];
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesNv;
		return $wgValidSpecialPagesNv;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesNv;
		return $wgSysopSpecialPagesNv;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesNv;
		return $wgDeveloperSpecialPagesNv;
	}

/*
	function getMessage( $key )
	{
		global $wgAllMessagesNv;
		return @$wgAllMessagesNv[$key];
	}

	function getAllMessages()
	{
		global $wgAllMessagesNv;
		return $wgAllMessagesNv;
	}
*/

	function &getMagicWords()
	{
		global $wgMagicWordsNv;
		return $wgMagicWordsNv;
	}
}

?>
