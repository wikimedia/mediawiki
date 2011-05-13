// Lazy load jquery.tablesorter

( function( $ ) {
	if ( $( 'table.sortable' ).length ) {
		mw.loader.using( 'jquery.tablesorter', function() {
			$( 'table.sortable' ).tablesorter(); 
		} );
	}
} )( jQuery );