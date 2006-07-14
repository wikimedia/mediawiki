<?php
/** Low Saxon (Plattdüütsch)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesNds.php');
}

class LanguageNds extends LanguageUtf8 {
	private $mMessagesNds, $mNamespaceNamesNds = null;

	private $mQuickbarSettingsNds = array(
		'Keen', 'Links, fast', 'Rechts, fast', 'Links, sweven'
	);
	
	private $mMagicWordsNds = array(
	#   ID                                 CASE  SYNONYMS
		'redirect'               => array( 0, '#redirect',                   '#wiederleiden'          ),
		'notoc'                  => array( 0, '__NOTOC__',                   '__KEENINHOLTVERTEKEN__' ),
		'forcetoc'               => array( 0, '__FORCETOC__',                '__WIESINHOLTVERTEKEN__' ),
		'toc'                    => array( 0, '__TOC__',                     '__INHOLTVERTEKEN__'     ),
		'noeditsection'          => array( 0, '__NOEDITSECTION__',           '__KEENÄNNERNLINK__'     ),
		'start'                  => array( 0, '__START__'                                             ),
		'currentmonth'           => array( 1, 'CURRENTMONTH',                'AKTMAAND'               ),
		'currentmonthname'       => array( 1, 'CURRENTMONTHNAME',            'AKTMAANDNAAM'           ),
		'currentday'             => array( 1, 'CURRENTDAY',                  'AKTDAG'                 ),
		'currentdayname'         => array( 1, 'CURRENTDAYNAME',              'AKTDAGNAAM'             ),
		'currentyear'            => array( 1, 'CURRENTYEAR',                 'AKTJOHR'                ),
		'currenttime'            => array( 1, 'CURRENTTIME',                 'AKTTIED'                ),
		'numberofarticles'       => array( 1, 'NUMBEROFARTICLES',            'ARTIKELTALL'            ),
		'currentmonthnamegen'    => array( 1, 'CURRENTMONTHNAMEGEN',         'AKTMAANDNAAMGEN'        ),
		'pagename'               => array( 1, 'PAGENAME',                    'SIETNAAM'               ),
		'pagenamee'              => array( 1, 'PAGENAMEE',                   'SIETNAAME'              ),
		'namespace'              => array( 1, 'NAMESPACE',                   'NAAMRUUM'               ),
		'subst'                  => array( 0, 'SUBST:'                                                ),
		'msgnw'                  => array( 0, 'MSGNW:'                                                ),
		'end'                    => array( 0, '__END__',                     '__ENN__'                ),
		'img_thumbnail'          => array( 1, 'thumbnail', 'thumb',          'duum'                   ),
		'img_right'              => array( 1, 'right',                       'rechts'                 ),
		'img_left'               => array( 1, 'left',                        'links'                  ),
		'img_none'               => array( 1, 'none',                        'keen'                   ),
		'img_width'              => array( 1, '$1px',                        '$1px'                   ),
		'img_center'             => array( 1, 'center', 'centre',            'merrn'                  ),
		'img_framed'             => array( 1, 'framed', 'enframed', 'frame', 'rahmt'                  ),
		'int'                    => array( 0, 'INT:'                                                  ),
		'sitename'               => array( 1, 'SITENAME',                    'STEEDNAAM'              ),
		'ns'                     => array( 0, 'NS:',                         'NR:'                    ),
		'localurl'               => array( 0, 'LOCALURL:',                   'STEEDURL:'              ),
		'localurle'              => array( 0, 'LOCALURLE:',                  'STEEDURLE:'             ),
		'server'                 => array( 0, 'SERVER',                      'SERVER'                 ),
		'grammar'                => array( 0, 'GRAMMAR:',                    'GRAMMATIK:'             )
	);
	
	private $mSkinNamesNds = array(
		'standard'      => 'Klassik',
		'nostalgia'     => 'Nostalgie',
		'cologneblue'   => 'Kölsch Blau',
		'smarty'        => 'Paddington',
		'chick'         => 'Küken'
	);
	
	
	private $mBookstoreListNds = array(
		'Verteken vun leverbore Böker'  => 'http://www.buchhandel.de/sixcms/list.php?page=buchhandel_profisuche_frameset&suchfeld=isbn&suchwert=$1=0&y=0',
		'abebooks.de'                   => 'http://www.abebooks.de/servlet/BookSearchPL?ph=2&isbn=$1',
		'Amazon.de'                     => 'http://www.amazon.de/exec/obidos/ISBN=$1',
		'Lehmanns Fachbuchhandlung'     => 'http://www.lob.de/cgi-bin/work/suche?flag=new&stich1=$1',
	);
	
	function __construct() {
		parent::__construct();

		global $wgAllMessagesNds;
		$this->mMessagesNds =& $wgAllMessagesNds;

		global $wgMetaNamespace;
		$this->mNamespaceNamesNds = array(
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

	}

	function getBookstoreList() {
		return $this->mBookstoreListNds;
	}

	function getNamespaces() {
		return $this->mNamespaceNamesNds + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsNds;
	}

	function getSkinNames() {
		return $this->mSkinNamesNds + parent::getSkinNames();
	}

	function &getMagicWords()  {
		$t = $this->mMagicWordsNds + parent::getMagicWords();
		return $t;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesNds[$key] ) ) {
			return $this->mMessagesNds[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesNds;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function formatDay( $day, $format ) {
		return parent::formatDay( $day, $format ) . '.';
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

	function linkTrail() {
		return '/^([äöüßa-z]+)(.*)$/sDu';
	}

}
?>
