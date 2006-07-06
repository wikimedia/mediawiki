<?php
/**
 * Language file for Kannada.
 * Mosty done by:
 *   Hari Prasad Nadig <hpnadig@gmail.com>
 *     http://en.wikipedia.org/wiki/User:Hpnadig
 *   Ashwath Mattur <ashwatham@gmail.com>
 *     http://en.wikipedia.org/wiki/User:Ashwatham
 *
 * Also see the Kannada Localisation Initiative at:
 *      http://kannada.sourceforge.net/
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesKn.php');
}

class LanguageKn extends LanguageUtf8 {
	private $mMessagesKn, $mNamespaceNamesKn = null;

	function __construct() {
		parent::__construct();

		global $wgAllMessagesKn;
		$this->mMessagesKn =& $wgAllMessagesKn;

		global $wgMetaNamespace;
		$this->mNamespaceNamesKn = array(
			NS_MEDIA            => 'ಮೀಡಿಯ',
			NS_SPECIAL          => 'ವಿಶೇಷ',
			NS_MAIN             => '',
			NS_TALK             => 'ಚರ್ಚೆಪುಟ',
			NS_USER             => 'ಸದಸ್ಯ',
			NS_USER_TALK        => 'ಸದಸ್ಯರ_ಚರ್ಚೆಪುಟ',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => $wgMetaNamespace . '_ಚರ್ಚೆ',
			NS_IMAGE            => 'ಚಿತ್ರ',
			NS_IMAGE_TALK       => 'ಚಿತ್ರ_ಚರ್ಚೆಪುಟ',
			NS_MEDIAWIKI        => 'ಮೀಡಿಯವಿಕಿ',
			NS_MEDIAWIKI_TALK   => 'ಮೀಡೀಯವಿಕಿ_ಚರ್ಚೆ',
			NS_TEMPLATE         => 'ಟೆಂಪ್ಲೇಟು',
			NS_TEMPLATE_TALK    => 'ಟೆಂಪ್ಲೇಟು_ಚರ್ಚೆ',
			NS_HELP             => 'ಸಹಾಯ',
			NS_HELP_TALK        => 'ಸಹಾಯ_ಚರ್ಚೆ',
			NS_CATEGORY         => 'ವರ್ಗ',
			NS_CATEGORY_TALK    => 'ವರ್ಗ_ಚರ್ಚೆ'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesKn + parent::getNamespaces();
	}


	function digitTransformTable() {
		return array(
			'0' => '೦',
			'1' => '೧',
			'2' => '೨',
			'3' => '೩',
			'4' => '೪',
			'5' => '೫',
			'6' => '೬',
			'7' => '೭',
			'8' => '೮',
			'9' => '೯'
		);
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesKn[$key] ) ) {
			return $this->mMessagesKn[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesKn;
	}

}

?>
