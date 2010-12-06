/**
 * Southern Sami (Åarjelsaemien) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 4 );
	if ( count == 1 ) {
		return forms[1];
	}
	if ( count == 2 ) {
		return forms[2];
	}
	return forms[3];
};
