/**
 * Base language object
 *
 * Localized Language support attempts to mirror some of the functionality of
 * Language.php in MediaWiki. This object contains methods for loading and
 * transforming message text.
 */
( function( $, mw ) {

mw.language = {
	/**
	 * Process the PLURAL template substitution
	 *
	 * @param {object} template Template object
	 * @format template
	 * 	{
	 * 		'title': [title of template],
	 * 		'parameters': [template parameters]
	 * 	}
	 * @example {{Template:title|params}}
	 */
	'procPLURAL': function( template ) {
		if ( template.title && template.parameters && mw.language.convertPlural ) {
			// Check if we have forms to replace
			if ( template.parameters.length == 0 ) {
				return '';
			}
			// Restore the count into a Number ( if it got converted earlier )
			var count = mw.language.convertNumber( template.title, true );
			// Do convertPlural call 
			return mw.language.convertPlural( parseInt( count, 10 ), template.parameters );
		}
		// Could not process plural return first form or nothing
		if ( template.parameters[0] ) {
			return template.parameters[0];
		}
		return '';
	},
	/**
	 * Plural form transformations, needed for some languages.
	 *
	 * @param count integer Non-localized quantifier
	 * @param forms array List of plural forms
	 * @return string Correct form for quantifier in this language
	 */
	'convertPlural': function( count, forms ){
		if ( !forms || forms.length == 0 ) {
			return '';
		}
		return ( parseInt( count, 10 ) == 1 ) ? forms[0] : forms[1];
	},
	/**
	 * Pads an array to a specific length by copying the last one element.
	 *
	 * @param forms array Number of forms given to convertPlural
	 * @param count integer Number of forms required
	 * @return array Padded array of forms
	 */
	'preConvertPlural': function( forms, count ) {
		while ( forms.length < count ) {
			forms.push( forms[ forms.length-1 ] );
		}
		return forms;
	},
	/**
	 * Converts a number using digitTransformTable.
	 *
	 * @param {number} number Value to be converted
	 * @param {boolean} integer Convert the return value to an integer
	 */
	'convertNumber': function( num, integer ) {
		if ( !mw.language.digitTransformTable ) {
			return num;
		}
		// Set the target Transform table:
		var transformTable = mw.language.digitTransformTable;
		// Check if the "restore" to Latin number flag is set:
		if ( integer ) {
			if ( parseInt( num, 10 ) == num ) {
				return num;
			}
			var tmp = [];
			for ( var i in transformTable ) {
				tmp[ transformTable[ i ] ] = i;
			}
			transformTable = tmp;
		}
		var numberString =  '' + num;
		var convertedNumber = '';
		for ( var i = 0; i < numberString.length; i++ ) {
			if ( transformTable[ numberString[i] ] ) {
				convertedNumber += transformTable[numberString[i]];
			} else {
				convertedNumber += numberString[i];
			}
		}
		return integer ? parseInt( convertedNumber ) : convertedNumber;
	},
	// Digit Transform Table, populated by language classes where applicable
	'digitTransformTable': null
};
} )( jQuery, mediaWiki );
