( function ( mw, $ ) {

	$( function () {
		mw.hook( 'htmlform.enhance' ).fire( $( document ) );
	} );

	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		// Currently, this is only used for fields with 'hide-if', and the JS enhancements make them
		// properly validable
		$root.find( '.mw-htmlform' ).removeAttr( 'novalidate' );
	} );

}( mediaWiki, jQuery ) );
