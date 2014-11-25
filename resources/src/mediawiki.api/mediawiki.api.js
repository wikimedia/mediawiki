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
		// Keyed by ajax url and symbolic name for the individual request
		promises = {};

	// Pre-populate with fake ajax promises to save http requests for tokens
	// we already have on the page via the user.tokens module (bug 34733).
	promises[ defaultOptions.ajax.url ] = {};
	$.each( mw.user.tokens.get(), function ( key, value ) {
		// This requires #getToken to use the same key as user.tokens.
		// Format: token-type + "Token" (eg. editToken, patrolToken, watchToken).
		promises[ defaultOptions.ajax.url ][ key ] = $.Deferred()
			.resolve( value )
			.promise( { abort: function () {} } );
	} );

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
	 * Multiple values for a parameter can be specified using an array (since MW 1.25):
	 *
	 *     var api = new mw.Api();
	 *     api.get( {
	 *         action: 'query',
	 *         meta: [ 'userinfo', 'siteinfo' ] // same effect as 'userinfo|siteinfo'
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

		// Force a string if we got a mw.Uri object
		if ( options.ajax && options.ajax.url !== undefined ) {
			options.ajax.url = String( options.ajax.url );
		}

		options.parameters = $.extend( {}, defaultOptions.parameters, options.parameters );
		options.ajax = $.extend( {}, defaultOptions.ajax, options.ajax );

		this.defaults = options;
	};

	mw.Api.prototype = {

		/**
		 * Perform API get request
		 *
		 * @param {Object} parameters
		 * @param {Object} [ajaxOptions]
		 * @return {jQuery.Promise}
		 */
		get: function ( parameters, ajaxOptions ) {
			ajaxOptions = ajaxOptions || {};
			ajaxOptions.type = 'GET';
			return this.ajax( parameters, ajaxOptions );
		},

		/**
		 * Perform API post request
		 *
		 * TODO: Post actions for non-local hostnames will need proxy.
		 *
		 * @param {Object} parameters
		 * @param {Object} [ajaxOptions]
		 * @return {jQuery.Promise}
		 */
		post: function ( parameters, ajaxOptions ) {
			ajaxOptions = ajaxOptions || {};
			ajaxOptions.type = 'POST';
			return this.ajax( parameters, ajaxOptions );
		},

		/**
		 * Perform the API call.
		 *
		 * @param {Object} parameters
		 * @param {Object} [ajaxOptions]
		 * @return {jQuery.Promise} Done: API response data and the jqXHR object.
		 *  Fail: Error code
		 */
		ajax: function ( parameters, ajaxOptions ) {
			var token,
				apiDeferred = $.Deferred(),
				xhr, key, formData;

			parameters = $.extend( {}, this.defaults.parameters, parameters );
			ajaxOptions = $.extend( {}, this.defaults.ajax, ajaxOptions );

			// Ensure that token parameter is last (per [[mw:API:Edit#Token]]).
			if ( parameters.token ) {
				token = parameters.token;
				delete parameters.token;
			}

			for ( key in parameters ) {
				if ( $.isArray( parameters[key] ) ) {
					parameters[key] = parameters[key].join( '|' );
				}
			}

			// If multipart/form-data has been requested and emulation is possible, emulate it
			if (
				ajaxOptions.type === 'POST' &&
				window.FormData &&
				ajaxOptions.contentType === 'multipart/form-data'
			) {

				formData = new FormData();

				for ( key in parameters ) {
					formData.append( key, parameters[key] );
				}
				// If we extracted a token parameter, add it back in.
				if ( token ) {
					formData.append( 'token', token );
				}

				ajaxOptions.data = formData;

				// Prevent jQuery from mangling our FormData object
				ajaxOptions.processData = false;
				// Prevent jQuery from overriding the Content-Type header
				ajaxOptions.contentType = false;
			} else {
				// Some deployed MediaWiki >= 1.17 forbid periods in URLs, due to an IE XSS bug
				// So let's escape them here. See bug #28235
				// This works because jQuery accepts data as a query string or as an Object
				ajaxOptions.data = $.param( parameters ).replace( /\./g, '%2E' );

				// If we extracted a token parameter, add it back in.
				if ( token ) {
					ajaxOptions.data += '&token=' + encodeURIComponent( token );
				}

				if ( ajaxOptions.contentType === 'multipart/form-data' ) {
					// We were asked to emulate but can't, so drop the Content-Type header, otherwise
					// it'll be wrong and the server will fail to decode the POST body
					delete ajaxOptions.contentType;
				}
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
				.done( function ( result, textStatus, jqXHR ) {
					if ( result === undefined || result === null || result === '' ) {
						apiDeferred.reject( 'ok-but-empty',
							'OK response but empty result (check HTTP headers?)'
						);
					} else if ( result.error ) {
						var code = result.error.code === undefined ? 'unknown' : result.error.code;
						apiDeferred.reject( code, result );
					} else {
						apiDeferred.resolve( result, jqXHR );
					}
				} );

			// Return the Promise
			return apiDeferred.promise( { abort: xhr.abort } ).fail( function ( code, details ) {
				if ( !( code === 'http' && details && details.textStatus === 'abort' ) ) {
					mw.log( 'mw.Api error: ', code, details );
				}
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
		 * @param {Object} [ajaxOptions]
		 * @return {jQuery.Promise} See #post
		 * @since 1.22
		 */
		postWithToken: function ( tokenType, params, ajaxOptions ) {
			var api = this;

			return api.getToken( tokenType, params.assert ).then( function ( token ) {
				params.token = token;
				return api.post( params, ajaxOptions ).then(
					// If no error, return to caller as-is
					null,
					// Error handler
					function ( code ) {
						if ( code === 'badtoken' ) {
							// Clear from cache
							promises[ api.defaults.ajax.url ][ tokenType + 'Token' ] =
								params.token = undefined;

							// Try again, once
							return api.getToken( tokenType, params.assert ).then( function ( token ) {
								params.token = token;
								return api.post( params, ajaxOptions );
							} );
						}

						// Different error, pass on to let caller handle the error code
						return this;
					}
				);
			} );
		},

		/**
		 * Get a token for a certain action from the API.
		 *
		 * The assert parameter is only for internal use by postWithToken.
		 *
		 * @param {string} type Token type
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {string} return.done.token Received token.
		 * @since 1.22
		 */
		getToken: function ( type, assert ) {
			var apiPromise,
				promiseGroup = promises[ this.defaults.ajax.url ],
				d = promiseGroup && promiseGroup[ type + 'Token' ];

			if ( !d ) {
				apiPromise = this.get( { action: 'tokens', type: type, assert: assert } );

				d = apiPromise
					.then( function ( data ) {
						// If token type is not available for this user,
						// key '...token' is either missing or set to boolean false
						if ( data.tokens && data.tokens[type + 'token'] ) {
							return data.tokens[type + 'token'];
						}

						return $.Deferred().reject( 'token-missing', data );
					}, function () {
						// Clear promise. Do not cache errors.
						delete promiseGroup[ type + 'Token' ];

						// Pass on to allow the caller to handle the error
						return this;
					} )
					// Attach abort handler
					.promise( { abort: apiPromise.abort } );

				// Store deferred now so that we can use it again even if it isn't ready yet
				if ( !promiseGroup ) {
					promiseGroup = promises[ this.defaults.ajax.url ] = {};
				}
				promiseGroup[ type + 'Token' ] = d;
			}

			return d;
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
		'notloggedin',

		// Stash-specific errors - expanded
		'stashfailed',
		'stasherror',
		'stashedfilenotfound',
		'stashpathinvalid',
		'stashfilestorage',
		'stashzerolength',
		'stashnotloggedin',
		'stashwrongowner',
		'stashnosuchfilekey'
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
