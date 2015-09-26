/*
 * Language-fallback-chain-related utilities for mediawiki.language.
 */
( function ( mw, $ ) {
	/**
	 * @class mw.language
	 */

	/**
	 * Transform a message to the corresponding qqx message.
	 *
	 * @private
	 * @param {string} key Message key
	 * @param {string} msg Message (usually in English)
	 * @return {string} Message transformed to qqx.
	 */
	function getQqx( key, msg ) {
		var parameters, paramlist, i, max = 0;
		parameters = msg.match( /\$\d+/g );
		if ( parameters ) {
			max = Math.max.apply( Math, $.map( parameters, function ( param ) {
				// remove the leading '$' and convert to a number
				return Number( param.slice( 1 ) );
			} ) );
		}
		if ( max === 0 ) {
			paramlist = '';
		} else {
			paramlist = [];
			for ( i = 1; i <= max; i++ ) {
				paramlist.push( i );
			}
			paramlist = ': $' + paramlist.join( ', $' );
		}
		return '(' + key + paramlist + ')';
	}

	/**
	 * Transform an object of messages to the corresponding qqx messages.
	 *
	 * @private
	 * @param {Object} messages Messages (usually in English) to derive qqx messages from
	 * @return {Object} Object with same keys, but messages transformed to qqx.
	 */
	function transformToQqx( messages ) {
		var result, key;
		result = {};
		for ( key in messages ) {
			if ( messages.hasOwnProperty( key ) ) {
				result[ key ] = getQqx( key, messages[ key ] );
			}
		}
		return result;
	}

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
		},

		/**
		 * Set localized messages based on the current UI language.
		 *
		 * This function is meant to be used from user scripts etc.
		 * If you are able to register a proper ResourceLoader module,
		 * then use it's mechanism to load all required messages instead.
		 *
		 * Messages that were already set won't be overriden. You don't
		 * have to duplicate messages that can be inherited from a fallback
		 * language, the fallback chain is used automatically.
		 *
		 *     @example
		 *
		 *     // Let's assume the wiki changed the 'pagetitle' message to 'MyWiki: $1',
		 *     // and some ResourceLoader module loaded this message:
		 *     mw.messages.set( 'pagetitle', 'MyWiki: $1' );
		 *     // Now add your localizations
		 *     mw.language.addMessages( {
		 *         en: {
		 *             pagetitle: '$1 - {{SITENAME}}',
		 *             questionmark: '?',
		 *             'my-cool-message': 'My cool message!'
		 *         },
		 *         de: {
		 *             pagetitle: '$1 – {{SITENAME}}', // note the en dash
		 *             'my-cool-message': 'Meine heiße Nachricht!'
		 *         }
		 *     } );
		 *     // If the current UI language is 'pfl' (or any other language, that falls back -> 'de' -> 'en'),
		 *     // the result is:
		 *     console.log( mw.messages.get( 'pagetitle' ) ); // 'MyWiki: $1' - original message was kept
		 *     console.log( mw.messages.get( 'questionmark' ) ); // '?' - inherited from en
		 *     console.log( mw.messages.get( 'my-cool-message' ) ); // 'Meine heiße Nachricht!' - inherited from de
		 *
		 * @param {Object} l10n Object with localizations.
		 *  The keys must be language codes (in lower case), the values objects with messages for this language.
		 *  These objects have message names as keys and the messages as values.
		 */
		addMessages: function ( l10n ) {
			var origMsg, chain, i, lang;
			// .get() returns all messages, but by reference.
			// $.extend() is an easy way to get a copy of an object.
			origMsg = $.extend( {}, mw.messages.get() );
			if ( mw.config.get( 'wgUserLanguage' ) === 'qqx' ) {
				mw.messages.set( transformToQqx( l10n.en ) );
			} else {
				chain = mw.language.getFallbackLanguageChain();
				for ( i = chain.length - 1; i >= 0; i-- ) {
					lang = chain[ i ].toLowerCase();
					if ( lang in l10n ) {
						mw.messages.set( l10n[ lang ] );
					}
				}
			}
			mw.messages.set( origMsg );
		}

	} );

}( mediaWiki, jQuery ) );
