/*
 * Implementation for mediaWiki.user
 */

( function ( mw, $ ) {

	/**
	 * User object
	 */
	function User( options, tokens ) {
		var user, callbacks;

		/* Private Members */

		user = this;
		callbacks = {};

		/**
		 * Gets the current user's groups or rights.
		 * @param {String} info: One of 'groups' or 'rights'.
		 * @param {Function} callback
		 */
		function getUserInfo( info, callback ) {
			var api;
			if ( callbacks[info] ) {
				callbacks[info].add( callback );
				return;
			}
			callbacks.rights = $.Callbacks('once memory');
			callbacks.groups = $.Callbacks('once memory');
			callbacks[info].add( callback );
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
				callbacks.rights.fire( rights || [] );
				callbacks.groups.fire( groups || [] );
			} );
		}

		/* Public Members */

		this.options = options || new mw.Map();

		this.tokens = tokens || new mw.Map();

		/* Public Methods */

		/**
		 * Generates a random user session ID (32 alpha-numeric characters).
		 *
		 * This information would potentially be stored in a cookie to identify a user during a
		 * session or series of sessions. Its uniqueness should not be depended on.
		 *
		 * @return String: Random set of 32 alpha-numeric characters
		 */
		this.generateRandomSessionId = function () {
			var i, r,
				id = '',
				seed = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
			for ( i = 0; i < 32; i++ ) {
				r = Math.floor( Math.random() * seed.length );
				id += seed.substring( r, r + 1 );
			}
			return id;
		};

		/**
		 * Gets the current user's name.
		 *
		 * @return Mixed: User name string or null if users is anonymous
		 */
		this.getName = function () {
			return mw.config.get( 'wgUserName' );
		};

		/**
		 * @deprecated since 1.20 use mw.user.getName() instead
		 */
		this.name = function () {
			return this.getName();
		};

		/**
		 * Get date user registered, if available.
		 *
		 * @return {Date|false|null} date user registered, or false for anonymous users, or
		 *  null when data is not available
		 */
		this.getRegistration = function () {
			var registration = mw.config.get( 'wgUserRegistration' );
			if ( this.isAnon() ) {
				return false;
			} else if ( registration === null ) {
				// Information may not be available if they signed up before
				// MW began storing this.
				return null;
			} else {
				return new Date( registration );
			}
		};

		/**
		 * Checks if the current user is anonymous.
		 *
		 * @return Boolean
		 */
		this.isAnon = function () {
			return user.getName() === null;
		};

		/**
		 * @deprecated since 1.20 use mw.user.isAnon() instead
		 */
		this.anonymous = function () {
			return user.isAnon();
		};

		/**
		 * Gets a random session ID automatically generated and kept in a cookie.
		 *
		 * This ID is ephemeral for everyone, staying in their browser only until they close
		 * their browser.
		 *
		 * @return String: User name or random session ID
		 */
		this.sessionId = function () {
			var sessionId = $.cookie( 'mediaWiki.user.sessionId' );
			if ( typeof sessionId === 'undefined' || sessionId === null ) {
				sessionId = user.generateRandomSessionId();
				$.cookie( 'mediaWiki.user.sessionId', sessionId, { 'expires': null, 'path': '/' } );
			}
			return sessionId;
		};

		/**
		 * Gets the current user's name, or a semi-persistent random ID
		 *
		 * @return String: User name or random session ID
		 */
		this.id = function () {
			var id,
				name = user.getName();
			if ( name ) {
				return name;
			}
			// Fall back to the session ID, or a longer-lived ID when appropriate.
			return user.interSessionId();
		};

		/**
		 * Retrieve a semi-persistent ID which may be used to guess the user's last session ID.
		 *
		 * This might be done during usability studies, for example.
		 *
		 * Since this is a bald invasion of privacy, it will never be performed
		 * on the same user twice in a row.  In other words, every third
		 * visiting session, an anon's anonymity is guaranteed again.
		 *
		 * @return String: session ID, or previous session ID if available
		 */
		this.interSessionId = function() {
			var chanceOfStudy,
				id,
				sessionId = user.sessionId(),
				cheatCookie = $.cookie( 'mediaWiki.user.interSessionId' );

			if ( typeof cheatCookie === 'undefined' || cheatCookie === null ) {
				// the probability a user will be enrolled in the study, per session
				chanceOfStudy = mw.config.get( 'wgStudyAnonymousPopulation' );

				if ( chanceOfStudy && Math.random() < chanceOfStudy ) {
					// Enroll in our special program, until the next visit.
					$.cookie( 'mediaWiki.user.interSessionId', sessionId, { 'expires': 365, 'path': '/' } );
				} else {
					// not enrolled
					// Prevent enrollment for this session, so that the chance
					// of enrollment is independent of pageviews per visit.
					//FIXME: would an empty string cookie value trigger any browser bugs?
					$.cookie( 'mediaWiki.user.interSessionId', 'nil', { 'expires': null, 'path': '/' } );
				}
			} else if ( cheatCookie === 'nil' ) {
				// already prevented!
			} else if ( cheatCookie === sessionId ) {
				// nothing to do until the next visit
			} else {
				// convert to a session cookie
				$.cookie( 'mediaWiki.user.interSessionId', cheatCookie, { 'expires': null, 'path': '/' } );
				return cheatCookie;
			}
			return sessionId;
		};

		/**
		 * Gets the user's bucket, placing them in one at random based on set odds if needed.
		 *
		 * @param key String: Name of bucket
		 * @param options Object: Bucket configuration options
		 * @param options.buckets Object: List of bucket-name/relative-probability pairs (required,
		 * must have at least one pair)
		 * @param options.version Number: Version of bucket test, changing this forces rebucketing
		 * (optional, default: 0)
		 * @param options.expires Number: Length of time (in days) until the user gets rebucketed
		 * (optional, default: 30)
		 * @return String: Bucket name - the randomly chosen key of the options.buckets object
		 *
		 * @example
		 *     mw.user.bucket( 'test', {
		 *         'buckets': { 'ignored': 50, 'control': 25, 'test': 25 },
		 *         'version': 1,
		 *         'expires': 7
		 *     } );
		 */
		this.bucket = function ( key, options ) {
			var cookie, parts, version, bucket,
				range, k, rand, total;

			options = $.extend( {
				buckets: {},
				version: 0,
				expires: 30
			}, options || {} );

			cookie = $.cookie( 'mediaWiki.user.bucket:' + key );

			// Bucket information is stored as 2 integers, together as version:bucket like: "1:2"
			if ( typeof cookie === 'string' && cookie.length > 2 && cookie.indexOf( ':' ) > 0 ) {
				parts = cookie.split( ':' );
				if ( parts.length > 1 && Number( parts[0] ) === options.version ) {
					version = Number( parts[0] );
					bucket = String( parts[1] );
				}
			}
			if ( bucket === undefined ) {
				if ( !$.isPlainObject( options.buckets ) ) {
					throw 'Invalid buckets error. Object expected for options.buckets.';
				}
				version = Number( options.version );
				// Find range
				range = 0;
				for ( k in options.buckets ) {
					range += options.buckets[k];
				}
				// Select random value within range
				rand = Math.random() * range;
				// Determine which bucket the value landed in
				total = 0;
				for ( k in options.buckets ) {
					bucket = k;
					total += options.buckets[k];
					if ( total >= rand ) {
						break;
					}
				}
				$.cookie(
					'mediaWiki.user.bucket:' + key,
					version + ':' + bucket,
					{ 'path': '/', 'expires': Number( options.expires ) }
				);
			}
			return bucket;
		};

		/**
		 * Gets the current user's groups.
		 */
		this.getGroups = function ( callback ) {
			getUserInfo( 'groups', callback );
		};

		/**
		 * Gets the current user's rights.
		 */
		this.getRights = function ( callback ) {
			getUserInfo( 'rights', callback );
		};
	}

	// Extend the skeleton mw.user from mediawiki.js
	// This is kind of ugly but we're stuck with this for b/c reasons
	mw.user = new User( mw.user.options, mw.user.tokens );

}( mediaWiki, jQuery ) );
