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
(function () {

	var pluses = /\+/g;
	var config, cookie;

	function raw(s) {
		return s;
	}

	function decoded(s) {
		try {
			return unRfc2068(decodeURIComponent(s.replace(pluses, ' ')));
		} catch(e) {
			// If the cookie cannot be decoded this should not throw an error.
			// See T271838.
			return '';
		}
	}

	function unRfc2068(value) {
		if (value.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape
			value = value.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}
		return value;
	}

	function fromJSON(value) {
		return config.json ? JSON.parse(value) : value;
	}

	/**
	 * Get, set, or remove a cookie.
	 *
	 * @method cookie
	 * @param {string} [key] Cookie name or (when getting) omit to return an object with all
	 *  current cookie keys and values.
	 * @param {string|null} [value] Cookie value to set. If `null`, this method will remove the cookie.
	 *  If omited, this method will get and return the current value.
	 * @param {Object} [options]
	 * @param {string} [options.path] Custom scope for cookie key.
	 * @param {string} [options.domain] Custom scope for cookie key.
	 * @param {boolean} [options.secure] Custom scope for cookie key.
	 * @param {string} [options.sameSite] Custom scope for cookie key.
	 * @param {number} [options.expires] Number of days to store the value (when setting)
	 * @return {string|Object} The current value (if getting a cookie), or an internal `document.cookie`
	 *  expression (if setting or removing).
	 */
	config = cookie = function (key, value, options) {

		// write
		if (value !== undefined) {
			options = Object.assign({}, config.defaults, options);

			if (value === null) {
				options.expires = -1;
			}

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setDate(t.getDate() + days);
			}

			value = config.json ? JSON.stringify(value) : String(value);

			return (document.cookie = [
				encodeURIComponent(key), '=', config.raw ? value : encodeURIComponent(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : '',
				// PATCH: handle SameSite flag --tgr
				options.sameSite ? '; samesite=' + options.sameSite : ''
			].join(''));
		}

		// read
		var decode = config.raw ? raw : decoded;
		var cookies = document.cookie.split('; ');
		var result = key ? null : {};
		for (var i = 0, l = cookies.length; i < l; i++) {
			var parts = cookies[i].split('=');
			var name = decode(parts.shift());
			var s = decode(parts.join('='));

			if (key && key === name) {
				result = fromJSON(s);
				break;
			}

			if (!key) {
				result[name] = fromJSON(s);
			}
		}

		return result;
	};

	config.defaults = {};

	/**
	 * Remove a cookie by key
	 *
	 * @param {string} key
	 * @param {Object} [options] Custom scope for cookie key, must match the way it was set.
	 * @param {string} [options.path]
	 * @param {string} [options.domain]
	 * @param {boolean} [options.secure]
	 * @param {string} [options.sameSite]
	 * @return {boolean} True if the cookie previously existed
	 */
	function removeCookie(key, options) {
		if (cookie(key) !== null) {
			cookie(key, null, options);
			return true;
		}
		return false;
	}

	module.exports = {
		cookie: cookie,
		removeCookie: removeCookie
	};
}());
