/**
 * This plugin provides a generic way to add suggestions to a text box.
 *
 * Usage:
 *
 * Set options:
 *		$( '#textbox' ).suggestions( { option1: value1, option2: value2 } );
 *		$( '#textbox' ).suggestions( option, value );
 * Get option:
 *		value = $( '#textbox' ).suggestions( option );
 * Initialize:
 *		$( '#textbox' ).suggestions();
 *
 * Options:
 *
 * fetch(query): Callback that should fetch suggestions and set the suggestions property.
 *      Executed in the context of the textbox
 *		Type: Function
 * cancel: Callback function to call when any pending asynchronous suggestions fetches
 *      should be canceled. Executed in the context of the textbox
 *		Type: Function
 * special: Set of callbacks for rendering and selecting
 *		Type: Object of Functions 'render' and 'select'
 * result: Set of callbacks for rendering and selecting
 *		Type: Object of Functions 'render' and 'select'
 * $region: jQuery selection of element to place the suggestions below and match width of
 *		Type: jQuery Object, Default: $(this)
 * suggestions: Suggestions to display
 *		Type: Array of strings
 * maxRows: Maximum number of suggestions to display at one time
 *		Type: Number, Range: 1 - 100, Default: 7
 * delay: Number of ms to wait for the user to stop typing
 *		Type: Number, Range: 0 - 1200, Default: 120
 * submitOnClick: Whether to submit the form containing the textbox when a suggestion is clicked
 *		Type: Boolean, Default: false
 * maxExpandFactor: Maximum suggestions box width relative to the textbox width. If set
 *      to e.g. 2, the suggestions box will never be grown beyond 2 times the width of the textbox.
 *		Type: Number, Range: 1 - infinity, Default: 3
 * expandFrom: Which direction to offset the suggestion box from.
 *      Values 'start' and 'end' translate to left and right respectively depending on the
 *      directionality of the current document, according to $( 'html' ).css( 'direction' ).
 *      Type: String, default: 'auto', options: 'left', 'right', 'start', 'end', 'auto'.
 * positionFromLeft: Sets expandFrom=left, for backwards compatibility
 *		Type: Boolean, Default: true
 * highlightInput: Whether to hightlight matched portions of the input or not
 *		Type: Boolean, Default: false
 */
