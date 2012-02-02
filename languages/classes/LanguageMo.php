<?php
/**
 * Moldavian (Молдовеняскэ)
 *
 * @ingroup Language
 */
class LanguageMo extends Language {

	/**
	 * @param $count int
	 * @param $forms array
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		// Plural rules per
		// http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#mo
		// Identical to Romanian (ro).
		if ( !count( $forms ) ) { return ''; }

		$forms = $this->preConvertPlural( $forms, 3 );

		if ( $count == 1 ) {
			$index = 0;
		} elseif ( $count == 0 || ( $count % 100 > 0 && $count % 100 < 20 ) ) {
			$index = 1;
		} else {
			$index = 2;
		}
		return $forms[$index];
	}
}
