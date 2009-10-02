<?php
/**
 *
 * @ingroup Language
 */
class LanguageMk extends Language {
	/**
	 * Plural forms per
	 * http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#mk
	 */
	function convertPlural( $count, $forms ) {
		if ( !count($forms) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 2 );

		if ($count > 10 && floor( ($count % 100 ) / 10 ) == 1 ) {
			return $forms[1];
		} else {
			return $forms[0];
		}
	}
}
