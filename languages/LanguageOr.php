<?php
/** Oriya (ଓଡ଼ିଆ)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  */

require_once( 'LanguageUtf8.php' );

class LanguageOr extends LanguageUtf8 {
	var $digitTransTable = array(
		'0' => '୦',
		'1' => '୧',
		'2' => '୨',
		'3' => '୩',
		'4' => '୪',
		'5' => '୫',
		'6' => '୬',
		'7' => '୭',
		'8' => '୮',
		'9' => '୯',
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
