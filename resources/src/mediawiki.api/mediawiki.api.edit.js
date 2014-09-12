/**
 * @class mw.Api.plugin.edit
 */
( function ( mw, $ ) {

	var msg = 'Use of mediawiki.api callback params is deprecated. Use the Promise instead.';
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
			if ( ok || err ) {
				mw.track( 'mw.deprecate', 'api.cbParam' );
				mw.log.warn( msg );
			}
			return this.postWithToken( 'edit', params ).done( ok ).fail( err );
		},

		/**
		 * Api helper to grab an edit token.
		 *
		 * @param {Function} [ok] Success callback (deprecated)
		 * @param {Function} [err] Error callback (deprecated)
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {string} return.done.token Received token.
		 */
		getEditToken: function ( ok, err ) {
			if ( ok || err ) {
				mw.track( 'mw.deprecate', 'api.cbParam' );
				mw.log.warn( msg );
			}
			return this.getToken( 'edit' ).done( ok ).fail( err );
		},

		/**
		 * Create a new section of the page.
		 * @see #postWithEditToken
		 * @param {mw.Title|String} title Target page
		 * @param {string} header
		 * @param {string} message wikitext message
		 * @param {Function} [ok] Success handler (deprecated)
		 * @param {Function} [err] Error handler (deprecated)
		 * @return {jQuery.Promise}
		 */
		newSection: function ( title, header, message, ok, err ) {
			if ( ok || err ) {
				mw.track( 'mw.deprecate', 'api.cbParam' );
				mw.log.warn( msg );
			}
			return this.postWithEditToken( {
				action: 'edit',
				section: 'new',
				format: 'json',
				title: String( title ),
				summary: header,
				text: message
			} ).done( ok ).fail( err );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.edit
	 */

}( mediaWiki, jQuery ) );
