/**
 * Base language object
 *
 * Localized Language support attempts to mirror some of the functionality of
 * Language.php in MediaWiki. This object contains methods for loading and
 * transforming message text.
 */
( function( $, mw ) {

var language = {
	/**
	 * @var data {Object} Language related data (keyed by language,
	 * contains instances of mw.Map).
	 * @example Set data
	 * <code>
	 *     // Override, extend or create the language data object of 'nl'
	 *     mw.language.setData( 'nl', 'myKey', 'My value' );
	 * </code>
	 * @example Get GrammarForms data for language 'nl':
	 * <code>
	 *     var grammarForms = mw.language.getData( 'nl', 'grammarForms' );
	 * </code>
	 */
	data: {},

	/**
	 * Convenience method for retreiving language data by language code and data key,
	 * covering for the potential inexistance of a data object for this langiage.
	 * @param langCode {String}
	 * @param dataKey {String}
	 * @return {mixed} Value stored in the mw.Map (or undefined if there is no map for
	   the specified langCode).
	 */
	getData: function ( langCode, dataKey ) {
		var langData = language.data;
		if ( langData[langCode] instanceof mw.Map ) {
			return langData[langCode].get( dataKey );
		}
		return undefined;
	},

	/**
	 * Convenience method for setting language data by language code and data key.
	 * Creates a data object if there isn't one for the specified language already.
	 * @param langCode {String}
	 * @param dataKey {String}
	 * @param value {mixed}
	 */
	setData: function ( langCode, dataKey, value ) {
		var langData = language.data;
		if ( !( langData[langCode] instanceof mw.Map ) ) {
			langData[langCode] = new mw.Map();
		}
		langData[langCode].set( dataKey, value );
	},
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
			if ( template.parameters.length === 0 ) {
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
		if ( !forms || forms.length === 0 ) {
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
	 * @param {num} number Value to be converted
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
		return integer ? parseInt( convertedNumber, 10 ) : convertedNumber;
	},
	/**
	 * Provides an alternative text depending on specified gender.
	 * Usage {{gender:[gender|user object]|masculine|feminine|neutral}}.
	 * If second or third parameter are not specified, masculine is used.
	 * 
	 * These details may be overriden per language.
	 *
	 * @param gender string male, female, or anything else for neutral.
	 * @param forms array List of gender forms
	 *
	 * @return string
	 */
	'gender': function( gender, forms ) {
		if ( !forms || forms.length === 0 ) {
			return '';
		}
		forms = mw.language.preConvertPlural( forms, 2 );
		if ( gender === 'male' ) {
			return forms[0];
		}
		if ( gender === 'female' ) {
			return forms[1];
		}
		return ( forms.length === 3 ) ? forms[2] : forms[0];
	},

	/**
	 * Grammatical transformations, needed for inflected languages.
	 * Invoked by putting {{grammar:form|word}} in a message.
	 * The rules can be defined in $wgGrammarForms global or grammar
	 * forms can be computed dynamically by overriding this method per language
	 *
	 * @param word {String}
	 * @param form {String}
	 * @return {String}
	 */
	convertGrammar: function ( word, form ) {
		var grammarForms = language.getData( mw.config.get( 'wgContentLanguage' ), 'grammarForms' );
		if ( grammarForms && grammarForms[form] ) {
			return grammarForms[form][word] || word;
		}
		return word;
	},

	// Digit Transform Table, populated by language classes where applicable
	'digitTransformTable': null
};

mw.language = language;

} )( jQuery, mediaWiki );
