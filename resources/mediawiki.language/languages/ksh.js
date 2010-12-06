/**
 * Ripuarian (Ripoarƒósh) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 3 );
	if ( count == 1 ) {
		return forms[0];
	}
	if ( count == 0 ) {
		return forms[2];
	}
	return forms[1];
};
