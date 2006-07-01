<?php
/** Norwegian (Nynorsk)
  *
  * @license http://www.gnu.org/copyleft/fdl.html GNU Free Documentation License
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
  *
  * @author Olve Utne
  * @author Guttorm Flatabø
  * @link http://meta.wikimedia.org/w/index.php?title=LanguageNn.php&action=history
  * @link http://nn.wikipedia.org/w/index.php?title=Brukar:Dittaeva/LanguageNn.php&action=history
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesNn.php');
}

class LanguageNn extends LanguageUtf8 {
	private $mMessagesNn, $mNamespaceNamesNn = null;

	private $mQuickbarSettingsNn = array(
		'Ingen', 'Venstre', 'Høgre', 'Flytande venstre', 'Flytande høgre'
	);
	
	private $mSkinNamesNn = array(
		'standard'        => 'Klassisk',
		'nostalgia'       => 'Nostalgi',
		'cologneblue'     => 'Kölnerblå',
		'myskin'          => 'MiDrakt'
	);
	
	private $mDateFormatsNn = array(
		'Standard',
		'15. januar 2001 kl. 16:12',
		'15. jan. 2001 kl. 16:12',
		'16:12, 15. januar 2001',
		'16:12, 15. jan. 2001',
		'ISO 8601' => '2001-01-15 16:12:34'
	);
	
	private $mBookstoreListNn = array(
		'Bibsys'       => 'http://ask.bibsys.no/ask/action/result?kilde=biblio&fid=isbn&lang=nn&term=$1',
		'BokBerit'     => 'http://www.bokberit.no/annet_sted/bocker/$1.html',
		'Bokkilden'    => 'http://www.bokkilden.no/ProductDetails.aspx?ProductId=$1',
		'Haugenbok'    => 'http://www.haugenbok.no/resultat.cfm?st=hurtig&isbn=$1',
		'Akademika'    => 'http://www.akademika.no/sok.php?isbn=$1',
		'Gnist'        => 'http://www.gnist.no/sok.php?isbn=$1',
		'Amazon.co.uk' => 'http://www.amazon.co.uk/exec/obidos/ISBN=$1',
		'Amazon.de'    => 'http://www.amazon.de/exec/obidos/ISBN=$1',
		'Amazon.com'   => 'http://www.amazon.com/exec/obidos/ISBN=$1'
	);
	
	# Note to translators:
	#   Please include the English words as synonyms.  This allows people
	#   from other wikis to contribute more easily.
	#
	private $mMagicWordsNn = array(
	#   ID                                 CASE  SYNONYMS
		MAG_REDIRECT             => array( 0,    '#redirect', '#omdiriger'                                              ),
		MAG_NOTOC                => array( 0,    '__NOTOC__', '__INGAINNHALDSLISTE__', '__INGENINNHOLDSLISTE__'         ),
		MAG_FORCETOC             => array( 0,    '__FORCETOC__', '__ALLTIDINNHALDSLISTE__', '__ALLTIDINNHOLDSLISTE__'   ),
		MAG_TOC                  => array( 0,    '__TOC__', '__INNHALDSLISTE__', '__INNHOLDSLISTE__'                    ),
		MAG_NOEDITSECTION        => array( 0,    '__NOEDITSECTION__', '__INGABOLKENDRING__', '__INGABOLKREDIGERING__', '__INGENDELENDRING__'),
		MAG_CURRENTMONTH         => array( 1,    'CURRENTMONTH', 'MÅNADNO', 'MÅNEDNÅ'                                   ),
		MAG_CURRENTMONTHNAME     => array( 1,    'CURRENTMONTHNAME', 'MÅNADNONAMN', 'MÅNEDNÅNAVN'                       ),
		MAG_CURRENTMONTHABBREV   => array( 1,    'CURRENTMONTHABBREV', 'MÅNADNOKORT', 'MÅNEDNÅKORT'                     ),
		MAG_CURRENTDAY           => array( 1,    'CURRENTDAY', 'DAGNO', 'DAGNÅ'                                         ),
		MAG_CURRENTDAYNAME       => array( 1,    'CURRENTDAYNAME', 'DAGNONAMN', 'DAGNÅNAVN'                             ),
		MAG_CURRENTYEAR          => array( 1,    'CURRENTYEAR', 'ÅRNO', 'ÅRNÅ'                                          ),
		MAG_CURRENTTIME          => array( 1,    'CURRENTTIME', 'TIDNO', 'TIDNÅ'                                        ),
		MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES', 'INNHALDSSIDETAL', 'INNHOLDSSIDETALL'              ),
		MAG_NUMBEROFFILES        => array( 1,    'NUMBEROFFILES', 'FILTAL'                                              ),
		MAG_PAGENAME             => array( 1,    'PAGENAME', 'SIDENAMN', 'SIDENAVN'                                     ),
		MAG_PAGENAMEE            => array( 1,    'PAGENAMEE', 'SIDENAMNE', 'SIDENAVNE'                                  ),
		MAG_NAMESPACE            => array( 1,    'NAMESPACE', 'NAMNEROM', 'NAVNEROM'                                    ),
		MAG_SUBST                => array( 0,    'SUBST:', 'LIMINN:'                                                    ),
		MAG_MSGNW                => array( 0,    'MSGNW:', 'IKWIKMELD:'                                                 ),
		MAG_END                  => array( 0,    '__END__', '__SLUTT__'                                                 ),
		MAG_IMG_THUMBNAIL        => array( 1,    'thumbnail', 'thumb', 'mini', 'miniatyr'                               ),
		MAG_IMG_MANUALTHUMB      => array( 1,    'thumbnail=$1', 'thumb=$1', 'mini=$1', 'miniatyr=$1'                   ),
		MAG_IMG_RIGHT            => array( 1,    'right', 'høgre', 'høyre'                                              ),
		MAG_IMG_LEFT             => array( 1,    'left', 'venstre'                                                      ),
		MAG_IMG_NONE             => array( 1,    'none', 'ingen'                                                        ),
		MAG_IMG_WIDTH            => array( 1,    '$1px', '$1pk'                                                         ),
		MAG_IMG_CENTER           => array( 1,    'center', 'centre', 'sentrum'                                          ),
		MAG_IMG_FRAMED           => array( 1,    'framed', 'enframed', 'frame', 'ramme'                                 ),
		MAG_SITENAME             => array( 1,    'SITENAME', 'NETTSTADNAMN'                                             ),
		MAG_NS                   => array( 0,    'NS:', 'NR:'                                                           ),
		MAG_LOCALURL             => array( 0,    'LOCALURL:', 'LOKALLENKJE:', 'LOKALLENKE:'                             ),
		MAG_LOCALURLE            => array( 0,    'LOCALURLE:', 'LOKALLENKJEE:', 'LOKALLENKEE:'                          ),
		MAG_SERVER               => array( 0,    'SERVER', 'TENAR', 'TJENER'                                            ),
		MAG_SERVERNAME           => array( 0,    'SERVERNAME', 'TENARNAMN', 'TJENERNAVN'                                ),
		MAG_SCRIPTPATH           => array( 0,    'SCRIPTPATH', 'SKRIPTSTI'                                              ),
		MAG_GRAMMAR              => array( 0,    'GRAMMAR:', 'GRAMMATIKK:'                                              ),
		MAG_NOTITLECONVERT       => array( 0,    '__NOTITLECONVERT__', '__NOTC__'                                       ),
		MAG_NOCONTENTCONVERT     => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__'                                     ),
		MAG_CURRENTWEEK          => array( 1,    'CURRENTWEEK', 'VEKENRNO', 'UKENRNÅ'                                   ),
		MAG_CURRENTDOW           => array( 1,    'CURRENTDOW', 'VEKEDAGNRNO', 'UKEDAGNRNÅ'                              ),
		MAG_REVISIONID           => array( 1,    'REVISIONID', 'VERSJONSID'                                             )
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesNn;
		$this->mMessagesNn =& $wgAllMessagesNn;

		global $wgMetaNamespace;
		$this->mNamespaceNamesNn = array(
			NS_MEDIA          => 'Filpeikar',
			NS_SPECIAL        => 'Spesial',
			NS_MAIN           => '',
			NS_TALK           => 'Diskusjon',
			NS_USER           => 'Brukar',
			NS_USER_TALK      => 'Brukardiskusjon',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => $wgMetaNamespace . '-diskusjon',
			NS_IMAGE          => 'Fil',
			NS_IMAGE_TALK     => 'Fildiskusjon',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'MediaWiki-diskusjon',
			NS_TEMPLATE       => 'Mal',
			NS_TEMPLATE_TALK  => 'Maldiskusjon',
			NS_HELP           => 'Hjelp',
			NS_HELP_TALK      => 'Hjelpdiskusjon',
			NS_CATEGORY       => 'Kategori',
			NS_CATEGORY_TALK  => 'Kategoridiskusjon'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesNn + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsNn;
	}

	function getSkinNames() {
		return $this->mSkinNamesNn + parent::getSkinNames();
	}

	function getDateFormats() {
		return $this->mDateFormatsNn;
	}

	function getBookstoreList() {
		return $this->mBookstoreListNn;
	}

	function &getMagicWords()  {
		$t = $this->mMagicWordsNn + parent::getMagicWords();
		return $t;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesNn[$key] ) ) {
			return $this->mMessagesNn[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesNn;
	}


	function time($ts, $adj = false, $format = true) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); } # Adjust based on the timezone setting.

		$format = $this->dateFormat($format);

		switch( $format ) {
			# 2001-01-15 16:12:34
			case 'ISO 8601': return substr( $ts, 8, 2 ) . ':' . substr( $ts, 10, 2 ) . ':' . substr( $ts, 12, 2 );
			default: return substr( $ts, 8, 2 ) . ':' . substr( $ts, 10, 2 );
		}

	}

	function date( $ts, $adj = false, $format = true) {
		global $wgUser;
		if ( $adj ) { $ts = $this->userAdjust( $ts ); } # Adjust based on the timezone setting.
		$format = $this->dateFormat($format);

		switch( $format ) {
			# 15. jan. 2001 kl. 16:12 || 16:12, 15. jan. 2001
			case '2': case '4': return (0 + substr( $ts, 6, 2 )) . '. ' .
				$this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . '. ' .
				substr($ts, 0, 4);
			# 2001-01-15 16:12:34
			case 'ISO 8601': return substr($ts, 0, 4). '-' . substr($ts, 4, 2). '-' .substr($ts, 6, 2);

			# 15. januar 2001 kl. 16:12 || 16:12, 15. januar 2001
			default: return (0 + substr( $ts, 6, 2 )) . '. ' .
				$this->getMonthName( substr( $ts, 4, 2 ) ) . ' ' .
				substr($ts, 0, 4);
		}

	}

	function timeanddate( $ts, $adj = false, $format = true) {
		global $wgUser;

		$format = $this->dateFormat($format);

		switch ( $format ) {
			# 16:12, 15. januar 2001 || 16:12, 15. jan. 2001
			case '3': case '4': return $this->time( $ts, $adj, $format ) . ', ' . $this->date( $ts, $adj, $format );
			# 2001-01-15 16:12:34
			case 'ISO 8601': return $this->date( $ts, $adj, $format ) . ' ' . $this->time( $ts, $adj, $format );
			# 15. januar 2001 kl. 16:12 || 15. jan. 2001 kl. 16:12
			default: return $this->date( $ts, $adj, $format ) . ' kl. ' . $this->time( $ts, $adj, $format );
		}

	}

	function separatorTransformTable() {
		return array(
			',' => "\xc2\xa0",
			'.' => ','
		);
	}

}

?>
