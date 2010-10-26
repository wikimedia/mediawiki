/**
 * Samogitian (≈Ωemaitƒó≈°ka) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 4 );
	count = Math.abs( count );
	if ( count === 0 || ( count % 100 === 0 || ( count % 100 >= 10 && count % 100 < 20 ) ) ) {
		return forms[2];
	}
	if ( count % 10 === 1 ) {
		return forms[0];
	}
	if ( count % 10 === 2 ) {
		return forms[1];
	}
	return forms[3];
}
