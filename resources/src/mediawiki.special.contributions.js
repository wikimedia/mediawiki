( function () {

	// Return a promise that is resolved when the element is blurred (loses focus).
	// If it already is blurred, resolved immediately.
	function whenBlurred( $elem ) {
		const deferred = $.Deferred();
		if ( $elem.is( ':focus' ) ) {
			$elem.one( 'blur', deferred.resolve );
		} else {
			deferred.resolve();
		}
		return deferred.promise();
	}

	$( () => {
		// Do not infuse the date input while it has user focus.
		// This is especially important on Firefox, where hiding the native date input while the native
		// date picker is open will cause the date picker to remain visible (but non-functional), but
		// not replacing the interface while the user is working with it is probably a good idea anyway.
		const startReady = whenBlurred( $( '#mw-date-start .oo-ui-inputWidget-input' ) ).then( () => mw.widgets.DateInputWidget.static.infuse( $( '#mw-date-start' ) ) );
		const endReady = whenBlurred( $( '#mw-date-end .oo-ui-inputWidget-input' ) ).then( () => mw.widgets.DateInputWidget.static.infuse( $( '#mw-date-end' ) ) );

		$.when( startReady, endReady ).then( ( startInput, endInput ) => {
			startInput.on( 'deactivate', ( userSelected ) => {
				if ( userSelected ) {
					endInput.focus();
				}
			} );
		} );
	} );

}() );
