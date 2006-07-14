<?php
/** Irish (Gaeilge)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesGa.php');
}

class LanguageGa extends LanguageUtf8 {
	private $mMessagesGa, $mNamespaceNamesGa = null;

	private $mQuickbarSettingsGa = array(
		'Faic', 'Greamaithe ar chlé', 'Greamaithe ar dheis', 'Ag faoileáil ar chlé', 'Ag faoileáil ar dheis'
	);
	
	private $mSkinNamesGa = array(
		'standard' => 'Gnáth',
		'nostalgia' => 'Sean-nós',
		'cologneblue' => 'Gorm na Colóna',
		'smarty' => 'Paddington',
		'montparnasse' => 'Montparnasse',
		'davinci' => 'DaVinci',
		'mono' => 'Mono',
		'monobook' => 'MonoBook',
		'myskin' => 'MySkin',
		'chick' => 'Chick'
	);
	
	private $mDateFormatsGa = array(
		'Is cuma liom',
		'16:12, Eanáir 15, 2001',
		'16:12, 15 Eanáir 2001',
		'16:12, 2001 Eanáir 15',
		'ISO 8601' => '2001-01-15 16:12:34'
	);
	
	private $mMagicWordsGa = array(
	#   ID	                         CASE  SYNONYMS
		'redirect'               => array( 0,    '#redirect', '#athsheoladh' ),
		'notoc'                  => array( 0,    '__NOTOC__', '__GANCÁ__'              ),
		'forcetoc'               => array( 0,    '__FORCETOC__',         '__CÁGACHUAIR__'  ),
		'toc'                    => array( 0,    '__TOC__', '__CÁ__'                ),
		'noeditsection'          => array( 0,    '__NOEDITSECTION__',    '__GANMHÍRATHRÚ__'  ),
		'start'                  => array( 0,    '__START__', '__TÚS__'              ),
		'currentmonth'           => array( 1,    'CURRENTMONTH',  'MÍLÁITHREACH'  ),
		'currentmonthname'       => array( 1,    'CURRENTMONTHNAME',     'AINMNAMÍOSALÁITHREAÍ'  ),
		'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN',  'GINAINMNAMÍOSALÁITHREAÍ'  ),
		'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV',   'GIORRÚNAMÍOSALÁITHREAÍ'  ),
		'currentday'             => array( 1,    'CURRENTDAY',           'LÁLÁITHREACH'  ),
		'currentdayname'         => array( 1,    'CURRENTDAYNAME',       'AINMANLAELÁITHRIGH'  ),
		'currentyear'            => array( 1,    'CURRENTYEAR',          'BLIAINLÁITHREACH'  ),
		'currenttime'            => array( 1,    'CURRENTTIME',          'AMLÁITHREACH'  ),
		'numberofarticles'       => array( 1,    'NUMBEROFARTICLES',     'LÍONNANALT'  ),
		'numberoffiles'          => array( 1,    'NUMBEROFFILES',        'LÍONNAGCOMHAD'  ),
		'pagename'               => array( 1,    'PAGENAME',             'AINMANLGH'  ),
		'pagenamee'              => array( 1,    'PAGENAMEE',            'AINMANLGHB'  ),
		'namespace'              => array( 1,    'NAMESPACE',            'AINMSPÁS'  ),
		'msg'                    => array( 0,    'MSG:',                 'TCHT:'  ),
		'subst'                  => array( 0,    'SUBST:',               'IONAD:'  ),
		'msgnw'                  => array( 0,    'MSGNW:',               'TCHTFS:'  ),
		'end'                    => array( 0,    '__END__',              '__DEIREADH__'  ),
		'img_thumbnail'          => array( 1,    'thumbnail', 'thumb',   'mionsamhail', 'mion'  ),
		'img_right'              => array( 1,    'right',                'deas'  ),
		'img_left'               => array( 1,    'left',                 'clé'  ),
		'img_none'               => array( 1,    'none',                 'faic'  ),
		'img_width'              => array( 1,    '$1px'                   ),
		'img_center'             => array( 1,    'center', 'centre',     'lár'  ),
		'img_framed'             => array( 1,    'framed', 'enframed', 'frame', 'fráma', 'frámaithe' ),
		'int'                    => array( 0,    'INT:', 'INMH:'                   ),
		'sitename'               => array( 1,    'SITENAME',             'AINMANTSUÍMH'  ),
		'ns'                     => array( 0,    'NS:', 'AS:'                    ),
		'localurl'               => array( 0,    'LOCALURL:',            'URLÁITIÚIL'  ),
		'localurle'              => array( 0,    'LOCALURLE:',           'URLÁITIÚILB'  ),
		'server'                 => array( 0,    'SERVER',               'FREASTALAÍ'  ),
		'servername'             => array( 0,    'SERVERNAME',            'AINMANFHREASTALAÍ' ),
		'scriptpath'             => array( 0,    'SCRIPTPATH',           'SCRIPTCHOSÁN'  ),
		'grammar'                => array( 0,    'GRAMMAR:',             'GRAMADACH:'  ),
		'notitleconvert'         => array( 0,    '__NOTITLECONVERT__', '__NOTC__', '__GANTIONTÚNADTEIDEAL__', '__GANTT__'),
		'nocontentconvert'       => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__', '__GANTIONTÚNANÁBHAIR__', '__GANTA__' ),
		'currentweek'            => array( 1,    'CURRENTWEEK',          'SEACHTAINLÁITHREACH'  ),
		'currentdow'             => array( 1,    'CURRENTDOW',           'LÁLÁITHREACHNAS'  ),
		'revisionid'             => array( 1,    'REVISIONID',           'IDANLEASAITHE'  ),
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesGa;
		$this->mMessagesGa =& $wgAllMessagesGa;

		global $wgMetaNamespace;
		$this->mNamespaceNamesGa = array(
			NS_MEDIA	          => 'Meán',
			NS_SPECIAL          => 'Speisialta',
			NS_MAIN             => '',
			NS_TALK             => 'Plé',
			NS_USER             => 'Úsáideoir',
			NS_USER_TALK        => 'Plé_úsáideora',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => 'Plé_' . $this->convertGrammar( $wgMetaNamespace, 'genitive' ),
			NS_IMAGE            => 'Íomhá',
			NS_IMAGE_TALK       => 'Plé_íomhá',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'Plé_MediaWiki',
			NS_TEMPLATE         => 'Teimpléad',
			NS_TEMPLATE_TALK    => 'Plé_teimpléid',
			NS_HELP             => 'Cabhair',
			NS_HELP_TALK        => 'Plé_cabhrach',
			NS_CATEGORY         => 'Catagóir',
			NS_CATEGORY_TALK    => 'Plé_catagóire'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesGa + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsGa;
	}

	function getSkinNames() {
		return $this->mSkinNamesGa + parent::getSkinNames();
	}

	function getDateFormats() {
		return $this->mDateFormatsGa;
	}

	function &getMagicWords()  {
		$t = $this->mMagicWordsGa + parent::getMagicWords();
		return $t;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesGa[$key] ) ) {
			return $this->mMessagesGa[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesGa;
	}


	/**
	 * Get a namespace key by value, case insensetive.
	 *
	 * @param string $text
	 * @return mixed An integer if $text is a valid value otherwise false
	 */
	function getNsIndex( $text ) {
		$ns = $this->getNamespaces();

		foreach ( $ns as $i => $n ) {
			if ( strcasecmp( $n, $text ) == 0)
				return $i;
		}

		if ( strcasecmp( 'Plé_í­omhá', $text) == 0) return NS_IMAGE_TALK;
		if ( strcasecmp( 'Múnla', $text) == 0) return NS_TEMPLATE;
		if ( strcasecmp( 'Plé_múnla', $text) == 0) return NS_TEMPLATE_TALK;
		if ( strcasecmp( 'Rang', $text) == 0) return NS_CATEGORY;

		return false;
	}

	# Convert day names
	# Invoked with {{GRAMMAR:transformation|word}}
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms['ga'][$case][$word]) ) {
			return $wgGrammarForms['ga'][$case][$word];
		}

		switch ( $case ) {
		case 'genitive':
			switch ($word) {
			case 'Vicipéid':     $word = 'Vicipéide'; break;
			case 'Vicífhoclóir': $word = 'Vicífhoclóra'; break;
			case 'Vicíleabhair': $word = 'Vicíleabhar'; break;
			case 'Vicíshliocht': $word = 'Vicíshleachta'; break;
			case 'Vicífhoinse':  $word = 'Vicífhoinse'; break;
			case 'Vicíghnéithe': $word = 'Vicíghnéithe'; break;
			case 'Vicínuacht':   $word = 'Vicínuachta'; break;
			}

		case 'ainmlae':
			switch ($word) {
			case 'an Domhnach':
				$word = 'Dé Domhnaigh'; break;
			case 'an Luan':
				$word = 'Dé Luain'; break;
			case 'an Mháirt':
				$word = 'Dé Mháirt'; break;
			case 'an Chéadaoin':
				$word = 'Dé Chéadaoin'; break;
			case 'an Déardaoin':
				$word = 'Déardaoin'; break;
			case 'an Aoine':
				$word = 'Dé hAoine'; break;
			case 'an Satharn':
				$word = 'Dé Sathairn'; break;
			}
		}
		return $word;
	}

}

?>
