<?php
/**
 * Macedonian (Македонски)
 *
 * @ingroup Language
 */
class LanguageMk extends Language {
	/**
	 * Plural forms per
	 * http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#mk
	 *
	 * @param $count int
	 * @param $forms array
	 *
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 2 );
		// TODO CLDR defines forms[0] for n != 11 and not for n%100 !== 11
		if ( $count % 10 === 1 && $count % 100 !== 11 ) {
			return $forms[0];
		} else {
			return $forms[1];
		}
	}
}
