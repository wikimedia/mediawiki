/*!
 * Russian (Русский) language functions
 */

// These tests were originally made for names of Wikimedia
// websites, so they don't currently cover all the possible
// cases.

mediaWiki.language.convertGrammar = function ( word, form ) {
	'use strict';

	var grammarForms = mediaWiki.language.getData( 'ru', 'grammarForms' );
	if ( grammarForms && grammarForms[ form ] ) {
		return grammarForms[ form ][ word ];
	}
	switch ( form ) {
		case 'genitive': // родительный падеж
			if ( word.slice( -1 ) === 'ь' ) {
				word = word.slice( 0, -1 ) + 'я';
			} else if ( word.slice( -2 ) === 'ия' ) {
				word = word.slice( 0, -2 ) + 'ии';
			} else if ( word.slice( -2 ) === 'ка' ) {
				word = word.slice( 0, -2 ) + 'ки';
			} else if ( word.slice( -2 ) === 'ти' ) {
				word = word.slice( 0, -2 ) + 'тей';
			} else if ( word.slice( -2 ) === 'ды' ) {
				word = word.slice( 0, -2 ) + 'дов';
			} else if ( word.slice( -1 ) === 'д' ) {
				word = word.slice( 0, -1 ) + 'да';
			} else if ( word.slice( -3 ) === 'ные' ) {
				word = word.slice( 0, -3 ) + 'ных';
			} else if ( word.slice( -3 ) === 'ник' ) {
				word = word.slice( 0, -3 ) + 'ника';
			}
			break;
		case 'prepositional': // предложный падеж
			if ( word.slice( -1 ) === 'ь' ) {
				word = word.slice( 0, -1 ) + 'е';
			} else if ( word.slice( -2 ) === 'ия' ) {
				word = word.slice( 0, -2 ) + 'ии';
			} else if ( word.slice( -2 ) === 'ка' ) {
				word = word.slice( 0, -2 ) + 'ке';
			} else if ( word.slice( -2 ) === 'ти' ) {
				word = word.slice( 0, -2 ) + 'тях';
			} else if ( word.slice( -2 ) === 'ды' ) {
				word = word.slice( 0, -2 ) + 'дах';
			} else if ( word.slice( -1 ) === 'д' ) {
				word = word.slice( 0, -1 ) + 'де';
			} else if ( word.slice( -3 ) === 'ные' ) {
				word = word.slice( 0, -3 ) + 'ных';
			} else if ( word.slice( -3 ) === 'ник' ) {
				word = word.slice( 0, -3 ) + 'нике';
			}
			break;
	}
	return word;
};
