/*!
 * JavaScript for diff views
 */
const inlineFormatToggle = require( './inlineFormatToggle.js' );

( function () {
	$( () => {
		/**
		 * Get the diff side of the given node, or undefined if the node is outside the diff.
		 *
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
		 * @param {string|undefined} side Either "added" or "deleted", or undefined to unset.
		 * @ignore
		 */
		function setSideLock( side ) {
			$( '.diff' ).attr( 'data-selected-side', side || null );
		}

		/**
		 * When the user clicks somewhere, check whether the node belongs to the diff. If it does, lock the
		 * selection to that side of the diff. If it doesn't, unlock selection.
		 *
		 * @param {MouseEvent} e
		 * @ignore
		 */
		function maybeClearSelectProtection( e ) {
			if ( e.button === 2 ) {
				// Right click.
				return;
			}
			const clickSide = getNodeSide( e.target );
			setSideLock( clickSide );
		}

		/**
		 * When a new selection is started, see if the anchor node belongs to the diff, and if so lock the selection
		 * to that side. If the anchor is outside the diff, clear any previously set locking.
		 *
		 * @ignore
		 */
		function selectionHandler() {
			// Different browsers behave differently when handling the `selectionstart` event. For example, in
			// Chrome 135, the `getSelection()` call would not return the currently-starting selection, but some
			// random outdated value from a previous selection, or just nothing. In Firefox 137, instead, it sees
			// the current selection. Other browsers are untested. Enqueue the processing in a timeout so that
			// hopefully all browsers see the expected value.
			setTimeout( () => {
				const anchorNode = document.getSelection().anchorNode;

				if ( !anchorNode ) {
					return;
				}

				setSideLock( getNodeSide( anchorNode ) );
			}, 0 );
		}

		$( document ).on( 'selectstart', selectionHandler );
		$( document ).on( 'mousedown', maybeClearSelectProtection );

		$( document ).on(
			'click',
			[
				'.mw-diff-inline-moved del',
				'.mw-diff-inline-moved ins',
				'.mw-diff-inline-changed del',
				'.mw-diff-inline-changed ins',
				'.mw-diff-inline-added ins',
				'.mw-diff-inline-deleted del'
			].join( ',' ),
			/**
			 * Shows a tooltip when added/deleted text is clicked on a tablet or mobile resolution.
			 *
			 * @param {Event} ev
			 */
			( ev ) => {
				// Limit this behaviour to mobile.
				const widthBreakpointTablet = 720;
				const isTabletOrMobile = window.matchMedia( `(max-width: ${ widthBreakpointTablet }px)` );
				if ( !isTabletOrMobile.matches ) {
					return;
				}
				const contentAdded = ev.target && ev.target.matches( 'ins' );
				const text = contentAdded ?
					mw.msg( 'diff-inline-tooltip-ins' ) :
					mw.msg( 'diff-inline-tooltip-del' );
				mw.loader.using( 'oojs-ui-core' ).then( () => {
					const popup = new OO.ui.PopupWidget( {
						$content: $( '<p>' ).text( text ),
						padded: true,
						autoClose: true,
						anchor: true,
						align: 'center',
						$floatableContainer: $( ev.target ),
						position: 'below',
						classes: [ 'mw-diff-popup' ],
						width: 'auto'
					} );
					$( OO.ui.getTeleportTarget() ).append( popup.$element );
					popup.toggle( true );
					popup.toggleClipping( true );
				} );
			}
		);
	} );

	// If there is a diff present on page, load the toggle.
	const $inlineToggleSwitchLayout = $( '#mw-diffPage-inline-toggle-switch-layout' );
	// Return if inline switch is not displaying.
	if ( $inlineToggleSwitchLayout.length ) {
		mw.loader.using( 'oojs-ui' ).then( () => {
			inlineFormatToggle( $inlineToggleSwitchLayout );
		} );
	}
}() );
