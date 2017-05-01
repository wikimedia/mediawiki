( function ( mw ) {

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

		assert.equal(
			getBucket( experiment, token ),
			getBucket( experiment, token ),
			'It returns the same bucket for the same experiment-token pair.'
		);

		// --------
		experiment = createExperiment();
		experiment.buckets = {
			A: 0.314159265359
		};

		assert.equal(
			'A',
			getBucket( experiment, token ),
			'It returns the bucket if only one is defined.'
		);

		// --------
		experiment = createExperiment();
		experiment.enabled = false;

		assert.equal(
			'control',
			getBucket( experiment, token ),
			'It returns "control" if the experiment is disabled.'
		);

		// --------
		experiment = createExperiment();
		experiment.buckets = {};

		assert.equal(
			'control',
			getBucket( experiment, token ),
			'It returns "control" if the experiment doesn\'t have any buckets.'
		);
	} );

}( mediaWiki ) );
