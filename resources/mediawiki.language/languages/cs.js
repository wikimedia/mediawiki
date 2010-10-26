/**
 * Czech (ƒçe≈°tina [subst.], ƒçesk√Ω [adj.], ƒçesky [adv.]) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 3 );
	switch ( count ) {
		case 1:
			return forms[0];
			break;
		case 2:
		case 3:
		case 4:
			return forms[1];
			break;
		default:
			return forms[2];
	}
}
