( function () {
	Object.assign( mw.language, /** @lends mw.language */{

		/**
		 * Plural form transformations, needed for some languages.
		 *
		 * @param {number} count Non-localized quantifier
		 * @param {Array} forms List of plural forms
		 * @param {Object} [explicitPluralForms] List of explicit plural forms
		 * @return {string} Correct form for quantifier in this language
		 */
		convertPlural: function ( count, forms, explicitPluralForms ) {
			let pluralFormIndex = 0;

			if ( explicitPluralForms && ( explicitPluralForms[ count ] !== undefined ) ) {
				return explicitPluralForms[ count ];
			}

			if ( !forms || forms.length === 0 ) {
				return '';
			}

			const pluralRules = mw.language.getData( mw.config.get( 'wgUserLanguage' ), 'pluralRules' );
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
			const userLanguage = mw.config.get( 'wgUserLanguage' );

			const forms = mw.language.getData( userLanguage, 'grammarForms' );
			if ( forms && forms[ form ] ) {
				return forms[ form ][ word ];
			}

			const transformations = mw.language.getData( userLanguage, 'grammarTransformations' );

			if ( !( transformations && transformations[ form ] ) ) {
				return word;
			}

			let patterns = transformations[ form ];

			// Some names of grammar rules are aliases for other rules.
			// In such cases the value is a string rather than object,
			// so load the actual rules.
			if ( typeof patterns === 'string' ) {
				patterns = transformations[ patterns ];
			}

			for ( let i = 0; i < patterns.length; i++ ) {
				const rule = patterns[ i ];
				const sourcePattern = rule[ 0 ];

				if ( sourcePattern === '@metadata' ) {
					continue;
				}

				const regexp = new RegExp( sourcePattern );
				const replacement = rule[ 1 ];

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
			let text = '';

			for ( let i = 0; i < list.length; i++ ) {
				text += list[ i ];
				if ( list.length - 2 === i ) {
					text += mw.msg( 'and' ) + mw.msg( 'word-separator' );
				} else if ( list.length - 1 !== i ) {
					text += mw.msg( 'comma-separator' );
				}
			}
			return text;
		},

		/**
		 * Formats language tags according the BCP 47 standard.
		 * See LanguageCode::bcp47 for the PHP implementation.
		 *
		 * @param {string} languageTag Well-formed language tag
		 * @return {string}
		 */
		bcp47: function ( languageTag ) {
			let isFirstSegment = true,
				isPrivate = false;

			languageTag = languageTag.toLowerCase();

			const bcp47Map = mw.language.getData( mw.config.get( 'wgUserLanguage' ), 'bcp47Map' );
			if ( bcp47Map && Object.prototype.hasOwnProperty.call( bcp47Map, languageTag ) ) {
				languageTag = bcp47Map[ languageTag ];
			}

			const segments = languageTag.split( '-' );
			const formatted = segments.map( ( segment ) => {
				let newSegment;

				// when previous segment is x, it is a private segment and should be lc
				if ( isPrivate ) {
					newSegment = segment.toLowerCase();
				// ISO 3166 country code
				} else if ( segment.length === 2 && !isFirstSegment ) {
					newSegment = segment.toUpperCase();
				// ISO 15924 script code
				} else if ( segment.length === 4 && !isFirstSegment ) {
					newSegment = segment.charAt( 0 ).toUpperCase() + segment.slice( 1 ).toLowerCase();
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
