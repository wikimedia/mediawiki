<?php
/** German
 *
 * @ingroup Language
 */
class LanguageDe extends Language {

	/*
	 * German numeric format is "12 345,67" but "1234,56"
	 * Copied from LanguageUk.php
	 */

	function commafy($_) {
		if (!preg_match('/^\d{1,4}$/',$_)) {
			return strrev((string)preg_replace('/(\d{3})(?=\d)(?!\d*\.)/','$1,',strrev($_)));
		} else {
			return $_;
		}
	}
}
