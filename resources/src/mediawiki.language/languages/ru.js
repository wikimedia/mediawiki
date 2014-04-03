/*!
 * Russian (Русский) language functions
 */

// These tests were originally made for names of Wikimedia
// websites, so they don't currently cover all the possible
// cases.

mediaWiki.language.convertGrammar = function ( word, form ) {
	'use strict';

	var grammarForms = mediaWiki.language.getData( 'ru', 'grammarForms' );
	if ( grammarForms && grammarForms[form] ) {
		return grammarForms[form][word];
	}
	switch ( form ) {
		case 'genitive': // родительный падеж
			if ( word.substr( word.length - 1 ) === 'ь' ) {
				word = word.substr(0, word.length - 1 ) + 'я';
			} else if ( word.substr( word.length - 2 ) === 'ия' ) {
				word = word.substr(0, word.length - 2 ) + 'ии';
			} else if ( word.substr( word.length - 2 ) === 'ка' ) {
				word = word.substr(0, word.length - 2 ) + 'ки';
			} else if ( word.substr( word.length - 2 )  === 'ти' ) {
				word = word.substr(0, word.length - 2 ) + 'тей';
			} else if ( word.substr( word.length - 2 ) === 'ды' ) {
				word = word.substr(0, word.length - 2 ) + 'дов';
			} else if ( word.substr( word.length - 3 ) === 'ные' ) {
				word = word.substr(0, word.length - 3 ) + 'ных';
			} else if ( word.substr( word.length - 3 ) === 'ник' ) {
				word = word.substr(0, word.length - 3 ) + 'ника';
			}
			break;
		case 'prepositional': // предложный падеж
			if ( word.substr( word.length - 1 ) === 'ь' ) {
				word = word.substr(0, word.length - 1 ) + 'е';
			} else if ( word.substr( word.length - 2 ) === 'ия' ) {
				word = word.substr(0, word.length - 2 ) + 'ии';
			} else if ( word.substr( word.length - 2 ) === 'ка' ) {
				word = word.substr(0, word.length - 2 ) + 'ке';
			} else if ( word.substr( word.length - 2 )  === 'ти' ) {
				word = word.substr(0, word.length - 2 ) + 'тях';
			} else if ( word.substr( word.length - 2 ) === 'ды' ) {
				word = word.substr(0, word.length - 2 ) + 'дах';
			} else if ( word.substr( word.length - 3 ) === 'ные' ) {
				word = word.substr(0, word.length - 3 ) + 'ных';
			} else if ( word.substr( word.length - 3 ) === 'ник' ) {
				word = word.substr(0, word.length - 3 ) + 'нике';
			}
			break;
	}
	return word;
};
