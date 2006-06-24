<?php
/**
 * Slovak (Slovenčina)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesSk.php');
}

class LanguageSk extends LanguageUtf8 {

	private $mQuickbarSettingsSk = array(
		'Žiadne', 'Ukotvené vľavo', 'Ukotvené vpravo', 'Plávajúce vľavo'
	);
	
	private $mDateFormatsSk = array(
		'Default',
		'15. január 2001 16:12',
		'15. jan. 2001 16:12',
		'16:12, 15. január 2001',
		'16:12, 15. jan. 2001',
		'ISO 8601' => '2001-01-15 16:12:34'
	);
	
	private $mBookstoreListSk = array(
		'Bibsys' => 'http://ask.bibsys.no/ask/action/result?cmd=&kilde=biblio&fid=isbn&term=$1',
		'BokBerit' => 'http://www.bokberit.no/annet_sted/bocker/$1.html',
		'Bokkilden' => 'http://www.bokkilden.no/ProductDetails.aspx?ProductId=$1',
		'Haugenbok' => 'http://www.haugenbok.no/searchresults.cfm?searchtype=simple&isbn=$1',
		'Akademika' => 'http://www.akademika.no/sok.php?isbn=$1',
		'Gnist' => 'http://www.gnist.no/sok.php?isbn=$1',
		'Amazon.co.uk' => 'http://www.amazon.co.uk/exec/obidos/ISBN=$1',
		'Amazon.de' => 'http://www.amazon.de/exec/obidos/ISBN=$1',
		'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
	);
	
	# Note to translators:
	# Please include the English words as synonyms. This allows people
	# from other wikis to contribute more easily.
	#
	private $mMagicWordsSk = array(
	# ID CASE SYNONYMS
		MAG_REDIRECT => array( 0, '#redirect', '#presmeruj' ),
		MAG_NOTOC => array( 0, '__NOTOC__', '__BEZOBSAHU__' ),
		MAG_FORCETOC => array( 0, '__FORCETOC__', '__VYNÚŤOBSAH__' ),
		MAG_TOC => array( 0, '__TOC__', '__OBSAH__' ),
		MAG_NOEDITSECTION => array( 0, '__NOEDITSECTION__', '__NEUPRAVUJSEKCIE__' ),
		MAG_START => array( 0, '__START__', '__ŠTART__' ),
		MAG_CURRENTMONTH => array( 1, 'CURRENTMONTH', 'MESIAC' ),
		MAG_CURRENTMONTHNAME => array( 1, 'CURRENTMONTHNAME', 'MENOMESIACA' ),
		MAG_CURRENTMONTHNAMEGEN => array( 1, 'CURRENTMONTHNAMEGEN', 'MENOAKTUÁLNEHOMESIACAGEN' ),
		MAG_CURRENTMONTHABBREV   => array( 1, 'CURRENTMONTHABBREV', 'MENOAKTUÁLNEHOMESIACASKRATKA' ),
		MAG_CURRENTDAY => array( 1, 'CURRENTDAY', 'AKTUÁLNYDEŇ' ),
		MAG_CURRENTDAYNAME => array( 1, 'CURRENTDAYNAME', 'MENOAKTUÁLNEHODŇA' ),
		MAG_CURRENTYEAR => array( 1, 'CURRENTYEAR', 'AKTUÁLNYROK' ),
		MAG_CURRENTTIME => array( 1, 'CURRENTTIME', 'AKTUÁLNYČAS' ),
		MAG_NUMBEROFARTICLES => array( 1, 'NUMBEROFARTICLES', 'POČETČLÁNKOV' ),
		MAG_PAGENAME => array( 1, 'PAGENAME', 'MENOSTRÁNKY' ),
		MAG_PAGENAMEE => array( 1, 'PAGENAMEE' ),
		MAG_NAMESPACE => array( 1, 'NAMESPACE', 'MENNÝPRIESTOR' ),
		MAG_MSG => array( 0, 'MSG:', 'SPRÁVA:' ),
		MAG_SUBST => array( 0, 'SUBST:' ),
		MAG_MSGNW => array( 0, 'MSGNW:' ),
		MAG_END => array( 0, '__END__', '__KONIEC__' ),
		MAG_IMG_THUMBNAIL => array( 1, 'thumbnail', 'thumb', 'náhľad', 'náhľadobrázka' ),
		MAG_IMG_RIGHT => array( 1, 'right', 'vpravo' ),
		MAG_IMG_LEFT => array( 1, 'left', 'vľavo' ),
		MAG_IMG_NONE => array( 1, 'none', 'žiadny' ),
		MAG_IMG_WIDTH => array( 1, '$1px', '$1bod' ),
		MAG_IMG_CENTER => array( 1, 'center', 'centre', 'stred' ),
		MAG_IMG_FRAMED => array( 1, 'framed', 'enframed', 'frame', 'rám' ),
		MAG_INT => array( 0, 'INT:' ),
		MAG_SITENAME => array( 1, 'SITENAME', 'MENOLOKALITY' ),
		MAG_NS => array( 0, 'NS:', 'MP:' ),
		MAG_LOCALURL => array( 0, 'LOCALURL:' ),
		MAG_LOCALURLE => array( 0, 'LOCALURLE:' ),
		MAG_SERVER => array( 0, 'SERVER' ),
		MAG_GRAMMAR => array( 0, 'GRAMMAR:', 'GRAMATIKA:' ),
		MAG_NOTITLECONVERT => array( 0, '__NOTITLECONVERT__', '__NOTC__' ),
		MAG_NOCONTENTCONVERT => array( 0, '__NOCONTENTCONVERT__', '__NOCC__' ),
		MAG_CURRENTWEEK => array( 1, 'CURRENTWEEK', 'AKTUÁLNYTÝŽDEŇ' ),
		MAG_CURRENTDOW => array( 1, 'CURRENTDOW' ),
		MAG_REVISIONID => array( 1, 'REVISIONID' ),
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesSk;
		$this->mMessagesSk =& $wgAllMessagesSk;

		global $wgMetaNamespace;
		$this->mNamespaceNamesSk = array(
			NS_MEDIA          => 'Médiá',
			NS_SPECIAL        => 'Špeciálne',
			NS_MAIN           => '',
			NS_TALK           => 'Diskusia',
			NS_USER           => 'Redaktor',
			NS_USER_TALK      => 'Diskusia_s_redaktorom',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => 'Diskusia_k_' . $this->convertGrammar( $wgMetaNamespace, 'datív' ),
			NS_IMAGE          => 'Obrázok',
			NS_IMAGE_TALK     => 'Diskusia_k_obrázku',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'Diskusia_k_MediaWiki',
			NS_TEMPLATE       => 'Šablóna',
			NS_TEMPLATE_TALK  => 'Diskusia_k_šablóne',
			NS_HELP           => 'Pomoc',
			NS_HELP_TALK      => 'Diskusia_k_pomoci',
			NS_CATEGORY       => 'Kategória',
			NS_CATEGORY_TALK  => 'Diskusia_ku_kategórii'
		);
	}

	function getNamespaces() {
		return $this->mNamespaceNamesSk + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsSk;
	}

	function getDateFormats() {
		return $this->mDateFormatsSk;
	}

	function getBookstoreList() {
		return $this->mBookstoreListSk;
	}

	function &getMagicWords()  {
		$t = $this->mMagicWordsSk + parent::getMagicWords();
		return $t;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesSk[$key] ) ) {
			return $this->mMessagesSk[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesSk;
	}

	function getNsIndex( $text ) {

		foreach ( $this->mNamespaceNamesSk as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		# Compatbility with old names:
		if( 0 == strcasecmp( "Komentár", $text ) ) { return NS_TALK; }
		if( 0 == strcasecmp( "Komentár_k_redaktorovi", $text ) ) { return NS_USER_TALK; }
		if( 0 == strcasecmp( "Komentár_k_Wikipédii", $text ) ) { return NS_PROJECT_TALK; }
		if( 0 == strcasecmp( "Komentár_k_obrázku", $text ) ) { return NS_IMAGE_TALK; }
		if( 0 == strcasecmp( "Komentár_k_MediaWiki", $text ) ) { return NS_MEDIAWIKI_TALK; }
		return false;
	}

	function separatorTransformTable() {
		return array(
			',' => "\xc2\xa0",
			'.' => ','
		);
	}

	function linkTrail() {
		return '/^([a-záäčďéíľĺňóôŕšťúýž]+)(.*)$/sDu';
	}

	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	/**
	 * Cases: genitív, datív, akuzatív, lokál, inštrumentál
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms['sk'][$case][$word]) ) {
			return $wgGrammarForms['sk'][$case][$word];
		}

		switch ( $case ) {
			case 'genitív':
				if ( $word == 'Wikipédia' ) {
					$word = 'Wikipédie';
				} elseif ( $word == 'Wikislovník' ) {
					$word = 'Wikislovníku';
				} elseif ( $word == 'Wikicitáty' ) {
					$word = 'Wikicitátov';
				} elseif ( $word == 'Wikiknihy' ) {
					$word = 'Wikikníh';
				}
			break;
			case 'datív':
				if ( $word == 'Wikipédia' ) {
					$word = 'Wikipédii';
				} elseif ( $word == 'Wikislovník' ) {
					$word = 'Wikislovníku';
				} elseif ( $word == 'Wikicitáty' ) {
					$word = 'Wikicitátom';
				} elseif ( $word == 'Wikiknihy' ) {
					$word = 'Wikiknihám';
				}
			break;
			case 'akuzatív':
				if ( $word == 'Wikipédia' ) {
					$word = 'Wikipédiu';
				} elseif ( $word == 'Wikislovník' ) {
					$word = 'Wikislovník';
				} elseif ( $word == 'Wikicitáty' ) {
					$word = 'Wikicitáty';
				} elseif ( $word == 'Wikiknihy' ) {
					$word = 'Wikiknihy';
				}
			break;
			case 'lokál':
				if ( $word == 'Wikipédia' ) {
					$word = 'Wikipédii';
				} elseif ( $word == 'Wikislovník' ) {
					$word = 'Wikislovníku';
				} elseif ( $word == 'Wikicitáty' ) {
					$word = 'Wikicitátoch';
				} elseif ( $word == 'Wikiknihy' ) {
					$word = 'Wikiknihách';
				}
			break;
			case 'inštrumentál':
				if ( $word == 'Wikipédia' ) {
					$word = 'Wikipédiou';
				} elseif ( $word == 'Wikislovník' ) {
					$word = 'Wikislovníkom';
				} elseif ( $word == 'Wikicitáty' ) {
					$word = 'Wikicitátmi';
				} elseif ( $word == 'Wikiknihy' ) {
					$word = 'Wikiknihami';
				}
			break;
		}
	return $word;
	}
	
	function convertPlural( $count, $w1, $w2, $w3) {
		$count = str_replace ('.', '', $count);
		$forms = array( $w1, $w2, $w3);
		if ( $count == 1 ) {
			$index = 0;
		} elseif ( $count == 2 || $count == 3 || $count == 4 ) {
			$index = 1;
		} else {
			$index = 2;
		}
		return $forms[$index];
	}

}
?>
