<?php

/* Gaeilge - Irish */

/* private */ $wgNamespaceNamesGa = array(
	NS_MEDIA	    => 'Meán',
	NS_SPECIAL          => 'Speisialta',
	NS_MAIN             => '',
	NS_TALK             => 'Plé',
	NS_USER             => 'Úsáideoir',
	NS_USER_TALK        => 'Plé_úsáideora',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => FALSE,  # Set in constructor
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
) + $wgNamespaceNamesEn;


/* private */ $wgQuickbarSettingsGa = array(
	"Faic", "Greamaithe ar chlé", "Greamaithe ar dheis", "Ag faoileáil ar chlé", "Ag faoileáil ar dheis"
);

/* private */ $wgSkinNamesGa = array(
	'standard' => "Gnáth",
	'nostalgia' => "Sean-nós",
	'cologneblue' => "Gorm na Colóna",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
	"myskin" => "MySkin",
	'chick' => 'Chick'
) + $wgSkinNamesEn;

/* private */ $wgDateFormatsGa = array(
	'Is cuma liom',
	'16:12, Eanáir 15, 2001',
	'16:12, 15 Eanáir 2001',
	'16:12, 2001 Eanáir 15',
	'ISO 8601' => '2001-01-15 16:12:34'
);

/* private */ $wgMagicWordsGa = array(
#   ID	                         CASE  SYNONYMS
	MAG_REDIRECT             => array( 0,    '#redirect', '#athsheoladh' ),
	MAG_NOTOC                => array( 0,    '__NOTOC__', '__GANCÁ__'              ),
	MAG_FORCETOC             => array( 0,    '__FORCETOC__',         '__CÁGACHUAIR__'  ),
	MAG_TOC                  => array( 0,    '__TOC__', '__CÁ__'                ),
	MAG_NOEDITSECTION        => array( 0,    '__NOEDITSECTION__',    '__GANMHÍRATHRÚ__'  ),
	MAG_START                => array( 0,    '__START__', '__TÚS__'              ),
	MAG_CURRENTMONTH         => array( 1,    'CURRENTMONTH',  'MÍLÁITHREACH'  ),
	MAG_CURRENTMONTHNAME     => array( 1,    'CURRENTMONTHNAME',     'AINMNAMÍOSALÁITHREAÍ'  ),
	MAG_CURRENTMONTHNAMEGEN  => array( 1,    'CURRENTMONTHNAMEGEN',  'GINAINMNAMÍOSALÁITHREAÍ'  ),
	MAG_CURRENTMONTHABBREV   => array( 1,    'CURRENTMONTHABBREV',   'GIORRÚNAMÍOSALÁITHREAÍ'  ),
	MAG_CURRENTDAY           => array( 1,    'CURRENTDAY',           'LÁLÁITHREACH'  ),
	MAG_CURRENTDAYNAME       => array( 1,    'CURRENTDAYNAME',       'AINMANLAELÁITHRIGH'  ),
	MAG_CURRENTYEAR          => array( 1,    'CURRENTYEAR',          'BLIAINLÁITHREACH'  ),
	MAG_CURRENTTIME          => array( 1,    'CURRENTTIME',          'AMLÁITHREACH'  ),
	MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES',     'LÍONNANALT'  ),
	MAG_NUMBEROFFILES        => array( 1,    'NUMBEROFFILES',        'LÍONNAGCOMHAD'  ),
	MAG_PAGENAME             => array( 1,    'PAGENAME',             'AINMANLGH'  ),
	MAG_PAGENAMEE            => array( 1,    'PAGENAMEE',            'AINMANLGHB'  ),
	MAG_NAMESPACE            => array( 1,    'NAMESPACE',            'AINMSPÁS'  ),
	MAG_MSG                  => array( 0,    'MSG:',                 'TCHT:'  ),
	MAG_SUBST                => array( 0,    'SUBST:',               'IONAD:'  ),
	MAG_MSGNW                => array( 0,    'MSGNW:',               'TCHTFS:'  ),
	MAG_END                  => array( 0,    '__END__',              '__DEIREADH__'  ),
	MAG_IMG_THUMBNAIL        => array( 1,    'thumbnail', 'thumb',   'mionsamhail', 'mion'  ),
	MAG_IMG_RIGHT            => array( 1,    'right',                'deas'  ),
	MAG_IMG_LEFT             => array( 1,    'left',                 'clé'  ),
	MAG_IMG_NONE             => array( 1,    'none',                 'faic'  ),
	MAG_IMG_WIDTH            => array( 1,    '$1px'                   ),
	MAG_IMG_CENTER           => array( 1,    'center', 'centre',     'lár'  ),
	MAG_IMG_FRAMED           => array( 1,    'framed', 'enframed', 'frame', 'fráma', 'frámaithe' ),
	MAG_INT                  => array( 0,    'INT:', 'INMH:'                   ),
	MAG_SITENAME             => array( 1,    'SITENAME',             'AINMANTSUÍMH'  ),
	MAG_NS                   => array( 0,    'NS:', 'AS:'                    ),
	MAG_LOCALURL             => array( 0,    'LOCALURL:',            'URLÁITIÚIL'  ),
	MAG_LOCALURLE            => array( 0,    'LOCALURLE:',           'URLÁITIÚILB'  ),
	MAG_SERVER               => array( 0,    'SERVER',               'FREASTALAÍ'  ),
	MAG_SERVERNAME           => array( 0,    'SERVERNAME',            'AINMANFHREASTALAÍ' ),
	MAG_SCRIPTPATH           => array( 0,    'SCRIPTPATH',           'SCRIPTCHOSÁN'  ),
	MAG_GRAMMAR              => array( 0,    'GRAMMAR:',             'GRAMADACH:'  ),
	MAG_NOTITLECONVERT       => array( 0,    '__NOTITLECONVERT__', '__NOTC__', '__GANTIONTÚNADTEIDEAL__', '__GANTT__'),
	MAG_NOCONTENTCONVERT     => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__', '__GANTIONTÚNANÁBHAIR__', '__GANTA__' ),
	MAG_CURRENTWEEK          => array( 1,    'CURRENTWEEK',          'SEACHTAINLÁITHREACH'  ),
	MAG_CURRENTDOW           => array( 1,    'CURRENTDOW',           'LÁLÁITHREACHNAS'  ),
	MAG_REVISIONID           => array( 1,    'REVISIONID',           'IDANLEASAITHE'  ),
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesGa.php');
}

