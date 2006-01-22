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
	var $digitTransTable = array(
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

	function getMessage( $key ) {
		global $wgAllMessagesKn;
		if( array_key_exists( $key, $wgAllMessagesKn ) )
			return $wgAllMessagesKn[$key];
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
