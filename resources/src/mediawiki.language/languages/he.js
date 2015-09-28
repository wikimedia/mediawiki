/*!
 * Hebrew (עברית) language functions
 */

mediaWiki.language.convertGrammar = function ( word, form ) {
	var grammarForms = mediaWiki.language.getData( 'he', 'grammarForms' );
	if ( grammarForms && grammarForms[ form ] ) {
		return grammarForms[ form ][ word ];
	}

	grammarTransformations = mediaWiki.language.getData( 'ru', 'grammarTransformations' );

	if ( grammarTransformations[ grammarCase ] ) {
		for ( i = 0; i < grammarTransformations[ grammarCase ].length; i++ ) {
			rule = grammarTransformations[ grammarCase ][ i ];
			form = rule[ 0 ];

			if ( form === '@metadata' ) {
				continue;
			}

			regexp = new RegExp( form );
			replacement = rule[ 1 ];

			if ( word.match( form ) ) {
				word = word.replace( regexp, replacement );

				break;
			}
		}
	}

	return word;
};
