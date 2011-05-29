<?php
/** Belarusian normative (Беларуская мова)
  *
  * This is still the version from Be-x-old, only duplicated for consistency of
  * plural and grammar functions. If there are errors please send a patch.
  *
  * @ingroup Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  * @link http://be.wikipedia.org/wiki/Talk:LanguageBe.php
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
  * @license http://www.gnu.org/copyleft/fdl.html GNU Free Documentation License
  */

class LanguageBe extends Language {

	/**
	 * @param $count int
	 * @param $forms array
	 *
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
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
}
