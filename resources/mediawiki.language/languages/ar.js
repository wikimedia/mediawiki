/**
 * Arabic (العربية) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 6 );
	if ( count == 0 ) {
		return forms[0];
	}
	if ( count == 1 ) {
		return forms[1];
	}
	if ( count == 2 ) {
		return forms[2];
	}
	if ( count % 100 >= 3 && count % 100 <= 10 ) {
		return forms[3];
	}
	if ( count % 100 >= 11 && count % 100 <= 99 ) {
		return forms[4];
	}
	return forms[5];
};

