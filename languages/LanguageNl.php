<?php
/** Dutch (Nederlands)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesNl.php');
}

class LanguageNl extends LanguageUtf8 {
	private $mMessagesNl, $mNamespaceNamesNl = null;

	private $mQuickbarSettingsNl = array(
		'Uitgeschakeld', 'Links vast', 'Rechts vast', 'Links zwevend'
	);
	
	private $mSkinNamesNl = array(
		'standard' => 'Standaard',
		'nostalgia' => 'Nostalgie',
		'cologneblue' => 'Keuls blauw',
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesNl;
		$this->mMessagesNl =& $wgAllMessagesNl;

		global $wgMetaNamespace;
		$this->mNamespaceNamesNl = array(
			NS_MEDIA          => 'Media',
			NS_SPECIAL        => 'Speciaal',
			NS_MAIN           => '',
			NS_TALK           => 'Overleg',
			NS_USER           => 'Gebruiker',
			NS_USER_TALK      => 'Overleg_gebruiker',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => 'Overleg_' . $wgMetaNamespace,
			NS_IMAGE          => 'Afbeelding',
			NS_IMAGE_TALK     => 'Overleg_afbeelding',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'Overleg_MediaWiki',
			NS_TEMPLATE       => 'Sjabloon',
			NS_TEMPLATE_TALK  => 'Overleg_sjabloon',
			NS_HELP           => 'Help',
			NS_HELP_TALK      => 'Overleg_help',
			NS_CATEGORY       => 'Categorie',
			NS_CATEGORY_TALK  => 'Overleg_categorie'
		);
	}

	function getNamespaces() {
		return $this->mNamespaceNamesNl + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsNl;
	}

	function getSkinNames() {
		return $this->mSkinNamesNl + parent::getSkinNames();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesNl[$key] ) ) {
			return $this->mMessagesNl[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesNl;
	}

	function timeBeforeDate( ) {
		return false;
	}

	function timeDateSeparator( $format ) {
		return ' ';
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

	function linkTrail() {
		return '/^([a-zäöüïëéèà]+)(.*)$/sDu';
	}

}
?>
