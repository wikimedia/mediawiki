<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesHi = array(
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
	NS_TEMPLATE_TALK  => "Template_talk",
	NS_CATEGORY       => 'श्रेणी',
	NS_CATEGORY_TALK  => 'श्रेणी_वार्ता',
) + $wgNamespaceNamesEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesHi.php');
}


class LanguageHi extends LanguageUtf8 {
	var $digitTransTable = array(
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

	function getNamespaces() {
		global $wgNamespaceNamesHi;
		return $wgNamespaceNamesHi;
	}

	function getMessage( $key ) {
		global $wgAllMessagesHi;
		if(array_key_exists($key, $wgAllMessagesHi))
			return $wgAllMessagesHi[$key];
		else
			return parent::getMessage($key);
	}

	function formatNum( $number ) {
		global $wgTranslateNumerals;
		if( $wgTranslateNumerals ) {
			return strtr( $number, $this->digitTransTable );
		} else {
			return $number;
		}
	}

}

?>
