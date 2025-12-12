( function () {
	const deleteButton = document.querySelector( '.mw-specialwatchlistlabels-delete-button' );
	const checkboxes = document.querySelectorAll( 'input[type="checkbox"][name="wll_ids[]"]' );
	if ( !deleteButton || !checkboxes ) {
		return;
	}

	// Disable initially, as no checkboxes are ever selected on page load.
	deleteButton.classList.add( 'cdx-field--disabled' );
	deleteButton.disabled = true;

	const checked = new Map();
	checkboxes.forEach(
		( checkbox ) => checkbox.addEventListener( 'change', ( e ) => {
			if ( e.target.checked ) {
				checked.set( e.target.value, true );
			} else {
				checked.delete( e.target.value );
			}
			deleteButton.disabled = checked.size === 0;
		} )
	);

}() );
