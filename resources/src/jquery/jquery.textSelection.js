/**
 * These plugins provide extra functionality for interaction with textareas.
 */
( function ( $ ) {
	$.fn.textSelection = function ( command, options ) {
		var fn,
			alternateFn,
			retval;

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
			 * Get the currently selected text in this textarea.
			 *
			 * @return {string}
			 */
			getSelection: function () {
				var retval,
					el = this.get( 0 );

				if ( !el || $( el ).is( ':hidden' ) ) {
					retval = '';
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
						isSample, startPos, endPos,
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
						if ( this.selectionStart || this.selectionStart === 0 ) {
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
			 *
			 * Get the position (in resolution of bytes not necessarily characters)
			 * in a textarea
			 *
			 * @param {Object} options Options
			 * FIXME document the options parameters
			 * @return {number} Position
			 */
			getCaretPosition: function ( options ) {
				function getCaret( e ) {
					var caretPos = 0,
						endPos = 0;

					if ( e && ( e.selectionStart || e.selectionStart === 0 ) ) {
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
					var scroll;
					// Do nothing if hidden
					if ( !$( this ).is( ':hidden' ) ) {
						if ( this.selectionStart || this.selectionStart === 0 ) {
							scroll = getCaretScrollPosition( this );
							if ( options.force || scroll < $( this ).scrollTop() ||
									scroll > $( this ).scrollTop() + $( this ).height() ) {
								$( this ).scrollTop( scroll );
							}
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

		retval = ( alternateFn && alternateFn[ command ] || fn[ command ] ).call( this, options );

		return retval;
	};

}( jQuery ) );
