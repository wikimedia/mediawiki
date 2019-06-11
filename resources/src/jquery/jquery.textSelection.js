/*!
 * These plugins provide extra functionality for interaction with textareas.
 *
 * - encapsulateSelection: Ported from skins/common/edit.js by Trevor Parscal
 *   © 2009 Wikimedia Foundation (GPLv2) - https://www.wikimedia.org
 * - getCaretPosition, scrollToCaretPosition: Ported from Wikia's LinkSuggest extension
 *   https://github.com/Wikia/app/blob/c0cd8b763/extensions/wikia/LinkSuggest/js/jquery.wikia.linksuggest.js
 *   © 2010 Inez Korczyński (korczynski@gmail.com) & Jesús Martínez Novo (martineznovo@gmail.com) (GPLv2)
 */

/**
 * @class jQuery.plugin.textSelection
 *
 * Do things to the selection in a `<textarea>`, or a textarea-like editable element.
 *
 *     var $textbox = $( '#wpTextbox1' );
 *     $textbox.textSelection( 'setContents', 'This is bold!' );
 *     $textbox.textSelection( 'setSelection', { start: 8, end: 12 } );
 *     $textbox.textSelection( 'encapsulateSelection', { pre: '<b>', post: '</b>' } );
 *     // Result: Textbox contains 'This is <b>bold</b>!', with cursor before the '!'
 */
