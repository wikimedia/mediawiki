( function ( mw, $ ) {

	$( function () {
		mw.hook( 'htmlform.enhance' ).fire( $( document ) );
	} );

}( mediaWiki, jQuery ) );
