/**
 * Lithuanian (Lietuvių) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	if ( forms.length == 2 ) {
		return count == 1 ? forms[0] : forms[1];
	}
	forms = mediaWiki.language.preConvertPlural( forms, 3 );
	if ( count % 10 == 1 && count % 100 != 11 ) {
		return forms[0];
	}
	if ( count % 10 >= 2 && ( count % 100 < 10 || count % 100 >= 20 ) ) {
		return forms[1];		
	}
	return forms[2];
};
