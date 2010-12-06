/**
 * Northern Sami (Sámegiella) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	if ( count == 0 ) {
		return '';
	}
	forms = mediaWiki.language.preConvertPlural( forms, 3 );
	if ( count == 1 ) {
		return forms[1];
	}
	if ( count == 2 ) {
		return forms[2];
	}
	return ''
};
