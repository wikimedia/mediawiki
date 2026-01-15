/*!
 * JavaScript for Special:EditWatchlist
 */
( function () {
	$( () => {
		let checkAllChangeOngoing = false;
		let selectChangeOngoing = false;

		const $watchedItemCheckboxes = $( '.watchlist-item-checkbox' );
		const $selectAllCheckbox = $( '#select-all-checkbox' );

		$selectAllCheckbox.on( 'change', ( event ) => {
			if ( selectChangeOngoing ) {
				return;
			}
			checkAllChangeOngoing = true;
			const isChecked = event.target.checked;
			$watchedItemCheckboxes.each( ( i, input ) => {
				input.checked = isChecked;
			} );
			checkAllChangeOngoing = false;
			// Dispatch a new `selectall` event after all checkboxes have been changed.
			$selectAllCheckbox[ 0 ].dispatchEvent( new CustomEvent( 'selectall' ) );
		} );

		$watchedItemCheckboxes.on( 'change', () => {
			if ( checkAllChangeOngoing ) {
				return;
			}
			selectChangeOngoing = true;
			const numSelectedItems = $watchedItemCheckboxes.filter( ( i, input ) => input.checked === true ).length;
			if ( numSelectedItems === $watchedItemCheckboxes.length ) {
				$selectAllCheckbox.prop( 'checked', true );
				$selectAllCheckbox.prop( 'indeterminate', false );
			} else if ( numSelectedItems === 0 ) {
				$selectAllCheckbox.prop( 'checked', false );
				$selectAllCheckbox.prop( 'indeterminate', false );
			} else {
				$selectAllCheckbox.prop( 'indeterminate', true );
			}
			selectChangeOngoing = false;
		} );

		$( '#namespace-selector' ).on(
			'change',
			( event ) => {
				event.target.form.submit();
			}
		);
	} );

	if ( mw.config.get( 'watchlistLabels' ) ) {
		const Vue = require( 'vue' );
		const EditWatchlistDialog = require( './EditWatchlistDialog.vue' );
		const removeButton = document.querySelector( '.mw-editwatchlist-remove-selected' );
		if ( removeButton ) {
			const labelButtons = document.createElement( 'span' );
			removeButton.after( ' ', labelButtons );
			Vue.createMwApp( EditWatchlistDialog ).mount( labelButtons );
		}
	}

}() );
