/*!
 * Finnish (Suomi) language functions
 * @author Santhosh Thottingal
 * @author Jack Phoenix <jack@countervandalism.net>
 */
/* global $ */
mediaWiki.language.convertGrammar = function ( word, form ) {
	var grammarForms, aou, origWord;

	grammarForms = mediaWiki.language.getData( 'fi', 'grammarForms' );
	if ( grammarForms && grammarForms[form] ) {
		return grammarForms[form][word];
	}

	// vowel harmony flag
	aou = word.match( /[aou][^äöy]*$/i );
	origWord = word;
	if ( word.match( /wiki$/i ) ) {
		aou = false;
	}
	// append i after final consonant
	if ( word.match( /[bcdfghjklmnpqrstvwxz]$/i ) ) {
		word += 'i';
	}

	switch ( form ) {
		case 'genitive':
			word += 'n';
			break;
		case 'elative':
			word += ( aou ? 'sta' : 'stä' );
			break;
		case 'partitive':
			word += ( aou ? 'a' : 'ä' );
			break;
		case 'illative':
			// Double the last letter and add 'n'
			word += word.slice( -1 ) + 'n';
			break;
		case 'inessive':
			word += ( aou ? 'ssa' : 'ssä' );
			break;
		case 'allative':
			// Check for double consonants
			// Regex borrowed from http://tartarus.org/martin/PorterStemmer/php.txt
			// Needed to handle "special" cases like "Matti", "Pekka", etc.
			// which need to become Matille and Pekalle, respectively, in
			// allative
			var lastFiveCharacters = word.substr( -5 );
			var lastFourCharacters = word.substr( -4 );
			var matches = lastFiveCharacters.match( /[bcdfghjklmnpqrstvwxz]/ );
			if ( matches ) {
				// @see http://stackoverflow.com/questions/801545/how-to-replace-double-more-letters-to-a-single-letter/801571#801571
				// Get the last five characters instead of the whole word
				// to avoid fucking up compound words, since usually you
				// should apply transformation only on the last word of
				// a compound word.
				// This is sorta stupid because this replace call runs
				// even if lastFiveCharacters does *not* contain two
				// consecutive instances of matches[0], but eh.
				var newLastFiveCharacters = lastFiveCharacters.replace( ( matches[0] + matches[0] ), matches[0] );
				word = word.replace( lastFiveCharacters, newLastFiveCharacters );
			}
			if ( lastFourCharacters === 'neni' ) {
				// Many Finnish surnames end in -nen, and they are handled
				// a bit differently...
				// The superfluous "i" comes from the vowel harmony flag stuff
				// above
				// This transforms "Matti Meikäläinen" into "Matti Meikäläiselle"
				word = word.replace( lastFourCharacters, 'se' );
			}
			// If $word is something like "Test1234" or whatever (last
			// characters being numbers), the allative form needs to contain
			// the colon before the "lle" suffix.
			// @see http://stackoverflow.com/questions/4114609/check-if-a-string-ends-with-a-number-in-php/4114619#4114619
			var lastCharacterIsNumeric = $.isNumeric( word.substr( -1, 1 ) );
			word += ( lastCharacterIsNumeric ? ':' : '' ) + 'lle';
			break;
		default:
			word = origWord;
			break;
	}
	return word;
};
