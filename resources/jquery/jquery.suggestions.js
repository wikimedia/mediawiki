/**
 * This plugin provides a generic way to add suggestions to a text box.
 *
 * Usage:
 *
 * Set options:
 *		$('#textbox').suggestions( { option1: value1, option2: value2 } );
 *		$('#textbox').suggestions( option, value );
 * Get option:
 *		value = $('#textbox').suggestions( option );
 * Initialize:
 *		$('#textbox').suggestions();
 *
 * Options:
 *
 * fetch(query): Callback that should fetch suggestions and set the suggestions property. Executed in the context of the
 * 		textbox
 * 		Type: Function
 * cancel: Callback function to call when any pending asynchronous suggestions fetches should be canceled.
 * 		Executed in the context of the textbox
 *		Type: Function
 * special: Set of callbacks for rendering and selecting
 *		Type: Object of Functions 'render' and 'select'
 * result: Set of callbacks for rendering and selecting
 *		Type: Object of Functions 'render' and 'select'
 * $region: jQuery selection of element to place the suggestions below and match width of
 * 		Type: jQuery Object, Default: $(this)
 * suggestions: Suggestions to display
 * 		Type: Array of strings
 * maxRows: Maximum number of suggestions to display at one time
 * 		Type: Number, Range: 1 - 100, Default: 7
 * delay: Number of ms to wait for the user to stop typing
 * 		Type: Number, Range: 0 - 1200, Default: 120
 * submitOnClick: Whether to submit the form containing the textbox when a suggestion is clicked
 *		Type: Boolean, Default: false
 * maxExpandFactor: Maximum suggestions box width relative to the textbox width.  If set to e.g. 2, the suggestions box
 *		will never be grown beyond 2 times the width of the textbox.
 *		Type: Number, Range: 1 - infinity, Default: 3
 * positionFromLeft: Whether to position the suggestion box with the left attribute or the right
 *		Type: Boolean, Default: true
 * highlightInput: Whether to hightlight matched portions of the input or not
 *		Type: Boolean, Default: false
 */
