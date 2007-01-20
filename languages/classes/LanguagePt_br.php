<?php
/** Brazilian Portugese (Portuguêsi do Brasil)
 *
 * @addtogroup Language
 *
 */

class LanguagePt_br extends Language {
	/**
	 * Use singular form for zero (see bug 7309)
	 */
        function convertPlural( $count, $w1, $w2, $w3, $w4, $w5) {
		return $count <= '1' ? $w1 : $w2;
        }
}
?>
