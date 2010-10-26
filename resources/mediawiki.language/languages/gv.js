/**
 * Manx (Gaelg) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 4 );
	if ( count > 0 && ( count % 20 ) === 0 ) {
		return forms[0];
	}
	switch ( count % 10 ) {
		case 1:
			return forms[1];
		case 2:
			return forms[2];
		default:
			return forms[3];
	}
}
