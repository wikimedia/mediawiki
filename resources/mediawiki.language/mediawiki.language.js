/**
 * Localized Language support attempts to mirror some of the functionality of
 * Language.php in MediaWiki.
 * This adds methods for transforming message text.
 */
( function ( mw, $ ) {

var language = {

	/**
	 * Process the PLURAL template substitution
	 *
	 * @param {object} template Template object
	 * @format template
	 *  {
	 *      'title': [title of template],
	 *      'parameters': [template parameters]
	 *  }
	 * @example {{Template:title|params}}
	 */
	procPLURAL: function ( template ) {
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
	convertPlural: function ( count, forms ) {
		var pluralRules,
			pluralFormIndex = 0;

		if ( !forms || forms.length === 0 ) {
			return '';
		}
		pluralRules = mw.language.getData( mw.config.get( 'wgUserLanguage' ), 'pluralRules' );
		if ( !pluralRules ) {
			// default fallback.
			return ( count === 1 ) ? forms[0] : forms[1];
		}
		pluralFormIndex = mw.cldr.getPluralForm( count, pluralRules );
		pluralFormIndex = Math.min( pluralFormIndex, forms.length - 1 );
		return forms[pluralFormIndex];
	},

	/**
	 * Pads an array to a specific length by copying the last one element.
	 *
	 * @param forms array Number of forms given to convertPlural
	 * @param count integer Number of forms required
	 * @return array Padded array of forms
	 */
	preConvertPlural: function ( forms, count ) {
		while ( forms.length < count ) {
			forms.push( forms[ forms.length-1 ] );
		}
		return forms;
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
	gender: function ( gender, forms ) {
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
		var grammarForms = mw.language.getData( mw.config.get( 'wgUserLanguage' ), 'grammarForms' );
		if ( grammarForms && grammarForms[form] ) {
			return grammarForms[form][word] || word;
		}
		return word;
	}

};

$.extend( mw.language, language );

}( mediaWiki, jQuery ) );
