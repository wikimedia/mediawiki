/*!
 * Upper Sorbian (Hornjoserbsce) language functions
 */

mw.language.convertGrammarMapping.hsb = function ( word, form ) {
	switch ( form ) {
		case 'instrumental': // instrumental
			word = 'z ' + word;
			break;
		case 'lokatiw': // lokatiw
			word = 'wo ' + word;
			break;
	}
	return word;
};
