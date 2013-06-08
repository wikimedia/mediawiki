/**
 * Show gallery captions when focused. Copied directly from jquery.mw-jump.js.
 * Also Dynamically resize images to justify them.
 */
jQuery( function ( $ ) {

	// Is there a better way to detect a touchscreen? Current check taken from stack overflow.
	var isTouchScreen = !!("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch );

	if ( isTouchScreen ) {
		// Always show the caption for a touch screen.
		$( 'ul.mw-gallery-height-constrained-overlay' )
			.addClass( 'mw-gallery-height-constrained-static' )
			.removeClass( 'mw-gallery-height-constrained-overlay' );
	} else {
		// Note use of just "a", not a.image, since we want this to trigger if a link in
		// the caption recieves focus
		$( 'ul.mw-gallery-height-constrained-overlay li.gallerybox' ).on( 'focus blur', 'a', function ( e ) {
			// Confusingly jQuery leaves e.type as focusout for delegated blur events
			if ( e.type === 'blur' || e.type === 'focusout' ) {
				$( this ).closest( 'li.gallerybox' ).removeClass( 'mw-gallery-focused' );
			} else {
				$( this ).closest( 'li.gallerybox' ).addClass( 'mw-gallery-focused' );
			}
		} );
	}

	// Now on to justification.
	// We may still get ragged edges if someone resizes their window. Could bind to
	// that event, otoh do we really want to constantly be resizing galleries?
	var galleries = 'ul.mw-gallery-height-constrained-static, ul.mw-gallery-height-constrained, ul.mw-gallery-height-constrained-overlay';
	$( galleries ).each( function() {
		var rows = [];
		var lastTop;
		 $( this ).children( 'li' ).each( function() {
			// floor to be paranoid if things are off by 0.00000000001
			var top = Math.floor( $(this ).position().top );
			if ( top !== lastTop ) {
				rows[rows.length] = [];
				lastTop = top;
			}
			var img = $( 'div.thumb img', this );
			var height;
			if ( img.length ) {
				height = img[0].height;
			} else {
				height = $( this ).children().children( 'div:first' ).height();
			}
			rows[rows.length-1][rows[rows.length-1].length] = {
				elm: this,
				width: $( this ).outerWidth(),
				imgWidth: img.length ? img[0].width : 0,
				aspect: img.length ? img[0].width / img[0].height : 0,
				captionWidth: $( this ).children().children( 'div.gallerytextwrapper' ).width(),
				height: height
			};
		});

		// Note, we do not try to fit the last row.
		for ( i = 0; i < rows.length - 1; i++ ) {
			var maxWidth = $( this ).width();
			var combinedAspect = 0;
			var combinedPadding = 0;
			var curRow = rows[i];

			for ( j = 0; j < curRow.length; j++ ) {
				combinedAspect += curRow[j].aspect;
				combinedPadding += curRow[j].width - curRow[j].imgWidth;
			}

			// Add some padding for interelement space.
			combinedPadding += 5* curRow.length;
			var wantedWidth = maxWidth-combinedPadding
			var preferredHeight = wantedWidth / combinedAspect;
			if ( preferredHeight > curRow[0].height*1.5 ) {
				// Only expand at most 1.5 times current size
				// As that's as high a resolution as we have.
				// Also on the off chance there is a bug in this
				// code, would prevent accidentally expanding to
				// be 10 billion pixels wide.
				mw.log( 'mw.page.gallery: Cannot fit row, aspect is ' + preferredHeight/curRow[0].height );
				preferredHeight = 1.5*curRow[0].height;
			}
			for ( j = 0; j < curRow.length; j++ ) {
				var newWidth = preferredHeight*curRow[j].aspect;

				var padding = curRow[j].width - curRow[j].imgWidth;
				var outerDiv = $( curRow[j].elm );
				var innerDiv = outerDiv.children( 'div' ).first()
				var imageDiv = innerDiv.children( 'div.thumb' );
				var imageElm = imageDiv.find( 'img' ).length ? imageDiv.find( 'img' )[0] : null;
				var caption = outerDiv.children().children( 'div.gallerytextwrapper' );

				outerDiv.width( newWidth + padding );
				innerDiv.width( newWidth + padding );
				imageDiv.width( newWidth );
				caption.width( curRow[j].captionWidth + (newWidth - curRow[j].imgWidth ) );

				if ( imageElm ) {
					// We don't always have an img, e.g. in the case of an invalid file.
					imageElm.width = newWidth;
					imageElm.height = preferredHeight;
				} else {
					// Not a file box.
					innerDiv.height( preferredHeight );
				}
			}

		}
	});
} );
