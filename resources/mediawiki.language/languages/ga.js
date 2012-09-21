/**
 * Irish (Gaeilge) language functions
 */

mediaWiki.language.convertGrammar = function( word, form ) {
	var grammarForms = mw.language.getData( 'ga', 'grammarForms' );
	if ( grammarForms && grammarForms[form] ) {
		return grammarForms[form][word] ;
	}
	switch ( form ) {
		case 'ainmlae':
			switch ( word ) {
				case 'an Domhnach':
					word = 'Dé Domhnaigh';
					break;
				case 'an Luan':
					word = 'Dé Luain';
					break;
				case 'an Mháirt':
					word = 'Dé Mháirt';
					break;
				case 'an Chéadaoin':
					word = 'Dé Chéadaoin';
					break;
				case 'an Déardaoin':
					word = 'Déardaoin';
					break;
				case 'an Aoine':
					word = 'Dé hAoine';
					break;
				case 'an Satharn':
					word = 'Dé Sathairn';
					break;
			}
	}
	return word;
};
