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
			var d = $.Deferred();
			// Backwards compatibility (< MW 1.20)
			d.done( ok );
			d.fail( err );

			this.get( {
					action: 'parse',
					text: wikitext
				} )
				.done( function ( data ) {
					if ( data.parse && data.parse.text && data.parse.text['*'] ) {
						d.resolve( data.parse.text['*'] );
					}
				} )
				.fail( d.reject );

			return d.promise();
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.parse
	 */

}( mediaWiki, jQuery ) );
