/**
 * Tagalog (Tagalog) language functions
 */
mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 2 );
	return ( count <= 1 ) ? forms[0] : forms[1];
}
