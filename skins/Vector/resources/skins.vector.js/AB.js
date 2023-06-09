/** @module WebABTest */

const EXCLUDED_BUCKET = 'unsampled';
const TREATMENT_BUCKET_SUBSTRING = 'treatment';
const WEB_AB_TEST_ENROLLMENT_HOOK = 'mediawiki.web_AB_test_enrollment';

let initialized = false;

/**
 * @typedef {Function} TreatmentBucketFunction
 * @param {string} [a]
 * @return {boolean}
 */

/**
 * @typedef {Object} WebABTest
 * @property {string} name
 * @property {function(): string} getBucket
 * @property {function(string): boolean} isInBucket
 * @property {function(): boolean} isInSample
 * @property {TreatmentBucketFunction} isInTreatmentBucket
 */

/**
 * @typedef {Object} SamplingRate
 * @property {number} samplingRate The desired sampling rate for the group in the range [0, 1].
 */

/**
 * @typedef {Object} WebABTestProps
 * @property {string} name The name of the experiment.
 * @property {Record<string, SamplingRate>} buckets Dict with bucket name as key and SamplingRate
 * object as value. There must be an `unsampled` bucket that represents a
 * population excluded from the experiment. Additionally, the treatment
 * bucket(s) must include a case-insensitive `treatment` substring in their name
 * (e.g. `treatment`, `stickyHeaderTreatment`, `sticky-header-treatment`).
 * @property {string} [token] Token that uniquely identifies the subject for the
 * duration of the experiment. If bucketing/server occurs on the server and the
 * bucket is a class on the body tag, this can be ignored. Otherwise, it is
 * required.
 */

/**
 * Initializes an AB test experiment.
 *
 * Example props:
 *
 * webABTest({
 *  name: 'nameOfExperiment',
 *  buckets: {
 *    unsampled: {
 *      samplingRate: 0.25
 *    },
 *    control: {
 *      samplingRate: 0.25
 *    },
 *    treatment1: {
 *      samplingRate: 0.25
 *    },
 *    treatment2: {
 *      samplingRate: 0.25
 *    }
 *  },
 *  token: 'token'
 * });
 *
 * @param {WebABTestProps} props
 * @param {string} token
 * @param {boolean} [forceInit] forces initialization of init event.
 *  Bypasses caching. Do not use outside tests.
 * @return {WebABTest}
 */
module.exports = function webABTest( props, token, forceInit ) {
	let /** @type {string} */ cachedBucket;

	/**
	 * Get the names of all the buckets from props.buckets.
	 *
	 * @return {string[]}
	 */
	function getBucketNames() {
		return Object.keys( props.buckets );

	}

	/**
	 * Get the name of the bucket from the class added to the body tag.
	 *
	 * @return {?string}
	 */
	function getBucketFromHTML() {
		for ( const bucketName of getBucketNames() ) {
			if ( document.body.classList.contains( `${props.name}-${bucketName}` ) ) {
				return bucketName;
			}
		}
		if ( props.name === 'skin-vector-zebra-experiment' ) {
			return document.documentElement.classList.contains(
				'vector-feature-zebra-design-enabled'
			) ? 'treatment' : 'control';
		}

		return null;
	}

	/**
	 * Get the name of the bucket the subject is assigned to for A/B testing.
	 *
	 * @throws {Error} Will throw an error if token is undefined and body tag has
	 * not been assigned a bucket.
	 * @return {string} the name of the bucket the subject is assigned.
	 */
	function getBucket() {
		if ( cachedBucket ) {
			// If we've already cached the bucket, return early.
			return cachedBucket;
		}

		const bucketFromHTML = getBucketFromHTML();
		// If bucketing/sampling already occurred on the server, return that bucket
		// instead of trying to do it on the client.
		if ( bucketFromHTML ) {
			cachedBucket = bucketFromHTML;

			return bucketFromHTML;
		}

		if ( token === undefined ) {
			throw new Error( 'Tried to call `getBucket` with an undefined token' );
		}

		cachedBucket = mw.experiments.getBucket( {
			name: props.name,
			enabled: true,
			buckets: getBucketNames().reduce(
				( /** @type {Record<string, number>} */ buckets, key ) => {
					buckets[ key ] = props.buckets[ key ].samplingRate;
					return buckets;
				}, {} )
		}, token );

		return cachedBucket;
	}

	/**
	 * Whether or not the subject is included in the experiment.
	 *
	 * @return {boolean}
	 */
	function isInSample() {
		return getBucket() !== EXCLUDED_BUCKET;
	}

	/**
	 * Determine if subject is in a particular bucket.
	 *
	 * @param {string} targetBucket The target test bucket.
	 * @return {boolean} Whether the subject is in the test bucket.
	 */
	function isInBucket( targetBucket ) {
		return getBucket() === targetBucket;
	}

	/**
	 * Whether or not the subject has been bucketed in a treatment bucket as
	 * defined by the bucket name containing the case-insensitive 'treatment',
	 * 'treatment1', or 'treatment2' substring (e.g. 'treatment', 'treatment1',
	 * 'sticky-header-treatment1' and 'stickyHeaderTreatment2' are all assumed
	 * to be treatment buckets).
	 *
	 * @param {string|null} [treatmentBucketName] lowercase name of bucket.
	 * @return {boolean}
	 */
	function isInTreatmentBucket( treatmentBucketName = '' ) {
		const bucketLowerCase = getBucket().toLowerCase();
		// Array.prototype.includes` is ES7
		return bucketLowerCase.indexOf( `${TREATMENT_BUCKET_SUBSTRING}${treatmentBucketName}` ) > -1;
	}

	/**
	 * Initialize and fire hook to register A/B test enrollment if necessary.
	 */
	function init() {
		// Send data to WikimediaEvents to log A/B test initialization if the subject
		// has been sampled into the experiment.
		if ( isInSample() ) {
			mw.hook( WEB_AB_TEST_ENROLLMENT_HOOK ).fire( {
				group: getBucket(),
				experimentName: props.name
			} );
			// don't run this again.
			initialized = true;
		}
	}

	if ( !initialized || forceInit ) {
		init();
	}

	/**
	 * @typedef {Object} WebABTest
	 * @property {string} name
	 * @property {getBucket} getBucket
	 * @property {isInBucket} isInBucket
	 * @property {isInSample} isInSample
	 * @property {isInTreatmentBucket} isInTreatmentBucket
	 */
	return {
		name: props.name,
		getBucket,
		isInBucket,
		isInSample,
		isInTreatmentBucket
	};
};
