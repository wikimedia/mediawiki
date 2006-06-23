<?php
/** Bengali (বাংলা)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesBn.php');
}

class LanguageBn extends LanguageUtf8 {
	private $mMessagesBn, $mNamespaceNamesBn = null;

	function __construct() {
		parent::__construct();

		global $wgAllMessagesBn;
		$this->mMessagesBn =& $wgAllMessagesBn;

		global $wgMetaNamespace;
		$this->mNamespaceNamesBn = array(
			NS_SPECIAL        => 'বিশেষ',
			NS_MAIN           => '',
			NS_TALK           => 'আলাপ',
			NS_USER           => 'ব্যবহারকারী',
			NS_USER_TALK      => 'ব্যবহারকারী_আলাপ',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => $wgMetaNamespace . '_আলাপ',
			NS_IMAGE          => 'চিত্র',
			NS_IMAGE_TALK     => 'চিত্র_আলাপ',
			NS_MEDIAWIKI_TALK => 'MediaWiki_আলাপ'
		);
	}

	function getNamespaces() {
		return $this->mNamespaceNamesBn + parent::getNamespaces();
	}

	function getDateFormats() {
		return false;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesBn[$key] ) ) {
			return $this->mMessagesBn[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesBn;
	}

	function digitTransformTable() {
		return array(
			'0' => '০',
			'1' => '১',
			'2' => '২',
			'3' => '৩',
			'4' => '৪',
			'5' => '৫',
			'6' => '৬',
			'7' => '৭',
			'8' => '৮',
			'9' => '৯'
		);
	}

}

?>
