( function () {

	Object.assign( mw.Api.prototype, /** @lends mw.Api.prototype */ {
		/**
		 * Convenience method for 'action=parse'.
		 *
		 * @param {string|mw.Title} content Content to parse, either as a wikitext string or
		 *   a mw.Title.
		 * @param {Object} additionalParams Parameters object to set custom settings, e.g.
		 *   `redirects`, `sectionpreview`. `prop` should not be overridden.
		 * @return {jQuery.Promise<string>} Promise that resolves with the parsed HTML of `wikitext`
		 */
		parse: function ( content, additionalParams ) {
			var apiPromise,
				config = Object.assign( {
					formatversion: 2,
					action: 'parse',
					// Minimize the JSON we get back, there is no way to access anything else anyway
					prop: 'text',
					contentmodel: 'wikitext'
				}, additionalParams );

			if ( mw.Title && content instanceof mw.Title ) {
				// Parse existing page
				config.page = content.getPrefixedDb();
				apiPromise = this.get( config );
			} else {
				// Parse wikitext from input
				config.text = String( content );
				apiPromise = this.post( config );
			}

			return apiPromise
				.then( ( data ) => data.parse.text )
				.promise( { abort: apiPromise.abort } );
		}
	} );

}() );
