<?php
/** Slovenian (Slovenščina)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 */

#
# Revision/
# Inačica 1.00.00 XJamRastafire 2003-07-08 |NOT COMPLETE
#         1.00.10 XJamRastafire 2003-11-03 |NOT COMPLETE
# ______________________________________________________
#         1.00.20 XJamRastafire 2003-11-05 |    COMPLETE
#         1.00.30 romanm        2003-11-07 |    minor changes
#         1.00.31 romanm        2003-11-11 |    merged incorrectly broken lines
#         1.00.32 romanm        2003-11-19 |    merged incorrectly broken lines
#         1.00.40 romanm        2003-11-21 |    fixed Google search


require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesSl.php');
}

class LanguageSl extends LanguageUtf8 {
	private $mMessagesSl, $mNamespaceNamesSl = null;

	private $mQuickbarSettingsSl = array(
		'Brez', 'Levo nepomično', 'Desno nepomično', 'Levo leteče'
	);

	private $mMonthNameGenSl = array(
		'januarja', 'februarja', 'marca', 'aprila', 'maja', 'junija',
		'julija', 'avgusta', 'septembra', 'oktobra', 'novembra', 'decembra'
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesSl;
		$this->mMessagesSl =& $wgAllMessagesSl;

		global $wgMetaNamespace;
		$this->mNamespaceNamesSl = array(
			NS_MEDIA          => 'Media',
			NS_SPECIAL        => 'Posebno',
			NS_MAIN           => '',
			NS_TALK           => 'Pogovor',
			NS_USER           => 'Uporabnik',
			NS_USER_TALK      => 'Uporabniški_pogovor',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => 'Pogovor_' .
			str_replace( ' ', '_', $this->convertGrammar( $wgMetaNamespace, 'mestnik' ) ),
			NS_IMAGE          => 'Slika',
			NS_IMAGE_TALK     => 'Pogovor_o_sliki',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'Pogovor_o_MediaWiki',
			NS_TEMPLATE       => 'Predloga',
			NS_TEMPLATE_TALK  => 'Pogovor_o_predlogi',
			NS_HELP           => 'Pomoč',
			NS_HELP_TALK      => 'Pogovor_o_pomoči',
			NS_CATEGORY       => 'Kategorija',
			NS_CATEGORY_TALK  => 'Pogovor_o_kategoriji'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesSl + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsSl;
	}

	function getDateFormats() {
		return false;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesSl[$key] ) ) {
			return $this->mMessagesSl[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesSl;
	}


	function fallback8bitEncoding() {
		return "iso-8859-2";
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

	function getMonthNameGen( $key ) {
		return $this->mMonthNameGenSl[$key-1];
	}


	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	/**
	 * Cases: rodilnik, dajalnik, tožilnik, mestnik, orodnik
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms['sl'][$case][$word]) ) {
			return $wgGrammarForms['sl'][$case][$word];
		}

		switch ( $case ) {
			case 'rodilnik': # genitive
				if ( $word == 'Wikipedija' ) {
					$word = 'Wikipedije';
				} elseif ( $word == 'Wikiknjige' ) {
					$word = 'Wikiknjig';
				} elseif ( $word == 'Wikinovice' ) {
					$word = 'Wikinovic';
				} elseif ( $word == 'Wikinavedek' ) {
					$word = 'Wikinavedka';
				} elseif ( $word == 'Wikivir' ) {
					$word = 'Wikivira';
				} elseif ( $word == 'Wikislovar' ) {
					$word = 'Wikislovarja';
				}
			break;
			case 'dajalnik': # dativ
				if ( $word == 'Wikipedija' ) {
					$word = 'Wikipediji';
				} elseif ( $word == 'Wikiknjige' ) {
					$word = 'Wikiknjigam';
				} elseif ( $word == 'Wikinovice' ) {
					$word = 'Wikinovicam';
				} elseif ( $word == 'Wikinavedek' ) {
					$word = 'Wikinavedku';
				} elseif ( $word == 'Wikivir' ) {
					$word = 'Wikiviru';
				} elseif ( $word == 'Wikislovar' ) {
					$word = 'Wikislovarju';
				}
			break;
			case 'tožilnik': # akuzatív
				if ( $word == 'Wikipedija' ) {
					$word = 'Wikipedijo';
				} elseif ( $word == 'Wikiknjige' ) {
					$word = 'Wikiknjige';
				} elseif ( $word == 'Wikinovice' ) {
					$word = 'Wikinovice';
				} elseif ( $word == 'Wikinavedek' ) {
					$word = 'Wikinavedek';
				} elseif ( $word == 'Wikivir' ) {
					$word = 'Wikivir';
				} elseif ( $word == 'Wikislovar' ) {
					$word = 'Wikislovar';
				}
			break;
			case 'mestnik': # locative
				if ( $word == 'Wikipedija' ) {
					$word = 'o Wikipediji';
				} elseif ( $word == 'Wikiknjige' ) {
					$word = 'o Wikiknjigah';
				} elseif ( $word == 'Wikinovice' ) {
					$word = 'o Wikinovicah';
				} elseif ( $word == 'Wikinavedek' ) {
					$word = 'o Wikinavedku';
				} elseif ( $word == 'Wikivir' ) {
					$word = 'o Wikiviru';
				} elseif ( $word == 'Wikislovar' ) {
					$word = 'o Wikislovarju';
				} else {
					$word = 'o ' . $word;
				}
			break;
			case 'orodnik': # instrumental
				if ( $word == 'Wikipedija' ) {
					$word = 'z Wikipedijo';
				} elseif ( $word == 'Wikiknjige' ) {
					$word = 'z Wikiknjigami';
				} elseif ( $word == 'Wikinovice' ) {
					$word = 'z Wikinovicami';
				} elseif ( $word == 'Wikinavedek' ) {
					$word = 'z Wikinavedkom';
				} elseif ( $word == 'Wikivir' ) {
					$word = 'z Wikivirom';
				} elseif ( $word == 'Wikislovar' ) {
					$word = 'z Wikislovarjem';
				} else {
					$word = 'z ' . $word;
				}
			break;
		}

		return $word; # this will return the original value for 'imenovalnik' (nominativ) and all undefined case values
	}

	function convertPlural( $count, $w1, $w2, $w3, $w4, $w5) {
		$count = str_replace ('.', '', $count);
		$forms = array( $w1, $w2, $w3, $w4, $w5 );
		if ( $count % 100 == 1 ) {
			$index = 0;
		} elseif ( $count % 100 == 2 ) {
			$index = 1;
		} elseif ( $count % 100 == 3 || $count % 100 == 4 ) {
			$index = 2;
		} elseif ( $count != 0 ) {
			$index = 3;
		} else {
			$index = 4;
		}
		return $forms[$index];
	}


}
?>