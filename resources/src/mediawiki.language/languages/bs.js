/*!
 * Bosnian (bosanski) language functions
 */

mw.language.convertGrammarMapping.bs = function ( word, form ) {
	switch ( form ) {
		case 'instrumental': // instrumental
			word = 's ' + word;
			break;
		case 'lokativ': // locative
			word = 'o ' + word;
			break;
	}
	return word;
};
