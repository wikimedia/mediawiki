<?php
/** Kurdish (Kurdî / كوردي)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesKu.php');
}

class LanguageKu extends LanguageUtf8 {
	private $mMessagesKu, $mNamespaceNamesKu = null;

	function __construct() {
		parent::__construct();

		global $wgAllMessagesKu;
		$this->mMessagesKu =& $wgAllMessagesKu;

		global $wgMetaNamespace;
		$this->mNamespaceNamesKu = array(
			NS_MEDIA            => 'Medya',
			NS_SPECIAL          => 'Taybet',
			NS_MAIN             => '',
			NS_TALK             => 'Nîqaş',
			NS_USER             => 'Bikarhêner',
			NS_USER_TALK        => 'Bikarhêner_nîqaş',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => $wgMetaNamespace . '_nîqaş',
			NS_IMAGE            => 'Wêne',
			NS_IMAGE_TALK       => 'Wêne_nîqaş',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'MediaWiki_nîqaş',
			NS_TEMPLATE         => 'Şablon',
			NS_TEMPLATE_TALK    => 'Şablon_nîqaş',
			NS_HELP             => 'Alîkarî',
			NS_HELP_TALK        => 'Alîkarî_nîqaş',
			NS_CATEGORY         => 'Kategorî',
			NS_CATEGORY_TALK    => 'Kategorî_nîqaş'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesKu + parent::getNamespaces();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesKu[$key] ) ) {
			return $this->mMessagesKu[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesKu;
	}

}

?>
