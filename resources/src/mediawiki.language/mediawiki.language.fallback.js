/*
 * Language-fallback-chain-related utilities for mediawiki.language.
 */
( function ( mw, $ ) {
	/**
	 * @class mw.language
	 */

	$.extend( mw.language, {

		/**
		 * Get the language fallback chain for current UI language (not including the language itself).
		 *
		 * @return {string[]} Array of language keys, e.g. `['de', 'en']`
		 */
		getFallbackLanguages: function () {
			return mw.language.getData( mw.config.get( 'wgUserLanguage' ),
				'fallbackLanguages' ) || [];
		},

		/**
		 * Get the language fallback chain for current UI language, including the language itself.
		 *
		 * @return {string[]} Array of language keys, e.g. `['pfl', de', 'en']`
		 */
		getFallbackLanguageChain: function () {
			return [ mw.config.get( 'wgUserLanguage' ) ].concat( mw.language.getFallbackLanguages() );
		},

		/**
		 * Given an object keyed by language names, returns the value corresponding to current UI
		 * language or the best possible fallback.
		 *
		 *     // mw.config.get( 'wgUserLanguage' ) === 'pfl'
		 *     // mw.language.getFallbackLanguages() === ['de', 'en']
		 *     var translations = {
		 *       en: {
		 *         'cat': 'Cat',
		 *         'dog': 'Dog'
		 *       },
		 *       de: {
		 *         'cat': 'Katze',
		 *         'dog': 'Hund'
		 *       }
		 *     };
		 *     mw.messages.set( mw.language.getByLanguageWithFallback( translations ) );
		 *     mw.msg( 'cat' ); // => 'Katze'
		 *     mw.msg( 'dog' ); // => 'Hund'
		 *
		 * @param {Object} object
		 * @return {Mixed} A value from `object` or `undefined` if nothing found.
		 */
		getByLanguageWithFallback: function ( object ) {
			var
				chain = mw.language.getFallbackLanguageChain(),
				len = chain.length,
				i;
			for ( i = 0; i < len; i++ ) {
				if ( object.hasOwnProperty( chain[i] ) ) {
					return object[ chain[i] ];
				}
			}
			return undefined;
		},

		/**
		 * Given an object keyed by language names, returns the amalgamation of values corresponding to
		 * current UI language or the best possible fallback.
		 *
		 * The values are combined using `jQuery.extend`.
		 *
		 *     // mw.config.get( 'wgUserLanguage' ) === 'pfl'
		 *     // mw.language.getFallbackLanguages() === ['de', 'en']
		 *     var translations = {
		 *       en: {
		 *         'cat': 'Cat',
		 *         'dog': 'Dog'
		 *       },
		 *       de: {
		 *         'cat': 'Katze'
		 *       }
		 *     };
		 *     mw.messages.set( mw.language.getExtendByLanguageWithFallback( translations ) );
		 *     mw.msg( 'cat' ); // => 'Katze'
		 *     mw.msg( 'dog' ); // => 'Dog'
		 *
		 * @param {Object} object Object with nested objects as values.
		 * @return {Object}
		 */
		getExtendByLanguageWithFallback: function ( object ) {
			var
				chain = mw.language.getFallbackLanguageChain(),
				len = chain.length,
				ret = {},
				i;
			for ( i = len - 1; i >= 0; i-- ) {
				if ( object.hasOwnProperty( chain[i] ) ) {
					$.extend( ret, object[ chain[i] ] );
				}
			}
			return ret;
		}

	} );

}( mediaWiki, jQuery ) );
