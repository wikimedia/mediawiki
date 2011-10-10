/**
 * Library for simple URI parsing and manipulation.  Requires jQuery.
 *
 * Do not expect full RFC 3986 compliance. Intended to be minimal, but featureful.
 * The use cases we have in mind are constructing 'next page' or 'previous page' URLs,
 * detecting whether we need to use cross-domain proxies for an API, constructing
 * simple URL-based API calls, etc.
 *
 * Intended to compress very well if you use a JS-parsing minifier.
 *
 * Dependencies: mw, jQuery
 *
 * Example:
 *
 *     var uri = new mw.Uri( 'http://foo.com/mysite/mypage.php?quux=2' );
 *
 *     if ( uri.host == 'foo.com' ) {
 *         uri.host = 'www.foo.com';
 *         uri.extend( { bar: 1 } );
 *
 *         $( 'a#id1' ).attr( 'href', uri );
 *         // anchor with id 'id1' now links to http://foo.com/mysite/mypage.php?bar=1&quux=2
 *
 *         $( 'a#id2' ).attr( 'href', uri.clone().extend( { bar: 3, pif: 'paf' } ) );
 *         // anchor with id 'id2' now links to http://foo.com/mysite/mypage.php?bar=3&quux=2&pif=paf
 *     }
 *
 * Parsing here is regex based, so may not work on all URIs, but is good enough for most.
 *
 * Given a URI like
 * 'http://usr:pwd@www.test.com:81/dir/dir.2/index.htm?q1=0&&test1&test2=&test3=value+%28escaped%29&r=1&r=2#top':
 * The returned object will have the following properties:
 *
 *    protocol  'http'
 *    user      'usr'
 *    password  'pwd'
 *    host      'www.test.com'
 *    port      '81'
 *    path      '/dir/dir.2/index.htm'
 *    query     {
 *                  q1: 0,
 *                  test1: null,
 *                  test2: '',
 *                  test3: 'value (escaped)'
 *                  r: [1, 2]
 *              }
 *    fragment  'top'
 *
 * n.b. 'password' is not technically allowed for HTTP URIs, but it is possible with other
 * sorts of URIs.
 * You can modify the properties directly. Then use the toString() method to extract the
 * full URI string again.
 *
 * Parsing based on parseUri 1.2.2 (c) Steven Levithan <stevenlevithan.com> MIT License
 * http://stevenlevithan.com/demo/parseuri/js/
 *
 */

