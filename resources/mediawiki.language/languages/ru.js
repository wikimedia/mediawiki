/**
 * Russian (—Ä—É—Å—Å–∫–∏–π —è–∑—ã–∫) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	if ( forms.length === 2 ) {
		return count == 1 ? forms[0] : forms[1];		
	}
	forms = mediaWiki.language.preConvertPlural( forms, 3 );
	if ( count > 10 && Math.floor( ( count % 100 ) / 10 ) == 1 ) {
		return forms[2];
	}
	switch ( count % 10 ) {
		case 1:
			return forms[0];
		case 2:
		case 3:
		case 4:
			return forms[1];
		default:
			return forms[2];
	}
}
