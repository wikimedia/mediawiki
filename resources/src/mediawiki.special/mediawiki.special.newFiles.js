/*!
 * JavaScript for Special:NewFiles
 */
( function ( mw, $ ) {
	$( function () {
		var start = mw.widgets.datetime.DateTimeInputWidget.static.infuse( 'mw-input-start' ),
			end = mw.widgets.datetime.DateTimeInputWidget.static.infuse( 'mw-input-end' ),
			temp;

		// If the start date comes after the end date, swap the two values.
		// This swap is already done internally when the form is submitted with a start date that
		// comes after the end date, but this swap makes the change visible in the HTMLForm.
		if ( start.getValue() !== '' &&
			end.getValue() !== '' &&
			start.getValue() > end.getValue() ) {
			temp = start.getValue();
			start.setValue( end.getValue() );
			end.setValue( temp );
		}
	} );
}( mediaWiki, jQuery ) );
