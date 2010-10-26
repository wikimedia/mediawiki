/**
 * Irish (Gaeilge) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 3 );
	if ( count == 1 ) {
		return forms[0];
	}
	if ( count == 2 ) {
		return forms[1];
	}
	return forms[2];
}
