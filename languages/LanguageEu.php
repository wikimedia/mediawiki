<?php
/** Basque (Euskara)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesEu.php');
}

class LanguageEu extends LanguageUtf8 {
	private $mMessagesEu, $mNamespaceNamesEu = null;

	private $mQuickbarSettingsEu = array(
		'Ezein ere', 'Eskuinean', 'Ezkerrean', 'Ezkerrean mugikor'
	);

	private $mSkinNamesEu = array(
		'standard'     => 'Lehenetsia',
		'nostalgia'    => 'Nostalgia',
		'cologneblue'  => 'Cologne Blue',
		'smarty'       => 'Paddington',
		'montparnasse' => 'Montparnasse'
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesEu;
		$this->mMessagesEu =& $wgAllMessagesEu;

		global $wgMetaNamespace;
		$this->mNamespaceNamesEu = array(
			NS_MEDIA          => 'Media',
			NS_SPECIAL        => 'Aparteko',
			NS_MAIN           => '',
			NS_TALK           => 'Eztabaida',
			NS_USER           => 'Lankide',
			NS_USER_TALK      => 'Lankide_eztabaida',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => $wgMetaNamespace.'_eztabaida',
			NS_IMAGE          => 'Irudi',
			NS_IMAGE_TALK     => 'Irudi_eztabaida',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'MediaWiki_eztabaida',
			NS_TEMPLATE       => 'Txantiloi',
			NS_TEMPLATE_TALK  => 'Txantiloi_eztabaida',
			NS_CATEGORY       => 'Kategoria',
			NS_CATEGORY_TALK  => 'Kategoria_eztabaida',
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesEu + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsEu;
	}

	function getSkinNames() {
		return $this->mSkinNamesEu + parent::getSkinNames();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesEu[$key] ) ) {
			return $this->mMessagesEu[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesEu;
	}

}

?>
