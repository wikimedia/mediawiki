/**
 * Maltese (Malti) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 4 );
	if ( count == 1 ) {
		return forms[0];
	}
	if ( count == 0 || ( count % 100 > 1 && count % 100 < 11 ) ) {
		return forms[1];
	}
	if ( count % 100 > 10 && count % 100 < 20 ) {
		return forms[2];
	}
	return forms[3];
};
