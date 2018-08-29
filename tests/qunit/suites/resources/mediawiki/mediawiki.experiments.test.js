( function () {

	var getBucket = mw.experiments.getBucket;

	function createExperiment() {
		return {
			name: 'experiment',
			enabled: true,
			buckets: {
				control: 0.25,
				A: 0.25,
				B: 0.25,
				C: 0.25
			}
		};
	}

	QUnit.module( 'mediawiki.experiments' );

	QUnit.test( 'getBucket( experiment, token )', function ( assert ) {
		var experiment = createExperiment(),
			token = '123457890';

		assert.strictEqual(
			getBucket( experiment, token ),
			getBucket( experiment, token ),
			'It returns the same bucket for the same experiment-token pair.'
		);

		// --------
		experiment = createExperiment();
		experiment.buckets = {
			A: 0.314159265359
		};

		assert.strictEqual(
			getBucket( experiment, token ),
			'A',
			'It returns the bucket if only one is defined.'
		);

		// --------
		experiment = createExperiment();
		experiment.enabled = false;

		assert.strictEqual(
			getBucket( experiment, token ),
			'control',
			'It returns "control" if the experiment is disabled.'
		);

		// --------
		experiment = createExperiment();
		experiment.buckets = {};

		assert.strictEqual(
			getBucket( experiment, token ),
			'control',
			'It returns "control" if the experiment doesn\'t have any buckets.'
		);
	} );

}() );
