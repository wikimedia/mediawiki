( function( mw, $ ) {

	'use strict';

	mw.cookie = {
		set: function( key, value, expire, options ) {
			var config = mw.config.get( [ 'wgCookiePrefix', 'wgCookieDomain',
				'wgCookiePath', 'wgCookieSecure', 'wgCookieExpiration' ] ),
				defaultOptions = {
					prefix: config.wgCookiePrefix,
					domain: config.wgCookieDomain,
					path: config.wgCookiePath,
					secure: config.wgCookieSecure
				},
				seconds;

			options = options || {};

			if ( expire === undefined || expire === null ) {
				expire = 0;
			} else if ( expire === 0 && config.wgCookieExpiration ) {
				expire = config.wgCookieExpiration;
			}

			if ( expire > 0 ) {
				seconds = expire;
				expire = new Date();
				expire.setTime( +expire + seconds );
			}

			options.expires = expire;
			options = $.extend( {}, defaultOptions, options );

			$.cookie( key, value, options );
		},

		get: function( key, prefix, defaultValue ) {
			var value;

			prefix = prefix || mw.config.get( 'wgCookiePrefix' );
			value = $.cookie( prefix + key ) || defaultValue;

			return value;
		}
	};

} )( mediaWiki, jQuery );
