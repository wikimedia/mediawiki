( function ( mw ) {

	var getBucket = mw.experiments.getBucket;

	function createExperiment() {
		return {
			enabled: true,
			buckets: {
				control: 0.5,
				A: 0.25,
				B: 0.25
			}
		};
	}

	QUnit.module( 'mediawiki.experiments' );

	QUnit.test( 'getBucket( experiments, experiment, token )', 4, function ( assert ) {
		var experiment = 'experiment',
			experiments = {},
			bucket,
			token = '123457890',
			withOneBucket = createExperiment();

		assert.throws(
			function () {
				getBucket( {}, 'foo', 'bar' );
			},
			new Error( 'The "foo" experiment isn\'t defined.' ),
			'It throws an error when the experiment isn\'t defined.'
		);

		// --------
		experiments[experiment] = createExperiment();
		bucket = getBucket( experiments, experiment, token );

		assert.equal(
			bucket,
			getBucket( experiments, experiment, token ),
			'It returns the same bucket for the same experiment-token pair.'
		);

		// --------
		withOneBucket = createExperiment();
		withOneBucket.buckets = {
			A: 0.314159265359
		};

		assert.equal(
			'A',
			getBucket(
				{
					oneBucket: withOneBucket
				},
				'oneBucket',
				token
			)
		);

		// --------
		assert.equal(
			'control',
			getBucket(
				{
					disabled: {
						enabled: false
					}
				},
				'disabled',
				token
			),
			'It returns "control" if the experiment is disabled.'
		);
	} );

}( mediaWiki ) );
