/**
 * Armenian (Հայերեն) language functions
 */

mediaWiki.language.convertPlural = function( count, forms ) {
	forms = mediaWiki.language.preConvertPlural( forms, 2 );
	return ( Math.abs( count ) <= 1 ) ? forms[0] : forms[1];
};

mediaWiki.language.convertGrammar = function( word, form ) {
	var grammarForms = mw.language.getData( 'hy', 'grammarForms' );
	if ( grammarForms && grammarForms[form] ) {
		return grammarForms[form][word] ;
	}

	// These rules are not perfect, but they are currently only used for site names so it doesn't
	// matter if they are wrong sometimes. Just add a special case for your site name if necessary.

	switch ( form ) {
		case 'genitive': // սեռական հոլով
			if ( word.substr( -1 ) === 'ա' )
				word = word.substr( 0, word.length -1 )  + 'այի';
			else if ( word.substr( -1 ) === 'ո' )
				word = word.substr( 0, word.length - 1 ) + 'ոյի';
			else if ( word.substr( -4 ) === 'գիրք' )
				word = word.substr( 0, word.length - 4 ) + 'գրքի';
			else
				word = word + 'ի';
			break;
		}
	return word;
};
