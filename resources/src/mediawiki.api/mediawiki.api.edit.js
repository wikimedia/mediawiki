/**
 * @class mw.Api.plugin.edit
 */
( function ( mw, $ ) {

	$.extend( mw.Api.prototype, {

		/**
		 * Post to API with edit token. If we have no token, get one and try to post.
		 * If we have a cached token try using that, and if it fails, blank out the
		 * cached token and start over.
		 *
		 * @param {Object} params API parameters
		 * @return {jQuery.Promise} See #post
		 */
		postWithEditToken: function ( params ) {
			return this.postWithToken( 'edit', params );
		},

		/**
		 * Api helper to grab an edit token.
		 *
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {string} return.done.token Received token.
		 */
		getEditToken: function () {
			return this.getToken( 'edit' );
		},

		/**
		 * Create a new section of the page.
		 * @see #postWithEditToken
		 * @param {mw.Title|String} title Target page
		 * @param {string} header
		 * @param {string} message wikitext message
		 * @return {jQuery.Promise}
		 */
		newSection: function ( title, header, message ) {
			return this.postWithEditToken( {
				action: 'edit',
				section: 'new',
				format: 'json',
				title: String( title ),
				summary: header,
				text: message
			} );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.edit
	 */

}( mediaWiki, jQuery ) );
