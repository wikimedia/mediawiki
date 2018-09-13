/*!
 * Slovenian (Slovenščina) language functions
 */

mw.language.convertGrammar = function ( word, form ) {
	var grammarForms = mw.language.getData( 'sl', 'grammarForms' );
	if ( grammarForms && grammarForms[ form ] ) {
		return grammarForms[ form ][ word ];
	}
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
