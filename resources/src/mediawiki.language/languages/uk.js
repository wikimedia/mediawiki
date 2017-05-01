/*!
 * Ukrainian (Українська) language functions
 */

mediaWiki.language.convertGrammar = function ( word, form ) {
	var grammarForms = mediaWiki.language.getData( 'uk', 'grammarForms' );
	if ( grammarForms && grammarForms[ form ] ) {
		return grammarForms[ form ][ word ];
	}
	switch ( form ) {
		case 'genitive': // родовий відмінок
			if ( word.slice( -2 ) === 'ія' ) {
				word = word.slice( 0, -2 ) + 'ії';
			} else if ( word.slice( -2 ) === 'ти' ) {
				word = word.slice( 0, -2 ) + 'т';
			} else if ( word.slice( -2 ) === 'ди' ) {
				word = word.slice( 0, -2 ) + 'дів';
			} else if ( word.slice( -3 ) === 'ник' ) {
				word = word.slice( 0, -3 ) + 'ника';
			}

			break;
		case 'accusative': // знахідний відмінок
			if ( word.slice( -2 ) === 'ія' ) {
				word = word.slice( 0, -2 ) + 'ію';
			}

			break;
	}

	return word;
};
