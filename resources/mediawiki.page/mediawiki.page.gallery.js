/**
 * Show gallery captions when focused. Copied directly from jquery.mw-jump.js.
 * Also Dynamically resize images to justify them.
 */
( function ( $, mw ) {
	$( function () {
		var isTouchScreen,
			gettingFocus,
			galleries = 'ul.mw-gallery-packed-overlay, ul.mw-gallery-packed-hover, ul.mw-gallery-packed';

		// Is there a better way to detect a touchscreen? Current check taken from stack overflow.
		isTouchScreen = !!( window.ontouchstart !== undefined || window.DocumentTouch !== undefined && document instanceof window.DocumentTouch );

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
				gettingFocus = e.type !== 'blur' && e.type !== 'focusout';
				$( this ).closest( 'li.gallerybox' ).toggleClass( 'mw-gallery-focused', gettingFocus );
			} );
		}

		// Now on to justification.
		// We may still get ragged edges if someone resizes their window. Could bind to
		// that event, otoh do we really want to constantly be resizing galleries?
		$( galleries ).each( function () {
			var lastTop,
				$img,
				imgWidth,
				imgHeight,
				rows = [],
				$gallery = $( this );

			$gallery.children( 'li' ).each( function () {
				// Math.floor to be paranoid if things are off by 0.00000000001
				var top = Math.floor( $( this ).position().top ),
					$this = $( this );

				if ( top !== lastTop ) {
					rows[rows.length] = [];
					lastTop = top;
				}

				$img = $this.find( 'div.thumb a.image img' );
				if ( $img.length && $img[0].height ) {
					imgHeight = $img[0].height;
					imgWidth = $img[0].width;
				} else {
					// If we don't have a real image, get the containing divs width/height.
					// Note that if we do have a real image, using this method will generally
					// give the same answer, but can be different in the case of a very
					// narrow image where extra padding is added.
					imgHeight = $this.children().children( 'div:first' ).height();
					imgWidth = $this.children().children( 'div:first' ).width();
				}

				// Hack to make an edge case work ok
				if ( imgHeight < 30 ) {
					// Don't try and resize this item.
					imgHeight = 0;
				}

				rows[rows.length - 1][rows[rows.length - 1].length] = {
					$elm: $this,
					width: $this.outerWidth(),
					imgWidth: imgWidth,
					// XXX: can divide by 0 ever happen?
					aspect: imgWidth / imgHeight,
					captionWidth: $this.children().children( 'div.gallerytextwrapper' ).width(),
					height: imgHeight
				};
			} );

			( function () {
				var maxWidth,
					combinedAspect,
					combinedPadding,
					curRow,
					curRowHeight,
					wantedWidth,
					preferredHeight,
					newWidth,
					padding,
					$outerDiv,
					$innerDiv,
					$imageDiv,
					$imageElm,
					imageElm,
					$caption,
					hookInfo,
					i,
					j,
					avgZoom,
					totalZoom = 0;

				for ( i = 0; i < rows.length; i++ ) {
					maxWidth = $gallery.width();
					combinedAspect = 0;
					combinedPadding = 0;
					curRow = rows[i];
					curRowHeight = 0;

					for ( j = 0; j < curRow.length; j++ ) {
						if ( curRowHeight === 0 ) {
							if ( isFinite( curRow[j].height ) ) {
								// Get the height of this row, by taking the first
								// non-out of bounds height
								curRowHeight = curRow[j].height;
							}
						}

						if ( curRow[j].aspect === 0 || !isFinite( curRow[j].aspect ) ) {
							mw.log( 'Skipping item ' + j + ' due to aspect: ' + curRow[j].aspect );
							// One of the dimensions are 0. Probably should
							// not try to resize.
							combinedPadding += curRow[j].width;
						} else {
							combinedAspect += curRow[j].aspect;
							combinedPadding += curRow[j].width - curRow[j].imgWidth;
						}
					}

					// Add some padding for inter-element spacing.
					combinedPadding += 5 * curRow.length;
					wantedWidth = maxWidth - combinedPadding;
					preferredHeight = wantedWidth / combinedAspect;

					if ( preferredHeight > curRowHeight * 1.5 ) {
						// Only expand at most 1.5 times current size
						// As that's as high a resolution as we have.
						// Also on the off chance there is a bug in this
						// code, would prevent accidentally expanding to
						// be 10 billion pixels wide.
						mw.log( 'mw.page.gallery: Cannot fit row, aspect is ' + preferredHeight / curRowHeight );
						if ( i === rows.length - 1 ) {
							// If its the last row, and we can't fit it,
							// don't make the entire row huge.
							avgZoom = ( totalZoom / ( rows.length - 1 ) ) * curRowHeight;
							if ( isFinite( avgZoom ) && avgZoom >= 1 && avgZoom <= 1.5 ) {
								preferredHeight = avgZoom;
							} else {
								// Probably a single row gallery
								preferredHeight = curRowHeight;
							}
						} else {
							preferredHeight = 1.5 * curRowHeight;
						}
					}
					if ( !isFinite( preferredHeight ) ) {
						// This *definitely* should not happen.
						mw.log( 'mw.page.gallery: Trying to resize row ' + i + ' to ' + preferredHeight + '?!' );
						// Skip this row.
						continue;
					}
					if ( preferredHeight < 5 ) {
						// Well something clearly went wrong...
						mw.log( {
							maxWidth: maxWidth,
							combinedPadding: combinedPadding,
							combinedAspect: combinedAspect,
							wantedWidth: wantedWidth
						} );
						mw.log( 'mw.page.gallery: [BUG!] Fitting row ' + i + ' to too small a size: ' + preferredHeight );
						// Skip this row.
						continue;
					}

					if ( preferredHeight / curRowHeight > 1 ) {
						totalZoom += preferredHeight / curRowHeight;
					} else {
						// If we shrink, still consider that a zoom of 1
						totalZoom += 1;
					}

					for ( j = 0; j < curRow.length; j++ ) {
						newWidth = preferredHeight * curRow[j].aspect;
						padding = curRow[j].width - curRow[j].imgWidth;
						$outerDiv = curRow[j].$elm;
						$innerDiv = $outerDiv.children( 'div' ).first();
						$imageDiv = $innerDiv.children( 'div.thumb' );
						$imageElm = $imageDiv.find( 'img' ).first();
						imageElm = $imageElm.length ? $imageElm[0] : null;
						$caption = $outerDiv.find( 'div.gallerytextwrapper' );

						// Since we are going to re-adjust the height, the vertical
						// centering margins need to be reset.
						$imageDiv.children( 'div' ).css( 'margin', '0px auto' );

						if ( newWidth < 60 || !isFinite( newWidth ) ) {
							// Making something skinnier than this will mess up captions,
							mw.log( 'mw.page.gallery: Tried to make image ' + newWidth + 'px wide but too narrow.' );
							if ( newWidth < 1 || !isFinite( newWidth ) ) {
								$innerDiv.height( preferredHeight );
								// Don't even try and touch the image size if it could mean
								// making it disappear.
								continue;
							}
						} else {
							$outerDiv.width( newWidth + padding );
							$innerDiv.width( newWidth + padding );
							$imageDiv.width( newWidth );
							$caption.width( curRow[j].captionWidth + ( newWidth - curRow[j].imgWidth ) );
						}

						hookInfo = {
							fullWidth: newWidth + padding,
							imgWidth: newWidth,
							imgHeight: preferredHeight,
							$innerDiv: $innerDiv,
							$imageDiv: $imageDiv,
							$outerDiv: $outerDiv,
							// Whether the hook took action
							resolved: false
						};

						/**
						 * Gallery resize.
						 *
						 * If your handler resizes an image, it should also set the resolved
						 * property to true. Additionally, because this module only exposes this
						 * logic temporarily, you should load your module in position top to
						 * ensure it is registered before this runs (FIXME: Don't use mw.hook)
						 *
						 * See TimedMediaHandler for an example.
						 *
						 * @event mediawiki_page_gallery_resize
						 * @member mw.hook
						 * @param {Object} hookInfo
						 */
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
		} );
	} );
} )( jQuery, mediaWiki );
