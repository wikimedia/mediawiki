<?php
/**
  * @package MediaWiki
  * @subpackage Language
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

#         1.00.50 Nikerabbit    2005-08-15 |    removed old stuff, some cleanup, NOT COMPLETE!


require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesSl = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Posebno',
	NS_MAIN           => '',
	NS_TALK           => 'Pogovor',
	NS_USER           => 'Uporabnik',
	NS_USER_TALK      => 'Uporabniški_pogovor',
	NS_PROJECT        => $wgMetaNamespace,
	NS_PROJECT_TALK   => FALSE,  # Set in constructor
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
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsSl = array(
	"Brez", "Levo nepomično", "Desno nepomično", "Levo leteče"
);

/* private */ $wgDateFormatsSl = array(
#        'No preference',
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesSl.php');
}

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageSl extends LanguageUtf8 {
	function LanguageSl() {
		global $wgNamespaceNamesSl, $wgMetaNamespace;
		LanguageUtf8::LanguageUtf8();
		$wgNamespaceNamesSl[NS_PROJECT_TALK] = 'Pogovor_' .
			str_replace( ' ', '_',
				$this->convertGrammar( $wgMetaNamespace, 'orodnik' ) );
	}

	function getNamespaces() {
		global $wgNamespaceNamesSl;
		return $wgNamespaceNamesSl;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsSl;
		return $wgQuickbarSettingsSl;
	}

	function getDateFormats() {
		global $wgDateFormatsSl;
		return $wgDateFormatsSl;
	}

	function getMessage( $key ) {
		global $wgAllMessagesSl;
		if(array_key_exists($key, $wgAllMessagesSl))
			return $wgAllMessagesSl[$key];
		else
			return parent::getMessage($key);
	}

	function fallback8bitEncoding() {
		return "iso-8859-2";
	}

	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ',.' );
	}

	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	function convertGrammar( $word, $case ) {
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
			case 'orodnik': # instrumental
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
		}

		return $word; # this will return the original value for 'imenovalnik' (nominativ) and all undefined case values
	}

}
?>