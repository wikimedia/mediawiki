/**
 * @typedef {Object} module:mediawiki.cookie~CookieOptions Custom scope for cookie key, must match the way it was set.
 * @property {string} [path] The path attribute of the cookie. Defaults to wgCookiePath.
 * @property {string} [prefix] The prefix of the key. Defaults to wgCookiePrefix.
 * @property {string} [domain] Custom scope for cookie key. The domain attribute of the cookie.
 *  Defaults to wgCookieDomain.
 * @property {boolean} [secure] Whether or not to include the secure attribute. Defaults to false.
 *   (Does **not** use the wgCookieSecure configuration variable)
 * @property {string} [sameSite] The SameSite flag of the cookie ('None' / 'Lax'
 *   / 'Strict', case-insensitive; default is to omit the flag, which results in Lax on
 *   modern browsers). Set to None AND set secure=true if the cookie needs to be visible on
 *   cross-domain requests.
 * @property {boolean} [sameSiteLegacy] Deprecated, ignored.
 * @property {Date|number|null} [expires] Number of days to store the value (when setting).
 *  The expiry date of the cookie, or lifetime in seconds.
 *  If null or 0, then a session cookie is set. Defaults to wgCookieExpiration.
 */
