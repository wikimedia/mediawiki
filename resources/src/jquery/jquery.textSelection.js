/**
 * These plugins provide extra functionality for interaction with textareas.
 */
( function ( $ ) {
	if ( document.selection && document.selection.createRange ) {
		// On IE, patch the focus() method to restore the windows' scroll position
		// (T34241)
		$.fn.extend( {
			focus: ( function ( jqFocus ) {
				return function () {
					var $w, state, result;
					if ( arguments.length === 0 ) {
						$w = $( window );
						state = { top: $w.scrollTop(), left: $w.scrollLeft() };
						result = jqFocus.apply( this, arguments );
						window.scrollTo( state.top, state.left );
						return result;
					}
					return jqFocus.apply( this, arguments );
				};
			}( $.fn.focus ) )
		} );
	}

	$.fn.textSelection = function ( command, options ) {
		var fn,
			alternateFn,
			context,
			hasWikiEditor,
			needSave,
			retval;

		/**
		 * Helper function to get an IE TextRange object for an element
		 *
		 * @param {HTMLElement} element
		 * @return {TextRange}
		 */
		function rangeForElementIE( element ) {
			var sel;
			if ( element.nodeName.toLowerCase() === 'input' ) {
				return element.createTextRange();
			} else {
				sel = document.body.createTextRange();
				sel.moveToElementText( element );
				return sel;
			}
		}

		/**
		 * Helper function for IE for activating the textarea. Called only in the
		 * IE-specific code paths below; makes use of IE-specific non-standard
		 * function setActive() if possible to avoid screen flicker.
		 *
		 * @param {HTMLElement} element
		 */
		function activateElementOnIE( element ) {
			if ( element.setActive ) {
				element.setActive(); // T34241: doesn't scroll
			} else {
				$( element ).focus(); // may scroll (but we patched it above)
			}
		}

		fn = {
			/**
			 * Get the contents of the textarea
			 *
			 * @return {string}
			 */
			getContents: function () {
				return this.val();
			},
			/**
			 * Set the contents of the textarea, replacing anything that was there before
			 *
			 * @param {string} content
			 */
			setContents: function ( content ) {
				this.val( content );
			},
			/**
			 * Get the currently selected text in this textarea. Will focus the textarea
			 * in some browsers (IE/Opera)
			 *
			 * @return {string}
			 */
			getSelection: function () {
				var retval, range,
					el = this.get( 0 );

				if ( !el || $( el ).is( ':hidden' ) ) {
					retval = '';
				} else if ( document.selection && document.selection.createRange ) {
					activateElementOnIE( el );
					range = document.selection.createRange();
					retval = range.text;
				} else if ( el.selectionStart || el.selectionStart === 0 ) {
					retval = el.value.substring( el.selectionStart, el.selectionEnd );
				}

				return retval;
			},
			/**
			 * Ported from skins/common/edit.js by Trevor Parscal
			 * (c) 2009 Wikimedia Foundation (GPLv2) - http://www.wikimedia.org
			 *
			 * Inserts text at the beginning and end of a text selection, optionally
			 * inserting text at the caret when selection is empty.
			 *
			 * @param {Object} options Options
			 * FIXME document the options parameters
			 * @return {jQuery}
			 */
			encapsulateSelection: function ( options ) {
				return this.each( function () {
					var selText, scrollTop, insertText,
						isSample, range, range2, range3, startPos, endPos,
						pre = options.pre,
						post = options.post;

					/**
					 * Check if the selected text is the same as the insert text
					 */
					function checkSelectedText() {
						if ( !selText ) {
							selText = options.peri;
							isSample = true;
						} else if ( options.replace ) {
							selText = options.peri;
						} else {
							while ( selText.charAt( selText.length - 1 ) === ' ' ) {
								// Exclude ending space char
								selText = selText.slice( 0, -1 );
								post += ' ';
							}
							while ( selText.charAt( 0 ) === ' ' ) {
								// Exclude prepending space char
								selText = selText.slice( 1 );
								pre = ' ' + pre;
							}
						}
					}

					/**
					 * Do the splitlines stuff.
					 *
					 * Wrap each line of the selected text with pre and post
					 *
					 * @param {string} selText Selected text
					 * @param {string} pre Text before
					 * @param {string} post Text after
					 * @return {string} Wrapped text
					 */
					function doSplitLines( selText, pre, post ) {
						var i,
							insertText = '',
							selTextArr = selText.split( '\n' );
						for ( i = 0; i < selTextArr.length; i++ ) {
							insertText += pre + selTextArr[ i ] + post;
							if ( i !== selTextArr.length - 1 ) {
								insertText += '\n';
							}
						}
						return insertText;
					}

					isSample = false;
					// Do nothing if display none
					if ( this.style.display !== 'none' ) {
						if ( document.selection && document.selection.createRange ) {
							// IE

							// Note that IE9 will trigger the next section unless we check this first.
							// See bug T37201.

							activateElementOnIE( this );
							if ( context ) {
								context.fn.restoreCursorAndScrollTop();
							}
							if ( options.selectionStart !== undefined ) {
								$( this ).textSelection( 'setSelection', { start: options.selectionStart, end: options.selectionEnd } );
							}

							selText = $( this ).textSelection( 'getSelection' );
							scrollTop = this.scrollTop;
							range = document.selection.createRange();

							checkSelectedText();
							insertText = pre + selText + post;
							if ( options.splitlines ) {
								insertText = doSplitLines( selText, pre, post );
							}
							if ( options.ownline && range.moveStart ) {
								range2 = document.selection.createRange();
								range2.collapse();
								range2.moveStart( 'character', -1 );
								// FIXME: Which check is correct?
								if ( range2.text !== '\r' && range2.text !== '\n' && range2.text !== '' ) {
									insertText = '\n' + insertText;
									pre += '\n';
								}
								range3 = document.selection.createRange();
								range3.collapse( false );
								range3.moveEnd( 'character', 1 );
								if ( range3.text !== '\r' && range3.text !== '\n' && range3.text !== '' ) {
									insertText += '\n';
									post += '\n';
								}
							}

							range.text = insertText;
							if ( isSample && options.selectPeri && range.moveStart ) {
								range.moveStart( 'character', -post.length - selText.length );
								range.moveEnd( 'character', -post.length );
							}
							range.select();
							// Restore the scroll position
							this.scrollTop = scrollTop;
						} else if ( this.selectionStart || this.selectionStart === 0 ) {
							// Mozilla/Opera

							$( this ).focus();
							if ( options.selectionStart !== undefined ) {
								$( this ).textSelection( 'setSelection', { start: options.selectionStart, end: options.selectionEnd } );
							}

							selText = $( this ).textSelection( 'getSelection' );
							startPos = this.selectionStart;
							endPos = this.selectionEnd;
							scrollTop = this.scrollTop;
							checkSelectedText();
							if (
								options.selectionStart !== undefined &&
								endPos - startPos !== options.selectionEnd - options.selectionStart
							) {
								// This means there is a difference in the selection range returned by browser and what we passed.
								// This happens for Chrome in the case of composite characters. Ref bug #30130
								// Set the startPos to the correct position.
								startPos = options.selectionStart;
							}

							insertText = pre + selText + post;
							if ( options.splitlines ) {
								insertText = doSplitLines( selText, pre, post );
							}
							if ( options.ownline ) {
								if ( startPos !== 0 && this.value.charAt( startPos - 1 ) !== '\n' && this.value.charAt( startPos - 1 ) !== '\r' ) {
									insertText = '\n' + insertText;
									pre += '\n';
								}
								if ( this.value.charAt( endPos ) !== '\n' && this.value.charAt( endPos ) !== '\r' ) {
									insertText += '\n';
									post += '\n';
								}
							}
							this.value = this.value.slice( 0, startPos ) + insertText +
								this.value.slice( endPos );
							// Setting this.value scrolls the textarea to the top, restore the scroll position
							this.scrollTop = scrollTop;
							if ( window.opera ) {
								pre = pre.replace( /\r?\n/g, '\r\n' );
								selText = selText.replace( /\r?\n/g, '\r\n' );
								post = post.replace( /\r?\n/g, '\r\n' );
							}
							if ( isSample && options.selectPeri && ( !options.splitlines || ( options.splitlines && selText.indexOf( '\n' ) === -1 ) ) ) {
								this.selectionStart = startPos + pre.length;
								this.selectionEnd = startPos + pre.length + selText.length;
							} else {
								this.selectionStart = startPos + insertText.length;
								this.selectionEnd = this.selectionStart;
							}
						}
					}
					$( this ).trigger( 'encapsulateSelection', [ options.pre, options.peri, options.post, options.ownline,
						options.replace, options.spitlines ] );
				} );
			},
			/**
			 * Ported from Wikia's LinkSuggest extension
			 * https://svn.wikia-code.com/wikia/trunk/extensions/wikia/LinkSuggest
			 * Some code copied from
			 * http://www.dedestruct.com/2008/03/22/howto-cross-browser-cursor-position-in-textareas/
			 *
			 * Get the position (in resolution of bytes not necessarily characters)
			 * in a textarea
			 *
			 * Will focus the textarea in some browsers (IE/Opera)
			 *
			 * @param {Object} options Options
			 * FIXME document the options parameters
			 * @return {number} Position
			 */
			getCaretPosition: function ( options ) {
				function getCaret( e ) {
					var caretPos = 0,
						endPos = 0,
						preText, rawPreText, periText,
						rawPeriText, postText,
						// IE Support
						preFinished,
						periFinished,
						postFinished,
						// Range containing text in the selection
						periRange,
						// Range containing text before the selection
						preRange,
						// Range containing text after the selection
						postRange;

					if ( e && document.selection && document.selection.createRange ) {
						// IE doesn't properly report non-selected caret position through
						// the selection ranges when textarea isn't focused. This can
						// lead to saving a bogus empty selection, which then screws up
						// whatever we do later (T33847).
						activateElementOnIE( e );

						preFinished = false;
						periFinished = false;
						postFinished = false;
						periRange = document.selection.createRange().duplicate();

						preRange = rangeForElementIE( e );
						// Move the end where we need it
						preRange.setEndPoint( 'EndToStart', periRange );

						postRange = rangeForElementIE( e );
						// Move the start where we need it
						postRange.setEndPoint( 'StartToEnd', periRange );

						// Load the text values we need to compare
						preText = rawPreText = preRange.text;
						periText = rawPeriText = periRange.text;
						postText = postRange.text;

						/*
						 * Check each range for trimmed newlines by shrinking the range by 1
						 * character and seeing if the text property has changed. If it has
						 * not changed then we know that IE has trimmed a \r\n from the end.
						 */
						do {
							if ( !preFinished ) {
								if ( preRange.compareEndPoints( 'StartToEnd', preRange ) === 0 ) {
									preFinished = true;
								} else {
									preRange.moveEnd( 'character', -1 );
									if ( preRange.text === preText ) {
										rawPreText += '\r\n';
									} else {
										preFinished = true;
									}
								}
							}
							if ( !periFinished ) {
								if ( periRange.compareEndPoints( 'StartToEnd', periRange ) === 0 ) {
									periFinished = true;
								} else {
									periRange.moveEnd( 'character', -1 );
									if ( periRange.text === periText ) {
										rawPeriText += '\r\n';
									} else {
										periFinished = true;
									}
								}
							}
							if ( !postFinished ) {
								if ( postRange.compareEndPoints( 'StartToEnd', postRange ) === 0 ) {
									postFinished = true;
								} else {
									postRange.moveEnd( 'character', -1 );
									if ( postRange.text !== postText ) {
										postFinished = true;
									}
								}
							}
						} while ( ( !preFinished || !periFinished || !postFinished ) );
						caretPos = rawPreText.replace( /\r\n/g, '\n' ).length;
						endPos = caretPos + rawPeriText.replace( /\r\n/g, '\n' ).length;
					} else if ( e && ( e.selectionStart || e.selectionStart === 0 ) ) {
						// Firefox support
						caretPos = e.selectionStart;
						endPos = e.selectionEnd;
					}
					return options.startAndEnd ? [ caretPos, endPos ] : caretPos;
				}
				return getCaret( this.get( 0 ) );
			},
			/**
			 * @param {Object} options options
			 * FIXME document the options parameters
			 * @return {jQuery}
			 */
			setSelection: function ( options ) {
				return this.each( function () {
					var selection, length, newLines;
					// Do nothing if hidden
					if ( !$( this ).is( ':hidden' ) ) {
						if ( this.selectionStart || this.selectionStart === 0 ) {
							// Opera 9.0 doesn't allow setting selectionStart past
							// selectionEnd; any attempts to do that will be ignored
							// Make sure to set them in the right order
							if ( options.start > this.selectionEnd ) {
								this.selectionEnd = options.end;
								this.selectionStart = options.start;
							} else {
								this.selectionStart = options.start;
								this.selectionEnd = options.end;
							}
						} else if ( document.body.createTextRange ) {
							selection = rangeForElementIE( this );
							length = this.value.length;
							// IE doesn't count \n when computing the offset, so we won't either
							newLines = this.value.match( /\n/g );
							if ( newLines ) {
								length = length - newLines.length;
							}
							selection.moveStart( 'character', options.start );
							selection.moveEnd( 'character', -length + options.end );

							// This line can cause an error under certain circumstances (textarea empty, no selection)
							// Silence that error
							try {
								selection.select();
							} catch ( e ) { }
						}
					}
				} );
			},
			/**
			 * Ported from Wikia's LinkSuggest extension
			 * https://svn.wikia-code.com/wikia/trunk/extensions/wikia/LinkSuggest
			 *
			 * Scroll a textarea to the current cursor position. You can set the cursor
			 * position with setSelection()
			 *
			 * @param {Object} options options
			 * @cfg {boolean} [force=false] Whether to force a scroll even if the caret position
			 *  is already visible.
			 * FIXME document the options parameters
			 * @return {jQuery}
			 */
			scrollToCaretPosition: function ( options ) {
				function getLineLength( e ) {
					return Math.floor( e.scrollWidth / ( $.client.profile().platform === 'linux' ? 7 : 8 ) );
				}
				function getCaretScrollPosition( e ) {
					// FIXME: This functions sucks and is off by a few lines most
					// of the time. It should be replaced by something decent.
					var i, j,
						nextSpace,
						text = e.value.replace( /\r/g, '' ),
						caret = $( e ).textSelection( 'getCaretPosition' ),
						lineLength = getLineLength( e ),
						row = 0,
						charInLine = 0,
						lastSpaceInLine = 0;

					for ( i = 0; i < caret; i++ ) {
						charInLine++;
						if ( text.charAt( i ) === ' ' ) {
							lastSpaceInLine = charInLine;
						} else if ( text.charAt( i ) === '\n' ) {
							lastSpaceInLine = 0;
							charInLine = 0;
							row++;
						}
						if ( charInLine > lineLength ) {
							if ( lastSpaceInLine > 0 ) {
								charInLine = charInLine - lastSpaceInLine;
								lastSpaceInLine = 0;
								row++;
							}
						}
					}
					nextSpace = 0;
					for ( j = caret; j < caret + lineLength; j++ ) {
						if (
							text.charAt( j ) === ' ' ||
							text.charAt( j ) === '\n' ||
							caret === text.length
						) {
							nextSpace = j;
							break;
						}
					}
					if ( nextSpace > lineLength && caret <= lineLength ) {
						charInLine = caret - lastSpaceInLine;
						row++;
					}
					return ( $.client.profile().platform === 'mac' ? 13 : ( $.client.profile().platform === 'linux' ? 15 : 16 ) ) * row;
				}
				return this.each( function () {
					var scroll, range, savedRange, pos, oldScrollTop;
					// Do nothing if hidden
					if ( !$( this ).is( ':hidden' ) ) {
						if ( this.selectionStart || this.selectionStart === 0 ) {
							// Mozilla
							scroll = getCaretScrollPosition( this );
							if ( options.force || scroll < $( this ).scrollTop() ||
									scroll > $( this ).scrollTop() + $( this ).height() ) {
								$( this ).scrollTop( scroll );
							}
						} else if ( document.selection && document.selection.createRange ) {
							// IE / Opera
							/*
							 * IE automatically scrolls the selected text to the
							 * bottom of the textarea at range.select() time, except
							 * if it was already in view and the cursor position
							 * wasn't changed, in which case it does nothing. To
							 * cover that case, we'll force it to act by moving one
							 * character back and forth.
							 */
							range = document.body.createTextRange();
							savedRange = document.selection.createRange();
							pos = $( this ).textSelection( 'getCaretPosition' );
							oldScrollTop = this.scrollTop;
							range.moveToElementText( this );
							range.collapse();
							range.move( 'character', pos + 1 );
							range.select();
							if ( this.scrollTop !== oldScrollTop ) {
								this.scrollTop += range.offsetTop;
							} else if ( options.force ) {
								range.move( 'character', -1 );
								range.select();
							}
							savedRange.select();
						}
					}
					$( this ).trigger( 'scrollToPosition' );
				} );
			}
		};

		alternateFn = $( this ).data( 'jquery.textSelection' );

		// Apply defaults
		switch ( command ) {
			// case 'getContents': // no params
			// case 'setContents': // no params with defaults
			// case 'getSelection': // no params
			case 'encapsulateSelection':
				options = $.extend( {
					pre: '', // Text to insert before the cursor/selection
					peri: '', // Text to insert between pre and post and select afterwards
					post: '', // Text to insert after the cursor/selection
					ownline: false, // Put the inserted text on a line of its own
					replace: false, // If there is a selection, replace it with peri instead of leaving it alone
					selectPeri: true, // Select the peri text if it was inserted (but not if there was a selection and replace==false, or if splitlines==true)
					splitlines: false, // If multiple lines are selected, encapsulate each line individually
					selectionStart: undefined, // Position to start selection at
					selectionEnd: undefined // Position to end selection at. Defaults to start
				}, options );
				break;
			case 'getCaretPosition':
				options = $.extend( {
					// Return [start, end] instead of just start
					startAndEnd: false
				}, options );
				// FIXME: We may not need character position-based functions if we insert markers in the right places
				break;
			case 'setSelection':
				options = $.extend( {
					// Position to start selection at
					start: undefined,
					// Position to end selection at. Defaults to start
					end: undefined
				}, options );

				if ( options.end === undefined ) {
					options.end = options.start;
				}
				// FIXME: We may not need character position-based functions if we insert markers in the right places
				break;
			case 'scrollToCaretPosition':
				options = $.extend( {
					force: false // Force a scroll even if the caret position is already visible
				}, options );
				break;
			case 'register':
				if ( alternateFn ) {
					throw new Error( 'Another textSelection API was already registered' );
				}
				$( this ).data( 'jquery.textSelection', options );
				// No need to update alternateFn as this command only stores the options.
				// A command that uses it will set it again.
				return;
			case 'unregister':
				$( this ).removeData( 'jquery.textSelection' );
				return;
		}

		context = $( this ).data( 'wikiEditor-context' );
		hasWikiEditor = ( context !== undefined && context.$iframe !== undefined );

		// IE selection restore voodoo
		needSave = false;
		if ( hasWikiEditor && context.savedSelection !== null ) {
			context.fn.restoreSelection();
			needSave = true;
		}
		retval = ( alternateFn && alternateFn[ command ] || fn[ command ] ).call( this, options );
		if ( hasWikiEditor && needSave ) {
			context.fn.saveSelection();
		}

		return retval;
	};

}( jQuery ) );
