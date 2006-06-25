<?php
/** Bosnian (bosanski)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesBs.php');
}

class LanguageBs extends LanguageUtf8 {
	private $mMessagesBs, $mNamespaceNamesBs = null;

	private $mQuickbarSettingsBs = array(
		'Nikakva', 'Pričvršćena lijevo', 'Pričvršćena desno', 'Plutajuća lijevo'
	);
	
	private $mSkinNamesBs = array(
		'Obična', 'Nostalgija', 'Kelnsko plavo', 'Pedington', 'Monparnas'
	);
	
	private $mDateFormatsBs = array(
		'Nije bitno',
		'06:12, 5. januar 2001.',
		'06:12, 5 januar 2001',
		'06:12, 05.01.2001.',
		'06:12, 5.1.2001.',
		'06:12, 5. jan 2001.',
		'06:12, 5 jan 2001',
		'6:12, 5. januar 2001.',
		'6:12, 5 januar 2001',
		'6:12, 05.01.2001.',
		'6:12, 5.1.2001.',
		'6:12, 5. jan 2001.',
		'6:12, 5 jan 2001',
	);
	
	private $mMagicWordsBs = array(
		# ID                              CASE SYNONYMS
		MAG_REDIRECT             => array( 0, '#Preusmjeri', '#redirect', '#preusmjeri', '#PREUSMJERI' ),
		MAG_NOTOC                => array( 0, '__NOTOC__', '__BEZSADRŽAJA__' ),
		MAG_FORCETOC             => array( 0, '__FORCETOC__', '__FORSIRANISADRŽAJ__' ),
		MAG_TOC                  => array( 0, '__TOC__', '__SADRŽAJ__' ),
		MAG_NOEDITSECTION        => array( 0, '__NOEDITSECTION__', '__BEZ_IZMENA__', '__BEZIZMENA__' ),
		MAG_START                => array( 0, '__START__', '__POČETAK__' ),
		MAG_END                  => array( 0, '__END__', '__KRAJ__' ),
		MAG_CURRENTMONTH         => array( 1, 'CURRENTMONTH', 'TRENUTNIMJESEC' ),
		MAG_CURRENTMONTHNAME     => array( 1, 'CURRENTMONTHNAME', 'TRENUTNIMJESECIME' ),
		MAG_CURRENTMONTHNAMEGEN  => array( 1, 'CURRENTMONTHNAMEGEN', 'TRENUTNIMJESECROD' ),
		MAG_CURRENTMONTHABBREV   => array( 1, 'CURRENTMONTHABBREV', 'TRENUTNIMJESECSKR' ),
		MAG_CURRENTDAY           => array( 1, 'CURRENTDAY', 'TRENUTNIDAN' ),
		MAG_CURRENTDAYNAME       => array( 1, 'CURRENTDAYNAME', 'TRENUTNIDANIME' ),
		MAG_CURRENTYEAR          => array( 1, 'CURRENTYEAR', 'TRENUTNAGODINA' ),
		MAG_CURRENTTIME          => array( 1, 'CURRENTTIME', 'TRENUTNOVRIJEME' ),
		MAG_NUMBEROFARTICLES     => array( 1, 'NUMBEROFARTICLES', 'BROJČLANAKA' ),
		MAG_NUMBEROFFILES        => array( 1, 'NUMBEROFFILES', 'BROJDATOTEKA', 'BROJFAJLOVA' ),
		MAG_PAGENAME             => array( 1, 'PAGENAME', 'STRANICA' ),
		MAG_PAGENAMEE            => array( 1, 'PAGENAMEE', 'STRANICE' ),
		MAG_NAMESPACE            => array( 1, 'NAMESPACE', 'IMENSKIPROSTOR' ),
		MAG_NAMESPACEE           => array( 1, 'NAMESPACEE', 'IMENSKIPROSTORI' ),
		MAG_FULLPAGENAME         => array( 1, 'FULLPAGENAME', 'PUNOIMESTRANE' ),
		MAG_FULLPAGENAMEE        => array( 1, 'FULLPAGENAMEE', 'PUNOIMESTRANEE' ),
		MAG_MSG                  => array( 0, 'MSG:', 'POR:' ),
		MAG_SUBST                => array( 0, 'SUBST:', 'ZAMJENI:' ),
		MAG_MSGNW                => array( 0, 'MSGNW:', 'NVPOR:' ),
		MAG_IMG_THUMBNAIL        => array( 1, 'thumbnail', 'thumb', 'mini' ),
		MAG_IMG_MANUALTHUMB      => array( 1, 'thumbnail=$1', 'thumb=$1', 'mini=$1' ),
		MAG_IMG_RIGHT            => array( 1, 'right', 'desno', 'd' ),
		MAG_IMG_LEFT             => array( 1, 'left', 'lijevo', 'l' ),
		MAG_IMG_NONE             => array( 1, 'none', 'n', 'bez' ),
		MAG_IMG_WIDTH            => array( 1, '$1px', '$1piksel' , '$1p' ),
		MAG_IMG_CENTER           => array( 1, 'center', 'centre', 'centar', 'c' ),
		MAG_IMG_FRAMED           => array( 1, 'framed', 'enframed', 'frame', 'okvir', 'ram' ),
		MAG_INT                  => array( 0, 'INT:', 'INT:' ),
		MAG_SITENAME             => array( 1, 'SITENAME', 'IMESAJTA' ),
		MAG_NS                   => array( 0, 'NS:', 'IP:' ),
		MAG_LOCALURL             => array( 0, 'LOCALURL:', 'LOKALNAADRESA:' ),
		MAG_LOCALURLE            => array( 0, 'LOCALURLE:', 'LOKALNEADRESE:' ),
		MAG_SERVER               => array( 0, 'SERVER', 'SERVER' ),
		MAG_SERVERNAME           => array( 0, 'SERVERNAME', 'IMESERVERA' ),
		MAG_SCRIPTPATH           => array( 0, 'SCRIPTPATH', 'SKRIPTA' ),
		MAG_GRAMMAR              => array( 0, 'GRAMMAR:', 'GRAMATIKA:' ),
		MAG_NOTITLECONVERT       => array( 0, '__NOTITLECONVERT__', '__NOTC__', '__BEZTC__' ),
		MAG_NOCONTENTCONVERT     => array( 0, '__NOCONTENTCONVERT__', '__NOCC__', '__BEZCC__' ),
		MAG_CURRENTWEEK          => array( 1, 'CURRENTWEEK', 'TRENUTNASEDMICA' ),
		MAG_CURRENTDOW           => array( 1, 'CURRENTDOW', 'TRENUTNIDOV' ),
		MAG_REVISIONID           => array( 1, 'REVISIONID', 'IDREVIZIJE' ),
		MAG_PLURAL               => array( 0, 'PLURAL:', 'MNOŽINA:' ),
		MAG_FULLURL              => array( 0, 'FULLURL:', 'PUNURL:' ),
		MAG_FULLURLE             => array( 0, 'FULLURLE:', 'PUNURLE:' ),
		MAG_LCFIRST              => array( 0, 'LCFIRST:', 'LCPRVI:' ),
		MAG_UCFIRST              => array( 0, 'UCFIRST:', 'UCPRVI:' ),
		MAG_LC                   => array( 0, 'LC:', 'LC:' ),
		MAG_UC                   => array( 0, 'UC:', 'UC:' ),
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesBs;
		$this->mMessagesBs =& $wgAllMessagesBs;

		global $wgMetaNamespace;
		$this->mNamespaceNamesBs = array(
			NS_MEDIA            => 'Medija',
			NS_SPECIAL          => 'Posebno',
			NS_MAIN             => '',
			NS_TALK             => 'Razgovor',
			NS_USER             => 'Korisnik',
			NS_USER_TALK        => 'Razgovor_sa_korisnikom',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => 'Razgovor_' . str_replace( ' ', '_',
				$this->convertGrammar( $wgMetaNamespace, 'instrumental' ) ),
			NS_IMAGE            => 'Slika',
			NS_IMAGE_TALK       => 'Razgovor_o_slici',
			NS_MEDIAWIKI        => 'MedijaViki',
			NS_MEDIAWIKI_TALK   => 'Razgovor_o_MedijaVikiju',
			NS_TEMPLATE         => 'Šablon',
			NS_TEMPLATE_TALK    => 'Razgovor_o_šablonu',
			NS_HELP             => 'Pomoć',
			NS_HELP_TALK        => 'Razgovor_o_pomoći',
			NS_CATEGORY         => 'Kategorija',
			NS_CATEGORY_TALK    => 'Razgovor_o_kategoriji',
		);
	}

	function getNamespaces() {
		return $this->mNamespaceNamesBs + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsBs;
	}

	function getSkinNames() {
		return $this->mSkinNamesBs + parent::getSkinNames();
	}

	// Not implemented ??
/*	function getDateFormats() {
		return $this->mDateFormatsBs;
	}*/

	function getMessage( $key ) {
		if( isset( $this->mMessagesBs[$key] ) ) {
			return $this->mMessagesBs[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesBs;
	}

	function fallback8bitEncoding() {
		return "iso-8859-2";
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

	function convertPlural( $count, $wordform1, $wordform2, $wordform3) {
		$count = str_replace ('.', '', $count);
		if ($count > 10 && floor(($count % 100) / 10) == 1) {
			return $wordform3;
		} else {
			switch ($count % 10) {
				case 1: return $wordform1;
				case 2:
				case 3:
				case 4: return $wordform2;
				default: return $wordform3;
			}
		}
	}

	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	/**
	 * Cases: genitiv, dativ, akuzativ, vokativ, instrumental, lokativ
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms['bs'][$case][$word]) ) {
			return $wgGrammarForms['bs'][$case][$word];
		}
		switch ( $case ) {
			case 'genitiv': # genitive
				if ( $word == 'Wikipedia' ) {
					$word = 'Wikipedije';
				} elseif ( $word == 'Wikiknjige' ) {
					$word = 'Wikiknjiga';
				} elseif ( $word == 'Wikivijesti' ) {
					$word = 'Wikivijesti';
				} elseif ( $word == 'Wikicitati' ) {
					$word = 'Wikicitata';
				} elseif ( $word == 'Wikiizvor' ) {
					$word = 'Wikiizvora';
				} elseif ( $word == 'Vikirječnik' ) {
					$word = 'Vikirječnika';
				}
			break;
			case 'dativ': # dative
				if ( $word == 'Wikipedia' ) {
					$word = 'Wikipediji';
				} elseif ( $word == 'Wikiknjige' ) {
					$word = 'Wikiknjigama';
				} elseif ( $word == 'Wikicitati' ) {
					$word = 'Wikicitatima';
				} elseif ( $word == 'Wikivijesti' ) {
					$word = 'Wikivijestima';
				} elseif ( $word == 'Wikiizvor' ) {
					$word = 'Wikiizvoru';
				} elseif ( $word == 'Vikirječnik' ) {
					$word = 'Vikirječniku';
				}
			break;
			case 'akuzativ': # akusative
				if ( $word == 'Wikipedia' ) {
					$word = 'Wikipediju';
				} elseif ( $word == 'Wikiknjige' ) {
					$word = 'Wikiknjige';
				} elseif ( $word == 'Wikicitati' ) {
					$word = 'Wikicitate';
				} elseif ( $word == 'Wikivijesti' ) {
					$word = 'Wikivijesti';
				} elseif ( $word == 'Wikiizvor' ) {
					$word = 'Wikiizvora';
				} elseif ( $word == 'Vikirječnik' ) {
					$word = 'Vikirječnika';
				}
			break;
			case 'vokativ': # vocative
				if ( $word == 'Wikipedia' ) {
					$word = 'Wikipedijo';
				} elseif ( $word == 'Wikiknjige' ) {
					$word = 'Wikiknjige';
				} elseif ( $word == 'Wikicitati' ) {
					$word = 'Wikicitati';
				} elseif ( $word == 'Wikivijesti' ) {
					$word = 'Wikivijesti';
				} elseif ( $word == 'Wikiizvor' ) {
					$word = 'Wikizivoru';
				} elseif ( $word == 'Vikirječnik' ) {
					$word = 'Vikirječniče';
				}
			break;
			case 'instrumental': # instrumental
				if ( $word == 'Wikipedia' ) {
					$word = 's Wikipediom';
				} elseif ( $word == 'Wikiknjige' ) {
					$word = 's Wikiknjigama';
				} elseif ( $word == 'Wikicitati' ) {
					$word = 's Wikicitatima';
				} elseif ( $word == 'Wikivijesti' ) {
					$word = 's Wikivijestima';
				} elseif ( $word == 'Wikiizvor' ) {
					$word = 's Wikiizvorom';
				} elseif ( $word == 'Vikirječnik' ) {
					$word = 's Vikirječnikom';
				} else {
					$word = 's ' . $word;
				}
			break;
			case 'lokativ': # locative
				if ( $word == 'Wikipedia' ) {
					$word = 'o Wikipediji';
				} elseif ( $word == 'Wikiknjige' ) {
					$word = 'o Wikiknjigama';
				} elseif ( $word == 'Wikicitati' ) {
					$word = 'o Wikicitatima';
				} elseif ( $word == 'Wikivijesti' ) {
					$word = 'o Wikivijestima';
				} elseif ( $word == 'Wikiizvor' ) {
					$word = 'o Wikiizvoru';
				} elseif ( $word == 'Vikirječnik' ) {
					$word = 'o Vikirječniku';
				} else {
					$word = 'o ' . $word;
				}
			break;
		}

		return $word; # this will return the original value for 'nominativ' (nominative) and all undefined case values
	}

}

?>