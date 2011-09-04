/*
 * Implementation for mediaWiki.log stub
 */

(function( $ ) {

	/**
	 * User object
	 */
	function User() {

		/* Private Members */

		var that = this;

		/* Public Members */

		this.options = new mw.Map();

		this.tokens = new mw.Map();

		/* Public Methods */

		/**
		 * Generates a random user session ID (32 alpha-numeric characters).
		 *
		 * This information would potentially be stored in a cookie to identify a user during a
		 * session or series of sessions. It's uniqueness should not be depended on.
		 *
		 * @return String: Random set of 32 alpha-numeric characters
		 */
		function generateId() {
			var id = '';
			var seed = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
			for ( var i = 0, r; i < 32; i++ ) {
				r = Math.floor( Math.random() * seed.length );
				id += seed.substring( r, r + 1 );
			}
			return id;
		}

		/**
		 * Gets the current user's name.
		 *
		 * @return Mixed: User name string or null if users is anonymous
		 */
		this.name = function() {
			return mw.config.get( 'wgUserName' );
		};

		/**
		 * Checks if the current user is anonymous.
		 *
		 * @return Boolean
		 */
		this.anonymous = function() {
			return that.name() ? false : true;
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
			if ( typeof sessionId == 'undefined' || sessionId === null ) {
				sessionId = generateId();
				$.cookie( 'mediaWiki.user.sessionId', sessionId, { 'expires': null, 'path': '/' } );
			}
			return sessionId;
		};

		/**
		 * Gets the current user's name or a random ID automatically generated and kept in a cookie.
		 *
		 * This ID is persistent for anonymous users, staying in their browser up to 1 year. The
		 * expiration time is reset each time the ID is queried, so in most cases this ID will
		 * persist until the browser's cookies are cleared or the user doesn't visit for 1 year.
		 *
		 * @return String: User name or random session ID
		 */
		this.id = function() {
			var name = that.name();
			if ( name ) {
				return name;
			}
			var id = $.cookie( 'mediaWiki.user.id' );
			if ( typeof id == 'undefined' || id === null ) {
				id = generateId();
			}
			// Set cookie if not set, or renew it if already set
			$.cookie( 'mediaWiki.user.id', id, { 'expires': 365, 'path': '/' } );
			return id;
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
		 * @param options.tracked Boolean: Track the event of bucketing through the API module of
		 * the ClickTracking extension (optional, default: false)
		 * @param options.expires Number: Length of time (in days) until the user gets rebucketed
		 * (optional, default: 30)
		 * @return String: Bucket name - the randomly chosen key of the options.buckets object
		 *
		 * @example
		 *     mw.user.bucket( 'test', {
		 *         'buckets': { 'ignored': 50, 'control': 25, 'test': 25 },
		 *         'version': 1,
		 *         'tracked': true,
		 *         'expires': 7
		 *     } );
		 */
		this.bucket = function( key, options ) {
			options = $.extend( {
				'buckets': {},
				'version': 0,
				'tracked': false,
				'expires': 30
			}, options || {} );
			var cookie = $.cookie( 'mediaWiki.user.bucket:' + key );
			var bucket = null;
			var version = 0;
			// Bucket information is stored as 2 integers, together as version:bucket like: "1:2"
			if ( typeof cookie === 'string' && cookie.length > 2 && cookie.indexOf( ':' ) > 0 ) {
				var parts = cookie.split( ':' );
				if ( parts.length > 1 && parts[0] == options.version ) {
					version = Number( parts[0] );
					bucket = String( parts[1] );
				}
			}
			if ( bucket === null ) {
				if ( !$.isPlainObject( options.buckets ) ) {
					throw 'Invalid buckets error. Object expected for options.buckets.';
				}
				version = Number( options.version );
				// Find range
				var	range = 0, k;
				for ( k in options.buckets ) {
					range += options.buckets[k];
				}
				// Select random value within range
				var rand = Math.random() * range;
				// Determine which bucket the value landed in
				var total = 0;
				for ( k in options.buckets ) {
					bucket = k;
					total += options.buckets[k];
					if ( total >= rand ) {
						break;
					}
				}
				if ( options.tracked ) {
					mw.loader.using( 'jquery.clickTracking', function() {
						$.trackAction(
							'mediaWiki.user.bucket:' + key + '@' + version + ':' + bucket
						);
					} );
				}
				$.cookie(
					'mediaWiki.user.bucket:' + key,
					version + ':' + bucket,
					{ 'path': '/', 'expires': Number( options.expires ) }
				);
			}
			return bucket;
		};
	}

	mw.user = new User();

})(jQuery);