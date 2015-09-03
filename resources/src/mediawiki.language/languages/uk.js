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
			if ( word.slice( -4 ) !== 'вікі' && word.slice( -4 ) !== 'Вікі' ) {
				if ( word.slice( -1 ) === 'ь' ) {
					word = word.slice( 0, -1 ) + 'я';
				} else if ( word.slice( -2 ) === 'ія' ) {
					word = word.slice( 0, -2 ) + 'ії';
				} else if ( word.slice( -2 ) === 'ка' ) {
					word = word.slice( 0, -2 ) + 'ки';
				} else if ( word.slice( -2 ) === 'ти' ) {
					word = word.slice( 0, -2 ) + 'тей';
				} else if ( word.slice( -2 ) === 'ды' ) {
					word = word.slice( 0, -2 ) + 'дов';
				} else if ( word.slice( -3 ) === 'ник' ) {
					word = word.slice( 0, -3 ) + 'ника';
				}
			}
			break;
		case 'accusative': // знахідний відмінок
			if ( word.slice( -4 ) !== 'вікі' && word.slice( -4 ) !== 'Вікі' ) {
				if ( word.slice( -2 ) === 'ія' ) {
					word = word.slice( 0, -2 ) + 'ію';
				}
			}
			break;
	}
	return word;
};
