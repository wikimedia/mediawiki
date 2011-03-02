window.currentFocused = undefined;

// this function adds a toolbar button to the mwEditButtons list
window.addButton = function( imageFile, speedTip, tagOpen, tagClose, sampleText, imageId, selectText ) {
	// Don't generate buttons for browsers which don't fully
	// support it.
	mwEditButtons.push({
		'imageId': imageId,
		'imageFile': imageFile,
		'speedTip': speedTip,
		'tagOpen': tagOpen,
		'tagClose': tagClose,
		'sampleText': sampleText,
		'selectText': selectText
	});
};

// this function adds one toolbar button from a mwEditButtons/mwCustomEditButtons item
window.mwInsertEditButton = function( parent, item ) {
	var image = document.createElement( 'img' );
	image.width = 23;
	image.height = 22;
	image.className = 'mw-toolbar-editbutton';
	if ( item.imageId ) {
		image.id = item.imageId;
	}
	image.src = item.imageFile;
	image.border = 0;
	image.alt = item.speedTip;
	image.title = item.speedTip;
	image.style.cursor = 'pointer';
	image.onclick = function() {
		insertTags( item.tagOpen, item.tagClose, item.sampleText, item.selectText );
		// click tracking
		if ( ( typeof $ != 'undefined' )  && ( typeof $.trackAction != 'undefined' ) ) {
			$.trackAction( 'oldedit.' + item.speedTip.replace(/ /g, '-') );
		}
		return false;
	};

	parent.appendChild( image );
	return true;
};

// this function generates the actual toolbar buttons with localized text
// we use it to avoid creating the toolbar where javascript is not enabled
window.mwSetupToolbar = function() {
	var toolbar = document.getElementById( 'toolbar' );
	var i = 0;
	if ( !toolbar ) {
		return false;
	}

	// Don't generate buttons for browsers which don't fully
	// support it.
	// but don't assume wpTextbox1 is always here
	var textboxes = document.getElementsByTagName( 'textarea' );
	if ( !textboxes.length ) {
		// No toolbar if we can't find any textarea
		return false;
	}
	// Only check for selection capability if the textarea is visible - errors will occur otherwise - just because
	// the textarea is not visible, doesn't mean we shouldn't build out the toolbar though - it might have been replaced
	// with some other kind of control
	if ( textboxes[0].style.display != 'none' ) {
		if ( !( document.selection && document.selection.createRange )
			&& textboxes[0].selectionStart === null ) {
			return false;
		}
	}
	for ( i = 0; i < mwEditButtons.length; i++ ) {
		mwInsertEditButton( toolbar, mwEditButtons[i] );
	}
	for ( i = 0; i < mwCustomEditButtons.length; i++ ) {
		mwInsertEditButton( toolbar, mwCustomEditButtons[i] );
	}
	return true;
};

