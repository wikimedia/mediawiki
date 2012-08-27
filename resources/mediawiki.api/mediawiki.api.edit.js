/**
 * Additional mw.Api methods to assist with API calls related to editing wiki pages.
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
		 * @param params {Object} API parameters
		 * @param ok {Function} callback for success
		 * @param err {Function} [optional] error callback
		 * @return {jqXHR}
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
		 * Api helper to grab an edit token
		 *
		 * token callback has signature ( String token )
		 * error callback has signature ( String code, Object results, XmlHttpRequest xhr, Exception exception )
		 * Note that xhr and exception are only available for 'http_*' errors
		 *  code may be any http_* error code (see mw.Api), or 'token_missing'
		 *
		 * @param tokenCallback {Function} received token callback
		 * @param err {Function} error callback
		 * @return {jqXHR}
		 */
		getEditToken: function ( tokenCallback, err ) {
			var parameters = {
					action: 'tokens',
					type: 'edit'
				},
				ok = function ( data ) {
					var token;
					// If token type is not available for this user,
					// key 'edittoken' is missing or can contain Boolean false
					if ( data.tokens && data.tokens.edittoken ) {
						token = data.tokens.edittoken;
						cachedToken = token;
						tokenCallback( token );
					} else {
						err( 'token-missing', data );
					}
				},
				ajaxOptions = {
					ok: ok,
					err: err,
					// Due to the API assuming we're logged out if we pass the callback-parameter,
					// we have to disable jQuery's callback system, and instead parse JSON string,
					// by setting 'jsonp' to false.
					jsonp: false
				};

			return this.get( parameters, ajaxOptions );
		},

		/**
		 * Create a new section of the page.
		 * @param title {mw.Title|String} target page
		 * @param header {String}
		 * @param message {String} wikitext message
		 * @param ok {Function} success handler
		 * @param err {Function} error handler
		 * @return {jqXHR}
		 */
		newSection: function ( title, header, message, ok, err ) {
			var params = {
				action: 'edit',
				section: 'new',
				format: 'json',
				title: title.toString(),
				summary: header,
				text: message
			};
			return this.postWithEditToken( params, ok, err );
		}

	 } );

}( mediaWiki, jQuery ) );
