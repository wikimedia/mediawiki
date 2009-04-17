<?php
/** Scots Gaelic (Gàidhlig)
 *
 * @ingroup Language
 *
 * @author Raimond Spekking
 */
class LanguageGd extends Language {

	/**
	 * Plural form transformations
	 * Based on this discussion: http://translatewiki.net/w/i.php?title=Portal_talk:Gd&oldid=1094065#%C3%80ireamhan
	 *
	 * $forms[0] - singular form (for 1)
	 * $forms[1] - dual form (for 2)
	 * $forms[2] - plural form 1 (for 3-10)
	 * $forms[3] - plural form 2 (for >= 11)
	 *
	 */
	function convertPlural( $count, $forms ) {
		if ( !count($forms) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 4 );

		$count = abs( $count );
		if ( $count === 1 ) {
			return $forms[0];
		} elseif ( $count === 2 ) {
			return $forms[1];
		} elseif ( $count >= 3 && $count <= 10 ) {
			return $forms[2];
		} else {
			return $forms[3];
		}
	}
}
