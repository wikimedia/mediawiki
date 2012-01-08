/**
 * JavaScript for History action
 */
jQuery( document ).ready( function ( $ ) {
	var	$lis = $( '#pagehistory > li' ),
		$radios;

	/**
	 * @context {Element} input
	 * @param e {jQuery.Event}
	 */
	function updateDiffRadios() {
		var diffLi = false, // the li where the diff radio is checked
			oldLi = false; // the li where the oldid radio is checked

		if ( !$lis.length ) {
			return true;
		}

		$lis
		.removeClass( 'selected' )
		.each( function () {
			var	$li = $(this),
				$inputs = $li.find( 'input[type="radio"]' ),
				$oldidRadio = $inputs.filter( '[name="oldid"]' ).eq(0),
				$diffRadio = $inputs.filter( '[name="diff"]' ).eq(0);

			if ( !$oldidRadio.length || !$diffRadio.length ) {
				return true;
			}

			if ( $oldidRadio.prop( 'checked' ) ) { 
				oldLi = true;
				$li.addClass( 'selected' );
				$oldidRadio.css( 'visibility', 'visible' );
				$diffRadio.css( 'visibility', 'hidden' );

			} else if ( $diffRadio.prop( 'checked' ) ) { 
				diffLi = true;
				$li.addClass( 'selected' );
				$oldidRadio.css( 'visibility', 'hidden' );
				$diffRadio.css( 'visibility', 'visible' );

			// This list item has neither checked
			} else { 
				// We're below the selected radios
				if ( diffLi && oldLi ) {
					$oldidRadio.css( 'visibility', 'visible' );
					$diffRadio.css( 'visibility', 'hidden' );

				// We're between the selected radios
	 			} else if ( diffLi ) {
					$diffRadio.css( 'visibility', 'visible' );
					$oldidRadio.css( 'visibility', 'visible' );

				// We're above the selected radios
				} else {
					$diffRadio.css( 'visibility', 'visible' );
					$oldidRadio.css( 'visibility', 'hidden' );
				}
			}
		});

		return true;
	}

	$radios = $( '#pagehistory li input[name="diff"], #pagehistory li input[name="oldid"]' ).click( updateDiffRadios );

	// Set initial state
	updateDiffRadios();
} );
