module( 'jquery.colorUtil.js' );

test( '-- Initial check', function(){

	ok( jQuery.colorUtil, 'jQuery.colorUtil defined' );
});

test( 'getRGB', function(){

	equals( typeof jQuery.colorUtil.getRGB(), 'undefined', 'No arguments' );
	equals( typeof jQuery.colorUtil.getRGB( '' ), 'undefined', 'Empty string' );
	same( jQuery.colorUtil.getRGB( [0, 100, 255] ), [0, 100, 255], 'Array' );
	same( jQuery.colorUtil.getRGB( 'rgb(0,100,255)' ), [0, 100, 255], 'Parse simple string' );
	same( jQuery.colorUtil.getRGB( 'rgb(0, 100, 255)' ), [0, 100, 255], 'Parse simple string (whitespace)' );
	same( jQuery.colorUtil.getRGB( 'rgb(0%,20%,40%)' ), [0, 51, 102], 'Parse percentages string' );
	same( jQuery.colorUtil.getRGB( 'rgb(0%, 20%, 40%)' ), [0, 51, 102], 'Parse percentages string (whitespace)' );
	same( jQuery.colorUtil.getRGB( '#f2ddee' ), [242, 221, 238], 'Hex string: 6 char lowercase' );
	same( jQuery.colorUtil.getRGB( '#f2DDEE' ), [242, 221, 238], 'Hex string: 6 char uppercase' );
	same( jQuery.colorUtil.getRGB( '#f2DdEe' ), [242, 221, 238], 'Hex string: 6 char mixed' );
	same( jQuery.colorUtil.getRGB( '#eee' ), [238, 238, 238], 'Hex string: 3 char lowercase' );
	same( jQuery.colorUtil.getRGB( '#EEE' ), [238, 238, 238], 'Hex string: 3 char uppercase' );
	same( jQuery.colorUtil.getRGB( '#eEe' ), [238, 238, 238], 'Hex string: 3 char mixed' );
	same( jQuery.colorUtil.getRGB( 'rgba(0, 0, 0, 0)' ), [255, 255, 255], 'Zero rgba for Safari 3; Transparent (whitespace)' );
	// Perhaps this is a bug in colorUtil, but it is the current behaviour so, let's keep track
	// would that ever chnge
	equals( typeof jQuery.colorUtil.getRGB( 'rgba(0,0,0,0)' ), 'undefined', 'Zero rgba without whitespace' );
	
	same( jQuery.colorUtil.getRGB( 'lightGreen' ), [144, 238, 144], 'Color names (lightGreen)' );
	same( jQuery.colorUtil.getRGB( 'lightGreen' ), [144, 238, 144], 'Color names (transparent)' );
	equals( typeof jQuery.colorUtil.getRGB( 'mediaWiki' ), 'undefined', 'Inexisting Color name' );

});

test( 'rgbToHsl', function(){
	var hsl = jQuery.colorUtil.rgbToHsl( 144, 238, 144 );
	var dualDecimals = function(a,b){
		return Math.round(a*100)/100;
	};

	ok( hsl, 'Basic return evaluation' );
	same( dualDecimals(hsl[0]) , 0.33, 'rgb(144, 238, 144): H 0.33' );
	same( dualDecimals(hsl[1]) , 0.73, 'rgb(144, 238, 144): S 0.73' );
	same( dualDecimals(hsl[2]) , 0.75, 'rgb(144, 238, 144): L 0.75' );

});

test( 'hslToRgb', function(){
	var rgb = jQuery.colorUtil.hslToRgb( 0.3, 0.7, 0.8 );

	ok( rgb, 'Basic return evaluation' );
	same( Math.round(rgb[0]) , 183, 'hsl(0.3, 0.7, 0.8): R 183' );
	same( Math.round(rgb[1]) , 240, 'hsl(0.3, 0.7, 0.8): G 240' );
	same( Math.round(rgb[2]) , 168, 'hsl(0.3, 0.7, 0.8): B 168' );

});

test( 'getColorBrightness', function(){

	var a = jQuery.colorUtil.getColorBrightness( 'red', +0.1 );

	equals( a, 'rgb(255,50,50)', 'Start with named color, brighten 10%' );
	
	var b = jQuery.colorUtil.getColorBrightness( 'rgb(200,50,50)', -0.2 );
	
	equals( b, 'rgb(118,29,29)', 'Start with rgb string, darken 10%' );

});
