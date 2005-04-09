<?php
/** Malayalam (മലയാളം)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  */

require_once( 'LanguageUtf8.php' );

class LanguageMl extends LanguageUtf8 {
	var $digitTransTable = array(
		'0' => '൦',
		'1' => '൧',
		'2' => '൨',
		'3' => '൩',
		'4' => '൪',
		'5' => '൫',
		'6' => '൬',
		'7' => '൭',
		'8' => '൮',
		'9' => '൯'
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
