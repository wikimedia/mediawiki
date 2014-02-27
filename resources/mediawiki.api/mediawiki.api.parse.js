/**
 * @class mw.Api.plugin.parse
 */
( function ( mw, $ ) {

	$.extend( mw.Api.prototype, {
		/**
		 * Convinience method for 'action=parse'.
		 *
		 * @param {string} wikitext
		 * @param {Function} [ok] Success callback (deprecated)
		 * @param {Function} [err] Error callback (deprecated)
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {string} return.done.data Parsed HTML of `wikitext`.
		 */
		parse: function ( wikitext, ok, err ) {
			var d = $.Deferred(),
				apiPromise;

			if ( ok || err ) {
				mw.track( 'mw.deprecate', 'api.cbParam' );
				mw.log.warn( 'Use of mediawiki.api callback params is deprecated. Use the Promise instead.' );
			}
			// Backwards compatibility (< MW 1.20)
			d.done( ok ).fail( err );

			apiPromise = this.get( {
					action: 'parse',
					contentmodel: 'wikitext',
					text: wikitext
				} )
				.done( function ( data ) {
					if ( data.parse && data.parse.text && data.parse.text['*'] ) {
						d.resolve( data.parse.text['*'] );
					}
				} )
				.fail( d.reject );

			return d.promise( { abort: apiPromise.abort } );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.parse
	 */

}( mediaWiki, jQuery ) );
