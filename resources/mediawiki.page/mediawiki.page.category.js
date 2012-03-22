jQuery( document ).ready( function( $ ) {

	$( '#mw-collation-select' ).change( function() {
		$( '#mw-collation-selector' )[0].submit();
	} );

	$( '#mw-collation-go' ).hide();

} );
