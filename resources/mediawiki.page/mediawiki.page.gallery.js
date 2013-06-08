/**
 * Show gallery captions when focused. Copied directly from jquery.mw-jump.js.
 * Also Dynamically resize images to justify them.
 */
jQuery( function ( $ ) {
	var isTouchScreen,
	galleries;

	// Is there a better way to detect a touchscreen? Current check taken from stack overflow.
	isTouchScreen = !!("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch );

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
	galleries = 'ul.mw-gallery-height-constrained-static, ul.mw-gallery-height-constrained, ul.mw-gallery-height-constrained-overlay';
	$( galleries ).each( function() {
		var rows = [],
		lastTop,
		top,
		$img,
		$video,
		imgWidth,
		imgHeight,
		gallery = this;

		$( gallery ).children( 'li' ).each( function() {
			// floor to be paranoid if things are off by 0.00000000001
			top = Math.floor( $(this ).position().top );
			if ( top !== lastTop ) {
				rows[rows.length] = [];
				lastTop = top;
			}
			$img = $( 'div.thumb img', this );
			$video = $( 'div.thumb video', this );
			if ( $img.length ) {
				imgHeight = $img[0].height;
				imgWidth = $img[0].width;
			} else if ( $video.length ) { // FIXME should be in TMH
				// Audio is treated in the not an image case.
				imgHeight = $video.height();
				imgWidth = $video.width();
			} else {
				imgHeight = $( this ).children().children( 'div:first' ).height();
				imgWidth = 0;
			}
			rows[rows.length-1][rows[rows.length-1].length] = {
				elm: this,
				width: $( this ).outerWidth(),
				imgWidth: imgWidth,
				aspect: imgWidth / imgHeight, // XXX: can divide by 0 ever happen?
				captionWidth: $( this ).children().children( 'div.gallerytextwrapper' ).width(),
				height: imgHeight,
			};
		});

		(function () {
			var maxWidth,
			combinedAspect,
			combinedPadding,
			curRow,
			wantedWidth,
			preferredHeight,
			newWidth,
			padding,
			$outerDiv,
			$innerDiv,
			$imageDiv,
			imageElm,
			$tmhElms,
			$tmhVideo,
			$caption,
			i,
			j;

			for ( i = 0; i < rows.length ; i++ ) {
				maxWidth = $( gallery ).width();
				combinedAspect = 0;
				combinedPadding = 0;
				curRow = rows[i];

				for ( j = 0; j < curRow.length; j++ ) {
					combinedAspect += curRow[j].aspect;
					combinedPadding += curRow[j].width - curRow[j].imgWidth;
				}

				// Add some padding for interelement space.
				combinedPadding += 5* curRow.length;
				wantedWidth = maxWidth - combinedPadding;
				preferredHeight = wantedWidth / combinedAspect;
				if ( preferredHeight > curRow[0].height*1.5 ) {
					// Only expand at most 1.5 times current size
					// As that's as high a resolution as we have.
					// Also on the off chance there is a bug in this
					// code, would prevent accidentally expanding to
					// be 10 billion pixels wide.
					mw.log( 'mw.page.gallery: Cannot fit row, aspect is ' + preferredHeight/curRow[0].height );
					preferredHeight = 1.5*curRow[0].height;
				}
				if ( preferredHeight < 5 ) {
					// Well something clearly went wrong...
					mw.log( 'mw.page.gallery: [BUG!] Fitting row ' + i + ' to too small a size: ' + preferredHeight );
					// Skip this row.
					continue;
				}
				for ( j = 0; j < curRow.length; j++ ) {
					newWidth = preferredHeight*curRow[j].aspect;
					padding = curRow[j].width - curRow[j].imgWidth;
					$outerDiv = $( curRow[j].elm );
					$innerDiv = $outerDiv.children( 'div' ).first();
					$imageDiv = $innerDiv.children( 'div.thumb' );
					// FIXME: Should be in TMH extension
					$tmhElms = $imageDiv.find( 'div.mediaContainer, video' );
					$tmhVideo = $imageDiv.find( 'video' );
					imageElm = $imageDiv.find( 'img' ).length ? $imageDiv.find( 'img' )[0] : null;
					$caption = $outerDiv.children().children( 'div.gallerytextwrapper' );

					$outerDiv.width( newWidth + padding );
					$innerDiv.width( newWidth + padding );
					$imageDiv.width( newWidth );
					$tmhElms.width( newWidth );
					$tmhVideo.height( preferredHeight );
					$caption.width( curRow[j].captionWidth + (newWidth - curRow[j].imgWidth ) );

					if ( imageElm ) {
						// We don't always have an img, e.g. in the case of an invalid file.
						imageElm.width = newWidth;
						imageElm.height = preferredHeight;
					} else if ( $tmhVideo ) {
						// FIXME: TMH specific
						// Add some padding, so caption doesn't overlap video controls.
						$outerDiv.find( 'div.gallerytext' ).css( 'padding-bottom', '20px' );
					} else {
						// Not a file box.
						$innerDiv.height( preferredHeight );
					}
				}
			}
		} )();
	});
} );
