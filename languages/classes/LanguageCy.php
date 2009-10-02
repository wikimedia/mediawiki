<?php
/** Welsh (Cymraeg)
 *
 * @ingroup Language
 *
 * @author Niklas Laxström
 */
class LanguageCy extends Language {
	function convertPlural( $count, $forms ) {
		if ( !count($forms) ) { return ''; }

		// FIXME: CLDR defines 4 plural forms; very different, actually.
		// See http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#cy
		$forms = $this->preConvertPlural( $forms, 6 );
		$count = abs( $count );
		if ( $count >= 0 && $count <= 3 ) {
			return $forms[$count];
		} elseif ( $count == 6 ) {
			return $forms[4];
		} else {
			return $forms[5];
		}
	}
}
