/**
 * Upper Sorbian (Hornjoserbsce) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 4 );
	switch ( Math.abs( count ) % 100 ) {
		case 1:
			return forms[0];
		case 2:
			return forms[1];
		case 3:
		case 4:
			return forms[2];
		default:
			return forms[3];
	}
};
