/*!
 * JavaScript for diff views
 */
( function () {
	$( function setDiffSideProtection() {
		/**
		 * @param {Node} node
		 * @return {string|undefined}
		 * @ignore
		 */
		function getNodeSide( node ) {
			if ( $( node ).closest( '.diff-side-deleted' ).length !== 0 ) {
				return 'deleted';
			} else if ( $( node ).closest( '.diff-side-added' ).length !== 0 ) {
				return 'added';
			} else {
				// Not inside the diff.
				return undefined;
			}
		}

		/**
		 * @return {string|undefined}
		 * @ignore
		 */
		function getCurrentlyLockedSide() {
			return $( '.diff' ).attr( 'data-selected-side' );
		}

		/**
		 * @param {string|undefined} side Either "added" or "deleted", or undefined to unset.
		 * @ignore
		 */
		function setSideLock( side ) {
			$( '.diff' ).attr( 'data-selected-side', side );
		}

		/**
		 * @param {MouseEvent} e
		 * @ignore
		 */
		function maybeClearSelectProtection( e ) {
			if ( e.button === 2 ) {
				// Right click.
				return;
			}
			var clickSide = getNodeSide( e.target );
			if ( getCurrentlyLockedSide() !== clickSide ) {
				document.getSelection().removeAllRanges();
			}
			setSideLock( clickSide );
		}

		function selectionHandler() {
			var textNode = document.getSelection().anchorNode;

			if ( !textNode ) {
				return;
			}

			setSideLock( getNodeSide( textNode ) );
		}

		$( document ).on( 'selectionchange', selectionHandler );
		$( document ).on( 'mousedown', maybeClearSelectProtection );
	} );
}() );
