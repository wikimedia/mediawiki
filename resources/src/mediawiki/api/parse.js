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
		 * @param {Object} additionalParams Parameters object to set custom settings, e.g.
		 *   redirects, sectionpreview.  prop should not be overridden.
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {string} return.done.data Parsed HTML of `wikitext`.
		 */
		parse: function ( content, additionalParams ) {
			var apiPromise,
				config = $.extend( {
					formatversion: 2,
					action: 'parse',
					contentmodel: 'wikitext'
				}, additionalParams );

			if ( mw.Title && content instanceof mw.Title ) {
				// Parse existing page
				config.page = content.getPrefixedDb();
			} else {
				// Parse wikitext from input
				config.text = String( content );
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
