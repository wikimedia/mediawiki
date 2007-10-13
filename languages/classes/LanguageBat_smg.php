<?php
/** Samogitian (Žemaitėška)
 *
 * @addtogroup Language
 *
 * @author Niklas Laxström
 */
class LanguageBat_smg extends Language {

	public function convertPlural( $count, $w1, $w2, $w3, $w4, $w5) {
		$count = abs( $count );
		if ( $count === 0 || ($count%100 === 0 || ($count%100 >= 10 && $count%100 < 20)) ) {
			return $w3;
		} elseif ( $count%10 === 1 ) {
			return $w1;
		} elseif ( $count%10 === 2 ) {
			return $w2;
		} else {
			return $w4;
		}
	}

}