<?php
/** Czech (česky)
 *
 * @package MediaWiki
 * @subpackage Language
 */

/** */
require_once( 'LanguageUtf8.php' );

# Yucky hardcoding hack
switch( $wgMetaNamespace ) {
case 'Wikipedie':
case 'Wikipedia':
	$wgUserNamespace = 'Wikipedista'; break;
default:
	$wgUserNamespace = 'Uživatel';
}

/* private */ $wgNamespaceNamesCs = array(
	NS_MEDIA              => 'Média',
	NS_SPECIAL            => 'Speciální',
	NS_MAIN               => '',
	NS_TALK               => 'Diskuse',
	NS_USER               => $wgUserNamespace,
	NS_USER_TALK          => $wgUserNamespace . '_diskuse',
	NS_PROJECT            => $wgMetaNamespace,
	NS_PROJECT_TALK	      => $wgMetaNamespace . '_diskuse',
	NS_IMAGE              => 'Soubor',
	NS_IMAGE_TALK         => 'Soubor_diskuse',
	NS_MEDIAWIKI          => 'MediaWiki',
	NS_MEDIAWIKI_TALK     => 'MediaWiki_diskuse',
	NS_TEMPLATE           => 'Šablona',
	NS_TEMPLATE_TALK      => 'Šablona_diskuse',
	NS_HELP               => 'Nápověda',
	NS_HELP_TALK          => 'Nápověda_diskuse',
	NS_CATEGORY           => 'Kategorie',
	NS_CATEGORY_TALK      => 'Kategorie_diskuse',
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsCs = array(
	'Žádný', 'Leží vlevo', 'Leží vpravo', 'Visí vlevo'
);

/* private */ $wgSkinNamesCs = array(
	'standard'            => 'Standard',
	'nostalgia'           => 'Nostalgie',
	'cologneblue'         => 'Kolínská modř',
	'chick'               => 'Kuře'
) + $wgSkinNamesEn;

# Hledání knihy podle ISBN
# $wgBookstoreListCs = ..
/* private */ $wgBookstoreListCs = array(
    'Národní knihovna'			=> 'http://sigma.nkp.cz/F/?func=find-a&find_code=ISN&request=$1',
    'Státní technická knihovna' => 'http://www.stk.cz/cgi-bin/dflex/CZE/STK/BROWSE?A=01&V=$1'
) + $wgBookstoreListEn;

# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
# Nepoužívá se, pro používání je třeba povolit getMagicWords dole v LanguageCs.
/* private */ $wgMagicWordsCs = array(
##   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0,    '#REDIRECT',        '#PŘESMĚRUJ'     ),
	'notoc'                  => array( 0,    '__NOTOC__',        '__BEZOBSAHU__'  ),
	'forcetoc'               => array( 0,    '__FORCETOC__',     '__VŽDYOBSAH__'  ),
	'toc'                    => array( 0,    '__TOC__',          '__OBSAH__'      ),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__', '__BEZEDITOVATČÁST__' ),
	'start'                  => array( 0,    '__START__',        '__ZAČÁTEK__'        ),
	'currentmonth'           => array( 1,    'CURRENTMONTH',     'AKTUÁLNÍMĚSÍC'      ),
	'currentmonthname'       => array( 1,    'CURRENTMONTHNAME', 'AKTUÁLNÍMĚSÍCJMÉNO' ),
	'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN', 'AKTUÁLNÍMĚSÍCGEN' ),
#	'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV' 'AKTUÁLNÍMĚSÍCZKR'  ),
	'currentday'             => array( 1,    'CURRENTDAY',       'AKTUÁLNÍDEN' ),
	'currentdayname'         => array( 1,    'CURRENTDAYNAME',   'AKTUÁLNÍDENJMÉNO'   ),
	'currentyear'            => array( 1,    'CURRENTYEAR',      'AKTUÁLNÍROK'        ),
	'currenttime'            => array( 1,    'CURRENTTIME',      'AKTUÁLNÍČAS'        ),
	'numberofarticles'       => array( 1,    'NUMBEROFARTICLES', 'POČETČLÁNKŮ'        ),
	'pagename'               => array( 1,    'PAGENAME',         'NÁZEVSTRANY'        ),
	'pagenamee'  			 => array( 1,    'PAGENAMEE',        'NÁZEVSTRANYE'       ),
	'namespace'              => array( 1,    'NAMESPACE',        'JMENNÝPROSTOR'      ),
	'msg'                    => array( 0,    'MSG:'                   ),
	'subst'                  => array( 0,    'SUBST:',           'VLOŽIT:'            ),
	'msgnw'                  => array( 0,    'MSGNW:',           'VLOŽITNW:'          ),
	'end'                    => array( 0,    '__END__',          '__KONEC__'          ),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb', 'náhled'           ),
	'img_right'              => array( 1,    'right',            'vpravo'             ),
	'img_left'               => array( 1,    'left',             'vlevo'              ),
	'img_none'               => array( 1,    'none',             'žádné'              ),
	'img_width'              => array( 1,    '$1px'                   ),
	'img_center'             => array( 1,    'center', 'centre', 'střed'              ),
	'img_framed'  	         => array( 1,    'framed', 'enframed', 'frame', 'rám'     ),
	'int'                    => array( 0,    'INT:'                   ),
	'sitename'               => array( 1,    'SITENAME',         'NÁZEVSERVERU'       ),
	'ns'                     => array( 0,    'NS:'                    ),
	'localurl'               => array( 0,    'LOCALURL:',        'MÍSTNÍURL:'         ),
	'localurle'              => array( 0,    'LOCALURLE:',       'MÍSTNÍURLE:'        ),
	'server'                 => array( 0,    'SERVER'                 ),
	'revisionid'             => array( 1,    'REVISIONID',       'IDREVIZE'           )
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesCs.php');
}

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageCs extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListCs ;
		return $wgBookstoreListCs ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesCs;
		return $wgNamespaceNamesCs;
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesCs;

		foreach ( $wgNamespaceNamesCs as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsCs;
		return $wgQuickbarSettingsCs;
	}

	function getSkinNames() {
		global $wgSkinNamesCs;
		return $wgSkinNamesCs;
	}

	function getMonthNameGen( $key ) {
		#TODO: převést na return $this->convertGrammar( $this->getMonthName( $key ), '2sg' );
		global $wgMonthNamesGenEn, $wgContLang;
		// see who called us and use the correct message function
		if( get_class( $wgContLang->getLangObj() ) == get_class( $this ) )
			return wfMsgForContent( $wgMonthNamesGenEn[$key-1] );
		else
			return wfMsg( $wgMonthNamesGenEn[$key-1] );
	}

	function formatMonth( $month, $format ) {
		return intval( $month ) . '.';
	}

	function formatDay( $day, $format ) {
		return intval( $day ) . '.';
	}

	function getMessage( $key ) {
		global $wgAllMessagesCs;
		if(array_key_exists($key, $wgAllMessagesCs))
			return $wgAllMessagesCs[$key];
		else
			return parent::getMessage($key);
	}

	function getAllMessages() {
		global $wgAllMessagesCs;
		return $wgAllMessagesCs;
	}

	function checkTitleEncoding( $s ) {

		# Check for non-UTF-8 URLs; assume they are WinLatin2
		$ishigh = preg_match( '/[\x80-\xff]/', $s);
		$isutf = ($ishigh ? preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
		'[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s ) : true );

		if( $ishigh and !$isutf ) {
			return iconv( 'cp1250', 'utf-8', $s );
		}

		return $s;
	}

	function separatorTransformTable() {
		return array(',' => "\xc2\xa0", '.' => ',' );
	}

	# Grammatical transformations, needed for inflected languages
	# Invoked by putting {{grammar:case|word}} in a message
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms['cs'][$case][$word]) ) {
			return $wgGrammarForms['cs'][$case][$word];
		}
		# allowed values for $case:
		#	1sg, 2sg, ..., 7sg -- nominative, genitive, ... (in singular)
		switch ( $word ) {
			case 'Wikipedia':
			case 'Wikipedie':
				switch ( $case ) {
					case '3sg':
					case '4sg':
					case '6sg':
						return 'Wikipedii';
					case '7sg':
						return 'Wikipedií';
					default:
						return 'Wikipedie';
				}

			case 'Wiktionary':
			case 'Wikcionář':
				switch ( $case ) {
					case '2sg':
						return 'Wikcionáře';
					case '3sg':
					case '5sg';
					case '6sg';
						return 'Wikcionáři';
					case '7sg':
						return 'Wikcionářem';
					default:
						return 'Wikcionář';
				}

			case 'Wikiquote':
			case 'Wikicitáty':
				switch ( $case ) {
					case '2sg':
						return 'Wikicitátů';
					case '3sg':
						return 'Wikicitátům';
					case '6sg';
						return 'Wikicitátech';
					default:
						return 'Wikicitáty';
				}
		}
		# unknown
		return $word;
	}

  # Plural form transformations, needed for some languages.
  # Invoked by {{plural:count|wordform1|wordform2|wordform3}}
  function convertPlural( $count, $wordform1, $wordform2, $wordform3) {
    switch ( $count ) {
      case 1:
        return $wordform1;

      case 2:
      case 3:
      case 4:
        return $wordform2;

      default:
        return $wordform3;
    };
  }
}

?>
