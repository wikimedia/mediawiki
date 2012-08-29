/**
 * Hebrew (עברית) language functions
 */

mediaWiki.language.convertGrammar = function( word, form ) {
	var grammarForms = mw.language.getData( 'he', 'grammarForms' );
	if ( grammarForms && grammarForms[form] ) {
		return grammarForms[form][word] ;
	}
	switch ( form ) {
		case 'prefixed':
		case 'תחילית': // the same word in Hebrew
			// Duplicate prefixed "Waw", but only if it's not already double
			if ( word.substr( 0, 1 ) === "ו" && word.substr( 0, 2 ) !== "וו" ) {
				word = "ו" + word;
			}

			// Remove the "He" if prefixed
			if ( word.substr( 0, 1 ) === "ה" ) {
				word = word.substr( 1, word.length );
			}

			// Add a hyphen (maqaf) before numbers and non-Hebrew letters
			if (  word.substr( 0, 1 ) < "א" ||  word.substr( 0, 1 ) > "ת" ) {
				word = "־" + word;
			}
	}
	return word;
};
