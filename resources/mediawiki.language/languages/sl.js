/**
 * Slovenian (Slovenščina) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 5 );
	if ( count % 100 == 1 ) {
		return forms[0];
	}
	if ( count % 100 == 2 ) {
		return forms[1];
	}
	if ( count % 100 == 3 || count % 100 == 4 ) {
		return forms[2];
	}
	if ( count != 0 ) {
		return forms[3];
	}
	return forms[4];
};

mediaWiki.language.convertGrammar = function( word, form ) {
	var grammarForms = mw.language.getData( 'sl', 'grammarForms' );
	if ( grammarForms && grammarForms[form] ) {
		return grammarForms[form][word] ;
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
