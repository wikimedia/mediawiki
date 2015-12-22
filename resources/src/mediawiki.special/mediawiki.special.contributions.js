/*!
 * JavaScript for Special:Preferences
 */
( function ( mw, $ ) {
	$( function () {
		var hiddenFields,
			$year = $( '#year' ),
			$month = $( '#month' ),
			$day = $( '#day' ),
			dateField, dateWidget;


		// Hide old fields (but don't remove them)
		$year.hide();
		$( 'label[for="year"]' ).hide();
		$month.hide();
		$( 'label[for="month"]' ).hide();
		$day.hide();
		$( 'label[for="day"]' ).hide();

		// Add fancy new field!
		dateWidget = new mw.widgets.DateInputWidget();
		dateField = new OO.ui.FieldLayout(
			dateWidget,
			{
				align: 'right',
				label: 'Filter blah blah'
			}
		);

		$day.after(dateField.$element);

		// Set current value into the widget if we have a date already selected
		if ( $year.val() && $month.val() != -1 && $day.val() ) {
			dateWidget.setValue($year.val() + '-' + $month.val() + '-' + $day.val());
		}

		// Update legacy hidden fields on change so everything works as before
		dateWidget.on(
			'change',
			function () {
				var dateStr = dateWidget.getValue(), date;

				if (dateStr.length > 0) {
					date = new Date(dateStr);
					$year.val(date.getUTCFullYear());
					$month.val(date.getUTCMonth() + 1);
					$day.val(date.getUTCDate());
				}
			}
		);


	} );
}( mediaWiki, jQuery ) );
