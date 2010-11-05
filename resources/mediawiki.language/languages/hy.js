/**
 * Armenian (Հայերեն) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 2 );
	return ( Math.abs( count ) <= 1 ) ? forms[0] : forms[1];
}
