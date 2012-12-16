/**
 * JavaScript for History action
 */
( function ( mw, $ ) {
	$( document ).ready( function () {
		var	$historyCompareForm = $( '#mw-history-compare' ),
			$historySubmitter,
			$lis = $( '#pagehistory > li' ),
			$compareLinks;

		/**
		 * @param {string} oldid earlier revid
		 * @param {string} diff later revid
		 *
		 * @return {string} revision diff compare button
		 */
		function getDiffURI( oldid, diff ) {
			oldid = Number(oldid);
			diff = Number(oldid);
			return mw.config.get( 'wgScript' ) + '?title=' + mw.util.wikiUrlencode( mw.config.get( 'wgPageName' ) ) + '&diff=' + diff + '&oldid=' + oldid;
		}

		/**
		 * Converts revision comparison button to link that uses button styles
		 *
		 * @return new links
		 */
		function convertToLinks() {
			var	$oldidRadio = $historyCompareForm.find( '[name="oldid"]:checked' ),
				$diffRadio = $historyCompareForm.find( '[name="diff"]:checked' ),

				$compareButtons = $historyCompareForm.find( '.mw-history-compareselectedversions-button' ),

				$compareLink;

			if ( $oldidRadio.length !== 0 && $diffRadio.length !== 0 ) {
				$compareLink = $( '<a>' )
					.attr( {
						href: getDiffURI( $oldidRadio.val(), $diffRadio.val() ),
						'class': 'mw-history-compareselectedversions-button'
					} )
					.button( {
						label: $compareButtons.val()
					} );
				$compareButtons.replaceWith( function() {
					return $compareLink.clone();
				} );
			}

			return $historyCompareForm.find( '.mw-history-compareselectedversions-button' );
		}

		/**
		 * @context {Element} input
		 */
		function updateDiffElements() {
			var	$checkedOldidRadio,
				$checkedDiffRadio,
				diffUri;

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
					$li.addClass( 'selected' );
					$checkedOldidRadio = $oldidRadio;
					$oldidRadio.css( 'visibility', 'visible' );
					$diffRadio.css( 'visibility', 'hidden' );

				} else if ( $diffRadio.prop( 'checked' ) ) {
					$li.addClass( 'selected' );
					$checkedDiffRadio = $diffRadio;
					$oldidRadio.css( 'visibility', 'hidden' );
					$diffRadio.css( 'visibility', 'visible' );

				// This list item has neither checked
				} else {
					// We're below the selected radios
					if ( $checkedDiffRadio !== undefined && $checkedOldidRadio !== undefined ) {
						$oldidRadio.css( 'visibility', 'visible' );
						$diffRadio.css( 'visibility', 'hidden' );

					// We're between the selected radios
					} else if ( $checkedDiffRadio !== undefined ) {
						$diffRadio.css( 'visibility', 'visible' );
						$oldidRadio.css( 'visibility', 'visible' );

					// We're above the selected radios
					} else {
						$diffRadio.css( 'visibility', 'visible' );
						$oldidRadio.css( 'visibility', 'hidden' );
					}
				}
			} );

			if ( $checkedOldidRadio !== undefined && $checkedDiffRadio !== undefined ) {
				diffUri = getDiffURI( $checkedOldidRadio.val(), $checkedDiffRadio.val() );
				$compareLinks.attr( 'href', diffUri );
			}

			return true;
		}

		// Convert buttons to links
		$compareLinks = convertToLinks();

		// Set initial state
		updateDiffElements();

		$lis.find( 'input[name="diff"], input[name="oldid"]' ).click( updateDiffElements );

		// Prettify url output for HistoryAction submissions,
		// to cover up action=historysubmit construction.

		// Ideally we'd use e.target instead of $historySubmitter, but e.target points
		// to the form element for submit actions, so.
		$historyCompareForm.find( '.historysubmit' ).click( function () {
			$historySubmitter = $(this);
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
				$copyAction = $copyForm.find( '> [name="action"]');

				// Remove action=historysubmit and ids[..]=..
				if ( $historySubmitter.hasClass( 'mw-history-compareselectedversions-button' ) ) {
					$copyAction.remove();
					$copyForm.find( 'input[name^="ids["]:checked' ).prop( 'checked', false );

				// Remove diff=&oldid=, change action=historysubmit to revisiondelete, remove revisiondelete
				} else if ( $historySubmitter.hasClass( 'mw-history-revisiondelete-button' ) ) {
					$copyRadios.remove();
					$copyAction.val( $historySubmitter.attr( 'name' ) );
					$copyForm.find( ':submit' ).remove();
				}

				// IE7 doesn't do submission from an off-DOM clone, so insert hidden into document first
				// Also remove potentially conflicting id attributes that we don't need anyway
				$copyForm
					.css( 'display', 'none' )
					.find('[id]')
						.removeAttr('id')
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
}( mediaWiki, jQuery ) );