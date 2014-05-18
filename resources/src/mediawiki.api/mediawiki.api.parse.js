/**
 * @class mw.Api.plugin.parse
 */
( function ( mw, $ ) {

	$.extend( mw.Api.prototype, {
		/**
		 * Convenience method for 'action=parse'.
		 *
		 * @param {string} wikitext
		 * @param {Function} [ok] Success callback (deprecated)
		 * @param {Function} [err] Error callback (deprecated)
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {string} return.done.data Parsed HTML of `wikitext`.
		 */
		parse: function ( wikitext, ok, err ) {
			var apiPromise = this.get( {
				action: 'parse',
				contentmodel: 'wikitext',
				text: wikitext
			} );

			// Backwards compatibility (< MW 1.20)
			if ( ok || err ) {
				mw.track( 'mw.deprecate', 'api.cbParam' );
				mw.log.warn( 'Use of mediawiki.api callback params is deprecated. Use the Promise instead.' );
			}

			return apiPromise
				.then( function ( data ) {
					return data.parse.text['*'];
				} )
				.done( ok )
				.fail( err )
				.promise( { abort: apiPromise.abort } );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.parse
	 */

}( mediaWiki, jQuery ) );
