<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
#
# Tatarish localisation for MediaWiki
#

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesTt = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Maxsus',
	NS_MAIN             => '',
	NS_TALK             => 'Bäxäs',
	NS_USER             => 'Äğzä',
	NS_USER_TALK        => "Äğzä_bäxäse",
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . '_bäxäse',
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

/* private */ $wgDateFormatsTt = array(
#        "köyläwsez",
);

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

if (!$wgCachedMessageArrays) {
	require_once('MessagesTt.php');
}

class LanguageTt extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesTt;
		return $wgNamespaceNamesTt;
	}

	function getDateFormats() {
		global $wgDateFormatsTt;
		return $wgDateFormatsTt;
	}


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

	function getMessage( $key ) {
		global $wgAllMessagesTt;
		if( isset( $wgAllMessagesTt[$key] ) ) {
			return $wgAllMessagesTt[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function fallback8bitEncoding() {
		# Windows codepage 1252 is a superset of iso 8859-1
		# override this to use difference source encoding to
		# translate incoming 8-bit URLs.
		return "windows-1254";
	}
}

?>
