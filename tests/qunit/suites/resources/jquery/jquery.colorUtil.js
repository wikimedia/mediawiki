module( 'jquery.colorUtil.js' );

test( '-- Initial check', function() {
	expect(1);
	ok( $.colorUtil, '$.colorUtil defined' );
});

test( 'getRGB', function() {
	expect(18);

	strictEqual( $.colorUtil.getRGB(), undefined, 'No arguments' );
	strictEqual( $.colorUtil.getRGB( '' ), undefined, 'Empty string' );
	deepEqual( $.colorUtil.getRGB( [0, 100, 255] ), [0, 100, 255], 'Parse array of rgb values' );
	deepEqual( $.colorUtil.getRGB( 'rgb(0,100,255)' ), [0, 100, 255], 'Parse simple rgb string' );
	deepEqual( $.colorUtil.getRGB( 'rgb(0, 100, 255)' ), [0, 100, 255], 'Parse simple rgb string with spaces' );
	deepEqual( $.colorUtil.getRGB( 'rgb(0%,20%,40%)' ), [0, 51, 102], 'Parse rgb string with percentages' );
	deepEqual( $.colorUtil.getRGB( 'rgb(0%, 20%, 40%)' ), [0, 51, 102], 'Parse rgb string with percentages and spaces' );
	deepEqual( $.colorUtil.getRGB( '#f2ddee' ), [242, 221, 238], 'Hex string: 6 char lowercase' );
	deepEqual( $.colorUtil.getRGB( '#f2DDEE' ), [242, 221, 238], 'Hex string: 6 char uppercase' );
	deepEqual( $.colorUtil.getRGB( '#f2DdEe' ), [242, 221, 238], 'Hex string: 6 char mixed' );
	deepEqual( $.colorUtil.getRGB( '#eee' ), [238, 238, 238], 'Hex string: 3 char lowercase' );
	deepEqual( $.colorUtil.getRGB( '#EEE' ), [238, 238, 238], 'Hex string: 3 char uppercase' );
	deepEqual( $.colorUtil.getRGB( '#eEe' ), [238, 238, 238], 'Hex string: 3 char mixed' );
	deepEqual( $.colorUtil.getRGB( 'rgba(0, 0, 0, 0)' ), [255, 255, 255], 'Zero rgba for Safari 3; Transparent (whitespace)' );

	// Perhaps this is a bug in colorUtil, but it is the current behaviour so, let's keep
	// track of it, so we will know in case it would ever change.
	strictEqual( $.colorUtil.getRGB( 'rgba(0,0,0,0)' ), undefined, 'Zero rgba without whitespace' );

	deepEqual( $.colorUtil.getRGB( 'lightGreen' ), [144, 238, 144], 'Color names (lightGreen)' );
	deepEqual( $.colorUtil.getRGB( 'transparent' ), [255, 255, 255], 'Color names (transparent)' );
	strictEqual( $.colorUtil.getRGB( 'mediaWiki' ), undefined, 'Inexisting color name' );
});

test( 'rgbToHsl', function() {
	expect(1);

	var hsl = $.colorUtil.rgbToHsl( 144, 238, 144 );

	// Cross-browser differences in decimals...
	// Round to two decimals so they can be more reliably checked.
	var dualDecimals = function(a,b){
		return Math.round(a*100)/100;
	};
	// Re-create the rgbToHsl return array items, limited to two decimals.
	var ret = [dualDecimals(hsl[0]), dualDecimals(hsl[1]), dualDecimals(hsl[2])];

	deepEqual( ret, [0.33, 0.73, 0.75], 'rgb(144, 238, 144): hsl(0.33, 0.73, 0.75)' );
});

test( 'hslToRgb', function() {
	expect(1);

	var rgb = $.colorUtil.hslToRgb( 0.3, 0.7, 0.8 );

	// Cross-browser differences in decimals...
	// Re-create the hslToRgb return array items, rounded to whole numbers.
	var ret = [Math.round(rgb[0]), Math.round(rgb[1]), Math.round(rgb[2])];

	deepEqual( ret ,[183, 240, 168], 'hsl(0.3, 0.7, 0.8): rgb(183, 240, 168)' );
});

test( 'getColorBrightness', function() {
	expect(2);

	var a = $.colorUtil.getColorBrightness( 'red', +0.1 );
	equal( a, 'rgb(255,50,50)', 'Start with named color "red", brighten 10%' );

	var b = $.colorUtil.getColorBrightness( 'rgb(200,50,50)', -0.2 );
	equal( b, 'rgb(118,29,29)', 'Start with rgb string "rgb(200,50,50)", darken 20%' );
});
