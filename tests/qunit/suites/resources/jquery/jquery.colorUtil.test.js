( function ( $ ) {
	QUnit.module( 'jquery.colorUtil', QUnit.newMwEnvironment() );

	QUnit.test( 'getRGB', 18, function ( assert ) {
		assert.strictEqual( $.colorUtil.getRGB(), undefined, 'No arguments' );
		assert.strictEqual( $.colorUtil.getRGB( '' ), undefined, 'Empty string' );
		assert.deepEqual( $.colorUtil.getRGB( [ 0, 100, 255 ] ), [ 0, 100, 255 ], 'Parse array of rgb values' );
		assert.deepEqual( $.colorUtil.getRGB( 'rgb(0,100,255)' ), [ 0, 100, 255 ], 'Parse simple rgb string' );
		assert.deepEqual( $.colorUtil.getRGB( 'rgb(0, 100, 255)' ), [ 0, 100, 255 ], 'Parse simple rgb string with spaces' );
		assert.deepEqual( $.colorUtil.getRGB( 'rgb(0%,20%,40%)' ), [ 0, 51, 102 ], 'Parse rgb string with percentages' );
		assert.deepEqual( $.colorUtil.getRGB( 'rgb(0%, 20%, 40%)' ), [ 0, 51, 102 ], 'Parse rgb string with percentages and spaces' );
		assert.deepEqual( $.colorUtil.getRGB( '#f2ddee' ), [ 242, 221, 238 ], 'Hex string: 6 char lowercase' );
		assert.deepEqual( $.colorUtil.getRGB( '#f2DDEE' ), [ 242, 221, 238 ], 'Hex string: 6 char uppercase' );
		assert.deepEqual( $.colorUtil.getRGB( '#f2DdEe' ), [ 242, 221, 238 ], 'Hex string: 6 char mixed' );
		assert.deepEqual( $.colorUtil.getRGB( '#eee' ), [ 238, 238, 238 ], 'Hex string: 3 char lowercase' );
		assert.deepEqual( $.colorUtil.getRGB( '#EEE' ), [ 238, 238, 238 ], 'Hex string: 3 char uppercase' );
		assert.deepEqual( $.colorUtil.getRGB( '#eEe' ), [ 238, 238, 238 ], 'Hex string: 3 char mixed' );
		assert.deepEqual( $.colorUtil.getRGB( 'rgba(0, 0, 0, 0)' ), [ 255, 255, 255 ], 'Zero rgba for Safari 3; Transparent (whitespace)' );

		// Perhaps this is a bug in colorUtil, but it is the current behavior so, let's keep
		// track of it, so we will know in case it would ever change.
		assert.strictEqual( $.colorUtil.getRGB( 'rgba(0,0,0,0)' ), undefined, 'Zero rgba without whitespace' );

		assert.deepEqual( $.colorUtil.getRGB( 'lightGreen' ), [ 144, 238, 144 ], 'Color names (lightGreen)' );
		assert.deepEqual( $.colorUtil.getRGB( 'transparent' ), [ 255, 255, 255 ], 'Color names (transparent)' );
		assert.strictEqual( $.colorUtil.getRGB( 'mediaWiki' ), undefined, 'Inexisting color name' );
	} );

	QUnit.test( 'rgbToHsl', 1, function ( assert ) {
		var hsl, ret;

		// Cross-browser differences in decimals...
		// Round to two decimals so they can be more reliably checked.
		function dualDecimals( a ) {
			return Math.round( a * 100 ) / 100;
		}

		// Re-create the rgbToHsl return array items, limited to two decimals.
		hsl = $.colorUtil.rgbToHsl( 144, 238, 144 );
		ret = [ dualDecimals( hsl[ 0 ] ), dualDecimals( hsl[ 1 ] ), dualDecimals( hsl[ 2 ] ) ];

		assert.deepEqual( ret, [ 0.33, 0.73, 0.75 ], 'rgb(144, 238, 144): hsl(0.33, 0.73, 0.75)' );
	} );

	QUnit.test( 'hslToRgb', 1, function ( assert ) {
		var rgb, ret;
		rgb = $.colorUtil.hslToRgb( 0.3, 0.7, 0.8 );

		// Re-create the hslToRgb return array items, rounded to whole numbers.
		ret = [ Math.round( rgb[ 0 ] ), Math.round( rgb[ 1 ] ), Math.round( rgb[ 2 ] ) ];

		assert.deepEqual( ret, [ 183, 240, 168 ], 'hsl(0.3, 0.7, 0.8): rgb(183, 240, 168)' );
	} );

	QUnit.test( 'getColorBrightness', 2, function ( assert ) {
		var a, b;
		a = $.colorUtil.getColorBrightness( 'red', +0.1 );
		assert.equal( a, 'rgb(255,50,50)', 'Start with named color "red", brighten 10%' );

		b = $.colorUtil.getColorBrightness( 'rgb(200,50,50)', -0.2 );
		assert.equal( b, 'rgb(118,29,29)', 'Start with rgb string "rgb(200,50,50)", darken 20%' );
	} );
}( jQuery ) );
