/**
 * User library provided by 'mediawiki.user' ResourceLoader module.
 *
 * @namespace mw.user
 */
( function () {
	let userInfoPromise, tempUserNamePromise, pageviewRandomId, sessionId;
	// Keep in sync with ResourceLoader's ClientHtml.php class constant.
	const CLIENTPREF_COOKIE_NAME = 'mwclientpreferences';
	const CLIENTPREF_SUFFIX = '-clientpref-';
	const CLIENTPREF_DELIMITER = ',';

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

	/**
	 * Save the feature value to the client preferences cookie.
	 *
	 * @private
	 * @param {string} feature
	 * @param {string} value
	 */
	function saveClientPrefs( feature, value ) {
		const existingCookie = mw.cookie.get( CLIENTPREF_COOKIE_NAME ) || '';
		const data = {};
		existingCookie.split( CLIENTPREF_DELIMITER ).forEach( ( keyValuePair ) => {
			const m = keyValuePair.match( /^([\w-]+)-clientpref-(\w+)$/ );
			if ( m ) {
				data[ m[ 1 ] ] = m[ 2 ];
			}
		} );
		data[ feature ] = value;

		const newCookie = Object.keys( data ).map( ( key ) => key + CLIENTPREF_SUFFIX + data[ key ] ).join( CLIENTPREF_DELIMITER );
		mw.cookie.set( CLIENTPREF_COOKIE_NAME, newCookie );
	}

	/**
	 * Check if the feature name is composed of valid characters.
	 *
	 * A valid feature name may contain letters, numbers, and "-" characters.
	 *
	 * @private
	 * @param {string} value
	 * @return {boolean}
	 */
	function isValidFeatureName( value ) {
		return value.match( /^[a-zA-Z0-9-]+$/ ) !== null;
	}

	/**
	 * Check if the value is composed of valid characters.
	 *
	 * @private
	 * @param {string} value
	 * @return {boolean}
	 */
	function isValidFeatureValue( value ) {
		return value.match( /^[a-zA-Z0-9]+$/ ) !== null;
	}

	// mw.user with the properties options and tokens gets defined in mediawiki.base.js.
	Object.assign( mw.user, /** @lends mw.user */{

		/**
		 * Generate a random user session ID.
		 *
		 * This information would potentially be stored in a cookie to identify a user during a
		 * session or series of sessions. Its uniqueness should not be depended on unless the
		 * browser supports the crypto API.
		 *
		 * Known problems with `Math.random()`:
		 * Using the `Math.random` function we have seen sets
		 * with 1% of non uniques among 200,000 values with Safari providing most of these.
		 * Given the prevalence of Safari in mobile the percentage of duplicates in
		 * mobile usages of this code is probably higher.
		 *
		 * Rationale:
		 * We need about 80 bits to make sure that probability of collision
		 * on 155 billion  is <= 1%
		 *
		 * See {@link https://en.wikipedia.org/wiki/Birthday_attack#Mathematics}
		 *
		 * `n(p;H) = n(0.01,2^80)= sqrt (2 * 2^80 * ln(1/(1-0.01)))`
		 *
		 * @return {string} 80 bit integer (20 characters) in hex format, padded
		 */
		generateRandomSessionId: function () {
			let rnds;

			// We first attempt to generate a set of random values using the WebCrypto API's
			// getRandomValues method. If the WebCrypto API is not supported, the Uint16Array
			// type does not exist, or getRandomValues fails (T263041), an exception will be
			// thrown, which we'll catch and fall back to using Math.random.
			try {
				// Initialize a typed array containing 5 0-initialized 16-bit integers.
				// Note that Uint16Array is array-like but does not implement Array.

				rnds = new Uint16Array( 5 );
				// Overwrite the array elements with cryptographically strong random values.
				// https://developer.mozilla.org/en-US/docs/Web/API/Crypto/getRandomValues
				// NOTE: this operation can fail internally (T263041), so the try-catch block
				// must be preserved even after WebCrypto is supported in all modern (Grade A)
				// browsers.
				crypto.getRandomValues( rnds );
			} catch ( e ) {
				rnds = new Array( 5 );
				// 0x10000 is 2^16 so the operation below will return a number
				// between 2^16 and zero
				for ( let i = 0; i < 5; i++ ) {
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
		 * Get the current user's database id.
		 *
		 * Not to be confused with {@link mw.user#id id}.
		 *
		 * @return {number} Current user's id, or 0 if user is anonymous
		 */
		getId: function () {
			return mw.config.get( 'wgUserId' ) || 0;
		},

		/**
		 * Check whether the user is a normal non-temporary registered user.
		 *
		 * @return {boolean}
		 */
		isNamed: function () {
			return !mw.user.isAnon() && !mw.user.isTemp();
		},

		/**
		 * Check whether the user is an autocreated temporary user.
		 *
		 * @return {boolean}
		 */
		isTemp: function () {
			return mw.config.get( 'wgUserIsTemp' ) || false;
		},

		/**
		 * Get the current user's name.
		 *
		 * @return {string|null} User name string or null if user is anonymous
		 */
		getName: function () {
			return mw.config.get( 'wgUserName' );
		},

		/**
		 * Acquire a temporary user username and stash it in the current session, if temp account creation
		 * is enabled and the current user is logged out. If a name has already been stashed, returns the
		 * same name.
		 *
		 * If the user later performs an action that results in temp account creation, the stashed username
		 * will be used for their account. It may also be used in previews. However, the account is not
		 * created yet, and the name is not visible to other users.
		 *
		 * @return {jQuery.Promise} Promise resolved with the username if we succeeded,
		 *   or resolved with `null` if we failed
		 */
		acquireTempUserName: function () {
			if ( tempUserNamePromise !== undefined ) {
				// Return the existing promise if we already tried. Do not retry even if we failed.
				return tempUserNamePromise;
			}

			if ( mw.config.get( 'wgUserId' ) ) {
				// User is logged in (or has a temporary account), nothing to do
				tempUserNamePromise = $.Deferred().resolve( null );
			} else if ( mw.config.get( 'wgTempUserName' ) ) {
				// Temporary user username already acquired
				tempUserNamePromise = $.Deferred().resolve( mw.config.get( 'wgTempUserName' ) );
			} else {
				const api = new mw.Api();
				tempUserNamePromise = api.post( { action: 'acquiretempusername' } ).then( ( resp ) => {
					mw.config.set( 'wgTempUserName', resp.acquiretempusername );
					return resp.acquiretempusername;
				} ).catch(
					// Ignore failures. The temp name should not be necessary for anything to work.
					() => null
				);
			}

			return tempUserNamePromise;
		},

		/**
		 * Get date user registered, if available.
		 *
		 * @return {boolean|null|Date} False for anonymous users, null if data is
		 *  unavailable, or Date for when the user registered.
		 */
		getRegistration: function () {
			if ( mw.user.isAnon() ) {
				return false;
			}
			const registration = mw.config.get( 'wgUserRegistration' );
			// Registration may be unavailable if the user signed up before MediaWiki
			// began tracking this.
			return !registration ? null : new Date( registration );
		},

		/**
		 * Get date user first registered, if available.
		 *
		 * @return {boolean|null|Date} False for anonymous users, null if data is
		 *  unavailable, or Date for when the user registered. For temporary users
		 *  that is when their temporary account was created.
		 */
		getFirstRegistration: function () {
			if ( mw.user.isAnon() ) {
				return false;
			}
			const registration = mw.config.get( 'wgUserFirstRegistration' );
			// Registration may be unavailable if the user signed up before MediaWiki
			// began tracking this.
			return registration ? new Date( registration ) : null;
		},

		/**
		 * Check whether the current user is anonymous.
		 *
		 * @return {boolean}
		 */
		isAnon: function () {
			return mw.user.getName() === null;
		},

		/**
		 * Retrieve a random ID, generating it if needed.
		 *
		 * This ID is shared across windows, tabs, and page views. It is persisted
		 * for the duration of one browser session (until the browser app is closed),
		 * unless the user evokes a "restore previous session" feature that some browsers have.
		 *
		 * **Note:** Server-side code must never interpret or modify this value.
		 *
		 * @return {string} Random session ID (20 hex characters)
		 */
		sessionId: function () {
			if ( sessionId === undefined ) {
				sessionId = mw.cookie.get( 'mwuser-sessionId' );
				// Validate that the value is 20 hex characters, as it is user-controlled,
				// and we also used different formats in the past (T283881)
				if ( sessionId === null || !/^[0-9a-f]{20}$/.test( sessionId ) ) {
					sessionId = mw.user.generateRandomSessionId();
					// Setting the `expires` field to `null` means that the cookie should
					// persist (shared across windows and tabs) until the browser is closed.
					mw.cookie.set( 'mwuser-sessionId', sessionId, { expires: null } );
				}
			}
			return sessionId;
		},

		/**
		 * Get the current user's name or the session ID.
		 *
		 * Not to be confused with {@link mw.user#getId getId}.
		 *
		 * @return {string} User name or random session ID
		 */
		id: function () {
			return mw.user.getName() || mw.user.sessionId();
		},

		/**
		 * Get the current user's groups.
		 *
		 * @param {Function} [callback]
		 * @return {jQuery.Promise}
		 */
		getGroups: function ( callback ) {
			const userGroups = mw.config.get( 'wgUserGroups', [] );

			// Uses promise for backwards compatibility
			return $.Deferred().resolve( userGroups ).then( callback );
		},

		/**
		 * Get the current user's rights.
		 *
		 * @param {Function} [callback]
		 * @return {jQuery.Promise}
		 */
		getRights: function ( callback ) {
			return getUserInfo().then(
				( userInfo ) => userInfo.rights,
				() => []
			).then( callback );
		},

		/**
		 * Manage client preferences.
		 *
		 * For skins that enable the `clientPrefEnabled` option (see Skin class in PHP),
		 * this feature allows you to store preferences in the browser session that will
		 * switch one or more the classes on the HTML document.
		 *
		 * This is only supported for unregistered users. For registered users, skins
		 * and extensions must use user preferences (e.g. hidden or API-only options)
		 * and swap class names server-side through the Skin interface.
		 *
		 * This feature is limited to page views by unregistered users. For logged-in requests,
		 * store preferences in the database instead, via UserOptionsManager or
		 * {@link mw.Api#saveOption} (may be hidden or API-only to exclude from Special:Preferences),
		 * and then include the desired classes directly in Skin::getHtmlElementAttributes.
		 *
		 * Classes toggled by this feature must be named as `<feature>-clientpref-<value>`,
		 * where `value` contains only alphanumerical characters (a-z, A-Z, and 0-9), and `feature`
		 * can also include hyphens.
		 *
		 * @namespace mw.user.clientPrefs
		 */
		clientPrefs: {
			/**
			 * Change the class on the HTML document element, and save the value in a cookie.
			 *
			 * @memberof mw.user.clientPrefs
			 * @param {string} feature
			 * @param {string} value
			 * @return {boolean} True if feature was stored successfully, false if the value
			 *   uses a forbidden character or the feature is not recognised
			 *   e.g. a matching class was not defined on the HTML document element.
			 */
			set: function ( feature, value ) {
				if ( mw.user.isNamed() ) {
					// Avoid storing an unused cookie and returning true when the setting
					// wouldn't actually be applied.
					// Encourage future-proof and server-first implementations.
					// Encourage feature parity for logged-in users.
					throw new Error( 'clientPrefs are for unregistered users only' );
				}
				if ( !isValidFeatureName( feature ) || !isValidFeatureValue( value ) ) {
					return false;
				}
				const currentValue = mw.user.clientPrefs.get( feature );
				// the feature is not recognized
				if ( !currentValue ) {
					return false;
				}
				const oldFeatureClass = feature + CLIENTPREF_SUFFIX + currentValue;
				const newFeatureClass = feature + CLIENTPREF_SUFFIX + value;
				// The following classes are removed here:
				// * feature-name-clientpref-<old-feature-value>
				// * e.g. vector-font-size--clientpref-small
				document.documentElement.classList.remove( oldFeatureClass );
				// The following classes are added here:
				// * feature-name-clientpref-<feature-value>
				// * e.g. vector-font-size--clientpref-xlarge
				document.documentElement.classList.add( newFeatureClass );
				saveClientPrefs( feature, value );
				return true;
			},

			/**
			 * Retrieve the current value of the feature from the HTML document element.
			 *
			 * @memberof mw.user.clientPrefs
			 * @param {string} feature
			 * @return {string|boolean} returns boolean if the feature is not recognized
			 *  returns string if a feature was found.
			 */
			get: function ( feature ) {
				const featurePrefix = feature + CLIENTPREF_SUFFIX;
				const docClass = document.documentElement.className;

				const featureRegEx = new RegExp(
					'(^| )' + mw.util.escapeRegExp( featurePrefix ) + '([a-zA-Z0-9]+)( |$)'
				);
				const match = docClass.match( featureRegEx );

				// check no further matches if we replaced this occurance.
				const isAmbiguous = docClass.replace( featureRegEx, '$1$3' ).match( featureRegEx ) !== null;
				return !isAmbiguous && match ? match[ 2 ] : false;
			}
		}
	} );

}() );
