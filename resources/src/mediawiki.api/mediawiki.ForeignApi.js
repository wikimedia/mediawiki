( function ( mw, $ ) {

	/**
	 * Create an object like mw.Api, but automatically handling everything required to communicate
	 * with another MediaWiki wiki via cross-origin requests (CORS).
	 *
	 * The foreign wiki must be configured to accept requests from the current wiki. See
	 * <https://www.mediawiki.org/wiki/Manual:$wgCrossSiteAJAXdomains> for details.
	 *
	 *     var api = new mw.ForeignApi( { url: 'https://commons.wikimedia.org/w/api.php' } );
	 *     api.get( {
	 *         action: 'query',
	 *         meta: 'userinfo'
	 *     } ).done( function ( data ) {
	 *         console.log( data );
	 *     } );
	 *
	 * Authentication-related MediaWiki extensions may extend this class to ensure that the user
	 * authenticated on the current wiki will be automatically authenticated on the foreign one. These
	 * extension modules should be registered using the ResourceLoaderForeignApiModules hook. See
	 * CentralAuth for a practical example. The general pattern to extend and override the name is:
	 *
	 *     function MyForeignApi() {};
	 *     OO.inheritClass( MyForeignApi, mw.ForeignApi );
	 *     mw.ForeignApi = MyForeignApi;
	 *
	 * @class mw.ForeignApi
	 * @extends mw.Api
	 * @since 1.26
	 *
	 * @constructor
	 * @param {Object} [options]
	 * @param {string|mw.Uri} [options.url] URL pointing to another wiki's `api.php` endpoint. If not
	 *     given or empty, current wiki's `api.php` is assumed and the behavior is identical to
	 *     regular mw.Api.
	 *
	 * @author Bartosz Dziewoński
	 * @author Jon Robson
	 */
	function CoreForeignApi( options ) {
		options = options || {};
		this.apiUrl = String( options.url );

		if ( this.apiUrl ) {
			// Deep-copy of 'options' to avoid clobbering the original object
			options = $.extend( true,
				{},
				options,
				{
					ajax: {
						// Add 'origin' query parameter. Per API documentation, it must also be part of
						// the request URI, and not just request body (even in case of POST requests).
						url: this.apiUrl + ( this.apiUrl.indexOf( '?' ) !== -1 ? '&' : '?' ) +
							'origin=' + encodeURIComponent( this.getOrigin() ),
						xhrFields: {
							// Should the requests to foreign wiki use pre-existing browser's cookies for it?
							// Anons should probably stay anonymous on the foreign wiki, too. Logged-in users should
							// probably stay logged in (although we have no guarantee it's the same user there).
							withCredentials: mw.config.get( 'wgUserName' ) !== null
						}
					},
					parameters: {
						origin: this.getOrigin()
					}
				}
			);
		}

		// Call parent constructor
		CoreForeignApi.parent.call( this, options );
	}

	OO.inheritClass( CoreForeignApi, mw.Api );

	/**
	 * Return the origin to use for API requests, in the required format (protocol, host and port, if
	 * any).
	 *
	 * @protected
	 * @return {string}
	 */
	CoreForeignApi.prototype.getOrigin = function () {
		var origin = window.location.protocol + '//' + window.location.hostname;
		if ( window.location.port ) {
			origin += ':' + window.location.port;
		}
		return origin;
	};

	// Expose
	mw.ForeignApi = CoreForeignApi;

}( mediaWiki, jQuery ) );
