module( 'jquery.colorUtil.js' );

test( '-- Initial check', function(){

	ok( jQuery.colorUtil, 'jQuery.colorUtil defined' );
});

test( 'getRGB', function(){

	equal( typeof jQuery.colorUtil.getRGB(), 'undefined', 'No arguments' );
	equal( typeof jQuery.colorUtil.getRGB( '' ), 'undefined', 'Empty string' );
	deepEqual( jQuery.colorUtil.getRGB( [0, 100, 255] ), [0, 100, 255], 'Array' );
	deepEqual( jQuery.colorUtil.getRGB( 'rgb(0,100,255)' ), [0, 100, 255], 'Parse simple string' );
	deepEqual( jQuery.colorUtil.getRGB( 'rgb(0, 100, 255)' ), [0, 100, 255], 'Parse simple string (whitespace)' );
	deepEqual( jQuery.colorUtil.getRGB( 'rgb(0%,20%,40%)' ), [0, 51, 102], 'Parse percentages string' );
	deepEqual( jQuery.colorUtil.getRGB( 'rgb(0%, 20%, 40%)' ), [0, 51, 102], 'Parse percentages string (whitespace)' );
	deepEqual( jQuery.colorUtil.getRGB( '#f2ddee' ), [242, 221, 238], 'Hex string: 6 char lowercase' );
	deepEqual( jQuery.colorUtil.getRGB( '#f2DDEE' ), [242, 221, 238], 'Hex string: 6 char uppercase' );
	deepEqual( jQuery.colorUtil.getRGB( '#f2DdEe' ), [242, 221, 238], 'Hex string: 6 char mixed' );
	deepEqual( jQuery.colorUtil.getRGB( '#eee' ), [238, 238, 238], 'Hex string: 3 char lowercase' );
	deepEqual( jQuery.colorUtil.getRGB( '#EEE' ), [238, 238, 238], 'Hex string: 3 char uppercase' );
	deepEqual( jQuery.colorUtil.getRGB( '#eEe' ), [238, 238, 238], 'Hex string: 3 char mixed' );
	deepEqual( jQuery.colorUtil.getRGB( 'rgba(0, 0, 0, 0)' ), [255, 255, 255], 'Zero rgba for Safari 3; Transparent (whitespace)' );
	// Perhaps this is a bug in colorUtil, but it is the current behaviour so, let's keep track
	// would that ever chnge
	equal( typeof jQuery.colorUtil.getRGB( 'rgba(0,0,0,0)' ), 'undefined', 'Zero rgba without whitespace' );
	
	deepEqual( jQuery.colorUtil.getRGB( 'lightGreen' ), [144, 238, 144], 'Color names (lightGreen)' );
	deepEqual( jQuery.colorUtil.getRGB( 'lightGreen' ), [144, 238, 144], 'Color names (transparent)' );
	equal( typeof jQuery.colorUtil.getRGB( 'mediaWiki' ), 'undefined', 'Inexisting Color name' );

});

test( 'rgbToHsl', function(){
	var hsl = jQuery.colorUtil.rgbToHsl( 144, 238, 144 );
	var dualDecimals = function(a,b){
		return Math.round(a*100)/100;
	};

	ok( hsl, 'Basic return evaluation' );
	deepEqual( dualDecimals(hsl[0]) , 0.33, 'rgb(144, 238, 144): H 0.33' );
	deepEqual( dualDecimals(hsl[1]) , 0.73, 'rgb(144, 238, 144): S 0.73' );
	deepEqual( dualDecimals(hsl[2]) , 0.75, 'rgb(144, 238, 144): L 0.75' );

});

test( 'hslToRgb', function(){
	var rgb = jQuery.colorUtil.hslToRgb( 0.3, 0.7, 0.8 );

	ok( rgb, 'Basic return evaluation' );
	deepEqual( Math.round(rgb[0]) , 183, 'hsl(0.3, 0.7, 0.8): R 183' );
	deepEqual( Math.round(rgb[1]) , 240, 'hsl(0.3, 0.7, 0.8): G 240' );
	deepEqual( Math.round(rgb[2]) , 168, 'hsl(0.3, 0.7, 0.8): B 168' );

});

test( 'getColorBrightness', function(){

	var a = jQuery.colorUtil.getColorBrightness( 'red', +0.1 );

	equal( a, 'rgb(255,50,50)', 'Start with named color, brighten 10%' );
	
	var b = jQuery.colorUtil.getColorBrightness( 'rgb(200,50,50)', -0.2 );
	
	equal( b, 'rgb(118,29,29)', 'Start with rgb string, darken 10%' );

});
