<?php
/** Greek (Ελληνικά)
  *
  * Translation by Pasok Internet Volunteers
  * http://forum.pasok.gr
  * version 1.0 (initial release)
  *
  *The project for the translation of MediaWiki into Greek
  *was undertaken by a group of ICT volunteers working under
  *the auspices of the Greek political party PASOK.
  *
  *The idea behind this effort was  to provide an extensible,
  *easy-to-use and non-intimidating tool for content development
  *and project management, to be used throughout the administrative
  *and political structure of PASOK by staff, volunteers, party members
  *and elected officials (all of whom possess varying degrees of ICT skills).
  *
  *The PASOK ICT team and the volunteers who worked on this project are
  *now returning the translated interface to the Open-Source Community
  *with over 98% of the messages translated into user-friendly Greek.
  *
  *We hope that it will be used as a tool by other civil society organizations
  *in Greece, and that it will enhance the collective creation and the dissemination
  *of knowledge - an essential component of the democratic process.
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesEl.php');
}

class LanguageEl extends LanguageUtf8 {
	private $mMessagesEl, $mNamespaceNamesEl = null;
	
	function __construct() {
		parent::__construct();

		global $wgAllMessagesEl;
		$this->mMessagesEl =& $wgAllMessagesEl;

		global $wgMetaNamespace;
		$this->mNamespaceNamesEl = array(
			NS_MEDIA            => 'Μέσον',
			NS_SPECIAL          => 'Ειδικό',
			NS_MAIN	            => '',
			NS_TALK	            => 'Συζήτηση',
			NS_USER             => 'Χρήστης',
			NS_USER_TALK        => 'Συζήτηση_χρήστη',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => $wgMetaNamespace . '_συζήτηση',
			NS_IMAGE            => 'Εικόνα',
			NS_IMAGE_TALK       => 'Συζήτηση_εικόνας',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
			NS_TEMPLATE         => 'Πρότυπο',
			NS_TEMPLATE_TALK    => 'Συζήτηση_προτύπου',
			NS_HELP             => 'Βοήθεια',
			NS_HELP_TALK        => 'Συζήτηση_βοήθειας',
			NS_CATEGORY         => 'Κατηγορία',
			NS_CATEGORY_TALK    => 'Συζήτηση_κατηγορίας',
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesEl + parent::getNamespaces();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesEl[$key] ) ) {
			return $this->mMessagesEl[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesEl;
	}

	function fallback8bitEncoding() {
		return 'iso-8859-7';
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

}

?>
