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
		$( 'ul.mw-gallery-packed-hover' )
			.addClass( 'mw-gallery-packed-overlay' )
			.removeClass( 'mw-gallery-packed-hover' );
	} else {
		// Note use of just "a", not a.image, since we want this to trigger if a link in
		// the caption receives focus
		$( 'ul.mw-gallery-packed-hover li.gallerybox' ).on( 'focus blur', 'a', function ( e ) {
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
	galleries = 'ul.mw-gallery-packed-overlay, ul.mw-gallery-packed-hover, ul.mw-gallery-packed';
	$( galleries ).each( function() {
		var rows = [],
		lastTop,
		top,
		$img,
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
			$img = $( 'div.thumb a.image img', this );
			if ( $img.length && $img[0].height ) {
				imgHeight = $img[0].height;
				imgWidth = $img[0].width;
			} else {
				// If we don't have a real image, get the containing divs width/height.
				// Note that if we do have a real image, using this method will generally
				// give the same answer, but can be different in the case of a very
				// narrow image where extra padding is added.
				imgHeight = $( this ).children().children( 'div:first' ).height();
				imgWidth = $( this ).children().children( 'div:first' ).width();
			}

			// Hack to make an edge case work ok
			if ( imgHeight < 30 ) {
				// Don't try and resize this item.
				imgHeight = 0;
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
			$caption,
			hookInfo,
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

				// Add some padding for inter-element spacing.
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
				if ( isNaN( preferredHeight ) ) {
					// This *definitely* should not happen.
					mw.log( 'mw.page.gallery: Trying to resize row ' + i + ' to NaN?!' );
					// Skip this row.
					continue;
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
					imageElm = $imageDiv.find( 'img' ).length ? $imageDiv.find( 'img' )[0] : null;
					$caption = $outerDiv.children().children( 'div.gallerytextwrapper' );

					if ( newWidth < 60 ) {
						// Making something skinnier than this will mess up captions,
						mw.log( 'mw.page.gallery: Tried to make image ' + newWidth + 'px wide but too narrow.' );
						if ( newWidth < 1 ) {
							$innerDiv.height( preferredHeight );
							// Don't even try and touch the image size if it could mean
							// making it disappear.
							continue;
						}
					} else {
						$outerDiv.width( newWidth + padding );
						$innerDiv.width( newWidth + padding );
						$imageDiv.width( newWidth );
						$caption.width( curRow[j].captionWidth + (newWidth - curRow[j].imgWidth ) );
					}

					hookInfo = {
						'fullWidth': newWidth + padding,
						'imgWidth': newWidth,
						'imgHeight': preferredHeight,
						'$innerDiv': $innerDiv,
						'$imageDiv': $imageDiv,
						'$outerDiv': $outerDiv,
						'resolved': false  /* Did the hook take action */
					};
					// Allow other media handlers to hook in.
					// If you're hook resizes an image, it is expected it will
					// set resolved to true. Additionally you should load
					// your module in position top to ensure it is registered
					// before this runs (FIXME: there must be a better way?)
					// See TimedMediaHandler for an example.
					mw.hook( 'mediawiki.page.gallery.resize' ).fire( hookInfo );

					if ( !hookInfo.resolved ) {
						if ( imageElm ) {
							// We don't always have an img, e.g. in the case of an invalid file.
							imageElm.width = newWidth;
							imageElm.height = preferredHeight;
						} else {
							// Not a file box.
							$imageDiv.height( preferredHeight );
						}
					}
				}
			}
		} )();
	});
} );
