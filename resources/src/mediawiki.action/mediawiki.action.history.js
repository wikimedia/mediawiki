/*!
 * JavaScript for History action
 */
jQuery( function ( $ ) {
	var	$historyCompareForm = $( '#mw-history-compare' ),
		$historySubmitter,
		$lis = $( '#pagehistory > li' );

	/**
	 * @ignore
	 * @context {Element} input
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

		$lis
		.each( function () {
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
			} else if ( $diffRadio.prop( 'checked' ) ) {
				$li.addClass( 'selected ' + nextState );
				nextState = 'between';
			} else {
				// This list item has neither checked
				// apply the appropriate class following the previous item.
				$li.addClass( nextState );
			}
		} );

		return true;
	}

	$lis.find( 'input[name="diff"], input[name="oldid"]' ).click( updateDiffRadios );

	// Set initial state
	updateDiffRadios();

	// Prettify url output for HistoryAction submissions,
	// to cover up action=historysubmit construction.

	// Ideally we'd use e.target instead of $historySubmitter, but e.target points
	// to the form element for submit actions, so.
	$historyCompareForm.find( '.historysubmit' ).click( function () {
		$historySubmitter = $( this );
	} );

	// On submit we clone the form element, remove unneeded fields in the clone
	// that pollute the query parameter with stuff from the other "use case",
	// and then submit the clone.
	// Without the cloning we'd be changing the real form, which is slower, could make
	// the page look broken for a second in slow browsers and might show the form broken
	// again when coming back from a "next" page.
	$historyCompareForm.submit( function ( e ) {
		var	$copyForm, $copyRadios, $copyAction;

		if ( $historySubmitter ) {
			$copyForm = $historyCompareForm.clone();
			$copyRadios = $copyForm.find( '#pagehistory > li' ).find( 'input[name="diff"], input[name="oldid"]' );
			$copyAction = $copyForm.find( '> [name="action"]' );

			// Remove action=historysubmit and ids[..]=..
			if ( $historySubmitter.hasClass( 'mw-history-compareselectedversions-button' ) ) {
				$copyAction.remove();
				$copyForm.find( 'input[name^="ids["]:checked' ).prop( 'checked', false );

			// Remove diff=&oldid=, change action=historysubmit to revisiondelete, remove revisiondelete
			} else if ( $historySubmitter.hasClass( 'mw-history-revisiondelete-button' ) ||
					$historySubmitter.hasClass( 'mw-history-editchangetags-button' ) ) {
				$copyRadios.remove();
				$copyAction.val( $historySubmitter.attr( 'name' ) );
				$copyForm.find( ':submit' ).remove();
			}

			// IE7 doesn't do submission from an off-DOM clone, so insert hidden into document first
			// Also remove potentially conflicting id attributes that we don't need anyway
			$copyForm
				.css( 'display', 'none' )
				.find( '[id]' )
					.removeAttr( 'id' )
				.end()
				.insertAfter( $historyCompareForm )
				.submit();

			e.preventDefault();
			return false; // Because the submit is special, return false as well.
		}

		// Continue natural browser handling other wise
		return true;
	} );
} );
