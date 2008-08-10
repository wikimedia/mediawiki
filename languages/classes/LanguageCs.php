<?php

/** Czech (česky)
 *
 * @ingroup Language
 */
class LanguageCs extends Language {

	function convertPlural( $count, $forms ) {
		if ( !count($forms) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 3 );

		switch ( $count ) {
			case 1:
				return $forms[0];
			case 2:
			case 3:
			case 4:
				return $forms[1];
			default:
				return $forms[2];
		}
	}
}
