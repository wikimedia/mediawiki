<?php

/** Manx (Gaelg)
 *
 * @ingroup Language
 *
 * @author Niklas Laxström
 */
class LanguageGv extends Language {

	function convertPlural( $count, $forms ) {
		if ( !count($forms) ) { return ''; }

		$forms = $this->preConvertPlural( $forms, 3 );

		if ($count > 0 && ($count % 20) === 0 ) {
			return $forms[0];
		} else {
			switch ($count % 10) {
				case 1:
				case 2:  return $forms[1];
				default: return $forms[2];
			}
		}
	}

}
