/**
 * @class mw.user
 * @singleton
 */
/* global Uint32Array */
( function ( mw, $ ) {
	var userInfoPromise, stickyRandomSessionId;

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
			var rnds, i,
				hexRnds = new Array( 2 ),
				// Support: IE 11
				crypto = window.crypto || window.msCrypto;

			if ( crypto && crypto.getRandomValues && typeof Uint32Array === 'function' ) {
				// Fill an array with 2 random values, each of which is 32 bits.
				// Note that Uint32Array is array-like but does not implement Array.
				rnds = new Uint32Array( 2 );
				crypto.getRandomValues( rnds );
			} else {
				rnds = [
					Math.floor( Math.random() * 0x100000000 ),
					Math.floor( Math.random() * 0x100000000 )
				];
			}
			// Convert number to a string with 16 hex characters
			for ( i = 0; i < 2; i++ ) {
				// Add 0x100000000 before converting to hex and strip the extra character
				// after converting to keep the leading zeros.
				hexRnds[ i ] = ( rnds[ i ] + 0x100000000 ).toString( 16 ).slice( 1 );
			}

			// Concatenation of two random integers with entropy n and m
			// returns a string with entropy n+m if those strings are independent
			return hexRnds.join( '' );
		},

		/**
		 * A sticky generateRandomSessionId for the current JS execution context,
		 * cached within this class.
		 *
		 * @return {string} 64 bit integer in hex format, padded
		 */
		stickyRandomId: function () {
			if ( !stickyRandomSessionId ) {
				stickyRandomSessionId = mw.user.generateRandomSessionId();
			}

			return stickyRandomSessionId;
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
		 * Get an automatically generated random ID (persisted in sessionStorage)
		 *
		 * This ID is ephemeral for everyone, staying in their browser only until they
		 * close their browsing session.
		 *
		 * @return {string} Random session ID
		 */
		sessionId: function () {
			var sessionId = mw.storage.session.get( 'mwuser-sessionId' );
			if ( !sessionId ) {
				sessionId = mw.user.generateRandomSessionId();
				mw.storage.session.set( 'mwuser-sessionId', sessionId );
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
			return $.Deferred().resolve( userGroups ).done( callback );
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
			).done( callback );
		}
	} );

}( mediaWiki, jQuery ) );
