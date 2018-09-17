( function () {

	// Return a promise that is resolved when the element is blurred (loses focus).
	// If it already is blurred, resolved immediately.
	function whenBlurred( $elem ) {
		var deferred = $.Deferred();
		if ( $elem.is( ':focus' ) ) {
			$elem.one( 'blur', deferred.resolve );
		} else {
			deferred.resolve();
		}
		return deferred.promise();
	}

	$( function () {
		var startReady, endReady;

		// Do not infuse the date input while it has user focus.
		// This is especially important on Firefox, where hiding the native date input while the native
		// date picker is open will cause the date picker to remain visible (but non-functional), but
		// not replacing the interface while the user is working with it is probably a good idea anyway.
		startReady = whenBlurred( $( '#mw-date-start .oo-ui-inputWidget-input' ) ).then( function () {
			return mw.widgets.DateInputWidget.static.infuse( 'mw-date-start' );
		} );
		endReady = whenBlurred( $( '#mw-date-end .oo-ui-inputWidget-input' ) ).then( function () {
			return mw.widgets.DateInputWidget.static.infuse( 'mw-date-end' );
		} );

		$.when( startReady, endReady ).then( function ( startInput, endInput ) {
			startInput.on( 'deactivate', function ( userSelected ) {
				if ( userSelected ) {
					endInput.focus();
				}
			} );
		} );
	} );

}() );
