<?php

/** Latvian (Latviešu)
 *
 * @ingroup Language
 *
 * @author Niklas Laxström
 *
 * @copyright Copyright © 2006, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
class LanguageLv extends Language {
	/**
	 * Plural form transformations. Using the first form for words with the last digit 1, but not for words with the last digits 11, and the second form for all the others.
	 *
	 * Example: {{plural:{{NUMBEROFARTICLES}}|article|articles}}
	 *
	 * @param $count Integer
	 * @param $forms Array
	 * @return String
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }

		// FIXME: CLDR defines 3 plural forms instead of 2.  Form for 0 is missing.
		//        http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#lv
		$forms = $this->preConvertPlural( $forms, 2 );

		return ( ( $count % 10 == 1 ) && ( $count % 100 != 11 ) ) ? $forms[0] : $forms[1];
	}
}
