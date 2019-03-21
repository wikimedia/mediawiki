/**
 * jQuery confirmable plugin
 *
 * Released under the MIT License.
 *
 * @author Bartosz Dziewoński
 *
 * @class jQuery.plugin.confirmable
 */
( function () {
	var identity = function ( data ) {
		return data;
	};

	// eslint-disable-next-line valid-jsdoc
	/**
	 * Enable inline confirmation for given clickable element (like `<a />` or `<button />`).
	 *
	 * An additional inline confirmation step being shown before the default action is carried out on
	 * click.
	 *
	 * Calling `.confirmable( { handler: function () { … } } )` will fire the handler only after the
	 * confirmation step.
	 *
	 * The element will have the `jquery-confirmable-element` class added to it when it's clicked for
	 * the first time, which has `white-space: nowrap;` and `display: inline-block;` defined in CSS.
	 * If the computed values for the element are different when you make it confirmable, you might
	 * encounter unexpected behavior.
	 *
	 * @param {Object} [options]
	 * @param {string} [options.events='click'] Events to hook to.
	 * @param {Function} [options.wrapperCallback] Callback to fire when preparing confirmable
	 *     interface. Receives the interface jQuery object as the only parameter.
	 * @param {Function} [options.buttonCallback] Callback to fire when preparing confirmable buttons.
	 *     It is fired separately for the 'Yes' and 'No' button. Receives the button jQuery object as
	 *     the first parameter and 'yes' or 'no' as the second.
	 * @param {Function} [options.handler] Callback to fire when the action is confirmed (user clicks
	 *     the 'Yes' button).
	 * @param {string} [options.delegate] Optional selector used for jQuery event delegation
	 * @param {string} [options.i18n] Text to use for interface elements.
	 * @param {string} [options.i18n.space] Word separator to place between the three text messages.
	 * @param {string} [options.i18n.confirm] Text to use for the confirmation question.
	 * @param {string} [options.i18n.yes] Text to use for the 'Yes' button.
	 * @param {string} [options.i18n.no] Text to use for the 'No' button.
	 * @param {string} [options.i18n.yesTitle] Title text to use for the 'Yes' button.
	 * @param {string} [options.i18n.noTitle] Title text to use for the 'No' button.
	 *
	 * @chainable
	 */
	$.fn.confirmable = function ( options ) {
		options = $.extend( true, {}, $.fn.confirmable.defaultOptions, options || {} );

		if ( options.delegate === null ) {
			return this.on( options.events, function ( e ) {
				$.fn.confirmable.handler( e, options );
			} );
		}

		return this.on( options.events, options.delegate, function ( e ) {
			$.fn.confirmable.handler( e, options );
		} );
	};

	$.fn.confirmable.handler = function ( event, options ) {
		var $element, $text, $buttonYes, $buttonNo, $wrapper, $interface, $elementClone,
			interfaceWidth, elementWidth, rtl, positionOffscreen, positionRestore, sideMargin;

		$element = $( event.target );

		if ( $element.data( 'jquery-confirmable-button' ) ) {
			// We're running on a clone of this element that represents the 'Yes' or 'No' button.
			// (This should never happen for the 'No' case unless calling code does bad things.)
			return;
		}

		// Only prevent native event handling. Stopping other JavaScript event handlers
		// is impossible because they might have already run (we have no control over the order).
		event.preventDefault();

		rtl = $element.css( 'direction' ) === 'rtl';
		if ( rtl ) {
			positionOffscreen = { position: 'absolute', right: '-9999px' };
			positionRestore = { position: '', right: '' };
			sideMargin = 'marginRight';
		} else {
			positionOffscreen = { position: 'absolute', left: '-9999px' };
			positionRestore = { position: '', left: '' };
			sideMargin = 'marginLeft';
		}

		if ( $element.hasClass( 'jquery-confirmable-element' ) ) {
			$wrapper = $element.closest( '.jquery-confirmable-wrapper' );
			$interface = $wrapper.find( '.jquery-confirmable-interface' );
			$text = $interface.find( '.jquery-confirmable-text' );
			$buttonYes = $interface.find( '.jquery-confirmable-button-yes' );
			$buttonNo = $interface.find( '.jquery-confirmable-button-no' );

			interfaceWidth = $interface.data( 'jquery-confirmable-width' );
			elementWidth = $element.data( 'jquery-confirmable-width' );
		} else {
			$elementClone = $element.clone( true );
			$element.addClass( 'jquery-confirmable-element' );

			elementWidth = $element.width();
			$element.data( 'jquery-confirmable-width', elementWidth );

			$wrapper = $( '<span>' )
				.addClass( 'jquery-confirmable-wrapper' );
			$element.wrap( $wrapper );

			// Build the mini-dialog
			$text = $( '<span>' )
				.addClass( 'jquery-confirmable-text' )
				.text( options.i18n.confirm );

			// Clone original element along with event handlers to easily replicate its behavior.
			// We could fiddle with .trigger() etc., but that is troublesome especially since
			// Safari doesn't implement .click() on <a> links and jQuery follows suit.
			$buttonYes = $elementClone.clone( true )
				.addClass( 'jquery-confirmable-button jquery-confirmable-button-yes' )
				.data( 'jquery-confirmable-button', true )
				.text( options.i18n.yes );
			if ( options.handler ) {
				$buttonYes.on( options.events, options.handler );
			}
			if ( options.i18n.yesTitle ) {
				$buttonYes.attr( 'title', options.i18n.yesTitle );
			}
			$buttonYes = options.buttonCallback( $buttonYes, 'yes' );

			// Clone it without any events and prevent default action to represent the 'No' button.
			$buttonNo = $elementClone.clone( false )
				.addClass( 'jquery-confirmable-button jquery-confirmable-button-no' )
				.data( 'jquery-confirmable-button', true )
				.text( options.i18n.no )
				.on( options.events, function ( e ) {
					$element.css( sideMargin, 0 );
					$interface.css( 'width', 0 );
					e.preventDefault();
				} );
			if ( options.i18n.noTitle ) {
				$buttonNo.attr( 'title', options.i18n.noTitle );
			} else {
				$buttonNo.removeAttr( 'title' );
			}
			$buttonNo = options.buttonCallback( $buttonNo, 'no' );

			// Prevent memory leaks
			$elementClone.remove();

			$interface = $( '<span>' )
				.addClass( 'jquery-confirmable-interface' )
				.append( $text, options.i18n.space, $buttonYes, options.i18n.space, $buttonNo );
			$interface = options.wrapperCallback( $interface );

			// Render offscreen to measure real width
			$interface.css( positionOffscreen );
			// Insert it in the correct place while we're at it
			$element.after( $interface );
			interfaceWidth = $interface.width();
			$interface.data( 'jquery-confirmable-width', interfaceWidth );
			$interface.css( positionRestore );

			// Hide to animate the transition later
			$interface.css( 'width', 0 );
		}

		// Hide element, show interface. This triggers both transitions.
		// In a timeout to trigger the 'width' transition.
		setTimeout( function () {
			$element.css( sideMargin, -elementWidth );
			$interface.css( 'width', interfaceWidth );
		}, 1 );
	};

	/**
	 * Default options. Overridable primarily for internationalisation handling.
	 * @property {Object} defaultOptions
	 */
	$.fn.confirmable.defaultOptions = {
		events: 'click',
		wrapperCallback: identity,
		buttonCallback: identity,
		handler: null,
		delegate: null,
		i18n: {
			space: ' ',
			confirm: 'Are you sure?',
			yes: 'Yes',
			no: 'No',
			yesTitle: undefined,
			noTitle: undefined
		}
	};
}() );
