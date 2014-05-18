/*!
 * Ukrainian (Українська) language functions
 */

mediaWiki.language.convertGrammar = function ( word, form ) {
	var grammarForms = mediaWiki.language.getData( 'uk', 'grammarForms' );
	if ( grammarForms && grammarForms[form] ) {
		return grammarForms[form][word];
	}
	switch ( form ) {
		case 'genitive': // родовий відмінок
			if ( word.substr( word.length - 4 ) !== 'вікі' && word.substr( word.length - 4 ) !== 'Вікі' ) {
				if ( word.substr( word.length - 1 ) === 'ь' ) {
					word = word.substr(0, word.length - 1 ) + 'я';
				} else if ( word.substr( word.length - 2 ) === 'ія' ) {
					word = word.substr(0, word.length - 2 ) + 'ії';
				} else if ( word.substr( word.length - 2 ) === 'ка' ) {
					word = word.substr(0, word.length - 2 ) + 'ки';
				} else if ( word.substr( word.length - 2 ) === 'ти' ) {
					word = word.substr(0, word.length - 2 ) + 'тей';
				} else if ( word.substr( word.length - 2 ) === 'ды' ) {
					word = word.substr(0, word.length - 2 ) + 'дов';
				} else if ( word.substr( word.length - 3 ) === 'ник' ) {
					word = word.substr(0, word.length - 3 ) + 'ника';
				}
			}
			break;
		case 'accusative': // знахідний відмінок
			if ( word.substr( word.length - 4 ) !== 'вікі' && word.substr( word.length - 4 ) !== 'Вікі' ) {
				if ( word.substr( word.length - 2 ) === 'ія' ) {
					word = word.substr(0, word.length - 2 ) + 'ію';
				}
			}
			break;
	}
	return word;
};
