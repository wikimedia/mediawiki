/* jshint bitwise:false */
( function ( mw ) {

	var CONTROL_BUCKET = 'control',
		MAX_INT32_UNSIGNED = 4294967295;

	/**
	 * An implementation of Jenkins' one-at-a-time hash.
	 *
	 * @see http://en.wikipedia.org/wiki/Jenkins_hash_function
	 *
	 * @param {String} key String to hash.
	 * @return {Number} 32-bit integer.
	 * @ignore
	 *
	 * @author Ori Livneh <ori@wikimedia.org>
	 * @see http://jsbin.com/kejewi/4/watch?js,console
	 */
	function hashString( key ) {
		var hash = 0,
			i = key.length;

		while ( i-- ) {
			hash += key.charCodeAt( i );
			hash += ( hash << 10 );
			hash ^= ( hash >> 6 );
		}
		hash += ( hash << 3 );
		hash ^= ( hash >> 11 );
		hash += ( hash << 15 );

		return hash;
	}

	/**
	 * Gets the bucket for the experiment given the token.
	 *
	 * The name of the experiment and the token are hashed. The hash is converted to a number
	 * which is then used to get a bucket.
	 *
	 * Consider the following experiment configuration:
	 *
	 * ```
	 * {
	 *   enabled: true,
	 *   buckets: {
	 *     control: 0.5
	 *     A: 0.25,
	 *     B: 0.25
	 *   }
	 * }
	 * ```
	 *
	 * The experiment has three buckets: control, A, and B. The user has a 50% chance of being
	 * assigned to the control bucket, and a 25% chance of being assigned to either the A or B
	 * buckets. If the experiment were disabled, then the user would always be assigned to the
	 * control bucket.
	 *
	 * This function is based on the deprecated `mw.user.bucket` function.
	 *
	 * @ignore
	 * @param {Object} experiments A map of experiment name to experiment definition
	 * @param {String} experiment
	 * @param {String} token
	 * @throws Error If the experiment hasn't been defined
	 * @returns {String}
	 */
	mw.experiments = {
		getBucket: function ( experiments, experiment, token ) {
			var options,
				buckets,
				key,
				range = 0,
				hash,
				max,
				acc = 0;

			if ( !experiments.hasOwnProperty( experiment ) ) {
				throw new Error( 'The "' + experiment + '" experiment isn\'t defined.' );
			}

			options = experiments[experiment];

			if ( !options.enabled ) {
				return CONTROL_BUCKET;
			}

			buckets = options.buckets;

			for ( key in buckets ) {
				range += buckets[key];
			}

			hash = hashString( experiment + ':' + token );
			max = ( Math.abs( hash ) / MAX_INT32_UNSIGNED ) * range;

			for ( key in buckets ) {
				acc += buckets[key];

				if ( max <= acc ) {
					return key;
				}
			}

			return CONTROL_BUCKET;
		}
	};

}( mediaWiki ) );
