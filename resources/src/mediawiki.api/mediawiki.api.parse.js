/**
 * @class mw.Api.plugin.parse
 */
( function ( mw, $ ) {

	$.extend( mw.Api.prototype, {
		/**
		 * Convenience method for 'action=parse'.
		 *
		 * @param {string} wikitext
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {string} return.done.data Parsed HTML of `wikitext`.
		 */
		parse: function ( wikitext ) {
			var apiPromise = this.get( {
				action: 'parse',
				contentmodel: 'wikitext',
				text: wikitext
			} );

			return apiPromise
				.then( function ( data ) {
					return data.parse.text[ '*' ];
				} )
				.promise( { abort: apiPromise.abort } );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.parse
	 */

}( mediaWiki, jQuery ) );
