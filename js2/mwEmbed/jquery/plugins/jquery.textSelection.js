/**
 * These plugins provide extra functionality for interaction with textareas.
 */
( function( $ ) { $.fn.extend( {
/**
 * Get the currently selected text in this textarea. Will focus the textarea
 * in some browsers (IE/Opera)
 */
textSelection: function() {
	var e = this.jquery ? this[0] : this;
	var retval = '';
	if ( e.style.display == 'none' ) {
		// Do nothing
	} else if ( document.selection && document.selection.createRange ) {
		e.focus();
		var range = document.selection.createRange();
		retval = range.text;
	} else if ( e.selectionStart || e.selectionStart == '0' ) {
		retval = e.value.substring( e.selectionStart, e.selectionEnd );
	}
	return retval;
},
/**
 * Ported from skins/common/edit.js by Trevor Parscal
 * (c) 2009 Wikimedia Foundation (GPLv2) - http://www.wikimedia.org
 * 
 * Inserts text at the begining and end of a text selection, optionally
 * inserting text at the caret when selection is empty.
 * 
 * @param pre Text to insert before selection
 * @param peri Text to insert at caret if selection is empty
 * @param post Text to insert after selection
 * @param ownline If true, put the inserted text is on its own line
 * @param replace If true, replaces any selected text with peri; if false, peri is ignored and selected text is left alone
 */
encapsulateSelection: function( pre, peri, post, ownline, replace ) {
	return this.each( function() {
		/**
		 * Check if the selected text is the same as the insert text
		 */ 
		function checkSelectedText() {
			if ( !selText ) {
				selText = peri;
				isSample = true;
			} else if ( replace ) {
				selText = peri;
			} else if ( selText.charAt( selText.length - 1 ) == ' ' ) {
				// Exclude ending space char
				selText = selText.substring(0, selText.length - 1);
				post += ' ';
			}
		}
		var selText = $(this).getSelection();
		var isSample = false;
		if ( this.style.display == 'none' ) {
			// Do nothing
		} else if ( this.selectionStart || this.selectionStart == '0' ) {
			// Mozilla/Opera
			$(this).focus();
			var startPos = this.selectionStart;
			var endPos = this.selectionEnd;
			checkSelectedText();
			if ( ownline ) {
				if ( startPos != 0 && this.value.charAt( startPos - 1 ) != "\n" ) {
					pre = "\n" + pre;
				}
				if ( this.value.charAt( endPos ) != "\n" ) {
					post += "\n";
				}
			}
			this.value = this.value.substring( 0, startPos ) + pre + selText + post + this.value.substring( endPos, this.value.length );
			if ( window.opera ) {
				pre = pre.replace( /\r?\n/g, "\r\n" );
				selText = selText.replace( /\r?\n/g, "\r\n" );
				post = post.replace( /\r?\n/g, "\r\n" );
			}
			if ( isSample ) {
				this.selectionStart = startPos + pre.length;
				this.selectionEnd = startPos + pre.length + selText.length;
			} else {
				this.selectionStart = startPos + pre.length + selText.length + post.length;
				this.selectionEnd = this.selectionStart;
			}
		} else if ( document.selection && document.selection.createRange ) {
			// IE
			$(this).focus();
			var range = document.selection.createRange();
			if ( ownline && range.moveStart ) {
				var range2 = document.selection.createRange();
				range2.collapse();
				range2.moveStart( 'character', -1 );
				// FIXME: Which check is correct?
				if ( range2.text != "\r" && range2.text != "\n" && range2.text != "" ) {
					pre = "\n" + pre;
				}
				var range3 = document.selection.createRange();
				range3.collapse( false );
				range3.moveEnd( 'character', 1 );
				if ( range3.text != "\r" && range3.text != "\n" && range3.text != "" ) {
					post += "\n";
				}
			}
			checkSelectedText();
			range.text = pre + selText + post;
			if ( isSample && range.moveStart ) {
				range.moveStart( 'character', - post.length - selText.length );
				range.moveEnd( 'character', - post.length );
			}
			range.select();
		}
		// Scroll the textarea to the inserted text
		$(this).scrollToCaretPosition();
		$(this).trigger( 'encapsulateSelection', [ pre, peri, post, ownline, replace ] );
	});
},
/**
 * Ported from Wikia's LinkSuggest extension
 * https://svn.wikia-code.com/wikia/trunk/extensions/wikia/LinkSuggest
 * Some code copied from
 * http://www.dedestruct.com/2008/03/22/howto-cross-browser-cursor-position-in-textareas/
 *
 * Get the position (in resolution of bytes not nessecarily characters)
 * in a textarea 
 */
 getCaretPosition: function( startAndEnd ) {
	function getCaret( e ) {
		var caretPos = 0, endPos = 0;
		if ( $.browser.msie ) {
			// IE Support
			var postFinished = false;
			var periFinished = false;
			var postFinished = false;
			var preText, rawPreText, periText;
			var rawPeriText, postText, rawPostText;
			// Create range containing text in the selection
			var periRange = document.selection.createRange().duplicate();
			// Create range containing text before the selection
			var preRange = document.body.createTextRange();
			// Select all the text
			preRange.moveToElementText(e);
			// Move the end where we need it
			preRange.setEndPoint("EndToStart", periRange);
			// Create range containing text after the selection
			var postRange = document.body.createTextRange();
			// Select all the text
			postRange.moveToElementText(e);
			// Move the start where we need it
			postRange.setEndPoint("StartToEnd", periRange);
			// Load the text values we need to compare
			preText = rawPreText = preRange.text;
			periText = rawPeriText = periRange.text;
			postText = rawPostText = postRange.text;
			/*
			 * Check each range for trimmed newlines by shrinking the range by 1
			 * character and seeing if the text property has changed. If it has
			 * not changed then we know that IE has trimmed a \r\n from the end.
			 */
			do {
				if ( !postFinished ) {
					if ( preRange.compareEndPoints( "StartToEnd", preRange ) == 0 ) {
						postFinished = true;
					} else {
						preRange.moveEnd( "character", -1 )
						if ( preRange.text == preText ) {
							rawPreText += "\r\n";
						} else {
							postFinished = true;
						}
					}
				}
				if ( !periFinished ) {
					if ( periRange.compareEndPoints( "StartToEnd", periRange ) == 0 ) {
						periFinished = true;
					} else {
						periRange.moveEnd( "character", -1 )
						if ( periRange.text == periText ) {
							rawPeriText += "\r\n";
						} else {
							periFinished = true;
						}
					}
				}
				if ( !postFinished ) {
					if ( postRange.compareEndPoints("StartToEnd", postRange) == 0 ) {
						postFinished = true;
					} else {
						postRange.moveEnd( "character", -1 )
						if ( postRange.text == postText ) {
							rawPostText += "\r\n";
						} else {
							postFinished = true;
						}
					}
				}
			} while ( ( !postFinished || !periFinished || !postFinished ) );
			caretPos = rawPreText.replace( /\r\n/g, "\n" ).length;
			endPos = caretPos + rawPeriText.replace( /\r\n/g, "\n" ).length;
		} else if ( e.selectionStart || e.selectionStart == '0' ) {
			// Firefox support
			caretPos = e.selectionStart;
			endPos = e.selectionEnd;
		}
		return startAndEnd ? [ caretPos, endPos ] : caretPos;
	}
	return getCaret( this.get( 0 ) );
},
setSelection: function( start, end ) {
	if ( typeof end == 'undefined' )
		end = start;
	return this.each( function() {
		if ( this.selectionStart || this.selectionStart == '0' ) {
			// Opera 9.0 doesn't allow setting selectionStart past
			// selectionEnd; any attempts to do that will be ignored
			// Make sure to set them in the right order
			if ( start > this.selectionEnd ) {
				this.selectionEnd = end;
				this.selectionStart = start;
			} else {
				this.selectionStart = start;
				this.selectionEnd = end;
			}
		} else if ( document.body.createTextRange ) {
			var selection = document.body.createTextRange();
			selection.moveToElementText( this );
			var length = selection.text.length;
			selection.moveStart( 'character', start );
			selection.moveEnd( 'character', -length + end );
			selection.select();
		}
	});
},
/**
 * Ported from Wikia's LinkSuggest extension
 * https://svn.wikia-code.com/wikia/trunk/extensions/wikia/LinkSuggest
 * 
 * Scroll a textarea to the current cursor position. You can set the cursor
 * position with setSelection()
 * @param force boolean Whether to force a scroll even if the caret position
 *  is already visible. Defaults to false
 */
scrollToCaretPosition: function( force ) {
	function getLineLength( e ) {
		return Math.floor( e.scrollWidth / ( $.os.name == 'linux' ? 7 : 8 ) );
	}
	function getCaretScrollPosition( e ) {
		// FIXME: This functions sucks and is off by a few lines most
		// of the time. It should be replaced by something decent.
		var text = e.value.replace( /\r/g, "" );
		var caret = $( e ).getCaretPosition();
		var lineLength = getLineLength( e );
		var row = 0;
		var charInLine = 0;
		var lastSpaceInLine = 0;
		for ( i = 0; i < caret; i++ ) {
			charInLine++;
			if ( text.charAt( i ) == " " ) {
				lastSpaceInLine = charInLine;
			} else if ( text.charAt( i ) == "\n" ) {
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
		var nextSpace = 0;
		for ( j = caret; j < caret + lineLength; j++ ) {
			if (
				text.charAt( j ) == " " ||
				text.charAt( j ) == "\n" ||
				caret == text.length
			) {
				nextSpace = j;
				break;
			}
		}
		if ( nextSpace > lineLength && caret <= lineLength ) {
			charInLine = caret - lastSpaceInLine;
			row++;
		}
		return ( $.os.name == 'mac' ? 13 : ( $.os.name == 'linux' ? 15 : 16 ) ) * row;
	}
	return this.each(function() {
		if ( this.selectionStart || this.selectionStart == '0' ) {
			// Mozilla
			var scroll = getCaretScrollPosition( this );
			if ( force || scroll < $(this).scrollTop() ||
					scroll > $(this).scrollTop() + $(this).height() )
				$(this).scrollTop( scroll );
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
			var range = document.selection.createRange();
			var pos = $(this).getCaretPosition();
			var oldScrollTop = this.scrollTop;
			range.moveToElementText( this );
			range.collapse();
			range.move( 'character', pos + 1);
			range.select();
			if ( this.scrollTop != oldScrollTop )
				this.scrollTop += range.offsetTop;
			else if ( force ) {
				range.move( 'character', -1 );
				range.select();
			}
		}
		$(this).trigger( 'scrollToPosition' );
	} );
}

} ); } )( jQuery );