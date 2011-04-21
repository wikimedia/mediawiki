(function( $ ) {
	// currentFocus is used to determine where to insert tags
	var currentFocused = $( '#wpTextbox1' );
	
	mw.toolbar = {
		$toolbar : $( '#toolbar' ),
		addButton : function( imageFile, speedTip, tagOpen, tagClose, sampleText, imageId, selectText ) {
			var image = $('<img>', {
				width  : 23,
				height : 23,
				src    : imageFile,
				alt    : speedTip,
				title  : speedTip,
				id     : imageId || '',
				'class': 'mw-toolbar-editbutton'
			} ).click( function() {
				mw.toolbar.insertTags( tagOpen, tagClose, sampleText, selectText );
				return false;
			} );

			this.$toolbar.append( image );
			return true;
		},

		// apply tagOpen/tagClose to selection in textarea,
		// use sampleText instead of selection if there is none
		insertTags : function( tagOpen, tagClose, sampleText, selectText) {
			if ( currentFocused.length ) {
				currentFocused.textSelection(
					'encapsulateSelection', { 'pre': tagOpen, 'peri': sampleText, 'post': tagClose }
				);
				return;
			}
		}, 
		init : function() {
			// Legacy
			// Print out buttons from mwCustomEditButtons
			// If you want to add buttons, use 
			// $( document ).ready( function () { mw.toolbar.addButton( imageFile, speedTip, tagOpen, tagClose, sampleText, imageId, selectText ) } );
			var c;
			for ( var i = 0; i < window.mwCustomEditButtons.length; i++ ) {
				c = window.mwCustomEditButtons[i];
				mw.toolbar.addButton( c.imageFile, c.speedTip, c.tagOpen, c.tagClose, c.sampleText, c.imageId, c.selectText );
			}
			return true;
		}
	};

	//Legacy
	window.addButton =  mw.toolbar.addButton;
	window.insertTags = mw.toolbar.insertTags;

	//make sure edit summary does not exceed byte limit
	$( '#wpSummary' ).attr( 'maxLength', 250 ).keypress( function( e ) {
		// first check to see if this is actually a character key
		// being pressed.
		// Based on key-event info from http://unixpapa.com/js/key.html
		// jQuery should also normalize e.which to be consistent cross-browser,
		// however the same check is still needed regardless of jQuery.

		// Note: At the moment, for some older opera versions (~< 10.5)
		// some special keys won't be recognized (aka left arrow key).
		// Backspace will be, so not big issue.

		if ( e.which === 0 || e.charCode === 0 || e.which === 8 ||
			e.ctrlKey || e.altKey || e.metaKey )
		{
			return true; //a special key (backspace, etc) so don't interfere.
		}

		// This basically figures out how many bytes a UTF-16 string (which is what js sees)
		// will take in UTF-8 by replacing a 2 byte character with 2 *'s, etc, and counting that.
		// Note, surrogate (\uD800-\uDFFF) characters are counted as 2 bytes, since there's two of them
		// and the actual character takes 4 bytes in UTF-8 (2*2=4). Might not work perfectly in edge cases
		// such as illegal sequences, but that should never happen.

		var len = this.value.replace( /[\u0080-\u07FF\uD800-\uDFFF]/g, '**' ).replace( /[\u0800-\uD7FF\uE000-\uFFFF]/g, '***' ).length;
		//247 as this doesn't count character about to be inserted.
		if ( len > 247 ) {
			e.preventDefault();
		}
	});
	
	
	$( document ).ready( function() {
		/**
		 * Restore the edit box scroll state following a preview operation,
		 * and set up a form submission handler to remember this state
		 */
		var scrollEditBox = function() {
			var editBox = document.getElementById( 'wpTextbox1' );
			var scrollTop = document.getElementById( 'wpScrolltop' );
			var $editForm = $( '#editform' );
			if( $editForm.length && editBox && scrollTop ) {
				if( scrollTop.value ) {
					editBox.scrollTop = scrollTop.value;
				}
				$editForm.submit( function() {
					scrollTop.value = editBox.scrollTop;
				});
			}
		};
		scrollEditBox();

		$( '#wpSummary, #wpTextbox1' ).focus( function() {
			currentFocused = $(this);
		});

		// HACK: make currentFocused work with the usability iframe
		// With proper focus detection support (HTML 5!) this'll be much cleaner
		var iframe = $( '.wikiEditor-ui-text iframe' );
		if ( iframe.length > 0 ) {
			$( iframe.get( 0 ).contentWindow.document )
				.add( iframe.get( 0 ).contentWindow.document.body ) // for IE
				.focus( function() { currentFocused = iframe; } );
		}
	});
})(jQuery);
