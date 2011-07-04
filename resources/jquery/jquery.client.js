/**
 * User-agent detection
 */
( function( $ ) {

	/* Private Members */

	/**
	 * @var profileCache {Object} Keyed by userAgent string,
	 * value is the parsed $.client.profile object for that user agent.
	 */
	var profileCache = {};

	/* Public Methods */

	$.client = {
	
		/**
		 * Get an object containing information about the client.
		 *
		 * @param nav {Object} An object with atleast a 'userAgent' and 'platform' key.=
		 * Defaults to the global Navigator object.
		 * @return {Object} The resulting client object will be in the following format:
		 *  {
		 *   'name': 'firefox',
		 *   'layout': 'gecko',
		 *   'layoutVersion': 20101026,
		 *   'platform': 'linux'
		 *   'version': '3.5.1',
		 *   'versionBase': '3',
		 *   'versionNumber': 3.5,
		 *  }
		 */
		profile: function( nav ) {
			if ( nav === undefined ) {
				nav = window.navigator;
			}
			// Use the cached version if possible
			if ( profileCache[nav.userAgent] === undefined ) {
	
				/* Configuration */
	
				// Name of browsers or layout engines we don't recognize
				var uk = 'unknown';
				// Generic version digit
				var x = 'x';
				// Strings found in user agent strings that need to be conformed
				var wildUserAgents = [ 'Opera', 'Navigator', 'Minefield', 'KHTML', 'Chrome', 'PLAYSTATION 3'];
				// Translations for conforming user agent strings
				var userAgentTranslations = [
				    // Tons of browsers lie about being something they are not
					[/(Firefox|MSIE|KHTML,\slike\sGecko|Konqueror)/, ''],
					// Chrome lives in the shadow of Safari still
					['Chrome Safari', 'Chrome'],
					// KHTML is the layout engine not the browser - LIES!
					['KHTML', 'Konqueror'],
					// Firefox nightly builds
					['Minefield', 'Firefox'],
					// This helps keep differnt versions consistent
					['Navigator', 'Netscape'],
					// This prevents version extraction issues, otherwise translation would happen later
					['PLAYSTATION 3', 'PS3']
				];
				// Strings which precede a version number in a user agent string - combined and used as match 1 in
				// version detectection
				var versionPrefixes = [
					'camino', 'chrome', 'firefox', 'netscape', 'netscape6', 'opera', 'version', 'konqueror', 'lynx',
					'msie', 'safari', 'ps3'
				];
				// Used as matches 2, 3 and 4 in version extraction - 3 is used as actual version number
				var versionSuffix = '(\\/|\\;?\\s|)([a-z0-9\\.\\+]*?)(\\;|dev|rel|\\)|\\s|$)';
				// Names of known browsers
				var names = [
					'camino', 'chrome', 'firefox', 'netscape', 'konqueror', 'lynx', 'msie', 'opera', 'safari', 'ipod',
					'iphone', 'blackberry', 'ps3'
				];
				// Tanslations for conforming browser names
				var nameTranslations = [];
				// Names of known layout engines
				var layouts = ['gecko', 'konqueror', 'msie', 'opera', 'webkit'];
				// Translations for conforming layout names
				var layoutTranslations = [['konqueror', 'khtml'], ['msie', 'trident'], ['opera', 'presto']];
				// Names of supported layout engines for version number
				var layoutVersions = ['applewebkit', 'gecko'];
				// Names of known operating systems
				var platforms = ['win', 'mac', 'linux', 'sunos', 'solaris', 'iphone'];
				// Translations for conforming operating system names
				var platformTranslations = [['sunos', 'solaris']];
	
				/* Methods */
	
				// Performs multiple replacements on a string
				var translate = function( source, translations ) {
					for ( var i = 0; i < translations.length; i++ ) {
						source = source.replace( translations[i][0], translations[i][1] );
					}
					return source;
				};
	
				/* Pre-processing  */
	
				var	ua = nav.userAgent,
					match,
					name = uk,
					layout = uk,
					layoutversion = uk,
					platform = uk,
					version = x;

				if ( match = new RegExp( '(' + wildUserAgents.join( '|' ) + ')' ).exec( ua ) ) {
					// Takes a userAgent string and translates given text into something we can more easily work with
					ua = translate( ua, userAgentTranslations );
				}
				// Everything will be in lowercase from now on
				ua = ua.toLowerCase();
	
				/* Extraction */
	
				if ( match = new RegExp( '(' + names.join( '|' ) + ')' ).exec( ua ) ) {
					name = translate( match[1], nameTranslations );
				}
				if ( match = new RegExp( '(' + layouts.join( '|' ) + ')' ).exec( ua ) ) {
					layout = translate( match[1], layoutTranslations );
				}
				if ( match = new RegExp( '(' + layoutVersions.join( '|' ) + ')\\\/(\\d+)').exec( ua ) ) {
					layoutversion = parseInt( match[2], 10 );
				}
				if ( match = new RegExp( '(' + platforms.join( '|' ) + ')' ).exec( nav.platform.toLowerCase() ) ) {
					platform = translate( match[1], platformTranslations );
				}
				if ( match = new RegExp( '(' + versionPrefixes.join( '|' ) + ')' + versionSuffix ).exec( ua ) ) {
					version = match[3];
				}
	
				/* Edge Cases -- did I mention about how user agent string lie? */
	
				// Decode Safari's crazy 400+ version numbers
				if ( name.match( /safari/ ) && version > 400 ) {
					version = '2.0';
				}
				// Expose Opera 10's lies about being Opera 9.8
				if ( name === 'opera' && version >= 9.8) {
					version = ua.match( /version\/([0-9\.]*)/i )[1] || 10;
				}
				var versionNumber = parseFloat( version, 10 ) || 0.0;
	
				/* Caching */
	
				profileCache[nav.userAgent] = {
					'name': name,
					'layout': layout,
					'layoutVersion': layoutversion,
					'platform': platform,
					'version': version,
					'versionBase': ( version !== x ? Math.floor( versionNumber ).toString() : x ),
					'versionNumber': versionNumber
				};
			}
			return profileCache[nav.userAgent];
		},
	
		/**
		 * Checks the current browser against a support map object to determine if the browser has been black-listed or
		 * not. If the browser was not configured specifically it is assumed to work. It is assumed that the body
		 * element is classified as either "ltr" or "rtl". If neither is set, "ltr" is assumed.
		 *
		 * A browser map is in the following format:
		 * {
		 *   'ltr': {
		 *     // Multiple rules with configurable operators
		 *     'msie': [['>=', 7], ['!=', 9]],
		 *      // Blocked entirely
		 *     'iphone': false
		 *   },
		 *   'rtl': {
		 *     // Test against a string
		 *     'msie': [['!==', '8.1.2.3']],
		 *     // RTL rules do not fall through to LTR rules, you must explicity set each of them
		 *     'iphone': false
		 *   }
		 * }
		 *
		 * @param map {Object} Browser support map
		 * @param profile {Object} (optional) a client-profile object.
		 *
		 * @return Boolean true if browser known or assumed to be supported, false if blacklisted
		 */
		test: function( map, profile ) {
			profile = $.isPlainObject( profile ) ? profile : $.client.profile();

			var dir = $( 'body' ).is( '.rtl' ) ? 'rtl' : 'ltr';
			// Check over each browser condition to determine if we are running in a compatible client
			if ( typeof map[dir] !== 'object' || typeof map[dir][profile.name] === 'undefined' ) {
				// Unknown, so we assume it's working
				return true;
			}
			var conditions = map[dir][profile.name];
			for ( var i = 0; i < conditions.length; i++ ) {
				var op = conditions[i][0];
				var val = conditions[i][1];
				if ( val === false ) {
					return false;
				} else if ( typeof val == 'string' ) {
					if ( !( eval( 'profile.version' + op + '"' + val + '"' ) ) ) {
						return false;
					}
				} else if ( typeof val == 'number' ) {
					if ( !( eval( 'profile.versionNumber' + op + val ) ) ) {
						return false;
					}
				}
			}
			return true;
		}
	};
} )( jQuery );
