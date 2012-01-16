<?php

/** Lithuanian (Lietuvių)
 *
 * @ingroup Language
 */
class LanguageLt extends Language {
	/* Word forms (with examples):
		1 - vienas (1) lapas, dvidešimt vienas (21) lapas
		2 - trys (3) lapai
		3 - penkiolika (15) lapų
	*/

	/**
	 * Lithuanian plural forms as per http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#lt
	 * @param $count int
	 * @param $forms array
	 *
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }

		// if the number is not mentioned in message, then use $form[0] for singular and $form[1] for plural or zero
		if ( count( $forms ) === 2 ) return $count == 1 ? $forms[0] : $forms[1];

		$forms = $this->preConvertPlural( $forms, 3 );
		// Form[0] if n mod 10 is 1 and n mod 100 not in 11..19;
		if ( $count % 10 == 1 && $count % 100 != 11 ) return $forms[0];
		// Forms[1] if n mod 10 in 2..9 and n mod 100 not in 11..19;
		if ( $count % 10 >= 2 && ( $count % 100 < 10 || $count % 100 >= 20 ) ) return $forms[1];
		return $forms[2];
	}
}
