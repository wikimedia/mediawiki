/*!
 * Enhances mediawiki-ui style elements with JavaScript.
 */

( function ( $ ) {
	/*
	 * Reduce eye-wandering due to adjacent colorful buttons
	 * This will make unhovered and unfocused sibling buttons become faded and blurred
	 * Usage: Buttons must be in a form, or in a parent with flow-ui-button-container, or they must be siblings
	 */
	$( document ).ready( function () {
		function onMwUiButtonFocus( event ) {
			var $el, $form, $siblings;

			if ( event.target.className.indexOf( 'flow-ui-button' ) === -1 ) {
				// Not a button event
				return;
			}

			$el = $( event.target );

			if ( event.type != 'keyup' || $el.is( ':focus' ) ) {
				// Reset style
				$el.removeClass( 'flow-ui-button-althover' );

				$form = $el.closest( 'form, .flow-ui-button-container' );
				if ( $form.length ) {
					// If this button is in a form, apply this to all the form's buttons.
					$siblings = $form.find( '.flow-ui-button' );
				} else {
					// Otherwise, try to find neighboring buttons
					$siblings = $el.siblings( '.flow-ui-button' );
				}

				// Add fade/blur to unfocused sibling buttons
				$siblings.not( $el ).filter( ':not(:focus)' )
					.addClass( 'flow-ui-button-althover' );
			}
		}

		function onMwUiButtonBlur( event ) {
			if ( event.target.className.indexOf( 'flow-ui-button' ) === -1 ) {
				// Not a button event
				return;
			}

			var $el       = $( event.target ),
				$form, $siblings, $focused;

			$form = $el.closest( 'form, .flow-ui-button-container' );
			if ( $form.length ) {
				// If this button is in a form, apply this to all the form's buttons.
				$siblings = $form.find( '.flow-ui-button' );
			} else {
				// Otherwise, try to find neighboring buttons
				$siblings = $el.siblings( '.flow-ui-button' );
			}

			// Add fade/blur to unfocused sibling buttons
			$focused = $siblings.not( $el ).filter( ':focus' );

			if ( event.type === 'mouseleave' && $el.is( ':focus' ) ) {
				// If this button is still focused, but the mouse left it, keep siblings faded
				return;
			} else if ( $focused.length ) {
				// A sibling has focus; have it trigger the restyling
				$focused.trigger( 'mouseenter.mw-ui-enhance' );
			} else {
				// No other siblings are focused; removing button fading
				$siblings.removeClass( 'flow-ui-button-althover' );
			}
		}

		// Attach the mouseenter and mouseleave handlers on document
		$( document )
			.on( 'mouseenter.mw-ui-enhance', '.flow-ui-button', onMwUiButtonFocus )
			.on( 'mouseleave.mw-ui-enhance', '.flow-ui-button', onMwUiButtonBlur );

		// Attach these independently, because jQuery doesn't support useCapture mode (focus propagation)
		if ( document.attachEvent ) {
			document.attachEvent( 'focusin', onMwUiButtonFocus );
			document.attachEvent( 'focusout', onMwUiButtonBlur );
		} else {
			document.addEventListener( 'focus', onMwUiButtonFocus, true );
			document.addEventListener( 'blur', onMwUiButtonBlur, true );
		}
	} );


	/*
	 * Disable / enable preview and submit buttons without/with text in field.
	 * Usage: field needs required attribute
	 */
	$( document ).ready( function () {
		$( document ).on( 'keyup.flow-actions-disabler', '.mw-ui-input', function () {
			var $form = $( this ).closest( 'form' ),
				$fields = $form.find( 'input, textarea' ).filter( '[required]' ),
				ready = true;

			$fields.each( function () {
				if ( this.value === '' ) {
					ready = false;
				}
			} );

			// @todo scrap data-role? use submit types? or a single role=action?
			$form.find( '.flow-ui-button' ).filter( '[data-role=action], [data-role=submit]' )
				.attr( 'disabled', !ready );
		} );
	} );
}( jQuery ) );