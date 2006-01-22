<?php
/** Bengali (বাংলা)
  *
  * @package MediaWiki
  * @subpackage Language
  */

/** This is an UTF8 language */
require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesBn = array(
	NS_SPECIAL  => 'বিশেষ',
	NS_MAIN => '',
	NS_TALK => 'আলাপ',
	NS_USER => 'ব্যবহারকারী',
	NS_USER_TALK => 'ব্যবহারকারী_আলাপ',
	NS_PROJECT => $wgMetaNamespace,
	NS_PROJECT_TALK => $wgMetaNamespace . '_আলাপ',
	NS_IMAGE => 'চিত্র',
	NS_IMAGE_TALK => 'চিত্র_আলাপ',
	NS_MEDIAWIKI_TALK => 'MediaWik i_আলাপ',
) + $wgNamespaceNamesEn;

/* private */ $wgDateFormatsBn = array();

if (!$wgCachedMessageArrays) {
	require_once('MessagesBn.php');
}

class LanguageBn extends LanguageUtf8 {
	function getNamespaces() {
		global $wgNamespaceNamesBn;
		return $wgNamespaceNamesBn;
	}

	function getMessage( $key ) {
		global $wgAllMessagesBn;
		if(array_key_exists($key, $wgAllMessagesBn)) {
			return $wgAllMessagesBn[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getDateFormats() {
		global $wgDateFormatsBn;
		return $wgDateFormatsBn;
	}

	var $digitTransTable = array(
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
