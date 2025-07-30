/*!
 * JavaScript for MergeHistory special page
 * This is similar to the JS used for the history action, but allows
 * the same ts for both points, unlike diffs which need two different revisions
 */
$( () => {
	'use strict';

	const $pagehistory = $( '#mw-mergehistory-list' );
	const $lis = $pagehistory.find( '.mw-contributions-list > li' );

	/**
	 * @ignore
	 * @this Element input
	 * @param {jQuery.Event} e
	 * @return {boolean} False to cancel the default event
	 */
	function updateDiffRadios() {
		let disableOld = true;
		let disableNew = false;
		$lis.each( function () {
			const $li = $( this );
			const $inputs = $li.find( 'input[type="radio"]' );
			const $oldRadio = $inputs.filter( '[name="mergepointold"]' ).eq( 0 );
			const $newRadio = $inputs.filter( '[name="mergepoint"]' ).eq( 0 );

			if ( !$oldRadio.length || !$newRadio.length ) {
				return true;
			}
			// Disable the "old" radio buttons unless they are below (or the same as) the selected "new" radio button
			if ( $newRadio.prop( 'checked' ) ) {
				disableOld = false;
			}
			$oldRadio.prop( 'disabled', disableOld );
			$newRadio.prop( 'disabled', disableNew );
			// Disable the "new" radio buttons if they are below the selected "old" radio button
			if ( $oldRadio.prop( 'checked' ) ) {
				disableNew = true;
			}
		} );
		return true;
	}
	$pagehistory.on( 'change', 'input[name="mergepointold"], input[name="mergepoint"]', updateDiffRadios );

	// Set initial state
	updateDiffRadios();
} );
