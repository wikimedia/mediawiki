const AB = require( '../../resources/skins.vector.js/AB.js' );
const NAME_OF_EXPERIMENT = 'name-of-experiment';
const TOKEN = 'token';
const MW_EXPERIMENT_PARAM = {
	name: NAME_OF_EXPERIMENT,
	enabled: true,
	buckets: {
		unsampled: 0.5,
		control: 0.25,
		treatment: 0.25
	}
};

/**
 * @param {string} token
 * @return {AB.WebABTest}
 */
function createInstance( token ) {
	const mergedProps = /** @type {AB.WebABTestProps} */ ( {
		name: NAME_OF_EXPERIMENT,
		buckets: {
			unsampled: {
				samplingRate: 0.5
			},
			control: {
				samplingRate: 0.25
			},
			treatment: {
				samplingRate: 0.25
			}
		}
	} );

	return AB( mergedProps, token, true );
}

describe( 'AB.js', () => {
	const bucket = 'treatment';
	const getBucketMock = jest.fn().mockReturnValue( bucket );
	mw.experiments.getBucket = getBucketMock;

	afterEach( () => {
		document.body.removeAttribute( 'class' );
	} );

	describe( 'initialization when body tag does not contain bucket', () => {
		let /** @type {jest.SpyInstance} */ hookMock;

		beforeEach( () => {
			hookMock = jest.spyOn( mw, 'hook' );
		} );

		it( 'sends data to WikimediaEvents when the bucket is part of sample (e.g. control)', () => {
			getBucketMock.mockReturnValueOnce( 'control' );
			createInstance( TOKEN );
			expect( hookMock ).toHaveBeenCalled();
		} );
		it( 'sends data to WikimediaEvents when the bucket is part of sample (e.g. treatment)', () => {
			getBucketMock.mockReturnValueOnce( 'treatment' );
			createInstance( TOKEN );
			expect( hookMock ).toHaveBeenCalled();
		} );
		it( 'does not send data to WikimediaEvents when the bucket is unsampled ', () => {
			getBucketMock.mockReturnValueOnce( 'unsampled' );
			createInstance( TOKEN );
			expect( hookMock ).not.toHaveBeenCalled();
		} );
	} );

	describe( 'initialization when body tag contains bucket', () => {
		let /** @type {jest.SpyInstance} */ hookMock;

		beforeEach( () => {
			hookMock = jest.spyOn( mw, 'hook' );
		} );

		it( 'sends data to WikimediaEvents when the bucket is part of sample (e.g. control)', () => {
			document.body.classList.add( 'name-of-experiment-control' );
			createInstance( TOKEN );
			expect( hookMock ).toHaveBeenCalled();
		} );
		it( 'sends data to WikimediaEvents when the bucket is part of sample (e.g. treatment)', () => {
			document.body.classList.add( 'name-of-experiment-treatment' );
			createInstance( TOKEN );
			expect( hookMock ).toHaveBeenCalled();
		} );
		it( 'does not send data to WikimediaEvents when the bucket is unsampled ', () => {
			document.body.classList.add( 'name-of-experiment-unsampled' );
			createInstance( TOKEN );
			expect( hookMock ).not.toHaveBeenCalled();
		} );
	} );

	describe( 'initialization when token is undefined', () => {
		it( 'throws an error', () => {
			expect( () => {
				createInstance( undefined );
			} ).toThrow( 'Tried to call `getBucket`' );
		} );
	} );

	describe( 'getBucket when body tag does not contain AB class', () => {
		it( 'calls mw.experiments.getBucket with config data', () => {
			const experiment = createInstance( TOKEN );

			expect( getBucketMock ).toBeCalledWith( MW_EXPERIMENT_PARAM, TOKEN );
			expect( experiment.getBucket() ).toBe( bucket );
		} );
	} );

	describe( 'getBucket when body tag contains AB class that is in the sample', () => {
		it( 'returns the bucket on the body tag', () => {
			document.body.classList.add( 'name-of-experiment-control' );
			const experiment = createInstance( TOKEN );

			expect( getBucketMock ).not.toHaveBeenCalled();
			expect( experiment.getBucket() ).toBe( 'control' );
		} );
	} );

	describe( 'getBucket when body tag contains AB class that is not in the sample', () => {
		it( 'returns the bucket on the body tag', () => {
			document.body.classList.add( 'name-of-experiment-unsampled' );
			const experiment = createInstance( TOKEN );

			expect( getBucketMock ).not.toHaveBeenCalled();
			expect( experiment.getBucket() ).toBe( 'unsampled' );
		} );
	} );

	describe( 'isInBucket', () => {
		it( 'compares assigned bucket with passed in bucket', () => {
			const experiment = createInstance( TOKEN );

			expect( experiment.isInBucket( 'treatment' ) ).toBe( true );
		} );
	} );

	describe( 'isInTreatmentBucket when assigned to unsampled bucket (from server)', () => {
		it( 'returns false', () => {
			document.body.classList.add( 'name-of-experiment-unsampled' );
			const experiment = createInstance( TOKEN );

			expect( experiment.isInTreatmentBucket() ).toBe( false );
		} );
	} );

	describe( 'isInTreatmentBucket when assigned to control bucket (from server)', () => {
		it( 'returns false', () => {
			document.body.classList.add( 'name-of-experiment-control' );
			const experiment = createInstance( TOKEN );

			expect( experiment.isInTreatmentBucket() ).toBe( false );
		} );
	} );

	describe( 'isInTreatmentBucket when assigned to treatment bucket (from server)', () => {
		it( 'returns true', () => {
			document.body.classList.add( 'name-of-experiment-treatment' );
			const experiment = createInstance( TOKEN );

			expect( experiment.isInTreatmentBucket() ).toBe( true );
		} );
	} );

	describe( 'isInTreatmentBucket when assigned to unsampled bucket (from client)', () => {
		it( 'returns false', () => {
			getBucketMock.mockReturnValueOnce( 'unsampled' );
			const experiment = createInstance( TOKEN );

			expect( experiment.isInTreatmentBucket() ).toBe( false );
		} );
	} );

	describe( 'isInTreatmentBucket when assigned to control bucket (from client)', () => {
		it( 'returns false', () => {
			getBucketMock.mockReturnValueOnce( 'control' );
			const experiment = createInstance( TOKEN );

			expect( experiment.isInTreatmentBucket() ).toBe( false );
		} );
	} );

	describe( 'isInTreatmentBucket when assigned to treatment bucket (from client)', () => {
		it( 'returns true', () => {
			getBucketMock.mockReturnValueOnce( 'treatment' );
			const experiment = createInstance( TOKEN );

			expect( experiment.isInTreatmentBucket() ).toBe( true );
		} );
	} );

	describe( 'isInTreatmentBucket when assigned to treatment bucket (is case insensitive)', () => {
		it( 'returns true', () => {
			getBucketMock.mockReturnValueOnce( 'StickyHeaderVisibleTreatment' );
			const experiment = createInstance( TOKEN );

			expect( experiment.isInTreatmentBucket() ).toBe( true );
		} );
	} );

	describe( 'isInSample when in unsampled bucket', () => {
		it( 'returns false', () => {
			document.body.classList.add( 'name-of-experiment-unsampled' );
			const experiment = createInstance( TOKEN );

			expect( experiment.isInSample() ).toBe( false );
		} );
	} );

	describe( 'isInSample when in control bucket', () => {
		it( 'returns true', () => {
			document.body.classList.add( 'name-of-experiment-control' );
			const experiment = createInstance( TOKEN );

			expect( experiment.isInSample() ).toBe( true );
		} );
	} );

	describe( 'isInSample when in treatment bucket', () => {
		it( 'returns true', () => {
			document.body.classList.add( 'name-of-experiment-treatment' );
			const experiment = createInstance( TOKEN );

			expect( experiment.isInSample() ).toBe( true );
		} );
	} );
} );
