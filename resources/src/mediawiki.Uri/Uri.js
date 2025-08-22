( function () {
	/**
	 * Function that's useful when constructing the URI string -- we frequently encounter the pattern
	 * of having to add something to the URI as we go, but only if it's present, and to include a
	 * character before or after if so.
	 *
	 * @private
	 * @static
	 * @param {string|undefined} pre To prepend
	 * @param {string} val To include
	 * @param {string} post To append
	 * @param {boolean} raw If true, val will not be encoded
	 * @return {string} Result
	 */
	function cat( pre, val, post, raw ) {
		if ( val === undefined || val === null || val === '' ) {
			return '';
		}

		return pre + ( raw ? val : mw.Uri.encode( val ) ) + post;
	}

	/**
	 * Regular expressions to parse many common URIs.
	 *
	 * These are gnarly expressions. For improved readability, they have been moved to a separate
	 * file where they make use of named capture groups. That syntax isn't valid in JavaScript ES5,
	 * so the server-side strips these before delivering to the client.
	 *
	 * @private
	 * @static
	 * @property {Object} parser
	 */
	const parser = {
		strict: require( './strict.regexp.js' ),
		loose: require( './loose.regexp.js' )
	};

	/**
	 * The order here matches the order of captured matches in the `parser` property regexes.
	 *
	 * @private
	 * @static
	 * @property {string[]} properties
	 */
	const properties = [
		'protocol',
		'user',
		'password',
		'host',
		'port',
		'path',
		'query',
		'fragment'
	];

	/**
	 * A factory method to create an {@link mw.Uri} class with a default location to resolve relative URLs
	 * against (including protocol-relative URLs).
	 *
	 * @memberof mw
	 * @param {string|Function} documentLocation A full url, or function returning one.
	 *  If passed a function, the return value may change over time and this will be honoured. (T74334)
	 * @return {mw.Uri} An mw.Uri class constructor
	 */
	mw.UriRelative = function ( documentLocation ) {
		const getDefaultUri = ( function () {
			// Cache
			let href, uri;

			return function () {
				const hrefCur = typeof documentLocation === 'string' ? documentLocation : documentLocation();
				if ( href === hrefCur ) {
					return uri;
				}
				href = hrefCur;
				uri = new Uri( href );
				return uri;
			};
		}() );
		/**
		 * Options for mw.Uri object.
		 *
		 * @typedef {Object} mw.Uri.UriOptions
		 * @property {boolean} [strictMode=false] Trigger strict mode parsing of the url.
		 * @property {boolean} [overrideKeys=false] Whether to let duplicate query parameters
		 *  override each other (`true`) or automagically convert them to an array (`false`).
		 * @property {boolean} [arrayParams=false] Whether to parse array query parameters (e.g.
		 *  `&foo[0]=a&foo[1]=b` or `&foo[]=a&foo[]=b`) or leave them alone. Currently this does not
		 *  handle associative or multi-dimensional arrays, but that may be improved in the future.
		 *  Implies `overrideKeys: true` (query parameters without `[...]` are not parsed as arrays).
		 */

		/**
		 * @classdesc Create and manipulate MediaWiki URIs.
		 *
		 * **DEPRECATED: mw.Uri has been deprecated in MediaWiki 1.43.**
		 * Please use the browser native {@link URL} class instead.
		 * See {@link https://www.mediawiki.org/wiki/Migrating_mw.Uri_to_URL migration guide}.
		 *
		 * Intended to be minimal, but featureful; do not expect full RFC 3986 compliance. The use cases we
		 * have in mind are constructing 'next page' or 'previous page' URLs, detecting whether we need to
		 * use cross-domain proxies for an API, constructing simple URL-based API calls, etc. Parsing here
		 * is regex-based, so may not work on all URIs, but is good enough for most.
		 *
		 * You can modify the properties directly, then use the {@link mw.Uri#toString toString} method to extract the full URI
		 * string again. Example:
		 * ```
		 * var uri = new mw.Uri( 'http://example.com/mysite/mypage.php?quux=2' );
		 *
		 * if ( uri.host == 'example.com' ) {
		 *     uri.host = 'foo.example.com';
		 *     uri.extend( { bar: 1 } );
		 *
		 *     $( 'a#id1' ).attr( 'href', uri );
		 *     // anchor with id 'id1' now links to http://foo.example.com/mysite/mypage.php?bar=1&quux=2
		 *
		 *     $( 'a#id2' ).attr( 'href', uri.clone().extend( { bar: 3, pif: 'paf' } ) );
		 *     // anchor with id 'id2' now links to http://foo.example.com/mysite/mypage.php?bar=3&quux=2&pif=paf
		 * }
		 * ```
		 * Given a URI like
		 * `http://usr:pwd@www.example.com:81/dir/dir.2/index.htm?q1=0&&test1&test2=&test3=value+%28escaped%29&r=1&r=2#top`
		 * the returned object will have the following properties:
		 * ```
		 * protocol  'http'
		 * user      'usr'
		 * password  'pwd'
		 * host      'www.example.com'
		 * port      '81'
		 * path      '/dir/dir.2/index.htm'
		 * query     {
		 *               q1: '0',
		 *               test1: null,
		 *               test2: '',
		 *               test3: 'value (escaped)'
		 *               r: ['1', '2']
		 *           }
		 * fragment  'top'
		 * ```
		 * Note: 'password' is technically not allowed for HTTP URIs, but it is possible with other kinds
		 * of URIs.
		 *
		 * Parsing based on parseUri 1.2.2 (c) Steven Levithan <http://stevenlevithan.com>, MIT License.
		 * <http://stevenlevithan.com/demo/parseuri/js/>
		 *
		 * @deprecated since MediaWiki 1.43.
		 * Please use the browser native {@link URL} class instead.
		 * See {@link https://www.mediawiki.org/wiki/Migrating_mw.Uri_to_URL migration guide}.
		 *
		 * @class
		 * @name mw.Uri
		 *
		 * @constructor
		 * @description Construct a new URI object. Throws error if arguments are illegal/impossible, or
		 * otherwise don't parse.
		 * @param {Object|string} [uri] URI string, or an Object with appropriate properties (especially
		 *  another URI object to clone). Object must have non-blank `protocol`, `host`, and `path`
		 *  properties. If omitted (or set to `undefined`, `null` or empty string), then an object
		 *  will be created for the default `uri` of this constructor (`location.href` for mw.Uri,
		 *  other values for other instances -- see {@link mw.UriRelative} for details).
		 * @param {mw.Uri.UriOptions|boolean} [options] Object with options, or (backwards compatibility) a boolean
		 *  for strictMode
		 * @throws {Error} when the query string or fragment contains an unknown % sequence
		 */
		function Uri( uri, options ) {
			const hasOptions = ( options !== undefined ),
				defaultUri = getDefaultUri();

			options = typeof options === 'object' ? options : { strictMode: !!options };
			options = Object.assign( {
				strictMode: false,
				overrideKeys: false,
				arrayParams: false
			}, options );

			this.arrayParams = options.arrayParams;

			if ( uri !== undefined && uri !== null && uri !== '' ) {
				if ( typeof uri === 'string' ) {
					this.parse( uri, options );
				} else if ( typeof uri === 'object' ) {
					// Copy data over from existing URI object
					for ( const prop in uri ) {
						// Only copy direct properties, not inherited ones
						if ( Object.prototype.hasOwnProperty.call( uri, prop ) ) {
							// Deep copy object properties
							if ( Array.isArray( uri[ prop ] ) || $.isPlainObject( uri[ prop ] ) ) {
								this[ prop ] = $.extend( true, {}, uri[ prop ] );
							} else {
								this[ prop ] = uri[ prop ];
							}
						}
					}
					if ( !this.query ) {
						this.query = {};
					}
				}
			} else if ( hasOptions ) {
				// We didn't get a URI in the constructor, but we got options.
				const hrefCur = typeof documentLocation === 'string' ? documentLocation : documentLocation();
				this.parse( hrefCur, options );
			} else {
				// We didn't get a URI or options in the constructor, use the default instance.
				return defaultUri.clone();
			}

			// protocol-relative URLs
			if ( !this.protocol ) {
				this.protocol = defaultUri.protocol;
			}
			// No host given:
			if ( !this.host ) {
				this.host = defaultUri.host;
				// port ?
				if ( !this.port ) {
					this.port = defaultUri.port;
				}
			}
			if ( this.path && this.path[ 0 ] !== '/' ) {
				// A real relative URL, relative to defaultUri.path. We can't really handle that since we cannot
				// figure out whether the last path component of defaultUri.path is a directory or a file.
				throw new Error( 'Bad constructor arguments' );
			}
			if ( !( this.protocol && this.host && this.path ) ) {
				throw new Error( 'Bad constructor arguments' );
			}
		}

		/**
		 * For example `http` (always present).
		 *
		 * @name mw.Uri.prototype.protocol
		 * @type {string}
		 */

		/**
		 * For example `usr`.
		 *
		 * @name mw.Uri.prototype.user
		 * @type {string|undefined}
		 */
		/**
		 * For example `pwd`.
		 *
		 * @name mw.Uri.prototype.password
		 * @type {string|undefined}
		 */
		/**
		 * For example `www.example.com` (always present).
		 *
		 * @name mw.Uri.prototype.host
		 * @type {string}
		 */
		/**
		 * For example `81`.
		 *
		 * @name mw.Uri.prototype.port
		 * @type {string|undefined}
		 */
		/**
		 * For example `/dir/dir.2/index.htm` (always present).
		 *
		 * @name mw.Uri.prototype.path
		 * @type {string}
		 */
		/**
		 * For example `{ a: '0', b: '', c: 'value' }` (always present).
		 *
		 * @name mw.Uri.prototype.query
		 * @type {Object}
		 */
		/**
		 * For example `top`.
		 *
		 * @name mw.Uri.prototype.fragment
		 * @type {string|undefined}
		 */

		/**
		 * Encode a value for inclusion in a url.
		 *
		 * Standard encodeURIComponent, with extra stuff to make all browsers work similarly and more
		 * compliant with RFC 3986. Similar to rawurlencode from PHP and our JS library
		 * {@link module:mediawiki.util.rawurlencode mw.util.rawurlencode}, except this also replaces spaces with `+`.
		 *
		 * @method
		 * @name mw.Uri.encode
		 * @param {string} s String to encode
		 * @return {string} Encoded string for URI
		 */
		Uri.encode = function ( s ) {
			return encodeURIComponent( s )
				.replace( /!/g, '%21' ).replace( /'/g, '%27' ).replace( /\(/g, '%28' )
				.replace( /\)/g, '%29' ).replace( /\*/g, '%2A' )
				.replace( /%20/g, '+' );
		};

		/**
		 * Decode a url encoded value.
		 *
		 * Reversed {@link mw.Uri.encode encode}. Standard decodeURIComponent, with addition of replacing
		 * `+` with a space.
		 *
		 * @method
		 * @name mw.Uri.decode
		 * @param {string} s String to decode
		 * @return {string} Decoded string
		 * @throws {Error} when the string contains an unknown % sequence
		 */
		Uri.decode = function ( s ) {
			return decodeURIComponent( s.replace( /\+/g, '%20' ) );
		};

		Uri.prototype = /** @lends mw.Uri.prototype */ {

			/**
			 * Parse a string and set our properties accordingly.
			 *
			 * @private
			 * @param {string} str URI, see constructor.
			 * @param {Object} options See constructor.
			 * @throws {Error} when the query string or fragment contains an unknown % sequence
			 */
			parse: function ( str, options ) {
				const uri = this,
					hasOwn = Object.prototype.hasOwnProperty;

				// Apply parser regex and set all properties based on the result
				const matches = parser[ options.strictMode ? 'strict' : 'loose' ].exec( str );
				properties.forEach( ( property, i ) => {
					uri[ property ] = matches[ i + 1 ];
				} );

				// uri.query starts out as the query string; we will parse it into key-val pairs then make
				// that object the "query" property.
				// we overwrite query in uri way to make cloning easier, it can use the same list of properties.
				const q = {};
				// using replace to iterate over a string
				if ( uri.query ) {

					uri.query.replace( /(?:^|&)([^&=]*)(?:(=)([^&]*))?/g, ( match, k, eq, v ) => {
						let arrayKeyMatch, i;
						if ( k ) {
							k = Uri.decode( k );
							v = ( eq === '' || eq === undefined ) ? null : Uri.decode( v );
							arrayKeyMatch = k.match( /^([^[]+)\[(\d*)\]$/ );

							// If arrayParams and this parameter name contains an array index...
							if ( options.arrayParams && arrayKeyMatch ) {
								// Remove the index from parameter name
								k = arrayKeyMatch[ 1 ];

								// Turn the parameter value into an array (throw away anything else)
								if ( !Array.isArray( q[ k ] ) ) {
									q[ k ] = [];
								}

								i = arrayKeyMatch[ 2 ];
								if ( i === '' ) {
									// If no explicit index, append at the end
									i = q[ k ].length;
								}

								q[ k ][ i ] = v;

							// If overrideKeys, always (re)set top level value.
							// If not overrideKeys but this key wasn't set before, then we set it as well.
							// arrayParams implies overrideKeys (no array handling for non-array params).
							} else if ( options.arrayParams || options.overrideKeys || !hasOwn.call( q, k ) ) {
								q[ k ] = v;

							// Use arrays if overrideKeys is false and key was already seen before
							} else {
								// Once before, still a string, turn into an array
								if ( typeof q[ k ] === 'string' ) {
									q[ k ] = [ q[ k ] ];
								}
								// Add to the array
								if ( Array.isArray( q[ k ] ) ) {
									q[ k ].push( v );
								}
							}
						}
					} );
				}
				uri.query = q;

				// Decode uri.fragment, otherwise it gets double-encoded when serializing
				if ( uri.fragment !== undefined ) {
					uri.fragment = Uri.decode( uri.fragment );
				}
			},

			/**
			 * Get user and password section of a URI.
			 *
			 * @return {string}
			 */
			getUserInfo: function () {
				return cat( '', this.user, cat( ':', this.password, '' ) );
			},

			/**
			 * Get host and port section of a URI.
			 *
			 * @return {string}
			 */
			getHostPort: function () {
				return this.host + cat( ':', this.port, '' );
			},

			/**
			 * Get the userInfo, host and port section of the URI.
			 *
			 * In most real-world URLs this is simply the hostname, but the definition of 'authority' section is more general.
			 *
			 * @return {string}
			 */
			getAuthority: function () {
				return cat( '', this.getUserInfo(), '@' ) + this.getHostPort();
			},

			/**
			 * Get the query arguments of the URL, encoded into a string.
			 *
			 * Does not preserve the original order of arguments passed in the URI. Does handle escaping.
			 *
			 * @return {string}
			 */
			getQueryString: function () {
				const args = [],
					arrayParams = this.arrayParams;
				Object.keys( this.query ).forEach( ( key ) => {
					const val = this.query[ key ];
					const k = Uri.encode( key ),
						isArrayParam = Array.isArray( val ),
						vals = isArrayParam ? val : [ val ];
					vals.forEach( ( v, i ) => {
						let ki = k;
						if ( arrayParams && isArrayParam ) {
							ki += Uri.encode( '[' + i + ']' );
						}
						if ( v === null ) {
							args.push( ki );
						} else if ( k === 'title' ) {
							args.push( ki + '=' + mw.util.wikiUrlencode( v ) );
						} else {
							args.push( ki + '=' + Uri.encode( v ) );
						}
					} );
				} );
				return args.join( '&' );
			},

			/**
			 * Get everything after the authority section of the URI.
			 *
			 * @return {string}
			 */
			getRelativePath: function () {
				return this.path + cat( '?', this.getQueryString(), '', true ) + cat( '#', this.fragment, '' );
			},

			/**
			 * Get the entire URI string.
			 *
			 * Note that the output may not be precisely the same as the constructor input,
			 * due to order of query arguments.
			 * Note also that the fragment is not always roundtripped as-is; some characters will
			 * become encoded, including the slash character, which can cause problems with e.g.
			 * mediawiki.router. It is recommended to use the native URL class (via
			 * web2017-polyfills, which loads a polyfill if needed) in contexts where the fragment
			 * is important.
			 *
			 * @return {string} The URI string
			 */
			toString: function () {
				return this.protocol + '://' + this.getAuthority() + this.getRelativePath();
			},

			/**
			 * Clone this URI.
			 *
			 * @return {Object} New URI object with same properties
			 */
			clone: function () {
				return new Uri( this );
			},

			/**
			 * Extend the query section of the URI with new parameters.
			 *
			 * @param {Object} parameters Query parameters to add to ours (or to override ours with) as an
			 *  object
			 * @return {Object} This URI object
			 */
			extend: function ( parameters ) {
				for ( const name in parameters ) {
					const parameter = parameters[ name ];
					if ( parameter !== undefined ) {
						this.query[ name ] = parameter;
					}
				}
				return this;
			}
		};

		return Uri;
	};

	/**
	 * Default to the current browsing location (for relative URLs).
	 *
	 * @ignore
	 * @return {mw.Uri}
	 */
	mw.Uri = mw.UriRelative( () => location.href );

}() );
