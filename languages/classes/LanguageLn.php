<?php
/**
 * Lingala (Lingála)
 *
 * @ingroup Language
 */
class LanguageLn extends Language {
	/**
	 * Use singular form for zero
	 * http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#ln

	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 2 );

		return ( $count <= 1 ) ? $forms[0] : $forms[1];
	}
}
