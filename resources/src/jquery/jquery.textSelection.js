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
 * Do things to the selection in a `<textarea>`, or a textarea-like editable element.
 * Provided by the `jquery.textSelection` ResourceLoader module.
 *
 * @example
 * mw.loader.using( 'jquery.textSelection' ).then( () => {
 *     const contents = $( '#wpTextbox1' ).textSelection( 'getContents' );
 * } );
 *
 * @module jquery.textSelection
 */
( function () {
	/**
	 * Checks if you can try to use insertText (it might still fail).
	 *
	 * @ignore
	 * @return {boolean}
	 */
	function supportsInsertText() {
		return $( this ).data( 'jquery.textSelection' ) === undefined &&
			typeof document.execCommand === 'function' &&
			typeof document.queryCommandSupported === 'function' &&
			document.queryCommandSupported( 'insertText' );
	}

	/**
	 * Insert text into textarea or contenteditable.
	 *
	 * @ignore
	 * @param {HTMLElement} field Field to select.
	 * @param {string} content Text to insert.
	 * @param {Function} fallback To execute as a fallback.
	 */
	function execInsertText( field, content, fallback ) {
		let inserted = false;

		if (
			supportsInsertText() &&
			!(
				// Support: Chrome, Safari
				// Inserting multiple lines is very slow in Chrome/Safari (T343795)
				// If this is ever fixed, remove the dependency on jquery.client
				$.client.profile().layout === 'webkit' &&
				content.split( '\n' ).length > 100
			)
		) {
			field.focus();
			try {
				if (
					// Ensure the field was focused, otherwise we can't use execCommand() to change it.
					// focus() can fail if e.g. the field is disabled, or its container is inert.
					document.activeElement === field &&
					// Try to insert
					document.execCommand( 'insertText', false, content )
				) {
					inserted = true;
				}
			} catch ( e ) {}
		}
		// Fallback
		if ( !inserted ) {
			fallback.call( field, content );
		}
	}

	const fn = {
		/**
		 * Get the contents of the textarea.
		 *
		 * @return {string}
		 * @memberof module:jquery.textSelection
		 */
		getContents: function () {
			return this.val();
		},

		/**
		 * Set the contents of the textarea, replacing anything that was there before.
		 *
		 * @param {string} content
		 * @return {jQuery}
		 * @chainable
		 * @memberof module:jquery.textSelection
		 */
		setContents: function ( content ) {
			return this.each( function () {
				const scrollTop = this.scrollTop;
				this.select();
				execInsertText( this, content, function () {
					$( this ).val( content );
				} );
				// Setting this.value may scroll the textarea, restore the scroll position
				this.scrollTop = scrollTop;
			} );
		},

		/**
		 * Get the currently selected text in this textarea.
		 *
		 * @return {string}
		 * @memberof module:jquery.textSelection
		 */
		getSelection: function () {
			const el = this.get( 0 );

			let val;
			if ( !el ) {
				val = '';
			} else {
				val = el.value.slice( el.selectionStart, el.selectionEnd );
			}

			return val;
		},

		/**
		 * Replace the selected text in the textarea with the given text, or insert it at the cursor.
		 *
		 * @param {string} value
		 * @return {jQuery}
		 * @chainable
		 * @memberof module:jquery.textSelection
		 */
		replaceSelection: function ( value ) {
			return this.each( function () {
				execInsertText( this, value, function () {
					const allText = $( this ).textSelection( 'getContents' );
					const currSelection = $( this ).textSelection( 'getCaretPosition', { startAndEnd: true } );
					const startPos = currSelection[ 0 ];
					const endPos = currSelection[ 1 ];

					$( this ).textSelection( 'setContents', allText.slice( 0, startPos ) + value +
						allText.slice( endPos ) );
					$( this ).textSelection( 'setSelection', {
						start: startPos,
						end: startPos + value.length
					} );
				} );
			} );
		},

		/**
		 * Insert text at the beginning and end of a text selection, optionally
		 * inserting text at the caret when selection is empty.
		 *
		 * Also focusses the textarea.
		 *
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
		 * @memberof module:jquery.textSelection
		 */
		encapsulateSelection: function ( options ) {
			return this.each( function () {
				let selText, isSample,
					pre = options.pre,
					post = options.post;

				/**
				 * Check if the selected text is the same as the insert text
				 *
				 * @ignore
				 */
				function checkSelectedText() {
					if ( !selText ) {
						selText = options.peri;
						isSample = true;
					} else if ( options.replace ) {
						selText = options.peri;
					} else {
						while ( selText.endsWith( ' ' ) ) {
							// Exclude ending space char
							selText = selText.slice( 0, -1 );
							post += ' ';
						}
						while ( selText.startsWith( ' ' ) ) {
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
				 * @ignore
				 * @param {string} text Selected text
				 * @param {string} preText Text before
				 * @param {string} postText Text after
				 * @return {string} Wrapped text
				 */
				function doSplitLines( text, preText, postText ) {
					const selTextArr = text.split( '\n' );
					let insText = '';
					for ( let i = 0; i < selTextArr.length; i++ ) {
						insText += preText + selTextArr[ i ] + postText;
						if ( i !== selTextArr.length - 1 ) {
							insText += '\n';
						}
					}
					return insText;
				}

				isSample = false;
				$( this ).trigger( 'focus' );
				if ( options.selectionStart !== undefined ) {
					$( this ).textSelection( 'setSelection', { start: options.selectionStart, end: options.selectionEnd } );
				}

				selText = $( this ).textSelection( 'getSelection' );
				const allText = $( this ).textSelection( 'getContents' );
				const currSelection = $( this ).textSelection( 'getCaretPosition', { startAndEnd: true } );
				let startPos = currSelection[ 0 ];
				const endPos = currSelection[ 1 ];
				checkSelectedText();
				let combiningCharSelectionBug = false;
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

				let insertText = pre + selText + post;
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
				if ( isSample && options.selectPeri && ( !options.splitlines || ( options.splitlines && !selText.includes( '\n' ) ) ) ) {
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
		 * @param {Object} [options]
		 * @param {Object} [options.startAndEnd=false] Return range of the selection rather than just start
		 * @return {number|number[]}
		 *  - When `startAndEnd` is `false`: number
		 *  - When `startAndEnd` is `true`: array with two numbers, for start and end of selection
		 * @memberof module:jquery.textSelection
		 */
		getCaretPosition: function ( options ) {
			function getCaret( e ) {
				let caretPos = 0,
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
		 * @param {Object} [options]
		 * @param {number} options.start
		 * @param {number} [options.end=options.start]
		 * @return {jQuery}
		 * @chainable
		 * @memberof module:jquery.textSelection
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
		 * position with {@link module:jquery.textSelection.setSelection setSelection}.
		 *
		 * @param {Object} [options]
		 * @param {string} [options.force=false] Whether to force a scroll even if the caret position
		 *  is already visible.
		 * @return {jQuery}
		 * @chainable
		 * @memberof module:jquery.textSelection
		 */
		scrollToCaretPosition: function ( options ) {
			return this.each( function () {
				const clientHeight = this.clientHeight,
					origValue = this.value,
					origSelectionStart = this.selectionStart,
					origSelectionEnd = this.selectionEnd,
					origScrollTop = this.scrollTop;

				// Delete all text after the selection and scroll the textarea to the end.
				// This ensures the selection is visible (aligned to the bottom of the textarea).
				// Then restore the text we deleted without changing scroll position.
				this.value = this.value.slice( 0, this.selectionEnd );
				this.scrollTop = this.scrollHeight;
				// Chrome likes to adjust scroll position when changing value, so save and re-set later.
				// Note that this is not equal to scrollHeight, it's scrollHeight minus clientHeight.
				let calcScrollTop = this.scrollTop;
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
	 * Register an alternative textSelection API for this element.
	 *
	 * @method register
	 * @param {Object} functions Functions to replace. Keys are command names (as in {@link module:jquery.textSelection.textSelection textSelection},
	 *  except 'register' and 'unregister'). Values are functions to execute when a given command is
	 *  called.
	 * @memberof module:jquery.textSelection
	 */

	/**
	 * Unregister the alternative textSelection API for this element (see {@link module:jquery.textSelection.register register}).
	 *
	 * @method unregister
	 * @memberof module:jquery.textSelection
	 */

	/**
	 * Execute a textSelection command about the element.
	 *
	 * @example
	 * var $textbox = $( '#wpTextbox1' );
	 * $textbox.textSelection( 'setContents', 'This is bold!' );
	 * $textbox.textSelection( 'setSelection', { start: 8, end: 12 } );
	 * $textbox.textSelection( 'encapsulateSelection', { pre: '<b>', post: '</b>' } );
	 * // Result: Textbox contains 'This is <b>bold</b>!', with cursor before the '!'
	 * @memberof module:jquery.textSelection
	 * @method
	 * @param {string} command Command to execute, one of:
	 *
	 *  - {@link module:jquery.textSelection.getContents getContents}
	 *  - {@link module:jquery.textSelection.setContents setContents}
	 *  - {@link module:jquery.textSelection.getSelection getSelection}
	 *  - {@link module:jquery.textSelection.replaceSelection replaceSelection}
	 *  - {@link module:jquery.textSelection.encapsulateSelection encapsulateSelection}
	 *  - {@link module:jquery.textSelection.getCaretPosition getCaretPosition}
	 *  - {@link module:jquery.textSelection.setSelection setSelection}
	 *  - {@link module:jquery.textSelection.scrollToCaretPosition scrollToCaretPosition}
	 *  - {@link module:jquery.textSelection.register register}
	 *  - {@link module:jquery.textSelection.unregister unregister}
	 * @param {any} [commandOptions] Options to pass to the command
	 * @return {any} Depending on the command
	 */
	$.fn.textSelection = function ( command, commandOptions ) {
		const alternateFn = $( this ).data( 'jquery.textSelection' );

		// Prevent values of `undefined` overwriting defaults (T368102)
		for ( const key in commandOptions ) {
			if ( commandOptions[ key ] === undefined ) {
				delete commandOptions[ key ];
			}
		}

		// Apply defaults
		switch ( command ) {
			// case 'getContents': // no params
			// case 'setContents': // no params with defaults
			// case 'getSelection': // no params
			// case 'replaceSelection': // no params with defaults
			case 'encapsulateSelection':
				commandOptions = Object.assign( {
					pre: '',
					peri: '',
					post: '',
					ownline: false,
					replace: false,
					selectPeri: true,
					splitlines: false,
					selectionStart: undefined,
					selectionEnd: undefined
				}, commandOptions );
				break;
			case 'getCaretPosition':
				commandOptions = Object.assign( {
					startAndEnd: false
				}, commandOptions );
				break;
			case 'setSelection':
				commandOptions = Object.assign( {
					start: undefined,
					end: undefined
				}, commandOptions );
				if ( commandOptions.end === undefined ) {
					commandOptions.end = commandOptions.start;
				}
				break;
			case 'scrollToCaretPosition':
				commandOptions = Object.assign( {
					force: false
				}, commandOptions );
				break;
			case 'register':
				if ( alternateFn ) {
					throw new Error( 'Another textSelection API was already registered' );
				}
				$( this ).data( 'jquery.textSelection', commandOptions );
				// No need to update alternateFn as this command only stores the options.
				// A command that uses it will set it again.
				return;
			case 'unregister':
				$( this ).removeData( 'jquery.textSelection' );
				return;
		}

		const retval = ( alternateFn && alternateFn[ command ] || fn[ command ] ).call( this, commandOptions );

		return retval;
	};
}() );
