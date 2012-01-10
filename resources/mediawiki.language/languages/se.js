/**
 * Northern Sami (Sámegiella) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	if ( !forms || forms.length === 0 ) {
			return '';
	}
	forms = mediaWiki.language.preConvertPlural( forms, 3 );
	if ( count == 1 ) {
		return forms[0];
	}
	if ( count == 2 ) {
		return forms[1];
	}
	return forms[2];
};
