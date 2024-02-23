var jar = require( './jar.js' );

/**
 * Set a cookie.
 *
 * @memberof jQueryPlugins
 * @method cookie
 * @param {string} [key] Cookie name or (when getting) omit to return an object with all
 *  current cookie keys and values.
 * @param {string|null} [value] Cookie value to set. If `null`, this method will remove the cookie.
 *  If omited, this method will get and return the current value.
 * @param {mw.cookie.CookieOptions} [options]
 * @return {string|Object} The current value (if getting a cookie), or an internal `document.cookie`
 *  expression (if setting or removing).
 *
 * @example
 * mw.loader.using( 'mediawiki.cookie' ).then( () => {
 *     $.cookie( 'name', 'value', {} );
 * } );
 */
$.cookie = jar.cookie;

/**
 * Remove a cookie by key.
 *
 * @example
 * mw.loader.using( 'mediawiki.cookie' ).then( () => {
 *     $.removeCookie( 'name', {} );
 * } );
 *
 * @memberof jQueryPlugins
 * @method removeCookie
 * @param {string} key
 * @param {mw.cookie.CookieOptions} options
 * @return {boolean} True if the cookie previously existed
 */
$.removeCookie = jar.removeCookie;
