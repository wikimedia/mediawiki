/**
 * @class mw.user
 * @singleton
 */
/* global Uint16Array */
( function () {
	var userInfoPromise, pageviewRandomId;

	/**
	 * Get the current user's groups or rights
	 *
	 * @private
	 * @return {jQuery.Promise}
	 */
	function getUserInfo() {
		if ( !userInfoPromise ) {
			userInfoPromise = new mw.Api().getUserInfo();
		}
		return userInfoPromise;
	}

	// mw.user with the properties options and tokens gets defined in mediawiki.base.js.
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
		 * We need about 80 bits to make sure that probability of collision
		 * on 155 billion  is <= 1%
		 *
		 * See https://en.wikipedia.org/wiki/Birthday_attack#Mathematics
		 * n(p;H) = n(0.01,2^80)= sqrt (2 * 2^80 * ln(1/(1-0.01)))
		 *
		 * @return {string} 80 bit integer in hex format, padded
		 */
		generateRandomSessionId: function () {
			var rnds, i,
				// Support: IE 11
				crypto = window.crypto || window.msCrypto;

			if ( crypto && crypto.getRandomValues && typeof Uint16Array === 'function' ) {
				// Fill an array with 5 random values, each of which is 16 bits.
				// Note that Uint16Array is array-like but does not implement Array.
				rnds = new Uint16Array( 5 );
				crypto.getRandomValues( rnds );
			} else {
				rnds = new Array( 5 );
				// 0x10000 is 2^16 so the operation below will return a number
				// between 2^16 and zero
				for ( i = 0; i < 5; i++ ) {
					rnds[ i ] = Math.floor( Math.random() * 0x10000 );
				}
			}

			// Convert the 5 16bit-numbers into 20 characters (4 hex per 16 bits).
			// Concatenation of two random integers with entropy n and m
			// returns a string with entropy n+m if those strings are independent.
			// Tested that below code is faster than array + loop + join.
			return ( rnds[ 0 ] + 0x10000 ).toString( 16 ).slice( 1 ) +
				( rnds[ 1 ] + 0x10000 ).toString( 16 ).slice( 1 ) +
				( rnds[ 2 ] + 0x10000 ).toString( 16 ).slice( 1 ) +
				( rnds[ 3 ] + 0x10000 ).toString( 16 ).slice( 1 ) +
				( rnds[ 4 ] + 0x10000 ).toString( 16 ).slice( 1 );
		},

		/**
		 * A sticky generateRandomSessionId for the current JS execution context,
		 * cached within this class (also known as a page view token).
		 *
		 * @since 1.32
		 * @return {string} 80 bit integer in hex format, padded
		 */
		getPageviewToken: function () {
			if ( !pageviewRandomId ) {
				pageviewRandomId = mw.user.generateRandomSessionId();
			}

			return pageviewRandomId;
		},

		/**
		 * Get the current user's database id
		 *
		 * Not to be confused with #id.
		 *
		 * @return {number} Current user's id, or 0 if user is anonymous
		 */
		getId: function () {
			return mw.config.get( 'wgUserId' ) || 0;
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
		 * @return {boolean|null|Date} False for anonymous users, null if data is
		 *  unavailable, or Date for when the user registered.
		 */
		getRegistration: function () {
			var registration;
			if ( mw.user.isAnon() ) {
				return false;
			}
			registration = mw.config.get( 'wgUserRegistration' );
			// Registration may be unavailable if the user signed up before MediaWiki
			// began tracking this.
			return !registration ? null : new Date( registration );
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
		 * Retrieve a random ID, generating it if needed
		 *
		 * This ID is shared across windows, tabs, and page views. It is persisted
		 * for the duration of one browser session (until the browser app is closed),
		 * unless the user evokes a "restore previous session" feature that some browsers have.
		 *
		 * **Note:** Server-side code must never interpret or modify this value.
		 *
		 * @return {string} Random session ID
		 */
		sessionId: function () {
			var sessionId = mw.cookie.get( 'mwuser-sessionId' );
			if ( sessionId === null ) {
				sessionId = mw.user.generateRandomSessionId();
				// Setting the `expires` field to `null` means that the cookie should
				// persist (shared across windows and tabs) until the browser is closed.
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
		 * Get the current user's groups
		 *
		 * @param {Function} [callback]
		 * @return {jQuery.Promise}
		 */
		getGroups: function ( callback ) {
			var userGroups = mw.config.get( 'wgUserGroups', [] );

			// Uses promise for backwards compatibility
			return $.Deferred().resolve( userGroups ).then( callback );
		},

		/**
		 * Get the current user's rights
		 *
		 * @param {Function} [callback]
		 * @return {jQuery.Promise}
		 */
		getRights: function ( callback ) {
			return getUserInfo().then(
				function ( userInfo ) { return userInfo.rights; },
				function () { return []; }
			).then( callback );
		}
	} );

}() );
