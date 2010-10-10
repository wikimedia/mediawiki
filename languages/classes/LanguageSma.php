<?php
/**
 * Southern Sami (Åarjelsaemien)
 *
 * @ingroup Language
 */
class LanguageSma extends Language {
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }

		// plural forms per http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#sma
		$forms = $this->preConvertPlural( $forms, 3 );

		if ( $count == 1 ) {
			$index = 1;
		} elseif ( $count == 2 ) {
			$index = 2;
		} else {
			$index = 3;
		}
		return $forms[$index];
	}
}
