/*!
 * JavaScript for diff views
 */
const inlineFormatToggle = require( './inlineFormatToggle.js' );

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
