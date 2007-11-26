<?php
/** Karakalpak (Qaraqalpaqsha)
 *
 *
 * @addtogroup Language
 */

class LanguageKaa extends Language {

	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	/**
	 * Cases: genitive, dative, accusative, locative, ablative, comitative + possessive forms
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['kaa'][$case][$word] ) ) {
		     return $wgGrammarForms['kaa'][$case][$word];
		}
		/* Full code of function convertGrammar() is in development. Updates coming soon. */
		return $word;
	}

	function ucfirst ( $string ) {
		if ( $string[0] == 'i' ) {
			return 'İ' . substr( $string, 1 );
		} else {
			return parent::ucfirst( $string );
		}
	}

	/**
	 * Avoid grouping whole numbers between 0 to 9999
	 */
	function commafy( $_ ) {
		if ( !preg_match( '/^\d{1,4}$/', $_ ) ) {
			return strrev( (string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,', strrev($_) ) );
		} else {
			return $_;
		}
	}

}