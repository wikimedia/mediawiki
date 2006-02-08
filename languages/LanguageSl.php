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

/** TODO: fixme, remove wikipedia
/* private */ $wgNamespaceNamesSl = array(
	NS_MEDIA          => "Media",
	NS_SPECIAL        => "Posebno",
	NS_MAIN           => "",
	NS_TALK           => "Pogovor",
	NS_USER           => "Uporabnik",
	NS_USER_TALK      => "Uporabniški_pogovor",
	NS_PROJECT        => "Wikipedija",
	NS_PROJECT_TALK   => "Pogovor_k_Wikipediji",
	NS_IMAGE          => "Slika",
	NS_IMAGE_TALK     => "Pogovor_k_sliki",
	NS_MEDIAWIKI      => "MediaWiki",
	NS_MEDIAWIKI_TALK => "MediaWiki_talk",
	NS_TEMPLATE       => "Predloga",
	NS_TEMPLATE_TALK  => "Pogovor_k_predlogi",
	NS_CATEGORY       => "Kategorija",
	NS_CATEGORY_TALK  => "Pogovor_k_kategoriji"
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
}

?>
