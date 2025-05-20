/**
 * Cookie Plugin
 * Based on https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 *
 * Now forked by MediaWiki.
 *
 * @private
 * @class mw.cookie.jar
 */
( function () {

	const pluses = /\+/g;
	let config = null, cookie;

	function raw( s ) {
		return s;
	}

	function decoded( s ) {
		try {
			return unRfc2068( decodeURIComponent( s.replace( pluses, ' ' ) ) );
		} catch ( e ) {
			// If the cookie cannot be decoded this should not throw an error.
			// See T271838.
			return '';
		}
	}

	function unRfc2068( value ) {
		if ( value.startsWith( '"' ) ) {
			// This is a quoted cookie as according to RFC2068, unescape
			value = value.slice( 1, -1 ).replace( /\\"/g, '"' ).replace( /\\\\/g, '\\' );
		}
		return value;
	}

	function fromJSON( value ) {
		return config.json ? JSON.parse( value ) : value;
	}

	/**
	 * Get, set, or remove a cookie.
	 *
	 * @ignore
	 * @param {string} [key] Cookie name or (when getting) omit to return an object with all
	 *  current cookie keys and values.
	 * @param {string|null} [value] Cookie value to set. If `null`, this method will remove the cookie.
	 *  If omited, this method will get and return the current value.
	 * @param {mw.cookie.CookieOptions} [options]
	 * @return {string|Object} The current value (if getting a cookie), or an internal `document.cookie`
	 *  expression (if setting or removing).
	 */
	config = cookie = function ( key, value, options ) {

		// write
		if ( value !== undefined ) {
			options = Object.assign( {}, config.defaults, options );

			if ( value === null ) {
				options.expires = -1;
			}

			if ( typeof options.expires === 'number' ) {
				const days = options.expires, t = options.expires = new Date();
				t.setDate( t.getDate() + days );
			}

			value = config.json ? JSON.stringify( value ) : String( value );

			try {
				return ( document.cookie = [
					encodeURIComponent( key ), '=', config.raw ? value : encodeURIComponent( value ),
					options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
					options.path ? '; path=' + options.path : '',
					options.domain ? '; domain=' + options.domain : '',
					options.secure ? '; secure' : '',
					// PATCH: handle SameSite flag --tgr
					options.sameSite ? '; samesite=' + options.sameSite : ''
				].join( '' ) );
			} catch ( e ) {
				// Fail silently if the document is not allowed to access cookies.
				return '';
			}
		}

		// read
		const decode = config.raw ? raw : decoded;
		let cookies;
		try {
			cookies = document.cookie.split( '; ' );
		} catch ( e ) {
			// Fail silently if the document is not allowed to access cookies.
			cookies = [];
		}
		let result = key ? null : {};
		for ( let i = 0, l = cookies.length; i < l; i++ ) {
			const parts = cookies[ i ].split( '=' );
			const name = decode( parts.shift() );
			const s = decode( parts.join( '=' ) );

			if ( key && key === name ) {
				result = fromJSON( s );
				break;
			}

			if ( !key ) {
				result[ name ] = fromJSON( s );
			}
		}

		return result;
	};

	config.defaults = {};

	/**
	 * Remove a cookie by key.
	 *
	 * @ignore
	 * @param {string} key
	 * @param {mw.cookie.CookieOptions} options
	 * @return {boolean} True if the cookie previously existed
	 */
	function removeCookie( key, options ) {
		if ( cookie( key ) !== null ) {
			cookie( key, null, options );
			return true;
		}
		return false;
	}

	module.exports = {
		cookie,
		removeCookie
	};
}() );
