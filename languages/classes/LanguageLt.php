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
	function convertPlural( $count, $forms ) {
		if ( !count($forms) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 3 );

		if ($count%10==1 && $count%100!=11) return $forms[0];
		if ($count%10>=2 && ($count%100<10 || $count%100>=20)) return $forms[1];
		return $forms[2];
	}
}

