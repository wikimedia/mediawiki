/**
 * Polish (polski) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 3 );
	count = Math.abs( count );
	if ( count == 1 ) {
		return forms[0];
	}
	switch ( count % 10 ) {
		case 2:
		case 3:
		case 4:
			if ( count / 10 % 10 != 1 ) {
				return forms[1];
			}
		default:
			return forms[2];
	}
}