( function( $ ) {

$.suggestions = {
	/**
	 * Cancel any delayed updateSuggestions() call and inform the user so
	 * they can cancel their result fetching if they use AJAX or something
	 */
	cancel: function( context ) {
		if ( context.data.timerID != null ) {
			clearTimeout( context.data.timerID );
		}
		if ( typeof context.config.cancel == 'function' ) {
			context.config.cancel.call( context.data.$textbox );
		}
	},
	/**
	 * Restore the text the user originally typed in the textbox, before it was overwritten by highlight(). This
	 * restores the value the currently displayed suggestions are based on, rather than the value just before
	 * highlight() overwrote it; the former is arguably slightly more sensible.
	 */
	restore: function( context ) {
		context.data.$textbox.val( context.data.prevText );
	},
	/**
	 * Ask the user-specified callback for new suggestions. Any previous delayed call to this function still pending
	 * will be canceled.  If the value in the textbox is empty or hasn't changed since the last time suggestions were fetched, this
	 * function does nothing.
	 * @param {Boolean} delayed Whether or not to delay this by the currently configured amount of time
	 */
	update: function( context, delayed ) {
		// Only fetch if the value in the textbox changed and is not empty
		// if the textbox is empty then clear the result div, but leave other settings intouched
		function maybeFetch() {
			if ( context.data.$textbox.val().length == 0 ) {
				context.data.$container.hide();
				context.data.prevText = '';
			} else if ( context.data.$textbox.val() !== context.data.prevText ) {
				if ( typeof context.config.fetch == 'function' ) {
     					context.data.prevText = context.data.$textbox.val();
					context.config.fetch.call( context.data.$textbox, context.data.$textbox.val() );
				}
			}
		}

		// Cancel previous call
		if ( context.data.timerID != null ) {
			clearTimeout( context.data.timerID );
		}
		if ( delayed ) {
			// Start a new asynchronous call
			context.data.timerID = setTimeout( maybeFetch, context.config.delay );
		} else {
			maybeFetch();
		}
		$.suggestions.special( context );
	},
	special: function( context ) {
		// Allow custom rendering - but otherwise don't do any rendering
		if ( typeof context.config.special.render == 'function' ) {
			// Wait for the browser to update the value
			setTimeout( function() {
				// Render special
				$special = context.data.$container.find( '.suggestions-special' );
				context.config.special.render.call( $special, context.data.$textbox.val() );
			}, 1 );
		}
	},
	/**
	 * Sets the value of a property, and updates the widget accordingly
	 * @param property String Name of property
	 * @param value Mixed Value to set property with
	 */
	configure: function( context, property, value ) {
		// Validate creation using fallback values
		switch( property ) {
			case 'fetch':
			case 'cancel':
			case 'special':
			case 'result':
			case '$region':
				context.config[property] = value;
				break;
			case 'suggestions':
				context.config[property] = value;
				// Update suggestions
				if ( typeof context.data !== 'undefined'  ) {
					if ( context.data.$textbox.val().length == 0 ) {
						// Hide the div when no suggestion exist
						context.data.$container.hide();
					} else {
						// Rebuild the suggestions list
						context.data.$container.show();
						// Update the size and position of the list
						var newCSS = {
							'top': context.config.$region.offset().top + context.config.$region.outerHeight(),
							'bottom': 'auto',
							'width': context.config.$region.outerWidth(),
							'height': 'auto'
						};
						if ( context.config.positionFromLeft ) {
							newCSS['left'] = context.config.$region.offset().left;
							newCSS['right'] = 'auto';
						} else {
							newCSS['left'] = 'auto';
							newCSS['right'] = $( 'body' ).width() - ( context.config.$region.offset().left + context.config.$region.outerWidth() );
						}
						context.data.$container.css( newCSS );
						var $results = context.data.$container.children( '.suggestions-results' );
						$results.empty();
						var expWidth = -1;
						var $autoEllipseMe = $( [] );
						var matchedText = null;
						for ( var i = 0; i < context.config.suggestions.length; i++ ) {
							var text = context.config.suggestions[i];
							var $result = $( '<div />' )
								.addClass( 'suggestions-result' )
								.attr( 'rel', i )
								.data( 'text', context.config.suggestions[i] )
								.mousemove( function( e ) {
									context.data.selectedWithMouse = true;
									$.suggestions.highlight(
										context, $(this).closest( '.suggestions-results div' ), false
									);
								} )
								.appendTo( $results );
							// Allow custom rendering
							if ( typeof context.config.result.render == 'function' ) {
								context.config.result.render.call( $result, context.config.suggestions[i] );
							} else {
								// Add <span> with text
								if( context.config.highlightInput ) {
									matchedText = context.data.prevText;
								}
								$result.append( $( '<span />' )
										.css( 'whiteSpace', 'nowrap' )
										.text( text )
									);
								
								// Widen results box if needed
								// New width is only calculated here, applied later
								var $span = $result.children( 'span' );
								if ( $span.outerWidth() > $result.width() && $span.outerWidth() > expWidth ) {
									// factor in any padding, margin, or border space on the parent
									expWidth = $span.outerWidth() + ( context.data.$container.width() - $span.parent().width());
								}
								$autoEllipseMe = $autoEllipseMe.add( $result );
							}
						}
						// Apply new width for results box, if any
						if ( expWidth > context.data.$container.width() ) {
							var maxWidth = context.config.maxExpandFactor*context.data.$textbox.width();
							context.data.$container.width( Math.min( expWidth, maxWidth ) );
						}
						// autoEllipse the results. Has to be done after changing the width
						$autoEllipseMe.autoEllipsis( { hasSpan: true, tooltip: true, matchText: matchedText } );
					}
				}
				break;
			case 'maxRows':
				context.config[property] = Math.max( 1, Math.min( 100, value ) );
				break;
			case 'delay':
				context.config[property] = Math.max( 0, Math.min( 1200, value ) );
				break;
			case 'maxExpandFactor':
				context.config[property] = Math.max( 1, value );
				break;
			case 'submitOnClick':
			case 'positionFromLeft':
			case 'highlightInput':
				context.config[property] = value ? true : false;
				break;
		}
	},
	/**
	 * Highlight a result in the results table
	 * @param result <tr> to highlight: jQuery object, or 'prev' or 'next'
	 * @param updateTextbox If true, put the suggestion in the textbox
	 */
	highlight: function( context, result, updateTextbox ) {
		var selected = context.data.$container.find( '.suggestions-result-current' );
		if ( !result.get || selected.get( 0 ) != result.get( 0 ) ) {
			if ( result == 'prev' ) {
				if( selected.is( '.suggestions-special' ) ) {
					result = context.data.$container.find( '.suggestions-result:last' )
				} else {
					result = selected.prev();
					if ( selected.length == 0 ) {
						// we are at the beginning, so lets jump to the last item
						if ( context.data.$container.find( '.suggestions-special' ).html() != "" ) {
							result = context.data.$container.find( '.suggestions-special' );
						} else {
							result = context.data.$container.find( '.suggestions-results div:last' );
						}
					}
				}
			} else if ( result == 'next' ) {
				if ( selected.length == 0 ) {
					// No item selected, go to the first one
					result = context.data.$container.find( '.suggestions-results div:first' );
					if ( result.length == 0 && context.data.$container.find( '.suggestions-special' ).html() != "" ) {
						// No suggestion exists, go to the special one directly
						result = context.data.$container.find( '.suggestions-special' );
					}
				} else {
					result = selected.next();
					if ( selected.is( '.suggestions-special' ) ) {
						result = $( [] );
					} else if (
						result.length == 0 &&
						context.data.$container.find( '.suggestions-special' ).html() != ""
					) {
						// We were at the last item, jump to the specials!
						result = context.data.$container.find( '.suggestions-special' );
					}
				}
			}
			selected.removeClass( 'suggestions-result-current' );
			result.addClass( 'suggestions-result-current' );
		}
		if ( updateTextbox ) {
			if ( result.length == 0 || result.is( '.suggestions-special' ) ) {
				$.suggestions.restore( context );
			} else {
				context.data.$textbox.val( result.data( 'text' ) );
				// .val() doesn't call any event handlers, so
				// let the world know what happened
				context.data.$textbox.change();
			}
			context.data.$textbox.trigger( 'change' );
		}
	},
	/**
	 * Respond to keypress event
	 * @param key Integer Code of key pressed
	 */
	keypress: function( e, context, key ) {
		var wasVisible = context.data.$container.is( ':visible' );
		var preventDefault = false;
		switch ( key ) {
			// Arrow down
			case 40:
				if ( wasVisible ) {
					$.suggestions.highlight( context, 'next', true );
					context.data.selectedWithMouse = false;
				} else {
					$.suggestions.update( context, false );
				}
				preventDefault = true;
				break;
			// Arrow up
			case 38:
				if ( wasVisible ) {
					$.suggestions.highlight( context, 'prev', true );
					context.data.selectedWithMouse = false;
				}
				preventDefault = wasVisible;
				break;
			// Escape
			case 27:
				context.data.$container.hide();
				$.suggestions.restore( context );
				$.suggestions.cancel( context );
				context.data.$textbox.trigger( 'change' );
				preventDefault = wasVisible;
				break;
			// Enter
			case 13:
				context.data.$container.hide();
				preventDefault = wasVisible;
				selected = context.data.$container.find( '.suggestions-result-current' );
				if ( selected.size() == 0 || context.data.selectedWithMouse ) {
					// if nothing is selected OR if something was selected with the mouse, 
					// cancel any current requests and submit the form
					$.suggestions.cancel( context );
					context.config.$region.closest( 'form' ).submit();
				} else if ( selected.is( '.suggestions-special' ) ) {
					if ( typeof context.config.special.select == 'function' ) {
						context.config.special.select.call( selected, context.data.$textbox );
					}
				} else {
					if ( typeof context.config.result.select == 'function' ) {
						$.suggestions.highlight( context, selected, true );
						context.config.result.select.call( selected, context.data.$textbox );
					} else {
						$.suggestions.highlight( context, selected, true );
					}
				}
				break;
			default:
				$.suggestions.update( context, true );
				break;
		}
		if ( preventDefault ) {
			e.preventDefault();
			e.stopImmediatePropagation();
		}
	}
};
$.fn.suggestions = function() {
	
	// Multi-context fields
	var returnValue = null;
	var args = arguments;
	
	$(this).each( function() {

		/* Construction / Loading */
		
		var context = $(this).data( 'suggestions-context' );
		if ( typeof context == 'undefined' || context == null ) {
			context = {
				config: {
					'fetch' : function() {},
					'cancel': function() {},
					'special': {},
					'result': {},
					'$region': $(this),
					'suggestions': [],
					'maxRows': 7,
					'delay': 120,
					'submitOnClick': false,
					'maxExpandFactor': 3,
					'positionFromLeft': true,
					'highlightInput': false
				}
			};
		}
		
		/* API */
		
		// Handle various calling styles
		if ( args.length > 0 ) {
			if ( typeof args[0] == 'object' ) {
				// Apply set of properties
				for ( var key in args[0] ) {
					$.suggestions.configure( context, key, args[0][key] );
				}
			} else if ( typeof args[0] == 'string' ) {
				if ( args.length > 1 ) {
					// Set property values
					$.suggestions.configure( context, args[0], args[1] );
				} else if ( returnValue == null ) {
					// Get property values, but don't give access to internal data - returns only the first
					returnValue = ( args[0] in context.config ? undefined : context.config[args[0]] );
				}
			}
		}
		
		/* Initialization */
		
		if ( typeof context.data == 'undefined' ) {
			context.data = {
				// ID of running timer
				'timerID': null,
				// Text in textbox when suggestions were last fetched
				'prevText': null,
				// Number of results visible without scrolling
				'visibleResults': 0,
				// Suggestion the last mousedown event occured on
				'mouseDownOn': $( [] ),
				'$textbox': $(this),
				'selectedWithMouse': false
			};
			// Setup the css for positioning the results box
			var newCSS = {
				'top': Math.round( context.data.$textbox.offset().top + context.data.$textbox.outerHeight() ),
				'width': context.data.$textbox.outerWidth(),
				'display': 'none'
			};
			if ( context.config.positionFromLeft ) {
				newCSS['left'] = context.config.$region.offset().left;
				newCSS['right'] = 'auto';
			} else {
				newCSS['left'] = 'auto';
				newCSS['right'] = $( 'body' ).width() - ( context.config.$region.offset().left + context.config.$region.outerWidth() );
			}
			
			context.data.$container = $( '<div />' )
				.css( newCSS )
				.addClass( 'suggestions' )
				.append(
					$( '<div />' ).addClass( 'suggestions-results' )
						// Can't use click() because the container div is hidden when the textbox loses focus. Instead,
						// listen for a mousedown followed by a mouseup on the same div
						.mousedown( function( e ) {
							context.data.mouseDownOn = $( e.target ).closest( '.suggestions-results div' );
						} )
						.mouseup( function( e ) {
							var $result = $( e.target ).closest( '.suggestions-results div' );
							var $other = context.data.mouseDownOn;
							context.data.mouseDownOn = $( [] );
							if ( $result.get( 0 ) != $other.get( 0 ) ) {
								return;
							}
							$.suggestions.highlight( context, $result, true );
							context.data.$container.hide();
							if ( typeof context.config.result.select == 'function' ) {
								context.config.result.select.call( $result, context.data.$textbox );
							}
							context.data.$textbox.focus();
						} )
				)
				.append(
					$( '<div />' ).addClass( 'suggestions-special' )
						// Can't use click() because the container div is hidden when the textbox loses focus. Instead,
						// listen for a mousedown followed by a mouseup on the same div
						.mousedown( function( e ) {
							context.data.mouseDownOn = $( e.target ).closest( '.suggestions-special' );
						} )
						.mouseup( function( e ) {
							var $special = $( e.target ).closest( '.suggestions-special' );
							var $other = context.data.mouseDownOn;
							context.data.mouseDownOn = $( [] );
							if ( $special.get( 0 ) != $other.get( 0 ) ) {
								return;
							}
							context.data.$container.hide();
							if ( typeof context.config.special.select == 'function' ) {
								context.config.special.select.call( $special, context.data.$textbox );
							}
							context.data.$textbox.focus();
						} )
						.mousemove( function( e ) {
							context.data.selectedWithMouse = true;
							$.suggestions.highlight(
								context, $( e.target ).closest( '.suggestions-special' ), false
							);
						} )
				)
				.appendTo( $( 'body' ) );
			$(this)
				// Stop browser autocomplete from interfering
				.attr( 'autocomplete', 'off')
				.keydown( function( e ) {
					// Store key pressed to handle later
					context.data.keypressed = ( e.keyCode == undefined ) ? e.which : e.keyCode;
					context.data.keypressedCount = 0;
					
					switch ( context.data.keypressed ) {
						// This preventDefault logic is duplicated from
						// $.suggestions.keypress(), which sucks
						case 40:
							e.preventDefault();
							e.stopImmediatePropagation();
							break;
						case 38:
						case 27:
						case 13:
							if ( context.data.$container.is( ':visible' ) ) {
								e.preventDefault();
								e.stopImmediatePropagation();
							}
					}
				} )
				.keypress( function( e ) {
					context.data.keypressedCount++;
					$.suggestions.keypress( e, context, context.data.keypressed );
				} )
				.keyup( function( e ) {
					// Some browsers won't throw keypress() for arrow keys. If we got a keydown and a keyup without a
					// keypress in between, solve it
					if ( context.data.keypressedCount == 0 ) {
						$.suggestions.keypress( e, context, context.data.keypressed );
					}
				} )
				.blur( function() {
					// When losing focus because of a mousedown
					// on a suggestion, don't hide the suggestions
					if ( context.data.mouseDownOn.length > 0 ) {
						return;
					}
					context.data.$container.hide();
					$.suggestions.cancel( context );
				} );
		}
		// Store the context for next time
		$(this).data( 'suggestions-context', context );
	} );
	return returnValue !== null ? returnValue : $(this);
};
} )( jQuery );