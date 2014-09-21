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
		 * @return {string[]} List of language keys, e.g. `['de', 'en']`
		 */
		getFallbackLanguages: function () {
			return mw.language.getData(
				mw.config.get( 'wgUserLanguage' ),
				'fallbackLanguages'
			) || [];
		},

		/**
		 * Get the language fallback chain for current UI language, including the language itself.
		 *
		 * @return {string[]} List of language keys, e.g. `['pfl', de', 'en']`
		 */
		getFallbackLanguageChain: function () {
			return [ mw.config.get( 'wgUserLanguage' ) ]
				.concat( mw.language.getFallbackLanguages() );
		}

	} );

}( mediaWiki, jQuery ) );
