( function ( mw, $ ) {
	$( function () {
		var startInput = mw.widgets.DateInputWidget.static.infuse( 'mw-date-start' ),
			endInput = mw.widgets.DateInputWidget.static.infuse( 'mw-date-end' );

		startInput.on( 'deactivate', function ( userSelected ) {
			if ( userSelected ) {
				endInput.focus();
			}
		} );
	} );
}( mediaWiki, jQuery ) );
