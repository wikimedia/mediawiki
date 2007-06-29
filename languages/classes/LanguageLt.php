<?php
/** Lithuanian (Lietuvių)
 *
 * @addtogroup Language
 *
 */

class LanguageLt extends Language {
	/* Word forms (with examples):
		1 - vienas (1) lapas, dvidešimt vienas (21) lapas
		2 - trys (3) lapai
		3 - penkiolika (15) lapų
	*/
	function convertPlural( $count, $wordform1, $wordform2, $wordform3, $w4, $w5) {
		if ($count%10==1 && $count%100!=11) return $wordform1;
		if ($count%10>=2 && ($count%100<10 || $count%100>=20)) return $wordform2;
		//if third form not specified, then use second form
		return empty($wordform3)?$wordform2:$wordform3;
	}
}

