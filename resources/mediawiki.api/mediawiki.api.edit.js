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
			var msg = 'MWDeprecationWarning: Use of "ok" and "err" on postWithEditToken is deprecated. Use .done() and .fail() instead.';
			if ( ok ) {
				mw.track( 'mw.deprecate', 'ok' );
				mw.log.warn( msg );
			}
			if ( err ) {
				mw.track( 'mw.deprecate', 'err' );
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
			var msg = 'MWDeprecationWarning: Use of "ok" and "err" on getEditToken is deprecated. Use .done() and .fail() instead.';
			if ( ok ) {
				mw.track( 'mw.deprecate', 'ok' );
				mw.log.warn( msg );
			}
			if ( err ) {
				mw.track( 'mw.deprecate', 'err' );
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
			var msg = 'MWDeprecationWarning: Use of "ok" and "err" on newSection is deprecated. Use .done() and .fail() instead.';
			if ( ok ) {
				mw.track( 'mw.deprecate', 'ok' );
				mw.log.warn( msg );
			}
			if ( err ) {
				mw.track( 'mw.deprecate', 'err' );
				mw.log.warn( msg );
			}
			return this.postWithEditToken( {
				action: 'edit',
				section: 'new',
				format: 'json',
				title: title.toString(),
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
