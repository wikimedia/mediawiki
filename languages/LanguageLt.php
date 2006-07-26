<?php
/** Lithuanian (Lietuvių)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 */

class LanguageLt extends Language {
	/* Word forms (with examples):
		1 - vienas (1) lapas
		2 - trys (3) lapai
		3 - penkiolika (15) lapų
	*/	
	function convertPlural( $count, $wordform1, $wordform2, $wordform3) {
		$count = str_replace (' ', '', $count);
		if ($count%10==1 && $count%100!=11) return $wordform1;
		if ($count%10>=2 && ($count%100<10 || $count%100>=20)) return $wordform2;
		return $wordform3;
	}
}
?>
