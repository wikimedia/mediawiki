<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
#
# Low Saxon (Plattdüütsch) localisation for MediaWiki
#
require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesNds = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Spezial',
	NS_MAIN             => '',
	NS_TALK             => 'Diskuschoon',
	NS_USER             => 'Bruker',
	NS_USER_TALK        => 'Bruker_Diskuschoon',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . '_Diskuschoon',
	NS_IMAGE            => 'Bild',
	NS_IMAGE_TALK       => 'Bild_Diskuschoon',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Diskuschoon',
	NS_TEMPLATE         => 'Vörlaag',
	NS_TEMPLATE_TALK    => 'Vörlaag_Diskuschoon',
	NS_HELP             => 'Hülp',
	NS_HELP_TALK        => 'Hülp_Diskuschoon',
	NS_CATEGORY         => 'Kategorie',
	NS_CATEGORY_TALK    => 'Kategorie_Diskuschoon'
);

/* private */ $wgQuickbarSettingsNds = array(
	'Keen', 'Links, fast', 'Rechts, fast', 'Links, sweven'
);

# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
/* private */ $wgMagicWordsNds = array(
#   ID                                 CASE  SYNONYMS
	MAG_REDIRECT             => array( 0,    '#redirect',                   '#wiederleiden'          ),
	MAG_NOTOC                => array( 0,    '__NOTOC__',                   '__KEENINHOLTVERTEKEN__' ),
	MAG_FORCETOC             => array( 0,    '__FORCETOC__',                '__WIESINHOLTVERTEKEN__' ),
	MAG_TOC                  => array( 0,    '__TOC__',                     '__INHOLTVERTEKEN__'     ),
	MAG_NOEDITSECTION        => array( 0,    '__NOEDITSECTION__',           '__KEENÄNNERNLINK__'     ),
	MAG_START                => array( 0,    '__START__'                                             ),
	MAG_CURRENTMONTH         => array( 1,    'CURRENTMONTH',                'AKTMAAND'               ),
	MAG_CURRENTMONTHNAME     => array( 1,    'CURRENTMONTHNAME',            'AKTMAANDNAAM'           ),
	MAG_CURRENTDAY           => array( 1,    'CURRENTDAY',                  'AKTDAG'                 ),
	MAG_CURRENTDAYNAME       => array( 1,    'CURRENTDAYNAME',              'AKTDAGNAAM'             ),
	MAG_CURRENTYEAR          => array( 1,    'CURRENTYEAR',                 'AKTJOHR'                ),
	MAG_CURRENTTIME          => array( 1,    'CURRENTTIME',                 'AKTTIED'                ),
	MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES',            'ARTIKELTALL'            ),
	MAG_CURRENTMONTHNAMEGEN  => array( 1,    'CURRENTMONTHNAMEGEN',         'AKTMAANDNAAMGEN'        ),
	MAG_PAGENAME             => array( 1,    'PAGENAME',                    'SIETNAAM'               ),
	MAG_PAGENAMEE            => array( 1,    'PAGENAMEE',                   'SIETNAAME'              ),
	MAG_NAMESPACE            => array( 1,    'NAMESPACE',                   'NAAMRUUM'               ),
	MAG_SUBST                => array( 0,    'SUBST:'                                                ),
	MAG_MSGNW                => array( 0,    'MSGNW:'                                                ),
	MAG_END                  => array( 0,    '__END__',                     '__ENN__'                ),
	MAG_IMG_THUMBNAIL        => array( 1,    'thumbnail', 'thumb',          'duum'                   ),
	MAG_IMG_RIGHT            => array( 1,    'right',                       'rechts'                 ),
	MAG_IMG_LEFT             => array( 1,    'left',                        'links'                  ),
	MAG_IMG_NONE             => array( 1,    'none',                        'keen'                   ),
	MAG_IMG_WIDTH            => array( 1,    '$1px',                        '$1px'                   ),
	MAG_IMG_CENTER           => array( 1,    'center', 'centre',            'merrn'                  ),
	MAG_IMG_FRAMED           => array( 1,    'framed', 'enframed', 'frame', 'rahmt'                  ),
	MAG_INT                  => array( 0,    'INT:'                                                  ),
	MAG_SITENAME             => array( 1,    'SITENAME',                    'STEEDNAAM'              ),
	MAG_NS                   => array( 0,    'NS:',                         'NR:'                    ),
	MAG_LOCALURL             => array( 0,    'LOCALURL:',                   'STEEDURL:'              ),
	MAG_LOCALURLE            => array( 0,    'LOCALURLE:',                  'STEEDURLE:'             ),
	MAG_SERVER               => array( 0,    'SERVER',                      'SERVER'                 ),
	MAG_GRAMMAR              => array( 0,    'GRAMMAR:',                    'GRAMMATIK:'             )
);

/* private */ $wgSkinNamesNds = array(
	'standard'      => 'Klassik',
	'nostalgia'     => 'Nostalgie',
	'cologneblue'   => 'Kölsch Blau',
	'smarty'        => 'Paddington',
	'chick'         => 'Küken'
) + $wgSkinNamesEn;


/* private */ $wgBookstoreListNds = array(
	'Verteken vun leverbore Böker' => 'http://www.buchhandel.de/sixcms/list.php?page=buchhandel_profisuche_frameset&suchfeld=isbn&suchwert=$1=0&y=0',
	'abebooks.de'                   => 'http://www.abebooks.de/servlet/BookSearchPL?ph=2&isbn=$1',
	'Amazon.de'                     => 'http://www.amazon.de/exec/obidos/ISBN=$1',
	'Lehmanns Fachbuchhandlung'     => 'http://www.lob.de/cgi-bin/work/suche?flag=new&stich1=$1',
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesNds.php');
}

class LanguageNds extends LanguageUtf8 {

	function getBookstoreList() {
		global $wgBookstoreListNds;
		return $wgBookstoreListNds;
	}

	function getNamespaces() {
		global $wgNamespaceNamesNds;
		return $wgNamespaceNamesNds;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsNds;
		return $wgQuickbarSettingsNds;
	}

	function getSkinNames() {
		global $wgSkinNamesNds;
		return $wgSkinNamesNds;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function formatDay( $day, $format ) {
		return parent::formatDay( $day, $format ) . '.';
	}

	function getMessage( $key ) {
		global $wgAllMessagesNds;
		if( isset( $wgAllMessagesNds[$key] ) ) {
			return $wgAllMessagesNds[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ',.' );
	}
}

?>
