/*!
 * @author Derk-Jan Hartman 2014
 * @since 1.24
 */
 ( function ( mw, $ ) {

	/**
	 * @class mw.Setting
	 *
	 * Object that wraps the various ways that we can set and get settings for users.
	 * The order of preference is:
	 *   User preferences, localStorage, cookie, a sitewide config provided value and the constructor provided default value.
	 *
	 * @constructor
	 * @param {string|Object} names Names of the keys that correspond to the preference.
	 * @param {string} [names.preferenceKey] The name of a key in the preferences. Only works for logged in users and does not usually expire unless renamed.
	 * @param {string} [names.localStorageKey] The name of a localStorage Key. LocalStorage values are lossy, but do not have an expiration date.
	 * @param {string} [names.cookieName] The name of a mw.cookie that has this value
	 * @param {string} [names.configKey] The name of mw.config key that can be used as a fallback value
	 * @param {defaultValue} [defaultValue] If given, will used as default value if setting is not present
	 * @param {Object} [options] Object with settings to control behavior of the setting. Migration strategies, cookie timeouts etc.
	 * @param {string} [options.prefix=wgCookiePrefix] The prefix of the key
	 * @param {string} [options.domain=wgCookieDomain] The domain attribute of the cookie
	 * @param {string} [options.path=wgCookiePath] The path attribute of the cookie
	 * @param {boolean} [options.secure=false] Whether or not to include the secure attribute.
	 */
	function Setting( names, defaultValue, options ) {
		this.api = new Api();
		this.defaultValue = defaultValue || undefined;
		this.options = options || {} ;

		if (typeof names === 'string' ) {
			this.preferenceKey = names;
			this.localStorageKey = names;
			this.cookieName = names;
			this.configKey = names;
		} else if ( typeof names === 'object' ) {
			if ( 'preferenceKey' in names ) {
				this.preferenceKey = names.preferenceKey;
			}
			if ( 'localStorageKey' in names ) {
				this.localStorageKey = names.localStorageKey;
			}
			if ( 'cookieName' in names ) {
				this.cookieName = names.cookieName;
			}
			if ( 'configKey' in names ) {
				this.configKey = names.configKey;
				this.defaultValue = mw.config.get( this.configKey );
			}
		}
	}

	var localStorageSupported = ( 'localStorage' in window && localStorage !== null ),
	getPrefix = function( options ) {
		if ( typeof options.prefix !== 'undefined' ) {
			return options.prefix;
		}
		return mw.config.get( 'wgCookiePrefix' );
	}

	/* Public members */
	Setting.prototype = {
		constructor: Setting,

		// TODO, add expire param ?
		set: function ( value ) {
			var apiDeferred = $.Deferred();

			if ( mw.config.get( 'wgUserId' ) !== null && typeof this.preferenceKey !== 'undefined' ) {
				this.api.postWithToken( 'options', {
					'action': 'options',
				} )
				.fail( function ( code ) ) {
				} )
				.done( function ( responseData ) {
				} );
			}

			// Return the Promise
			return apiDeferred.promise( );
		}

		/**
		 * Gets the value of setting.
		 *
		 * @return {jQuery.Promise} Done: the current value
		 */
		get: function ( ) {
			var apiDeferred = $.Deferred();

			if ( mw.config.get( 'wgUserId' ) !== null && typeof this.preferenceKey !== 'undefined' ) {
				apiDeferred.resolve( mw.user.options[this.preferenceKey] );
			} else if ( typeof this.cookieName !== 'undefined' ) {
				apiDeferred.resolve( mw.cookie.get( this.cookieName, this.options.prefix, this.defaultValue ) );
			} else if ( typeof this.configKey !== 'undefined' ) {
				apiDeferred.resolve( mw.config.get( this.configKey ) );
			} else {
				apiDeferred.resolve( this.defaultValue );
			}
			// Return the Promise
			return apiDeferred.promise();
		}
	}


	mw.user.Setting = Setting;

} ( mediaWiki, jQuery ) );
