<?php
/** Scots Gaelic (Gàidhlig)
 *
 * @ingroup Language
 *
 * @author Raimond Spekking
 * @author Niklas Laxström
 */
class LanguageGd extends Language {

	/**
	 * Plural form transformations
	 * Based on this discussion: http://translatewiki.net/wiki/Thread:Support/New_plural_rules_for_Scots_Gaelic_(gd)
	 *
	 * $forms[0] - 1
	 * $forms[1] - 2
	 * $forms[2] - 11
	 * $forms[3] - 12
	 * $forms[4] - 3-10, 13-19
	 * $forms[5] - 0, 20, rest
	 *
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 6 );

		$count = abs( $count );
		if ( $count === 1 ) {
			return $forms[0];
		} elseif ( $count === 2 ) {
			return $forms[1];
		} elseif ( $count === 11 ) {
			return $forms[2];
		} elseif ( $count === 12 ) {
			return $forms[3];
		} elseif ( ($count >= 3 && $count <= 10) || ($count >= 13 && $count <= 19) ) {
			return $forms[4];
		} else {
			return $forms[5];
		}
	}
}
