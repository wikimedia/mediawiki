/*!
 * Russian (Русский) language functions
 */

mediaWiki.language.convertGrammar = function ( word, form ) {
	'use strict';

	var forms, transformations, i, rule, sourcePattern, regexp, replacement;

	forms = mediaWiki.language.getData( 'ru', 'grammarForms' );
	if ( forms && forms[ form ] ) {
		return forms[ form ][ word ];
	}

	transformations = mediaWiki.language.getData( 'ru', 'grammarTransformations' );

	if ( !transformations[ form ] ) {
		return word;
	}

	for ( i = 0; i < transformations[ form ].length; i++ ) {
		rule = transformations[ form ][ i ];
		sourcePattern = rule[ 0 ];

		if ( sourcePattern === '@metadata' ) {
			continue;
		}

		regexp = new RegExp( sourcePattern );
		replacement = rule[ 1 ];

		if ( word.match( regexp ) ) {
			return word.replace( regexp, replacement );
		}
	}

	return word;
};
