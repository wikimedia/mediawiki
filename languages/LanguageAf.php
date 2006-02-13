<?php
/** Afrikaans (Afrikaans)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesAf = array(
	NS_MEDIA		=> "Media",
	NS_SPECIAL		=> "Spesiaal",
	NS_MAIN			=> "",
	NS_TALK			=> "Bespreking",
	NS_USER			=> "Gebruiker",
	NS_USER_TALK		=> "Gebruikerbespreking",
	NS_PROJECT		=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> $wgMetaNamespace."bespreking",
	NS_IMAGE		=> "Beeld",
	NS_IMAGE_TALK		=> "Beeldbespreking",
	NS_MEDIAWIKI		=> "MediaWiki",
	NS_MEDIAWIKI_TALK	=> "MediaWikibespreking",
	NS_TEMPLATE		=> 'Sjabloon',
	NS_TEMPLATE_TALK	=> 'Sjabloonbespreking',
	NS_HELP			=> 'Hulp',
	NS_HELP_TALK		=> 'Hulpbespreking',
	NS_CATEGORY		=> 'Kategorie',
	NS_CATEGORY_TALK	=> 'Kategoriebespreking'
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsAf = array(
	"Geen.", "Links vas.", "Regs vas.", "Dryf links."
);

/* private */ $wgSkinNamesAf = array(
	'standard' => "Standaard",
	'nostalgia' => "Nostalgie",
	'cologneblue' => "Keulen blou",
) + $wgSkinNamesEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesAf.php');
}


class LanguageAf extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesAf;
		return $wgNamespaceNamesAf;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsAf;
		return $wgQuickbarSettingsAf;
	}

	function getSkinNames() {
		global $wgSkinNamesAf;
		return $wgSkinNamesAf;
	}

	function getMessage( $key ) {
		global $wgAllMessagesAf;
		if( isset( $wgAllMessagesAf[$key] ) ) {
			return $wgAllMessagesAf[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function formatNum( $number, $year = false ) {
		# South Africa uses space for thousands and comma for decimal
		# Reference: AWS Reël 7.4 p. 52, 2002 edition
		# glibc is wrong in this respect in some versions
		if ( $year ) {
			return $number;
		} else {
			return strtr($this->commafy($number), array(
				'.' => ',',
				',' => "\xc2\xa0" // non-breaking space
			));
		}
	}

}

?>
