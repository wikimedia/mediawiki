/**
 * Old Church Slavonic (Словѣ́ньскъ / ⰔⰎⰑⰂⰡⰐⰠⰔⰍⰟ) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 4 );
	switch ( count % 10 ) {
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
