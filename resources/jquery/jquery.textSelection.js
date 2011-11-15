/**
 * These plugins provide extra functionality for interaction with textareas.
 */
( function( $ ) {

if (document.selection && document.selection.createRange) {
	// On IE, patch the focus() method to restore the windows' scroll position
	// (bug 32241)
	$.fn.extend({
		focus : (function ( _focus ) {
			return function () {
				if ( arguments.length == 0 ) {
					var $w = $( window );
					var state = {top: $w.scrollTop(), left: $w.scrollLeft()};
					var result = _focus.apply( this, arguments );
					window.scrollTo( state.top, state.left );
					return result;
				}
				return _focus.apply( this, arguments );
			};
		})( $.fn.focus )
	});
}

$.fn.textSelection = function( command, options ) {

/**
 * Helper function to get an IE TextRange object for an element
 */
function rangeForElementIE( e ) {
	if ( e.nodeName.toLowerCase() == 'input' ) {
		return e.createTextRange();
	} else {
		var sel = document.body.createTextRange();
		sel.moveToElementText( e );
		return sel;
	}
}

/**
 * Helper function for IE for activating the textarea. Called only in the
 * IE-specific code paths below; makes use of IE-specific non-standard
 * function setActive() if possible to avoid screen flicker.
 */
function activateElementOnIE( element ) {
	if ( element.setActive ) {
		element.setActive(); // bug 32241: doesn't scroll
	} else {
		$( element ).focus(); // may scroll (but we patched it above)
	}
}

var fn = {
/**
 * Get the contents of the textarea
 */
getContents: function() {
	return this.val();
},
/**
 * Get the currently selected text in this textarea. Will focus the textarea
 * in some browsers (IE/Opera)
 */
getSelection: function() {
	var e = this.get( 0 );
	var retval = '';
	if ( $(e).is( ':hidden' ) ) {
		// Do nothing
	} else if ( document.selection && document.selection.createRange ) {
		activateElementOnIE( e );
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
 * @fixme document the options parameters
 */
encapsulateSelection: function( options ) {
	return this.each( function() {
		var pre = options.pre, post = options.post;
		
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
				while ( selText.charAt( selText.length - 1 ) == ' ' ) {
					// Exclude ending space char
					selText = selText.substring( 0, selText.length - 1 );
					post += ' ';
				}
				while ( selText.charAt( 0 ) == ' ' ) {
					// Exclude prepending space char
					selText = selText.substring( 1, selText.length );
					pre = ' ' + pre;
				}
			}
		}
		
		/**
		 * Do the splitlines stuff.
		 * 
		 * Wrap each line of the selected text with pre and post
		 */
		function doSplitLines( selText, pre, post ) {
			var insertText = '';
			var selTextArr = selText.split( '\n' );
			for ( var i = 0; i < selTextArr.length; i++ ) {
				insertText += pre + selTextArr[i] + post;
				if ( i != selTextArr.length - 1 ) {
					insertText += '\n';
				}
			}
			return insertText;
		}
		
		var isSample = false;
		if ( this.style.display == 'none' ) {
			// Do nothing
		} else if ( this.selectionStart || this.selectionStart == '0' ) {
			// Mozilla/Opera
			$(this).focus();
			if ( options.selectionStart !== undefined ) {
				$(this).textSelection( 'setSelection', { 'start': options.selectionStart, 'end': options.selectionEnd } );
			}
			
			var selText = $(this).textSelection( 'getSelection' );
			var startPos = this.selectionStart;
			var endPos = this.selectionEnd;
			var scrollTop = this.scrollTop;
			checkSelectedText();
			
			if ( options.selectionStart !== undefined
					&& endPos - startPos != options.selectionEnd - options.selectionStart )
			{
				// This means there is a difference in the selection range returned by browser and what we passed.
				// This happens for Chrome in the case of composite characters. Ref bug #30130
				// Set the startPos to the correct position.
				startPos = options.selectionStart;
			}

			var insertText = pre + selText + post;
			if ( options.splitlines ) {
				insertText = doSplitLines( selText, pre, post );
			}
			if ( options.ownline ) {
				if ( startPos != 0 && this.value.charAt( startPos - 1 ) != "\n" ) {
					insertText = "\n" + insertText;
				}
				if ( this.value.charAt( endPos ) != "\n" ) {
					insertText += "\n";
				}
			}
			this.value = this.value.substring( 0, startPos ) + insertText +
				this.value.substring( endPos, this.value.length );
			// Setting this.value scrolls the textarea to the top, restore the scroll position
			this.scrollTop = scrollTop;
			if ( window.opera ) {
				pre = pre.replace( /\r?\n/g, "\r\n" );
				selText = selText.replace( /\r?\n/g, "\r\n" );
				post = post.replace( /\r?\n/g, "\r\n" );
			}
			if ( isSample && options.selectPeri && !options.splitlines ) {
				this.selectionStart = startPos + pre.length;
				this.selectionEnd = startPos + pre.length + selText.length;
			} else {
				this.selectionStart = startPos + insertText.length;
				this.selectionEnd = this.selectionStart;
			}
		} else if ( document.selection && document.selection.createRange ) {
			// IE
			activateElementOnIE( this );
			if ( context ) {
				context.fn.restoreCursorAndScrollTop();
			}
			if ( options.selectionStart !== undefined ) {
				$(this).textSelection( 'setSelection', { 'start': options.selectionStart, 'end': options.selectionEnd } );
			}
			
			var selText = $(this).textSelection( 'getSelection' );
			var scrollTop = this.scrollTop;
			var range = document.selection.createRange();
			
			checkSelectedText();
			var insertText = pre + selText  + post;
			if ( options.splitlines ) {
				insertText = doSplitLines( selText, pre, post );
			}
			if ( options.ownline && range.moveStart ) {
				var range2 = document.selection.createRange();
				range2.collapse();
				range2.moveStart( 'character', -1 );
				// FIXME: Which check is correct?
				if ( range2.text != "\r" && range2.text != "\n" && range2.text != "" ) {
					insertText = "\n" + insertText;
				}
				var range3 = document.selection.createRange();
				range3.collapse( false );
				range3.moveEnd( 'character', 1 );
				if ( range3.text != "\r" && range3.text != "\n" && range3.text != "" ) {
					insertText += "\n";
				}
			}
			
			range.text = insertText;
			if ( isSample && options.selectPeri && range.moveStart ) {
				range.moveStart( 'character', - post.length - selText.length );
				range.moveEnd( 'character', - post.length );
			}
			range.select();
			// Restore the scroll position
			this.scrollTop = scrollTop;
		}
		$(this).trigger( 'encapsulateSelection', [ options.pre, options.peri, options.post, options.ownline,
			options.replace, options.spitlines ] );
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
 *
 * Will focus the textarea in some browsers (IE/Opera)
 *
 * @fixme document the options parameters
 */
 getCaretPosition: function( options ) {
	function getCaret( e ) {
		var caretPos = 0, endPos = 0;
		if ( document.selection && document.selection.createRange ) {
			// IE doesn't properly report non-selected caret position through
			// the selection ranges when textarea isn't focused. This can
			// lead to saving a bogus empty selection, which then screws up
			// whatever we do later (bug 31847).
			activateElementOnIE( e );

			// IE Support
			var preFinished = false;
			var periFinished = false;
			var postFinished = false;
			var preText, rawPreText, periText;
			var rawPeriText, postText, rawPostText;
			// Create range containing text in the selection
			var periRange = document.selection.createRange().duplicate();
			// Create range containing text before the selection
			var preRange = rangeForElementIE( e );
			// Move the end where we need it
			preRange.setEndPoint("EndToStart", periRange);
			// Create range containing text after the selection
			var postRange = rangeForElementIE( e );
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
				if ( !preFinished ) {
					if ( preRange.compareEndPoints( "StartToEnd", preRange ) == 0 ) {
						preFinished = true;
					} else {
						preRange.moveEnd( "character", -1 );
						if ( preRange.text == preText ) {
							rawPreText += "\r\n";
						} else {
							preFinished = true;
						}
					}
				}
				if ( !periFinished ) {
					if ( periRange.compareEndPoints( "StartToEnd", periRange ) == 0 ) {
						periFinished = true;
					} else {
						periRange.moveEnd( "character", -1 );
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
						postRange.moveEnd( "character", -1 );
						if ( postRange.text == postText ) {
							rawPostText += "\r\n";
						} else {
							postFinished = true;
						}
					}
				}
			} while ( ( !preFinished || !periFinished || !postFinished ) );
			caretPos = rawPreText.replace( /\r\n/g, "\n" ).length;
			endPos = caretPos + rawPeriText.replace( /\r\n/g, "\n" ).length;
		} else if ( e.selectionStart || e.selectionStart == '0' ) {
			// Firefox support
			caretPos = e.selectionStart;
			endPos = e.selectionEnd;
		}
		return options.startAndEnd ? [ caretPos, endPos ] : caretPos;
	}
	return getCaret( this.get( 0 ) );
},
/**
 * @fixme document the options parameters
 */
setSelection: function( options ) {
	return this.each( function() {
		if ( $(this).is( ':hidden' ) ) {
			// Do nothing
		} else if ( this.selectionStart || this.selectionStart == '0' ) {
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
			var selection = rangeForElementIE( this );
			var length = this.value.length;
			// IE doesn't count \n when computing the offset, so we won't either
			var newLines = this.value.match( /\n/g );
			if ( newLines) length = length - newLines.length;
			selection.moveStart( 'character', options.start );
			selection.moveEnd( 'character', -length + options.end );
			
			// This line can cause an error under certain circumstances (textarea empty, no selection)
			// Silence that error
			try {
				selection.select();
			} catch( e ) { }
		}
	});
},
/**
 * Ported from Wikia's LinkSuggest extension
 * https://svn.wikia-code.com/wikia/trunk/extensions/wikia/LinkSuggest
 *
 * Scroll a textarea to the current cursor position. You can set the cursor
 * position with setSelection()
 * @param options boolean Whether to force a scroll even if the caret position
 *  is already visible. Defaults to false
 *
 * @fixme document the options parameters (function body suggests options.force is a boolean, not options itself)
 */
scrollToCaretPosition: function( options ) {
	function getLineLength( e ) {
		return Math.floor( e.scrollWidth / ( $.client.profile().platform == 'linux' ? 7 : 8 ) );
	}
	function getCaretScrollPosition( e ) {
		// FIXME: This functions sucks and is off by a few lines most
		// of the time. It should be replaced by something decent.
		var text = e.value.replace( /\r/g, "" );
		var caret = $( e ).textSelection( 'getCaretPosition' );
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
		return ( $.client.profile().platform == 'mac' ? 13 : ( $.client.profile().platform == 'linux' ? 15 : 16 ) ) * row;
	}
	return this.each(function() {
		if ( $(this).is( ':hidden' ) ) {
			// Do nothing
		} else if ( this.selectionStart || this.selectionStart == '0' ) {
			// Mozilla
			var scroll = getCaretScrollPosition( this );
			if ( options.force || scroll < $(this).scrollTop() ||
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
			var range = document.body.createTextRange();
			var savedRange = document.selection.createRange();
			var pos = $(this).textSelection( 'getCaretPosition' );
			var oldScrollTop = this.scrollTop;
			range.moveToElementText( this );
			range.collapse();
			range.move( 'character', pos + 1);
			range.select();
			if ( this.scrollTop != oldScrollTop )
				this.scrollTop += range.offsetTop;
			else if ( options.force ) {
				range.move( 'character', -1 );
				range.select();
			}
			savedRange.select();
		}
		$(this).trigger( 'scrollToPosition' );
	} );
}
};
	// Apply defaults
	switch ( command ) {
		//case 'getContents': // no params
		//case 'setContents': // no params with defaults
		//case 'getSelection': // no params
		case 'encapsulateSelection':
			options = $.extend( {
				'pre': '', // Text to insert before the cursor/selection
				'peri': '', // Text to insert between pre and post and select afterwards
				'post': '', // Text to insert after the cursor/selection
				'ownline': false, // Put the inserted text on a line of its own
				'replace': false, // If there is a selection, replace it with peri instead of leaving it alone
				'selectPeri': true, // Select the peri text if it was inserted (but not if there was a selection and replace==false, or if splitlines==true)
				'splitlines': false, // If multiple lines are selected, encapsulate each line individually
				'selectionStart': undefined, // Position to start selection at
				'selectionEnd': undefined // Position to end selection at. Defaults to start
			}, options );
			break;
		case 'getCaretPosition':
			options = $.extend( {
				'startAndEnd': false // Return [start, end] instead of just start
			}, options );
			// FIXME: We may not need character position-based functions if we insert markers in the right places
			break;
		case 'setSelection':
			options = $.extend( {
				'start': undefined, // Position to start selection at
				'end': undefined, // Position to end selection at. Defaults to start
				'startContainer': undefined, // Element to start selection in (iframe only)
				'endContainer': undefined // Element to end selection in (iframe only). Defaults to startContainer
			}, options );
			if ( options.end === undefined )
				options.end = options.start;
			if ( options.endContainer == undefined )
				options.endContainer = options.startContainer;
			// FIXME: We may not need character position-based functions if we insert markers in the right places
			break;
		case 'scrollToCaretPosition':
			options = $.extend( {
				'force': false // Force a scroll even if the caret position is already visible
			}, options );
			break;
	}
	var context = $(this).data( 'wikiEditor-context' );
	var hasIframe = typeof context !== 'undefined' && context && typeof context.$iframe !== 'undefined';
	
	// IE selection restore voodoo
	var needSave = false;
	if ( hasIframe && context.savedSelection !== null ) {
		context.fn.restoreSelection();
		needSave = true;
	}
	retval = ( hasIframe ? context.fn : fn )[command].call( this, options );
	if ( hasIframe && needSave ) {
		context.fn.saveSelection();
	}
	return retval;
};
} )( jQuery );
