/*!
 * JavaScript for Special:EditWatchlist
 */
( function () {
	$( function () {
		var checkAllChangeOngoing = false;
		var multiselectChangeOngoing = false;

		var checkAllCheckboxes = $( '.mw-watchlistedit-checkall .oo-ui-checkboxInputWidget' )
			.toArray()
			.map( function ( element ) {
				return OO.ui.infuse( element );
			} );

		var multiselects = $( '.mw-watchlistedit-check .oo-ui-checkboxMultiselectInputWidget' )
			.toArray()
			.map( function ( element ) {
				return OO.ui.infuse( element ).checkboxMultiselectWidget;
			} );

		checkAllCheckboxes.forEach( function ( checkbox, index ) {
			checkbox.on( 'change', function ( isChecked ) {
				if ( multiselectChangeOngoing ) {
					return;
				}
				checkAllChangeOngoing = true;

				// Select or de-select all the title checkboxes for this namespace,
				// using jQuery since OOUI's selectItems has completely unacceptable
				// performance with as few as 200 items (T335082)
				var multiselect = multiselects[ index ];
				multiselect.$element.find( 'input' ).prop( 'checked', isChecked );

				checkAllChangeOngoing = false;
			} );
		} );

		multiselects.forEach( function ( multiselect, index ) {
			multiselect.on( 'change', function () {
				if ( checkAllChangeOngoing ) {
					return;
				}
				multiselectChangeOngoing = true;

				// Update the state of the check-all checkbox for this namespace
				var checkAllCheckbox = checkAllCheckboxes[ index ];
				var numSelectedItems = multiselect.findSelectedItems().length;
				if ( numSelectedItems === multiselect.items.length ) {
					checkAllCheckbox.setSelected( true );
					checkAllCheckbox.setIndeterminate( false );
				} else if ( numSelectedItems === 0 ) {
					checkAllCheckbox.setSelected( false );
					checkAllCheckbox.setIndeterminate( false );
				} else {
					checkAllCheckbox.setIndeterminate( true );
				}

				multiselectChangeOngoing = false;
			} );
		} );
	} );
}() );
