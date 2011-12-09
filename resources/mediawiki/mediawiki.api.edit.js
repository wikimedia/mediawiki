// library to assist with edits

( function( mw, $, undefined ) {

	// cached token so we don't have to keep fetching new ones for every single post
	var cachedToken = null;

	$.extend( mw.Api.prototype, { 

		/* Post to API with edit token. If we have no token, get one and try to post.
	 	 * If we have a cached token try using that, and if it fails, blank out the
	 	 * cached token and start over.
		 * 
	 	 * @param params API parameters
		 * @param ok callback for success
		 * @param err (optional) error callback
		 */
		postWithEditToken: function( params, ok, err ) {
			var api = this;
			if ( cachedToken === null ) {
				// We don't have a valid cached token, so get a fresh one and try posting.
				// We do not trap any 'badtoken' or 'notoken' errors, because we don't want
				// an infinite loop. If this fresh token is bad, something else is very wrong.
				var useTokenToPost = function( token ) {
					params.token = token; 
					api.post( params, ok, err );
				};
				api.getEditToken( useTokenToPost, err );
			} else {
				// We do have a token, but it might be expired. So if it is 'bad' then
				// start over with a new token.
				params.token = cachedToken;
				var getTokenIfBad = function( code, result ) {
					if ( code === 'badtoken' )  {
						cachedToken = null; // force a new token
						api.postWithEditToken( params, ok, err );
					} else {
						err( code, result );
					}
				};
				api.post( params, { 'ok' : ok, 'err' : getTokenIfBad });
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
		 * @param {Function} received token callback
		 * @param {Function} error callback
		 */
		getEditToken: function( tokenCallback, err ) {
			var api = this;

			var parameters = {			
				'prop': 'info',
				'intoken': 'edit',
				/* we need some kind of dummy page to get a token from. This will return a response 
				   complaining that the page is missing, but we should also get an edit token */
				'titles': 'DummyPageForEditToken'
			};

			var ok = function( data ) {
				var token;
				$.each( data.query.pages, function( i, page ) {
					if ( page['edittoken'] ) {
						token = page['edittoken'];
						return false;
					}
				} );
				if ( token !== undefined ) {
					cachedToken = token;
					tokenCallback( token );
				} else {
					err( 'token-missing', data );
				}
			};

			var ajaxOptions = {
				'ok': ok,
				'err': err,
				// Due to the API assuming we're logged out if we pass the callback-parameter,
				// we have to disable jQuery's callback system, and instead parse JSON string,
				// by setting 'jsonp' to false.
				'jsonp': false
			};

			api.get( parameters, ajaxOptions );
		},

		/**
		 * Create a new section of the page.
		 * @param {mw.Title|String} target page
		 * @param {String} header
		 * @param {String} wikitext message
		 * @param {Function} success handler
	 	 * @param {Function} error handler
		 */
		newSection: function( title, header, message, ok, err ) {
			var params = {
				action: 'edit',
				section: 'new',
				format: 'json',
				title: title.toString(),
				summary: header,
				text: message
			};
			this.postWithEditToken( params, ok, err );
		}

	 } ); // end extend

} )( window.mediaWiki, jQuery );
