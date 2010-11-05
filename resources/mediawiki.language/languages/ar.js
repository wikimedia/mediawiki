/**
 * Arabic (العربية) language functions
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
    '0': '٠', // &#x0660;
    '1': '١', // &#x0661;
    '2': '٢', // &#x0662;
    '3': '٣', // &#x0663;
    '4': '٤', // &#x0664;
    '5': '٥', // &#x0665;
    '6': '٦', // &#x0666;
    '7': '٧', // &#x0667;
    '8': '٨', // &#x0668;
    '9': '٩', // &#x0669;
    '.': '٫', // &#x066b; wrong table ?
    ',': '٬' // &#x066c;
};
