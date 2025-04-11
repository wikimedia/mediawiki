module.exports = ( function () {

	/**
	 * @classdesc Interact with the API of another MediaWiki site. mw.Foreign API creates
	 * an object like {@link mw.Api}, but automatically handle everything required to communicate
	 * with another MediaWiki wiki via cross-origin requests (CORS).
	 *
	 * The foreign wiki must be configured to accept requests from the current wiki.
	 * For details, see [$wgCrossSiteAJAXdomains](https://www.mediawiki.org/wiki/Manual:$wgCrossSiteAJAXdomains)
	 * and [$wgRestAllowCrossOriginCookieAuth](https://www.mediawiki.org/wiki/Manual:$wgRestAllowCrossOriginCookieAuth).
	 * ```
	 * const api = new mw.ForeignApi( 'https://commons.wikimedia.org/w/api.php' );
	 * api.get( {
	 *     action: 'query',
	 *     meta: 'userinfo'
	 * } ).done( function ( data ) {
	 *     console.log( data );
	 * } );
	 * ```
	 *
	 * To ensure that the user at the foreign wiki is logged in, pass the `assert: 'user'` parameter
	 * to {@link mw.ForeignApi#get get()}/{@link mw.ForeignApi#post post()} (since MW 1.23), otherwise
	 * the API request will fail. (Note that this doesn't guarantee that it's the same user. To assert
	 * that the user at the foreign wiki has a specific username, pass the `assertuser` parameter with
	 * the desired username.)
	 *
	 * Authentication-related MediaWiki extensions may extend this class to ensure that the user
	 * authenticated on the current wiki will be automatically authenticated on the foreign one. These
	 * extension modules should be registered using the ResourceLoaderForeignApiModules hook. See
	 * CentralAuth for a practical example. The general pattern to extend and override the name is:
	 * ```
	 * function MyForeignApi() {};
	 * OO.inheritClass( MyForeignApi, mw.ForeignApi );
	 * mw.ForeignApi = MyForeignApi;
	 * ```
	 *
	 * @class mw.ForeignApi
	 * @extends mw.Api
	 * @since 1.26
	 *
	 * @constructor
	 * @description Create an instance of `mw.ForeignApi`.
	 * @param {string} url URL pointing to another wiki's `api.php` endpoint.
	 * @param {mw.Api.Options} [options] Also accepts all the options from {@link mw.Api.Options}.
	 * @param {boolean} [options.anonymous=false] Perform all requests anonymously. Use this option if
	 *     the target wiki may otherwise not accept cross-origin requests, or if you don't need to
	 *     perform write actions or read restricted information and want to avoid the overhead.
	 *
	 * @author Bartosz Dziewoński
	 * @author Jon Robson
	 */
	function CoreForeignApi( url, options ) {
		if ( !url || $.isPlainObject( url ) ) {
			throw new Error( 'mw.ForeignApi() requires a `url` parameter' );
		}

		this.apiUrl = String( url );
		this.anonymous = options && options.anonymous;

		options = $.extend( /* deep= */ true,
			{
				ajax: {
					url: this.apiUrl,
					xhrFields: {
						withCredentials: !this.anonymous
					}
				},
				parameters: {
					origin: this.getOrigin()
				}
			},
			options
		);

		// Call parent constructor
		CoreForeignApi.super.call( this, options );
	}

	OO.inheritClass( CoreForeignApi, mw.Api );

	/**
	 * Return the origin to use for API requests, in the required format (protocol, host and port, if
	 * any).
	 *
	 * @memberof mw.ForeignApi.prototype
	 * @protected
	 * @return {string|undefined}
	 */
	CoreForeignApi.prototype.getOrigin = function () {
		if ( this.anonymous ) {
			return '*';
		}

		const origin = location.origin;
		const apiOrigin = new URL( this.apiUrl, location.origin ).origin;

		if ( origin === apiOrigin ) {
			// requests are not cross-origin, omit parameter
			return undefined;
		}

		return origin;
	};

	/**
	 * @inheritdoc
	 */
	CoreForeignApi.prototype.ajax = function ( parameters, ajaxOptions ) {
		let newAjaxOptions;

		// 'origin' query parameter must be part of the request URI, and not just POST request body
		if ( ajaxOptions.type === 'POST' ) {
			let url = ( ajaxOptions && ajaxOptions.url ) || this.defaults.ajax.url;
			const origin = ( parameters && parameters.origin ) || this.defaults.parameters.origin;
			if ( origin !== undefined ) {
				url += ( url.includes( '?' ) ? '&' : '?' ) +
					'origin=' + encodeURIComponent( origin );
			}
			newAjaxOptions = Object.assign( {}, ajaxOptions, { url: url } );
		} else {
			newAjaxOptions = ajaxOptions;
		}

		return CoreForeignApi.super.prototype.ajax.call( this, parameters, newAjaxOptions );
	};

	return CoreForeignApi;
}() );
