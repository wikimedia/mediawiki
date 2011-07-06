/*
 * JavaScript for History action
 */
jQuery( function( $ ) {
	var $lis = $( 'ul#pagehistory li' );
	var updateDiffRadios = function() {
		var diffLi = false, // the li where the diff radio is checked
			oldLi = false; // the li where the oldid radio is checked

		if ( !$lis.length ) {
			return true;
		}
		$lis.removeClass( 'selected' );
		$lis.each( function() {
			var $this = $(this);
			var $inputs = $this.find( 'input[type="radio"]' );
			if ( $inputs.length !== 2 ) {
				return true;
			}

			// this row has a checked radio button
			if ( $inputs.get(0).checked ) { 
				oldLi = true;
				$this.addClass( 'selected' );
				$inputs.eq(0).css( 'visibility', 'visible' );
				$inputs.eq(1).css( 'visibility', 'hidden' );
			} else if ( $inputs.get(1).checked ) {
				diffLi = true;
				$this.addClass( 'selected' );
				$inputs.eq(0).css( 'visibility', 'hidden' );
				$inputs.eq(1).css( 'visibility', 'visible' );
			} else { 
				// no radio is checked in this row
				if ( diffLi && oldLi ) {
					// We're below the selected radios
					$inputs.eq(0).css( 'visibility', 'visible' );
					$inputs.eq(1).css( 'visibility', 'hidden' );
	 			} else if ( diffLi ) {
					// We're between the selected radios
					$inputs.css( 'visibility', 'visible' );
				} else {
					// We're above the selected radios
					$inputs.eq(1).css( 'visibility', 'visible' );
					$inputs.eq(0).css( 'visibility', 'hidden' );
				}
			}
		});
		return true;
	};

	$( '#pagehistory li input[name="diff"], #pagehistory li input[name="oldid"]' ).click( updateDiffRadios );
	updateDiffRadios();
});