( function ( $ ) {

$.suggestions = {
	/**
	 * Cancel any delayed maybeFetch() call and callback the context so
	 * they can cancel any async fetching if they use AJAX or something.
	 */
	cancel: function ( context ) {
		if ( context.data.timerID !== null ) {
			clearTimeout( context.data.timerID );
		}
		if ( $.isFunction( context.config.cancel ) ) {
			context.config.cancel.call( context.data.$textbox );
		}
	},

	/**
	 * Hide the element with suggestions and clean up some state.
	 */
	hide: function ( context ) {
		// Remove any highlights, including on "special" items
		context.data.$container.find( '.suggestions-result-current' ).removeClass( 'suggestions-result-current' );
		// Hide the container
		context.data.$container.hide();
	},

	/**
	 * Restore the text the user originally typed in the textbox, before it
	 * was overwritten by highlight(). This restores the value the currently
	 * displayed suggestions are based on, rather than the value just before
	 * highlight() overwrote it; the former is arguably slightly more sensible.
	 */
	restore: function ( context ) {
		context.data.$textbox.val( context.data.prevText );
	},

	/**
	 * Ask the user-specified callback for new suggestions. Any previous delayed
	 * call to this function still pending will be canceled. If the value in the
	 * textbox is empty or hasn't changed since the last time suggestions were fetched,
	 * this function does nothing.
	 * @param {Boolean} delayed Whether or not to delay this by the currently configured amount of time
	 */
	update: function ( context, delayed ) {
		function maybeFetch() {
			// Only fetch if the value in the textbox changed and is not empty, or if the results were hidden
			// if the textbox is empty then clear the result div, but leave other settings intouched
			if ( context.data.$textbox.val().length === 0 ) {
				$.suggestions.hide( context );
				context.data.prevText = '';
			} else if (
				context.data.$textbox.val() !== context.data.prevText ||
				!context.data.$container.is( ':visible' )
			) {
				if ( typeof context.config.fetch === 'function' ) {
					context.data.prevText = context.data.$textbox.val();
					context.config.fetch.call( context.data.$textbox, context.data.$textbox.val() );
				}
			}

			// Always update special rendering
			$.suggestions.special( context );
		}

		// Cancels any delayed maybeFetch call, and invokes context.config.cancel.
		$.suggestions.cancel( context );

		if ( delayed ) {
			// To avoid many started/aborted requests while typing, we're gonna take a short
			// break before trying to fetch data.
			context.data.timerID = setTimeout( maybeFetch, context.config.delay );
		} else {
			maybeFetch();
		}
	},

	special: function ( context ) {
		// Allow custom rendering - but otherwise don't do any rendering
		if ( typeof context.config.special.render === 'function' ) {
			// Wait for the browser to update the value
			setTimeout( function () {
				// Render special
				var $special = context.data.$container.find( '.suggestions-special' );
				context.config.special.render.call( $special, context.data.$textbox.val(), context );
			}, 1 );
		}
	},

	/**
	 * Sets the value of a property, and updates the widget accordingly
	 * @param property String Name of property
	 * @param value Mixed Value to set property with
	 */
	configure: function ( context, property, value ) {
		var newCSS,
			$result, $results, $spanForWidth, childrenWidth,
			i, expWidth, maxWidth, text;

		// Validate creation using fallback values
		switch ( property ) {
			case 'fetch':
			case 'cancel':
			case 'special':
			case 'result':
			case '$region':
			case 'expandFrom':
				context.config[property] = value;
				break;
			case 'suggestions':
				context.config[property] = value;
				// Update suggestions
				if ( context.data !== undefined ) {
					if ( context.data.$textbox.val().length === 0 ) {
						// Hide the div when no suggestion exist
						$.suggestions.hide( context );
					} else {
						// Rebuild the suggestions list
						context.data.$container.show();
						// Update the size and position of the list
						newCSS = {
							top: context.config.$region.offset().top + context.config.$region.outerHeight(),
							bottom: 'auto',
							width: context.config.$region.outerWidth(),
							height: 'auto'
						};

						// Process expandFrom, after this it is set to left or right.
						context.config.expandFrom = ( function ( expandFrom ) {
							var regionWidth, docWidth, regionCenter, docCenter,
								docDir = $( document.documentElement ).css( 'direction' ),
								$region = context.config.$region;

							// Backwards compatible
							if ( context.config.positionFromLeft ) {
								expandFrom = 'left';

							// Catch invalid values, default to 'auto'
							} else if ( $.inArray( expandFrom, ['left', 'right', 'start', 'end', 'auto'] ) === -1 ) {
								expandFrom = 'auto';
							}

							if ( expandFrom === 'auto' ) {
								if ( $region.data( 'searchsuggest-expand-dir' ) ) {
									// If the markup explicitly contains a direction, use it.
									expandFrom = $region.data( 'searchsuggest-expand-dir' );
								} else {
									regionWidth = $region.outerWidth();
									docWidth = $( document ).width();
									if ( ( regionWidth / docWidth  ) > 0.85 ) {
										// If the input size takes up more than 85% of the document horizontally
										// expand the suggestions to the writing direction's native end.
										expandFrom = 'start';
									} else {
										// Calculate the center points of the input and document
										regionCenter = $region.offset().left + regionWidth / 2;
										docCenter = docWidth / 2;
										if ( Math.abs( regionCenter - docCenter ) / docCenter < 0.10 ) {
											// If the input's center is within 10% of the document center
											// use the writing direction's native end.
											expandFrom = 'start';
										} else {
											// Otherwise expand the input from the closest side of the page,
											// towards the side of the page with the most free open space
											expandFrom = regionCenter > docCenter ? 'right' : 'left';
										}
									}
								}
							}

							if ( expandFrom === 'start' ) {
								expandFrom = docDir === 'rtl' ? 'right': 'left';

							} else if ( expandFrom === 'end' ) {
								expandFrom = docDir === 'rtl' ? 'left': 'right';
							}

							return expandFrom;

						}( context.config.expandFrom ) );

						if ( context.config.expandFrom === 'left' ) {
							// Expand from left
							newCSS.left = context.config.$region.offset().left;
							newCSS.right = 'auto';
						} else {
							// Expand from right
							newCSS.left = 'auto';
							newCSS.right = $( document ).width() - ( context.config.$region.offset().left + context.config.$region.outerWidth() );
						}

						context.data.$container.css( newCSS );
						$results = context.data.$container.children( '.suggestions-results' );
						$results.empty();
						expWidth = -1;
						for ( i = 0; i < context.config.suggestions.length; i++ ) {
							/*jshint loopfunc:true */
							text = context.config.suggestions[i];
							$result = $( '<div>' )
								.addClass( 'suggestions-result' )
								.attr( 'rel', i )
								.data( 'text', context.config.suggestions[i] )
								.mousemove( function () {
									context.data.selectedWithMouse = true;
									$.suggestions.highlight(
										context,
										$(this).closest( '.suggestions-results .suggestions-result' ),
										false
									);
								} )
								.appendTo( $results );
							// Allow custom rendering
							if ( typeof context.config.result.render === 'function' ) {
								context.config.result.render.call( $result, context.config.suggestions[i], context );
							} else {
								$result.text( text );
							}

							if ( context.config.highlightInput ) {
								$result.highlightText( context.data.prevText );
							}

							// Widen results box if needed (new width is only calculated here, applied later).

							// The monstrosity below accomplishes two things:
							// * Wraps the text contents in a DOM element, so that we can know its width. There is
							//   no way to directly access the width of a text node, and we can't use the parent
							//   node width as it has text-overflow: ellipsis; and overflow: hidden; applied to
							//   it, which trims it to a smaller width.
							// * Temporarily applies position: absolute; to the wrapper to pull it out of normal
							//   document flow. Otherwise the CSS text-overflow: ellipsis; and overflow: hidden;
							//   rules would cause some browsers (at least all versions of IE from 6 to 11) to
							//   still report the "trimmed" width. This should not be done in regular CSS
							//   stylesheets as we don't want this rule to apply to other <span> elements, like
							//   the ones generated by jquery.highlightText.
							$spanForWidth = $result.wrapInner( '<span>' ).children();
							childrenWidth = $spanForWidth.css( 'position', 'absolute' ).outerWidth();
							$spanForWidth.contents().unwrap();

							if ( childrenWidth > $result.width() && childrenWidth > expWidth ) {
								// factor in any padding, margin, or border space on the parent
								expWidth = childrenWidth + ( context.data.$container.width() - $result.width() );
							}
						}

						// Apply new width for results box, if any
						if ( expWidth > context.data.$container.width() ) {
							maxWidth = context.config.maxExpandFactor * context.data.$textbox.width();
							context.data.$container.width( Math.min( expWidth, maxWidth ) );
						}
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
	highlight: function ( context, result, updateTextbox ) {
		var selected = context.data.$container.find( '.suggestions-result-current' );
		if ( !result.get || selected.get( 0 ) !== result.get( 0 ) ) {
			if ( result === 'prev' ) {
				if( selected.hasClass( 'suggestions-special' ) ) {
					result = context.data.$container.find( '.suggestions-result:last' );
				} else {
					result = selected.prev();
					if ( !( result.length && result.hasClass( 'suggestions-result' ) ) ) {
						// there is something in the DOM between selected element and the wrapper, bypass it
						result = selected.parents( '.suggestions-results > *' ).prev().find( '.suggestions-result' ).eq(0);
					}

					if ( selected.length === 0 ) {
						// we are at the beginning, so lets jump to the last item
						if ( context.data.$container.find( '.suggestions-special' ).html() !== '' ) {
							result = context.data.$container.find( '.suggestions-special' );
						} else {
							result = context.data.$container.find( '.suggestions-results .suggestions-result:last' );
						}
					}
				}
			} else if ( result === 'next' ) {
				if ( selected.length === 0 ) {
					// No item selected, go to the first one
					result = context.data.$container.find( '.suggestions-results .suggestions-result:first' );
					if ( result.length === 0 && context.data.$container.find( '.suggestions-special' ).html() !== '' ) {
						// No suggestion exists, go to the special one directly
						result = context.data.$container.find( '.suggestions-special' );
					}
				} else {
					result = selected.next();
					if ( !( result.length && result.hasClass( 'suggestions-result' ) ) ) {
						// there is something in the DOM between selected element and the wrapper, bypass it
						result = selected.parents( '.suggestions-results > *' ).next().find( '.suggestions-result' ).eq(0);
					}

					if ( selected.hasClass( 'suggestions-special' ) ) {
						result = $( [] );
					} else if (
						result.length === 0 &&
						context.data.$container.find( '.suggestions-special' ).html() !== ''
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
			if ( result.length === 0 || result.is( '.suggestions-special' ) ) {
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
	keypress: function ( e, context, key ) {
		var selected,
			wasVisible = context.data.$container.is( ':visible' ),
			preventDefault = false;

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
				$.suggestions.hide( context );
				$.suggestions.restore( context );
				$.suggestions.cancel( context );
				context.data.$textbox.trigger( 'change' );
				preventDefault = wasVisible;
				break;
			// Enter
			case 13:
				preventDefault = wasVisible;
				selected = context.data.$container.find( '.suggestions-result-current' );
				$.suggestions.hide( context );
				if ( selected.length === 0 || context.data.selectedWithMouse ) {
					// If nothing is selected or if something was selected with the mouse
					// cancel any current requests and allow the form to be submitted
					// (simply don't prevent default behavior).
					$.suggestions.cancel( context );
					preventDefault = false;
				} else if ( selected.is( '.suggestions-special' ) ) {
					if ( typeof context.config.special.select === 'function' ) {
						// Allow the callback to decide whether to prevent default or not
						if ( context.config.special.select.call( selected, context.data.$textbox ) === true ) {
							preventDefault = false;
						}
					}
				} else {
					$.suggestions.highlight( context, selected, true );

					if ( typeof context.config.result.select === 'function' ) {
						// Allow the callback to decide whether to prevent default or not
						if ( context.config.result.select.call( selected, context.data.$textbox ) === true ) {
							preventDefault = false;
						}
					}
				}
				break;
			default:
				$.suggestions.update( context, true );
				break;
		}
		if ( preventDefault ) {
			e.preventDefault();
			e.stopPropagation();
		}
	}
};
$.fn.suggestions = function () {

	// Multi-context fields
	var returnValue,
		args = arguments;

	$(this).each( function () {
		var context, key;

		/* Construction / Loading */

		context = $(this).data( 'suggestions-context' );
		if ( context === undefined || context === null ) {
			context = {
				config: {
					fetch: function () {},
					cancel: function () {},
					special: {},
					result: {},
					$region: $(this),
					suggestions: [],
					maxRows: 7,
					delay: 120,
					submitOnClick: false,
					maxExpandFactor: 3,
					expandFrom: 'auto',
					highlightInput: false
				}
			};
		}

		/* API */

		// Handle various calling styles
		if ( args.length > 0 ) {
			if ( typeof args[0] === 'object' ) {
				// Apply set of properties
				for ( key in args[0] ) {
					$.suggestions.configure( context, key, args[0][key] );
				}
			} else if ( typeof args[0] === 'string' ) {
				if ( args.length > 1 ) {
					// Set property values
					$.suggestions.configure( context, args[0], args[1] );
				} else if ( returnValue === null || returnValue === undefined ) {
					// Get property values, but don't give access to internal data - returns only the first
					returnValue = ( args[0] in context.config ? undefined : context.config[args[0]] );
				}
			}
		}

		/* Initialization */

		if ( context.data === undefined ) {
			context.data = {
				// ID of running timer
				timerID: null,

				// Text in textbox when suggestions were last fetched
				prevText: null,

				// Number of results visible without scrolling
				visibleResults: 0,

				// Suggestion the last mousedown event occurred on
				mouseDownOn: $( [] ),
				$textbox: $(this),
				selectedWithMouse: false
			};

			context.data.$container = $( '<div>' )
				.css( 'display', 'none' )
				.addClass( 'suggestions' )
				.append(
					$( '<div>' ).addClass( 'suggestions-results' )
						// Can't use click() because the container div is hidden when the
						// textbox loses focus. Instead, listen for a mousedown followed
						// by a mouseup on the same div.
						.mousedown( function ( e ) {
							context.data.mouseDownOn = $( e.target ).closest( '.suggestions-results .suggestions-result' );
						} )
						.mouseup( function ( e ) {
							var $result = $( e.target ).closest( '.suggestions-results .suggestions-result' ),
								$other = context.data.mouseDownOn;

							context.data.mouseDownOn = $( [] );
							if ( $result.get( 0 ) !== $other.get( 0 ) ) {
								return;
							}
							// do not interfere with non-left clicks or if modifier keys are pressed (e.g. ctrl-click)
							if ( !( e.which !== 1 || e.altKey || e.ctrlKey || e.shiftKey || e.metaKey ) ) {
								$.suggestions.highlight( context, $result, true );
								$.suggestions.hide( context );
								if ( typeof context.config.result.select === 'function' ) {
									context.config.result.select.call( $result, context.data.$textbox );
								}
							}
							// but still restore focus to the textbox, so that the suggestions will be hidden properly
							context.data.$textbox.focus();
						} )
				)
				.append(
					$( '<div>' ).addClass( 'suggestions-special' )
						// Can't use click() because the container div is hidden when the
						// textbox loses focus. Instead, listen for a mousedown followed
						// by a mouseup on the same div.
						.mousedown( function ( e ) {
							context.data.mouseDownOn = $( e.target ).closest( '.suggestions-special' );
						} )
						.mouseup( function ( e ) {
							var $special = $( e.target ).closest( '.suggestions-special' ),
								$other = context.data.mouseDownOn;

							context.data.mouseDownOn = $( [] );
							if ( $special.get( 0 ) !== $other.get( 0 ) ) {
								return;
							}
							// do not interfere with non-left clicks or if modifier keys are pressed (e.g. ctrl-click)
							if ( !( e.which !== 1 || e.altKey || e.ctrlKey || e.shiftKey || e.metaKey ) ) {
								$.suggestions.hide( context );
								if ( typeof context.config.special.select === 'function' ) {
									context.config.special.select.call( $special, context.data.$textbox );
								}
							}
							// but still restore focus to the textbox, so that the suggestions will be hidden properly
							context.data.$textbox.focus();
						} )
						.mousemove( function ( e ) {
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
				.keydown( function ( e ) {
					// Store key pressed to handle later
					context.data.keypressed = e.which;
					context.data.keypressedCount = 0;
				} )
				.keypress( function ( e ) {
					context.data.keypressedCount++;
					$.suggestions.keypress( e, context, context.data.keypressed );
				} )
				.keyup( function ( e ) {
					// Some browsers won't throw keypress() for arrow keys. If we got a keydown and a keyup without a
					// keypress in between, solve it
					if ( context.data.keypressedCount === 0 ) {
						$.suggestions.keypress( e, context, context.data.keypressed );
					}
				} )
				.blur( function () {
					// When losing focus because of a mousedown
					// on a suggestion, don't hide the suggestions
					if ( context.data.mouseDownOn.length > 0 ) {
						return;
					}
					$.suggestions.hide( context );
					$.suggestions.cancel( context );
				} );
		}

		// Store the context for next time
		$(this).data( 'suggestions-context', context );
	} );
	return returnValue !== undefined ? returnValue : $(this);
};

}( jQuery ) );
