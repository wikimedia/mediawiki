/**
 * @class mw.user
 * @singleton
 */
( function ( mw, $ ) {
	var user,
		callbacks = {},
		// Extend the skeleton mw.user from mediawiki.js
		// This is kind of ugly but we're stuck with this for b/c reasons
		options = mw.user.options || new mw.Map(),
		tokens = mw.user.tokens || new mw.Map();

	/**
	 * Get the current user's groups or rights
	 *
	 * @private
	 * @param {string} info One of 'groups' or 'rights'
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

	mw.user = user = {
		options: options,
		tokens: tokens,

		/**
		 * Generate a random user session ID (32 alpha-numeric characters)
		 *
		 * This information would potentially be stored in a cookie to identify a user during a
		 * session or series of sessions. Its uniqueness should not be depended on.
		 *
		 * @return {string} Random set of 32 alpha-numeric characters
		 */
		generateRandomSessionId: function () {
			var i, r,
				id = '',
				seed = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
			for ( i = 0; i < 32; i++ ) {
				r = Math.floor( Math.random() * seed.length );
				id += seed.substring( r, r + 1 );
			}
			return id;
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
		 * @inheritdoc #getName
		 * @deprecated since 1.20 use #getName instead
		 */
		name: function () {
			return user.getName();
		},

		/**
		 * Get date user registered, if available
		 *
		 * @return {Date|boolean|null} Date user registered, or false for anonymous users, or
		 *  null when data is not available
		 */
		getRegistration: function () {
			var registration = mw.config.get( 'wgUserRegistration' );
			if ( user.isAnon() ) {
				return false;
			} else if ( registration === null ) {
				// Information may not be available if they signed up before
				// MW began storing this.
				return null;
			} else {
				return new Date( registration );
			}
		},

		/**
		 * Whether the current user is anonymous
		 *
		 * @return {boolean}
		 */
		isAnon: function () {
			return user.getName() === null;
		},

		/**
		 * @inheritdoc #isAnon
		 * @deprecated since 1.20 use #isAnon instead
		 */
		anonymous: function () {
			return user.isAnon();
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
			var sessionId = $.cookie( 'mediaWiki.user.sessionId' );
			if ( sessionId === undefined || sessionId === null ) {
				sessionId = user.generateRandomSessionId();
				$.cookie( 'mediaWiki.user.sessionId', sessionId, { expires: null, path: '/' } );
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
			return user.getName() || user.sessionId();
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

			cookie = $.cookie( 'mediaWiki.user.bucket:' + key );

			// Bucket information is stored as 2 integers, together as version:bucket like: "1:2"
			if ( typeof cookie === 'string' && cookie.length > 2 && cookie.indexOf( ':' ) !== -1 ) {
				parts = cookie.split( ':' );
				if ( parts.length > 1 && Number( parts[0] ) === options.version ) {
					version = Number( parts[0] );
					bucket = String( parts[1] );
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
					{ path: '/', expires: Number( options.expires ) }
				);
			}

			return bucket;
		},

		/**
		 * Get the current user's groups
		 *
		 * @param {Function} callback
		 */
		getGroups: function ( callback ) {
			getUserInfo( 'groups', callback );
		},

		/**
		 * Get the current user's rights
		 *
		 * @param {Function} callback
		 */
		getRights: function ( callback ) {
			getUserInfo( 'rights', callback );
		}
	};

}( mediaWiki, jQuery ) );
