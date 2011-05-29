<?php

/** Karakalpak (Qaraqalpaqsha)
 *
 * @ingroup Language
 */
class LanguageKaa extends Language {

	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	/**
	 * Cases: genitive, dative, accusative, locative, ablative, comitative + possessive forms
	 *
	 * @param $word string
	 * @param $case string
	 *
	 * @return string
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['kaa'][$case][$word] ) ) {
		     return $wgGrammarForms['kaa'][$case][$word];
		}
		/* Full code of function convertGrammar() is in development. Updates coming soon. */
		return $word;
	}

	/**
	 * It fixes issue with ucfirst for transforming 'i' to 'İ'
	 *
	 * @param $string string
	 *
	 * @return string
	 */
	function ucfirst ( $string ) {
		if ( substr( $string, 0, 1 ) === 'i' ) {
			return 'İ' . substr( $string, 1 );
		} else {
			return parent::ucfirst( $string );
		}
	}

	/**
	 * It fixes issue with  lcfirst for transforming 'I' to 'ı'
	 *
	 * @param $string string
	 *
	 * @return string
	 */
	function lcfirst ( $string ) {
		if ( substr( $string, 0, 1 ) === 'I' ) {
			return 'ı' . substr( $string, 1 );
		} else {
			return parent::lcfirst( $string );
		}
	}

	/**
	 * Avoid grouping whole numbers between 0 to 9999
	 *
	 * @param $_ string
	 *
	 * @return string
	 */
	function commafy( $_ ) {
		if ( !preg_match( '/^\d{1,4}$/', $_ ) ) {
			return strrev( (string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,', strrev( $_ ) ) );
		} else {
			return $_;
		}
	}

}
