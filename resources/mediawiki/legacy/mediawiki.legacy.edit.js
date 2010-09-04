/*
 * Legacy emulation for the now depricated skins/common/edit.js
 */

( function( $, mw ) {

/* Extension */

$.extend( true, mw.legacy, {
	
	/* Global Variables */
	
	'currentFocused': null,
	
	/* Functions */
	
	/**
	 * Generates the actual toolbar buttons with localized text we use it to avoid creating the toolbar
	 * where javascript is not enabled
	 */
	'addButton': function( imageFile, speedTip, tagOpen, tagClose, sampleText, imageId ) {
		// Don't generate buttons for browsers which don't fully support it.
		mw.legacy.mwEditButtons.push( {
			'imageId': imageId,
			'imageFile': imageFile,
			'speedTip': speedTip,
			'tagOpen': tagOpen,
			'tagClose': tagClose,
			'sampleText': sampleText
		} );
	},
	/**
	 * Generates the actual toolbar buttons with localized text we use it to avoid creating the toolbar where JavaScript
	 * is not enabled
	 */
	'mwInsertEditButton': function( parent, item ) {
		var $image = $( '<img />' )
			.attr( {
				'width': 23,
				'height': 22,
				'class': 'mw-toolbar-editbutton',
				'id': item.imageId ? item.imageId : null,
				'src': = item.imageFile,
				'border': 0,
				'alt': item.speedTip,
				'title': item.speedTip
			} )
			.css( 'cursor', 'pointer' )
			.click( function() {
				mw.legacy.insertTags( item.tagOpen, item.tagClose, item.sampleText );
				// Click tracking
				if ( typeof $.trackAction != 'undefined' ) {
					$.trackAction( 'oldedit.' + item.speedTip.replace( / /g, '-' ) );
				}
				return false;
			} )
			.appendTo( $( parent ) );
		return true;
	},
	/**
	 * Sets up the toolbar
	 */
	'mwSetupToolbar': function() {
		var $toolbar = $( '#toolbar' );
		var $textbox = $( 'textarea' ).get( 0 );
		if ( !$toolbar.length || !$textbox.length ) {
			return false;
		}
		// Only check for selection capability if the textarea is visible - errors will occur otherwise - just because
		// the textarea is not visible, doesn't mean we shouldn't build out the toolbar though - it might have been
		// replaced with some other kind of control
		if (
			$textbox.is( ':visible' ) &&
			!( document.selection && document.selection.createRange ) &&
			textboxes[0].selectionStart === null
		) {
			return false;
		}
		for ( var i = 0; i < mw.legacy.mwEditButtons.length; i++ ) {
			mw.legacy.mwInsertEditButton( $toolbar, mw.legacy.mwEditButtons[i] );
		}
		for ( var i = 0; i < mw.legacy.mwCustomEditButtons.length; i++ ) {
			mw.legacy.mwInsertEditButton( $toolbar, mw.legacy.mwCustomEditButtons[i] );
		}
		return true;
	},
	/**
	 * Apply tagOpen/tagClose to selection in textarea, use sampleText instead of selection if there is none
	 */
	'insertTags': function( tagOpen, tagClose, sampleText ) {
		function checkSelectedText() {
			if ( !selText ) {
				selText = sampleText;
				isSample = true;
			} else if ( selText.charAt( selText.length - 1 ) == ' ' ) { // exclude ending space char
				selText = selText.substring( 0, selText.length - 1 );
				tagClose += ' ';
			}
		}
		var currentFocused = $( mw.legacy.currentFocused );
		if (
			typeof $.fn.textSelection != 'undefined' &&
			( $currentFocused.name().toLowerCase() == 'iframe' || $currentFocused.attr( 'id' ) == 'wpTextbox1' )
		) {
			$j( '#wpTextbox1' ).textSelection(
				'encapsulateSelection', { 'pre': tagOpen, 'peri': sampleText, 'post': tagClose }
			);
			return;
		}
		var $textarea;
		if ( $( 'form[name=editform]' ) {
			$textarea = $currentFocused;
		} else {
			// Some alternate form? take the first one we can find
			$textarea = $( 'textarea' ).get( 0 );
		}
		var selText, isSample = false;
		// Text selection implementation for IE and Opera
		if ( document.selection  && document.selection.createRange ) {
			// Save window scroll position
			if ( document.documentElement && document.documentElement.scrollTop ) {
				var winScroll = document.documentElement.scrollTop
			} else if ( document.body ) {
				var winScroll = document.body.scrollTop;
			}
			// Get current selection
			$textarea.focus();
			var range = document.selection.createRange();
			selText = range.text;
			// Insert tags
			checkSelectedText();
			range.text = tagOpen + selText + tagClose;
			// Mark sample text as selected
			if ( isSample && range.moveStart ) {
				if ( window.opera ) {
					tagClose = tagClose.replace( /\n/g,'' );
				}
				range.moveStart( 'character', - tagClose.length - selText.length );
				range.moveEnd( 'character', - tagClose.length );
			}
			range.select();
			// Restore window scroll position
			if ( document.documentElement && document.documentElement.scrollTop ) {
				document.documentElement.scrollTop = winScroll;
			} else if ( document.body ) {
				document.body.scrollTop = winScroll;
			}
		}
		// Text selection implementation for Mozilla, Chrome and Safari
		else if ( $textarea[0].selectionStart || $textarea[0].selectionStart == '0' ) {
			// Save textarea scroll position
			var textScroll = $textarea.scrollTop;
			// Get current selection
			$textarea.focus();
			var startPos = $textarea[0].selectionStart;
			var endPos = $textarea[0].selectionEnd;
			selText = $textarea.value.substring( startPos, endPos );
			// Insert tags
			checkSelectedText();
			$textarea.val(
				$textarea.val().substring( 0, startPos ) +
				tagOpen + selText + tagClose +
				$textarea.val().substring( endPos, $textarea.val().length )
			);
			// Set new selection
			if ( isSample ) {
				$textarea[0].selectionStart = startPos + tagOpen.length;
				$textarea[0].selectionEnd = startPos + tagOpen.length + selText.length;
			} else {
				$textarea[0].selectionStart = startPos + tagOpen.length + selText.length + tagClose.length;
				$textarea[0].selectionEnd = $textarea[0].selectionStart;
			}
			// Restore textarea scroll position
			$textarea[0].scrollTop = textScroll;
		}
	},
	/**
	 * Restore the edit box scroll state following a preview operation,
	 * and set up a form submission handler to remember this state
	 */
	'scrollEditBox': function() {
		var $textbox = $( '#wpTextbox1' );
		var $scrollTop = $( '#wpScrolltop' );
		var $editForm = $( '#editform' );
		if ( $editForm.length && $textbox.length && $scrollTop.length ) {
			if ( scrollTop.val() ) {
				$textbox.scrollTop = $scrollTop.val();
			}
			$editForm.submit( function() {
				$scrollTop.val( $textbox.scrollTop );
			} );
		}
	}
} );

/* Initialization */

$( document ).ready( function() {
	mw.legacy.scrollEditBox();
	mw.legacy.mwSetupToolbar();
	mw.legacy.currentFocused = $( '#wpTextbox1' ).get( 0 );
	// http://www.quirksmode.org/blog/archives/2008/04/delegating_the.html focus does not bubble normally, but using a
	// trick we can do event delegation on the focus event on all text inputs to make the toolbox usable on all of them
	$( '#editform' ).focus( function() {
		$(this).each( function( e ) {
			var elm = e.target || e.srcElement;
			if ( !elm ) {
				return;
			}
			var tagName = elm.tagName.toLowerCase();
			var type = elm.type || '';
			if ( tagName !== 'textarea' && tagName !== 'input' ) {
				return;
			}
			if ( tagName === 'input' && type.toLowerCase() !== 'text' ) {
				return;
			}
			mw.legacy.currentFocused = elm;
		} );
	} );
	// HACK: make currentFocused work with the usability iframe - with proper focus detection support (HTML 5!) this'll
	// be much cleaner
	var $iframe = $j( '.wikiEditor-ui-text iframe' );
	if ( $iframe.length > 0 ) {
		$j( $iframe.get( 0 ).contentWindow.document )
			// For IE
			.add( $iframe.get( 0 ).contentWindow.document.body )
			.focus( function() { mw.legacy.currentFocused = $iframe.get( 0 ); } );
	}
	// Make sure edit summary does not exceed byte limit
	var $summary = $( '#wpSummary' );
	if ( !$summary.length ) {
		return;
	}
	// L must be capitalized in length
	$summary.get( 0 ).maxLength = 250;
	$summary.keypress( function( e ) {
		// First check to see if this is actually a character key being pressed. Based on key-event info from
		// http://unixpapa.com/js/key.html note === sign, if undefined, still could be a real key
		if ( e.which === 0 || e.charCode === 0 || e.ctrlKey || e.altKey || e.metaKey ) {
			// A special key (backspace, etc) so don't interefere.
			return true;
		}
		// This basically figures out how many bytes a utf-16 string (which is what js sees) will take in utf-8 by
		// replacing a 2 byte character with 2 *'s, etc, and counting that. Note, surogate (\uD800-\uDFFF) characters
		// are counted as 2 bytes, since theres two of them and the actual character takes 4 bytes in utf-8 (2*2=4).
		// Might not work perfectly in edge cases such as such as illegal sequences, but that should never happen.
		len = summary.value
			.replace(/[\u0080-\u07FF\uD800-\uDFFF]/g, '**')
			.replace(/[\u0800-\uD7FF\uE000-\uFFFF]/g, '***')
			.length;
		// 247 as this doesn't count character about to be inserted.
		if ( len > 247 ) {
			if ( e.preventDefault ) {
				e.preventDefault();
			}
			// IE
			e.returnValue = false;
			return false;
		}
	} );
} );

} )( jQuery, mediaWiki );