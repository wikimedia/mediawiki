<?php
/** Khmer (ភាសាខ្មែរ)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  */

require_once( 'LanguageUtf8.php' );

class LanguageKm extends LanguageUtf8 {
	var $digitTransTable = array(
		'0' => '០',
		'1' => '១',
		'2' => '២',
		'3' => '៣',
		'4' => '៤',
		'5' => '៥',
		'6' => '៦',
		'7' => '៧',
		'8' => '៨',
		'9' => '៩'
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
