<?php
/** Upper Sorbian (Hornjoserbsce)
 *
 * @ingroup Language
 */

class LanguageHsb extends Language {

	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}

	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['hsb'][$case][$word] ) ) {
			return $wgGrammarForms['hsb'][$case][$word];
		}

		switch ( $case ) {
			case 'instrumental': # instrumental
				$word = 'z ' . $word;
				break;
			case 'lokatiw': # lokatiw
				$word = 'wo ' . $word;
				break;
			}

		return $word; # this will return the original value for 'nominatiw' (nominativ) and all undefined case values
	}

	function convertPlural( $count, $forms ) {
		if ( !count($forms) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 4 );

		switch ( abs( $count ) % 100 ) {
			case 1:  return $forms[0]; // singular
			case 2:  return $forms[1]; // dual
			case 3:
			case 4:  return $forms[2]; // plural
			default: return $forms[3]; // pluralgen
		}
	}
}