require_once( "LanguageUtf8.php" );

class LanguageGa extends LanguageUtf8 {
	function LanguageGa() {
		global $wgNamespaceNamesGa, $wgMetaNamespace;
		LanguageUtf8::LanguageUtf8();
		$wgNamespaceNamesGa[NS_PROJECT_TALK] = 'Plé_' . $this->convertGrammar( $wgMetaNamespace, 'genitive' );
	}

	function getNamespaces() {
		global $wgNamespaceNamesGa;
		return $wgNamespaceNamesGa;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsGa;
		return $wgQuickbarSettingsGa;
	}

	function getSkinNames() {
		global $wgSkinNamesGa;
		return $wgSkinNamesGa;
	}

	function getDateFormats() {
		global $wgDateFormatsGa;
		return $wgDateFormatsGa;
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


	function getMessage( $key ) {
	global $wgAllMessagesGa;
		if( isset( $wgAllMessagesGa[$key] ) ) {
			return $wgAllMessagesGa[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		global $wgAllMessagesGa;
		return $wgAllMessagesGa;
	}

	function getMagicWords() {
		global $wgMagicWordsGa;
		return $wgMagicWordsGa;
	}

	# Convert day names
	# Invoked with {{GRAMMAR:transformation|word}}
	function convertGrammar( $word, $what ) {
		switch ( $what ) {
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
