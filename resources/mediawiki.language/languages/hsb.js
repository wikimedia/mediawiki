/**
 * Upper Sorbian (Hornjoserbsce) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 4 );
	switch ( Math.abs( count ) % 100 ) {
		case 1:
			return forms[0];
		case 2:
			return forms[1];
		case 3:
		case 4:
			return forms[2];
		default:
			return forms[3];
	}
};


mediaWiki.language.convertGrammar = function( word, form ) {
	var grammarForms =mw.language.getData( 'hsb', 'grammarForms' );
	if ( grammarForms && grammarForms[form] ) {
		return grammarForms[form][word] ;
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
