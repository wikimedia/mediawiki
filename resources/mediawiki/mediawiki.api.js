/* mw.Api objects represent the API of a particular MediaWiki server. */	

( function( mw, $j, undefined ) {
	
	/**
	 * Represents the API of a particular MediaWiki server.
	 *
	 * Required options: 
	 *   url - complete URL to API endpoint. Usually equivalent to wgServer + wgScriptPath + '/api.php'
	 *
	 * Other options:
	 *   can override the parameter defaults and ajax default options.
	 *	XXX document!
	 *  
	 * ajax options can also be overriden on every get() or post()
	 * 
	 * @param options {Mixed} can take many options, but must include at minimum the API url.
	 */
	mw.Api = function( options ) {

		// make sure we at least have a URL endpoint for the API
		if ( options.url === undefined ) {
			throw new Error( 'Configuration error - needs url property' );
		}

		this.url = options.url;

		/* We allow people to omit these default parameters from API requests */
		// there is very customizable error handling here, on a per-call basis
		// wondering, would it be simpler to make it easy to clone the api object, change error handling, and use that instead?
		this.defaults = {
			parameters: {
				action: 'query',
				format: 'json'
			},

			ajax: {
				// force toString if we got a mw.Uri object
				url: new String( this.url ),  

				/* default function for success and no API error */
				ok: function() {},

				// caller can supply handlers for http transport error or api errors
				err: function( code, result ) {
					mw.log( "mw.Api error: " + code, 'debug' );
				},

				timeout: 30000, /* 30 seconds */

				dataType: 'json'

			}
		};


		if ( options.parameters ) {
			$j.extend( this.defaults.parameters, options.parameters );
		}

		if ( options.ajax ) { 
			$j.extend( this.defaults.ajax, options.ajax );
		}
	};

	mw.Api.prototype = {

		/**
		 * For api queries, in simple cases the caller just passes a success callback.
		 * In complex cases they pass an object with a success property as callback and probably other options.
		 * Normalize the argument so that it's always the latter case.
		 * 
		 * @param {Object|Function} ajax properties, or just a success function
		 * @return Function
		 */
		normalizeAjaxOptions: function( arg ) {
			if ( typeof arg === 'function' ) {
				var ok = arg;
				arg = { 'ok': ok };
			}
			if (! arg.ok ) {
				throw Error( "ajax options must include ok callback" );
			}
			return arg;
		},

		/**
		 * Perform API get request
		 *
		 * @param {Object} request parameters 
		 * @param {Object|Function} ajax properties, or just a success function
		 */	
		get: function( parameters, ajaxOptions ) {
			ajaxOptions = this.normalizeAjaxOptions( ajaxOptions );
			ajaxOptions.type = 'GET';
			this.ajax( parameters, ajaxOptions );
		},

		/**
		 * Perform API post request
		 * TODO post actions for nonlocal will need proxy 
		 * 
		 * @param {Object} request parameters 
		 * @param {Object|Function} ajax properties, or just a success function
		 */
		post: function( parameters, ajaxOptions ) {
			ajaxOptions = this.normalizeAjaxOptions( ajaxOptions );
			ajaxOptions.type = 'POST';
			this.ajax( parameters, ajaxOptions );
		},

		/**
		 * Perform the API call. 
		 * 
		 * @param {Object} request parameters 
		 * @param {Object} ajax properties
		 */
		ajax: function( parameters, ajaxOptions ) {
			parameters = $j.extend( {}, this.defaults.parameters, parameters );
			ajaxOptions = $j.extend( {}, this.defaults.ajax, ajaxOptions );

			// Some deployed MediaWiki >= 1.17 forbid periods in URLs, due to an IE XSS bug
			// So let's escape them here. See bug #28235
			// This works because jQuery accepts data as a query string or as an Object
			ajaxOptions.data = $j.param( parameters ).replace( /\./g, '%2E' );
		
			ajaxOptions.error = function( xhr, textStatus, exception ) {
				ajaxOptions.err( 'http', { xhr: xhr, textStatus: textStatus, exception: exception } );
			};

			
			/* success just means 200 OK; also check for output and API errors */
			ajaxOptions.success = function( result ) {
				if ( result === undefined || result === null || result === '' ) {
					ajaxOptions.err( "ok-but-empty", "OK response but empty result (check HTTP headers?)" );
				} else if ( result.error ) {
					var code = result.error.code === undefined ? 'unknown' : result.error.code;
					ajaxOptions.err( code, result );
				} else { 
					ajaxOptions.ok( result );
				}
			};

			$j.ajax( ajaxOptions );

		}

	};

	/**
	 * This is a list of errors we might receive from the API.
	 * For now, this just documents our expectation that there should be similar messages
	 * available.
	 */
	mw.Api.errors = [
		/* occurs when POST aborted - jQuery 1.4 can't distinguish abort or lost connection from 200 OK + empty result */
		'ok-but-empty',

		// timeout
		'timeout',

		/* really a warning, but we treat it like an error */
		'duplicate',
		'duplicate-archive',

		/* upload succeeded, but no image info. 
		   this is probably impossible, but might as well check for it */
		'noimageinfo',

		/* remote errors, defined in API */
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
		'filename-tooshort',
		'illegal-filename',
		'verification-error',
		'hookaborted',
		'unknown-error',
		'internal-error',
		'overwrite',
		'badtoken',
		'fetchfileerror',
		'fileexists-shared-forbidden'
	];

	/**
	 * This is a list of warnings we might receive from the API.
	 * For now, this just documents our expectation that there should be similar messages
	 * available.
	 */

	mw.Api.warnings = [
		'duplicate',
		'exists'
	];

}) ( window.mediaWiki, jQuery );
