<?php
/** Arabic (العربية)
 *
 * @addtogroup Language
 *
 * @author Niklas Laxström
 */
class LanguageAr extends Language {

	function convertPlural( $count, $w1, $w2, $w3, $w4, $w5) {
		$forms = array($w1, $w2, $w3, $w4, $w5);
		if ( $count == 1 ) {
			$index = 0;
		} elseif( $count == 2 ) {
			$index = 1;
		} elseif( $count < 11 && $count > 2 ) {
			$index = 2;
		} elseif( $count % 100 == 0) {
			$index = 3;
		} else {
			$index = 4;
		}
		return $forms[$index];
	}

}

?>