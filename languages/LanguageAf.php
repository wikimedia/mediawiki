<?php
/** Afrikaans (Afrikaans)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesAf.php');
}

class LanguageAf extends LanguageUtf8 {
	private $mMessagesAf, $mNamespaceNamesAf = null;

	private $mQuickbarSettingsAf = array(
		"Geen.", "Links vas.", "Regs vas.", "Dryf links."
	);
	
	private $mSkinNamesAf = array(
		'standard' => "Standaard",
		'nostalgia' => "Nostalgie",
		'cologneblue' => "Keulen blou",
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesAf;
		$this->mMessagesAf =& $wgAllMessagesAf;

		global $wgMetaNamespace;
		$this->mNamespaceNamesAf = array(
			NS_MEDIA          => "Media",
			NS_SPECIAL        => "Spesiaal",
			NS_MAIN           => "",
			NS_TALK           => "Bespreking",
			NS_USER           => "Gebruiker",
			NS_USER_TALK      => "Gebruikerbespreking",
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => $wgMetaNamespace."bespreking",
			NS_IMAGE          => "Beeld",
			NS_IMAGE_TALK     => "Beeldbespreking",
			NS_MEDIAWIKI      => "MediaWiki",
			NS_MEDIAWIKI_TALK => "MediaWikibespreking",
			NS_TEMPLATE       => 'Sjabloon',
			NS_TEMPLATE_TALK  => 'Sjabloonbespreking',
			NS_HELP           => 'Hulp',
			NS_HELP_TALK      => 'Hulpbespreking',
			NS_CATEGORY       => 'Kategorie',
			NS_CATEGORY_TALK  => 'Kategoriebespreking'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesAf + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsAf;
	}

	function getSkinNames() {
		return $this->mSkinNamesAf + parent::getSkinNames();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesAf[$key] ) ) {
			return $this->mMessagesAf[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesAf;
	}

	# South Africa uses space for thousands and comma for decimal
	# Reference: AWS Reël 7.4 p. 52, 2002 edition
	# glibc is wrong in this respect in some versions
	function separatorTransformTable() {
		return array(',' => "\xc2\xa0", '.' => ',' );
	}

}

?>
