/**
 * @class mw.user
 * @singleton
 */
( function ( mw, $ ) {
	var i,
		deferreds = {},
		byteToHex = [];

	/**
	 * Get the current user's groups or rights
	 *
	 * @private
	 * @param {string} info One of 'groups' or 'rights'
	 * @return {jQuery.Promise}
	 */
	function getUserInfo( info ) {
		var api;
		if ( !deferreds[ info ] ) {

			deferreds.rights = $.Deferred();
			deferreds.groups = $.Deferred();

			api = new mw.Api();
			api.get( {
				action: 'query',
				meta: 'userinfo',
				uiprop: 'rights|groups'
			} ).always( function ( data ) {
				var rights, groups;
				if ( data.query && data.query.userinfo ) {
					rights = data.query.userinfo.rights;
					groups = data.query.userinfo.groups;
				}
				deferreds.rights.resolve( rights || [] );
				deferreds.groups.resolve( groups || [] );
			} );

		}

		return deferreds[ info ].promise();
	}

	// Map from numbers 0-255 to a hex string (with padding)
	for ( i = 0; i < 256; i++ ) {
		// Padding: Add a full byte (0x100, 256) and strip the extra character
		byteToHex[ i ] = ( i + 256 ).toString( 16 ).slice( 1 );
	}

	// mw.user with the properties options and tokens gets defined in mediawiki.js.
	$.extend( mw.user, {

		/**
		 * Generate a random user session ID.
		 *
		 * This information would potentially be stored in a cookie to identify a user during a
		 * session or series of sessions. Its uniqueness should not be depended on unless the
		 * browser supports the crypto API.
		 *
		 * Known problems with Math.random():
		 * Using the Math.random function we have seen sets
		 * with 1% of non uniques among 200,000 values with Safari providing most of these.
		 * Given the prevalence of Safari in mobile the percentage of duplicates in
		 * mobile usages of this code is probably higher.
		 *
		 * Rationale:
		 * We need about 64 bits to make sure that probability of collision
		 * on 500 million (5*10^8) is <= 1%
		 * See https://en.wikipedia.org/wiki/Birthday_problem#Probability_table
		 *
		 * @return {string} 64 bit integer in hex format, padded
		 */
		generateRandomSessionId: function () {
			/*jshint bitwise:false */
			var rnds, i, r,
				hexRnds = new Array( 8 ),
				// Support: IE 11
				crypto = window.crypto || window.msCrypto;

			// Based on https://github.com/broofa/node-uuid/blob/bfd9f96127/uuid.js
			if ( crypto && crypto.getRandomValues ) {
				// Fill an array with 8 random values, each of which is 8 bits.
				// Note that Uint8Array is array-like but does not implement Array.
				rnds = new Uint8Array( 8 );
				crypto.getRandomValues( rnds );
			} else {
				rnds = new Array( 8 );
				for ( i = 0; i < 8; i++ ) {
					if ( ( i & 3 ) === 0 ) {
						r = Math.random() * 0x100000000;
					}
					rnds[ i ] = r >>> ( ( i & 3 ) << 3 ) & 255;
				}
			}
			// Convert from number to hex
			for ( i = 0; i < 8; i++ ) {
				hexRnds[ i ] = byteToHex[ rnds[ i ] ];
			}

			// Concatenation of two random integers with entrophy n and m
			// returns a string with entrophy n+m if those strings are independent
			return hexRnds.join( '' );
		},

		/**
		 * Get the current user's database id
		 *
		 * Not to be confused with #id.
		 *
		 * @return {number} Current user's id, or 0 if user is anonymous
		 */
		getId: function () {
			return mw.config.get( 'wgUserId', 0 );
		},

		/**
		 * Get the current user's name
		 *
		 * @return {string|null} User name string or null if user is anonymous
		 */
		getName: function () {
			return mw.config.get( 'wgUserName' );
		},

		/**
		 * Get date user registered, if available
		 *
		 * @return {Date|boolean|null} Date user registered, or false for anonymous users, or
		 *  null when data is not available
		 */
		getRegistration: function () {
			var registration = mw.config.get( 'wgUserRegistration' );
			if ( mw.user.isAnon() ) {
				return false;
			}
			if ( registration === null ) {
				// Information may not be available if they signed up before
				// MW began storing this.
				return null;
			}
			return new Date( registration );
		},

		/**
		 * Whether the current user is anonymous
		 *
		 * @return {boolean}
		 */
		isAnon: function () {
			return mw.user.getName() === null;
		},

		/**
		 * Get an automatically generated random ID (stored in a session cookie)
		 *
		 * This ID is ephemeral for everyone, staying in their browser only until they close
		 * their browser.
		 *
		 * @return {string} Random session ID
		 */
		sessionId: function () {
			var sessionId = mw.cookie.get( 'mwuser-sessionId' );
			if ( sessionId === null ) {
				sessionId = mw.user.generateRandomSessionId();
				mw.cookie.set( 'mwuser-sessionId', sessionId, { expires: null } );
			}
			return sessionId;
		},

		/**
		 * Get the current user's name or the session ID
		 *
		 * Not to be confused with #getId.
		 *
		 * @return {string} User name or random session ID
		 */
		id: function () {
			return mw.user.getName() || mw.user.sessionId();
		},

		/**
		 * Get the user's bucket (place them in one if not done already)
		 *
		 *     mw.user.bucket( 'test', {
		 *         buckets: { ignored: 50, control: 25, test: 25 },
		 *         version: 1,
		 *         expires: 7
		 *     } );
		 *
		 * @deprecated since 1.23
		 * @param {string} key Name of bucket
		 * @param {Object} options Bucket configuration options
		 * @param {Object} options.buckets List of bucket-name/relative-probability pairs (required,
		 *  must have at least one pair)
		 * @param {number} [options.version=0] Version of bucket test, changing this forces
		 *  rebucketing
		 * @param {number} [options.expires=30] Length of time (in days) until the user gets
		 *  rebucketed
		 * @return {string} Bucket name - the randomly chosen key of the `options.buckets` object
		 */
		bucket: function ( key, options ) {
			var cookie, parts, version, bucket,
				range, k, rand, total;

			options = $.extend( {
				buckets: {},
				version: 0,
				expires: 30
			}, options || {} );

			cookie = mw.cookie.get( 'mwuser-bucket:' + key );

			// Bucket information is stored as 2 integers, together as version:bucket like: "1:2"
			if ( typeof cookie === 'string' && cookie.length > 2 && cookie.indexOf( ':' ) !== -1 ) {
				parts = cookie.split( ':' );
				if ( parts.length > 1 && Number( parts[ 0 ] ) === options.version ) {
					version = Number( parts[ 0 ] );
					bucket = String( parts[ 1 ] );
				}
			}

			if ( bucket === undefined ) {
				if ( !$.isPlainObject( options.buckets ) ) {
					throw new Error( 'Invalid bucket. Object expected for options.buckets.' );
				}

				version = Number( options.version );

				// Find range
				range = 0;
				for ( k in options.buckets ) {
					range += options.buckets[ k ];
				}

				// Select random value within range
				rand = Math.random() * range;

				// Determine which bucket the value landed in
				total = 0;
				for ( k in options.buckets ) {
					bucket = k;
					total += options.buckets[ k ];
					if ( total >= rand ) {
						break;
					}
				}

				mw.cookie.set(
					'mwuser-bucket:' + key,
					version + ':' + bucket,
					{ expires: Number( options.expires ) * 86400 }
				);
			}

			return bucket;
		},

		/**
		 * Get the current user's groups
		 *
		 * @param {Function} [callback]
		 * @return {jQuery.Promise}
		 */
		getGroups: function ( callback ) {
			return getUserInfo( 'groups' ).done( callback );
		},

		/**
		 * Get the current user's rights
		 *
		 * @param {Function} [callback]
		 * @return {jQuery.Promise}
		 */
		getRights: function ( callback ) {
			return getUserInfo( 'rights' ).done( callback );
		}
	} );

}( mediaWiki, jQuery ) );
