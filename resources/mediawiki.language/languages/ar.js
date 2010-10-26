/**
 * Arabic (ÿßŸÑÿπÿ±ÿ®Ÿäÿ©) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 6 );
	if ( count == 0 ) {
		return forms[0];
	}
	if ( count == 1 ) {
		return forms[1];
	}
	if ( count == 2 ) {
		return forms[2];
	}
	if ( count % 100 >= 3 && count % 100 <= 10 ) {
		return forms[3];
	}
	if ( count % 100 >= 11 && count % 100 <= 99 ) {
		return forms[4];
	}
	return forms[5];
}

mediaWiki.language.digitTransformTable = {
    '0': 'Ÿ†', // &#x0660;
    '1': 'Ÿ°', // &#x0661;
    '2': 'Ÿ¢', // &#x0662;
    '3': 'Ÿ£', // &#x0663;
    '4': 'Ÿ§', // &#x0664;
    '5': 'Ÿ•', // &#x0665;
    '6': 'Ÿ¶', // &#x0666;
    '7': 'Ÿß', // &#x0667;
    '8': 'Ÿ®', // &#x0668;
    '9': 'Ÿ©', // &#x0669;
    '.': 'Ÿ´', // &#x066b; wrong table ?
    ',': 'Ÿ¨' // &#x066c;
};
