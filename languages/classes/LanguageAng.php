<?php

/** Old English (Ænglisc)
 *
 * @ingroup Language
 */
class LanguageAng extends Language {
	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	/**
	 * Cases: nemniendlīc (nom), wrēgendlīc (acc), forgifendlīc (dat), geāgniendlīc (gen), tōllīc (ins)
	 *
	 * @param $word string
	 * @param $case string
	 *
	 * @return string
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['ang'][$case][$word] ) ) {
			return $wgGrammarForms['ang'][$case][$word];
		}

		return $word; # this will return the original value for all words without set grammar forms
	}

}