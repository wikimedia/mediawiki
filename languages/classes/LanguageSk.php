<?php
/**
 * Slovak (Slovenčina)
 *
 * @ingroup Language
 */
class LanguageSk extends Language {
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 3 );

		if ( $count == 1 ) {
			$index = 0;
		} elseif ( $count == 2 || $count == 3 || $count == 4 ) {
			$index = 1;
		} else {
			$index = 2;
		}
		return $forms[$index];
	}
}
