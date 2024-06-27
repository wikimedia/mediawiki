/*!
 * JavaScript for History action
 */
$( () => {
	var
		$pagehistory = $( '#pagehistory' ),
		$lis = $pagehistory.find( '.mw-contributions-list > li' );

	/**
	 * @ignore
	 * @this Element input
	 * @param {jQuery.Event} e
	 * @return {boolean} False to cancel the default event
	 */
	function updateDiffRadios() {
		var nextState = 'before',
			$li,
			$inputs,
			$oldidRadio,
			$diffRadio;

		if ( !$lis.length ) {
			return true;
		}

		$lis.each( function () {
			$li = $( this );
			$inputs = $li.find( 'input[type="radio"]' );
			$oldidRadio = $inputs.filter( '[name="oldid"]' ).eq( 0 );
			$diffRadio = $inputs.filter( '[name="diff"]' ).eq( 0 );

			$li.removeClass( 'selected between before after' );

			if ( !$oldidRadio.length || !$diffRadio.length ) {
				return true;
			}

			if ( $oldidRadio.prop( 'checked' ) ) {
				$li.addClass( 'selected after' );
				nextState = 'after';
				// Disable the hidden radio because it can still be selected with
				// arrow keys on Firefox
				$diffRadio.prop( 'disabled', true );
			} else if ( $diffRadio.prop( 'checked' ) ) {
				// The following classes are used here:
				// * before
				// * after
				$li.addClass( 'selected ' + nextState );
				nextState = 'between';
				// Disable the hidden radio because it can still be selected with
				// arrow keys on Firefox
				$oldidRadio.prop( 'disabled', true );
			} else {
				// This list item has neither checked
				// apply the appropriate class following the previous item.
				// The following classes are used here:
				// * before
				// * after
				$li.addClass( nextState );
				// Disable or re-enable for Firefox, provided the revision is accessible
				if ( $li.find( 'a.mw-changeslist-date' ).length ) {
					$oldidRadio.prop( 'disabled', nextState === 'before' );
					$diffRadio.prop( 'disabled', nextState === 'after' );
				}
			}
		} );

		return true;
	}

	$pagehistory.on( 'change', 'input[name="diff"], input[name="oldid"]', updateDiffRadios );

	// Set initial state
	updateDiffRadios();
} );
