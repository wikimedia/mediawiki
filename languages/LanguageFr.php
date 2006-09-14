<?php
/** French (Français)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 */

class LanguageFr extends Language {
	/**
	 * Use singular form for zero (see bug 7309)
	 */
        function convertPlural( $count, $w1, $w2, $w3) {
		return $count <= '1' ? $w1 : $w2;
        }
}
?>
