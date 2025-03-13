/*!
 * Hungarian language functions
 * @author Santhosh Thottingal
 */

mw.language.convertGrammarMapping.hu = function ( word, form ) {
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
