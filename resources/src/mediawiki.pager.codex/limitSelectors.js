/**
 * Implements the functionality of the limit selectors, such that when a value is selected
 * the page is refreshed to reflect the new limit
 *
 * @param {string|*} documentRoot A Document or selector to use as the root of the
 *   search for elements. Optional, will default to the document if not provided.
 */
module.exports = ( documentRoot ) => {
	if ( !documentRoot ) {
		documentRoot = document;
	}

	/**
	 * When the items-per-page limit changes, submit the form to reload the page and show the new
	 * number of items.
	 */
	function onLimitChange() {
		$( this ).parent( 'form' ).trigger( 'submit' );
	}

	const $limitSelectors = $( '.cdx-table-pager__limit-form .cdx-select', documentRoot );
	$limitSelectors.on( 'change', onLimitChange );

	// See if any of the limit selectors on the page have changed in the time between the page loading and
	// the script loading. If this happens, then refresh the form with the limit for the first changed
	// selector.
	$limitSelectors.each( function () {
		if ( $( this ).val() !== mw.config.get( 'wgCodexTablePagerLimit' ).toString() ) {
			onLimitChange();
			return false;
		}
	} );
};
