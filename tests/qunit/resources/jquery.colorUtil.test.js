QUnit.module( 'jquery.colorUtil', () => {

	QUnit.test( 'getRGB [no arguments]', ( assert ) => {
		assert.strictEqual( $.colorUtil.getRGB(), undefined );
	} );

	QUnit.test.each( 'getRGB', {
		'empty string': [ '', undefined ],
		'array of rgb': [ [ 0, 100, 255 ], [ 0, 100, 255 ] ],
		'rgb string': [ 'rgb(0,100,255)', [ 0, 100, 255 ] ],
		'rgb spaces': [ 'rgb(0, 100, 255)', [ 0, 100, 255 ] ],
		'rgb percent': [ 'rgb(0%,20%,40%)', [ 0, 51, 102 ] ],
		'rgb percent spaces': [ 'rgb(0%, 20%, 40%)', [ 0, 51, 102 ] ],
		'hex 6 lowercase': [ '#f2ddee', [ 242, 221, 238 ] ],
		'hex 6 uppercase': [ '#f2DDEE', [ 242, 221, 238 ] ],
		'hex 6 mixed': [ '#f2DdEe', [ 242, 221, 238 ] ],
		'hex 3 lowercase': [ '#eee', [ 238, 238, 238 ] ],
		'hex 3 uppercase': [ '#EEE', [ 238, 238, 238 ] ],
		'hex 3 mixed': [ '#eEe', [ 238, 238, 238 ] ],
		'rgba zeros': [ 'rgba(0, 0, 0, 0)', [ 255, 255, 255 ] ],
		// Known limitation, not yet supported
		'rgba zeros nospace': [ 'rgba(0,0,0,0)', undefined ],
		'literal name lightGreen': [ 'lightGreen', [ 144, 238, 144 ] ],
		'literal keyword transparent': [ 'transparent', [ 255, 255, 255 ] ],
		'literal invalid': [ 'mediaWiki', undefined ]
	}, ( assert, [ input, expected ] ) => {
		assert.deepEqual( $.colorUtil.getRGB( input ), expected );
	} );

	function normalDecimal( a ) {
		return Math.round( a * 100 ) / 100;
	}

	QUnit.test( 'rgbToHsl', ( assert ) => {
		const hsl = $.colorUtil.rgbToHsl( 144, 238, 144 );
		// Limit testing to two decimals to normalize cross-browser differences.
		const ret = [ normalDecimal( hsl[ 0 ] ), normalDecimal( hsl[ 1 ] ), normalDecimal( hsl[ 2 ] ) ];

		assert.deepEqual( ret, [ 0.33, 0.73, 0.75 ], 'rgb(144, 238, 144): hsl(0.33, 0.73, 0.75)' );
	} );

	QUnit.test( 'hslToRgb', ( assert ) => {
		const rgb = $.colorUtil.hslToRgb( 0.3, 0.7, 0.8 );
		// Limit to whole numbers to normalize cros-browser differences.
		const ret = [ Math.round( rgb[ 0 ] ), Math.round( rgb[ 1 ] ), Math.round( rgb[ 2 ] ) ];

		assert.deepEqual( ret, [ 183, 240, 168 ], 'hsl(0.3, 0.7, 0.8): rgb(183, 240, 168)' );
	} );

	QUnit.test( 'getColorBrightness', ( assert ) => {
		let ret;
		ret = $.colorUtil.getColorBrightness( 'red', +0.1 );
		assert.strictEqual( ret, 'rgb(255,50,50)', 'Start with named color "red", brighten 10%' );

		ret = $.colorUtil.getColorBrightness( 'rgb(200,50,50)', -0.2 );
		assert.strictEqual( ret, 'rgb(118,29,29)', 'Start with rgb string "rgb(200,50,50)", darken 20%' );
	} );
} );
