/**
 * User-agent detection
 */
( function ( $ ) {

	/* Private Members */

	/**
	 * @var {Object} profileCache Keyed by userAgent string,
	 * value is the parsed $.client.profile object for that user agent.
	 */
	var profileCache = {};

	/* Public Methods */

	$.client = {

		/**
		 * Get an object containing information about the client.
		 *
		 * @param {Object} nav An object with atleast a 'userAgent' and 'platform' key.
		 * Defaults to the global Navigator object.
		 * @returns {Object} The resulting client object will be in the following format:
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
		profile: function ( nav ) {
			/*jshint boss: true */

			if ( nav === undefined ) {
				nav = window.navigator;
			}
			// Use the cached version if possible
			if ( profileCache[nav.userAgent] === undefined ) {

				var
					versionNumber,

					/* Configuration */

					// Name of browsers or layout engines we don't recognize
					uk = 'unknown',
					// Generic version digit
					x = 'x',
					// Strings found in user agent strings that need to be conformed
					wildUserAgents = ['Opera', 'Navigator', 'Minefield', 'KHTML', 'Chrome', 'PLAYSTATION 3', 'Iceweasel'],
					// Translations for conforming user agent strings
					userAgentTranslations = [
						// Tons of browsers lie about being something they are not
						[/(Firefox|MSIE|KHTML,?\slike\sGecko|Konqueror)/, ''],
						// Chrome lives in the shadow of Safari still
						['Chrome Safari', 'Chrome'],
						// KHTML is the layout engine not the browser - LIES!
						['KHTML', 'Konqueror'],
						// Firefox nightly builds
						['Minefield', 'Firefox'],
						// This helps keep different versions consistent
						['Navigator', 'Netscape'],
						// This prevents version extraction issues, otherwise translation would happen later
						['PLAYSTATION 3', 'PS3']
					],
					// Strings which precede a version number in a user agent string - combined and used as
					// match 1 in version detection
					versionPrefixes = [
						'camino', 'chrome', 'firefox', 'iceweasel', 'netscape', 'netscape6', 'opera', 'version', 'konqueror',
						'lynx', 'msie', 'safari', 'ps3', 'android'
					],
					// Used as matches 2, 3 and 4 in version extraction - 3 is used as actual version number
					versionSuffix = '(\\/|\\;?\\s|)([a-z0-9\\.\\+]*?)(\\;|dev|rel|\\)|\\s|$)',
					// Names of known browsers
					names = [
						'camino', 'chrome', 'firefox', 'iceweasel', 'netscape', 'konqueror', 'lynx', 'msie', 'opera',
						'safari', 'ipod', 'iphone', 'blackberry', 'ps3', 'rekonq', 'android'
					],
					// Tanslations for conforming browser names
					nameTranslations = [],
					// Names of known layout engines
					layouts = ['gecko', 'konqueror', 'msie', 'trident', 'opera', 'webkit'],
					// Translations for conforming layout names
					layoutTranslations = [ ['konqueror', 'khtml'], ['msie', 'trident'], ['opera', 'presto'] ],
					// Names of supported layout engines for version number
					layoutVersions = ['applewebkit', 'gecko', 'trident'],
					// Names of known operating systems
					platforms = ['win', 'wow64', 'mac', 'linux', 'sunos', 'solaris', 'iphone'],
					// Translations for conforming operating system names
					platformTranslations = [ ['sunos', 'solaris'], ['wow64', 'win'] ],

					/* Methods */

					/**
					 * Performs multiple replacements on a string
					 */
					translate = function ( source, translations ) {
						var i;
						for ( i = 0; i < translations.length; i++ ) {
							source = source.replace( translations[i][0], translations[i][1] );
						}
						return source;
					},

					/* Pre-processing */

					ua = nav.userAgent,
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
				if ( name === 'safari' && version > 400 ) {
					version = '2.0';
				}
				// Expose Opera 10's lies about being Opera 9.8
				if ( name === 'opera' && version >= 9.8 ) {
					match = ua.match( /\bversion\/([0-9\.]*)/ );
					if ( match && match[1] ) {
						version = match[1];
					} else {
						version = '10';
					}
				}
				// And Opera 15's lies about being Chrome
				if ( name === 'chrome' && ( match = ua.match( /\bopr\/([0-9\.]*)/ ) ) ) {
					if ( match[1] ) {
						name = 'opera';
						version = match[1];
					}
				}
				// And IE 11's lies about being not being IE
				if ( layout === 'trident' && layoutversion >= 7 && ( match = ua.match( /\brv[ :\/]([0-9\.]*)/ ) ) ) {
					if ( match[1] ) {
						name = 'msie';
						version = match[1];
					}
				}

				versionNumber = parseFloat( version, 10 ) || 0.0;

				/* Caching */

				profileCache[nav.userAgent] = {
					name: name,
					layout: layout,
					layoutVersion: layoutversion,
					platform: platform,
					version: version,
					versionBase: ( version !== x ? Math.floor( versionNumber ).toString() : x ),
					versionNumber: versionNumber
				};
			}
			return profileCache[nav.userAgent];
		},

		/**
		 * Checks the current browser against a support map object.
		 *
		 * A browser map is in the following format:
		 * {
		 *   // Multiple rules with configurable operators
		 *   'msie': [['>=', 7], ['!=', 9]],
		 *    // Match no versions
		 *   'iphone': false,
		 *    // Match any version
		 *   'android': null
		 * }
		 *
		 * It can optionally be split into ltr/rtl sections:
		 * {
		 *   'ltr': {
		 *     'android': null,
		 *     'iphone': false
		 *   },
		 *   'rtl': {
		 *     'android': false,
		 *     // rules are not inherited from ltr
		 *     'iphone': false
		 *   }
		 * }
		 *
		 * @param {Object} map Browser support map
		 * @param {Object} [profile] A client-profile object
		 * @param {boolean} [exactMatchOnly=false] Only return true if the browser is matched, otherwise
		 * returns true if the browser is not found.
		 *
		 * @returns {boolean} The current browser is in the support map
		 */
		test: function ( map, profile, exactMatchOnly ) {
			/*jshint evil: true */

			var conditions, dir, i, op, val;
			profile = $.isPlainObject( profile ) ? profile : $.client.profile();
			if ( map.ltr && map.rtl ) {
				dir = $( 'body' ).is( '.rtl' ) ? 'rtl' : 'ltr';
				map = map[dir];
			}
			// Check over each browser condition to determine if we are running in a compatible client
			if ( typeof map !== 'object' || map[profile.name] === undefined ) {
				// Not found, return true if exactMatchOnly not set, false otherwise
				return !exactMatchOnly;
			}
			conditions = map[profile.name];
			if ( conditions === false ) {
				// Match no versions
				return false;
			}
			if ( conditions === null ) {
				// Match all versions
				return true;
			}
			for ( i = 0; i < conditions.length; i++ ) {
				op = conditions[i][0];
				val = conditions[i][1];
				if ( typeof val === 'string' ) {
					if ( !( eval( 'profile.version' + op + '"' + val + '"' ) ) ) {
						return false;
					}
				} else if ( typeof val === 'number' ) {
					if ( !( eval( 'profile.versionNumber' + op + val ) ) ) {
						return false;
					}
				}
			}

			return true;
		}
	};
}( jQuery ) );
