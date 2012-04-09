/**
 * Ossetian (Ирон) language functions
 * @author Santhosh Thottingal
 */


mediaWiki.language.convertGrammar = function( word, form ) {
	var grammarForms = mw.language.getData( 'os', 'grammarForms' );
	if ( grammarForms && grammarForms[form] ) {
		return grammarForms[form][word] ;
	}
	// Ending for allative case
	var end_allative = 'мæ';
	// Variable for 'j' beetwen vowels
	var jot = '';
	// Variable for "-" for not Ossetic words
	var hyphen = '';
	// Variable for ending
	var ending = '';
	// Checking if the $word is in plural form
	if ( word.match( /тæ$/i ) ) {
		word = word.substring( 0, word.length - 1 );
		end_allative = 'æм';
	}
	// Works if word is in singular form.
	// Checking if word ends on one of the vowels: е, ё, и, о, ы, э, ю, я.
	else if ( word.match( /[аæеёиоыэюя]$/i ) ) {
		jot = 'й';
	}
	// Checking if word ends on 'у'. 'У' can be either consonant 'W' or vowel 'U' in cyrillic Ossetic.
	// Examples: {{grammar:genitive|аунеу}} = аунеуы, {{grammar:genitive|лæппу}} = лæппуйы.
	else if ( word.match( /у$/i ) ) {
		if ( ! word.substring( word.length-2, word.length-1 ).match( /[аæеёиоыэюя]$/i ) ) {
			jot = 'й';
		}
	} else if ( !word.match( /[бвгджзйклмнопрстфхцчшщьъ]$/i ) ) {
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
			ending = hyphen + end_allative;
			break;
		case 'ablative':
			if ( jot == 'й' ) {
				ending = hyphen + jot + 'æ';
			}
			else {
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
