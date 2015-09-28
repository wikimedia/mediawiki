/*
 * Methods for transforming message syntax.
 */
( function ( mw, $ ) {

/**
 * @class mw.language
 */
$.extend( mw.language, {

	/**
	 * Process the PLURAL template substitution
	 *
	 * @private
	 * @param {Object} template Template object
	 * @param {string} template.title
	 * @param {Array} template.parameters
	 * @return {string}
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
		if ( template.parameters[ 0 ] ) {
			return template.parameters[ 0 ];
		}
		return '';
	},

	/**
	 * Plural form transformations, needed for some languages.
	 *
	 * @param {number} count Non-localized quantifier
	 * @param {Array} forms List of plural forms
	 * @param {Object} [explicitPluralForms] List of explicit plural forms
	 * @return {string} Correct form for quantifier in this language
	 */
	convertPlural: function ( count, forms, explicitPluralForms ) {
		var pluralRules,
			pluralFormIndex = 0;

		if ( explicitPluralForms && explicitPluralForms[ count ] ) {
			return explicitPluralForms[ count ];
		}

		if ( !forms || forms.length === 0 ) {
			return '';
		}

		pluralRules = mw.language.getData( mw.config.get( 'wgUserLanguage' ), 'pluralRules' );
		if ( !pluralRules ) {
			// default fallback.
			return ( count === 1 ) ? forms[ 0 ] : forms[ 1 ];
		}
		pluralFormIndex = mw.cldr.getPluralForm( count, pluralRules );
		pluralFormIndex = Math.min( pluralFormIndex, forms.length - 1 );
		return forms[ pluralFormIndex ];
	},

	/**
	 * Pads an array to a specific length by copying the last one element.
	 *
	 * @private
	 * @param {Array} forms Number of forms given to convertPlural
	 * @param {number} count Number of forms required
	 * @return {Array} Padded array of forms
	 */
	preConvertPlural: function ( forms, count ) {
		while ( forms.length < count ) {
			forms.push( forms[ forms.length - 1 ] );
		}
		return forms;
	},

	/**
	 * Provides an alternative text depending on specified gender.
	 *
	 * Usage in message text: `{{gender:[gender|user object]|masculine|feminine|neutral}}`.
	 * If second or third parameter are not specified, masculine is used.
	 *
	 * These details may be overridden per language.
	 *
	 * @param {string} gender 'male', 'female', or anything else for neutral.
	 * @param {Array} forms List of gender forms
	 * @return {string}
	 */
	gender: function ( gender, forms ) {
		if ( !forms || forms.length === 0 ) {
			return '';
		}
		forms = mw.language.preConvertPlural( forms, 2 );
		if ( gender === 'male' ) {
			return forms[ 0 ];
		}
		if ( gender === 'female' ) {
			return forms[ 1 ];
		}
		return ( forms.length === 3 ) ? forms[ 2 ] : forms[ 0 ];
	},

	/**
	 * Grammatical transformations, needed for inflected languages.
	 * Invoked by putting `{{grammar:case|word}}` in a message.
	 *
	 * The rules can be defined in $wgGrammarForms global or computed
	 * dynamically by overriding this method per language.
	 *
	 * @param {string} word
	 * @param {string} grammarCase
	 * @return {string}
	 */
	convertGrammar: function ( word, grammarCase ) {
		var userLanguage, grammarForms, grammarTransformations,
			i, rule, forms, form, regexp, replacement;

		userLanguage = mw.config.get( 'wgUserLanguage' );

		grammarForms = mediaWiki.language.getData( userLanguage, 'grammarForms' );
		if ( grammarForms && grammarForms[ grammarCase ] ) {
			return grammarForms[ grammarCase ][ word ];
		}

		grammarTransformations = mediaWiki.language.getData(
			userLanguage,
			'grammarTransformations'
		);

		if ( grammarTransformations && grammarTransformations[ grammarCase ] ) {
			forms = grammarTransformations[ grammarCase ];

			// Check whether it's a redirect to a different name for
			// the grammar tranformation
			if ( typeof forms === 'string' ) {
				forms = grammarTransformations[ forms ];
			}

			for ( i = 0; i < forms.length; i++ ) {
				rule = forms[ i ];
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
	},

	/**
	 * Turn a list of string into a simple list using commas and 'and'.
	 *
	 * See Language::listToText in languages/Language.php
	 *
	 * @param {string[]} list
	 * @return {string}
	 */
	listToText: function ( list ) {
		var text = '',
			i = 0;

		for ( ; i < list.length; i++ ) {
			text += list[ i ];
			if ( list.length - 2 === i ) {
				text += mw.msg( 'and' ) + mw.msg( 'word-separator' );
			} else if ( list.length - 1 !== i ) {
				text += mw.msg( 'comma-separator' );
			}
		}
		return text;
	},

	setSpecialCharacters: function ( data ) {
		this.specialCharacters = data;
	}
} );

}( mediaWiki, jQuery ) );
