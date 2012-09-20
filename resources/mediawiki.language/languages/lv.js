/**
 * Latvian (Latviešu) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 2 );	
	return ( ( count % 10 == 1 ) && ( count % 100 != 11 ) ) ? forms[0] : forms[1];
};
