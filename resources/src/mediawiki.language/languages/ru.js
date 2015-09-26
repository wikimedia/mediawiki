/*!
 * Russian (Русский) language functions
 */

mediaWiki.language.convertGrammar = function ( word, grammarCase ) {
	'use strict';

	var grammarForms, grammarTransformations, i, rule, form, regexp, replacement;

	grammarForms = mediaWiki.language.getData( 'ru', 'grammarForms' );
	if ( grammarForms && grammarForms[ grammarCase ] ) {
		return grammarForms[ grammarCase ][ word ];
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
