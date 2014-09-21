/*!
 * Lower Sorbian (Dolnoserbski) language functions
 */

mediaWiki.language.convertGrammar = function ( word, form ) {
	var grammarForms = mediaWiki.language.getData( 'dsb', 'grammarForms' );
	if ( grammarForms && grammarForms[form] ) {
		return grammarForms[form][word];
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
