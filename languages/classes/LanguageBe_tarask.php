<?php
/** Belarusian in Taraškievica orthography (Беларуская тарашкевіца)
  *
  * @ingroup Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  * @see http://be-x-old.wikipedia.org/wiki/Project_talk:LanguageBe_tarask.php
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
  * @license http://www.gnu.org/copyleft/fdl.html GNU Free Documentation License
  */

class LanguageBe_tarask extends Language {
	/**
	 * Plural form transformations
	 *
	 * $wordform1 - singular form (for 1, 21, 31, 41...)
	 * $wordform2 - plural form (for 2, 3, 4, 22, 23, 24, 32, 33, 34...)
	 * $wordform3 - plural form (for 0, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 25, 26...)
	 */

	/**
	 * @param $count int
	 * @param $forms array
	 *
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }

		// If the actual number is not mentioned in the expression, then just two forms are enough:
		// singular for $count == 1
		// plural   for $count != 1
		// For example, "This user belongs to {{PLURAL:$1|one group|several groups}}."
		if ( count( $forms ) === 2 ) return $count == 1 ? $forms[0] : $forms[1];

		// @todo FIXME: CLDR defines 4 plural forms instead of 3
		//        http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html
		$forms = $this->preConvertPlural( $forms, 3 );

		if ( $count > 10 && floor( ( $count % 100 ) / 10 ) == 1 ) {
			return $forms[2];
		} else {
			switch ( $count % 10 ) {
				case 1:  return $forms[0];
				case 2:
				case 3:
				case 4:  return $forms[1];
				default: return $forms[2];
			}
		}
	}

	/**
	 * The Belarusian language uses apostrophe sign,
	 * but the characters used for this could be both U+0027 and U+2019.
	 * This function unifies apostrophe sign in search index values
	 * to enable seach using both apostrophe signs.
	 *
	 * @param $string string
	 *
	 * @return string
	 */
	function normalizeForSearch( $string ) {
		wfProfileIn( __METHOD__ );

		# MySQL fulltext index doesn't grok utf-8, so we
		# need to fold cases and convert to hex

		# Replacing apostrophe sign U+2019 with U+0027
		$s = preg_replace( '/\xe2\x80\x99/', '\'', $string );

		$s = parent::normalizeForSearch( $s );

		wfProfileOut( __METHOD__ );
		return $s;
	}

	/**
	 * Four-digit number should be without group commas (spaces)
	 * So "1 234 567", "12 345" but "1234"
	 *
	 * @param $_ string
	 *
	 * @return string
	 */
	function commafy( $_ ) {
		if ( preg_match( '/^-?\d{1,4}(\.\d*)?$/', $_ ) ) {
			return $_;
		} else {
			return strrev( (string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,', strrev( $_ ) ) );
		}
	}
}
