/**
 * Scots Gaelic (Gàidhlig) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 4 );
	count = Math.abs( count );
	if ( count === 1 ) {
		return forms[0];
	}
	if ( count === 2 ) {
		return forms[1];
	}
	if ( count >= 3 && count <= 10 ) {
		return forms[2];
	}
	return forms[3];
}
