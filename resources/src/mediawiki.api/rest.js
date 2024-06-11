( function () {

	/**
	 * @typedef {Object} mw.Rest.Options
	 * @property {Object} [ajax={ url: mw.util.wikiScript( 'rest' ), timeout: 30 * 1000 }] Default
	 *  options for [ajax()]{@link mw.Rest#ajax} calls. Can be overridden by passing `options` to
	 *  the {@link mw.Rest} constructor.
	 */

	/**
	 * @type {mw.Rest.Options}
	 * @private
	 */
	var defaultOptions = {
		ajax: {
			url: mw.util.wikiScript( 'rest' ),
			timeout: 30 * 1000 // 30 seconds
		}
	};

	/**
	 * Lower cases the key names in the provided object.
	 *
	 * @param {Object} headers
	 * @return {Object}
	 * @private
	 */
	function objectKeysToLowerCase( headers ) {
		return Object.keys( headers || {} ).reduce( ( updatedHeaders, key ) => {
			updatedHeaders[ key.toLowerCase() ] = headers[ key ];
			return updatedHeaders;
		}, {} );
	}

	/**
	 * @classdesc Interact with the REST API. mw.Rest is a client library
	 * for the [REST API](https://www.mediawiki.org/wiki/Special:MyLanguage/API:REST_API).
	 * An mw.Rest object represents the REST API of a MediaWiki site.
	 * For the action API, see {@link mw.Api}.
	 *
	 * @example
	 * var api = new mw.Rest();
	 * api.get( '/v1/page/Main_Page/html' )
	 * .then( function ( data ) {
	 *     console.log( data );
	 * } );
	 *
	 * api.post( '/v1/page/Main_Page', {
	 *      token: 'anon_token',
	 *      source: 'Lörem Ipsüm',
	 *      comment: 'tästing',
	 *      title: 'My_Page'
	 * }, {
	 *     'authorization': 'token'
	 * } )
	 * .then( function ( data ) {
	 *     console.log( data );
	 * } );
	 *
	 * @constructor
	 * @description Create an instance of `mw.Rest`.
	 * @param {mw.Rest.Options} [options] See {@link mw.Rest.Options}
	 */
	mw.Rest = function ( options ) {
		var defaults = Object.assign( {}, options );
		defaults.ajax = Object.assign( {}, defaultOptions.ajax, defaults.ajax );

		this.url = defaults.ajax.url;
		delete defaults.ajax.url;

		this.defaults = defaults;
		this.requests = [];
	};

	mw.Rest.prototype = {
		/**
		 * Abort all unfinished requests issued by this Api object.
		 *
		 * @method
		 */
		abort: function () {
			this.requests.forEach( ( request ) => {
				if ( request ) {
					request.abort();
				}
			} );
		},

		/**
		 * Perform REST API get request.
		 *
		 * @method
		 * @param {string} path
		 * @param {Object} query
		 * @param {Object} [headers]
		 * @return {jQuery.Promise}
		 */
		get: function ( path, query, headers ) {
			return this.ajax( path, {
				type: 'GET',
				data: query,
				headers: headers || {}
			} );
		},

		/**
		 * Perform REST API post request.
		 *
		 * Note: only sending application/json is currently supported.
		 *
		 * @method
		 * @param {string} path
		 * @param {Object} [body]
		 * @param {Object} [headers]
		 * @return {jQuery.Promise}
		 */
		post: function ( path, body, headers ) {
			if ( body === undefined ) {
				body = {};
			}

			headers = objectKeysToLowerCase( headers );
			return this.ajax( path, {
				type: 'POST',
				headers: Object.assign( headers, { 'content-type': 'application/json' } ),
				data: JSON.stringify( body )
			} );
		},

		/**
		 * Perform REST API PUT request.
		 *
		 * Note: only sending `application/json` is currently supported.
		 *
		 * @method
		 * @param {string} path
		 * @param {Object} body
		 * @param {Object} [headers]
		 * @return {jQuery.Promise}
		 */
		put: function ( path, body, headers ) {
			headers = objectKeysToLowerCase( headers );
			return this.ajax( path, {
				type: 'PUT',
				headers: Object.assign( headers, { 'content-type': 'application/json' } ),
				data: JSON.stringify( body )
			} );
		},

		/**
		 * Perform REST API DELETE request.
		 *
		 * Note: only sending `application/json` is currently supported.
		 *
		 * @method
		 * @param {string} path
		 * @param {Object} body
		 * @param {Object} [headers]
		 * @return {jQuery.Promise}
		 */
		delete: function ( path, body, headers ) {
			headers = objectKeysToLowerCase( headers );
			return this.ajax( path, {
				type: 'DELETE',
				headers: Object.assign( headers, { 'content-type': 'application/json' } ),
				data: JSON.stringify( body )
			} );
		},

		/**
		 * Perform the API call.
		 *
		 * @method
		 * @param {string} path
		 * @param {Object} [ajaxOptions]
		 * @return {jQuery.Promise} Done: API response data and the jqXHR object.
		 *  Fail: Error code
		 */
		ajax: function ( path, ajaxOptions ) {
			var self = this,
				apiDeferred = $.Deferred(),
				xhr, requestIndex;

			ajaxOptions = Object.assign( {}, this.defaults.ajax, ajaxOptions );
			ajaxOptions.url = this.url + path;

			// Make the AJAX request.
			xhr = $.ajax( ajaxOptions );

			// Save it to make it possible to abort.
			requestIndex = this.requests.length;
			this.requests.push( xhr );
			xhr.always( () => {
				self.requests[ requestIndex ] = null;
			} );

			xhr.then(
				// AJAX success just means "200 OK" response.
				( result, textStatus, jqXHR ) => {
					apiDeferred.resolve( result, jqXHR );
				},
				// If AJAX fails, reject API call with error code 'http'
				// and details in second argument.
				( jqXHR, textStatus, exception ) => {
					apiDeferred.reject( 'http', {
						xhr: jqXHR,
						textStatus: textStatus,
						exception: exception
					} );
				}
			);

			// Return the Promise
			return apiDeferred.promise( { abort: xhr.abort } );
		}
	};
}() );
