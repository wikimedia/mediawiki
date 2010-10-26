/**
 * Welsh (Cymraeg) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 6 );
	count = Math.abs( count );
	if ( count >= 0 && count <= 3 ) {
		return forms[count];
	}
	if ( count == 6 ) {
		return forms[4];
	}
	return forms[5];
}
