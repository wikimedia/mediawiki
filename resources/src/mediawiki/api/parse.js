/**
 * @class mw.Api.plugin.parse
 */
( function ( mw, $ ) {

	$.extend( mw.Api.prototype, {
		/**
		 * Convenience method for 'action=parse'.
		 *
		 * @param {string|mw.Title} content Content to parse, either as a wikitext string or
		 *   a mw.Title.
		 * @param {Object} customConfig Config object to set custom settings, e.g. redirects, sectionpreview.
		 *   prop should not be overridden.
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {string} return.done.data Parsed HTML of `wikitext`.
		 */
		parse: function ( content, customConfig ) {
			var config = $.extend( {}, customConfig, {
				formatversion: 2,
				action: 'parse',
				contentmodel: 'wikitext'
			} ), apiPromise;

			if ( $.type( content ) === 'string' ) {
				// Wikitext
				config.text = content;
			} else {
				// mw.Title
				config.page = content.getPrefixedDb();
			}

			apiPromise = this.get( config );

			return apiPromise
				.then( function ( data ) {
					return data.parse.text;
				} )
				.promise( { abort: apiPromise.abort } );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.parse
	 */

}( mediaWiki, jQuery ) );
