/**
 * To use, load the `mediawiki.cookie` module.
 *
 * @class jQuery.plugin.cookie
 */

var jar = require( './jar.js' );

/**
 * @method cookie
 * @inheritdoc mw.cookie.jar#cookie
 */
$.cookie = jar.cookie;

/**
 * @method removeCookie
 * @inheritdoc mw.cookie.jar#removeCookie
 */
$.removeCookie = jar.removeCookie;

/**
 * @class jQuery
 * @mixins jQuery.plugin.cookie
 */
