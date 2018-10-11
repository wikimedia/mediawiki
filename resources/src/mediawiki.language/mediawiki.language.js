/*
 * Methods for transforming message syntax.
 */
( function () {

	/**
	 * @class mw.language
	 */
	$.extend( mw.language, {

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

			if ( explicitPluralForms && ( explicitPluralForms[ count ] !== undefined ) ) {
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
		 * @param {string} form
		 * @return {string}
		 */
		convertGrammar: function ( word, form ) {
			var userLanguage, forms, transformations,
				patterns, i, rule, sourcePattern, regexp, replacement;

			userLanguage = mw.config.get( 'wgUserLanguage' );

			forms = mw.language.getData( userLanguage, 'grammarForms' );
			if ( forms && forms[ form ] ) {
				return forms[ form ][ word ];
			}

			transformations = mw.language.getData( userLanguage, 'grammarTransformations' );

			if ( !( transformations && transformations[ form ] ) ) {
				return word;
			}

			patterns = transformations[ form ];

			// Some names of grammar rules are aliases for other rules.
			// In such cases the value is a string rather than object,
			// so load the actual rules.
			if ( typeof patterns === 'string' ) {
				patterns = transformations[ patterns ];
			}

			for ( i = 0; i < patterns.length; i++ ) {
				rule = patterns[ i ];
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
		},

		/**
		 * Formats language tags according the BCP 47 standard.
		 * See LanguageCode::bcp47 for the PHP implementation.
		 *
		 * @param {string} languageTag Well-formed language tag
		 * @return {string}
		 */
		bcp47: function ( languageTag ) {
			var bcp47Map,
				formatted,
				segments,
				isFirstSegment = true,
				isPrivate = false;

			languageTag = languageTag.toLowerCase();

			bcp47Map = mw.language.getData( mw.config.get( 'wgUserLanguage' ), 'bcp47Map' );
			if ( bcp47Map && Object.prototype.hasOwnProperty.call( bcp47Map, languageTag ) ) {
				languageTag = bcp47Map[ languageTag ];
			}

			segments = languageTag.split( '-' );
			formatted = segments.map( function ( segment ) {
				var newSegment;

				// when previous segment is x, it is a private segment and should be lc
				if ( isPrivate ) {
					newSegment = segment.toLowerCase();
				// ISO 3166 country code
				} else if ( segment.length === 2 && !isFirstSegment ) {
					newSegment = segment.toUpperCase();
				// ISO 15924 script code
				} else if ( segment.length === 4 && !isFirstSegment ) {
					newSegment = segment.charAt( 0 ).toUpperCase() + segment.substring( 1 ).toLowerCase();
				// Use lowercase for other cases
				} else {
					newSegment = segment.toLowerCase();
				}

				isPrivate = segment.toLowerCase() === 'x';
				isFirstSegment = false;

				return newSegment;
			} );

			return formatted.join( '-' );
		}
	} );

}() );
