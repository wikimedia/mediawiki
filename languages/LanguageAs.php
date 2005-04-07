<?php
/** Assamese (অসমীয়া)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

class LanguageAs extends LanguageUtf8 {
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
