<?php
/** Tibetan
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

class LanguageBo extends LanguageUtf8 {
	var $digitTransTable = array(
		'0' => '༠',
		'1' => '༡',
		'2' => '༢',
		'3' => '༣',
		'4' => '༤',
		'5' => '༥',
		'6' => '༦',
		'7' => '༧',
		'8' => '༨',
		'9' => '༩'
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
