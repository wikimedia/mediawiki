/*!
 * JavaScript for diff views
 */
( function () {
	$( function setDiffSideProtection() {
		/*
		 * @param {MouseEvent} e
		 */
		function maybeClearSelectProtection( e ) {
			if ( e.button === 2 ) {
				// Right click.
				return;
			}
			document.getSelection().removeAllRanges();
			$( '.diff' ).attr( 'data-selected-side', '' );
		}

		function selectionHandler() {
			var selection = document.getSelection(),
				textNode = selection.anchorNode,
				side;

			if ( !textNode ) {
				return;
			}

			if ( $( textNode ).closest( '.diff-left' ).length !== 0 ) {
				side = 'left';
			} else if ( $( textNode ).closest( '.diff-right' ).length !== 0 ) {
				side = 'right';
			} else {
				// Not inside the diff, ignore.
				return;
			}
			$( '.diff' ).attr( 'data-selected-side', side );
		}

		$( document ).on( 'selectionchange', selectionHandler );
		$( document ).on( 'mousedown', maybeClearSelectProtection );
	} );
}() );
