( function ( mw, $ ) {

	/**
	 * Create an object like mw.Api, but automatically handling everything required to communicate
	 * with another MediaWiki wiki via cross-origin requests (CORS).
	 *
	 * The foreign wiki must be configured to accept requests from the current wiki. See
	 * <https://www.mediawiki.org/wiki/Manual:$wgCrossSiteAJAXdomains> for details.
	 *
	 *     var api = new mw.ForeignApi( 'https://commons.wikimedia.org/w/api.php' );
	 *     api.get( {
	 *         action: 'query',
	 *         meta: 'userinfo'
	 *     } ).done( function ( data ) {
	 *         console.log( data );
	 *     } );
	 *
	 * To ensure that the user at the foreign wiki is logged in, pass the `assert: 'user'` parameter
	 * to #get/#post (since MW 1.23): if they are not, the API request will fail. (Note that this
	 * doesn't guarantee that it's the same user.)
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
	 * @param {string|mw.Uri} url URL pointing to another wiki's `api.php` endpoint.
	 * @param {Object} [options] See mw.Api.
	 *
	 * @author Bartosz Dziewoński
	 * @author Jon Robson
	 */
	function CoreForeignApi( url, options ) {
		if ( !url || $.isPlainObject( url ) ) {
			throw new Error( 'mw.ForeignApi() requires a `url` parameter' );
		}

		this.apiUrl = String( url );

		options = $.extend( /*deep=*/ true,
			{
				ajax: {
					url: this.apiUrl,
					xhrFields: {
						withCredentials: true
					}
				},
				parameters: {
					// Add 'origin' query parameter to all requests.
					origin: this.getOrigin()
				}
			},
			options
		);

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
		var origin = location.protocol + '//' + location.hostname;
		if ( location.port ) {
			origin += ':' + location.port;
		}
		return origin;
	};

	/**
	 * @inheritdoc
	 */
	CoreForeignApi.prototype.ajax = function ( parameters, ajaxOptions ) {
		var url, origin, newAjaxOptions;

		// 'origin' query parameter must be part of the request URI, and not just POST request body
		if ( ajaxOptions.type === 'POST' ) {
			url = ( ajaxOptions && ajaxOptions.url ) || this.defaults.ajax.url;
			origin = ( parameters && parameters.origin ) || this.defaults.parameters.origin;
			url += ( url.indexOf( '?' ) !== -1 ? '&' : '?' ) +
				'origin=' + encodeURIComponent( origin );
			newAjaxOptions = $.extend( {}, ajaxOptions, { url: url } );
		} else {
			newAjaxOptions = ajaxOptions;
		}

		return CoreForeignApi.parent.prototype.ajax.call( this, parameters, newAjaxOptions );
	};

	// Expose
	mw.ForeignApi = CoreForeignApi;

}( mediaWiki, jQuery ) );