( function () {
	/**
	 * Do things to the selection in a `<textarea>`, or a textarea-like editable element.
	 *
	 *     var $textbox = $( '#wpTextbox1' );
	 *     $textbox.textSelection( 'setContents', 'This is bold!' );
	 *     $textbox.textSelection( 'setSelection', { start: 8, end: 12 } );
	 *     $textbox.textSelection( 'encapsulateSelection', { pre: '<b>', post: '</b>' } );
	 *     // Result: Textbox contains 'This is <b>bold</b>!', with cursor before the '!'
	 *
	 * @param {string} command Command to execute, one of:
	 *
	 *  - {@link jQuery.plugin.textSelection#getContents getContents}
	 *  - {@link jQuery.plugin.textSelection#setContents setContents}
	 *  - {@link jQuery.plugin.textSelection#getSelection getSelection}
	 *  - {@link jQuery.plugin.textSelection#replaceSelection replaceSelection}
	 *  - {@link jQuery.plugin.textSelection#encapsulateSelection encapsulateSelection}
	 *  - {@link jQuery.plugin.textSelection#getCaretPosition getCaretPosition}
	 *  - {@link jQuery.plugin.textSelection#setSelection setSelection}
	 *  - {@link jQuery.plugin.textSelection#scrollToCaretPosition scrollToCaretPosition}
	 *  - {@link jQuery.plugin.textSelection#register register}
	 *  - {@link jQuery.plugin.textSelection#unregister unregister}
	 * @param {Mixed} [options] Options to pass to the command
	 * @return {Mixed} Depending on the command
	 */
	$.fn.textSelection = function ( command, options ) {
		var fn,
			alternateFn,
			retval;

		fn = {
			/**
			 * Get the contents of the textarea.
			 *
			 * @private
			 * @return {string}
			 */
			getContents: function () {
				return this.val();
			},

			/**
			 * Set the contents of the textarea, replacing anything that was there before.
			 *
			 * @private
			 * @param {string} content
			 * @return {jQuery}
			 * @chainable
			 */
			setContents: function ( content ) {
				return this.each( function () {
					var scrollTop = this.scrollTop;
					$( this ).val( content );
					// Setting this.value may scroll the textarea, restore the scroll position
					this.scrollTop = scrollTop;
				} );
			},

			/**
			 * Get the currently selected text in this textarea.
			 *
			 * @private
			 * @return {string}
			 */
			getSelection: function () {
				var retval,
					el = this.get( 0 );

				if ( !el ) {
					retval = '';
				} else {
					retval = el.value.substring( el.selectionStart, el.selectionEnd );
				}

				return retval;
			},

			/**
			 * Replace the selected text in the textarea with the given text, or insert it at the cursor.
			 *
			 * @private
			 * @param {string} value
			 * @return {jQuery}
			 * @chainable
			 */
			replaceSelection: function ( value ) {
				return this.each( function () {
					var allText, currSelection, startPos, endPos;

					allText = $( this ).textSelection( 'getContents' );
					currSelection = $( this ).textSelection( 'getCaretPosition', { startAndEnd: true } );
					startPos = currSelection[ 0 ];
					endPos = currSelection[ 1 ];

					$( this ).textSelection( 'setContents', allText.slice( 0, startPos ) + value +
						allText.slice( endPos ) );
					$( this ).textSelection( 'setSelection', {
						start: startPos,
						end: startPos + value.length
					} );
				} );
			},

			/**
			 * Insert text at the beginning and end of a text selection, optionally
			 * inserting text at the caret when selection is empty.
			 *
			 * Also focusses the textarea.
			 *
			 * @private
			 * @param {Object} [options]
			 * @param {string} [options.pre] Text to insert before the cursor/selection
			 * @param {string} [options.peri] Text to insert between pre and post and select afterwards
			 * @param {string} [options.post] Text to insert after the cursor/selection
			 * @param {boolean} [options.ownline=false] Put the inserted text on a line of its own
			 * @param {boolean} [options.replace=false] If there is a selection, replace it with peri
			 *  instead of leaving it alone
			 * @param {boolean} [options.selectPeri=true] Select the peri text if it was inserted (but not
			 *  if there was a selection and replace==false, or if splitlines==true)
			 * @param {boolean} [options.splitlines=false] If multiple lines are selected, encapsulate
			 *  each line individually
			 * @param {number} [options.selectionStart] Position to start selection at
			 * @param {number} [options.selectionEnd=options.selectionStart] Position to end selection at
			 * @return {jQuery}
			 * @chainable
			 */
			encapsulateSelection: function ( options ) {
				return this.each( function () {
					var selText, allText, currSelection, insertText,
						combiningCharSelectionBug = false,
						isSample, startPos, endPos,
						pre = options.pre,
						post = options.post;

					/**
					 * @ignore
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
					 * @ignore
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
					$( this ).trigger( 'focus' );
					if ( options.selectionStart !== undefined ) {
						$( this ).textSelection( 'setSelection', { start: options.selectionStart, end: options.selectionEnd } );
					}

					selText = $( this ).textSelection( 'getSelection' );
					allText = $( this ).textSelection( 'getContents' );
					currSelection = $( this ).textSelection( 'getCaretPosition', { startAndEnd: true } );
					startPos = currSelection[ 0 ];
					endPos = currSelection[ 1 ];
					checkSelectedText();
					if (
						options.selectionStart !== undefined &&
						endPos - startPos !== options.selectionEnd - options.selectionStart
					) {
						// This means there is a difference in the selection range returned by browser and what we passed.
						// This happens for Safari 5.1, Chrome 12 in the case of composite characters. Ref T32130
						// Set the startPos to the correct position.
						startPos = options.selectionStart;
						combiningCharSelectionBug = true;
						// TODO: The comment above is from 2011. Is this still a problem for browsers we support today?
						// Minimal test case: https://jsfiddle.net/z4q7a2ko/
					}

					insertText = pre + selText + post;
					if ( options.splitlines ) {
						insertText = doSplitLines( selText, pre, post );
					}
					if ( options.ownline ) {
						if ( startPos !== 0 && allText.charAt( startPos - 1 ) !== '\n' && allText.charAt( startPos - 1 ) !== '\r' ) {
							insertText = '\n' + insertText;
							pre += '\n';
						}
						if ( allText.charAt( endPos ) !== '\n' && allText.charAt( endPos ) !== '\r' ) {
							insertText += '\n';
							post += '\n';
						}
					}
					if ( combiningCharSelectionBug ) {
						$( this ).textSelection( 'setContents', allText.slice( 0, startPos ) + insertText +
							allText.slice( endPos ) );
					} else {
						$( this ).textSelection( 'replaceSelection', insertText );
					}
					if ( isSample && options.selectPeri && ( !options.splitlines || ( options.splitlines && selText.indexOf( '\n' ) === -1 ) ) ) {
						$( this ).textSelection( 'setSelection', {
							start: startPos + pre.length,
							end: startPos + pre.length + selText.length
						} );
					} else {
						$( this ).textSelection( 'setSelection', {
							start: startPos + insertText.length
						} );
					}
					$( this ).trigger( 'encapsulateSelection', [ options.pre, options.peri, options.post, options.ownline,
						options.replace, options.splitlines ] );
				} );
			},

			/**
			 * Get the current cursor position (in UTF-16 code units) in a textarea.
			 *
			 * @private
			 * @param {Object} [options]
			 * @param {Object} [options.startAndEnd=false] Return range of the selection rather than just start
			 * @return {Mixed}
			 *  - When `startAndEnd` is `false`: number
			 *  - When `startAndEnd` is `true`: array with two numbers, for start and end of selection
			 */
			getCaretPosition: function ( options ) {
				function getCaret( e ) {
					var caretPos = 0,
						endPos = 0;
					if ( e ) {
						caretPos = e.selectionStart;
						endPos = e.selectionEnd;
					}
					return options.startAndEnd ? [ caretPos, endPos ] : caretPos;
				}
				return getCaret( this.get( 0 ) );
			},

			/**
			 * Set the current cursor position (in UTF-16 code units) in a textarea.
			 *
			 * @private
			 * @param {Object} [options]
			 * @param {number} options.start
			 * @param {number} [options.end=options.start]
			 * @return {jQuery}
			 * @chainable
			 */
			setSelection: function ( options ) {
				return this.each( function () {
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
				} );
			},

			/**
			 * Scroll a textarea to the current cursor position. You can set the cursor
			 * position with #setSelection.
			 *
			 * @private
			 * @param {Object} [options]
			 * @param {string} [options.force=false] Whether to force a scroll even if the caret position
			 *  is already visible.
			 * @return {jQuery}
			 * @chainable
			 */
			scrollToCaretPosition: function ( options ) {
				return this.each( function () {
					var
						clientHeight = this.clientHeight,
						origValue = this.value,
						origSelectionStart = this.selectionStart,
						origSelectionEnd = this.selectionEnd,
						origScrollTop = this.scrollTop,
						calcScrollTop;

					// Delete all text after the selection and scroll the textarea to the end.
					// This ensures the selection is visible (aligned to the bottom of the textarea).
					// Then restore the text we deleted without changing scroll position.
					this.value = this.value.slice( 0, this.selectionEnd );
					this.scrollTop = this.scrollHeight;
					// Chrome likes to adjust scroll position when changing value, so save and re-set later.
					// Note that this is not equal to scrollHeight, it's scrollHeight minus clientHeight.
					calcScrollTop = this.scrollTop;
					this.value = origValue;
					this.selectionStart = origSelectionStart;
					this.selectionEnd = origSelectionEnd;

					if ( !options.force ) {
						// Check if all the scrolling was unnecessary and if so, restore previous position.
						// If the current position is no more than a screenful above the original,
						// the selection was previously visible on the screen.
						if ( calcScrollTop < origScrollTop && origScrollTop - calcScrollTop < clientHeight ) {
							calcScrollTop = origScrollTop;
						}
					}

					this.scrollTop = calcScrollTop;

					$( this ).trigger( 'scrollToPosition' );
				} );
			}
		};

		/**
		 * @method register
		 *
		 * Register an alternative textSelection API for this element.
		 *
		 * @private
		 * @param {Object} functions Functions to replace. Keys are command names (as in #textSelection,
		 *  except 'register' and 'unregister'). Values are functions to execute when a given command is
		 *  called.
		 */

		/**
		 * @method unregister
		 *
		 * Unregister the alternative textSelection API for this element (see #register).
		 *
		 * @private
		 */

		alternateFn = $( this ).data( 'jquery.textSelection' );

		// Apply defaults
		switch ( command ) {
			// case 'getContents': // no params
			// case 'setContents': // no params with defaults
			// case 'getSelection': // no params
			// case 'replaceSelection': // no params with defaults
			case 'encapsulateSelection':
				options = $.extend( {
					pre: '',
					peri: '',
					post: '',
					ownline: false,
					replace: false,
					selectPeri: true,
					splitlines: false,
					selectionStart: undefined,
					selectionEnd: undefined
				}, options );
				break;
			case 'getCaretPosition':
				options = $.extend( {
					startAndEnd: false
				}, options );
				break;
			case 'setSelection':
				options = $.extend( {
					start: undefined,
					end: undefined
				}, options );
				if ( options.end === undefined ) {
					options.end = options.start;
				}
				break;
			case 'scrollToCaretPosition':
				options = $.extend( {
					force: false
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

	/**
	 * @class jQuery
	 */
	/**
	 * @method textSelection
	 * @inheritdoc jQuery.plugin.textSelection#textSelection
	 */

}() );