( function( $, mw ) {

	/**
	 * Function that's useful when constructing the URI string -- we frequently encounter the pattern of
	 * having to add something to the URI as we go, but only if it's present, and to include a character before or after if so.
	 * @param {String} to prepend, if value not empty
	 * @param {String} value to include, if not empty
	 * @param {String} to append, if value not empty
	 * @param {Boolean} raw -- if true, do not URI encode
	 * @return {String}
	 */
	function cat( pre, val, post, raw ) {
		if ( val === undefined || val === null || val === '' ) {
			return '';
		} else {
			return pre + ( raw ? val : mw.Uri.encode( val ) ) + post;
		}
	}

	// Regular expressions to parse many common URIs.
	var parser = {
		strict: /^(?:([^:\/?#]+):)?(?:\/\/(?:(?:([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)?((?:[^?#\/]*\/)*[^?#]*)(?:\?([^#]*))?(?:#(.*))?/,
		loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?(?:(?:([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?((?:\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?[^?#\/]*)(?:\?([^#]*))?(?:#(.*))?/
	},

	// The order here matches the order of captured matches in the above parser regexes.
	properties = [
		'protocol',  // http
		'user',      // usr
		'password',  // pwd
		'host',      // www.test.com
		'port',      // 81
		'path',      // /dir/dir.2/index.htm
		'query',     // q1=0&&test1&test2=value (will become { q1: 0, test1: '', test2: 'value' } )
		'fragment'   // top
	];


	/**
	 * We use a factory to inject a document location, for relative URLs, including protocol-relative URLs.
	 * so the library is still testable & purely functional.
	 */
	mw.UriRelative = function( documentLocation ) {

		/**
		 * Constructs URI object. Throws error if arguments are illegal/impossible, or otherwise don't parse.
		 * @constructor
		 * @param {!Object|String} URI string, or an Object with appropriate properties (especially another URI object to clone). Object must have non-blank 'protocol', 'host', and 'path' properties.
		 * @param {Boolean} strict mode (when parsing a string)
		 */
		function Uri( uri, strictMode ) {
			strictMode = !!strictMode;
			if ( uri !== undefined && uri !== null || uri !== '' ) {
				if ( typeof uri === 'string' ) {
					this._parse( uri, strictMode );
				} else if ( typeof uri === 'object' ) {
					var _this = this;
					$.each( properties, function( i, property ) {
						_this[property] = uri[property];
					} );
					if ( this.query === undefined ) {
						this.query = {};
					}
				}
			}

			// protocol-relative URLs
			if ( !this.protocol ) {
				this.protocol = defaultProtocol;
			}

			if ( !( this.protocol && this.host && this.path ) ) {
				throw new Error( 'Bad constructor arguments' );
			}
		}

		/**
		 * Standard encodeURIComponent, with extra stuff to make all browsers work similarly and more compliant with RFC 3986
		 * Similar to rawurlencode from PHP and our JS library mw.util.rawurlencode, but we also replace space with a +
		 * @param {String} string
		 * @return {String} encoded for URI
		 */
		Uri.encode = function( s ) {
			return encodeURIComponent( s )
				.replace( /!/g, '%21').replace( /'/g, '%27').replace( /\(/g, '%28')
				.replace( /\)/g, '%29').replace( /\*/g, '%2A')
				.replace( /%20/g, '+' );
		};

		/**
		 * Standard decodeURIComponent, with '+' to space
		 * @param {String} string encoded for URI
		 * @return {String} decoded string
		 */
		Uri.decode = function( s ) {
			return decodeURIComponent( s ).replace( /\+/g, ' ' );
		};

		Uri.prototype = {

			/**
			 * Parse a string and set our properties accordingly.
			 * @param {String} URI
			 * @param {Boolean} strictness
			 * @return {Boolean} success
			 */
			_parse: function( str, strictMode ) {
				var matches = parser[ strictMode ? 'strict' : 'loose' ].exec( str );
				var uri = this;
				$.each( properties, function( i, property ) {
					uri[ property ] = matches[ i+1 ];
				} );

				// uri.query starts out as the query string; we will parse it into key-val pairs then make
				// that object the "query" property.
				// we overwrite query in uri way to make cloning easier, it can use the same list of properties.
				var q = {};
				// using replace to iterate over a string
				if ( uri.query ) {
					uri.query.replace( /(?:^|&)([^&=]*)(?:(=)([^&]*))?/g, function ($0, $1, $2, $3) {
						if ( $1 ) {
							var k = Uri.decode( $1 );
							var v = ( $2 === '' || $2 === undefined ) ? null : Uri.decode( $3 );
							if ( typeof q[ k ] === 'string' ) {
								q[ k ] = [ q[ k ] ];
							}
							if ( typeof q[ k ] === 'object' ) {
								q[ k ].push( v );
							} else {
								q[ k ] = v;
							}
						}
					} );
				}
				this.query = q;
			},

			/**
			 * Returns user and password portion of a URI.
			 * @return {String}
			 */
			getUserInfo: function() {
				return cat( '', this.user, cat( ':', this.password, '' ) );
			},

			/**
			 * Gets host and port portion of a URI.
			 * @return {String}
			 */
			getHostPort: function() {
				return this.host + cat( ':', this.port, '' );
			},

			/**
			 * Returns the userInfo and host and port portion of the URI.
			 * In most real-world URLs, this is simply the hostname, but it is more general.
			 * @return {String}
			 */
			getAuthority: function() {
				return cat( '', this.getUserInfo(), '@' ) + this.getHostPort();
			},

			/**
			 * Returns the query arguments of the URL, encoded into a string
			 * Does not preserve the order of arguments passed into the URI. Does handle escaping.
			 * @return {String}
			 */
			getQueryString: function() {
				var args = [];
				$.each( this.query, function( key, val ) {
					var k = Uri.encode( key );
					var vals = val === null ? [ null ] : $.makeArray( val );
					$.each( vals, function( i, v ) {
						args.push( k + ( v === null ? '' : '=' + Uri.encode( v ) ) );
					} );
				} );
				return args.join( '&' );
			},

			/**
			 * Returns everything after the authority section of the URI
			 * @return {String}
			 */
			getRelativePath: function() {
				return this.path + cat( '?', this.getQueryString(), '', true ) + cat( '#', this.fragment, '' );
			},

			/**
			 * Gets the entire URI string. May not be precisely the same as input due to order of query arguments.
			 * @return {String} the URI string
			 */
			toString: function() {
				return this.protocol + '://' + this.getAuthority() + this.getRelativePath();
			},

			/**
			 * Clone this URI
			 * @return {Object} new URI object with same properties
			 */
			clone: function() {
				return new Uri( this );
			},

			/**
			 * Extend the query -- supply query parameters to override or add to ours
			 * @param {Object} query parameters in key-val form to override or add
			 * @return {Object} this URI object
			 */
			extend: function( parameters ) {
				$.extend( this.query, parameters );
				return this;
			}
		};

		var defaultProtocol = ( new Uri( documentLocation ) ).protocol;

		return Uri;	
	};

	// inject the current document location, for relative URLs
	mw.Uri = mw.UriRelative( document.location.href );

	

} )( jQuery, mediaWiki );
