<?php

/** Estonian (Eesti)
 *
 * @ingroup Language
 *
 */
class LanguageEt extends Language {
	/**
	 * Avoid grouping whole numbers between 0 to 9999
	 */
	function commafy($_) {
		if (!preg_match('/^\d{1,4}$/',$_)) {
			return strrev((string)preg_replace('/(\d{3})(?=\d)(?!\d*\.)/','$1,',strrev($_)));
		} else {
			return $_;
		}
	}

	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['et'][$case][$word] ) ) {
			return $wgGrammarForms['et'][$case][$word];
		}
	}
}