// apply tagOpen/tagClose to selection in textarea,
// use sampleText instead of selection if there is none
window.insertTags = function( tagOpen, tagClose, sampleText, selectText) {
	if ( typeof $ != 'undefined' && typeof $.fn.textSelection != 'undefined' && currentFocused &&
			( currentFocused.nodeName.toLowerCase() == 'iframe' || currentFocused.id == 'wpTextbox1' ) ) {
		$( '#wpTextbox1' ).textSelection(
			'encapsulateSelection', { 'pre': tagOpen, 'peri': sampleText, 'post': tagClose }
		);
		return;
	}
	var txtarea;
	if ( document.editform ) {
		txtarea = currentFocused;
	} else {
		// some alternate form? take the first one we can find
		var areas = document.getElementsByTagName( 'textarea' );
		txtarea = areas[0];
	}
	var selText, isSample = false;

	function checkSelectedText() {
		if ( !selText ) {
			selText = sampleText;
			isSample = true;
		} else if ( selText.charAt(selText.length - 1) == ' ' ) { // exclude ending space char
			selText = selText.substring(0, selText.length - 1);
			tagClose += ' ';
		}
	}

	if ( document.selection  && document.selection.createRange ) { // IE/Opera
		// save window scroll position
		var winScroll = null;
		if ( document.documentElement && document.documentElement.scrollTop ) {
			winScroll = document.documentElement.scrollTop;
		} else if ( document.body ) {
			winScroll = document.body.scrollTop;
		}
		// get current selection
		txtarea.focus();
		var range = document.selection.createRange();
		selText = range.text;
		// insert tags
		checkSelectedText();
		range.text = tagOpen + selText + tagClose;
		// mark sample text as selected if not switched off by option
		if ( selectText !== false ) {
			if ( isSample && range.moveStart ) {
				if ( window.opera ) {
					tagClose = tagClose.replace(/\n/g,'');
				}
				range.moveStart('character', - tagClose.length - selText.length);
				range.moveEnd('character', - tagClose.length);
			}
			range.select();
		}
		// restore window scroll position
		if ( document.documentElement && document.documentElement.scrollTop ) {
			document.documentElement.scrollTop = winScroll;
		} else if ( document.body ) {
			document.body.scrollTop = winScroll;
		}

	} else if ( txtarea.selectionStart || txtarea.selectionStart == '0' ) { // Mozilla
		// save textarea scroll position
		var textScroll = txtarea.scrollTop;
		// get current selection
		txtarea.focus();
		var startPos = txtarea.selectionStart;
		var endPos = txtarea.selectionEnd;
		selText = txtarea.value.substring( startPos, endPos );
		// insert tags
		checkSelectedText();
		txtarea.value = txtarea.value.substring(0, startPos)
			+ tagOpen + selText + tagClose
			+ txtarea.value.substring(endPos, txtarea.value.length);
		// set new selection
		if ( isSample && ( selectText !== false )) {
			txtarea.selectionStart = startPos + tagOpen.length;
			txtarea.selectionEnd = startPos + tagOpen.length + selText.length;
		} else {
			txtarea.selectionStart = startPos + tagOpen.length + selText.length + tagClose.length;
			txtarea.selectionEnd = txtarea.selectionStart;
		}
		// restore textarea scroll position
		txtarea.scrollTop = textScroll;
	}

};

/**
 * Restore the edit box scroll state following a preview operation,
 * and set up a form submission handler to remember this state
 */
window.scrollEditBox = function() {
	var editBox = document.getElementById( 'wpTextbox1' );
	var scrollTop = document.getElementById( 'wpScrolltop' );
	var editForm = document.getElementById( 'editform' );
	if( editForm && editBox && scrollTop ) {
		if( scrollTop.value ) {
			editBox.scrollTop = scrollTop.value;
		}
		addHandler( editForm, 'submit', function() {
			scrollTop.value = editBox.scrollTop;
		} );
	}
};
hookEvent( 'load', scrollEditBox );
hookEvent( 'load', mwSetupToolbar );
hookEvent( 'load', function() {
	currentFocused = document.getElementById( 'wpTextbox1' );
	// http://www.quirksmode.org/blog/archives/2008/04/delegating_the.html
	// focus does not bubble normally, but using a trick we can do event delegation
	// on the focus event on all text inputs to make the toolbox usable on all of them
	var editForm = document.getElementById( 'editform' );
	if ( !editForm ) {
		return;
	}
	function onfocus( e ) {
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

		currentFocused = elm;
	}

	if ( editForm.addEventListener ) {
		// Gecko, WebKit, Opera, etc... (all standards compliant browsers)
		editForm.addEventListener( 'focus', onfocus, true ); // This MUST be true to work
	} else if ( editForm.attachEvent ) {
		// IE needs a specific trick here since it doesn't support the standard
		editForm.attachEvent( 'onfocusin', function() { onfocus( event ); } );
	}
	
	// HACK: make currentFocused work with the usability iframe
	// With proper focus detection support (HTML 5!) this'll be much cleaner
	if ( typeof $ != 'undefined' ) {
		var iframe = $( '.wikiEditor-ui-text iframe' );
		if ( iframe.length > 0 ) {
			$( iframe.get( 0 ).contentWindow.document )
				.add( iframe.get( 0 ).contentWindow.document.body ) // for IE
				.focus( function() { currentFocused = iframe.get( 0 ); } );
		}
	}

} );