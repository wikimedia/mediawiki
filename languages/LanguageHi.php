<?php
/** Hindi (हिन्दी)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 * @author Niklas Laxström
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesHi.php');
}

class LanguageHi extends LanguageUtf8 {
	private $mMessagesHi, $mNamespaceNamesHi = null;

	function __construct() {
		parent::__construct();

		global $wgAllMessagesHi;
		$this->mMessagesHi =& $wgAllMessagesHi;

		global $wgMetaNamespace;
		$this->mNamespaceNamesHi = array(
			NS_MEDIA          => 'Media',
			NS_SPECIAL        => 'विशेष',
			NS_MAIN           => '',
			NS_TALK           => 'वार्ता',
			NS_USER           => 'सदस्य',
			NS_USER_TALK      => 'सदस्य_वार्ता',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => $wgMetaNamespace . '_वार्ता',
			NS_IMAGE          => 'चित्र',
			NS_IMAGE_TALK     => 'चित्र_वार्ता',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'MediaWiki_talk',
			NS_TEMPLATE       => 'Template',
			NS_TEMPLATE_TALK  => 'Template_talk',
			NS_CATEGORY       => 'श्रेणी',
			NS_CATEGORY_TALK  => 'श्रेणी_वार्ता',
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesHi + parent::getNamespaces();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesHi[$key] ) ) {
			return $this->mMessagesHi[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesHi;
	}

	function digitTransformTable() {
		return array(
			"0" => "०",
			"1" => "१",
			"2" => "२",
			"3" => "३",
			"4" => "४",
			"5" => "५",
			"6" => "६",
			"7" => "७",
			"8" => "८",
			"9" => "९"
		);
	}

}

?>
