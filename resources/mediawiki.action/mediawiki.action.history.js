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

	var fixCompare = function () {
		var $diffList = $( '#pagehistory' ),
		 $histForm = $( '#mw-history-compare' ),
		 $buttons = $histForm.find( 'input.historysubmit' );

		// There's only one rev, nothing to do here
		if ( !$buttons.length ) {
			return false;
		}
		var copyAttrs = ['title', 'accesskey'];
		$buttons.each(function() {
			var $button = $(this),
				$compareLink= $( '<a></a>', {
					'class': 'compare-link',
					'text': $button.val()
				}).button();
			$.each( copyAttrs, function( i, name ) {
				var val = $button.attr( name );
				if (val) {
					$compareLink.attr( name, val );
				}
			});
			$button.replaceWith( $compareLink );
		});
		var updateCompare = function() {
			var $radio = $histForm.find( 'input[type="radio"]:checked' );
			var genLink = mw.config.get( 'wgScript' )
				+ '?title=' + mw.util.wikiUrlencode( mw.config.get( 'wgPageName' ) )
				+ '&diff=' + $radio.eq(0).val()
				+ '&oldid=' + $radio.eq(1).val();
			$( '.compare-link' ).each( function() {
				$(this).attr('href', genLink);
			});
		}
		updateCompare();
		$diffList.change( updateCompare );
	};

	$( '#pagehistory li input[name="diff"], #pagehistory li input[name="oldid"]' ).click( updateDiffRadios );
	fixCompare();
	updateDiffRadios();
});