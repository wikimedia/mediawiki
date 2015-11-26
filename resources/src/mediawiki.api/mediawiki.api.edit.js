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
		 * @param {Object} [ajaxOptions]
		 * @return {jQuery.Promise} See #post
		 */
		postWithEditToken: function ( params, ajaxOptions ) {
			return this.postWithToken( 'edit', params, ajaxOptions );
		},

		/**
		 * API helper to grab an edit token.
		 *
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {string} return.done.token Received token.
		 */
		getEditToken: function () {
			return this.getToken( 'edit' );
		},

		/**
		 * Post a new section to the page.
		 *
		 * @see #postWithEditToken
		 * @param {mw.Title|String} title Target page
		 * @param {string} header
		 * @param {string} message wikitext message
		 * @param {Object} [additionalParams] Additional API parameters, e.g. `{ redirect: true }`
		 * @return {jQuery.Promise}
		 */
		newSection: function ( title, header, message, additionalParams ) {
			return this.postWithEditToken( $.extend( {
				action: 'edit',
				section: 'new',
				title: String( title ),
				summary: header,
				text: message
			}, additionalParams ) );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.edit
	 */

}( mediaWiki, jQuery ) );
