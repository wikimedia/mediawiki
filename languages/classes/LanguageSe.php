<?php
/**
 *
 * @ingroup Language
 */
class LanguageSe extends Language {
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }

		// plural forms per http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#se
		$forms = $this->preConvertPlural( $forms, 3 );

		if ( $count == 1 ) {
			$index = 0;
		} elseif ( $count == 2 ) {
			$index = 1;
		} else {
			$index = 2;
		}
		return $forms[$index];
	}
}
