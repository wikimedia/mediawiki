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
		 * @param {Function} [ok] Success callback (deprecated)
		 * @param {Function} [err] Error callback (deprecated)
		 * @return {jQuery.Promise} See #post
		 */
		postWithEditToken: function ( params, ok, err ) {
			return this.postWithToken( 'edit', params ).done( ok ).fail( err );
		},

		/**
		 * Api helper to grab an edit token.
		 *
		 * @param {Function} [ok] Success callback
		 * @param {Function} [err] Error callback
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {string} return.done.token Received token.
		 */
		getEditToken: function ( ok, err ) {
			return this.getToken( 'edit' ).done( ok ).fail( err );
		},

		/**
		 * Create a new section of the page.
		 * @see #postWithEditToken
		 * @param {mw.Title|String} title Target page
		 * @param {string} header
		 * @param {string} message wikitext message
		 * @param {Function} [ok] Success handler
		 * @param {Function} [err] Error handler
		 * @return {jQuery.Promise}
		 */
		newSection: function ( title, header, message, ok, err ) {
			return this.postWithEditToken( {
				action: 'edit',
				section: 'new',
				format: 'json',
				title: title.toString(),
				summary: header,
				text: message
			}, ok, err );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.edit
	 */

}( mediaWiki, jQuery ) );
