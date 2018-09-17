/*
 * HTMLForm enhancements:
 * Show fancy tooltips for checkmatrix fields.
 */
( function () {

	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var $matrixTooltips = $root.find( '.mw-htmlform-matrix .mw-htmlform-tooltip' );
		if ( $matrixTooltips.length ) {
			mw.loader.using( 'jquery.tipsy', function () {
				$matrixTooltips.tipsy( { gravity: 's' } );
			} );
		}
	} );

}() );
