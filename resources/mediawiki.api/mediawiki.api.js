( function ( mw, $ ) {

	// We allow people to omit these default parameters from API requests
	// there is very customizable error handling here, on a per-call basis
	// wondering, would it be simpler to make it easy to clone the api object,
	// change error handling, and use that instead?
	var defaultOptions = {

			// Query parameters for API requests
			parameters: {
				action: 'query',
				format: 'json'
			},

			// Ajax options for jQuery.ajax()
			ajax: {
				url: mw.util.wikiScript( 'api' ),

				timeout: 30 * 1000, // 30 seconds

				dataType: 'json'
			}
		},
		tokenCache = {};

	/**
	 * Constructor to create an object to interact with the API of a particular MediaWiki server.
	 * mw.Api objects represent the API of a particular MediaWiki server.
	 *
	 * TODO: Share API objects with exact same config.
	 *
	 *     var api = new mw.Api();
	 *     api.get( {
	 *         action: 'query',
	 *         meta: 'userinfo'
	 *     } ).done ( function ( data ) {
	 *         console.log( data );
	 *     } );
	 *
	 * @class
	 *
	 * @constructor
	 * @param {Object} options See defaultOptions documentation above. Ajax options can also be
	 *  overridden for each individual request to {@link jQuery#ajax} later on.
	 */
	mw.Api = function ( options ) {

		if ( options === undefined ) {
			options = {};
		}

		// Force toString if we got a mw.Uri object
		if ( options.ajax && options.ajax.url !== undefined ) {
			options.ajax.url = String( options.ajax.url );
		}

		options.parameters = $.extend( {}, defaultOptions.parameters, options.parameters );
		options.ajax = $.extend( {}, defaultOptions.ajax, options.ajax );

		this.defaults = options;
	};

	mw.Api.prototype = {

		/**
		 * Normalize the ajax options for compatibility and/or convenience methods.
		 *
		 * @param {Object} [arg] An object contaning one or more of options.ajax.
		 * @return {Object} Normalized ajax options.
		 */
		normalizeAjaxOptions: function ( arg ) {
			// Arg argument is usually empty
			// (before MW 1.20 it was used to pass ok callbacks)
			var opts = arg || {};
			// Options can also be a success callback handler
			if ( typeof arg === 'function' ) {
				opts = { ok: arg };
			}
			return opts;
		},

		/**
		 * Perform API get request
		 *
		 * @param {Object} parameters
		 * @param {Object|Function} [ajaxOptions]
		 * @return {jQuery.Promise}
		 */
		get: function ( parameters, ajaxOptions ) {
			ajaxOptions = this.normalizeAjaxOptions( ajaxOptions );
			ajaxOptions.type = 'GET';
			return this.ajax( parameters, ajaxOptions );
		},

		/**
		 * Perform API post request
		 *
		 * TODO: Post actions for non-local hostnames will need proxy.
		 *
		 * @param {Object} parameters
		 * @param {Object|Function} [ajaxOptions]
		 * @return {jQuery.Promise}
		 */
		post: function ( parameters, ajaxOptions ) {
			ajaxOptions = this.normalizeAjaxOptions( ajaxOptions );
			ajaxOptions.type = 'POST';
			return this.ajax( parameters, ajaxOptions );
		},

		/**
		 * Perform the API call.
		 *
		 * @param {Object} parameters
		 * @param {Object} [ajaxOptions]
		 * @return {jQuery.Promise} Done: API response data. Fail: Error code
		 */
		ajax: function ( parameters, ajaxOptions ) {
			var token,
				apiDeferred = $.Deferred(),
				xhr;

			parameters = $.extend( {}, this.defaults.parameters, parameters );
			ajaxOptions = $.extend( {}, this.defaults.ajax, ajaxOptions );

			// Ensure that token parameter is last (per [[mw:API:Edit#Token]]).
			if ( parameters.token ) {
				token = parameters.token;
				delete parameters.token;
			}
			// Some deployed MediaWiki >= 1.17 forbid periods in URLs, due to an IE XSS bug
			// So let's escape them here. See bug #28235
			// This works because jQuery accepts data as a query string or as an Object
			ajaxOptions.data = $.param( parameters ).replace( /\./g, '%2E' );

			// If we extracted a token parameter, add it back in.
			if ( token ) {
				ajaxOptions.data += '&token=' + encodeURIComponent( token );
			}

			// Backwards compatibility: Before MediaWiki 1.20,
			// callbacks were done with the 'ok' and 'err' property in ajaxOptions.
			if ( ajaxOptions.ok ) {
				apiDeferred.done( ajaxOptions.ok );
				delete ajaxOptions.ok;
			}
			if ( ajaxOptions.err ) {
				apiDeferred.fail( ajaxOptions.err );
				delete ajaxOptions.err;
			}

			// Make the AJAX request
			xhr = $.ajax( ajaxOptions )
				// If AJAX fails, reject API call with error code 'http'
				// and details in second argument.
				.fail( function ( xhr, textStatus, exception ) {
					apiDeferred.reject( 'http', {
						xhr: xhr,
						textStatus: textStatus,
						exception: exception
					} );
				} )
				// AJAX success just means "200 OK" response, also check API error codes
				.done( function ( result ) {
					if ( result === undefined || result === null || result === '' ) {
						apiDeferred.reject( 'ok-but-empty',
							'OK response but empty result (check HTTP headers?)'
						);
					} else if ( result.error ) {
						var code = result.error.code === undefined ? 'unknown' : result.error.code;
						apiDeferred.reject( code, result );
					} else {
						apiDeferred.resolve( result );
					}
				} );

			// Return the Promise
			return apiDeferred.promise( { abort: xhr.abort } ).fail( function ( code, details ) {
				mw.log( 'mw.Api error: ', code, details );
			} );
		},

		/**
		 * Post to API with specified type of token. If we have no token, get one and try to post.
		 * If we have a cached token try using that, and if it fails, blank out the
		 * cached token and start over. For example to change an user option you could do:
		 *
		 *     new mw.Api().postWithToken( 'options', {
		 *         action: 'options',
		 *         optionname: 'gender',
		 *         optionvalue: 'female'
		 *     } );
		 *
		 * @param {string} tokenType The name of the token, like options or edit.
		 * @param {Object} params API parameters
		 * @return {jQuery.Promise} See #post
		 */
		postWithToken: function ( tokenType, params ) {
			var api = this, hasOwn = tokenCache.hasOwnProperty;
			if ( hasOwn.call( tokenCache, tokenType ) && tokenCache[tokenType] !== undefined ) {
				params.token = tokenCache[tokenType];
				return api.post( params ).then(
					null,
					function ( code ) {
						if ( code === 'badtoken' ) {
							// force a new token, clear any old one
							tokenCache[tokenType] = params.token = undefined;
							return api.post( params );
						}
						// Pass the promise forward, so the caller gets error codes
						return this;
					}
				);
			} else {
				return api.getToken( tokenType ).then( function ( token ) {
					tokenCache[tokenType] = params.token = token;
					return api.post( params );
				} );
			}
		},

		/**
		 * Api helper to grab any token.
		 *
		 * @param {string} type Token type.
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {string} return.done.token Received token.
		 */
		getToken: function ( type ) {
			var apiPromise,
				d = $.Deferred();

			apiPromise = this.get( {
					action: 'tokens',
					type: type
				}, {
					// Due to the API assuming we're logged out if we pass the callback-parameter,
					// we have to disable jQuery's callback system, and instead parse JSON string,
					// by setting 'jsonp' to false.
					// TODO: This concern seems genuine but no other module has it. Is it still
					// needed and/or should we pass this by default?
				} )
				.done( function ( data ) {
					// If token type is not available for this user,
					// key '...token' is missing or can contain Boolean false
					if ( data.tokens && data.tokens[type + 'token'] ) {
						d.resolve( data.tokens[type + 'token'] );
					} else {
						d.reject( 'token-missing', data );
					}
				} )
				.fail( d.reject );

			return d.promise( { abort: apiPromise.abort } );
		}
	};

	/**
	 * @static
	 * @property {Array}
	 * List of errors we might receive from the API.
	 * For now, this just documents our expectation that there should be similar messages
	 * available.
	 */
	mw.Api.errors = [
		// occurs when POST aborted
		// jQuery 1.4 can't distinguish abort or lost connection from 200 OK + empty result
		'ok-but-empty',

		// timeout
		'timeout',

		// really a warning, but we treat it like an error
		'duplicate',
		'duplicate-archive',

		// upload succeeded, but no image info.
		// this is probably impossible, but might as well check for it
		'noimageinfo',
		// remote errors, defined in API
		'uploaddisabled',
		'nomodule',
		'mustbeposted',
		'badaccess-groups',
		'stashfailed',
		'missingresult',
		'missingparam',
		'invalid-file-key',
		'copyuploaddisabled',
		'mustbeloggedin',
		'empty-file',
		'file-too-large',
		'filetype-missing',
		'filetype-banned',
		'filetype-banned-type',
		'filename-tooshort',
		'illegal-filename',
		'verification-error',
		'hookaborted',
		'unknown-error',
		'internal-error',
		'overwrite',
		'badtoken',
		'fetchfileerror',
		'fileexists-shared-forbidden',
		'invalidtitle',
		'notloggedin'
	];

	/**
	 * @static
	 * @property {Array}
	 * List of warnings we might receive from the API.
	 * For now, this just documents our expectation that there should be similar messages
	 * available.
	 */
	mw.Api.warnings = [
		'duplicate',
		'exists'
	];

}( mediaWiki, jQuery ) );
