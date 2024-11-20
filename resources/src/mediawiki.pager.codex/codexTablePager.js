/*!
 * Progressive enhancements for the CodexTablePager class.
 */
( function () {
	/**
	 * When the items-per-page limit changes, submit the form to reload the page and show the new
	 * number of items.
	 */
	const onLimitChange = () => {
		$( '#cdx-table-pager-limit-form' ).trigger( 'submit' );
	};

	$( () => {
		const $limitSelect = $( '#cdx-table-pager-limit-form .cdx-select' );
		$limitSelect.on( 'change', onLimitChange );

		// If the select has changed between the time the page loaded and this script loaded, submit
		// the form.
		if ( $limitSelect.val() !== mw.config.get( 'wgCodexTablePagerLimit' ).toString() ) {
			onLimitChange();
		}
	} );

}() );
