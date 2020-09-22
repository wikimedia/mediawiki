/*!
 * jQuery Client 3.0.0
 * https://gerrit.wikimedia.org/g/jquery-client/
 *
 * Copyright 2010-2020 wikimedia/jquery-client maintainers and other contributors.
 * Released under the MIT license
 * https://jquery-client.mit-license.org
 */

/**
 * User-agent detection
 *
 * @class jQuery.client
 * @singleton
 */
( function () {

	/**
	 * @private
	 * @property {Object} profileCache Keyed by userAgent string,
	 * value is the parsed $.client.profile object for that user agent.
	 */
	var profileCache = {};

	$.client = {

		/**
		 * Get an object containing information about the client.
		 *
		 * The resulting client object will be in the following format:
		 *
		 *     {
		 *         'name': 'firefox',
		 *         'layout': 'gecko',
		 *         'layoutVersion': 20101026,
		 *         'platform': 'linux'
		 *         'version': '3.5.1',
		 *         'versionBase': '3',
		 *         'versionNumber': 3.5,
		 *     }
		 *
		 * Example:
		 *
		 *     if ( $.client.profile().layout == 'gecko' ) {
		 *         // This will only run in Gecko browsers, such as Mozilla Firefox.
		 *     }
		 *
		 *     var profile = $.client.profile();
		 *     if ( profile.layout == 'gecko' && profile.platform == 'linux' ) {
		 *         // This will only run in Gecko browsers on Linux.
		 *     }
		 *
		 * Recognised browser names:
		 *
		 * - `android` (legacy Android browser, prior to Chrome Mobile)
		 * - `chrome` (includes Chrome Mobile, Microsoft Edge, Opera, and others)
		 * - `crios` (Chrome on iOS, which uses Mobile Safari)
		 * - `edge` (legacy Microsoft Edge, which uses EdgeHTML)
		 * - `firefox` (includes Firefox Mobile, Iceweasel, and others)
		 * - `fxios` (Firefox on iOS, which uses Mobile Safari)
		 * - `konqueror`
		 * - `msie`
		 * - `opera` (legacy Opera, which uses Presto)
		 * - `rekonq`
		 * - `safari` (including Mobile Safari)
		 * - `silk`
		 *
		 * Recognised layout engines:
		 *
		 * - `edge` (EdgeHTML 12-18, as used by legacy Microsoft Edge)
		 * - `gecko`
		 * - `khtml`
		 * - `presto`
		 * - `trident`
		 * - `webkit`
		 *
		 * Note that Chrome and Chromium-based browsers like Opera have their layout
		 * engine identified as `webkit`.
		 *
		 * Recognised platforms:
		 *
		 * - `ipad`
		 * - `iphone`
		 * - `linux`
		 * - `mac`
		 * - `solaris` (untested)
		 * - `win`
		 *
		 * @param {Object} [nav] An object with a 'userAgent' and 'platform' property.
		 *  Defaults to the global `navigator` object.
		 * @return {Object} The client object
		 */
		profile: function ( nav ) {
			if ( !nav ) {
				nav = window.navigator;
			}

			// Use the cached version if possible
			if ( profileCache[ nav.userAgent + '|' + nav.platform ] ) {
				return profileCache[ nav.userAgent + '|' + nav.platform ];
			}

			// eslint-disable-next-line vars-on-top
			var
				versionNumber,
				key = nav.userAgent + '|' + nav.platform,

				// Configuration

				// Name of browsers or layout engines we don't recognize
				uk = 'unknown',
				// Generic version digit
				x = 'x',
				// Fixups for user agent strings that contain wild words
				wildFixups = [
					// Chrome lives in the shadow of Safari still
					[ 'Chrome Safari', 'Chrome' ],
					// KHTML is the layout engine not the browser - LIES!
					[ 'KHTML/', 'Konqueror/' ],
					// For Firefox Mobile, strip out "Android;" or "Android [version]" so that we
					// classify it as Firefox instead of Android (default browser)
					[ /Android(?:;|\s[a-zA-Z0-9.+-]+)(.*Firefox)/, '$1' ]
				],
				// Strings which precede a version number in a user agent string
				versionPrefixes = '(?:chrome|crios|firefox|fxios|opera|version|konqueror|msie|safari|android)',
				// This matches the actual version number, with non-capturing groups for the
				// separator and suffix
				versionSuffix = '(?:\\/|;?\\s|)([a-z0-9\\.\\+]*?)(?:;|dev|rel|\\)|\\s|$)',
				// Match the names of known browser families
				rName = /(chrome|crios|firefox|fxios|konqueror|msie|opera|safari|rekonq|android)/,
				// Match the name of known layout engines
				rLayout = /(gecko|konqueror|msie|trident|edge|opera|webkit)/,
				// Translations for conforming layout names
				layoutMap = { konqueror: 'khtml', msie: 'trident', opera: 'presto' },
				// Match the prefix and version of supported layout engines
				rLayoutVersion = /(applewebkit|gecko|trident|edge)\/(\d+)/,
				// Match the name of known operating systems
				rPlatform = /(win|wow64|mac|linux|sunos|solaris|iphone|ipad)/,
				// Translations for conforming operating system names
				platformMap = { sunos: 'solaris', wow64: 'win' },

				// Pre-processing

				ua = nav.userAgent,
				match,
				name = uk,
				layout = uk,
				layoutversion = uk,
				platform = uk,
				version = x;

			// Takes a userAgent string and fixes it into something we can more
			// easily work with
			wildFixups.forEach( function ( fixup ) {
				ua = ua.replace( fixup[ 0 ], fixup[ 1 ] );
			} );
			// Everything will be in lowercase from now on
			ua = ua.toLowerCase();

			// Extraction

			if ( ( match = rName.exec( ua ) ) ) {
				name = match[ 1 ];
			}
			if ( ( match = rLayout.exec( ua ) ) ) {
				layout = layoutMap[ match[ 1 ] ] || match[ 1 ];
			}
			if ( ( match = rLayoutVersion.exec( ua ) ) ) {
				layoutversion = parseInt( match[ 2 ], 10 );
			}
			if ( ( match = rPlatform.exec( nav.platform.toLowerCase() ) ) ) {
				platform = platformMap[ match[ 1 ] ] || match[ 1 ];
			}
			if ( ( match = new RegExp( versionPrefixes + versionSuffix ).exec( ua ) ) ) {
				version = match[ 1 ];
			}

			// Edge Cases -- did I mention about how user agent string lie?

			// Decode Safari's crazy 400+ version numbers
			if ( name === 'safari' && version > 400 ) {
				version = '2.0';
			}
			// Expose Opera 10's lies about being Opera 9.8
			if ( name === 'opera' && version >= 9.8 ) {
				match = ua.match( /\bversion\/([0-9.]*)/ );
				if ( match && match[ 1 ] ) {
					version = match[ 1 ];
				} else {
					version = '10';
				}
			}
			// And IE 11's lies about being not being IE
			if ( layout === 'trident' && layoutversion >= 7 && ( match = ua.match( /\brv[ :/]([0-9.]*)/ ) ) ) {
				if ( match[ 1 ] ) {
					name = 'msie';
					version = match[ 1 ];
				}
			}
			// And MS Edge's lies about being Chrome
			//
			// It's different enough from classic IE Trident engine that they do this
			// to avoid getting caught by MSIE-specific browser sniffing.
			if ( name === 'chrome' && ( match = ua.match( /\bedge\/([0-9.]*)/ ) ) ) {
				name = 'edge';
				version = match[ 1 ];
				layout = 'edge';
				layoutversion = parseInt( match[ 1 ], 10 );
			}
			// And Amazon Silk's lies about being Android on mobile or Safari on desktop
			if ( ( match = ua.match( /\bsilk\/([0-9.\-_]*)/ ) ) ) {
				if ( match[ 1 ] ) {
					name = 'silk';
					version = match[ 1 ];
				}
			}

			versionNumber = parseFloat( version, 10 ) || 0.0;

			// Caching
			profileCache[ key ] = {
				name: name,
				layout: layout,
				layoutVersion: layoutversion,
				platform: platform,
				version: version,
				versionBase: ( version !== x ? Math.floor( versionNumber ).toString() : x ),
				versionNumber: versionNumber
			};

			return profileCache[ key ];
		},

		/**
		 * Checks the current browser against a support map object.
		 *
		 * Version numbers passed as numeric values will be compared like numbers (1.2 > 1.11).
		 * Version numbers passed as string values will be compared using a simple component-wise
		 * algorithm, similar to PHP's version_compare ('1.2' < '1.11').
		 *
		 * A browser map is in the following format:
		 *
		 *     {
		 *         // Multiple rules with configurable operators
		 *         'msie': [['>=', 7], ['!=', 9]],
		 *         // Match no versions
		 *         'iphone': false,
		 *         // Match any version
		 *         'android': null
		 *     }
		 *
		 * It can optionally be split into ltr/rtl sections:
		 *
		 *     {
		 *         'ltr': {
		 *             'android': null,
		 *             'iphone': false
		 *         },
		 *         'rtl': {
		 *             'android': false,
		 *             // rules are not inherited from ltr
		 *             'iphone': false
		 *         }
		 *     }
		 *
		 * @param {Object} map Browser support map
		 * @param {Object} [profile] A client-profile object
		 * @param {boolean} [exactMatchOnly=false] Only return true if the browser is matched,
		 *  otherwise returns true if the browser is not found.
		 *
		 * @return {boolean} The current browser is in the support map
		 */
		test: function ( map, profile, exactMatchOnly ) {
			var conditions, dir, i, op, val, j, pieceVersion, pieceVal, compare;
			profile = $.isPlainObject( profile ) ? profile : $.client.profile();
			if ( map.ltr && map.rtl ) {
				dir = $( document.body ).is( '.rtl' ) ? 'rtl' : 'ltr';
				map = map[ dir ];
			}
			// Check over each browser condition to determine if we are running in a
			// compatible client
			if ( typeof map !== 'object' || map[ profile.name ] === undefined ) {
				// Not found, return true if exactMatchOnly not set, false otherwise
				return !exactMatchOnly;
			}
			conditions = map[ profile.name ];
			if ( conditions === false ) {
				// Match no versions
				return false;
			}
			if ( conditions === null ) {
				// Match all versions
				return true;
			}
			for ( i = 0; i < conditions.length; i++ ) {
				op = conditions[ i ][ 0 ];
				val = conditions[ i ][ 1 ];
				if ( typeof val === 'string' ) {
					// Perform a component-wise comparison of versions, similar to
					// PHP's version_compare but simpler. '1.11' is larger than '1.2'.
					pieceVersion = profile.version.toString().split( '.' );
					pieceVal = val.split( '.' );
					// Extend with zeroes to equal length
					while ( pieceVersion.length < pieceVal.length ) {
						pieceVersion.push( '0' );
					}
					while ( pieceVal.length < pieceVersion.length ) {
						pieceVal.push( '0' );
					}
					// Compare components
					compare = 0;
					for ( j = 0; j < pieceVersion.length; j++ ) {
						if ( Number( pieceVersion[ j ] ) < Number( pieceVal[ j ] ) ) {
							compare = -1;
							break;
						} else if ( Number( pieceVersion[ j ] ) > Number( pieceVal[ j ] ) ) {
							compare = 1;
							break;
						}
					}
					// compare will be -1, 0 or 1, depending on comparison result
					// eslint-disable-next-line no-eval
					if ( !( eval( String( compare + op + '0' ) ) ) ) {
						return false;
					}
				} else if ( typeof val === 'number' ) {
					// eslint-disable-next-line no-eval
					if ( !( eval( 'profile.versionNumber' + op + val ) ) ) {
						return false;
					}
				}
			}

			return true;
		}
	};
}() );
