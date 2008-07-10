<?php

/** Maltese (Malti)
 *
 * @ingroup Language
 *
 * @author Niklas Laxström
 */

class LanguageMt extends Language {
	function convertPlural( $count, $forms ) {
		if ( !count($forms) ) { return ''; }

		$forms = $this->preConvertPlural( $forms, 4 );

		$index = (n==1 ? 0 : n==0 or ( n%100>1 && n%100<11) ? 1 : (n%100>10 && n%100<20 ) ? 2 : 3);
		return $forms[$index];
	}
}