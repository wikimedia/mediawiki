( function () {

	/**
	 * @class mw.Rest
	 */

	/**
	 * @property {Object} defaultOptions Default options for #ajax calls. Can be overridden by passing
	 *     `options` to mw.Rest constructor.
	 * @property {Object} defaultOptions.ajax Default options for jQuery#ajax.
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
		return Object.keys( headers || {} ).reduce( function ( updatedHeaders, key ) {
			updatedHeaders[ key.toLowerCase() ] = headers[ key ];
			return updatedHeaders;
		}, {} );
	}

	/**
	 * Constructor to create an object to interact with the REST API of a particular MediaWiki server.
	 * mw.Rest objects represent the REST API of a particular MediaWiki server.
	 *
	 *     var api = new mw.Rest();
	 *     api.get( '/v1/page/Main_Page/html' )
	 *     .done( function ( data ) {
	 *         console.log( data );
	 *     } );
	 *
	 *     api.post( '/v1/page/Main_Page', {
	 *          token: 'anon_token',
	 *          source: 'Lörem Ipsüm',
	 *          comment: 'tästing',
	 *          title: 'My_Page'
	 *     }, {
	 *         'authorization': 'token'
	 *     } )
	 *     .done( function ( data ) {
	 *         console.log( data );
	 *     } );
	 *
	 * @constructor
	 * @param {Object} [options] See #defaultOptions documentation above.
	 */
	mw.Rest = function ( options ) {
		var defaults = $.extend( {}, options );
		defaults.ajax = $.extend( {}, defaultOptions.ajax, defaults.ajax );

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
			this.requests.forEach( function ( request ) {
				if ( request ) {
					request.abort();
				}
			} );
		},

		/**
		 * Perform REST API get request
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
		 * @param {Object} body
		 * @param {Object} [headers]
		 * @return {jQuery.Promise}
		 */
		post: function ( path, body, headers ) {
			headers = objectKeysToLowerCase( headers );
			return this.ajax( path, {
				type: 'POST',
				headers: $.extend( headers, { 'content-type': 'application/json' } ),
				data: JSON.stringify( body ),
				dataType: 'json'
			} );
		},

		/**
		 * Perform REST API PUT request.
		 *
		 * Note: only sending application/json is currently supported.
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
				headers: $.extend( headers, { 'content-type': 'application/json' } ),
				data: JSON.stringify( body ),
				dataType: 'json'
			} );
		},

		/**
		 * Perform REST API DELETE request.
		 *
		 * Note: only sending application/json is currently supported.
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
				headers: $.extend( headers, { 'content-type': 'application/json' } ),
				data: JSON.stringify( body ),
				dataType: 'json'
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

			ajaxOptions = $.extend( {}, this.defaults.ajax, ajaxOptions );
			ajaxOptions.url = this.url + path;

			// Make the AJAX request.
			xhr = $.ajax( ajaxOptions );

			// Save it to make it possible to abort.
			requestIndex = this.requests.length;
			this.requests.push( xhr );
			xhr.always( function () {
				self.requests[ requestIndex ] = null;
			} );

			xhr.then(
				// AJAX success just means "200 OK" response.
				function ( result, textStatus, jqXHR ) {
					apiDeferred.resolve( result, jqXHR );
				},
				// If AJAX fails, reject API call with error code 'http'
				// and details in second argument.
				function ( jqXHR, textStatus, exception ) {
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
