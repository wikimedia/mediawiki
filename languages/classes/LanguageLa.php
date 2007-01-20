<?php
/** Latin (lingua Latina)
  *
  * @addtogroup Language
  */

class LanguageLa extends Language {
	/**
	 * Convert from the nominative form of a noun to some other case
	 *
	 * Just used in a couple places for sitenames; special-case as necessary.
	 * Rules are far from complete.
	 *
	 * Cases: genitive
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms['la'][$case][$word]) ) {
			return $wgGrammarForms['la'][$case][$word];
		}

		switch ( $case ) {
		case 'genitive':
			// 1st and 2nd declension singular only.
			$in  = array( '/a$/', '/u[ms]$/', '/tio$/' );
			$out = array( 'ae',   'i',        'tionis' );
			return preg_replace( $in, $out, $word );
		default:
			return $word;
		}
	}

}


?>
