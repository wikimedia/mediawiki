/**
 * Serbian (latin script) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 3 );
	if ( count > 10 && Math.floor( ( count % 100 ) / 10 ) == 1 ) {
		return forms[2];
	}
	switch ( count % 10 ) {
		case 1:
			return forms[0];
		case 2:
		case 3:
		case 4:
			return forms[1];
		default:
			return forms[2];
	}
}
