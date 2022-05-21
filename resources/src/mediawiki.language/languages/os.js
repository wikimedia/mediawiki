/*!
 * Ossetian (Ирон) language functions
 * @author Santhosh Thottingal
 */

mw.language.convertGrammar = function ( word, form ) {
	var grammarForms = mw.language.getData( 'os', 'grammarForms' ),
		// Ending for allative case
		endAllative = 'мæ',
		// Variable for 'j' beetwen vowels
		jot = '',
		// Variable for "-" for not Ossetic words
		hyphen = '',
		// Variable for ending
		ending = '';

	if ( grammarForms && grammarForms[ form ] ) {
		return grammarForms[ form ][ word ];
	}
	// Checking if the $word is in plural form
	if ( /тæ$/i.test( word ) ) {
		word = word.slice( 0, -1 );
		endAllative = 'æм';
	} else if ( /[аæеёиоыэюя]$/i.test( word ) ) {
		// Works if word is in singular form.
		// Checking if word ends on one of the vowels: е, ё, и, о, ы, э, ю, я.
		jot = 'й';
	} else if ( /у$/i.test( word ) ) {
		// Checking if word ends on 'у'. 'У' can be either consonant 'W' or vowel 'U' in Cyrillic Ossetic.
		// Examples: {{grammar:genitive|аунеу}} = аунеуы, {{grammar:genitive|лæппу}} = лæппуйы.

		if ( !word.slice( -2, -1 ).match( /[аæеёиоыэюя]$/i ) ) {
			jot = 'й';
		}
	} else if ( !/[бвгджзйклмнопрстфхцчшщьъ]$/i.test( word ) ) {
		hyphen = '-';
	}

	switch ( form ) {
		case 'genitive':
			ending = hyphen + jot + 'ы';
			break;
		case 'dative':
			ending = hyphen + jot + 'æн';
			break;
		case 'allative':
			ending = hyphen + endAllative;
			break;
		case 'ablative':
			if ( jot === 'й' ) {
				ending = hyphen + jot + 'æ';
			} else {
				ending = hyphen + jot + 'æй';
			}
			break;
		case 'superessive':
			ending = hyphen + jot + 'ыл';
			break;
		case 'equative':
			ending = hyphen + jot + 'ау';
			break;
		case 'comitative':
			ending = hyphen + 'имæ';
			break;
	}
	return word + ending;
};
