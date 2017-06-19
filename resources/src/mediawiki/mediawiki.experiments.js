( function ( mw, $ ) {

	var CONTROL_BUCKET = 'control',
		MAX_INT32_UNSIGNED = 4294967295;

	/**
	 * Provides an API for bucketing users in experiments.
	 *
	 * @class mw.experiments
	 * @singleton
	 */
	mw.experiments = {

		/**
		 * Gets the bucket for the experiment given the token.
		 *
		 * The name of the experiment and the token are hashed. The hash is converted
		 * to a number which is then used to get a bucket.
		 *
		 * Consider the following experiment specification:
		 *
		 * ```
		 * {
		 *   name: 'My first experiment',
		 *   enabled: true,
		 *   buckets: {
		 *     control: 0.5
		 *     A: 0.25,
		 *     B: 0.25
		 *   }
		 * }
		 * ```
		 *
		 * The experiment has three buckets: control, A, and B. The user has a 50%
		 * chance of being assigned to the control bucket, and a 25% chance of being
		 * assigned to either the A or B buckets. If the experiment were disabled,
		 * then the user would always be assigned to the control bucket.
		 *
		 * @param {Object} experiment
		 * @param {string} experiment.name The name of the experiment
		 * @param {boolean} experiment.enabled Whether or not the experiment is
		 *  enabled. If the experiment is disabled, then the user is always assigned
		 *  to the control bucket
		 * @param {Object} experiment.buckets A map of bucket name to probability
		 *  that the user will be assigned to that bucket
		 * @param {string} token A token that uniquely identifies the user for the
		 *  duration of the experiment
		 * @return {string} The bucket
		 */
		getBucket: function ( experiment, token ) {
			var buckets = experiment.buckets,
				key,
				range = 0,
				hash,
				max,
				acc = 0;

			if ( !experiment.enabled || $.isEmptyObject( experiment.buckets ) ) {
				return CONTROL_BUCKET;
			}

			for ( key in buckets ) {
				range += buckets[ key ];
			}

			hash = mw.hash.jenkins( experiment.name + ':' + token );
			max = ( hash / MAX_INT32_UNSIGNED ) * range;

			for ( key in buckets ) {
				acc += buckets[ key ];

				if ( max <= acc ) {
					return key;
				}
			}
		}
	};

}( mediaWiki, jQuery ) );
