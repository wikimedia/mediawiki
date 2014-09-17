/**
 * @class mw.Api.plugin.parse
 */
( function ( mw, $ ) {

	$.extend( mw.Api.prototype, {
		/**
		 * Convenience method for 'action=parse'.
		 *
		 * @param {string} wikitext
		 * @return {Promise}
		 * @return {Function} return.then
		 * @return {string} return.then.data Parsed HTML of `wikitext`.
		 */
		parse: function ( wikitext ) {
			var apiPromise = this.get( {
				action: 'parse',
				contentmodel: 'wikitext',
				text: wikitext
			} );

			return apiPromise
				.then( function ( data ) {
					return data.parse.text['*'];
				} );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.parse
	 */

}( mediaWiki, jQuery ) );
