(function( $ ) {
	// currentFocus is used to determine where to insert tags
	var currentFocused = $( '#wpTextbox1' );
	
	mw.toolbar = {
		$toolbar : $( '#toolbar' ),
		buttons : [],
		// If you want to add buttons, use 
		// mw.toolbar.addButton( imageFile, speedTip, tagOpen, tagClose, sampleText, imageId, selectText );
		addButton : function() {
			this.buttons.push( [].slice.call( arguments ) );
		},
		insertButton : function( imageFile, speedTip, tagOpen, tagClose, sampleText, imageId, selectText ) {
			var image = $('<img>', {
				width  : 23,
				height : 22,
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
			}
		}, 
		init : function() {
			// Legacy
			// Merge buttons from mwCustomEditButtons
			var buttons = [].concat( this.buttons, window.mwCustomEditButtons );
			for ( var i = 0; i < buttons.length; i++ ) {
				if ( $.isArray( buttons[i] ) ) {
					// Passes our button array as arguments
					mw.toolbar.insertButton.apply( this, buttons[i] );
				} else {
					// Legacy mwCustomEditButtons is an object
					var c = buttons[i];
					mw.toolbar.insertButton( c.imageFile, c.speedTip, c.tagOpen, c.tagClose, c.sampleText, c.imageId, c.selectText );
				}
			}
			return true;
		}
	};

	//Legacy
	window.addButton =  mw.toolbar.addButton;
	window.insertTags = mw.toolbar.insertTags;

	//make sure edit summary does not exceed byte limit
	$( '#wpSummary' ).byteLimit( 250 );
	
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
		
		// Create button bar
		mw.toolbar.init();
		
		$( 'textarea, input:text' ).focus( function() {
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
