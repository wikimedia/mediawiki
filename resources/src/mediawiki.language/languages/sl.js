/*!
 * Slovenian (Slovenščina) language functions
 */

mw.language.convertGrammarMapping.sl = function ( word, form ) {
	switch ( form ) {
		case 'mestnik': // locative
			word = 'o ' + word;
			break;
		case 'orodnik': // instrumental
			word = 'z ' + word;
			break;
	}
	return word;
};
