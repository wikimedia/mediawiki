/**
 * @class mw.Api.plugin.edit
 */
( function ( mw, $ ) {

	// Cache token so we don't have to keep fetching new ones for every single request.
	var cachedToken = null;

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
			var useTokenToPost, getTokenIfBad,
				api = this;
			if ( cachedToken === null ) {
				// We don't have a valid cached token, so get a fresh one and try posting.
				// We do not trap any 'badtoken' or 'notoken' errors, because we don't want
				// an infinite loop. If this fresh token is bad, something else is very wrong.
				useTokenToPost = function ( token ) {
					params.token = token;
					api.post( params, ok, err );
				};
				return api.getEditToken( useTokenToPost, err );
			} else {
				// We do have a token, but it might be expired. So if it is 'bad' then
				// start over with a new token.
				params.token = cachedToken;
				getTokenIfBad = function ( code, result ) {
					if ( code === 'badtoken' ) {
						// force a new token, clear any old one
						cachedToken = null;
						api.postWithEditToken( params, ok, err );
					} else {
						err( code, result );
					}
				};
				return api.post( params, { ok : ok, err : getTokenIfBad });
			}
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
			var d = $.Deferred();
			// Backwards compatibility (< MW 1.20)
			d.done( ok );
			d.fail( err );

			this.get( {
					action: 'tokens',
					type: 'edit'
				}, {
					// Due to the API assuming we're logged out if we pass the callback-parameter,
					// we have to disable jQuery's callback system, and instead parse JSON string,
					// by setting 'jsonp' to false.
					// TODO: This concern seems genuine but no other module has it. Is it still
					// needed and/or should we pass this by default?
					jsonp: false
				} )
				.done( function ( data ) {
					var token;
					// If token type is not available for this user,
					// key 'edittoken' is missing or can contain Boolean false
					if ( data.tokens && data.tokens.edittoken ) {
						token = data.tokens.edittoken;
						cachedToken = token;
						d.resolve( token );
					} else {
						d.reject( 'token-missing', data );
					}
				})
				.fail( d.reject );

			return d.promise();
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
