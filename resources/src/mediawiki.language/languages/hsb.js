/*!
 * Upper Sorbian (Hornjoserbsce) language functions
 */

mw.language.convertGrammar = function ( word, form ) {
	var grammarForms = mw.language.getData( 'hsb', 'grammarForms' );
	if ( grammarForms && grammarForms[ form ] ) {
		return grammarForms[ form ][ word ];
	}
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
