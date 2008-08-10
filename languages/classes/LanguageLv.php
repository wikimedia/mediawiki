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
	 * @param integer $count
	 * @param string $wordform1
	 * @param string $wordform2
	 * @param string $wordform3 (not used)
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		if ( !count($forms) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 2 );

		return ( ( $count % 10 == 1 ) && ( $count % 100 != 11 ) ) ? $forms[0] : $forms[1];
	}
}
