<?php

/** Slovenian (Slovenščina)
 *
 * @ingroup Language
 */
class LanguageSl extends Language {
	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	/**
	 * Cases: rodilnik, dajalnik, tožilnik, mestnik, orodnik
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['sl'][$case][$word] ) ) {
			return $wgGrammarForms['sl'][$case][$word];
		}

		switch ( $case ) {
			case 'mestnik': # locative
				$word = 'o ' . $word; break;
			case 'orodnik': # instrumental
				$word = 'z ' . $word;
		}

		return $word; # this will return the original value for 'imenovalnik' (nominativ) and all undefined case values
	}

	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 5 );

		if ( $count % 100 == 1 ) {
			$index = 0;
		} elseif ( $count % 100 == 2 ) {
			$index = 1;
		} elseif ( $count % 100 == 3 || $count % 100 == 4 ) {
			$index = 2;
		} elseif ( $count != 0 ) {
			$index = 3;
		} else {
			$index = 4;
		}
		return $forms[$index];
	}
}
