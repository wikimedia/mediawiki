/*!
 * JavaScript for Special:EditWatchlist
 */
( function () {
	$( function () {
		var $checkAllCheckboxes = $( 'span.mw-watchlistedit-checkall' );

		$checkAllCheckboxes.each( function ( index, el ) {
			var checkbox = OO.ui.infuse( el );
			checkbox.on( 'change', function ( e ) {
				var status = e;
				var namespace = checkbox.elementId.split( 'CheckAllNs' )[ 1 ];
				var $itemsToCheck = $( '.mw-watchlistedit-check' + namespace ).find( 'input[type="checkbox"]' );
				$itemsToCheck.prop( 'checked', status );
			} );
		} );
	} );
}() );
