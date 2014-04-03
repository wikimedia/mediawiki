/*!
 * Hungarian language functions
 * @author Santhosh Thottingal
 */

mediaWiki.language.convertGrammar = function ( word, form ) {
	var grammarForms = mediaWiki.language.getData( 'hu', 'grammarForms' );
	if ( grammarForms && grammarForms[form] ) {
		return grammarForms[form][word];
	}
	switch ( form ) {
		case 'rol':
			word += 'ról';
			break;
		case 'ba':
			word += 'ba';
			break;
		case 'k':
			word += 'k';
			break;
	}
	return word;
};
