<?php
# $Id$
#
require_once( "LanguageUtf8.php" );


/* private */ $wgQuickbarSettingsLt = array(
        "Nerodyti", "Fiksuoti kair&#279;je", "Fiksuoti de&#353;in&#279;je", "Plaukiojantis kair&#279;je"
);

/* private */ $wgSkinNamesLt = array(
        'standard' => "Standartin&#279;",
        'nostalgia' => "Nostalgija",
        'cologneblue' => "Kiolno M&#279;lyna",
        'davinci' => "Da Vin&#269;i",
        'mono' => "Mono",
        'monobook' => "MonoBook",
	'myskin' => "MySkin"
);

/* private */ $wgMathNamesLt = array(
        "Always render PNG",
        "HTML if very simple or else PNG",
        "HTML if possible or else PNG",
        "Leave it as TeX (for text browsers)",
        "Recommended for modern browsers"
);

/* private */ $wgDateFormatsLt = array(
        "Nesvarbu",
        "Sausio 15, 2001",
        "15 Sausio 2001",
        "2001 Sausio 15",
        "2001-01-15"
);

/* private */ $wgWeekdayNamesLt = array(
        "Sekmadienis", "Pirmadienis", "Antradienis", "Tre&#269;iadienis", "Ketvirtadienis",
        "Penktadienis", "&#352;e&#353;tadienis"
);

/* private */ $wgMonthNamesLt = array(
        "Sausio", "Vasario", "Kovo", "Baland&#382;io", "Gegu&#382;&#279;s", "Bir&#382;elio",
        "Liepos", "Rugpj&#363;&#269;io", "Rugs&#279;jo", "Spalio", "Lapkri&#269;io",
        "Gruod&#382;io"
);

/* private */ $wgMonthAbbreviationsLt = array(
        "Sau", "Vas", "Kov", "Bal", "Geg", "Bir", "Lie", "Rgp",
        "Rgs", "Spa", "Lap", "Gru"
);

# Note to translators: 
#   Please include the English words as synonyms.  This allows people 
#   from other wikis to contribute more easily.
# 
/* private */ $wgMagicWordsLt = array(
#   ID                                 CASE  SYNONYMS
    MAG_REDIRECT             => array( 0,    "#redirect"              ),
    MAG_NOTOC                => array( 0,    "__NOTOC__"              ),
    MAG_FORCETOC             => array( 0,    "__FORCETOC__"           ),
    MAG_NOEDITSECTION        => array( 0,    "__NOEDITSECTION__"      ),
    MAG_START                => array( 0,    "__START__"              ),
    MAG_CURRENTMONTH         => array( 1,    "CURRENTMONTH"           ),
    MAG_CURRENTMONTHNAME     => array( 1,    "CURRENTMONTHNAME"       ),
    MAG_CURRENTDAY           => array( 1,    "CURRENTDAY"             ),
    MAG_CURRENTDAYNAME       => array( 1,    "CURRENTDAYNAME"         ),
    MAG_CURRENTYEAR          => array( 1,    "CURRENTYEAR"            ),
    MAG_CURRENTTIME          => array( 1,    "CURRENTTIME"            ),
    MAG_NUMBEROFARTICLES     => array( 1,    "NUMBEROFARTICLES"       ),
    MAG_CURRENTMONTHNAMEGEN  => array( 1,    "CURRENTMONTHNAMEGEN"    ),
        MAG_MSG                  => array( 0,    "MSG:"                   ),
        MAG_SUBST                => array( 0,    "SUBST:"                 ),
    MAG_MSGNW                => array( 0,    "MSGNW:"                 ),
        MAG_END                  => array( 0,    "__END__"                ),
    MAG_IMG_THUMBNAIL        => array( 1,    "thumbnail", "thumb"     ),
    MAG_IMG_RIGHT            => array( 1,    "right"                  ),
    MAG_IMG_LEFT             => array( 1,    "left"                   ),
    MAG_IMG_NONE             => array( 1,    "none"                   ),
    MAG_IMG_WIDTH            => array( 1,    "$1px"                   ),
    MAG_IMG_CENTER           => array( 1,    "center", "centre"       ),
    MAG_INT                  => array( 0,    "INT:"                   ),
    MAG_SITENAME             => array( 1,    "SITENAME"               ),
    MAG_NS                   => array( 0,    "NS:"                    ),
        MAG_LOCALURL             => array( 0,    "LOCALURL:"              ),
        MAG_LOCALURLE            => array( 0,    "LOCALURLE:"             ),
        MAG_SERVER               => array( 0,    "SERVER"                 )
);

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageLt extends LanguageUtf8  {
        # Inherent default user options unless customization is desired

        function getQuickbarSettings() {
                global $wgQuickbarSettingsLt;
                return $wgQuickbarSettingsLt;
        }

        function getSkinNames() {
                global $wgSkinNamesLt;
                return $wgSkinNamesLt;
        }

        function getMathNames() {
                global $wgMathNamesLt;
                return $wgMathNamesLt;
        }
        
        function getDateFormats() {
                global $wgDateFormatsLt;
                return $wgDateFormatsLt;
        }

        function getMonthName( $key )
        {
                global $wgMonthNamesLt;
                return $wgMonthNamesLt[$key-1];
        }

        function getMonthAbbreviation( $key )
        {
                global $wgMonthAbbreviationsLt;
                return $wgMonthAbbreviationsLt[$key-1];
        }

        function getWeekdayName( $key )
        {
                global $wgWeekdayNamesLt;
                return $wgWeekdayNamesLt[$key-1];
        }

    function fallback8bitEncoding() {
            return "windows-1257";
    }
}
?>
