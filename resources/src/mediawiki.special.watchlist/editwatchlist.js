/*!
 * JavaScript for Special:EditWatchlist
 */
( function () {
	$( () => {
		var checkAllChangeOngoing = false;
		var multiselectChangeOngoing = false;

		var checkAllCheckboxes = $( '.mw-watchlistedit-checkall .oo-ui-checkboxInputWidget' )
			.toArray()
			.map( ( element ) => OO.ui.infuse( element ) );

		var multiselects = $( '.mw-watchlistedit-check .oo-ui-checkboxMultiselectInputWidget' )
			.toArray()
			.map( ( element ) => OO.ui.infuse( element ).checkboxMultiselectWidget );

		checkAllCheckboxes.forEach( ( checkbox, index ) => {
			checkbox.on( 'change', ( isChecked ) => {
				if ( multiselectChangeOngoing ) {
					return;
				}
				checkAllChangeOngoing = true;

				// Select or de-select all the title checkboxes for this namespace
				var multiselect = multiselects[ index ];
				multiselect.selectItems( isChecked ? multiselect.items : [] );

				checkAllChangeOngoing = false;
			} );
		} );

		multiselects.forEach( ( multiselect, index ) => {
			multiselect.on( 'change', () => {
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
