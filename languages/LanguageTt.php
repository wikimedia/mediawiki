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
	'redirect'               => array( 0,    '#yünältü'               ),
	'notoc'                  => array( 0,    '__ETYUQ__'              ),
	'forcetoc'               => array( 0,    '__ETTIQ__'              ),
	'toc'                    => array( 0,    '__ET__'                 ),
	'noeditsection'          => array( 0,    '__BÜLEMTÖZÄTÜYUQ__'     ),
	'start'                  => array( 0,    '__BAŞLAW__'             ),
	'currentmonth'           => array( 1,    'AĞIMDAĞI_AY'            ),
	'currentmonthname'       => array( 1,    'AĞIMDAĞI_AY_İSEME'      ),
	'currentday'             => array( 1,    'AĞIMDAĞI_KÖN'           ),
	'currentdayname'         => array( 1,    'AĞIMDAĞI_KÖN_İSEME'     ),
	'currentyear'            => array( 1,    'AĞIMDAĞI_YIL'           ),
	'currenttime'            => array( 1,    'AĞIMDAĞI_WAQIT'         ),
	'numberofarticles'       => array( 1,    'MÄQÄLÄ_SANI'            ),
	'currentmonthnamegen'    => array( 1,    'AĞIMDAĞI_AY_İSEME_GEN'  ),
	'pagename'               => array( 1,    'BİTİSEME'               ),
	'namespace'              => array( 1,    'İSEMARA'                ),
	'subst'                  => array( 0,    'TÖPÇEK:'                ),
	'msgnw'                  => array( 0,    'MSGNW:'                 ),
	'end'                    => array( 0,    '__AZAQ__'               ),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb'     ),
	'img_right'              => array( 1,    'uñda'                   ),
	'img_left'               => array( 1,    'sulda'                  ),
	'img_none'               => array( 1,    'yuq'                    ),
	'img_width'              => array( 1,    '$1px'                   ),
	'img_center'             => array( 1,    'center', 'centre'       ),
	'img_framed'             => array( 1,    'framed', 'enframed', 'frame' ),
	'int'                    => array( 0,    'EÇKE:'                   ),
	'sitename'               => array( 1,    'SÄXİFÄİSEME'            ),
	'ns'                     => array( 0,    'İA:'                    ),
	'localurl'               => array( 0,    'URINLIURL:'              ),
	'localurle'              => array( 0,    'URINLIURLE:'             ),
	'server'                 => array( 0,    'SERVER'                 )
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

	/**
	 * $format and $timecorrection are for compatibility with Language::date
	 */
	function date( $ts, $adj = false, $format = true, $timecorrection = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . ". " .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . " " .
		  substr( $ts, 0, 4 );
		return $d;
	}

	/**
	 * $format and $timecorrection are for compatibility with language::time
	 */
	function time($ts, $adj = false, $format = true, $timecorrection = false) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
		return $t;
	}

	/**
	 * $format and $timecorrection are for compatibility with Language::date
	 */
	function timeanddate( $ts, $adj = false, $format = true, $timecorrection = false ) {
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
